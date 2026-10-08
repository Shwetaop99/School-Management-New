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
use App\Models\Class\Subject;
use App\Models\ResultWhatsappNotification;
use App\Services\WhatsAppService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

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
            ->when(
                $request->filled('exam_id'),
                function ($query) use ($request) {
                    $query->where(
                        'exam_id',
                        $request->exam_id
                    );
                }
            )
            ->when(
                $request->filled('student_id'),
                function ($query) use ($request) {
                    $query->whereHas(
                        'student',
                        function ($studentQuery) use ($request) {
                            $studentQuery->where(
                                'student_id',
                                'like',
                                '%' . $request->student_id . '%'
                            );
                        }
                    );
                }
            )
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.results.index',
            compact(
                'exams',
                'results'
            )
        );
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

        return view(
            'admin.results.generate',
            compact(
                'exams',
                'selectedExam'
            )
        );
    }

    /**
     * Load classes assigned to selected examination.
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

        $exam = Exam::findOrFail(
            $validated['exam_id']
        );

        $examClasses = ExamClass::with('schoolClass')
            ->where(
                'exam_id',
                $exam->id
            )
            ->get()
            ->filter(function ($examClass) {
                return $examClass->schoolClass !== null;
            })
            ->values();

        $classes = $examClasses
            ->map(function ($examClass) {
                return [
                    'id' =>
                        $examClass->class_id,

                    'name' =>
                        $examClass->schoolClass->class_name,

                    'section' =>
                        $examClass->schoolClass->section,

                    'academic_year' =>
                        $examClass->schoolClass->academic_year,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,

            'exam' => [
                'id' =>
                    $exam->id,

                'exam_name' =>
                    $exam->exam_name,

                'academic_year' =>
                    $exam->academic_year,
            ],

            'classes' =>
                $classes,
        ]);
    }

    /**
     * Get classes assigned to examination with result statistics.
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

        $examId =
            $validated['exam_id'];

        $exam =
            Exam::findOrFail(
                $examId
            );

        $examClasses =
            ExamClass::with('schoolClass')
                ->where(
                    'exam_id',
                    $examId
                )
                ->get();

        $classes = $examClasses
            ->filter(function ($examClass) {
                return $examClass->schoolClass !== null;
            })
            ->map(function ($examClass) use (
                $examId,
                $exam
            ) {

                $schoolClass =
                    $examClass->schoolClass;

                $studentClassValues =
                    $this->getStudentClassValues(
                        $schoolClass->class_name
                    );

                $studentQuery =
                    Student::query()
                        ->where(
                            function ($query) use (
                                $studentClassValues
                            ) {

                                foreach (
                                    $studentClassValues
                                    as $index => $classValue
                                ) {

                                    if ($index === 0) {

                                        $query->where(
                                            'class',
                                            $classValue
                                        );

                                    } else {

                                        $query->orWhere(
                                            'class',
                                            $classValue
                                        );
                                    }
                                }
                            }
                        );

                if (
                    !empty(
                        $schoolClass->section
                    )
                ) {

                    $studentQuery->where(
                        'section',
                        trim(
                            $schoolClass->section
                        )
                    );
                }

                $academicYears =
                    array_values(
                        array_unique(
                            array_filter([
                                trim(
                                    (string)
                                    $schoolClass->academic_year
                                ),

                                trim(
                                    (string)
                                    $exam->academic_year
                                ),
                            ])
                        )
                    );

                if (
                    !empty($academicYears)
                ) {

                    $studentQuery->whereIn(
                        'academic_year',
                        $academicYears
                    );
                }

                $studentQuery->where(
                    function ($statusQuery) {

                        $statusQuery
                            ->where(
                                'status',
                                'active'
                            )
                            ->orWhere(
                                'status',
                                1
                            );
                    }
                );

                $studentIds =
                    $studentQuery->pluck('id');

                $studentCount =
                    $studentIds->count();

                $resultQuery =
                    Result::query()
                        ->where(
                            'exam_id',
                            $examId
                        )
                        ->whereIn(
                            'student_id',
                            $studentIds
                        );

                $generatedCount =
                    (clone $resultQuery)
                        ->where(
                            'publication_status',
                            'generated'
                        )
                        ->count();

                $verifiedCount =
                    (clone $resultQuery)
                        ->where(
                            'publication_status',
                            'verified'
                        )
                        ->count();

                $approvedCount =
                    (clone $resultQuery)
                        ->where(
                            'publication_status',
                            'approved'
                        )
                        ->count();

                $publishedCount =
                    (clone $resultQuery)
                        ->where(
                            'publication_status',
                            'published'
                        )
                        ->count();

                return [
                    'exam_class_id' =>
                        $examClass->id,

                    'exam_id' =>
                        $examId,

                    'class_id' =>
                        $schoolClass->id,

                    'class_name' =>
                        $schoolClass->class_name,

                    'section' =>
                        $schoolClass->section,

                    'academic_year' =>
                        $schoolClass->academic_year
                        ?? $exam->academic_year
                        ?? null,

                    'student_count' =>
                        $studentCount,

                    'generated_count' =>
                        $generatedCount,

                    'verified_count' =>
                        $verifiedCount,

                    'approved_count' =>
                        $approvedCount,

                    'published_count' =>
                        $publishedCount,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,

            'exam' => [
                'id' =>
                    $exam->id,

                'exam_name' =>
                    $exam->exam_name,

                'academic_year' =>
                    $exam->academic_year,
            ],

            'classes' =>
                $classes,
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        $examClass =
            ExamClass::where(
                'exam_id',
                $exam->id
            )
                ->where(
                    'class_id',
                    $schoolClass->id
                )
                ->first();

        if (!$examClass) {

            return response()->json([
                'success' => false,

                'message' =>
                    'This class is not assigned to the selected exam.',

                'students' => [],
            ], 422);
        }

        $section =
            !empty($validated['section'])
                ? trim($validated['section'])
                : trim(
                    (string)
                    $schoolClass->section
                );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        $students->each(
            function ($student) {

                $student->full_name =
                    collect([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name,
                    ])
                        ->filter(
                            function ($value) {
                                return filled($value);
                            }
                        )
                        ->implode(' ');
            }
        );

        return response()->json([
            'success' => true,

            'exam' => [
                'id' =>
                    $exam->id,

                'name' =>
                    $exam->exam_name,

                'academic_year' =>
                    $exam->academic_year,
            ],

            'class' => [
                'id' =>
                    $schoolClass->id,

                'name' =>
                    $schoolClass->class_name,

                'section' =>
                    $section,
            ],

            'students' =>
                $students,
        ]);
    }

    /**
     * Show Enter Marks page.
     */
    public function marks(Request $request)
    {
        $exams = Exam::query()
            ->orderByDesc('id')
            ->get();

        if (
            !$request->filled('exam_id')
        ) {

            return view(
                'admin.results.marks',
                [
                    'exams' =>
                        $exams,

                    'exam' =>
                        null,

                    'examClasses' =>
                        collect(),

                    'examClass' =>
                        null,

                    'examSubject' =>
                        null,

                    'students' =>
                        collect(),

                    'section' =>
                        null,

                    'sections' =>
                        collect(),

                    'selectionMode' =>
                        true,
                ]
            );
        }

        $exam =
            Exam::findOrFail(
                $request->exam_id
            );

        $examClasses =
            ExamClass::with('schoolClass')
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->get()
                ->filter(
                    function ($examClass) {
                        return $examClass->schoolClass !== null;
                    }
                )
                ->values();

        $selectedClassId =
            $request->class_id;

        $sections =
            collect();

        if ($selectedClassId) {

            $selectedExamClass =
                $examClasses->firstWhere(
                    'class_id',
                    (int) $selectedClassId
                );

            if (
                $selectedExamClass &&
                $selectedExamClass->schoolClass
            ) {

                $className =
                    $selectedExamClass
                        ->schoolClass
                        ->class_name;

                $sections =
                    SchoolClass::query()
                        ->where(
                            'class_name',
                            $className
                        )
                        ->where(
                            function ($query) use ($exam) {

                                $query
                                    ->where(
                                        'academic_year',
                                        $exam->academic_year
                                    )
                                    ->orWhereNull(
                                        'academic_year'
                                    );
                            }
                        )
                        ->where(
                            'status',
                            true
                        )
                        ->whereNotNull(
                            'section'
                        )
                        ->where(
                            'section',
                            '!=',
                            ''
                        )
                        ->orderBy(
                            'section'
                        )
                        ->pluck(
                            'section'
                        )
                        ->unique()
                        ->values();
            }
        }

        if (
            !$request->filled('class_id') ||
            !$request->filled('section') ||
            !$request->filled('subject_id')
        ) {

            return view(
                'admin.results.marks',
                [
                    'exams' =>
                        $exams,

                    'exam' =>
                        $exam,

                    'examClasses' =>
                        $examClasses,

                    'examClass' =>
                        null,

                    'examSubject' =>
                        null,

                    'students' =>
                        collect(),

                    'section' =>
                        $request->section,

                    'sections' =>
                        $sections,

                    'selectionMode' =>
                        true,
                ]
            );
        }

        $validated =
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

        $examClass =
            ExamClass::with('schoolClass')
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'class_id',
                    $validated['class_id']
                )
                ->firstOrFail();

        $sectionExists =
            SchoolClass::query()
                ->where(
                    'id',
                    $validated['class_id']
                )
                ->where(
                    'class_name',
                    $examClass->schoolClass->class_name
                )
                ->where(
                    'section',
                    $validated['section']
                )
                ->where(
                    'status',
                    true
                )
                ->exists();

        if (!$sectionExists) {

            abort(
                404,
                'Selected section does not belong to the selected class.'
            );
        }

        $examSubject =
            ExamSubject::with('subject')
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'class_id',
                    $validated['class_id']
                )
                ->where(
                    'subject_id',
                    $validated['subject_id']
                )
                ->where(
                    'status',
                    true
                )
                ->firstOrFail();

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $examClass->schoolClass,
                $validated['section']
            );

        $students->each(
            function ($student) {

                $student->full_name =
                    collect([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name,
                    ])
                        ->filter(
                            function ($value) {
                                return filled($value);
                            }
                        )
                        ->implode(' ');
            }
        );

        $studentIds =
            $students->pluck('id');

        $existingMarks =
            ExamMark::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'exam_class_id',
                    $examClass->id
                )
                ->where(
                    'subject_id',
                    $examSubject->subject_id
                )
                ->whereIn(
                    'student_id',
                    $studentIds
                )
                ->get()
                ->keyBy(
                    'student_id'
                );

        $students->each(
            function ($student) use (
                $existingMarks
            ) {

                $student->examMark =
                    $existingMarks->get(
                        $student->id
                    );
            }
        );

        return view(
            'admin.results.marks',
            [
                'exams' =>
                    $exams,

                'exam' =>
                    $exam,

                'examClasses' =>
                    $examClasses,

                'examClass' =>
                    $examClass,

                'examSubject' =>
                    $examSubject,

                'students' =>
                    $students,

                'section' =>
                    $validated['section'],

                'sections' =>
                    $sections,

                'selectionMode' =>
                    false,
            ]
        );
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

        $exam =
            Exam::findOrFail(
                $request->exam_id
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $request->class_id
            );

        $examClass =
            ExamClass::where(
                'exam_id',
                $exam->id
            )
                ->where(
                    'class_id',
                    $schoolClass->id
                )
                ->first();

        if (!$examClass) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This class is not assigned to the selected examination.'
                );
        }

        $examSubject =
            ExamSubject::where(
                'exam_id',
                $exam->id
            )
                ->where(
                    'class_id',
                    $schoolClass->id
                )
                ->where(
                    'subject_id',
                    $request->subject_id
                )
                ->where(
                    'status',
                    true
                )
                ->first();

        if (!$examSubject) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'This subject is not assigned to the selected examination and class.'
                );
        }

        $maximumMarks =
            (float)
            $examSubject->maximum_marks;

        $section =
            $request->filled('section')
                ? trim($request->section)
                : trim(
                    (string)
                    $schoolClass->section
                );

        $submittedStudentIds =
            array_keys(
                $request->input(
                    'marks',
                    []
                )
            );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section,
                $submittedStudentIds
            );

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

        DB::transaction(
            function () use (
                $request,
                $students,
                $exam,
                $examClass,
                $examSubject,
                $maximumMarks
            ) {

                foreach (
                    $students
                    as $student
                ) {

                    $studentMarks =
                        $request->input(
                            'marks.' .
                            $student->id,
                            []
                        );

                    $status =
                        $studentMarks['status']
                        ?? 'present';

                    if (
                        $status !== 'present'
                    ) {

                        $internalMarks = 0;
                        $theoryMarks = 0;
                        $practicalMarks = 0;
                        $totalMarks = 0;

                    } else {

                        $internalMarks =
                            (float) (
                                $studentMarks[
                                    'internal_marks'
                                ]
                                ?? 0
                            );

                        $theoryMarks =
                            (float) (
                                $studentMarks[
                                    'theory_marks'
                                ]
                                ?? 0
                            );

                        $practicalMarks =
                            (float) (
                                $studentMarks[
                                    'practical_marks'
                                ]
                                ?? 0
                            );

                        $totalMarks =
                            $internalMarks +
                            $theoryMarks +
                            $practicalMarks;

                        if (
                            $totalMarks >
                            $maximumMarks
                        ) {

                            throw ValidationException::withMessages([
                                "marks.{$student->id}.internal_marks" =>
                                    "Total marks for {$student->student_id} cannot exceed {$maximumMarks}.",
                            ]);
                        }
                    }

                    ExamMark::updateOrCreate(
                        [
                            'exam_id' =>
                                $exam->id,

                            'student_id' =>
                                $student->id,

                            'subject_id' =>
                                $examSubject->subject_id,
                        ],
                        [
                            'exam_class_id' =>
                                $examClass->id,

                            'internal_marks' =>
                                $internalMarks,

                            'theory_marks' =>
                                $theoryMarks,

                            'practical_marks' =>
                                $practicalMarks,

                            'max_marks' =>
                                $maximumMarks,

                            'total_marks' =>
                                $totalMarks,

                            'status' =>
                                $status,

                            'remarks' =>
                                $studentMarks['remarks']
                                ?? null,
                        ]
                    );
                }
            }
        );

        return redirect()
            ->route(
                'admin.results.marks',
                [
                    'exam_id' =>
                        $exam->id,

                    'class_id' =>
                        $schoolClass->id,

                    'section' =>
                        $section,

                    'subject_id' =>
                        $examSubject->subject_id,
                ]
            )
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        $examClass =
            ExamClass::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'class_id',
                    $schoolClass->id
                )
                ->first();

        if (!$examClass) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Selected class is not assigned to this exam.',

                'subjects' => [],
            ], 422);
        }

        $examSubjects =
            ExamSubject::with('subject')
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'class_id',
                    $schoolClass->id
                )
                ->where(
                    'status',
                    true
                )
                ->orderBy('id')
                ->get();

        $subjects =
            $examSubjects
                ->map(
                    function ($examSubject) {

                        $subject =
                            $examSubject->subject;

                        return [
                            'id' =>
                                (int)
                                $examSubject->subject_id,

                            'name' =>
                                $subject
                                ? $subject->subject_name
                                : 'Unknown Subject',

                            'code' =>
                                $subject
                                ? $subject->subject_code
                                : null,

                            'maximum_marks' =>
                                $examSubject->maximum_marks,

                            'passing_marks' =>
                                $examSubject->passing_marks,

                            'duration_minutes' =>
                                $examSubject->duration_minutes,
                        ];
                    }
                )
                ->values()
                ->toArray();

        return response()->json([
            'success' => true,

            'exam' => [
                'id' =>
                    (int)
                    $exam->id,

                'name' =>
                    $exam->exam_name,

                'academic_year' =>
                    $exam->academic_year,
            ],

            'class' => [
                'id' =>
                    (int)
                    $schoolClass->id,

                'name' =>
                    $schoolClass->class_name,

                'section' =>
                    $schoolClass->section,
            ],

            'subjects' =>
                $subjects,
        ]);
    }

    /**
     * Generate student results.
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        $examClassQuery =
            ExamClass::where(
                'exam_id',
                $exam->id
            )
                ->where(
                    'class_id',
                    $schoolClass->id
                );

        if (
            !empty(
                $validated['exam_class_id']
            )
        ) {

            $examClassQuery->where(
                'id',
                $validated['exam_class_id']
            );
        }

        $examClass =
            $examClassQuery->first();

        if (!$examClass) {

            return back()->with(
                'error',
                'This class/section is not assigned to the selected examination.'
            );
        }

        $section =
            !empty($validated['section'])
                ? trim(
                    $validated['section']
                )
                : trim(
                    (string)
                    $schoolClass->section
                );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        if (
            $students->isEmpty()
        ) {

            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $examMarks =
            ExamMark::with('subject')
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->where(
                    'exam_class_id',
                    $examClass->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->groupBy(
                    'student_id'
                );

        if (
            $examMarks->isEmpty()
        ) {

            return back()->with(
                'error',
                'No marks have been entered for this examination.'
            );
        }

        DB::transaction(
            function () use (
                $students,
                $examMarks,
                $exam,
                $schoolClass,
                $section
            ) {

                foreach (
                    $students
                    as $student
                ) {

                    $studentMarks =
                        $examMarks->get(
                            $student->id,
                            collect()
                        );

                    if (
                        $studentMarks->isEmpty()
                    ) {
                        continue;
                    }

                    $totalMaximumMarks = 0;
                    $totalObtainedMarks = 0;

                    $hasFailedSubject = false;
                    $hasAbsentSubject = false;

                    foreach (
                        $studentMarks
                        as $mark
                    ) {

                        $maximumMarks =
                            (float)
                            $mark->max_marks;

                        $obtainedMarks =
                            (float)
                            $mark->total_marks;

                        $totalMaximumMarks +=
                            $maximumMarks;

                        $totalObtainedMarks +=
                            $obtainedMarks;

                        if (
                            $mark->status ===
                            'absent'
                        ) {

                            $hasAbsentSubject = true;
                        }

                        $examSubject =
                            ExamSubject::query()
                                ->where(
                                    'exam_id',
                                    $exam->id
                                )
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
                            $mark->status ===
                                'present' &&
                            $examSubject &&
                            $obtainedMarks <
                                (float)
                                $examSubject->passing_marks
                        ) {

                            $hasFailedSubject = true;
                        }
                    }

                    $percentage =
                        $totalMaximumMarks > 0
                            ? (
                                $totalObtainedMarks /
                                $totalMaximumMarks
                            ) * 100
                            : 0;

                    $percentage =
                        round(
                            $percentage,
                            2
                        );

                    $grade =
                        $this->calculateGrade(
                            $percentage
                        );

                    if (
                        $hasFailedSubject
                    ) {

                        $resultStatus =
                            'fail';

                    } elseif (
                        $hasAbsentSubject
                    ) {

                        $resultStatus =
                            'absent';

                    } else {

                        $resultStatus =
                            'pass';
                    }

                    /*
                     * --------------------------------------------------
                     * Create / update result
                     * --------------------------------------------------
                     */
                    $result =
                        Result::updateOrCreate(
                            [
                                'student_id' =>
                                    $student->id,

                                'exam_id' =>
                                    $exam->id,
                            ],
                            [
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

                                /*
                                 * Every new generation starts
                                 * the publication workflow again.
                                 */
                                'publication_status' =>
                                    'generated',

                                'published_at' =>
                                    null,

                                'published_by' =>
                                    null,

                                'generated_at' =>
                                    now(),

                                'remarks' =>
                                    null,
                            ]
                        );

                    /*
                     * --------------------------------------------------
                     * Result Version
                     * --------------------------------------------------
                     */
                    $lastVersion =
                        ResultVersion::where(
                            'result_id',
                            $result->id
                        )->max(
                            'version_number'
                        );

                    $nextVersion =
                        ((int)
                            $lastVersion) + 1;

                    $resultVersion =
                        ResultVersion::create([
                            'result_id' =>
                                $result->id,

                            'version_number' =>
                                $nextVersion,

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
                     * --------------------------------------------------
                     * Result Version Details
                     * --------------------------------------------------
                     */
                    foreach (
                        $studentMarks
                        as $mark
                    ) {

                        $subjectName =
                            $mark->subject
                                ? $mark->subject->subject_name
                                : 'Unknown Subject';

                        $subjectMaximumMarks =
                            (float)
                            $mark->max_marks;

                        $subjectObtainedMarks =
                            (float)
                            $mark->total_marks;

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

                            /*
                             * Existing database structure:
                             * total_marks stores obtained total
                             * for ResultVersionDetail.
                             */
                            'total_marks' =>
                                $subjectObtainedMarks,

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
                     * --------------------------------------------------
                     * Replace current Result Details
                     * --------------------------------------------------
                     */
                    $result->details()->delete();

                    foreach (
                        $studentMarks
                        as $mark
                    ) {

                        $subjectName =
                            $mark->subject
                                ? $mark->subject->subject_name
                                : 'Unknown Subject';

                        $subjectMaximumMarks =
                            (float)
                            $mark->max_marks;

                        $subjectObtainedMarks =
                            (float)
                            $mark->total_marks;

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

                            /*
                             * IMPORTANT:
                             *
                             * total_marks =
                             * maximum marks
                             *
                             * obtained_marks =
                             * student's obtained marks
                             */
                            'total_marks' =>
                                $subjectMaximumMarks,

                            'obtained_marks' =>
                                $subjectObtainedMarks,

                            'internal_marks' =>
                                $mark->internal_marks,

                            'theory_marks' =>
                                $mark->theory_marks,

                            'practical_marks' =>
                                $mark->practical_marks,

                            'grade' =>
                                $subjectGrade,

                            'grade_point' =>
                                $this->calculateGradePoint(
                                    $subjectPercentage
                                ),
                        ]);
                    }
                }
            }
        );

        Log::info(
            'RESULT GENERATION COMPLETED',
            [
                'result_student_count' =>
                    $students->count(),

                'exam_id' =>
                    $exam->id,

                'class_id' =>
                    $schoolClass->id,

                'section' =>
                    $section,

                'redirect_route' =>
                    'admin.results.index',

                'redirect_url' =>
                    route(
                        'admin.results.index'
                    ),
            ]
        );

        return redirect()
            ->route(
                'admin.results.index'
            )
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
    public function history(
        Result $result
    ) {
        $result->load([
            'student',
            'exam',

            'versions' =>
                function ($query) {
                    $query->orderByDesc(
                        'version_number'
                    );
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
        if (
            $version->result_id !==
            $result->id
        ) {

            abort(404);
        }

        $version->load([
            'details' =>
                function ($query) {
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
    public function show(
        Result $result
    ) {
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
    public function print(
        Result $result
    ) {
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
    public function pdf(
        Result $result
    ) {
        $result->load([
            'student',
            'exam',
            'details',
        ]);

        $school =
            \App\Models\SchoolSetting::first();

        $pdf =
            Pdf::loadView(
                'admin.results.pdf',
                [
                    'result' =>
                        $result,

                    'school' =>
                        $school,
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

        return $pdf->download(
            $filename
        );
    }

    /**
     * Calculate grade from percentage.
     */
    private function calculateGrade(
        float $percentage
    ): string {

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
    public function classResults(
        Request $request
    ) {
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
        |--------------------------------------------------------------------------
        | Examination
        |--------------------------------------------------------------------------
        */

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        /*
        |--------------------------------------------------------------------------
        | School Class
        |--------------------------------------------------------------------------
        */

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        /*
        |--------------------------------------------------------------------------
        | Exam Class
        |--------------------------------------------------------------------------
        */

        $examClass =
            ExamClass::query()
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
                    $schoolClass->id
                )
                ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Section
        |--------------------------------------------------------------------------
        */

        $section =
            !empty($validated['section'])
                ? trim(
                    (string)
                    $validated['section']
                )
                : trim(
                    (string)
                    (
                        $schoolClass->section
                        ?? ''
                    )
                );

        /*
        |--------------------------------------------------------------------------
        | Students
        |--------------------------------------------------------------------------
        */

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        /*
        |--------------------------------------------------------------------------
        | Results
        |--------------------------------------------------------------------------
        */

        $results =
            Result::query()
                ->with([
                    'student',
                    'exam',
                    'details',
                ])
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->orderBy('id')
                ->get()
                ->keyBy(
                    'student_id'
                );

        /*
        |--------------------------------------------------------------------------
        | Build Student Result List
        |--------------------------------------------------------------------------
        */

        $studentResults =
            $students
                ->map(
                    function ($student) use (
                        $results
                    ) {

                        $result =
                            $results->get(
                                $student->id
                            );

                        $student->full_name =
                            collect([
                                $student->first_name,
                                $student->middle_name,
                                $student->last_name,
                            ])
                                ->filter(
                                    function ($value) {
                                        return filled($value);
                                    }
                                )
                                ->implode(' ');

                        $student->result =
                            $result;

                        return $student;
                    }
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalStudents =
            $studentResults->count();

        $generatedCount =
            $studentResults
                ->filter(
                    function ($student) {
                        return $student->result !== null;
                    }
                )
                ->count();

        $verifiedCount =
            $studentResults
                ->filter(
                    function ($student) {
                        return $student->result &&
                            $student->result
                                ->publication_status ===
                            'verified';
                    }
                )
                ->count();

        $approvedCount =
            $studentResults
                ->filter(
                    function ($student) {
                        return $student->result &&
                            $student->result
                                ->publication_status ===
                            'approved';
                    }
                )
                ->count();

        $publishedCount =
            $studentResults
                ->filter(
                    function ($student) {
                        return $student->result &&
                            $student->result
                                ->publication_status ===
                            'published';
                    }
                )
                ->count();

        $generatedResults =
            $studentResults
                ->filter(
                    function ($student) {
                        return $student->result &&
                            $student->result
                                ->publication_status ===
                            'generated';
                    }
                )
                ->count();

        return view(
            'admin.results.class-results',
            [
                'exam' =>
                    $exam,

                'examClass' =>
                    $examClass,

                'schoolClass' =>
                    $schoolClass,

                'section' =>
                    $section,

                'students' =>
                    $students,

                'studentResults' =>
                    $studentResults,

                'results' =>
                    $results,

                'totalStudents' =>
                    $totalStudents,

                'generatedCount' =>
                    $generatedCount,

                'generatedResults' =>
                    $generatedResults,

                'verifiedCount' =>
                    $verifiedCount,

                'approvedCount' =>
                    $approvedCount,

                'publishedCount' =>
                    $publishedCount,
            ]
        );
    }

    /**
     * Verify a generated result.
     */
    public function verify(
        Result $result
    ) {
        if (
            $result->publication_status !==
            'generated'
        ) {

            return back()->with(
                'error',
                'Only generated results can be verified.'
            );
        }

        $result->update([
            'publication_status' =>
                'verified',
        ]);

        return back()->with(
            'success',
            'Result verified successfully.'
        );
    }

    /**
     * Approve a verified result.
     */
    public function approve(
        Result $result
    ) {
        if (
            $result->publication_status !==
            'verified'
        ) {

            return back()->with(
                'error',
                'Only verified results can be approved.'
            );
        }

        $result->update([
            'publication_status' =>
                'approved',
        ]);

        return back()->with(
            'success',
            'Result approved successfully.'
        );
    }

    /**
     * Publish an approved result.
     *
     * WhatsApp sending remains paused.
     */
    public function publish(
        Result $result,
        WhatsAppService $whatsappService
    ) {
        if (
            $result->publication_status !==
            'approved'
        ) {

            return back()->with(
                'error',
                'Only approved results can be published.'
            );
        }

        $result->load([
            'student',
            'exam',
        ]);

        $student =
            $result->student;

        if (!$student) {

            return back()->with(
                'error',
                'Student record not found for this result.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Consistent parent phone priority
        |--------------------------------------------------------------------------
        */

        $phone =
            $this->getStudentWhatsAppPhone(
                $student
            );

        $result->update([
            'publication_status' =>
                'published',

            'published_at' =>
                now(),

            'published_by' =>
                Auth::id(),
        ]);

        $message =
            $this->buildResultWhatsAppMessage(
                $result
            );

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

        if (
            $phone === ''
        ) {

            $notification->update([
                'status' =>
                    'failed',

                'error_message' =>
                    'Parent mobile number is not registered.',
            ]);

            return back()->with(
                'warning',
                'Result published, but WhatsApp notification is currently paused because parent mobile number is not registered.'
            );
        }

        return back()->with(
            'success',
            'Result published successfully. WhatsApp notification is currently paused.'
        );
    }

    /**
     * Publish all approved results for a class and section.
     */
    public function publishClassResults(
        Request $request
    ) {
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        ExamClass::query()
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
                $schoolClass->id
            )
            ->firstOrFail();

        $section =
            !empty($validated['section'])
                ? trim(
                    $validated['section']
                )
                : trim(
                    (string)
                    $schoolClass->section
                );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        if (
            $students->isEmpty()
        ) {

            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $results =
            Result::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->keyBy(
                    'student_id'
                );

        $missingResults =
            $students->filter(
                function ($student) use (
                    $results
                ) {

                    return !$results->has(
                        $student->id
                    );
                }
            );

        if (
            $missingResults->isNotEmpty()
        ) {

            return back()->with(
                'error',
                $missingResults->count() .
                ' student(s) do not have generated results. Generate all results before publishing.'
            );
        }

        $notReadyResults =
            $results->filter(
                function ($result) {

                    return !in_array(
                        $result->publication_status,
                        [
                            'approved',
                            'published',
                        ],
                        true
                    );
                }
            );

        if (
            $notReadyResults->isNotEmpty()
        ) {

            $generatedCount =
                $notReadyResults
                    ->where(
                        'publication_status',
                        'generated'
                    )
                    ->count();

            $verifiedCount =
                $notReadyResults
                    ->where(
                        'publication_status',
                        'verified'
                    )
                    ->count();

            $messageParts = [];

            if (
                $generatedCount > 0
            ) {

                $messageParts[] =
                    $generatedCount .
                    ' result(s) still need verification.';
            }

            if (
                $verifiedCount > 0
            ) {

                $messageParts[] =
                    $verifiedCount .
                    ' result(s) still need approval.';
            }

            return back()->with(
                'error',
                implode(
                    ' ',
                    $messageParts
                ) .
                ' Please complete the verification and approval process for all students before publishing.'
            );
        }

        $approvedResults =
            $results->filter(
                function ($result) {

                    return $result->publication_status ===
                        'approved';
                }
            );

        if (
            $approvedResults->isEmpty()
        ) {

            return back()->with(
                'success',
                'All results for this class and section are already published.'
            );
        }

        $publishedCount = 0;

        DB::transaction(
            function () use (
                $approvedResults,
                &$publishedCount
            ) {

                foreach (
                    $approvedResults
                    as $result
                ) {

                    if (
                        $result->publication_status !==
                        'approved'
                    ) {
                        continue;
                    }

                    $result->update([
                        'publication_status' =>
                            'published',

                        'published_at' =>
                            now(),

                        'published_by' =>
                            Auth::id(),
                    ]);

                    $publishedCount++;
                }
            }
        );

        return back()->with(
            'success',
            $publishedCount .
            ' result(s) published successfully for ' .
            $schoolClass->class_name .
            (
                $section !== ''
                    ? ' - Section ' . $section
                    : ''
            ) .
            '. WhatsApp notification is currently paused.'
        );
    }

    /**
     * Build WhatsApp notification message.
     */
    protected function buildResultWhatsAppMessage(
        Result $result
    ): string {

        $student =
            $result->student;

        $studentName =
            collect([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ])
                ->filter(
                    function ($value) {
                        return filled($value);
                    }
                )
                ->implode(' ');

        if (
            $studentName === ''
        ) {

            $studentName =
                'your child';
        }

        $resultUrl =
            URL::route(
                'result.public'
            );

        $examName =
            $result->exam?->exam_name
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
            (
                $result->academic_year
                ?? 'N/A'
            ) .
            "\n\n" .
            "View your child's result online:\n" .
            $resultUrl .
            "\n\n" .
            "Student ID: " .
            (
                $student->student_id
                ?? 'N/A'
            ) .
            "\n\n" .
            "For verification, enter the Student ID and Mother's Name on the result page and complete the CAPTCHA.\n\n" .
            "Regards,\n" .
            "School Administration";
    }

    /**
     * Verify all generated results for a class and section.
     */
    public function verifyClassResults(
        Request $request
    ) {
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        ExamClass::query()
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
                $schoolClass->id
            )
            ->firstOrFail();

        $section =
            !empty($validated['section'])
                ? trim(
                    $validated['section']
                )
                : trim(
                    (string)
                    $schoolClass->section
                );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        if (
            $students->isEmpty()
        ) {

            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $results =
            Result::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->keyBy(
                    'student_id'
                );

        $missingResults =
            $students->filter(
                function ($student) use (
                    $results
                ) {

                    return !$results->has(
                        $student->id
                    );
                }
            );

        if (
            $missingResults->isNotEmpty()
        ) {

            return back()->with(
                'error',
                $missingResults->count() .
                ' student(s) do not have generated results. Generate all results before verification.'
            );
        }

        $generatedResults =
            $results->filter(
                function ($result) {

                    return $result->publication_status ===
                        'generated';
                }
            );

        if (
            $generatedResults->isEmpty()
        ) {

            return back()->with(
                'success',
                'All results for this class and section are already verified or processed.'
            );
        }

        $verifiedCount = 0;

        DB::transaction(
            function () use (
                $generatedResults,
                &$verifiedCount
            ) {

                foreach (
                    $generatedResults
                    as $result
                ) {

                    if (
                        $result->publication_status !==
                        'generated'
                    ) {
                        continue;
                    }

                    $result->update([
                        'publication_status' =>
                            'verified',
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
            (
                $section !== ''
                    ? ' - Section ' . $section
                    : ''
            ) .
            '.'
        );
    }

    /**
     * Approve all verified results for a class and section.
     */
    public function approveClassResults(
        Request $request
    ) {
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

        $exam =
            Exam::findOrFail(
                $validated['exam_id']
            );

        $schoolClass =
            SchoolClass::findOrFail(
                $validated['class_id']
            );

        ExamClass::query()
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
                $schoolClass->id
            )
            ->firstOrFail();

        $section =
            !empty($validated['section'])
                ? trim(
                    $validated['section']
                )
                : trim(
                    (string)
                    $schoolClass->section
                );

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $section
            );

        if (
            $students->isEmpty()
        ) {

            return back()->with(
                'error',
                'No active students found for the selected class and section.'
            );
        }

        $results =
            Result::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->keyBy(
                    'student_id'
                );

        $missingResults =
            $students->filter(
                function ($student) use (
                    $results
                ) {

                    return !$results->has(
                        $student->id
                    );
                }
            );

        if (
            $missingResults->isNotEmpty()
        ) {

            return back()->with(
                'error',
                $missingResults->count() .
                ' student(s) do not have generated results. Generate all results before approval.'
            );
        }

        $notReadyResults =
            $results->filter(
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

        if (
            $notReadyResults->isNotEmpty()
        ) {

            $generatedCount =
                $notReadyResults
                    ->where(
                        'publication_status',
                        'generated'
                    )
                    ->count();

            $messageParts = [];

            if (
                $generatedCount > 0
            ) {

                $messageParts[] =
                    $generatedCount .
                    ' result(s) still need verification.';
            }

            return back()->with(
                'error',
                implode(
                    ' ',
                    $messageParts
                ) .
                ' Please verify all results before approval.'
            );
        }

        $verifiedResults =
            $results->filter(
                function ($result) {

                    return $result->publication_status ===
                        'verified';
                }
            );

        if (
            $verifiedResults->isEmpty()
        ) {

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

                foreach (
                    $verifiedResults
                    as $result
                ) {

                    if (
                        $result->publication_status !==
                        'verified'
                    ) {
                        continue;
                    }

                    $result->update([
                        'publication_status' =>
                            'approved',
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
            (
                $section !== ''
                    ? ' - Section ' . $section
                    : ''
            ) .
            '.'
        );
    }

    /**
     * Open WhatsApp with pre-filled result message.
     */
    public function whatsapp(
        Result $result
    ) {
        if (
            $result->publication_status !==
            'published'
        ) {

            return back()->with(
                'error',
                'Only published results can be sent through WhatsApp.'
            );
        }

        $result->load([
            'student',
            'exam',
        ]);

        $student =
            $result->student;

        if (!$student) {

            return back()->with(
                'error',
                'Student record not found for this result.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Use same phone logic as Bulk WhatsApp.
        |--------------------------------------------------------------------------
        */

        $phone =
            $this->getStudentWhatsAppPhone(
                $student
            );

        if (
            $phone === ''
        ) {

            return back()->with(
                'warning',
                'Parent mobile number is not registered for this student.'
            );
        }

        $phone =
            $this->normalizeWhatsAppPhone(
                $phone
            );

        if (
            $phone === null
        ) {

            return back()->with(
                'error',
                'The registered parent mobile number is invalid.'
            );
        }

        $message =
            $this->buildResultWhatsAppMessage(
                $result
            );

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

        return redirect()->away(
            $whatsappUrl
        );
    }

    /**
     * Bulk WhatsApp result notification page.
     */
    public function bulkWhatsapp(
        Request $request
    ) {
        $examId =
            $request->input(
                'exam_id'
            );

        $examClassId =
            $request->input(
                'exam_class_id'
            );

        $classId =
            $request->input(
                'class_id'
            );

        $section =
            $request->input(
                'section'
            );

        if (
            !$examId ||
            !$classId
        ) {

            return redirect()
                ->route(
                    'admin.results.index'
                )
                ->with(
                    'error',
                    'Please select an exam and class before opening Bulk WhatsApp.'
                );
        }

        $exam =
            Exam::find(
                $examId
            );

        if (!$exam) {

            return redirect()
                ->route(
                    'admin.results.index'
                )
                ->with(
                    'error',
                    'Selected examination was not found.'
                );
        }

        $schoolClass =
            SchoolClass::find(
                $classId
            );

        if (!$schoolClass) {

            return redirect()
                ->route(
                    'admin.results.index'
                )
                ->with(
                    'error',
                    'Selected class was not found.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify exam class when supplied.
        |--------------------------------------------------------------------------
        */

        if (
            !empty($examClassId)
        ) {

            $examClassExists =
                ExamClass::query()
                    ->where(
                        'id',
                        $examClassId
                    )
                    ->where(
                        'exam_id',
                        $exam->id
                    )
                    ->where(
                        'class_id',
                        $schoolClass->id
                    )
                    ->exists();

            if (
                !$examClassExists
            ) {

                return redirect()
                    ->route(
                        'admin.results.index'
                    )
                    ->with(
                        'error',
                        'Selected examination class does not belong to this exam and class.'
                    );
            }
        }

        $selectedSection =
            !empty($section)
                ? trim($section)
                : trim(
                    (string)
                    $schoolClass->section
                );

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        |
        | getStudentsForExamClass() now loads the complete Student
        | record, including parent_phone, mother_phone, father_phone,
        | guardian_phone and phone.
        |--------------------------------------------------------------------------
        */

        $students =
            $this->getStudentsForExamClass(
                $exam,
                $schoolClass,
                $selectedSection
            );

        $results =
            Result::query()
                ->where(
                    'exam_id',
                    $exam->id
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get()
                ->keyBy(
                    'student_id'
                );

        /*
        |--------------------------------------------------------------------------
        | Add useful computed values to every student.
        |--------------------------------------------------------------------------
        */

        $students->each(
            function ($student) use (
                $results
            ) {

                $student->full_name =
                    collect([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name,
                    ])
                        ->filter(
                            function ($value) {
                                return filled($value);
                            }
                        )
                        ->implode(' ');

                $student->whatsapp_phone =
                    $this->getStudentWhatsAppPhone(
                        $student
                    );

                $student->has_whatsapp_phone =
                    $student->whatsapp_phone !== '';

                $student->result =
                    $results->get(
                        $student->id
                    );
            }
        );

        return view(
            'admin.results.bulk-whatsapp',
            [
                'students' =>
                    $students,

                'results' =>
                    $results,

                'exam' =>
                    $exam,

                'schoolClass' =>
                    $schoolClass,

                'examClassId' =>
                    $examClassId,

                'section' =>
                    $selectedSection,
            ]
        );
    }

    /**
     * Get parent/student WhatsApp phone.
     *
     * Priority:
     *
     * 1. mother_phone
     * 2. father_phone
     * 3. guardian_phone
     * 4. parent_phone
     * 5. phone
     */
    private function getStudentWhatsAppPhone(
        Student $student
    ): string {

        $phone =
            $student->mother_phone
            ?? $student->father_phone
            ?? $student->guardian_phone
            ?? $student->parent_phone
            ?? $student->phone
            ?? '';

        return trim(
            (string) $phone
        );
    }

    /**
     * Normalize phone number for WhatsApp.
     *
     * Supports:
     *
     * 9876543210
     * 09876543210
     * 919876543210
     * 0919876543210
     *
     * Returns null when invalid.
     */
    private function normalizeWhatsAppPhone(
        string $phone
    ): ?string {

        $phone =
            preg_replace(
                '/\D+/',
                '',
                trim($phone)
            );

        if (
            !$phone
        ) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | 091XXXXXXXXXX
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $phone,
                '091'
            ) &&
            strlen($phone) === 13
        ) {

            $phone =
                substr(
                    $phone,
                    1
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 0XXXXXXXXXX
        |--------------------------------------------------------------------------
        */

        if (
            strlen($phone) === 11 &&
            str_starts_with(
                $phone,
                '0'
            )
        ) {

            $phone =
                substr(
                    $phone,
                    1
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 10 digit Indian number
        |--------------------------------------------------------------------------
        */

        if (
            strlen($phone) === 10
        ) {

            $phone =
                '91' .
                $phone;
        }

        /*
        |--------------------------------------------------------------------------
        | Valid international number.
        |--------------------------------------------------------------------------
        */

        if (
            strlen($phone) < 10 ||
            strlen($phone) > 15
        ) {

            return null;
        }

        return $phone;
    }

    /**
     * Common student loader.
     *
     * IMPORTANT:
     *
     * We intentionally DO NOT restrict the selected columns here.
     *
     * The Bulk WhatsApp module needs parent contact fields such as:
     *
     * - mother_phone
     * - father_phone
     * - guardian_phone
     * - parent_phone
     * - phone
     *
     * Loading the complete Student model also keeps this method
     * compatible if your students table contains additional fields.
     */
    private function getStudentsForExamClass(
        Exam $exam,
        SchoolClass $schoolClass,
        ?string $section = null,
        ?array $studentIds = null
    ) {

        $classValues =
            $this->getStudentClassValues(
                $schoolClass->class_name
            );

        $query =
            Student::query()
                ->where(
                    function ($query) use (
                        $classValues
                    ) {

                        foreach (
                            $classValues
                            as $index => $classValue
                        ) {

                            if (
                                $index === 0
                            ) {

                                $query->where(
                                    'class',
                                    $classValue
                                );

                            } else {

                                $query->orWhere(
                                    'class',
                                    $classValue
                                );
                            }
                        }
                    }
                );

        /*
        |--------------------------------------------------------------------------
        | Section
        |--------------------------------------------------------------------------
        */

        $section =
            trim(
                (string) $section
            );

        if (
            $section !== ''
        ) {

            $query->where(
                'section',
                $section
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Academic year
        |--------------------------------------------------------------------------
        */

        $academicYears =
            array_values(
                array_unique(
                    array_filter([
                        trim(
                            (string)
                            $schoolClass->academic_year
                        ),

                        trim(
                            (string)
                            $exam->academic_year
                        ),
                    ])
                )
            );

        if (
            !empty($academicYears)
        ) {

            $query->whereIn(
                'academic_year',
                $academicYears
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active students
        |--------------------------------------------------------------------------
        */

        $query->where(
            function ($statusQuery) {

                $statusQuery
                    ->where(
                        'status',
                        'active'
                    )
                    ->orWhere(
                        'status',
                        1
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Optional submitted student IDs.
        |--------------------------------------------------------------------------
        */

        if (
            $studentIds !== null
        ) {

            $query->whereIn(
                'id',
                $studentIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Ordering
        |--------------------------------------------------------------------------
        */

        return $query
            ->orderByRaw(
                '
                CASE
                    WHEN roll_number IS NULL
                    OR roll_number = ""
                    THEN 1
                    ELSE 0
                END
                '
            )
            ->orderBy(
                'roll_number'
            )
            ->orderBy(
                'first_name'
            )
            ->orderBy(
                'middle_name'
            )
            ->orderBy(
                'last_name'
            )
            ->get();
    }

    /**
     * Get possible values stored in students.class.
     */
    private function getStudentClassValues(
        ?string $className
    ): array {

        $className =
            trim(
                (string) $className
            );

        $values = [];

        if (
            $className !== ''
        ) {

            $values[] =
                $className;
        }

        $classNumber =
            $className;

        if (
            preg_match(
                '/\d+/',
                $className,
                $matches
            )
        ) {

            $classNumber =
                $matches[0];
        }

        if (
            $classNumber !== ''
        ) {

            $values[] =
                trim(
                    (string)
                    $classNumber
                );
        }

        return array_values(
            array_unique(
                array_filter(
                    $values
                )
            )
        );
    }
}
