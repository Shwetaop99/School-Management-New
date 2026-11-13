
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Student Result</title>

    <style>

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            color: #172033;
            font-size: 11px;
            background: #ffffff;
        }

        .report-container {
            position: relative;
            width: 100%;
            margin: 0 auto;
        }

        /* =========================================================
           FAINT BACKGROUND WATERMARK
        ========================================================== */

        .watermark {
            position: fixed;
            top: 34%;
            left: 50%;
            width: 330px;
            height: 330px;
            margin-left: -165px;
            margin-top: -165px;
            text-align: center;
            z-index: -1;
        }

        .watermark img {
            width: 330px;
            height: 330px;
            object-fit: contain;
            opacity: 0.055;
        }

        /* =========================================================
           MAIN BORDER
        ========================================================== */

        .main-border {
            border: 1.5px solid #1677f0;
            padding: 9px;
            min-height: 270mm;
        }

        .inner-border {
            border: 1px solid #d7e4f5;
            padding: 12px;
        }

        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #1677f0;
        }

        .school-logo {
            max-height: 68px;
            max-width: 95px;
            margin-bottom: 4px;
        }

        .school-name {
            margin: 0;
            color: #12345b;
            font-size: 21px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .school-address {
            margin-top: 4px;
            color: #526174;
            font-size: 9px;
            line-height: 1.5;
        }

        .report-title-wrapper {
            margin-top: 10px;
        }

        .report-title {
            display: inline-block;
            margin: 0;
            padding: 6px 20px;
            background: #1677f0;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* =========================================================
           STUDENT INFORMATION
        ========================================================== */

        .section-heading {
            margin-top: 13px;
            margin-bottom: 6px;
            padding: 6px 9px;
            background: #eef6ff;
            border-left: 4px solid #1677f0;
            color: #12345b;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .student-section {
            border: 1px solid #d4deea;
            border-radius: 4px;
            padding: 8px;
            background: #ffffff;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-table td {
            padding: 5px 6px;
            vertical-align: middle;
            border-bottom: 1px solid #edf1f5;
        }

        .student-table tr:last-child td {
            border-bottom: none;
        }

        .info-label {
            width: 17%;
            color: #526174;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .info-value {
            width: 33%;
            color: #172033;
            font-size: 10px;
            font-weight: bold;
        }

        /* =========================================================
           MARKS TABLE
        ========================================================== */

        .marks-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #c8d3df;
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
        }

        .marks-table th {
            background: #1677f0;
            color: #ffffff;
            font-weight: bold;
        }

        .marks-table thead tr:nth-child(2) th {
            background: #eaf3ff;
            color: #12345b;
            font-size: 8px;
        }

        .marks-table tbody tr:nth-child(even) td {
            background: #f8fbff;
        }

        .marks-table .subject {
            text-align: left;
            width: 27%;
            font-weight: bold;
            color: #26364a;
        }

        .marks-table tbody td {
            height: 27px;
        }

        /* =========================================================
           SUMMARY CARDS
        ========================================================== */

        .summary {
            margin-top: 12px;
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #d4deea;
            padding: 8px 5px;
            text-align: center;
            background: #f8fbff;
        }

        .summary-label {
            display: block;
            color: #66758a;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .summary-value {
            display: block;
            margin-top: 3px;
            color: #12345b;
            font-size: 13px;
            font-weight: bold;
        }

        /* =========================================================
           RESULT STATUS
        ========================================================== */

        .result-status-box {
            margin-top: 9px;
            text-align: center;
            border: 1px solid #cbd8e6;
            padding: 8px;
            background: #f8fbff;
        }

        .result-status-label {
            color: #68778a;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .result-status-value {
            margin-top: 3px;
            color: #1677f0;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* =========================================================
           VERIFICATION
        ========================================================== */

        .verification-box {
            margin-top: 9px;
            border: 1px solid #cbd8e6;
            padding: 8px;
            text-align: center;
        }

        .verified {
            background: #f0fff7;
            border-color: #9bd8b7;
        }

        .not-verified {
            background: #fffaf0;
            border-color: #e8cf98;
        }

        .verified-title {
            color: #15803d;
            font-size: 11px;
            font-weight: bold;
        }

        .verified-text {
            margin-top: 3px;
            color: #3f6650;
            font-size: 8px;
        }

        .verified-date {
            margin-top: 3px;
            color: #526174;
            font-size: 7.5px;
        }

        .not-verified-title {
            color: #a16207;
            font-size: 11px;
            font-weight: bold;
        }

        .not-verified-text {
            margin-top: 3px;
            color: #76613a;
            font-size: 8px;
        }

        /* =========================================================
           REMARKS
        ========================================================== */

        .remarks {
            margin-top: 9px;
            border: 1px solid #d4deea;
            padding: 8px;
            background: #fafcff;
            min-height: 38px;
            font-size: 8.5px;
            color: #36475b;
        }

        .remarks-title {
            color: #12345b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }

        /* =========================================================
           SIGNATURES
        ========================================================== */

        .signature-table {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            padding-top: 25px;
        }

        .signature-line {
            border-top: 1px solid #26364a;
            padding-top: 5px;
            margin: 0 18px;
            color: #26364a;
            font-size: 8.5px;
            font-weight: bold;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .footer {
            margin-top: 12px;
            padding-top: 7px;
            border-top: 1px solid #d7e0ea;
            text-align: center;
            color: #7a8797;
            font-size: 7px;
            line-height: 1.5;
        }

        /* =========================================================
           PRINT DETAILS
        ========================================================== */

        .document-meta {
            margin-top: 5px;
            color: #7a8797;
            font-size: 7.5px;
        }

    </style>

</head>


<body>

<div class="report-container">

    {{-- =========================================================
         FAINT SCHOOL LOGO WATERMARK
    ========================================================== --}}

    @if($school?->logo)

        @php

            $watermarkPath = storage_path(
                'app/public/' . ltrim($school->logo, '/')
            );

        @endphp

        @if(file_exists($watermarkPath))

            <div class="watermark">
                <img
                    src="{{ $watermarkPath }}"
                    alt=""
                >
            </div>

        @endif

    @endif


    <div class="main-border">

        <div class="inner-border">


            {{-- =====================================================
                 SCHOOL HEADER
            ====================================================== --}}

            <div class="school-header">

                @if($school?->logo)

                    @php

                        $logoPath = storage_path(
                            'app/public/' . ltrim($school->logo, '/')
                        );

                    @endphp

                    @if(file_exists($logoPath))

                        <img
                            src="{{ $logoPath }}"
                            class="school-logo"
                            alt="School Logo"
                        >

                    @endif

                @endif


                {{-- SCHOOL NAME --}}

                <h1 class="school-name">
                    {{ $school?->school_name ?? 'School Name' }}
                </h1>


                {{-- SCHOOL ADDRESS --}}

                @if($school)

                    <div class="school-address">

                        {{ $school->address ?? '' }}

                        @if($school->city)
                            , {{ $school->city }}
                        @endif

                        @if($school->district)
                            , {{ $school->district }}
                        @endif

                        @if($school->state)
                            , {{ $school->state }}
                        @endif

                        @if($school->pincode)
                            - {{ $school->pincode }}
                        @endif

                    </div>


                    {{-- PHONE / EMAIL --}}

                    @if($school->phone || $school->email)

                        <div class="school-address">

                            @if($school->phone)

                                Phone:
                                {{ $school->phone }}

                            @endif


                            @if($school->phone && $school->email)

                                &nbsp; | &nbsp;

                            @endif


                            @if($school->email)

                                Email:
                                {{ $school->email }}

                            @endif

                        </div>

                    @endif

                @endif


                {{-- REPORT TITLE --}}

                <div class="report-title-wrapper">

                    <span class="report-title">
                        Student Result
                    </span>

                </div>


                <div class="document-meta">

                    Official Academic Result Statement

                </div>

            </div>



            {{-- =====================================================
                 STUDENT INFORMATION
            ====================================================== --}}

            <div class="section-heading">
                Student Information
            </div>


            <div class="student-section">

                <table class="student-table">

                    {{-- ROW 1 --}}

                    <tr>

                        <td class="info-label">
                            Student ID
                        </td>

                        <td class="info-value">
                            {{ $result->student?->student_id ?? '-' }}
                        </td>


                        <td class="info-label">
                            Roll Number
                        </td>

                        <td class="info-value">
                            {{ $result->student?->roll_number ?? '-' }}
                        </td>

                    </tr>


                    {{-- ROW 2 --}}

                    <tr>

                        <td class="info-label">
                            Student Name
                        </td>

                        <td class="info-value">

                            {{ trim(
                                ($result->student?->first_name ?? '') . ' ' .
                                ($result->student?->middle_name ?? '') . ' ' .
                                ($result->student?->last_name ?? '')
                            ) ?: '-' }}

                        </td>


                        <td class="info-label">
                            Academic Year
                        </td>

                        <td class="info-value">
                            {{ $result->academic_year ?? '-' }}
                        </td>

                    </tr>


                    {{-- ROW 3 --}}

                    <tr>

                        <td class="info-label">
                            Class
                        </td>

                        <td class="info-value">
                            {{ $result->class_name ?? '-' }}
                        </td>


                        <td class="info-label">
                            Section
                        </td>

                        <td class="info-value">
                            {{ $result->section ?? '-' }}
                        </td>

                    </tr>


                    {{-- ROW 4 --}}

                    <tr>

                        <td class="info-label">
                            Examination
                        </td>

                        <td class="info-value">
                            {{ $result->exam?->exam_name ?? '-' }}
                        </td>


                        <td class="info-label">
                            Generated Date
                        </td>

                        <td class="info-value">
                            {{ $result->generated_at?->format('d M Y') ?? '-' }}
                        </td>

                    </tr>

                </table>

            </div>



            {{-- =====================================================
                 SUBJECT-WISE MARKS
            ====================================================== --}}

            <div class="section-heading">
                Subject-wise Marks
            </div>


            <table class="marks-table">

                <thead>

                    <tr>

                        <th rowspan="2" style="width: 7%;">
                            Sr. No.
                        </th>

                        <th rowspan="2" class="subject">
                            Subject
                        </th>

                        <th colspan="3">
                            Marks Obtained
                        </th>

                        <th rowspan="2" style="width: 11%;">
                            Obtained
                        </th>

                        <th rowspan="2" style="width: 11%;">
                            Maximum
                        </th>

                        <th rowspan="2" style="width: 10%;">
                            Grade
                        </th>

                    </tr>


                    <tr>

                        <th>
                            Internal
                        </th>

                        <th>
                            Theory
                        </th>

                        <th>
                            Practical
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($result->details as $index => $detail)

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>


                            <td class="subject">
                                {{ $detail->subject_name }}
                            </td>


                            <td>
                                {{ number_format((float) $detail->internal_marks, 2) }}
                            </td>


                            <td>
                                {{ number_format((float) $detail->theory_marks, 2) }}
                            </td>


                            <td>
                                {{ number_format((float) $detail->practical_marks, 2) }}
                            </td>


                            <td>
                                <strong>
                                    {{ number_format((float) $detail->obtained_marks, 2) }}
                                </strong>
                            </td>


                            <td>
                                {{ number_format((float) $detail->max_marks, 2) }}
                            </td>


                            <td>
                                <strong>
                                    {{ $detail->grade ?? '-' }}
                                </strong>
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">
                                No subject marks available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>



            {{-- =====================================================
                 SUMMARY
            ====================================================== --}}

            <table class="summary">

                <tr>

                    <td>

                        <span class="summary-label">
                            Total Maximum
                        </span>

                        <span class="summary-value">
                            {{ number_format((float) $result->total_marks, 2) }}
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Total Obtained
                        </span>

                        <span class="summary-value">
                            {{ number_format((float) $result->obtained_marks, 2) }}
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Percentage
                        </span>

                        <span class="summary-value">
                            {{ number_format((float) $result->percentage, 2) }}%
                        </span>

                    </td>


                    <td>

                        <span class="summary-label">
                            Grade
                        </span>

                        <span class="summary-value">
                            {{ $result->grade ?? '-' }}
                        </span>

                    </td>

                </tr>

            </table>



            {{-- =====================================================
                 RESULT STATUS
            ====================================================== --}}

            <div class="result-status-box">

                <div class="result-status-label">
                    Overall Result Status
                </div>

                <div class="result-status-value">

                    {{ strtoupper($result->result_status ?? 'PENDING') }}

                </div>

            </div>



            {{-- =====================================================
                 VERIFICATION / PUBLICATION STATUS
            ====================================================== --}}

            @if($result->publication_status === 'published')

                <div class="verification-box verified">

                    <div class="verified-title">
                        ✓ OFFICIALLY PUBLISHED
                    </div>

                    <div class="verified-text">
                        This result has been officially published by the school.
                    </div>


                    @if($result->published_at)

                        <div class="verified-date">

                            Published On:
                            {{ \Carbon\Carbon::parse($result->published_at)->format('d M Y h:i A') }}

                        </div>

                    @endif

                </div>

            @else

                <div class="verification-box not-verified">

                    <div class="not-verified-title">
                        RESULT NOT PUBLISHED
                    </div>

                    <div class="not-verified-text">
                        This result has not been officially published by the school.
                    </div>

                </div>

            @endif



            {{-- =====================================================
                 REMARKS
            ====================================================== --}}

            @if($result->remarks)

                <div class="remarks">

                    <span class="remarks-title">
                        Remarks:
                    </span>

                    &nbsp;

                    {{ $result->remarks }}

                </div>

            @endif



            {{-- =====================================================
                 SIGNATURES
            ====================================================== --}}

            <table class="signature-table">

                <tr>

                    <td>

                        <div class="signature-line">
                            Class Teacher
                        </div>

                    </td>


                    <td>

                        <div class="signature-line">
                            Principal
                        </div>

                    </td>


                    <td>

                        <div class="signature-line">
                            Parent / Guardian
                        </div>

                    </td>

                </tr>

            </table>



            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="footer">

                This is a computer-generated result.

                @if($result->publication_status === 'published')

                    This result is officially published by the school.

                @endif

                <br>

                Student ID and other academic information should be verified
                with the school records if required.

            </div>


        </div>

    </div>

</div>

</body>

</html>
