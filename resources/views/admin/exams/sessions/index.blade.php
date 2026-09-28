
@extends('layouts.app')

@section('title', 'Exam Sessions')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>
            <div class="d-flex align-items-center gap-2 mb-2">

                <span class="page-icon">
                    <i class="bi bi-clock-history"></i>
                </span>

                <div>
                    <h4 class="fw-bold mb-0">
                        Exam Sessions
                    </h4>

                    <div class="text-muted small mt-1">
                        Manage examination time slots and session timings.
                    </div>
                </div>

            </div>
        </div>


        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('admin.exams.show', $exam) }}"
               class="btn btn-outline-secondary px-3">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Exam

            </a>

            <a href="{{ route('admin.exam-sessions.create', $exam) }}"
               class="btn btn-primary px-3">

                <i class="bi bi-plus-lg me-1"></i>
                Add Session

            </a>

        </div>

    </div>


    {{-- =========================================================
        EXAM SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm exam-summary-card mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center g-4">

                {{-- Exam --}}
                <div class="col-lg-7">

                    <div class="d-flex align-items-start gap-3">

                        <div class="exam-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>

                        <div>

                            <div class="small text-muted mb-1">
                                Examination
                            </div>

                            <h5 class="fw-bold mb-2">
                                {{ $exam->exam_name }}
                            </h5>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="info-badge">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $exam->academic_year }}
                                </span>

                                @if(!empty($exam->exam_type))

                                    <span class="info-badge">
                                        <i class="bi bi-bookmark me-1"></i>
                                        {{ $exam->exam_type }}
                                    </span>

                                @endif

                                @if(!empty($exam->status))

                                    <span class="status-badge">
                                        <i class="bi bi-check-circle me-1"></i>
                                        {{ ucfirst($exam->status) }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Description --}}
                <div class="col-lg-5">

                    <div class="configuration-box">

                        <div class="configuration-icon">
                            <i class="bi bi-clock"></i>
                        </div>

                        <div>

                            <div class="fw-semibold">
                                Session Configuration
                            </div>

                            <div class="small text-muted">
                                Sessions define the available time slots
                                for your examination timetable.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FLASH SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success custom-alert alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-start gap-3">

                <div class="alert-icon success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="flex-grow-1">
                    <div class="fw-semibold mb-1">
                        Success
                    </div>

                    <div>
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
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger custom-alert mb-4">

            <div class="d-flex align-items-start gap-3">

                <div class="alert-icon danger-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <div>

                    <div class="fw-semibold mb-1">
                        Please correct the following errors:
                    </div>

                    <ul class="mb-0 ps-3">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
        INFORMATION NOTICE
    ========================================================== --}}
    <div class="info-notice mb-4">

        <div class="notice-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div>

            <div class="fw-semibold mb-1">
                About Exam Sessions
            </div>

            <div class="small">
                Sessions define the time periods in which exams can be
                scheduled. You can create multiple sessions such as
                <strong>Morning</strong>, <strong>Afternoon</strong>,
                or other time slots.
            </div>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}
    @php

        $totalSessions = $sessions->count();

        $activeSessions = $sessions->where('status', true)->count();

        $inactiveSessions = $sessions->where('status', false)->count();

        $totalMinutes = 0;

        foreach ($sessions as $item) {

            $itemStart = \Carbon\Carbon::parse($item->start_time);
            $itemEnd = \Carbon\Carbon::parse($item->end_time);

            $totalMinutes += $itemStart->diffInMinutes($itemEnd);

        }

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon blue">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Total Sessions
                    </div>

                    <div class="summary-value">
                        {{ $totalSessions }}
                    </div>

                    <div class="summary-description">
                        Configured time slots
                    </div>
                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Active Sessions
                    </div>

                    <div class="summary-value">
                        {{ $activeSessions }}
                    </div>

                    <div class="summary-description">
                        Available for scheduling
                    </div>
                </div>

            </div>

        </div>


        {{-- Duration --}}
        <div class="col-xl-4 col-md-6">

            <div class="summary-card">

                <div class="summary-icon orange">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Total Session Time
                    </div>

                    <div class="summary-value">
                        {{ $totalMinutes }}
                        <span class="value-unit">min</span>
                    </div>

                    <div class="summary-description">
                        Combined configured duration
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SESSIONS TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm session-card">

        <div class="card-header bg-white border-0 px-4 py-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="section-icon">
                        <i class="bi bi-list-ul"></i>
                    </div>

                    <div>

                        <h5 class="fw-bold mb-1">
                            Configured Sessions
                        </h5>

                        <div class="text-muted small">
                            Manage examination time slots.
                        </div>

                    </div>

                </div>


                @if($totalSessions > 0)

                    <span class="count-badge">
                        {{ $totalSessions }}
                        {{ $totalSessions == 1 ? 'Session' : 'Sessions' }}
                    </span>

                @endif

            </div>

        </div>


        <div class="card-body p-0">

            @if($sessions->count())

                <div class="table-responsive">

                    <table class="table sessions-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4" width="70">
                                    #
                                </th>

                                <th>
                                    Session
                                </th>

                                <th>
                                    Start Time
                                </th>

                                <th>
                                    End Time
                                </th>

                                <th>
                                    Duration
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4" width="170">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($sessions as $session)

                                @php

                                    $start = \Carbon\Carbon::parse($session->start_time);

                                    $end = \Carbon\Carbon::parse($session->end_time);

                                    $duration = $start->diffInMinutes($end);

                                    $hours = intdiv($duration, 60);

                                    $minutes = $duration % 60;

                                @endphp

                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4">

                                        <span class="row-number">
                                            {{ $loop->iteration }}
                                        </span>

                                    </td>


                                    {{-- Session --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="session-icon">
                                                <i class="bi bi-clock"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold session-name">
                                                    {{ $session->session_name }}
                                                </div>

                                                <div class="small text-muted">
                                                    Examination time slot
                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Start --}}
                                    <td>

                                        <div class="time-display">

                                            <i class="bi bi-play-circle me-1"></i>

                                            {{ $start->format('h:i A') }}

                                        </div>

                                    </td>


                                    {{-- End --}}
                                    <td>

                                        <div class="time-display">

                                            <i class="bi bi-stop-circle me-1"></i>

                                            {{ $end->format('h:i A') }}

                                        </div>

                                    </td>


                                    {{-- Duration --}}
                                    <td>

                                        <span class="duration-badge">

                                            <i class="bi bi-hourglass-split me-1"></i>

                                            @if($hours > 0)
                                                {{ $hours }}h
                                            @endif

                                            @if($minutes > 0)
                                                {{ $minutes }}m
                                            @endif

                                            @if($hours == 0 && $minutes == 0)
                                                0m
                                            @endif

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($session->status)

                                            <span class="status-active">
                                                <span class="status-dot"></span>
                                                Active
                                            </span>

                                        @else

                                            <span class="status-inactive">
                                                <span class="status-dot"></span>
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button class="btn action-btn dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route(
                                                           'admin.exam-sessions.edit',
                                                           [$exam, $session]
                                                       ) }}">

                                                        <i class="bi bi-pencil me-2 text-primary"></i>
                                                        Edit Session

                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                <li>

                                                    <form method="POST"
                                                          action="{{ route(
                                                              'admin.exam-sessions.destroy',
                                                              [$exam, $session]
                                                          ) }}"
                                                          onsubmit="return confirm('Are you sure you want to delete this session?');">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>
                                                            Delete Session

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

            @else

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}
                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-clock-history"></i>

                    </div>

                    <h5 class="fw-bold mb-2">
                        No Exam Sessions Configured
                    </h5>

                    <p class="text-muted mb-4">
                        No time slots have been added for this examination yet.
                        Add at least one session before generating the timetable.
                    </p>

                    <a href="{{ route(
                        'admin.exam-sessions.create',
                        $exam
                    ) }}"
                       class="btn btn-primary px-4">

                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Session

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =============================================================
    PAGE STYLES
============================================================= --}}
<style>

    :root {
        --primary-blue: #1677f0;
        --primary-dark: #0f5fc4;
        --soft-blue: #eef6ff;
        --border-color: #e7ebf0;
        --text-dark: #263238;
        --muted-text: #6c757d;
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .page-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 21px;
    }


    /* =========================================================
       EXAM SUMMARY
    ========================================================== */

    .exam-summary-card {
        border-radius: 16px;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f7fbff 100%
        );
    }

    .exam-icon {
        width: 54px;
        height: 54px;
        min-width: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: var(--primary-blue);
        color: #ffffff;
        font-size: 23px;
        box-shadow: 0 8px 20px rgba(22, 119, 240, 0.20);
    }

    .info-badge,
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .info-badge {
        background: #f0f3f7;
        color: #606b76;
    }

    .status-badge {
        background: #e9f8ef;
        color: #198754;
    }

    .configuration-box {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        background: #f8fbff;
        border: 1px solid #dcecff;
    }

    .configuration-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e5f1ff;
        color: var(--primary-blue);
        font-size: 19px;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .custom-alert {
        position: relative;
        border: 0;
        border-radius: 12px;
        padding: 15px 50px 15px 16px;
    }

    .alert-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
    }

    .success-icon {
        background: rgba(25, 135, 84, 0.10);
        color: #198754;
    }

    .danger-icon {
        background: rgba(220, 53, 69, 0.10);
        color: #dc3545;
    }


    /* =========================================================
       INFORMATION NOTICE
    ========================================================== */

    .info-notice {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 16px 18px;
        border-radius: 12px;
        background: #f7fbff;
        border: 1px solid #dcecff;
        color: #536170;
    }

    .notice-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #e5f1ff;
        color: var(--primary-blue);
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-card {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 19px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(30, 50, 70, 0.04);
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(30, 50, 70, 0.08);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 21px;
    }

    .summary-icon.blue {
        background: #eaf3ff;
        color: var(--primary-blue);
    }

    .summary-icon.green {
        background: #eaf8f0;
        color: #198754;
    }

    .summary-icon.orange {
        background: #fff5e6;
        color: #e09a00;
    }

    .summary-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .summary-value {
        margin-top: 2px;
        color: var(--text-dark);
        font-size: 25px;
        font-weight: 700;
        line-height: 1.2;
    }

    .value-unit {
        font-size: 13px;
        font-weight: 600;
        color: #6c757d;
    }

    .summary-description {
        color: #929aa3;
        font-size: 12px;
        margin-top: 2px;
    }


    /* =========================================================
       SESSION CARD
    ========================================================== */

    .session-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .session-card .card-header {
        border-bottom: 1px solid var(--border-color) !important;
    }

    .section-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 19px;
    }

    .count-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 8px;
        background: #f1f4f8;
        color: #65717c;
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .sessions-table {
        color: var(--text-dark);
    }

    .sessions-table thead th {
        background: #f8fafc;
        color: #68737d;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding-top: 13px;
        padding-bottom: 13px;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .sessions-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
        border-color: #eef1f4;
    }

    .sessions-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .sessions-table tbody tr:hover {
        background-color: #fafcff;
    }

    .row-number {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f4f8;
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
    }

    .session-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: var(--soft-blue);
        color: var(--primary-blue);
    }

    .session-name {
        color: var(--text-dark);
        font-size: 14px;
    }

    .time-display {
        color: #4f5b66;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .time-display i {
        color: #7f8a94;
    }

    .duration-badge {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 8px;
        background: #f4f7fa;
        color: #596570;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-active,
    .status-inactive {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-active {
        color: #198754;
    }

    .status-inactive {
        color: #6c757d;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .action-btn {
        width: 36px;
        height: 36px;
        padding: 0;
        border: 1px solid #e1e6eb;
        background: #ffffff;
        color: #66727d;
        border-radius: 8px;
    }

    .action-btn::after {
        display: none;
    }

    .action-btn:hover,
    .action-btn:focus {
        background: var(--soft-blue);
        color: var(--primary-blue);
        border-color: #cfe2ff;
    }

    .dropdown-menu {
        border-radius: 10px;
        padding: 7px;
        min-width: 175px;
    }

    .dropdown-item {
        border-radius: 7px;
        padding: 9px 10px;
        font-size: 13px;
    }

    .dropdown-item:hover {
        background: #f4f7fa;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 38px;
    }

    .empty-state h5 {
        color: var(--text-dark);
    }

    .empty-state p {
        max-width: 540px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
    }


    /* =========================================================
       BUTTONS
    ========================================================== */

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .exam-summary-card .card-body {
            padding: 18px !important;
        }

        .configuration-box {
            margin-top: 5px;
        }

        .sessions-table {
            min-width: 900px;
        }

        .summary-card {
            padding: 16px;
        }

        .empty-state {
            padding: 50px 20px;
        }

    }

</style>

@endsection

