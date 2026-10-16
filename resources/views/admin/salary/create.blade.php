@extends('layouts.app')

@section('title', 'Generate Salary')
@section('page-title', 'Generate Salary')

@section('content')

<style>
.salary-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 64px);
}

.salary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 20px;
}

.salary-header-left h2 {
    margin: 0;
    color: #172033;
    font-size: 28px;
    font-weight: 700;
}

.salary-header-left p {
    margin: 6px 0 0;
    color: #718096;
    font-size: 14px;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 10px;
    text-decoration: none;
    background: #fff;
    color: #1769d1;
    border: 1px solid #dbe5f0;
    font-weight: 600;
    transition: .2s ease;
}

.back-btn:hover {
    background: #1769d1;
    color: #fff;
}

.salary-card {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(30, 55, 90, .07);
    overflow: hidden;
}

.card-section {
    padding: 26px 28px;
    border-bottom: 1px solid #edf1f6;
}

.card-section:last-child {
    border-bottom: none;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
}

.section-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
}

.section-title h3 {
    margin: 0;
    color: #1c2738;
    font-size: 18px;
    font-weight: 700;
}

.section-title span {
    display: block;
    margin-top: 3px;
    color: #8793a5;
    font-size: 12px;
}

.salary-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.salary-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px;
}

.form-group {
    width: 100%;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    color: #344054;
    font-size: 13px;
    font-weight: 600;
}

.required {
    color: #e53935;
}

.form-control,
.form-select {
    width: 100%;
    min-height: 46px;
    padding: 11px 13px;
    border: 1px solid #d9e1ec;
    border-radius: 10px;
    background: #fff;
    color: #1f2937;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
}

.form-control:focus,
.form-select:focus {
    border-color: #5c8df6;
    box-shadow: 0 0 0 3px rgba(92, 141, 246, .12);
}

.form-control[readonly] {
    background: #f7f9fc;
}

.form-help {
    margin-top: 6px;
    color: #8995a7;
    font-size: 11px;
}

textarea.form-control {
    min-height: 110px;
    resize: vertical;
}

.input-group {
    display: flex;
    width: 100%;
}

.input-group-text {
    min-width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
    background: #f5f7fb;
    border: 1px solid #d9e1ec;
    color: #667085;
    font-weight: 600;
}

.input-group .input-group-text:first-child {
    border-radius: 10px 0 0 10px;
    border-right: none;
}

.input-group .form-control {
    border-radius: 0 10px 10px 0;
}

/* =========================
   ATTENDANCE
========================= */

.attendance-status {
    margin-top: 22px;
    padding: 18px;
    border-radius: 14px;
    border: 1px solid #dce8fb;
    background: linear-gradient(135deg, #f6faff, #f8f7ff);
}

.attendance-status-title {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 15px;
    color: #24324a;
    font-size: 14px;
    font-weight: 700;
}

.attendance-status-title i {
    color: #1769d1;
}

.attendance-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 12px;
}

.attendance-item {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 10px;
    padding: 13px 10px;
    text-align: center;
}

.attendance-item-label {
    color: #8490a2;
    font-size: 11px;
    margin-bottom: 5px;
}

.attendance-item-value {
    color: #172033;
    font-size: 17px;
    font-weight: 700;
}

/* =========================
   LIVE CALCULATION
========================= */

.automatic-box {
    margin-top: 20px;
    padding: 20px;
    border-radius: 14px;
    background: #f8fbff;
    border: 1px solid #dce8f8;
}

.automatic-title {
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 9px;
    color: #1d4f91;
    font-size: 14px;
    font-weight: 700;
}

.automatic-title i {
    color: #147cf5;
}

.automatic-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

.auto-field {
    padding: 15px;
    background: #fff;
    border: 1px solid #e3eaf3;
    border-radius: 11px;
}

.auto-label {
    display: block;
    margin-bottom: 7px;
    color: #7a8798;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.auto-value {
    color: #172033;
    font-size: 20px;
    font-weight: 750;
}

.auto-value.blue {
    color: #1769d1;
}

.auto-value.red {
    color: #d64545;
}

.auto-value.green {
    color: #17834b;
}

.auto-description {
    margin-top: 5px;
    color: #8995a7;
    font-size: 11px;
}

/* =========================
   PAYROLL SUMMARY
========================= */

.payroll-summary {
    margin-top: 22px;
    padding: 22px;
    border-radius: 15px;
    background: linear-gradient(135deg, #f6f9ff, #faf9ff);
    border: 1px solid #e0e8f5;
}

.payroll-summary-title {
    margin-bottom: 18px;
    color: #1b2940;
    font-size: 15px;
    font-weight: 700;
}

.summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 11px 0;
    border-bottom: 1px dashed #dfe5ed;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-label {
    color: #69778b;
    font-size: 13px;
}

.summary-value {
    color: #1f2937;
    font-size: 14px;
    font-weight: 700;
}

.summary-value.deduction {
    color: #d64545;
}

.summary-value.allowance {
    color: #17834b;
}

.net-salary-box {
    margin-top: 18px;
    padding: 20px;
    border-radius: 14px;
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.net-salary-label {
    font-size: 13px;
    opacity: .88;
}

.net-salary-title {
    margin-top: 4px;
    font-size: 20px;
    font-weight: 700;
}

.net-salary-value {
    font-size: 28px;
    font-weight: 800;
    white-space: nowrap;
}

.payment-note {
    margin-top: 8px;
    padding: 12px 14px;
    border-radius: 9px;
    background: #f7f9fc;
    color: #778397;
    font-size: 11px;
}

.form-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    padding: 22px 28px;
    background: #fbfcfe;
    border-top: 1px solid #edf1f6;
}

.btn {
    min-height: 45px;
    padding: 0 20px;
    border-radius: 10px;
    border: none;
    font-size: 14px;
    font-weight: 650;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
}

.btn-secondary {
    background: #fff;
    color: #526174;
    border: 1px solid #d8e0ea;
}

.btn-primary {
    color: #fff;
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    box-shadow: 0 5px 14px rgba(48, 99, 220, .20);
}

.alert {
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 13px;
}

.alert-danger {
    color: #9b2525;
    background: #fff1f1;
    border: 1px solid #ffd2d2;
}

.alert-danger ul {
    margin: 0;
    padding-left: 18px;
}

@media (max-width: 1100px) {
    .attendance-grid {
        grid-template-columns: repeat(3, 1fr);
    }

    .automatic-grid,
    .salary-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .salary-page {
        padding: 18px;
    }

    .salary-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .salary-grid,
    .salary-grid-3,
    .automatic-grid {
        grid-template-columns: 1fr;
    }

    .attendance-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .net-salary-box {
        flex-direction: column;
        align-items: flex-start;
    }

    .form-footer {
        flex-direction: column-reverse;
    }

    .form-footer .btn {
        width: 100%;
    }
}
</style>

<div class="salary-page">

    <div class="salary-header">
        <div class="salary-header-left">
            <h2>Generate Salary</h2>
            <p>
                Automatically calculate salary using teacher attendance
                and payroll details.
            </p>
        </div>

        <a href="{{ route('admin.teachers.salary.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Salary
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.teachers.salary.store') }}"
        method="POST"
        id="salaryForm"
    >
        @csrf

        <div class="salary-card">

            {{-- ================================
                 TEACHER INFORMATION
            ================================= --}}
            <div class="card-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>

                    <div>
                        <h3>Teacher Information</h3>
                        <span>Select teacher and salary month</span>
                    </div>
                </div>

                <div class="salary-grid">

                    <div class="form-group">
                        <label class="form-label" for="teacher_id">
                            Teacher <span class="required">*</span>
                        </label>

                        <select
                            name="teacher_id"
                            id="teacher_id"
                            class="form-select"
                            required
                        >
                            <option value="">Select Teacher</option>

                            @foreach ($teachers as $teacher)
                                <option
                                    value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                                >
                                    {{ $teacher->first_name }}
                                    {{ $teacher->last_name }}

                                    @if ($teacher->teacher_id)
                                        - {{ $teacher->teacher_id }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <div class="form-help">
                            Attendance will load automatically.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="salary_month">
                            Salary Month <span class="required">*</span>
                        </label>

                        <input
                            type="month"
                            name="salary_month"
                            id="salary_month"
                            class="form-control"
                            value="{{ old('salary_month', now()->format('Y-m')) }}"
                            required
                        >

                        <div class="form-help">
                            Attendance for this month will be used.
                        </div>
                    </div>

                </div>

                {{-- ================================
                     ATTENDANCE
                ================================= --}}
                <div class="attendance-status">

                    <div class="attendance-status-title">
                        <i class="fas fa-calendar-check"></i>
                        Attendance Summary
                    </div>

                    <div class="attendance-grid">

                        <div class="attendance-item">
                            <div class="attendance-item-label">Working Days</div>
                            <div class="attendance-item-value" id="display_working_days">0</div>
                        </div>

                        <div class="attendance-item">
                            <div class="attendance-item-label">Present</div>
                            <div class="attendance-item-value" id="display_present_days">0</div>
                        </div>

                        <div class="attendance-item">
                            <div class="attendance-item-label">Absent</div>
                            <div class="attendance-item-value" id="display_absent_days">0</div>
                        </div>

                        <div class="attendance-item">
                            <div class="attendance-item-label">Half Day</div>
                            <div class="attendance-item-value" id="display_half_days">0</div>
                        </div>

                        <div class="attendance-item">
                            <div class="attendance-item-label">Leave</div>
                            <div class="attendance-item-value" id="display_leave_days">0</div>
                        </div>

                        <div class="attendance-item">
                            <div class="attendance-item-label">Overtime</div>
                            <div class="attendance-item-value" id="display_overtime_hours">
                                0.00
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ================================
                 SALARY DETAILS
            ================================= --}}
            <div class="card-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>

                    <div>
                        <h3>Salary Details</h3>
                        <span>Enter salary amount and view automatic calculation</span>
                    </div>
                </div>

                <div class="salary-grid-3">

                    <div class="form-group">
                        <label class="form-label" for="basic_salary">
                            Basic Salary <span class="required">*</span>
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₹</span>

                            <input
                                type="number"
                                name="basic_salary"
                                id="basic_salary"
                                class="form-control"
                                value="{{ old('basic_salary', 0) }}"
                                min="0"
                                step="0.01"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="allowances">
                            Other Allowances
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">₹</span>

                            <input
                                type="number"
                                name="allowances"
                                id="allowances"
                                class="form-control"
                                value="{{ old('allowances', 0) }}"
                                min="0"
                                step="0.01"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Attendance Percentage
                        </label>

                        <input
                            type="text"
                            id="attendance_percentage"
                            class="form-control"
                            value="0%"
                            readonly
                        >
                    </div>

                </div>

                {{-- ================================
                     LIVE AUTOMATIC CALCULATION
                ================================= --}}
                <div class="automatic-box">

                    <div class="automatic-title">
                        <i class="fas fa-calculator"></i>
                        Live Attendance Salary Calculation
                    </div>

                    <div class="automatic-grid">

                        <div class="auto-field">
                            <span class="auto-label">Deduction Rate</span>

                            <div
                                class="auto-value blue"
                                id="display_deduction_rate"
                            >
                                0%
                            </div>

                            <div class="auto-description">
                                Automatically based on attendance
                            </div>
                        </div>

                        <div class="auto-field">
                            <span class="auto-label">Basic Salary</span>

                            <div
                                class="auto-value"
                                id="display_basic_salary"
                            >
                                ₹ 0.00
                            </div>

                            <div class="auto-description">
                                Salary used for deduction
                            </div>
                        </div>

                        <div class="auto-field">
                            <span class="auto-label">
                                Actual Attendance Deduction
                            </span>

                            <div
                                class="auto-value red"
                                id="display_deduction_amount"
                            >
                                ₹ 0.00
                            </div>

                            <div class="auto-description">
                                Basic Salary × Deduction Rate ÷ 100
                            </div>
                        </div>

                        <div class="auto-field">
                            <span class="auto-label">
                                Attendance Allowance
                            </span>

                            <div
                                class="auto-value green"
                                id="display_attendance_allowance"
                            >
                                ₹ 0.00
                            </div>

                            <div class="auto-description">
                                Based on attendance percentage
                            </div>
                        </div>

                        <div class="auto-field">
                            <span class="auto-label">
                                Overtime Amount
                            </span>

                            <div
                                class="auto-value green"
                                id="display_overtime_amount"
                            >
                                ₹ 0.00
                            </div>

                            <div class="auto-description">
                                Overtime hours × overtime rate
                            </div>
                        </div>

                        <div class="auto-field">
                            <span class="auto-label">
                                Total Deduction
                            </span>

                            <div
                                class="auto-value red"
                                id="display_total_deductions"
                            >
                                ₹ 0.00
                            </div>

                            <div class="auto-description">
                                Attendance deduction only
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- ================================
                 PAYROLL CALCULATION
            ================================= --}}
            <div class="card-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>

                    <div>
                        <h3>Payroll Calculation</h3>
                        <span>Live final salary preview</span>
                    </div>
                </div>

                <div class="payroll-summary">

                    <div class="payroll-summary-title">
                        Salary Breakdown
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Basic Salary</span>
                        <span class="summary-value" id="summary_basic">
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Other Allowances</span>
                        <span class="summary-value" id="summary_allowances">
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">
                            Attendance Allowance
                        </span>

                        <span
                            class="summary-value allowance"
                            id="summary_attendance_allowance"
                        >
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Overtime Amount</span>

                        <span
                            class="summary-value allowance"
                            id="summary_overtime"
                        >
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">
                            Attendance Deduction
                        </span>

                        <span
                            class="summary-value deduction"
                            id="summary_deduction"
                        >
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Gross Salary</span>

                        <span
                            class="summary-value"
                            id="summary_gross"
                        >
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Total Deductions</span>

                        <span
                            class="summary-value deduction"
                            id="summary_total_deductions"
                        >
                            ₹ 0.00
                        </span>
                    </div>

                    <div class="net-salary-box">

                        <div>
                            <div class="net-salary-label">
                                Final Salary Payable
                            </div>

                            <div class="net-salary-title">
                                Net Salary
                            </div>
                        </div>

                        <div
                            class="net-salary-value"
                            id="summary_net"
                        >
                            ₹ 0.00
                        </div>

                    </div>

                </div>

            </div>

            {{-- ================================
                 PAYMENT DETAILS
            ================================= --}}
            <div class="card-section">

                <div class="section-title">
                    <div class="section-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>

                    <div>
                        <h3>Payment Details</h3>
                        <span>Select payment status and additional information</span>
                    </div>
                </div>

                <div class="salary-grid">

                    <div class="form-group">
                        <label class="form-label" for="payment_status">
                            Payment Status <span class="required">*</span>
                        </label>

                        <select
                            name="payment_status"
                            id="payment_status"
                            class="form-select"
                            required
                        >
                            <option
                                value="Pending"
                                {{ old('payment_status', 'Pending') == 'Pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="Paid"
                                {{ old('payment_status') == 'Paid' ? 'selected' : '' }}
                            >
                                Paid
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="payment_date">
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            id="payment_date"
                            class="form-control"
                            value="{{ old('payment_date') }}"
                        >
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="remarks">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control"
                            placeholder="Add any additional salary remarks..."
                        >{{ old('remarks') }}</textarea>
                    </div>

                </div>

                <div class="payment-note">
                    <i class="fas fa-info-circle"></i>
                    Attendance and salary calculation are shown live before
                    generating the salary.
                </div>

            </div>

            {{-- ================================
                 FOOTER
            ================================= --}}
            <div class="form-footer">

                <a
                    href="{{ route('admin.teachers.salary.index') }}"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-check-circle"></i>
                    Generate Salary
                </button>

            </div>

        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const teacherSelect = document.getElementById('teacher_id');
    const salaryMonth = document.getElementById('salary_month');
    const basicSalary = document.getElementById('basic_salary');
    const allowances = document.getElementById('allowances');

    let attendanceData = null;

    /* ================================
       MONEY FORMAT
    ================================= */
    function money(value) {
        const amount = Number(value) || 0;

        return '₹ ' + amount.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /* ================================
       SET TEXT
    ================================= */
    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value;
        }
    }

    /* ================================
       RESET
    ================================= */
    function resetAttendance() {

        attendanceData = null;

        setText('display_working_days', '0');
        setText('display_present_days', '0');
        setText('display_absent_days', '0');
        setText('display_half_days', '0');
        setText('display_leave_days', '0');
        setText('display_overtime_hours', '0.00');

        setText('attendance_percentage', '0%');
        setText('display_deduction_rate', '0%');

        setText('display_attendance_allowance', money(0));
        setText('display_overtime_amount', money(0));

        calculateSalary();
    }

    /* ================================
       LOAD ATTENDANCE
    ================================= */
    async function loadAttendance() {

        const teacherId = teacherSelect.value;
        const month = salaryMonth.value;

        if (!teacherId || !month) {
            resetAttendance();
            return;
        }

        setText('display_working_days', 'Loading...');
        setText('display_present_days', '...');
        setText('display_absent_days', '...');
        setText('display_half_days', '...');
        setText('display_leave_days', '...');
        setText('display_overtime_hours', '...');

        try {

            const url =
                "{{ route('admin.teachers.salary.attendance-data') }}" +
                "?teacher_id=" +
                encodeURIComponent(teacherId) +
                "&salary_month=" +
                encodeURIComponent(month);

            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(
                    'Attendance request failed: ' + response.status
                );
            }

            const data = await response.json();

            console.log('Attendance Data:', data);

            if (!data.success) {
                throw new Error('Attendance data not available.');
            }

            attendanceData = data;

            /* Attendance */

            setText(
                'display_working_days',
                data.working_days ?? 0
            );

            setText(
                'display_present_days',
                data.present_days ?? 0
            );

            setText(
                'display_absent_days',
                data.absent_days ?? 0
            );

            setText(
                'display_half_days',
                data.half_days ?? 0
            );

            setText(
                'display_leave_days',
                data.leave_days ?? 0
            );

            setText(
                'display_overtime_hours',
                Number(data.overtime_hours || 0).toFixed(2)
            );

            /* Attendance percentage */

            const attendancePercentage =
                Number(data.attendance_percentage || 0);

            setText(
                'attendance_percentage',
                attendancePercentage.toFixed(2) + '%'
            );

            /* Deduction rate */

            const deductionRate =
                Number(data.deduction_rate || 0);

            setText(
                'display_deduction_rate',
                deductionRate.toFixed(2) + '%'
            );

            /* Automatic allowance */

            setText(
                'display_attendance_allowance',
                money(data.attendance_allowance)
            );

            /* Overtime */

            setText(
                'display_overtime_amount',
                money(data.overtime_amount)
            );

            /* IMPORTANT:
               Recalculate immediately after attendance loads.
            */

            calculateSalary();

        } catch (error) {

            console.error('Attendance Error:', error);

            resetAttendance();

            alert(
                'Unable to load attendance data. Please check the attendance-data route and controller.'
            );
        }
    }

    /* ================================
       CALCULATE SALARY LIVE
    ================================= */
    function calculateSalary() {

        const basic =
            Number(basicSalary.value) || 0;

        const otherAllowances =
            Number(allowances.value) || 0;

        let deductionRate = 0;
        let attendanceAllowance = 0;
        let overtimeAmount = 0;

        if (attendanceData) {

            deductionRate =
                Number(attendanceData.deduction_rate) || 0;

            attendanceAllowance =
                Number(attendanceData.attendance_allowance) || 0;

            overtimeAmount =
                Number(attendanceData.overtime_amount) || 0;
        }

        /* ==================================
           ACTUAL ATTENDANCE DEDUCTION

           Basic Salary × Deduction Rate / 100
        ================================== */

        const deductionAmount =
            (basic * deductionRate) / 100;

        /* ==================================
           GROSS SALARY
        ================================== */

        const grossSalary =
            basic +
            otherAllowances +
            attendanceAllowance +
            overtimeAmount;

        /* ==================================
           TOTAL DEDUCTION

           Only attendance deduction.
           No double deduction.
        ================================== */

        const totalDeductions =
            deductionAmount;

        /* ==================================
           NET SALARY
        ================================== */

        const netSalary =
            Math.max(
                0,
                grossSalary - totalDeductions
            );

        /* ==================================
           AUTOMATIC CALCULATION BOX
        ================================== */

        setText(
            'display_basic_salary',
            money(basic)
        );

        setText(
            'display_deduction_amount',
            money(deductionAmount)
        );

        setText(
            'display_total_deductions',
            money(totalDeductions)
        );

        /* ==================================
           PAYROLL SUMMARY
        ================================== */

        setText(
            'summary_basic',
            money(basic)
        );

        setText(
            'summary_allowances',
            money(otherAllowances)
        );

        setText(
            'summary_attendance_allowance',
            money(attendanceAllowance)
        );

        setText(
            'summary_overtime',
            money(overtimeAmount)
        );

        setText(
            'summary_deduction',
            money(deductionAmount)
        );

        setText(
            'summary_gross',
            money(grossSalary)
        );

        setText(
            'summary_total_deductions',
            money(totalDeductions)
        );

        setText(
            'summary_net',
            money(netSalary)
        );
    }

    /* ================================
       EVENTS
    ================================= */

    teacherSelect.addEventListener(
        'change',
        loadAttendance
    );

    salaryMonth.addEventListener(
        'change',
        loadAttendance
    );

    basicSalary.addEventListener(
        'input',
        calculateSalary
    );

    allowances.addEventListener(
        'input',
        calculateSalary
    );

    /* Initial calculation */

    calculateSalary();

    /* Load attendance if values already exist */

    if (
        teacherSelect.value &&
        salaryMonth.value
    ) {
        loadAttendance();
    }

});
</script>

@endsection