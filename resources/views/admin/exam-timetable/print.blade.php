<!DOCTYPE html>

<html lang="en">

<head>


<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Date Sheet -
    {{ $exam->exam_name }}
</title>


<style>

    /* =====================================================
       GLOBAL
    ====================================================== */

    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        background: #ffffff;
    }

    body {
        font-family: "Times New Roman", Times, serif;
        color: #172b4d;
    }

    .page {
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding: 20px 28px;
        overflow: hidden;
    }


    /* =====================================================
       WATERMARK
    ====================================================== */

    .watermark {
        position: absolute;
        left: 50%;
        top: 52%;
        transform: translate(-50%, -50%);

        width: 310px;
        height: 310px;

        opacity: 0.045;

        object-fit: contain;

        z-index: 0;
    }

    .content {
        position: relative;
        z-index: 2;
    }


    /* =====================================================
       PRINT ACTION
    ====================================================== */

    .print-actions {
        text-align: right;
        margin-bottom: 12px;
    }

    .print-button {
        padding: 8px 18px;

        border: none;
        border-radius: 4px;

        background: #17365d;
        color: #ffffff;

        font-family: Arial, sans-serif;
        font-size: 13px;

        cursor: pointer;
    }

    .print-button:hover {
        background: #0f2948;
    }


    /* =====================================================
       SCHOOL HEADER
    ====================================================== */

    .school-header {
        width: 100%;
        border-bottom: 3px solid #17365d;
        padding-bottom: 9px;
        margin-bottom: 10px;
    }

    .school-header-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logo-cell {
        width: 105px;
        text-align: center;
        vertical-align: middle;
    }

    .school-logo {
        width: 82px;
        height: 82px;
        object-fit: contain;
    }

    .school-info {
        text-align: center;
        vertical-align: middle;
    }

    .school-name {
        color: #17365d;

        font-size: 29px;
        line-height: 1.05;

        font-weight: bold;

        letter-spacing: 1.5px;

        text-transform: uppercase;
    }

    .school-tagline {
        margin-top: 3px;

        color: #17365d;

        font-size: 15px;
        font-weight: bold;

        letter-spacing: 1px;
    }

    .school-address {
        margin-top: 5px;

        font-size: 12px;
        font-weight: bold;

        color: #333333;
    }

    .school-contact {
        margin-top: 3px;

        font-size: 11px;
        font-weight: bold;

        color: #333333;
    }


    /* =====================================================
       DATE SHEET TITLE
    ====================================================== */

    .date-sheet-heading {
        text-align: center;

        margin-top: 14px;
        margin-bottom: 12px;
    }

    .date-sheet-heading h1 {
        margin: 0;

        color: #b51f1f;

        font-size: 25px;
        line-height: 1.1;

        font-weight: bold;

        letter-spacing: 2px;

        text-transform: uppercase;
    }

    .exam-name {
        margin-top: 6px;

        color: #17365d;

        font-size: 20px;

        font-weight: bold;

        text-transform: uppercase;
    }

    .academic-year {
        margin-top: 4px;

        color: #17365d;

        font-size: 14px;

        font-weight: bold;
    }


    /* =====================================================
       DATE SHEET TABLE
    ====================================================== */

    .date-sheet-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;

        margin-top: 15px;
    }

    .date-sheet-table th,
    .date-sheet-table td {
        border: 1px solid #17365d;
    }


    /* =====================================================
       TABLE HEADER
    ====================================================== */

    .date-sheet-table th {
        height: 42px;

        padding: 7px 8px;

        background: #edf3f9;

        color: #17365d;

        font-size: 14px;

        font-weight: bold;

        text-align: center;
        vertical-align: middle;

        text-transform: uppercase;
    }


    /* =====================================================
       TABLE BODY
    ====================================================== */

    .date-sheet-table td {
        min-height: 58px;

        padding: 10px 9px;

        color: #172b4d;

        font-size: 14px;

        font-weight: bold;

        text-align: center;
        vertical-align: middle;

        line-height: 1.35;
    }

    .date-sheet-table tbody tr {
        height: 62px;
    }


    /* =====================================================
       COLUMN WIDTHS
    ====================================================== */

    .day-date-column {
        width: 31%;
    }

    .time-column {
        width: 25%;
    }

    .subject-code-column {
        width: 17%;
    }

    .subject-column {
        width: 27%;
    }


    /* =====================================================
       DAY & DATE
    ====================================================== */

    .date-cell {
        line-height: 1.4;
    }


    /* =====================================================
       TIME
    ====================================================== */

    .time-cell {
        line-height: 1.45;
        white-space: nowrap;
    }


    /* =====================================================
       SUBJECT CODE
    ====================================================== */

    .code-cell {
        font-weight: bold;
        letter-spacing: 1px;
    }


    /* =====================================================
       SUBJECT
    ====================================================== */

    .subject-cell {
        font-weight: bold;

        text-transform: uppercase;

        line-height: 1.35;
    }


    /* =====================================================
       SIGNATURES
    ====================================================== */

    .signature-area {
        width: 100%;

        margin-top: 27px;
    }

    .signature-table {
        width: 100%;

        border-collapse: collapse;
    }

    .signature-table td {
        width: 50%;

        border: none;

        vertical-align: bottom;
    }

    .signature-left {
        text-align: left;
        padding-left: 35px;
    }

    .signature-right {
        text-align: right;
        padding-right: 35px;
    }

    .signature-line {
        display: inline-block;

        min-width: 170px;

        border-bottom: 1px solid #17365d;

        height: 30px;
    }

    .signature-name {
        margin-top: 4px;

        color: #17365d;

        font-size: 13px;

        font-weight: bold;
    }


    /* =====================================================
       FOOTER
    ====================================================== */

    .footer {
        margin-top: 18px;

        border-top: 2px solid #17365d;

        padding-top: 6px;

        text-align: center;
    }

    .footer-text {
        color: #17365d;

        font-size: 12px;

        font-style: italic;

        font-weight: bold;
    }


    /* =====================================================
       EMPTY STATE
    ====================================================== */

    .empty {
        border: 1px solid #17365d;

        padding: 25px;

        margin-top: 20px;

        text-align: center;

        color: #17365d;

        font-size: 15px;

        font-weight: bold;
    }


    /* =====================================================
       MULTIPLE CLASS PAGES
    ====================================================== */

    .class-date-sheet {
        page-break-after: always;
    }

    .class-date-sheet:last-child {
        page-break-after: auto;
    }


    /* =====================================================
       PRINT
    ====================================================== */

    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    @media print {

        body {
            background: #ffffff;
        }

        .page {
            min-height: auto;

            padding: 5px 8px;
        }

        .print-actions {
            display: none !important;
        }

        .class-date-sheet {
            page-break-after: always;
        }

        .class-date-sheet:last-child {
            page-break-after: auto;
        }

        .date-sheet-table {
            page-break-inside: auto;
        }

        .date-sheet-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

    }

</style>


</head>

<body>

<div class="page">


{{-- =====================================================
     PRINT BUTTON
====================================================== --}}

<div class="print-actions">

    <button type="button"
            class="print-button"
            onclick="window.print()">

        <span>
            Print Date Sheet
        </span>

    </button>

</div>


{{-- =====================================================
     GROUP SCHEDULES BY CLASS
====================================================== --}}

@php

    $groupedSchedules = $schedules->groupBy(
        'exam_class_id'
    );

@endphp


@if($groupedSchedules->count())


    @foreach($groupedSchedules as $classId => $classSchedules)


        @php

            $class = $classSchedules
                ->first()
                ->examClass;

        @endphp


        <div class="content class-date-sheet">


            {{-- =================================================
                 SCHOOL HEADER
            ================================================== --}}

            <div class="school-header">

                <table class="school-header-table">

                    <tr>


                        {{-- SCHOOL LOGO --}}
                        <td class="logo-cell">

                            @if($school?->logo)

                                <img src="{{ asset(
                                    'storage/' . $school->logo
                                ) }}"
                                     class="school-logo"
                                     alt="School Logo">

                            @else

                                <img src="{{ asset(
                                    'images/gurukullogo.png'
                                ) }}"
                                     class="school-logo"
                                     alt="School Logo">

                            @endif

                        </td>


                        {{-- SCHOOL INFORMATION --}}
                        <td class="school-info">

                            <div class="school-name">

                                {{ $school?->school_name
                                    ?? 'Gurukul Vidyalaya'
                                }}

                            </div>


                            <div class="school-tagline">

                                Learn &nbsp; • &nbsp;
                                Grow &nbsp; • &nbsp;
                                Succeed

                            </div>


                            @if($school?->address)

                                <div class="school-address">

                                    {{ $school->address }}

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

                            @endif


                            <div class="school-contact">


                                @if($school?->phone)

                                    Phone:
                                    {{ $school->phone }}

                                @endif


                                @if($school?->email)

                                    @if($school?->phone)
                                        &nbsp; | &nbsp;
                                    @endif

                                    Email:
                                    {{ $school->email }}

                                @endif


                                @if($school?->udise_code)

                                    &nbsp; | &nbsp;

                                    UDISE Code:
                                    {{ $school->udise_code }}

                                @endif


                            </div>

                        </td>

                    </tr>

                </table>

            </div>


            {{-- =================================================
                 WATERMARK
            ================================================== --}}

            @if($school?->logo)

                <img src="{{ asset(
                    'storage/' . $school->logo
                ) }}"
                     class="watermark"
                     alt="">

            @else

                <img src="{{ asset(
                    'images/gurukullogo.png'
                ) }}"
                     class="watermark"
                     alt="">

            @endif


            {{-- =================================================
                 DATE SHEET HEADING
            ================================================== --}}

            <div class="date-sheet-heading">


                <h1>

                    DATE SHEET

                    @if($class)

                        GRADE
                        {{ $class->class_name }}

                    @endif

                </h1>


                <div class="exam-name">

                    {{ $exam->exam_name }}

                </div>


                <div class="academic-year">

                    Academic Year:
                    {{ $exam->academic_year }}

                </div>


            </div>


            {{-- =================================================
                 DATE SHEET TABLE
            ================================================== --}}

            <table class="date-sheet-table">


                <thead>

                    <tr>

                        <th class="day-date-column">
                            DAY &amp; DATE
                        </th>

                        <th class="time-column">
                            TIME
                        </th>

                        <th class="subject-code-column">
                            SUBJECT CODE
                        </th>

                        <th class="subject-column">
                            SUBJECT
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @foreach(
                        $classSchedules
                            ->sortBy(function ($schedule) {

                                return
                                    $schedule->exam_date
                                    . ' '
                                    . $schedule->start_time;

                            })
                        as $schedule
                    )


                        <tr>


                            {{-- =================================
                                 DAY & DATE
                            ================================== --}}

                            <td class="date-cell">


                                @if($schedule->exam_date)

                                    {{ $schedule->exam_date->format('l') }},

                                    {{ $schedule->exam_date->format('jS') }}

                                    {{ $schedule->exam_date->format('F') }},

                                    {{ $schedule->exam_date->format('Y') }}

                                @else

                                    -

                                @endif


                            </td>


                            {{-- =================================
                                 TIME
                            ================================== --}}

                            <td class="time-cell">


                                {{ \Carbon\Carbon::parse(
                                    $schedule->start_time
                                )->format('h.i A') }}

                                TO

                                {{ \Carbon\Carbon::parse(
                                    $schedule->end_time
                                )->format('h.i A') }}


                            </td>


                            {{-- =================================
                                 SUBJECT CODE
                            ================================== --}}

                            <td class="code-cell">


                                @if($schedule->subject_id)

                                    {{ str_pad(
                                        $schedule->subject_id,
                                        3,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                @else

                                    -

                                @endif


                            </td>


                            {{-- =================================
                                 SUBJECT
                            ================================== --}}

                            <td class="subject-cell">


                                @if($schedule->subject_id)

                                    Subject
                                    {{ $schedule->subject_id }}

                                @else

                                    -

                                @endif


                            </td>


                        </tr>


                    @endforeach


                </tbody>

            </table>


            {{-- =================================================
                 SIGNATURES
            ================================================== --}}

            <div class="signature-area">


                <table class="signature-table">

                    <tr>


                        <td class="signature-left">

                            <div class="signature-line">
                            </div>

                            <div class="signature-name">

                                Controller of Examinations

                            </div>

                        </td>


                        <td class="signature-right">

                            <div class="signature-line">
                            </div>

                            <div class="signature-name">

                                Principal

                            </div>

                        </td>


                    </tr>

                </table>


            </div>


            {{-- =================================================
                 FOOTER
            ================================================== --}}

            <div class="footer">

                <div class="footer-text">

                    Best Wishes for Your Exams

                </div>

            </div>


        </div>


    @endforeach


@else


    {{-- =====================================================
         NO SCHEDULE
    ====================================================== --}}

    <div class="content">


        <div class="school-header">

            <table class="school-header-table">

                <tr>

                    <td class="logo-cell">

                        <img src="{{ asset(
                            'images/gurukullogo.png'
                        ) }}"
                             class="school-logo"
                             alt="School Logo">

                    </td>


                    <td class="school-info">

                        <div class="school-name">

                            {{ $school?->school_name
                                ?? 'Gurukul Vidyalaya'
                            }}

                        </div>

                    </td>

                </tr>

            </table>

        </div>


        <div class="date-sheet-heading">

            <h1>
                DATE SHEET
            </h1>

            <div class="exam-name">

                {{ $exam->exam_name }}

            </div>

            <div class="academic-year">

                Academic Year:
                {{ $exam->academic_year }}

            </div>

        </div>


        <div class="empty">

            No examination schedule has been created.

        </div>


    </div>


@endif


</div>

<script>

    window.addEventListener('load', function () {

        window.print();

    });

</script>

</body>

</html>
