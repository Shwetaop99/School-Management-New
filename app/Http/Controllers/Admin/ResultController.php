<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamMark;
use App\Models\ExamSubject;
use App\Models\Result;
use App\Models\ResultDetail;
use App\Models\ResultVersion;
use App\Models\ResultVersionDetail;
use App\Models\Student;
use App\Models\Class\SchoolClass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\ResultWhatsappNotification;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\URL;

class ResultController extends Controller
{
    /**
     * Result list.
     */
    public function index(Request $request)
    {
        $exams = Exam::query()
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->get();

        $results = Result::with([
                'student',
                'exam',
                'details',
            ])
            ->when($request->filled('exam_id'), function ($query) use ($request) {
                $query->where('exam_id', $request->exam_id);
            })
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->whereHas('student', function ($studentQuery) use ($request) {
                    $studentQuery->where(
                        'student_id',
                        'like',
                        '%' . $request->student_id . '%'
                    );
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.results.index', compact(
            'exams',
            'results'
        ));
    }

    /**
     * Show result generation page.
     */
    public function generate()
    {
        $exams = Exam::query()
            ->orderByDesc('id')
            ->get();

        $selectedExam = null;

        return view('admin.results.generate', compact(
            'exams',
            'selectedExam'
        ));
    }

    /**
     * Load classes assigned to selected examination.
     *
     * AJAX endpoint:
     * GET /admin/results/load-classes?exam_id=4
     */
    public function loadClasses(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $examClasses = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->get()
            ->filter(function ($examClass) {
                return $examClass->schoolClass !== null;
            })
            ->values();

        $classes = $examClasses
            ->map(function ($examClass) {
                return [
                    'id' => $examClass->class_id,
                    'name' => $examClass->schoolClass->class_name,
                    'section' => $examClass->schoolClass->section,
                    'academic_year' => $examClass->schoolClass->academic_year,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id' => $exam->id,
                'exam_name' => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'classes' => $classes,
        ]);
    }

    /**
     * Load classes assigned to selected examination.
     *
     * AJAX endpoint:
     * GET /admin/results/exam-classes?exam_id=4
     */

/**
 * Load classes assigned to selected examination.
 *
 * AJAX endpoint:
 * GET /admin/results/exam-classes?exam_id=4
 *
 * Returns dynamic:
 * - student count
 * - generated count
 * - verified count
 * - approved count
 * - published count
 */
public function getExamClasses(Request $request)
{
    $validated = $request->validate([
        'exam_id' => [
            'required',
            'integer',
            'exists:exams,id',
        ],
    ]);

    $examId = $validated['exam_id'];

    /*
     * ------------------------------------------------------------
     * Get selected examination
     * ------------------------------------------------------------
     */
    $exam = Exam::findOrFail($examId);

    /*
     * ------------------------------------------------------------
     * Get classes assigned to this examination
     * ------------------------------------------------------------
     */
    $examClasses = ExamClass::with('schoolClass')
        ->where('exam_id', $examId)
        ->get();

    /*
     * ------------------------------------------------------------
     * Build dynamic class data
     * ------------------------------------------------------------
     */
    $classes = $examClasses
        ->filter(function ($examClass) {
            return $examClass->schoolClass !== null;
        })
        ->map(function ($examClass) use ($examId, $exam) {

            $schoolClass = $examClass->schoolClass;

            /*
             * ----------------------------------------------------
             * Students belonging to this school class
             *
             * Your students.class may store values such as:
             * 1, 2, 3 ... 12
             *
             * while school_classes.class_name may be:
             * Class 1, Class 2, etc.
             * ----------------------------------------------------
             */

            $className = trim(
                (string) ($schoolClass->class_name ?? '')
            );

            /*
             * Extract numeric class value.
             *
             * Examples:
             * "Class 1" -> 1
             * "Class 8" -> 8
             * "8th"     -> 8
             * "10"      -> 10
             */
            preg_match(
                '/\d+/',
                $className,
                $matches
            );

            $studentClass = $matches[0] ?? $className;

            /*
             * ----------------------------------------------------
             * Student query
             * ----------------------------------------------------
             */
            $studentQuery = \App\Models\Student::query()
                ->where('class', $studentClass);

            /*
             * Match section when the school class has one.
             */
            if (
                !empty($schoolClass->section)
            ) {
                $studentQuery->where(
                    'section',
                    $schoolClass->section
                );
            }

            /*
             * Academic year matching.
             *
             * Only apply it when the school class actually
             * contains an academic year.
             */
            if (
                !empty($schoolClass->academic_year)
            ) {
                $studentQuery->where(
                    'academic_year',
                    $schoolClass->academic_year
                );
            }

            /*
             * Get student IDs.
             */
            $studentIds = $studentQuery
                ->pluck('id');

            $studentCount = $studentIds->count();

            /*
             * ----------------------------------------------------
             * Dynamic Result counts
             * ----------------------------------------------------
             *
             * IMPORTANT:
             *
             * publication_status is the workflow field:
             *
             * generated
             * verified
             * approved
             * published
             *
             * We count results belonging to:
             *
             * - selected examination
             * - students of this class/section
             */
            $resultQuery = Result::query()
                ->where('exam_id', $examId)
                ->whereIn('student_id', $studentIds);

            /*
             * Current status counts.
             */
            $generatedCount = (clone $resultQuery)
                ->where(
                    'publication_status',
                    'generated'
                )
                ->count();

            $verifiedCount = (clone $resultQuery)
                ->where(
                    'publication_status',
                    'verified'
                )
                ->count();

            $approvedCount = (clone $resultQuery)
                ->where(
                    'publication_status',
                    'approved'
                )
                ->count();

            $publishedCount = (clone $resultQuery)
                ->where(
                    'publication_status',
                    'published'
                )
                ->count();

            /*
             * ----------------------------------------------------
             * Return card data
             * ----------------------------------------------------
             */
            return [
                'exam_class_id' => $examClass->id,

                'exam_id' => $examId,

                'class_id' => $schoolClass->id,

                'class_name' => $schoolClass->class_name,

                'section' => $schoolClass->section,

                'academic_year' =>
                    $schoolClass->academic_year
                    ?? $exam->academic_year
                    ?? null,

                'student_count' => $studentCount,

                'generated_count' => $generatedCount,

                'verified_count' => $verifiedCount,

                'approved_count' => $approvedCount,

                'published_count' => $publishedCount,
            ];
        })
        ->values();

    /*
     * ------------------------------------------------------------
     * Return JSON
     * ------------------------------------------------------------
     */
    return response()->json([
        'success' => true,

        'exam' => [
            'id' => $exam->id,
            'exam_name' => $exam->exam_name,
            'academic_year' => $exam->academic_year,
        ],

        'classes' => $classes,
    ]);
}


    /**
     * Load students for selected exam/class/section.
     */
    public function loadStudents(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
            'section' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        /*
         * Make sure this class is assigned to the selected exam.
         */
        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return response()->json([
                'success' => false,
                'message' => 'This class is not assigned to the selected exam.',
                'students' => [],
            ], 422);
        }

        /*
         * Convert:
         *
         * school_classes.class_name = Class 1
         * students.class            = 1
         *
         * to:
         *
         * 1
         */
        $studentClass = preg_replace(
            '/^Class\s+/i',
            '',
            trim((string) $schoolClass->class_name)
        );

        /*
         * Use requested section.
         * If no section was supplied, use class section.
         */
        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        $students = Student::query()
            ->where('class', $studentClass)
            ->when(
                $section !== '',
                function ($query) use ($section) {
                    $query->where('section', $section);
                }
            )
            ->where('academic_year', $exam->academic_year)
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(NULLIF(roll_number, "") AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'class',
                'section',
                'academic_year',
                'status',
            ]);

        /*
         * Add full name for AJAX response.
         */
        $students->each(function ($student) {
            $student->full_name = collect([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ])
                ->filter(function ($value) {
                    return filled($value);
                })
                ->implode(' ');
        });

        return response()->json([
            'success' => true,
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'class' => [
                'id' => $schoolClass->id,
                'name' => $schoolClass->class_name,
                'section' => $section,
            ],
            'students' => $students,
        ]);
    }

    /**
     * Show Enter Marks page.
     */
    public function marks(Request $request)
    {
        /*
         * 1. Load exams.
         */
        $exams = Exam::query()
            ->orderByDesc('id')
            ->get();

        /*
         * 2. No exam selected.
         */
        if (!$request->filled('exam_id')) {
            return view('admin.results.marks', [
                'exams' => $exams,
                'exam' => null,
                'examClasses' => collect(),
                'examClass' => null,
                'examSubject' => null,
                'students' => collect(),
                'section' => null,
                'sections' => collect(),
                'selectionMode' => true,
            ]);
        }

        /*
         * 3. Selected exam.
         */
        $exam = Exam::findOrFail($request->exam_id);

        /*
         * 4. Classes assigned to exam.
         */
        $examClasses = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->get()
            ->filter(function ($examClass) {
                return $examClass->schoolClass !== null;
            })
            ->values();

        /*
         * 5. Load sections.
         */
        $selectedClassId = $request->class_id;

        $sections = collect();

        if ($selectedClassId) {
            $selectedExamClass = $examClasses->firstWhere(
                'class_id',
                (int) $selectedClassId
            );

            if (
                $selectedExamClass &&
                $selectedExamClass->schoolClass
            ) {
                $className = $selectedExamClass
                    ->schoolClass
                    ->class_name;

                $sections = SchoolClass::query()
                    ->where('class_name', $className)
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

        /*
         * 6. Selection not complete.
         */
        if (
            !$request->filled('class_id') ||
            !$request->filled('section') ||
            !$request->filled('subject_id')
        ) {
            return view('admin.results.marks', [
                'exams' => $exams,
                'exam' => $exam,
                'examClasses' => $examClasses,
                'examClass' => null,
                'examSubject' => null,
                'students' => collect(),
                'section' => $request->section,
                'sections' => $sections,
                'selectionMode' => true,
            ]);
        }

        /*
         * 7. Validate selection.
         */
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
            'section' => [
                'required',
                'string',
                'max:50',
            ],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
        ]);

        /*
         * 8. Get exam class.
         */
        $examClass = ExamClass::with('schoolClass')
            ->where('exam_id', $exam->id)
            ->where('class_id', $validated['class_id'])
            ->firstOrFail();

        /*
         * 9. Verify section.
         */
        $sectionExists = SchoolClass::query()
            ->where('id', $validated['class_id'])
            ->where(
                'class_name',
                $examClass->schoolClass->class_name
            )
            ->where('section', $validated['section'])
            ->where(
                'academic_year',
                $exam->academic_year
            )
            ->where('status', true)
            ->exists();

        if (!$sectionExists) {
            abort(
                404,
                'Selected section does not belong to the selected class.'
            );
        }

        /*
         * 10. Get subject.
         */
        $examSubject = ExamSubject::with('subject')
            ->where('exam_id', $exam->id)
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('status', true)
            ->firstOrFail();

        /*
         * 11. Convert class name.
         */
        $className = $examClass->schoolClass->class_name ?? '';

        $studentClassName = preg_replace(
            '/^Class\s+/i',
            '',
            trim($className)
        );

        /*
         * 12. Load students.
         */
        $students = Student::query()
            ->where('class', $studentClassName)
            ->where('section', $validated['section'])
            ->where('academic_year', $exam->academic_year)
            ->where('status', 'active')
            ->orderByRaw(
                '
                CASE
                    WHEN roll_number IS NULL OR roll_number = "" THEN 1
                    ELSE 0
                END
                '
            )
            ->orderBy('roll_number')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        /*
         * 13. Attach full name.
         */
        $students->each(function ($student) {
            $student->full_name = collect([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ])
                ->filter(function ($value) {
                    return filled($value);
                })
                ->implode(' ');
        });

        /*
         * 14. Load existing marks.
         */
        $studentIds = $students->pluck('id');

        $existingMarks = ExamMark::query()
            ->where('exam_id', $exam->id)
            ->where('exam_class_id', $examClass->id)
            ->where(
                'subject_id',
                $examSubject->subject_id
            )
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy('student_id');

        /*
         * 15. Attach marks.
         */
        $students->each(function ($student) use ($existingMarks) {
            $student->examMark = $existingMarks->get(
                $student->id
            );
        });

        /*
         * 16. Return marks page.
         */
        return view('admin.results.marks', [
            'exams' => $exams,
            'exam' => $exam,
            'examClasses' => $examClasses,
            'examClass' => $examClass,
            'examSubject' => $examSubject,
            'students' => $students,
            'section' => $validated['section'],
            'sections' => $sections,
            'selectionMode' => false,
        ]);
    }

    /**
     * Save Internal + Theory + Practical marks.
     */
    public function saveMarks(Request $request)
    {
        $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
            'section' => [
                'nullable',
                'string',
                'max:50',
            ],
            'subject_id' => [
                'required',
                'integer',
                'exists:subjects,id',
            ],
            'marks' => [
                'required',
                'array',
            ],
            'marks.*.internal_marks' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'marks.*.theory_marks' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'marks.*.practical_marks' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'marks.*.status' => [
                'required',
                'in:present,absent,na',
            ],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $schoolClass = SchoolClass::findOrFail(
            $request->class_id
        );

        /*
         * Verify class.
         */
        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This class is not assigned to the selected examination.'
                );
        }

        /*
         * Verify subject.
         */
        $examSubject = ExamSubject::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->where(
                'subject_id',
                $request->subject_id
            )
            ->where('status', true)
            ->first();

        if (!$examSubject) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'This subject is not assigned to the selected examination and class.'
                );
        }

        /*
         * Maximum marks.
         */
        $maximumMarks = (float) $examSubject->maximum_marks;

        /*
         * Convert class name.
         */
        $studentClass = preg_replace(
            '/^Class\s+/i',
            '',
            trim((string) $schoolClass->class_name)
        );

        $section = $request->filled('section')
            ? trim($request->section)
            : trim((string) $schoolClass->section);

        /*
         * Get submitted students.
         */
        $submittedStudentIds = array_keys(
            $request->input('marks', [])
        );

        $students = Student::query()
            ->where('class', $studentClass)
            ->when(
                $section !== '',
                function ($query) use ($section) {
                    $query->where('section', $section);
                }
            )
            ->where('academic_year', $exam->academic_year)
            ->where('status', 'active')
            ->whereIn('id', $submittedStudentIds)
            ->get();

        /*
         * Security check.
         */
        if (
            $students->count() !==
            count($submittedStudentIds)
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'One or more selected students do not belong to this class or section.'
                );
        }

        /*
         * Save all marks inside transaction.
         */
        DB::transaction(function () use (
            $request,
            $students,
            $exam,
            $examClass,
            $examSubject,
            $maximumMarks
        ) {
            foreach ($students as $student) {
                $studentMarks = $request->input(
                    'marks.' . $student->id,
                    []
                );

                $status = $studentMarks['status']
                    ?? 'present';

                if ($status !== 'present') {
                    $internalMarks = 0;
                    $theoryMarks = 0;
                    $practicalMarks = 0;
                    $totalMarks = 0;
                } else {
                    $internalMarks = (float) (
                        $studentMarks['internal_marks']
                        ?? 0
                    );

                    $theoryMarks = (float) (
                        $studentMarks['theory_marks']
                        ?? 0
                    );

                    $practicalMarks = (float) (
                        $studentMarks['practical_marks']
                        ?? 0
                    );

                    $totalMarks =
                        $internalMarks +
                        $theoryMarks +
                        $practicalMarks;

                    /*
                     * Total cannot exceed subject maximum.
                     */
                    if ($totalMarks > $maximumMarks) {
                        throw ValidationException::withMessages([
                            "marks.{$student->id}.internal_marks" =>
                                "Total marks for {$student->student_id} cannot exceed {$maximumMarks}.",
                        ]);
                    }
                }

                ExamMark::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                        'subject_id' => $examSubject->subject_id,
                    ],
                    [
                        'exam_class_id' => $examClass->id,
                        'internal_marks' => $internalMarks,
                        'theory_marks' => $theoryMarks,
                        'practical_marks' => $practicalMarks,
                        'max_marks' => $maximumMarks,
                        'total_marks' => $totalMarks,
                        'status' => $status,
                        'remarks' => $studentMarks['remarks'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.results.marks', [
                'exam_id' => $exam->id,
                'class_id' => $schoolClass->id,
                'section' => $section,
                'subject_id' => $examSubject->subject_id,
            ])
            ->with(
                'success',
                'Marks saved successfully for ' .
                $students->count() .
                ' student(s).'
            );
    }

    /**
     * Load subjects assigned to selected exam and class.
     */
    public function loadSubjects(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
        ]);

        $exam = Exam::findOrFail($validated['exam_id']);

        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        /*
         * Verify class assignment.
         */
        $examClass = ExamClass::where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->first();

        if (!$examClass) {
            return response()->json([
                'success' => false,
                'message' => 'Selected class is not assigned to this exam.',
                'subjects' => [],
            ], 422);
        }

        /*
         * Load subjects.
         */
        $examSubjects = ExamSubject::with('subject')
            ->where('exam_id', $exam->id)
            ->where('class_id', $schoolClass->id)
            ->where('status', true)
            ->get();

        $subjects = $examSubjects
            ->map(function ($examSubject) {
                return [
                    'id' => $examSubject->subject_id,
                    'name' => $examSubject->subject
                        ? $examSubject->subject->subject_name
                        : 'Unknown Subject',
                    'code' => $examSubject->subject
                        ? $examSubject->subject->subject_code
                        : null,
                    'maximum_marks' =>
                        $examSubject->maximum_marks,
                    'passing_marks' =>
                        $examSubject->passing_marks,
                    'duration_minutes' =>
                        $examSubject->duration_minutes,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->exam_name,
                'academic_year' => $exam->academic_year,
            ],
            'class' => [
                'id' => $schoolClass->id,
                'name' => $schoolClass->class_name,
                'section' => $schoolClass->section,
            ],
            'subjects' => $subjects,
        ]);
    }

    /**
     * Generate student results.
     *
     * Every generation creates a new ResultVersion.
     * Old versions are never deleted.
     */
    public function generateResult(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
            'section' => [
                'nullable',
                'string',
                'max:50',
            ],
            'exam_class_id' => [
                'nullable',
                'integer',
                'exists:exam_classes,id',
            ],
        ]);

        $exam = Exam::findOrFail(
            $validated['exam_id']
        );

        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        /*
         * Find the exact examination class assignment.
         */
        $examClassQuery = ExamClass::where(
                'exam_id',
                $exam->id
            )
            ->where(
                'class_id',
                $schoolClass->id
            );

        if (!empty($validated['exam_class_id'])) {
            $examClassQuery->where(
                'id',
                $validated['exam_class_id']
            );
        }

        $examClass = $examClassQuery->first();

        if (!$examClass) {
            return back()->with(
                'error',
                'This class/section is not assigned to the selected examination.'
            );
        }

        /*
         * Convert:
         *
         * Class 1 -> 1
         * Class 10 -> 10
         */
        $studentClass = preg_replace(
            '/^Class\s+/i',
            '',
            trim((string) $schoolClass->class_name)
        );

        /*
         * Section.
         */
        $section = !empty($validated['section'])
            ? trim($validated['section'])
            : trim((string) $schoolClass->section);

        /*
         * Load active students.
         */
        $students = Student::query()
            ->where('class', $studentClass)
            ->when(
                $section !== '',
                function ($query) use ($section) {
                    $query->where('section', $section);
                }
            )
            ->where(
                'academic_year',
                $exam->academic_year
            )
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(NULLIF(roll_number, "") AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        if ($students->isEmpty()) {
            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        /*
         * Load marks only for this exact exam class.
         */
        $examMarks = ExamMark::with('subject')
            ->where('exam_id', $exam->id)
            ->where(
                'exam_class_id',
                $examClass->id
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->get()
            ->groupBy('student_id');

        if ($examMarks->isEmpty()) {
            return back()->with(
                'error',
                'No marks have been entered for this examination.'
            );
        }

        /*
         * Generate all results inside one transaction.
         *
         * Result = current/latest result.
         * ResultVersion = permanent historical snapshot.
         */
        DB::transaction(function () use (
            $students,
            $examMarks,
            $exam,
            $schoolClass,
            $section
        ) {
            foreach ($students as $student) {
                $studentMarks = $examMarks->get(
                    $student->id,
                    collect()
                );

                /*
                 * Skip students who have no marks.
                 */
                if ($studentMarks->isEmpty()) {
                    continue;
                }

                $totalMaximumMarks = 0;
                $totalObtainedMarks = 0;
                $hasFailedSubject = false;
                $hasAbsentSubject = false;

                /*
                 * Calculate overall marks.
                 */
                foreach ($studentMarks as $mark) {
                    $maximumMarks = (float) $mark->max_marks;
                    $obtainedMarks = (float) $mark->total_marks;

                    $totalMaximumMarks += $maximumMarks;
                    $totalObtainedMarks += $obtainedMarks;

                    /*
                     * Absent subject.
                     */
                    if ($mark->status === 'absent') {
                        $hasAbsentSubject = true;
                    }

                    /*
                     * Check passing marks.
                     */
                    $examSubject = ExamSubject::query()
                        ->where('exam_id', $exam->id)
                        ->where(
                            'class_id',
                            $schoolClass->id
                        )
                        ->where(
                            'subject_id',
                            $mark->subject_id
                        )
                        ->first();

                    if (
                        $mark->status === 'present' &&
                        $examSubject &&
                        $obtainedMarks <
                        (float) $examSubject->passing_marks
                    ) {
                        $hasFailedSubject = true;
                    }
                }

                /*
                 * Percentage.
                 */
                $percentage = $totalMaximumMarks > 0
                    ? (
                        $totalObtainedMarks /
                        $totalMaximumMarks
                    ) * 100
                    : 0;

                $percentage = round(
                    $percentage,
                    2
                );

                /*
                 * Grade.
                 */
                $grade = $this->calculateGrade(
                    $percentage
                );

                /*
                 * Result status.
                 */
                if ($hasFailedSubject) {
                    $resultStatus = 'fail';
                } elseif ($hasAbsentSubject) {
                    $resultStatus = 'absent';
                } else {
                    $resultStatus = 'pass';
                }

                /*
                 * =====================================================
                 * 1. CREATE OR UPDATE CURRENT RESULT
                 * =====================================================
                 */
                $result = Result::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'exam_id' => $exam->id,
                    ],
                    [
                        'academic_year' => $exam->academic_year,
                        'class_name' => $schoolClass->class_name,
                        'section' => $section,
                        'total_marks' => $totalMaximumMarks,
                        'obtained_marks' => $totalObtainedMarks,
                        'percentage' => $percentage,
                        'grade' => $grade,
                        'result_status' => $resultStatus,

                        /*
                         * Every regenerated result starts
                         * from Generated state.
                         */
                        'publication_status' => 'generated',
                        'published_at' => null,
                        'published_by' => null,
                        'generated_at' => now(),
                        'remarks' => null,
                    ]
                );

                /*
                 * =====================================================
                 * 2. FIND NEXT VERSION NUMBER
                 * =====================================================
                 */
                $lastVersion = ResultVersion::where(
                    'result_id',
                    $result->id
                )->max('version_no');

                $nextVersion = ((int) $lastVersion) + 1;

                /*
                 * =====================================================
                 * 3. CREATE HISTORICAL RESULT VERSION
                 * =====================================================
                 */
                $resultVersion = ResultVersion::create([
                    'result_id' => $result->id,
                    'version_no' => $nextVersion,
                    'student_id_snapshot' =>
                        $student->student_id,
                    'student_name_snapshot' =>
                        trim(
                            collect([
                                $student->first_name,
                                $student->middle_name,
                                $student->last_name,
                            ])
                                ->filter()
                                ->implode(' ')
                        ),
                    'exam_name_snapshot' =>
                        $exam->exam_name,
                    'academic_year' =>
                        $exam->academic_year,
                    'class_name' =>
                        $schoolClass->class_name,
                    'section' =>
                        $section,
                    'total_marks' =>
                        $totalMaximumMarks,
                    'obtained_marks' =>
                        $totalObtainedMarks,
                    'percentage' =>
                        $percentage,
                    'grade' =>
                        $grade,
                    'result_status' =>
                        $resultStatus,
                    'remarks' =>
                        null,
                    'generated_at' =>
                        now(),
                ]);

                /*
                 * =====================================================
                 * 4. SAVE HISTORICAL SUBJECT DETAILS
                 * =====================================================
                 */
                foreach ($studentMarks as $mark) {
                    $subjectName = $mark->subject
                        ? $mark->subject->subject_name
                        : 'Unknown Subject';

                    $subjectMaximumMarks =
                        (float) $mark->max_marks;

                    $subjectObtainedMarks =
                        (float) $mark->total_marks;

                    $subjectPercentage =
                        $subjectMaximumMarks > 0
                            ? (
                                $subjectObtainedMarks /
                                $subjectMaximumMarks
                            ) * 100
                            : 0;

                    $subjectPercentage =
                        round(
                            $subjectPercentage,
                            2
                        );

                    $subjectGrade =
                        $this->calculateGrade(
                            $subjectPercentage
                        );

                    ResultVersionDetail::create([
                        'result_version_id' =>
                            $resultVersion->id,
                        'subject_id' =>
                            $mark->subject_id,
                        'subject_name' =>
                            $subjectName,
                        'max_marks' =>
                            $subjectMaximumMarks,
                        'internal_marks' =>
                            $mark->internal_marks,
                        'theory_marks' =>
                            $mark->theory_marks,
                        'practical_marks' =>
                            $mark->practical_marks,
                        'total_marks' =>
                            $subjectMaximumMarks,
                        'obtained_marks' =>
                            $subjectObtainedMarks,
                        'grade' =>
                            $subjectGrade,
                        'grade_point' =>
                            $this->calculateGradePoint(
                                $subjectPercentage
                            ),
                        'status' =>
                            $mark->status,
                        'remarks' =>
                            $mark->remarks,
                    ]);
                }

                /*
                 * =====================================================
                 * 5. REPLACE CURRENT RESULT DETAILS
                 * =====================================================
                 */
                $result->details()->delete();

                foreach ($studentMarks as $mark) {
                    $subjectName = $mark->subject
                        ? $mark->subject->subject_name
                        : 'Unknown Subject';

                    $subjectMaximumMarks =
                        (float) $mark->max_marks;

                    $subjectObtainedMarks =
                        (float) $mark->total_marks;

                    $subjectPercentage =
                        $subjectMaximumMarks > 0
                            ? (
                                $subjectObtainedMarks /
                                $subjectMaximumMarks
                            ) * 100
                            : 0;

                    $subjectPercentage =
                        round(
                            $subjectPercentage,
                            2
                        );

                    $subjectGrade =
                        $this->calculateGrade(
                            $subjectPercentage
                        );

                    ResultDetail::create([
                        'result_id' =>
                            $result->id,
                        'subject_id' =>
                            $mark->subject_id,
                        'subject_name' =>
                            $subjectName,
                        'max_marks' =>
                            $subjectMaximumMarks,
                        'internal_marks' =>
                            $mark->internal_marks,
                        'theory_marks' =>
                            $mark->theory_marks,
                        'practical_marks' =>
                            $mark->practical_marks,
                        'total_marks' =>
                            $subjectMaximumMarks,
                        'obtained_marks' =>
                            $subjectObtainedMarks,
                        'grade' =>
                            $subjectGrade,
                        'grade_point' =>
                            $this->calculateGradePoint(
                                $subjectPercentage
                            ),
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.results.index')
            ->with(
                'success',
                'Results generated successfully for ' .
                $students->count() .
                ' student(s).'
            );
    }

    /**
     * Show all generated versions.
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

        return view(
            'admin.results.history',
            compact('result')
        );
    }

    /**
     * Show one historical result version.
     */
    public function historyVersion(
        Result $result,
        ResultVersion $version
    ) {
        /*
         * Make sure version belongs to result.
         */
        if ($version->result_id !== $result->id) {
            abort(404);
        }

        $version->load([
            'details' => function ($query) {
                $query->orderBy('id');
            },
        ]);

        return view(
            'admin.results.history-version',
            compact(
                'result',
                'version'
            )
        );
    }

    /**
     * Show current/latest result.
     */
    public function show(Result $result)
    {
        $result->load([
            'student',
            'exam',
            'details',
            'whatsappNotifications',
        ]);

        return view(
            'admin.results.show',
            compact('result')
        );
    }

    /**
     * Print current result.
     */
    public function print(Result $result)
    {
        $result->load([
            'student',
            'exam',
            'details',
        ]);

        return view(
            'admin.results.print',
            compact('result')
        );
    }

    /**
     * Download current result PDF.
     */
    public function pdf(Result $result)
    {
        $result->load([
            'student',
            'exam',
            'details',
        ]);

        $school = \App\Models\SchoolSetting::first();

        $pdf = Pdf::loadView(
            'admin.results.pdf',
            [
                'result' => $result,
                'school' => $school,
            ]
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        $studentId =
            $result->student?->student_id
            ?? 'student';

        $examName =
            $result->exam?->exam_name
            ?? 'result';

        $filename =
            'Result-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $studentId
            ) .
            '-' .
            preg_replace(
                '/[^A-Za-z0-9\-]/',
                '-',
                $examName
            ) .
            '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Calculate grade from percentage.
     */
    private function calculateGrade(float $percentage): string
    {
        if ($percentage >= 90) {
            return 'A+';
        }

        if ($percentage >= 80) {
            return 'A';
        }

        if ($percentage >= 70) {
            return 'B+';
        }

        if ($percentage >= 60) {
            return 'B';
        }

        if ($percentage >= 50) {
            return 'C';
        }

        if ($percentage >= 40) {
            return 'D';
        }

        return 'F';
    }

    /**
     * Calculate grade point.
     */
    private function calculateGradePoint(
        float $percentage
    ): float {
        if ($percentage >= 90) {
            return 10.0;
        }

        if ($percentage >= 80) {
            return 9.0;
        }

        if ($percentage >= 70) {
            return 8.0;
        }

        if ($percentage >= 60) {
            return 7.0;
        }

        if ($percentage >= 50) {
            return 6.0;
        }

        if ($percentage >= 40) {
            return 5.0;
        }

        return 0.0;
    }

    /**
     * Show class-wise generated results.
     */
    public function classResults(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => [
                'required',
                'integer',
                'exists:exams,id',
            ],
            'exam_class_id' => [
                'required',
                'integer',
                'exists:exam_classes,id',
            ],
            'class_id' => [
                'required',
                'integer',
                'exists:school_classes,id',
            ],
            'section' => [
                'nullable',
                'string',
                'max:50',
            ],
        ]);

        /*
         * Get exam.
         */
        $exam = Exam::findOrFail(
            $validated['exam_id']
        );

        /*
         * Make sure this exam-class actually belongs
         * to the selected exam and class.
         */
        $examClass = ExamClass::query()
            ->where(
                'id',
                $validated['exam_class_id']
            )
            ->where(
                'exam_id',
                $exam->id
            )
            ->where(
                'class_id',
                $validated['class_id']
            )
            ->firstOrFail();

        /*
         * Get actual school class.
         *
         * Section is stored here, NOT in exam_classes.
         */
        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );

        /*
         * Use URL section if supplied.
         * Otherwise use section from school_classes.
         */
        $section = $validated['section']
            ?: $schoolClass->section;

        /*
         * Convert:
         *
         * Class 1 -> 1
         * Class 10 -> 10
         */
        $studentClass = preg_replace(
            '/^Class\s+/i',
            '',
            trim((string) $schoolClass->class_name)
        );

        /*
         * Get students belonging to this class/section.
         */
        $students = Student::query()
            ->where(
                'class',
                $studentClass
            )
            ->where(
                'section',
                $section
            )
            ->where(
                'academic_year',
                $exam->academic_year
            )
            ->where(
                'status',
                'active'
            )
            ->orderByRaw(
                'CAST(NULLIF(roll_number, "") AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        /*
         * Get already generated results for these students.
         */
        $results = Result::query()
            ->where(
                'exam_id',
                $exam->id
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->get()
            ->keyBy('student_id');

        return view(
            'admin.results.class-results',
            compact(
                'exam',
                'examClass',
                'schoolClass',
                'section',
                'students',
                'results'
            )
        );
    }

    /**
     * Verify a generated result.
     */
    public function verify(Result $result)
    {
        if ($result->publication_status !== 'generated') {
            return back()->with(
                'error',
                'Only generated results can be verified.'
            );
        }

        $result->update([
            'publication_status' => 'verified',
        ]);

        return back()->with(
            'success',
            'Result verified successfully.'
        );
    }

    /**
     * Approve a verified result.
     */
    public function approve(Result $result)
    {
        if ($result->publication_status !== 'verified') {
            return back()->with(
                'error',
                'Only verified results can be approved.'
            );
        }

        $result->update([
            'publication_status' => 'approved',
        ]);

        return back()->with(
            'success',
            'Result approved successfully.'
        );
    }

    /**
     * Publish an approved result online.
     *
     * WhatsApp sending is temporarily paused.
     */
    public function publish(
        Result $result,
        WhatsAppService $whatsappService
    ) {
        /*
        |--------------------------------------------------------------------------
        | 1. Only APPROVED results can be published
        |--------------------------------------------------------------------------
        */
        if ($result->publication_status !== 'approved') {
            return back()->with(
                'error',
                'Only approved results can be published.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Load student
        |--------------------------------------------------------------------------
        */
        $result->load([
            'student',
            'exam',
        ]);

        $student = $result->student;

        if (!$student) {
            return back()->with(
                'error',
                'Student record not found for this result.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Get mother's registered mobile number
        |--------------------------------------------------------------------------
        */
        $phone = $student->mother_mobile;

        /*
        |--------------------------------------------------------------------------
        | 4. Publish the result
        |--------------------------------------------------------------------------
        */
        $result->update([
            'publication_status' => 'published',
            'published_at' => now(),
            'published_by' => Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. Create WhatsApp notification record
        |--------------------------------------------------------------------------
        */
        $message = $this->buildResultWhatsAppMessage(
            $result
        );

        $notification =
            ResultWhatsappNotification::create([
                'result_id' => $result->id,
                'student_id' => $student->id,
                'phone' => $phone ?? '',
                'message' => $message,
                'status' => 'pending',
            ]);

        /*
        |--------------------------------------------------------------------------
        | 6. Check mother's mobile number
        |--------------------------------------------------------------------------
        */
        if (empty($phone)) {
            $notification->update([
                'status' => 'failed',
                'error_message' =>
                    'Mother mobile number is not registered.',
            ]);

            return back()->with(
                'warning',
                'Result published, but WhatsApp notification is currently paused because mother mobile number is not registered.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 7. WhatsApp sending temporarily paused
        |--------------------------------------------------------------------------
        |
        | Meta WhatsApp API will be connected later.
        |
        */

        return back()->with(
            'success',
            'Result published successfully. WhatsApp notification is currently paused.'
        );
    }


    /**
 * Publish all approved results for a class and section.
 *
 * Every student in the selected class/section must have a result.
 * Every result must be either approved or already published.
 *
 * Nothing is published if any student is missing a result or
 * has a result that is still generated/verified.
 */
public function publishClassResults(Request $request)
{
    $validated = $request->validate([
        'exam_id' => [
            'required',
            'integer',
            'exists:exams,id',
        ],
        'exam_class_id' => [
            'required',
            'integer',
            'exists:exam_classes,id',
        ],
        'class_id' => [
            'required',
            'integer',
            'exists:school_classes,id',
        ],
        'section' => [
            'nullable',
            'string',
            'max:50',
        ],
    ]);

    $exam = Exam::findOrFail(
        $validated['exam_id']
    );

    $schoolClass = SchoolClass::findOrFail(
        $validated['class_id']
    );

    /*
     * Make sure the selected ExamClass actually belongs
     * to the selected exam and school class.
     */
    $examClass = ExamClass::query()
        ->where('id', $validated['exam_class_id'])
        ->where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->firstOrFail();

    /*
     * Determine section.
     */
    $section = !empty($validated['section'])
        ? trim($validated['section'])
        : trim((string) $schoolClass->section);

    /*
     * Convert:
     *
     * Class 8
     *
     * into:
     *
     * 8
     *
     * This must match the value stored in students.class.
     */
    $studentClass = preg_replace(
        '/^Class\s+/i',
        '',
        trim((string) $schoolClass->class_name)
    );

    /*
     * Get all active students belonging to this
     * exact class, section and academic year.
     */
    $students = Student::query()
        ->where('class', $studentClass)
        ->when(
            $section !== '',
            function ($query) use ($section) {
                $query->where('section', $section);
            }
        )
        ->where(
            'academic_year',
            $exam->academic_year
        )
        ->where(
            'status',
            'active'
        )
        ->orderByRaw(
            'CAST(NULLIF(roll_number, "") AS UNSIGNED)'
        )
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get();

    if ($students->isEmpty()) {
        return back()->with(
            'error',
            'No active students found for the selected class and section.'
        );
    }

    /*
     * Get all results for these students.
     */
    $results = Result::query()
        ->where('exam_id', $exam->id)
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy('student_id');

    /*
     * Check whether every student has a generated result.
     */
    $missingResults = $students->filter(function ($student) use ($results) {
        return !$results->has($student->id);
    });

    if ($missingResults->isNotEmpty()) {
        return back()->with(
            'error',
            $missingResults->count() .
            ' student(s) do not have generated results. Generate all results before publishing.'
        );
    }

    /*
     * Check publication status of every result.
     *
     * Allowed:
     * - approved
     * - published
     *
     * Not allowed:
     * - generated
     * - verified
     */
    $notReadyResults = $results->filter(function ($result) {
        return !in_array(
            $result->publication_status,
            [
                'approved',
                'published',
            ],
            true
        );
    });

    if ($notReadyResults->isNotEmpty()) {

        $generatedCount = $notReadyResults
            ->where(
                'publication_status',
                'generated'
            )
            ->count();

        $verifiedCount = $notReadyResults
            ->where(
                'publication_status',
                'verified'
            )
            ->count();

        $messageParts = [];

        if ($generatedCount > 0) {
            $messageParts[] =
                $generatedCount .
                ' result(s) still need verification.';
        }

        if ($verifiedCount > 0) {
            $messageParts[] =
                $verifiedCount .
                ' result(s) still need approval.';
        }

        return back()->with(
            'error',
            implode(' ', $messageParts) .
            ' Please complete the verification and approval process for all students before publishing.'
        );
    }

    /*
     * Only approved results actually need publishing.
     *
     * Already published results are left unchanged.
     */
    $approvedResults = $results->filter(function ($result) {
        return $result->publication_status === 'approved';
    });

    if ($approvedResults->isEmpty()) {
        return back()->with(
            'success',
            'All results for this class and section are already published.'
        );
    }

    /*
     * Publish all approved results in one transaction.
     */
    $publishedCount = 0;

    DB::transaction(function () use (
        $approvedResults,
        &$publishedCount
    ) {
        foreach ($approvedResults as $result) {

            /*
             * Re-check the status inside the transaction
             * before publishing.
             */
            if (
                $result->publication_status !==
                'approved'
            ) {
                continue;
            }

            $result->update([
                'publication_status' => 'published',
                'published_at' => now(),
                'published_by' => Auth::id(),
            ]);

            $publishedCount++;
        }
    });

    return back()->with(
        'success',
        $publishedCount .
        ' result(s) published successfully for ' .
        $schoolClass->class_name .
        ($section !== ''
            ? ' - Section ' . $section
            : '') .
        '. WhatsApp notification is currently paused.'
    );
}


    /**
 * Build WhatsApp notification message.
 */
protected function buildResultWhatsAppMessage(
    Result $result
): string {
    $student = $result->student;

    /*
     * Build student's full name from actual student fields.
     */
    $studentName = collect([
        $student->first_name,
        $student->middle_name,
        $student->last_name,
    ])
        ->filter(function ($value) {
            return filled($value);
        })
        ->implode(' ');

    if ($studentName === '') {
        $studentName = 'your child';
    }

    /*
     * Public result page.
     *
     * Parent will enter:
     * - Student ID
     * - Mother's Name
     * - CAPTCHA
     */
    $resultUrl = URL::route(
        'result.public'
    );

    $examName = $result->exam?->exam_name
        ?? 'Examination';

    return
        "Dear Parent,\n\n" .

        "The result of your child " .
        $studentName .
        " has been published.\n\n" .

        "Exam: " .
        $examName .
        "\n" .

        "Academic Year: " .
        ($result->academic_year ?? 'N/A') .
        "\n\n" .

        "View your child's result online:\n" .
        $resultUrl .
        "\n\n" .

        "Student ID: " .
        ($student->student_id ?? 'N/A') .
        "\n\n" .

        "For verification, enter the Student ID and Mother's Name on the result page and complete the CAPTCHA.\n\n" .

        "Regards,\n" .
        "School Administration";
}

/**
 * Verify all generated results for a class and section.
 *
 * Every active student in the selected class/section must have
 * a generated result before verification can begin.
 *
 * Already verified, approved, or published results are left unchanged.
 */
public function verifyClassResults(Request $request)
{
    $validated = $request->validate([
        'exam_id' => [
            'required',
            'integer',
            'exists:exams,id',
        ],
        'exam_class_id' => [
            'required',
            'integer',
            'exists:exam_classes,id',
        ],
        'class_id' => [
            'required',
            'integer',
            'exists:school_classes,id',
        ],
        'section' => [
            'nullable',
            'string',
            'max:50',
        ],
    ]);

    $exam = Exam::findOrFail(
        $validated['exam_id']
    );

    $schoolClass = SchoolClass::findOrFail(
        $validated['class_id']
    );

    /*
     * Make sure the ExamClass belongs to the
     * selected exam and school class.
     */
    ExamClass::query()
        ->where('id', $validated['exam_class_id'])
        ->where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->firstOrFail();

    /*
     * Determine section.
     */
    $section = !empty($validated['section'])
        ? trim($validated['section'])
        : trim((string) $schoolClass->section);

    /*
     * Convert:
     *
     * Class 8
     *
     * into:
     *
     * 8
     */
    $studentClass = preg_replace(
        '/^Class\s+/i',
        '',
        trim((string) $schoolClass->class_name)
    );

    /*
     * Get all active students for this
     * class, section and academic year.
     */
    $students = Student::query()
        ->where(
            'class',
            $studentClass
        )
        ->when(
            $section !== '',
            function ($query) use ($section) {
                $query->where(
                    'section',
                    $section
                );
            }
        )
        ->where(
            'academic_year',
            $exam->academic_year
        )
        ->where(
            'status',
            'active'
        )
        ->get();

    if ($students->isEmpty()) {
        return back()->with(
            'error',
            'No active students found for the selected class and section.'
        );
    }

    /*
     * Get all results for these students.
     */
    $results = Result::query()
        ->where(
            'exam_id',
            $exam->id
        )
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy('student_id');

    /*
     * Every student must have a result.
     */
    $missingResults = $students->filter(
        function ($student) use ($results) {
            return !$results->has(
                $student->id
            );
        }
    );

    if ($missingResults->isNotEmpty()) {
        return back()->with(
            'error',
            $missingResults->count() .
            ' student(s) do not have generated results. Generate all results before verification.'
        );
    }

    /*
     * Only generated results are verified.
     *
     * Already verified, approved and published
     * results remain unchanged.
     */
    $generatedResults = $results->filter(
        function ($result) {
            return $result->publication_status === 'generated';
        }
    );

    if ($generatedResults->isEmpty()) {
        return back()->with(
            'success',
            'All results for this class and section are already verified or processed.'
        );
    }

    /*
     * Verify all generated results together.
     */
    $verifiedCount = 0;

    DB::transaction(
        function () use (
            $generatedResults,
            &$verifiedCount
        ) {
            foreach ($generatedResults as $result) {

                if (
                    $result->publication_status !==
                    'generated'
                ) {
                    continue;
                }

                $result->update([
                    'publication_status' => 'verified',
                ]);

                $verifiedCount++;
            }
        }
    );

    return back()->with(
        'success',
        $verifiedCount .
        ' result(s) verified successfully for ' .
        $schoolClass->class_name .
        ($section !== ''
            ? ' - Section ' . $section
            : '') .
        '.'
    );
}


/**
 * Approve all verified results for a class and section.
 *
 * Every active student must have a result.
 * Results must be verified before they can be approved.
 *
 * Already approved or published results are left unchanged.
 */
public function approveClassResults(Request $request)
{
    $validated = $request->validate([
        'exam_id' => [
            'required',
            'integer',
            'exists:exams,id',
        ],
        'exam_class_id' => [
            'required',
            'integer',
            'exists:exam_classes,id',
        ],
        'class_id' => [
            'required',
            'integer',
            'exists:school_classes,id',
        ],
        'section' => [
            'nullable',
            'string',
            'max:50',
        ],
    ]);

    $exam = Exam::findOrFail(
        $validated['exam_id']
    );

    $schoolClass = SchoolClass::findOrFail(
        $validated['class_id']
    );

    // Make sure this ExamClass belongs to the selected exam and class.
    ExamClass::query()
        ->where('id', $validated['exam_class_id'])
        ->where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->firstOrFail();

    $section = !empty($validated['section'])
        ? trim($validated['section'])
        : trim((string) $schoolClass->section);

    // Convert "Class 4" to "4" if required.
    $studentClass = preg_replace(
        '/^Class\s+/i',
        '',
        trim((string) $schoolClass->class_name)
    );

    /*
     * Get all active students from the selected
     * class, section and academic year.
     */
    $students = Student::query()
        ->where(
            'class',
            $studentClass
        )
        ->when(
            $section !== '',
            function ($query) use ($section) {
                $query->where(
                    'section',
                    $section
                );
            }
        )
        ->where(
            'academic_year',
            $exam->academic_year
        )
        ->where(
            'status',
            'active'
        )
        ->get();

    if ($students->isEmpty()) {
        return back()->with(
            'error',
            'No active students found for the selected class and section.'
        );
    }

    /*
     * Get results belonging to these students
     * for the selected exam.
     */
    $results = Result::query()
        ->where(
            'exam_id',
            $exam->id
        )
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy('student_id');

    /*
     * Every active student must have a result.
     */
    $missingResults = $students->filter(
        function ($student) use ($results) {
            return !$results->has(
                $student->id
            );
        }
    );

    if ($missingResults->isNotEmpty()) {
        return back()->with(
            'error',
            $missingResults->count() .
            ' student(s) do not have generated results. Generate all results before approval.'
        );
    }

    /*
     * Results can only be approved when they are
     * already verified.
     *
     * Approved and published results are already processed.
     */
    $notReadyResults = $results->filter(
        function ($result) {
            return !in_array(
                $result->publication_status,
                [
                    'verified',
                    'approved',
                    'published',
                ],
                true
            );
        }
    );

    if ($notReadyResults->isNotEmpty()) {

        $generatedCount = $notReadyResults
            ->where(
                'publication_status',
                'generated'
            )
            ->count();

        $messageParts = [];

        if ($generatedCount > 0) {
            $messageParts[] =
                $generatedCount .
                ' result(s) still need verification.';
        }

        return back()->with(
            'error',
            implode(' ', $messageParts) .
            ' Please verify all results before approval.'
        );
    }

    /*
     * Select only verified results.
     */
    $verifiedResults = $results->filter(
        function ($result) {
            return $result->publication_status === 'verified';
        }
    );

    if ($verifiedResults->isEmpty()) {
        return back()->with(
            'success',
            'All results for this class and section are already approved or published.'
        );
    }

    $approvedCount = 0;

    DB::transaction(
        function () use (
            $verifiedResults,
            &$approvedCount
        ) {
            foreach ($verifiedResults as $result) {

                // Safety check.
                if (
                    $result->publication_status !==
                    'verified'
                ) {
                    continue;
                }

                $result->update([
                    'publication_status' => 'approved',
                ]);

                $approvedCount++;
            }
        }
    );

    return back()->with(
        'success',
        $approvedCount .
        ' result(s) approved successfully for ' .
        $schoolClass->class_name .
        ($section !== ''
            ? ' - Section ' . $section
            : '') .
        '.'
    );
}

/**
 * Open WhatsApp with pre-filled result publication message.
 *
 * No WhatsApp API key is required.
 *
 * Flow:
 * Admin clicks "Send WhatsApp"
 *      ↓
 * Laravel validates published result
 *      ↓
 * Gets mother's registered mobile number
 *      ↓
 * Builds result message
 *      ↓
 * Opens WhatsApp with pre-filled message
 *      ↓
 * Admin presses Send
 */
public function whatsapp(Result $result)
{
    /*
     * ------------------------------------------------------------
     * 1. Result must already be published
     * ------------------------------------------------------------
     */
    if ($result->publication_status !== 'published') {
        return back()->with(
            'error',
            'Only published results can be sent through WhatsApp.'
        );
    }

    /*
     * ------------------------------------------------------------
     * 2. Load required relationships
     * ------------------------------------------------------------
     */
    $result->load([
        'student',
        'exam',
    ]);

    $student = $result->student;

    if (!$student) {
        return back()->with(
            'error',
            'Student record not found for this result.'
        );
    }

    /*
     * ------------------------------------------------------------
     * 3. Get mother's registered mobile number
     * ------------------------------------------------------------
     */
   $phone = trim(
    (string) (
        $student->mother_phone
        ?? $student->father_phone
        ?? $student->guardian_phone
        ?? $student->phone
        ?? ''
    )
);

    if ($phone === '') {
        return back()->with(
            'warning',
            'Mother mobile number is not registered for this student.'
        );
    }

    /*
     * ------------------------------------------------------------
     * 4. Normalize phone number
     *
     * Examples:
     *
     * 9876543210
     * 09876543210
     * +91 9876543210
     * 91-9876543210
     *
     * become:
     *
     * 919876543210
     * ------------------------------------------------------------
     */
    $phone = preg_replace(
        '/\D+/',
        '',
        $phone
    );

    /*
     * If number starts with 0 and contains 11 digits,
     * remove the leading zero.
     */
    if (
        strlen($phone) === 11 &&
        str_starts_with($phone, '0')
    ) {
        $phone = substr(
            $phone,
            1
        );
    }

    /*
     * If it is a normal Indian 10-digit mobile number,
     * add country code 91.
     */
    if (strlen($phone) === 10) {
        $phone = '91' . $phone;
    }

    /*
     * ------------------------------------------------------------
     * 5. Basic phone validation
     * ------------------------------------------------------------
     */
    if (
        strlen($phone) < 10 ||
        strlen($phone) > 15
    ) {
        return back()->with(
            'error',
            'The registered mother mobile number is invalid.'
        );
    }

    /*
     * ------------------------------------------------------------
     * 6. Build WhatsApp message
     * ------------------------------------------------------------
     */
    $message = $this->buildResultWhatsAppMessage(
        $result
    );

    /*
     * ------------------------------------------------------------
     * 7. Save notification history
     * ------------------------------------------------------------
     */
    $notification =
        ResultWhatsappNotification::create([
            'result_id' =>
                $result->id,

            'student_id' =>
                $student->id,

            'phone' =>
                $phone,

            'message' =>
                $message,

            'status' =>
                'pending',
        ]);

    /*
     * ------------------------------------------------------------
     * 8. Build WhatsApp click-to-chat URL
     *
     * No API key.
     * No WhatsApp Cloud API.
     * No external service.
     * ------------------------------------------------------------
     */
    $whatsappUrl =
        'https://wa.me/' .
        $phone .
        '?text=' .
        urlencode($message);

    /*
     * ------------------------------------------------------------
     * 9. Redirect to WhatsApp
     * ------------------------------------------------------------
     */
    return redirect()->away(
        $whatsappUrl
    );
}

/**
 * Open the bulk WhatsApp result notification page.
 *
 * Step 1:
 * Only prepares the selected exam/class/section.
 * Actual WhatsApp sending will be implemented next.
 */


/**
 * Bulk WhatsApp result notification page.
 */



public function bulkWhatsapp(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Get Parameters
    |--------------------------------------------------------------------------
    */

    $examId      = $request->input('exam_id');
    $examClassId = $request->input('exam_class_id');
    $classId     = $request->input('class_id');
    $section     = $request->input('section');


    /*
    |--------------------------------------------------------------------------
    | Validate Parameters
    |--------------------------------------------------------------------------
    */

    if (!$examId || !$classId) {
        return redirect()
            ->route('admin.results.index')
            ->with(
                'error',
                'Please select an exam and class before opening Bulk WhatsApp.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Load Exam
    |--------------------------------------------------------------------------
    */

    $exam = \App\Models\Exam::find($examId);

    if (!$exam) {
        return redirect()
            ->route('admin.results.index')
            ->with(
                'error',
                'Selected examination was not found.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Load School Class
    |--------------------------------------------------------------------------
    */

    $schoolClass = \App\Models\Class\SchoolClass::find($classId);

    if (!$schoolClass) {
        return redirect()
            ->route('admin.results.index')
            ->with(
                'error',
                'Selected class was not found.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Class Name
    |--------------------------------------------------------------------------
    |
    | Class 1  -> 1
    | Class 7  -> 7
    | Class 10 -> 10
    |
    */

    $className = trim($schoolClass->class_name);

    $classNumber = preg_replace(
        '/^Class\s*/i',
        '',
        $className
    );

    $classNumber = trim($classNumber);


    /*
    |--------------------------------------------------------------------------
    | Section
    |--------------------------------------------------------------------------
    */

    $selectedSection = !empty($section)
        ? trim($section)
        : trim($schoolClass->section);


    /*
    |--------------------------------------------------------------------------
    | Load Students
    |--------------------------------------------------------------------------
    |
    | Father's mobile number is stored in:
    |
    | students.father_phone
    |
    */

    $students = \App\Models\Student::query()
        ->whereNull('deleted_at')
        ->where('class', $classNumber)
        ->where('section', $selectedSection)
        ->where('academic_year', $exam->academic_year)
        ->orderBy('roll_number')
        ->orderBy('first_name')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Load Results
    |--------------------------------------------------------------------------
    */

    $results = \App\Models\Result::query()
        ->where('exam_id', $exam->id)
        ->whereIn(
            'student_id',
            $students->pluck('id')
        )
        ->get()
        ->keyBy('student_id');


    /*
    |--------------------------------------------------------------------------
    | Return Bulk WhatsApp Page
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.results.bulk-whatsapp',
        compact(
            'students',
            'results',
            'exam',
            'schoolClass'
        )
    );
}


}
