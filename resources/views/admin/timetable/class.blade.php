@extends('layouts.app')

@section('title', 'Class Timetable')

@section('page-title', 'Class Timetable')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>

/* =========================================================
   CLASS TIMETABLE PAGE
========================================================= */

.class-timetable-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 20px;
    background: #f4f7fb;
    min-height: calc(100vh - 120px);
}

/* =========================================================
   PAGE HEADER
========================================================= */

.class-timetable-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}

.class-timetable-header-left h2 {
    margin: 0;
    color: #1e293b;
    font-size: 23px;
    font-weight: 700;
}

.class-timetable-header-left p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 12px;
}

/* =========================================================
   SELECTION CARD
========================================================= */

.selection-card {
    background: #ffffff;
    border: 1px solid #e4eaf2;
    border-radius: 14px;
    padding: 18px;
    box-shadow: 0 4px 15px rgba(30, 64, 175, 0.06);
    margin-bottom: 18px;
}

.selection-card-header {
    margin-bottom: 14px;
}

.selection-card-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 16px;
    font-weight: 700;
}

.selection-card-header p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 11px;
}

/* =========================================================
   FORM
========================================================= */

.selection-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px;
    align-items: end;
}

.form-group-custom {
    display: flex;
    flex-direction: column;
}

.form-group-custom label {
    margin-bottom: 6px;
    color: #334155;
    font-size: 11px;
    font-weight: 700;
}

.form-control-custom {
    width: 100%;
    height: 40px;
    padding: 0 11px;
    border: 1px solid #d7e0eb;
    border-radius: 8px;
    background: #ffffff;
    color: #334155;
    font-size: 12px;
    outline: none;
    transition: all 0.2s ease;
}

.form-control-custom:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.08);
}

.form-control-custom:hover {
    border-color: #b8c7da;
}

/* =========================================================
   VIEW BUTTON
========================================================= */

.view-timetable-btn {
    height: 40px;
    padding: 0 17px;
    border: none;
    border-radius: 8px;
    background: #147cf5;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

.view-timetable-btn:hover {
    background: #1268ca;
    transform: translateY(-1px);
}

/* =========================================================
   RESULT CARD
========================================================= */

.timetable-result-card {
    margin-top: 18px;
    background: #ffffff;
    border-radius: 14px;
    padding: 16px;
    border: 1px solid #e4eaf2;
    box-shadow: 0 4px 16px rgba(30, 64, 175, 0.07);
}

/* =========================================================
   RESULT HEADER
========================================================= */

.timetable-result-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 15px;
}

.timetable-result-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 17px;
    font-weight: 700;
}

.timetable-result-header p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 11px;
}

/* =========================================================
   DOWNLOAD BUTTONS
========================================================= */

.timetable-download-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.download-btn {
    height: 34px;
    padding: 0 11px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border-radius: 7px;
    text-decoration: none;
    font-size: 10px;
    font-weight: 700;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.download-btn i {
    font-size: 13px;
}

/* PDF */

.pdf-btn {
    background: #fff0f0;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.pdf-btn:hover {
    background: #dc2626;
    color: #ffffff;
    transform: translateY(-1px);
}

/* EXCEL */

.excel-btn {
    background: #effcf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
}

.excel-btn:hover {
    background: #15803d;
    color: #ffffff;
    transform: translateY(-1px);
}

/* =========================================================
   TABLE WRAPPER
========================================================= */

.timetable-table-wrapper {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e5eaf1;
    border-radius: 9px;
}

/* =========================================================
   WEEKLY TIMETABLE
========================================================= */

.weekly-timetable {
    width: 100%;
    min-width: 850px;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: fixed;
}

/* =========================================================
   TABLE HEADER
========================================================= */

.weekly-timetable th {
    padding: 9px 7px;
    background: #147cf5;
    color: #ffffff;
    text-align: center;
    border-right: 1px solid rgba(255, 255, 255, 0.25);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.weekly-timetable th:first-child {
    border-top-left-radius: 8px;
}

.weekly-timetable th:last-child {
    border-right: none;
    border-top-right-radius: 8px;
}

/* =========================================================
   DAY COLUMN
========================================================= */

.day-column {
    width: 90px;
    min-width: 90px;
    max-width: 90px;
}

.day-name {
    width: 90px;
    min-width: 90px;
    max-width: 90px;
    background: #f4f7fb !important;
    color: #1e293b !important;
    text-align: center;
    font-size: 11px;
    font-weight: 700;
    padding: 8px 5px !important;
}

/* =========================================================
   PERIOD HEADER
========================================================= */

.period-title {
    font-size: 11px;
    font-weight: 700;
}

.period-time {
    margin-top: 3px;
    font-size: 9px;
    font-weight: 400;
    opacity: 0.9;
    white-space: nowrap;
}

/* =========================================================
   TABLE CELLS
========================================================= */

.weekly-timetable td {
    min-width: 120px;
    height: 95px;
    padding: 6px;
    background: #ffffff;
    border-right: 1px solid #e5eaf1;
    border-bottom: 1px solid #e5eaf1;
    vertical-align: middle;
}

.weekly-timetable td:last-child {
    border-right: none;
}

/* =========================================================
   REGULAR SUBJECT
========================================================= */

.subject-cell {
    min-height: 72px;
    padding: 8px;
    border-radius: 8px;
    background: #f5f9ff;
    border-left: 3px solid #147cf5;
    transition: all 0.2s ease;
}

.subject-cell:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(20, 124, 245, 0.10);
}

.subject-name {
    color: #1268ca;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 4px;
    line-height: 1.2;
    word-break: break-word;
}

.teacher-name {
    color: #475569;
    font-size: 10px;
    margin-bottom: 3px;
    line-height: 1.2;
    word-break: break-word;
}

.room-name {
    color: #64748b;
    font-size: 9px;
    margin-bottom: 4px;
    word-break: break-word;
}

.subject-type {
    display: inline-block;
    padding: 3px 6px;
    border-radius: 12px;
    background: #e7f1ff;
    color: #147cf5;
    font-size: 8px;
    font-weight: 700;
}

/* =========================================================
   SPECIAL PERIOD
========================================================= */

.special-period {
    min-height: 72px;
    padding: 7px;
    border-radius: 8px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.special-icon {
    font-size: 18px;
    line-height: 1;
}

.special-title {
    margin-top: 4px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    word-break: break-word;
}

.special-time {
    margin-top: 3px;
    color: #64748b;
    font-size: 8px;
    white-space: nowrap;
}

/* =========================================================
   BREAK
========================================================= */

.break-period {
    background: #fff8e8;
    border: 1px solid #f5d78e;
}

.break-period .special-title {
    color: #a16207;
}

/* =========================================================
   LUNCH
========================================================= */

.lunch-period {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
}

.lunch-period .special-title {
    color: #15803d;
}

/* =========================================================
   ACTIVITY
========================================================= */

.activity-period {
    background: #f5f3ff;
    border: 1px solid #ddd6fe;
}

.activity-period .special-title {
    color: #6d28d9;
}

.activity-period .teacher-name {
    margin-top: 3px;
    margin-bottom: 2px;
}

/* =========================================================
   FREE PERIOD
========================================================= */

.free-period {
    min-height: 72px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #94a3b8;
    font-size: 10px;
    font-style: italic;
}

/* =========================================================
   LEGEND
========================================================= */

.timetable-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 13px;
    margin-top: 14px;
    padding-top: 13px;
    border-top: 1px solid #e5eaf1;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #64748b;
    font-size: 10px;
}

.legend-box {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    display: inline-block;
}

.legend-box.regular {
    background: #147cf5;
}

.legend-box.break {
    background: #f5d78e;
}

.legend-box.lunch {
    background: #86efac;
}

.legend-box.activity {
    background: #c4b5fd;
}

.legend-box.free {
    background: #cbd5e1;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.empty-timetable {
    margin-top: 18px;
    padding: 40px 20px;
    background: #ffffff;
    border: 1px solid #e6edf7;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(30, 64, 175, 0.05);
}

.empty-icon {
    font-size: 34px;
    margin-bottom: 8px;
}

.empty-timetable h3 {
    margin: 0 0 6px;
    color: #1e293b;
    font-size: 17px;
}

.empty-timetable p {
    margin: 0;
    color: #64748b;
    font-size: 12px;
}

/* =========================================================
   INITIAL STATE
========================================================= */

.initial-state {
    margin-top: 18px;
    padding: 40px 20px;
    background: #ffffff;
    border: 1px solid #e6edf7;
    border-radius: 14px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(30, 64, 175, 0.05);
}

.initial-state-icon {
    font-size: 34px;
    margin-bottom: 8px;
}

.initial-state h3 {
    margin: 0 0 6px;
    color: #1e293b;
    font-size: 17px;
}

.initial-state p {
    margin: 0;
    color: #64748b;
    font-size: 12px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .class-timetable-page {
        padding: 14px;
    }

    .class-timetable-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .selection-form {
        grid-template-columns: 1fr;
    }

    .view-timetable-btn {
        width: 100%;
    }

    .timetable-result-card {
        padding: 12px;
    }

    .timetable-result-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .timetable-download-actions {
        width: 100%;
    }

    .download-btn {
        flex: 1;
    }

    .weekly-timetable {
        min-width: 850px;
    }
}

</style>


<div class="class-timetable-page">

{{-- =====================================================
     PAGE HEADER
====================================================== --}}

<div class="class-timetable-header">

    <div class="class-timetable-header-left">

        <h2>
            Class Timetable
        </h2>

        <p>
            View the weekly timetable for a selected class and section.
        </p>

    </div>

</div>


{{-- =====================================================
     CLASS / SECTION SELECTION
====================================================== --}}

<div class="selection-card">

    <div class="selection-card-header">

        <h3>
            Select Class & Section
        </h3>

        <p>
            Choose a class and section to view its weekly timetable.
        </p>

    </div>


    <form
        method="GET"
        action="{{ route('admin.timetable.class') }}"
        class="selection-form"
    >

        {{-- CLASS --}}

        <div class="form-group-custom">

            <label for="class">
                Class
            </label>

            <select
                name="class"
                id="class"
                class="form-control-custom"
                required
                onchange="loadSections(this.value)"
            >

                <option value="">
                    -- Select Class --
                </option>

                @foreach($classes as $class)

                    <option
                        value="{{ $class }}"
                        {{ request('class') == $class ? 'selected' : '' }}
                    >
                        {{ $class }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- SECTION --}}

        <div class="form-group-custom">

            <label for="section">
                Section
            </label>

            <select
                name="section"
                id="section"
                class="form-control-custom"
                {{ request('class') ? '' : 'disabled' }}
                required
            >

                <option value="">
                    -- Select Section --
                </option>

                @foreach($sections as $section)

                    <option
                        value="{{ $section }}"
                        {{ request('section') == $section ? 'selected' : '' }}
                    >
                        {{ $section }}
                    </option>

                @endforeach

            </select>

        </div>


        {{-- VIEW BUTTON --}}

        <div class="form-group-custom">

            <button
                type="submit"
                class="view-timetable-btn"
            >
                <i class="bi bi-calendar3 me-1"></i>
                View Timetable
            </button>

        </div>

    </form>

</div>


{{-- =====================================================
     TIMETABLE RESULT
====================================================== --}}

@if(request('class') && request('section'))

    @if($timetables->count())

        <div class="timetable-result-card">

            {{-- =================================================
                 RESULT HEADER
            ================================================== --}}

            <div class="timetable-result-header">

                <div>

                    <h3>
                        {{ request('class') }}
                        -
                        Section {{ request('section') }}
                    </h3>

                    <p>
                        Weekly Class Timetable
                    </p>

                </div>


                {{-- DOWNLOAD BUTTONS --}}

                <div class="timetable-download-actions">

                    <a
                        href="{{ route('admin.timetable.class.pdf', [
                            'class' => request('class'),
                            'section' => request('section')
                        ]) }}"
                        class="download-btn pdf-btn"
                        target="_blank"
                        title="Download PDF"
                    >

                        <i class="bi bi-file-earmark-pdf"></i>

                        PDF

                    </a>


                    <a
                        href="{{ route('admin.timetable.class.excel', [
                            'class' => request('class'),
                            'section' => request('section')
                        ]) }}"
                        class="download-btn excel-btn"
                        title="Export Excel"
                    >

                        <i class="bi bi-file-earmark-spreadsheet"></i>

                        Excel

                    </a>

                </div>

            </div>


            {{-- =================================================
                 PERIOD DATA
            ================================================== --}}

            @php

                $days = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ];

                $periods = $timetables
                    ->groupBy('period_number')
                    ->sortKeys();

            @endphp


            {{-- =================================================
                 TIMETABLE TABLE
            ================================================== --}}

            <div class="timetable-table-wrapper">

                <table class="weekly-timetable">

                    {{-- TABLE HEADER --}}

                    <thead>

                        <tr>

                            <th class="day-column">
                                Day
                            </th>


                            @foreach($periods as $periodNumber => $periodEntries)

                                @php
                                    $period = $periodEntries->first();
                                @endphp

                                <th>

                                    <div class="period-title">
                                        P{{ $periodNumber }}
                                    </div>

                                    @if($period->start_time && $period->end_time)

                                        <div class="period-time">

                                            {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}

                                            -

                                            {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}

                                        </div>

                                    @endif

                                </th>

                            @endforeach

                        </tr>

                    </thead>


                    {{-- TABLE BODY --}}

                    <tbody>

                        @foreach($days as $day)

                            <tr>

                                {{-- DAY --}}

                                <td class="day-name">
                                    {{ $day }}
                                </td>


                                {{-- PERIODS --}}

                                @foreach($periods as $periodNumber => $periodEntries)

                                    @php

                                        $entry = $periodEntries->firstWhere(
                                            'day',
                                            $day
                                        );

                                    @endphp


                                    <td>

                                        @if($entry)

                                            {{-- =====================================
                                                 BREAK
                                            ====================================== --}}

                                            @if($entry->period_type === 'Break')

                                                <div class="special-period break-period">

                                                    <div class="special-icon">
                                                        ☕
                                                    </div>

                                                    <div class="special-title">
                                                        Break
                                                    </div>

                                                    @if($entry->start_time && $entry->end_time)

                                                        <div class="special-time">

                                                            {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}

                                                            -

                                                            {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- =====================================
                                                 LUNCH
                                            ====================================== --}}

                                            @elseif($entry->period_type === 'Lunch')

                                                <div class="special-period lunch-period">

                                                    <div class="special-icon">
                                                        🍱
                                                    </div>

                                                    <div class="special-title">
                                                        Lunch
                                                    </div>

                                                    @if($entry->start_time && $entry->end_time)

                                                        <div class="special-time">

                                                            {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}

                                                            -

                                                            {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- =====================================
                                                 ACTIVITY
                                            ====================================== --}}

                                            @elseif($entry->period_type === 'Activity')

                                                <div class="special-period activity-period">

                                                    <div class="special-icon">
                                                        🎨
                                                    </div>

                                                    <div class="special-title">
                                                        {{ $entry->subject ?: 'Activity' }}
                                                    </div>

                                                    @if($entry->teacher)

                                                        <div class="teacher-name">

                                                            {{ $entry->teacher->first_name }}
                                                            {{ $entry->teacher->last_name }}

                                                        </div>

                                                    @endif

                                                    @if($entry->room)

                                                        <div class="room-name">
                                                            Room: {{ $entry->room }}
                                                        </div>

                                                    @endif

                                                    @if($entry->start_time && $entry->end_time)

                                                        <div class="special-time">

                                                            {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}

                                                            -

                                                            {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}

                                                        </div>

                                                    @endif

                                                </div>


                                            {{-- =====================================
                                                 REGULAR SUBJECT
                                            ====================================== --}}

                                            @else

                                                <div class="subject-cell">

                                                    <div class="subject-name">
                                                        {{ $entry->subject }}
                                                    </div>

                                                    @if($entry->teacher)

                                                        <div class="teacher-name">

                                                            {{ $entry->teacher->first_name }}
                                                            {{ $entry->teacher->last_name }}

                                                        </div>

                                                    @endif

                                                    @if($entry->room)

                                                        <div class="room-name">
                                                            Room: {{ $entry->room }}
                                                        </div>

                                                    @endif

                                                    @if($entry->subject_type)

                                                        <div class="subject-type">
                                                            {{ $entry->subject_type }}
                                                        </div>

                                                    @endif

                                                </div>

                                            @endif


                                        @else

                                            {{-- FREE PERIOD --}}

                                            <div class="free-period">
                                                Free
                                            </div>

                                        @endif

                                    </td>

                                @endforeach

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                 LEGEND
            ================================================== --}}

            <div class="timetable-legend">

                <div class="legend-item">
                    <span class="legend-box regular"></span>
                    Regular
                </div>

                <div class="legend-item">
                    <span class="legend-box break"></span>
                    Break
                </div>

                <div class="legend-item">
                    <span class="legend-box lunch"></span>
                    Lunch
                </div>

                <div class="legend-item">
                    <span class="legend-box activity"></span>
                    Activity
                </div>

                <div class="legend-item">
                    <span class="legend-box free"></span>
                    Free
                </div>

            </div>

        </div>


    @else

        {{-- =================================================
             NO TIMETABLE
        ================================================== --}}

        <div class="empty-timetable">

            <div class="empty-icon">
                📅
            </div>

            <h3>
                No Timetable Found
            </h3>

            <p>

                No timetable has been created for

                <strong>
                    {{ request('class') }}
                </strong>

                -

                <strong>
                    Section {{ request('section') }}
                </strong>

            </p>

        </div>

    @endif


@else

    {{-- =================================================
         INITIAL STATE
    ================================================== --}}

    <div class="initial-state">

        <div class="initial-state-icon">
            📚
        </div>

        <h3>
            Select a Class and Section
        </h3>

        <p>
            Select a class and section above to view the weekly timetable.
        </p>

    </div>

@endif


</div>


<script>

/* =========================================================
   LOAD SECTIONS FOR SELECTED CLASS
========================================================= */

function loadSections(classValue)
{
    if (!classValue) {

        window.location.href =
            "{{ route('admin.timetable.class') }}";

        return;
    }

    const url = new URL(
        "{{ route('admin.timetable.class') }}",
        window.location.origin
    );

    url.searchParams.set('class', classValue);

    window.location.href = url.toString();
}

</script>

@endsection