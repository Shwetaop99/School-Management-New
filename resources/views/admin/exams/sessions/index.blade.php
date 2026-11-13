
@extends('layouts.app')

@section('title', 'Exam Sessions')

@section('content')

<div class="container-fluid py-4 exam-sessions-page">

    {{-- SIMPLE HEADER --}}
    <header class="sd-page-header mb-4">
        <div class="sd-header-copy">
            <div class="sd-breadcrumb mb-2">
                <a href="{{ route('admin.exams.show', $exam) }}">Examinations</a>
                <i class="bi bi-chevron-right"></i>
                <span>Exam Sessions</span>
            </div>

            <div class="sd-header-title-row">
                <div class="sd-header-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <h1>Exam Sessions</h1>
                    <p>
                        Manage examination time slots, timings and session availability.
                    </p>
                </div>
            </div>
        </div>

        <div class="sd-header-actions">
            <a href="{{ route('admin.exams.show', $exam) }}"
               class="sd-btn sd-btn-outline">
                <i class="bi bi-arrow-left"></i>
                Back to Exam
            </a>

            <a href="{{ route('admin.exam-sessions.create', $exam) }}"
               class="sd-btn sd-btn-forest">
                <i class="bi bi-plus-lg"></i>
                Add Session
            </a>
        </div>
    </header>

    {{-- EXAM SUMMARY --}}
    <section class="sd-exam-card mb-4">
        <div class="sd-exam-main">
            <div class="sd-exam-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="sd-exam-details">
                <div class="sd-section-eyebrow">CURRENT EXAMINATION</div>
                <h2>{{ $exam->exam_name }}</h2>

                <div class="sd-meta-list">
                    <span class="sd-meta-chip">
                        <i class="bi bi-calendar3"></i>
                        {{ $exam->academic_year }}
                    </span>

                    @if(!empty($exam->exam_type))
                        <span class="sd-meta-chip">
                            <i class="bi bi-bookmark"></i>
                            {{ $exam->exam_type }}
                        </span>
                    @endif

                    @if(!empty($exam->status))
                        <span class="sd-status-chip">
                            <i class="bi bi-check-circle"></i>
                            {{ ucfirst($exam->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="sd-configuration-box">
            <div class="sd-configuration-icon">
                <i class="bi bi-calendar2-week"></i>
            </div>

            <div>
                <h3>Session Configuration</h3>
                <p>
                    Sessions define the available time slots for your examination timetable.
                </p>
            </div>
        </div>
    </section>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="sd-alert sd-alert-success mb-4" role="alert">
            <div class="sd-alert-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="sd-alert-content">
                <strong>Success</strong>
                <p>{{ session('success') }}</p>
            </div>

            <button type="button"
                    class="sd-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="sd-alert sd-alert-danger mb-4" role="alert">
            <div class="sd-alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="sd-alert-content">
                <strong>Please correct the following errors</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button"
                    class="sd-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- INFORMATION NOTICE --}}
    <section class="sd-notice mb-4">
        <div class="sd-notice-icon">
            <i class="bi bi-info-lg"></i>
        </div>

        <div class="sd-notice-content">
            <h3>About Exam Sessions</h3>
            <p>
                Sessions define the time periods in which exams can be scheduled.
                Create multiple sessions such as <strong>Morning</strong>,
                <strong>Afternoon</strong>, or other time slots.
            </p>
        </div>

        <div class="sd-notice-decoration">
            <i class="bi bi-clock-history"></i>
        </div>
    </section>

    {{-- SESSION STATISTICS --}}
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

    <div class="row g-3 mb-4 sd-statistics">

        <div class="col-xl-4 col-md-6">
            <div class="sd-stat-card sd-stat-sage">
                <div class="sd-stat-top"></div>
                <div class="sd-stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="sd-stat-label">Total Sessions</div>
                <div class="sd-stat-value">{{ $totalSessions }}</div>
                <div class="sd-stat-description">Configured examination time slots</div>
                <div class="sd-stat-watermark">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="sd-stat-card sd-stat-green">
                <div class="sd-stat-top"></div>
                <div class="sd-stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="sd-stat-label">Active Sessions</div>
                <div class="sd-stat-value">{{ $activeSessions }}</div>
                <div class="sd-stat-description">Available for scheduling</div>
                <div class="sd-stat-watermark">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="sd-stat-card sd-stat-amber">
                <div class="sd-stat-top"></div>
                <div class="sd-stat-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="sd-stat-label">Total Session Time</div>
                <div class="sd-stat-value">
                    {{ $totalMinutes }}
                    <span class="sd-stat-unit">min</span>
                </div>
                <div class="sd-stat-description">Combined configured duration</div>
                <div class="sd-stat-watermark">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- CONFIGURED SESSIONS TABLE --}}
    <section class="sd-table-card">

        <div class="sd-table-heading">
            <div class="sd-table-heading-main">
                <div class="sd-table-heading-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>
                    <div class="sd-section-eyebrow">TIME SLOT DIRECTORY</div>
                    <h2>Configured Sessions</h2>
                    <p>Manage examination session timings and availability.</p>
                </div>
            </div>

            @if($totalSessions > 0)
                <div class="sd-count-chip">
                    <span class="sd-count-dot"></span>
                    {{ $totalSessions }}
                    {{ $totalSessions == 1 ? 'Session' : 'Sessions' }}
                </div>
            @endif
        </div>

        <div class="sd-table-body">
            @if($sessions->count())

                <div class="table-responsive sd-table-responsive">
                    <table class="table sd-sessions-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="75">#</th>
                                <th>Session Details</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th class="text-end pe-4" width="110">Actions</th>
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
                                    <td class="ps-4">
                                        <span class="sd-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="sd-session-cell">
                                            <div class="sd-session-icon">
                                                <i class="bi bi-clock"></i>
                                            </div>

                                            <div>
                                                <div class="sd-session-name">
                                                    {{ $session->session_name }}
                                                </div>
                                                <div class="sd-session-caption">
                                                    Examination time slot
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="sd-time-value">
                                            <i class="bi bi-play-circle"></i>
                                            {{ $start->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="sd-time-value">
                                            <i class="bi bi-stop-circle"></i>
                                            {{ $end->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td>
                                        <span class="sd-duration-chip">
                                            <i class="bi bi-hourglass-split"></i>

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

                                    <td>
                                        @if($session->status)
                                            <span class="sd-session-status is-active">
                                                <span class="sd-status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="sd-session-status is-inactive">
                                                <span class="sd-status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button
                                                class="sd-action-button dropdown-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                aria-label="Session actions">
                                                <i class="bi bi-three-dots"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end sd-dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.exam-sessions.edit', [$exam, $session]) }}">
                                                        <i class="bi bi-pencil"></i>
                                                        Edit Session
                                                    </a>
                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.exam-sessions.destroy', [$exam, $session]) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this session?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="dropdown-item sd-delete-action">
                                                            <i class="bi bi-trash3"></i>
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
                {{-- EMPTY STATE --}}
                <div class="sd-empty-state">
                    <div class="sd-empty-decoration sd-empty-decoration-one"></div>
                    <div class="sd-empty-decoration sd-empty-decoration-two"></div>

                    <div class="sd-empty-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div class="sd-section-eyebrow">NOTHING SCHEDULED YET</div>
                    <h3>No Exam Sessions Configured</h3>

                    <p>
                        No time slots have been added for this examination yet.
                        Add your first session before generating the timetable.
                    </p>

                    <a href="{{ route('admin.exam-sessions.create', $exam) }}"
                       class="sd-btn sd-btn-forest">
                        <i class="bi bi-plus-lg"></i>
                        Add First Session
                    </a>

                    <div class="sd-empty-footnote">
                        <i class="bi bi-check2-circle"></i>
                        Safe Journeys. Happy Students.
                    </div>
                </div>
            @endif
        </div>

        @if($totalSessions > 0)
            <div class="sd-table-footer">
                <span>
                    <i class="bi bi-info-circle"></i>
                    Showing {{ $totalSessions }}
                    {{ $totalSessions == 1 ? 'configured session' : 'configured sessions' }}
                </span>

                <span class="sd-footer-note">
                    <i class="bi bi-shield-check"></i>
                    Examination schedule settings
                </span>
            </div>
        @endif

    </section>

</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Simple neutral header; scoped to Exam Sessions only
========================================================= */

.exam-sessions-page {
    --sd-page: #f7f6f0;
    --sd-chalk: #f5f3e9;
    --sd-paper: #fffefa;
    --sd-ink: #263b35;
    --sd-dark: #1d302b;
    --sd-soft: #40574a;
    --sd-text: #39483e;
    --sd-muted: #7b857b;
    --sd-line: #e5e1d5;
    --sd-sage: #527f70;
    --sd-blue: #426b8a;
    --sd-amber: #a87543;
    --sd-green: #43835d;
    --sd-red: #b95b50;
    --sd-cream-cta: #f1ead8;

    max-width: 1700px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
    color: var(--sd-text);
    font-family: "Public Sans", "Segoe UI", Arial, sans-serif;
}

.exam-sessions-page *,
.exam-sessions-page *::before,
.exam-sessions-page *::after {
    box-sizing: border-box;
}

.exam-sessions-page a {
    text-decoration: none;
}

.exam-sessions-page h1,
.exam-sessions-page h2,
.exam-sessions-page h3,
.exam-sessions-page p {
    margin-top: 0;
}

.exam-sessions-page .text-muted {
    color: var(--sd-muted) !important;
}

/* SIMPLE HEADER — NO COLOURED HERO BACKGROUND */

.sd-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 20px;
    padding: 5px 0 23px;
    border-bottom: 1px solid var(--sd-line);
}

.sd-header-copy {
    min-width: 0;
}

.sd-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    color: var(--sd-muted);
    font-size: 11px;
}

.sd-breadcrumb a {
    color: var(--sd-sage);
    font-weight: 650;
}

.sd-breadcrumb a:hover {
    color: var(--sd-ink);
}

.sd-breadcrumb i {
    font-size: 9px;
    color: #a3a99d;
}

.sd-header-title-row {
    display: flex;
    align-items: center;
    gap: 13px;
}

.sd-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 47px;
    width: 47px;
    height: 47px;
    border: 1px solid var(--sd-line);
    border-radius: 12px;
    background: var(--sd-paper);
    color: var(--sd-sage);
    font-size: 21px;
}

.sd-header-title-row h1 {
    margin-bottom: 5px;
    color: var(--sd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(24px, 2.6vw, 31px);
    font-weight: 700;
    letter-spacing: -.4px;
}

.sd-header-title-row p {
    margin-bottom: 0;
    color: var(--sd-muted);
    font-size: 12px;
    line-height: 1.7;
}

.sd-header-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* BUTTONS */

.exam-sessions-page .sd-btn {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    min-height: 39px;
    padding: 10px 14px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
    transition: background .2s ease, color .2s ease,
                border-color .2s ease, transform .2s ease;
}

.exam-sessions-page .sd-btn:hover {
    transform: translateY(-1px);
}

.exam-sessions-page .sd-btn-outline {
    border-color: var(--sd-line);
    background: var(--sd-paper);
    color: var(--sd-soft);
}

.exam-sessions-page .sd-btn-outline:hover {
    border-color: #cbd6c7;
    background: var(--sd-chalk);
    color: var(--sd-ink);
}

.exam-sessions-page .sd-btn-forest {
    background: var(--sd-dark);
    color: var(--sd-paper);
    box-shadow: 0 4px 11px rgba(29, 48, 43, .1);
}

.exam-sessions-page .sd-btn-forest:hover {
    background: var(--sd-sage);
    color: #fff;
}

/* EXAM SUMMARY */

.sd-exam-card {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(280px, .8fr);
    align-items: center;
    gap: 25px;
    padding: 23px;
    border: 1px solid var(--sd-line);
    border-radius: 15px;
    background: var(--sd-paper);
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}

.sd-exam-main {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    min-width: 0;
}

.sd-exam-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 53px;
    width: 53px;
    height: 53px;
    border: 1px solid #dfe5d8;
    border-radius: 14px;
    background: #edf0e6;
    color: var(--sd-sage);
    font-size: 23px;
}

.sd-section-eyebrow {
    display: block;
    margin-bottom: 7px;
    color: var(--sd-sage);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.1px;
}

.sd-exam-details h2 {
    margin-bottom: 12px;
    color: var(--sd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 23px;
    font-weight: 700;
}

.sd-meta-list {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}

.sd-meta-chip,
.sd-status-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border: 1px solid var(--sd-line);
    border-radius: 8px;
    background: var(--sd-chalk);
    color: var(--sd-soft);
    font-size: 11px;
    font-weight: 650;
}

.sd-status-chip {
    border-color: #d5e5d5;
    background: #edf5eb;
    color: var(--sd-green);
}

.sd-configuration-box {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 17px;
    border: 1px solid #e0e6d9;
    border-radius: 12px;
    background: #f7f8f0;
}

.sd-configuration-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 41px;
    width: 41px;
    height: 41px;
    border-radius: 11px;
    background: #e7ecde;
    color: var(--sd-sage);
    font-size: 19px;
}

.sd-configuration-box h3 {
    margin: 2px 0 5px;
    color: var(--sd-ink);
    font-size: 13px;
    font-weight: 750;
}

.sd-configuration-box p {
    margin-bottom: 0;
    color: var(--sd-muted);
    font-size: 12px;
    line-height: 1.7;
}

/* ALERTS */

.sd-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 13px;
    padding: 16px 48px 16px 17px;
    border: 1px solid var(--sd-line);
    border-radius: 12px;
    background: var(--sd-paper);
}

.sd-alert-success {
    border-color: #d7e7d8;
    background: #f3f8f0;
}

.sd-alert-danger {
    border-color: #edd8d2;
    background: #fff7f4;
}

.sd-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 37px;
    width: 37px;
    height: 37px;
    border-radius: 10px;
    background: #e1eddd;
    color: var(--sd-green);
    font-size: 18px;
}

.sd-alert-danger .sd-alert-icon {
    background: #f6e5df;
    color: var(--sd-red);
}

.sd-alert-content strong {
    display: block;
    margin: 1px 0 5px;
    color: var(--sd-ink);
    font-size: 13px;
}

.sd-alert-content p {
    margin-bottom: 0;
    color: var(--sd-text);
    font-size: 13px;
    line-height: 1.6;
}

.sd-alert-content ul {
    margin: 0;
    padding-left: 18px;
    color: var(--sd-text);
    font-size: 12px;
    line-height: 1.8;
}

.sd-alert-close {
    position: absolute;
    top: 15px;
    right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border: 0;
    border-radius: 7px;
    background: transparent;
    color: var(--sd-muted);
}

.sd-alert-close:hover {
    background: rgba(38, 59, 53, .07);
    color: var(--sd-ink);
}

/* INFORMATION NOTICE */

.sd-notice {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 13px;
    overflow: hidden;
    padding: 17px 20px;
    border: 1px solid #dce4d5;
    border-radius: 12px;
    background: #f3f5eb;
}

.sd-notice-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #e2e9d9;
    color: var(--sd-sage);
    font-size: 18px;
}

.sd-notice-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
}

.sd-notice-content h3 {
    margin: 1px 0 5px;
    color: var(--sd-ink);
    font-size: 13px;
    font-weight: 750;
}

.sd-notice-content p {
    margin-bottom: 0;
    color: #687568;
    font-size: 12px;
    line-height: 1.8;
}

.sd-notice-content strong {
    color: var(--sd-ink);
}

.sd-notice-decoration {
    position: absolute;
    top: 50%;
    right: 24px;
    transform: translateY(-50%);
    color: rgba(82, 127, 112, .09);
    font-size: 62px;
}

/* STATISTICS */

.sd-stat-card {
    position: relative;
    height: 100%;
    min-height: 163px;
    overflow: hidden;
    padding: 22px 21px;
    border: 1px solid var(--sd-line);
    border-radius: 14px;
    background: var(--sd-paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.sd-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .08);
}

.sd-stat-top {
    position: absolute;
    top: 0;
    left: 20px;
    width: 53px;
    height: 4px;
    border-radius: 0 0 5px 5px;
    background: var(--sd-sage);
}

.sd-stat-green .sd-stat-top {
    background: var(--sd-green);
}

.sd-stat-amber .sd-stat-top {
    background: var(--sd-amber);
}

.sd-stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    margin-bottom: 14px;
    border-radius: 12px;
    background: #e8eee4;
    color: var(--sd-sage);
    font-size: 20px;
}

.sd-stat-green .sd-stat-icon {
    background: #e7f0e5;
    color: var(--sd-green);
}

.sd-stat-amber .sd-stat-icon {
    background: #f5ecdf;
    color: var(--sd-amber);
}

.sd-stat-label {
    color: var(--sd-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .85px;
    text-transform: uppercase;
}

.sd-stat-value {
    margin-top: 5px;
    color: var(--sd-ink);
    font-size: 29px;
    font-weight: 800;
    line-height: 1.15;
}

.sd-stat-unit {
    color: var(--sd-muted);
    font-size: 12px;
    font-weight: 700;
}

.sd-stat-description {
    margin-top: 6px;
    color: var(--sd-muted);
    font-size: 11px;
}

.sd-stat-watermark {
    position: absolute;
    right: 16px;
    bottom: 12px;
    color: rgba(82, 127, 112, .07);
    font-size: 57px;
    pointer-events: none;
}

.sd-stat-green .sd-stat-watermark {
    color: rgba(67, 131, 93, .07);
}

.sd-stat-amber .sd-stat-watermark {
    color: rgba(168, 117, 67, .08);
}

/* TABLE CARD */

.sd-table-card {
    overflow: hidden;
    border: 1px solid var(--sd-line);
    border-radius: 15px;
    background: var(--sd-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .04);
}

.sd-table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 23px 24px;
    border-bottom: 1px solid var(--sd-line);
    background: var(--sd-paper);
}

.sd-table-heading-main {
    display: flex;
    align-items: center;
    gap: 13px;
}

.sd-table-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 45px;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: #e8eee4;
    color: var(--sd-sage);
    font-size: 20px;
}

.sd-table-heading h2 {
    margin-bottom: 5px;
    color: var(--sd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
}

.sd-table-heading p {
    margin-bottom: 0;
    color: var(--sd-muted);
    font-size: 12px;
}

.sd-count-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border: 1px solid var(--sd-line);
    border-radius: 8px;
    background: var(--sd-chalk);
    color: var(--sd-soft);
    font-size: 11px;
    font-weight: 750;
}

.sd-count-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--sd-sage);
}

.sd-table-responsive {
    width: 100%;
}

.sd-sessions-table {
    color: var(--sd-text);
}

.sd-sessions-table thead th {
    padding-top: 14px;
    padding-bottom: 14px;
    border-top: 0;
    border-bottom: 1px solid var(--sd-line);
    background: #f5f4ec;
    color: #737d70;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
    white-space: nowrap;
}

.sd-sessions-table tbody td {
    padding-top: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eeece3;
    vertical-align: middle;
}

.sd-sessions-table tbody tr:last-child td {
    border-bottom: 0;
}

.sd-sessions-table tbody tr {
    transition: background .15s ease;
}

.sd-sessions-table tbody tr:hover {
    background: #f8f8f1;
}

.sd-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid var(--sd-line);
    border-radius: 9px;
    background: var(--sd-chalk);
    color: var(--sd-soft);
    font-size: 11px;
    font-weight: 750;
}

.sd-session-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 175px;
}

.sd-session-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    border: 1px solid #dfe7d9;
    border-radius: 11px;
    background: #eef1e7;
    color: var(--sd-sage);
    font-size: 17px;
}

.sd-session-name {
    color: var(--sd-ink);
    font-size: 13px;
    font-weight: 750;
    overflow-wrap: anywhere;
}

.sd-session-caption {
    margin-top: 4px;
    color: var(--sd-muted);
    font-size: 10px;
}

.sd-time-value {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--sd-soft);
    font-size: 12px;
    font-weight: 650;
    white-space: nowrap;
}

.sd-time-value i {
    color: var(--sd-sage);
    font-size: 14px;
}

.sd-duration-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 9px;
    border: 1px solid #e7dfcf;
    border-radius: 8px;
    background: #f7f1e6;
    color: #8d6c3e;
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.sd-session-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.sd-session-status.is-active {
    border-color: #d9e8d8;
    background: #edf5e9;
    color: var(--sd-green);
}

.sd-session-status.is-inactive {
    border-color: #e4e2d9;
    background: #f2f0e9;
    color: var(--sd-muted);
}

.sd-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

/* ACTION MENU */

.sd-action-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    padding: 0;
    border: 1px solid var(--sd-line);
    border-radius: 9px;
    background: var(--sd-paper);
    color: var(--sd-soft);
    transition: all .2s ease;
}

.sd-action-button::after {
    display: none;
}

.sd-action-button:hover,
.sd-action-button:focus {
    border-color: #cbd9c8;
    background: #edf1e7;
    color: var(--sd-ink);
    box-shadow: none;
}

.sd-dropdown-menu {
    min-width: 175px;
    padding: 7px;
    border: 1px solid var(--sd-line) !important;
    border-radius: 11px;
    background: var(--sd-paper);
    box-shadow: 0 12px 28px rgba(29, 48, 43, .12);
}

.sd-dropdown-menu .dropdown-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    border-radius: 7px;
    color: var(--sd-text);
    font-size: 12px;
}

.sd-dropdown-menu .dropdown-item i {
    color: var(--sd-sage);
}

.sd-dropdown-menu .dropdown-item:hover {
    background: var(--sd-chalk);
    color: var(--sd-ink);
}

.sd-dropdown-menu .dropdown-divider {
    border-color: var(--sd-line);
}

.sd-dropdown-menu .sd-delete-action,
.sd-dropdown-menu .sd-delete-action i {
    color: var(--sd-red);
}

.sd-dropdown-menu .sd-delete-action:hover {
    background: #fbefeb;
    color: var(--sd-red);
}

/* EMPTY STATE */

.sd-empty-state {
    position: relative;
    overflow: hidden;
    padding: 62px 24px 35px;
    text-align: center;
}

.sd-empty-decoration {
    position: absolute;
    width: 150px;
    height: 150px;
    border: 1px solid rgba(82, 127, 112, .1);
    border-radius: 50%;
    pointer-events: none;
}

.sd-empty-decoration-one {
    top: -80px;
    left: -55px;
}

.sd-empty-decoration-two {
    right: -60px;
    bottom: -85px;
}

.sd-empty-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 79px;
    height: 79px;
    margin: 0 auto 21px;
    border: 1px solid #dfe6d8;
    border-radius: 23px;
    background: #eef1e7;
    color: var(--sd-sage);
    font-size: 34px;
}

.sd-empty-state .sd-section-eyebrow {
    margin-bottom: 9px;
}

.sd-empty-state h3 {
    margin-bottom: 10px;
    color: var(--sd-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 23px;
    font-weight: 700;
}

.sd-empty-state p {
    max-width: 490px;
    margin: 0 auto 23px;
    color: var(--sd-muted);
    font-size: 12px;
    line-height: 1.9;
}

.sd-empty-footnote {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 7px;
    margin-top: 27px;
    padding-top: 19px;
    border-top: 1px solid var(--sd-line);
    color: var(--sd-muted);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 11px;
    font-style: italic;
}

.sd-empty-footnote i {
    color: var(--sd-green);
}

/* FOOTER */

.sd-table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 13px 22px;
    border-top: 1px solid var(--sd-line);
    background: #f8f7f0;
    color: var(--sd-muted);
    font-size: 10px;
}

.sd-table-footer span {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.sd-table-footer i {
    color: var(--sd-sage);
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .sd-exam-card {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767.98px) {
    .exam-sessions-page {
        padding-right: 15px;
        padding-left: 15px;
    }

    .sd-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .sd-header-actions {
        width: 100%;
    }

    .sd-header-actions .sd-btn {
        flex: 1 1 auto;
    }

    .sd-header-title-row {
        align-items: flex-start;
    }

    .sd-header-title-row h1 {
        font-size: 25px;
    }

    .sd-exam-card {
        gap: 19px;
        padding: 18px;
    }

    .sd-exam-main {
        gap: 12px;
    }

    .sd-exam-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 19px;
    }

    .sd-exam-details h2 {
        font-size: 20px;
    }

    .sd-stat-card {
        min-height: 150px;
        padding: 19px;
    }

    .sd-table-heading {
        align-items: flex-start;
        padding: 19px;
    }

    .sd-table-heading h2 {
        font-size: 18px;
    }

    .sd-sessions-table {
        min-width: 900px;
    }

    .sd-table-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .sd-empty-state {
        padding: 48px 20px 28px;
    }

    .sd-empty-state h3 {
        font-size: 21px;
    }
}

@media (max-width: 420px) {
    .sd-header-actions {
        flex-direction: column;
    }

    .sd-header-actions .sd-btn {
        width: 100%;
    }

    .sd-notice {
        padding: 14px;
    }

    .sd-notice-decoration {
        display: none;
    }

    .sd-table-heading-main {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .exam-sessions-page *,
    .exam-sessions-page *::before,
    .exam-sessions-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

@endsection
