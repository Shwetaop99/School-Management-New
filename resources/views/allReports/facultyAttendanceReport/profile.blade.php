
@extends('layouts.app')

@section('title', 'Faculty Attendance Profile | Admin')
@section('page-title', 'Faculty Attendance Profile')

@section('content')

<div class="payroll-simple faculty-profile-page">

    <style>
        /* =========================================
           NORMAL SCREEN DESIGN
        ========================================= */

        .faculty-profile-page {
            background: #f5f4f0;
            color: #1e2a25;
            min-height: 100vh;
            padding: 10px;
        }

        .faculty-profile-page .profile-hero {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            background: #fdfbf7;
            border: 1px solid #d4cfc2;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .faculty-profile-page .hero-main,
        .faculty-profile-page .hero-side {
            padding: 28px;
        }

        .faculty-profile-page .hero-side {
            background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .faculty-profile-page .eyebrow {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .faculty-profile-page .faculty-name {
            font-size: 28px;
            font-weight: 800;
            color: #1e2a25;
            margin: 10px 0;
        }

        .faculty-profile-page .faculty-meta {
            color: #5c6b64;
            font-size: 14px;
            line-height: 1.9;
        }

        .faculty-profile-page .profile-card,
        .faculty-profile-page .stat-card {
            background: #fff;
            border: 1px solid #d4cfc2;
            border-radius: 15px;
            padding: 18px;
            box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
        }

        .faculty-profile-page .profile-card {
            height: auto;
            margin: 0 0 8px !important;
        }

        .faculty-profile-page .section-heading {
            font-size: 17px;
            font-weight: 800;
            color: #1e2a25;
            margin-bottom: 12px;
        }

        .faculty-profile-page .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #7b857b;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 5px;
        }

        .faculty-profile-page .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e36;
            overflow-wrap: anywhere;
        }

        .faculty-profile-page .stat-label {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .faculty-profile-page .stat-value {
            color: #2c3e36;
            font-size: 27px;
            font-weight: 800;
            margin-top: 9px;
        }

        .faculty-profile-page .stat-green {
            color: #1a6b3c;
        }

        .faculty-profile-page .btn-primary-custom {
            background: #2c3e36;
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .faculty-profile-page .btn-primary-custom:hover {
            background: #3a4f45;
            color: #fff;
        }

        .faculty-profile-page .btn-light-custom {
            background: #f3ead8;
            color: #2c3e36;
            border: 1px solid #d4cfc2;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .faculty-profile-page .table-wrap {
            overflow-x: auto;
        }

        .faculty-profile-page .table {
            margin-bottom: 0;
            vertical-align: middle;
            min-width: 700px;
        }

        .faculty-profile-page .table thead th {
            background: #f5f4f0;
            color: #5c6b64;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 14px;
            border-bottom: 1px solid #d4cfc2;
            white-space: nowrap;
        }

        .faculty-profile-page .table tbody td {
            padding: 14px;
            color: #39483e;
            font-size: 13px;
            border-color: #eeeae1;
        }

        .faculty-profile-page .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
        }

        .faculty-profile-page .status-present {
            background: #e4f2e8;
            color: #1a6b3c;
        }

        .faculty-profile-page .status-absent {
            background: #f9e7e5;
            color: #b3443b;
        }

        .faculty-profile-page .status-half {
            background: #fff0d9;
            color: #9b671f;
        }

        .faculty-profile-page .status-leave {
            background: #f3ead8;
            color: #85612e;
        }

        .faculty-profile-page .empty-state {
            text-align: center;
            padding: 30px !important;
            color: #7b857b !important;
        }

        .faculty-profile-page .progress {
            height: 9px;
            background: #eeeae1;
            border-radius: 20px;
            margin-top: 12px;
        }

        .faculty-profile-page .progress-bar {
            background: #1a6b3c;
            border-radius: 20px;
        }

        .faculty-profile-page .profile-avatar {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e8dcc6;
            margin-bottom: 12px;
        }

        /* Print report hidden on screen */
        .faculty-profile-page .print-profile {
            display: none;
        }

        @media(max-width: 768px) {
            .faculty-profile-page .profile-hero {
                grid-template-columns: 1fr;
            }

            .faculty-profile-page .faculty-name {
                font-size: 23px;
            }
        }

        /* =========================================
           A4 LANDSCAPE PRINT SETTINGS
        ========================================= */

        @page {
            size: A4 landscape;
            margin: 25px 30px;
        }

        @media print {

            /* Hide every application element */
            body * {
                visibility: hidden !important;
            }

            html,
            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Hide the normal screen profile */
            .faculty-profile-page .screen-profile {
                display: none !important;
            }

            /* Show only the dedicated report */
            .faculty-profile-page .print-profile,
            .faculty-profile-page .print-profile * {
                visibility: visible !important;
            }

            .faculty-profile-page .print-profile {
                display: block !important;
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                font-family: Arial, sans-serif !important;
                color: #26352e !important;
                font-size: 10px !important;
            }

            .faculty-profile-page {
                background: #fff !important;
                color: #26352e !important;
                padding: 0 !important;
                min-height: auto !important;
            }

            /* SCHOOL HEADER */
            .faculty-profile-page .print-school-header {
                text-align: center;
                border-bottom: 2px solid #2c3e36;
                padding-bottom: 12px;
                margin-bottom: 18px;
            }

            .faculty-profile-page .print-school-logo {
                max-height: 65px;
                max-width: 90px;
                display: block;
                margin: 0 auto 5px;
                object-fit: contain;
            }

            .faculty-profile-page .print-school-name {
                font-size: 21px;
                font-weight: bold;
                color: #263b35;
            }

            .faculty-profile-page .print-school-details {
                color: #5c6b64;
                font-size: 9px;
                margin-top: 5px;
                line-height: 1.6;
            }

            /* REPORT TITLE */
            .faculty-profile-page .print-report-title {
                text-align: center;
                font-size: 15px;
                font-weight: bold;
                margin: 15px 0;
                color: #263b35;
            }

            .faculty-profile-page .print-faculty-heading {
                background: #f3ead8 !important;
                padding: 10px;
                border: 1px solid #d4cfc2;
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            /* FACULTY DETAILS */
            .faculty-profile-page .print-details-table,
            .faculty-profile-page .print-attendance-table,
            .faculty-profile-page .print-summary-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .faculty-profile-page .print-details-table {
                margin-bottom: 16px;
            }

            .faculty-profile-page .print-details-table td {
                padding: 7px 8px;
                border: 1px solid #e2ded4;
                width: 25%;
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .faculty-profile-page .print-field-label {
                display: block;
                color: #68766e;
                font-size: 8px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .faculty-profile-page .print-field-value {
                font-weight: bold;
                color: #2c3e36;
                font-size: 9px;
            }

            /* SECTION HEADINGS */
            .faculty-profile-page .print-section-title {
                font-size: 12px;
                font-weight: bold;
                color: #263b35;
                margin: 15px 0 8px;
            }

            /* ATTENDANCE TABLE */
            .faculty-profile-page .print-attendance-table {
                table-layout: auto;
            }

            .faculty-profile-page .print-attendance-table th {
                background: #2c3e36 !important;
                color: #fff !important;
                padding: 9px 7px;
                text-align: left;
                font-size: 9px;
                border: 1px solid #2c3e36;
            }

            .faculty-profile-page .print-attendance-table td {
                padding: 8px 7px;
                border: 1px solid #e5e1d8;
                font-size: 9px;
                color: #26352e;
                overflow-wrap: anywhere;
            }

            .faculty-profile-page .print-attendance-table tbody tr:nth-child(even) {
                background: #f8f7f3 !important;
            }

            .faculty-profile-page .print-status-present {
                color: #1a6b3c !important;
                font-weight: bold;
            }

            .faculty-profile-page .print-status-absent {
                color: #b3443b !important;
                font-weight: bold;
            }

            /* SUMMARY */
            .faculty-profile-page .print-summary-table {
                margin-top: 8px;
            }

            .faculty-profile-page .print-summary-table td {
                width: 25%;
                padding: 12px 8px;
                border: 1px solid #d4cfc2;
                background: #f8f7f3 !important;
                text-align: center;
            }

            .faculty-profile-page .print-summary-label {
                color: #5c6b64;
                font-size: 8px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .faculty-profile-page .print-summary-value {
                font-size: 18px;
                font-weight: bold;
                color: #2c3e36;
                margin-top: 5px;
            }

            .faculty-profile-page .print-green {
                color: #1a6b3c !important;
            }

            /* FOOTER */
            .faculty-profile-page .print-footer {
                margin-top: 18px;
                border-top: 1px solid #d4cfc2;
                padding-top: 8px;
                color: #7b857b;
                font-size: 8px;
                text-align: right;
            }

            .faculty-profile-page .print-no-records {
                text-align: center;
                padding: 15px !important;
                color: #777 !important;
            }

            .faculty-profile-page .print-attendance-table thead {
                display: table-header-group;
            }

            .faculty-profile-page .print-attendance-table tr,
            .faculty-profile-page .print-summary-table tr {
                page-break-inside: avoid;
            }
        }
    </style>

    {{-- =========================================
         NORMAL SCREEN PROFILE
    ========================================= --}}

    <div class="screen-profile">

        <div class="container-fluid py-4">

            {{-- PAGE ACTIONS --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 no-print">

                <a href="{{ route('admin.reports.teacher-attendance', [
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                    ]) }}"
                   class="btn btn-light-custom">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Faculty Reports
                </a>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.reports.teacher-attendance.profile.pdf', [
                        'teacher' => $teacher->id,
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                    ]) }}"
                       class="btn btn-light-custom">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>

                    <a href="{{ route('admin.reports.teacher-attendance.profile.excel', [
                        'teacher' => $teacher->id,
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                    ]) }}"
                       class="btn btn-light-custom">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="btn btn-primary-custom">
                        <i class="fas fa-print me-1"></i> Print Profile
                    </button>

                </div>
            </div>

            {{-- FACULTY PROFILE HERO --}}
            <div class="profile-hero">

                <div class="hero-main">

                    <div class="eyebrow">Faculty Attendance Profile</div>

                    @if(!empty($teacher->profile_image))
                        <img src="{{ $teacher->profile_image }}"
                             class="profile-avatar"
                             alt="Faculty Photo">
                    @endif

                    <h2 class="faculty-name">
                        {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Faculty Member' }}
                    </h2>

                    <div class="faculty-meta">

                        <div>
                            <strong>Faculty ID:</strong>
                            {{ $teacher->teacher_id ?? $teacher->id }}
                        </div>

                        <div>
                            <strong>Subject:</strong>
                            {{ $teacher->subject ?? 'Not specified' }}
                        </div>

                        <div>
                            <strong>Email:</strong>
                            {{ $teacher->email ?? 'Not available' }}
                        </div>

                        <div>
                            <strong>Phone:</strong>
                            {{ $teacher->phone ?? 'Not available' }}
                        </div>

                    </div>
                </div>

                <div class="hero-side">

                    <div class="eyebrow">Overall Attendance</div>

                    <div class="stat-value stat-green">
                        {{ number_format($attendanceSummary['percentage'] ?? 0, 2) }}%
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                             style="width: {{ min(100, max(0, $attendanceSummary['percentage'] ?? 0)) }}%;">
                        </div>
                    </div>

                    <div class="faculty-meta mt-3">
                        Based on all recorded attendance entries.
                    </div>

                </div>
            </div>

            {{-- FACULTY INFORMATION --}}
            <div class="profile-card">

                <h5 class="section-heading">Faculty Information</h5>

                <div class="row g-4">

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Qualification</div>
                        <div class="info-value">
                            {{ $teacher->qualification ?? 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Gender</div>
                        <div class="info-value">
                            {{ $teacher->gender ?? 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">
                            {{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Joining Date</div>
                        <div class="info-value">
                            {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="info-label">Address</div>
                        <div class="info-value">
                            {{ $teacher->address ?? 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            {{ ucfirst($teacher->status ?? 'Active') }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- DAILY ATTENDANCE HISTORY --}}
            <div class="profile-card">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                    <div>
                        <h5 class="section-heading mb-1">
                            Daily Attendance History
                        </h5>

                        <div class="faculty-meta">
                            Complete recorded attendance history for this faculty member.
                        </div>
                    </div>

                    <div class="eyebrow">
                        {{ $attendanceRecords->count() }} Records
                    </div>

                </div>

                <div class="table-wrap">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Attendance Date</th>
                                <th>Day</th>
                                <th>Status</th>
                                <th>Overtime Hours</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($attendanceRecords as $index => $attendance)

                                @php
                                    $statusClass = match(strtolower($attendance->status ?? '')) {
                                        'present' => 'status-present',
                                        'absent' => 'status-absent',
                                        'half day' => 'status-half',
                                        'leave' => 'status-leave',
                                        default => '',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td class="fw-bold">
                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l') }}
                                    </td>

                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $attendance->status ?? 'Not specified' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ number_format((float) ($attendance->overtime_hours ?? 0), 2) }}
                                    </td>

                                    <td>
                                        {{ $attendance->remarks ?: '-' }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="empty-state">
                                        No attendance records found for this faculty member.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ATTENDANCE SUMMARY --}}
            <div class="mb-3">

                <h5 class="section-heading">Attendance Summary</h5>

                <div class="faculty-meta mb-3">
                    Overall attendance statistics based on recorded working days.
                </div>

                <div class="row g-3">

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Present Days</div>
                            <div class="stat-value stat-green">
                                {{ $attendanceSummary['present'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Absent Days</div>
                            <div class="stat-value" style="color:#b3443b;">
                                {{ $attendanceSummary['absent'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Working Days</div>
                            <div class="stat-value">
                                {{ $attendanceSummary['working_days'] ?? 0 }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Attendance Percentage</div>
                            <div class="stat-value stat-green">
                                {{ number_format($attendanceSummary['percentage'] ?? 0, 2) }}%
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- =========================================
         PRINT PROFILE - PDF MATCHING DESIGN
    ========================================= --}}

    <div class="print-profile">

        {{-- DYNAMIC SCHOOL HEADER --}}
        <div class="print-school-header">

            @if(!empty($schoolSetting?->logo_url))
                <img src="{{ $schoolSetting->logo_url }}"
                     class="print-school-logo"
                     alt="School Logo">
            @endif

            <div class="print-school-name">
                {{ $schoolSetting->school_name ?? 'School Name' }}
            </div>

            <div class="print-school-details">

                {{ $schoolSetting->address ?? '' }}

                @if(!empty($schoolSetting?->city))
                    , {{ $schoolSetting->city }}
                @endif

                @if(!empty($schoolSetting?->district))
                    , {{ $schoolSetting->district }}
                @endif

                @if(!empty($schoolSetting?->state))
                    , {{ $schoolSetting->state }}
                @endif

                @if(!empty($schoolSetting?->phone))
                    | Phone: {{ $schoolSetting->phone }}
                @endif

                @if(!empty($schoolSetting?->email))
                    | Email: {{ $schoolSetting->email }}
                @endif

            </div>
        </div>

        {{-- REPORT TITLE --}}
        <div class="print-report-title">
            FACULTY ATTENDANCE PROFILE
        </div>

        {{-- FACULTY HEADING --}}
        <div class="print-faculty-heading">

            {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Faculty Member' }}

            &nbsp; | &nbsp;

            Faculty ID: {{ $teacher->teacher_id ?? $teacher->id }}

        </div>

        {{-- FACULTY DETAILS --}}
        <table class="print-details-table">

            <tr>
                <td>
                    <span class="print-field-label">Faculty Name</span>
                    <span class="print-field-value">
                        {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Faculty ID</span>
                    <span class="print-field-value">
                        {{ $teacher->teacher_id ?? $teacher->id }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Subject</span>
                    <span class="print-field-value">
                        {{ $teacher->subject ?? 'Not specified' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Email</span>
                    <span class="print-field-value">
                        {{ $teacher->email ?? 'Not available' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="print-field-label">Phone</span>
                    <span class="print-field-value">
                        {{ $teacher->phone ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Qualification</span>
                    <span class="print-field-value">
                        {{ $teacher->qualification ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Gender</span>
                    <span class="print-field-value">
                        {{ $teacher->gender ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Date of Birth</span>
                    <span class="print-field-value">
                        {{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not available' }}
                    </span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="print-field-label">Joining Date</span>
                    <span class="print-field-value">
                        {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Status</span>
                    <span class="print-field-value">
                        {{ ucfirst($teacher->status ?? 'Active') }}
                    </span>
                </td>

                <td colspan="2">
                    <span class="print-field-label">Address</span>
                    <span class="print-field-value">
                        {{ $teacher->address ?? 'Not available' }}
                    </span>
                </td>
            </tr>

        </table>

        {{-- DAILY ATTENDANCE HISTORY --}}
        <div class="print-section-title">
            Daily Attendance History
        </div>

        <table class="print-attendance-table">

            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="17%">Attendance Date</th>
                    <th width="15%">Day</th>
                    <th width="15%">Status</th>
                    <th width="15%">Overtime Hours</th>
                    <th width="33%">Remarks</th>
                </tr>
            </thead>

            <tbody>

                @forelse($attendanceRecords as $index => $attendance)

                    @php
                        $status = strtolower(trim($attendance->status ?? ''));
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l') }}
                        </td>

                        <td class="{{ $status === 'present' ? 'print-status-present' : ($status === 'absent' ? 'print-status-absent' : '') }}">
                            {{ $attendance->status ?? 'Not specified' }}
                        </td>

                        <td>
                            {{ number_format((float) ($attendance->overtime_hours ?? 0), 2) }}
                        </td>

                        <td>
                            {{ $attendance->remarks ?: '-' }}
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="print-no-records">
                            No attendance records found.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

        {{-- ATTENDANCE SUMMARY --}}
        <div class="print-section-title">
            Attendance Summary
        </div>

        <table class="print-summary-table">

            <tr>
                <td>
                    <div class="print-summary-label">Present Days</div>
                    <div class="print-summary-value print-green">
                        {{ $attendanceSummary['present'] ?? 0 }}
                    </div>
                </td>

                <td>
                    <div class="print-summary-label">Absent Days</div>
                    <div class="print-summary-value">
                        {{ $attendanceSummary['absent'] ?? 0 }}
                    </div>
                </td>

                <td>
                    <div class="print-summary-label">Working Days</div>
                    <div class="print-summary-value">
                        {{ $attendanceSummary['working_days'] ?? 0 }}
                    </div>
                </td>

                <td>
                    <div class="print-summary-label">Attendance Percentage</div>
                    <div class="print-summary-value print-green">
                        {{ number_format($attendanceSummary['percentage'] ?? 0, 2) }}%
                    </div>
                </td>
            </tr>

        </table>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            Generated on {{ now()->format('d M Y, h:i A') }}
        </div>

    </div>

</div>

@endsection
