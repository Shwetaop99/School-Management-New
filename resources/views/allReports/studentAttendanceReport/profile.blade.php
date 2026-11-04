@extends('layouts.app')

@section('title', 'Student Attendance Profile | Admin')
@section('page-title', 'Student Attendance Profile')

@section('content')

<div class="payroll-simple student-attendance-profile-page">

    <style>
        /* =========================================
           NORMAL SCREEN DESIGN
        ========================================= */

        .student-attendance-profile-page {
            background: #f5f4f0;
            color: #1e2a25;
            min-height: 100vh;
            padding: 10px;
        }

        .student-attendance-profile-page .profile-hero {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            background: #fdfbf7;
            border: 1px solid #d4cfc2;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .student-attendance-profile-page .hero-main,
        .student-attendance-profile-page .hero-side {
            padding: 28px;
        }

        .student-attendance-profile-page .hero-side {
            background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .student-attendance-profile-page .eyebrow {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .student-attendance-profile-page .student-name {
            font-size: 28px;
            font-weight: 800;
            color: #1e2a25;
            margin: 10px 0;
        }

        .student-attendance-profile-page .student-meta {
            color: #5c6b64;
            font-size: 14px;
            line-height: 1.9;
        }

        .student-attendance-profile-page .profile-card,
        .student-attendance-profile-page .stat-card,
        .student-attendance-profile-page .filter-card {
            background: #fff;
            border: 1px solid #d4cfc2;
            border-radius: 15px;
            padding: 18px;
            box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
        }

        .student-attendance-profile-page .profile-card {
            margin-bottom: 18px;
        }

        .student-attendance-profile-page .section-heading {
            font-size: 17px;
            font-weight: 800;
            color: #1e2a25;
            margin-bottom: 12px;
        }

        .student-attendance-profile-page .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #7b857b;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 5px;
        }

        .student-attendance-profile-page .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e36;
            overflow-wrap: anywhere;
        }

        .student-attendance-profile-page .stat-label {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .student-attendance-profile-page .stat-value {
            color: #2c3e36;
            font-size: 27px;
            font-weight: 800;
            margin-top: 9px;
        }

        .student-attendance-profile-page .stat-green {
            color: #1a6b3c;
        }

        .student-attendance-profile-page .btn-primary-custom {
            background: #2c3e36;
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .student-attendance-profile-page .btn-primary-custom:hover {
            background: #3a4f45;
            color: #fff;
        }

        .student-attendance-profile-page .btn-light-custom {
            background: #f3ead8;
            color: #2c3e36;
            border: 1px solid #d4cfc2;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .student-attendance-profile-page .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
        }

        .student-attendance-profile-page .filter-field label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: #5c6b64;
            margin-bottom: 7px;
        }

        .student-attendance-profile-page .filter-field input {
            width: 100%;
            min-height: 43px;
            border: 1px solid #d4cfc2;
            border-radius: 8px;
            padding: 9px 12px;
            background: #fdfbf7;
            color: #2c3e36;
        }

        .student-attendance-profile-page .filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 17px;
        }

        .student-attendance-profile-page .table-wrap {
            overflow-x: auto;
        }

        .student-attendance-profile-page .table {
            margin-bottom: 0;
            vertical-align: middle;
            min-width: 650px;
        }

        .student-attendance-profile-page .table thead th {
            background: #f5f4f0;
            color: #5c6b64;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 14px;
            border-bottom: 1px solid #d4cfc2;
            white-space: nowrap;
        }

        .student-attendance-profile-page .table tbody td {
            padding: 14px;
            color: #39483e;
            font-size: 13px;
            border-color: #eeeae1;
        }

        .student-attendance-profile-page .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            text-transform: capitalize;
        }

        .student-attendance-profile-page .status-present {
            background: #e4f2e8;
            color: #1a6b3c;
        }

        .student-attendance-profile-page .status-absent {
            background: #f9e7e5;
            color: #b3443b;
        }

        .student-attendance-profile-page .status-late {
            background: #fff0d9;
            color: #9b671f;
        }

        .student-attendance-profile-page .status-half {
            background: #e8eef7;
            color: #426b8a;
        }

        .student-attendance-profile-page .empty-state {
            text-align: center;
            padding: 30px !important;
            color: #7b857b !important;
        }

        .student-attendance-profile-page .progress {
            height: 9px;
            background: #eeeae1;
            border-radius: 20px;
            margin-top: 12px;
        }

        .student-attendance-profile-page .progress-bar {
            background: #1a6b3c;
            border-radius: 20px;
        }

        .student-attendance-profile-page .student-avatar {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #f3ead8;
            color: #2c3e36;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            border: 3px solid #e8dcc6;
            margin-bottom: 12px;
        }

        /* PRINT REPORT HIDDEN ON SCREEN */

        .student-attendance-profile-page .print-profile {
            display: none;
        }

        @media(max-width: 768px) {
            .student-attendance-profile-page .profile-hero {
                grid-template-columns: 1fr;
            }

            .student-attendance-profile-page .student-name {
                font-size: 23px;
            }

            .student-attendance-profile-page .filter-grid {
                grid-template-columns: 1fr;
            }
        }

        /* =========================================
           A4 LANDSCAPE PRINT SETTINGS
           MATCHING FACULTY ATTENDANCE PROFILE
        ========================================= */

        @page {
            size: A4 landscape;
            margin: 25px 30px;
        }

        @media print {

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

            .student-attendance-profile-page .screen-profile {
                display: none !important;
            }

            .student-attendance-profile-page .print-profile,
            .student-attendance-profile-page .print-profile * {
                visibility: visible !important;
            }

            .student-attendance-profile-page .print-profile {
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

            .student-attendance-profile-page {
                background: #fff !important;
                color: #26352e !important;
                padding: 0 !important;
                min-height: auto !important;
            }

            /* SCHOOL HEADER */

            .student-attendance-profile-page .print-school-header {
                text-align: center;
                border-bottom: 2px solid #2c3e36;
                padding-bottom: 12px;
                margin-bottom: 18px;
            }

            .student-attendance-profile-page .print-school-logo {
                max-height: 65px;
                max-width: 90px;
                display: block;
                margin: 0 auto 5px;
                object-fit: contain;
            }

            .student-attendance-profile-page .print-school-name {
                font-size: 21px;
                font-weight: bold;
                color: #263b35;
            }

            .student-attendance-profile-page .print-school-details {
                color: #5c6b64;
                font-size: 9px;
                margin-top: 5px;
                line-height: 1.6;
            }

            /* REPORT TITLE */

            .student-attendance-profile-page .print-report-title {
                text-align: center;
                font-size: 15px;
                font-weight: bold;
                margin: 15px 0;
                color: #263b35;
            }

            .student-attendance-profile-page .print-student-heading {
                background: #f3ead8 !important;
                padding: 10px;
                border: 1px solid #d4cfc2;
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            /* DETAILS TABLE */

            .student-attendance-profile-page .print-details-table,
            .student-attendance-profile-page .print-attendance-table,
            .student-attendance-profile-page .print-summary-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .student-attendance-profile-page .print-details-table {
                margin-bottom: 16px;
            }

            .student-attendance-profile-page .print-details-table td {
                padding: 7px 8px;
                border: 1px solid #e2ded4;
                width: 25%;
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .student-attendance-profile-page .print-field-label {
                display: block;
                color: #68766e;
                font-size: 8px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .student-attendance-profile-page .print-field-value {
                font-weight: bold;
                color: #2c3e36;
                font-size: 9px;
            }

            /* SECTION HEADINGS */

            .student-attendance-profile-page .print-section-title {
                font-size: 12px;
                font-weight: bold;
                color: #263b35;
                margin: 15px 0 8px;
            }

            /* ATTENDANCE TABLE */

            .student-attendance-profile-page .print-attendance-table {
                table-layout: auto;
            }

            .student-attendance-profile-page .print-attendance-table th {
                background: #2c3e36 !important;
                color: #fff !important;
                padding: 9px 7px;
                text-align: left;
                font-size: 9px;
                border: 1px solid #2c3e36;
            }

            .student-attendance-profile-page .print-attendance-table td {
                padding: 8px 7px;
                border: 1px solid #e5e1d8;
                font-size: 9px;
                color: #26352e;
                overflow-wrap: anywhere;
            }

            .student-attendance-profile-page .print-attendance-table tbody tr:nth-child(even) {
                background: #f8f7f3 !important;
            }

            .student-attendance-profile-page .print-status-present {
                color: #1a6b3c !important;
                font-weight: bold;
            }

            .student-attendance-profile-page .print-status-absent {
                color: #b3443b !important;
                font-weight: bold;
            }

            /* SUMMARY */

            .student-attendance-profile-page .print-summary-table {
                margin-top: 8px;
            }

            .student-attendance-profile-page .print-summary-table td {
                width: 25%;
                padding: 12px 8px;
                border: 1px solid #d4cfc2;
                background: #f8f7f3 !important;
                text-align: center;
            }

            .student-attendance-profile-page .print-summary-label {
                color: #5c6b64;
                font-size: 8px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .student-attendance-profile-page .print-summary-value {
                font-size: 18px;
                font-weight: bold;
                color: #2c3e36;
                margin-top: 5px;
            }

            .student-attendance-profile-page .print-green {
                color: #1a6b3c !important;
            }

            /* FOOTER */

            .student-attendance-profile-page .print-footer {
                margin-top: 18px;
                border-top: 1px solid #d4cfc2;
                padding-top: 8px;
                color: #7b857b;
                font-size: 8px;
                text-align: right;
            }

            .student-attendance-profile-page .print-no-records {
                text-align: center;
                padding: 15px !important;
                color: #777 !important;
            }

            .student-attendance-profile-page .print-attendance-table thead {
                display: table-header-group;
            }

            .student-attendance-profile-page .print-attendance-table tr,
            .student-attendance-profile-page .print-summary-table tr {
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

                <a href="{{ route('admin.reports.student-attendance.class', [
                    'class' => $student->class,
                    'section' => $student->section,
                    'academic_year' => $academicYear,
                ]) }}"
                   class="btn btn-light-custom">

                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Student List

                </a>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.reports.student-attendance.profile.pdf', [
                        'student' => $student->id,
                        'academic_year' => $academicYear,
                        'month' => $month,
                        'from_date' => $fromDate,
                        'to_date' => $toDate,
                    ]) }}"
                       class="btn btn-light-custom">

                        <i class="fas fa-file-pdf me-1"></i> PDF

                    </a>

                    <a href="{{ route('admin.reports.student-attendance.profile.excel', [
                        'student' => $student->id,
                        'academic_year' => $academicYear,
                        'month' => $month,
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


            {{-- STUDENT PROFILE HERO --}}

            <div class="profile-hero">

                <div class="hero-main">

                    <div class="eyebrow">
                        Student Attendance Profile
                    </div>

                    <div class="student-avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <h2 class="student-name">
                        {{ $student->full_name }}
                    </h2>

                    <div class="student-meta">

                        <div>
                            <strong>Student ID:</strong>
                            {{ $student->student_id ?? $student->id }}
                        </div>

                        <div>
                            <strong>Roll Number:</strong>
                            {{ $student->roll_number ?? 'Not available' }}
                        </div>

                        <div>
                            <strong>Class:</strong>
                            {{ $student->class ?? '—' }}
                        </div>

                        <div>
                            <strong>Division:</strong>
                            {{ $student->section ?? '—' }}
                        </div>

                    </div>

                </div>

                <div class="hero-side">

                    <div class="eyebrow">
                        Attendance Percentage
                    </div>

                    <div class="stat-value stat-green">
                        {{ number_format($attendanceSummary['percentage'] ?? 0, 2) }}%
                    </div>

                    <div class="progress">

                        <div class="progress-bar"
                             style="width: {{ min(100, max(0, $attendanceSummary['percentage'] ?? 0)) }}%;">
                        </div>

                    </div>

                    <div class="student-meta mt-3">
                        Academic Year: {{ $academicYear ?: '—' }}
                    </div>

                </div>

            </div>


            {{-- FILTER ATTENDANCE --}}

            <div class="filter-card mb-4 no-print">

                <h5 class="section-heading">
                    <i class="fas fa-filter me-2"></i>
                    Filter Attendance
                </h5>

                <form method="GET"
                      action="{{ route('admin.reports.student-attendance.profile', $student->id) }}">

                    <input type="hidden"
                           name="academic_year"
                           value="{{ $academicYear }}">

                    <div class="filter-grid">

                        <div class="filter-field">

                            <label for="month">
                                Select Month
                            </label>

                            <input type="month"
                                   id="month"
                                   name="month"
                                   value="{{ $month ?? '' }}">

                        </div>

                        <div class="filter-field">

                            <label for="from_date">
                                From Date
                            </label>

                            <input type="date"
                                   id="from_date"
                                   name="from_date"
                                   value="{{ $fromDate ?? '' }}">

                        </div>

                        <div class="filter-field">

                            <label for="to_date">
                                To Date
                            </label>

                            <input type="date"
                                   id="to_date"
                                   name="to_date"
                                   value="{{ $toDate ?? '' }}">

                        </div>

                    </div>

                    <div class="filter-actions">

                        <button type="submit"
                                class="btn btn-primary-custom">

                            <i class="fas fa-search me-1"></i>
                            Apply Filter

                        </button>

                        <a href="{{ route('admin.reports.student-attendance.profile', [
                            'student' => $student->id,
                            'academic_year' => $academicYear,
                        ]) }}"
                           class="btn btn-light-custom">

                            <i class="fas fa-undo me-1"></i>
                            Reset Filter

                        </a>

                    </div>

                </form>

            </div>


            {{-- STUDENT INFORMATION --}}

            <div class="profile-card">

                <h5 class="section-heading">
                    Student Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student Name</div>
                        <div class="info-value">
                            {{ $student->full_name ?? 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student ID</div>
                        <div class="info-value">
                            {{ $student->student_id ?? $student->id }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Roll Number</div>
                        <div class="info-value">
                            {{ $student->roll_number ?? 'Not available' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Class</div>
                        <div class="info-value">
                            {{ $student->class ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Division</div>
                        <div class="info-value">
                            {{ $student->section ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value">
                            {{ $academicYear ?? '—' }}
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

                        <div class="student-meta">
                            Attendance records for the selected period.
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
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($attendanceRecords as $index => $attendance)

                                @php
                                    $status = strtolower(trim(str_replace('_', ' ', $attendance->status ?? '')));

                                    $statusClass = match($status) {
                                        'present' => 'status-present',
                                        'absent' => 'status-absent',
                                        'late' => 'status-late',
                                        'half day' => 'status-half',
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
                                            {{ str_replace('_', ' ', ucfirst($attendance->status ?? 'Not specified')) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $attendance->remarks ?: '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="empty-state">
                                        No attendance records found for the selected period.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- ATTENDANCE SUMMARY --}}

            <div class="mb-3">

                <h5 class="section-heading">
                    Attendance Summary
                </h5>

                <div class="student-meta mb-3">
                    Attendance statistics based on the selected date range.
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
         DEDICATED PRINT PROFILE
         SAME DESIGN AS FACULTY PRINT PROFILE
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
            STUDENT ATTENDANCE PROFILE
        </div>


        {{-- STUDENT HEADING --}}

        <div class="print-student-heading">

            {{ $student->full_name ?? 'Student' }}

            &nbsp; | &nbsp;

            Student ID: {{ $student->student_id ?? $student->id }}

        </div>


        {{-- STUDENT DETAILS --}}

        <table class="print-details-table">

            <tr>

                <td>
                    <span class="print-field-label">Student Name</span>
                    <span class="print-field-value">
                        {{ $student->full_name ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Student ID</span>
                    <span class="print-field-value">
                        {{ $student->student_id ?? $student->id }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Roll Number</span>
                    <span class="print-field-value">
                        {{ $student->roll_number ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Class</span>
                    <span class="print-field-value">
                        {{ $student->class ?? 'Not available' }}
                    </span>
                </td>

            </tr>

            <tr>

                <td>
                    <span class="print-field-label">Division</span>
                    <span class="print-field-value">
                        {{ $student->section ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Academic Year</span>
                    <span class="print-field-value">
                        {{ $academicYear ?? 'Not available' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Selected From Date</span>
                    <span class="print-field-value">
                        {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : 'All Dates' }}
                    </span>
                </td>

                <td>
                    <span class="print-field-label">Selected To Date</span>
                    <span class="print-field-value">
                        {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : 'All Dates' }}
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
                    <th width="7%">#</th>
                    <th width="22%">Attendance Date</th>
                    <th width="18%">Day</th>
                    <th width="18%">Status</th>
                    <th width="35%">Remarks</th>
                </tr>
            </thead>

            <tbody>

                @forelse($attendanceRecords as $index => $attendance)

                    @php
                        $status = strtolower(trim(str_replace('_', ' ', $attendance->status ?? '')));
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
                            {{ str_replace('_', ' ', ucfirst($attendance->status ?? 'Not specified')) }}
                        </td>

                        <td>
                            {{ $attendance->remarks ?: '-' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="print-no-records">
                            No attendance records found for the selected period.
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
                    <div class="print-summary-label">
                        Present Days
                    </div>

                    <div class="print-summary-value print-green">
                        {{ $attendanceSummary['present'] ?? 0 }}
                    </div>
                </td>

                <td>
                    <div class="print-summary-label">
                        Absent Days
                    </div>

                    <div class="print-summary-value">
                        {{ $attendanceSummary['absent'] ?? 0 }}
                    </div>
                </td>

                <td>
                    <div class="print-summary-label">
                        Attendance Percentage
                    </div>

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