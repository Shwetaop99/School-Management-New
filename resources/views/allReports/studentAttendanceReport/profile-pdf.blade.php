
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Transport Profile</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #26352e;
            font-size: 10px;
            line-height: 1.45;
            margin: 0;
        }

        /* SCHOOL HEADER - MATCHES ATTENDANCE PDF */

        .school-header {
            text-align: center;
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .school-logo {
            max-height: 65px;
            max-width: 90px;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 21px;
            font-weight: bold;
            color: #263b35;
        }

        .school-details {
            color: #5c6b64;
            font-size: 9px;
            margin-top: 5px;
        }

        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 15px 0;
            color: #263b35;
        }

        .section-heading {
            background: #344f43;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            padding: 8px 10px;
            margin: 13px 0 0;
            letter-spacing: .4px;
        }

        /* STUDENT INFORMATION */

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .student-table td {
            border: 1px solid #e5e1d5;
            padding: 8px;
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
            font-size: 9px;
            font-weight: bold;
        }

        /* TRANSPORT HISTORY */

        .history-intro {
            font-size: 8px;
            color: #68756c;
            padding: 8px 0 10px;
        }

        .assignment-card {
            width: 100%;
            border: 1px solid #d8dfd7;
            border-collapse: collapse;
            margin-bottom: 13px;
            page-break-inside: avoid;
        }

        .assignment-header {
            background: #e9eee8;
            border-bottom: 1px solid #cbd5cc;
        }

        .assignment-header td {
            padding: 9px 10px;
            vertical-align: middle;
        }

        .assignment-number {
            color: #263b35;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .4px;
        }

        .assignment-status {
            text-align: right;
        }

        .assignment-body {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .assignment-body td {
            width: 50%;
            padding: 0;
            vertical-align: top;
            border-bottom: 1px solid #eeeae0;
        }

        .assignment-body td:first-child {
            border-right: 1px solid #eeeae0;
        }

        .detail-cell {
            padding: 8px 10px;
        }

        .detail-label {
            display: block;
            font-size: 7px;
            color: #7b857b;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: .35px;
            margin-bottom: 3px;
        }

        .detail-value {
            display: block;
            font-size: 9px;
            color: #263b35;
            font-weight: bold;
            word-wrap: break-word;
        }

        .status {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 7px;
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

        .assignment-footer {
            background: #faf9f5;
        }

        .assignment-footer td {
            padding: 8px 10px;
            border-top: 1px solid #e5e1d5;
        }

        .remarks-label {
            font-size: 7px;
            font-weight: bold;
            color: #7b857b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .remarks-value {
            font-size: 9px;
            color: #39483e;
        }

        .empty-state {
            border: 1px solid #e5e1d5;
            background: #faf9f5;
            text-align: center;
            padding: 22px 10px;
            color: #7b857b;
            font-size: 9px;
        }

        /* FOOTER */

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            border-top: 1px solid #d4cfc2;
            padding-top: 8px;
            color: #7b857b;
            font-size: 8px;
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

    $schoolName = $school?->school_name ?? 'School Name';
    $schoolAddress = $school?->address ?? '';
    $schoolCity = $school?->city ?? '';
    $schoolDistrict = $school?->district ?? '';
    $schoolState = $school?->state ?? '';
    $schoolPhone = $school?->phone ?? '';
    $schoolEmail = $school?->email ?? '';

    $logoUrl = $school?->logo_url ?? null;

    if (empty($logoUrl) && !empty($school?->logo)) {
        $logoUrl = asset('storage/' . ltrim($school->logo, '/'));
    }

    $studentName = $student->full_name
        ?? trim(collect([
            $student->first_name ?? '',
            $student->middle_name ?? '',
            $student->last_name ?? '',
        ])->filter()->implode(' '));

    $cleanValue = function ($value) {
        return ($value !== null && trim((string) $value) !== '')
            ? $value
            : '—';
    };

    $formatWords = function ($value) {
        if ($value === null || trim((string) $value) === '') {
            return '—';
        }

        return ucwords(str_replace('_', ' ', (string) $value));
    };

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

    $formatAmount = function ($amount) {
        return $amount !== null && $amount !== ''
            ? number_format((float) $amount, 2)
            : '—';
    };

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

{{-- SCHOOL HEADER: LOGO, NAME AND CONTACT DETAILS --}}

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

    <div class="school-details">

        {{ $schoolAddress }}

        @if(!empty($schoolCity))
            , {{ $schoolCity }}
        @endif

        @if(!empty($schoolDistrict))
            , {{ $schoolDistrict }}
        @endif

        @if(!empty($schoolState))
            , {{ $schoolState }}
        @endif

        @if(!empty($schoolPhone))
            | Phone: {{ $schoolPhone }}
        @endif

        @if(!empty($schoolEmail))
            | Email: {{ $schoolEmail }}
        @endif

    </div>

</div>

{{-- REPORT TITLE --}}

<div class="report-title">
    STUDENT TRANSPORT PROFILE
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
    Individual transport assignment records, including route, vehicle,
    pickup and drop information, fee details, payment status and assignment period.
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

            {{-- VERTICAL TWO-COLUMN DETAILS --}}

            <tr>
                <td style="padding:0;">

                    <table class="assignment-body">

                        <tr>
                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Route</span>
                                    <span class="detail-value">
                                        {{ $cleanValue($record->route ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Vehicle</span>
                                    <span class="detail-value">
                                        {{ $cleanValue($record->vehicle ?? null) }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Transport Type</span>
                                    <span class="detail-value">
                                        {{ $formatWords($record->transport_type ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Transport Fee</span>
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
                                    <span class="detail-label">Pickup Point</span>
                                    <span class="detail-value">
                                        {{ $cleanValue($record->pickup_point ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Drop Point</span>
                                    <span class="detail-value">
                                        {{ $cleanValue($record->drop_point ?? null) }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Pickup Time</span>
                                    <span class="detail-value">
                                        {{ $formatTime($record->pickup_time ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Drop Time</span>
                                    <span class="detail-value">
                                        {{ $formatTime($record->drop_time ?? null) }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Fee Frequency</span>
                                    <span class="detail-value">
                                        {{ $formatWords($record->fee_frequency ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Payment Status</span>
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
                                    <span class="detail-label">Assignment Start Date</span>
                                    <span class="detail-value">
                                        {{ $formatDate($record->start_date ?? null) }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="detail-cell">
                                    <span class="detail-label">Assignment End Date</span>
                                    <span class="detail-value">
                                        {{ $formatDate($record->end_date ?? null) }}
                                    </span>
                                </div>
                            </td>
                        </tr>

                    </table>

                </td>
            </tr>

            {{-- REMARKS --}}

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
                Generated on {{ now()->format('d M Y, h:i A') }}
            </td>
        </tr>
    </table>
</div>

</body>
</html>
