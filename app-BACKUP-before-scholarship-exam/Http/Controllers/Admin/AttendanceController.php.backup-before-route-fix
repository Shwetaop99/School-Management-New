<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassTeacherAssignment;
use App\Models\Student;
use App\Exports\StudentAttendanceExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * =========================================================
     * STUDENT ATTENDANCE - CLASS / SECTION DASHBOARD
     * =========================================================
     */
    public function classes(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | MONTH
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
        } catch (\Throwable $e) {
            $monthDate = now()->startOfMonth();
            $month = $monthDate->format('Y-m');
        }

        $monthStart = $monthDate->copy()->startOfMonth();
        $monthEnd = $monthDate->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | SELECTED DATE
        |--------------------------------------------------------------------------
        */

        $requestedDate = null;

        if ($request->filled('date')) {
            try {
                $date = Carbon::parse(
                    $request->input('date')
                )->startOfDay();

                if (
                    $date->gte($monthStart) &&
                    $date->lte($monthEnd)
                ) {
                    $requestedDate = $date;
                }
            } catch (\Throwable $e) {
                $requestedDate = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ATTENDANCE DATE
        |--------------------------------------------------------------------------
        */

        $attendanceDate = $requestedDate
            ? $requestedDate->copy()
            : now()->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | IF SELECTED DATE IS HOLIDAY
        |--------------------------------------------------------------------------
        */

        $holidayName = $this->getHolidayName(
            $attendanceDate
        );

        /*
        |--------------------------------------------------------------------------
        | ACTIVE STUDENTS GROUPED BY CLASS + SECTION
        |--------------------------------------------------------------------------
        */

        $studentGroups = Student::query()
            ->where(
                'students.status',
                'Active'
            )
            ->whereNotNull(
                'students.academic_year'
            )
            ->whereNotNull(
                'students.class'
            )
            ->whereNotNull(
                'students.section'
            )
            ->select([
                'students.academic_year',
                'students.class',
                'students.section',
                DB::raw(
                    'COUNT(students.id) AS student_count'
                ),
            ])
            ->groupBy(
                'students.academic_year',
                'students.class',
                'students.section'
            )
            ->orderBy(
                'students.class'
            )
            ->orderBy(
                'students.section'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AUTOMATICALLY CREATE HOLIDAY RECORDS
        |--------------------------------------------------------------------------
        |
        | If the selected date is Sunday or National Holiday,
        | mark all active students as holiday automatically.
        |
        */

        if ($holidayName) {

            $holidayStudents = Student::query()
                ->where(
                    'status',
                    'Active'
                )
                ->whereNotNull(
                    'academic_year'
                )
                ->whereNotNull(
                    'class'
                )
                ->whereNotNull(
                    'section'
                )
                ->get();

            foreach ($holidayStudents as $student) {

                Attendance::updateOrCreate(
                    [
                        'student_id' =>
                            $student->id,

                        'attendance_date' =>
                            $attendanceDate->toDateString(),
                    ],
                    [
                        'academic_year' =>
                            $student->academic_year,

                        'class' =>
                            $student->class,

                        'section' =>
                            $student->section,

                        'status' =>
                            'holiday',

                        'remarks' =>
                            $holidayName,

                        'marked_by' =>
                            auth()->id(),
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE COUNTS FOR SELECTED DATE
        |--------------------------------------------------------------------------
        */

        $attendanceMap = [];

        foreach ($studentGroups as $group) {

            $key = $this->makeClassKey(
                $group->academic_year,
                $group->class,
                $group->section
            );

            /*
            |--------------------------------------------------------------------------
            | COUNT PRESENT / ABSENT / HOLIDAY
            |--------------------------------------------------------------------------
            */

            $counts = Attendance::query()
                ->join(
                    'students',
                    'students.id',
                    '=',
                    'attendances.student_id'
                )
                ->where(
                    'students.status',
                    'Active'
                )
                ->where(
                    'students.academic_year',
                    $group->academic_year
                )
                ->where(
                    'students.class',
                    $group->class
                )
                ->where(
                    'students.section',
                    $group->section
                )
                ->whereDate(
                    'attendances.attendance_date',
                    $attendanceDate->toDateString()
                )
                ->select([
                    DB::raw("
                        COUNT(
                            DISTINCT CASE
                                WHEN LOWER(TRIM(attendances.status)) = 'present'
                                THEN attendances.student_id
                            END
                        ) AS present_count
                    "),

                    DB::raw("
                        COUNT(
                            DISTINCT CASE
                                WHEN LOWER(TRIM(attendances.status)) = 'absent'
                                THEN attendances.student_id
                            END
                        ) AS absent_count
                    "),

                    DB::raw("
                        COUNT(
                            DISTINCT CASE
                                WHEN LOWER(TRIM(attendances.status)) = 'holiday'
                                THEN attendances.student_id
                            END
                        ) AS holiday_count
                    "),
                ])
                ->first();

            $attendanceMap[$key] = [

                'present_count' =>
                    (int) (
                        $counts->present_count ?? 0
                    ),

                'absent_count' =>
                    (int) (
                        $counts->absent_count ?? 0
                    ),

                'holiday_count' =>
                    (int) (
                        $counts->holiday_count ?? 0
                    ),

                'attendance_date' =>
                    $attendanceDate->toDateString(),

                'holiday_name' =>
                    $holidayName,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CLASS TEACHERS
        |--------------------------------------------------------------------------
        */

        $teacherAssignments = ClassTeacherAssignment::query()
            ->join(
                'teachers',
                'teachers.id',
                '=',
                'class_teacher_assignments.teacher_id'
            )
            ->join(
                'classes',
                'classes.id',
                '=',
                'class_teacher_assignments.class_id'
            )
            ->join(
                'sections',
                'sections.id',
                '=',
                'class_teacher_assignments.section_id'
            )
            ->select([
                'class_teacher_assignments.academic_year',
                'classes.class_name',
                'sections.section_name',
                'teachers.first_name',
                'teachers.last_name',
            ])
            ->get();

        $teacherMap = [];

        foreach ($teacherAssignments as $assignment) {

            $key = $this->makeClassKey(
                $assignment->academic_year,
                $assignment->class_name,
                $assignment->section_name
            );

            $teacherName = trim(
                $assignment->first_name .
                ' ' .
                $assignment->last_name
            );

            $teacherMap[$key] =
                $teacherName ?: null;
        }

        /*
        |--------------------------------------------------------------------------
        | BUILD CLASS CARDS
        |--------------------------------------------------------------------------
        */

        $classCards = $studentGroups->map(
            function ($group) use (
                $attendanceMap,
                $teacherMap
            ) {

                $key = $this->makeClassKey(
                    $group->academic_year,
                    $group->class,
                    $group->section
                );

                return (object) [

                    'academic_year' =>
                        $group->academic_year,

                    'class' =>
                        $group->class,

                    'section' =>
                        $group->section,

                    'student_count' =>
                        (int) $group->student_count,

                    'present_count' =>
                        $attendanceMap[$key]['present_count']
                        ?? 0,

                    'absent_count' =>
                        $attendanceMap[$key]['absent_count']
                        ?? 0,

                    'holiday_count' =>
                        $attendanceMap[$key]['holiday_count']
                        ?? 0,

                    'attendance_date' =>
                        $attendanceMap[$key]['attendance_date']
                        ?? null,

                    'holiday_name' =>
                        $attendanceMap[$key]['holiday_name']
                        ?? null,

                    'class_teacher' =>
                        $teacherMap[$key]
                        ?? null,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */

        $totalStudents =
            $classCards->sum('student_count');

        $totalPresent =
            $classCards->sum('present_count');

        $totalAbsent =
            $classCards->sum('absent_count');

        $totalHoliday =
            $classCards->sum('holiday_count');

        /*
        |--------------------------------------------------------------------------
        | RETURN DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.attendance.student-index',
            compact(
                'classCards',
                'month',
                'attendanceDate',
                'totalStudents',
                'totalPresent',
                'totalAbsent',
                'totalHoliday',
                'holidayName'
            )
        );
    }


    /**
     * =========================================================
     * STUDENT ATTENDANCE SHEET
     * =========================================================
     */
    public function index(Request $request)
    {
        $academic_year = $request->input(
            'academic_year'
        );

        $class = $request->input(
            'class'
        );

        $section = $request->input(
            'section'
        );

        if (
            empty($academic_year) ||
            empty($class) ||
            empty($section)
        ) {
            return redirect()
                ->route(
                    'admin.attendance.student'
                )
                ->with(
                    'error',
                    'Please select a class and section before marking attendance.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | MONTH
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
        } catch (\Throwable $e) {
            $monthDate = now()->startOfMonth();
            $month = $monthDate->format('Y-m');
        }

        $monthStart =
            $monthDate->copy()->startOfMonth();

        $monthEnd =
            $monthDate->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | ACTIVE STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = Student::query()
            ->where(
                'students.status',
                'Active'
            )
            ->where(
                'students.academic_year',
                $academic_year
            )
            ->where(
                'students.class',
                $class
            )
            ->where(
                'students.section',
                $section
            )
            ->orderBy(
                'students.roll_number'
            )
            ->orderBy(
                'students.first_name'
            )
            ->get();

        $studentIds =
            $students->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | SELECTED DATE
        |--------------------------------------------------------------------------
        */

        $selectedDate =
            $request->input('date');

        if ($selectedDate) {

            try {

                $selectedDate = Carbon::parse(
                    $selectedDate
                )->format('Y-m-d');

            } catch (\Throwable $e) {

                $selectedDate = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LATEST ATTENDANCE DATE
        |--------------------------------------------------------------------------
        */

        if (!$selectedDate) {

            $latestDate = Attendance::query()
                ->whereIn(
                    'student_id',
                    $studentIds
                )
                ->whereBetween(
                    'attendance_date',
                    [
                        $monthStart->toDateString(),
                        $monthEnd->toDateString(),
                    ]
                )
                ->whereIn(
                    DB::raw(
                        'LOWER(TRIM(status))'
                    ),
                    [
                        'present',
                        'absent',
                        'holiday',
                    ]
                )
                ->orderByDesc(
                    'attendance_date'
                )
                ->value(
                    'attendance_date'
                );

            if ($latestDate) {

                $selectedDate =
                    Carbon::parse(
                        $latestDate
                    )->format('Y-m-d');

            } else {

                if (
                    now()->gte($monthStart) &&
                    now()->lte($monthEnd)
                ) {
                    $selectedDate =
                        now()->format('Y-m-d');
                } else {
                    $selectedDate =
                        $monthStart->format('Y-m-d');
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SELECTED CARBON DATE
        |--------------------------------------------------------------------------
        */

        $selectedCarbonDate = Carbon::parse(
            $selectedDate
        )->startOfDay();

        /*
        |--------------------------------------------------------------------------
        | CHECK SUNDAY / NATIONAL HOLIDAY
        |--------------------------------------------------------------------------
        */

        $holidayName =
            $this->getHolidayName(
                $selectedCarbonDate
            );

        /*
        |--------------------------------------------------------------------------
        | AUTOMATICALLY MARK HOLIDAY
        |--------------------------------------------------------------------------
        */

        if ($holidayName) {

            foreach ($students as $student) {

                Attendance::updateOrCreate(
                    [
                        'student_id' =>
                            $student->id,

                        'attendance_date' =>
                            $selectedCarbonDate
                                ->toDateString(),
                    ],
                    [
                        'academic_year' =>
                            $student->academic_year,

                        'class' =>
                            $student->class,

                        'section' =>
                            $student->section,

                        'status' =>
                            'holiday',

                        'remarks' =>
                            $holidayName,

                        'marked_by' =>
                            auth()->id(),
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY ATTENDANCE
        |--------------------------------------------------------------------------
        */

        $attendanceRecords = Attendance::query()
            ->whereIn(
                'student_id',
                $studentIds
            )
            ->whereBetween(
                'attendance_date',
                [
                    $monthStart->toDateString(),
                    $monthEnd->toDateString(),
                ]
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE MAP
        |--------------------------------------------------------------------------
        */

        $attendanceMap = [];

        foreach ($attendanceRecords as $record) {

            $dateKey = Carbon::parse(
                $record->attendance_date
            )->format('Y-m-d');

            $attendanceMap[
                $record->student_id
            ][$dateKey] = strtolower(
                trim(
                    (string) $record->status
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY COUNTS
        |--------------------------------------------------------------------------
        */

        $presentCount = $attendanceRecords
            ->filter(function ($record) {

                return strtolower(
                    trim(
                        (string) $record->status
                    )
                ) === 'present';
            })
            ->count();

        $absentCount = $attendanceRecords
            ->filter(function ($record) {

                return strtolower(
                    trim(
                        (string) $record->status
                    )
                ) === 'absent';
            })
            ->count();

        $holidayCount = $attendanceRecords
            ->filter(function ($record) {

                return strtolower(
                    trim(
                        (string) $record->status
                    )
                ) === 'holiday';
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | SELECTED DATE ATTENDANCE
        |--------------------------------------------------------------------------
        */

        $selectedAttendance = Attendance::query()
            ->whereIn(
                'student_id',
                $studentIds
            )
            ->whereDate(
                'attendance_date',
                $selectedDate
            )
            ->get()
            ->keyBy(
                'student_id'
            );

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.attendance.student',
            compact(
                'students',
                'attendanceMap',
                'academic_year',
                'class',
                'section',
                'month',
                'monthStart',
                'monthEnd',
                'presentCount',
                'absentCount',
                'holidayCount',
                'selectedDate',
                'selectedAttendance',
                'holidayName'
            )
        );
    }


    /**
     * =========================================================
     * GET SECTIONS
     * =========================================================
     */
    public function sections(Request $request)
    {
        $class = $request->input(
            'class'
        );

        $sections = DB::table(
            'sections'
        )
            ->join(
                'classes',
                'classes.id',
                '=',
                'sections.class_id'
            )
            ->when(
                $class,
                function ($query) use ($class) {

                    $query->where(
                        'classes.class_name',
                        $class
                    );
                }
            )
            ->select([
                'sections.id',
                'sections.section_name',
            ])
            ->orderBy(
                'sections.section_name'
            )
            ->get();

        return response()->json(
            $sections
        );
    }


    /**
     * =========================================================
     * GET STUDENTS
     * =========================================================
     */
    public function students(Request $request)
    {
        $request->validate([
            'academic_year' =>
                'required',

            'class' =>
                'required',

            'section' =>
                'required',
        ]);

        $students = Student::query()
            ->where(
                'status',
                'Active'
            )
            ->where(
                'academic_year',
                $request->academic_year
            )
            ->where(
                'class',
                $request->class
            )
            ->where(
                'section',
                $request->section
            )
            ->orderBy(
                'roll_number'
            )
            ->orderBy(
                'first_name'
            )
            ->get();

        return response()->json(
            $students
        );
    }


    /**
     * =========================================================
     * STORE ATTENDANCE
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' =>
                'required|string',

            'class' =>
                'required|string',

            'section' =>
                'required|string',

            'attendance' =>
                'required|array',
        ]);

        $attendanceMonth =
            $request->input(
                'attendance_month'
            ) ?: $request->input(
                'month'
            );

        /*
        |--------------------------------------------------------------------------
        | SAVE EVERYTHING IN ONE TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated
        ) {

            foreach (
                $validated['attendance']
                as $studentId => $attendanceData
            ) {

                /*
                |--------------------------------------------------------------------------
                | MONTHLY FORMAT ONLY
                |--------------------------------------------------------------------------
                */

                if (!is_array($attendanceData)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | VERIFY STUDENT
                |--------------------------------------------------------------------------
                */

                $student = Student::query()
                    ->where(
                        'id',
                        $studentId
                    )
                    ->where(
                        'status',
                        'Active'
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
                    ->first();

                if (!$student) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | PROCESS EACH DATE
                |--------------------------------------------------------------------------
                */

                foreach (
                    $attendanceData
                    as $date => $status
                ) {

                    $status = strtolower(
                        trim(
                            (string) $status
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | DATE
                    |--------------------------------------------------------------------------
                    */

                    try {

                        $attendanceDate =
                            Carbon::createFromFormat(
                                'Y-m-d',
                                $date
                            )->startOfDay();

                    } catch (\Throwable $e) {

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SUNDAY / NATIONAL HOLIDAY
                    |--------------------------------------------------------------------------
                    |
                    | User cannot mark Present or Absent
                    | on a holiday.
                    |
                    */

                    $holidayName =
                        $this->getHolidayName(
                            $attendanceDate
                        );

                    if ($holidayName) {

                        Attendance::updateOrCreate(
                            [
                                'student_id' =>
                                    $student->id,

                                'attendance_date' =>
                                    $attendanceDate
                                        ->toDateString(),
                            ],
                            [
                                'academic_year' =>
                                    $validated[
                                        'academic_year'
                                    ],

                                'class' =>
                                    $validated['class'],

                                'section' =>
                                    $validated['section'],

                                'status' =>
                                    'holiday',

                                'remarks' =>
                                    $holidayName,

                                'marked_by' =>
                                    auth()->id(),
                            ]
                        );

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | BLANK = REMOVE RECORD
                    |--------------------------------------------------------------------------
                    */

                    if ($status === '') {

                        Attendance::query()
                            ->where(
                                'student_id',
                                $student->id
                            )
                            ->whereDate(
                                'attendance_date',
                                $attendanceDate
                                    ->toDateString()
                            )
                            ->delete();

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ONLY PRESENT / ABSENT / HOLIDAY
                    |--------------------------------------------------------------------------
                    */

                    if (!in_array(
                        $status,
                        [
                            'present',
                            'absent',
                            'holiday',
                        ],
                        true
                    )) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SAVE / UPDATE
                    |--------------------------------------------------------------------------
                    */

                    Attendance::updateOrCreate(
                        [
                            'student_id' =>
                                $student->id,

                            'attendance_date' =>
                                $attendanceDate
                                    ->toDateString(),
                        ],
                        [
                            'academic_year' =>
                                $validated[
                                    'academic_year'
                                ],

                            'class' =>
                                $validated['class'],

                            'section' =>
                                $validated['section'],

                            'status' =>
                                $status,

                            'marked_by' =>
                                auth()->id(),
                        ]
                    );
                }
            }
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        if (!$attendanceMonth) {
            $attendanceMonth =
                now()->format('Y-m');
        }

        return redirect()
            ->route(
                'admin.attendance.student.sheet',
                [
                    'academic_year' =>
                        $validated['academic_year'],

                    'class' =>
                        $validated['class'],

                    'section' =>
                        $validated['section'],

                    'month' =>
                        $attendanceMonth,
                ]
            )
            ->with(
                'success',
                'Student attendance saved successfully.'
            );
    }


    /**
     * =========================================================
     * UPDATE ATTENDANCE
     * =========================================================
     */
    public function update(
        Request $request,
        $id
    ) {
        $validated = $request->validate([
            'status' =>
                'required|in:present,absent,holiday',

            'remarks' =>
                'nullable|string|max:500',
        ]);

        $attendance =
            Attendance::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | PROTECT SUNDAY / NATIONAL HOLIDAYS
        |--------------------------------------------------------------------------
        */

        $attendanceDate =
            Carbon::parse(
                $attendance->attendance_date
            )->startOfDay();

        $holidayName =
            $this->getHolidayName(
                $attendanceDate
            );

        if ($holidayName) {

            $attendance->update([
                'status' =>
                    'holiday',

                'remarks' =>
                    $holidayName,

                'marked_by' =>
                    auth()->id(),
            ]);

            return back()->with(
                'success',
                $holidayName . ' marked as holiday.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NORMAL ATTENDANCE UPDATE
        |--------------------------------------------------------------------------
        */

        $attendance->update([
            'status' =>
                strtolower(
                    $validated['status']
                ),

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
     * =========================================================
     * ATTENDANCE REPORT
     * =========================================================
     */
    public function report(
        Request $request
    ) {
        $query = Attendance::query()
            ->with('student');

        if ($request->filled(
            'academic_year'
        )) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        if ($request->filled(
            'class'
        )) {
            $query->where(
                'class',
                $request->class
            );
        }

        if ($request->filled(
            'section'
        )) {
            $query->where(
                'section',
                $request->section
            );
        }

        if ($request->filled(
            'date'
        )) {
            $query->whereDate(
                'attendance_date',
                $request->date
            );
        }

        if ($request->filled(
            'status'
        )) {
            $query->whereRaw(
                'LOWER(status) = ?',
                [
                    strtolower(
                        $request->status
                    ),
                ]
            );
        }

        $attendance = $query
            ->orderByDesc(
                'attendance_date'
            )
            ->get();

        return view(
            'admin.attendance.report',
            compact('attendance')
        );
    }


    /**
     * =========================================================
     * STUDENT REPORT
     * =========================================================
     */
    public function studentReport(
        $studentId,
        Request $request
    ) {
        $student =
            Student::findOrFail($studentId);

        $query = Attendance::query()
            ->where(
                'student_id',
                $studentId
            );

        if ($request->filled(
            'month'
        )) {

            try {

                $date =
                    Carbon::createFromFormat(
                        'Y-m',
                        $request->month
                    );

                $query->whereBetween(
                    'attendance_date',
                    [
                        $date
                            ->copy()
                            ->startOfMonth()
                            ->toDateString(),

                        $date
                            ->copy()
                            ->endOfMonth()
                            ->toDateString(),
                    ]
                );

            } catch (\Throwable $e) {
                // Ignore invalid month.
            }
        }

        $attendance = $query
            ->orderBy(
                'attendance_date'
            )
            ->get();

        return view(
            'admin.attendance.student-report',
            compact(
                'student',
                'attendance'
            )
        );
    }


    /**
     * =========================================================
     * MONTHLY REPORT
     * =========================================================
    */
    public function monthlyReport(
        Request $request
    ) {
        $month = $request->input(
            'month',
            now()->format('Y-m')
        );

        try {

            $date =
                Carbon::createFromFormat(
                    'Y-m',
                    $month
                );

        } catch (\Throwable $e) {

            $date = now();

            $month =
                $date->format('Y-m');
        }

        $attendance = Attendance::query()
            ->with('student')
            ->whereBetween(
                'attendance_date',
                [
                    $date
                        ->copy()
                        ->startOfMonth()
                        ->toDateString(),

                    $date
                        ->copy()
                        ->endOfMonth()
                        ->toDateString(),
                ]
            )
            ->orderBy(
                'attendance_date'
            )
            ->get();

        return view(
            'admin.attendance.monthly-report',
            compact(
                'attendance',
                'month'
            )
        );
    }


    /**
     * =========================================================
     * PRINT REPORT
     * =========================================================
     */
    public function printReport(
        Request $request
    ) {
        $query = Attendance::query()
            ->with('student');

        if ($request->filled(
            'date'
        )) {
            $query->whereDate(
                'attendance_date',
                $request->date
            );
        }

        if ($request->filled(
            'academic_year'
        )) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        if ($request->filled(
            'class'
        )) {
            $query->where(
                'class',
                $request->class
            );
        }

        if ($request->filled(
            'section'
        )) {
            $query->where(
                'section',
                $request->section
            );
        }

        $attendance = $query
            ->orderBy(
                'attendance_date'
            )
            ->get();

        return view(
            'admin.attendance.print-report',
            compact('attendance')
        );
    }


    /**
     * =========================================================
     * PDF
     * =========================================================
     */
  public function pdf(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    $month = $request->input(
        'month',
        now()->format('Y-m')
    );

    $academicYear = $request->input(
        'academic_year'
    );

    $className = $request->input(
        'class'
    );

    $section = $request->input(
        'section'
    );

    /*
    |--------------------------------------------------------------------------
    | MONTH DATE
    |--------------------------------------------------------------------------
    */

    try {

        $monthDate = Carbon::createFromFormat(
            'Y-m',
            $month
        )->startOfMonth();

    } catch (\Exception $e) {

        $monthDate = now()->startOfMonth();

        $month = $monthDate->format('Y-m');
    }

    $startDate = $monthDate
        ->copy()
        ->startOfMonth();

    $endDate = $monthDate
        ->copy()
        ->endOfMonth();

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    $studentsQuery = Student::query()
        ->where('status', 'Active')
        ->select([
            'id',
            'student_id',
            'roll_number',
            'first_name',
            'middle_name',
            'last_name',
            'academic_year',
            'class',
            'section',
        ]);

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR FILTER
    |--------------------------------------------------------------------------
    */

    if (!empty($academicYear)) {

        $studentsQuery->where(
            'academic_year',
            $academicYear
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLASS FILTER
    |--------------------------------------------------------------------------
    */

    if (!empty($className)) {

        $studentsQuery->where(
            'class',
            $className
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SECTION FILTER
    |--------------------------------------------------------------------------
    */

    if (!empty($section)) {

        $studentsQuery->where(
            'section',
            $section
        );
    }

    /*
    |--------------------------------------------------------------------------
    | GET STUDENTS
    |--------------------------------------------------------------------------
    */

    $students = $studentsQuery
        ->orderBy('roll_number')
        ->orderBy('first_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */

    $attendance = collect();

    if ($students->isNotEmpty()) {

        $studentIds = $students->pluck('id');

        $attendance = Attendance::query()
            ->whereIn(
                'student_id',
                $studentIds
            )
            ->whereBetween(
                'attendance_date',
                [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ]
            )
            ->get([
                'id',
                'student_id',
                'attendance_date',
                'status',
            ])
            ->groupBy('student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    $pdf = app(
        'dompdf.wrapper'
    );

    $pdf->loadView(
        'admin.attendance.pdf',
        compact(
            'students',
            'attendance',
            'month',
            'academicYear',
            'className',
            'section',
            'startDate',
            'endDate'
        )
    );

    return $pdf->download(
        'student-attendance-' .
        $month .
        '.pdf'
    );
}
    /**
     * =========================================================
     * EXCEL
     * =========================================================
     */
 
/**
 * =========================================================
 * EXCEL
 * =========================================================
 */
public function excel(
    Request $request
) {
    $month = $request->input(
        'month',
        now()->format('Y-m')
    );

    $academicYear = $request->input(
        'academic_year'
    );

    $className = $request->input(
        'class'
    );

    $section = $request->input(
        'section'
    );

    return Excel::download(
        new StudentAttendanceExport(
            $month,
            $academicYear,
            $className,
            $section
        ),
        'student-attendance-' . $month . '.xlsx'
    );
}

    /**
     * =========================================================
     * STUDENT NAME
     * =========================================================
     */
    public function studentName(
        $id
    ) {
        $student =
            Student::find($id);

        if (!$student) {
            return response()->json(
                [
                    'name' =>
                        'Student Not Found',
                ],
                404
            );
        }

        return response()->json(
            [
                'name' => trim(
                    $student->first_name .
                    ' ' .
                    ($student->middle_name ?? '') .
                    ' ' .
                    $student->last_name
                ),
            ]
        );
    }


    /**
     * =========================================================
     * NATIONAL HOLIDAYS
     * =========================================================
     *
     * Fixed Indian National Holidays:
     *
     * 26 January  - Republic Day
     * 15 August   - Independence Day
     * 2 October   - Gandhi Jayanti
     *
     */
    private function nationalHolidays(): array
    {
        return [

            '01-26' =>
                'Republic Day',

            '08-15' =>
                'Independence Day',

            '10-02' =>
                'Gandhi Jayanti',
        ];
    }


    /**
     * =========================================================
     * GET HOLIDAY NAME
     * =========================================================
     *
     * Returns:
     *
     * Sunday
     * Republic Day
     * Independence Day
     * Gandhi Jayanti
     *
     * Otherwise returns null.
     *
     */
    private function getHolidayName(
        Carbon $date
    ): ?string {

        /*
        |--------------------------------------------------------------------------
        | SUNDAY
        |--------------------------------------------------------------------------
        */

        if ($date->isSunday()) {
            return 'Sunday';
        }

        /*
        |--------------------------------------------------------------------------
        | NATIONAL HOLIDAY
        |--------------------------------------------------------------------------
        */

        $key =
            $date->format('m-d');

        return
            $this->nationalHolidays()[$key]
            ?? null;
    }


    /**
     * =========================================================
     * CLASS KEY
     * =========================================================
     */
    private function makeClassKey(
        $academicYear,
        $class,
        $section
    ) {
        return strtolower(
            trim(
                (string) $academicYear
            ) .
            '|' .
            trim(
                (string) $class
            ) .
            '|' .
            trim(
                (string) $section
            )
        );
    }
}