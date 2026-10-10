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

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #222;
            background: #fff;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .school-address {
            font-size: 11px;
            color: #555;
            margin-bottom: 10px;
        }

        .title {
            font-size: 17px;
            font-weight: bold;
            margin-top: 8px;
        }

        .exam-info {
            margin-top: 5px;
            font-size: 11px;
            color: #555;
        }

        .filter-info {
            text-align: center;
            margin: 10px 0 15px;
            font-size: 11px;
        }

        .date-block {
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .date-header {
            background: #f1f3f5;
            border: 1px solid #d9dde1;
            padding: 9px 12px;
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            background: #f8f9fa;
            border: 1px solid #cfd4da;
            padding: 8px 9px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #d9dde1;
            padding: 8px 9px;
            vertical-align: middle;
        }

        .time {
            width: 17%;
            white-space: nowrap;
            font-weight: 600;
        }

        .class {
            width: 12%;
            font-weight: 600;
        }

        .subject {
            width: 31%;
        }

        .marks {
            width: 10%;
            text-align: center;
        }

        .teacher {
            width: 30%;
        }

        .time-empty {
            color: transparent;
        }

        .session-separator td {
            height: 5px;
            padding: 0;
            background: #f8f9fa;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }

        .no-data {
            text-align: center;
            padding: 35px;
            border: 1px solid #ddd;
        }

    </style>

</head>

<body>


{{-- =========================================================
     SCHOOL HEADER
========================================================== --}}

<div class="header">

    @if(!empty($logoData))
        <img src="{{ $logoData }}"
             class="logo"
             alt="School Logo">
    @endif

    @if($school?->school_name)
        <div class="school-name">
            {{ $school->school_name }}
        </div>
    @endif

    @if($school?->address || $school?->city || $school?->district)
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

        </div>
    @endif

    <div class="title">
        EXAMINATION TIMETABLE
    </div>

    <div class="exam-info">

        <strong>{{ $exam->exam_name }}</strong>

        @if($exam->exam_type)
            &nbsp; | &nbsp;
            {{ $exam->exam_type }}
        @endif

        @if($exam->academic_year)
            &nbsp; | &nbsp;
            Academic Year: {{ $exam->academic_year }}
        @endif

    </div>

</div>


@if($selectedClass || request('exam_date'))

    <div class="filter-info">

        @if($selectedClass)
            Class:
            <strong>
                {{ $selectedClass->class_name }}
                @if($selectedClass->section)
                    - {{ $selectedClass->section }}
                @endif
            </strong>
        @endif

        @if(request('exam_date'))

            @if($selectedClass)
                &nbsp; | &nbsp;
            @endif

            Date:
            <strong>
                {{ \Illuminate\Support\Carbon::parse(
                    request('exam_date')
                )->format('d F Y') }}
            </strong>

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

        <div class="date-block">

            <div class="date-header">

                {{ \Illuminate\Support\Carbon::parse($date)->format('d F Y') }}

                <span style="font-weight: normal;">
                    -
                    {{ \Illuminate\Support\Carbon::parse($date)->format('l') }}
                </span>

            </div>


            @php

                $groupedByTime = $dateSchedules->groupBy(function ($schedule) {

                    return
                        \Illuminate\Support\Carbon::parse(
                            $schedule->start_time
                        )->format('H:i')
                        . '|'
                        .
                        \Illuminate\Support\Carbon::parse(
                            $schedule->end_time
                        )->format('H:i');

                });

            @endphp


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
                            Teacher
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($groupedByTime as $time => $timeSchedules)

                    @php
                        [$startTime, $endTime] = explode('|', $time);
                    @endphp


                    @foreach($timeSchedules as $index => $schedule)

                        <tr>

                            <td class="time">

                                @if($index === 0)

                                    {{ \Illuminate\Support\Carbon::createFromFormat(
                                        'H:i',
                                        $startTime
                                    )->format('H:i') }}

                                    –

                                    {{ \Illuminate\Support\Carbon::createFromFormat(
                                        'H:i',
                                        $endTime
                                    )->format('H:i') }}

                                @else

                                    <span class="time-empty">—</span>

                                @endif

                            </td>


                            <td class="class">

                                {{ optional($schedule->schoolClass)->class_name }}

                                @if(optional($schedule->schoolClass)->section)
                                    -{{ optional($schedule->schoolClass)->section }}
                                @endif

                            </td>


                            <td class="subject">

                                {{ optional(
                                    optional($schedule->examSubject)->subject
                                )->subject_name ?? '—' }}

                            </td>


                            <td class="marks">

                                {{ $schedule->maximum_marks }}

                            </td>


                            <td class="teacher">

                                @if($schedule->teacher)

                                    {{ trim(
                                        $schedule->teacher->first_name . ' ' .
                                        $schedule->teacher->last_name
                                    ) }}

                                @else

                                    Not Assigned

                                @endif

                            </td>

                        </tr>

                    @endforeach


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


<div class="footer">
    Generated on {{ now()->format('d F Y h:i A') }}
</div>


<script>
    window.onload = function () {
        window.print();
    };
</script>

</body>
</html>