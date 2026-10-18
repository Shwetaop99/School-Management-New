<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExamReportExport;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExamReportController extends Controller
{
    /**
     * Display exam reports.
     */
    public function index(Request $request)
    {
        $totalExams = Exam::count();

        $completedExams = Exam::where('status', 'completed')->count();

        $upcomingExams = Exam::whereDate('start_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->count();

        $academicYears = Exam::query()
            ->whereNotNull('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $examTypes = Exam::query()
            ->whereNotNull('exam_type')
            ->distinct()
            ->orderBy('exam_type')
            ->pluck('exam_type');

        $exams = $this->filteredExams($request)
            ->paginate(10)
            ->withQueryString();

        return view('allReports.examReport.exam', compact(
            'totalExams',
            'completedExams',
            'upcomingExams',
            'academicYears',
            'examTypes',
            'exams'
        ));
    }

    /**
     * Download the filtered exam report as PDF.
     */
    public function pdf(Request $request)
    {
        $exams = $this->filteredExams($request)->get();

        $pdf = Pdf::loadView('allReports.examReport.pdf', [
            'exams' => $exams,
            'generatedAt' => now(),
            'academicYear' => $request->input('academic_year'),
            'totalExams' => $exams->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('exam-report-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Download the filtered exam report as Excel.
     */
    public function excel(Request $request)
    {
        $exams = $this->filteredExams($request)->get();

        return Excel::download(
            new ExamReportExport($exams),
            'exam-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Build the common filtered exam query for the page and exports.
     */
    private function filteredExams(Request $request)
    {
        $query = Exam::query()
            ->withCount('examSubjects')
            ->withSum(
                'examSubjects as total_maximum_marks',
                'maximum_marks'
            );

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('exam_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->input('academic_year')
            );
        }

        if ($request->filled('exam_type')) {
            $query->where(
                'exam_type',
                $request->input('exam_type')
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->input('status')
            );
        }

        return $query
            ->orderByDesc('start_date')
            ->orderByDesc('id');
    }
}
