<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\SchoolSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FeeReportController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentFee::with([
            'student',
            'feeStructure',
            'payments',
        ]);

        // Search student details
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Academic year
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        // Fee status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Due date range
        if ($request->filled('from_date')) {
            $query->whereDate('due_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('due_date', '<=', $request->to_date);
        }

        $totalAssigned = (clone $query)->sum('total_amount');
        $totalPaid = (clone $query)->sum('paid_amount');
        $totalBalance = (clone $query)->sum('balance_amount');

        $fees = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $academicYears = StudentFee::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('allReports.feeReport.index', compact(
            'fees',
            'academicYears',
            'totalAssigned',
            'totalPaid',
            'totalBalance'
        ));
    }

    /**
     * Download the filtered fee report as PDF.
     */
    public function pdf(Request $request)
    {
        $query = StudentFee::with([
            'student',
            'feeStructure',
            'payments',
        ]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('due_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('due_date', '<=', $request->input('to_date'));
        }

        $fees = $query->latest('id')->get();

        $totalAssigned = $fees->sum('total_amount');
        $totalPaid = $fees->sum('paid_amount');
        $totalBalance = $fees->sum('balance_amount');

        $schoolSetting = SchoolSetting::query()->first();

        $pdf = Pdf::loadView('allReports.feeReport.pdf', [
            'fees' => $fees,
            'totalAssigned' => $totalAssigned,
            'totalPaid' => $totalPaid,
            'totalBalance' => $totalBalance,
            'generatedAt' => now(),
            'academicYear' => $request->input('academic_year'),
            'selectedSearch' => $request->input('search'),
            'selectedStatus' => $request->input('status'),
            'fromDate' => $request->input('from_date'),
            'toDate' => $request->input('to_date'),
            'schoolSetting' => $schoolSetting,
            'schoolName' => trim((string) ($schoolSetting->school_name ?? '')) ?: 'School Management System',
            'schoolLogo' => $schoolSetting->logo_url ?? null,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('fee-report-' . now()->format('Y-m-d') . '.pdf');
    }
}
