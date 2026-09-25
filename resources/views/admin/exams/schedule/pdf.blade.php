
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
            margin: 28px 30px 35px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .page {
            position: relative;
            width: 100%;
        }

        /*
        |--------------------------------------------------------------------------
        | Watermark
        |--------------------------------------------------------------------------
        */

        .watermark {
            position: fixed;
            top: 175px;
            left: 285px;
            width: 260px;
            height: 260px;
            opacity: 0.055;
            z-index: -1;
            text-align: center;
        }

        .watermark img {
            width: 260px;
            height: 260px;
            object-fit: contain;
        }

        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        .school-header {
            width: 100%;
            text-align: center;
            border-bottom: 2px solid #222;
            padding-bottom: 8px;
        }

        .logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 4px;
        }

        .school-name {
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .school-address {
            font-size: 9px;
            margin-top: 3px;
        }

        .school-contact {
            font-size: 8px;
            margin-top: 2px;
        }

        /*
        |--------------------------------------------------------------------------
        | Document title
        |--------------------------------------------------------------------------
        */

        .document-title {
            text-align: center;
            margin-top: 10px;
        }

        .document-title h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .document-title .subtitle {
            margin-top: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Exam information
        |--------------------------------------------------------------------------
        */

        .exam-info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .exam-info td {
            border: 1px solid #555;
            padding: 5px 7px;
            vertical-align: middle;
        }

        .exam-info .label {
            width: 12%;
            font-weight: bold;
            background: #f1f1f1;
        }

        .exam-info .value {
            width: 21%;
        }

        /*
        |--------------------------------------------------------------------------
        | Class heading
        |--------------------------------------------------------------------------
        */

        .class-heading {
            margin-top: 10px;
            padding: 7px 10px;
            border: 1px solid #444;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Timetable
        |--------------------------------------------------------------------------
        */

        .timetable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .timetable th {
            border: 1px solid #333;
            padding: 6px 5px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            background: #eeeeee;
        }

        .timetable td {
            border: 1px solid #555;
            padding: 6px 5px;
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .subject {
            font-weight: bold;
        }

        .supervisor {
            font-size: 8.5px;
        }

        /*
        |--------------------------------------------------------------------------
        | No data
        |--------------------------------------------------------------------------
        */

        .no-data {
            text-align: center;
            padding: 20px;
            border: 1px solid #555;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Instructions
        |--------------------------------------------------------------------------
        */

        .instructions {
            margin-top: 12px;
            border: 1px solid #555;
            padding: 7px 9px;
        }

        .instructions-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .instructions ol {
            margin: 3px 0 0 18px;
            padding: 0;
        }

        .instructions li {
            margin-bottom: 2px;
        }

        /*
        |--------------------------------------------------------------------------
        | Signatures
        |--------------------------------------------------------------------------
        */

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: bottom;
            height: 55px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 75%;
            margin: 0 auto 4px auto;
        }

        .signature-label {
            font-size: 9px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Footer
        |--------------------------------------------------------------------------
        */

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5px;
            color: #555;
        }

        .generated-date {
            margin-top: 8px;
            text-align: right;
            font-size: 7.5px;
            color: #555;
        }
    </style>
</head>

<body>

<div class="page">

    {{-- ========================================================= --}}
    {{-- WATERMARK --}}
    {{-- ========================================================= --}}

    @if($logoData)
        <div class="watermark">
            <img src="{{ $logoData }}" alt="School Logo">
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- SCHOOL HEADER --}}
    {{-- ========================================================= --}}

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

        <div class="school-contact">

            @if($school?->phone)
                Phone: {{ $school->phone }}
            @endif

            @if($school?->email)
                &nbsp;&nbsp; | &nbsp;&nbsp;
                Email: {{ $school->email }}
            @endif

            @if($school?->udise_code)
                &nbsp;&nbsp; | &nbsp;&nbsp;
                UDISE: {{ $school->udise_code }}
            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TITLE --}}
    {{-- ========================================================= --}}

    <div class="document-title">

        <h1>
            Examination Timetable
        </h1>

        <div class="subtitle">
            {{ $exam->exam_name }}
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EXAM INFORMATION --}}
    {{-- ========================================================= --}}

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


    {{-- ========================================================= --}}
    {{-- CLASS FILTER HEADING --}}
    {{-- ========================================================= --}}

    @if($selectedClass)

        <div class="class-heading">

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
            All Classes
        </div>

    @endif


    {{-- ========================================================= --}}
{{-- TIMETABLE --}}
{{-- ========================================================= --}}

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

        {{-- =====================================================
             DATE HEADING
        ====================================================== --}}

        <div class="class-heading">

            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}

            <span style="font-weight: normal;">
                &nbsp; | &nbsp;
                {{ \Carbon\Carbon::parse($date)->format('l') }}
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
                    Marks
                </th>

                <th style="width: 30%;">
                    Teacher
                </th>

            </tr>

            </thead>


            <tbody>

            @foreach($groupedByTime as $time => $timeSchedules)

                @php

                    [$startTime, $endTime] = explode('|', $time);

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

                        {{-- =================================================
                             TIME
                        ================================================== --}}

                        <td class="center">

                            @if($index === 0)

                                <strong>
                                    {{ $formattedStartTime }}
                                    -
                                    {{ $formattedEndTime }}
                                </strong>

                            @endif

                        </td>


                        {{-- =================================================
                             CLASS
                        ================================================== --}}

                        <td class="center">

                            <strong>
                                {{ $className }}

                                @if($section)
                                    -{{ $section }}
                                @endif
                            </strong>

                        </td>


                        {{-- =================================================
                             SUBJECT
                        ================================================== --}}

                        <td class="subject">

                            {{ $subjectName }}

                            @if($subjectCode)

                                <br>

                                <span style="font-size: 7.5px;">
                                    {{ $subjectCode }}
                                </span>

                            @endif

                        </td>


                        {{-- =================================================
                             MARKS
                        ================================================== --}}

                        <td class="center">

                            <strong>
                                {{ $schedule->maximum_marks }}
                            </strong>

                        </td>


                        {{-- =================================================
                             TEACHER
                        ================================================== --}}

                        <td class="center supervisor">

                            {{ $teacherName }}

                            @if($schedule->teacher?->designation)

                                <br>

                                <span style="font-size: 7.5px;">
                                    {{ $schedule->teacher->designation }}
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach


                {{-- =====================================================
                     SESSION SEPARATOR
                ====================================================== --}}

                @if(!$loop->last)

                    <tr class="session-separator">
                        <td colspan="5"></td>
                    </tr>

                @endif

            @endforeach

            </tbody>

        </table>


        {{-- =====================================================
             SPACE BETWEEN DATES
        ====================================================== --}}

        @if(!$loop->last)

            <div style="height: 8px;"></div>

        @endif

    @endforeach


@else

    <div class="no-data">
        No timetable entries found.
    </div>

@endif

    {{-- ========================================================= --}}
    {{-- INSTRUCTIONS --}}
    {{-- ========================================================= --}}

    <div class="instructions">

        <div class="instructions-title">
            Instructions:
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


    {{-- ========================================================= --}}
    {{-- SIGNATURES --}}
    {{-- ========================================================= --}}

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


    <div class="generated-date">

        Generated on:
        {{ now()->format('d M Y, h:i A') }}

    </div>

</div>


{{-- ============================================================= --}}
{{-- FOOTER --}}
{{-- ============================================================= --}}

<div class="footer">

    {{ $school?->school_name ?? 'School Management System' }}

    &nbsp; | &nbsp;

    Examination Timetable

</div>

</body>
</html>
