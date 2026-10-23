<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class TeacherSalaryController extends Controller
{
    /**
     * Display teacher salary records.
     */
    public function index()
    {
        $salaries = TeacherSalary::with('teacher')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.salary.index', compact('salaries'));
    }

    /**
     * Show the create salary form.
     */
    public function create()
    {
        $teachers = Teacher::where('status', 'Active')
            ->orderBy('first_name')
            ->get();

        return view('admin.salary.create', compact('teachers'));
    }

    /**
     * Store a salary record and calculate payroll.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'salary_month' => ['required', 'date_format:Y-m'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => [
                'required',
                Rule::in(['Pending', 'Paid']),
            ],
            'payment_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $basic = (float) $validated['basic_salary'];
        $allowances = (float) ($validated['allowances'] ?? 0);

        $payroll = $this->calculatePayroll(
            (int) $validated['teacher_id'],
            $validated['salary_month'],
            $basic,
            $allowances
        );

        $salary = new TeacherSalary();

        $this->fillSupportedColumns($salary, [
            'teacher_id' => $validated['teacher_id'],
            'salary_month' => $validated['salary_month'],
            'basic_salary' => round($basic, 2),
            'allowances' => round($allowances, 2),
            'working_days' => $payroll['working_days'],
            'present_days' => $payroll['present_days'],
            'absent_days' => $payroll['absent_days'],
            'half_days' => $payroll['half_days'],
            'leave_days' => $payroll['leave_days'],
            'overtime_hours' => $payroll['overtime_hours'],
            'overtime_amount' => $payroll['overtime_amount'],
            'attendance_allowance' => $payroll['attendance_allowance'],
            'attendance_deduction' => $payroll['attendance_deduction'],
            'deduction_rate' => $payroll['deduction_rate'],
            'gross_salary' => $payroll['gross_salary'],
            'deductions' => $payroll['deductions'],
            'net_salary' => $payroll['net_salary'],
            'payment_status' => $validated['payment_status'],
            'payment_date' => $validated['payment_date'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $salary->save();

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with('success', 'Salary generated successfully.');
    }

    /**
     * Return attendance and payroll figures for the create form.
     */
    public function attendanceData(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'salary_month' => ['required', 'date_format:Y-m'],
        ]);

        $attendance = $this->getMonthlyAttendance(
            (int) $validated['teacher_id'],
            $validated['salary_month']
        );

        $overtimeRate = 200;
        $attendanceAllowance =
            $attendance['attendance_percentage'] >= 95 ? 500 : 0;

        return response()->json([
            'success' => true,
            'working_days' => $attendance['working_days'],
            'present_days' => $attendance['present_days'],
            'absent_days' => $attendance['absent_days'],
            'half_days' => $attendance['half_days'],
            'leave_days' => $attendance['leave_days'],
            'attendance_percentage' => $attendance['attendance_percentage'],
            'deduction_rate' => $attendance['deduction_rate'],
            'overtime_hours' => $attendance['overtime_hours'],
            'overtime_rate' => $overtimeRate,
            'overtime_amount' => round(
                $attendance['overtime_hours'] * $overtimeRate,
                2
            ),
            'attendance_allowance' => $attendanceAllowance,
            'attendance_deduction' => 0,
        ]);
    }

    /**
     * Calculate monthly attendance for a teacher.
     */
    private function getMonthlyAttendance(
        int $teacherId,
        string $salaryMonth
    ): array {
        $month = Carbon::createFromFormat('!Y-m', $salaryMonth);
        $start = $month->copy()->startOfMonth()->toDateString();
        $end = $month->copy()->endOfMonth()->toDateString();

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->whereBetween('attendance_date', [$start, $end])
            ->get();

        $present = $attendance
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        $absent = $attendance->where('status', 'Absent')->count();
        $halfDays = $attendance->where('status', 'Half Day')->count();

        $workingDays = $present + $absent + $halfDays;
        $effectivePresent = $present + ($halfDays * 0.5);

        $percentage = $workingDays > 0
            ? ($effectivePresent / $workingDays) * 100
            : 0;

        return [
            'working_days' => $workingDays,
            'present_days' => $present,
            'absent_days' => $absent,
            'half_days' => $halfDays,
            'leave_days' => $this->getMonthlyLeaveDays(
                $teacherId,
                $salaryMonth
            ),
            'overtime_hours' => round(
                (float) $attendance->sum('overtime_hours'),
                2
            ),
            'attendance_percentage' => round($percentage, 2),
            'deduction_rate' => round(
                max(0, min(100, 100 - $percentage)),
                2
            ),
        ];
    }

    /**
     * Count approved leave days overlapping the selected month.
     */
    private function getMonthlyLeaveDays(
        int $teacherId,
        string $salaryMonth
    ): int {
        $month = Carbon::createFromFormat('!Y-m', $salaryMonth);
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $leaves = LeaveApplication::where('teacher_id', $teacherId)
            ->where('status', 'Approved')
            ->whereDate('from_date', '<=', $end->toDateString())
            ->whereDate('to_date', '>=', $start->toDateString())
            ->get();

        return (int) $leaves->sum(function ($leave) use ($start, $end) {
            $from = Carbon::parse($leave->from_date);
            $to = Carbon::parse($leave->to_date);

            if ($from->lt($start)) {
                $from = $start->copy();
            }

            if ($to->gt($end)) {
                $to = $end->copy();
            }

            return (int) $from->diffInDays($to) + 1;
        });
    }

    /**
     * Calculate salary using attendance, overtime and deductions.
     */
    private function calculatePayroll(
        int $teacherId,
        string $salaryMonth,
        float $basic,
        float $allowances
    ): array {
        $attendance = $this->getMonthlyAttendance(
            $teacherId,
            $salaryMonth
        );

        $deductionRate = $attendance['deduction_rate'];
        $attendanceDeduction = ($basic * $deductionRate) / 100;

        $overtimeRate = 200;
        $overtimeAmount =
            $attendance['overtime_hours'] * $overtimeRate;

        $attendanceAllowance =
            $attendance['attendance_percentage'] >= 95 ? 500 : 0;

        $gross = $basic
            + $allowances
            + $attendanceAllowance
            + $overtimeAmount;

        $deductions = $attendanceDeduction;
        $net = max(0, $gross - $deductions);

        return [
            ...$attendance,
            'overtime_rate' => $overtimeRate,
            'overtime_amount' => round($overtimeAmount, 2),
            'deduction_rate' => round($deductionRate, 2),
            'deduction_amount' => round($attendanceDeduction, 2),
            'attendance_deduction' => round($attendanceDeduction, 2),
            'attendance_allowance' => round($attendanceAllowance, 2),
            'gross_salary' => round($gross, 2),
            'deductions' => round($deductions, 2),
            'net_salary' => round($net, 2),
        ];
    }

    /**
     * Assign only columns that exist in the current database schema.
     */
    private function fillSupportedColumns(
        TeacherSalary $salary,
        array $data
    ): void {
        $columns = Schema::getColumnListing('teacher_salaries');

        foreach ($data as $field => $value) {
            if (in_array($field, $columns, true)) {
                $salary->{$field} = $value;
            }
        }
    }

    /**
     * Display a salary record.
     */
    public function show(TeacherSalary $teacherSalary)
    {
        $teacherSalary->load('teacher');

        return view('admin.salary.show', compact('teacherSalary'));
    }

    /**
     * Show the edit form and calculated payroll figures.
     */
    public function edit(TeacherSalary $teacherSalary)
    {
        $teachers = Teacher::orderBy('first_name')->get();

        $month = Carbon::parse($teacherSalary->salary_month)
            ->format('Y-m');

        $payroll = $this->calculatePayroll(
            (int) $teacherSalary->teacher_id,
            $month,
            (float) $teacherSalary->basic_salary,
            (float) ($teacherSalary->allowances ?? 0)
        );

        foreach ([
            'attendance_percentage',
            'deduction_rate',
            'overtime_hours',
            'overtime_amount',
            'attendance_allowance',
            'attendance_deduction',
            'gross_salary',
            'net_salary',
        ] as $field) {
            if (
                $teacherSalary->{$field} === null
                && array_key_exists($field, $payroll)
            ) {
                $teacherSalary->{$field} = $payroll[$field];
            }
        }

        return view(
            'admin.salary.edit',
            compact('teacherSalary', 'teachers', 'payroll')
        );
    }

    /**
     * Update salary inputs and recalculate derived values server-side.
     */
    public function update(
        Request $request,
        TeacherSalary $teacherSalary
    ) {
        $validated = $request->validate([
            'teacher_id' => ['required', 'exists:teachers,id'],
            'salary_month' => ['required', 'date_format:Y-m'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowances' => ['nullable', 'numeric', 'min:0'],
            'payment_status' => [
                'required',
                Rule::in(['Pending', 'Paid']),
            ],
            'payment_date' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $basic = (float) $validated['basic_salary'];
        $allowances = (float) ($validated['allowances'] ?? 0);

        $payroll = $this->calculatePayroll(
            (int) $validated['teacher_id'],
            $validated['salary_month'],
            $basic,
            $allowances
        );

        $this->fillSupportedColumns($teacherSalary, [
            'teacher_id' => $validated['teacher_id'],
            'salary_month' => $validated['salary_month'],
            'basic_salary' => round($basic, 2),
            'allowances' => round($allowances, 2),
            'working_days' => $payroll['working_days'],
            'present_days' => $payroll['present_days'],
            'absent_days' => $payroll['absent_days'],
            'half_days' => $payroll['half_days'],
            'leave_days' => $payroll['leave_days'],
            'overtime_hours' => $payroll['overtime_hours'],
            'overtime_amount' => $payroll['overtime_amount'],
            'attendance_allowance' => $payroll['attendance_allowance'],
            'attendance_deduction' => $payroll['attendance_deduction'],
            'deduction_rate' => $payroll['deduction_rate'],
            'gross_salary' => $payroll['gross_salary'],
            'deductions' => $payroll['deductions'],
            'net_salary' => $payroll['net_salary'],
            'payment_status' => $validated['payment_status'],
            'payment_date' => $validated['payment_date'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ]);

        $teacherSalary->save();

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with('success', 'Teacher salary updated successfully.');
    }

    /**
     * Delete a salary record.
     */
    public function destroy(TeacherSalary $teacherSalary)
    {
        $teacherSalary->delete();

        return redirect()
            ->route('admin.teachers.salary.index')
            ->with('success', 'Salary deleted successfully.');
    }
}
