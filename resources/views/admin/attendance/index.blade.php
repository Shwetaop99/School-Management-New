@extends('layouts.app')

@section('title', 'Faculty Attendance')
@section('page-title', 'Faculty Attendance')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    body {
        background: #f4f7fb;
    }

    .attendance-page {
        min-height: calc(100vh - 80px);
        padding: 16px;
    }

    .attendance-container {
        width: 100%;
        max-width: 1550px;
        margin: auto;
    }

    /* =====================================================
       HEADER
    ===================================================== */

    .attendance-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        margin-bottom: 14px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1769d1, #6c63ff);
        color: white;
        box-shadow: 0 5px 18px rgba(23, 105, 209, .12);
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .header-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: rgba(255,255,255,.16);
        font-size: 17px;
    }

    .header-title {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
    }

    .header-subtitle {
        margin: 2px 0 0;
        font-size: 9px;
        opacity: .8;
    }

    .header-month {
        font-size: 13px;
        font-weight: 700;
    }

    /* =====================================================
       FILTER
    ===================================================== */

    .filter-card {
        display: flex;
        align-items: end;
        gap: 10px;
        padding: 13px;
        margin-bottom: 13px;
        background: white;
        border: 1px solid #e4e9ef;
        border-radius: 10px;
    }

    .filter-form {
        width: 100%;
        display: flex;
        align-items: end;
        gap: 10px;
    }

    .filter-item {
        flex: 1;
    }

    .filter-item.month {
        max-width: 170px;
    }

    .filter-item label {
        display: block;
        margin-bottom: 4px;
        color: #64748b;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .filter-control {
        width: 100%;
        height: 34px;
        padding: 0 9px;
        border: 1px solid #dce3eb;
        border-radius: 7px;
        background: white;
        color: #334155;
        font-size: 10px;
        outline: none;
    }

    .filter-control:focus {
        border-color: #1769d1;
    }

    .filter-button {
        height: 34px;
        padding: 0 14px;
        border: none;
        border-radius: 7px;
        background: #1769d1;
        color: white;
        font-size: 9px;
        font-weight: 800;
        cursor: pointer;
    }

    .filter-button:hover {
        background: #125bb7;
    }

    /* =====================================================
       MONTH BAR
    ===================================================== */

    .month-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .month-title {
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
    }

    .month-buttons {
        display: flex;
        gap: 4px;
    }

    .month-button {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dce3eb;
        border-radius: 6px;
        background: white;
        color: #64748b;
        text-decoration: none;
        font-size: 10px;
    }

    .month-button:hover {
        color: #1769d1;
        border-color: #1769d1;
    }

    /* =====================================================
       FACULTY INFO
    ===================================================== */

    .faculty-info {
        display: flex;
        align-items: center;
        gap: 28px;
        padding: 11px 14px;
        margin-bottom: 12px;
        background: white;
        border: 1px solid #e4e9ef;
        border-radius: 9px;
    }

    .info-block {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-label {
        color: #94a3b8;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .info-value {
        color: #334155;
        font-size: 10px;
        font-weight: 700;
    }

    /* =====================================================
       TABLE CARD
    ===================================================== */

    .table-card {
        overflow: hidden;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 13px;
        border-bottom: 1px solid #e8edf3;
    }

    .table-title {
        margin: 0;
        color: #1e293b;
        font-size: 11px;
        font-weight: 800;
    }

    .table-help {
        margin: 2px 0 0;
        color: #94a3b8;
        font-size: 8px;
    }

    /* =====================================================
       TABLE
    ===================================================== */

    .table-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        min-width: max-content;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }

    .attendance-table th {
        height: 42px;
        padding: 2px;
        background: #f8fafc;
        border-right: 1px solid #edf1f5;
        border-bottom: 1px solid #dfe5ec;
        text-align: center;
        vertical-align: middle;
    }

    .attendance-table td {
        height: 43px;
        padding: 2px;
        background: white;
        border-right: 1px solid #edf1f5;
        border-bottom: 1px solid #edf1f5;
        text-align: center;
        vertical-align: middle;
    }

    .attendance-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =====================================================
       COLUMNS
    ===================================================== */

    .faculty-column {
        width: 150px;
        min-width: 150px;
        max-width: 150px;
        text-align: left !important;
    }

    .id-column {
        width: 58px;
        min-width: 58px;
        max-width: 58px;
    }

    .date-column {
        width: 36px;
        min-width: 36px;
        max-width: 36px;
    }

    .summary-column {
        width: 125px;
        min-width: 125px;
        max-width: 125px;
    }

    /* =====================================================
       STICKY
    ===================================================== */

    .sticky-faculty {
        position: sticky;
        left: 0;
        z-index: 5;
        background: white !important;
    }

    .sticky-id {
        position: sticky;
        left: 150px;
        z-index: 5;
        background: white !important;
    }

    .sticky-summary {
        position: sticky;
        right: 0;
        z-index: 5;
        background: white !important;
        border-left: 1px solid #dfe5ec !important;
    }

    thead .sticky-faculty,
    thead .sticky-id,
    thead .sticky-summary {
        z-index: 10;
        background: #f8fafc !important;
    }

    /* =====================================================
       DATE HEADER
    ===================================================== */

    .date-number {
        display: block;
        color: #334155;
        font-size: 9px;
        font-weight: 800;
        line-height: 11px;
    }

    .day-name {
        display: block;
        color: #94a3b8;
        font-size: 6px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* =====================================================
       HOLIDAY HEADER
    ===================================================== */

    .holiday-header {
        background: #fff1f2 !important;
        border-right-color: #fecdd3 !important;
        border-bottom-color: #fecdd3 !important;
    }

    .holiday-header .date-number {
        color: #be123c;
    }

    .holiday-header .day-name {
        color: #e11d48;
    }

    .holiday-name {
        display: block;
        margin-top: 1px;
        color: #be123c;
        font-size: 5px;
        font-weight: 900;
        line-height: 6px;
        text-transform: uppercase;
    }

    /* =====================================================
       FACULTY
    ===================================================== */

    .faculty-name {
        padding-left: 11px;
        color: #1e293b;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .faculty-subject {
        padding-left: 11px;
        margin-top: 1px;
        color: #94a3b8;
        font-size: 7px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .faculty-id {
        color: #64748b;
        font-size: 8px;
        font-weight: 700;
    }

    /* =====================================================
       STATUS CELL
    ===================================================== */

    .attendance-cell {
        width: 36px;
        min-width: 36px;
        height: 43px;
        padding: 0 !important;
    }

    .attendance-status-button {
        width: 28px;
        height: 28px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 7px;
        background: #f8fafc;
        color: #94a3b8;
        font-size: 7px;
        font-weight: 900;
        cursor: pointer;
        transition: all .15s ease;
        user-select: none;
    }

    .attendance-status-button:hover {
        transform: scale(1.05);
        border-color: #cbd5e1;
    }

    /* =====================================================
       BLANK
    ===================================================== */

    .status-blank {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #cbd5e1;
    }

    /* =====================================================
       PRESENT
    ===================================================== */

    .status-present {
        background: #e9f8f0;
        border-color: #49b981;
        color: #16834d;
    }

    /* =====================================================
       HALF DAY
    ===================================================== */

    .status-half {
        background: #fff7df;
        border-color: #e7b93c;
        color: #a56b00;
    }

    /* =====================================================
       ABSENT
    ===================================================== */

    .status-absent {
        background: #fff0f1;
        border-color: #e5737d;
        color: #c92f3b;
    }

    /* =====================================================
       HOLIDAY CELL
    ===================================================== */

    .holiday-cell {
        background: #fff7f8 !important;
        border-right-color: #fecdd3 !important;
        border-bottom-color: #fecdd3 !important;
    }

    .holiday-button {
        width: 28px;
        height: 28px;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fda4af;
        border-radius: 7px;
        background: #ffe4e6;
        color: #be123c;
        font-size: 6px;
        font-weight: 900;
        cursor: not-allowed;
    }

    /* =====================================================
       SUMMARY
    ===================================================== */

    .summary-box {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 11px;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 20px;
    }

    .summary-label {
        color: #94a3b8;
        font-size: 6px;
        font-weight: 800;
    }

    .summary-value {
        margin-top: 1px;
        color: #334155;
        font-size: 9px;
        font-weight: 800;
    }

    .present-count {
        color: #16834d;
    }

    .half-count {
        color: #a56b00;
    }

    .absent-count {
        color: #c92f3b;
    }

    .holiday-count {
        color: #be123c;
    }

    .percentage-value {
        color: #6c63ff;
    }

    /* =====================================================
       FOOTER
    ===================================================== */

    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 13px;
        border-top: 1px solid #e8edf3;
    }

    .legend {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #64748b;
        font-size: 8px;
    }

    .legend-dot {
        width: 18px;
        height: 10px;
        border-radius: 4px;
        background: #f8fafc;
        border: 1px solid #d7dee7;
    }

    .legend-dot.present {
        background: #e9f8f0;
        border-color: #49b981;
    }

    .legend-dot.half {
        background: #fff7df;
        border-color: #e7b93c;
    }

    .legend-dot.absent {
        background: #fff0f1;
        border-color: #e5737d;
    }

    .legend-dot.holiday {
        background: #ffe4e6;
        border-color: #fda4af;
    }

    .save-button {
        height: 32px;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: none;
        border-radius: 7px;
        background: #1769d1;
        color: white;
        font-size: 8px;
        font-weight: 800;
        cursor: pointer;
    }

    .save-button:hover {
        background: #125bb7;
    }

    .save-button:disabled {
        opacity: .7;
        cursor: not-allowed;
    }

    /* =====================================================
       EMPTY
    ===================================================== */

    .empty-state {
        padding: 40px;
        text-align: center;
    }

    .empty-state i {
        display: block;
        margin-bottom: 8px;
        color: #94a3b8;
        font-size: 25px;
    }

    .empty-state h4 {
        margin: 0 0 3px;
        color: #334155;
        font-size: 13px;
    }

    .empty-state p {
        margin: 0;
        color: #94a3b8;
        font-size: 9px;
    }

    /* =====================================================
       MOBILE
    ===================================================== */

    @media (max-width: 800px) {

        .attendance-page {
            padding: 10px;
        }

        .attendance-header {
            padding: 14px;
        }

        .header-month {
            display: none;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-item.month {
            max-width: none;
        }

        .faculty-info {
            flex-wrap: wrap;
            gap: 10px 18px;
        }

        .table-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 9px;
        }

        .save-button {
            justify-content: center;
        }
    }
</style>

<div class="attendance-page">

    <div class="attendance-container">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="attendance-header">

            <div class="header-left">

                <div class="header-icon">
                    <i class="bi bi-calendar2-check"></i>
                </div>

                <div>

                    <h1 class="header-title">
                        Faculty Attendance
                    </h1>

                    <p class="header-subtitle">
                        Monthly Attendance Register
                    </p>

                </div>

            </div>

            <div class="header-month">
                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
            </div>

        </div>


        {{-- =====================================================
             FILTER
        ====================================================== --}}

        <div class="filter-card">

            <form
                method="GET"
                action="{{ route('admin.teachers.attendance.index') }}"
                class="filter-form"
            >

                <div class="filter-item">

                    <label for="teacher_id">
                        Faculty
                    </label>

                    <select
                        name="teacher_id"
                        id="teacher_id"
                        class="filter-control"
                    >

                        <option value="">
                            All Faculty
                        </option>

                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}
                            >
                                {{ trim($teacher->first_name . ' ' . $teacher->last_name) }}

                                @if($teacher->teacher_id)
                                    — {{ $teacher->teacher_id }}
                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="filter-item month">

                    <label for="month">
                        Month
                    </label>

                    <input
                        type="month"
                        name="month"
                        id="month"
                        class="filter-control"
                        value="{{ $month }}"
                    >

                </div>


                <button
                    type="submit"
                    class="filter-button"
                >
                    <i class="bi bi-funnel-fill"></i>
                    Apply
                </button>

            </form>

        </div>


        {{-- =====================================================
             MONTH NAVIGATION
        ====================================================== --}}

        @php

            $currentMonth = \Carbon\Carbon::createFromFormat(
                'Y-m',
                $month
            );

            $previousMonth = $currentMonth
                ->copy()
                ->subMonth()
                ->format('Y-m');

            $nextMonth = $currentMonth
                ->copy()
                ->addMonth()
                ->format('Y-m');

            /*
            |--------------------------------------------------------------------------
            | National Holidays
            |--------------------------------------------------------------------------
            */

            $nationalHolidays = [
                '01-26' => 'Republic Day',
                '08-15' => 'Independence Day',
                '10-02' => 'Gandhi Jayanti',
            ];

        @endphp


        <div class="month-bar">

            <div class="month-title">
                {{ $currentMonth->format('F Y') }}
            </div>

            <div class="month-buttons">

                <a
                    href="{{ route('admin.teachers.attendance.index', [
                        'month' => $previousMonth,
                        'teacher_id' => request('teacher_id')
                    ]) }}"
                    class="month-button"
                    title="Previous Month"
                >
                    <i class="bi bi-chevron-left"></i>
                </a>

                <a
                    href="{{ route('admin.teachers.attendance.index', [
                        'month' => $nextMonth,
                        'teacher_id' => request('teacher_id')
                    ]) }}"
                    class="month-button"
                    title="Next Month"
                >
                    <i class="bi bi-chevron-right"></i>
                </a>

            </div>

        </div>


        {{-- =====================================================
             SELECTED FACULTY
        ====================================================== --}}

        @if($selectedTeacher)

            <div class="faculty-info">

                <div class="info-block">

                    <span class="info-label">
                        Faculty
                    </span>

                    <span class="info-value">
                        {{ trim(
                            $selectedTeacher->first_name . ' ' .
                            $selectedTeacher->last_name
                        ) }}
                    </span>

                </div>

                <div class="info-block">

                    <span class="info-label">
                        ID
                    </span>

                    <span class="info-value">
                        {{ $selectedTeacher->teacher_id ?? '-' }}
                    </span>

                </div>

                <div class="info-block">

                    <span class="info-label">
                        Subject
                    </span>

                    <span class="info-value">
                        {{ $selectedTeacher->subject ?? '-' }}
                    </span>

                </div>

            </div>

        @endif


        {{-- =====================================================
             ATTENDANCE
        ====================================================== --}}

        @if($teachers->count())

            <form
                method="POST"
                action="{{ route('admin.teachers.attendance.store') }}"
                id="attendanceForm"
            >

                @csrf

                <input
                    type="hidden"
                    name="attendance_month"
                    value="{{ $month }}"
                >

                @if(request('teacher_id'))

                    <input
                        type="hidden"
                        name="teacher_id"
                        value="{{ request('teacher_id') }}"
                    >

                @endif


                <div class="table-card">

                    <div class="table-header">

                        <div>

                            <h3 class="table-title">
                                Attendance Register
                            </h3>

                            <p class="table-help">
                                Click a working day to cycle:
                                Present → Half Day → Absent → Blank
                            </p>

                        </div>

                    </div>


                    <div class="table-scroll">

                        <table class="attendance-table">

                            <thead>

                                <tr>

                                    <th class="faculty-column sticky-faculty">
                                        Faculty
                                    </th>

                                    <th class="id-column sticky-id">
                                        ID
                                    </th>


                                    {{-- =================================================
                                         DATE HEADERS
                                    ================================================== --}}

                                    @foreach($dates as $date)

                                        @php

                                            $holidayName = null;

                                            if ($date->isSunday()) {

                                                $holidayName = 'Sunday';

                                            } elseif (
                                                isset(
                                                    $nationalHolidays[
                                                        $date->format('m-d')
                                                    ]
                                                )
                                            ) {

                                                $holidayName =
                                                    $nationalHolidays[
                                                        $date->format('m-d')
                                                    ];

                                            }

                                            $isHoliday =
                                                $holidayName !== null;

                                        @endphp


                                        <th
                                            class="date-column {{ $isHoliday ? 'holiday-header' : '' }}"
                                            title="{{ $isHoliday ? $holidayName : $date->format('l') }}"
                                        >

                                            <span class="date-number">
                                                {{ $date->format('d') }}
                                            </span>

                                            <span class="day-name">
                                                {{ $date->format('D') }}
                                            </span>

                                            @if($isHoliday)

                                                <span class="holiday-name">
                                                    HOL
                                                </span>

                                            @endif

                                        </th>

                                    @endforeach


                                    <th class="summary-column sticky-summary">
                                        Summary
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($teachers as $teacher)

                                    @php

                                        $teacherPresent = 0;
                                        $teacherHalf = 0;
                                        $teacherAbsent = 0;
                                        $teacherHoliday = 0;

                                    @endphp


                                    <tr>

                                        {{-- FACULTY --}}

                                        <td class="faculty-column sticky-faculty">

                                            <div class="faculty-name">

                                                {{ trim(
                                                    $teacher->first_name . ' ' .
                                                    $teacher->last_name
                                                ) }}

                                            </div>

                                            <div class="faculty-subject">
                                                {{ $teacher->subject ?? 'Faculty' }}
                                            </div>

                                        </td>


                                        {{-- ID --}}

                                        <td class="id-column sticky-id">

                                            <span class="faculty-id">
                                                {{ $teacher->teacher_id ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- =================================================
                                             DATES
                                        ================================================== --}}

                                        @foreach($dates as $date)

                                            @php

                                                $dateKey =
                                                    $date->format('Y-m-d');

                                                $recordKey =
                                                    $teacher->id . '_' .
                                                    $dateKey;

                                                $attendance =
                                                    $attendanceRecords[$recordKey]
                                                    ?? null;

                                                $status =
                                                    $attendance?->status ?? '';

                                                /*
                                                |--------------------------------------------------------------------------
                                                | Holiday
                                                |--------------------------------------------------------------------------
                                                */

                                                $holidayName = null;

                                                if ($date->isSunday()) {

                                                    $holidayName = 'Sunday';

                                                } elseif (
                                                    isset(
                                                        $nationalHolidays[
                                                            $date->format('m-d')
                                                        ]
                                                    )
                                                ) {

                                                    $holidayName =
                                                        $nationalHolidays[
                                                            $date->format('m-d')
                                                        ];

                                                }

                                                $isHoliday =
                                                    $holidayName !== null;

                                                /*
                                                |--------------------------------------------------------------------------
                                                | Count only working-day attendance
                                                |--------------------------------------------------------------------------
                                                */

                                                if (!$isHoliday) {

                                                    if ($status === 'Present') {
                                                        $teacherPresent++;
                                                    }

                                                    if ($status === 'Half Day') {
                                                        $teacherHalf++;
                                                    }

                                                    if ($status === 'Absent') {
                                                        $teacherAbsent++;
                                                    }

                                                }

                                                if ($isHoliday) {
                                                    $teacherHoliday++;
                                                }

                                            @endphp


                                            {{-- =================================================
                                                 HOLIDAY CELL
                                            ================================================== --}}

                                            @if($isHoliday)

                                                <td
                                                    class="attendance-cell holiday-cell"
                                                    title="{{ $holidayName }}"
                                                >

                                                    <button
                                                        type="button"
                                                        class="holiday-button"
                                                        disabled
                                                        title="{{ $holidayName }}"
                                                    >
                                                        HOL
                                                    </button>

                                                </td>


                                            {{-- =================================================
                                                 WORKING DAY CELL
                                            ================================================== --}}

                                            @else

                                                <td class="attendance-cell">

                                                    <input
                                                        type="hidden"
                                                        class="attendance-input"
                                                        name="attendance[{{ $teacher->id }}][{{ $dateKey }}]"
                                                        value="{{ $status }}"
                                                    >

                                                    <button
                                                        type="button"
                                                        class="attendance-status-button status-{{ strtolower(str_replace(' ', '-', $status ?: 'blank')) }}"
                                                        data-status="{{ $status }}"
                                                        title="{{ $status ?: 'Not Marked' }}"
                                                    >

                                                        <span class="status-text">

                                                            @switch($status)

                                                                @case('Present')
                                                                    P
                                                                    @break

                                                                @case('Half Day')
                                                                    H
                                                                    @break

                                                                @case('Absent')
                                                                    A
                                                                    @break

                                                                @default
                                                                    •

                                                            @endswitch

                                                        </span>

                                                    </button>

                                                </td>

                                            @endif

                                        @endforeach


                                        {{-- =================================================
                                             SUMMARY
                                        ================================================== --}}

                                        @php

                                            $marked =
                                                $teacherPresent +
                                                $teacherHalf +
                                                $teacherAbsent;

                                            $attendancePoints =
                                                $teacherPresent +
                                                ($teacherHalf * 0.5);

                                            $percentage =
                                                $marked > 0
                                                    ? round(
                                                        ($attendancePoints / $marked) * 100,
                                                        1
                                                    )
                                                    : 0;

                                        @endphp


                                        <td class="summary-column sticky-summary">

                                            <div class="summary-box">

                                                <div class="summary-item">

                                                    <span class="summary-label">
                                                        P
                                                    </span>

                                                    <span class="summary-value present-count">
                                                        {{ $teacherPresent }}
                                                    </span>

                                                </div>


                                                <div class="summary-item">

                                                    <span class="summary-label">
                                                        H
                                                    </span>

                                                    <span class="summary-value half-count">
                                                        {{ $teacherHalf }}
                                                    </span>

                                                </div>


                                                <div class="summary-item">

                                                    <span class="summary-label">
                                                        A
                                                    </span>

                                                    <span class="summary-value absent-count">
                                                        {{ $teacherAbsent }}
                                                    </span>

                                                </div>


                                                <div class="summary-item">

                                                    <span class="summary-label">
                                                        %
                                                    </span>

                                                    <span class="summary-value percentage-value">
                                                        {{ number_format($percentage, 1) }}%
                                                    </span>

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =====================================================
                         FOOTER
                    ====================================================== --}}

                    <div class="table-footer">

                        <div class="legend">

                            <div class="legend-item">

                                <span class="legend-dot present"></span>

                                Present

                            </div>


                            <div class="legend-item">

                                <span class="legend-dot half"></span>

                                Half Day

                            </div>


                            <div class="legend-item">

                                <span class="legend-dot absent"></span>

                                Absent

                            </div>


                            <div class="legend-item">

                                <span class="legend-dot holiday"></span>

                                Holiday

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="save-button"
                        >

                            <i class="bi bi-check2"></i>

                            Save Attendance

                        </button>

                    </div>

                </div>

            </form>

        @else

            <div class="table-card">

                <div class="empty-state">

                    <i class="bi bi-person-x"></i>

                    <h4>
                        No Faculty Found
                    </h4>

                    <p>
                        No faculty members are available.
                    </p>

                </div>

            </div>

        @endif

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    =========================================================
    STATUS ORDER

    Blank
       ↓
    Present
       ↓
    Half Day
       ↓
    Absent
       ↓
    Blank
    =========================================================
    */

    const statuses = [
        '',
        'Present',
        'Half Day',
        'Absent'
    ];

    const statusClasses = {
        '': 'status-blank',
        'Present': 'status-present',
        'Half Day': 'status-half',
        'Absent': 'status-absent'
    };

    const statusLetters = {
        '': '•',
        'Present': 'P',
        'Half Day': 'H',
        'Absent': 'A'
    };


    /*
    =========================================================
    GET NEXT STATUS
    =========================================================
    */

    function getNextStatus(currentStatus) {

        const currentIndex =
            statuses.indexOf(currentStatus);

        if (currentIndex === -1) {
            return 'Present';
        }

        return statuses[
            (currentIndex + 1) % statuses.length
        ];
    }


    /*
    =========================================================
    UPDATE CELL
    =========================================================
    */

    function updateAttendanceCell(button) {

        const cell =
            button.closest('.attendance-cell');

        if (!cell) {
            return;
        }

        /*
        |------------------------------------------------------
        | Holiday cells have no attendance input and cannot
        | be changed.
        |------------------------------------------------------
        */

        if (
            cell.classList.contains('holiday-cell')
        ) {
            return;
        }

        const input =
            cell.querySelector('.attendance-input');

        const text =
            button.querySelector('.status-text');

        if (!input || !text) {
            return;
        }

        const currentStatus =
            input.value || '';

        const nextStatus =
            getNextStatus(currentStatus);

        input.value = nextStatus;


        Object.values(statusClasses).forEach(function (className) {

            button.classList.remove(className);

        });


        button.classList.add(
            statusClasses[nextStatus]
        );


        text.textContent =
            statusLetters[nextStatus];


        button.title =
            nextStatus || 'Not Marked';


        updateRowSummary(
            button.closest('tr')
        );

    }


    /*
    =========================================================
    CLICK HANDLER
    =========================================================
    */

    document
        .querySelectorAll('.attendance-status-button')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    updateAttendanceCell(this);

                }
            );

        });


    /*
    =========================================================
    UPDATE ROW SUMMARY
    =========================================================
    */

    function updateRowSummary(row) {

        if (!row) {
            return;
        }

        const inputs =
            row.querySelectorAll('.attendance-input');

        let present = 0;
        let half = 0;
        let absent = 0;


        inputs.forEach(function (input) {

            switch (input.value) {

                case 'Present':

                    present++;

                    break;

                case 'Half Day':

                    half++;

                    break;

                case 'Absent':

                    absent++;

                    break;

            }

        });


        const marked =
            present +
            half +
            absent;


        const attendancePoints =
            present +
            (half * 0.5);


        const percentage =
            marked > 0
                ? ((attendancePoints / marked) * 100).toFixed(1)
                : '0.0';


        const presentCount =
            row.querySelector('.present-count');

        const halfCount =
            row.querySelector('.half-count');

        const absentCount =
            row.querySelector('.absent-count');

        const percentageValue =
            row.querySelector('.percentage-value');


        if (presentCount) {

            presentCount.textContent =
                present;

        }


        if (halfCount) {

            halfCount.textContent =
                half;

        }


        if (absentCount) {

            absentCount.textContent =
                absent;

        }


        if (percentageValue) {

            percentageValue.textContent =
                percentage + '%';

        }

    }


    /*
    =========================================================
    FORM SUBMIT
    =========================================================
    */

    const attendanceForm =
        document.getElementById('attendanceForm');


    if (attendanceForm) {

        attendanceForm.addEventListener(
            'submit',
            function () {

                const button =
                    this.querySelector('.save-button');

                if (button) {

                    button.disabled = true;

                    button.innerHTML =
                        '<i class="bi bi-arrow-repeat"></i> Saving...';

                }

            }
        );

    }


});
</script>

@endsection