<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | RESULT INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $exams = Exam::orderByDesc('id')->get();

        $results = Result::with([
            'exam',
            'student',
            'examSubject.subject',
            'examSubject.schoolClass',
        ])
            ->when($request->exam_id, function ($query) use ($request) {
                $query->where('exam_id', $request->exam_id);
            })
            ->when($request->class_id, function ($query) use ($request) {
                $query->whereHas('examSubject', function ($q) use ($request) {
                    $q->where('class_id', $request->class_id);
                });
            })
            ->orderBy('student_id')
            ->orderBy('exam_subject_id')
            ->get();

        $classIds = ExamSubject::query()
            ->when($request->exam_id, function ($query) use ($request) {
                $query->where('exam_id', $request->exam_id);
            })
            ->where('status', 1)
            ->pluck('class_id')
            ->unique()
            ->values();

        $classes = DB::table('school_classes')
            ->whereIn('id', $classIds)
            ->orderBy('class_name')
            ->get();

        return view('admin.results.index', compact(
            'exams',
            'classes',
            'results'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE RESULT FORM
    |--------------------------------------------------------------------------
    */

    public function generate(Request $request)
    {
        $exams = Exam::orderByDesc('id')->get();

        $selectedExam = null;
        $classes = collect();

        if ($request->filled('exam_id')) {

            $selectedExam = Exam::findOrFail(
                $request->exam_id
            );

            $classIds = ExamSubject::where(
                'exam_id',
                $selectedExam->id
            )
                ->where('status', 1)
                ->pluck('class_id')
                ->unique()
                ->values();

            $classes = DB::table('school_classes')
                ->whereIn('id', $classIds)
                ->orderBy('class_name')
                ->get();
        }

        return view('admin.results.generate', compact(
            'exams',
            'selectedExam',
            'classes'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | MARKS ENTRY PAGE
    |--------------------------------------------------------------------------
    */

    public function marks(Request $request)
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
            ],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $examSubjects = ExamSubject::with([
            'subject',
            'schoolClass',
        ])
            ->where('exam_id', $exam->id)
            ->where('class_id', $request->class_id)
            ->where('status', 1)
            ->get();

        if ($examSubjects->isEmpty()) {
            return redirect()
                ->route('admin.results.generate', [
                    'exam_id' => $exam->id,
                ])
                ->with(
                    'error',
                    'No subjects are configured for this exam and class.'
                );
        }

        $schoolClass = DB::table('school_classes')
            ->where('id', $request->class_id)
            ->first();

        if (!$schoolClass) {
            return back()
                ->with('error', 'Selected class was not found.');
        }

        $className = trim(
            str_ireplace(
                'class',
                '',
                $schoolClass->class_name
            )
        );

        $students = Student::query()
            ->whereNull('deleted_at')
            ->where('status', 'active')
            ->where('academic_year', $exam->academic_year)
            ->where(function ($query) use ($schoolClass, $className) {

                $query->where(
                    'class',
                    $schoolClass->class_name
                );

                $query->orWhere(
                    'class',
                    $className
                );
            })
            ->orderByRaw('CAST(roll_number AS UNSIGNED)')
            ->orderBy('first_name')
            ->get();

        $existingResults = Result::where(
            'exam_id',
            $exam->id
        )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->whereIn(
                'exam_subject_id',
                $examSubjects->pluck('id')
            )
            ->get()
            ->keyBy(function ($result) {
                return $result->student_id .
                    '_' .
                    $result->exam_subject_id;
            });

        return view('admin.results.marks', compact(
            'exam',
            'schoolClass',
            'examSubjects',
            'students',
            'existingResults'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE MARKS
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
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
            ],

            'marks' => [
                'required',
                'array',
            ],

            'marks.*' => [
                'array',
            ],
        ]);

        $exam = Exam::findOrFail($request->exam_id);

        $examSubjects = ExamSubject::where(
            'exam_id',
            $exam->id
        )
            ->where(
                'class_id',
                $request->class_id
            )
            ->where('status', 1)
            ->get()
            ->keyBy('id');

        DB::transaction(function () use (
            $request,
            $exam,
            $examSubjects
        ) {

            foreach ($request->marks as $studentId => $subjects) {

                $student = Student::whereNull('deleted_at')
                    ->find($studentId);

                if (!$student) {
                    continue;
                }

                foreach ($subjects as $examSubjectId => $marksObtained) {

                    if (!isset($examSubjects[$examSubjectId])) {
                        continue;
                    }

                    $examSubject = $examSubjects[$examSubjectId];

                    if (
                        $marksObtained === null ||
                        $marksObtained === ''
                    ) {
                        Result::updateOrCreate(
                            [
                                'exam_id' => $exam->id,
                                'student_id' => $student->id,
                                'exam_subject_id' => $examSubject->id,
                            ],
                            [
                                'marks_obtained' => null,
                                'grade' => null,
                                'remarks' => null,
                            ]
                        );

                        continue;
                    }

                    $marks = (float) $marksObtained;

                    if ($marks < 0) {
                        $marks = 0;
                    }

                    if ($marks > $examSubject->maximum_marks) {
                        $marks = $examSubject->maximum_marks;
                    }

                    $percentage = 0;

                    if ($examSubject->maximum_marks > 0) {
                        $percentage =
                            ($marks / $examSubject->maximum_marks) * 100;
                    }

                    $grade = $this->calculateGrade(
                        $percentage
                    );

                    $remarks = $marks >= $examSubject->passing_marks
                        ? 'Pass'
                        : 'Fail';

                    Result::updateOrCreate(
                        [
                            'exam_id' => $exam->id,
                            'student_id' => $student->id,
                            'exam_subject_id' => $examSubject->id,
                        ],
                        [
                            'marks_obtained' => $marks,
                            'grade' => $grade,
                            'remarks' => $remarks,
                        ]
                    );
                }
            }
        });

        return redirect()
            ->route('admin.results.index', [
                'exam_id' => $exam->id,
            ])
            ->with(
                'success',
                'Student marks saved successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | GRADE CALCULATION
    |--------------------------------------------------------------------------
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
            return 'C+';
        }

        if ($percentage >= 40) {
            return 'C';
        }

        if ($percentage >= 35) {
            return 'D';
        }

        return 'F';
    }
}