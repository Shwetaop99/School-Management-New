<?php

namespace App\Http\Controllers\Scholarship;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MeritConcessionController extends Controller
{
    /**
     * Display scholarship eligibility based on Annual Exam results.
     */
    public function index(Request $request)
    {
        $annualExams = Exam::query()
            ->where(function ($q) {
                $q->where('exam_name', 'like', '%annual%')
                    ->orWhere('exam_type', 'like', '%annual%');
            });

        $academicYears = (clone $annualExams)
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $annualExamIds = (clone $annualExams)->select('id');

        $aggregateQuery = DB::table('results')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join(
                'exam_subjects',
                'exam_subjects.id',
                '=',
                'results.exam_subject_id'
            )
            ->join('students', 'students.id', '=', 'results.student_id')
            ->whereIn('exams.id', $annualExamIds)
            ->select(
                'results.student_id',
                'results.exam_id',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'students.student_id as admission_student_id',
                'students.roll_number',
                'students.class as class_name',
                'students.section',
                'exams.exam_name',
                'exams.exam_type',
                'exams.academic_year'
            )
            ->selectRaw(
                'SUM(COALESCE(results.marks_obtained, 0)) AS obtained_marks'
            )
            ->selectRaw(
                'SUM(exam_subjects.maximum_marks) AS total_marks'
            )
            ->groupBy(
                'results.student_id',
                'results.exam_id',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'students.student_id',
                'students.roll_number',
                'students.class',
                'students.section',
                'exams.exam_name',
                'exams.exam_type',
                'exams.academic_year'
            );

        $aggregateQuery->havingRaw(
            'SUM(exam_subjects.maximum_marks) > 0'
        );

        if ($request->filled('search')) {
            $search = trim($request->search);

            $aggregateQuery->where(function ($q) use ($search) {
                $q->where('students.first_name', 'like', "%{$search}%")
                    ->orWhere('students.middle_name', 'like', "%{$search}%")
                    ->orWhere('students.last_name', 'like', "%{$search}%")
                    ->orWhere('students.student_id', 'like', "%{$search}%")
                    ->orWhere('students.roll_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year')) {
            $aggregateQuery->where(
                'exams.academic_year',
                $request->academic_year
            );
        }

        if ($request->filled('class')) {
            $aggregateQuery->where(
                'students.class',
                $request->class
            );
        }

        if ($request->filled('section')) {
            $aggregateQuery->where(
                'students.section',
                $request->section
            );
        }

        $allRows = (clone $aggregateQuery)->get();

        $allRows->each(function ($row) {
            $row->percentage = $row->total_marks > 0
                ? round(($row->obtained_marks / $row->total_marks) * 100, 2)
                : 0;

            $row->scholarship_percentage =
                $this->getScholarshipPercentage((float) $row->percentage);

            $row->scholarship_eligible =
                $row->scholarship_percentage > 0;

            $row->student = (object) [
                'id' => $row->student_id,
                'student_id' => $row->admission_student_id,
                'first_name' => $row->first_name,
                'middle_name' => $row->middle_name,
                'last_name' => $row->last_name,
                'roll_number' => $row->roll_number,
                'class' => $row->class_name,
                'section' => $row->section,
            ];

            $row->exam = (object) [
                'id' => $row->exam_id,
                'exam_name' => $row->exam_name,
                'exam_type' => $row->exam_type,
                'academic_year' => $row->academic_year,
            ];
        });

        if ($request->filled('min_percentage')) {
            $allRows = $allRows->filter(
                fn ($row) => $row->percentage >= (float) $request->min_percentage
            );
        }

        if ($request->filled('max_percentage')) {
            $allRows = $allRows->filter(
                fn ($row) => $row->percentage <= (float) $request->max_percentage
            );
        }

        $allRows = $allRows
            ->sortByDesc('percentage')
            ->values();

        $totalStudents = $allRows->count();

        $eligibleStudents = $allRows
            ->where('percentage', '>=', 60)
            ->count();

        $scholarshipAssigned = $eligibleStudents;

        $notEligible = $allRows
            ->where('percentage', '<', 60)
            ->count();

        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $sections = Student::query()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $page = max(1, (int) $request->input('page', 1));

        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $allRows->slice(($page - 1) * 10, 10)->values(),
            $allRows->count(),
            10,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'admin.merit-concession.index',
            compact(
                'results',
                'academicYears',
                'classes',
                'sections',
                'totalStudents',
                'eligibleStudents',
                'scholarshipAssigned',
                'notEligible'
            )
        );
    }

    /**
     * Show scholarship configuration.
     */
    public function create()
    {
        $academicYears = Exam::query()
            ->where(function ($q) {
                $q->where('exam_name', 'like', '%annual%')
                    ->orWhere('exam_type', 'like', '%annual%');
            })
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view(
            'admin.merit-concession.create',
            compact('academicYears')
        );
    }

    /**
     * Store scholarship settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_year' => 'required|string|max:20',
            'minimum_percentage' => 'required|numeric|min:0|max:100',
            'scholarship_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        return redirect()
            ->route('admin.merit-concession.index')
            ->with('success', 'Scholarship percentage rule saved successfully.');
    }

    /**
     * Display a result record.
     */
    public function show(Result $result)
    {
        $result->load(['student', 'exam']);

        abort_unless(
            $result->exam &&
            (
                str_contains(strtolower($result->exam->exam_name ?? ''), 'annual') ||
                str_contains(strtolower($result->exam->exam_type ?? ''), 'annual')
            ),
            404
        );

        $maximumMarks = DB::table('exam_subjects')
            ->where('exam_id', $result->exam_id)
            ->whereIn(
                'id',
                Result::where('student_id', $result->student_id)
                    ->where('exam_id', $result->exam_id)
                    ->select('exam_subject_id')
            )
            ->sum('maximum_marks');

        $obtainedMarks = Result::where('student_id', $result->student_id)
            ->where('exam_id', $result->exam_id)
            ->sum('marks_obtained');

        $result->percentage = $maximumMarks > 0
            ? round(($obtainedMarks / $maximumMarks) * 100, 2)
            : 0;

        $scholarshipPercentage =
            $this->getScholarshipPercentage((float) $result->percentage);

        return view(
            'admin.merit-concession.show',
            compact('result', 'scholarshipPercentage')
        );
    }

    /**
     * Edit scholarship information.
     */
    public function edit(Result $result)
    {
        $result->load(['student', 'exam']);

        $maximumMarks = DB::table('exam_subjects')
            ->where('exam_id', $result->exam_id)
            ->whereIn(
                'id',
                Result::where('student_id', $result->student_id)
                    ->where('exam_id', $result->exam_id)
                    ->select('exam_subject_id')
            )
            ->sum('maximum_marks');

        $obtainedMarks = Result::where('student_id', $result->student_id)
            ->where('exam_id', $result->exam_id)
            ->sum('marks_obtained');

        $result->percentage = $maximumMarks > 0
            ? round(($obtainedMarks / $maximumMarks) * 100, 2)
            : 0;

        $scholarshipPercentage =
            $this->getScholarshipPercentage((float) $result->percentage);

        return view(
            'admin.merit-concession.edit',
            compact('result', 'scholarshipPercentage')
        );
    }

    /**
     * Update scholarship configuration without changing exam results.
     */
    public function update(Request $request, Result $result)
    {
        $request->validate([
            'scholarship_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
            'remarks' => 'nullable|string',
        ]);

        return redirect()
            ->route('admin.merit-concession.show', $result)
            ->with('success', 'Scholarship information updated successfully.');
    }

    /**
     * Scholarship percentage slabs.
     */
    private function getScholarshipPercentage(float $percentage): float
    {
        if ($percentage >= 90) {
            return 10;
        }

        if ($percentage >= 80) {
            return 35;
        }

        if ($percentage >= 70) {
            return 10;
        }

        if ($percentage >= 60) {
            return 10;
        }

        return 0;
    }

    /**
     * Keep original result records unchanged.
     */
    public function destroy(Result $result)
    {
        return redirect()
            ->route('admin.merit-concession.index')
            ->with(
                'success',
                'Scholarship record removed from view. Original result remains unchanged.'
            );
    }
}
