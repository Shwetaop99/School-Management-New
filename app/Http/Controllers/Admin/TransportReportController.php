<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportRecord;
use Illuminate\Http\Request;

class TransportReportController extends Controller
{
    /**
     * Transport Management Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = TransportRecord::with('student')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        // Student
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        // Transport Status
        if ($request->filled('transport_status')) {
            $query->where(
                'transport_status',
                $request->transport_status
            );
        }

        // Transport Type
        if ($request->filled('transport_type')) {
            $query->where(
                'transport_type',
                $request->transport_type
            );
        }

        // Payment Status
        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        // From Date
        if ($request->filled('from_date')) {
            $query->whereDate(
                'start_date',
                '>=',
                $request->from_date
            );
        }

        // To Date
        if ($request->filled('to_date')) {
            $query->whereDate(
                'start_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filtered Records
        |--------------------------------------------------------------------------
        */

        $records = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Students for Filter Dropdown
        |--------------------------------------------------------------------------
        */

        $students = TransportRecord::with('student')
            ->whereNotNull('student_id')
            ->get()
            ->pluck('student')
            ->filter()
            ->unique('id')
            ->sortBy('first_name')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalRecords = $records->count();

        $activeRecords = $records
            ->where('transport_status', 'active')
            ->count();

        $inactiveRecords = $records
            ->where('transport_status', 'inactive')
            ->count();

        $assignedStudents = $records
            ->whereNotNull('student_id')
            ->count();

        $totalTransportFees = $records->sum(
            'transport_fee'
        );

        $pendingPayments = $records
            ->whereIn(
                'payment_status',
                ['pending', 'partially_paid']
            )
            ->count();

        return view(
            'allReports.transportReport.index',
            compact(
                'records',
                'students',
                'totalRecords',
                'activeRecords',
                'inactiveRecords',
                'assignedStudents',
                'totalTransportFees',
                'pendingPayments'
            )
        );
    }
}