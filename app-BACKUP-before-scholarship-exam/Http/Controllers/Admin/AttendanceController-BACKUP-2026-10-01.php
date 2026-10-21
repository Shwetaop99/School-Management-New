<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\SchoolSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Display the attendance page.
     */
    public function index(): View
    {
        $academicYears = Student::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $classes = Student::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderByRaw('CAST(class AS UNSIGNED)')
            ->pluck('class');

        return view(
            'admin.attendance.index',
            compact(
                'academicYears',
                'classes'
            )
        );
    }

    /**
     * Load students according to academic year, class and section.
     */
    public function students(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class' => [
                'required',
                'string',
                'max:50',
            ],

            'section' => [
                'required',
                'string',
                'max:10',
            ],

            'attendance_date' => [
                'required',
                'date',
            ],
        ]);

        $students = Student::query()
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'class',
                $validated['class']
            )
            ->where(
                'section',
                $validated['section']
            )
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(roll_number AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
                'profile_image',
                'class',
                'section',
            ]);

        /*
         * Get already saved attendance for this date.
         */
        $attendance = Attendance::query()
            ->whereDate(
                'attendance_date',
                $validated['attendance_date']
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->get()
            ->keyBy('student_id');

        $data = $students->map(
            function (Student $student) use ($attendance) {

                $record = $attendance->get(
                    $student->id
                );

                return [
                    'id' =>
                        $student->id,

                    'student_id' =>
                        $student->student_id,

                    'roll_number' =>
                        $student->roll_number,

                    'name' =>
                        trim(
                            $student->first_name . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            $student->last_name
                        ),

                    'profile_image' =>
                        $student->profile_image,

                    'class' =>
                        $student->class,

                    'section' =>
                        $student->section,

                    'attendance_status' =>
                        $record?->status,

                    'attendance_id' =>
                        $record?->id,

                    'remarks' =>
                        $record?->remarks,
                ];
            }
        );

        return response()->json([
            'success' =>
                true,

            'count' =>
                $data->count(),

            'students' =>
                $data,
        ]);
    }

    /**
     * Save attendance.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class' => [
                'required',
                'string',
                'max:50',
            ],

            'section' => [
                'required',
                'string',
                'max:10',
            ],

            'attendance_date' => [
                'required',
                'date',
            ],

            'attendance' => [
                'required',
                'array',
            ],

            'attendance.*.student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],

            'attendance.*.status' => [
                'required',
                'in:present,absent,leave,half_day,late',
            ],

            'attendance.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        foreach ($validated['attendance'] as $record) {

            Attendance::updateOrCreate(
                [
                    'student_id' =>
                        $record['student_id'],

                    'attendance_date' =>
                        $validated['attendance_date'],
                ],
                [
                    'academic_year' =>
                        $validated['academic_year'],

                    'class' =>
                        $validated['class'],

                    'section' =>
                        $validated['section'],

                    'status' =>
                        $record['status'],

                    'remarks' =>
                        $record['remarks'] ?? null,

                    'marked_by' =>
                        auth()->id(),
                ]
            );
        }

        return back()->with(
            'success',
            'Attendance saved successfully.'
        );
    }

    /**
     * Update attendance.
     */
    public function update(
        Request $request,
        Attendance $attendance
    ): RedirectResponse {

        $validated = $request->validate([
            'status' => [
                'required',
                'in:present,absent,leave,half_day,late',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $attendance->update([
            'status' =>
                $validated['status'],

            'remarks' =>
                $validated['remarks'] ?? null,

            'marked_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Attendance updated successfully.'
        );
    }

    /**
     * Display attendance report.
     */
    public function report(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Normal page load
        |--------------------------------------------------------------------------
        */

        if (!$request->expectsJson()) {

            $academicYears = Student::query()
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->distinct()
                ->orderBy(
                    'academic_year',
                    'desc'
                )
                ->pluck('academic_year');

            $classes = Student::query()
                ->whereNotNull('class')
                ->where('class', '!=', '')
                ->distinct()
                ->orderByRaw(
                    'CAST(class AS UNSIGNED)'
                )
                ->pluck('class');

            return view(
                'admin.attendance.report',
                compact(
                    'academicYears',
                    'classes'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate filters
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class' => [
                'required',
                'string',
                'max:50',
            ],

            'section' => [
                'required',
                'string',
                'max:10',
            ],

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get students
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'class',
                $validated['class']
            )
            ->where(
                'section',
                $validated['section']
            )
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(roll_number AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Get attendance records
        |--------------------------------------------------------------------------
        */

        $attendanceRecords = Attendance::query()
            ->whereBetween(
                'attendance_date',
                [
                    $validated['from_date'],
                    $validated['to_date'],
                ]
            )
            ->whereIn(
                'student_id',
                $students->pluck('id')
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Group attendance by student
        |--------------------------------------------------------------------------
        */

        $attendanceByStudent =
            $attendanceRecords->groupBy(
                'student_id'
            );

        /*
        |--------------------------------------------------------------------------
        | Build student report
        |--------------------------------------------------------------------------
        */

        $studentReports = $students->map(
            function (
                Student $student
            ) use (
                $attendanceByStudent
            ) {

                $records =
                    $attendanceByStudent->get(
                        $student->id,
                        collect()
                    );

                $present =
                    $records
                        ->where(
                            'status',
                            'present'
                        )
                        ->count();

                $absent =
                    $records
                        ->where(
                            'status',
                            'absent'
                        )
                        ->count();

                $leave =
                    $records
                        ->where(
                            'status',
                            'leave'
                        )
                        ->count();

                $halfDay =
                    $records
                        ->where(
                            'status',
                            'half_day'
                        )
                        ->count();

                $late =
                    $records
                        ->where(
                            'status',
                            'late'
                        )
                        ->count();

                $workingDays =
                    $records->count();

                $attendanceValue =
                    $present +
                    $late +
                    ($halfDay * 0.5);

                $percentage =
                    $workingDays > 0
                        ? round(
                            (
                                $attendanceValue /
                                $workingDays
                            ) * 100,
                            2
                        )
                        : 0;

                $name =
                    trim(
                        $student->first_name . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        $student->last_name
                    );

                return [
                    'id' =>
                        $student->id,

                    'student_id' =>
                        $student->student_id,

                    'roll_number' =>
                        $student->roll_number,

                    'name' =>
                        $name,

                    'working_days' =>
                        $workingDays,

                    'present' =>
                        $present,

                    'absent' =>
                        $absent,

                    'leave' =>
                        $leave,

                    'half_day' =>
                        $halfDay,

                    'late' =>
                        $late,

                    'attendance_percentage' =>
                        $percentage,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Overall summary
        |--------------------------------------------------------------------------
        */

        $totalStudents =
            $studentReports->count();

        $totalPresent =
            $studentReports->sum('present');

        $totalAbsent =
            $studentReports->sum('absent');

        $totalWorkingDays =
            $studentReports->sum('working_days');

        $averageAttendance =
            $totalWorkingDays > 0
                ? round(
                    (
                        $studentReports->sum(
                            function ($student) {

                                return
                                    $student['present'] +
                                    $student['late'] +
                                    (
                                        $student['half_day']
                                        * 0.5
                                    );
                            }
                        )
                        /
                        $totalWorkingDays
                    ) * 100,
                    2
                )
                : 0;

        return response()->json([
            'success' =>
                true,

            'academic_year' =>
                $validated['academic_year'],

            'class' =>
                $validated['class'],

            'section' =>
                $validated['section'],

            'from_date' =>
                $validated['from_date'],

            'to_date' =>
                $validated['to_date'],

            'summary' => [
                'total_students' =>
                    $totalStudents,

                'total_present' =>
                    $totalPresent,

                'total_absent' =>
                    $totalAbsent,

                'average_attendance' =>
                    $averageAttendance,
            ],

            'students' =>
                $studentReports->values(),
        ]);
    }

    /**
     * Display attendance report for one student.
     */
    public function studentReport(
        Student $student
    ): View {

        $attendances =
            $student->attendances()
                ->orderByDesc(
                    'attendance_date'
                )
                ->get();

        return view(
            'admin.attendance.student-report',
            compact(
                'student',
                'attendances'
            )
        );
    }

    /**
     * Display monthly attendance report.
     */
    public function monthlyReport(
        Request $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | Normal page load
        |--------------------------------------------------------------------------
        */

        if (!$request->expectsJson()) {

            $academicYears = Student::query()
                ->whereNotNull('academic_year')
                ->where('academic_year', '!=', '')
                ->distinct()
                ->orderBy(
                    'academic_year',
                    'desc'
                )
                ->pluck('academic_year');

            $classes = Student::query()
                ->whereNotNull('class')
                ->where('class', '!=', '')
                ->distinct()
                ->orderByRaw(
                    'CAST(class AS UNSIGNED)'
                )
                ->pluck('class');

            return view(
                'admin.attendance.monthly',
                compact(
                    'academicYears',
                    'classes'
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class' => [
                'required',
                'string',
                'max:50',
            ],

            'section' => [
                'required',
                'string',
                'max:10',
            ],

            'month' => [
                'required',
                'date_format:Y-m',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Month dates
        |--------------------------------------------------------------------------
        */

        $monthStart =
            \Carbon\Carbon::createFromFormat(
                'Y-m',
                $validated['month']
            )->startOfMonth();

        $monthEnd =
            $monthStart
                ->copy()
                ->endOfMonth();

        $daysInMonth =
            $monthStart->daysInMonth;

        /*
        |--------------------------------------------------------------------------
        | Get students
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'class',
                $validated['class']
            )
            ->where(
                'section',
                $validated['section']
            )
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(roll_number AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Get attendance
        |--------------------------------------------------------------------------
        */

        $attendanceRecords =
            Attendance::query()
                ->whereBetween(
                    'attendance_date',
                    [
                        $monthStart->toDateString(),
                        $monthEnd->toDateString(),
                    ]
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Group by student and date
        |--------------------------------------------------------------------------
        */

        $attendanceMap = [];

        foreach ($attendanceRecords as $record) {

            $date =
                \Carbon\Carbon::parse(
                    $record->attendance_date
                )->format('j');

            $attendanceMap[
                $record->student_id
            ][$date] =
                $record->status;
        }

        /*
        |--------------------------------------------------------------------------
        | Student report
        |--------------------------------------------------------------------------
        */

        $studentReports = $students->map(
            function (
                Student $student
            ) use (
                $attendanceMap,
                $daysInMonth
            ) {

                $days = [];

                $present = 0;
                $absent = 0;
                $leave = 0;
                $halfDay = 0;
                $late = 0;

                for (
                    $day = 1;
                    $day <= $daysInMonth;
                    $day++
                ) {

                    $status =
                        $attendanceMap[
                            $student->id
                        ][$day] ?? null;

                    $days[$day] =
                        $status;

                    switch ($status) {

                        case 'present':
                            $present++;
                            break;

                        case 'absent':
                            $absent++;
                            break;

                        case 'leave':
                            $leave++;
                            break;

                        case 'half_day':
                            $halfDay++;
                            break;

                        case 'late':
                            $late++;
                            break;
                    }
                }

                $recordedDays =
                    $present +
                    $absent +
                    $leave +
                    $halfDay +
                    $late;

                $attendanceValue =
                    $present +
                    $late +
                    ($halfDay * 0.5);

                $percentage =
                    $recordedDays > 0
                        ? round(
                            (
                                $attendanceValue /
                                $recordedDays
                            ) * 100,
                            2
                        )
                        : 0;

                return [
                    'id' =>
                        $student->id,

                    'student_id' =>
                        $student->student_id,

                    'roll_number' =>
                        $student->roll_number,

                    'name' =>
                        trim(
                            $student->first_name . ' ' .
                            ($student->middle_name ?? '') . ' ' .
                            $student->last_name
                        ),

                    'days' =>
                        $days,

                    'present' =>
                        $present,

                    'absent' =>
                        $absent,

                    'leave' =>
                        $leave,

                    'half_day' =>
                        $halfDay,

                    'late' =>
                        $late,

                    'recorded_days' =>
                        $recordedDays,

                    'attendance_percentage' =>
                        $percentage,
                ];
            }
        );

        return response()->json([
            'success' =>
                true,

            'academic_year' =>
                $validated['academic_year'],

            'class' =>
                $validated['class'],

            'section' =>
                $validated['section'],

            'month' =>
                $validated['month'],

            'days_in_month' =>
                $daysInMonth,

            'students' =>
                $studentReports->values(),
        ]);
    }

    /**
     * Generate printable attendance report.
     */
    public function printReport(
        Request $request
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Validate filters
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'class' => [
                'required',
                'string',
                'max:50',
            ],

            'section' => [
                'required',
                'string',
                'max:10',
            ],

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get School Profile
        |--------------------------------------------------------------------------
        |
        | This retrieves the single school profile saved from:
        | Admin → School Profile
        |
        */

        $school = SchoolSetting::first();

        /*
        |--------------------------------------------------------------------------
        | Get students
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->where(
                'class',
                $validated['class']
            )
            ->where(
                'section',
                $validated['section']
            )
            ->where('status', 'active')
            ->orderByRaw(
                'CAST(roll_number AS UNSIGNED)'
            )
            ->orderBy('first_name')
            ->get([
                'id',
                'student_id',
                'roll_number',
                'first_name',
                'middle_name',
                'last_name',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Get attendance records
        |--------------------------------------------------------------------------
        */

        $attendanceRecords =
            Attendance::query()
                ->whereBetween(
                    'attendance_date',
                    [
                        $validated['from_date'],
                        $validated['to_date'],
                    ]
                )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->where(
                    'class',
                    $validated['class']
                )
                ->where(
                    'section',
                    $validated['section']
                )
                ->whereIn(
                    'student_id',
                    $students->pluck('id')
                )
                ->orderBy(
                    'attendance_date'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | Determine recorded working days
        |--------------------------------------------------------------------------
        */

        $workingDates =
            $attendanceRecords
                ->pluck('attendance_date')
                ->map(function ($date) {

                    return \Carbon\Carbon::parse(
                        $date
                    )->format('Y-m-d');

                })
                ->unique()
                ->sort()
                ->values();

        $workingDays =
            $workingDates->count();

        /*
        |--------------------------------------------------------------------------
        | Group attendance by student
        |--------------------------------------------------------------------------
        */

        $attendanceByStudent =
            $attendanceRecords
                ->groupBy('student_id');

        /*
        |--------------------------------------------------------------------------
        | Build report
        |--------------------------------------------------------------------------
        */

        $studentReports = $students->map(
            function (
                Student $student
            ) use (
                $attendanceByStudent,
                $workingDays
            ) {

                $records =
                    $attendanceByStudent->get(
                        $student->id,
                        collect()
                    );

                $present =
                    $records
                        ->where(
                            'status',
                            'present'
                        )
                        ->count();

                $absent =
                    $records
                        ->where(
                            'status',
                            'absent'
                        )
                        ->count();

                $leave =
                    $records
                        ->where(
                            'status',
                            'leave'
                        )
                        ->count();

                $halfDay =
                    $records
                        ->where(
                            'status',
                            'half_day'
                        )
                        ->count();

                $late =
                    $records
                        ->where(
                            'status',
                            'late'
                        )
                        ->count();

                /*
                |--------------------------------------------------------------------------
                | Attendance calculation
                |--------------------------------------------------------------------------
                |
                | Present = 1
                | Late    = 1
                | Half Day = 0.5
                | Absent / Leave = 0
                |
                */

                $attendanceValue =
                    $present +
                    $late +
                    ($halfDay * 0.5);

                $percentage =
                    $workingDays > 0
                        ? round(
                            (
                                $attendanceValue /
                                $workingDays
                            ) * 100,
                            2
                        )
                        : 0;

                $name =
                    trim(
                        $student->first_name . ' ' .
                        ($student->middle_name ?? '') . ' ' .
                        $student->last_name
                    );

                return [
                    'id' =>
                        $student->id,

                    'student_id' =>
                        $student->student_id,

                    'roll_number' =>
                        $student->roll_number,

                    'name' =>
                        $name,

                    'working_days' =>
                        $workingDays,

                    'present' =>
                        $present,

                    'absent' =>
                        $absent,

                    'leave' =>
                        $leave,

                    'half_day' =>
                        $halfDay,

                    'late' =>
                        $late,

                    'attendance_percentage' =>
                        $percentage,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalStudents =
            $studentReports->count();

        $totalPresent =
            $studentReports->sum('present');

        $totalAbsent =
            $studentReports->sum('absent');

        $totalLeave =
            $studentReports->sum('leave');

        $totalHalfDay =
            $studentReports->sum('half_day');

        $totalLate =
            $studentReports->sum('late');

        /*
        |--------------------------------------------------------------------------
        | Overall attendance
        |--------------------------------------------------------------------------
        */

        $totalPossibleDays =
            $totalStudents *
            $workingDays;

        $totalAttendanceValue =
            $totalPresent +
            $totalLate +
            ($totalHalfDay * 0.5);

        $averageAttendance =
            $totalPossibleDays > 0
                ? round(
                    (
                        $totalAttendanceValue /
                        $totalPossibleDays
                    ) * 100,
                    2
                )
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Return printable view
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.attendance.print',
            [
                /*
                |--------------------------------------------------------------------------
                | School information
                |--------------------------------------------------------------------------
                */

                'school' =>
                    $school,

                /*
                |--------------------------------------------------------------------------
                | Attendance information
                |--------------------------------------------------------------------------
                */

                'students' =>
                    $studentReports,

                'academicYear' =>
                    $validated['academic_year'],

                'className' =>
                    $validated['class'],

                'section' =>
                    $validated['section'],

                'fromDate' =>
                    \Carbon\Carbon::parse(
                        $validated['from_date']
                    ),

                'toDate' =>
                    \Carbon\Carbon::parse(
                        $validated['to_date']
                    ),

                'workingDays' =>
                    $workingDays,

                'totalStudents' =>
                    $totalStudents,

                'totalPresent' =>
                    $totalPresent,

                'totalAbsent' =>
                    $totalAbsent,

                'totalLeave' =>
                    $totalLeave,

                'totalHalfDay' =>
                    $totalHalfDay,

                'totalLate' =>
                    $totalLate,

                'averageAttendance' =>
                    $averageAttendance,
            ]
        );
    }
}
