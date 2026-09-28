<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<title>
    {{ $exam->exam_name }} - Exam Timetable
</title>

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    @page {
        size: A4 landscape;
        margin: 12mm;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: DejaVu Sans, Arial, sans-serif;
        color: #202938;
        background: #ffffff;
        font-size: 11px;
    }


    /* =========================================================
       CENTER FAINT WATERMARK
    ========================================================= */

    .watermark {
        position: fixed;

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
       MAIN CONTAINER
    ========================================================= */

    .page {
        position: relative;
        width: 100%;
    }


    /* =========================================================
       SCHOOL HEADER
    ========================================================= */

    .header {
        position: relative;

        text-align: center;

        padding: 10px 15px 11px;

        margin-bottom: 10px;

        border: 1px solid #cfd8e3;

        border-radius: 8px;

        background: #ffffff;
    }


    .logo {
        width: 65px;
        height: 65px;

        object-fit: contain;

        margin-bottom: 4px;
    }


    .school-name {
        color: #172033;

        font-size: 20px;

        font-weight: bold;

        text-transform: uppercase;

        letter-spacing: 0.6px;

        margin-bottom: 3px;
    }


    .school-address {
        color: #5f6b7a;

        font-size: 9.5px;

        line-height: 1.5;

        margin-bottom: 5px;
    }


    .school-contact {
        color: #6b7788;

        font-size: 8px;

        margin-top: 3px;
    }


    .header-line {
        width: 50%;

        margin: 7px auto 0;

        border-top: 2px solid #1677f0;
    }


    /* =========================================================
       TITLE
    ========================================================= */

    .title {
        display: inline-block;

        color: #0d5fc4;

        background: #eef6ff;

        border: 1px solid #c7ddf7;

        border-radius: 20px;

        padding: 4px 15px;

        font-size: 9px;

        font-weight: bold;

        letter-spacing: 0.8px;

        margin-top: 7px;
    }


    .exam-info {
        margin-top: 5px;

        color: #596577;

        font-size: 9px;
    }


    .exam-info strong {
        color: #172033;

        font-size: 10px;
    }


    /* =========================================================
       FILTER INFORMATION
    ========================================================= */

    .filter-info {
        text-align: center;

        margin: 8px 0 12px;

        padding: 6px 10px;

        color: #415065;

        background: #f7f9fc;

        border: 1px solid #d8e0e9;

        border-radius: 5px;

        font-size: 9px;
    }


    .filter-label {
        color: #6c7888;

        font-weight: normal;
    }


    .filter-value {
        color: #172033;

        font-weight: bold;
    }


    /* =========================================================
       DATE BLOCK
    ========================================================= */

    .date-block {
        margin-top: 10px;

        page-break-inside: avoid;
    }


    .date-header {
        position: relative;

        background: #eef6ff;

        border: 1px solid #bfd8f5;

        border-left: 4px solid #1677f0;

        border-radius: 5px;

        padding: 7px 11px;

        color: #164f91;

        font-size: 11px;

        font-weight: bold;
    }


    .date-weekday {
        color: #687589;

        font-weight: normal;
    }


    .paper-count {
        float: right;

        color: #526174;

        font-size: 8px;

        font-weight: normal;

        padding-top: 2px;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    table {
        width: 100%;

        border-collapse: collapse;

        margin-bottom: 10px;
    }


    th {
        color: #ffffff;

        background: #1677f0;

        border: 1px solid #1268d4;

        padding: 7px 8px;

        text-align: center;

        font-size: 8.5px;

        font-weight: bold;

        text-transform: uppercase;

        letter-spacing: 0.2px;
    }


    td {
        border: 1px solid #d1d9e3;

        padding: 7px 8px;

        vertical-align: middle;

        background: rgba(255, 255, 255, 0.96);

        font-size: 9px;
    }


    tbody tr:nth-child(even):not(.session-separator) td {
        background: rgba(248, 250, 253, 0.96);
    }


    .time {
        width: 17%;

        text-align: center;

        white-space: nowrap;

        color: #183d68;

        font-weight: 600;
    }


    .class {
        width: 12%;

        text-align: center;

        color: #26364a;

        font-weight: 600;
    }


    .subject {
        width: 31%;

        color: #202938;

        font-weight: 600;
    }


    .marks {
        width: 10%;

        text-align: center;

        color: #227345;

        font-weight: bold;
    }


    .teacher {
        width: 30%;

        color: #3e4b5d;

        text-align: center;
    }


    /* =========================================================
       BADGES
    ========================================================= */

    .class-badge {
        display: inline-block;

        padding: 3px 7px;

        background: #f0f4f8;

        border: 1px solid #d5dee8;

        border-radius: 12px;

        color: #354457;

        font-weight: bold;
    }


    .marks-badge {
        display: inline-block;

        min-width: 38px;

        padding: 3px 8px;

        background: #edf8f2;

        border: 1px solid #c8e5d3;

        border-radius: 12px;

        color: #227345;

        font-weight: bold;
    }


    .subject-code {
        display: block;

        margin-top: 2px;

        color: #7a8797;

        font-size: 7px;

        font-weight: normal;
    }


    .teacher-name {
        color: #344054;

        font-weight: bold;
    }


    .teacher-designation {
        margin-top: 2px;

        color: #7a8797;

        font-size: 7px;
    }


    /* =========================================================
       TIME EMPTY
    ========================================================= */

    .time-empty {
        color: transparent;
    }


    /* =========================================================
       SESSION SEPARATOR
    ========================================================= */

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
        margin-top: 15px;

        padding: 28px;

        border: 1px dashed #b9c6d5;

        border-radius: 7px;

        background: #f8fafc;

        color: #657184;

        text-align: center;

        font-size: 10px;

        font-weight: bold;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .footer {
        margin-top: 12px;

        padding-top: 5px;

        border-top: 1px solid #dce2e8;

        text-align: right;

        color: #7a8594;

        font-size: 7.5px;
    }


    .footer-school {
        float: left;

        color: #667386;
    }


    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        body {
            background: #ffffff;
        }

        .watermark {
            display: block;
        }

    }

</style>


</head>

<body>

<div class="page">


{{-- =========================================================
     FAINT CENTER SCHOOL LOGO WATERMARK
========================================================== --}}

@if(!empty($logoData))

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

<div class="header">


    @if(!empty($logoData))

        <img
            src="{{ $logoData }}"
            class="logo"
            alt="School Logo"
        >

    @endif


    @if($school?->school_name)

        <div class="school-name">

            {{ $school->school_name }}

        </div>

    @endif


    @if(
        $school?->address ||
        $school?->city ||
        $school?->district ||
        $school?->state
    )

        <div class="school-address">

            {{ $school?->address }}

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


    @if(
        $school?->phone ||
        $school?->email ||
        $school?->udise_code
    )

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


    <div class="header-line"></div>


    {{-- =====================================================
         TITLE
    ====================================================== --}}

    <div class="title">

        EXAMINATION TIMETABLE

    </div>


    {{-- =====================================================
         EXAM INFORMATION
    ====================================================== --}}

    <div class="exam-info">

        <strong>
            {{ $exam->exam_name }}
        </strong>


        @if($exam->exam_type)

            &nbsp; | &nbsp;

            {{ $exam->exam_type }}

        @endif


        @if($exam->academic_year)

            &nbsp; | &nbsp;

            Academic Year:
            {{ $exam->academic_year }}

        @endif

    </div>

</div>


{{-- =========================================================
     FILTER INFORMATION
========================================================== --}}

@if($selectedClass || request('exam_date'))

    <div class="filter-info">


        @if($selectedClass)

            <span class="filter-label">
                Class:
            </span>

            <span class="filter-value">

                {{ $selectedClass->class_name }}

                @if($selectedClass->section)

                    - {{ $selectedClass->section }}

                @endif

            </span>

        @endif


        @if(request('exam_date'))

            @if($selectedClass)

                &nbsp; | &nbsp;

            @endif


            <span class="filter-label">
                Date:
            </span>

            <span class="filter-value">

                {{ \Illuminate\Support\Carbon::parse(
                    request('exam_date')
                )->format('d F Y') }}

            </span>

        @endif


    </div>

@endif


{{-- =========================================================
     TIMETABLE
========================================================== --}}

@php

    $groupedByDate = $timetables->groupBy(function ($schedule) {

        return \Illuminate\Support\Carbon::parse(
            $schedule->exam_date
        )->format('Y-m-d');

    });

@endphp


@if($groupedByDate->isEmpty())


    <div class="no-data">

        No examination timetable entries are available.

    </div>


@else


    @foreach($groupedByDate as $date => $dateSchedules)


        {{-- =================================================
             DATE BLOCK
        ================================================== --}}

        <div class="date-block">


            {{-- =================================================
                 DATE HEADER
            ================================================== --}}

            <div class="date-header">


                {{ \Illuminate\Support\Carbon::parse(
                    $date
                )->format('d F Y') }}


                <span class="date-weekday">

                    &nbsp; - &nbsp;

                    {{ \Illuminate\Support\Carbon::parse(
                        $date
                    )->format('l') }}

                </span>


                <span class="paper-count">

                    {{ $dateSchedules->count() }}

                    {{ $dateSchedules->count() === 1
                        ? 'Paper'
                        : 'Papers'
                    }}

                </span>


            </div>


            @php

                $groupedByTime = $dateSchedules->groupBy(
                    function ($schedule) {

                        return

                            \Illuminate\Support\Carbon::parse(
                                $schedule->start_time
                            )->format('H:i')

                            . '|'

                            .

                            \Illuminate\Support\Carbon::parse(
                                $schedule->end_time
                            )->format('H:i');

                    }
                );

            @endphp


            {{-- =================================================
                 TIMETABLE TABLE
            ================================================== --}}

            <table>


                <thead>

                    <tr>

                        <th class="time">
                            Time
                        </th>

                        <th class="class">
                            Class
                        </th>

                        <th class="subject">
                            Subject
                        </th>

                        <th class="marks">
                            Marks
                        </th>

                        <th class="teacher">
                            Teacher / Supervisor
                        </th>

                    </tr>

                </thead>


                <tbody>


                @foreach($groupedByTime as $time => $timeSchedules)


                    @php

                        [$startTime, $endTime] =
                            explode('|', $time);

                    @endphp


                    @foreach($timeSchedules as $index => $schedule)


                        <tr>


                            {{-- =================================
                                 TIME
                            ================================== --}}

                            <td class="time">


                                @if($index === 0)


                                    {{ \Illuminate\Support\Carbon::createFromFormat(
                                        'H:i',
                                        $startTime
                                    )->format('h:i A') }}


                                    <br>

                                    <span style="
                                        color:#8793a3;
                                        font-size:7px;
                                        font-weight:normal;
                                    ">
                                        to
                                    </span>

                                    <br>


                                    {{ \Illuminate\Support\Carbon::createFromFormat(
                                        'H:i',
                                        $endTime
                                    )->format('h:i A') }}


                                @else


                                    <span class="time-empty">
                                        —
                                    </span>


                                @endif


                            </td>


                            {{-- =================================
                                 CLASS
                            ================================== --}}

                            <td class="class">


                                <span class="class-badge">


                                    {{ optional(
                                        $schedule->schoolClass
                                    )->class_name }}


                                    @if(
                                        optional(
                                            $schedule->schoolClass
                                        )->section
                                    )

                                        -
                                        {{ optional(
                                            $schedule->schoolClass
                                        )->section }}

                                    @endif


                                </span>


                            </td>


                            {{-- =================================
                                 SUBJECT
                            ================================== --}}

                            <td class="subject">


                                {{ optional(
                                    optional(
                                        $schedule->examSubject
                                    )->subject
                                )->subject_name ?? '—' }}


                                @if(
                                    optional(
                                        optional(
                                            $schedule->examSubject
                                        )->subject
                                    )->subject_code
                                )

                                    <span class="subject-code">

                                        Code:
                                        {{ optional(
                                            optional(
                                                $schedule->examSubject
                                            )->subject
                                        )->subject_code }}

                                    </span>

                                @endif


                            </td>


                            {{-- =================================
                                 MARKS
                            ================================== --}}

                            <td class="marks">


                                <span class="marks-badge">

                                    {{ $schedule->maximum_marks }}

                                </span>


                            </td>


                            {{-- =================================
                                 TEACHER
                            ================================== --}}

                            <td class="teacher">


                                @if($schedule->teacher)


                                    <div class="teacher-name">

                                        {{ trim(
                                            $schedule->teacher->first_name
                                            . ' ' .
                                            $schedule->teacher->last_name
                                        ) }}

                                    </div>


                                    @if(
                                        $schedule->teacher->designation
                                    )

                                        <div class="teacher-designation">

                                            {{ $schedule->teacher->designation }}

                                        </div>

                                    @endif


                                @else


                                    <span style="color:#8a95a5;">
                                        Not Assigned
                                    </span>


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


        </div>


    @endforeach


@endif


{{-- =========================================================
     FOOTER
========================================================== --}}

<div class="footer">


    <span class="footer-school">

        {{ $school?->school_name ?? 'School Management System' }}

    </span>


    Generated on:

    {{ now()->format('d F Y h:i A') }}


</div>


</div>

{{-- =========================================================
AUTO PRINT
========================================================== --}}

<script>

    window.onload = function () {

        window.print();

    };

</script>

</body>

</html>
