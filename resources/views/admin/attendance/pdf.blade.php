<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Student Attendance Register</title>

    @php
        /*
        |--------------------------------------------------------------------------
        | MONTH
        |--------------------------------------------------------------------------
        */

        $selectedMonth = $month
            ?? request('month', now()->format('Y-m'));

        try {

            $currentMonth = \Carbon\Carbon::createFromFormat(
                'Y-m',
                $selectedMonth
            )->startOfMonth();

        } catch (\Throwable $e) {

            $currentMonth = now()->startOfMonth();

            $selectedMonth =
                $currentMonth->format('Y-m');
        }

        /*
        |--------------------------------------------------------------------------
        | DATES
        |--------------------------------------------------------------------------
        */

        $pdfDates = collect();

        $datePointer =
            $currentMonth->copy()->startOfMonth();

        $monthEnd =
            $currentMonth->copy()->endOfMonth();

        while ($datePointer->lte($monthEnd)) {

            $pdfDates->push(
                $datePointer->copy()
            );

            $datePointer->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENTS
        |--------------------------------------------------------------------------
        */

        $students = collect(
            $students ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE
        |--------------------------------------------------------------------------
        |
        | Controller sends:
        |
        | $attendance
        |
        | Grouped by student_id.
        |
        */

        $attendance =
            $attendance ?? collect();

        /*
        |--------------------------------------------------------------------------
        | FILTER INFORMATION
        |--------------------------------------------------------------------------
        */

        $academicYear =
            $academicYear
            ?? request('academic_year');

        $selectedClass =
            $className
            ?? request('class');

        $selectedSection =
            $section
            ?? request('section');

        /*
        |--------------------------------------------------------------------------
        | HOLIDAYS
        |--------------------------------------------------------------------------
        */

        $nationalHolidays = [

            '01-26' => 'Republic Day',

            '08-15' => 'Independence Day',

            '10-02' => 'Gandhi Jayanti',

        ];

        $getHolidayName = function ($date) use (
            $nationalHolidays
        ) {

            /*
            |--------------------------------------------------------------------------
            | SUNDAY
            |--------------------------------------------------------------------------
            */

            if ($date->isSunday()) {

                return 'Sunday Holiday';
            }

            /*
            |--------------------------------------------------------------------------
            | NATIONAL HOLIDAY
            |--------------------------------------------------------------------------
            */

            $key =
                $date->format('m-d');

            return
                $nationalHolidays[$key]
                ?? null;
        };

        /*
        |--------------------------------------------------------------------------
        | WORKING DAYS
        |--------------------------------------------------------------------------
        */

        $workingDays =
            $pdfDates->filter(
                function ($date) use (
                    $getHolidayName
                ) {

                    return
                        $getHolidayName($date)
                        === null;
                }
            )->count();

        /*
        |--------------------------------------------------------------------------
        | HOLIDAY DAYS
        |--------------------------------------------------------------------------
        */

        $holidayDays =
            $pdfDates->filter(
                function ($date) use (
                    $getHolidayName
                ) {

                    return
                        $getHolidayName($date)
                        !== null;
                }
            )->count();
    @endphp


    <style>

        @page {

            size: A4 landscape;

            margin: 20px;
        }

        * {

            box-sizing: border-box;
        }

        body {

            font-family:
                DejaVu Sans,
                sans-serif;

            font-size: 8px;

            color: #222;

            margin: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {

            text-align: center;

            margin-bottom: 12px;
        }

        .school-name {

            font-size: 18px;

            font-weight: bold;

            margin-bottom: 4px;
        }

        .title {

            font-size: 13px;

            font-weight: bold;
        }

        .month {

            font-size: 10px;

            margin-top: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | INFORMATION
        |--------------------------------------------------------------------------
        */

        .info {

            width: 100%;

            margin-bottom: 10px;

            border-collapse: collapse;
        }

        .info td {

            padding: 5px;

            border: 1px solid #ddd;

            background: #f5f7fb;
        }

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE TABLE
        |--------------------------------------------------------------------------
        */

        table.attendance {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }

        table.attendance th,
        table.attendance td {

            border:
                1px solid #bfc5d2;

            text-align: center;

            padding: 3px 2px;

            height: 22px;
        }

        table.attendance th {

            background: #e9eef8;

            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */

        .student {

            width: 135px;

            text-align: left !important;
        }

        .roll {

            width: 55px;
        }

        .day {

            width: 21px;
        }

        .summary {

            width: 85px;
        }

        /*
        |--------------------------------------------------------------------------
        | HOLIDAY
        |--------------------------------------------------------------------------
        */

        .holiday-header {

            background:
                #fbe3e3 !important;

            color:
                #a01818;
        }

        .holiday-cell {

            background:
                #fff0f0;

            color:
                #a01818;

            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | PRESENT
        |--------------------------------------------------------------------------
        */

        .present {

            background:
                #d9f2df;

            color:
                #146c2e;

            font-weight: bold;

            padding:
                1px 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | ABSENT
        |--------------------------------------------------------------------------
        */

        .absent {

            background:
                #f8dddd;

            color:
                #a01818;

            font-weight: bold;

            padding:
                1px 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | NOT MARKED
        |--------------------------------------------------------------------------
        */

        .empty {

            color:
                #999;
        }

        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        .summary-text {

            font-size: 7px;

            line-height: 11px;
        }

        /*
        |--------------------------------------------------------------------------
        | LEGEND
        |--------------------------------------------------------------------------
        */

        .legend {

            margin-top: 12px;
        }

        .legend span {

            margin-right: 18px;
        }

        .box {

            display: inline-block;

            width: 9px;

            height: 9px;

            border:
                1px solid #aaa;

            vertical-align: middle;

            margin-right: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | SIGNATURES
        |--------------------------------------------------------------------------
        */

        .signatures {

            width: 100%;

            margin-top: 35px;

            border-collapse: collapse;
        }

        .signature {

            width: 33%;

            text-align: center;
        }

        .line {

            border-top:
                1px solid #333;

            width: 150px;

            margin:
                0 auto 5px;
        }

        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {

            margin-top: 12px;

            text-align: center;

            font-size: 7px;

            color: #777;
        }

    </style>

</head>


<body>


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="header">

    <div class="school-name">

        GURUKUL VIDYALAYA

    </div>

    <div class="title">

        Student Attendance Register

    </div>

    <div class="month">

        {{ $currentMonth->format('F Y') }}

    </div>

</div>


{{-- =========================================================
     INFORMATION
========================================================= --}}

<table class="info">

    <tr>

        <td>

            <strong>
                Academic Year:
            </strong>

            {{ $academicYear ?: 'N/A' }}

        </td>


        <td>

            <strong>
                Class:
            </strong>

            {{ $selectedClass ?: 'All Classes' }}

        </td>


        <td>

            <strong>
                Section:
            </strong>

            {{ $selectedSection ?: 'All Sections' }}

        </td>


        <td>

            <strong>
                Total Students:
            </strong>

            {{ $students->count() }}

        </td>


        <td>

            <strong>
                Working Days:
            </strong>

            {{ $workingDays }}

        </td>


        <td>

            <strong>
                Holidays:
            </strong>

            {{ $holidayDays }}

        </td>

    </tr>

</table>


{{-- =========================================================
     ATTENDANCE TABLE
========================================================= --}}

<table class="attendance">

    <thead>

        <tr>

            <th class="student">

                Student

            </th>


            <th class="roll">

                Roll No.

            </th>


            @foreach($pdfDates as $date)

                @php

                    $holidayName =
                        $getHolidayName($date);

                @endphp

                <th
                    class="
                        day
                        {{ $holidayName
                            ? 'holiday-header'
                            : '' }}
                    "
                >

                    {{ $date->format('d') }}

                    @if($holidayName)

                        <br>

                        <small>

                            @if($date->isSunday())

                                SUN

                            @else

                                HOL

                            @endif

                        </small>

                    @endif

                </th>

            @endforeach


            <th class="summary">

                Summary

            </th>

        </tr>

    </thead>


    <tbody>


        @forelse($students as $student)

            @php

                $present = 0;

                $absent = 0;

            @endphp


            <tr>


                {{-- STUDENT --}}

                <td class="student">

                    {{ trim(
                        ($student->first_name ?? '') .
                        ' ' .
                        ($student->middle_name ?? '') .
                        ' ' .
                        ($student->last_name ?? '')
                    ) }}

                </td>


                {{-- ROLL NUMBER --}}

                <td class="roll">

                    {{ $student->roll_number ?? '-' }}

                </td>


                {{-- DAILY ATTENDANCE --}}

                @foreach($pdfDates as $date)

                    @php

                        $dateKey =
                            $date->format('Y-m-d');

                        $holidayName =
                            $getHolidayName($date);

                        /*
                        |--------------------------------------------------------------------------
                        | GET STUDENT ATTENDANCE
                        |--------------------------------------------------------------------------
                        */

                        $studentRecords =
                            $attendance->get(
                                $student->id,
                                collect()
                            );

                        $record =
                            $studentRecords
                                ->first(
                                    function ($item)
                                    use ($dateKey) {

                                        return
                                            \Carbon\Carbon::parse(
                                                $item->attendance_date
                                            )->format('Y-m-d')
                                            === $dateKey;
                                    }
                                );

                        $status =
                            strtolower(
                                trim(
                                    (string)
                                    (
                                        $record->status
                                        ?? ''
                                    )
                                )
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | COUNTS
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $status === 'present'
                            &&
                            !$holidayName
                        ) {

                            $present++;
                        }

                        if (
                            $status === 'absent'
                            &&
                            !$holidayName
                        ) {

                            $absent++;
                        }

                    @endphp


                    <td
                        class="
                            {{ $holidayName
                                ? 'holiday-cell'
                                : '' }}
                        "
                    >


                        @if($date->isSunday())

                            <span>

                                SUN

                            </span>


                        @elseif($status === 'holiday')

                            <span>

                                HOL

                            </span>


                        @elseif($status === 'present')

                            <span class="present">

                                P

                            </span>


                        @elseif($status === 'absent')

                            <span class="absent">

                                A

                            </span>


                        @else

                            <span class="empty">

                                -

                            </span>

                        @endif


                    </td>

                @endforeach


                {{-- =====================================================
                     SUMMARY
                ====================================================== --}}

                @php

                    $marked =
                        $present + $absent;

                    $percentage =
                        $marked > 0
                        ? round(
                            ($present / $marked) * 100,
                            1
                        )
                        : 0;

                @endphp


                <td class="summary">

                    <div class="summary-text">

                        P: {{ $present }}

                        &nbsp;

                        A: {{ $absent }}

                        <br>

                        {{ $percentage }}%

                    </div>

                </td>

            </tr>


        @empty

            <tr>

                <td
                    colspan="{{ $pdfDates->count() + 3 }}"
                    style="
                        text-align:center;
                        padding:15px;
                        color:#777;
                    "
                >

                    No students found for the selected filters.

                </td>

            </tr>

        @endforelse


    </tbody>

</table>


{{-- =========================================================
     LEGEND
========================================================= --}}

<div class="legend">


    <span>

        <span
            class="box"
            style="background:#d9f2df;"
        ></span>

        Present

    </span>


    <span>

        <span
            class="box"
            style="background:#f8dddd;"
        ></span>

        Absent

    </span>


    <span>

        <span
            class="box"
            style="background:#fff0f0;"
        ></span>

        Holiday

    </span>


    <span>

        <span
            class="box"
            style="background:#fff;"
        ></span>

        Not Marked

    </span>


</div>


{{-- =========================================================
     SIGNATURES
========================================================= --}}

<table class="signatures">

    <tr>


        <td class="signature">

            <div class="line"></div>

            Class Teacher

        </td>


        <td class="signature">

            <div class="line"></div>

            Attendance In-charge

        </td>


        <td class="signature">

            <div class="line"></div>

            Principal

        </td>


    </tr>

</table>


{{-- =========================================================
     FOOTER
========================================================= --}}

<div class="footer">

    Generated on

    {{ now()->format('d M Y, h:i A') }}

</div>


</body>
</html>