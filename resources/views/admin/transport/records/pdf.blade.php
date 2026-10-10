<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Transport Record #{{ $transportRecord->id }}
    </title>

    <style>

        @page {
            size: A4;
            margin: 12mm 14mm 14mm 14mm;
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
           HEADER
        ===================================================== */

        .header {
            width: 100%;
            border-bottom: 2px solid #1769d1;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .logo-cell {
            width: 15%;
            vertical-align: middle;
            text-align: left;
        }

        .header-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .school-cell {
            width: 60%;
            vertical-align: middle;
            padding-left: 5px;
        }

        .school-name {
            color: #1769d1;
            font-size: 15px;
            font-weight: bold;
            line-height: 1.25;
        }

        .school-address {
            color: #64748b;
            font-size: 6.8px;
            margin-top: 4px;
            line-height: 1.5;
        }

        .document-cell {
            width: 25%;
            vertical-align: middle;
            text-align: right;
        }

        .document-label {
            color: #94a3b8;
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .document-number {
            color: #1769d1;
            font-size: 13px;
            font-weight: bold;
            margin-top: 2px;
        }

        .document-date {
            color: #64748b;
            font-size: 6.5px;
            margin-top: 3px;
        }

        /* =====================================================
           TITLE
        ===================================================== */

        .title-area {
            margin-bottom: 15px;
        }

        .title {
            color: #172033;
            font-size: 20px;
            font-weight: bold;
            line-height: 1.2;
        }

        .title-line {
            width: 35px;
            height: 3px;
            background: #1769d1;
            margin-top: 5px;
        }

        /* =====================================================
           STUDENT SUMMARY
        ===================================================== */

        .student-card {
            width: 100%;
            border: 1px solid #d6dee8;
            margin-bottom: 17px;
        }

        .student-card-title {
            background: #f4f7fb;
            color: #1769d1;
            padding: 7px 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid #d6dee8;
        }

        .student-main {
            width: 55%;
            padding: 11px;
            vertical-align: middle;
        }

        .student-name {
            color: #172033;
            font-size: 13px;
            font-weight: bold;
        }

        .student-id {
            color: #64748b;
            font-size: 7px;
            margin-top: 4px;
        }

        .student-status {
            width: 45%;
            padding: 11px;
            vertical-align: middle;
            text-align: right;
        }

        .status-title {
            color: #94a3b8;
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }

        .status-active {
            display: inline-block;
            padding: 4px 9px;
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-size: 6.3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-inactive {
            display: inline-block;
            padding: 4px 9px;
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecdd3;
            font-size: 6.3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* =====================================================
           SECTION
        ===================================================== */

        .section {
            width: 100%;
            margin-bottom: 17px;
            page-break-inside: avoid;
        }

        .section-header {
            width: 100%;
            margin-bottom: 7px;
        }

        .section-marker {
            display: inline-block;
            width: 4px;
            height: 13px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .blue {
            background: #1769d1;
        }

        .cyan {
            background: #0891b2;
        }

        .green {
            background: #159447;
        }

        .orange {
            background: #ea7b18;
        }

        .section-title {
            color: #172033;
            font-size: 9.5px;
            font-weight: bold;
            vertical-align: middle;
        }

        /* =====================================================
           INFORMATION TABLE
        ===================================================== */

        .info-table {
            width: 100%;
            border: 1px solid #d6dee8;
        }

        .info-table td {
            width: 33.33%;
            padding: 9px 10px;
            vertical-align: top;
            border-right: 1px solid #e1e7ee;
            border-bottom: 1px solid #e1e7ee;
        }

        .info-table td:last-child {
            border-right: none;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        .label {
            color: #94a3b8;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .45px;
            margin-bottom: 3px;
        }

        .value {
            color: #172033;
            font-size: 8.2px;
            font-weight: bold;
            word-wrap: break-word;
        }

        .normal {
            font-weight: normal;
        }

        /* =====================================================
           ASSIGNMENT HIGHLIGHT
        ===================================================== */

        .assignment {
            width: 100%;
            border: 1px solid #cbd8e7;
            background: #f8fbff;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .assignment-title {
            color: #1769d1;
            padding: 8px 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1px solid #d9e3ef;
        }

        .assignment td {
            width: 25%;
            padding: 9px 10px;
            vertical-align: top;
            border-right: 1px solid #e1e7ee;
        }

        .assignment td:last-child {
            border-right: none;
        }

        .assignment-label {
            color: #94a3b8;
            font-size: 5.8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 3px;
        }

        .assignment-value {
            color: #172033;
            font-size: 8px;
            font-weight: bold;
        }

        .route {
            color: #c2610d;
        }

        .vehicle {
            color: #1769d1;
        }

        .pickup {
            color: #087f9c;
        }

        /* =====================================================
           BADGES
        ===================================================== */

        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-paid,
        .badge-active {
            background: #ecfdf3;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-partial {
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
        }

        .badge-pending,
        .badge-inactive {
            background: #fff1f2;
            color: #dc2626;
            border: 1px solid #fecdd3;
        }

        /* =====================================================
           FEE HIGHLIGHT
        ===================================================== */

        .fee {
            color: #15803d;
            font-size: 9px;
            font-weight: bold;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            width: 100%;
            border-top: 1px solid #d6dee8;
            padding-top: 7px;
            margin-top: 5px;
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

        .student-card,
        .assignment,
        .section {
            page-break-inside: avoid;
        }

    </style>

</head>

<body>

<div>

    {{-- =====================================================
         SCHOOL HEADER
    ====================================================== --}}

    <table class="header">

        <tr>

            <td class="logo-cell">

                {{-- FIXED: school_settings uses "logo", not "logo_url" --}}
                @if($school?->logo)

                    <img
                        src="{{ $school->logo }}"
                        class="header-logo"
                        alt="School Logo"
                    >

                @endif

            </td>

            <td class="school-cell">

                <div class="school-name">
                    {{ $school?->school_name ?? 'School Management System' }}
                </div>

                @if(
                    $school?->address ||
                    $school?->city ||
                    $school?->district ||
                    $school?->state ||
                    $school?->pincode
                )

                    <div class="school-address">

                        {{ $school?->address }}

                        @if($school?->address && $school?->city)
                            ,
                        @endif

                        {{ $school?->city }}

                        @if($school?->city && $school?->district)
                            ,
                        @endif

                        {{ $school?->district }}

                        @if($school?->state)
                            , {{ $school?->state }}
                        @endif

                        @if($school?->pincode)
                            - {{ $school?->pincode }}
                        @endif

                    </div>

                @endif

            </td>

            <td class="document-cell">

                <div class="document-label">
                    Transport Record
                </div>

                <div class="document-number">
                    #{{ $transportRecord->id }}
                </div>

                <div class="document-date">
                    {{ now()->format('d M Y') }}
                </div>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         TITLE
    ====================================================== --}}

    <div class="title-area">

        <div class="title">
            Transport Record
        </div>

        <div class="title-line"></div>

    </div>


    {{-- =====================================================
         STUDENT SUMMARY
    ====================================================== --}}

    <table class="student-card">

        <tr>

            <td colspan="2" class="student-card-title">
                Student
            </td>

        </tr>

        <tr>

            <td class="student-main">

                <div class="student-name">
                    {{ $transportRecord->student?->full_name ?? 'Student Not Available' }}
                </div>

                <div class="student-id">

                    ID:
                    {{ $transportRecord->student?->student_id ?? '—' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    Roll No:
                    {{ $transportRecord->student?->roll_number ?? '—' }}

                    &nbsp;&nbsp; | &nbsp;&nbsp;

                    Class:
                    {{ $transportRecord->student?->class ?? '—' }}

                    @if($transportRecord->student?->section)
                        - {{ $transportRecord->student->section }}
                    @endif

                </div>

            </td>

            <td class="student-status">

                <div class="status-title">
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
                Transport Assignment
            </td>

        </tr>

        <tr>

            <td>

                <div class="assignment-label">
                    Route
                </div>

                <div class="assignment-value route">
                    {{ $transportRecord->route ?: 'Not Assigned' }}
                </div>

            </td>

            <td>

                <div class="assignment-label">
                    Vehicle
                </div>

                <div class="assignment-value vehicle">
                    {{ $transportRecord->vehicle ?: 'Not Assigned' }}
                </div>

            </td>

            <td>

                <div class="assignment-label">
                    Pickup Point
                </div>

                <div class="assignment-value pickup">
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
         STUDENT DETAILS
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td>

                    <span class="section-marker blue"></span>

                    <span class="section-title">
                        Student Details
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
                        Parent / Guardian
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->father_name ?? '—' }}
                    </div>

                </td>

                <td>

                    <div class="label">
                        Parent Phone
                    </div>

                    <div class="value">
                        {{ $transportRecord->student?->father_phone ?? '—' }}
                    </div>

                </td>

            </tr>

            <tr>

                <td colspan="3">

                    <div class="label">
                        Address
                    </div>

                    <div class="value normal">
                        {{ $transportRecord->student?->address ?? '—' }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         TRAVEL DETAILS
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td>

                    <span class="section-marker cyan"></span>

                    <span class="section-title">
                        Travel Details
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

            <tr>

                <td>

                    <div class="label">
                        Start Date
                    </div>

                    <div class="value">
                        {{ $transportRecord->start_date?->format('d M Y') ?? '—' }}
                    </div>

                </td>

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
                        Status
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

            </tr>

        </table>

    </div>


    {{-- =====================================================
         FEE DETAILS
    ====================================================== --}}

    <div class="section">

        <table class="section-header">

            <tr>

                <td>

                    <span class="section-marker green"></span>

                    <span class="section-title">
                        Fee Details
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

                    <div class="value fee">

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
                        Frequency
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
         FOOTER
    ====================================================== --}}

    <table class="footer">

        <tr>

            <td class="footer-left">

                <span class="footer-brand">
                    {{ $school?->school_name ?? 'School Management System' }}
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
