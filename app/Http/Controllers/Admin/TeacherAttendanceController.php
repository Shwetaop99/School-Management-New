<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\TeacherAttendanceExport;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TeacherAttendanceController extends Controller
{
    /**
     * =========================================================
     * INDIAN NATIONAL HOLIDAYS
     * =========================================================
     */
    private function nationalHolidays(): array
    {
        return [
            '01-26' => 'Republic Day',
            '08-15' => 'Independence Day',
            '10-02' => 'Gandhi Jayanti',
        ];
    }

    /**
     * =========================================================
     * GET HOLIDAY NAME
     * =========================================================
     */
    private function getHolidayName(Carbon $date): ?string
    {
        // Every Sunday
        if ($date->isSunday()) {
            return 'Sunday';
        }

        // National holidays
        $key = $date->format('m-d');

        return $this->nationalHolidays()[$key] ?? null;
    }

    /**
     * =========================================================
     * MONTHLY FACULTY ATTENDANCE
     * =========================================================
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Month
        |--------------------------------------------------------------------------
        */

        $month = $request->input(
            'month',
            now()->format('Y-m')
        );

        try {
            $monthDate = Carbon::createFromFormat(
                'Y-m',
                $month
            )->startOfMonth();
        } catch (\Exception $e) {
            $monthDate = now()->startOfMonth();
            $month = $monthDate->format('Y-m');
        }

        $startDate = $monthDate->copy()->startOfMonth();
        $endDate = $monthDate->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Selected Teacher
        |--------------------------------------------------------------------------
        */

        $selectedTeacher = null;

        if ($request->filled('teacher_id')) {
            $selectedTeacher = Teacher::find(
                $request->input('teacher_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Teachers
        |--------------------------------------------------------------------------
        */

        $teachersQuery = Teacher::query()
            ->orderBy('first_name')
            ->orderBy('last_name');

        if ($selectedTeacher) {
            $teachersQuery->where(
                'id',
                $selectedTeacher->id
            );
        }

        $teachers = $teachersQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Monthly Dates
        |--------------------------------------------------------------------------
        */

        $dates = [];

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->copy();

            $currentDate->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Records
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | We DO NOT create Holiday records here.
        |
        | Sundays and national holidays are handled automatically
        | by the controller/view without inserting "Holiday" into
        | the database.
        |
        |--------------------------------------------------------------------------
        */

        $attendanceQuery = TeacherAttendance::query()
            ->whereBetween('attendance_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]);

        if ($selectedTeacher) {
            $attendanceQuery->where(
                'teacher_id',
                $selectedTeacher->id
            );
        }

        $attendanceRecords = $attendanceQuery
            ->get()
            ->keyBy(function ($attendance) {

                return $attendance->teacher_id . '_' .
                    Carbon::parse(
                        $attendance->attendance_date
                    )->format('Y-m-d');
            });

        /*
        |--------------------------------------------------------------------------
        | Monthly Summary
        |--------------------------------------------------------------------------
        */

        $summary = [
            'total' => $teachers->count(),
            'present' => 0,
            'half_day' => 0,
            'absent' => 0,
            'holiday' => 0,
            'attendance_percentage' => 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | Count Holidays
        |--------------------------------------------------------------------------
        */

        $holidayDays = 0;

        foreach ($dates as $date) {

            if ($this->getHolidayName($date)) {
                $holidayDays++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Summary
        |--------------------------------------------------------------------------
        */

        if ($selectedTeacher) {

            $teacherAttendance = $attendanceRecords
                ->filter(function ($attendance) use ($selectedTeacher) {

                    return (int) $attendance->teacher_id ===
                        (int) $selectedTeacher->id;
                });

            $summary['present'] = $teacherAttendance
                ->where('status', 'Present')
                ->count();

            $summary['half_day'] = $teacherAttendance
                ->where('status', 'Half Day')
                ->count();

            $summary['absent'] = $teacherAttendance
                ->where('status', 'Absent')
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Holiday count is calculated from calendar,
            | not database records.
            |--------------------------------------------------------------------------
            */

            $summary['holiday'] = $holidayDays;

        } else {

            $summary['present'] = $attendanceRecords
                ->where('status', 'Present')
                ->count();

            $summary['half_day'] = $attendanceRecords
                ->where('status', 'Half Day')
                ->count();

            $summary['absent'] = $attendanceRecords
                ->where('status', 'Absent')
                ->count();

            $summary['holiday'] =
                $holidayDays * $teachers->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Percentage
        |--------------------------------------------------------------------------
        |
        | Holiday is NOT included.
        |
        | Present = 1
        | Half Day = 0.5
        | Absent = 0
        |
        |--------------------------------------------------------------------------
        */

        $markedDays =
            $summary['present'] +
            $summary['half_day'] +
            $summary['absent'];

        $attendancePoints =
            $summary['present'] +
            ($summary['half_day'] * 0.5);

        $summary['attendance_percentage'] = $markedDays > 0
            ? round(
                ($attendancePoints / $markedDays) * 100,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.attendance.index',
            compact(
                'teachers',
                'selectedTeacher',
                'dates',
                'attendanceRecords',
                'month',
                'summary'
            )
        );
    }

    /**
     * =========================================================
     * SAVE MONTHLY ATTENDANCE
     * =========================================================
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendance_month' => [
                'required',
                'date_format:Y-m',
            ],

            'attendance' => [
                'required',
                'array',
            ],

            'teacher_id' => [
                'nullable',
                'integer',
                'exists:teachers,id',
            ],
        ]);

        $monthDate = Carbon::createFromFormat(
            'Y-m',
            $request->attendance_month
        )->startOfMonth();

        $startDate = $monthDate->copy()->startOfMonth();
        $endDate = $monthDate->copy()->endOfMonth();

        $selectedTeacherId = $request->input('teacher_id');

        DB::transaction(function () use (
            $request,
            $startDate,
            $endDate,
            $selectedTeacherId
        ) {

            foreach ($request->attendance as $teacherId => $dates) {

                /*
                |--------------------------------------------------------------------------
                | Selected Teacher Safety
                |--------------------------------------------------------------------------
                */

                if (
                    $selectedTeacherId !== null &&
                    (int) $teacherId !== (int) $selectedTeacherId
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Teacher Exists
                |--------------------------------------------------------------------------
                */

                if (!Teacher::whereKey($teacherId)->exists()) {
                    continue;
                }

                foreach ($dates as $date => $status) {

                    /*
                    |--------------------------------------------------------------------------
                    | Parse Date
                    |--------------------------------------------------------------------------
                    */

                    try {
                        $attendanceDate = Carbon::parse($date);
                    } catch (\Exception $e) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Date Must Belong To Selected Month
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $attendanceDate->lt($startDate) ||
                        $attendanceDate->gt($endDate)
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HOLIDAY PROTECTION
                    |--------------------------------------------------------------------------
                    |
                    | Sundays and national holidays are NOT saved.
                    |
                    | This prevents the database from receiving:
                    |
                    | status = Holiday
                    |
                    |--------------------------------------------------------------------------
                    */

                    $holidayName = $this->getHolidayName(
                        $attendanceDate
                    );

                    if ($holidayName) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Blank = Remove Existing Attendance
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $status === null ||
                        $status === ''
                    ) {

                        TeacherAttendance::where(
                            'teacher_id',
                            $teacherId
                        )
                        ->whereDate(
                            'attendance_date',
                            $attendanceDate->toDateString()
                        )
                        ->delete();

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Allowed Attendance Statuses
                    |--------------------------------------------------------------------------
                    |
                    | Holiday is intentionally NOT included.
                    |
                    |--------------------------------------------------------------------------
                    */

                    if (!in_array($status, [
                        'Present',
                        'Half Day',
                        'Absent',
                        'Leave',
                    ], true)) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Save Attendance
                    |--------------------------------------------------------------------------
                    */

                    TeacherAttendance::updateOrCreate(
                        [
                            'teacher_id' => $teacherId,
                            'attendance_date' =>
                                $attendanceDate->toDateString(),
                        ],
                        [
                            'status' => $status,
                        ]
                    );
                }
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.teachers.attendance.index',
                array_filter([
                    'month' => $request->attendance_month,
                    'teacher_id' => $request->teacher_id,
                ], function ($value) {

                    return $value !== null &&
                        $value !== '';
                })
            )
            ->with(
                'success',
                'Faculty attendance saved successfully. Sundays and national holidays are automatically treated as holidays.'
            );
    }

    /**
     * =========================================================
     * EXCEL EXPORT
     * =========================================================
     */
    public function excel(Request $request)
    {
        $date = $request->input(
            'date',
            now()->toDateString()
        );

        return Excel::download(
            new TeacherAttendanceExport($date),
            'teacher-attendance-' . $date . '.xlsx'
        );
    }
}