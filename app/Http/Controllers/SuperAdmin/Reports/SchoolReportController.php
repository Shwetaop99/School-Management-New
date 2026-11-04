<?php

namespace App\Http\Controllers\SuperAdmin\Reports;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolReportController extends Controller
{
    public function index(Request $request)
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

        // Search by school name, code, city or district.
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('district', 'like', "%{$search}%");
            });
        }

        // Filter by school status.
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by school type.
        if ($request->filled('school_type')) {
            $query->where('school_type', $request->school_type);
        }

        // Filter by education board.
        if ($request->filled('education_board')) {
            $query->where('education_board', $request->education_board);
        }

        // Filter by registration date.
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $schools = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Summary statistics.
        $totalSchools = School::count();

        $activeSchools = School::where('status', 'active')->count();

        $inactiveSchools = School::where('status', 'inactive')->count();

        $totalUsers = School::withCount('users')
            ->get()
            ->sum('users_count');

        // Filter dropdown values.
        $schoolTypes = School::whereNotNull('school_type')
            ->distinct()
            ->orderBy('school_type')
            ->pluck('school_type');

        $educationBoards = School::whereNotNull('education_board')
            ->distinct()
            ->orderBy('education_board')
            ->pluck('education_board');

        return view('super-admin.reports.schools.index', compact(
            'schools',
            'totalSchools',
            'activeSchools',
            'inactiveSchools',
            'totalUsers',
            'schoolTypes',
            'educationBoards'
        ));
    }
}
