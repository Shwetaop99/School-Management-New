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
    padding: 28px;
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
    gap: 20px;
    margin-bottom: 24px;
}

.class-timetable-header-left h2 {
    margin: 0;
    color: #1e293b;
    font-size: 26px;
    font-weight: 700;
}

.class-timetable-header-left p {
    margin: 7px 0 0;
    color: #64748b;
    font-size: 14px;
}

/* =========================================================
   SELECTION CARD
========================================================= */

.selection-card {
    background: #ffffff;
    border: 1px solid #e4eaf2;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(30, 64, 175, 0.07);
    margin-bottom: 25px;
}

.selection-card-header {
    margin-bottom: 20px;
}

.selection-card-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 19px;
    font-weight: 700;
}

.selection-card-header p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
}

/* =========================================================
   FORM
========================================================= */

.selection-form {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 18px;
    align-items: end;
}

.form-group-custom {
    display: flex;
    flex-direction: column;
}

.form-group-custom label {
    margin-bottom: 8px;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
}

.form-control-custom {
    width: 100%;
    height: 46px;
    padding: 0 14px;
    border: 1px solid #d7e0eb;
    border-radius: 10px;
    background: #ffffff;
    color: #334155;
    font-size: 14px;
    outline: none;
    transition: all 0.2s ease;
}

.form-control-custom:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10);
}

.form-control-custom:hover {
    border-color: #b8c7da;
}

/* =========================================================
   VIEW BUTTON
========================================================= */

.view-timetable-btn {
    height: 46px;
    padding: 0 22px;
    border: none;
    border-radius: 10px;
    background: #147cf5;
    color: #ffffff;
    font-size: 14px;
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
    margin-top: 25px;
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid #e4eaf2;
    box-shadow: 0 5px 20px rgba(30, 64, 175, 0.08);
}

/* =========================================================
   RESULT HEADER
========================================================= */

.timetable-result-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
}

.timetable-result-header h3 {
    margin: 0;
    color: #1e293b;
    font-size: 21px;
    font-weight: 700;
}

.timetable-result-header p {
    margin: 6px 0 0;
    color: #64748b;
    font-size: 14px;
}

/* =========================================================
   DOWNLOAD BUTTONS
========================================================= */

.timetable-download-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.download-btn {
    height: 40px;
    padding: 0 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.download-btn i {
    font-size: 16px;
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
    border-radius: 12px;
}

/* =========================================================
   WEEKLY TIMETABLE
========================================================= */

.weekly-timetable {
    width: 100%;
    min-width: 1050px;
    border-collapse: separate;
    border-spacing: 0;
}

/* =========================================================
   TABLE HEADER
========================================================= */

.weekly-timetable th {
    padding: 15px 12px;
    background: #147cf5;
    color: #ffffff;
    text-align: center;
    border-right: 1px solid rgba(255, 255, 255, 0.25);
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
}

.weekly-timetable th:first-child {
    border-top-left-radius: 10px;
}

.weekly-timetable th:last-child {
    border-right: none;
    border-top-right-radius: 10px;
}

/* =========================================================
   DAY COLUMN
========================================================= */

.day-column {
    width: 120px;
    min-width: 120px;
}

.day-name {
    width: 120px;
    min-width: 120px;
    background: #f4f7fb !important;
    color: #1e293b !important;
    text-align: center;
    font-size: 14px;
    font-weight: 700;
}

/* =========================================================
   PERIOD HEADER
========================================================= */

.period-title {
    font-size: 14px;
    font-weight: 700;
}

.period-time {
    margin-top: 5px;
    font-size: 11px;
    font-weight: 400;
    opacity: 0.9;
    white-space: nowrap;
}

/* =========================================================
   TABLE CELLS
========================================================= */

.weekly-timetable td {
    min-width: 150px;
    height: 125px;
    padding: 10px;
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
    min-height: 95px;
    padding: 12px;
    border-radius: 11px;
    background: #f5f9ff;
    border-left: 4px solid #147cf5;
    transition: all 0.2s ease;
}

.subject-cell:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(20, 124, 245, 0.12);
}

.subject-name {
    color: #1268ca;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 7px;
    line-height: 1.3;
}

.teacher-name {
    color: #475569;
    font-size: 12px;
    margin-bottom: 5px;
    line-height: 1.3;
}

.room-name {
    color: #64748b;
    font-size: 11px;
    margin-bottom: 7px;
}

.subject-type {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 20px;
    background: #e7f1ff;
    color: #147cf5;
    font-size: 10px;
    font-weight: 700;
}

/* =========================================================
   SPECIAL PERIOD
========================================================= */

.special-period {
    min-height: 95px;
    padding: 12px;
    border-radius: 11px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.special-icon {
    font-size: 25px;
    line-height: 1;
}

.special-title {
    margin-top: 7px;
    font-size: 14px;
    font-weight: 700;
}

.special-time {
    margin-top: 5px;
    color: #64748b;
    font-size: 10px;
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

/* =========================================================
   FREE PERIOD
========================================================= */

.free-period {
    min-height: 95px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #94a3b8;
    font-size: 12px;
    font-style: italic;
}

/* =========================================================
   LEGEND
========================================================= */

.timetable-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #e5eaf1;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #64748b;
    font-size: 12px;
}

.legend-box {
    width: 13px;
    height: 13px;
    border-radius: 4px;
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
    margin-top: 25px;
    padding: 55px 25px;
    background: #ffffff;
    border: 1px solid #e6edf7;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 4px 18px rgba(30, 64, 175, 0.06);
}

.empty-icon {
    font-size: 42px;
    margin-bottom: 12px;
}

.empty-timetable h3 {
    margin: 0 0 8px;
    color: #1e293b;
    font-size: 20px;
}

.empty-timetable p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

/* =========================================================
   INITIAL STATE
========================================================= */

.initial-state {
    margin-top: 25px;
    padding: 55px 25px;
    background: #ffffff;
    border: 1px solid #e6edf7;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 4px 18px rgba(30, 64, 175, 0.06);
}

.initial-state-icon {
    font-size: 42px;
    margin-bottom: 12px;
}

.initial-state h3 {
    margin: 0 0 8px;
    color: #1e293b;
    font-size: 20px;
}

.initial-state p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .class-timetable-page {
        padding: 18px;
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
        padding: 18px;
    }

    .timetable-result-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .timetable-download-actions {
        width: 100%;
    }

    .download-btn {
        flex: 1;
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
                 RESULT HEADER + DOWNLOAD BUTTONS
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


                <div class="timetable-download-actions">

                    {{-- PDF --}}

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

                        Download PDF

                    </a>


                    {{-- EXCEL --}}

                    <a
                        href="{{ route('admin.timetable.class.excel', [
                            'class' => request('class'),
                            'section' => request('section')
                        ]) }}"
                        class="download-btn excel-btn"
                        title="Export Excel"
                    >

                        <i class="bi bi-file-earmark-spreadsheet"></i>

                        Export Excel

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
                                        Period {{ $periodNumber }}
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

                                            {{-- BREAK --}}

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


                                            {{-- LUNCH --}}

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


                                            {{-- ACTIVITY --}}

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


                                            {{-- REGULAR SUBJECT --}}

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

                    Regular Subject

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

                    Free Period

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
