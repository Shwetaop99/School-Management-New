```blade
@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

@php
    use Illuminate\Support\Carbon;

    /*
    |--------------------------------------------------------------------------
    | Group schedules by examination date
    |--------------------------------------------------------------------------
    */
    $groupedByDate = $schedules->groupBy(function ($schedule) {
        return Carbon::parse($schedule->exam_date)->format('Y-m-d');
    });
@endphp

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="page-header mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="d-flex align-items-center">

                <div class="page-icon me-3">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>

                    <h4 class="fw-bold mb-1">
                        Exam Timetable
                    </h4>

                    <div class="text-muted small">

                        <span class="fw-semibold text-dark">
                            {{ $exam->exam_name }}
                        </span>

                        @if($exam->exam_type)
                            <span class="mx-2">•</span>
                            {{ $exam->exam_type }}
                        @endif

                        @if($exam->academic_year)
                            <span class="mx-2">•</span>
                            {{ $exam->academic_year }}
                        @endif

                    </div>

                </div>

            </div>


            {{-- HEADER ACTIONS --}}
            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.exam-schedules.generate', $exam) }}"
                   class="btn btn-primary">

                    <i class="bi bi-magic me-1"></i>
                    Generate Timetable

                </a>


                <a href="{{ route('admin.exam-schedules.print', array_merge(
                        ['exam' => $exam->id],
                        request()->only(['class_id', 'exam_date'])
                    )) }}"
                   target="_blank"
                   class="btn btn-light border">

                    <i class="bi bi-printer me-1"></i>
                    Print

                </a>


                <a href="{{ route('admin.exam-schedules.pdf', array_merge(
                        ['exam' => $exam->id],
                        request()->only(['class_id', 'exam_date'])
                    )) }}"
                   class="btn btn-light border text-danger">

                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    PDF

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-center">

                <div class="message-icon success-message-icon me-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>

                    <div class="fw-semibold">
                        Success
                    </div>

                    <div class="small">
                        {{ session('success') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-center">

                <div class="message-icon danger-message-icon me-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div>

                    <div class="fw-semibold">
                        Error
                    </div>

                    <div class="small">
                        {{ session('error') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-start">

                <div class="message-icon danger-message-icon me-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div class="flex-grow-1">

                    <div class="fw-semibold mb-2">
                        Please fix the following:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)

                            <li class="small mb-1">
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        QUICK SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- EXAM PERIOD --}}
        <div class="col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon blue">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Exam Period
                    </div>

                    <div class="summary-value">

                        @if($exam->start_date)

                            {{ Carbon::parse($exam->start_date)->format('d M Y') }}

                            @if($exam->end_date)
                                <span class="text-muted mx-1">–</span>
                                {{ Carbon::parse($exam->end_date)->format('d M Y') }}
                            @endif

                        @else
                            -
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- CLASSES --}}
        <div class="col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon purple">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Classes
                    </div>

                    <div class="summary-value">
                        {{ $classes->count() }}
                    </div>

                </div>

            </div>

        </div>


        {{-- ENTRIES --}}
        <div class="col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon green">
                    <i class="bi bi-calendar-check-fill"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Timetable Entries
                    </div>

                    <div class="summary-value">
                        {{ $schedules->count() }}
                    </div>

                </div>

            </div>

        </div>


        {{-- EXAM DAYS --}}
        <div class="col-sm-6 col-xl-3">

            <div class="summary-card">

                <div class="summary-icon orange">
                    <i class="bi bi-clock-fill"></i>
                </div>

                <div>

                    <div class="summary-label">
                        Exam Days
                    </div>

                    <div class="summary-value">
                        {{ $groupedByDate->count() }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm professional-card mb-4">

        <div class="card-header bg-white px-4 py-3">

            <div class="d-flex align-items-center">

                <div class="section-icon blue me-3">
                    <i class="bi bi-funnel-fill"></i>
                </div>

                <div>

                    <h6 class="fw-bold mb-1">
                        Filter Timetable
                    </h6>

                    <div class="text-muted small">
                        View timetable by class or examination date.
                    </div>

                </div>

            </div>

        </div>


        <div class="card-body px-4 py-4">

            <form method="GET"
                  action="{{ route('admin.exam-schedules.index', $exam) }}">

                <div class="row g-3 align-items-end">

                    {{-- CLASS --}}
                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            <i class="bi bi-people me-1 text-primary"></i>
                            Class

                        </label>

                        <select name="class_id"
                                class="form-select">

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option value="{{ $class->id }}"
                                    {{ request('class_id') == $class->id ? 'selected' : '' }}>

                                    {{ $class->class_name }}

                                    @if($class->section)
                                        - {{ $class->section }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DATE --}}
                    <div class="col-md-5">

                        <label class="form-label fw-semibold">

                            <i class="bi bi-calendar-date me-1 text-primary"></i>
                            Examination Date

                        </label>

                        <input type="date"
                               name="exam_date"
                               class="form-control"
                               value="{{ request('exam_date') }}">

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-md-2">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">

                                <i class="bi bi-filter me-1"></i>
                                Filter

                            </button>

                            <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                               class="btn btn-light border"
                               title="Clear Filters">

                                <i class="bi bi-arrow-counterclockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PROFESSIONAL TIMETABLE
    ========================================================== --}}
    @forelse($groupedByDate as $date => $dateSchedules)

        @php
            /*
            |--------------------------------------------------------------------------
            | Group by session/time
            |--------------------------------------------------------------------------
            */
            $groupedBySession = $dateSchedules->groupBy(function ($schedule) {

                return
                    ($schedule->exam_session_id
                        ?? $schedule->session_id
                        ?? (
                            ($schedule->start_time ?? '') .
                            '|' .
                            ($schedule->end_time ?? '')
                        )
                    );

            });
        @endphp


        {{-- =====================================================
            DATE CARD
        ====================================================== --}}
        <div class="timetable-day-card mb-4">


            {{-- DATE HEADER --}}
            <div class="timetable-day-header">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="d-flex align-items-center">

                        <div class="date-icon me-3">

                            <div class="date-day">
                                {{ Carbon::parse($date)->format('d') }}
                            </div>

                            <div class="date-month">
                                {{ Carbon::parse($date)->format('M') }}
                            </div>

                        </div>


                        <div>

                            <div class="date-title">
                                {{ Carbon::parse($date)->format('d F Y') }}
                            </div>

                            <div class="date-weekday">

                                <i class="bi bi-calendar-week me-1"></i>

                                {{ Carbon::parse($date)->format('l') }}

                            </div>

                        </div>

                    </div>


                    <div class="date-summary">

                        <div class="date-summary-number">
                            {{ $dateSchedules->count() }}
                        </div>

                        <div>
                            <div class="date-summary-label">
                                Scheduled
                            </div>

                            <div class="date-summary-text">
                                {{ $dateSchedules->count() == 1 ? 'Exam' : 'Exams' }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SESSION GROUPS
            ================================================== --}}
            @foreach($groupedBySession as $sessionKey => $sessionSchedules)

                @php
                    $firstSchedule = $sessionSchedules->first();

                    $startTime = $firstSchedule->start_time ?? null;
                    $endTime = $firstSchedule->end_time ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | Session name
                    |--------------------------------------------------------------------------
                    */
                    $sessionName =
                        optional($firstSchedule->session)->session_name
                        ?? optional($firstSchedule->examSession)->session_name
                        ?? $firstSchedule->session_name
                        ?? null;

                    /*
                    |--------------------------------------------------------------------------
                    | If no session name exists, use time
                    |--------------------------------------------------------------------------
                    */
                    if (!$sessionName) {

                        if ($startTime && $endTime) {

                            $sessionName =
                                Carbon::parse($startTime)->format('h:i A')
                                . ' – ' .
                                Carbon::parse($endTime)->format('h:i A');

                        } else {

                            $sessionName = 'Exam Session';

                        }

                    }
                @endphp


                <div class="session-block">


                    {{-- SESSION HEADER --}}
                    <div class="session-header">

                        <div class="d-flex align-items-center gap-3">

                            <div class="session-icon">
                                <i class="bi bi-clock-fill"></i>
                            </div>

                            <div>

                                <div class="session-title">
                                    {{ $sessionName }}
                                </div>

                                @if($startTime && $endTime)

                                    <div class="session-time">

                                        {{ Carbon::parse($startTime)->format('h:i A') }}

                                        <span class="mx-1">–</span>

                                        {{ Carbon::parse($endTime)->format('h:i A') }}

                                    </div>

                                @endif

                            </div>

                        </div>


                        <div class="session-count">

                            {{ $sessionSchedules->count() }}

                            {{ $sessionSchedules->count() == 1 ? 'Entry' : 'Entries' }}

                        </div>

                    </div>


                    {{-- =================================================
                        TABLE
                    ================================================== --}}
                    <div class="table-responsive">

                        <table class="table timetable-table mb-0">

                            <thead>

                                <tr>

                                    <th class="time-column">
                                        Time
                                    </th>

                                    <th class="class-column">
                                        Class
                                    </th>

                                    <th class="subject-column">
                                        Subject
                                    </th>

                                    <th class="marks-column text-center">
                                        Max Marks
                                    </th>

                                    <th class="duration-column text-center">
                                        Duration
                                    </th>

                                    <th class="teacher-column">
                                        Invigilator
                                    </th>

                                    <th class="actions-column text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($sessionSchedules as $schedule)

                                    <tr>

                                        {{-- TIME --}}
                                        <td>

                                            <div class="time-display">

                                                <div class="time-main">

                                                    <i class="bi bi-clock me-1"></i>

                                                    @if($schedule->start_time)

                                                        {{ Carbon::parse($schedule->start_time)->format('h:i A') }}

                                                    @else
                                                        -
                                                    @endif

                                                </div>

                                                @if($schedule->end_time)

                                                    <div class="time-end">

                                                        to

                                                        {{ Carbon::parse($schedule->end_time)->format('h:i A') }}

                                                    </div>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- CLASS --}}
                                        <td>

                                            <div class="class-display">

                                                <div class="class-icon">
                                                    <i class="bi bi-mortarboard-fill"></i>
                                                </div>

                                                <div>

                                                    <div class="class-name">

                                                        {{ optional($schedule->schoolClass)->class_name ?? '—' }}

                                                    </div>

                                                    @if(optional($schedule->schoolClass)->section)

                                                        <div class="class-section">

                                                            Section
                                                            {{ $schedule->schoolClass->section }}

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- SUBJECT --}}
                                        <td>

                                            <div class="subject-display">

                                                <div class="subject-icon">
                                                    <i class="bi bi-book-fill"></i>
                                                </div>

                                                <div>

                                                    <div class="subject-name">

                                                        {{
                                                            optional(
                                                                optional($schedule->examSubject)->subject
                                                            )->subject_name ?? '—'
                                                        }}

                                                    </div>

                                                    @if(
                                                        optional(
                                                            optional($schedule->examSubject)->subject
                                                        )->subject_code
                                                    )

                                                        <div class="subject-code">

                                                            {{ $schedule->examSubject->subject->subject_code }}

                                                        </div>

                                                    @endif

                                                </div>

                                            </div>

                                        </td>


                                        {{-- MARKS --}}
                                        <td class="text-center">

                                            @if($schedule->maximum_marks !== null)

                                                <span class="marks-badge">

                                                    <i class="bi bi-award me-1"></i>

                                                    {{ $schedule->maximum_marks }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- DURATION --}}
                                        <td class="text-center">

                                            @if($schedule->duration_minutes)

                                                <span class="duration-badge">

                                                    <i class="bi bi-stopwatch me-1"></i>

                                                    {{ $schedule->duration_minutes }}
                                                    min

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- TEACHER --}}
                                        <td>

                                            @if($schedule->teacher)

                                                @php

                                                    $teacherName = trim(

                                                        ($schedule->teacher->first_name ?? '') .
                                                        ' ' .
                                                        ($schedule->teacher->middle_name ?? '') .
                                                        ' ' .
                                                        ($schedule->teacher->last_name ?? '')

                                                    );

                                                    if ($teacherName === '') {

                                                        $teacherName =
                                                            $schedule->teacher->name
                                                            ?? 'Teacher';

                                                    }

                                                @endphp


                                                <div class="teacher-display">

                                                    <div class="teacher-avatar">

                                                        {{ strtoupper(substr($teacherName, 0, 1)) }}

                                                    </div>

                                                    <div class="teacher-info">

                                                        <div class="teacher-name">

                                                            {{ $teacherName }}

                                                        </div>

                                                        @if($schedule->teacher->employee_id ?? false)

                                                            <div class="teacher-id">

                                                                ID:
                                                                {{ $schedule->teacher->employee_id }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                            @else

                                                <span class="unassigned-badge">

                                                    <i class="bi bi-exclamation-circle me-1"></i>

                                                    Not Assigned

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTIONS --}}
                                        <td class="text-center">

                                            <div class="dropdown">

                                                <button
                                                    class="btn action-btn"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    title="Actions"
                                                >

                                                    <i class="bi bi-three-dots-vertical"></i>

                                                </button>


                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">


                                                    {{-- EDIT --}}
                                                    <li>

                                                        <a
                                                            class="dropdown-item"
                                                            href="{{ route(
                                                                'admin.exam-schedules.edit',
                                                                [
                                                                    'exam' => $exam->id,
                                                                    'schedule' => $schedule->id
                                                                ]
                                                            ) }}"
                                                        >

                                                            <i class="bi bi-pencil me-2 text-primary"></i>

                                                            Edit

                                                        </a>

                                                    </li>


                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>


                                                    {{-- DELETE --}}
                                                    <li>

                                                        <form
                                                            method="POST"
                                                            action="{{ route(
                                                                'admin.exam-schedules.destroy',
                                                                [
                                                                    'exam' => $exam->id,
                                                                    'schedule' => $schedule->id
                                                                ]
                                                            ) }}"
                                                        >

                                                            @csrf
                                                            @method('DELETE')


                                                            <button
                                                                type="submit"
                                                                class="dropdown-item text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this timetable entry?')"
                                                            >

                                                                <i class="bi bi-trash me-2"></i>

                                                                Delete

                                                            </button>

                                                        </form>

                                                    </li>

                                                </ul>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            @endforeach

        </div>


    @empty

        {{-- =====================================================
            EMPTY STATE
        ====================================================== --}}
        <div class="card border-0 shadow-sm professional-card">

            <div class="card-body text-center py-5">

                <div class="empty-icon mb-3">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <h5 class="fw-bold mb-2">
                    No Timetable Found
                </h5>

                <p class="text-muted mb-4 mx-auto"
                   style="max-width: 520px;">

                    No examination timetable has been generated
                    for the selected filters.

                </p>


                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    @if(request('class_id') || request('exam_date'))

                        <a
                            href="{{ route('admin.exam-schedules.index', $exam) }}"
                            class="btn btn-light border"
                        >

                            <i class="bi bi-arrow-counterclockwise me-1"></i>

                            Clear Filters

                        </a>

                    @endif


                    <a
                        href="{{ route('admin.exam-schedules.generate', $exam) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-magic me-1"></i>

                        Generate Timetable

                    </a>

                </div>

            </div>

        </div>

    @endforelse

</div>


{{-- =============================================================
    STYLES
============================================================= --}}
<style>

    /* =========================================================
       GENERAL
    ========================================================== */

    .professional-card,
    .timetable-day-card {

        border: 1px solid #e9ecef;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        overflow: hidden;

    }

    .min-width-0 {
        min-width: 0;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {

        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 18px 20px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);

    }

    .page-icon {

        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #eef5ff;
        color: #1677f0;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;
        flex-shrink: 0;

    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-card {

        min-height: 82px;

        display: flex;
        align-items: center;
        gap: 14px;

        padding: 16px 18px;

        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;

        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);

        transition: all .2s ease;

    }

    .summary-card:hover {

        transform: translateY(-2px);

        box-shadow: 0 7px 18px rgba(0, 0, 0, .06);

    }

    .summary-icon {

        width: 43px;
        height: 43px;

        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .summary-icon.blue {

        background: #eef5ff;
        color: #1677f0;

    }

    .summary-icon.purple {

        background: #f2edff;
        color: #6f42c1;

    }

    .summary-icon.green {

        background: #eaf8f0;
        color: #198754;

    }

    .summary-icon.orange {

        background: #fff4df;
        color: #d98b00;

    }

    .summary-label {

        color: #6c757d;
        font-size: 10px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .05em;

        margin-bottom: 4px;

    }

    .summary-value {

        color: #212529;
        font-size: 14px;
        font-weight: 700;

    }


    /* =========================================================
       SECTION ICON
    ========================================================== */

    .section-icon {

        width: 40px;
        height: 40px;

        border-radius: 10px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .section-icon.blue {

        background: #eef5ff;
        color: #1677f0;

    }


    /* =========================================================
       FORM
    ========================================================== */

    .form-select,
    .form-control {

        border-radius: 9px;
        min-height: 42px;
        border-color: #dee2e6;
        font-size: 13px;

    }

    .form-select:focus,
    .form-control:focus {

        border-color: #86b7fe;

        box-shadow:
            0 0 0 .2rem rgba(13, 110, 253, .08);

    }


    /* =========================================================
       DATE HEADER
    ========================================================== */

    .timetable-day-header {

        background: #fff;

        padding: 18px 20px;

        border-bottom: 1px solid #edf0f2;

    }

    .date-icon {

        width: 54px;
        height: 54px;

        border-radius: 12px;

        background: #eef5ff;
        color: #1677f0;

        display: flex;
        flex-direction: column;

        align-items: center;
        justify-content: center;

        line-height: 1;

        flex-shrink: 0;

    }

    .date-day {

        font-size: 19px;
        font-weight: 800;

    }

    .date-month {

        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;

        margin-top: 4px;

        letter-spacing: .05em;

    }

    .date-title {

        color: #212529;
        font-size: 16px;
        font-weight: 750;

    }

    .date-weekday {

        color: #6c757d;
        font-size: 12px;
        margin-top: 3px;

    }

    .date-summary {

        display: flex;
        align-items: center;
        gap: 9px;

        padding: 7px 12px;

        background: #f8f9fa;

        border: 1px solid #e9ecef;

        border-radius: 10px;

    }

    .date-summary-number {

        width: 30px;
        height: 30px;

        border-radius: 8px;

        background: #1677f0;
        color: #fff;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 12px;
        font-weight: 800;

    }

    .date-summary-label {

        color: #6c757d;
        font-size: 9px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .04em;

    }

    .date-summary-text {

        color: #343a40;
        font-size: 11px;
        font-weight: 700;

    }


    /* =========================================================
       SESSION
    ========================================================== */

    .session-block {

        border-bottom: 1px solid #edf0f2;

    }

    .session-block:last-child {

        border-bottom: 0;

    }

    .session-header {

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 12px 20px;

        background: #f8faff;

        border-bottom: 1px solid #e8eef7;

    }

    .session-icon {

        width: 35px;
        height: 35px;

        border-radius: 9px;

        background: #eaf2ff;
        color: #1677f0;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .session-title {

        color: #212529;
        font-size: 13px;
        font-weight: 750;

    }

    .session-time {

        color: #6c757d;
        font-size: 11px;
        margin-top: 2px;

    }

    .session-count {

        padding: 5px 10px;

        background: #fff;

        border: 1px solid #e0e6ee;

        border-radius: 20px;

        color: #495057;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;

    }


    /* =========================================================
       MAIN TABLE
    ========================================================== */

    .timetable-table {

        min-width: 1050px;

    }

    .timetable-table thead th {

        background: #fff;

        color: #6c757d;

        font-size: 10px;
        font-weight: 750;

        text-transform: uppercase;
        letter-spacing: .055em;

        padding: 13px 16px;

        border-bottom: 1px solid #e5e7eb;

        white-space: nowrap;

    }

    .timetable-table tbody td {

        padding: 15px 16px;

        vertical-align: middle;

        border-bottom: 1px solid #f0f1f3;

        background: #fff;

    }

    .timetable-table tbody tr:last-child td {

        border-bottom: 0;

    }

    .timetable-table tbody tr:hover td {

        background: #fafcff;

    }

    .time-column {
        width: 155px;
    }

    .class-column {
        width: 175px;
    }

    .subject-column {
        width: 230px;
    }

    .marks-column {
        width: 105px;
    }

    .duration-column {
        width: 115px;
    }

    .teacher-column {
        width: 245px;
    }

    .actions-column {
        width: 70px;
    }


    /* =========================================================
       TIME
    ========================================================== */

    .time-display {

        white-space: nowrap;

    }

    .time-main {

        color: #212529;
        font-size: 12px;
        font-weight: 750;

    }

    .time-main i {

        color: #d98b00;

    }

    .time-end {

        color: #8a9198;
        font-size: 10px;

        padding-left: 19px;
        margin-top: 2px;

    }


    /* =========================================================
       CLASS
    ========================================================== */

    .class-display {

        display: flex;
        align-items: center;
        gap: 10px;

    }

    .class-icon {

        width: 36px;
        height: 36px;

        border-radius: 9px;

        background: #eef5ff;
        color: #1677f0;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .class-name {

        color: #212529;
        font-size: 12px;
        font-weight: 750;

    }

    .class-section {

        color: #6c757d;
        font-size: 10px;

        margin-top: 2px;

    }


    /* =========================================================
       SUBJECT
    ========================================================== */

    .subject-display {

        display: flex;
        align-items: center;
        gap: 10px;

        min-width: 180px;

    }

    .subject-icon {

        width: 36px;
        height: 36px;

        border-radius: 9px;

        background: #eaf8f0;
        color: #198754;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .subject-name {

        color: #212529;
        font-size: 12px;
        font-weight: 700;

    }

    .subject-code {

        color: #8a9198;
        font-size: 10px;

        margin-top: 2px;

    }


    /* =========================================================
       MARKS
    ========================================================== */

    .marks-badge {

        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 62px;

        padding: 6px 9px;

        border-radius: 7px;

        background: #f4f5f7;
        color: #495057;

        font-size: 11px;
        font-weight: 750;

    }


    /* =========================================================
       DURATION
    ========================================================== */

    .duration-badge {

        display: inline-flex;
        align-items: center;

        padding: 6px 9px;

        border-radius: 7px;

        background: #fff6e8;
        color: #b76e00;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;

    }


    /* =========================================================
       TEACHER
    ========================================================== */

    .teacher-display {

        display: flex;
        align-items: center;
        gap: 10px;

        min-width: 180px;

    }

    .teacher-avatar {

        width: 36px;
        height: 36px;

        border-radius: 9px;

        background: #f2edff;
        color: #6f42c1;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 12px;
        font-weight: 800;

        flex-shrink: 0;

    }

    .teacher-info {

        min-width: 0;

    }

    .teacher-name {

        color: #212529;

        font-size: 12px;
        font-weight: 700;

        max-width: 180px;

        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;

    }

    .teacher-id {

        color: #8a9198;
        font-size: 10px;

        margin-top: 2px;

    }

    .unassigned-badge {

        display: inline-flex;
        align-items: center;

        padding: 6px 9px;

        border-radius: 7px;

        background: #fff0f1;
        color: #dc3545;

        font-size: 10px;
        font-weight: 700;

        white-space: nowrap;

    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .action-btn {

        width: 35px;
        height: 35px;

        border-radius: 8px;

        border: 1px solid #e1e5e9;

        background: #fff;
        color: #495057;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        transition: all .15s ease;

    }

    .action-btn:hover {

        background: #f1f5f9;
        border-color: #cfd6dd;
        color: #1677f0;

    }

    .dropdown-menu {

        border-radius: 10px;

        padding: 6px;

        min-width: 150px;

    }

    .dropdown-item {

        border-radius: 7px;

        padding: 8px 10px;

        font-size: 12px;

    }

    .dropdown-item:hover {

        background: #f5f7fa;

    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-icon {

        width: 68px;
        height: 68px;

        margin-left: auto;
        margin-right: auto;

        border-radius: 50%;

        background: #f1f3f5;
        color: #6c757d;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 28px;

    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .message-icon {

        width: 38px;
        height: 38px;

        border-radius: 9px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;

    }

    .success-message-icon {

        background: rgba(25, 135, 84, .10);
        color: #198754;

    }

    .danger-message-icon {

        background: rgba(220, 53, 69, .10);
        color: #dc3545;

    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 767.98px) {

        .page-header {

            padding: 16px;

        }

        .page-icon {

            width: 43px;
            height: 43px;

        }

        .timetable-day-header {

            padding: 15px;

        }

        .session-header {

            padding: 11px 15px;

        }

        .timetable-table {

            min-width: 1050px;

        }

        .date-summary {

            width: 100%;

        }

    }


    @media (max-width: 575.98px) {

        .page-icon {

            display: none;

        }

        .page-header .btn {

            flex: 1;

        }

    }

</style>

@endsection
