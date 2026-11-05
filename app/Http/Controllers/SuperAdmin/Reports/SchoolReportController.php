<?php

namespace App\Http\Controllers\SuperAdmin\Reports;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\Subscription;
use App\Exports\SchoolReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SchoolReportController extends Controller
{
    /**
     * Display the school report.
     */
    public function index(Request $request)
    {
        $schools = $this->filteredSchools($request)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // School statistics
        $totalSchools = School::count();

        $activeSchools = School::where('status', 'active')->count();

        $inactiveSchools = School::where('status', 'inactive')->count();

        $totalUsers = User::whereHas('school')
            ->whereDoesntHave('role', function ($query) {
                $query->where('name', 'super_admin');
            })
            ->count();

        // Subscription statistics
        $totalSubscriptions = Subscription::count();

        $activeSubscriptions = Subscription::where(
            'status', 'active'
        )->count();

        $trialSubscriptions = Subscription::where(
            'type', 'trial'
        )->count();

        $paidSubscriptions = Subscription::where(
            'type', 'paid'
        )->count();

        // Filter dropdown values
        $schoolTypes = School::whereNotNull('school_type')
            ->distinct()
            ->orderBy('school_type')
            ->pluck('school_type');

        $educationBoards = School::whereNotNull('education_board')
            ->distinct()
            ->orderBy('education_board')
            ->pluck('education_board');

        return view(
            'super-admin.reports.schools.index',
            compact(
                'schools',
                'totalSchools',
                'activeSchools',
                'inactiveSchools',
                'totalUsers',
                'totalSubscriptions',
                'activeSubscriptions',
                'trialSubscriptions',
                'paidSubscriptions',
                'schoolTypes',
                'educationBoards'
            )
        );
    }

    /**
     * Export school report as Excel.
     */
    public function exportExcel(Request $request)
    {
        $schools = $this->filteredSchools($request)
            ->latest()
            ->get();

        return Excel::download(
            new SchoolReportExport($schools),
            'school-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Download school report as PDF.
     */
    public function exportPdf(Request $request)
    {
        $schools = $this->filteredSchools($request)
            ->latest()
            ->get();

        $pdf = Pdf::loadView(
            'super-admin.reports.schools.pdf',
            compact('schools')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'school-report-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    /**
     * Display print-friendly school report.
     */
    public function print(Request $request)
    {
        $schools = $this->filteredSchools($request)
            ->latest()
            ->get();

        return view(
            'super-admin.reports.schools.print',
            compact('schools')
        );
    }

    /**
     * Reusable school query with all report filters.
     */
    private function filteredSchools(Request $request)
    {
        $query = School::query()
            ->withCount([
                'users as total_users' => function ($query) {
                    $query->whereDoesntHave('role', function ($roleQuery) {
                        $roleQuery->where('name', 'super_admin');
                    });
                },

                'users as active_users' => function ($query) {
                    $query->where('status', 'Active')
                        ->whereDoesntHave('role', function ($roleQuery) {
                            $roleQuery->where('name', 'super_admin');
                        });
                },

                'users as inactive_users' => function ($query) {
                    $query->where('status', 'Inactive')
                        ->whereDoesntHave('role', function ($roleQuery) {
                            $roleQuery->where('name', 'super_admin');
                        });
                },
            ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('school_type')) {
            $query->where('school_type', $request->school_type);
        }

        if ($request->filled('education_board')) {
            $query->where(
                'education_board',
                $request->education_board
            );
        }

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        return $query;
    }
}
