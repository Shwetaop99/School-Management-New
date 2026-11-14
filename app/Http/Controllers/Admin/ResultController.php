<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamMark;
use App\Models\ExamSubject;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\ResultVersion;
use App\Models\ResultVersionDetail;
use App\Models\ResultWhatsappNotification;
use App\Models\Student;
use App\Support\Access;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
            'student_id' => ['nullable', 'string', 'max:50'],
        ]);

        $exams = Exam::query()->orderByDesc('start_date')->orderByDesc('id')->get();
        $results = Result::with(['student', 'exam', 'details'])
            ->when($request->filled('exam_id'), fn ($q) => $q->where('exam_id', $request->exam_id))
            ->when($request->filled('student_id'), function ($q) use ($request) {
                $term = trim($request->student_id);
                $q->whereHas('student', fn ($sq) => $sq->where('student_id', 'like', "%{$term}%"));
            })
            ->latest('id')->paginate(15)->withQueryString();

        return view('admin.results.index', compact('exams', 'results'));
    }

    public function generate()
    {
        $exams = Exam::query()->orderByDesc('id')->get();
        $selectedExam = null;
        return view('admin.results.generate', compact('exams', 'selectedExam'));
    }

    public function loadClasses(Request $request)
    {
        $data = $request->validate(['exam_id' => ['required', 'integer', 'exists:exams,id']]);
        $exam = Exam::findOrFail($data['exam_id']);
        $classes = ExamClass::with('schoolClass')->where('exam_id', $exam->id)->get()
            ->filter(fn ($ec) => $ec->schoolClass)
            ->map(fn ($ec) => [
                'id' => $ec->class_id,
                'exam_class_id' => $ec->id,
                'name' => $ec->schoolClass->class_name,
                'class_name' => $ec->schoolClass->class_name,
                'section' => $ec->schoolClass->section,
                'academic_year' => $ec->schoolClass->academic_year,
            ])->values();

        return response()->json(['success' => true, 'exam' => [
            'id' => $exam->id, 'exam_name' => $exam->exam_name, 'academic_year' => $exam->academic_year,
        ], 'classes' => $classes]);
    }

    public function getExamClasses(Request $request)
    {
        $data = $request->validate(['exam_id' => ['required', 'integer', 'exists:exams,id']]);
        $exam = Exam::findOrFail($data['exam_id']);
        $classes = ExamClass::with('schoolClass')->where('exam_id', $exam->id)->get()
            ->filter(fn ($ec) => $ec->schoolClass)
            ->map(function ($ec) use ($exam) {
                $sc = $ec->schoolClass;
                $students = $this->classStudents($exam, $sc, (string) $sc->section);
                $studentIds = $students->pluck('id');
                $rq = Result::query()->where('exam_id', $exam->id)->whereIn('student_id', $studentIds);
                return [
                    'exam_class_id' => $ec->id, 'exam_id' => $exam->id, 'class_id' => $sc->id,
                    'class_name' => $sc->class_name, 'section' => $sc->section,
                    'academic_year' => $sc->academic_year ?? $exam->academic_year,
                    'student_count' => $students->count(),
                    'generated_count' => (clone $rq)->where('publication_status', 'generated')->count(),
                    'verified_count' => (clone $rq)->where('publication_status', 'verified')->count(),
                    'approved_count' => (clone $rq)->where('publication_status', 'approved')->count(),
                    'published_count' => (clone $rq)->where('publication_status', 'published')->count(),
                ];
            })->values();
        return response()->json(['success' => true, 'exam' => [
            'id' => $exam->id, 'exam_name' => $exam->exam_name, 'academic_year' => $exam->academic_year,
        ], 'classes' => $classes]);
    }

    public function loadStudents(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section' => ['nullable', 'string', 'max:50'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']);
        $sc = SchoolClass::findOrFail($data['class_id']);
        $section = trim((string) ($data['section'] ?? $sc->section ?? ''));
        $students = $this->classStudents($exam, $sc, $section);
        return response()->json(['success' => true, 'students' => $students]);
    }

    public function marks(Request $request)
    {
        $exams = Exam::query()->orderByDesc('id')->get();
        $classes = collect(); $students = collect(); $subjects = collect(); $marks = collect();
        $selectedExam = null; $selectedClass = null; $selectedSubject = null; $section = null;

        if ($request->filled('exam_id')) {
            $selectedExam = Exam::find($request->integer('exam_id'));
        }
        if ($selectedExam && $request->filled('class_id')) {
            $selectedClass = SchoolClass::find($request->integer('class_id'));
            if ($selectedClass) {
                $section = trim((string) $request->input('section', $selectedClass->section));
                $students = $this->classStudents($selectedExam, $selectedClass, $section);
                $subjects = ExamSubject::with('subject')->where('exam_id', $selectedExam->id)
                    ->where('class_id', $selectedClass->id)->get();
                if ($request->filled('subject_id')) {
                    $selectedSubject = $subjects->firstWhere('subject_id', $request->integer('subject_id'));
                    if ($selectedSubject) {
                        $examClass = ExamClass::where('exam_id', $selectedExam->id)->where('class_id', $selectedClass->id)->first();
                        if ($examClass) {
                            $marks = ExamMark::where('exam_id', $selectedExam->id)
                                ->where('exam_class_id', $examClass->id)
                                ->where('subject_id', $selectedSubject->subject_id)
                                ->whereIn('student_id', $students->pluck('id'))->get()->keyBy('student_id');
                        }
                    }
                }
            }
        }
        if ($selectedExam) {
            $classes = ExamClass::with('schoolClass')->where('exam_id', $selectedExam->id)->get()->pluck('schoolClass')->filter()->values();
        }
        return view('admin.results.marks', compact('exams', 'classes', 'students', 'subjects', 'marks', 'selectedExam', 'selectedClass', 'selectedSubject', 'section'));
    }

    public function saveMarksDistribution(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'internal_max_marks' => ['required', 'numeric', 'min:0'],
            'theory_max_marks' => ['required', 'numeric', 'min:0'],
            'passing_marks' => ['required', 'numeric', 'min:0'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']);
        $sc = SchoolClass::findOrFail($data['class_id']);
        $examClass = ExamClass::where('exam_id', $exam->id)->where('class_id', $sc->id)->first();
        $subject = ExamSubject::where('exam_id', $exam->id)->where('class_id', $sc->id)->where('subject_id', $data['subject_id'])->first();
        if (!$examClass || !$subject) return back()->withInput()->with('error', 'Class or subject is not assigned to this examination.');
        $internal = (float) $data['internal_max_marks']; $theory = (float) $data['theory_max_marks'];
        $total = $internal + $theory; $passing = (float) $data['passing_marks'];
        if ($total <= 0 || $passing > $total) return back()->withInput()->with('error', 'Check the maximum and passing marks.');
        $subject->forceFill(['internal_max_marks' => $internal, 'theory_max_marks' => $theory, 'maximum_marks' => $total, 'passing_marks' => $passing])->save();
        return redirect()->route('admin.results.marks', ['exam_id' => $exam->id, 'class_id' => $sc->id, 'section' => $request->input('section', $sc->section), 'subject_id' => $data['subject_id']])->with('success', 'Marks distribution saved successfully.');
    }

    public function saveMarks(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'], 'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section' => ['nullable', 'string', 'max:50'], 'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'marks' => ['required', 'array'], 'marks.*.internal_marks' => ['nullable', 'numeric', 'min:0'],
            'marks.*.theory_marks' => ['nullable', 'numeric', 'min:0'], 'marks.*.practical_marks' => ['nullable', 'numeric', 'min:0'],
            'marks.*.status' => ['required', 'in:present,absent,na'], 'marks.*.remarks' => ['nullable', 'string', 'max:255'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']); $sc = SchoolClass::findOrFail($data['class_id']);
        $examClass = ExamClass::where('exam_id', $exam->id)->where('class_id', $sc->id)->first();
        $examSubject = ExamSubject::where('exam_id', $exam->id)->where('class_id', $sc->id)->where('subject_id', $data['subject_id'])->first();
        if (!$examClass || !$examSubject) return back()->withInput()->with('error', 'Class or subject is not assigned to this examination.');
        $internalMax = (float) $examSubject->internal_max_marks; $theoryMax = (float) $examSubject->theory_max_marks;
        $practicalMax = (float) ($examSubject->practical_max_marks ?? 0); $maximum = (float) $examSubject->maximum_marks;
        if ($maximum <= 0) return back()->withInput()->with('error', 'Configure maximum marks before entering marks.');
        $section = trim((string) ($data['section'] ?? $sc->section ?? ''));
        $ids = array_map('intval', array_keys($data['marks']));
        $students = $this->classStudents($exam, $sc, $section)->whereIn('id', $ids);
        if ($students->count() !== count($ids)) return back()->withInput()->with('error', 'One or more students do not belong to this class or section.');
        DB::transaction(function () use ($data, $students, $exam, $examClass, $examSubject, $internalMax, $theoryMax, $practicalMax, $maximum) {
            foreach ($students as $student) {
                $row = $data['marks'][$student->id]; $status = $row['status'];
                $internal = $status === 'present' ? (float) ($row['internal_marks'] ?? 0) : 0;
                $theory = $status === 'present' ? (float) ($row['theory_marks'] ?? 0) : 0;
                $practical = $status === 'present' ? (float) ($row['practical_marks'] ?? 0) : 0;
                if ($internal > $internalMax || $theory > $theoryMax || $practical > $practicalMax || ($internal + $theory + $practical) > $maximum) {
                    throw ValidationException::withMessages(['marks' => "Marks exceed the configured maximum for student {$student->student_id}."]);
                }
                ExamMark::updateOrCreate([
                    'exam_id' => $exam->id, 'exam_class_id' => $examClass->id, 'student_id' => $student->id, 'subject_id' => $examSubject->subject_id,
                ], [
                    'internal_marks' => $internal, 'theory_marks' => $theory, 'practical_marks' => $practical,
                    'max_marks' => $maximum, 'total_marks' => $internal + $theory + $practical,
                    'status' => $status, 'remarks' => $row['remarks'] ?? null,
                ]);
            }
        });
        return redirect()->route('admin.results.marks', ['exam_id' => $exam->id, 'class_id' => $sc->id, 'section' => $section, 'subject_id' => $examSubject->subject_id])->with('success', 'Marks saved successfully.');
    }

    public function loadSections(Request $request)
    {
        $data = $request->validate(['class_id' => ['required', 'integer', 'exists:school_classes,id']]);
        $sc = SchoolClass::findOrFail($data['class_id']);
        $sections = Student::where('class', $this->studentClassValue($sc))->where('academic_year', $sc->academic_year)->whereNotNull('section')->distinct()->orderBy('section')->pluck('section');
        return response()->json(['success' => true, 'sections' => $sections]);
    }

    public function loadSubjects(Request $request)
    {
        $data = $request->validate(['exam_id' => ['required', 'integer', 'exists:exams,id'], 'class_id' => ['required', 'integer', 'exists:school_classes,id']]);
        $subjects = ExamSubject::with('subject')->where('exam_id', $data['exam_id'])->where('class_id', $data['class_id'])->get()->map(fn ($es) => [
            'id' => $es->subject_id, 'subject_id' => $es->subject_id, 'name' => $es->subject->subject_name ?? 'Subject',
            'subject_name' => $es->subject->subject_name ?? 'Subject', 'internal_max_marks' => $es->internal_max_marks,
            'theory_max_marks' => $es->theory_max_marks, 'maximum_marks' => $es->maximum_marks, 'passing_marks' => $es->passing_marks,
        ]);
        return response()->json(['success' => true, 'subjects' => $subjects]);
    }

    public function generateResult(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'], 'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section' => ['nullable', 'string', 'max:50'], 'exam_class_id' => ['nullable', 'integer', 'exists:exam_classes,id'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']); $sc = SchoolClass::findOrFail($data['class_id']);
        $ecQuery = ExamClass::where('exam_id', $exam->id)->where('class_id', $sc->id);
        if (!empty($data['exam_class_id'])) $ecQuery->where('id', $data['exam_class_id']);
        $examClass = $ecQuery->first();
        if (!$examClass) return back()->with('error', 'This class is not assigned to the selected examination.');
        $section = trim((string) ($data['section'] ?? $sc->section ?? ''));
        $students = $this->classStudents($exam, $sc, $section);
        if ($students->isEmpty()) return back()->with('error', 'No active students found for this class and section.');
        $existingPublished = Result::where('exam_id', $exam->id)->whereIn('student_id', $students->pluck('id'))->where('publication_status', 'published')->exists();
        if ($existingPublished) return back()->with('error', 'Published results cannot be regenerated.');
        $subjectConfig = ExamSubject::where('exam_id', $exam->id)->where('class_id', $sc->id)->get()->keyBy('subject_id');
        $marksByStudent = ExamMark::with('subject')->where('exam_id', $exam->id)->where('exam_class_id', $examClass->id)->whereIn('student_id', $students->pluck('id'))->get()->groupBy('student_id');
        $skipped = [];
        DB::transaction(function () use ($students, $marksByStudent, $exam, $sc, $section, $subjectConfig, &$skipped) {
            foreach ($students as $student) {
                $marks = $marksByStudent->get($student->id, collect());
                $activeCount = $subjectConfig->filter(fn ($s) => !isset($s->status) || (bool) $s->status)->count();
                if ($marks->isEmpty() || $marks->count() < $activeCount) { $skipped[] = $student->student_id; continue; }
                $maxTotal = 0; $obtainedTotal = 0; $failed = false; $absent = false;
                foreach ($marks as $mark) {
                    $max = (float) $mark->max_marks; $obtained = (float) $mark->total_marks;
                    $maxTotal += $max; $obtainedTotal += $obtained;
                    if ($mark->status === 'absent') $absent = true;
                    $config = $subjectConfig->get($mark->subject_id);
                    if ($mark->status === 'present' && $config && $obtained < (float) $config->passing_marks) $failed = true;
                }
                $percentage = $maxTotal > 0 ? round($obtainedTotal / $maxTotal * 100, 2) : 0;
                $grade = $this->calculateGrade($percentage);
                $status = $failed ? 'fail' : ($absent ? 'absent' : 'pass');
                $result = Result::updateOrCreate(['student_id' => $student->id, 'exam_id' => $exam->id], [
                    'academic_year' => $exam->academic_year, 'class_name' => $sc->class_name, 'section' => $section,
                    'total_marks' => $maxTotal, 'obtained_marks' => $obtainedTotal, 'percentage' => $percentage, 'grade' => $grade,
                    'result_status' => $status, 'publication_status' => 'generated', 'published_at' => null, 'published_by' => null,
                    'generated_at' => now(), 'remarks' => null,
                ]);
                $versionNo = (int) ResultVersion::where('result_id', $result->id)->max('version_no') + 1;
                $version = ResultVersion::create([
                    'result_id' => $result->id, 'version_no' => $versionNo, 'student_id_snapshot' => $student->student_id,
                    'student_name_snapshot' => $this->fullName($student), 'exam_name_snapshot' => $exam->exam_name,
                    'academic_year' => $exam->academic_year, 'class_name' => $sc->class_name, 'section' => $section,
                    'total_marks' => $maxTotal, 'obtained_marks' => $obtainedTotal, 'percentage' => $percentage,
                    'grade' => $grade, 'result_status' => $status, 'remarks' => null, 'generated_at' => now(),
                ]);
                $result->details()->delete();
                foreach ($marks as $mark) {
                    $max = (float) $mark->max_marks; $obtained = (float) $mark->total_marks;
                    $subjectPercentage = $max > 0 ? round($obtained / $max * 100, 2) : 0;
                    $detail = [
                        'subject_id' => $mark->subject_id, 'subject_name' => $mark->subject->subject_name ?? 'Unknown Subject',
                        'max_marks' => $max, 'internal_marks' => $mark->internal_marks, 'theory_marks' => $mark->theory_marks,
                        'practical_marks' => $mark->practical_marks, 'total_marks' => $max, 'obtained_marks' => $obtained,
                        'grade' => $this->calculateGrade($subjectPercentage), 'grade_point' => $this->calculateGradePoint($subjectPercentage),
                    ];
                    ResultVersionDetail::create($detail + ['result_version_id' => $version->id, 'status' => $mark->status, 'remarks' => $mark->remarks]);
                    ResultDetail::create($detail + ['result_id' => $result->id]);
                }
            }
        });
        $count = $students->count() - count($skipped);
        $message = "Results generated for {$count} student(s).";
        if ($skipped) $message .= ' Skipped students with incomplete marks: ' . implode(', ', array_slice($skipped, 0, 10));
        return redirect()->route('admin.results.index')->with($skipped ? 'warning' : 'success', $message);
    }

    public function history(Result $result)
    {
        $result->load(['student', 'exam', 'details']);
        $versions = ResultVersion::where('result_id', $result->id)->orderByDesc('version_no')->get();
        return view('admin.results.history', compact('result', 'versions'));
    }

    public function historyVersion(Result $result, $version)
    {
        $version = ResultVersion::where('result_id', $result->id)->where('id', $version)->firstOrFail();
        $version->load('details');
        return view('admin.results.history-version', compact('result', 'version'));
    }

    public function show(Result $result)
    {
        $result->load(['student', 'exam', 'details']);
        return view('admin.results.show', compact('result'));
    }

    public function print(Result $result)
    {
        $result->load(['student', 'exam', 'details']);
        return view('admin.results.print', compact('result'));
    }

    public function pdf(Result $result)
    {
        $result->load(['student', 'exam', 'details']);
        $pdf = Pdf::loadView('admin.results.pdf', compact('result'));
        return $pdf->download('result-' . $result->id . '.pdf');
    }

    public function classResults(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'], 'exam_class_id' => ['nullable', 'integer', 'exists:exam_classes,id'],
            'class_id' => ['nullable', 'integer', 'exists:school_classes,id'], 'section' => ['nullable', 'string', 'max:50'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']);
        $ecQuery = ExamClass::with('schoolClass')->where('exam_id', $exam->id);
        if (!empty($data['exam_class_id'])) $ecQuery->where('id', $data['exam_class_id']);
        elseif (!empty($data['class_id'])) $ecQuery->where('class_id', $data['class_id']);
        $examClass = $ecQuery->firstOrFail(); $sc = $examClass->schoolClass;
        abort_unless($sc, 404);
        $section = trim((string) ($data['section'] ?? $sc->section ?? ''));
        $students = $this->classStudents($exam, $sc, $section);
        $results = Result::where('exam_id', $exam->id)->whereIn('student_id', $students->pluck('id'))->get()->keyBy('student_id');
        return view('admin.results.class-results', compact('exam', 'examClass', 'schoolClass', 'section', 'students', 'results'));
    }

    public function verify(Result $result)
    {
        $this->requirePermission('results.edit');
        if ($result->publication_status !== 'generated') return back()->with('error', 'Only generated results can be verified.');
        $result->update(['publication_status' => 'verified']);
        return back()->with('success', 'Result verified successfully.');
    }

    public function approve(Result $result)
    {
        $this->requireRole(['super_admin', 'admin']);
        if ($result->publication_status !== 'verified') return back()->with('error', 'Only verified results can be approved.');
        $result->update(['publication_status' => 'approved']);
        return back()->with('success', 'Result approved successfully.');
    }

    public function publish(Result $result)
    {
        $this->requireRole(['super_admin', 'admin']);
        if ($result->publication_status !== 'approved') return back()->with('error', 'Only approved results can be published.');
        $result->load(['student', 'exam']);
        $student = $result->student;
        if (!$student) return back()->with('error', 'Student record not found.');
        $phone = $student->mother_mobile ?? null;
        DB::transaction(function () use ($result, $student, $phone) {
            $result->update(['publication_status' => 'published', 'published_at' => now(), 'published_by' => Auth::id()]);
            ResultWhatsappNotification::create([
                'result_id' => $result->id, 'student_id' => $student->id, 'phone' => $phone ?? '',
                'message' => $this->buildResultWhatsAppMessage($result), 'status' => $phone ? 'pending' : 'failed',
                'error_message' => $phone ? null : 'Mother mobile number is not registered.',
            ]);
        });
        return back()->with('success', 'Result published successfully. WhatsApp sending remains paused.');
    }

    public function verifyClassResults(Request $request)
    {
        return $this->bulkWorkflowAction($request, 'generated', 'verified', false);
    }

    public function approveClassResults(Request $request)
    {
        $this->requireRole(['super_admin', 'admin']);
        return $this->bulkWorkflowAction($request, 'verified', 'approved', false);
    }

    public function publishClassResults(Request $request)
    {
        $this->requireRole(['super_admin', 'admin']);
        return $this->bulkWorkflowAction($request, 'approved', 'published', true);
    }

    private function bulkWorkflowAction(Request $request, string $from, string $to, bool $publish): mixed
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'], 'exam_class_id' => ['required', 'integer', 'exists:exam_classes,id'],
            'section' => ['nullable', 'string', 'max:50'],
        ]);
        $exam = Exam::findOrFail($data['exam_id']); $ec = ExamClass::with('schoolClass')->where('exam_id', $exam->id)->findOrFail($data['exam_class_id']);
        $sc = $ec->schoolClass; abort_unless($sc, 404);
        $section = trim((string) ($data['section'] ?? $sc->section ?? ''));
        $students = $this->classStudents($exam, $sc, $section);
        $results = Result::where('exam_id', $exam->id)->whereIn('student_id', $students->pluck('id'))->get();
        if ($students->isEmpty() || $results->count() !== $students->count()) return back()->with('error', 'Every active student must have a generated result before this action.');
        if ($results->contains(fn ($r) => $r->publication_status !== $from)) return back()->with('error', "All results must be in {$from} status before continuing.");
        DB::transaction(function () use ($results, $to, $publish) {
            foreach ($results as $result) {
                $fields = ['publication_status' => $to];
                if ($publish) {
                    $result->load('student'); $fields['published_at'] = now(); $fields['published_by'] = Auth::id();
                }
                $result->update($fields);
                if ($publish && $result->student) {
                    $phone = $result->student->mother_mobile ?? '';
                    ResultWhatsappNotification::create([
                        'result_id' => $result->id, 'student_id' => $result->student_id, 'phone' => $phone,
                        'message' => $this->buildResultWhatsAppMessage($result), 'status' => $phone ? 'pending' : 'failed',
                        'error_message' => $phone ? null : 'Mother mobile number is not registered.',
                    ]);
                }
            }
        });
        return back()->with('success', count($results) . " result(s) {$to} successfully.");
    }

    public function whatsapp(Result $result)
    {
        if ($result->publication_status !== 'published') return back()->with('error', 'Only published results can be shared.');
        $result->load('student'); $student = $result->student;
        $phone = preg_replace('/\D+/', '', (string) ($student->mother_mobile ?? $student->father_mobile ?? $student->mobile ?? ''));
        if ($phone === '') return back()->with('error', 'No parent or student mobile number is registered.');
        if (strlen($phone) === 10) $phone = '91' . $phone;
        $url = 'https://wa.me/' . $phone . '?text=' . rawurlencode($this->buildResultWhatsAppMessage($result));
        return redirect()->away($url);
    }

    public function bulkWhatsapp(Request $request)
    {
        $exams = Exam::orderByDesc('id')->get();
        $selectedExam = $request->filled('exam_id') ? Exam::find($request->integer('exam_id')) : null;
        $classes = $selectedExam ? ExamClass::with('schoolClass')->where('exam_id', $selectedExam->id)->get()->pluck('schoolClass')->filter()->values() : collect();
        return view('admin.results.bulk-whatsapp', compact('exams', 'selectedExam', 'classes'));
    }

    protected function buildResultWhatsAppMessage(Result $result): string
    {
        $url = URL::route('admin.results.show', ['result' => $result->id]);
        return "Dear Parent, the result is now available. You can view it here: {$url}";
    }

    private function calculateGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+', $percentage >= 80 => 'A', $percentage >= 70 => 'B+',
            $percentage >= 60 => 'B', $percentage >= 50 => 'C', $percentage >= 40 => 'D', default => 'F',
        };
    }

    private function calculateGradePoint(float $percentage): float
    {
        return match (true) {
            $percentage >= 90 => 10, $percentage >= 80 => 9, $percentage >= 70 => 8,
            $percentage >= 60 => 7, $percentage >= 50 => 6, $percentage >= 40 => 5, default => 0,
        };
    }

    private function requirePermission(string $permission): void
    {
        abort_unless(Access::allows($permission), 403, 'You are not authorized to perform this action.');
    }

    private function requireRole(array $roles): void
    {
        $role = auth()->user()?->role?->name;
        abort_unless(in_array($role, $roles, true), 403, 'You are not authorized to perform this action.');
    }

    private function studentClassValue(SchoolClass $schoolClass): string
    {
        $value = trim((string) $schoolClass->class_name);
        return preg_replace('/^Class\s+/i', '', $value);
    }

    private function fullName($student): string
    {
        return trim(implode(' ', array_filter([
            $student->first_name ?? null, $student->middle_name ?? null, $student->last_name ?? null,
        ])));
    }

    private function classStudents(Exam $exam, SchoolClass $schoolClass, string $section)
    {
        return Student::query()
            ->where('class', $this->studentClassValue($schoolClass))
            ->when($section !== '', fn ($q) => $q->where('section', $section))
            ->when($exam->academic_year, fn ($q) => $q->where('academic_year', $exam->academic_year))
            ->where('status', 'active')
            ->orderByRaw('CAST(NULLIF(roll_number, "") AS UNSIGNED)')
            ->orderBy('first_name')->orderBy('middle_name')->orderBy('last_name')->get();
    }
}
