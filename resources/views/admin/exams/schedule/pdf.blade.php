<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">


<title>
    {{ $exam->exam_name }} - Examination Timetable
</title>

<style>
    @page {
        size: A4 landscape;
        margin: 25px 28px 32px 28px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
        color: #202938;
        background: #ffffff;
    }

    .page {
        position: relative;
        width: 100%;
    }

    /* =========================================================
       WATERMARK
    ========================================================= */

    .watermark {
        position: fixed;

        /* Exact center of A4 landscape page */
        top: 50%;
        left: 50%;

        width: 300px;
        height: 300px;

        margin-left: -150px;
        margin-top: -150px;

        opacity: 0.045;

        z-index: -1;

        text-align: center;
    }

    .watermark img {
        width: 300px;
        height: 300px;
        object-fit: contain;
        display: block;
    }

    /* =========================================================
       SCHOOL HEADER
    ========================================================= */

    .school-header {
        width: 100%;
        border: 1px solid #cfd8e3;
        border-radius: 8px;
        padding: 12px 15px 11px 15px;
        text-align: center;
        background: #ffffff;
    }

    .logo {
        width: 66px;
        height: 66px;
        object-fit: contain;
        margin-bottom: 4px;
    }

    .school-name {
        color: #172033;
        font-size: 20px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .school-address {
        color: #4f5c6d;
        font-size: 8.5px;
        margin-top: 4px;
    }

    .school-contact {
        color: #647184;
        font-size: 7.8px;
        margin-top: 4px;
    }

    .school-divider {
        width: 55%;
        margin: 7px auto 0 auto;
        border-top: 2px solid #1677f0;
    }

    /* =========================================================
       DOCUMENT TITLE
    ========================================================= */

    .document-title {
        text-align: center;
        margin-top: 10px;
    }

    .title-label {
        display: inline-block;
        padding: 4px 14px;
        border-radius: 20px;
        background: #eef6ff;
        color: #0d5fc4;
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.7px;
    }

    .document-title h1 {
        margin: 5px 0 0 0;
        color: #172033;
        font-size: 17px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .document-title .subtitle {
        margin-top: 3px;
        color: #0d5fc4;
        font-size: 10px;
        font-weight: bold;
    }

    /* =========================================================
       EXAM INFORMATION
    ========================================================= */

    .exam-info {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
        border: 1px solid #cfd8e3;
        border-radius: 7px;
        overflow: hidden;
    }

    .exam-info td {
        border-right: 1px solid #cfd8e3;
        border-bottom: 1px solid #cfd8e3;
        padding: 5px 7px;
        vertical-align: middle;
    }

    .exam-info tr:last-child td {
        border-bottom: none;
    }

    .exam-info td:last-child {
        border-right: none;
    }

    .exam-info .label {
        width: 12%;
        color: #4b596b;
        background: #f6f8fb;
        font-weight: bold;
        font-size: 7.8px;
        text-transform: uppercase;
    }

    .exam-info .value {
        width: 21%;
        color: #172033;
        font-size: 8.5px;
        font-weight: bold;
    }

    /* =========================================================
       CLASS / DATE HEADING
    ========================================================= */

    .class-heading {
        margin-top: 10px;
        padding: 6px 10px;
        border: 1px solid #b9d5f7;
        border-left: 4px solid #1677f0;
        border-radius: 5px;
        background: #eef6ff;
        color: #164f91;
        font-size: 9.5px;
        font-weight: bold;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .date-icon {
        display: inline-block;
        width: 17px;
        height: 17px;
        line-height: 17px;
        margin-right: 5px;
        border-radius: 50%;
        background: #1677f0;
        color: #ffffff;
        text-align: center;
        font-size: 8px;
        font-weight: bold;
    }

    .date-weekday {
        color: #647184;
        font-weight: normal;
        text-transform: none;
    }

    /* =========================================================
       TIMETABLE
    ========================================================= */

    .timetable {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 6px;
        border: 1px solid #cfd8e3;
        border-radius: 6px;
        overflow: hidden;
    }

    .timetable th {
        border-right: 1px solid #d7e0ea;
        border-bottom: 1px solid #c4d1df;
        padding: 6px 5px;
        color: #ffffff;
        background: #1677f0;
        font-size: 8px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        text-transform: uppercase;
        letter-spacing: 0.25px;
    }

    .timetable th:last-child {
        border-right: none;
    }

    .timetable td {
        border-right: 1px solid #cfd8e3;
        border-bottom: 1px solid #cfd8e3;
        padding: 6px 5px;
        vertical-align: middle;
        background: #ffffff;
    }

    .timetable tr:last-child td {
        border-bottom: none;
    }

    .timetable td:last-child {
        border-right: none;
    }

    .timetable tr:nth-child(even):not(.session-separator) td {
        background: #fbfcfe;
    }

    .center {
        text-align: center;
    }

    .time-cell {
        color: #183d68;
        font-weight: bold;
        font-size: 8.5px;
        line-height: 1.4;
    }

    .class-cell {
        color: #172033;
        font-weight: bold;
        font-size: 8.5px;
    }

    .class-badge {
        display: inline-block;
        padding: 3px 7px;
        border-radius: 12px;
        background: #eef3f8;
        border: 1px solid #d5dee8;
        color: #354457;
        font-weight: bold;
    }

    .subject {
        color: #202938;
        font-weight: bold;
        font-size: 9px;
    }

    .subject-code {
        display: inline-block;
        margin-top: 2px;
        color: #748195;
        font-size: 7px;
        font-weight: normal;
    }

    .marks-badge {
        display: inline-block;
        min-width: 38px;
        padding: 3px 7px;
        border-radius: 12px;
        background: #edf8f2;
        border: 1px solid #c7e7d4;
        color: #227345;
        font-weight: bold;
    }

    .teacher-name {
        color: #334155;
        font-size: 8px;
        font-weight: bold;
    }

    .teacher-designation {
        color: #7a8797;
        font-size: 7px;
        margin-top: 2px;
    }

    .session-separator td {
        height: 4px;
        padding: 0;
        border: none;
        background: #eef2f7 !important;
    }

    /* =========================================================
       NO DATA
    ========================================================= */

    .no-data {
        margin-top: 10px;
        padding: 22px;
        border: 1px dashed #b8c5d4;
        border-radius: 7px;
        background: #f8fafc;
        color: #647184;
        text-align: center;
        font-weight: bold;
        font-size: 9px;
    }

    /* =========================================================
       INSTRUCTIONS
    ========================================================= */

    .instructions {
        margin-top: 11px;
        padding: 7px 10px;
        border: 1px solid #cbd6e2;
        border-left: 4px solid #1677f0;
        border-radius: 5px;
        background: #f8fafc;
    }

    .instructions-title {
        color: #172033;
        margin-bottom: 3px;
        font-size: 8.5px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .instructions ol {
        margin: 3px 0 0 16px;
        padding: 0;
    }

    .instructions li {
        margin-bottom: 2px;
        color: #526071;
        font-size: 7.8px;
    }

    /* =========================================================
       SIGNATURES
    ========================================================= */

    .signature-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 22px;
    }

    .signature-table td {
        width: 33.33%;
        height: 50px;
        text-align: center;
        vertical-align: bottom;
    }

    .signature-line {
        width: 68%;
        margin: 0 auto 4px auto;
        border-top: 1px solid #536174;
    }

    .signature-label {
        color: #344054;
        font-size: 8px;
        font-weight: bold;
    }

    /* =========================================================
       GENERATED DATE
    ========================================================= */

    .generated-date {
        margin-top: 5px;
        color: #7a8594;
        text-align: right;
        font-size: 7px;
    }

    /* =========================================================
       FOOTER
    ========================================================= */

    .footer {
        position: fixed;
        bottom: -18px;
        left: 0;
        right: 0;
        color: #7a8594;
        text-align: center;
        font-size: 7px;
    }

    .footer-line {
        display: inline-block;
        width: 35px;
        margin: 0 5px;
        border-top: 1px solid #cbd5e1;
        vertical-align: middle;
    }

</style>


</head>

<body>

<div class="page">


{{-- =========================================================
     CENTER FAINT SCHOOL LOGO WATERMARK
========================================================== --}}

@if($logoData)

    <div class="watermark">

        <img
            src="{{ $logoData }}"
            alt="School Logo Watermark"
        >

    </div>

@endif


{{-- =========================================================
     SCHOOL HEADER
========================================================== --}}

<div class="school-header">

    @if($logoData)

        <img
            src="{{ $logoData }}"
            class="logo"
            alt="School Logo"
        >

    @endif


    <div class="school-name">

        {{ $school?->school_name ?? 'School Name' }}

    </div>


    @if($school?->address)

        <div class="school-address">

            {{ $school->address }}

            @if($school?->city)
                , {{ $school->city }}
            @endif

            @if($school?->district)
                , {{ $school->district }}
            @endif

            @if($school?->state)
                , {{ $school->state }}
            @endif

            @if($school?->pincode)
                - {{ $school->pincode }}
            @endif

        </div>

    @endif


    @if($school?->phone || $school?->email || $school?->udise_code)

        <div class="school-contact">

            @if($school?->phone)

                Phone:
                {{ $school->phone }}

            @endif


            @if($school?->email)

                &nbsp;&nbsp; | &nbsp;&nbsp;

                Email:
                {{ $school->email }}

            @endif


            @if($school?->udise_code)

                &nbsp;&nbsp; | &nbsp;&nbsp;

                UDISE:
                {{ $school->udise_code }}

            @endif

        </div>

    @endif


    <div class="school-divider"></div>

</div>


{{-- =========================================================
     DOCUMENT TITLE
========================================================== --}}

<div class="document-title">

    <span class="title-label">

        Official Examination Document

    </span>


    <h1>

        Examination Timetable

    </h1>


    <div class="subtitle">

        {{ $exam->exam_name }}

    </div>

</div>


{{-- =========================================================
     EXAM INFORMATION
========================================================== --}}

<table class="exam-info">

    <tr>

        <td class="label">
            Academic Year
        </td>

        <td class="value">
            {{ $exam->academic_year }}
        </td>


        <td class="label">
            Examination Type
        </td>

        <td class="value">
            {{ $exam->exam_type }}
        </td>


        <td class="label">
            Examination Period
        </td>

        <td class="value">

            {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}

            @if($exam->end_date)

                -

                {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}

            @endif

        </td>

    </tr>


    <tr>

        <td class="label">
            Status
        </td>

        <td class="value">

            {{ ucfirst($exam->status) }}

        </td>


        <td class="label">
            Total Papers
        </td>

        <td class="value">

            {{ $schedules->count() }}

        </td>


        <td class="label">
            Generated On
        </td>

        <td class="value">

            {{ now()->format('d M Y') }}

        </td>

    </tr>

</table>


{{-- =========================================================
     CLASS HEADING
========================================================== --}}

@if($selectedClass)

    <div class="class-heading">

        <span class="date-icon">
            C
        </span>

        Class:
        {{ $selectedClass->class_name }}

        @if($selectedClass->section)

            &nbsp; | &nbsp;

            Section:
            {{ $selectedClass->section }}

        @endif

    </div>

@else

    <div class="class-heading">

        <span class="date-icon">
            A
        </span>

        All Classes

    </div>

@endif


{{-- =========================================================
     TIMETABLE
========================================================== --}}

@if($schedules->count())

    @php

        /*
        |--------------------------------------------------------------------------
        | Group schedules by examination date
        |--------------------------------------------------------------------------
        */

        $groupedByDate = $schedules->groupBy(function ($schedule) {

            return \Carbon\Carbon::parse(
                $schedule->exam_date
            )->format('Y-m-d');

        });

    @endphp


    @foreach($groupedByDate as $date => $dateSchedules)


        {{-- =================================================
             DATE HEADING
        ================================================== --}}

        <div class="class-heading">

            <span class="date-icon">
                D
            </span>


            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}


            <span class="date-weekday">

                &nbsp; | &nbsp;

                {{ \Carbon\Carbon::parse($date)->format('l') }}

            </span>


            <span style="float: right; font-weight: normal;">

                {{ $dateSchedules->count() }}

                {{ $dateSchedules->count() === 1 ? 'Paper' : 'Papers' }}

            </span>

        </div>


        @php

            /*
            |--------------------------------------------------------------------------
            | Group by exact start/end time
            |--------------------------------------------------------------------------
            */

            $groupedByTime = $dateSchedules->groupBy(function ($schedule) {

                return

                    \Carbon\Carbon::parse(
                        $schedule->start_time
                    )->format('H:i')

                    . '|'

                    .

                    \Carbon\Carbon::parse(
                        $schedule->end_time
                    )->format('H:i');

            });

        @endphp


        <table class="timetable">

            <thead>

                <tr>

                    <th style="width: 15%;">
                        Time
                    </th>

                    <th style="width: 14%;">
                        Class
                    </th>

                    <th style="width: 31%;">
                        Subject
                    </th>

                    <th style="width: 10%;">
                        Maximum Marks
                    </th>

                    <th style="width: 30%;">
                        Teacher / Supervisor
                    </th>

                </tr>

            </thead>


            <tbody>


                @foreach($groupedByTime as $time => $timeSchedules)

                    @php

                        [$startTime, $endTime] = explode(
                            '|',
                            $time
                        );


                        $formattedStartTime =

                            \Carbon\Carbon::createFromFormat(
                                'H:i',
                                $startTime
                            )->format('h:i A');


                        $formattedEndTime =

                            \Carbon\Carbon::createFromFormat(
                                'H:i',
                                $endTime
                            )->format('h:i A');

                    @endphp


                    @foreach($timeSchedules as $index => $schedule)


                        @php

                            $teacherName = $schedule->teacher

                                ? trim(
                                    $schedule->teacher->first_name .
                                    ' ' .
                                    $schedule->teacher->last_name
                                )

                                : 'Not Assigned';


                            $className =
                                $schedule->schoolClass?->class_name
                                ?? '-';


                            $section =
                                $schedule->schoolClass?->section
                                ?? null;


                            $subjectName =
                                $schedule->examSubject?->subject?->subject_name
                                ?? '-';


                            $subjectCode =
                                $schedule->examSubject?->subject?->subject_code
                                ?? null;

                        @endphp


                        <tr>


                            {{-- =============================================
                                 TIME
                            ============================================== --}}

                            <td class="center time-cell">

                                @if($index === 0)

                                    {{ $formattedStartTime }}

                                    <br>

                                    <span style="
                                        font-weight: normal;
                                        color: #718096;
                                    ">
                                        to
                                    </span>

                                    <br>

                                    {{ $formattedEndTime }}

                                @endif

                            </td>


                            {{-- =============================================
                                 CLASS
                            ============================================== --}}

                            <td class="center class-cell">

                                <span class="class-badge">

                                    {{ $className }}

                                    @if($section)
                                        -{{ $section }}
                                    @endif

                                </span>

                            </td>


                            {{-- =============================================
                                 SUBJECT
                            ============================================== --}}

                            <td class="subject">

                                {{ $subjectName }}

                                @if($subjectCode)

                                    <br>

                                    <span class="subject-code">

                                        Code:
                                        {{ $subjectCode }}

                                    </span>

                                @endif

                            </td>


                            {{-- =============================================
                                 MARKS
                            ============================================== --}}

                            <td class="center">

                                <span class="marks-badge">

                                    {{ $schedule->maximum_marks }}

                                </span>

                            </td>


                            {{-- =============================================
                                 TEACHER
                            ============================================== --}}

                            <td class="center">

                                <div class="teacher-name">

                                    {{ $teacherName }}

                                </div>


                                @if($schedule->teacher?->designation)

                                    <div class="teacher-designation">

                                        {{ $schedule->teacher->designation }}

                                    </div>

                                @endif

                            </td>

                        </tr>


                    @endforeach


                    {{-- =============================================
                         SESSION SEPARATOR
                    ============================================== --}}

                    @if(!$loop->last)

                        <tr class="session-separator">

                            <td colspan="5"></td>

                        </tr>

                    @endif


                @endforeach


            </tbody>

        </table>


        {{-- =================================================
             SPACE BETWEEN DATES
        ================================================== --}}

        @if(!$loop->last)

            <div style="height: 6px;"></div>

        @endif


    @endforeach


@else


    <div class="no-data">

        No timetable entries found.

    </div>


@endif


{{-- =========================================================
     INSTRUCTIONS
========================================================== --}}

<div class="instructions">

    <div class="instructions-title">

        Examination Instructions

    </div>


    <ol>

        <li>

            Students should report to the examination room at least
            15 minutes before the scheduled examination.

        </li>


        <li>

            Students must carry the required examination materials
            and follow the instructions of the examination supervisor.

        </li>


        <li>

            Supervisors are requested to report before the commencement
            of the examination session.

        </li>


        <li>

            Any change in the examination schedule will be communicated
            separately by the school administration.

        </li>

    </ol>

</div>


{{-- =========================================================
     SIGNATURES
========================================================== --}}

<table class="signature-table">

    <tr>

        <td>

            <div class="signature-line"></div>

            <div class="signature-label">

                Exam In-Charge

            </div>

        </td>


        <td>

            <div class="signature-line"></div>

            <div class="signature-label">

                Examination Coordinator

            </div>

        </td>


        <td>

            <div class="signature-line"></div>

            <div class="signature-label">

                Principal / Headmaster

            </div>

        </td>

    </tr>

</table>


{{-- =========================================================
     GENERATED DATE
========================================================== --}}

<div class="generated-date">

    Generated on:

    {{ now()->format('d M Y, h:i A') }}

</div>


</div>

{{-- =============================================================
FOOTER
============================================================== --}}

<div class="footer">


{{ $school?->school_name ?? 'School Management System' }}

<span class="footer-line"></span>

Examination Timetable

</div>

</body>

</html>
