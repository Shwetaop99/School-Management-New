<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Transport Profile</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 22px 28px 32px 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #263b35;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* =====================================
           SCHOOL HEADER
        ===================================== */

        .school-header {
            width: 100%;
            text-align: center;
            border-bottom: 2px solid #344f43;
            padding: 0 0 9px 0;
            margin: 0 0 10px 0;
        }

        .school-logo {
            display: block;
            max-height: 60px;
            max-width: 85px;
            width: auto;
            height: auto;
            margin: 0 auto 5px auto;
        }

        .school-name {
            display: block;
            width: 100%;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #263b35;
            margin: 0;
            text-transform: uppercase;
        }

        .school-contact {
            display: block;
            width: 100%;
            text-align: center;
            color: #68756c;
            font-size: 8px;
            line-height: 1.45;
            margin-top: 3px;
        }

        /* =====================================
           REPORT HEADING
        ===================================== */

        .report-heading {
            text-align: center;
            margin: 9px 0 9px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            color: #263b35;
            letter-spacing: .8px;
        }

        .report-subtitle {
            font-size: 7.5px;
            color: #7b857b;
            margin-top: 2px;
        }

        /* =====================================
           SECTION HEADINGS
        ===================================== */

        .section-heading {
            background: #344f43;
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
            padding: 6px 9px;
            margin: 7px 0 0;
            letter-spacing: .4px;
        }

        /* =====================================
           STUDENT INFORMATION TABLE
        ===================================== */

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 0 5px 0;
            page-break-inside: avoid;
        }

        .student-table td {
            border: 1px solid #e5e1d5;
            padding: 5px 8px;
            vertical-align: middle;
        }

        .student-label {
            width: 15%;
            background: #f5f3e9;
            color: #526258;
            font-size: 8px;
            font-weight: bold;
        }

        .student-value {
            width: 35%;
            color: #263b35;
            font-size: 8.5px;
            font-weight: bold;
        }

        /* =====================================
           TRANSPORT HISTORY
        ===================================== */

        .history-intro {
            font-size: 7.5px;
            color: #68756c;
            padding: 5px 0 6px;
            margin: 0;
        }

        /* =====================================
           ASSIGNMENT CARD
        ===================================== */

        .assignment-card {
            width: 100%;
            border: 1px solid #d8dfd7;
            border-collapse: collapse;
            margin: 0 0 8px 0;
            page-break-inside: avoid;
        }

        .assignment-header {
            background: #e9eee8;
            border-bottom: 1px solid #cbd5cc;
        }

        .assignment-header td {
            padding: 6px 9px;
            vertical-align: middle;
        }

        .assignment-number {
            color: #263b35;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: .35px;
        }

        .assignment-status {
            text-align: right;
        }

        /* =====================================
           TWO-COLUMN DETAILS
        ===================================== */

        .assignment-body {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .assignment-body td {
            width: 50%;
            padding: 0;
            vertical-align: middle;
            border-bottom: 1px solid #eeeae0;
        }

        .assignment-body tr:last-child td {
            border-bottom: none;
        }

        .assignment-body td:first-child {
            border-right: 1px solid #eeeae0;
        }

        .detail-cell {
            padding: 5px 9px;
        }

        .detail-label {
            display: block;
            font-size: 6.5px;
            color: #7b857b;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: .35px;
            margin-bottom: 2px;
        }

        .detail-value {
            display: block;
            font-size: 8px;
            color: #263b35;
            font-weight: bold;
            word-wrap: break-word;
        }

        /* =====================================
           REMARKS
        ===================================== */

        .assignment-footer {
            background: #faf9f5;
        }

        .assignment-footer td {
            padding: 6px 9px;
            border-top: 1px solid #e5e1d5;
        }

        .remarks-label {
            font-size: 6.5px;
            font-weight: bold;
            color: #7b857b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .remarks-value {
            font-size: 8px;
            color: #39483e;
        }

        /* =====================================
           STATUS BADGES
        ===================================== */

        .status {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 6.5px;
            font-weight: bold;
        }

        .status-active {
            background: #e5f1e7;
            color: #347447;
        }

        .status-inactive {
            background: #f7e8e5;
            color: #a34d43;
        }

        .status-neutral {
            background: #f1eee5;
            color: #74694e;
        }

        /* =====================================
           EMPTY STATE
        ===================================== */

        .empty-state {
            border: 1px solid #e5e1d5;
            background: #faf9f5;
            text-align: center;
            padding: 18px 10px;
            color: #7b857b;
            font-size: 8px;
            margin: 0 0 8px 0;
        }

        /* =====================================
           FOOTER
        ===================================== */

        .footer {
            position: fixed;
            bottom: -18px;
            left: 0;
            right: 0;
            border-top: 1px solid #e5e1d5;
            padding-top: 5px;
            font-size: 7px;
            color: #7b857b;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-right {
            text-align: right;
        }

    </style>
</head>

<body>

@php

    $records = $student->transportRecords ?? collect();

    /* SCHOOL INFORMATION */

    $schoolName = $school?->school_name ?? 'School Name';
    $schoolAddress = $school?->address ?? '';
    $schoolPhone = $school?->phone ?? '';
    $schoolEmail = $school?->email ?? '';

    /* SCHOOL LOGO */

    $logoUrl = null;

    if (!empty($school?->logo_url)) {
        $logoUrl = $school->logo_url;
    } elseif (!empty($school?->logo)) {
        $logoUrl = asset('storage/' . ltrim($school->logo, '/'));
    }

    /* STUDENT NAME */

    $studentName = $student->full_name
        ?? trim(collect([
            $student->first_name ?? '',
            $student->middle_name ?? '',
            $student->last_name ?? '',
        ])->filter()->implode(' '));

    /* DATE FORMAT */

    $formatDate = function ($date) {
        if (empty($date)) {
            return '—';
        }

        try {
            return \Carbon\Carbon::parse($date)->format('d M Y');
        } catch (\Throwable $e) {
            return '—';
        }
    };

    /* TIME FORMAT */

    $formatTime = function ($time) {
        if (empty($time)) {
            return '—';
        }

        try {
            return \Carbon\Carbon::parse($time)->format('h:i A');
        } catch (\Throwable $e) {
            return $time;
        }
    };

    /* AMOUNT FORMAT */

    $formatAmount = function ($amount) {
        return $amount !== null && $amount !== ''
            ? number_format((float) $amount, 2)
            : '—';
    };

    /* EMPTY VALUE HANDLING */

    $cleanValue = function ($value) {
        return ($value !== null && trim((string) $value) !== '')
            ? $value
            : '—';
    };

    /* WORD FORMATTING */

    $formatWords = function ($value) {
        if ($value === null || trim((string) $value) === '') {
            return '—';
        }

        return ucwords(str_replace('_', ' ', (string) $value));
    };

    /* TRANSPORT STATUS */

    $getStatusClass = function ($status) {
        $status = strtolower(trim((string) $status));

        if (in_array($status, ['active', 'running'])) {
            return 'status-active';
        }

        if (in_array($status, ['inactive', 'cancelled', 'canceled', 'stopped'])) {
            return 'status-inactive';
        }

        return 'status-neutral';
    };

    /* PAYMENT STATUS */

    $getPaymentClass = function ($status) {
        $status = strtolower(trim((string) $status));

        if ($status === 'paid') {
            return 'status-active';
        }

        if (in_array($status, ['pending', 'unpaid', 'due'])) {
            return 'status-inactive';
        }

        return 'status-neutral';
    };

@endphp


{{-- =========================================
     SCHOOL HEADER
     LOGO TOP CENTRE
     SCHOOL NAME BELOW LOGO
========================================= --}}

<div class="school-header">

    @if(!empty($logoUrl))

        <img
            src="{{ $logoUrl }}"
            class="school-logo"
            alt="School Logo"
        >

    @endif

    <div class="school-name">
        {{ $schoolName }}
    </div>

    <div class="school-contact">

        @if(!empty($schoolAddress))
            {{ $schoolAddress }}
        @endif

        @if(!empty($schoolPhone))
            <br>Phone: {{ $schoolPhone }}
        @endif

        @if(!empty($schoolEmail))
            | Email: {{ $schoolEmail }}
        @endif

    </div>

</div>


{{-- REPORT TITLE --}}

<div class="report-heading">

    <div class="report-title">
        STUDENT TRANSPORT PROFILE
    </div>

    <div class="report-subtitle">
        Individual Student Transport Assignment & History Report
    </div>

</div>


{{-- STUDENT INFORMATION --}}

<div class="section-heading">
    STUDENT INFORMATION
</div>

<table class="student-table">

    <tr>

        <td class="student-label">Student Name</td>

        <td class="student-value">
            {{ $cleanValue($studentName) }}
        </td>

        <td class="student-label">Student ID</td>

        <td class="student-value">
            {{ $cleanValue($student->student_id ?? $student->id) }}
        </td>

    </tr>

    <tr>

        <td class="student-label">Class</td>

        <td class="student-value">
            {{ $cleanValue($student->class ?? null) }}
        </td>

        <td class="student-label">Section</td>

        <td class="student-value">
            {{ $cleanValue($student->section ?? null) }}
        </td>

    </tr>

    <tr>

        <td class="student-label">Academic Year</td>

        <td class="student-value">
            {{ $cleanValue($student->academic_year ?? null) }}
        </td>

        <td class="student-label">Date of Birth</td>

        <td class="student-value">
            {{ $formatDate($student->date_of_birth ?? null) }}
        </td>

    </tr>

</table>


{{-- TRANSPORT ASSIGNMENT DETAILS & HISTORY --}}

<div class="section-heading">
    TRANSPORT ASSIGNMENT DETAILS & HISTORY
</div>

<div class="history-intro">
    Individual transport assignment records with route, vehicle,
    pickup and drop details, fee information, payment status,
    and assignment period.
</div>


@if($records->isNotEmpty())

    @foreach($records as $index => $record)

        <table class="assignment-card">

            {{-- ASSIGNMENT HEADER --}}

            <tr class="assignment-header">

                <td>

                    <table style="width:100%; border-collapse:collapse;">

                        <tr>

                            <td class="assignment-number">
                                TRANSPORT ASSIGNMENT —
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="assignment-status">

                                <span class="status {{ $getStatusClass($record->transport_status ?? '') }}">
                                    {{ $formatWords($record->transport_status ?? null) }}
                                </span>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>


            {{-- TRANSPORT DETAILS --}}

            <tr>

                <td style="padding:0;">

                    <table class="assignment-body">

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Route
                                    </span>

                                    <span class="detail-value">
                                        {{ $cleanValue($record->route ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Vehicle
                                    </span>

                                    <span class="detail-value">
                                        {{ $cleanValue($record->vehicle ?? null) }}
                                    </span>

                                </div>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Transport Type
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatWords($record->transport_type ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Transport Fee
                                    </span>

                                    <span class="detail-value">

                                        @if($record->transport_fee !== null && $record->transport_fee !== '')
                                            ₹ {{ $formatAmount($record->transport_fee) }}
                                        @else
                                            —
                                        @endif

                                    </span>

                                </div>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Pickup Point
                                    </span>

                                    <span class="detail-value">
                                        {{ $cleanValue($record->pickup_point ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Drop Point
                                    </span>

                                    <span class="detail-value">
                                        {{ $cleanValue($record->drop_point ?? null) }}
                                    </span>

                                </div>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Pickup Time
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatTime($record->pickup_time ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Drop Time
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatTime($record->drop_time ?? null) }}
                                    </span>

                                </div>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Fee Frequency
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatWords($record->fee_frequency ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Payment Status
                                    </span>

                                    <span class="detail-value">

                                        <span class="status {{ $getPaymentClass($record->payment_status ?? '') }}">
                                            {{ $formatWords($record->payment_status ?? null) }}
                                        </span>

                                    </span>

                                </div>
                            </td>

                        </tr>

                        <tr>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Assignment Start Date
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatDate($record->start_date ?? null) }}
                                    </span>

                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">

                                    <span class="detail-label">
                                        Assignment End Date
                                    </span>

                                    <span class="detail-value">
                                        {{ $formatDate($record->end_date ?? null) }}
                                    </span>

                                </div>
                            </td>

                        </tr>

                    </table>

                </td>

            </tr>


            {{-- ASSIGNMENT REMARKS --}}

            @if(!empty(trim((string) ($record->remarks ?? ''))))

                <tr class="assignment-footer">

                    <td>

                        <div class="remarks-label">
                            Additional Remarks
                        </div>

                        <div class="remarks-value">
                            {{ $record->remarks }}
                        </div>

                    </td>

                </tr>

            @endif

        </table>

    @endforeach

@else

    <div class="empty-state">
        No transport assignment records are available for this student.
    </div>

@endif


{{-- FOOTER --}}

<div class="footer">

    <table class="footer-table">

        <tr>

            <td>
                {{ $schoolName }} | Student Transport Report
            </td>

            <td class="footer-right">
                Generated on: {{ now()->format('d M Y, h:i A') }}
            </td>

        </tr>

    </table>

</div>

</body>
</html>
