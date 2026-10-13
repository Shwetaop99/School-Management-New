
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Result - {{ $result->student?->student_id ?? 'Student' }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #1677f0;
            --primary-dark: #0d63d3;
            --primary-light: #eef6ff;
            --navy: #173b69;
            --text: #172033;
            --muted: #667085;
            --border: #d7dee8;
            --success: #16803c;
            --success-bg: #edf9f0;
        }

        body {
            margin: 0;
            padding: 25px;
            background: #eef2f7;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
        }

        /* =========================================================
           TOP ACTIONS
        ========================================================== */

        .top-actions {
            max-width: 1100px;
            margin: 0 auto 18px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 10px 18px;

            border: 0;
            border-radius: 8px;

            cursor: pointer;
            text-decoration: none;

            font-size: 14px;
            font-weight: 700;

            transition: .2s ease;
        }

        .btn-print {
            background: var(--primary);
            color: #fff;

            box-shadow: 0 4px 12px rgba(22, 119, 240, .18);
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-back {
            background: #667085;
            color: #fff;
        }

        .btn-back:hover {
            background: #475467;
            transform: translateY(-1px);
        }

        /* =========================================================
           MAIN DOCUMENT
        ========================================================== */

        .print-container {
            position: relative;

            max-width: 1100px;
            margin: 0 auto;

            background: #fff;

            padding: 28px;

            border: 1px solid #d8dee8;
            border-radius: 14px;

            box-shadow:
                0 12px 40px rgba(20, 40, 80, .10);

            overflow: hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | Faint background logo
        |--------------------------------------------------------------------------
        */

        .background-logo {
            position: absolute;

            top: 50%;
            left: 50%;

            width: 430px;
            height: 430px;

            transform: translate(-50%, -50%);

            z-index: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            pointer-events: none;
        }

        .background-logo img {
            width: 100%;
            height: 100%;

            object-fit: contain;

            /*
            | Very faint watermark
            */
            opacity: .045;
        }

        /*
        |--------------------------------------------------------------------------
        | Keep actual content above watermark
        |--------------------------------------------------------------------------
        */

        .document-content {
            position: relative;
            z-index: 1;
        }

        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {
            position: relative;

            text-align: center;

            padding: 10px 15px 20px;

            border-bottom: 3px solid var(--primary);
        }

        .school-header-inner {
            display: flex;

            align-items: center;
            justify-content: center;

            gap: 22px;
        }

        /* =========================================================
           SCHOOL LOGO
        ========================================================== */

        .school-logo-wrapper {
            width: 115px;
            height: 115px;

            min-width: 115px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;
            overflow: hidden;

            background: #fff;

            border: 3px solid var(--primary);

            box-shadow:
                0 6px 18px rgba(22, 119, 240, .16);
        }

        .school-logo {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: contain;

            padding: 8px;

            background: #fff;
        }

        .school-logo-fallback {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f4f8ff;
            color: var(--primary);

            font-size: 38px;
            font-weight: 800;
        }

        /* =========================================================
           SCHOOL INFORMATION
        ========================================================== */

        .school-header-text {
            text-align: left;
        }

        .school-name {
            margin: 0;

            color: #10213d;

            font-size: 29px;
            font-weight: 800;

            line-height: 1.2;

            text-transform: uppercase;

            letter-spacing: .5px;
        }

        .school-address {
            margin-top: 8px;

            color: #58657a;

            font-size: 13px;

            line-height: 1.5;
        }

        .school-contact {
            margin-top: 5px;

            color: #58657a;

            font-size: 12px;
        }

        .school-code {
            margin-top: 5px;

            color: #667085;

            font-size: 11px;
        }

        /* =========================================================
           REPORT TITLE
        ========================================================== */

        .report-title {
            margin-top: 20px;

            display: inline-block;

            padding: 9px 32px;

            border-radius: 30px;

            background: var(--primary);
            color: #fff;

            font-size: 17px;
            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 1.4px;

            box-shadow:
                0 5px 14px rgba(22, 119, 240, .18);
        }

        /* =========================================================
           SECTION TITLE
        ========================================================== */

        .section-title,
        .marks-title {
            margin-top: 25px;
            margin-bottom: 9px;

            padding: 10px 14px;

            border-left: 4px solid var(--primary);

            background: var(--primary-light);

            color: #17325c;

            font-size: 15px;
            font-weight: 800;

            letter-spacing: .1px;

            border-radius: 0 6px 6px 0;
        }

        /* =========================================================
           STUDENT INFORMATION
        ========================================================== */

        .student-section {
            display: grid;

            grid-template-columns: 1fr 1fr;

            border: 1px solid #d5dce7;

            border-radius: 9px;

            overflow: hidden;

            background: rgba(255, 255, 255, .94);
        }

        .info-row {
            display: flex;

            align-items: center;

            gap: 8px;

            min-height: 44px;

            padding: 9px 14px;

            border-bottom: 1px solid #e3e7ed;
        }

        .info-row:nth-child(odd) {
            border-right: 1px solid #e3e7ed;
        }

        .info-row:nth-last-child(-n+2) {
            border-bottom: 0;
        }

        .info-label {
            min-width: 125px;

            color: var(--muted);

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: .2px;
        }

        .info-value {
            color: var(--text);

            font-size: 13px;
            font-weight: 700;
        }

        /* =========================================================
           MARKS TABLE
        ========================================================== */

        .marks-table-wrapper {
            overflow: hidden;

            border: 1px solid #172b4d;

            border-radius: 8px;

            background: rgba(255, 255, 255, .96);
        }

        table {
            width: 100%;

            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #cbd3df;

            padding: 10px 7px;

            text-align: center;

            font-size: 12px;
        }

        th {
            background: var(--navy);

            color: #fff;

            font-weight: 800;
        }

        thead tr:nth-child(2) th {
            background: #24578f;
        }

        td {
            background: rgba(255, 255, 255, .95);
        }

        tbody tr:nth-child(even) td {
            background: rgba(248, 250, 252, .94);
        }

        tbody tr:hover td {
            background: #eef6ff;
        }

        td.subject {
            text-align: left;

            font-weight: 700;

            color: #26364d;
        }

        /* =========================================================
           GRADE
        ========================================================== */

        .grade-badge {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 34px;

            padding: 4px 8px;

            border-radius: 20px;

            background: #eef6ff;

            color: var(--primary);

            font-size: 11px;
            font-weight: 800;
        }

        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary {
            margin-top: 18px;

            display: grid;

            grid-template-columns: repeat(4, 1fr);

            border: 1px solid #cbd3df;

            border-radius: 9px;

            overflow: hidden;

            background: rgba(255, 255, 255, .95);
        }

        .summary-item {
            padding: 15px 8px;

            text-align: center;

            border-right: 1px solid #cbd3df;

            background: #f8fafc;
        }

        .summary-item:last-child {
            border-right: 0;
        }

        .summary-label {
            display: block;

            margin-bottom: 6px;

            color: #68758a;

            font-size: 10px;
            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .4px;
        }

        .summary-value {
            display: block;

            color: #14233b;

            font-size: 20px;
            font-weight: 800;
        }

        /* =========================================================
           RESULT STATUS
        ========================================================== */

        .result-status {
            margin-top: 18px;

            padding: 13px;

            text-align: center;

            border: 1px solid #b8dec6;

            border-radius: 8px;

            background: var(--success-bg);

            color: var(--success);

            font-size: 16px;
            font-weight: 800;

            letter-spacing: .5px;
        }

        /* =========================================================
           PUBLICATION STATUS
        ========================================================== */

        .publication-status {
            margin-top: 10px;

            display: flex;

            align-items: center;
            justify-content: center;

            gap: 7px;

            color: #667085;

            font-size: 11px;
            font-weight: 600;
        }

        .publication-dot {
            width: 9px;
            height: 9px;

            border-radius: 50%;

            background: #12b76a;

            box-shadow:
                0 0 0 3px rgba(18, 183, 106, .12);
        }

        /* =========================================================
           REMARKS
        ========================================================== */

        .remarks {
            margin-top: 15px;

            border: 1px solid #d5dce7;

            border-radius: 8px;

            padding: 13px;

            min-height: 55px;

            background: rgba(250, 251, 253, .95);

            font-size: 13px;

            color: #344054;
        }

        .remarks strong {
            color: #173b69;
        }

        /* =========================================================
           SIGNATURES
        ========================================================== */

        .signature-section {
            margin-top: 75px;

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 45px;

            text-align: center;
        }

        .signature {
            padding-top: 12px;

            border-top: 1px solid #222;

            font-size: 12px;
            font-weight: 700;

            color: #344054;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .footer {
            margin-top: 30px;

            padding-top: 10px;

            border-top: 1px solid #e0e4ea;

            text-align: center;

            font-size: 10px;

            color: #7a8494;

            line-height: 1.6;
        }

        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 700px) {

            body {
                padding: 10px;
            }

            .top-actions {
                flex-direction: column;

                align-items: stretch;
            }

            .btn {
                width: 100%;
            }

            .print-container {
                padding: 15px;

                border-radius: 9px;
            }

            .school-header-inner {
                flex-direction: column;

                gap: 12px;
            }

            .school-header-text {
                text-align: center;
            }

            .school-name {
                font-size: 21px;
            }

            .school-address,
            .school-contact,
            .school-code {
                font-size: 11px;
            }

            .school-logo-wrapper {
                width: 100px;
                height: 100px;
                min-width: 100px;
            }

            .student-section {
                grid-template-columns: 1fr;
            }

            .info-row:nth-child(odd) {
                border-right: 0;
            }

            .info-row:nth-last-child(-n+2) {
                border-bottom: 1px solid #e3e7ed;
            }

            .info-row:last-child {
                border-bottom: 0;
            }

            .summary {
                grid-template-columns: 1fr 1fr;
            }

            .summary-item:nth-child(2) {
                border-right: 0;
            }

            .summary-item:nth-child(3),
            .summary-item:nth-child(4) {
                border-top: 1px solid #cbd3df;
            }

            .signature-section {
                gap: 15px;
            }

            .background-logo {
                width: 280px;
                height: 280px;
            }

            .marks-table-wrapper {
                overflow-x: auto;
            }

            .marks-table-wrapper table {
                min-width: 700px;
            }

            .info-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 3px;
            }

            .info-label {
                min-width: auto;
            }
        }

        /* =========================================================
           PRINT
        ========================================================== */

        @media print {

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            body {
                background: #fff;

                padding: 0;
            }

            .top-actions {
                display: none !important;
            }

            .print-container {
                max-width: none;

                margin: 0;

                padding: 0;

                border: none;

                border-radius: 0;

                box-shadow: none;

                overflow: visible;
            }

            /*
            |--------------------------------------------------------------------------
            | Print watermark
            |--------------------------------------------------------------------------
            */

            .background-logo {
                position: fixed;

                top: 50%;
                left: 50%;

                z-index: 0;
            }

            .background-logo img {
                opacity: .045;
            }

            .document-content {
                position: relative;

                z-index: 1;
            }

            .school-header {
                break-inside: avoid;
            }

            .school-logo-wrapper,
            .school-logo,
            .report-title,
            th,
            thead tr:nth-child(2) th,
            .section-title,
            .marks-title,
            .result-status,
            .grade-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .student-section,
            .summary,
            .remarks,
            .publication-status {
                break-inside: avoid;
            }

            .signature-section {
                page-break-inside: avoid;
            }

            .footer {
                page-break-inside: avoid;
            }
        }

    </style>

</head>


<body>

@php

    /*
    |--------------------------------------------------------------------------
    | School
    |--------------------------------------------------------------------------
    */

    $school = \App\Models\SchoolSetting::first();


    /*
    |--------------------------------------------------------------------------
    | School Logo URL
    |--------------------------------------------------------------------------
    */

    $logoUrl = null;

    if ($school?->logo) {

        if (
            str_starts_with($school->logo, 'http://') ||
            str_starts_with($school->logo, 'https://')
        ) {

            $logoUrl = $school->logo;

        } else {

            $logoUrl = url(
                'storage/' . ltrim($school->logo, '/')
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Student Name
    |--------------------------------------------------------------------------
    */

    $studentName = trim(
        ($result->student?->first_name ?? '') . ' ' .
        ($result->student?->middle_name ?? '') . ' ' .
        ($result->student?->last_name ?? '')
    );

    $studentName = $studentName ?: '-';

@endphp


{{-- =========================================================
     TOP ACTIONS
========================================================= --}}

<div class="top-actions">

    <a
        href="{{ route('admin.results.show', $result->id) }}"
        class="btn btn-back"
    >
        ← Back
    </a>


    <button
        type="button"
        class="btn btn-print"
        onclick="window.print()"
    >
        🖨 Print Result
    </button>

</div>


{{-- =========================================================
     RESULT DOCUMENT
========================================================= --}}

<div class="print-container">


    {{-- =====================================================
         FAINT BACKGROUND LOGO
    ====================================================== --}}

    @if($logoUrl)

        <div class="background-logo">

            <img
                src="{{ $logoUrl }}"
                alt=""
            >

        </div>

    @endif


    <div class="document-content">


        {{-- =====================================================
             SCHOOL HEADER
        ====================================================== --}}

        <div class="school-header">

            <div class="school-header-inner">


                {{-- SCHOOL LOGO --}}

                <div class="school-logo-wrapper">

                    @if($logoUrl)

                        <img
                            src="{{ $logoUrl }}"
                            alt="School Logo"
                            class="school-logo"
                        >

                    @else

                        <div class="school-logo-fallback">

                            {{ strtoupper(
                                substr(
                                    $school?->school_name ?? 'S',
                                    0,
                                    1
                                )
                            ) }}

                        </div>

                    @endif

                </div>


                {{-- SCHOOL INFORMATION --}}

                <div class="school-header-text">

                    <h1 class="school-name">

                        {{ $school?->school_name ?? 'School Name' }}

                    </h1>


                    @if($school)

                        @php

                            $addressParts = array_filter([
                                $school->address ?? null,
                                $school->city ?? null,
                                $school->district ?? null,
                                $school->state ?? null,
                            ]);

                        @endphp


                        @if(count($addressParts))

                            <div class="school-address">

                                {{ implode(', ', $addressParts) }}

                                @if($school->pincode)

                                    - {{ $school->pincode }}

                                @endif

                            </div>

                        @endif


                        @if($school->phone || $school->email)

                            <div class="school-contact">

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


                        @if($school->udise_code || $school->school_code)

                            <div class="school-code">

                                @if($school->udise_code)

                                    UDISE Code:
                                    {{ $school->udise_code }}

                                @endif


                                @if(
                                    $school->udise_code &&
                                    $school->school_code
                                )

                                    &nbsp; | &nbsp;

                                @endif


                                @if($school->school_code)

                                    School Code:
                                    {{ $school->school_code }}

                                @endif

                            </div>

                        @endif

                    @endif

                </div>

            </div>


            {{-- REPORT TITLE --}}

            <div class="report-title">

                Student Result

            </div>

        </div>



        {{-- =====================================================
             STUDENT INFORMATION
        ====================================================== --}}

        <div class="section-title">

            Student Information

        </div>


        <div class="student-section">


            <div class="info-row">

                <span class="info-label">
                    Student ID
                </span>

                <span class="info-value">

                    {{ $result->student?->student_id ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Roll Number
                </span>

                <span class="info-value">

                    {{ $result->student?->roll_number ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Student Name
                </span>

                <span class="info-value">

                    {{ $studentName }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Academic Year
                </span>

                <span class="info-value">

                    {{ $result->academic_year ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Class
                </span>

                <span class="info-value">

                    {{ $result->class_name ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Section
                </span>

                <span class="info-value">

                    {{ $result->section ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Examination
                </span>

                <span class="info-value">

                    {{ $result->exam?->exam_name ?? '-' }}

                </span>

            </div>


            <div class="info-row">

                <span class="info-label">
                    Generated On
                </span>

                <span class="info-value">

                    {{ $result->generated_at?->format(
                        'd M Y h:i A'
                    ) ?? '-' }}

                </span>

            </div>

        </div>



        {{-- =====================================================
             SUBJECT MARKS
        ====================================================== --}}

        <div class="marks-title">

            Subject-wise Marks

        </div>


        <div class="marks-table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th rowspan="2" style="width: 8%;">
                            Sr. No.
                        </th>

                        <th rowspan="2" style="width: 25%;">
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

                    @forelse(
                        $result->details as $index => $detail
                    )

                        <tr>

                            <td>

                                {{ $index + 1 }}

                            </td>


                            <td class="subject">

                                {{ $detail->subject_name }}

                            </td>


                            <td>

                                {{ number_format(
                                    (float) $detail->internal_marks,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    (float) $detail->theory_marks,
                                    2
                                ) }}

                            </td>


                            <td>

                                {{ number_format(
                                    (float) $detail->practical_marks,
                                    2
                                ) }}

                            </td>


                            <td>

                                <strong>

                                    {{ number_format(
                                        (float) $detail->obtained_marks,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            <td>

                                {{ number_format(
                                    (float) $detail->max_marks,
                                    2
                                ) }}

                            </td>


                            <td>

                                <span class="grade-badge">

                                    {{ $detail->grade ?? '-' }}

                                </span>

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

        </div>



        {{-- =====================================================
             RESULT SUMMARY
        ====================================================== --}}

        <div class="section-title">

            Result Summary

        </div>


        <div class="summary">


            <div class="summary-item">

                <span class="summary-label">
                    Total Maximum
                </span>

                <span class="summary-value">

                    {{ number_format(
                        (float) $result->total_marks,
                        2
                    ) }}

                </span>

            </div>


            <div class="summary-item">

                <span class="summary-label">
                    Total Obtained
                </span>

                <span class="summary-value">

                    {{ number_format(
                        (float) $result->obtained_marks,
                        2
                    ) }}

                </span>

            </div>


            <div class="summary-item">

                <span class="summary-label">
                    Percentage
                </span>

                <span class="summary-value">

                    {{ number_format(
                        (float) $result->percentage,
                        2
                    ) }}%

                </span>

            </div>


            <div class="summary-item">

                <span class="summary-label">
                    Grade
                </span>

                <span class="summary-value">

                    {{ $result->grade ?? '-' }}

                </span>

            </div>

        </div>



        {{-- =====================================================
             RESULT STATUS
        ====================================================== --}}

        <div class="result-status">

            Result Status:

            {{ strtoupper(
                $result->result_status ?? 'PENDING'
            ) }}

        </div>



        {{-- =====================================================
             PUBLICATION STATUS
        ====================================================== --}}

        @if(
            $result->publication_status === 'published'
        )

            <div class="publication-status">

                <span class="publication-dot"></span>

                Online Result Published

                @if($result->published_at)

                    on

                    {{ $result->published_at->format(
                        'd M Y h:i A'
                    ) }}

                @endif

            </div>

        @endif



        {{-- =====================================================
             REMARKS
        ====================================================== --}}

        @if($result->remarks)

            <div class="remarks">

                <strong>
                    Remarks:
                </strong>

                {{ $result->remarks }}

            </div>

        @endif



        {{-- =====================================================
             SIGNATURES
        ====================================================== --}}

        <div class="signature-section">

            <div class="signature">

                Class Teacher

            </div>


            <div class="signature">

                Principal

            </div>


            <div class="signature">

                Parent / Guardian

            </div>

        </div>



        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="footer">

            This is a computer-generated result.

            @if($result->published_at)

                &nbsp; | &nbsp;

                Published on:

                {{ $result->published_at->format(
                    'd M Y h:i A'
                ) }}

            @endif

        </div>


    </div>

</div>


</body>

</html>
