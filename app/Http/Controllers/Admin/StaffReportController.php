<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Models\SchoolSetting;
use App\Models\Transport\TransportVehicle;
use App\Models\Transport\TransportRoute;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StaffReportExport;
use App\Exports\StaffProfileReportExport;

class StaffReportController extends Controller
{
    /**
     * Staff Reports Listing
     */
    public function index(Request $request)
    {
        $query = OtherStaff::query();

        // Staff Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        // Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Designation
        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        // Department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Designations dropdown
        $designations = OtherStaff::query()
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->distinct()
            ->orderBy('designation')
            ->pluck('designation');

        // Departments dropdown
        $departments = OtherStaff::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Genders dropdown
        $genders = OtherStaff::query()
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->distinct()
            ->orderBy('gender')
            ->pluck('gender');

        return view(
            'allReports.staffReport.index',
            compact('staff', 'designations', 'departments', 'genders')
        );
    }

    /**
     * Individual Other Staff Report
     */
    public function show(OtherStaff $otherStaff)
    {
        $schoolSetting = SchoolSetting::query()->first();

        $assignedVehicles = collect();
        $assignedRoutes = collect();

        // Load transport assignments only for Drivers
        if (strcasecmp(trim($otherStaff->designation ?? ''), 'Driver') === 0) {

            $assignedVehicles = TransportVehicle::query()
                ->where('driver_id', $otherStaff->id)
                ->orderBy('vehicle_number')
                ->get();

            $assignedRoutes = TransportRoute::query()
                ->with('vehicle')
                ->where('driver_id', $otherStaff->id)
                ->orderBy('route_name')
                ->get();
        }

        return view(
            'allReports.staffReport.show',
            compact(
                'otherStaff',
                'schoolSetting',
                'assignedVehicles',
                'assignedRoutes'
            )
        );
    }

    /**
     * Staff List PDF
     */
    public function pdf(Request $request)
    {
        $query = OtherStaff::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query->orderBy('name')->get();

        return Pdf::loadView(
            'allReports.staffReport.pdf',
            compact('staff')
        )
            ->setPaper('a4', 'landscape')
            ->download('staff-report.pdf');
    }

    /**
     * Staff List Excel
     */
    public function excel(Request $request)
    {
        $query = OtherStaff::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('staff_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $staff = $query->orderBy('name')->get();

        return Excel::download(
            new StaffReportExport($staff),
            'staff-report.xlsx'
        );
    }

    /**
     * Individual Staff Excel Download
     */
    public function profileExcel(OtherStaff $otherStaff)
    {
        $assignedVehicles = collect();
        $assignedRoutes = collect();

        if (strcasecmp(trim($otherStaff->designation ?? ''), 'Driver') === 0) {

            $assignedVehicles = TransportVehicle::query()
                ->where('driver_id', $otherStaff->id)
                ->orderBy('vehicle_number')
                ->get();

            $assignedRoutes = TransportRoute::query()
                ->with('vehicle')
                ->where('driver_id', $otherStaff->id)
                ->orderBy('route_name')
                ->get();
        }

        return Excel::download(
            new StaffProfileReportExport(
                $otherStaff,
                $assignedVehicles,
                $assignedRoutes
            ),
            'staff-profile-' . $otherStaff->id . '.xlsx'
        );
    }

    /**
     * Individual Staff PDF Download
     */
    public function profilePdf(OtherStaff $otherStaff)
    {
        $schoolSetting = SchoolSetting::query()->first();

        $assignedVehicles = collect();
        $assignedRoutes = collect();

        if (strcasecmp(trim($otherStaff->designation ?? ''), 'Driver') === 0) {

            $assignedVehicles = TransportVehicle::query()
                ->where('driver_id', $otherStaff->id)
                ->orderBy('vehicle_number')
                ->get();

            $assignedRoutes = TransportRoute::query()
                ->with('vehicle')
                ->where('driver_id', $otherStaff->id)
                ->orderBy('route_name')
                ->get();
        }

        return Pdf::loadView(
            'allReports.staffReport.profile-pdf',
            compact(
                'otherStaff',
                'schoolSetting',
                'assignedVehicles',
                'assignedRoutes'
            )
        )
            ->setPaper('a4', 'portrait')
            ->download('staff-profile-' . $otherStaff->id . '.pdf');
    }
}
