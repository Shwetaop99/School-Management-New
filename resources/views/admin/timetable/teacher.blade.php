@extends('layouts.app')

@section('title', 'Teacher Timetable')

@section('page-title', 'Teacher Timetable')

@section('content')

<style>

/* =========================================================
   PAGE
========================================================= */

.teacher-timetable-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
}


/* =========================================================
   HEADER
========================================================= */

.timetable-header {
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    border-radius: 18px;
    padding: 27px 32px;
    margin-bottom: 20px;
    box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15);
}

.timetable-header h2 {
    margin: 0 0 6px;
    font-size: 25px;
    font-weight: 700;
}

.timetable-header p {
    margin: 0;
    opacity: 0.92;
    font-size: 14px;
}


/* =========================================================
   FILTER
========================================================= */

.filter-card {
    background: #fff;
    border-radius: 15px;
    padding: 19px 21px;
    margin-bottom: 18px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
}

.filter-card label {
    display: block;
    font-weight: 600;
    color: #253858;
    margin-bottom: 7px;
    font-size: 13px;
}

.teacher-select-row {
    display: flex;
    gap: 12px;
    align-items: end;
}

.teacher-select-wrapper {
    flex: 1;
}

.teacher-select {
    width: 100%;
    height: 45px;
    border: 1px solid #d9e2ef;
    border-radius: 9px;
    padding: 0 13px;
    font-size: 14px;
    background: #fff;
    outline: none;
}

.teacher-select:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.1);
}

.view-button,
.action-button {
    height: 45px;
    border: none;
    border-radius: 9px;
    padding: 0 20px;
    background: #147cf5;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    white-space: nowrap;
}

.view-button:hover,
.action-button:hover {
    background: #1268ca;
}

.action-button.share {
    background: #6c63ff;
}

.action-button.share:hover {
    background: #574fd4;
}

.action-button.print {
    background: #334155;
}

.action-button.print:hover {
    background: #1e293b;
}


/* =========================================================
   TEACHER INFO
========================================================= */

.teacher-info {
    background: #fff;
    border-radius: 15px;
    padding: 16px 21px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
}

.teacher-info-left {
    display: flex;
    align-items: center;
    gap: 13px;
}

.teacher-avatar {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 700;
}

.teacher-info h3 {
    margin: 0 0 3px;
    color: #172b4d;
    font-size: 18px;
}

.teacher-info span {
    color: #718096;
    font-size: 12px;
}

.teacher-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 11px;
    border-radius: 20px;
    background: #eef6ff;
    color: #147cf5;
    font-size: 11px;
    font-weight: 700;
}

.teacher-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #16a34a;
    animation: statusPulse 1.5s infinite;
}

@keyframes statusPulse {
    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.4;
    }

    100% {
        opacity: 1;
    }
}


/* =========================================================
   ACTION BAR
========================================================= */

.timetable-actions {
    display: flex;
    justify-content: flex-end;
    gap: 9px;
    margin-bottom: 12px;
}


/* =========================================================
   WEEK LABEL
========================================================= */

.week-label {
    margin-bottom: 12px;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
}

.week-label strong {
    color: #172b4d;
}

#live-date-time {
    color: #147cf5;
    font-weight: 700;
}


/* =========================================================
   TIMETABLE
========================================================= */

.weekly-card {
    background: #fff;
    border-radius: 15px;
    padding: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    overflow-x: auto;
}

.weekly-table {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
    table-layout: fixed;
}


/* =========================================================
   TABLE HEADER
========================================================= */

.weekly-table thead th {
    height: 46px;
    padding: 8px 6px;
    background: #eef4ff;
    border: 1px solid #d7e2f0;
    color: #263b64;
    font-size: 12px;
    font-weight: 800;
    text-align: center;
    vertical-align: middle;
}

.weekly-table thead th.period-heading {
    width: 70px;
}

.weekly-table thead th.time-heading {
    width: 125px;
}


/* =========================================================
   TODAY HEADER
========================================================= */

.weekly-table thead th.today-column {
    background: linear-gradient(135deg, #147cf5, #6c63ff);
    color: #fff;
    border-color: #147cf5;
}


/* =========================================================
   TABLE BODY
========================================================= */

.weekly-table tbody tr {
    height: 92px;
}

.weekly-table tbody td {
    border: 1px solid #dce5ef;
    vertical-align: middle;
}


/* =========================================================
   PERIOD
========================================================= */

.period-cell {
    background: #f8fafc;
    text-align: center;
    color: #334155;
    font-size: 13px;
    font-weight: 800;
}

.period-number {
    display: inline-flex;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #147cf5;
}


/* =========================================================
   TIME
========================================================= */

.time-cell {
    background: #f8fafc;
    text-align: center;
    padding: 8px 5px;
    color: #475569;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.5;
}

.time-from {
    color: #334155;
}

.time-arrow {
    color: #94a3b8;
    font-size: 9px;
}

.time-to {
    color: #64748b;
}


/* =========================================================
   LECTURE CELL
========================================================= */

.lecture-cell {
    position: relative;
    background: #fff;
    padding: 8px 7px;
    text-align: left;
    vertical-align: middle !important;
    transition: 0.2s;
}

.lecture-cell:hover {
    background: #f8fbff;
}

.lecture-subject {
    color: #147cf5;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.25;
    margin-bottom: 4px;
}

.lecture-class {
    color: #263b64;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.3;
}

.lecture-room {
    color: #718096;
    font-size: 9px;
    margin-top: 3px;
}

.lecture-time {
    color: #6c63ff;
    font-size: 9px;
    font-weight: 700;
    margin-top: 4px;
}

.lecture-type {
    display: inline-block;
    margin-top: 5px;
    padding: 3px 6px;
    border-radius: 10px;
    background: #eef4ff;
    color: #147cf5;
    font-size: 8px;
    font-weight: 700;
}


/* =========================================================
   FREE
========================================================= */

.free-cell {
    background: #fbfcfe;
    color: #a0aec0;
    text-align: center;
    font-size: 10px;
    font-weight: 600;
}


/* =========================================================
   BREAK
========================================================= */

.break-cell {
    background: #fff9e9;
    color: #9a6b00;
    text-align: center;
    font-size: 11px;
    font-weight: 800;
    vertical-align: middle !important;
}


/* =========================================================
   LUNCH
========================================================= */

.lunch-cell {
    background: #f5f2ff;
    color: #6255c7;
    text-align: center;
    font-size: 11px;
    font-weight: 800;
    vertical-align: middle !important;
}


/* =========================================================
   TODAY CELL
========================================================= */

.today-cell {
    background: #f7fbff;
}


/* =========================================================
   LIVE NOW
========================================================= */

.current-lecture {
    background: #effcf4 !important;
    border: 2px solid #16a34a !important;
    box-shadow:
        inset 0 0 0 1px rgba(22, 163, 74, 0.08),
        0 0 8px rgba(22, 163, 74, 0.12);
}

.current-lecture .lecture-subject {
    color: #15803d;
}

.current-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 5px;
    padding: 3px 7px;
    border-radius: 12px;
    background: #16a34a;
    color: #fff;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 0.2px;
}

.current-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #fff;
    animation: livePulse 1.4s infinite;
}

@keyframes livePulse {

    0% {
        opacity: 1;
    }

    50% {
        opacity: 0.35;
    }

    100% {
        opacity: 1;
    }
}


/* =========================================================
   UPCOMING
========================================================= */

.upcoming-lecture {
    background: #fffdf2 !important;
    border: 2px solid #f2c94c !important;
    box-shadow: 0 0 7px rgba(242, 201, 76, 0.12);
}

.upcoming-lecture .lecture-subject {
    color: #9a7200;
}

.upcoming-badge {
    display: inline-block;
    margin-bottom: 5px;
    padding: 3px 7px;
    border-radius: 12px;
    background: #f2c94c;
    color: #604b00;
    font-size: 8px;
    font-weight: 800;
}


/* =========================================================
   LEGEND
========================================================= */

.timetable-legend {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 14px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    font-size: 10px;
    font-weight: 600;
}

.legend-box {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    border: 1px solid #d1d5db;
}

.legend-now {
    background: #effcf4;
    border-color: #16a34a;
}

.legend-upcoming {
    background: #fffdf2;
    border-color: #f2c94c;
}

.legend-free {
    background: #fbfcfe;
}


/* =========================================================
   NO DATA
========================================================= */

.no-data {
    background: #fff;
    border-radius: 15px;
    padding: 55px 20px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
}

.no-data-icon {
    font-size: 45px;
    margin-bottom: 15px;
}

.no-data h3 {
    margin: 0 0 8px;
    color: #263b64;
}

.no-data p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}


/* =========================================================
   PRINT HEADER
========================================================= */

.print-header {
    display: none;
}

.print-footer {
    display: none;
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    html,
    body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body * {
        visibility: hidden;
    }

    .teacher-timetable-page,
    .teacher-timetable-page * {
        visibility: visible;
    }

    .teacher-timetable-page {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: none !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .timetable-header,
    .filter-card,
    .teacher-info,
    .timetable-actions,
    .week-label,
    .timetable-legend,
    .no-data {
        display: none !important;
    }

    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 9px;
    }

    .print-school-name {
        color: #172b4d;
        font-size: 19px;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .print-title {
        color: #147cf5;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .print-details {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 9px;
    }

    .print-details td {
        border: 1px solid #aebdcd;
        padding: 5px 7px;
        font-size: 8px;
        text-align: left;
    }

    .print-details .label {
        width: 11%;
        background: #f1f5f9;
        color: #334155;
        font-weight: 800;
    }

    .weekly-card {
        display: block !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .weekly-table {
        width: 100% !important;
        min-width: 0 !important;
        border-collapse: collapse !important;
        table-layout: fixed !important;
    }

    .weekly-table thead th {
        height: auto !important;
        padding: 5px 3px !important;
        border: 1px solid #8fa4ba !important;
        background: #eaf2ff !important;
        color: #172b4d !important;
        font-size: 8px !important;
    }

    .weekly-table thead th.today-column {
        background: #eaf2ff !important;
        color: #172b4d !important;
    }

    .weekly-table thead th.period-heading {
        width: 6% !important;
    }

    .weekly-table thead th.time-heading {
        width: 12% !important;
    }

    .weekly-table tbody tr {
        height: 54px !important;
        page-break-inside: avoid !important;
    }

    .weekly-table tbody td {
        border: 1px solid #9eafc0 !important;
    }

    .period-cell {
        background: #f8fafc !important;
        font-size: 8px !important;
    }

    .period-number {
        width: 22px !important;
        height: 22px !important;
        background: transparent !important;
        color: #172b4d !important;
        font-size: 9px !important;
    }

    .time-cell {
        background: #f8fafc !important;
        padding: 3px !important;
        font-size: 7px !important;
        line-height: 1.3 !important;
    }

    .lecture-cell {
        height: 54px !important;
        padding: 4px !important;
        background: #fff !important;
    }

    .lecture-subject {
        color: #172b4d !important;
        font-size: 8px !important;
        margin-bottom: 2px !important;
    }

    .lecture-class {
        color: #475569 !important;
        font-size: 7px !important;
    }

    .lecture-room {
        color: #64748b !important;
        font-size: 6px !important;
        margin-top: 2px !important;
    }

    .lecture-time {
        color: #147cf5 !important;
        font-size: 6px !important;
        margin-top: 2px !important;
    }

    .lecture-type {
        display: none !important;
    }

    .free-cell {
        background: #fff !important;
        font-size: 7px !important;
        color: #94a3b8 !important;
    }

    .break-cell {
        background: #fffaf0 !important;
        color: #8a6200 !important;
        font-size: 7px !important;
    }

    .lunch-cell {
        background: #f7f5ff !important;
        color: #5b50ad !important;
        font-size: 7px !important;
    }

    .current-lecture,
    .upcoming-lecture {
        box-shadow: none !important;
    }

    .current-badge,
    .upcoming-badge {
        display: none !important;
    }

    .print-footer {
        display: block !important;
        margin-top: 7px;
        text-align: right;
        color: #64748b;
        font-size: 6px;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .teacher-timetable-page {
        padding: 15px;
    }

    .teacher-select-row {
        flex-direction: column;
        align-items: stretch;
    }

    .view-button {
        width: 100%;
    }

    .teacher-info {
        align-items: flex-start;
    }

    .teacher-status {
        display: none;
    }

    .timetable-actions {
        justify-content: stretch;
    }

    .action-button {
        flex: 1;
        padding: 0 10px;
    }

    .weekly-table {
        min-width: 1050px;
    }
}

</style>


<div class="teacher-timetable-page">


    {{-- =====================================================
         SCREEN HEADER
    ====================================================== --}}

    <div class="timetable-header">

        <h2>
            Teacher Weekly Timetable
        </h2>

        <p>
            View the complete weekly teaching schedule and current lecture status.
        </p>

    </div>


    {{-- =====================================================
         TEACHER SELECT
    ====================================================== --}}

    <div class="filter-card">

        <form
            method="GET"
            action="{{ route('admin.timetable.teacher') }}"
        >

            <div class="teacher-select-row">

                <div class="teacher-select-wrapper">

                    <label for="teacher_id">
                        Select Teacher
                    </label>

                    <select
                        name="teacher_id"
                        id="teacher_id"
                        class="teacher-select"
                        required
                    >

                        <option value="">
                            Select a teacher
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}
                            >

                                {{ $teacher->first_name }}
                                {{ $teacher->last_name }}

                                @if($teacher->teacher_id)
                                    ({{ $teacher->teacher_id }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                <button
                    type="submit"
                    class="view-button"
                >
                    View Timetable
                </button>

            </div>

        </form>

    </div>


    @if($selectedTeacher)


        {{-- =================================================
             TEACHER INFORMATION
        ================================================== --}}

        <div class="teacher-info">

            <div class="teacher-info-left">

                <div class="teacher-avatar">

                    {{ strtoupper(substr($selectedTeacher->first_name, 0, 1)) }}

                </div>

                <div>

                    <h3>

                        {{ $selectedTeacher->first_name }}
                        {{ $selectedTeacher->last_name }}

                    </h3>

                    <span>

                        Teacher ID:
                        {{ $selectedTeacher->teacher_id ?? 'N/A' }}

                    </span>

                </div>

            </div>


            <div class="teacher-status">

                <span class="teacher-status-dot"></span>

                Live Timetable

            </div>

        </div>


        @if($timetables->count())


            @php

                /*
                |--------------------------------------------------------------------------
                | DAYS
                |--------------------------------------------------------------------------
                */

                $days = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ];


                /*
                |--------------------------------------------------------------------------
                | GROUP TIMETABLE BY PERIOD
                |--------------------------------------------------------------------------
                */

                $periods = $timetables
                    ->sortBy(function ($item) {
                        return (int) $item->period_number;
                    })
                    ->groupBy(function ($item) {
                        return (string) $item->period_number;
                    });


                /*
                |--------------------------------------------------------------------------
                | SORT PERIOD NUMBERS
                |--------------------------------------------------------------------------
                */

                $periodNumbers = $periods
                    ->keys()
                    ->sort(function ($a, $b) {

                        if (is_numeric($a) && is_numeric($b)) {

                            return (int) $a <=> (int) $b;

                        }

                        return strcmp($a, $b);

                    })
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | FIRST ENTRY
                |--------------------------------------------------------------------------
                */

                $firstTimetable = $timetables->first();


                /*
                |--------------------------------------------------------------------------
                | ACADEMIC YEAR
                |--------------------------------------------------------------------------
                */

                $academicYear =
                    $firstTimetable->academic_year ?? 'N/A';

            @endphp


            {{-- =================================================
                 PRINT HEADER
            ================================================== --}}

            <div class="print-header">

                <div class="print-school-name">
                    Gurukul Vidyalaya
                </div>

                <div class="print-title">
                    Teacher Weekly Timetable
                </div>


                <table class="print-details">

                    <tr>

                        <td class="label">
                            Teacher
                        </td>

                        <td>

                            {{ $selectedTeacher->first_name }}
                            {{ $selectedTeacher->last_name }}

                        </td>


                        <td class="label">
                            Teacher ID
                        </td>

                        <td>
                            {{ $selectedTeacher->teacher_id ?? 'N/A' }}
                        </td>


                        <td class="label">
                            Academic Year
                        </td>

                        <td>
                            {{ $academicYear }}
                        </td>

                    </tr>

                </table>

            </div>


            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="timetable-actions">

                <button
                    type="button"
                    class="action-button share"
                    onclick="shareTeacherTimetable()"
                >
                    ↗ Share
                </button>


                <button
                    type="button"
                    class="action-button print"
                    onclick="window.print()"
                >
                    🖨 Print
                </button>

            </div>


            {{-- =================================================
                 WEEK LABEL
            ================================================== --}}

            <div class="week-label">

                Weekly Schedule

                <strong>
                    • {{ $academicYear }}
                </strong>

                <span id="live-date-time"></span>

            </div>


            {{-- =================================================
                 TIMETABLE
            ================================================== --}}

            <div class="weekly-card">

                <table class="weekly-table">

                    <thead>

                        <tr>

                            <th class="period-heading">
                                Period
                            </th>


                            <th class="time-heading">
                                Time
                            </th>


                            @foreach($days as $day)

                                <th
    class="day-header"
    data-day="{{ $day }}"
>
                                    {{ $day }}
                                </th>

                            @endforeach

                        </tr>

                    </thead>


                    <tbody>


                        @foreach($periodNumbers as $periodNumber)


                            @php

                                $periodEntries =
                                    $periods[$periodNumber];


                                /*
                                |--------------------------------------------------------------------------
                                | FIND FIRST TIME FOR PERIOD
                                |--------------------------------------------------------------------------
                                */

                                $periodTimeEntry =
                                    $periodEntries
                                        ->filter(function ($item) {
                                            return $item->start_time &&
                                                   $item->end_time;
                                        })
                                        ->sortBy('start_time')
                                        ->first();

                            @endphp


                            <tr
                                data-period="{{ $periodNumber }}"
                            >


                                {{-- =============================================
                                     PERIOD NUMBER
                                ============================================== --}}

                                <td class="period-cell">

                                    <span class="period-number">

                                        {{ is_numeric($periodNumber)
                                            ? 'P' . $periodNumber
                                            : $periodNumber
                                        }}

                                    </span>

                                </td>


                                {{-- =============================================
                                     TIME
                                ============================================== --}}

                                <td class="time-cell">

                                    @if(
                                        $periodTimeEntry &&
                                        $periodTimeEntry->start_time &&
                                        $periodTimeEntry->end_time
                                    )

                                        <div class="time-from">

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $periodTimeEntry->start_time
                                                )->format('h:i A')
                                            }}

                                        </div>

                                        <div class="time-arrow">
                                            ↓
                                        </div>

                                        <div class="time-to">

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $periodTimeEntry->end_time
                                                )->format('h:i A')
                                            }}

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- =============================================
                                     EACH DAY
                                ============================================== --}}

                                @foreach($days as $day)


                                    @php

                                        $lecture =
                                            $timetables
                                                ->where('day', $day)
                                                ->where(
                                                    'period_number',
                                                    $periodNumber
                                                )
                                                ->first();

                                    @endphp


                                    {{-- =========================================
                                         FREE
                                    ========================================== --}}

                                    @if(!$lecture)

                                        <td
                                            class="free-cell"
                                            data-day="{{ $day }}"
                                            data-period="{{ $periodNumber }}"
                                        >

                                            Free

                                        </td>


                                    {{-- =========================================
                                         BREAK
                                    ========================================== --}}

                                    @elseif($lecture->period_type === 'Break')

                                        <td
                                            class="break-cell"
                                            data-day="{{ $day }}"
                                            data-period="{{ $periodNumber }}"
                                            data-start="{{ $lecture->start_time }}"
                                            data-end="{{ $lecture->end_time }}"
                                        >

                                            Break

                                        </td>


                                    {{-- =========================================
                                         LUNCH
                                    ========================================== --}}

                                    @elseif($lecture->period_type === 'Lunch')

                                        <td
                                            class="lunch-cell"
                                            data-day="{{ $day }}"
                                            data-period="{{ $periodNumber }}"
                                            data-start="{{ $lecture->start_time }}"
                                            data-end="{{ $lecture->end_time }}"
                                        >

                                            Lunch

                                        </td>


                                    {{-- =========================================
                                         NORMAL LECTURE
                                    ========================================== --}}

                          @else
    <td
        class="lecture-cell"
        data-day="{{ $day }}"
        data-period="{{ $periodNumber }}"
        data-start="{{ \Carbon\Carbon::parse($lecture->start_time)->format('H:i:s') }}"
        data-end="{{ \Carbon\Carbon::parse($lecture->end_time)->format('H:i:s') }}"
        data-subject="{{ $lecture->subject }}"
        data-start-display="{{ \Carbon\Carbon::parse($lecture->start_time)->format('h:i A') }}"
    >

                                            {{-- LIVE STATUS --}}

                                            <div class="live-status-area"></div>


                                            {{-- SUBJECT --}}

                                            <div class="lecture-subject">

                                                {{ $lecture->subject }}

                                            </div>


                                            {{-- CLASS --}}

                                            @if($lecture->class)

                                                <div class="lecture-class">

                                                    {{ $lecture->class }}

                                                    @if($lecture->section)

                                                        -
                                                        Section
                                                        {{ $lecture->section }}

                                                    @endif

                                                </div>

                                            @endif


                                            {{-- ROOM --}}

                                            @if($lecture->room)

                                                <div class="lecture-room">

                                                    Room:
                                                    {{ $lecture->room }}

                                                </div>

                                            @endif


                                            {{-- TIME --}}

                                            @if(
                                                $lecture->start_time &&
                                                $lecture->end_time
                                            )

                                                <div class="lecture-time">

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $lecture->start_time
                                                        )->format('h:i A')
                                                    }}

                                                    -

                                                    {{
                                                        \Carbon\Carbon::parse(
                                                            $lecture->end_time
                                                        )->format('h:i A')
                                                    }}

                                                </div>

                                            @endif


                                            {{-- LECTURE TYPE --}}

                                            @if($lecture->lecture_type)

                                                <span class="lecture-type">

                                                    {{ ucfirst($lecture->lecture_type) }}

                                                </span>

                                            @endif

                                        </td>

                                    @endif

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>


                {{-- =================================================
                     LEGEND
                ================================================== --}}

                <div class="timetable-legend">

                    <div class="legend-item">

                        <span class="legend-box legend-now"></span>

                        Current Lecture

                    </div>


                    <div class="legend-item">

                        <span class="legend-box legend-upcoming"></span>

                        Upcoming

                    </div>


                    <div class="legend-item">

                        <span class="legend-box legend-free"></span>

                        Free Period

                    </div>

                </div>


                {{-- =================================================
                     PRINT FOOTER
                ================================================== --}}

                <div class="print-footer">

                    Gurukul Vidyalaya
                    •
                    Teacher Weekly Timetable
                    •
                    Generated on
                    {{ now()->format('d-m-Y h:i A') }}

                </div>

            </div>


        @else


            {{-- =================================================
                 NO TIMETABLE
            ================================================== --}}

            <div class="no-data">

                <div class="no-data-icon">
                    📅
                </div>

                <h3>
                    No Timetable Found
                </h3>

                <p>
                    This teacher does not have any timetable entries yet.
                </p>

            </div>

        @endif


    @elseif(request('teacher_id'))


        {{-- =================================================
             TEACHER NOT FOUND
        ================================================== --}}

        <div class="no-data">

            <div class="no-data-icon">
                📅
            </div>

            <h3>
                Teacher Not Found
            </h3>

            <p>
                Please select a valid teacher.
            </p>

        </div>


    @else


        {{-- =================================================
             SELECT TEACHER
        ================================================== --}}

        <div class="no-data">

            <div class="no-data-icon">
                👨‍🏫
            </div>

            <h3>
                Select a Teacher
            </h3>

            <p>
                Choose a teacher from the dropdown above to view the weekly timetable.
            </p>

        </div>

    @endif

</div>
<script>

function updateLiveTimetable() {

    const now = new Date();

    const dayNames = [
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ];

    const currentDay = dayNames[now.getDay()];

    const currentMinutes =
        (now.getHours() * 60) +
        now.getMinutes() +
        (now.getSeconds() / 60);


    /*
    |--------------------------------------------------------------------------
    | LIVE DATE & TIME
    |--------------------------------------------------------------------------
    */

    const liveDateTime = document.getElementById('live-date-time');

    if (liveDateTime) {

        liveDateTime.textContent =
            ' • ' +
            now.toLocaleDateString('en-IN', {
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) +
            ' • ' +
            now.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR OLD LECTURE HIGHLIGHTS
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.lecture-cell').forEach(function(cell) {

        cell.classList.remove(
            'current-lecture',
            'upcoming-lecture'
        );

        cell.querySelectorAll(
            '.current-badge, .upcoming-badge'
        ).forEach(function(badge) {
            badge.remove();
        });
    });


    /*
    |--------------------------------------------------------------------------
    | CLEAR TODAY HEADER
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.day-header').forEach(function(header) {

        header.classList.remove('today-column');

    });


    /*
    |--------------------------------------------------------------------------
    | HIGHLIGHT TODAY
    |--------------------------------------------------------------------------
    */

    const todayHeader = document.querySelector(
        '.day-header[data-day="' + currentDay + '"]'
    );

    if (todayHeader) {

        todayHeader.classList.add('today-column');

    }


    /*
    |--------------------------------------------------------------------------
    | GET TODAY'S LECTURES
    |--------------------------------------------------------------------------
    */

    const todayCells = document.querySelectorAll(
        '.lecture-cell[data-day="' + currentDay + '"]'
    );


    let currentCells = [];

    let upcomingCell = null;

    let smallestUpcomingTime = Infinity;


    /*
    |--------------------------------------------------------------------------
    | CHECK EACH LECTURE
    |--------------------------------------------------------------------------
    */

    todayCells.forEach(function(cell) {

        const startTime =
            cell.getAttribute('data-start');

        const endTime =
            cell.getAttribute('data-end');


        if (!startTime || !endTime) {
            return;
        }


        const startMinutes =
            convertTimeToMinutes(startTime);

        const endMinutes =
            convertTimeToMinutes(endTime);


        if (
            startMinutes === null ||
            endMinutes === null
        ) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT
        |--------------------------------------------------------------------------
        */

        if (
            currentMinutes >= startMinutes &&
            currentMinutes < endMinutes
        ) {

            currentCells.push(cell);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | UPCOMING
        |--------------------------------------------------------------------------
        */

        if (startMinutes > currentMinutes) {

            if (startMinutes < smallestUpcomingTime) {

                smallestUpcomingTime = startMinutes;

                upcomingCell = cell;
            }
        }

    });


    /*
    |--------------------------------------------------------------------------
    | CURRENT LECTURE
    |--------------------------------------------------------------------------
    */

    currentCells.forEach(function(cell) {

        cell.classList.add('current-lecture');


        const badge =
            document.createElement('span');

        badge.className = 'current-badge';

        badge.innerHTML = '● NOW';


        cell.insertBefore(
            badge,
            cell.firstChild
        );

    });


    /*
    |--------------------------------------------------------------------------
    | NEXT UPCOMING LECTURE
    |--------------------------------------------------------------------------
    */

    if (upcomingCell) {

        upcomingCell.classList.add(
            'upcoming-lecture'
        );


        const badge =
            document.createElement('span');

        badge.className = 'upcoming-badge';

        badge.textContent = 'UPCOMING';


        upcomingCell.insertBefore(
            badge,
            upcomingCell.firstChild
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DEBUG
    |--------------------------------------------------------------------------
    */

    console.log(
        'Today:',
        currentDay,
        '| Current time:',
        currentMinutes,
        '| Upcoming:',
        upcomingCell
            ? upcomingCell.getAttribute('data-subject')
            : 'None'
    );

}


/*
|--------------------------------------------------------------------------
| TIME CONVERTER
|--------------------------------------------------------------------------
*/

function convertTimeToMinutes(timeString) {

    if (!timeString) {
        return null;
    }


    const parts =
        String(timeString)
            .trim()
            .split(':');


    if (parts.length < 2) {
        return null;
    }


    const hours =
        Number(parts[0]);

    const minutes =
        Number(parts[1]);

    const seconds =
        parts.length >= 3
            ? Number(parts[2])
            : 0;


    if (
        Number.isNaN(hours) ||
        Number.isNaN(minutes) ||
        Number.isNaN(seconds)
    ) {
        return null;
    }


    return (
        (hours * 60) +
        minutes +
        (seconds / 60)
    );
}


/*
|--------------------------------------------------------------------------
| START
|--------------------------------------------------------------------------
*/

updateLiveTimetable();


/*
|--------------------------------------------------------------------------
| CHECK EVERY SECOND
|--------------------------------------------------------------------------
*/

setInterval(
    updateLiveTimetable,
    1000
);

</script>
@endsection