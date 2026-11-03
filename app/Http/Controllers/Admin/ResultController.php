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
            'exam_class_id' => $examClass->id,
            'class_id' => $examClass->schoolClass->id,
            'class_name' => $examClass->schoolClass->class_name,
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

//load classes

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



            $className = trim(
                (string) ($schoolClass->class_name ?? '')
            );


            $studentClass = $className;

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
 * Load students for selected exam, class and section.
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
        'exam_class_id' => [
            'nullable',
            'integer',
            'exists:exam_classes,id',
        ],
    ]);

    $exam = Exam::findOrFail($validated['exam_id']);

    $schoolClass = SchoolClass::findOrFail(
        $validated['class_id']
    );

    // Verify that this class is assigned to the selected exam.
    $examClassQuery = ExamClass::where(
        'exam_id',
        $exam->id
    )->where(
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
        return response()->json([
            'success' => false,
            'message' => 'This class is not assigned to the selected exam.',
            'students' => [],
        ], 422);
    }

    $section = trim(
        (string) (
            $validated['section']
            ?? $schoolClass->section
            ?? ''
        )
    );

    // Support both "Class 1" and "1" in the students table.
    $className = trim((string) $schoolClass->class_name);

    $classNumber = preg_replace(
        '/^Class\s*/i',
        '',
        $className
    );

    $classNumber = trim($classNumber);

    // Prefer the class's academic year, falling back to the exam year.
    $academicYear = $schoolClass->academic_year
        ?: $exam->academic_year;

    $studentsQuery = Student::query()
        ->whereIn('class', array_unique([
            $className,
            $classNumber,
        ]));

    if ($section !== '') {
        $studentsQuery->where('section', $section);
    }

    if (!empty($academicYear)) {
        $studentsQuery->where(
            'academic_year',
            $academicYear
        );
    }

    $students = $studentsQuery
        ->where('status', 'active')
        ->orderByRaw(
            'CASE
                WHEN roll_number IS NULL OR roll_number = "" THEN 1
                ELSE 0
            END'
        )
        ->orderBy('roll_number')
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

    $students->each(function ($student) {
        $student->full_name = collect([
            $student->first_name,
            $student->middle_name,
            $student->last_name,
        ])
            ->filter(fn ($value) => filled($value))
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
            'academic_year' => $academicYear,
        ],
        'students' => $students,
        'debug' => [
            'class_values_checked' => [
                $className,
                $classNumber,
            ],
            'section' => $section,
            'academic_year' => $academicYear,
            'student_count' => $students->count(),
        ],
    ]);
}

    /**
     * Show Enter Marks page.
     */

/**
 * Show Enter Marks page.
 */
public function marks(Request $request)
{
    $exams = Exam::orderBy('id', 'desc')->get();

    // Default values
    $classes = collect();
    $sections = collect();
    $subjects = collect();
    $students = collect();
    $existingMarks = collect();

    $selectedExam = null;
    $selectedClass = null;
    $selectedSection = null;
    $selectedSubject = null;
    $examSubject = null;
    $examClass = null;

    /*
    |--------------------------------------------------------------------------
    | 1. Selected examination
    |--------------------------------------------------------------------------
    */
    if ($request->filled('exam_id')) {
        $selectedExam = Exam::find($request->exam_id);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Classes assigned to examination
    |--------------------------------------------------------------------------
    */
    if ($selectedExam) {
        $classes = ExamSubject::where('exam_id', $selectedExam->id)
            ->with('schoolClass')
            ->select('class_id')
            ->distinct()
            ->get()
            ->pluck('schoolClass')
            ->filter()
            ->unique('id')
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Selected class and available sections
    |--------------------------------------------------------------------------
    */
    if ($request->filled('class_id') && $selectedExam) {
        $selectedClass = SchoolClass::find($request->class_id);

        if ($selectedClass) {
            $examClass = ExamClass::where('exam_id', $selectedExam->id)
                ->where('class_id', $selectedClass->id)
                ->first();

            $sections = Student::where(
                    'class',
                    trim((string) $selectedClass->class_name)
                )
                ->whereNotNull('section')
                ->where('academic_year', $selectedExam->academic_year)
                ->distinct()
                ->orderBy('section')
                ->pluck('section');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Subjects assigned to selected examination and class
    |--------------------------------------------------------------------------
    */
    if ($selectedExam && $selectedClass) {
        $subjects = ExamSubject::with('subject')
            ->where('exam_id', $selectedExam->id)
            ->where('class_id', $selectedClass->id)
            ->get();

        if ($request->filled('subject_id')) {
            $examSubject = $subjects->firstWhere(
                'subject_id',
                (int) $request->subject_id
            );

            $selectedSubject = $examSubject?->subject;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Students and existing marks
    |--------------------------------------------------------------------------
    */
    if (
        $selectedExam &&
        $selectedClass &&
        $examClass &&
        $request->filled('section') &&
        $request->filled('subject_id') &&
        $examSubject
    ) {
        $selectedSection = trim($request->section);

        $students = Student::query()
            ->where(
                'class',
                trim((string) $selectedClass->class_name)
            )
            ->where('section', $selectedSection)
            ->where(
                'academic_year',
                $selectedExam->academic_year
            )
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        /*
         * IMPORTANT:
         * exam_marks uses exam_class_id, not class_id.
         * Section is identified through the selected students.
         */
        if ($students->isNotEmpty()) {
            $existingMarks = ExamMark::query()
                ->where('exam_id', $selectedExam->id)
                ->where('exam_class_id', $examClass->id)
                ->where('subject_id', $examSubject->subject_id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Always return the marks page
    |--------------------------------------------------------------------------
    */
    return view('admin.results.marks', compact(
        'exams',
        'selectedExam',
        'classes',
        'selectedClass',
        'sections',
        'selectedSection',
        'subjects',
        'selectedSubject',
        'students',
        'examSubject',
        'existingMarks'
    ));
}

/**
 * Save manually configured marks distribution.
 */
public function saveMarksDistribution(Request $request)
{
    $validated = $request->validate([
        'exam_id' => ['required', 'integer', 'exists:exams,id'],
        'class_id' => ['required', 'integer', 'exists:school_classes,id'],
        'subject_id' => ['required', 'integer', 'exists:subjects,id'],
        'internal_max_marks' => ['required', 'numeric', 'min:0'],
        'theory_max_marks' => ['required', 'numeric', 'min:0'],
        'passing_marks' => ['required', 'numeric', 'min:0'],
    ]);

    $exam = Exam::findOrFail($validated['exam_id']);
    $schoolClass = SchoolClass::findOrFail($validated['class_id']);

    // Verify that this class belongs to the selected examination.
    $examClass = ExamClass::where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->first();

    if (!$examClass) {
        return back()
            ->withInput()
            ->with('error', 'The selected class is not assigned to this examination.');
    }

    // Fetch the exact subject distribution for this exam and class.
    $examSubject = ExamSubject::where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->where('subject_id', $validated['subject_id'])
        ->first();

    if (!$examSubject) {
        return back()
            ->withInput()
            ->with('error', 'Subject assignment not found for this examination and class.');
    }

    $internalMaximum = (float) $validated['internal_max_marks'];
    $theoryMaximum = (float) $validated['theory_max_marks'];
    $passingMarks = (float) $validated['passing_marks'];

    $totalMaximum = $internalMaximum + $theoryMaximum;

    if ($totalMaximum <= 0) {
        return back()
            ->withInput()
            ->with('error', 'Total maximum marks must be greater than zero.');
    }

    if ($passingMarks > $totalMaximum) {
        return back()
            ->withInput()
            ->with('error', 'Passing marks cannot exceed total maximum marks.');
    }

    DB::transaction(function () use (
        $examSubject,
        $internalMaximum,
        $theoryMaximum,
        $totalMaximum,
        $passingMarks
    ) {
        $examSubject->forceFill([
            'internal_max_marks' => $internalMaximum,
            'theory_max_marks' => $theoryMaximum,
            'maximum_marks' => $totalMaximum,
            'passing_marks' => $passingMarks,
        ])->save();
    });

    return redirect()
        ->route('admin.results.marks', [
            'exam_id' => $exam->id,
            'class_id' => $schoolClass->id,
            'section' => $request->input('section', $schoolClass->section),
            'subject_id' => $validated['subject_id'],
        ])
        ->with('success', 'Marks distribution saved successfully.');
}
    /**
 * Save Internal + Theory marks.
 */

public function saveMarks(Request $request)
{
    \Log::info('SAVE MARKS REQUEST RECEIVED', [
    'user_id' => auth()->id(),
    'exam_id' => $request->input('exam_id'),
    'class_id' => $request->input('class_id'),
    'section' => $request->input('section'),
    'subject_id' => $request->input('subject_id'),
    'student_ids' => array_keys($request->input('marks', [])),
]);

    $validated = $request->validate([
        'exam_id' => ['required', 'integer', 'exists:exams,id'],
        'class_id' => ['required', 'integer', 'exists:school_classes,id'],
        'section' => ['nullable', 'string', 'max:50'],
        'subject_id' => ['required', 'integer', 'exists:subjects,id'],

        'marks' => ['required', 'array'],

        'marks.*.internal_marks' => ['nullable', 'numeric', 'min:0'],
        'marks.*.theory_marks' => ['nullable', 'numeric', 'min:0'],
        'marks.*.status' => ['required', 'in:present,absent,na'],
        'marks.*.remarks' => ['nullable', 'string', 'max:255'],
    ]);

    $exam = Exam::findOrFail($validated['exam_id']);
    $schoolClass = SchoolClass::findOrFail($validated['class_id']);

    $examClass = ExamClass::where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->first();

    if (!$examClass) {
        return back()->withInput()->with(
            'error',
            'This class is not assigned to the selected examination.'
        );
    }

    $examSubject = ExamSubject::where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->where('subject_id', $validated['subject_id'])
        ->first();

    if (!$examSubject) {
        return back()->withInput()->with(
            'error',
            'This subject is not assigned to the selected examination and class. Please check the exam subject configuration.'
        );
    }

    $internalMaximum = (float) $examSubject->internal_max_marks;
    $theoryMaximum = (float) $examSubject->theory_max_marks;
    $maximumMarks = (float) $examSubject->maximum_marks;

    // Validate saved marks distribution.
    if (
        $maximumMarks <= 0 ||
        ($internalMaximum + $theoryMaximum) <= 0 ||
        abs(($internalMaximum + $theoryMaximum) - $maximumMarks) > 0.001
    ) {
        return back()->withInput()->with(
            'error',
            'Please configure valid Internal and Theory maximum marks for this subject before entering student marks.'
        );
    }

    $section = trim((string) (
        $validated['section']
        ?? $schoolClass->section
        ?? ''
    ));

    $submittedStudentIds = array_map(
        'intval',
        array_keys($validated['marks'])
    );

    $students = Student::query()
        ->where('class', trim((string) $schoolClass->class_name))
        ->when($section !== '', function ($query) use ($section) {
            $query->where('section', $section);
        })
        ->where('academic_year', $exam->academic_year)
        ->where('status', 'active')
        ->whereIn('id', $submittedStudentIds)
        ->get();

    if ($students->count() !== count($submittedStudentIds)) {
        return back()->withInput()->with(
            'error',
            'One or more selected students do not belong to this class or section.'
        );
    }

    DB::transaction(function () use (
        $validated,
        $students,
        $exam,
        $examClass,
        $examSubject,
        $internalMaximum,
        $theoryMaximum,
        $maximumMarks
    ) {
        foreach ($students as $student) {
            $studentMarks = $validated['marks'][$student->id];

            $status = $studentMarks['status'] ?? 'present';

            $internalMarks = 0;
            $theoryMarks = 0;
            $totalMarks = 0;

            if ($status === 'present') {
                $internalMarks = (float) (
                    $studentMarks['internal_marks'] ?? 0
                );

                $theoryMarks = (float) (
                    $studentMarks['theory_marks'] ?? 0
                );

                if ($internalMarks > $internalMaximum) {
                    throw ValidationException::withMessages([
                        "marks.{$student->id}.internal_marks" =>
                            "Internal marks for {$student->student_id} cannot exceed {$internalMaximum}.",
                    ]);
                }

                if ($theoryMarks > $theoryMaximum) {
                    throw ValidationException::withMessages([
                        "marks.{$student->id}.theory_marks" =>
                            "Theory marks for {$student->student_id} cannot exceed {$theoryMaximum}.",
                    ]);
                }

                $totalMarks = $internalMarks + $theoryMarks;

                if ($totalMarks > $maximumMarks) {
                    throw ValidationException::withMessages([
                        "marks.{$student->id}.theory_marks" =>
                            "Total marks for {$student->student_id} cannot exceed {$maximumMarks}.",
                    ]);
                }
            }

            ExamMark::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'exam_class_id' => $examClass->id,
                    'student_id' => $student->id,
                    'subject_id' => $examSubject->subject_id,
                ],
                [
                    'internal_marks' => $internalMarks,
                    'theory_marks' => $theoryMarks,
                    'practical_marks' => 0,
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
            'Internal and Theory marks saved successfully for ' .
            $students->count() .
            ' student(s).'
        );
}


public function loadSections(Request $request)
{
    $validated = $request->validate([
        'exam_id' => ['required', 'integer', 'exists:exams,id'],
        'class_id' => ['required', 'integer', 'exists:school_classes,id'],
    ]);

    $exam = Exam::findOrFail($validated['exam_id']);

    // Confirm the selected class belongs to this exam.
    $examClass = ExamClass::with('schoolClass')
        ->where('exam_id', $exam->id)
        ->where('class_id', $validated['class_id'])
        ->first();

    if (!$examClass || !$examClass->schoolClass) {
        return response()->json([
            'success' => false,
            'message' => 'This class is not assigned to the selected exam.',
            'sections' => [],
        ], 422);
    }

    $selectedSchoolClass = $examClass->schoolClass;

    // Find all sections belonging to this class and academic year.
    $sections = SchoolClass::query()
        ->where('class_name', $selectedSchoolClass->class_name)
        ->where('academic_year', $exam->academic_year)
        ->where(function ($query) {
            $query->where('status', 1)
                ->orWhere('status', 'active');
        })
        ->whereNotNull('section')
        ->where('section', '!=', '')
        ->orderBy('section')
        ->pluck('section')
        ->unique()
        ->values()
        ->map(fn ($section) => [
            'id' => $section,
            'name' => $section,
        ]);

    $ownSection = trim((string) $selectedSchoolClass->section);

    if (
        $ownSection !== '' &&
        !$sections->contains(fn ($item) => $item['id'] === $ownSection)
    ) {
        $sections->push([
            'id' => $ownSection,
            'name' => $ownSection,
        ]);
    }

    return response()->json([
        'success' => true,
        'sections' => $sections->sortBy('name')->values(),
    ]);
}



public function loadSubjects(Request $request)
{
    $validated = $request->validate([
        'exam_id' => 'required|integer|exists:exams,id',
        'class_id' => 'required|integer|exists:school_classes,id',
    ]);

    // Verify that the selected class is assigned to this examination.
    $examClass = ExamClass::where('exam_id', $validated['exam_id'])
        ->where('class_id', $validated['class_id'])
        ->first();

    if (!$examClass) {
        return response()->json([
            'success' => false,
            'message' => 'This class is not assigned to the selected examination.',
            'subjects' => [],
        ], 422);
    }

    // Fetch subjects configured for this exact examination and class.
    $subjects = ExamSubject::with('subject')
        ->where('exam_id', $validated['exam_id'])
        ->where('class_id', $validated['class_id'])
        ->get()
        ->map(function ($item) {

            $subject = $item->subject;

            return [
                'id' => $item->subject_id,

                'name' => $subject->subject_name
                    ?? $subject->name
                    ?? 'Unknown Subject',

                'code' => $subject->subject_code
                    ?? $subject->code
                    ?? '',

                'internal_max_marks' => (float) $item->internal_max_marks,

                'theory_max_marks' => (float) $item->theory_max_marks,

                'maximum_marks' => (float) $item->maximum_marks,

                'passing_marks' => (float) $item->passing_marks,

                'duration_minutes' => (int) $item->duration_minutes,
            ];
        })
        ->values();

    return response()->json([
        'success' => true,
        'subjects' => $subjects,
    ]);
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
 * Display a specific historical result version.
 */
public function history(Result $result)
{
    $result->load([
        'student',
        'exam',
    ]);

    $versions = $result->versions()
        ->with('details')
        ->orderByDesc('version_number')
        ->get();

    return view(
        'admin.results.history',
        compact('result', 'versions')
    );
}

public function historyVersion(Result $result, int $version)
{
    $result->load([
        'student',
        'exam',
    ]);

    $version = $result->versions()
        ->with('details')
        ->whereKey($version)
        ->firstOrFail();

    return view(
        'admin.results.history-version',
        compact('result', 'version')
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

        $schoolClass = SchoolClass::findOrFail(
            $validated['class_id']
        );


        $section = $validated['section']
            ?: $schoolClass->section;


        $studentClass = trim((string) $schoolClass->class_name);

$students = Student::query()
    ->where('class', $studentClass)
    ->where('section', $section)
    ->where('academic_year', $exam->academic_year)
    ->where('status', 'active')
    ->orderByRaw('CAST(NULLIF(roll_number, "") AS UNSIGNED)')
    ->orderBy('first_name')
    ->orderBy('middle_name')
    ->orderBy('last_name')
    ->get();


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

        return back()
    ->with('success', 'Result published successfully.')
    ->with('warning', 'WhatsApp notification was not sent because the mother mobile number is not registered.');
    }

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


    $studentClass = trim((string) $schoolClass->class_name);

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


    $studentClass = trim(
    (string) $schoolClass->class_name
);


    $students = Student::query()
    ->where('class', $studentClass)
    ->where('section', $section)
    ->where('academic_year', $exam->academic_year)
    ->where('status', 'active')
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
    $studentClass = trim((string) $schoolClass->class_name);

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



public function bulkWhatsapp(Request $request)
{
    // Get selected filters
    $examId = $request->input('exam_id');
    $examClassId = $request->input('exam_class_id');
    $classId = $request->input('class_id');
    $section = $request->input('section');

    // Validate required parameters
    if (!$examId || !$classId) {
        return redirect()
            ->route('admin.results.index')
            ->with('error', 'Please select an exam and class before opening Bulk WhatsApp.');
    }

    // Load exam
    $exam = \App\Models\Exam::find($examId);

    if (!$exam) {
        return redirect()
            ->route('admin.results.index')
            ->with('error', 'Selected examination was not found.');
    }

    // Load school class
    $schoolClass = \App\Models\Class\SchoolClass::find($classId);

    if (!$schoolClass) {
        return redirect()
            ->route('admin.results.index')
            ->with('error', 'Selected class was not found.');
    }

    // IMPORTANT: Keep the complete class name.
    // Database stores "Class 1", not just "1".
    $className = trim($schoolClass->class_name);

    // Build student query
    $selectedSection = trim(
    $section ?: $schoolClass->section
);

$students = \App\Models\Student::query()
    ->whereNull('deleted_at')
    ->where('class', trim($schoolClass->class_name))
    ->where('section', $selectedSection)
    ->where('academic_year', trim($exam->academic_year))
    ->where('status', 'active')
    ->orderBy('roll_number')
    ->orderBy('first_name')
    ->get();


    // Apply section only when explicitly selected
    if (!empty($section)) {
        $studentQuery->where('section', trim($section));
    }

   $students = \App\Models\Student::query()
    ->whereNull('deleted_at')
    ->where('class', trim($schoolClass->class_name))
    ->where('section', $selectedSection)
    ->where('academic_year', trim($exam->academic_year))
    ->where('status', 'active')
    ->orderBy('roll_number')
    ->orderBy('first_name')
    ->get();

    // Load results for these students
    $results = \App\Models\Result::query()
        ->where('exam_id', $exam->id)
        ->whereIn('student_id', $students->pluck('id'))
        ->get()
        ->keyBy('student_id');

    // Return page
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



/**
 * Generate results for one student or all students
 * in the selected examination class and section.
 */
public function generateResult(Request $request)
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
            'required',
            'string',
            'max:50',
        ],
        'student_id' => [
            'nullable',
            'integer',
            'exists:students,id',
        ],
    ]);

    $exam = Exam::findOrFail($validated['exam_id']);

    $schoolClass = SchoolClass::findOrFail(
        $validated['class_id']
    );

    $section = trim($validated['section']);

    /*
    |--------------------------------------------------------------------------
    | 1. Verify examination class assignment
    |--------------------------------------------------------------------------
    */
    $examClass = ExamClass::where('id', $validated['exam_class_id'])
        ->where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->first();

    if (!$examClass) {
        return back()->with(
            'error',
            'The selected class is not assigned to this examination.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Get subjects configured for this exact exam and class
    |--------------------------------------------------------------------------
    */
    $examSubjects = ExamSubject::with('subject')
        ->where('exam_id', $exam->id)
        ->where('class_id', $schoolClass->id)
        ->get();

    if ($examSubjects->isEmpty()) {
        return back()->with(
            'error',
            'No subjects are configured for this examination and class.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Validate subject maximum marks
    |--------------------------------------------------------------------------
    */
    foreach ($examSubjects as $examSubject) {
        $internalMaximum = (float) $examSubject->internal_max_marks;
        $theoryMaximum = (float) $examSubject->theory_max_marks;
        $maximumMarks = (float) $examSubject->maximum_marks;
        $passingMarks = (float) $examSubject->passing_marks;

        if (
            $maximumMarks <= 0 ||
            abs(
                ($internalMaximum + $theoryMaximum) - $maximumMarks
            ) > 0.001 ||
            $passingMarks < 0 ||
            $passingMarks > $maximumMarks
        ) {
            return back()->with(
                'error',
                'Invalid marks distribution found for subject ID ' .
                $examSubject->subject_id .
                '. Please configure its marks before generating results.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Get students from the exact class, section and academic year
    |--------------------------------------------------------------------------
    */
    $studentsQuery = Student::query()
        ->where(
            'class',
            trim((string) $schoolClass->class_name)
        )
        ->where('section', $section)
        ->where('academic_year', $exam->academic_year)
        ->where('status', 'active');

    if ($request->filled('student_id')) {
        $studentsQuery->where('id', $validated['student_id']);
    }

    $students = $studentsQuery
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
    |--------------------------------------------------------------------------
    | 5. Generate results inside a database transaction
    |--------------------------------------------------------------------------
    */
    $generatedCount = 0;

DB::transaction(function () use (
    $students,
    $exam,
    $examClass,
    $schoolClass,
    $examSubjects,
    $section,
    &$generatedCount
) {
        foreach ($students as $student) {

            $totalMaximum = 0;
            $totalObtained = 0;
            $studentPassed = true;

            $detailRows = [];

            /*
             * Get marks for this student and exact exam-class assignment.
             */
            $marks = ExamMark::query()
                ->where('exam_id', $exam->id)
                ->where('exam_class_id', $examClass->id)
                ->where('student_id', $student->id)
                ->get()
                ->keyBy('subject_id');

            foreach ($examSubjects as $examSubject) {

                $subjectId = $examSubject->subject_id;

                $mark = $marks->get($subjectId);

                if (!$mark) {
                    throw ValidationException::withMessages([
                        'student_id' =>
                            'Marks are missing for student ' .
                            ($student->student_id ?? $student->id) .
                            ' in subject ' .
                            ($examSubject->subject?->subject_name
                                ?? $examSubject->subject?->name
                                ?? $subjectId) .
                            '. Please enter all subject marks first.',
                    ]);
                }

                $attendanceStatus = $mark->status ?? 'present';

                // NA subjects are excluded from the student's result totals.
                if ($attendanceStatus === 'na') {
                    continue;
                }

                $internalMaximum = (float) $examSubject->internal_max_marks;
                $theoryMaximum = (float) $examSubject->theory_max_marks;
                $maximumMarks = (float) $examSubject->maximum_marks;
                $passingMarks = (float) $examSubject->passing_marks;

                $internalMarks = (float) $mark->internal_marks;
                $theoryMarks = (float) $mark->theory_marks;
                $practicalMarks = (float) ($mark->practical_marks ?? 0);

                if ($attendanceStatus === 'absent') {
                    $internalMarks = 0;
                    $theoryMarks = 0;
                    $practicalMarks = 0;
                }

                if (
                    $internalMarks < 0 ||
                    $internalMarks > $internalMaximum ||
                    $theoryMarks < 0 ||
                    $theoryMarks > $theoryMaximum ||
                    $practicalMarks < 0
                ) {
                    throw ValidationException::withMessages([
                        'student_id' =>
                            'Invalid marks found for student ' .
                            ($student->student_id ?? $student->id) .
                            ' in subject ID ' . $subjectId . '.',
                    ]);
                }

                $obtainedMarks =
                    $internalMarks +
                    $theoryMarks +
                    $practicalMarks;

                if ($obtainedMarks > $maximumMarks + 0.001) {
                    throw ValidationException::withMessages([
                        'student_id' =>
                            'Obtained marks exceed maximum marks for student ' .
                            ($student->student_id ?? $student->id) .
                            ' in subject ID ' . $subjectId . '.',
                    ]);
                }

                $subjectPercentage = $maximumMarks > 0
                    ? ($obtainedMarks / $maximumMarks) * 100
                    : 0;

                $subjectGrade = $this->calculateGrade(
                    $subjectPercentage
                );

                $subjectGradePoint = $this->calculateGradePoint(
                    $subjectPercentage
                );

                $totalMaximum += $maximumMarks;
                $totalObtained += $obtainedMarks;

                // An absent student or a score below passing marks fails.
                if (
                    $attendanceStatus === 'absent' ||
                    $obtainedMarks < $passingMarks
                ) {
                    $studentPassed = false;
                }

                $detailRows[] = [
                    'subject_id' => $subjectId,

                    'subject_name' =>
                        $examSubject->subject?->subject_name
                        ?? $examSubject->subject?->name
                        ?? 'Subject ' . $subjectId,

                    'max_marks' => $maximumMarks,
                    'internal_marks' => $internalMarks,
                    'theory_marks' => $theoryMarks,
                    'practical_marks' => $practicalMarks,
                    'total_marks' => $maximumMarks,
                    'obtained_marks' => $obtainedMarks,
                    'grade' => $subjectGrade,
                    'grade_point' => $subjectGradePoint,
                ];
            }

            if (empty($detailRows) || $totalMaximum <= 0) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'No applicable subject marks found for student ' .
                        ($student->student_id ?? $student->id) . '.',
                ]);
            }

            $percentage = round(
                ($totalObtained / $totalMaximum) * 100,
                2
            );

            $grade = $this->calculateGrade($percentage);

            $resultStatus = $studentPassed ? 'Pass' : 'Fail';

            /*
             * Do not overwrite results that have already entered
             * the verification, approval or publication workflow.
             */
            $result = Result::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();

            if (
                $result &&
                $result->publication_status !== 'generated'
            ) {
                throw ValidationException::withMessages([
                    'student_id' =>
                        'The result for student ' .
                        ($student->student_id ?? $student->id) .
                        ' has already entered the verification or publication workflow. ' .
                        'It cannot be regenerated.',
                ]);
            }

            /*
             * Create or update the main result.
             */
            $result = Result::updateOrCreate(
                [
                    'exam_id' => $exam->id,
                    'student_id' => $student->id,
                ],
                [
                    'academic_year' => $exam->academic_year,
                    'class_name' => $schoolClass->class_name,
                    'section' => $section,

                    'total_marks' => $totalMaximum,
                    'obtained_marks' => $totalObtained,
                    'percentage' => $percentage,
                    'grade' => $grade,
                    'result_status' => $resultStatus,

                    'publication_status' => 'generated',
                    'published_at' => null,
                    'published_by' => null,

                    'generated_at' => now(),
                ]
            );

            /*
             * Replace old subject details when regenerating.
             */
            $result->details()->delete();

            foreach ($detailRows as $detail) {
                $result->details()->create($detail);
            }

            $generatedCount++;
        }
    });

    return redirect()
        ->route('admin.results.index')
        ->with(
            'success',
            $generatedCount .
            ' student result(s) generated successfully. Results are awaiting verification.'
        );
}


}
