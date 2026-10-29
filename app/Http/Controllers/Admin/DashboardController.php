<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // A school dashboard must belong to a specific school.
        if (!$user || !$user->school_id) {
            abort(403, 'Your account is not assigned to a school.');
        }

        $schoolId = (int) $user->school_id;

        /*
        |--------------------------------------------------------------------------
        | Main Statistics
        |--------------------------------------------------------------------------
        */

        $students = $this->countTable('students', $schoolId);
        $teachers = $this->countTable('teachers', $schoolId);

        // A table without school_id is not counted globally.
        $classes = $this->countTable('classes', $schoolId);
        $notices = $this->countTable('notices', $schoolId);

        /*
        |--------------------------------------------------------------------------
        | Additional Statistics
        |--------------------------------------------------------------------------
        */

        $books = $this->countTable('books', $schoolId);
        $transport = $this->countTable('transport', $schoolId);
        $events = $this->countTable('events', $schoolId);

        /*
        |--------------------------------------------------------------------------
        | Student Gender Statistics
        |--------------------------------------------------------------------------
        */

        $maleStudents = $this->countGender(
            'students',
            'male',
            $schoolId
        );

        $femaleStudents = $this->countGender(
            'students',
            'female',
            $schoolId
        );

        /*
        |--------------------------------------------------------------------------
        | School Calendar
        |--------------------------------------------------------------------------
        */

        $schoolCalendar = $this->getSchoolCalendar($schoolId);

        /*
        |--------------------------------------------------------------------------
        | Recent Notices
        |--------------------------------------------------------------------------
        */

        $recentNotices = $this->getRecentNotices($schoolId);

        return view('admin.dashboard.index', compact(
            'students',
            'teachers',
            'classes',
            'notices',
            'books',
            'transport',
            'events',
            'maleStudents',
            'femaleStudents',
            'schoolCalendar',
            'recentNotices'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | School-Scoped Table Count
    |--------------------------------------------------------------------------
    */

    private function countTable(string $table): int
{
    if (!Schema::hasTable($table)) {
        return 0;
    }

    $query = DB::table($table);

    $user = auth()->user();

    if (
        $user &&
        $user->school_id &&
        Schema::hasColumn($table, 'school_id')
    ) {
        $query->where('school_id', $user->school_id);
    }

    return (int) $query->count();
}

    /*
    |--------------------------------------------------------------------------
    | School-Scoped Student Gender Statistics
    |--------------------------------------------------------------------------
    */

    private function countGender(
        string $table,
        string $gender,
        int $schoolId
    ): int {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        if (!Schema::hasColumn($table, 'school_id')) {
            return 0;
        }

        $genderColumn = null;

        if (Schema::hasColumn($table, 'gender')) {
            $genderColumn = 'gender';
        } elseif (Schema::hasColumn($table, 'sex')) {
            $genderColumn = 'sex';
        }

        if (!$genderColumn) {
            return 0;
        }

        return (int) DB::table($table)
            ->where('school_id', $schoolId)
            ->whereRaw(
                "LOWER(`{$genderColumn}`) = ?",
                [strtolower($gender)]
            )
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | School Calendar
    |--------------------------------------------------------------------------
    */

    private function getSchoolCalendar(int $schoolId): array
    {
        $today = Carbon::today();

        $start = $today->copy()->startOfMonth();
        $end = $today->copy()->endOfMonth();

        $days = [];

        for (
            $date = $start->copy();
            $date->lte($end);
            $date->addDay()
        ) {
            $days[] = [
                'date' => $date->copy(),
                'day' => $date->day,
                'dayName' => $date->format('D'),
                'isToday' => $date->isToday(),
                'isWeekend' => $date->isWeekend(),
            ];
        }

        $events = [];

        $possibleTables = [
            'school_events',
            'calendar_events',
            'events',
        ];

        foreach ($possibleTables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            if (
                !Schema::hasColumn($table, 'event_date') ||
                !Schema::hasColumn($table, 'title') ||
                !Schema::hasColumn($table, 'school_id')
            ) {
                // Try another candidate table rather than querying
                // a table whose records cannot be school-scoped.
                continue;
            }

            $events = DB::table($table)
                ->where('school_id', $schoolId)
                ->whereBetween('event_date', [
                    $start->toDateString(),
                    $end->toDateString(),
                ])
                ->orderBy('event_date')
                ->get()
                ->map(function ($event) {
                    return [
                        'title' => $event->title,
                        'date' => $event->event_date,
                    ];
                })
                ->toArray();

            // Use the first compatible event table.
            break;
        }

        return [
            'month' => $today->format('F'),
            'year' => $today->year,
            'days' => $days,
            'events' => $events,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Recent School Notices
    |--------------------------------------------------------------------------
    */

    private function getRecentNotices(int $schoolId)
    {
        $schoolId = auth()->user()->school_id;

$query = DB::table('notices')
    ->where('school_id', $schoolId);
    
        if (!Schema::hasTable('notices')) {
            return collect();
        }

        if (!Schema::hasColumn('notices', 'school_id')) {
            Log::warning(
                'Recent notices skipped: notices has no school_id column.'
            );

            return collect();
        }

        $query = DB::table('notices')
            ->where('school_id', $schoolId);

        if (Schema::hasColumn('notices', 'created_at')) {
            $query->orderByDesc('created_at');
        } elseif (Schema::hasColumn('notices', 'id')) {
            $query->orderByDesc('id');
        }

        return $query->limit(5)->get();
    }
}
