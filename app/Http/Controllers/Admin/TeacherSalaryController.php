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
        $startDate = Carbon::parse($salaryMonth)->startOfMonth();
        $endDate = Carbon::parse($salaryMonth)->endOfMonth();

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

        $leaveDays = $this->getMonthlyLeaveDays(
            $teacherId,
            $salaryMonth
        );

        $overtimeHours = (float) $attendance
            ->sum('overtime_hours');

        $recordedDays =
            $presentDays +
            $absentDays +
            $halfDays;

        $effectivePresentDays =
            $presentDays +
            ($halfDays * 0.5);

        $attendancePercentage = 0;

        if ($recordedDays > 0) {
            $attendancePercentage =
                ($effectivePresentDays / $recordedDays) * 100;
        }

        $deductionRate = max(
            0,
            min(
                100,
                100 - $attendancePercentage
            )
        );

        return [
            'working_days' => $recordedDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'half_days' => $halfDays,
            'leave_days' => $leaveDays,
            'overtime_hours' => round($overtimeHours, 2),
            'attendance_percentage' => round(
                $attendancePercentage,
                2
            ),
            'deduction_rate' => round(
                $deductionRate,
                2
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MONTHLY LEAVE DAYS
    |--------------------------------------------------------------------------
    */

    private function getMonthlyLeaveDays(
        $teacherId,
        $salaryMonth
    ) {
        $startDate = Carbon::parse($salaryMonth)->startOfMonth();
        $endDate = Carbon::parse($salaryMonth)->endOfMonth();

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

        $attendanceAllowance = 0;

        if (
            $attendance['attendance_percentage'] >= 95
        ) {
            $attendanceAllowance = 500;
        }

        $overtimeRate = 200;

        $overtimeAmount =
            $attendance['overtime_hours'] *
            $overtimeRate;

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

        $deductionRate =
            $attendance['deduction_rate'];

        $attendanceDeduction =
            ($basic * $deductionRate) / 100;

        $overtimeRate = 200;

        $overtimeAmount =
            $attendance['overtime_hours'] *
            $overtimeRate;

        $attendanceAllowance = 0;

        if (
            $attendance['attendance_percentage'] >= 95
        ) {
            $attendanceAllowance = 500;
        }

        $grossSalary =
            $basic +
            $allowances +
            $attendanceAllowance +
            $overtimeAmount;

        $totalDeductions =
            $attendanceDeduction;

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

        $basic = (float) (
            $validated['basic_salary'] ?? 0
        );

        $allowances = (float) (
            $validated['allowances'] ?? 0
        );

        $payroll = $this->calculatePayroll(
            $validated['teacher_id'],
            $validated['salary_month'],
            $basic,
            $allowances
        );

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

        if (
            Schema::hasColumn(
                'teacher_salaries',
                'deduction_rate'
            )
        ) {
            $salaryData['deduction_rate'] =
                $payroll['deduction_rate'];
        }

        $salary = new TeacherSalary();

        foreach ($salaryData as $field => $value) {
            $salary->{$field} = $value;
        }

        $salary->save();

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
        TeacherSalary $salary
    ) {
        $salary->load('teacher');

        return view(
            'admin.salary.show',
            [
                'teacherSalary' => $salary
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT SALARY
    |--------------------------------------------------------------------------
    */

    public function edit(
        TeacherSalary $salary
    ) {
        $teachers = Teacher::orderBy(
            'first_name'
        )->get();

        $payroll = $this->calculatePayroll(
            $salary->teacher_id,
            $salary->salary_month,
            (float) $salary->basic_salary,
            (float) (
                $salary->allowances ?? 0
            )
        );

        $salary->attendance_percentage =
            $payroll['attendance_percentage'];

        $salary->deduction_rate =
            $salary->deduction_rate
            ?? $payroll['deduction_rate'];

        $salary->overtime_hours =
            $salary->overtime_hours
            ?? $payroll['overtime_hours'];

        $salary->overtime_amount =
            $salary->overtime_amount
            ?? $payroll['overtime_amount'];

        $salary->attendance_allowance =
            $salary->attendance_allowance
            ?? $payroll['attendance_allowance'];

        $salary->attendance_deduction =
            $salary->attendance_deduction
            ?? $payroll['attendance_deduction'];

        $salary->gross_salary =
            $salary->gross_salary
            ?? $payroll['gross_salary'];

        $salary->net_salary =
            $salary->net_salary
            ?? $payroll['net_salary'];

        return view(
            'admin.salary.edit',
            [
                'teacherSalary' => $salary,
                'teachers' => $teachers,
                'payroll' => $payroll
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SALARY
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TeacherSalary $salary
    ) {
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

            'attendance_percentage' =>
                'nullable|numeric|min:0|max:100',

            'deduction_rate' =>
                'nullable|numeric|min:0|max:100',

            'attendance_deduction' =>
                'nullable|numeric|min:0',

            'overtime_hours' =>
                'nullable|numeric|min:0',

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

        $basicSalary = (float) (
            $validated['basic_salary'] ?? 0
        );

        $allowances = (float) (
            $validated['allowances'] ?? 0
        );

        $workingDays = (float) (
            $validated['working_days'] ?? 0
        );

        $presentDays = (float) (
            $validated['present_days'] ?? 0
        );

        $absentDays = (float) (
            $validated['absent_days'] ?? 0
        );

        $halfDays = (float) (
            $validated['half_days'] ?? 0
        );

        $leaveDays = (float) (
            $validated['leave_days'] ?? 0
        );

        $deductionRate = (float) (
            $validated['deduction_rate'] ?? 0
        );

        $deductionRate = max(
            0,
            min(
                100,
                $deductionRate
            )
        );

        $overtimeHours = (float) (
            $validated['overtime_hours'] ?? 0
        );

        $overtimeAmount = (float) (
            $validated['overtime_amount'] ?? 0
        );

        $attendanceAllowance = (float) (
            $validated['attendance_allowance'] ?? 0
        );

        $attendanceDeduction =
            (
                $basicSalary *
                $deductionRate
            ) / 100;

        $totalDeductions =
            $attendanceDeduction;

        $grossSalary =
            $basicSalary +
            $allowances +
            $attendanceAllowance +
            $overtimeAmount;

        $netSalary =
            $grossSalary -
            $totalDeductions;

        $netSalary = max(
            0,
            $netSalary
        );

        $salary->teacher_id =
            $validated['teacher_id'];

        $salary->salary_month =
            $validated['salary_month'];

        $salary->working_days =
            $workingDays;

        $salary->present_days =
            $presentDays;

        $salary->absent_days =
            $absentDays;

        $salary->half_days =
            $halfDays;

        $salary->leave_days =
            $leaveDays;

        $salary->overtime_hours =
            $overtimeHours;

        $salary->overtime_amount =
            round($overtimeAmount, 2);

        $salary->basic_salary =
            round($basicSalary, 2);

        $salary->allowances =
            round($allowances, 2);

        $salary->attendance_allowance =
            round($attendanceAllowance, 2);

        if (
            Schema::hasColumn(
                'teacher_salaries',
                'deduction_rate'
            )
        ) {
            $salary->deduction_rate =
                round($deductionRate, 2);
        }

        $salary->deductions =
            round($totalDeductions, 2);

        $salary->attendance_deduction =
            round($attendanceDeduction, 2);

        $salary->gross_salary =
            round($grossSalary, 2);

        $salary->net_salary =
            round($netSalary, 2);

        $salary->payment_status =
            $validated['payment_status'];

        $salary->payment_date =
            $validated['payment_date'] ?? null;

        $salary->remarks =
            $validated['remarks'] ?? null;

        $salary->save();

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
        TeacherSalary $salary
    ) {
        $salary->delete();

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
