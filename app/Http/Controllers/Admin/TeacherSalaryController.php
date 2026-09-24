<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\TeacherAttendance;
use App\Models\LeaveApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TeacherSalaryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $salaries = TeacherSalary::with('teacher')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.salary.index',
            compact('salaries')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SALARY
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        return view(
            'admin.salary.create',
            compact('teachers')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GET MONTHLY ATTENDANCE
    |--------------------------------------------------------------------------
    */

    private function getMonthlyAttendance($teacherId, $salaryMonth)
    {
        $startDate = Carbon::parse($salaryMonth)
            ->startOfMonth();

        $endDate = Carbon::parse($salaryMonth)
            ->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
        |--------------------------------------------------------------------------
        */

        $attendance = TeacherAttendance::where(
            'teacher_id',
            $teacherId
        )
            ->whereBetween(
                'attendance_date',
                [
                    $startDate->toDateString(),
                    $endDate->toDateString()
                ]
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE COUNTS
        |--------------------------------------------------------------------------
        */

        $presentDays = $attendance
            ->whereIn('status', [
                'Present',
                'Late'
            ])
            ->count();

        $absentDays = $attendance
            ->where('status', 'Absent')
            ->count();

        $halfDays = $attendance
            ->where('status', 'Half Day')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | LEAVE DAYS
        |--------------------------------------------------------------------------
        */

        $leaveDays = $this->getMonthlyLeaveDays(
            $teacherId,
            $salaryMonth
        );

        /*
        |--------------------------------------------------------------------------
        | OVERTIME HOURS
        |--------------------------------------------------------------------------
        */

        $overtimeHours = (float) $attendance
            ->sum('overtime_hours');

        /*
        |--------------------------------------------------------------------------
        | RECORDED WORKING DAYS
        |--------------------------------------------------------------------------
        */

        $recordedDays =
            $presentDays +
            $absentDays +
            $halfDays;

        /*
        |--------------------------------------------------------------------------
        | EFFECTIVE PRESENT DAYS
        |--------------------------------------------------------------------------
        */

        $effectivePresentDays =
            $presentDays +
            ($halfDays * 0.5);

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $attendancePercentage = 0;

        if ($recordedDays > 0) {
            $attendancePercentage =
                (
                    $effectivePresentDays /
                    $recordedDays
                ) * 100;
        }

        /*
        |--------------------------------------------------------------------------
        | DEDUCTION RATE
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Attendance = 95%
        | Deduction Rate = 5%
        |
        */

        $deductionRate = max(
            0,
            min(
                100,
                100 - $attendancePercentage
            )
        );

        return [
            'working_days' =>
                $recordedDays,

            'present_days' =>
                $presentDays,

            'absent_days' =>
                $absentDays,

            'half_days' =>
                $halfDays,

            'leave_days' =>
                $leaveDays,

            'overtime_hours' =>
                round($overtimeHours, 2),

            'attendance_percentage' =>
                round($attendancePercentage, 2),

            'deduction_rate' =>
                round($deductionRate, 2),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MONTHLY LEAVE DAYS
    |--------------------------------------------------------------------------
    */

    private function getMonthlyLeaveDays($teacherId, $salaryMonth)
    {
        $startDate = Carbon::parse($salaryMonth)
            ->startOfMonth();

        $endDate = Carbon::parse($salaryMonth)
            ->endOfMonth();

        return LeaveApplication::where(
            'teacher_id',
            $teacherId
        )
            ->where(
                'status',
                'Approved'
            )
            ->whereDate(
                'from_date',
                '<=',
                $endDate
            )
            ->whereDate(
                'to_date',
                '>=',
                $startDate
            )
            ->get()
            ->sum(function ($leave) use (
                $startDate,
                $endDate
            ) {
                $from = Carbon::parse(
                    $leave->from_date
                );

                $to = Carbon::parse(
                    $leave->to_date
                );

                if ($from->lt($startDate)) {
                    $from = $startDate->copy();
                }

                if ($to->gt($endDate)) {
                    $to = $endDate->copy();
                }

                return $from->diffInDays($to) + 1;
            });
    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE DATA
    |--------------------------------------------------------------------------
    */

    public function attendanceData(Request $request)
    {
        $request->validate([
            'teacher_id' =>
                'required|exists:teachers,id',

            'salary_month' =>
                'required|string',
        ]);

        $attendance = $this->getMonthlyAttendance(
            $request->teacher_id,
            $request->salary_month
        );

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $attendanceAllowance = 0;

        if (
            $attendance['attendance_percentage'] >= 95
        ) {
            $attendanceAllowance = 500;
        }

        /*
        |--------------------------------------------------------------------------
        | OVERTIME
        |--------------------------------------------------------------------------
        */

        $overtimeRate = 200;

        $overtimeAmount =
            $attendance['overtime_hours'] *
            $overtimeRate;

        /*
        |--------------------------------------------------------------------------
        | RETURN DATA
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'working_days' =>
                $attendance['working_days'],

            'present_days' =>
                $attendance['present_days'],

            'absent_days' =>
                $attendance['absent_days'],

            'half_days' =>
                $attendance['half_days'],

            'leave_days' =>
                $attendance['leave_days'],

            'attendance_percentage' =>
                $attendance['attendance_percentage'],

            'deduction_rate' =>
                $attendance['deduction_rate'],

            'overtime_hours' =>
                $attendance['overtime_hours'],

            'overtime_rate' =>
                $overtimeRate,

            'overtime_amount' =>
                round($overtimeAmount, 2),

            'attendance_allowance' =>
                $attendanceAllowance,

            'attendance_deduction' =>
                0,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE PAYROLL
    |--------------------------------------------------------------------------
    */

    private function calculatePayroll(
        $teacherId,
        $salaryMonth,
        $basic,
        $allowances
    ) {
        $attendance = $this->getMonthlyAttendance(
            $teacherId,
            $salaryMonth
        );

        /*
        |--------------------------------------------------------------------------
        | DEDUCTION RATE
        |--------------------------------------------------------------------------
        */

        $deductionRate =
            $attendance['deduction_rate'];

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE DEDUCTION
        |--------------------------------------------------------------------------
        */

        $attendanceDeduction =
            ($basic * $deductionRate) / 100;

        /*
        |--------------------------------------------------------------------------
        | OVERTIME
        |--------------------------------------------------------------------------
        */

        $overtimeRate = 200;

        $overtimeAmount =
            $attendance['overtime_hours'] *
            $overtimeRate;

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $attendanceAllowance = 0;

        if (
            $attendance['attendance_percentage'] >= 95
        ) {
            $attendanceAllowance = 500;
        }

        /*
        |--------------------------------------------------------------------------
        | GROSS SALARY
        |--------------------------------------------------------------------------
        */

        $grossSalary =
            $basic +
            $allowances +
            $attendanceAllowance +
            $overtimeAmount;

        /*
        |--------------------------------------------------------------------------
        | TOTAL DEDUCTIONS
        |--------------------------------------------------------------------------
        */

        $totalDeductions =
            $attendanceDeduction;

        /*
        |--------------------------------------------------------------------------
        | NET SALARY
        |--------------------------------------------------------------------------
        */

        $netSalary =
            $grossSalary -
            $totalDeductions;

        $netSalary = max(
            0,
            $netSalary
        );

        return [
            'working_days' =>
                $attendance['working_days'],

            'present_days' =>
                $attendance['present_days'],

            'absent_days' =>
                $attendance['absent_days'],

            'half_days' =>
                $attendance['half_days'],

            'leave_days' =>
                $attendance['leave_days'],

            'overtime_hours' =>
                $attendance['overtime_hours'],

            'overtime_rate' =>
                $overtimeRate,

            'overtime_amount' =>
                round($overtimeAmount, 2),

            'attendance_percentage' =>
                $attendance['attendance_percentage'],

            'deduction_rate' =>
                round($deductionRate, 2),

            'deduction_amount' =>
                round($attendanceDeduction, 2),

            'attendance_deduction' =>
                round($attendanceDeduction, 2),

            'attendance_allowance' =>
                round($attendanceAllowance, 2),

            'gross_salary' =>
                round($grossSalary, 2),

            'deductions' =>
                round($totalDeductions, 2),

            'net_salary' =>
                round($netSalary, 2),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | STORE SALARY
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' =>
                'required|exists:teachers,id',

            'salary_month' =>
                'required|string|max:20',

            'basic_salary' =>
                'required|numeric|min:0',

            'allowances' =>
                'nullable|numeric|min:0',

            'payment_status' =>
                'required|in:Pending,Paid',

            'payment_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string|max:1000',
        ]);

        $basic =
            (float) (
                $validated['basic_salary'] ?? 0
            );

        $allowances =
            (float) (
                $validated['allowances'] ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | CALCULATE PAYROLL
        |--------------------------------------------------------------------------
        */

        $payroll = $this->calculatePayroll(
            $validated['teacher_id'],
            $validated['salary_month'],
            $basic,
            $allowances
        );

        /*
        |--------------------------------------------------------------------------
        | PREPARE SALARY DATA
        |--------------------------------------------------------------------------
        */

        $salaryData = [
            'teacher_id' =>
                $validated['teacher_id'],

            'salary_month' =>
                $validated['salary_month'],

            'basic_salary' =>
                round($basic, 2),

            'allowances' =>
                round($allowances, 2),

            'working_days' =>
                $payroll['working_days'],

            'present_days' =>
                $payroll['present_days'],

            'absent_days' =>
                $payroll['absent_days'],

            'half_days' =>
                $payroll['half_days'],

            'leave_days' =>
                $payroll['leave_days'],

            'overtime_hours' =>
                $payroll['overtime_hours'],

            'overtime_amount' =>
                $payroll['overtime_amount'],

            'attendance_allowance' =>
                $payroll['attendance_allowance'],

            'attendance_deduction' =>
                $payroll['attendance_deduction'],

            'gross_salary' =>
                $payroll['gross_salary'],

            'deductions' =>
                $payroll['deductions'],

            'net_salary' =>
                $payroll['net_salary'],

            'payment_status' =>
                $validated['payment_status'],

            'payment_date' =>
                $validated['payment_date'] ?? null,

            'remarks' =>
                $validated['remarks'] ?? null,
        ];

        /*
        |--------------------------------------------------------------------------
        | SAVE DEDUCTION RATE
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'teacher_salaries',
                'deduction_rate'
            )
        ) {
            $salaryData['deduction_rate'] =
                $payroll['deduction_rate'];
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE SALARY
        |--------------------------------------------------------------------------
        */

        $salary = new TeacherSalary();

        foreach ($salaryData as $field => $value) {
            $salary->{$field} = $value;
        }

        $salary->save();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.teachers.salary.index'
            )
            ->with(
                'success',
                'Salary generated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SALARY
    |--------------------------------------------------------------------------
    */

    public function show(
        TeacherSalary $teacherSalary
    ) {
        $teacherSalary->load('teacher');

        return view(
            'admin.salary.show',
            compact('teacherSalary')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT SALARY
    |--------------------------------------------------------------------------
    */

    public function edit(
        TeacherSalary $teacherSalary
    ) {
        $teachers = Teacher::orderBy(
            'first_name'
        )->get();

        /*
        |--------------------------------------------------------------------------
        | RECALCULATE PAYROLL
        |--------------------------------------------------------------------------
        */

        $payroll = $this->calculatePayroll(
            $teacherSalary->teacher_id,
            $teacherSalary->salary_month,
            (float) $teacherSalary->basic_salary,
            (float) (
                $teacherSalary->allowances ?? 0
            )
        );

        /*
        |--------------------------------------------------------------------------
        | DISPLAY CALCULATED VALUES
        |--------------------------------------------------------------------------
        |
        | These values are only used for displaying data
        | on the edit page.
        |
        | attendance_percentage and overtime_rate are
        | NOT database columns.
        |
        */

        $teacherSalary->attendance_percentage =
            $payroll['attendance_percentage'];

        $teacherSalary->deduction_rate =
            $teacherSalary->deduction_rate
            ?? $payroll['deduction_rate'];

        $teacherSalary->overtime_hours =
            $teacherSalary->overtime_hours
            ?? $payroll['overtime_hours'];

        $teacherSalary->overtime_amount =
            $teacherSalary->overtime_amount
            ?? $payroll['overtime_amount'];

        $teacherSalary->attendance_allowance =
            $teacherSalary->attendance_allowance
            ?? $payroll['attendance_allowance'];

        $teacherSalary->attendance_deduction =
            $teacherSalary->attendance_deduction
            ?? $payroll['attendance_deduction'];

        $teacherSalary->gross_salary =
            $teacherSalary->gross_salary
            ?? $payroll['gross_salary'];

        $teacherSalary->net_salary =
            $teacherSalary->net_salary
            ?? $payroll['net_salary'];

        return view(
            'admin.salary.edit',
            compact(
                'teacherSalary',
                'teachers',
                'payroll'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SALARY
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TeacherSalary $teacherSalary
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'teacher_id' =>
                'required|exists:teachers,id',

            'salary_month' =>
                'required|date',

            'basic_salary' =>
                'required|numeric|min:0',

            'allowances' =>
                'nullable|numeric|min:0',

            'working_days' =>
                'nullable|numeric|min:0',

            'present_days' =>
                'nullable|numeric|min:0',

            'absent_days' =>
                'nullable|numeric|min:0',

            'half_days' =>
                'nullable|numeric|min:0',

            'leave_days' =>
                'nullable|numeric|min:0',

            /*
            |--------------------------------------------------------------------------
            | DISPLAY-ONLY FIELD
            |--------------------------------------------------------------------------
            |
            | attendance_percentage is accepted for validation
            | but is NOT saved because there is no database column.
            |
            */

            'attendance_percentage' =>
                'nullable|numeric|min:0|max:100',

            'deduction_rate' =>
                'nullable|numeric|min:0|max:100',

            'attendance_deduction' =>
                'nullable|numeric|min:0',

            'overtime_hours' =>
                'nullable|numeric|min:0',

            /*
            |--------------------------------------------------------------------------
            | DISPLAY-ONLY FIELD
            |--------------------------------------------------------------------------
            |
            | overtime_rate is NOT saved because there is
            | currently no overtime_rate column.
            |
            */

            'overtime_rate' =>
                'nullable|numeric|min:0',

            'overtime_amount' =>
                'nullable|numeric|min:0',

            'attendance_allowance' =>
                'nullable|numeric|min:0',

            'gross_salary' =>
                'nullable|numeric|min:0',

            'deductions' =>
                'nullable|numeric|min:0',

            'net_salary' =>
                'nullable|numeric|min:0',

            'payment_status' =>
                'required|in:Pending,Paid',

            'payment_date' =>
                'nullable|date',

            'remarks' =>
                'nullable|string|max:1000',
        ]);

        /*
        |--------------------------------------------------------------------------
        | BASIC VALUES
        |--------------------------------------------------------------------------
        */

        $basicSalary =
            (float) (
                $validated['basic_salary'] ?? 0
            );

        $allowances =
            (float) (
                $validated['allowances'] ?? 0
            );

        $workingDays =
            (float) (
                $validated['working_days'] ?? 0
            );

        $presentDays =
            (float) (
                $validated['present_days'] ?? 0
            );

        $absentDays =
            (float) (
                $validated['absent_days'] ?? 0
            );

        $halfDays =
            (float) (
                $validated['half_days'] ?? 0
            );

        $leaveDays =
            (float) (
                $validated['leave_days'] ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE PERCENTAGE
        |--------------------------------------------------------------------------
        |
        | Display only.
        | NOT saved to database.
        |
        */

        $attendancePercentage =
            (float) (
                $validated['attendance_percentage'] ?? 0
            );

        $attendancePercentage = max(
            0,
            min(
                100,
                $attendancePercentage
            )
        );

        /*
        |--------------------------------------------------------------------------
        | DEDUCTION RATE
        |--------------------------------------------------------------------------
        */

        $deductionRate =
            (float) (
                $validated['deduction_rate'] ?? 0
            );

        $deductionRate = max(
            0,
            min(
                100,
                $deductionRate
            )
        );

        /*
        |--------------------------------------------------------------------------
        | OVERTIME
        |--------------------------------------------------------------------------
        */

        $overtimeHours =
            (float) (
                $validated['overtime_hours'] ?? 0
            );

        $overtimeAmount =
            (float) (
                $validated['overtime_amount'] ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $attendanceAllowance =
            (float) (
                $validated['attendance_allowance'] ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE DEDUCTION
        |--------------------------------------------------------------------------
        */

        $attendanceDeduction =
            (
                $basicSalary *
                $deductionRate
            ) / 100;

        /*
        |--------------------------------------------------------------------------
        | TOTAL DEDUCTIONS
        |--------------------------------------------------------------------------
        */

        $totalDeductions =
            $attendanceDeduction;

        /*
        |--------------------------------------------------------------------------
        | GROSS SALARY
        |--------------------------------------------------------------------------
        */

        $grossSalary =
            $basicSalary +
            $allowances +
            $attendanceAllowance +
            $overtimeAmount;

        /*
        |--------------------------------------------------------------------------
        | NET SALARY
        |--------------------------------------------------------------------------
        */

        $netSalary =
            $grossSalary -
            $totalDeductions;

        $netSalary = max(
            0,
            $netSalary
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE REAL DATABASE COLUMNS ONLY
        |--------------------------------------------------------------------------
        */

        $teacherSalary->teacher_id =
            $validated['teacher_id'];

        $teacherSalary->salary_month =
            $validated['salary_month'];

        $teacherSalary->working_days =
            $workingDays;

        $teacherSalary->present_days =
            $presentDays;

        $teacherSalary->absent_days =
            $absentDays;

        $teacherSalary->half_days =
            $halfDays;

        $teacherSalary->leave_days =
            $leaveDays;

        $teacherSalary->overtime_hours =
            $overtimeHours;

        $teacherSalary->overtime_amount =
            round($overtimeAmount, 2);

        $teacherSalary->basic_salary =
            round($basicSalary, 2);

        $teacherSalary->allowances =
            round($allowances, 2);

        $teacherSalary->attendance_allowance =
            round($attendanceAllowance, 2);

        $teacherSalary->deduction_rate =
            round($deductionRate, 2);

        $teacherSalary->deductions =
            round($totalDeductions, 2);

        $teacherSalary->attendance_deduction =
            round($attendanceDeduction, 2);

        $teacherSalary->gross_salary =
            round($grossSalary, 2);

        $teacherSalary->net_salary =
            round($netSalary, 2);

        $teacherSalary->payment_status =
            $validated['payment_status'];

        $teacherSalary->payment_date =
            $validated['payment_date'] ?? null;

        $teacherSalary->remarks =
            $validated['remarks'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        $teacherSalary->save();

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.teachers.salary.index'
            )
            ->with(
                'success',
                'Teacher salary updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE SALARY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        TeacherSalary $teacherSalary
    ) {
        $teacherSalary->delete();

        return redirect()
            ->route(
                'admin.teachers.salary.index'
            )
            ->with(
                'success',
                'Salary deleted successfully.'
            );
    }
}

