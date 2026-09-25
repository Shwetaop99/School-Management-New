<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Transport Record #{{ $transportRecord->id }}</title>

    <style>

        @page {
            size: A4;
            margin: 12mm 14mm 15mm 14mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            line-height: 1.45;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* =====================================================
           DOCUMENT WRAPPER
        ===================================================== */

        .document {
            width: 100%;
        }

        /* =====================================================
           OFFICIAL HEADER
        ===================================================== */

        .header {
            width: 100%;
            border-bottom: 2px solid #1769d1;
            padding-bottom: 11px;
            margin-bottom: 16px;
        }

        .header-left {
            width: 68%;
            vertical-align: bottom;
        }

        .header-right {
            width: 32%;
            vertical-align: bottom;
            text-align: right;
        }

        .organization {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 4px;
        }

        .title {
            color: #172033;
            font-size: 19px;
            font-weight: bold;
            line-height: 1.15;
        }

        .subtitle {
            color: #64748b;
            font-size: 7px;
            margin-top: 4px;
        }

        .record-label {
            color: #94a3b8;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .record-number {
            color: #1769d1;
            font-size: 13px;
            font-weight: bold;
            margin-top: 2px;
        }

        .record-date {
            color: #64748b;
            font-size: 6.5px;
            margin-top: 3px;
        }

        /* =====================================================
           DOCUMENT REFERENCE
        ===================================================== */

        .reference {
            width: 100%;
            margin-bottom: 17px;
        }

        .reference td {
            background: #f8fafc;
            border-top: 1px solid #dce3eb;
            border-bottom: 1px solid #dce3eb;
            padding: 7px 9px;
            vertical-align: middle;
        }

        .reference-left {
            width: 70%;
            text-align: left;
        }

        .reference-right {
            width: 30%;
            text-align: right;
        }

        .reference-label {
            color: #94a3b8;
            font-size: 6px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .reference-value {
            color: #334155;
            font-size: 7px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* =====================================================
           STUDENT IDENTITY
        ===================================================== */

        .student-identity {
            width: 100%;
            border: 1px solid #cfd9e5;
            margin-bottom: 18px;
        }

        .identity-label {
            width: 18%;
            background: #f3f7fc;
            border-right: 1px solid #d8e1eb;
            padding: 10px;
            vertical-align: middle;
        }

        .identity-label-text {
            color: #1769d1;
            font-size: 6.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .identity-content {
            width: 62%;
            padding: 9px 11px;
            vertical-align: middle;
        }

        .identity-name {
            color: #172033;
            font-size: 12px;
            font-weight: bold;
        }

        .identity-details {
            color: #64748b;
            font-size: 7px;
            margin-top: 4px;
        }

        .identity-status {
            width: 20%;
            border-left: 1px solid #d8e1eb;
            padding: 9px;
            text-align: right;
            vertical-align: middle;
        }

        .status-label {
            color: #94a3b8;
            font-size: 5.8px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: .45px;
            margin-bottom: 3px;
        }

        .status-active {
            display: inline-block;
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 4px 8px;
            font-size: 6.3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-inactive {
            display: inline-block;
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecdd3;
            padding: 4px 8px;
            font-size: 6.3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* =====================================================
           SECTION HEADER
        ===================================================== */

        .section {
            width: 100%;
            margin-bottom: 17px;
            page-break-inside: avoid;
        }

        .section-header {
            width: 100%;
            border-bottom: 1px solid #cfd8e3;
            padding-bottom: 6px;
        }

        .section-header-left {
            width: 70%;
            vertical-align: middle;
        }

        .section-header-right {
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }

        .section-marker {
            display: inline-block;
            width: 4px;
            height: 13px;
            vertical-align: middle;
            margin-right: 6px;
        }

        .blue {
            background: #1769d1;
        }

        .orange {
            background: #ea7b18;
        }

        .cyan {
            background: #0891b2;
        }

        .green {
            background: #159447;
        }

        .section-title {
            color: #172033;
            font-size: 9.5px;
            font-weight: bold;
            vertical-align: middle;
        }

        .section-caption {
            color: #94a3b8;
            font-size: 6.3px;
        }

        /* =====================================================
           INFORMATION TABLE
        ===================================================== */

        .info-table {
            width: 100%;
            border: 1px solid #d4dde7;
            border-top: none;
        }

        .info-table td {
            border-right: 1px solid #e1e7ee;
            border-bottom: 1px solid #e1e7ee;
            padding: 8px 10px;
            vertical-align: top;
        }

        .info-table td:last-child {
            border-right: none;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .label {
            color: #64748b;
            font-size: 6.2px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .45px;
            margin-bottom: 3px;
        }

        .value {
            color: #172033;
            font-size: 8.4px;
            font-weight: bold;
            word-wrap: break-word;
        }

        .value-normal {
            font-weight: normal;
            line-height: 1.5;
        }

        /* =====================================================
           TRANSPORT HIGHLIGHTS
        ===================================================== */

        .route-value {
            color: #c2610d;
        }

        .vehicle-value {
            color: #1769d1;
            white-space: nowrap;
        }

        .pickup-value {
            color: #087f9c;
        }

        .fee-value {
            color: #15803d;
            font-size: 9px;
            font-weight: bold;
            white-space: nowrap;
        }

        /* =====================================================
           BADGES
        ===================================================== */

        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 6.3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-active {
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-inactive {
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecdd3;
        }

        .badge-paid {
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-partial {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .badge-pending {
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecdd3;
        }

        /* =====================================================
           TRANSPORT ASSIGNMENT LINE
        ===================================================== */

        .assignment {
            width: 100%;
            border: 1px solid #cfd9e5;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .assignment-title {
            background: #f3f7fc;
            color: #1769d1;
            padding: 7px 9px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .55px;
            border-bottom: 1px solid #d8e1eb;
        }

        .assignment td {
            width: 25%;
            padding: 8px 9px;
            vertical-align: top;
            border-right: 1px solid #e1e7ee;
        }

        .assignment td:last-child {
            border-right: none;
        }

        .assignment-label {
            color: #94a3b8;
            font-size: 5.9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 3px;
        }

        .assignment-value {
            color: #172033;
            font-size: 7.8px;
            font-weight: bold;
        }

        /* =====================================================
           DOCUMENT NOTE
        ===================================================== */

        .document-note {
            width: 100%;
            border-top: 1px solid #d4dde7;
            border-bottom: 1px solid #d4dde7;
            padding: 8px 0;
            margin-top: 2px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .document-note-title {
            color: #1769d1;
            font-size: 6.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 3px;
        }

        .document-note-text {
            color: #64748b;
            font-size: 6.8px;
            line-height: 1.5;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            width: 100%;
            border-top: 1px solid #d4dde7;
            padding-top: 7px;
        }

        .footer-left {
            width: 65%;
            color: #94a3b8;
            font-size: 6px;
            text-align: left;
        }

        .footer-right {
            width: 35%;
            color: #94a3b8;
            font-size: 6px;
            text-align: right;
        }

        .footer-brand {
            color: #1769d1;
            font-weight: bold;
        }

        /* =====================================================
           PRINT CONTROL
        ===================================================== */

        .section,
        .student-identity,
        .assignment,
        .document-note {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>

<div class="document">

    {{-- =====================================================
         OFFICIAL DOCUMENT HEADER
    ====================================================== --}}

    <table class="header">

        <tr>

            <td class="header-left">

                <div class="organization">
                    School Management System
                </div>

                <div class="title">
                    Transport Record
                </div>

                <div class="subtitle">
                    Student Transportation Record &amp; Assignment Details
                </div>

            </td>

            <td class="header-right">

                <div class="record-label">
                    Record Number
                </div>

                <div class="record-number">
                    #{{ $transportRecord->id }}
                </div>

                <div class="record-date">
                    Generated on {{ now()->format('d M Y') }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         DOCUMENT REFERENCE
    ====================================================== --}}

    <table class="reference">

        <tr>

            <td class="reference-left">

                <div class="reference-label">
                    Document Type
                </div>

                <div class="reference-value">
                    Student Transport Assignment Record
                </div>

            </td>

            <td class="reference-right">

                <div class="reference-label">
                    Record ID
                </div>

                <div class="reference-value">
                    TR-{{ str_pad($transportRecord->id, 5, '0', STR_PAD_LEFT) }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         STUDENT IDENTITY
    ====================================================== --}}

    <table class="student-identity">

        <tr>

            <td class="identity-label">

                <div class="identity-label-text">
                    Student Details
                </div>

            </td>

            <td class="identity-content">

                <div class="identity-name">
                    {{ $transportRecord->student?->full_name ?? 'Student Not Available' }}
                </div>

                <div class="identity-details">

                    Student ID:
                    {{ $transportRecord->student?->student_id ?? '—' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    Roll No:
                    {{ $transportRecord->student?->roll_number ?? '—' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    Class:
                    {{ $transportRecord->student?->class ?? '—' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    Division:
                    {{ $transportRecord->student?->section ?? '—' }}

                </div>

            </td>

            <td class="identity-status">

                <div class="status-label">
                    Transport Status
                </div>

                @if($transportRecord->transport_status === 'active')

                    <span class="status-active">
                        Active
                    </span>

                @else

                    <span class="status-inactive">
                        Inactive
                    </span>

                @endif

            </td>

        </tr>

    </table>


    {{-- =====================================================
         TRANSPORT ASSIGNMENT
    ====================================================== --}}

    <table class="assignment">

        <tr>

            <td colspan="4" class="assignment-title">
                Current Transport Assignment
            </td>

        </tr>

        <tr>

            <td>

                <div class="assignment-label">
                    Route
                </div>

                <div class="assignment-value">
                    {{ $transportRecord->route ?: 'Not Assigned' }}
                </div>

            </td>

            <td>

                <div class="assignment-label">
                    Vehicle
                </div>

                <div class="assignment-value vehicle-value">
                    {{ $transportRecord->vehicle ?: 'Not Assigned' }}
                </div>

            </td>

            <td>

                <div class="assignment-label">
                    Pickup Point
                </div>

                <div class="assignment-value">
                    {{ $transportRecord->pickup_point ?: 'Not Assigned' }}
                </div>

            </td>

            <td>

                <div class="assignment-label">
                    Drop Point
                </div>

                <div class="assignment-value">
                    {{ $transportRecord->drop_point ?: 'Not Assigned' }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         STUDENT INFORMATION
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td class="section-header-left">

                    <span class="section-marker blue"></span>

                    <span class="section-title">
                        Student Information
                    </span>

                </td>

                <td class="section-header-right">

                    <span class="section-caption">
                        Student and parent details
                    </span>

                </td>

            </tr>

        </table>


        <table class="info-table">

            <tr>

                <td>

                    <div class="label">
                        Student Name
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->full_name ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Student ID
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->student_id ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Roll Number
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->roll_number ?? '—' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        Class
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->class ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Division
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->section ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Parent / Guardian
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->father_name ?? '—' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        Parent Phone
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->father_phone ?? '—' }}
                    </div>

                </td>

                <td colspan="2">

                    <div class="label">
                        Address
                    </div>

                    <div class="value value-normal">
                        {{ $transportRecord->student?->address ?? '—' }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         TRANSPORT INFORMATION
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td class="section-header-left">

                    <span class="section-marker orange"></span>

                    <span class="section-title">
                        Transport Information
                    </span>

                </td>

                <td class="section-header-right">

                    <span class="section-caption">
                        Route, vehicle and assignment details
                    </span>

                </td>

            </tr>

        </table>


        <table class="info-table">

            <tr>

                <td>

                    <div class="label">
                        Route
                    </div>

                    <div class="value route-value">
                        {{ $transportRecord->route ?: '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Vehicle Number
                    </div>

                    <div class="value vehicle-value">
                        {{ $transportRecord->vehicle ?: '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Pickup Point
                    </div>

                    <div class="value pickup-value">
                        {{ $transportRecord->pickup_point ?: '—' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        Drop Point
                    </div>

                    <div class="value">
                        {{ $transportRecord->drop_point ?: '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Transport Status
                    </div>

                    <div class="value">

                        @if($transportRecord->transport_status === 'active')

                            <span class="badge badge-active">
                                Active
                            </span>

                        @else

                            <span class="badge badge-inactive">
                                Inactive
                            </span>

                        @endif

                    </div>

                </td>

                <td>

                    <div class="label">
                        Start Date
                    </div>

                    <div class="value">
                        {{ $transportRecord->start_date?->format('d M Y') ?? '—' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td>

                    <div class="label">
                        End Date
                    </div>

                    <div class="value">
                        {{ $transportRecord->end_date?->format('d M Y') ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Transport Type
                    </div>

                    <div class="value">

                        {{
                            $transportRecord->transport_type
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $transportRecord->transport_type
                                )
                            )
                            : '—'
                        }}

                    </div>

                </td>

                <td></td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         TRAVEL DETAILS
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td class="section-header-left">

                    <span class="section-marker cyan"></span>

                    <span class="section-title">
                        Travel Details
                    </span>

                </td>

                <td class="section-header-right">

                    <span class="section-caption">
                        Daily transportation schedule
                    </span>

                </td>

            </tr>

        </table>


        <table class="info-table">

            <tr>

                <td>

                    <div class="label">
                        Transport Type
                    </div>

                    <div class="value">

                        {{
                            $transportRecord->transport_type
                            ? ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $transportRecord->transport_type
                                )
                            )
                            : '—'
                        }}

                    </div>

                </td>

                <td>

                    <div class="label">
                        Pickup Time
                    </div>

                    <div class="value">

                        {{
                            $transportRecord->pickup_time
                            ? \Carbon\Carbon::parse(
                                $transportRecord->pickup_time
                            )->format('h:i A')
                            : '—'
                        }}

                    </div>

                </td>

                <td>

                    <div class="label">
                        Drop Time
                    </div>

                    <div class="value">

                        {{
                            $transportRecord->drop_time
                            ? \Carbon\Carbon::parse(
                                $transportRecord->drop_time
                            )->format('h:i A')
                            : '—'
                        }}

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         FEE INFORMATION
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td class="section-header-left">

                    <span class="section-marker green"></span>

                    <span class="section-title">
                        Fee Information
                    </span>

                </td>

                <td class="section-header-right">

                    <span class="section-caption">
                        Transport fee and payment details
                    </span>

                </td>

            </tr>

        </table>


        <table class="info-table">

            <tr>

                <td>

                    <div class="label">
                        Transport Fee
                    </div>

                    <div class="value fee-value">

                        @if($transportRecord->transport_fee !== null)

                            ₹ {{ number_format(
                                (float) $transportRecord->transport_fee,
                                2
                            ) }}

                        @else

                            —

                        @endif

                    </div>

                </td>

                <td>

                    <div class="label">
                        Fee Frequency
                    </div>

                    <div class="value">

                        {{
                            $transportRecord->fee_frequency
                            ? ucfirst(
                                $transportRecord->fee_frequency
                            )
                            : '—'
                        }}

                    </div>

                </td>

                <td>

                    <div class="label">
                        Payment Status
                    </div>

                    <div class="value">

                        @if($transportRecord->payment_status === 'paid')

                            <span class="badge badge-paid">
                                Paid
                            </span>

                        @elseif(
                            $transportRecord->payment_status === 'partially_paid'
                        )

                            <span class="badge badge-partial">
                                Partially Paid
                            </span>

                        @else

                            <span class="badge badge-pending">
                                Pending
                            </span>

                        @endif

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         DOCUMENT NOTE
    ====================================================== --}}

    <div class="document-note">

        <div class="document-note-title">
            Record Note
        </div>

        <div class="document-note-text">
            This document contains the transportation assignment,
            travel schedule and fee information maintained for the
            above student in the School Management System.
        </div>

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <table class="footer">

        <tr>

            <td class="footer-left">

                <span class="footer-brand">
                    School Management System
                </span>

                &nbsp; | &nbsp;

                Transport Management

            </td>

            <td class="footer-right">

                Record #{{ $transportRecord->id }}

                &nbsp; | &nbsp;

                {{ now()->format('d M Y') }}

            </td>

        </tr>

    </table>

</div>

</body>
</html>