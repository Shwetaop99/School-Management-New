@extends('layouts.app')

@section('title', 'Student Transport Profile | Admin')
@section('page-title', 'Student Transport Profile')

@section('content')

@php
    $studentName = $student->full_name ?: trim(collect([
        $student->first_name ?? null,
        $student->middle_name ?? null,
        $student->last_name ?? null,
    ])->filter()->implode(' '));

    $transportRecords = ($student->transportRecords ?? collect())->values();

    $formatDate = static function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->format('d M Y') : '—';
    };

    $formatTime = static function ($value) {
        return $value ? \Carbon\Carbon::parse($value)->format('h:i A') : '—';
    };

    $studentDob     = $student->dob ?? $student->date_of_birth ?? null;
    $academicYear   = $student->academic_year ?? null;

    $totalRecords    = $transportRecords->count();
    $activeRecords   = $transportRecords->filter(fn ($r) => strtolower($r->transport_status ?? '') === 'active')->count();
    $inactiveRecords = $transportRecords->filter(fn ($r) => strtolower($r->transport_status ?? '') === 'inactive')->count();
    $totalFees       = $transportRecords->sum(fn ($r) => (float) ($r->transport_fee ?? 0));

    // Vertical table rows: key => [label, value resolver]
    $verticalRows = [
        'status'        => ['Status',          fn ($r) => ucfirst($r->transport_status ?? '—')],
        'route'         => ['Route',           fn ($r) => $r->route ?? '—'],
        'vehicle'       => ['Vehicle',         fn ($r) => $r->vehicle ?? '—'],
        'type'          => ['Transport Type',  fn ($r) => ucfirst($r->transport_type ?? '—')],
        'pickup_point'  => ['Pickup Point',    fn ($r) => $r->pickup_point ?? '—'],
        'drop_point'    => ['Drop Point',      fn ($r) => $r->drop_point ?? '—'],
        'pickup_time'   => ['Pickup Time',     fn ($r) => $formatTime($r->pickup_time)],
        'drop_time'     => ['Drop Time',       fn ($r) => $formatTime($r->drop_time)],
        'start_date'    => ['Start Date',      fn ($r) => $formatDate($r->start_date)],
        'end_date'      => ['End Date',        fn ($r) => $formatDate($r->end_date)],
        'fee'           => ['Transport Fee',   fn ($r) => $r->transport_fee !== null ? '₹' . number_format((float) $r->transport_fee, 2) : '—'],
        'fee_frequency' => ['Fee Frequency',   fn ($r) => ucfirst($r->fee_frequency ?? '—')],
        'payment'       => ['Payment Status',  fn ($r) => ucfirst(str_replace('_', ' ', $r->payment_status ?? 'Pending'))],
        'remarks'       => ['Remarks',         fn ($r) => $r->remarks ?: '—'],
    ];

    $recordsPerTable = 4;
@endphp

<div class="payroll-simple student-transport-profile-page">

    <style>
        /* =========================================
           NORMAL SCREEN DESIGN
        ========================================= */

        .student-transport-profile-page {
            background: #f5f4f0;
            color: #1e2a25;
            min-height: 100vh;
            padding: 10px;
        }

        .student-transport-profile-page .profile-hero {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            background: #fdfbf7;
            border: 1px solid #d4cfc2;
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .student-transport-profile-page .hero-main,
        .student-transport-profile-page .hero-side {
            padding: 28px;
        }

        .student-transport-profile-page .hero-side {
            background: linear-gradient(135deg, #f8f1e3, #e8dcc6);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .student-transport-profile-page .eyebrow {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .student-transport-profile-page .student-name {
            font-size: 28px;
            font-weight: 800;
            color: #1e2a25;
            margin: 10px 0;
        }

        .student-transport-profile-page .student-meta {
            color: #5c6b64;
            font-size: 14px;
            line-height: 1.9;
        }

        .student-transport-profile-page .profile-card,
        .student-transport-profile-page .stat-card,
        .student-transport-profile-page .filter-card {
            background: #fff;
            border: 1px solid #d4cfc2;
            border-radius: 15px;
            padding: 18px;
            box-shadow: 0 3px 12px rgba(30, 42, 37, .035);
        }

        .student-transport-profile-page .profile-card {
            margin-bottom: 18px;
        }

        .student-transport-profile-page .section-heading {
            font-size: 17px;
            font-weight: 800;
            color: #1e2a25;
            margin-bottom: 12px;
        }

        .student-transport-profile-page .info-label {
            font-size: 11px;
            font-weight: 700;
            color: #7b857b;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 5px;
        }

        .student-transport-profile-page .info-value {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e36;
            overflow-wrap: anywhere;
        }

        .student-transport-profile-page .stat-label {
            color: #5c6b64;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .student-transport-profile-page .stat-value {
            color: #2c3e36;
            font-size: 27px;
            font-weight: 800;
            margin-top: 9px;
        }

        .student-transport-profile-page .stat-green {
            color: #1a6b3c;
        }

        .student-transport-profile-page .btn-primary-custom {
            background: #2c3e36;
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .student-transport-profile-page .btn-primary-custom:hover {
            background: #3a4f45;
            color: #fff;
        }

        .student-transport-profile-page .btn-light-custom {
            background: #f3ead8;
            color: #2c3e36;
            border: 1px solid #d4cfc2;
            border-radius: 9px;
            padding: 10px 18px;
            font-weight: 700;
            text-decoration: none;
        }

        .student-transport-profile-page .table-wrap {
            overflow-x: auto;
        }

        .student-transport-profile-page .table {
            margin-bottom: 0;
            vertical-align: middle;
            min-width: 650px;
        }

        .student-transport-profile-page .table thead th {
            background: #f5f4f0;
            color: #5c6b64;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 14px;
            border-bottom: 1px solid #d4cfc2;
            white-space: nowrap;
        }

        .student-transport-profile-page .table tbody td,
        .student-transport-profile-page .table tbody th {
            padding: 14px;
            color: #39483e;
            font-size: 13px;
            border-color: #eeeae1;
        }

        /* VERTICAL TRANSPORT TABLE (SCREEN) */

        .student-transport-profile-page .vertical-screen-table {
            min-width: 600px;
        }

        .student-transport-profile-page .vertical-screen-table thead th {
            text-align: center;
        }

        .student-transport-profile-page .vertical-screen-table thead th:first-child {
            text-align: left;
            width: 190px;
        }

        .student-transport-profile-page .vertical-screen-table tbody th {
            background: #f8f1e3;
            color: #2c3e36;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            white-space: nowrap;
        }

        .student-transport-profile-page .vertical-screen-table tbody td {
            text-align: center;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .student-transport-profile-page .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            text-transform: capitalize;
        }

        .student-transport-profile-page .status-present {
            background: #e4f2e8;
            color: #1a6b3c;
        }

        .student-transport-profile-page .status-absent {
            background: #f9e7e5;
            color: #b3443b;
        }

        .student-transport-profile-page .empty-state {
            text-align: center;
            padding: 30px !important;
            color: #7b857b !important;
        }

        .student-transport-profile-page .progress {
            height: 9px;
            background: #eeeae1;
            border-radius: 20px;
            margin-top: 12px;
        }

        .student-transport-profile-page .progress-bar {
            background: #1a6b3c;
            border-radius: 20px;
        }

        .student-transport-profile-page .student-avatar {
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

        .student-transport-profile-page .print-profile {
            display: none;
        }

        @media(max-width: 768px) {
            .student-transport-profile-page .profile-hero {
                grid-template-columns: 1fr;
            }

            .student-transport-profile-page .student-name {
                font-size: 23px;
            }
        }

        /* =========================================
           A4 LANDSCAPE PRINT SETTINGS
           SAME AS STUDENT ATTENDANCE PROFILE
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

            .student-transport-profile-page .screen-profile {
                display: none !important;
            }

            .student-transport-profile-page .print-profile,
            .student-transport-profile-page .print-profile * {
                visibility: visible !important;
            }

            .student-transport-profile-page .print-profile {
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

            .student-transport-profile-page {
                background: #fff !important;
                color: #26352e !important;
                padding: 0 !important;
                min-height: auto !important;
            }

            /* SCHOOL HEADER */

            .student-transport-profile-page .print-school-header {
                text-align: center;
                border-bottom: 2px solid #2c3e36;
                padding-bottom: 12px;
                margin-bottom: 18px;
            }

            .student-transport-profile-page .print-school-logo {
                max-height: 65px;
                max-width: 90px;
                display: block;
                margin: 0 auto 5px;
                object-fit: contain;
            }

            .student-transport-profile-page .print-school-name {
                font-size: 21px;
                font-weight: bold;
                color: #263b35;
            }

            .student-transport-profile-page .print-school-details {
                color: #5c6b64;
                font-size: 9px;
                margin-top: 5px;
                line-height: 1.6;
            }

            /* REPORT TITLE */

            .student-transport-profile-page .print-report-title {
                text-align: center;
                font-size: 15px;
                font-weight: bold;
                margin: 15px 0;
                color: #263b35;
            }

            .student-transport-profile-page .print-student-heading {
                background: #f3ead8 !important;
                padding: 10px;
                border: 1px solid #d4cfc2;
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            /* DETAILS TABLE */

            .student-transport-profile-page .print-details-table,
            .student-transport-profile-page .print-attendance-table,
            .student-transport-profile-page .print-summary-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            .student-transport-profile-page .print-details-table {
                margin-bottom: 16px;
            }

            .student-transport-profile-page .print-details-table td {
                padding: 7px 8px;
                border: 1px solid #e2ded4;
                width: 25%;
                vertical-align: top;
                overflow-wrap: anywhere;
            }

            .student-transport-profile-page .print-field-label {
                display: block;
                color: #68766e;
                font-size: 8px;
                text-transform: uppercase;
                margin-bottom: 4px;
            }

            .student-transport-profile-page .print-field-value {
                font-weight: bold;
                color: #2c3e36;
                font-size: 9px;
            }

            /* SECTION HEADINGS */

            .student-transport-profile-page .print-section-title {
                font-size: 12px;
                font-weight: bold;
                color: #263b35;
                margin: 15px 0 8px;
                page-break-after: avoid;
            }

            /* TABLE (SAME STYLE AS ATTENDANCE TABLE) */

            .student-transport-profile-page .print-attendance-table {
                table-layout: fixed;
                margin-bottom: 12px;
            }

            .student-transport-profile-page .print-attendance-table th {
                background: #2c3e36 !important;
                color: #fff !important;
                padding: 9px 7px;
                text-align: left;
                font-size: 9px;
                border: 1px solid #2c3e36;
            }

            .student-transport-profile-page .print-attendance-table td {
                padding: 8px 7px;
                border: 1px solid #e5e1d8;
                font-size: 9px;
                color: #26352e;
                overflow-wrap: anywhere;
            }

            .student-transport-profile-page .print-attendance-table tbody tr:nth-child(even) {
                background: #f8f7f3 !important;
            }

            /* VERTICAL TABLE */

            .student-transport-profile-page .print-vertical-table {
                page-break-inside: avoid;
            }

            .student-transport-profile-page .print-vertical-table thead th {
                text-align: center;
            }

            .student-transport-profile-page .print-vertical-table thead th:first-child {
                width: 18%;
                text-align: left;
            }

            .student-transport-profile-page .print-vertical-table tbody th {
                background: #f3ead8 !important;
                color: #263b35 !important;
                padding: 8px 7px;
                font-size: 9px;
                font-weight: bold;
                text-align: left;
                border: 1px solid #e5e1d8;
            }

            .student-transport-profile-page .print-vertical-table tbody td {
                text-align: center;
            }

            .student-transport-profile-page .print-status-present {
                color: #1a6b3c !important;
                font-weight: bold;
            }

            .student-transport-profile-page .print-status-absent {
                color: #b3443b !important;
                font-weight: bold;
            }

            /* SUMMARY */

            .student-transport-profile-page .print-summary-table {
                margin-top: 8px;
            }

            .student-transport-profile-page .print-summary-table td {
                width: 25%;
                padding: 12px 8px;
                border: 1px solid #d4cfc2;
                background: #f8f7f3 !important;
                text-align: center;
            }

            .student-transport-profile-page .print-summary-label {
                color: #5c6b64;
                font-size: 8px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .student-transport-profile-page .print-summary-value {
                font-size: 18px;
                font-weight: bold;
                color: #2c3e36;
                margin-top: 5px;
            }

            .student-transport-profile-page .print-green {
                color: #1a6b3c !important;
            }

            .student-transport-profile-page .print-red {
                color: #b3443b !important;
            }

            /* FOOTER */

            .student-transport-profile-page .print-footer {
                margin-top: 18px;
                border-top: 1px solid #d4cfc2;
                padding-top: 8px;
                color: #7b857b;
                font-size: 8px;
                text-align: right;
            }

            .student-transport-profile-page .print-no-records {
                text-align: center;
                padding: 15px !important;
                color: #777 !important;
            }

            .student-transport-profile-page .print-attendance-table thead {
                display: table-header-group;
            }

            .student-transport-profile-page .print-attendance-table tr,
            .student-transport-profile-page .print-summary-table tr {
                page-break-inside: avoid;
            }
        }
    </style>


    {{-- =========================================
         NORMAL SCREEN PROFILE
    ========================================= --}}

    <div class="screen-profile">

        <div class="container-fluid py-4">

            {{-- PAGE ACTIONS: BACK LEFT, PRINT / PDF / EXCEL RIGHT --}}

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 no-print">

                <a href="{{ route('admin.reports.transport.students.class', [
                    'class' => $student->class,
                    'section' => $student->section,
                    'academic_year' => $student->academic_year,
                ]) }}"
                   class="btn btn-light-custom">

                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Reports

                </a>

                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.reports.transport.students.pdf', $student->id) }}"
                       class="btn btn-light-custom">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>

                    <a href="{{ route('admin.reports.transport.students.excel', $student->id) }}"
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
                        Student Transport Profile
                    </div>

                    <div class="student-avatar">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <h2 class="student-name">
                        {{ $studentName ?: 'Student' }}
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
                        Transport Records
                    </div>

                    <div class="stat-value stat-green">
                        {{ $totalRecords }}
                    </div>

                    <div class="progress">
                        <div class="progress-bar"
                             style="width: {{ $totalRecords > 0 ? round(($activeRecords / $totalRecords) * 100) : 0 }}%;">
                        </div>
                    </div>

                    <div class="student-meta mt-3">
                        Active: {{ $activeRecords }} of {{ $totalRecords }}<br>
                        Academic Year: {{ $academicYear ?: '—' }}
                    </div>

                </div>

            </div>


            {{-- STUDENT INFORMATION --}}

            <div class="profile-card">

                <h5 class="section-heading">
                    Student Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student Name</div>
                        <div class="info-value">{{ $studentName ?: '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student ID</div>
                        <div class="info-value">{{ $student->student_id ?? $student->id }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Roll Number</div>
                        <div class="info-value">{{ $student->roll_number ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Class</div>
                        <div class="info-value">{{ $student->class ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Division</div>
                        <div class="info-value">{{ $student->section ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value">{{ $academicYear ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Gender</div>
                        <div class="info-value">{{ $student->gender ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">{{ $formatDate($studentDob) }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student Phone</div>
                        <div class="info-value">{{ $student->phone ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $student->email ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Student Status</div>
                        <div class="info-value">{{ ucfirst($student->status ?? '—') }}</div>
                    </div>

                </div>

            </div>


            {{-- PARENT / GUARDIAN INFORMATION (SCREEN ONLY) --}}

            <div class="profile-card">

                <h5 class="section-heading">
                    Parent / Guardian Information
                </h5>

                <div class="row g-4">

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Father's Name</div>
                        <div class="info-value">{{ $student->father_name ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Father's Phone</div>
                        <div class="info-value">{{ $student->father_phone ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Mother's Name</div>
                        <div class="info-value">{{ $student->mother_name ?? '—' }}</div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="info-label">Mother's Phone</div>
                        <div class="info-value">{{ $student->mother_phone ?? '—' }}</div>
                    </div>

                    <div class="col-12">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $student->address ?? '—' }}</div>
                    </div>

                </div>

            </div>


            {{-- TRANSPORT DETAILS & HISTORY (VERTICAL TABLE) --}}

            <div class="profile-card">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

                    <div>
                        <h5 class="section-heading mb-1">
                            Transport Details &amp; History
                        </h5>

                        <div class="student-meta">
                            All transport records registered for this student.
                        </div>
                    </div>

                    <div class="eyebrow">
                        {{ $totalRecords }} Records
                    </div>

                </div>

                @if($transportRecords->isNotEmpty())

                    <div class="table-wrap">

                        <table class="table vertical-screen-table">

                            <thead>
                                <tr>
                                    <th>Details</th>
                                    @foreach($transportRecords as $record)
                                        <th>Record {{ $loop->iteration }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($verticalRows as $key => [$label, $resolver])
                                    <tr>
                                        <th>{{ $label }}</th>

                                        @foreach($transportRecords as $record)
                                            <td>
                                                @if($key === 'status')
                                                    @php $isActive = strtolower($record->transport_status ?? '') === 'active'; @endphp
                                                    <span class="status-badge {{ $isActive ? 'status-present' : 'status-absent' }}">
                                                        {{ $resolver($record) }}
                                                    </span>
                                                @elseif($key === 'fee')
                                                    <strong>{{ $resolver($record) }}</strong>
                                                @else
                                                    {{ $resolver($record) }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="empty-state">
                        No transport records are currently registered for this student.
                    </div>

                @endif

            </div>


            {{-- TRANSPORT SUMMARY --}}

            <div class="mb-3">

                <h5 class="section-heading">
                    Transport Summary
                </h5>

                <div class="student-meta mb-3">
                    Transport statistics based on all registered records.
                </div>

                <div class="row g-3">

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Total Records</div>
                            <div class="stat-value">{{ $totalRecords }}</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Active Records</div>
                            <div class="stat-value stat-green">{{ $activeRecords }}</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Inactive Records</div>
                            <div class="stat-value" style="color:#b3443b;">{{ $inactiveRecords }}</div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="stat-card">
                            <div class="stat-label">Total Transport Fees</div>
                            <div class="stat-value stat-green">₹{{ number_format($totalFees, 2) }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================
         DEDICATED PRINT PROFILE
         SAME DESIGN AS STUDENT ATTENDANCE PRINT
    ========================================= --}}

    <div class="print-profile">

        {{-- DYNAMIC SCHOOL HEADER --}}

        <div class="print-school-header">

            @if(!empty($school?->logo_url))
                <img src="{{ $school->logo_url }}"
                     class="print-school-logo"
                     alt="School Logo">
            @endif

            <div class="print-school-name">
                {{ $school->school_name ?? config('app.name', 'School Name') }}
            </div>

            <div class="print-school-details">

                {{ $school->address ?? '' }}

                @if(!empty($school?->city))
                    , {{ $school->city }}
                @endif

                @if(!empty($school?->district))
                    , {{ $school->district }}
                @endif

                @if(!empty($school?->state))
                    , {{ $school->state }}
                @endif

                @if(!empty($school?->phone))
                    | Phone: {{ $school->phone }}
                @endif

                @if(!empty($school?->email))
                    | Email: {{ $school->email }}
                @endif

            </div>

        </div>


        {{-- REPORT TITLE --}}

        <div class="print-report-title">
            STUDENT TRANSPORT PROFILE
        </div>


        {{-- STUDENT HEADING --}}

        <div class="print-student-heading">

            {{ $studentName ?: 'Student' }}

            &nbsp; | &nbsp;

            Student ID: {{ $student->student_id ?? $student->id }}

        </div>


        {{-- STUDENT DETAILS --}}

        <table class="print-details-table">

            <tr>
                <td>
                    <span class="print-field-label">Student Name</span>
                    <span class="print-field-value">{{ $studentName ?: 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Student ID</span>
                    <span class="print-field-value">{{ $student->student_id ?? $student->id }}</span>
                </td>
                <td>
                    <span class="print-field-label">Roll Number</span>
                    <span class="print-field-value">{{ $student->roll_number ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Class</span>
                    <span class="print-field-value">{{ $student->class ?? 'Not available' }}</span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="print-field-label">Division</span>
                    <span class="print-field-value">{{ $student->section ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Academic Year</span>
                    <span class="print-field-value">{{ $academicYear ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Gender</span>
                    <span class="print-field-value">{{ $student->gender ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Date of Birth</span>
                    <span class="print-field-value">{{ $formatDate($studentDob) }}</span>
                </td>
            </tr>

            <tr>
                <td>
                    <span class="print-field-label">Phone</span>
                    <span class="print-field-value">{{ $student->phone ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Email</span>
                    <span class="print-field-value">{{ $student->email ?? 'Not available' }}</span>
                </td>
                <td>
                    <span class="print-field-label">Student Status</span>
                    <span class="print-field-value">{{ ucfirst($student->status ?? 'Not available') }}</span>
                </td>
                <td>
                    <span class="print-field-label">Transport Records</span>
                    <span class="print-field-value">{{ $totalRecords }}</span>
                </td>
            </tr>

        </table>


        {{-- TRANSPORT DETAILS & HISTORY (VERTICAL TABLE) --}}

        <div class="print-section-title">
            Transport Details &amp; History
        </div>

        @if($transportRecords->isNotEmpty())

            @foreach($transportRecords->chunk($recordsPerTable) as $chunkIndex => $chunk)

                <table class="print-attendance-table print-vertical-table">

                    <thead>
                        <tr>
                            <th>Details</th>
                            @foreach($chunk as $record)
                                <th>Record {{ ($chunkIndex * $recordsPerTable) + $loop->iteration }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($verticalRows as $key => [$label, $resolver])
                            <tr>
                                <th>{{ $label }}</th>

                                @foreach($chunk as $record)
                                    @php
                                        $isActive = strtolower($record->transport_status ?? '') === 'active';
                                        $isInactive = strtolower($record->transport_status ?? '') === 'inactive';
                                    @endphp
                                    <td class="{{ $key === 'status' ? ($isActive ? 'print-status-present' : ($isInactive ? 'print-status-absent' : '')) : '' }}">
                                        {{ $resolver($record) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            @endforeach

        @else

            <table class="print-attendance-table">
                <tr>
                    <td class="print-no-records">
                        No transport records found for this student.
                    </td>
                </tr>
            </table>

        @endif


        {{-- TRANSPORT SUMMARY --}}

        <div class="print-section-title">
            Transport Summary
        </div>

        <table class="print-summary-table">

            <tr>
                <td>
                    <div class="print-summary-label">Total Records</div>
                    <div class="print-summary-value">{{ $totalRecords }}</div>
                </td>

                <td>
                    <div class="print-summary-label">Active Records</div>
                    <div class="print-summary-value print-green">{{ $activeRecords }}</div>
                </td>

                <td>
                    <div class="print-summary-label">Inactive Records</div>
                    <div class="print-summary-value print-red">{{ $inactiveRecords }}</div>
                </td>

                <td>
                    <div class="print-summary-label">Total Transport Fees</div>
                    <div class="print-summary-value print-green">₹{{ number_format($totalFees, 2) }}</div>
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