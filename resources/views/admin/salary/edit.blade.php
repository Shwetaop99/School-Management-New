
@extends('layouts.app')

@section('title', 'Edit Salary')

@section('page-title', 'Edit Salary')

@section('content')

@php

    $salaryMonth = '';

    if ($teacherSalary->salary_month) {
        $salaryMonth = \Carbon\Carbon::parse($teacherSalary->salary_month)->format('Y-m');
    }

    $paymentDate = '';

    if ($teacherSalary->payment_date) {
        $paymentDate = \Carbon\Carbon::parse($teacherSalary->payment_date)->format('Y-m-d');
    }

    /*
    |--------------------------------------------------------------------------
    | SALARY
    |--------------------------------------------------------------------------
    */

    $basicSalary = old(
        'basic_salary',
        $teacherSalary->basic_salary ?? 0
    );

    $allowances = old(
        'allowances',
        $teacherSalary->allowances ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */

    $workingDays = old(
        'working_days',
        $teacherSalary->working_days ?? 0
    );

    $presentDays = old(
        'present_days',
        $teacherSalary->present_days ?? 0
    );

    $absentDays = old(
        'absent_days',
        $teacherSalary->absent_days ?? 0
    );

    $halfDays = old(
        'half_days',
        $teacherSalary->half_days ?? 0
    );

    $leaveDays = old(
        'leave_days',
        $teacherSalary->leave_days ?? 0
    );

    $attendancePercentage = old(
        'attendance_percentage',
        $teacherSalary->attendance_percentage ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | DEDUCTION
    |--------------------------------------------------------------------------
    */

    $deductionRate = old(
        'deduction_rate',
        $teacherSalary->deduction_rate ?? 0
    );

    $attendanceDeduction = old(
        'attendance_deduction',
        $teacherSalary->attendance_deduction ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | OVERTIME
    |--------------------------------------------------------------------------
    */

    $overtimeHours = old(
        'overtime_hours',
        $teacherSalary->overtime_hours ?? 0
    );

    $overtimeRate = old(
        'overtime_rate',
        $teacherSalary->overtime_rate ?? 200
    );

    $overtimeAmount = old(
        'overtime_amount',
        $teacherSalary->overtime_amount ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE ALLOWANCE
    |--------------------------------------------------------------------------
    */

    $attendanceAllowance = old(
        'attendance_allowance',
        $teacherSalary->attendance_allowance ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | PAYROLL VALUES
    |--------------------------------------------------------------------------
    */

    $grossSalary = old(
        'gross_salary',
        $teacherSalary->gross_salary ?? 0
    );

    $deductions = old(
        'deductions',
        $teacherSalary->deductions ?? 0
    );

    $netSalary = old(
        'net_salary',
        $teacherSalary->net_salary ?? 0
    );

@endphp


<style>

/* =========================================================
   PAGE
========================================================= */

.salary-edit-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
}


/* =========================================================
   HEADER
========================================================= */

.salary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.salary-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.salary-header-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    font-size: 21px;
    box-shadow: 0 8px 20px rgba(20, 124, 245, 0.18);
}

.salary-header h2 {
    margin: 0;
    color: #172033;
    font-size: 25px;
    font-weight: 700;
}

.salary-header p {
    margin: 4px 0 0;
    color: #7b8495;
    font-size: 14px;
}


/* =========================================================
   SUMMARY STRIP
========================================================= */

.summary-strip {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}

.summary-box {
    padding: 17px;
    border-radius: 13px;
    background: #fff;
    border: 1px solid #e4e9f1;
}

.summary-box span {
    display: block;
    font-size: 12px;
    color: #737d90;
    margin-bottom: 6px;
}

.summary-box strong {
    font-size: 19px;
    color: #202939;
}


/* =========================================================
   CARD
========================================================= */

.salary-card {
    background: #ffffff;
    border: 1px solid #e7ebf2;
    border-radius: 18px;
    padding: 24px;
    margin-bottom: 22px;
    box-shadow: 0 5px 20px rgba(27, 44, 76, 0.05);
}


/* =========================================================
   SECTION TITLE
========================================================= */

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid #edf0f5;
}

.section-title i {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef5ff;
    color: #147cf5;
}

.section-title h3 {
    margin: 0;
    color: #202939;
    font-size: 17px;
    font-weight: 700;
}


/* =========================================================
   FORM
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-label {
    margin-bottom: 7px;
    color: #394357;
    font-size: 13px;
    font-weight: 600;
}

.form-control {
    width: 100%;
    height: 44px;
    padding: 0 13px;
    border: 1px solid #dce2eb;
    border-radius: 10px;
    background: #fff;
    color: #202939;
    font-size: 14px;
    outline: none;
    transition: 0.2s ease;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10);
}

textarea.form-control {
    height: 100px;
    padding: 12px 13px;
    resize: vertical;
}

.required {
    color: #ef4444;
}


/* =========================================================
   ATTENDANCE
========================================================= */

.attendance-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
}

.attendance-box {
    background: #f8faff;
    border: 1px solid #e2e8f3;
    border-radius: 12px;
    padding: 14px;
}

.attendance-box label {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 600;
    color: #626c7e;
}


/* =========================================================
   OVERTIME
========================================================= */

.overtime-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-top: 18px;
}

.overtime-card {
    padding: 17px;
    border-radius: 13px;
    border: 1px solid #e2e8f3;
    background: #f9fbff;
}

.overtime-card label {
    display: block;
    color: #657084;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 8px;
}


/* =========================================================
   DEDUCTION
========================================================= */

.deduction-box {
    margin-top: 18px;
    padding: 18px;
    border-radius: 14px;
    border: 1px solid #e5e9f1;
    background: linear-gradient(135deg, #f8fbff, #faf9ff);
}

.deduction-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.deduction-info {
    padding: 14px;
    border-radius: 11px;
    background: #fff;
    border: 1px solid #e7ebf2;
}

.deduction-info span {
    display: block;
    color: #727c8e;
    font-size: 12px;
    margin-bottom: 7px;
}

.deduction-amount {
    font-size: 18px;
    font-weight: 700;
    color: #202939;
}


/* =========================================================
   RESULT
========================================================= */

.salary-result {
    margin-top: 18px;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.result-box {
    padding: 16px;
    border-radius: 12px;
    background: #f8faff;
    border: 1px solid #e3e8f1;
}

.result-box span {
    display: block;
    font-size: 12px;
    color: #717b8d;
    margin-bottom: 6px;
}

.result-box strong {
    font-size: 18px;
    color: #202939;
}


/* =========================================================
   BUTTONS
========================================================= */

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}

.btn {
    min-height: 44px;
    padding: 0 20px;
    border: 0;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: 0.2s ease;
}

.btn-secondary {
    background: #eef1f6;
    color: #4b5565;
}

.btn-primary {
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    box-shadow: 0 6px 16px rgba(20, 124, 245, 0.18);
}

.btn-primary:hover {
    transform: translateY(-1px);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .attendance-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .salary-result {
        grid-template-columns: repeat(2, 1fr);
    }

    .summary-strip {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {

    .salary-edit-page {
        padding: 16px;
    }

    .form-grid,
    .attendance-grid,
    .overtime-grid,
    .deduction-grid,
    .salary-result,
    .summary-strip {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}

</style>


<div class="salary-edit-page">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="salary-header">

        <div class="salary-header-left">

            <div class="salary-header-icon">
                <i class="fas fa-edit"></i>
            </div>

            <div>

                <h2>Edit Teacher Salary</h2>

                <p>
                    Update salary, attendance, overtime and deduction details.
                </p>

            </div>

        </div>

    </div>


    <!-- =====================================================
         TOP SUMMARY
    ====================================================== -->

    <div class="summary-strip">

        <div class="summary-box">

            <span>Teacher</span>

            <strong>
                {{ $teacherSalary->teacher->first_name ?? '' }}
                {{ $teacherSalary->teacher->last_name ?? '' }}
            </strong>

        </div>


        <div class="summary-box">

            <span>Attendance</span>

            <strong id="topAttendance">
                {{ number_format((float) $attendancePercentage, 2) }}%
            </strong>

        </div>


        <div class="summary-box">

            <span>Deduction Rate</span>

            <strong id="topDeductionRate">
                {{ number_format((float) $deductionRate, 2) }}%
            </strong>

        </div>


        <div class="summary-box">

            <span>Final Net Salary</span>

            <strong id="topNetSalary">
                ₹{{ number_format((float) $netSalary, 2) }}
            </strong>

        </div>

    </div>


    <form
        action="{{ route('admin.teachers.salary.update', $teacherSalary->id) }}"
        method="POST"
        id="salaryEditForm"
    >

        @csrf

        @method('PUT')


        <!-- =================================================
             TEACHER INFORMATION
        ================================================== -->

        <div class="salary-card">

            <div class="section-title">

                <i class="fas fa-user-tie"></i>

                <h3>Teacher Information</h3>

            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label">
                        Teacher <span class="required">*</span>
                    </label>

                    <select
                        name="teacher_id"
                        id="teacher_id"
                        class="form-control"
                        required
                    >

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ old('teacher_id', $teacherSalary->teacher_id) == $teacher->id ? 'selected' : '' }}
                            >

                                {{ $teacher->first_name }}
                                {{ $teacher->last_name }}

                                @if($teacher->teacher_id)
                                    - {{ $teacher->teacher_id }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Salary Month <span class="required">*</span>
                    </label>

                    <input
                        type="month"
                        name="salary_month"
                        value="{{ old('salary_month', $salaryMonth) }}"
                        class="form-control"
                        required
                    >

                </div>

            </div>

        </div>


        <!-- =================================================
             SALARY DETAILS
        ================================================== -->

        <div class="salary-card">

            <div class="section-title">

                <i class="fas fa-money-bill-wave"></i>

                <h3>Salary Details</h3>

            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label">
                        Basic Salary <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="basic_salary"
                        id="basic_salary"
                        class="form-control"
                        value="{{ $basicSalary }}"
                        min="0"
                        step="0.01"
                        required
                    >

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Other Allowances
                    </label>

                    <input
                        type="number"
                        name="allowances"
                        id="allowances"
                        class="form-control"
                        value="{{ $allowances }}"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>

        </div>


        <!-- =================================================
             ATTENDANCE & OVERTIME
        ================================================== -->

        <div class="salary-card">

            <div class="section-title">

                <i class="fas fa-calendar-check"></i>

                <h3>Attendance & Overtime</h3>

            </div>


            <div class="attendance-grid">

                <div class="attendance-box">

                    <label>
                        Working Days
                    </label>

                    <input
                        type="number"
                        name="working_days"
                        id="working_days"
                        class="form-control attendance-input"
                        value="{{ $workingDays }}"
                        min="0"
                        step="1"
                    >

                </div>


                <div class="attendance-box">

                    <label>
                        Present Days
                    </label>

                    <input
                        type="number"
                        name="present_days"
                        id="present_days"
                        class="form-control attendance-input"
                        value="{{ $presentDays }}"
                        min="0"
                        step="1"
                    >

                </div>


                <div class="attendance-box">

                    <label>
                        Absent Days
                    </label>

                    <input
                        type="number"
                        name="absent_days"
                        id="absent_days"
                        class="form-control attendance-input"
                        value="{{ $absentDays }}"
                        min="0"
                        step="1"
                    >

                </div>


                <div class="attendance-box">

                    <label>
                        Half Days
                    </label>

                    <input
                        type="number"
                        name="half_days"
                        id="half_days"
                        class="form-control attendance-input"
                        value="{{ $halfDays }}"
                        min="0"
                        step="0.5"
                    >

                </div>


                <div class="attendance-box">

                    <label>
                        Leave Days
                    </label>

                    <input
                        type="number"
                        name="leave_days"
                        id="leave_days"
                        class="form-control attendance-input"
                        value="{{ $leaveDays }}"
                        min="0"
                        step="0.5"
                    >

                </div>

            </div>


            <div class="overtime-grid">

                <div class="overtime-card">

                    <label>
                        Attendance Percentage (%)
                    </label>

                    <input
                        type="number"
                        name="attendance_percentage"
                        id="attendance_percentage"
                        class="form-control"
                        value="{{ $attendancePercentage }}"
                        min="0"
                        max="100"
                        step="0.01"
                    >

                </div>


                <div class="overtime-card">

                    <label>
                        Overtime Hours
                    </label>

                    <input
                        type="number"
                        name="overtime_hours"
                        id="overtime_hours"
                        class="form-control"
                        value="{{ $overtimeHours }}"
                        min="0"
                        step="0.01"
                    >

                </div>


                <div class="overtime-card">

                    <label>
                        Overtime Rate / Hour
                    </label>

                    <input
                        type="number"
                        name="overtime_rate"
                        id="overtime_rate"
                        class="form-control"
                        value="{{ $overtimeRate }}"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>


            <div class="overtime-grid">

                <div class="overtime-card">

                    <label>
                        Overtime Amount
                    </label>

                    <input
                        type="number"
                        name="overtime_amount"
                        id="overtime_amount"
                        class="form-control"
                        value="{{ $overtimeAmount }}"
                        min="0"
                        step="0.01"
                    >

                </div>


                <div class="overtime-card">

                    <label>
                        Attendance Allowance
                    </label>

                    <input
                        type="number"
                        name="attendance_allowance"
                        id="attendance_allowance"
                        class="form-control"
                        value="{{ $attendanceAllowance }}"
                        min="0"
                        step="0.01"
                    >

                </div>


                <div class="overtime-card">

                    <label>
                        Attendance Deduction
                    </label>

                    <input
                        type="number"
                        name="attendance_deduction"
                        id="attendance_deduction"
                        class="form-control"
                        value="{{ $attendanceDeduction }}"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>


            <!-- =================================================
                 DEDUCTION RATE
            ================================================== -->

            <div class="deduction-box">

                <div class="deduction-grid">

                    <div class="deduction-info">

                        <span>
                            Deduction Rate (%)
                        </span>

                        <input
                            type="number"
                            name="deduction_rate"
                            id="deduction_rate"
                            class="form-control"
                            value="{{ $deductionRate }}"
                            min="0"
                            max="100"
                            step="0.01"
                        >

                    </div>


                    <div class="deduction-info">

                        <span>
                            Deduction Amount
                        </span>

                        <div
                            class="deduction-amount"
                            id="deductionAmountDisplay"
                        >
                            ₹{{ number_format((float) $deductions, 2) }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 LIVE SALARY RESULT
            ================================================== -->

            <div class="salary-result">

                <div class="result-box">

                    <span>
                        Gross Salary
                    </span>

                    <strong id="grossSalaryDisplay">
                        ₹{{ number_format((float) $grossSalary, 2) }}
                    </strong>

                </div>


                <div class="result-box">

                    <span>
                        Total Deductions
                    </span>

                    <strong id="totalDeductionDisplay">
                        ₹{{ number_format((float) $deductions, 2) }}
                    </strong>

                </div>


                <div class="result-box">

                    <span>
                        Final Net Salary
                    </span>

                    <strong id="netSalaryDisplay">
                        ₹{{ number_format((float) $netSalary, 2) }}
                    </strong>

                </div>

            </div>

        </div>


        <!-- =================================================
             PAYMENT DETAILS
        ================================================== -->

        <div class="salary-card">

            <div class="section-title">

                <i class="fas fa-credit-card"></i>

                <h3>Payment Details</h3>

            </div>


            <div class="form-grid">

                <div class="form-group">

                    <label class="form-label">
                        Payment Status <span class="required">*</span>
                    </label>

                    <select
                        name="payment_status"
                        id="payment_status"
                        class="form-control"
                        required
                    >

                        <option
                            value="Pending"
                            {{ old('payment_status', $teacherSalary->payment_status) == 'Pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="Paid"
                            {{ old('payment_status', $teacherSalary->payment_status) == 'Paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label class="form-label">
                        Payment Date
                    </label>

                    <input
                        type="date"
                        name="payment_date"
                        id="payment_date"
                        class="form-control"
                        value="{{ old('payment_date', $paymentDate) }}"
                    >

                </div>

            </div>

        </div>


        <!-- =================================================
             REMARKS
        ================================================== -->

        <div class="salary-card">

            <div class="section-title">

                <i class="fas fa-comment-alt"></i>

                <h3>Remarks</h3>

            </div>


            <div class="form-group">

                <textarea
                    name="remarks"
                    class="form-control"
                    placeholder="Enter salary remarks..."
                >{{ old('remarks', $teacherSalary->remarks) }}</textarea>

            </div>

        </div>


        <!-- =================================================
             ACTIONS
        ================================================== -->

        <div class="form-actions">

            <a
                href="{{ route('admin.teachers.salary.index') }}"
                class="btn btn-secondary"
            >
                <i class="fas fa-arrow-left"></i>
                Cancel
            </a>


            <button
                type="submit"
                class="btn btn-primary"
                id="updateSalaryBtn"
            >
                <i class="fas fa-save"></i>
                Update Salary
            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const basicSalary =
        document.getElementById('basic_salary');

    const allowances =
        document.getElementById('allowances');

    const attendancePercentage =
        document.getElementById('attendance_percentage');

    const deductionRate =
        document.getElementById('deduction_rate');

    const overtimeHours =
        document.getElementById('overtime_hours');

    const overtimeRate =
        document.getElementById('overtime_rate');

    const overtimeAmount =
        document.getElementById('overtime_amount');

    const attendanceAllowance =
        document.getElementById('attendance_allowance');

    const attendanceDeduction =
        document.getElementById('attendance_deduction');

    const grossSalaryDisplay =
        document.getElementById('grossSalaryDisplay');

    const totalDeductionDisplay =
        document.getElementById('totalDeductionDisplay');

    const netSalaryDisplay =
        document.getElementById('netSalaryDisplay');

    const deductionAmountDisplay =
        document.getElementById('deductionAmountDisplay');

    const topAttendance =
        document.getElementById('topAttendance');

    const topDeductionRate =
        document.getElementById('topDeductionRate');

    const topNetSalary =
        document.getElementById('topNetSalary');


    /*
    |--------------------------------------------------------------------------
    | CURRENCY
    |--------------------------------------------------------------------------
    */

    function formatCurrency(value) {

        return '₹' + Number(value || 0).toLocaleString(
            'en-IN',
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    function numberValue(element) {

        return parseFloat(element.value) || 0;

    }


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */

    function calculateAttendance() {

        const workingDays =
            numberValue(
                document.getElementById('working_days')
            );

        const presentDays =
            numberValue(
                document.getElementById('present_days')
            );

        const halfDays =
            numberValue(
                document.getElementById('half_days')
            );

        let percentage = 0;

        if (workingDays > 0) {

            const effectivePresentDays =
                presentDays + (halfDays * 0.5);

            percentage =
                (effectivePresentDays / workingDays) * 100;

        }

        percentage =
            Math.max(
                0,
                Math.min(100, percentage)
            );

        attendancePercentage.value =
            percentage.toFixed(2);

        topAttendance.textContent =
            percentage.toFixed(2) + '%';

    }


    /*
    |--------------------------------------------------------------------------
    | OVERTIME
    |--------------------------------------------------------------------------
    */

    function calculateOvertime() {

        const hours =
            numberValue(overtimeHours);

        const rate =
            numberValue(overtimeRate);

        const amount =
            hours * rate;

        overtimeAmount.value =
            amount.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | PAYROLL CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculatePayroll() {

        const basic =
            numberValue(basicSalary);

        const otherAllowances =
            numberValue(allowances);

        const attendanceAllowanceValue =
            numberValue(attendanceAllowance);

        const overtime =
            numberValue(overtimeAmount);

        const rate =
            numberValue(deductionRate);

        /*
        |--------------------------------------------------------------------------
        | DEDUCTION
        |--------------------------------------------------------------------------
        */

        const deduction =
            basic * rate / 100;

        attendanceDeduction.value =
            deduction.toFixed(2);

        /*
        |--------------------------------------------------------------------------
        | GROSS
        |--------------------------------------------------------------------------
        */

        const gross =
            basic +
            otherAllowances +
            attendanceAllowanceValue +
            overtime;

        /*
        |--------------------------------------------------------------------------
        | NET
        |--------------------------------------------------------------------------
        */

        const net =
            Math.max(
                0,
                gross - deduction
            );

        /*
        |--------------------------------------------------------------------------
        | DISPLAY
        |--------------------------------------------------------------------------
        */

        grossSalaryDisplay.textContent =
            formatCurrency(gross);

        totalDeductionDisplay.textContent =
            formatCurrency(deduction);

        deductionAmountDisplay.textContent =
            formatCurrency(deduction);

        netSalaryDisplay.textContent =
            formatCurrency(net);

        topNetSalary.textContent =
            formatCurrency(net);

    }


    /*
    |--------------------------------------------------------------------------
    | BASIC SALARY
    |--------------------------------------------------------------------------
    */

    basicSalary.addEventListener(
        'input',
        calculatePayroll
    );


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCES
    |--------------------------------------------------------------------------
    */

    allowances.addEventListener(
        'input',
        calculatePayroll
    );


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.attendance-input')
        .forEach(function (input) {

            input.addEventListener(
                'input',
                function () {

                    calculateAttendance();
                    calculatePayroll();

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | MANUAL ATTENDANCE %
    |--------------------------------------------------------------------------
    */

    attendancePercentage.addEventListener(
        'input',
        function () {

            let value =
                numberValue(attendancePercentage);

            value =
                Math.max(
                    0,
                    Math.min(100, value)
                );

            attendancePercentage.value =
                value;

            topAttendance.textContent =
                value.toFixed(2) + '%';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | DEDUCTION RATE
    |--------------------------------------------------------------------------
    */

    deductionRate.addEventListener(
        'input',
        function () {

            let value =
                numberValue(deductionRate);

            value =
                Math.max(
                    0,
                    Math.min(100, value)
                );

            deductionRate.value =
                value;

            topDeductionRate.textContent =
                value.toFixed(2) + '%';

            calculatePayroll();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | OVERTIME HOURS
    |--------------------------------------------------------------------------
    */

    overtimeHours.addEventListener(
        'input',
        function () {

            calculateOvertime();
            calculatePayroll();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | OVERTIME RATE
    |--------------------------------------------------------------------------
    */

    overtimeRate.addEventListener(
        'input',
        function () {

            calculateOvertime();
            calculatePayroll();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | OVERTIME AMOUNT
    |--------------------------------------------------------------------------
    */

    overtimeAmount.addEventListener(
        'input',
        calculatePayroll
    );


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE ALLOWANCE
    |--------------------------------------------------------------------------
    */

    attendanceAllowance.addEventListener(
        'input',
        calculatePayroll
    );


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE DEDUCTION
    |--------------------------------------------------------------------------
    */

    attendanceDeduction.addEventListener(
        'input',
        function () {

            const value =
                numberValue(attendanceDeduction);

            deductionAmountDisplay.textContent =
                formatCurrency(value);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAYMENT DATE
    |--------------------------------------------------------------------------
    */

    const paymentStatus =
        document.getElementById('payment_status');

    const paymentDate =
        document.getElementById('payment_date');


    function updatePaymentDateRequirement() {

        if (paymentStatus.value === 'Paid') {

            paymentDate.required = true;

        } else {

            paymentDate.required = false;

        }

    }


    paymentStatus.addEventListener(
        'change',
        updatePaymentDateRequirement
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('salaryEditForm');

    const updateButton =
        document.getElementById('updateSalaryBtn');


    form.addEventListener(
        'submit',
        function () {

            updateButton.disabled = true;

            updateButton.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Updating...';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    calculateAttendance();

    calculatePayroll();

    updatePaymentDateRequirement();

});

</script>

@endsection

