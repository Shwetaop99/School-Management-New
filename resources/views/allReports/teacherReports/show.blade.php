@extends('layouts.app')

@section('title', 'Teacher Report | Admin')
@section('page-title', 'Teacher Report')

@section('content')

@php
    $teacherName = trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''));
    $attendanceCount = $attendanceRecords->count();
    $salaryCount = $salaryRecords->count();
    $presentCount = $attendanceRecords->where('status', 'Present')->count();
    $absentCount = $attendanceRecords->where('status', 'Absent')->count();
    $halfDayCount = $attendanceRecords->where('status', 'Half Day')->count();
    $paidCount = $salaryRecords->where('payment_status', 'Paid')->count();
    $pendingCount = $salaryRecords->where('payment_status', 'Pending')->count();

    $schoolName = $schoolSetting->school_name ?? config('app.name', 'School Management System');
    $schoolAddress = collect([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ])->filter()->implode(', ');
@endphp

<div class="payroll-simple teacher-individual-report">
    <div class="container-fluid py-4">

        <div class="teacher-print-header">
        @if($schoolSetting && $schoolSetting->logo_url)
            <img src="{{ $schoolSetting->logo_url }}" alt="School Logo">
        @endif
        <h2>{{ $schoolName }}</h2>
        @if($schoolAddress)
            <p>{{ $schoolAddress }}</p>
        @endif
        <p>
            {{ $schoolSetting->phone ?? '' }}
            @if($schoolSetting && $schoolSetting->phone && $schoolSetting->email) · @endif
            {{ $schoolSetting->email ?? '' }}
        </p>
        <h3>Individual Teacher Report</h3>
    </div>

        <div class="teacher-print-layout">

            <div class="teacher-print-section">
                <h3>Teacher Profile</h3>
                <table class="teacher-print-table">
                    <tbody>
                        <tr>
                            <th>Teacher Name</th>
                            <td>{{ $teacherName ?: 'Not Provided' }}</td>
                            <th>Teacher ID</th>
                            <td>{{ $teacher->teacher_id ?? 'Not Assigned' }}</td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>{{ $teacher->subject ?? 'Not Provided' }}</td>
                            <th>Qualification</th>
                            <td>{{ $teacher->qualification ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <th>Employment Status</th>
                            <td>{{ $teacher->status ?? 'Not Provided' }}</td>
                            <th>Joining Date</th>
                            <td>{{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not Provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="teacher-print-section">
                <h3>Personal Information</h3>
                <table class="teacher-print-table">
                    <tbody>
                        <tr>
                            <th>First Name</th>
                            <td>{{ $teacher->first_name ?? 'Not Provided' }}</td>
                            <th>Last Name</th>
                            <td>{{ $teacher->last_name ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $teacher->gender ?? 'Not Provided' }}</td>
                            <th>Date of Birth</th>
                            <td>{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <th>Qualification</th>
                            <td>{{ $teacher->qualification ?? 'Not Provided' }}</td>
                            <th>Subject</th>
                            <td>{{ $teacher->subject ?? 'Not Provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="teacher-print-section">
                <h3>Contact Information</h3>
                <table class="teacher-print-table">
                    <tbody>
                        <tr>
                            <th>Email Address</th>
                            <td>{{ $teacher->email ?? 'Not Provided' }}</td>
                            <th>Phone Number</th>
                            <td>{{ $teacher->phone ?? 'Not Provided' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td colspan="3">{{ $teacher->address ?? 'Not Provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="teacher-print-section">
                <h3>Attendance History</h3>
                <table class="teacher-print-table teacher-print-data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Overtime Hours</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceRecords as $attendance)
                            <tr>
                                <td>{{ $attendance->attendance_date ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') : '—' }}</td>
                                <td>{{ $attendance->status ?? 'Not Provided' }}</td>
                                <td>{{ number_format((float) ($attendance->overtime_hours ?? 0), 2) }}</td>
                                <td>{{ $attendance->remarks ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="teacher-print-empty">No attendance records available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="teacher-print-section">
                <h3>Month-wise Salary Details</h3>
                <table class="teacher-print-table teacher-print-data-table">
                    <thead>
                        <tr>
                            <th>Salary Month</th>
                            <th>Basic Salary</th>
                            <th>Allowances</th>
                            <th>Deductions</th>
                            <th>Net Salary</th>
                            <th>Payment Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaryRecords as $salary)
                            <tr>
                                <td>{{ $salary->salary_month ? \Carbon\Carbon::parse($salary->salary_month)->format('F Y') : '—' }}</td>
                                <td>&#8377;{{ number_format((float) ($salary->basic_salary ?? 0), 2) }}</td>
                                <td>&#8377;{{ number_format((float) ($salary->allowances ?? 0), 2) }}</td>
                                <td>&#8377;{{ number_format((float) ($salary->deductions ?? 0), 2) }}</td>
                                <td class="teacher-print-net">&#8377;{{ number_format((float) ($salary->net_salary ?? 0), 2) }}</td>
                                <td>{{ $salary->payment_status ?? 'Pending' }}</td>
                                <td>{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="teacher-print-empty">No salary records available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="teacher-print-footer">
                <span>{{ $schoolName }} | Teacher ID: {{ $teacher->teacher_id ?? '—' }}</span>
                <span>Generated on {{ now()->format('d M Y, h:i A') }}</span>
            </div>

        </div>
        <div class="teacher-report-topbar">
            <a href="{{ route('admin.teachers.reports.index') }}" class="teacher-report-back">
                <span>←</span> Back to Teacher Reports
            </a>

            <div class="teacher-report-actions">
                <a href="{{ route('admin.teachers.reports.pdf', $teacher->id) }}" class="teacher-action-btn">
                    PDF Download
                </a>

                <a href="{{ route('admin.teachers.reports.excel', $teacher->id) }}" class="teacher-action-btn">
                    Excel Download
                </a>

                <button type="button" class="teacher-action-btn teacher-print-btn" onclick="window.print()">
                    Print Report
                </button>
            </div>
        </div>

        <section class="teacher-report-hero">
            <div class="teacher-hero-content">
                <span class="teacher-eyebrow">FACULTY RECORDS</span>
                <h1>Teacher Profile & Report</h1>
                <p>
                    Complete employment information, attendance history and month-wise salary details.
                </p>

                <div class="teacher-hero-meta">
                    <span>Teacher ID: {{ $teacher->teacher_id ?? 'Not Assigned' }}</span>
                    <span class="teacher-status {{ strtolower($teacher->status ?? '') === 'active' ? 'is-active' : 'is-inactive' }}">
                        {{ $teacher->status ?? 'Not Provided' }}
                    </span>
                </div>
            </div>

            <div class="teacher-hero-art">
                <div class="teacher-art-icon">✦</div>
                <div class="teacher-art-title">Faculty</div>
                <div class="teacher-art-subtitle">Professional Record</div>
                <div class="teacher-art-line"></div>
                <div class="teacher-art-note">Information · Attendance · Salary</div>
            </div>
        </section>

        <section class="teacher-profile-card">
            <div class="teacher-profile-main">
                @if($teacher->profile_image)
                    <img src="{{ $teacher->profile_image }}" alt="{{ $teacherName }}" class="teacher-avatar">
                @else
                    <div class="teacher-avatar teacher-avatar-placeholder">
                        {{ strtoupper(substr($teacher->first_name ?? 'T', 0, 1) . substr($teacher->last_name ?? '', 0, 1)) }}
                    </div>
                @endif

                <div class="teacher-profile-details">
                    <span class="teacher-section-kicker">TEACHER PROFILE</span>
                    <h2>{{ $teacherName ?: 'Teacher Name Not Available' }}</h2>
                    <p>{{ $teacher->subject ?? 'Subject Not Provided' }} · {{ $teacher->qualification ?? 'Qualification Not Provided' }}</p>
                    <span class="teacher-profile-id">ID: {{ $teacher->teacher_id ?? 'Not Assigned' }}</span>
                </div>
            </div>

            <div class="teacher-profile-side">
                <span class="teacher-side-label">EMPLOYMENT STATUS</span>
                <strong class="{{ strtolower($teacher->status ?? '') === 'active' ? 'status-text-active' : 'status-text-inactive' }}">
                    {{ $teacher->status ?? 'Not Provided' }}
                </strong>
                <span class="teacher-side-label">JOINING DATE</span>
                <strong>
                    {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not Provided' }}
                </strong>
            </div>
        </section>

        <section class="teacher-report-card">
            <div class="teacher-section-heading">
                <div>
                    <span class="teacher-section-kicker">PERSONAL DETAILS</span>
                    <h3>Personal Information</h3>
                </div>
            </div>

            <div class="teacher-info-grid">
                <div class="teacher-info-item">
                    <span>First Name</span>
                    <strong>{{ $teacher->first_name ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Last Name</span>
                    <strong>{{ $teacher->last_name ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Gender</span>
                    <strong>{{ $teacher->gender ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Date of Birth</span>
                    <strong>{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Qualification</span>
                    <strong>{{ $teacher->qualification ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Subject</span>
                    <strong>{{ $teacher->subject ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Joining Date</span>
                    <strong>{{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Teacher ID</span>
                    <strong>{{ $teacher->teacher_id ?? 'Not Provided' }}</strong>
                </div>
            </div>
        </section>

        <section class="teacher-report-card">
            <div class="teacher-section-heading">
                <div>
                    <span class="teacher-section-kicker">CONTACT DETAILS</span>
                    <h3>Contact Information</h3>
                </div>
            </div>

            <div class="teacher-info-grid">
                <div class="teacher-info-item">
                    <span>Email Address</span>
                    <strong>{{ $teacher->email ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item">
                    <span>Phone Number</span>
                    <strong>{{ $teacher->phone ?? 'Not Provided' }}</strong>
                </div>
                <div class="teacher-info-item teacher-info-wide">
                    <span>Address</span>
                    <strong>{{ $teacher->address ?? 'Not Provided' }}</strong>
                </div>
            </div>
        </section>

        <section class="teacher-report-card">
            <div class="teacher-section-heading">
                <div>
                    <span class="teacher-section-kicker">ATTENDANCE HISTORY</span>
                    <h3>Teacher Attendance Records</h3>
                    <p>Attendance entries saved for this teacher.</p>
                </div>
            </div>

            <div class="teacher-mini-stats">
                <div><strong>{{ $presentCount }}</strong><span>Present</span></div>
                <div><strong>{{ $absentCount }}</strong><span>Absent</span></div>
                <div><strong>{{ $halfDayCount }}</strong><span>Half Day</span></div>
            </div>

            @if($attendanceRecords->isNotEmpty())
                <div class="teacher-table-wrap">
                    <table class="teacher-data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Attendance Date</th>
                                <th>Status</th>
                                <th>Overtime Hours</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendanceRecords as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->attendance_date ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') : '—' }}</td>
                                    <td>
                                        <span class="teacher-payment-status {{ strtolower(str_replace(' ', '-', $attendance->status ?? '')) }}">
                                            {{ $attendance->status ?? 'Not Provided' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format((float) ($attendance->overtime_hours ?? 0), 2) }}</td>
                                    <td>{{ $attendance->remarks ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="teacher-empty-state">
                    <span class="empty-state-icon">A</span>
                    <strong>No attendance records available</strong>
                    <p>There are currently no saved attendance entries for this teacher.</p>
                </div>
            @endif
        </section>

        <section class="teacher-report-card">
            <div class="teacher-section-heading">
                <div>
                    <span class="teacher-section-kicker">PAYROLL HISTORY</span>
                    <h3>Month-wise Salary & Payment Details</h3>
                    <p>Each saved salary entry is displayed separately.</p>
                </div>
            </div>

            <div class="teacher-mini-stats salary-mini-stats">
                <div><strong>{{ $salaryCount }}</strong><span>Total Entries</span></div>
                <div><strong>{{ $paidCount }}</strong><span>Paid</span></div>
                <div><strong>{{ $pendingCount }}</strong><span>Pending</span></div>
            </div>

            @if($salaryRecords->isNotEmpty())
                <div class="teacher-table-wrap">
                    <table class="teacher-data-table salary-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Salary Month</th>
                                <th>Basic Salary</th>
                                <th>Allowances</th>
                                <th>Deductions</th>
                                <th>Gross Salary</th>
                                <th>Net Salary</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryRecords as $salary)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $salary->salary_month ? \Carbon\Carbon::parse($salary->salary_month)->format('F Y') : '—' }}</td>
                                    <td>₹{{ number_format((float) ($salary->basic_salary ?? 0), 2) }}</td>
                                    <td>₹{{ number_format((float) ($salary->allowances ?? 0), 2) }}</td>
                                    <td>₹{{ number_format((float) ($salary->deductions ?? 0), 2) }}</td>
                                    <td>₹{{ number_format((float) ($salary->gross_salary ?? 0), 2) }}</td>
                                    <td class="teacher-net-salary">₹{{ number_format((float) ($salary->net_salary ?? 0), 2) }}</td>
                                    <td>
                                        <span class="teacher-payment-status {{ strtolower($salary->payment_status ?? 'pending') === 'paid' ? 'paid' : 'pending' }}">
                                            {{ $salary->payment_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}</td>
                                    <td>{{ $salary->payment_method ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="teacher-empty-state">
                    <span class="empty-state-icon">S</span>
                    <strong>No salary records available</strong>
                    <p>There are currently no saved salary entries for this teacher.</p>
                </div>
            @endif
        </section>

        <section class="teacher-report-card">
            <div class="teacher-section-heading">
                <div>
                    <span class="teacher-section-kicker">TEACHING SCHEDULE</span>
                    <h3>Timetable Summary</h3>
                </div>
            </div>

            <div class="teacher-timetable-summary">
                <strong>{{ $timetableCount }}</strong>
                <span>Saved timetable entries for this teacher</span>
            </div>
        </section>

        <div class="teacher-report-footer">
            <span>Teacher Report · {{ $teacher->teacher_id ?? '' }}</span>
            <span>Generated on {{ now()->format('d M Y, h:i A') }}</span>
        </div>

    </div>
</div>

<style>
.teacher-individual-report {
    --teacher-bg: #f5f4f0;
    --teacher-paper: #fffefa;
    --teacher-ink: #1e2a25;
    --teacher-primary: #2c3e36;
    --teacher-hover: #3a4f45;
    --teacher-muted: #5c6b64;
    --teacher-border: #d4cfc2;
    --teacher-sage: #527f70;
    background: var(--teacher-bg);
    color: var(--teacher-ink);
    border-radius: 18px;
    min-height: 100vh;
    padding: 1px 0 24px;
}

.teacher-report-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 22px;
}

.teacher-report-back {
    color: var(--teacher-primary);
    font-weight: 700;
    text-decoration: none;
}

.teacher-report-back:hover {
    color: var(--teacher-hover);
}

.teacher-report-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.teacher-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    border: 1px solid var(--teacher-border);
    border-radius: 9px;
    background: var(--teacher-paper);
    color: var(--teacher-primary);
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.teacher-action-btn:hover {
    background: var(--teacher-primary);
    color: #fff;
    border-color: var(--teacher-primary);
    transform: translateY(-1px);
}

.teacher-print-btn {
    background: var(--teacher-primary);
    color: #fff;
    border-color: var(--teacher-primary);
}

.teacher-report-hero {
    display: grid;
    grid-template-columns: 1.35fr .65fr;
    overflow: hidden;
    min-height: 245px;
    margin-bottom: 22px;
    border: 1px solid var(--teacher-border);
    border-radius: 17px;
    background: #fdfbf7;
}

.teacher-hero-content {
    padding: 34px 38px;
    align-self: center;
}

.teacher-eyebrow,
.teacher-section-kicker {
    display: block;
    color: #8b795b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

.teacher-hero-content h1 {
    margin: 10px 0;
    color: var(--teacher-ink);
    font-family: Georgia, serif;
    font-size: clamp(27px, 3vw, 39px);
    font-weight: 600;
}

.teacher-hero-content p {
    max-width: 590px;
    margin: 0;
    color: var(--teacher-muted);
    font-size: 14px;
    line-height: 1.7;
}

.teacher-hero-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin-top: 22px;
    color: var(--teacher-primary);
    font-size: 13px;
    font-weight: 700;
}

.teacher-status {
    display: inline-flex;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
}

.teacher-status.is-active {
    background: #e6f1e8;
    color: #34704a;
}

.teacher-status.is-inactive {
    background: #f8e8e4;
    color: #a54e43;
}

.teacher-hero-art {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 25px;
    background: linear-gradient(140deg, #f8f1e3, #e8dcc6);
    color: #665b46;
    text-align: center;
}

.teacher-art-icon {
    display: grid;
    place-items: center;
    width: 55px;
    height: 55px;
    margin-bottom: 13px;
    border: 1px solid rgba(139,121,91,.35);
    border-radius: 50%;
    font-size: 27px;
}

.teacher-art-title {
    font-family: Georgia, serif;
    font-size: 27px;
}

.teacher-art-subtitle {
    margin-top: 3px;
    font-size: 12px;
    letter-spacing: 1px;
}

.teacher-art-line {
    width: 65px;
    height: 1px;
    margin: 17px 0;
    background: #b5a487;
}

.teacher-art-note {
    font-size: 11px;
    color: #786b54;
}

.teacher-profile-card,
.teacher-report-card {
    margin-bottom: 20px;
    padding: 25px;
    border: 1px solid #e6e1d6;
    border-radius: 15px;
    background: var(--teacher-paper);
    box-shadow: 0 4px 14px rgba(38, 48, 39, .035);
}

.teacher-profile-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.teacher-profile-main {
    display: flex;
    align-items: center;
    gap: 20px;
    min-width: 0;
}

.teacher-avatar {
    flex: 0 0 88px;
    width: 88px;
    height: 88px;
    border: 4px solid #eee8db;
    border-radius: 50%;
    object-fit: cover;
}

.teacher-avatar-placeholder {
    display: grid;
    place-items: center;
    background: #e9eee7;
    color: var(--teacher-primary);
    font-family: Georgia, serif;
    font-size: 29px;
    font-weight: 700;
}

.teacher-profile-details h2 {
    margin: 6px 0;
    color: var(--teacher-ink);
    font-family: Georgia, serif;
    font-size: 25px;
}

.teacher-profile-details p {
    margin: 0 0 8px;
    color: var(--teacher-muted);
    font-size: 13px;
}

.teacher-profile-id {
    color: var(--teacher-primary);
    font-size: 12px;
    font-weight: 800;
}

.teacher-profile-side {
    display: grid;
    gap: 5px;
    min-width: 160px;
    padding-left: 22px;
    border-left: 1px solid var(--teacher-border);
}

.teacher-side-label {
    margin-top: 5px;
    color: #8a8b7e;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.teacher-profile-side strong {
    font-size: 13px;
}

.status-text-active {
    color: #34704a;
}

.status-text-inactive {
    color: #a54e43;
}

.teacher-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.teacher-summary-card {
    position: relative;
    padding: 20px;
    overflow: hidden;
    border: 1px solid #e6e1d6;
    border-radius: 13px;
    background: var(--teacher-paper);
}

.summary-icon {
    display: grid;
    place-items: center;
    width: 32px;
    height: 32px;
    margin-bottom: 13px;
    border-radius: 9px;
    background: #f1ead8;
    color: var(--teacher-primary);
    font-size: 12px;
    font-weight: 800;
}

.summary-number {
    color: var(--teacher-primary);
    font-family: Georgia, serif;
    font-size: 31px;
    font-weight: 700;
}

.summary-label {
    margin-top: 3px;
    color: var(--teacher-ink);
    font-size: 12px;
    font-weight: 800;
}

.summary-footnote {
    margin-top: 5px;
    color: var(--teacher-muted);
    font-size: 10px;
}

.teacher-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 21px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9e5dc;
}

.teacher-section-heading h3 {
    margin: 5px 0 0;
    color: var(--teacher-ink);
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 600;
}

.teacher-section-heading p {
    margin: 5px 0 0;
    color: var(--teacher-muted);
    font-size: 12px;
}

.teacher-info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}

.teacher-info-item {
    min-width: 0;
    padding: 15px 16px;
    border: 1px solid #eeeae1;
    border-radius: 10px;
    background: #faf9f5;
}

.teacher-info-item span {
    display: block;
    margin-bottom: 7px;
    color: #7c847b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.teacher-info-item strong {
    display: block;
    overflow-wrap: anywhere;
    color: var(--teacher-ink);
    font-size: 13px;
    font-weight: 700;
}

.teacher-info-wide {
    grid-column: 1 / -1;
}

.teacher-mini-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 0 0 18px;
}

.teacher-mini-stats div {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 14px;
    border: 1px solid #e8e3d9;
    border-radius: 8px;
    background: #faf9f5;
}

.teacher-mini-stats strong {
    color: var(--teacher-primary);
    font-size: 15px;
}

.teacher-mini-stats span {
    color: var(--teacher-muted);
    font-size: 11px;
    font-weight: 700;
}

.teacher-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e8e3d9;
    border-radius: 10px;
}

.teacher-data-table {
    width: 100%;
    min-width: 720px;
    border-collapse: collapse;
    background: #fffefa;
}

.salary-table {
    min-width: 1150px;
}

.teacher-data-table th {
    padding: 13px 12px;
    background: #f2efe7;
    color: #46564b;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-align: left;
    white-space: nowrap;
}

.teacher-data-table td {
    padding: 13px 12px;
    border-top: 1px solid #eeeae2;
    color: #354239;
    font-size: 12px;
    vertical-align: middle;
}

.teacher-data-table tbody tr:hover {
    background: #faf9f5;
}

.teacher-payment-status {
    display: inline-flex;
    padding: 5px 10px;
    border-radius: 20px;
    background: #eee;
    color: #555;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.teacher-payment-status.paid,
.teacher-payment-status.present {
    background: #e5f1e7;
    color: #34704a;
}

.teacher-payment-status.pending,
.teacher-payment-status.absent {
    background: #f8e8e4;
    color: #a54e43;
}

.teacher-payment-status.half-day,
.teacher-payment-status.late,
.teacher-payment-status.leave {
    background: #f5eedc;
    color: #8b6b35;
}

.teacher-net-salary {
    color: #1a6b3c !important;
    font-weight: 800;
    white-space: nowrap;
}

.teacher-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 35px 20px;
    border: 1px dashed #d9d2c4;
    border-radius: 11px;
    background: #faf9f5;
    text-align: center;
}

.empty-state-icon {
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    margin-bottom: 10px;
    border-radius: 50%;
    background: #f1ead8;
    color: var(--teacher-primary);
    font-weight: 800;
}

.teacher-empty-state strong {
    color: var(--teacher-ink);
    font-size: 13px;
}

.teacher-empty-state p {
    margin: 5px 0 0;
    color: var(--teacher-muted);
    font-size: 11px;
}

.teacher-timetable-summary {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 18px;
    border-radius: 10px;
    background: #faf9f5;
}

.teacher-timetable-summary strong {
    color: var(--teacher-primary);
    font-family: Georgia, serif;
    font-size: 30px;
}

.teacher-timetable-summary span {
    color: var(--teacher-muted);
    font-size: 12px;
    font-weight: 600;
}

.teacher-report-footer {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding: 12px 3px;
    color: #7c847b;
    font-size: 10px;
}

.teacher-print-header {
    display: none;
}

@media (max-width: 1000px) {
    .teacher-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .teacher-report-hero {
        grid-template-columns: 1fr .55fr;
    }
}

@media (max-width: 700px) {
    .teacher-report-topbar,
    .teacher-profile-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .teacher-report-actions {
        width: 100%;
    }

    .teacher-action-btn {
        flex: 1 1 auto;
    }

    .teacher-report-hero {
        grid-template-columns: 1fr;
    }

    .teacher-hero-content {
        padding: 25px;
    }

    .teacher-hero-art {
        min-height: 180px;
    }

    .teacher-profile-side {
        width: 100%;
        padding: 15px 0 0;
        border-top: 1px solid var(--teacher-border);
        border-left: 0;
    }

    .teacher-summary-grid {
        grid-template-columns: 1fr 1fr;
    }

    .teacher-info-grid {
        grid-template-columns: 1fr;
    }

    .teacher-info-wide {
        grid-column: auto;
    }

    .teacher-profile-main {
        align-items: flex-start;
    }

    .teacher-profile-details h2 {
        font-size: 20px;
    }

    .teacher-report-card,
    .teacher-profile-card {
        padding: 18px;
    }

    .teacher-report-footer {
        flex-direction: column;
    }
}

@media (max-width: 420px) {
    .teacher-summary-grid {
        grid-template-columns: 1fr;
    }
}

@page {
    size: A4 landscape;
    margin: 12mm;
}

@media print {
    body {
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden;
    }

    .teacher-individual-report,
    .teacher-individual-report * {
        visibility: visible;
    }

    .teacher-individual-report {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 0 !important;
        background: #fff !important;
        border-radius: 0;
    }

    .teacher-report-topbar,
    .teacher-hero-art,
    .teacher-summary-grid,
    .teacher-mini-stats,
    .teacher-timetable-summary,
    .teacher-individual-report section:has(.teacher-timetable-summary) {
        display: none !important;
    }

    .teacher-report-hero {
        display: none !important;
    }

    .teacher-profile-card,
    .teacher-report-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #d4cfc2 !important;
        border-radius: 8px;
        background: #fffefa !important;
        margin-bottom: 14px;
    }

    .teacher-report-card {
        padding: 16px !important;
    }

    .teacher-section-heading h3 {
        color: #2c3e36 !important;
        font-family: Georgia, serif;
    }

    .teacher-section-heading p,
    .teacher-section-kicker {
        color: #5c6b64 !important;
    }

    .teacher-table-wrap {
        overflow: visible !important;
    }

    .teacher-data-table {
        width: 100% !important;
        min-width: 0 !important;
        border-collapse: collapse !important;
    }

    .teacher-data-table th {
        background: #2c3e36 !important;
        color: #fff !important;
        padding: 8px 7px !important;
        font-size: 9px !important;
    }

    .teacher-data-table td {
        color: #1e2a25 !important;
        padding: 7px !important;
        font-size: 9px !important;
        border-bottom: 1px solid #e5e0d5 !important;
    }

    /* Attendance: hide the row-number column */
    .teacher-report-card:has(.teacher-mini-stats) .teacher-data-table th:first-child,
    .teacher-report-card:has(.teacher-mini-stats) .teacher-data-table td:first-child {
        display: none !important;
    }

    /* Salary table: match the PDF columns */
    .salary-table th:first-child,
    .salary-table td:first-child,
    .salary-table th:nth-child(7),
    .salary-table td:nth-child(7),
    .salary-table th:nth-child(10),
    .salary-table td:nth-child(10) {
        display: none !important;
    }

    .teacher-print-header {
        display: block !important;
        margin-bottom: 20px;
        padding: 0 0 14px;
        border-bottom: 2px solid #2c3e36;
        text-align: center;
        break-inside: avoid;
    }

    .teacher-print-header img {
        display: block;
        max-width: 65px;
        max-height: 65px;
        margin: 0 auto 8px;
        object-fit: contain;
    }

    .teacher-print-header h2 {
        margin: 0 0 5px;
        color: #1e2a25 !important;
        font-family: Georgia, serif;
        font-size: 21px;
    }

    .teacher-print-header p {
        margin: 3px 0;
        color: #5c6b64 !important;
        font-size: 9px;
    }

    .teacher-print-header h3 {
        margin: 10px 0 0;
        color: #2c3e36 !important;
        font-size: 13px;
    }

    .teacher-report-footer {
        border-top: 1px solid #d4cfc2;
        color: #5c6b64 !important;
    }

    a {
        text-decoration: none !important;
        color: inherit !important;
    }
}

/* Dedicated table-based teacher print report */
.teacher-print-layout {
    display: none;
}

@media print {
    .teacher-individual-report .teacher-print-layout {
        display: block !important;
        width: 100% !important;
        color: #1e2a25 !important;
        background: #fff !important;
    }

    .teacher-individual-report .teacher-report-topbar,
    .teacher-individual-report .teacher-report-hero,
    .teacher-individual-report .teacher-profile-card,
    .teacher-individual-report .teacher-summary-grid,
    .teacher-individual-report .teacher-report-card,
    .teacher-individual-report .teacher-report-footer {
        display: none !important;
    }

    .teacher-individual-report .teacher-print-header {
        display: block !important;
        text-align: center !important;
        margin-bottom: 18px !important;
        padding-bottom: 12px !important;
        border-bottom: 2px solid #2c3e36 !important;
        break-inside: avoid;
    }

    .teacher-print-section {
        margin-bottom: 17px !important;
        break-inside: auto;
    }

    .teacher-print-section h3 {
        margin: 0 0 8px !important;
        padding: 8px 10px !important;
        background: #f1ead8 !important;
        border-left: 4px solid #2c3e36 !important;
        color: #2c3e36 !important;
        font-family: Georgia, serif !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        break-after: avoid;
    }

    .teacher-print-table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
        margin-bottom: 5px !important;
    }

    .teacher-print-table th,
    .teacher-print-table td {
        border: 1px solid #d4cfc2 !important;
        padding: 7px 8px !important;
        font-size: 9px !important;
        line-height: 1.4 !important;
        text-align: left !important;
        vertical-align: top !important;
        overflow-wrap: anywhere !important;
    }

    .teacher-print-table th {
        width: 17%;
        background: #f5f4f0 !important;
        color: #2c3e36 !important;
        font-weight: 700 !important;
    }

    .teacher-print-table td {
        background: #fffefa !important;
        color: #1e2a25 !important;
    }

    .teacher-print-data-table th {
        width: auto !important;
        background: #2c3e36 !important;
        color: #fff !important;
        font-size: 8px !important;
        text-align: center !important;
    }

    .teacher-print-data-table td {
        font-size: 8px !important;
        text-align: center !important;
    }

    .teacher-print-net {
        color: #1a6b3c !important;
        font-weight: 800 !important;
    }

    .teacher-print-empty {
        text-align: center !important;
        color: #5c6b64 !important;
        padding: 12px !important;
    }

    .teacher-print-footer {
        display: flex !important;
        justify-content: space-between !important;
        gap: 12px !important;
        margin-top: 22px !important;
        padding-top: 10px !important;
        border-top: 1px solid #d4cfc2 !important;
        color: #5c6b64 !important;
        font-size: 9px !important;
        break-inside: avoid;
    }

    .teacher-print-table thead {
        display: table-header-group;
    }

    .teacher-print-table tr {
        break-inside: avoid;
    }
}
</style>

@endsection
