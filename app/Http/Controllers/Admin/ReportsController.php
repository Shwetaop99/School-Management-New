<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $teachers = Teacher::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('allReports.index', compact('teachers'));
    }

    /**
     * Display filtered payroll reports.
     */
    public function payroll(Request $request)
    {
        $validated = $request->validate([
            'salary_month' => ['nullable', 'date_format:Y-m'],
            'payment_status' => ['nullable', 'in:Paid,Pending'],
        ]);

        $query = TeacherSalary::with('teacher');

        if (!empty($validated['salary_month'])) {
            $query->where(
                'salary_month',
                'like',
                $validated['salary_month'] . '%'
            );
        }

        if (!empty($validated['payment_status'])) {
            $query->where(
                'payment_status',
                $validated['payment_status']
            );
        }

        $summary = (clone $query)
            ->selectRaw('
                COUNT(*) as total_records,
                COALESCE(SUM(basic_salary), 0) as basic_total,
                COALESCE(SUM(allowances), 0) as allowances_total,
                COALESCE(SUM(gross_salary), 0) as gross_total,
                COALESCE(SUM(deductions), 0) as deductions_total,
                COALESCE(SUM(net_salary), 0) as net_total
            ')
            ->first();

        $salaries = (clone $query)
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'allReports.payroll.index',
            compact('salaries', 'summary', 'validated')
        );
    }

    /**
     * Download the filtered payroll report directly as a PDF.
     */
    public function payrollPdf(Request $request)
    {
        $validated = $request->validate([
            'salary_month' => ['nullable', 'date_format:Y-m'],
            'payment_status' => ['nullable', 'in:Paid,Pending'],
        ]);

        $query = TeacherSalary::with('teacher');

        if (!empty($validated['salary_month'])) {
            $query->where(
                'salary_month',
                'like',
                $validated['salary_month'] . '%'
            );
        }

        if (!empty($validated['payment_status'])) {
            $query->where(
                'payment_status',
                $validated['payment_status']
            );
        }

        $summary = (clone $query)
            ->selectRaw('
                COUNT(*) as total_records,
                COALESCE(SUM(basic_salary), 0) as basic_total,
                COALESCE(SUM(allowances), 0) as allowances_total,
                COALESCE(SUM(gross_salary), 0) as gross_total,
                COALESCE(SUM(deductions), 0) as deductions_total,
                COALESCE(SUM(net_salary), 0) as net_total
            ')
            ->first();

        $salaries = (clone $query)
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->get();

        // Fetch school name, address, and logo from school settings.
        $schoolSetting = SchoolSetting::first();

        $pdf = Pdf::loadView(
            'allReports.payroll.pdf',
            compact(
                'salaries',
                'summary',
                'validated',
                'schoolSetting'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'payroll-report-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }
}
