<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Main Statistics
        |--------------------------------------------------------------------------
        */

        $students = $this->countTable('students');
        $teachers = $this->countTable('teachers');
        $classes  = $this->countTable('classes');
        $notices  = $this->countTable('notices');

        /*
        |--------------------------------------------------------------------------
        | Additional Statistics
        |--------------------------------------------------------------------------
        */

        $books     = $this->countTable('books');
        $transport = $this->countTable('transport');
        $events    = $this->countTable('events');

        /*
        |--------------------------------------------------------------------------
        | Student Statistics
        |--------------------------------------------------------------------------
        */

        $maleStudents = $this->countGender('students', 'male');
        $femaleStudents = $this->countGender('students', 'female');

        /*
        |--------------------------------------------------------------------------
        | School Calendar
        |--------------------------------------------------------------------------
        */

        $schoolCalendar = $this->getSchoolCalendar();

        /*
        |--------------------------------------------------------------------------
        | Recent Notices
        |--------------------------------------------------------------------------
        */

        $recentNotices = $this->getRecentNotices();

        /*
        |--------------------------------------------------------------------------
        | Send Everything To Dashboard
        |--------------------------------------------------------------------------
        */

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
    | Safe Table Count
    |--------------------------------------------------------------------------
    */

    private function countTable(string $table): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        return (int) DB::table($table)->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Student Gender Statistics
    |--------------------------------------------------------------------------
    */

    private function countGender(string $table, string $gender): int
    {
        /*
        | If Student Management has not created the table yet,
        | simply return 0.
        */

        if (!Schema::hasTable($table)) {
            return 0;
        }

        /*
        | Support either "gender" or "sex".
        */

        if (Schema::hasColumn($table, 'gender')) {

            return (int) DB::table($table)
                ->whereRaw(
                    'LOWER(gender) = ?',
                    [strtolower($gender)]
                )
                ->count();
        }

        if (Schema::hasColumn($table, 'sex')) {

            return (int) DB::table($table)
                ->whereRaw(
                    'LOWER(sex) = ?',
                    [strtolower($gender)]
                )
                ->count();
        }

        return 0;
    }


    /*
    |--------------------------------------------------------------------------
    | School Calendar
    |--------------------------------------------------------------------------
    */

    private function getSchoolCalendar(): array
    {
        $today = Carbon::today();

        $start = $today->copy()->startOfMonth();
        $end   = $today->copy()->endOfMonth();

        $days = [];

        /*
        | Create every day of the current month.
        */

        for (
            $date = $start->copy();
            $date->lte($end);
            $date->addDay()
        ) {
            $days[] = [
                'date'       => $date->copy(),
                'day'        => $date->day,
                'dayName'    => $date->format('D'),
                'isToday'    => $date->isToday(),
                'isWeekend'  => $date->isWeekend(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | School Events
        |--------------------------------------------------------------------------
        |
        | We check a few possible table names because the Events module
        | has not been created yet.
        |
        */

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

            /*
            | Only query if the expected columns exist.
            */

            if (
                Schema::hasColumn($table, 'event_date') &&
                Schema::hasColumn($table, 'title')
            ) {

                $events = DB::table($table)
                    ->whereBetween('event_date', [
                        $start->toDateString(),
                        $end->toDateString(),
                    ])
                    ->orderBy('event_date')
                    ->get()
                    ->map(function ($event) {

                        return [
                            'title' => $event->title,
                            'date'  => $event->event_date,
                        ];

                    })
                    ->toArray();
            }

            break;
        }

        return [
            'month'  => $today->format('F'),
            'year'   => $today->year,
            'days'   => $days,
            'events' => $events,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Recent Notices
    |--------------------------------------------------------------------------
    */

    private function getRecentNotices()
    {
        if (!Schema::hasTable('notices')) {
            return collect();
        }

        $query = DB::table('notices');

        if (Schema::hasColumn('notices', 'created_at')) {

            $query->orderByDesc('created_at');

        } elseif (Schema::hasColumn('notices', 'id')) {

            $query->orderByDesc('id');
        }

        return $query
            ->limit(5)
            ->get();
    }
}