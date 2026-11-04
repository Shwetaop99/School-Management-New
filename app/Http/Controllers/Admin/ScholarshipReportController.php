<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship\ScholarshipExam;
use App\Models\Scholarship\ScholarshipExamPassedStudent;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ScholarshipReportController extends Controller
{
    public function index(Request $request)
    {
        // Apply report filters consistently.
        $applyFilters = function ($query) use ($request) {
            if ($request->filled('academic_year')) {
                $query->where('academic_year', $request->academic_year);
            }

            if ($request->filled('search')) {
                $search = trim($request->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('exam_name', 'like', "%{$search}%")
                        ->orWhere('exam_type', 'like', "%{$search}%")
                        ->orWhere('conducted_by', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', (int) $request->status);
            }
        };

        // Main report table.
        $examsQuery = ScholarshipExam::query();
        $applyFilters($examsQuery);

        $exams = $examsQuery
            ->withCount([
                'applications as applications_count',
                'passedStudents as passed_students_count',
                'passedStudents as scholarships_awarded_count' => function ($query) {
                    $query->where('scholarship_received', true);
                },
            ])
            ->withSum([
                'passedStudents as scholarship_amount_sum' => function ($query) {
                    $query->where('scholarship_received', true);
                },
            ], 'scholarship_amount')
            ->orderByDesc('exam_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Summary statistics use the same filters.
        $statisticsQuery = ScholarshipExam::query();
        $applyFilters($statisticsQuery);

        $awardQuery = ScholarshipExamPassedStudent::query()
            ->where('scholarship_received', true)
            ->whereHas('exam', function ($query) use ($applyFilters) {
                $applyFilters($query);
            });

        $statistics = [
            'total_exams' => (clone $statisticsQuery)->count(),
            'total_eligible' => (clone $statisticsQuery)->sum('total_eligible'),
            'total_applied' => (clone $statisticsQuery)->sum('total_applied'),
            'total_appeared' => (clone $statisticsQuery)->sum('total_appeared'),
            'total_passed' => (clone $statisticsQuery)->sum('total_passed'),
            'total_scholarships' => (clone $awardQuery)->count(),
            'total_scholarship_amount' => (clone $awardQuery)->sum('scholarship_amount'),
        ];

        // Academic-year dropdown.
        $academicYears = ScholarshipExam::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('allReports.scholarshipReport.index', compact(
            'exams',
            'academicYears',
            'statistics'
        ));
    }

    /**
     * Merit Concession student report.
     */
    public function meritConcession(Request $request)
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

        $query = DB::table('results')
            ->join('exams', 'exams.id', '=', 'results.exam_id')
            ->join('students', 'students.id', '=', 'results.student_id')
            ->whereIn('exams.id', (clone $annualExams)->select('id'))
            ->where('results.total_marks', '>', 0)
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
            ->selectRaw('COALESCE(results.obtained_marks, 0) AS obtained_marks')
            ->selectRaw('COALESCE(results.total_marks, 0) AS total_marks');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('students.first_name', 'like', "%{$search}%")
                    ->orWhere('students.middle_name', 'like', "%{$search}%")
                    ->orWhere('students.last_name', 'like', "%{$search}%")
                    ->orWhere('students.student_id', 'like', "%{$search}%")
                    ->orWhere('students.roll_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('exams.academic_year', $request->input('academic_year'));
        }

        if ($request->filled('class')) {
            $query->where('students.class', $request->input('class'));
        }

        if ($request->filled('section')) {
            $query->where('students.section', $request->input('section'));
        }

        $allRows = $query->get();

        $allRows->each(function ($row) {
            $row->percentage = $row->total_marks > 0
                ? round(($row->obtained_marks / $row->total_marks) * 100, 2)
                : 0;

            $percentage = (float) $row->percentage;
            $row->scholarship_percentage = $percentage >= 80 ? ($percentage >= 90 ? 10 : 35)
                : ($percentage >= 60 ? 10 : 0);
            $row->scholarship_eligible = $row->scholarship_percentage > 0;
        });

        if ($request->filled('min_percentage')) {
            $allRows = $allRows->filter(
                fn ($row) => $row->percentage >= (float) $request->input('min_percentage')
            );
        }

        if ($request->filled('max_percentage')) {
            $allRows = $allRows->filter(
                fn ($row) => $row->percentage <= (float) $request->input('max_percentage')
            );
        }

        $allRows = $allRows->sortByDesc('percentage')->values();
        $totalStudents = $allRows->count();
        $eligibleStudents = $allRows->where('percentage', '>=', 60)->count();
        $scholarshipAssigned = $eligibleStudents;
        $notEligible = $allRows->where('percentage', '<', 60)->count();

        $classes = Student::query()
            ->whereNotNull('class')->where('class', '!=', '')
            ->distinct()->orderBy('class')->pluck('class');

        $sections = Student::query()
            ->whereNotNull('section')->where('section', '!=', '')
            ->distinct()->orderBy('section')->pluck('section');

        $page = max(1, (int) $request->input('page', 1));
        $results = new \Illuminate\Pagination\LengthAwarePaginator(
            $allRows->slice(($page - 1) * 10, 10)->values(),
            $allRows->count(),
            10,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('allReports.scholarshipReport.merit-concession', compact(
            'results', 'academicYears', 'classes', 'sections',
            'totalStudents', 'eligibleStudents', 'scholarshipAssigned', 'notEligible'
        ));
    }

    /**
     * Scholarship examination report.
     */
    public function exams(Request $request)
    {
        $applyFilters = function ($query) use ($request) {
            if ($request->filled('academic_year')) {
                $query->where('academic_year', $request->input('academic_year'));
            }

            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $query->where(function ($q) use ($search) {
                    $q->where('exam_name', 'like', "%{$search}%")
                        ->orWhere('exam_type', 'like', "%{$search}%")
                        ->orWhere('conducted_by', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', (int) $request->input('status'));
            }
        };

        $examsQuery = ScholarshipExam::query();
        $applyFilters($examsQuery);

        $exams = $examsQuery
            ->withCount([
                'applications as applications_count',
                'passedStudents as passed_students_count',
                'passedStudents as scholarships_awarded_count' => function ($query) {
                    $query->where('scholarship_received', true);
                },
            ])
            ->withSum([
                'passedStudents as scholarship_amount_sum' => function ($query) {
                    $query->where('scholarship_received', true);
                },
            ], 'scholarship_amount')
            ->orderByDesc('exam_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $statisticsQuery = ScholarshipExam::query();
        $applyFilters($statisticsQuery);

        $awardQuery = ScholarshipExamPassedStudent::query()
            ->where('scholarship_received', true)
            ->whereHas('exam', function ($query) use ($applyFilters) {
                $applyFilters($query);
            });

        $statistics = [
            'total_exams' => (clone $statisticsQuery)->count(),
            'total_eligible' => (clone $statisticsQuery)->sum('total_eligible'),
            'total_applied' => (clone $statisticsQuery)->sum('total_applied'),
            'total_appeared' => (clone $statisticsQuery)->sum('total_appeared'),
            'total_passed' => (clone $statisticsQuery)->sum('total_passed'),
            'total_scholarships' => (clone $awardQuery)->count(),
            'total_scholarship_amount' => (clone $awardQuery)->sum('scholarship_amount'),
        ];

        $academicYears = ScholarshipExam::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('allReports.scholarshipReport.exams', compact(
            'exams', 'academicYears', 'statistics'
        ));
    }
}
