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
use App\Services\WhatsAppService;
use App\Support\Access;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ResultController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | RESULT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        // UPDATE: check the filters first
        $request->validate([
            'exam_id'    => ['nullable', 'integer', 'exists:exams,id'],
            'student_id' => ['nullable', 'string', 'max:50'],
        ]);

        $exams = Exam::query()
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $results = Result::with(['student', 'exam', 'details'])
            ->when($request->filled('exam_id'), function ($query) use ($request) {
                $query->where('exam_id', $request->exam_id);
            })
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->whereHas('student', function ($studentQuery) use ($request) {
                    $studentQuery->where(
                        'student_id',
                        'like',
                        '%' . trim($request->student_id) . '%'
                    );
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.results.index', compact('exams', 'results'));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE PAGE
    |--------------------------------------------------------------------------
    */

    public function generate()
    {
        $exams = Exam::query()->orderByDesc('id')->get();

        $selectedExam = null;

        return view('admin.results.generate', compact('exams', 'selectedExam'));
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX: LOAD CLASSES OF AN EXAM
    |--------------------------------------------------------------------------
    */

    public function loadClasses(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $classes = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->get()
            ->filter(fn ($examClass) => $examClass->schoolClass !== null)
            ->map(fn ($examClass) => [
                'id'            => $examClass->class_id,
                'name'          => $examClass->schoolClass->class_name,
                'section'       => $examClass->schoolClass->section,
                'academic_year' => $examClass->schoolClass->academic_year,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id'            => $exam->id,
                'exam_name'     => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'classes' => $classes,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX: CLASS CARDS WITH WORKFLOW COUNTS
    |--------------------------------------------------------------------------
    */

    public function getExamClasses(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
        ]);

        $examId = $validated['exam_id'];

        $exam = Exam::findOrFail($examId);

        $classes = ExamClass::with('schoolClass')
            ->where('exam_id', $examId)
            ->get()
            ->filter(fn ($examClass) => $examClass->schoolClass !== null)
            ->map(function ($examClass) use ($examId, $exam) {

                $schoolClass = $examClass->schoolClass;

                // "Class 8" -> 8, "8th" -> 8, "10" -> 10
                $className = trim((string) ($schoolClass->class_name ?? ''));

                preg_match('/\d+/', $className, $matches);

                $studentClass = $matches[0] ?? $className;

                $studentQuery = Student::query()->where('class', $studentClass);

                if (!empty($schoolClass->section)) {
                    $studentQuery->where('section', $schoolClass->section);
                }

                if (!empty($schoolClass->academic_year)) {
                    $studentQuery->where('academic_year', $schoolClass->academic_year);
                }

                $studentIds = $studentQuery->pluck('id');

                $resultQuery = Result::query()
                    ->where('exam_id', $examId)
                    ->whereIn('student_id', $studentIds);

                return [
                    'exam_class_id'   => $examClass->id,
                    'exam_id'         => $examId,
                    'class_id'        => $schoolClass->id,
                    'class_name'      => $schoolClass->class_name,
                    'section'         => $schoolClass->section,
                    'academic_year'   => $schoolClass->academic_year
                        ?? $exam->academic_year
                        ?? null,
                    'student_count'   => $studentIds->count(),
                    'generated_count' => (clone $resultQuery)->where('publication_status', 'generated')->count(),
                    'verified_count'  => (clone $resultQuery)->where('publication_status', 'verified')->count(),
                    'approved_count'  => (clone $resultQuery)->where('publication_status', 'approved')->count(),
                    'published_count' => (clone $resultQuery)->where('publication_status', 'published')->count(),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id'            => $exam->id,
                'exam_name'     => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'classes' => $classes,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX: LOAD STUDENTS
    |--------------------------------------------------------------------------
    */

    public function loadStudents(Request $request)
    {
        $validated = $request->validate([
            'exam_id'  => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
            'section'  => ['nullable', 'string', 'max:50'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return response()->json([
                'success'  => false,
                'message'  => 'This class is not assigned to the selected exam.',
                'students' => [],
            ], 422);
        }

        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        $students = $this->classStudents($exam, $schoolClass, $section)
            ->map(function ($student) {
                return [
                    'id'            => $student->id,
                    'student_id'    => $student->student_id,
                    'roll_number'   => $student->roll_number,
                    'first_name'    => $student->first_name,
                    'middle_name'   => $student->middle_name,
                    'last_name'     => $student->last_name,
                    'class'         => $student->class,
                    'section'       => $student->section,
                    'academic_year' => $student->academic_year,
                    'status'        => $student->status,
                    'full_name'     => $this->fullName($student),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id'            => $exam->id,
                'name'          => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'class' => [
                'id'      => $schoolClass->id,
                'name'    => $schoolClass->class_name,
                'section' => $section,
            ],
            'students' => $students,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ENTER MARKS PAGE
    |--------------------------------------------------------------------------
    */

    public function marks(Request $request)
    {
        $exams = Exam::query()->orderByDesc('id')->get();

        // No exam selected
        if (!$request->filled('exam_id')) {
            return view('admin.results.marks', [
                'exams'         => $exams,
                'exam'          => null,
                'examClasses'   => collect(),
                'examClass'     => null,
                'examSubject'   => null,
                'students'      => collect(),
                'section'       => null,
                'sections'      => collect(),
                'selectionMode' => true,
            ]);
        }

        $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        // Classes assigned to the exam
        $examClasses = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->get()
            ->filter(fn ($examClass) => $examClass->schoolClass !== null)
            ->values();

        // Sections of the selected class
        $sections = collect();

        if ($request->filled('class_id')) {

            $selectedExamClass = $examClasses->firstWhere(
                'class_id',
                (int) $request->class_id
            );

            if ($selectedExamClass && $selectedExamClass->schoolClass) {
                $sections = SchoolClass::query()
                    ->where('class_name', $selectedExamClass->schoolClass->class_name)
                    ->where('academic_year', $exam->academic_year)
                    ->where('status', true)
                    ->whereNotNull('section')
                    ->where('section', '!=', '')
                    ->orderBy('section')
                    ->pluck('section')
                    ->unique()
                    ->values();
            }
        }

        // Selection not complete yet
        if (
            !$request->filled('class_id') ||
            !$request->filled('section') ||
            !$request->filled('subject_id')
        ) {
            return view('admin.results.marks', [
                'exams'         => $exams,
                'exam'          => $exam,
                'examClasses'   => $examClasses,
                'examClass'     => null,
                'examSubject'   => null,
                'students'      => collect(),
                'section'       => $request->section,
                'sections'      => $sections,
                'selectionMode' => true,
            ]);
        }

        $validated = $request->validate([
            'exam_id'    => ['required', 'integer', 'exists:exams,id'],
            'class_id'   => ['required', 'integer', 'exists:school_classes,id'],
            'section'    => ['required', 'string', 'max:50'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        ]);

        $examClass = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->where('class_id', $validated['class_id'])
            ->firstOrFail();

        // Section must belong to this class
        $sectionExists = SchoolClass::query()
            ->where('id', $validated['class_id'])
            ->where('class_name', $examClass->schoolClass->class_name)
            ->where('section', $validated['section'])
            ->where('academic_year', $exam->academic_year)
            ->where('status', true)
            ->exists();

        if (!$sectionExists) {
            abort(404, 'Selected section does not belong to the selected class.');
        }

        $examSubject = ExamSubject::with('subject')
            ->where('exam_id', $exam->id)
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('status', true)
            ->firstOrFail();

        // Students (blank roll numbers last)
        $students = Student::query()
            ->where('class', $this->studentClassValue($examClass->schoolClass))
            ->where('section', $validated['section'])
            ->where('academic_year', $exam->academic_year)
            ->where('status', 'active')
            ->orderByRaw(
                'CASE WHEN roll_number IS NULL OR roll_number = "" THEN 1 ELSE 0 END'
            )
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        $existingMarks = ExamMark::query()
            ->where('exam_id', $exam->id)
            ->where('exam_class_id', $examClass->id)
            ->where('subject_id', $examSubject->subject_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        $students->each(function ($student) use ($existingMarks) {
            $student->full_name = $this->fullName($student);
            $student->examMark  = $existingMarks->get($student->id);
        });

        return view('admin.results.marks', [
            'exams'         => $exams,
            'exam'          => $exam,
            'examClasses'   => $examClasses,
            'examClass'     => $examClass,
            'examSubject'   => $examSubject,
            'students'      => $students,
            'section'       => $validated['section'],
            'sections'      => $sections,
            'selectionMode' => false,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE MARKS
    |--------------------------------------------------------------------------
    */

    public function saveMarks(Request $request)
    {
        $request->validate([
            'exam_id'    => ['required', 'integer', 'exists:exams,id'],
            'class_id'   => ['required', 'integer', 'exists:school_classes,id'],
            'section'    => ['nullable', 'string', 'max:50'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],

            'marks' => ['required', 'array'],

            'marks.*.internal_marks'  => ['nullable', 'numeric', 'min:0'],
            'marks.*.theory_marks'    => ['nullable', 'numeric', 'min:0'],
            'marks.*.practical_marks' => ['nullable', 'numeric', 'min:0'],

            // UPDATE: remarks has a maximum length now
            'marks.*.remarks' => ['nullable', 'string', 'max:255'],

            'marks.*.status' => ['required', 'in:present,absent,na'],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $schoolClass = SchoolClass::findOrFail($request->class_id);

        // Class must be assigned to this exam
        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return back()
                ->withInput()
                ->with('error', 'This class is not assigned to the selected examination.');
        }

        // Subject must be assigned to this exam and class
        $examSubject = ExamSubject::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->where('subject_id', $request->subject_id)
            ->where('status', true)
            ->first();

        if (!$examSubject) {
            return back()
                ->withInput()
                ->with('error', 'This subject is not assigned to the selected examination and class.');
        }

        $maximumMarks = (float) $examSubject->maximum_marks;

        $section = $request->filled('section')
            ? trim($request->section)
            : trim((string) $schoolClass->section);

        // Every submitted student must belong to this class
        $submittedStudentIds = array_keys($request->input('marks', []));

        $students = $this->classStudents($exam, $schoolClass, $section)
            ->whereIn('id', array_map('intval', $submittedStudentIds))
            ->values();

        if ($students->count() !== count($submittedStudentIds)) {
            return back()
                ->withInput()
                ->with('error', 'One or more selected students do not belong to this class or section.');
        }

        // UPDATE: marks are locked after results are published
        $alreadyPublished = Result::query()
            ->where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->where('publication_status', 'published')
            ->exists();

        if ($alreadyPublished) {
            return back()
                ->withInput()
                ->with('error', 'Results for this class are already published, so marks are locked.');
        }

        DB::transaction(function () use (
            $request,
            $students,
            $exam,
            $examClass,
            $examSubject,
            $maximumMarks
        ) {
            foreach ($students as $student) {

                $studentMarks = $request->input('marks.' . $student->id, []);

                $status = $studentMarks['status'] ?? 'present';

                if ($status !== 'present') {
                    $internalMarks  = 0;
                    $theoryMarks    = 0;
                    $practicalMarks = 0;
                    $totalMarks     = 0;
                } else {
                    $internalMarks  = (float) ($studentMarks['internal_marks'] ?? 0);
                    $theoryMarks    = (float) ($studentMarks['theory_marks'] ?? 0);
                    $practicalMarks = (float) ($studentMarks['practical_marks'] ?? 0);

                    $totalMarks = $internalMarks + $theoryMarks + $practicalMarks;

                    // Total can never be more than the subject maximum
                    if ($totalMarks > $maximumMarks) {
                        throw ValidationException::withMessages([
                            "marks.{$student->id}.internal_marks" =>
                                "Total marks for {$student->student_id} cannot exceed {$maximumMarks}.",
                        ]);
                    }
                }

                ExamMark::updateOrCreate(
                    [
                        'exam_id'    => $exam->id,
                        'student_id' => $student->id,
                        'subject_id' => $examSubject->subject_id,
                    ],
                    [
                        'exam_class_id'   => $examClass->id,
                        'internal_marks'  => $internalMarks,
                        'theory_marks'    => $theoryMarks,
                        'practical_marks' => $practicalMarks,
                        'max_marks'       => $maximumMarks,
                        'total_marks'     => $totalMarks,
                        'status'          => $status,
                        'remarks'         => $studentMarks['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.results.marks', [
                'exam_id'    => $exam->id,
                'class_id'   => $schoolClass->id,
                'section'    => $section,
                'subject_id' => $examSubject->subject_id,
            ])
            ->with(
                'success',
                'Marks saved successfully for ' . $students->count() . ' student(s).'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | AJAX: LOAD SUBJECTS
    |--------------------------------------------------------------------------
    */

    public function loadSubjects(Request $request)
    {
        $validated = $request->validate([
            'exam_id'  => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:school_classes,id'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return response()->json([
                'success'  => false,
                'message'  => 'Selected class is not assigned to this exam.',
                'subjects' => [],
            ], 422);
        }

        $subjects = ExamSubject::with('subject')
            ->where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->where('status', true)
            ->get()
            ->map(fn ($examSubject) => [
                'id'               => $examSubject->subject_id,
                'name'             => $examSubject->subject
                    ? $examSubject->subject->subject_name
                    : 'Unknown Subject',
                'code'             => $examSubject->subject
                    ? $examSubject->subject->subject_code
                    : null,
                'maximum_marks'    => $examSubject->maximum_marks,
                'passing_marks'    => $examSubject->passing_marks,
                'duration_minutes' => $examSubject->duration_minutes,
            ])
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id'            => $exam->id,
                'name'          => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'class' => [
                'id'      => $schoolClass->id,
                'name'    => $schoolClass->class_name,
                'section' => $schoolClass->section,
            ],
            'subjects' => $subjects,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE RESULTS
    |--------------------------------------------------------------------------
    | Every generation creates a new ResultVersion.
    | Old versions are never deleted.
    |--------------------------------------------------------------------------
    */

    public function generateResult(Request $request)
    {
        $validated = $request->validate([
            'exam_id'       => ['required', 'integer', 'exists:exams,id'],
            'class_id'      => ['required', 'integer', 'exists:school_classes,id'],
            'section'       => ['nullable', 'string', 'max:50'],
            'exam_class_id' => ['nullable', 'integer', 'exists:exam_classes,id'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        $examClassQuery = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id);

        if (!empty($validated['exam_class_id'])) {
            $examClassQuery->where('id', $validated['exam_class_id']);
        }

        $examClass = $examClassQuery->first();

        if (!$examClass) {
            return back()->with(
                'error',
                'This class/section is not assigned to the selected examination.'
            );
        }

        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        $students = $this->classStudents($exam, $schoolClass, $section);

        if ($students->isEmpty()) {
            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $examMarks = ExamMark::with('subject')
            ->where('exam_id', $exam->id)
            ->where('exam_class_id', $examClass->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');

        if ($examMarks->isEmpty()) {
            return back()->with(
                'error',
                'No marks have been entered for this examination.'
            );
        }

        // UPDATE: published results must not be changed silently
        $publishedExists = Result::query()
            ->where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->where('publication_status', 'published')
            ->exists();

        if ($publishedExists) {
            return back()->with(
                'error',
                'Some results are already published, so this class cannot be generated again.'
            );
        }

        // Subjects of this exam and class (passing marks + how many are required)
        $examSubjects = ExamSubject::query()
            ->where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->get()
            ->keyBy('subject_id');

        // UPDATE: every student must have marks for EVERY active subject
        $subjectCount = $examSubjects->where('status', true)->count();

        $skipped = [];

        DB::transaction(function () use (
            $students,
            $examMarks,
            $exam,
            $schoolClass,
            $section,
            $examSubjects,
            $subjectCount,
            &$skipped
        ) {
            foreach ($students as $student) {

                $studentMarks = $examMarks->get($student->id, collect());

                // UPDATE: skip students with missing subject marks
                if (
                    $studentMarks->isEmpty() ||
                    $studentMarks->count() < $subjectCount
                ) {
                    $skipped[] = $student->student_id;
                    continue;
                }

                $totalMaximumMarks  = 0;
                $totalObtainedMarks = 0;
                $hasFailedSubject   = false;
                $hasAbsentSubject   = false;

                foreach ($studentMarks as $mark) {

                    $maximumMarks  = (float) $mark->max_marks;
                    $obtainedMarks = (float) $mark->total_marks;

                    $totalMaximumMarks  += $maximumMarks;
                    $totalObtainedMarks += $obtainedMarks;

                    if ($mark->status === 'absent') {
                        $hasAbsentSubject = true;
                    }

                    $examSubject = $examSubjects->get($mark->subject_id);

                    if (
                        $mark->status === 'present' &&
                        $examSubject &&
                        $obtainedMarks < (float) $examSubject->passing_marks
                    ) {
                        $hasFailedSubject = true;
                    }
                }

                $percentage = $totalMaximumMarks > 0
                    ? round(($totalObtainedMarks / $totalMaximumMarks) * 100, 2)
                    : 0;

                $grade = $this->calculateGrade($percentage);

                if ($hasFailedSubject) {
                    $resultStatus = 'fail';
                } elseif ($hasAbsentSubject) {
                    $resultStatus = 'absent';
                } else {
                    $resultStatus = 'pass';
                }

                // 1. Current result
                $result = Result::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'exam_id'    => $exam->id,
                    ],
                    [
                        'academic_year'      => $exam->academic_year,
                        'class_name'         => $schoolClass->class_name,
                        'section'            => $section,
                        'total_marks'        => $totalMaximumMarks,
                        'obtained_marks'     => $totalObtainedMarks,
                        'percentage'         => $percentage,
                        'grade'              => $grade,
                        'result_status'      => $resultStatus,
                        'publication_status' => 'generated',
                        'published_at'       => null,
                        'published_by'       => null,
                        'generated_at'       => now(),
                        'remarks'            => null,
                    ]
                );

                // 2. Next version number
                $nextVersion = ((int) ResultVersion::where('result_id', $result->id)
                    ->max('version_no')) + 1;

                // 3. Historical snapshot
                $resultVersion = ResultVersion::create([
                    'result_id'             => $result->id,
                    'version_no'            => $nextVersion,
                    'student_id_snapshot'   => $student->student_id,
                    'student_name_snapshot' => $this->fullName($student),
                    'exam_name_snapshot'    => $exam->exam_name,
                    'academic_year'         => $exam->academic_year,
                    'class_name'            => $schoolClass->class_name,
                    'section'               => $section,
                    'total_marks'           => $totalMaximumMarks,
                    'obtained_marks'        => $totalObtainedMarks,
                    'percentage'            => $percentage,
                    'grade'                 => $grade,
                    'result_status'         => $resultStatus,
                    'remarks'               => null,
                    'generated_at'          => now(),
                ]);

                // 4. Subject rows (used for both the snapshot and the current result)
                $detailRows = [];

                foreach ($studentMarks as $mark) {

                    $subjectMaximumMarks  = (float) $mark->max_marks;
                    $subjectObtainedMarks = (float) $mark->total_marks;

                    $subjectPercentage = $subjectMaximumMarks > 0
                        ? round(($subjectObtainedMarks / $subjectMaximumMarks) * 100, 2)
                        : 0;

                    $detailRows[] = [
                        'mark' => $mark,
                        'row'  => [
                            'subject_id'      => $mark->subject_id,
                            'subject_name'    => $mark->subject
                                ? $mark->subject->subject_name
                                : 'Unknown Subject',
                            'max_marks'       => $subjectMaximumMarks,
                            'internal_marks'  => $mark->internal_marks,
                            'theory_marks'    => $mark->theory_marks,
                            'practical_marks' => $mark->practical_marks,
                            'total_marks'     => $subjectMaximumMarks,
                            'obtained_marks'  => $subjectObtainedMarks,
                            'grade'           => $this->calculateGrade($subjectPercentage),
                            'grade_point'     => $this->calculateGradePoint($subjectPercentage),
                        ],
                    ];
                }

                foreach ($detailRows as $item) {
                    ResultVersionDetail::create(
                        $item['row'] + [
                            'result_version_id' => $resultVersion->id,
                            'status'            => $item['mark']->status,
                            'remarks'           => $item['mark']->remarks,
                        ]
                    );
                }

                // 5. Replace current result details
                $result->details()->delete();

                foreach ($detailRows as $item) {
                    ResultDetail::create(
                        $item['row'] + ['result_id' => $result->id]
                    );
                }
            }
        });

        $generated = $students->count() - count($skipped);

        $message = "Results generated for {$generated} student(s).";

        if (!empty($skipped)) {
            $message .= ' Skipped ' . count($skipped)
                . ' student(s) with missing subject marks: '
                . implode(', ', array_slice($skipped, 0, 10));
        }

        return redirect()
            ->route('admin.results.index')
            ->with(empty($skipped) ? 'success' : 'warning', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY
    |--------------------------------------------------------------------------
    */

    public function history(Result $result)
    {
        $result->load([
            'student',
            'exam',
            'versions' => function ($query) {
                $query->orderByDesc('version_no');
            },
        ]);

        return view('admin.results.history', compact('result'));
    }

    public function historyVersion(Result $result, ResultVersion $version)
    {
        // Version must belong to this result
        if ((int) $version->result_id !== (int) $result->id) {
            abort(404);
        }

        $version->load([
            'details' => function ($query) {
                $query->orderBy('id');
            },
        ]);

        return view('admin.results.history-version', compact('result', 'version'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW / PRINT / PDF
    |--------------------------------------------------------------------------
    */

    public function show(Result $result)
    {
        $result->load(['student', 'exam', 'details', 'whatsappNotifications']);

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

        $school = \App\Models\SchoolSetting::first();

        $pdf = Pdf::loadView('admin.results.pdf', [
            'result' => $result,
            'school' => $school,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $studentId = $result->student?->student_id ?? 'student';
        $examName  = $result->exam?->exam_name ?? 'result';

        $filename =
            'Result-' .
            preg_replace('/[^A-Za-z0-9\-]/', '-', $studentId) .
            '-' .
            preg_replace('/[^A-Za-z0-9\-]/', '-', $examName) .
            '.pdf';

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS RESULTS PAGE
    |--------------------------------------------------------------------------
    */

    public function classResults(Request $request)
    {
        $validated = $request->validate([
            'exam_id'       => ['required', 'integer', 'exists:exams,id'],
            'exam_class_id' => ['required', 'integer', 'exists:exam_classes,id'],
            'class_id'      => ['required', 'integer', 'exists:school_classes,id'],
            'section'       => ['nullable', 'string', 'max:50'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $examClass = ExamClass::query()
            ->where('id', $validated['exam_class_id'])
            ->where('exam_id', $exam->id)
            ->where('class_id', $validated['class_id'])
            ->firstOrFail();

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        $students = $this->classStudents($exam, $schoolClass, $section);

        $results = Result::query()
            ->where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('admin.results.class-results', compact(
            'exam',
            'examClass',
            'schoolClass',
            'section',
            'students',
            'results'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: VERIFY (single)
    |--------------------------------------------------------------------------
    */

    public function verify(Result $result)
    {
        $this->requirePermission('results.edit');

        if ($result->publication_status !== 'generated') {
            return back()->with('error', 'Only generated results can be verified.');
        }

        $result->update(['publication_status' => 'verified']);

        return back()->with('success', 'Result verified successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: APPROVE (single)
    |--------------------------------------------------------------------------
    */

    public function approve(Result $result)
    {
        $this->requireRole(['super_admin', 'admin']);

        if ($result->publication_status !== 'verified') {
            return back()->with('error', 'Only verified results can be approved.');
        }

        $result->update(['publication_status' => 'approved']);

        return back()->with('success', 'Result approved successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: PUBLISH (single)
    |--------------------------------------------------------------------------
    | WhatsApp sending is temporarily paused.
    |--------------------------------------------------------------------------
    */

    public function publish(Result $result, WhatsAppService $whatsappService)
    {
        $this->requireRole(['super_admin', 'admin']);

        if ($result->publication_status !== 'approved') {
            return back()->with('error', 'Only approved results can be published.');
        }

        $result->load(['student', 'exam']);

        $student = $result->student;

        if (!$student) {
            return back()->with('error', 'Student record not found for this result.');
        }

        $phone = $student->mother_mobile;

        $result->update([
            'publication_status' => 'published',
            'published_at'       => now(),
            'published_by'       => Auth::id(),
        ]);

        $notification = ResultWhatsappNotification::create([
            'result_id'  => $result->id,
            'student_id' => $student->id,
            'phone'      => $phone ?? '',
            'message'    => $this->buildResultWhatsAppMessage($result),
            'status'     => 'pending',
        ]);

        if (empty($phone)) {
            $notification->update([
                'status'        => 'failed',
                'error_message' => 'Mother mobile number is not registered.',
            ]);

            return back()->with(
                'warning',
                'Result published, but WhatsApp notification is currently paused because mother mobile number is not registered.'
            );
        }

        return back()->with(
            'success',
            'Result published successfully. WhatsApp notification is currently paused.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: VERIFY (whole class)
    |--------------------------------------------------------------------------
    */

    public function verifyClassResults(Request $request)
    {
        $this->requirePermission('results.edit');

        $ctx = $this->classActionContext($request);

        if ($ctx['students']->isEmpty()) {
            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $missing = $this->missingResults($ctx['students'], $ctx['results']);

        if ($missing > 0) {
            return back()->with(
                'error',
                $missing . ' student(s) do not have generated results. Generate all results before verification.'
            );
        }

        $generatedResults = $ctx['results']->filter(
            fn ($result) => $result->publication_status === 'generated'
        );

        if ($generatedResults->isEmpty()) {
            return back()->with(
                'success',
                'All results for this class and section are already verified or processed.'
            );
        }

        $count = 0;

        DB::transaction(function () use ($generatedResults, &$count) {
            foreach ($generatedResults as $result) {

                if ($result->publication_status !== 'generated') {
                    continue;
                }

                $result->update(['publication_status' => 'verified']);

                $count++;
            }
        });

        return back()->with(
            'success',
            $count . ' result(s) verified successfully for '
            . $this->classLabel($ctx['schoolClass'], $ctx['section']) . '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: APPROVE (whole class)
    |--------------------------------------------------------------------------
    */

    public function approveClassResults(Request $request)
    {
        $this->requireRole(['super_admin', 'admin']);

        $ctx = $this->classActionContext($request);

        if ($ctx['students']->isEmpty()) {
            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $missing = $this->missingResults($ctx['students'], $ctx['results']);

        if ($missing > 0) {
            return back()->with(
                'error',
                $missing . ' student(s) do not have generated results. Generate all results before approval.'
            );
        }

        // Results still in "generated" are not verified yet
        $needVerification = $ctx['results']
            ->where('publication_status', 'generated')
            ->count();

        if ($needVerification > 0) {
            return back()->with(
                'error',
                $needVerification . ' result(s) still need verification. Please verify all results before approval.'
            );
        }

        $verifiedResults = $ctx['results']->filter(
            fn ($result) => $result->publication_status === 'verified'
        );

        if ($verifiedResults->isEmpty()) {
            return back()->with(
                'success',
                'All results for this class and section are already approved or published.'
            );
        }

        $count = 0;

        DB::transaction(function () use ($verifiedResults, &$count) {
            foreach ($verifiedResults as $result) {

                if ($result->publication_status !== 'verified') {
                    continue;
                }

                $result->update(['publication_status' => 'approved']);

                $count++;
            }
        });

        return back()->with(
            'success',
            $count . ' result(s) approved successfully for '
            . $this->classLabel($ctx['schoolClass'], $ctx['section']) . '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW: PUBLISH (whole class)
    |--------------------------------------------------------------------------
    | Nothing is published if any student is missing a result or has a
    | result that is still generated / verified.
    |--------------------------------------------------------------------------
    */

    public function publishClassResults(Request $request)
    {
        $this->requireRole(['super_admin', 'admin']);

        $ctx = $this->classActionContext($request);

        if ($ctx['students']->isEmpty()) {
            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $missing = $this->missingResults($ctx['students'], $ctx['results']);

        if ($missing > 0) {
            return back()->with(
                'error',
                $missing . ' student(s) do not have generated results. Generate all results before publishing.'
            );
        }

        $generatedCount = $ctx['results']->where('publication_status', 'generated')->count();
        $verifiedCount  = $ctx['results']->where('publication_status', 'verified')->count();

        if ($generatedCount > 0 || $verifiedCount > 0) {

            $parts = [];

            if ($generatedCount > 0) {
                $parts[] = $generatedCount . ' result(s) still need verification.';
            }

            if ($verifiedCount > 0) {
                $parts[] = $verifiedCount . ' result(s) still need approval.';
            }

            return back()->with(
                'error',
                implode(' ', $parts)
                . ' Please complete the verification and approval process for all students before publishing.'
            );
        }

        $approvedResults = $ctx['results']->filter(
            fn ($result) => $result->publication_status === 'approved'
        );

        if ($approvedResults->isEmpty()) {
            return back()->with(
                'success',
                'All results for this class and section are already published.'
            );
        }

        $count = 0;

        DB::transaction(function () use ($approvedResults, &$count) {
            foreach ($approvedResults as $result) {

                if ($result->publication_status !== 'approved') {
                    continue;
                }

                $result->update([
                    'publication_status' => 'published',
                    'published_at'       => now(),
                    'published_by'       => Auth::id(),
                ]);

                $count++;
            }
        });

        return back()->with(
            'success',
            $count . ' result(s) published successfully for '
            . $this->classLabel($ctx['schoolClass'], $ctx['section'])
            . '. WhatsApp notification is currently paused.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WHATSAPP (single result)
    |--------------------------------------------------------------------------
    | Opens WhatsApp with a pre-filled message. No API key needed.
    |--------------------------------------------------------------------------
    */

    public function whatsapp(Result $result)
    {
        if ($result->publication_status !== 'published') {
            return back()->with(
                'error',
                'Only published results can be sent through WhatsApp.'
            );
        }

        $result->load(['student', 'exam']);

        $student = $result->student;

        if (!$student) {
            return back()->with('error', 'Student record not found for this result.');
        }

        $phone = trim((string) (
            $student->mother_phone
            ?? $student->father_phone
            ?? $student->guardian_phone
            ?? $student->phone
            ?? ''
        ));

        if ($phone === '') {
            return back()->with(
                'warning',
                'Mother mobile number is not registered for this student.'
            );
        }

        // Keep digits only
        $phone = preg_replace('/\D+/', '', $phone);

        // 09876543210 -> 9876543210
        if (strlen($phone) === 11 && str_starts_with($phone, '0')) {
            $phone = substr($phone, 1);
        }

        // 9876543210 -> 919876543210
        if (strlen($phone) === 10) {
            $phone = '91' . $phone;
        }

        if (strlen($phone) < 10 || strlen($phone) > 15) {
            return back()->with(
                'error',
                'The registered mother mobile number is invalid.'
            );
        }

        $message = $this->buildResultWhatsAppMessage($result);

        ResultWhatsappNotification::create([
            'result_id'  => $result->id,
            'student_id' => $student->id,
            'phone'      => $phone,
            'message'    => $message,
            'status'     => 'pending',
        ]);

        // UPDATE: rawurlencode keeps spaces as %20 (not +)
        $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);

        return redirect()->away($whatsappUrl);
    }

    /*
    |--------------------------------------------------------------------------
    | BULK WHATSAPP PAGE
    |--------------------------------------------------------------------------
    */

    public function bulkWhatsapp(Request $request)
    {
        $examId  = $request->input('exam_id');
        $classId = $request->input('class_id');
        $section = $request->input('section');

        if (!$examId || !$classId) {
            return redirect()
                ->route('admin.results.index')
                ->with('error', 'Please select an exam and class before opening Bulk WhatsApp.');
        }

        $exam = Exam::find($examId);

        if (!$exam) {
            return redirect()
                ->route('admin.results.index')
                ->with('error', 'Selected examination was not found.');
        }

        $schoolClass = SchoolClass::find($classId);

        if (!$schoolClass) {
            return redirect()
                ->route('admin.results.index')
                ->with('error', 'Selected class was not found.');
        }

        // Class 7 -> 7
        $classNumber = trim(preg_replace(
            '/^Class\s*/i',
            '',
            trim((string) $schoolClass->class_name)
        ));

        $selectedSection = !empty($section)
            ? trim($section)
            : trim((string) $schoolClass->section);

        $students = Student::query()
            ->whereNull('deleted_at')
            ->where('class', $classNumber)
            ->where('section', $selectedSection)
            ->where('academic_year', $exam->academic_year)
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->get();

        $results = Result::query()
            ->where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view(
            'admin.results.bulk-whatsapp',
            compact('students', 'results', 'exam', 'schoolClass')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function buildResultWhatsAppMessage(Result $result): string
    {
        $student = $result->student;

        $studentName = $this->fullName($student);

        if ($studentName === '') {
            $studentName = 'your child';
        }

        // Public result page: parent enters Student ID, Mother's Name and CAPTCHA
        $resultUrl = URL::route('result.public');

        $examName = $result->exam?->exam_name ?? 'Examination';

        return
            "Dear Parent,\n\n" .
            "The result of your child " . $studentName . " has been published.\n\n" .
            "Exam: " . $examName . "\n" .
            "Academic Year: " . ($result->academic_year ?? 'N/A') . "\n\n" .
            "View your child's result online:\n" .
            $resultUrl . "\n\n" .
            "Student ID: " . ($student->student_id ?? 'N/A') . "\n\n" .
            "For verification, enter the Student ID and Mother's Name on the result page and complete the CAPTCHA.\n\n" .
            "Regards,\n" .
            "School Administration";
    }

    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';

        return 'F';
    }

    private function calculateGradePoint(float $percentage): float
    {
        if ($percentage >= 90) return 10.0;
        if ($percentage >= 80) return 9.0;
        if ($percentage >= 70) return 8.0;
        if ($percentage >= 60) return 7.0;
        if ($percentage >= 50) return 6.0;
        if ($percentage >= 40) return 5.0;

        return 0.0;
    }

    // UPDATE: permission check
    private function requirePermission(string $permission): void
    {
        abort_unless(
            Access::allows(auth()->user(), $permission),
            403,
            'You do not have permission to do this.'
        );
    }

    // UPDATE: role check (only admins can approve and publish)
    private function requireRole(array $roles): void
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->role && in_array($user->role->name, $roles, true),
            403,
            'Only an administrator can do this.'
        );
    }

    // "Class 8" -> "8" (this is how students.class is stored)
    private function studentClassValue(SchoolClass $schoolClass): string
    {
        return preg_replace(
            '/^Class\s+/i',
            '',
            trim((string) $schoolClass->class_name)
        );
    }

    private function fullName($student): string
    {
        if (!$student) {
            return '';
        }

        return collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])
            ->filter(fn ($value) => filled($value))
            ->implode(' ');
    }

    // Active students of one exam class and section
    private function classStudents(Exam $exam, SchoolClass $schoolClass, string $section)
    {
        return Student::query()
            ->where('class', $this->studentClassValue($schoolClass))
            ->when($section !== '', function ($query) use ($section) {
                $query->where('section', $section);
            })
            ->where('academic_year', $exam->academic_year)
            ->where('status', 'active')
            ->orderByRaw('CAST(NULLIF(roll_number, "") AS UNSIGNED)')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();
    }

    // Shared start of verify / approve / publish for a whole class
    private function classActionContext(Request $request): array
    {
        $validated = $request->validate([
            'exam_id'       => ['required', 'integer', 'exists:exams,id'],
            'exam_class_id' => ['required', 'integer', 'exists:exam_classes,id'],
            'class_id'      => ['required', 'integer', 'exists:school_classes,id'],
            'section'       => ['nullable', 'string', 'max:50'],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail($validated['class_id']);

        // ExamClass must belong to this exam and class
        ExamClass::query()
            ->where('id', $validated['exam_class_id'])
            ->where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->firstOrFail();

        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        $students = $this->classStudents($exam, $schoolClass, $section);

        $results = Result::query()
            ->where('exam_id', $exam->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return compact('exam', 'schoolClass', 'section', 'students', 'results');
    }

    // How many students have no result yet
    private function missingResults($students, $results): int
    {
        return $students
            ->filter(fn ($student) => !$results->has($student->id))
            ->count();
    }

    // "Class 8 - Section A"
    private function classLabel(SchoolClass $schoolClass, string $section): string
    {
        return $schoolClass->class_name
            . ($section !== '' ? ' - Section ' . $section : '');
    }
    /**
 * Bulk delete multiple records.
 */

}