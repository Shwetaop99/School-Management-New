<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        {{ $exam->exam_name }} - Examination Timetable
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 12mm 15mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #202938;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9px;
        }

        .page {
            width: 100%;
            position: relative;
        }

        /* =========================
           WATERMARK
        ========================== */

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
        }

        /* =========================
           SCHOOL HEADER
        ========================== */

        .school-header {
            width: 100%;
            text-align: center;
            border: 1px solid #cfd8e3;
            border-radius: 7px;
            padding: 9px 12px 10px;
            background: #ffffff;
        }

        .logo {
            width: 60px;
            height: 60px;
            object-fit: contain;
            margin-bottom: 3px;
        }

        .school-name {
            color: #172033;
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .school-address {
            margin-top: 3px;
            color: #526071;
            font-size: 8px;
            line-height: 1.4;
        }

        .school-contact {
            margin-top: 3px;
            color: #6c7888;
            font-size: 7.5px;
        }

        .school-divider {
            width: 50%;
            margin: 6px auto 0;
            border-top: 2px solid #1677f0;
        }

        /* =========================
           TITLE
        ========================== */

        .document-title {
            text-align: center;
            margin-top: 7px;
        }

        .title-label {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            background: #eef6ff;
            color: #0d5fc4;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .document-title h1 {
            margin: 4px 0 0;
            font-size: 16px;
            color: #172033;
            text-transform: uppercase;
        }

        .exam-name {
            margin-top: 2px;
            color: #0d5fc4;
            font-size: 9px;
            font-weight: bold;
        }

        /* =========================
           EXAM INFORMATION
        ========================== */

        .exam-info {
            width: 100%;
            margin-top: 7px;
            border-collapse: collapse;
        }

        .exam-info td {
            border: 1px solid #cfd8e3;
            padding: 5px 7px;
        }

        .exam-info .label {
            width: 12%;
            background: #f5f7fa;
            color: #596577;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .exam-info .value {
            width: 21%;
            color: #172033;
            font-size: 8px;
            font-weight: bold;
        }

        /* =========================
           DATE
        ========================== */

        .date-block {
            margin-top: 9px;
            page-break-inside: avoid;
        }

        .date-header {
            padding: 6px 9px;
            border: 1px solid #bfd8f5;
            border-left: 4px solid #1677f0;
            border-radius: 5px 5px 0 0;
            background: #eef6ff;
            color: #164f91;
            font-size: 9px;
            font-weight: bold;
        }

        .date-weekday {
            color: #687589;
            font-weight: normal;
        }

        .paper-count {
            float: right;
            color: #687589;
            font-size: 7px;
            font-weight: normal;
        }

        /* =========================
           TIMETABLE
        ========================== */

        .timetable {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        .timetable thead {
            display: table-header-group;
        }

        .timetable tr {
            page-break-inside: avoid;
        }

        .timetable th {
            padding: 5px 6px;
            border: 1px solid #1268d4;
            background: #1677f0;
            color: #ffffff;
            font-size: 7.5px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .timetable td {
            padding: 5px 6px;
            border: 1px solid #cfd8e3;
            background: #ffffff;
            vertical-align: middle;
        }

        .timetable tbody tr:nth-child(even) td {
            background: #fbfcfe;
        }

        .center {
            text-align: center;
        }

        .time {
            width: 16%;
            text-align: center;
            color: #183d68;
            font-weight: bold;
            white-space: nowrap;
        }

        .class {
            width: 14%;
            text-align: center;
            font-weight: bold;
        }

        .subject {
            width: 30%;
            font-weight: bold;
        }

        .marks {
            width: 10%;
            text-align: center;
        }

        .duration {
            width: 10%;
            text-align: center;
        }

        .teacher {
            width: 20%;
            text-align: center;
        }

        .class-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            background: #f0f4f8;
            border: 1px solid #d5dee8;
            color: #354457;
        }

        .marks-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            background: #edf8f2;
            border: 1px solid #c8e5d3;
            color: #227345;
            font-weight: bold;
        }

        .subject-code {
            display: block;
            margin-top: 2px;
            color: #7a8797;
            font-size: 6.5px;
            font-weight: normal;
        }

        .teacher-name {
            color: #344054;
            font-weight: bold;
        }

        .teacher-designation {
            margin-top: 2px;
            color: #7a8797;
            font-size: 6.5px;
        }

        .not-assigned {
            color: #8a95a5;
            font-style: italic;
        }

        /* =========================
           NO DATA
        ========================== */

        .no-data {
            margin-top: 12px;
            padding: 25px;
            border: 1px dashed #b9c6d5;
            border-radius: 6px;
            background: #f8fafc;
            color: #657184;
            text-align: center;
            font-weight: bold;
        }

        /* =========================
           FOOTER
        ========================== */

        .footer {
            margin-top: 12px;
            padding-top: 5px;
            border-top: 1px solid #dce2e8;
            color: #7a8594;
            font-size: 7px;
            text-align: right;
        }

        .footer-school {
            float: left;
            color: #667386;
        }

        /* =========================
           PRINT
        ========================== */

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: #ffffff;
            }
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================
         WATERMARK
    ========================== --}}
    @if(!empty($logoData))
        <div class="watermark">
            <img
                src="{{ $logoData }}"
                alt="School Logo"
            >
        </div>
    @endif


    {{-- =========================
         SCHOOL HEADER
    ========================== --}}
    <div class="school-header">

        @if(!empty($logoData))
            <img
                src="{{ $logoData }}"
                class="logo"
                alt="School Logo"
            >
        @endif

        <div class="school-name">
            {{ $school?->school_name ?? 'School Name' }}
        </div>

        @if(
            $school?->address ||
            $school?->city ||
            $school?->district ||
            $school?->state ||
            $school?->pincode
        )
            <div class="school-address">

                @if($school?->address)
                    {{ $school->address }}
                @endif

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
            $school?->udise_code ||
            $school?->school_code
        )
            <div class="school-contact">

                @if($school?->phone)
                    Phone: {{ $school->phone }}
                @endif

                @if($school?->email)
                    &nbsp; | &nbsp;
                    Email: {{ $school->email }}
                @endif

                @if($school?->udise_code)
                    &nbsp; | &nbsp;
                    UDISE: {{ $school->udise_code }}
                @endif

                @if($school?->school_code)
                    &nbsp; | &nbsp;
                    School Code: {{ $school->school_code }}
                @endif

            </div>
        @endif

        <div class="school-divider"></div>

    </div>


    {{-- =========================
         DOCUMENT TITLE
    ========================== --}}
    <div class="document-title">

        <span class="title-label">
            Official Examination Document
        </span>

        <h1>
            Examination Timetable
        </h1>

        <div class="exam-name">
            {{ $exam->exam_name }}
        </div>

    </div>


    {{-- =========================
         EXAM INFORMATION
    ========================== --}}
    <table class="exam-info">

        <tr>
            <td class="label">Academic Year</td>
            <td class="value">
                {{ $exam->academic_year ?? '-' }}
            </td>

            <td class="label">Exam Type</td>
            <td class="value">
                {{ $exam->exam_type ?? '-' }}
            </td>

            <td class="label">Exam Period</td>
            <td class="value">

                @if($exam->start_date)
                    {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}
                @endif

                @if($exam->end_date)
                    -
                    {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}
                @endif

            </td>
        </tr>

        <tr>
            <td class="label">Status</td>
            <td class="value">
                {{ ucfirst($exam->status ?? '-') }}
            </td>

            <td class="label">Total Papers</td>
            <td class="value">
                {{ $timetables->count() }}
            </td>

            <td class="label">Generated On</td>
            <td class="value">
                {{ now()->format('d M Y') }}
            </td>
        </tr>

    </table>


    {{-- =========================
         TIMETABLE
    ========================== --}}

    @if($timetables->count())

        @php
            $groupedByDate = $timetables
                ->sortBy([
                    ['exam_date', 'asc'],
                    ['start_time', 'asc'],
                    ['class_id', 'asc'],
                ])
                ->groupBy(function ($schedule) {
                    return \Carbon\Carbon::parse(
                        $schedule->exam_date
                    )->format('Y-m-d');
                });
        @endphp


        @foreach($groupedByDate as $date => $dateSchedules)

            <div class="date-block">

                <div class="date-header">

                    {{ \Carbon\Carbon::parse($date)->format('d F Y') }}

                    <span class="date-weekday">
                        &nbsp; | &nbsp;
                        {{ \Carbon\Carbon::parse($date)->format('l') }}
                    </span>

                    <span class="paper-count">
                        {{ $dateSchedules->count() }}
                        {{ $dateSchedules->count() === 1 ? 'Paper' : 'Papers' }}
                    </span>

                </div>


                <table class="timetable">

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

                            <th class="duration">
                                Duration
                            </th>

                            <th class="marks">
                                Maximum Marks
                            </th>

                            <th class="teacher">
                                Teacher / Supervisor
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                    @foreach($dateSchedules as $schedule)

                        @php
                            $className =
                                $schedule->schoolClass?->class_name ?? '-';

                            $section =
                                $schedule->schoolClass?->section ?? null;

                            $subject =
                                $schedule->examSubject?->subject;

                            $subjectName =
                                $subject?->subject_name ?? '-';

                            $subjectCode =
                                $subject?->subject_code ?? null;

                            $start =
                                \Carbon\Carbon::parse(
                                    $schedule->start_time
                                );

                            $end =
                                \Carbon\Carbon::parse(
                                    $schedule->end_time
                                );

                            $teacherName = null;

                            if ($schedule->teacher) {
                                $teacherName = trim(
                                    ($schedule->teacher->first_name ?? '') .
                                    ' ' .
                                    ($schedule->teacher->last_name ?? '')
                                );
                            }
                        @endphp

                        <tr>

                            <td class="time">
                                {{ $start->format('h:i A') }}
                                <br>
                                <span style="font-size:7px;color:#8793a3;">
                                    to
                                </span>
                                <br>
                                {{ $end->format('h:i A') }}
                            </td>

                            <td class="class">

                                <span class="class-badge">

                                    {{ $className }}

                                    @if($section)
                                        -{{ $section }}
                                    @endif

                                </span>

                            </td>

                            <td class="subject">

                                {{ $subjectName }}

                                @if($subjectCode)
                                    <span class="subject-code">
                                        Code: {{ $subjectCode }}
                                    </span>
                                @endif

                            </td>

                            <td class="duration center">

                                {{ $schedule->duration_minutes ?? '-' }}
                                min

                            </td>

                            <td class="marks">

                                <span class="marks-badge">
                                    {{ $schedule->maximum_marks ?? '-' }}
                                </span>

                            </td>

                            <td class="teacher">

                                @if($teacherName)

                                    <div class="teacher-name">
                                        {{ $teacherName }}
                                    </div>

                                    @if($schedule->teacher?->designation)
                                        <div class="teacher-designation">
                                            {{ $schedule->teacher->designation }}
                                        </div>
                                    @endif

                                @else

                                    <span class="not-assigned">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        @endforeach

    @else

        <div class="no-data">
            No examination timetable entries are available.
        </div>

    @endif


    {{-- =========================
         FOOTER
    ========================== --}}
    <div class="footer">

        <span class="footer-school">
            {{ $school?->school_name ?? 'School Management System' }}
        </span>

        Generated on:
        {{ now()->format('d F Y, h:i A') }}

    </div>

</div>


<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>