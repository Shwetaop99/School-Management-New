<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Transport\TransportRecord;
use App\Exports\StudentTravelReportExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class TransportReportController extends Controller
{
    /**
     * Main Transport Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Student Search API
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_search')) {

            $search = trim((string) $request->student_search);

            $students = Student::query()
                ->where(function ($query) use ($search) {

                    $query
                        ->where('student_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw(
                            "CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?",
                            ["%{$search}%"]
                        );
                })
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('last_name')
                ->limit(10)
                ->get([
                    'id',
                    'student_id',
                    'first_name',
                    'middle_name',
                    'last_name',
                ]);

            return response()->json([
                'success' => true,

                'students' => $students->map(function ($student) {

                    $fullName = trim(
                        collect([
                            $student->first_name,
                            $student->middle_name,
                            $student->last_name,
                        ])
                            ->filter(fn ($value) =>
                                $value !== null &&
                                trim((string) $value) !== ''
                            )
                            ->implode(' ')
                    );

                    return [
                        'id' => $student->id,

                        'student_id' =>
                            $student->student_id
                            ?? $student->id,

                        'name' => $fullName,
                    ];
                })->values(),

                'count' => $students->count(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Transport Query
        |--------------------------------------------------------------------------
        */

        $query = TransportRecord::with('student')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Student Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('student_id')) {

            $query->where(
                'student_id',
                $request->student_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Transport Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('transport_status')) {

            $query->where(
                'transport_status',
                $request->transport_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Transport Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('transport_type')) {

            $query->where(
                'transport_type',
                $request->transport_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'start_date',
                '>=',
                $request->from_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $query->whereDate(
                'start_date',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Records
        |--------------------------------------------------------------------------
        */

        $records = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Selected Student
        |--------------------------------------------------------------------------
        */

        $selectedStudent = null;

        if ($request->filled('student_id')) {

            $selectedStudent = Student::find(
                $request->student_id
            );
        }

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
                [
                    'pending',
                    'partially_paid',
                ]
            )
            ->count();

        return view(
            'allReports.transportReport.index',
            compact(
                'records',
                'selectedStudent',
                'totalRecords',
                'activeRecords',
                'inactiveRecords',
                'assignedStudents',
                'totalTransportFees',
                'pendingPayments'
            )
        );
    }


    /**
     * Student Travel Report
     */
    public function studentTravel()
    {
        $records = TransportRecord::with('student')
            ->latest()
            ->get();

        return view(
            'allReports.transportReport.student-travel',
            compact('records')
        );
    }


    /**
     * Student Travel PDF
     */
    public function studentTravelPdf()
    {
        $records = TransportRecord::with('student')
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'allReports.transportReport.student-travel-pdf',
            compact('records')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'student-travel-report.pdf'
        );
    }


    /**
     * Student Travel Excel
     */
    public function studentTravelExcel()
    {
        return Excel::download(
            new StudentTravelReportExport,
            'student-travel-report.xlsx'
        );
    }


    /**
     * Vehicle Report
     */
    public function vehicle()
    {
        $vehicles = TransportRecord::query()
            ->selectRaw('
                COALESCE(vehicle, "Not Assigned") as vehicle,
                COALESCE(transport_type, "other") as transport_type,
                COUNT(*) as assigned_students,

                SUM(
                    CASE
                        WHEN transport_status = "active"
                        THEN 1
                        ELSE 0
                    END
                ) as active_students,

                SUM(
                    CASE
                        WHEN transport_status = "inactive"
                        THEN 1
                        ELSE 0
                    END
                ) as inactive_students,

                COALESCE(
                    SUM(transport_fee),
                    0
                ) as total_fees
            ')
            ->groupBy(
                'vehicle',
                'transport_type'
            )
            ->orderBy('vehicle')
            ->get();

        return view(
            'allReports.transportReport.vehicle',
            compact('vehicles')
        );
    }


    /**
     * Vehicle PDF
     */
    public function vehiclePdf()
    {
        $vehicles = TransportRecord::query()
            ->selectRaw('
                COALESCE(vehicle, "Not Assigned") as vehicle,
                COALESCE(transport_type, "other") as transport_type,
                COUNT(*) as assigned_students,

                SUM(
                    CASE
                        WHEN transport_status = "active"
                        THEN 1
                        ELSE 0
                    END
                ) as active_students,

                SUM(
                    CASE
                        WHEN transport_status = "inactive"
                        THEN 1
                        ELSE 0
                    END
                ) as inactive_students,

                COALESCE(
                    SUM(transport_fee),
                    0
                ) as total_fees
            ')
            ->groupBy(
                'vehicle',
                'transport_type'
            )
            ->orderBy('vehicle')
            ->get();

        $pdf = Pdf::loadView(
            'allReports.transportReport.vehicle-pdf',
            compact('vehicles')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'vehicle-report.pdf'
        );
    }


    /**
     * Vehicle Excel
     */
    public function vehicleExcel()
    {
        return Excel::download(
            new \App\Exports\VehicleReportExport,
            'vehicle-report.xlsx'
        );
    }
}