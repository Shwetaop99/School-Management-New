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

<div class="teacher-salary-create-page">
    <div class="tsc-container">

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="tsc-alert tsc-success" role="status">
                <span class="tsc-alert-icon">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="tsc-alert tsc-error" role="status">
                <span class="tsc-alert-icon">
                    <i class="bi bi-exclamation-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- FORM PANEL --}}
        <section class="tsc-paper-panel">

            <div class="tsc-panel-header">
                <div class="tsc-title-wrap">
                    <div class="tsc-title-icon">
                        <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3>Salary Information</h3>
                        <p>Enter teacher salary and payment details below.</p>
                    </div>
                </div>

                <a href="{{ route('admin.teachers.salary.index') }}"
                   class="tsc-back-btn">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    Back to Salary List
                </a>
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
            <form action="{{ route('admin.teachers.salary.store') }}"
                  method="POST"
                  class="tsc-form">

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
                {{-- STAFF INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-person-badge" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Staff Information</h4>
                            <p>Select the staff category, staff member and salary month.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-2">

                        {{-- STAFF TYPE --}}
                        <div class="tsc-field">
                            <label for="staff_type">
                                Staff Type <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-people" aria-hidden="true"></i>

                                <select name="staff_type"
                                        id="staff_type"
                                        class="tsc-control"
                                        required>
                                    <option value="">Select Staff Type</option>

                                    @foreach([
                                        'Teacher' => 'Teacher',
                                        'Driver' => 'Driver',
                                        'Peon' => 'Peon',
                                        'Librarian' => 'Librarian',
                                        'Accountant' => 'Accountant',
                                        'Receptionist' => 'Receptionist',
                                        'OtherStaff' => 'Other Staff',
                                    ] as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('staff_type', 'Teacher') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @error('staff_type')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- STAFF MEMBER --}}
                        <div class="tsc-field">
                            <label for="staff_member_id">
                                Staff Member <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-person" aria-hidden="true"></i>

                                <select id="staff_member_id"
                                        class="tsc-control"
                                        required>
                                    <option value="">Select Staff Member</option>

                                    @foreach($teachers as $teacher)
                                        <option
                                            value="{{ $teacher->id }}"
                                            data-category="Teacher"
                                            data-designation="Teacher"
                                            data-other-staff="0"
                                            {{ old('staff_type', 'Teacher') === 'Teacher'
                                                && old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                            @if($teacher->teacher_id)
                                                - {{ $teacher->teacher_id }}
                                            @endif
                                        </option>
                                    @endforeach

                                    @foreach($otherStaff as $staff)
                                        @php
                                            $designation = strtolower(trim($staff->designation ?? ''));

                                            if (str_contains($designation, 'driver')) {
                                                $category = 'Driver';
                                            } elseif (str_contains($designation, 'peon')) {
                                                $category = 'Peon';
                                            } elseif (
                                                str_contains($designation, 'librarian') ||
                                                str_contains($designation, 'library')
                                            ) {
                                                $category = 'Librarian';
                                            } elseif (
                                                str_contains($designation, 'accountant') ||
                                                str_contains($designation, 'account')
                                            ) {
                                                $category = 'Accountant';
                                            } elseif (
                                                str_contains($designation, 'receptionist') ||
                                                str_contains($designation, 'reception')
                                            ) {
                                                $category = 'Receptionist';
                                            } else {
                                                $category = 'OtherStaff';
                                            }
                                        @endphp

                                        <option
                                            value="{{ $staff->id }}"
                                            data-category="{{ $category }}"
                                            data-designation="{{ $staff->designation }}"
                                            data-other-staff="1"
                                            {{ old('staff_type') === $category
                                                && old('other_staff_id') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                            @if($staff->staff_id)
                                                - {{ $staff->staff_id }}
                                            @endif
                                            ({{ $staff->designation }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="hidden"
                                       name="teacher_id"
                                       id="teacher_id"
                                       value="{{ old('teacher_id') }}">

                                <input type="hidden"
                                       name="other_staff_id"
                                       id="other_staff_id"
                                       value="{{ old('other_staff_id') }}">
                            </div>

                            <small id="staff-help"
                                   style="display:block;margin-top:6px;color:#7b857b;">
                                Choose a staff type to see matching staff members.
                            </small>

                            @error('teacher_id')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror

                            @error('other_staff_id')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SALARY MONTH --}}
                        <div class="tsc-field">
                            <label for="salary_month">
                                Salary Month <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>

                                <input type="month"
                                       name="salary_month"
                                       id="salary_month"
                                       value="{{ old('salary_month', now()->format('Y-m')) }}"
                                       class="tsc-control"
                                       required>
                            </div>

                            @error('salary_month')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
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
                {{-- SALARY DETAILS --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-cash-coin" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Salary Details</h4>
                            <p>Enter basic salary, allowances and deduction rate.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-3">
                        <div class="tsc-field">
                            <label for="basic_salary">
                                Basic Salary <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-currency-rupee" aria-hidden="true"></i>

                                <input type="number"
                                       name="basic_salary"
                                       id="basic_salary"
                                       value="{{ old('basic_salary') }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                       required>
                            </div>

                            @error('basic_salary')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="allowances">Allowances</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-plus-circle" aria-hidden="true"></i>

                                <input type="number"
                                       name="allowances"
                                       id="allowances"
                                       value="{{ old('allowances', 0) }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01">
                            </div>

                            @error('allowances')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="deduction_rate">Deduction Rate (%)</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-percent" aria-hidden="true"></i>

                                <input type="number"
                                       name="deduction_rate"
                                       id="deduction_rate"
                                       value="{{ old('deduction_rate', 0) }}"
                                       class="tsc-control"
                                       placeholder="0.00"
                                       min="0"
                                       max="100"
                                       step="0.01">
                            </div>

                            @error('deduction_rate')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- CALCULATION --}}
                    <div class="tsc-calculation">
                        <div class="tsc-calculation-heading">
                            <i class="bi bi-calculator" aria-hidden="true"></i>
                            Salary Calculation
                        </div>

                        <div class="tsc-calculation-grid">
                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Basic</span>
                                <span class="tsc-calculation-value" id="display-basic">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Allowances</span>
                                <span class="tsc-calculation-value" id="display-allowances">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item">
                                <span class="tsc-calculation-label">Deductions</span>
                                <span class="tsc-calculation-value tsc-deduction" id="display-deductions">₹0.00</span>
                            </div>

                            <div class="tsc-calculation-item tsc-net">
                                <span class="tsc-calculation-label">Net Salary</span>
                                <span class="tsc-calculation-value" id="display-net">₹0.00</span>
                            </div>
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
                {{-- PAYMENT INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-credit-card" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Payment Information</h4>
                            <p>Choose the payment status and payment date.</p>
                        </div>
                    </div>

                    <div class="tsc-grid tsc-grid-2">
                        <div class="tsc-field">
                            <label>
                                Payment Status <span class="tsc-required">*</span>
                            </label>

                            <div class="tsc-status-options">
                                <div class="tsc-status-option">
                                    <input type="radio"
                                           name="payment_status"
                                           id="status_pending"
                                           value="Pending"
                                           {{ old('payment_status', 'Pending') == 'Pending' ? 'checked' : '' }}>

                                    <label for="status_pending">
                                        <i class="bi bi-clock" aria-hidden="true"></i>
                                        Pending
                                    </label>
                                </div>

                                <div class="tsc-status-option">
                                    <input type="radio"
                                           name="payment_status"
                                           id="status_paid"
                                           value="Paid"
                                           {{ old('payment_status') == 'Paid' ? 'checked' : '' }}>

                                    <label for="status_paid">
                                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                                        Paid
                                    </label>
                                </div>
                            </div>

                            @error('payment_status')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="tsc-field">
                            <label for="payment_date">Payment Date</label>

                            <div class="tsc-input-wrap">
                                <i class="bi bi-calendar-event" aria-hidden="true"></i>

                                <input type="date"
                                       name="payment_date"
                                       id="payment_date"
                                       value="{{ old('payment_date') }}"
                                       class="tsc-control">
                            </div>

                            @error('payment_date')
                                <div class="tsc-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ADDITIONAL INFORMATION --}}
                <div class="tsc-form-section">
                    <div class="tsc-section-heading">
                        <div class="tsc-section-heading-icon">
                            <i class="bi bi-chat-left-text" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h4>Additional Information</h4>
                            <p>Add any salary-related remarks if needed.</p>
                        </div>
                    </div>

                    <div class="tsc-field">
                        <label for="remarks">Remarks</label>

                        <textarea name="remarks"
                                  id="remarks"
                                  class="tsc-textarea"
                                  placeholder="Enter any additional salary notes...">{{ old('remarks') }}</textarea>

                        @error('remarks')
                            <div class="tsc-error">{{ $message }}</div>
                        @enderror
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
                {{-- ACTIONS --}}
                <div class="tsc-form-actions">
                    <a href="{{ route('admin.teachers.salary.index') }}"
                       class="tsc-cancel-btn">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                        Cancel
                    </a>

                    <button type="submit" class="tsc-save-btn">
                        <i class="bi bi-check-lg" aria-hidden="true"></i>
                        Generate Salary
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
/* TEACHER SALARY CREATE — TRANSPORT PAGE DESIGN SYSTEM
   No page header or hero. Scoped to this page. */

.teacher-salary-create-page {
    --tsc-ink: #263b35;
    --tsc-text: #39483e;
    --tsc-muted: #7b857b;
    --tsc-line: #e5e1d5;
    --tsc-paper: #fffefa;
    --tsc-chalk: #f5f3e9;
    --tsc-blue: #426b8a;
    --tsc-green: #43835d;
    --tsc-amber: #a87543;
    --tsc-red: #b95b50;
    --tsc-serif: var(--serif, Georgia, "Times New Roman", serif);

    min-height: calc(100vh - 70px);
    color: var(--tsc-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.teacher-salary-create-page *,
.teacher-salary-create-page *::before,
.teacher-salary-create-page *::after {
    box-sizing: border-box;
}

.teacher-salary-create-page a,
.teacher-salary-create-page button,
.teacher-salary-create-page input,
.teacher-salary-create-page select,
.teacher-salary-create-page textarea {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.teacher-salary-create-page .tsc-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
}

.teacher-salary-create-page .tsc-paper-panel {
    overflow: hidden;
    border: 1px solid var(--tsc-line);
    border-radius: 15px;
    background: var(--tsc-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* ALERTS */

.teacher-salary-create-page .tsc-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-success {
    border: 1px solid #d2e5d1;
    color: #347849;
    background: #f1f8ef;
}

.teacher-salary-create-page .tsc-error {
    border: 1px solid #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.teacher-salary-create-page .tsc-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: rgba(255, 255, 255, .7);
}

/* PANEL HEADER */

.teacher-salary-create-page .tsc-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.teacher-salary-create-page .tsc-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--tsc-ink);
    background: #f1f2e8;
    font-size: 16px;
}

.teacher-salary-create-page .tsc-title-wrap h3 {
    margin: 0;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.teacher-salary-create-page .tsc-title-wrap p {
    margin: 5px 0 0;
    color: var(--tsc-muted);
    font-size: 11px;
    line-height: 1.7;
}

.teacher-salary-create-page .tsc-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 9px 14px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: var(--tsc-paper);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.teacher-salary-create-page .tsc-back-btn:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

/* FORM */

.teacher-salary-create-page .tsc-form {
    padding: 24px;
}

.teacher-salary-create-page .tsc-form-section {
    margin-bottom: 28px;
    padding-bottom: 26px;
    border-bottom: 1px dashed #ddd8ca;
}

.teacher-salary-create-page .tsc-form-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.teacher-salary-create-page .tsc-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #eeece3;
}

.teacher-salary-create-page .tsc-section-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #dce6da;
    border-radius: 11px;
    color: #527f70;
    background: #edf3e9;
    font-size: 15px;
}

.teacher-salary-create-page .tsc-section-heading h4 {
    margin: 0;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 17px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-section-heading p {
    margin: 4px 0 0;
    color: var(--tsc-muted);
    font-size: 10px;
    line-height: 1.7;
}

.teacher-salary-create-page .tsc-grid {
    display: grid;
    gap: 16px;
}

.teacher-salary-create-page .tsc-grid-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.teacher-salary-create-page .tsc-grid-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.teacher-salary-create-page .tsc-field {
    min-width: 0;
}

.teacher-salary-create-page .tsc-field label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.teacher-salary-create-page .tsc-required {
    color: var(--tsc-red);
}

.teacher-salary-create-page .tsc-input-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.teacher-salary-create-page .tsc-input-wrap > i {
    position: absolute;
    left: 13px;
    z-index: 1;
    color: #829083;
    font-size: 13px;
    pointer-events: none;
}

.teacher-salary-create-page .tsc-control,
.teacher-salary-create-page .tsc-textarea {
    width: 100%;
    min-width: 0;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--tsc-text);
    background: var(--tsc-paper);
    font-family: inherit;
    font-size: 12px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.teacher-salary-create-page .tsc-control {
    height: 44px;
    padding: 0 12px 0 38px;
}

.teacher-salary-create-page .tsc-textarea {
    min-height: 105px;
    padding: 12px 13px;
    resize: vertical;
}

.teacher-salary-create-page .tsc-control:hover,
.teacher-salary-create-page .tsc-textarea:hover {
    border-color: #bdcabb;
}

.teacher-salary-create-page .tsc-control:focus,
.teacher-salary-create-page .tsc-textarea:focus {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.teacher-salary-create-page .tsc-control::placeholder,
.teacher-salary-create-page .tsc-textarea::placeholder {
    color: #9a9f93;
}

/* CALCULATION */

.teacher-salary-create-page .tsc-calculation {
    margin-top: 20px;
    padding: 18px;
    border: 1px solid #e7dfc9;
    border-radius: 12px;
    background: #faf5e8;
}

.teacher-salary-create-page .tsc-calculation-heading {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
    color: #765e32;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.teacher-salary-create-page .tsc-calculation-heading i {
    color: var(--tsc-amber);
}

.teacher-salary-create-page .tsc-calculation-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 13px;
}

.teacher-salary-create-page .tsc-calculation-item {
    min-width: 0;
    padding: 14px;
    border: 1px solid #e5e1d5;
    border-radius: 10px;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-calculation-label {
    display: block;
    color: #758176;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.teacher-salary-create-page .tsc-calculation-value {
    display: block;
    margin-top: 6px;
    overflow-wrap: anywhere;
    color: var(--tsc-ink);
    font-family: var(--tsc-serif);
    font-size: 18px;
    font-weight: 700;
}

.teacher-salary-create-page .tsc-deduction {
    color: var(--tsc-red);
}

.teacher-salary-create-page .tsc-net {
    border-color: #d4e5d0;
    background: #edf6e9;
}

.teacher-salary-create-page .tsc-net .tsc-calculation-value {
    color: #347849;
}

/* STATUS OPTIONS */

.teacher-salary-create-page .tsc-status-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.teacher-salary-create-page .tsc-status-option {
    position: relative;
}

.teacher-salary-create-page .tsc-status-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.teacher-salary-create-page .tsc-status-option label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 15px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: var(--tsc-paper);
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
}

.teacher-salary-create-page .tsc-status-option label:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

.teacher-salary-create-page .tsc-status-option input:checked + label {
    border-color: var(--tsc-ink);
    color: var(--tsc-ink);
    background: #f1f2e8;
}

/* ERRORS */

.teacher-salary-create-page .tsc-error {
    margin-top: 6px;
    color: #b95b50;
    font-size: 10px;
    font-weight: 700;
}

/* ACTIONS */

.teacher-salary-create-page .tsc-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
    padding-top: 22px;
    border-top: 1px dashed #ddd8ca;
}

.teacher-salary-create-page .tsc-cancel-btn,
.teacher-salary-create-page .tsc-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 16px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.teacher-salary-create-page .tsc-cancel-btn {
    border: 1px solid #deded2;
    color: #5d6b60;
    background: var(--tsc-paper);
}

.teacher-salary-create-page .tsc-cancel-btn:hover {
    border-color: #b5c4b0;
    color: var(--tsc-ink);
    background: #f0f3e9;
}

.teacher-salary-create-page .tsc-save-btn {
    border: 1px solid var(--tsc-ink);
    color: #fff;
    background: var(--tsc-ink);
}

.teacher-salary-create-page .tsc-save-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

/* TABLET */

@media (max-width: 1000px) {
    .teacher-salary-create-page .tsc-grid-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .teacher-salary-create-page .tsc-calculation-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* MOBILE */

@media (max-width: 700px) {
    .teacher-salary-create-page .tsc-container {
        padding: 18px 14px;
    }

    .teacher-salary-create-page .tsc-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .teacher-salary-create-page .tsc-title-wrap {
        gap: 10px;
    }

    .teacher-salary-create-page .tsc-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .teacher-salary-create-page .tsc-title-wrap h3 {
        font-size: 16px;
    }

    .teacher-salary-create-page .tsc-title-wrap p {
        font-size: 10px;
    }

    .teacher-salary-create-page .tsc-back-btn {
        width: 100%;
    }

    .teacher-salary-create-page .tsc-form {
        padding: 17px 15px;
    }

    .teacher-salary-create-page .tsc-section-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .teacher-salary-create-page .tsc-section-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .teacher-salary-create-page .tsc-grid-2,
    .teacher-salary-create-page .tsc-grid-3,
    .teacher-salary-create-page .tsc-calculation-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .teacher-salary-create-page .tsc-form-actions {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .teacher-salary-create-page .tsc-cancel-btn,
    .teacher-salary-create-page .tsc-save-btn {
        width: 100%;
    }
}

/* ACCESSIBILITY AND REDUCED MOTION */

.teacher-salary-create-page a:focus-visible,
.teacher-salary-create-page button:focus-visible,
.teacher-salary-create-page input:focus-visible,
.teacher-salary-create-page select:focus-visible,
.teacher-salary-create-page textarea:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .teacher-salary-create-page *,
    .teacher-salary-create-page *::before,
    .teacher-salary-create-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Salary calculation fields
    const basicInput = document.getElementById('basic_salary');
    const allowancesInput = document.getElementById('allowances');
    const deductionRateInput = document.getElementById('deduction_rate');

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
    // Staff fields
    const staffType = document.getElementById('staff_type');
    const staffSelect = document.getElementById('staff_member_id');
    const teacherIdInput = document.getElementById('teacher_id');
    const otherStaffIdInput = document.getElementById('other_staff_id');
    const staffHelp = document.getElementById('staff-help');

    const allStaffOptions = Array.from(staffSelect.options)
        .filter(option => option.value !== '');

    function updateStaffOptions() {
        const category = staffType.value;
        const previousTeacherId = teacherIdInput.value;
        const previousOtherStaffId = otherStaffIdInput.value;

        // Clear IDs so the form cannot submit a stale staff selection.
        teacherIdInput.value = '';
        otherStaffIdInput.value = '';

        staffSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = category
            ? 'Select Staff Member'
            : 'Select Staff Type First';

        staffSelect.appendChild(placeholder);

        const matchingOptions = allStaffOptions.filter(option => {
            return option.dataset.category === category;
        });

        matchingOptions.forEach(option => {
            staffSelect.appendChild(option);
        });

        const previousValue = category === 'Teacher'
            ? previousTeacherId
            : previousOtherStaffId;

        const previousOptionExists = matchingOptions.some(option => {
            return option.value === previousValue;
        });

        staffSelect.value = previousOptionExists ? previousValue : '';

        // Populate the correct hidden field.
        if (staffSelect.value) {
            if (category === 'Teacher') {
                teacherIdInput.value = staffSelect.value;
            } else {
                otherStaffIdInput.value = staffSelect.value;
            }
        }

        staffSelect.disabled = !category || matchingOptions.length === 0;

        const labels = {
            Teacher: 'teachers',
            Driver: 'drivers',
            Peon: 'peons',
            Librarian: 'librarians',
            Accountant: 'accountants',
            Receptionist: 'receptionists',
            OtherStaff: 'other staff members'
        };

        if (!category) {
            staffHelp.textContent =
                'Choose a staff type to see matching staff members.';
        } else if (matchingOptions.length === 0) {
            staffHelp.textContent =
                'No matching staff found. Check the staff member designations.';
        } else {
            staffHelp.textContent =
                'Showing ' + matchingOptions.length + ' ' +
                labels[category] + '.';
        }
    }

    staffType.addEventListener('change', updateStaffOptions);

    staffSelect.addEventListener('change', function () {
        teacherIdInput.value = '';
        otherStaffIdInput.value = '';

        if (!staffSelect.value) {
            return;
        }

        if (staffType.value === 'Teacher') {
            teacherIdInput.value = staffSelect.value;
        } else {
            otherStaffIdInput.value = staffSelect.value;
        }
    });

    // Initialize dropdown and restore any old selection.
    updateStaffOptions();

    // Salary calculation
    function getValue(input) {
        return parseFloat(input.value) || 0;
    }

    function formatMoney(value) {
        return '₹' + value.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
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
        const basic = getValue(basicInput);
        const allowances = getValue(allowancesInput);
        const deductionRate = getValue(deductionRateInput);

        const deductionAmount = basic * deductionRate / 100;
        const netSalary = basic + allowances - deductionAmount;

        displayBasic.textContent = formatMoney(basic);
        displayAllowances.textContent = formatMoney(allowances);
        displayDeductions.textContent = formatMoney(deductionAmount);
        displayNet.textContent = formatMoney(netSalary);
    }

    basicInput.addEventListener('input', calculateSalary);
    allowancesInput.addEventListener('input', calculateSalary);
    deductionRateInput.addEventListener('input', calculateSalary);

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