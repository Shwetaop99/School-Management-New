
@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

@php
    use Illuminate\Support\Carbon;

    $groupedByDate = $schedules->groupBy(function ($schedule) {
        return Carbon::parse($schedule->exam_date)->format('Y-m-d');
    });
@endphp

<div class="exam-timetable-page">

    {{-- PAGE HEADER --}}
    <div class="timetable-page-header">
        <div class="timetable-heading">
            <div class="timetable-page-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="timetable-heading-content">
                <div class="timetable-eyebrow">EXAMINATION MANAGEMENT</div>

                <h1 class="timetable-page-title">Exam Timetable</h1>

                <p class="timetable-page-subtitle">
                    <span>{{ $exam->exam_name }}</span>

                    @if($exam->exam_type)
                        <span class="heading-divider">/</span>
                        {{ $exam->exam_type }}
                    @endif

                    @if($exam->academic_year)
                        <span class="heading-divider">/</span>
                        {{ $exam->academic_year }}
                    @endif
                </p>
            </div>
        </div>

        <div class="timetable-header-actions">
            <a href="{{ route('admin.exam-schedules.generate', $exam) }}"
               class="timetable-btn timetable-btn-primary">
                <i class="bi bi-magic"></i>
                Generate Timetable
            </a>

            <a href="{{ route('admin.exam-schedules.print', array_merge(
                    ['exam' => $exam->id],
                    request()->only(['class_id', 'exam_date'])
                )) }}"
               target="_blank"
               rel="noopener"
               class="timetable-btn timetable-btn-light">
                <i class="bi bi-printer"></i>
                Print
            </a>

            <a href="{{ route('admin.exam-schedules.pdf', array_merge(
                    ['exam' => $exam->id],
                    request()->only(['class_id', 'exam_date'])
                )) }}"
               class="timetable-btn timetable-btn-light">
                <i class="bi bi-file-earmark-pdf"></i>
                PDF
            </a>
        </div>
    </div>

    {{-- BREADCRUMB --}}
    <div class="timetable-breadcrumb">
        <span>Examinations</span>
        <i class="bi bi-chevron-right"></i>
        <span class="breadcrumb-current">Exam Timetable</span>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="timetable-alert timetable-alert-success" role="status">
            <div class="alert-symbol">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div class="alert-copy">
                <strong>Changes saved successfully</strong>
                <p>{{ session('success') }}</p>
            </div>

            <button type="button" class="alert-dismiss"
                    data-bs-dismiss="alert" aria-label="Dismiss">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="timetable-alert timetable-alert-error" role="alert">
            <div class="alert-symbol">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="alert-copy">
                <strong>Unable to complete the action</strong>
                <p>{{ session('error') }}</p>
            </div>

            <button type="button" class="alert-dismiss"
                    data-bs-dismiss="alert" aria-label="Dismiss">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="timetable-alert timetable-alert-error" role="alert">
            <div class="alert-symbol">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="alert-copy">
                <strong>Please correct the following errors</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="alert-dismiss"
                    data-bs-dismiss="alert" aria-label="Dismiss">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="timetable-summary-grid">

        <div class="timetable-summary-card">
            <div class="summary-top-line line-sage"></div>
            <div class="summary-card-content">
                <div class="summary-icon summary-icon-sage">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Exam Period</span>

                    <strong class="summary-value">
                        @if($exam->start_date)
                            {{ Carbon::parse($exam->start_date)->format('d M Y') }}

                            @if($exam->end_date)
                                <span class="summary-separator">—</span>
                                {{ Carbon::parse($exam->end_date)->format('d M Y') }}
                            @endif
                        @else
                            —
                        @endif
                    </strong>

                    <span class="summary-caption">Scheduled examination dates</span>
                </div>
            </div>
        </div>

        <div class="timetable-summary-card">
            <div class="summary-top-line line-blue"></div>
            <div class="summary-card-content">
                <div class="summary-icon summary-icon-blue">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Classes</span>
                    <strong class="summary-value">{{ $classes->count() }}</strong>
                    <span class="summary-caption">Available classes</span>
                </div>
            </div>
        </div>

        <div class="timetable-summary-card">
            <div class="summary-top-line line-green"></div>
            <div class="summary-card-content">
                <div class="summary-icon summary-icon-green">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Timetable Entries</span>
                    <strong class="summary-value">{{ $schedules->count() }}</strong>
                    <span class="summary-caption">Scheduled exam entries</span>
                </div>
            </div>
        </div>

        <div class="timetable-summary-card">
            <div class="summary-top-line line-amber"></div>
            <div class="summary-card-content">
                <div class="summary-icon summary-icon-amber">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="summary-details">
                    <span class="summary-label">Exam Days</span>
                    <strong class="summary-value">{{ $groupedByDate->count() }}</strong>
                    <span class="summary-caption">Distinct examination dates</span>
                </div>
            </div>
        </div>

    </div>

    {{-- FILTER CARD --}}
    <div class="timetable-filter-card">

        <div class="timetable-card-header">
            <div class="filter-header-icon">
                <i class="bi bi-funnel"></i>
            </div>

            <div class="filter-header-copy">
                <h2>Filter Timetable</h2>
                <p>Find examination entries by class or examination date.</p>
            </div>

            @if(request('class_id') || request('exam_date'))
                <span class="filter-active-badge">
                    <span class="filter-active-dot"></span>
                    Filters applied
                </span>
            @endif
        </div>

        <div class="timetable-filter-body">
            <form method="GET"
                  action="{{ route('admin.exam-schedules.index', $exam) }}">

                <div class="timetable-filter-grid">

                    <div class="timetable-filter-field">
                        <label for="class_id" class="timetable-label">
                            <i class="bi bi-people"></i>
                            Class
                        </label>

                        <select name="class_id" id="class_id"
                                class="timetable-input">
                            <option value="">All Classes</option>

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

                    <div class="timetable-filter-field">
                        <label for="exam_date" class="timetable-label">
                            <i class="bi bi-calendar-date"></i>
                            Examination Date
                        </label>

                        <input type="date"
                               name="exam_date"
                               id="exam_date"
                               class="timetable-input"
                               value="{{ request('exam_date') }}">
                    </div>

                    <div class="timetable-filter-actions">
                        <button type="submit"
                                class="timetable-btn timetable-btn-primary filter-submit">
                            <i class="bi bi-funnel"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                           class="timetable-btn timetable-btn-light filter-reset"
                           title="Clear filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            @if(request('class_id') || request('exam_date'))
                <div class="active-filter-list">
                    <span class="active-filter-heading">Active filters:</span>

                    @if(request('class_id'))
                        @php
                            $selectedClass = $classes->firstWhere('id', request('class_id'));
                        @endphp

                        <span class="active-filter-chip">
                            <i class="bi bi-mortarboard"></i>
                            {{ $selectedClass->class_name ?? 'Selected class' }}
                            @if($selectedClass && $selectedClass->section)
                                - {{ $selectedClass->section }}
                            @endif
                        </span>
                    @endif

                    @if(request('exam_date'))
                        <span class="active-filter-chip">
                            <i class="bi bi-calendar3"></i>
                            {{ Carbon::parse(request('exam_date'))->format('d M Y') }}
                        </span>
                    @endif

                    <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                       class="clear-filter-link">
                        Clear all
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- TIMETABLE --}}
    @forelse($groupedByDate as $date => $dateSchedules)

        @php
            $groupedBySession = $dateSchedules->groupBy(function ($schedule) {
                return $schedule->exam_session_id
                    ?? $schedule->session_id
                    ?? (
                        ($schedule->start_time ?? '') .
                        '|' .
                        ($schedule->end_time ?? '')
                    );
            });
        @endphp

        <div class="timetable-day-card">

            {{-- DATE HEADER --}}
            <div class="timetable-day-header">
                <div class="day-heading-left">
                    <div class="date-calendar-tile">
                        <span class="date-day">
                            {{ Carbon::parse($date)->format('d') }}
                        </span>

                        <span class="date-month">
                            {{ Carbon::parse($date)->format('M') }}
                        </span>
                    </div>

                    <div class="day-heading-copy">
                        <div class="day-heading-eyebrow">EXAMINATION DAY</div>

                        <h2 class="date-title">
                            {{ Carbon::parse($date)->format('d F Y') }}
                        </h2>

                        <div class="date-weekday">
                            <i class="bi bi-calendar-week"></i>
                            {{ Carbon::parse($date)->format('l') }}
                        </div>
                    </div>
                </div>

                <div class="date-summary">
                    <div class="date-summary-number">
                        {{ $dateSchedules->count() }}
                    </div>

                    <div>
                        <span class="date-summary-label">Scheduled</span>
                        <strong class="date-summary-text">
                            {{ $dateSchedules->count() == 1 ? 'Exam entry' : 'Exam entries' }}
                        </strong>
                    </div>
                </div>
            </div>

            {{-- SESSION GROUPS --}}
            @foreach($groupedBySession as $sessionKey => $sessionSchedules)

                @php
                    $firstSchedule = $sessionSchedules->first();

                    $startTime = $firstSchedule->start_time ?? null;
                    $endTime = $firstSchedule->end_time ?? null;

                    $sessionName =
                        optional($firstSchedule->session)->session_name
                        ?? optional($firstSchedule->examSession)->session_name
                        ?? $firstSchedule->session_name
                        ?? null;

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

                <div class="timetable-session-block">

                    {{-- SESSION HEADER --}}
                    <div class="timetable-session-header">
                        <div class="session-heading-left">
                            <div class="session-icon">
                                <i class="bi bi-clock"></i>
                            </div>

                            <div>
                                <h3 class="session-title">{{ $sessionName }}</h3>

                                @if($startTime && $endTime)
                                    <div class="session-time">
                                        <i class="bi bi-clock-history"></i>
                                        {{ Carbon::parse($startTime)->format('h:i A') }}
                                        <span>—</span>
                                        {{ Carbon::parse($endTime)->format('h:i A') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <span class="session-count">
                            {{ $sessionSchedules->count() }}
                            {{ $sessionSchedules->count() == 1 ? 'Entry' : 'Entries' }}
                        </span>
                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive timetable-table-wrap">
                        <table class="table timetable-table mb-0">
                            <thead>
                                <tr>
                                    <th class="time-column">Time</th>
                                    <th class="class-column">Class</th>
                                    <th class="subject-column">Subject</th>
                                    <th class="marks-column text-center">Max Marks</th>
                                    <th class="duration-column text-center">Duration</th>
                                    <th class="teacher-column">Invigilator</th>
                                    <th class="actions-column text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($sessionSchedules as $schedule)
                                    <tr>
                                        {{-- TIME --}}
                                        <td>
                                            <div class="time-display">
                                                <div class="time-main">
                                                    <i class="bi bi-clock"></i>

                                                    @if($schedule->start_time)
                                                        {{ Carbon::parse($schedule->start_time)->format('h:i A') }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>

                                                @if($schedule->end_time)
                                                    <div class="time-end">
                                                        Until
                                                        {{ Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- CLASS --}}
                                        <td>
                                            <div class="class-display">
                                                <div class="class-icon">
                                                    <i class="bi bi-mortarboard"></i>
                                                </div>

                                                <div class="table-entity-copy">
                                                    <div class="class-name">
                                                        {{ optional($schedule->schoolClass)->class_name ?? '—' }}
                                                    </div>

                                                    @if(optional($schedule->schoolClass)->section)
                                                        <div class="class-section">
                                                            Section {{ $schedule->schoolClass->section }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- SUBJECT --}}
                                        <td>
                                            <div class="subject-display">
                                                <div class="subject-icon">
                                                    <i class="bi bi-book"></i>
                                                </div>

                                                <div class="table-entity-copy">
                                                    <div class="subject-name">
                                                        {{
                                                            optional(
                                                                optional($schedule->examSubject)->subject
                                                            )->subject_name ?? '—'
                                                        }}
                                                    </div>

                                                    @if(optional(optional($schedule->examSubject)->subject)->subject_code)
                                                        <div class="subject-code">
                                                            Code:
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
                                                    <i class="bi bi-award"></i>
                                                    {{ $schedule->maximum_marks }}
                                                </span>
                                            @else
                                                <span class="table-dash">—</span>
                                            @endif
                                        </td>

                                        {{-- DURATION --}}
                                        <td class="text-center">
                                            @if($schedule->duration_minutes)
                                                <span class="duration-badge">
                                                    <i class="bi bi-stopwatch"></i>
                                                    {{ $schedule->duration_minutes }} min
                                                </span>
                                            @else
                                                <span class="table-dash">—</span>
                                            @endif
                                        </td>

                                        {{-- INVIGILATOR --}}
                                        <td>
                                            @if($schedule->teacher)
                                                @php
                                                    $teacherName = trim(
                                                        ($schedule->teacher->first_name ?? '') . ' ' .
                                                        ($schedule->teacher->middle_name ?? '') . ' ' .
                                                        ($schedule->teacher->last_name ?? '')
                                                    );

                                                    if ($teacherName === '') {
                                                        $teacherName = $schedule->teacher->name ?? 'Teacher';
                                                    }
                                                @endphp

                                                <div class="teacher-display">
                                                    <div class="teacher-avatar">
                                                        {{ strtoupper(substr($teacherName, 0, 1)) }}
                                                    </div>

                                                    <div class="table-entity-copy">
                                                        <div class="teacher-name">
                                                            {{ $teacherName }}
                                                        </div>

                                                        @if($schedule->teacher->employee_id ?? false)
                                                            <div class="teacher-id">
                                                                ID: {{ $schedule->teacher->employee_id }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="unassigned-badge">
                                                    <i class="bi bi-exclamation-circle"></i>
                                                    Not Assigned
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button
                                                    class="timetable-action-btn"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    aria-label="Timetable entry actions"
                                                    title="Actions">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end timetable-dropdown">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route(
                                                               'admin.exam-schedules.edit',
                                                               [
                                                                   'exam' => $exam->id,
                                                                   'schedule' => $schedule->id
                                                               ]
                                                           ) }}">
                                                            <i class="bi bi-pencil-square"></i>
                                                            Edit Entry
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>

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
                                                            onsubmit="return confirm('Are you sure you want to delete this timetable entry?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                    class="dropdown-item dropdown-item-danger">
                                                                <i class="bi bi-trash3"></i>
                                                                Delete Entry
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

        {{-- EMPTY STATE --}}
        <div class="timetable-empty-card">
            <div class="empty-decoration empty-decoration-one"></div>
            <div class="empty-decoration empty-decoration-two"></div>

            <div class="empty-state-content">
                <div class="empty-icon">
                    <i class="bi bi-calendar2-x"></i>
                </div>

                <div class="empty-eyebrow">NO SCHEDULED ENTRIES</div>

                <h2>No Timetable Found</h2>

                <p>
                    No examination timetable matches the selected filters.
                    Clear the filters or generate the timetable for this examination.
                </p>

                <div class="empty-state-actions">
                    @if(request('class_id') || request('exam_date'))
                        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                           class="timetable-btn timetable-btn-light">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Clear Filters
                        </a>
                    @endif

                    <a href="{{ route('admin.exam-schedules.generate', $exam) }}"
                       class="timetable-btn timetable-btn-primary">
                        <i class="bi bi-magic"></i>
                        Generate Timetable
                    </a>
                </div>
            </div>
        </div>

    @endforelse

    {{-- FOOTER NOTE --}}
    <div class="timetable-bottom-note">
        <i class="bi bi-journal-check"></i>
        <span>
            <strong>Examination Records</strong>
            <span class="bottom-note-divider">/</span>
            Keep examination schedules accurate and up to date.
        </span>
    </div>

</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   EXAM TIMETABLE
========================================================= */

.exam-timetable-page {
    --tt-page: #f7f6f0;
    --tt-paper: #fffefa;
    --tt-cream: #f5f3e9;
    --tt-ink: #263b35;
    --tt-dark: #1d302b;
    --tt-soft: #40574a;
    --tt-text: #39483e;
    --tt-muted: #7b857b;
    --tt-line: #e5e1d5;
    --tt-sage: #527f70;
    --tt-blue: #426b8a;
    --tt-amber: #a87543;
    --tt-green: #43835d;
    --tt-red: #b95b50;

    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--tt-text);
    font-family: "Public Sans", "Segoe UI", sans-serif;
    font-size: 14px;
}

.exam-timetable-page *,
.exam-timetable-page *::before,
.exam-timetable-page *::after {
    box-sizing: border-box;
}

.exam-timetable-page a {
    text-decoration: none;
}

.exam-timetable-page button,
.exam-timetable-page input,
.exam-timetable-page select {
    font: inherit;
}

/* HEADER */

.timetable-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 18px;
}

.timetable-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.timetable-page-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    border: 1px solid #d9dfd2;
    border-radius: 15px;
    background: #e9eee4;
    color: var(--tt-ink);
    font-size: 23px;
}

.timetable-heading-content {
    min-width: 0;
}

.timetable-eyebrow {
    margin-bottom: 5px;
    color: var(--tt-sage);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.timetable-page-title {
    margin: 0;
    color: var(--tt-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 2.3vw, 32px);
    font-weight: 700;
    letter-spacing: -.7px;
    line-height: 1.25;
}

.timetable-page-subtitle {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 7px;
    margin: 7px 0 0;
    color: var(--tt-muted);
    font-size: 12px;
    line-height: 1.7;
}

.timetable-page-subtitle > span:first-child {
    color: var(--tt-soft);
    font-weight: 750;
}

.heading-divider {
    color: #b6b8aa;
}

.timetable-header-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 9px;
}

/* BUTTONS */

.timetable-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 0 14px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease,
        transform .2s ease, box-shadow .2s ease;
}

.timetable-btn-primary {
    border-color: var(--tt-dark);
    background: linear-gradient(135deg, #344f43 0%, #20352c 100%);
    color: #fffefa;
    box-shadow: 0 4px 10px rgba(29, 48, 43, .12);
}

.timetable-btn-primary:hover {
    border-color: var(--tt-dark);
    background: var(--tt-dark);
    color: #fffefa;
    box-shadow: 0 6px 14px rgba(29, 48, 43, .16);
    transform: translateY(-1px);
}

.timetable-btn-light {
    border-color: #dedfd5;
    background: var(--tt-paper);
    color: var(--tt-soft);
}

.timetable-btn-light:hover {
    border-color: #c5cbbd;
    background: #f0f1e8;
    color: var(--tt-ink);
}

.timetable-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 112, .35);
    outline-offset: 3px;
}

/* BREADCRUMB */

.timetable-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-bottom: 22px;
    color: #969b90;
    font-size: 11px;
}

.timetable-breadcrumb i {
    font-size: 9px;
}

.timetable-breadcrumb .breadcrumb-current {
    color: var(--tt-ink);
    font-weight: 750;
}

/* ALERTS */

.timetable-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    position: relative;
    margin-bottom: 18px;
    padding: 16px 48px 16px 17px;
    border: 1px solid;
    border-radius: 12px;
}

.timetable-alert-success {
    border-color: #d8e4d3;
    background: #f1f5ec;
    color: var(--tt-ink);
}

.timetable-alert-error {
    border-color: #e9cbc4;
    background: #fff8f5;
    color: #843f36;
}

.alert-symbol {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 10px;
    background: rgba(82, 127, 112, .11);
    color: var(--tt-green);
    font-size: 17px;
}

.timetable-alert-error .alert-symbol {
    background: #f7e5df;
    color: var(--tt-red);
}

.alert-copy {
    min-width: 0;
    padding-top: 2px;
}

.alert-copy strong {
    display: block;
    margin-bottom: 4px;
    font-size: 12px;
}

.alert-copy p {
    margin: 0;
    font-size: 12px;
    line-height: 1.7;
    overflow-wrap: anywhere;
}

.alert-copy ul {
    margin: 7px 0 0;
    padding-left: 18px;
    font-size: 11px;
    line-height: 1.8;
}

.alert-dismiss {
    position: absolute;
    top: 13px;
    right: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid transparent;
    border-radius: 8px;
    background: transparent;
    color: var(--tt-muted);
    cursor: pointer;
}

.alert-dismiss:hover {
    border-color: var(--tt-line);
    background: rgba(255, 255, 255, .65);
}

/* SUMMARY */

.timetable-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 22px;
}

.timetable-summary-card {
    overflow: hidden;
    min-width: 0;
    border: 1px solid var(--tt-line);
    border-radius: 15px;
    background: var(--tt-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.timetable-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(38, 59, 53, .07);
}

.summary-top-line {
    height: 3px;
}

.line-sage {
    background: #527f70;
}

.line-blue {
    background: #6687a0;
}

.line-green {
    background: #43835d;
}

.line-amber {
    background: #b78b57;
}

.summary-card-content {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 17px;
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    border-radius: 12px;
    font-size: 19px;
}

.summary-icon-sage {
    background: #e8eee5;
    color: var(--tt-ink);
}

.summary-icon-blue {
    background: #e9eff2;
    color: var(--tt-blue);
}

.summary-icon-green {
    background: #e8eee5;
    color: var(--tt-green);
}

.summary-icon-amber {
    background: #f5ecdf;
    color: var(--tt-amber);
}

.summary-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.summary-label {
    color: var(--tt-muted);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
}

.summary-value {
    overflow-wrap: anywhere;
    color: var(--tt-ink);
    font-size: 15px;
    font-weight: 800;
    line-height: 1.5;
}

.summary-separator {
    padding: 0 2px;
    color: #a2a699;
}

.summary-caption {
    color: #969b90;
    font-size: 10px;
    line-height: 1.5;
}

/* FILTER CARD */

.timetable-filter-card {
    overflow: hidden;
    margin-bottom: 23px;
    border: 1px solid var(--tt-line);
    border-radius: 15px;
    background: var(--tt-paper);
    box-shadow: 0 4px 16px rgba(38, 59, 53, .035);
}

.timetable-card-header {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 20px 23px;
    border-bottom: 1px solid var(--tt-line);
    background: #fbfaf5;
}

.filter-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    height: 41px;
    flex-shrink: 0;
    border: 1px solid #dce2d7;
    border-radius: 11px;
    background: #e9eee4;
    color: var(--tt-ink);
    font-size: 17px;
}

.filter-header-copy {
    min-width: 0;
}

.filter-header-copy h2 {
    margin: 0 0 4px;
    color: var(--tt-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 18px;
    font-weight: 700;
}

.filter-header-copy p {
    margin: 0;
    color: var(--tt-muted);
    font-size: 11px;
    line-height: 1.6;
}

.filter-active-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-left: auto;
    padding: 7px 10px;
    border: 1px solid #dce3d5;
    border-radius: 30px;
    background: #edf1e8;
    color: var(--tt-soft);
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.filter-active-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--tt-sage);
}

.timetable-filter-body {
    padding: 23px;
}

.timetable-filter-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
    align-items: end;
    gap: 17px;
}

.timetable-filter-field {
    min-width: 0;
}

.timetable-label {
    display: block;
    margin-bottom: 9px;
    color: var(--tt-ink);
    font-size: 12px;
    font-weight: 750;
}

.timetable-label i {
    margin-right: 5px;
    color: var(--tt-sage);
}

.timetable-input {
    display: block;
    width: 100%;
    min-width: 0;
    min-height: 45px;
    padding: 10px 12px;
    border: 1px solid #dedfd5;
    border-radius: 9px;
    outline: 0;
    background: var(--tt-paper);
    color: var(--tt-ink);
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.timetable-input:focus {
    border-color: var(--tt-sage);
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .11);
}

.timetable-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-submit {
    min-height: 45px;
}

.filter-reset {
    min-height: 45px;
}

.active-filter-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px solid var(--tt-line);
}

.active-filter-heading {
    color: var(--tt-muted);
    font-size: 11px;
    font-weight: 650;
}

.active-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #dfe3d8;
    border-radius: 7px;
    background: #f3f4ec;
    color: var(--tt-soft);
    font-size: 10px;
    font-weight: 700;
}

.active-filter-chip i {
    color: var(--tt-sage);
}

.clear-filter-link {
    margin-left: 3px;
    color: var(--tt-red);
    font-size: 11px;
    font-weight: 750;
}

/* DAY CARD */

.timetable-day-card {
    overflow: hidden;
    margin-bottom: 22px;
    border: 1px solid var(--tt-line);
    border-radius: 15px;
    background: var(--tt-paper);
    box-shadow: 0 4px 17px rgba(38, 59, 53, .04);
}

.timetable-day-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 20px 23px;
    border-bottom: 1px solid var(--tt-line);
    background: #f7f6ef;
}

.day-heading-left {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.date-calendar-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 59px;
    height: 61px;
    flex-shrink: 0;
    border: 1px solid #d8dfd3;
    border-radius: 13px;
    background: #e9eee4;
    color: var(--tt-ink);
    line-height: 1;
}

.date-day {
    font-family: Georgia, "Times New Roman", serif;
    font-size: 25px;
    font-weight: 700;
}

.date-month {
    margin-top: 5px;
    color: var(--tt-sage);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.day-heading-copy {
    min-width: 0;
}

.day-heading-eyebrow {
    margin-bottom: 4px;
    color: var(--tt-sage);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.date-title {
    margin: 0;
    color: var(--tt-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
    line-height: 1.4;
}

.date-weekday {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    color: var(--tt-muted);
    font-size: 11px;
}

.date-weekday i {
    color: var(--tt-sage);
}

.date-summary {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 9px 13px;
    border: 1px solid #e0e1d6;
    border-radius: 11px;
    background: var(--tt-paper);
}

.date-summary-number {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #e8eee5;
    color: var(--tt-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 16px;
    font-weight: 700;
}

.date-summary-label {
    display: block;
    margin-bottom: 3px;
    color: var(--tt-muted);
    font-size: 9px;
    font-weight: 750;
    letter-spacing: .6px;
    text-transform: uppercase;
}

.date-summary-text {
    display: block;
    color: var(--tt-ink);
    font-size: 11px;
    font-weight: 750;
}

/* SESSION */

.timetable-session-block {
    border-bottom: 1px solid var(--tt-line);
}

.timetable-session-block:last-child {
    border-bottom: 0;
}

.timetable-session-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 14px 22px;
    border-bottom: 1px solid #e5e5d9;
    background: #f3f3e9;
}

.session-heading-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.session-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    border: 1px solid #dce2d7;
    border-radius: 10px;
    background: #e7ece2;
    color: var(--tt-ink);
    font-size: 16px;
}

.session-title {
    margin: 0;
    color: var(--tt-ink);
    font-size: 13px;
    font-weight: 800;
}

.session-time {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-top: 4px;
    color: var(--tt-muted);
    font-size: 10px;
}

.session-time i {
    color: var(--tt-sage);
}

.session-count {
    padding: 7px 10px;
    border: 1px solid #dfe1d5;
    border-radius: 30px;
    background: var(--tt-paper);
    color: var(--tt-soft);
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

/* TABLE */

.timetable-table-wrap {
    width: 100%;
}

.timetable-table {
    min-width: 1080px;
    border-collapse: separate;
    border-spacing: 0;
}

.timetable-table thead th {
    padding: 14px 15px;
    border-bottom: 1px solid var(--tt-line);
    background: #fbfaf5;
    color: var(--tt-muted);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
    white-space: nowrap;
}

.timetable-table tbody td {
    padding: 15px;
    border-bottom: 1px solid #eeece3;
    background: var(--tt-paper);
    color: var(--tt-text);
    vertical-align: middle;
}

.timetable-table tbody tr:last-child td {
    border-bottom: 0;
}

.timetable-table tbody tr:hover td {
    background: #faf9f3;
}

.time-column {
    width: 150px;
}

.class-column {
    width: 170px;
}

.subject-column {
    width: 220px;
}

.marks-column {
    width: 100px;
}

.duration-column {
    width: 115px;
}

.teacher-column {
    width: 230px;
}

.actions-column {
    width: 75px;
}

.time-display {
    white-space: nowrap;
}

.time-main {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--tt-ink);
    font-size: 11px;
    font-weight: 800;
}

.time-main i {
    color: var(--tt-sage);
}

.time-end {
    margin-top: 5px;
    padding-left: 19px;
    color: var(--tt-muted);
    font-size: 10px;
}

.class-display,
.subject-display,
.teacher-display {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.class-icon,
.subject-icon,
.teacher-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex-shrink: 0;
    border: 1px solid #e0e2d7;
    border-radius: 10px;
    font-size: 15px;
}

.class-icon {
    background: #e9eee4;
    color: var(--tt-ink);
}

.subject-icon {
    background: #edf1e7;
    color: var(--tt-green);
}

.teacher-avatar {
    border-color: #e4dfd0;
    background: #f1eadb;
    color: #806541;
    font-size: 12px;
    font-weight: 800;
}

.table-entity-copy {
    min-width: 0;
}

.class-name,
.subject-name,
.teacher-name {
    overflow-wrap: anywhere;
    color: var(--tt-ink);
    font-size: 11px;
    font-weight: 750;
    line-height: 1.6;
}

.class-section,
.subject-code,
.teacher-id {
    margin-top: 3px;
    color: var(--tt-muted);
    font-size: 10px;
}

.marks-badge,
.duration-badge,
.unassigned-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 7px 9px;
    border: 1px solid;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.marks-badge {
    border-color: #e0e2d7;
    background: #f2f2e9;
    color: var(--tt-ink);
}

.duration-badge {
    border-color: #eadbc4;
    background: #f6ecda;
    color: #8b6338;
}

.unassigned-badge {
    border-color: #e9d6ce;
    background: #fbefea;
    color: #a25343;
}

.table-dash {
    color: #a1a397;
}

/* ACTION MENU */

.timetable-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border: 1px solid #dedfd5;
    border-radius: 9px;
    background: var(--tt-paper);
    color: var(--tt-soft);
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
}

.timetable-action-btn:hover,
.timetable-action-btn[aria-expanded="true"] {
    border-color: #c5cbbd;
    background: #edf0e7;
    color: var(--tt-ink);
}

.timetable-dropdown {
    min-width: 165px;
    padding: 6px;
    border: 1px solid var(--tt-line) !important;
    border-radius: 11px;
    background: var(--tt-paper);
    box-shadow: 0 10px 28px rgba(38, 59, 53, .12) !important;
}

.timetable-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 10px;
    border-radius: 7px;
    color: var(--tt-soft);
    font-size: 11px;
    font-weight: 650;
}

.timetable-dropdown .dropdown-item i {
    width: 15px;
    color: var(--tt-sage);
}

.timetable-dropdown .dropdown-item:hover {
    background: #f0f1e8;
    color: var(--tt-ink);
}

.timetable-dropdown .dropdown-item-danger,
.timetable-dropdown .dropdown-item-danger i {
    color: var(--tt-red);
}

.timetable-dropdown .dropdown-item-danger:hover {
    background: #fbefea;
    color: #963f35;
}

/* EMPTY STATE */

.timetable-empty-card {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 350px;
    padding: 45px 24px;
    border: 1px solid var(--tt-line);
    border-radius: 16px;
    background: var(--tt-paper);
    text-align: center;
    box-shadow: 0 4px 17px rgba(38, 59, 53, .04);
}

.empty-decoration {
    position: absolute;
    width: 170px;
    height: 170px;
    border: 1px solid #e9e8dd;
    border-radius: 50%;
    pointer-events: none;
}

.empty-decoration-one {
    top: -95px;
    right: -45px;
}

.empty-decoration-two {
    bottom: -120px;
    left: -50px;
    width: 220px;
    height: 220px;
}

.empty-state-content {
    position: relative;
    z-index: 1;
    max-width: 500px;
}

.empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #dce2d7;
    border-radius: 22px;
    background: #edf0e7;
    color: var(--tt-ink);
    font-size: 30px;
}

.empty-eyebrow {
    margin-bottom: 9px;
    color: var(--tt-sage);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

.empty-state-content h2 {
    margin: 0 0 10px;
    color: var(--tt-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 25px;
    font-weight: 700;
}

.empty-state-content p {
    margin: 0 auto 23px;
    color: var(--tt-muted);
    font-size: 12px;
    line-height: 1.8;
}

.empty-state-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

/* BOTTOM NOTE */

.timetable-bottom-note {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding: 13px 3px 0;
    color: var(--tt-muted);
    font-size: 11px;
    line-height: 1.7;
}

.timetable-bottom-note > i {
    color: var(--tt-sage);
    font-size: 15px;
}

.timetable-bottom-note strong {
    color: var(--tt-soft);
}

.bottom-note-divider {
    margin: 0 7px;
    color: #c1c1b5;
}

/* RESPONSIVE */

@media (max-width: 1200px) {
    .timetable-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .timetable-header-actions {
        width: 100%;
    }

    .timetable-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .timetable-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .timetable-filter-actions {
        grid-column: 1 / -1;
    }
}

@media (max-width: 767.98px) {
    .exam-timetable-page {
        padding: 14px;
    }

    .timetable-heading {
        align-items: flex-start;
        gap: 12px;
    }

    .timetable-page-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        font-size: 19px;
    }

    .timetable-page-title {
        font-size: 25px;
    }

    .timetable-header-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .timetable-header-actions .timetable-btn-primary {
        grid-column: 1 / -1;
    }

    .timetable-header-actions .timetable-btn {
        width: 100%;
    }

    .timetable-summary-grid {
        gap: 12px;
    }

    .summary-card-content {
        padding: 14px;
        gap: 10px;
    }

    .summary-icon {
        width: 39px;
        height: 39px;
        font-size: 17px;
    }

    .summary-value {
        font-size: 13px;
    }

    .timetable-card-header {
        padding: 17px;
    }

    .timetable-filter-body {
        padding: 17px;
    }

    .timetable-filter-grid {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .timetable-filter-actions {
        grid-column: auto;
        display: grid;
        grid-template-columns: 1fr auto;
    }

    .timetable-day-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 17px;
    }

    .date-summary {
        width: 100%;
    }

    .timetable-session-header {
        padding: 12px 15px;
    }

    .timetable-table {
        min-width: 1080px;
    }

    .timetable-bottom-note {
        align-items: flex-start;
    }
}

@media (max-width: 480px) {
    .exam-timetable-page {
        padding: 11px;
    }

    .timetable-page-icon {
        display: none;
    }

    .timetable-page-title {
        font-size: 23px;
    }

    .timetable-page-subtitle {
        font-size: 11px;
    }

    .timetable-summary-grid {
        grid-template-columns: 1fr;
    }

    .summary-card-content {
        padding: 15px 17px;
    }

    .timetable-card-header {
        align-items: flex-start;
        gap: 10px;
    }

    .filter-header-icon {
        width: 36px;
        height: 36px;
    }

    .filter-active-badge {
        margin-left: auto;
        padding: 6px 8px;
        font-size: 9px;
    }

    .date-title {
        font-size: 16px;
    }

    .date-calendar-tile {
        width: 52px;
        height: 56px;
    }

    .date-day {
        font-size: 22px;
    }

    .session-count {
        padding: 6px 8px;
        font-size: 9px;
    }

    .empty-state-content h2 {
        font-size: 22px;
    }

    .empty-state-actions {
        flex-direction: column;
    }

    .empty-state-actions .timetable-btn {
        width: 100%;
    }

    .bottom-note-divider {
        margin: 0 3px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .exam-timetable-page *,
    .exam-timetable-page *::before,
    .exam-timetable-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

@endsection
