<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Exam Schedule - {{ $exam->exam_name }}
</title>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 20px;
        background: #f2f2f2;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .print-page {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        padding: 15mm;
        background: #ffffff;
    }

    /* =========================================================
       SCHOOL HEADER
    ========================================================== */

    .school-header {
        text-align: center;
        border-bottom: 2px solid #222;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }

    .school-logo {
        width: 75px;
        height: 75px;
        object-fit: contain;
        margin-bottom: 6px;
    }

    .school-name {
        font-size: 24px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .school-address {
        font-size: 13px;
        line-height: 1.5;
        color: #444;
    }

    /* =========================================================
       TITLE
    ========================================================== */

    .document-title {
        text-align: center;
        margin: 20px 0;
    }

    .document-title h2 {
        margin: 0 0 5px;
        font-size: 21px;
        text-transform: uppercase;
    }

    .document-title p {
        margin: 0;
        font-size: 14px;
        color: #555;
    }

    /* =========================================================
       EXAM INFORMATION
    ========================================================== */

    .exam-info {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 22px;
    }

    .exam-info td {
        border: 1px solid #999;
        padding: 9px 10px;
        font-size: 13px;
    }

    .exam-info .label {
        width: 20%;
        font-weight: 700;
        background: #f5f5f5;
    }

    .exam-info .value {
        width: 30%;
    }

    /* =========================================================
       SCHEDULE DETAILS
    ========================================================== */

    .section-heading {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid #999;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 22px;
    }

    .schedule-table th,
    .schedule-table td {
        border: 1px solid #555;
        padding: 11px 10px;
        font-size: 13px;
    }

    .schedule-table th {
        background: #eeeeee;
        font-weight: 700;
        text-align: left;
        width: 35%;
    }

    .schedule-table td {
        text-align: left;
    }

    /* =========================================================
       STATUS
    ========================================================== */

    .status {
        display: inline-block;
        padding: 4px 12px;
        border: 1px solid #555;
        border-radius: 4px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
    }

    /* =========================================================
       INSTRUCTIONS
    ========================================================== */

    .instructions-box {
        border: 1px solid #555;
        padding: 12px;
        min-height: 80px;
        font-size: 13px;
        line-height: 1.6;
        white-space: pre-line;
    }

    /* =========================================================
       FOOTER
    ========================================================== */

    .footer {
        margin-top: 55px;
        display: flex;
        justify-content: space-between;
        font-size: 12px;
    }

    .signature {
        text-align: center;
        min-width: 160px;
    }

    .signature-line {
        margin-top: 45px;
        border-top: 1px solid #222;
        padding-top: 5px;
    }

    /* =========================================================
       PRINT BUTTON
    ========================================================== */

    .print-actions {
        width: 210mm;
        margin: 0 auto 15px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn {
        display: inline-block;
        padding: 9px 16px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 13px;
        border: 1px solid #999;
        cursor: pointer;
    }

    .btn-print {
        background: #1677f0;
        color: #ffffff;
        border-color: #1677f0;
    }

    .btn-back {
        background: #ffffff;
        color: #333;
    }

    /* =========================================================
       PRINT CSS
    ========================================================== */

    @page {
        size: A4 portrait;
        margin: 0;
    }

    @media print {

        body {
            padding: 0;
            margin: 0;
            background: #ffffff;
        }

        .print-page {
            width: 210mm;
            min-height: 297mm;
            margin: 0;
            padding: 15mm;
            box-shadow: none;
        }

        .print-actions {
            display: none;
        }

    }

</style>


</head>

<body>


{{-- =========================================================
     PRINT ACTIONS
========================================================== --}}

<div class="print-actions">

    <a href="{{ route(
        'admin.exam-schedules.index',
        $exam->id
    ) }}"
       class="btn btn-back">

        ← Back

    </a>

    <button type="button"
            onclick="window.print()"
            class="btn btn-print">

        Print Schedule

    </button>

</div>


{{-- =========================================================
     A4 PAGE
========================================================== --}}

<div class="print-page">


    {{-- =====================================================
         SCHOOL HEADER
    ====================================================== --}}

    <div class="school-header">

        <img src="{{ asset('images/gurukullogo.png') }}"
             alt="School Logo"
             class="school-logo">

        <div class="school-name">
            Gurukul Vidyalaya
        </div>

        <div class="school-address">
            Palus, Sangli, Maharashtra
        </div>

    </div>


    {{-- =====================================================
         DOCUMENT TITLE
    ====================================================== --}}

    <div class="document-title">

        <h2>
            Examination Schedule
        </h2>

        <p>
            Academic Year:
            <strong>
                {{ $exam->academic_year }}
            </strong>
        </p>

    </div>


    {{-- =====================================================
         EXAM INFORMATION
    ====================================================== --}}

    <table class="exam-info">

        <tr>

            <td class="label">
                Examination
            </td>

            <td class="value">
                {{ $exam->exam_name }}
            </td>

            <td class="label">
                Exam Type
            </td>

            <td class="value">
                {{ $exam->exam_type }}
            </td>

        </tr>

        <tr>

            <td class="label">
                Academic Year
            </td>

            <td class="value">
                {{ $exam->academic_year }}
            </td>

            <td class="label">
                Exam Period
            </td>

            <td class="value">

                @if($exam->start_date)
                    {{ $exam->start_date->format('d M Y') }}
                @else
                    —
                @endif

                &nbsp; to &nbsp;

                @if($exam->end_date)
                    {{ $exam->end_date->format('d M Y') }}
                @else
                    —
                @endif

            </td>

        </tr>

    </table>


    {{-- =====================================================
         SCHEDULE DETAILS
    ====================================================== --}}

    <div class="section-heading">
        Schedule Details
    </div>


    <table class="schedule-table">

        <tr>

            <th>
                Class
            </th>

            <td>
                {{ $schedule->examClass?->class_name ?? '—' }}
            </td>

        </tr>


        <tr>

            <th>
                Section
            </th>

            <td>

                @if($schedule->examClassSection)
                    Section {{ $schedule->examClassSection->section_name }}
                @else
                    All Sections
                @endif

            </td>

        </tr>


        <tr>

            <th>
                Examination Date
            </th>

            <td>

                {{ $schedule->exam_date?->format('d F Y') ?? '—' }}

            </td>

        </tr>


        <tr>

            <th>
                Start Time
            </th>

            <td>

                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}

            </td>

        </tr>


        <tr>

            <th>
                End Time
            </th>

            <td>

                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}

            </td>

        </tr>


        <tr>

            <th>
                Duration
            </th>

            <td>

                @php

                    $start = \Carbon\Carbon::parse($schedule->start_time);
                    $end = \Carbon\Carbon::parse($schedule->end_time);

                    $minutes = $start->diffInMinutes($end);

                    $hours = intdiv($minutes, 60);
                    $remainingMinutes = $minutes % 60;

                @endphp


                @if($hours > 0)
                    {{ $hours }} hour{{ $hours > 1 ? 's' : '' }}
                @endif

                @if($remainingMinutes > 0)

                    @if($hours > 0)
                        {{ ' ' }}
                    @endif

                    {{ $remainingMinutes }} minute{{ $remainingMinutes > 1 ? 's' : '' }}

                @endif

            </td>

        </tr>


        <tr>

            <th>
                Maximum Marks
            </th>

            <td>
                {{ $schedule->max_marks }}
            </td>

        </tr>


        <tr>

            <th>
                Pass Marks
            </th>

            <td>
                {{ $schedule->pass_marks }}
            </td>

        </tr>


        <tr>

            <th>
                Room / Hall No.
            </th>

            <td>
                {{ $schedule->room_no ?: '—' }}
            </td>

        </tr>


        <tr>

            <th>
                Status
            </th>

            <td>

                <span class="status">
                    {{ ucfirst($schedule->status) }}
                </span>

            </td>

        </tr>

    </table>


    {{-- =====================================================
         INSTRUCTIONS
    ====================================================== --}}

    @if($schedule->instructions)

        <div class="section-heading">
            Instructions
        </div>

        <div class="instructions-box">
            {{ $schedule->instructions }}
        </div>

    @endif


    {{-- =====================================================
         SIGNATURES
    ====================================================== --}}

    <div class="footer">

        <div class="signature">

            <div class="signature-line">
                Class Teacher
            </div>

        </div>


        <div class="signature">

            <div class="signature-line">
                Examination In-charge
            </div>

        </div>


        <div class="signature">

            <div class="signature-line">
                Principal
            </div>

        </div>

    </div>


</div>


</body>

</html>
