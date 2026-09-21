@extends('layouts.app')

@section('title', 'Timetable Management')

@section('page-title', 'Timetable Management')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>

/* =========================================================
   TIMETABLE MANAGEMENT PAGE
========================================================= */

.timetable-page {
    width: 100%;
    max-width: 1700px;
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
    padding: 28px 30px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(20, 124, 245, .15);
}

.header-content h2 {
    margin: 0 0 7px;
    font-size: 27px;
    font-weight: 800;
}

.header-content p {
    margin: 0;
    font-size: 14px;
    opacity: .9;
}

.add-timetable-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #fff;
    color: #147cf5;
    padding: 12px 19px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    transition: .2s ease;
}

.add-timetable-btn:hover {
    color: #1268ca;
    transform: translateY(-2px);
}

/* =========================================================
   SUCCESS MESSAGE
========================================================= */

.success-message {
    display: flex;
    align-items: center;
    gap: 9px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 22px;
}

/* =========================================================
   STATISTICS
========================================================= */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e6ebf2;
    border-radius: 15px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 5px 18px rgba(15, 23, 42, .05);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-icon.blue {
    background: #e7f0ff;
    color: #147cf5;
}

.stat-icon.green {
    background: #eafaf0;
    color: #16a34a;
}

.stat-icon.orange {
    background: #fff7e8;
    color: #d97706;
}

.stat-icon.purple {
    background: #f1edff;
    color: #6c63ff;
}

.stat-info small {
    display: block;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
}

.stat-info strong {
    display: block;
    color: #172033;
    font-size: 23px;
    font-weight: 800;
}

/* =========================================================
   MAIN CARD
========================================================= */

.timetable-card {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 17px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
}

.card-header {
    padding: 21px 23px;
    border-bottom: 1px solid #edf1f6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.card-title h3 {
    margin: 0;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.card-title p {
    margin: 5px 0 0;
    color: #94a3b8;
    font-size: 12px;
}

.result-count {
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
}

/* =========================================================
   FILTER AREA
========================================================= */

.filter-area {
    padding: 20px 23px;
    background: #fbfcfe;
    border-bottom: 1px solid #edf1f6;
}

.filter-grid {
    display: grid;
    grid-template-columns: 2fr repeat(5, 1fr);
    gap: 12px;
}

.filter-group label {
    display: block;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    margin-bottom: 6px;
}

.filter-control {
    width: 100%;
    height: 42px;
    border: 1px solid #dce4ee;
    border-radius: 9px;
    background: #fff;
    color: #334155;
    padding: 0 12px;
    font-size: 12px;
    outline: none;
}

.filter-control:focus {
    border-color: #147cf5;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, .08);
}

.clear-filter {
    margin-top: 17px;
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
}

.clear-filter:hover {
    color: #147cf5;
}

/* =========================================================
   TABLE
========================================================= */

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.timetable-table {
    width: 100%;
    min-width: 1380px;
    border-collapse: collapse;
}

.timetable-table th {
    padding: 14px;
    background: #f8fafc;
    border-bottom: 1px solid #e8edf4;
    color: #64748b;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    white-space: nowrap;
}

.timetable-table td {
    padding: 15px 14px;
    border-bottom: 1px solid #edf1f6;
    color: #334155;
    font-size: 12px;
    vertical-align: middle;
    white-space: nowrap;
}

.timetable-table tbody tr {
    transition: .15s ease;
}

.timetable-table tbody tr:hover {
    background: #f8fbff;
}

/* =========================================================
   TEACHER
========================================================= */

.teacher-cell {
    display: flex;
    align-items: center;
    gap: 10px;
}

.teacher-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #e7f0ff;
    color: #147cf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 800;
    flex-shrink: 0;
}

.teacher-name {
    color: #172033;
    font-weight: 700;
}

.teacher-id {
    margin-top: 2px;
    color: #94a3b8;
    font-size: 10px;
}

.no-teacher {
    color: #94a3b8;
    font-weight: 600;
}

/* =========================================================
   DATE BADGE
========================================================= */

.date-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border-radius: 7px;
    background: #eef5ff;
    color: #147cf5;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* =========================================================
   BADGES
========================================================= */

.day-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 7px;
    background: #e7f0ff;
    color: #147cf5;
    font-size: 10px;
    font-weight: 700;
}

.period-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 8px;
    border-radius: 6px;
    background: #f1edff;
    color: #6c63ff;
    font-size: 10px;
    font-weight: 700;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.type-regular {
    background: #eafaf0;
    color: #15803d;
}

.type-break {
    background: #fff7e8;
    color: #b45309;
}

.type-lunch {
    background: #fff1f2;
    color: #be123c;
}

.type-activity {
    background: #f1edff;
    color: #6c63ff;
}

.class-text {
    color: #172033;
    font-weight: 700;
}

.section-text {
    color: #64748b;
}

.subject-text {
    color: #334155;
    font-weight: 700;
}

.subject-type {
    display: block;
    margin-top: 3px;
    color: #94a3b8;
    font-size: 10px;
}

.time-text {
    color: #475569;
    font-weight: 600;
}

.room-text {
    color: #64748b;
}

/* =========================================================
   ACTIONS
========================================================= */

.action-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    min-height: 36px !important;
    padding: 0 !important;
    margin: 0 !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 1px solid transparent !important;
    border-radius: 9px !important;
    text-decoration: none !important;
    cursor: pointer !important;
    font-size: 15px !important;
    line-height: 1 !important;
    transition:
        transform .2s ease,
        background .2s ease,
        color .2s ease;
}

.timetable-edit-action {
    background: #f0edff !important;
    color: #6c63ff !important;
    border-color: #e3defe !important;
}

.timetable-edit-action:hover {
    background: #6c63ff !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.timetable-delete-action {
    background: #fff0f0 !important;
    color: #ef2028 !important;
    border-color: #ffe0e0 !important;
}

.timetable-delete-action:hover {
    background: #ef2028 !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.action-btn i {
    display: block !important;
    color: inherit !important;
    font-size: 15px !important;
    line-height: 1 !important;
}

/* =========================================================
   EMPTY
========================================================= */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    margin: 0 auto 15px;
    border-radius: 50%;
    background: #e7f0ff;
    color: #147cf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 27px;
}

.empty-state h4 {
    margin: 0 0 6px;
    color: #172033;
    font-size: 16px;
    font-weight: 700;
}

.empty-state p {
    margin: 0 0 18px;
    color: #94a3b8;
    font-size: 12px;
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1300px) {
    .filter-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1100px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 700px) {
    .timetable-page {
        padding: 16px;
    }

    .timetable-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .card-header {
        align-items: flex-start;
        flex-direction: column;
    }
}

</style>


<div class="timetable-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="timetable-header">

        <div class="header-content">

            <h2>
                <i class="bi bi-calendar-week me-2"></i>
                Timetable Management
            </h2>

            <p>
                Manage teacher schedules, classes, subjects and periods.
            </p>

        </div>

        <a
            href="{{ route('admin.timetable.create') }}"
            class="add-timetable-btn"
        >
            <i class="bi bi-plus-circle-fill"></i>
            Add Timetable
        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="success-message">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    @php

        $totalEntries = $timetables->count();

        $regularEntries = $timetables
            ->where('period_type', 'Regular')
            ->count();

        $breakEntries = $timetables
            ->whereIn('period_type', ['Break', 'Lunch'])
            ->count();

        $activityEntries = $timetables
            ->where('period_type', 'Activity')
            ->count();

    @endphp


    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-icon blue">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="stat-info">

                <small>Total Entries</small>

                <strong>
                    {{ $totalEntries }}
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon green">
                <i class="bi bi-book"></i>
            </div>

            <div class="stat-info">

                <small>Regular Classes</small>

                <strong>
                    {{ $regularEntries }}
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon orange">
                <i class="bi bi-cup-hot"></i>
            </div>

            <div class="stat-info">

                <small>Break / Lunch</small>

                <strong>
                    {{ $breakEntries }}
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon purple">
                <i class="bi bi-stars"></i>
            </div>

            <div class="stat-info">

                <small>Activities</small>

                <strong>
                    {{ $activityEntries }}
                </strong>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TIMETABLE CARD
    ====================================================== --}}

    <div class="timetable-card">

        <div class="card-header">

            <div class="card-title">

                <h3>
                    Timetable Entries
                </h3>

                <p>
                    View and manage all scheduled periods.
                </p>

            </div>

            <div class="result-count">

                Showing

                <span id="visibleCount">
                    {{ $totalEntries }}
                </span>

                entries

            </div>

        </div>


        {{-- =================================================
             FILTERS
        ================================================== --}}

        <div class="filter-area">

            <div class="filter-grid">

                {{-- SEARCH --}}

                <div class="filter-group">

                    <label>
                        Search
                    </label>

                    <input
                        type="text"
                        id="searchInput"
                        class="filter-control"
                        placeholder="Search teacher, class, subject, room..."
                    >

                </div>


                {{-- DATE --}}

                <div class="filter-group">

                    <label>
                        Date
                    </label>

                    <input
                        type="date"
                        id="dateFilter"
                        class="filter-control"
                        value="{{ request('timetable_date') }}"
                    >

                </div>


                {{-- DAY --}}

                <div class="filter-group">

                    <label>
                        Day
                    </label>

                    <select
                        id="dayFilter"
                        class="filter-control"
                    >

                        <option value="">
                            All Days
                        </option>

                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>

                    </select>

                </div>


                {{-- PERIOD TYPE --}}

                <div class="filter-group">

                    <label>
                        Period Type
                    </label>

                    <select
                        id="periodTypeFilter"
                        class="filter-control"
                    >

                        <option value="">
                            All Types
                        </option>

                        <option value="Regular">Regular</option>
                        <option value="Break">Break</option>
                        <option value="Lunch">Lunch</option>
                        <option value="Activity">Activity</option>

                    </select>

                </div>


                {{-- ACADEMIC YEAR --}}

                <div class="filter-group">

                    <label>
                        Academic Year
                    </label>

                    <select
                        id="academicYearFilter"
                        class="filter-control"
                    >

                        <option value="">
                            All Years
                        </option>

                        @foreach(
                            $timetables->pluck('academic_year')
                                ->filter()
                                ->unique()
                                ->sort()
                            as $year
                        )

                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- CLASS --}}

                <div class="filter-group">

                    <label>
                        Class
                    </label>

                    <select
                        id="classFilter"
                        class="filter-control"
                    >

                        <option value="">
                            All Classes
                        </option>

                        @foreach(
                            $timetables->pluck('class')
                                ->filter()
                                ->unique()
                                ->sort()
                            as $class
                        )

                            <option value="{{ $class }}">
                                {{ $class }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- CLEAR FILTERS --}}

            <button
                type="button"
                id="clearFilters"
                class="clear-filter"
            >

                <i class="bi bi-arrow-counterclockwise me-1"></i>

                Clear Filters

            </button>

        </div>


        {{-- =================================================
             TABLE
        ================================================== --}}

        <div class="table-wrapper">

            <table
                class="timetable-table"
                id="timetableTable"
            >

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Teacher</th>
                        <th>Academic Year</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Period</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Subject</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($timetables as $timetable)

                        @php

                            $hasTeacher = $timetable->teacher !== null;

                            $teacherName = $hasTeacher
                                ? trim(
                                    ($timetable->teacher->first_name ?? '') .
                                    ' ' .
                                    ($timetable->teacher->last_name ?? '')
                                )
                                : '';

                            $initials = '';

                            if ($hasTeacher) {

                                $initials =
                                    strtoupper(
                                        substr(
                                            $timetable->teacher->first_name ?? '',
                                            0,
                                            1
                                        )
                                    ) .
                                    strtoupper(
                                        substr(
                                            $timetable->teacher->last_name ?? '',
                                            0,
                                            1
                                        )
                                    );

                            }

                            $formattedDate = $timetable->timetable_date
                                ? \Carbon\Carbon::parse(
                                    $timetable->timetable_date
                                )->format('d M Y')
                                : '—';

                            $searchDate = $timetable->timetable_date
                                ? \Carbon\Carbon::parse(
                                    $timetable->timetable_date
                                )->format('Y-m-d')
                                : '';

                        @endphp


                        <tr
                            class="timetable-row"

                            data-search="{{ strtolower(
                                ($teacherName ?? '') . ' ' .
                                ($timetable->teacher->teacher_id ?? '') . ' ' .
                                ($timetable->class ?? '') . ' ' .
                                ($timetable->section ?? '') . ' ' .
                                ($timetable->subject ?? '') . ' ' .
                                ($timetable->room ?? '') . ' ' .
                                ($timetable->academic_year ?? '') . ' ' .
                                ($formattedDate ?? '') . ' ' .
                                ($searchDate ?? '')
                            ) }}"

                            data-date="{{ $searchDate }}"

                            data-day="{{ $timetable->day }}"

                            data-period-type="{{ $timetable->period_type }}"

                            data-academic-year="{{ $timetable->academic_year }}"

                            data-class="{{ $timetable->class }}"
                        >


                            {{-- NUMBER --}}

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- TEACHER --}}

                            <td>

                                @if($hasTeacher)

                                    <div class="teacher-cell">

                                        <div class="teacher-avatar">
                                            {{ $initials }}
                                        </div>

                                        <div>

                                            <div class="teacher-name">
                                                {{ $teacherName }}
                                            </div>

                                            <div class="teacher-id">
                                                {{ $timetable->teacher->teacher_id ?? 'No ID' }}
                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <div class="teacher-cell">

                                        <div class="teacher-avatar">
                                            —
                                        </div>

                                        <div>

                                            <div class="teacher-name no-teacher">
                                                —
                                            </div>

                                            <div class="teacher-id">
                                                No teacher assigned
                                            </div>

                                        </div>

                                    </div>

                                @endif

                            </td>


                            {{-- ACADEMIC YEAR --}}

                            <td>
                                {{ $timetable->academic_year ?? '—' }}
                            </td>


                            {{-- DATE --}}

                            <td>

                                @if($timetable->timetable_date)

                                    <span class="date-badge">

                                        <i class="bi bi-calendar3"></i>

                                        {{ \Carbon\Carbon::parse(
                                            $timetable->timetable_date
                                        )->format('d M Y') }}

                                    </span>

                                @else

                                    <span class="date-badge">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- DAY --}}

                            <td>

                                <span class="day-badge">
                                    {{ $timetable->day ?? '—' }}
                                </span>

                            </td>


                            {{-- PERIOD --}}

                            <td>

                                <span class="period-badge">

                                    Period
                                    {{ $timetable->period_number ?? '—' }}

                                </span>

                            </td>


                            {{-- PERIOD TYPE --}}

                            <td>

                                @php

                                    $typeClass = match(
                                        $timetable->period_type
                                    ) {

                                        'Regular' =>
                                            'type-regular',

                                        'Break' =>
                                            'type-break',

                                        'Lunch' =>
                                            'type-lunch',

                                        'Activity' =>
                                            'type-activity',

                                        default =>
                                            'type-regular',

                                    };

                                @endphp


                                <span class="type-badge {{ $typeClass }}">

                                    {{ $timetable->period_type }}

                                </span>

                            </td>


                            {{-- CLASS --}}

                            <td>

                                <span class="class-text">
                                    {{ $timetable->class ?: '—' }}
                                </span>

                            </td>


                            {{-- SECTION --}}

                            <td>

                                <span class="section-text">
                                    {{ $timetable->section ?: '—' }}
                                </span>

                            </td>


                            {{-- SUBJECT --}}

                            <td>

                                <span class="subject-text">
                                    {{ $timetable->subject ?: '—' }}
                                </span>

                                <span class="subject-type">
                                    {{ $timetable->subject_type ?: '—' }}
                                </span>

                            </td>


                            {{-- TIME --}}

                            <td>

                                <span class="time-text">

                                    {{ \Carbon\Carbon::parse(
                                        $timetable->start_time
                                    )->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse(
                                        $timetable->end_time
                                    )->format('h:i A') }}

                                </span>

                            </td>


                            {{-- ROOM --}}

                            <td>

                                <span class="room-text">
                                    {{ $timetable->room ?: '—' }}
                                </span>

                            </td>


                            {{-- ACTIONS --}}

                            <td>

                                <div class="action-group">

                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.timetable.edit',
                                            $timetable->id
                                        ) }}"
                                        class="action-btn timetable-edit-action"
                                        title="Edit Timetable"
                                        aria-label="Edit Timetable"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route(
                                            'admin.timetable.destroy',
                                            $timetable->id
                                        ) }}"
                                        method="POST"
                                        style="display:inline-flex; margin:0;"
                                        onsubmit="return confirm(
                                            'Are you sure you want to delete this timetable entry?'
                                        );"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn timetable-delete-action"
                                            title="Delete Timetable"
                                            aria-label="Delete Timetable"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="13">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>

                                    <h4>
                                        No Timetable Entries
                                    </h4>

                                    <p>
                                        Create your first timetable entry to start managing the school schedule.
                                    </p>

                                    <a
                                        href="{{ route('admin.timetable.create') }}"
                                        class="add-timetable-btn"
                                    >

                                        <i class="bi bi-plus-circle-fill"></i>

                                        Create Timetable

                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse


                    {{-- NO FILTER RESULTS --}}

                    <tr
                        id="noResultsRow"
                        style="display:none;"
                    >

                        <td colspan="13">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-search"></i>
                                </div>

                                <h4>
                                    No Matching Timetable
                                </h4>

                                <p>
                                    Try changing your search or filters.
                                </p>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('searchInput');

    const dateFilter =
        document.getElementById('dateFilter');

    const dayFilter =
        document.getElementById('dayFilter');

    const periodTypeFilter =
        document.getElementById('periodTypeFilter');

    const academicYearFilter =
        document.getElementById('academicYearFilter');

    const classFilter =
        document.getElementById('classFilter');

    const clearFilters =
        document.getElementById('clearFilters');

    const rows =
        document.querySelectorAll('.timetable-row');

    const visibleCount =
        document.getElementById('visibleCount');

    const noResultsRow =
        document.getElementById('noResultsRow');


    function filterTimetable() {

        const search =
            searchInput.value.toLowerCase().trim();

        const selectedDate =
            dateFilter.value;

        const day =
            dayFilter.value;

        const periodType =
            periodTypeFilter.value;

        const academicYear =
            academicYearFilter.value;

        const selectedClass =
            classFilter.value;


        let visible = 0;


        rows.forEach(function (row) {

            const rowSearch =
                row.dataset.search || '';

            const rowDate =
                row.dataset.date || '';

            const rowDay =
                row.dataset.day || '';

            const rowPeriodType =
                row.dataset.periodType || '';

            const rowAcademicYear =
                row.dataset.academicYear || '';

            const rowClass =
                row.dataset.class || '';


            const matchesSearch =
                !search ||
                rowSearch.includes(search);


            const matchesDate =
                !selectedDate ||
                rowDate === selectedDate;


            const matchesDay =
                !day ||
                rowDay === day;


            const matchesPeriodType =
                !periodType ||
                rowPeriodType === periodType;


            const matchesAcademicYear =
                !academicYear ||
                rowAcademicYear === academicYear;


            const matchesClass =
                !selectedClass ||
                rowClass === selectedClass;


            const show =
                matchesSearch &&
                matchesDate &&
                matchesDay &&
                matchesPeriodType &&
                matchesAcademicYear &&
                matchesClass;


            if (show) {

                row.style.display = '';

                visible++;

            } else {

                row.style.display = 'none';

            }

        });


        visibleCount.textContent = visible;


        noResultsRow.style.display =
            visible === 0 && rows.length > 0
                ? ''
                : 'none';

    }


    /* =====================================================
       FILTER EVENTS
    ===================================================== */

    searchInput.addEventListener(
        'input',
        filterTimetable
    );

    dateFilter.addEventListener(
        'change',
        filterTimetable
    );

    dayFilter.addEventListener(
        'change',
        filterTimetable
    );

    periodTypeFilter.addEventListener(
        'change',
        filterTimetable
    );

    academicYearFilter.addEventListener(
        'change',
        filterTimetable
    );

    classFilter.addEventListener(
        'change',
        filterTimetable
    );


    /* =====================================================
       CLEAR FILTERS
    ===================================================== */

    clearFilters.addEventListener(
        'click',
        function () {

            searchInput.value = '';

            dateFilter.value = '';

            dayFilter.value = '';

            periodTypeFilter.value = '';

            academicYearFilter.value = '';

            classFilter.value = '';

            filterTimetable();

        }
    );


    /* =====================================================
       INITIAL FILTER
    ===================================================== */

    filterTimetable();

});

</script>

@endsection