<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
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
}
