<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResultReportController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = DB::table('results')
            ->join('students', 'results.student_id', '=', 'students.id')
            ->join('exams', 'results.exam_id', '=', 'exams.id');

        // Apply filters
        $baseQuery
            ->when($request->filled('exam_id'), function ($query) use ($request) {
                $query->where('results.exam_id', $request->exam_id);
            })
            ->when($request->filled('academic_year'), function ($query) use ($request) {
                $query->where('exams.academic_year', $request->academic_year);
            })
            ->when($request->filled('class'), function ($query) use ($request) {
                $query->where('students.class', $request->class);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('students.first_name', 'like', "%{$search}%")
                        ->orWhere('students.middle_name', 'like', "%{$search}%")
                        ->orWhere('students.last_name', 'like', "%{$search}%")
                        ->orWhere('students.roll_number', 'like', "%{$search}%")
                        ->orWhere('students.student_id', 'like', "%{$search}%");
                });
            });

        // Summary statistics use the same filters as the report table.
        $summary = (clone $baseQuery)
            ->selectRaw('COUNT(*) as total_records')
            ->selectRaw('COUNT(DISTINCT results.student_id) as total_students')
            ->selectRaw('COALESCE(SUM(results.marks_obtained), 0) as total_obtained')
            ->selectRaw('AVG(results.marks_obtained) as average_marks')
            ->first();

        $results = (clone $baseQuery)
            ->select([
                'results.id',
                'results.exam_id',
                'results.exam_subject_id',
                'results.marks_obtained',
                'results.grade',
                'results.remarks',
                'students.student_id as student_code',
                'students.roll_number',
                'students.first_name',
                'students.middle_name',
                'students.last_name',
                'students.class',
                'students.section',
                'exams.exam_name',
                'exams.academic_year',
                'exams.exam_type',
            ])
            ->orderBy('exams.exam_name')
            ->orderBy('students.roll_number')
            ->paginate(15)
            ->withQueryString();

        $exams = DB::table('exams')
            ->select('id', 'exam_name', 'academic_year')
            ->orderByDesc('start_date')
            ->get();

        $academicYears = DB::table('exams')
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $classes = DB::table('students')
            ->whereNotNull('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        // Find the subject name when exam_subjects has a subject_id.
        
// Load subject names safely using the actual subjects table columns.
$subjectNames = [];

if (
    Schema::hasColumn('exam_subjects', 'subject_id')
    && Schema::hasTable('subjects')
) {
    $subjectColumn = collect([
        'subject_name',
        'name',
        'title',
    ])->first(
        fn ($column) => Schema::hasColumn('subjects', $column)
    );

    if ($subjectColumn) {
        $subjectNames = DB::table('exam_subjects')
            ->leftJoin(
                'subjects',
                'exam_subjects.subject_id',
                '=',
                'subjects.id'
            )
            ->select(
                'exam_subjects.id',
                "subjects.{$subjectColumn} as subject_name"
            )
            ->get()
            ->keyBy('id');
    }
}


        return view('allReports.resultReport.index', compact(
            'results',
            'summary',
            'exams',
            'academicYears',
            'classes',
            'subjectNames'
        ));
    }
}
