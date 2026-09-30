<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\StaffReportExport;

class StaffReportController extends Controller
{
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

        // Designations for dropdown
        $designations = OtherStaff::query()
            ->whereNotNull('designation')
            ->where('designation', '!=', '')
            ->distinct()
            ->orderBy('designation')
            ->pluck('designation');

        // Departments for dropdown
        $departments = OtherStaff::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        // Genders for dropdown
        $genders = OtherStaff::query()
            ->whereNotNull('gender')
            ->where('gender', '!=', '')
            ->distinct()
            ->orderBy('gender')
            ->pluck('gender');

        return view(
            'allReports.staffReport.index',
            compact(
                'staff',
                'designations',
                'departments',
                'genders'
            )
        );
    }
    public function show(OtherStaff $otherStaff)
{
    return view(
        'allReports.staffReport.show',
        compact('otherStaff')
    );
}
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
}