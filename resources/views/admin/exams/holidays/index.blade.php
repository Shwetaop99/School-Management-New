
@extends('layouts.app')

@section('title', 'Exam Holidays')

@section('content')

@php
    $holidayCount = $holidays->count();
@endphp

<div class="exam-holidays-page">

    {{-- PAGE HEADER --}}
    <div class="eh-page-header">

        <div class="eh-header-copy">
            <div class="eh-eyebrow">EXAMINATION MANAGEMENT</div>

            <div class="eh-title-row">
                <h1 class="eh-page-title">Exam Holidays</h1>

                <span class="eh-count-chip">
                    {{ $holidayCount }} {{ $holidayCount === 1 ? 'Holiday' : 'Holidays' }}
                </span>
            </div>

            <p class="eh-page-description">
                Manage non-working days for the examination timetable.
            </p>

            <div class="eh-breadcrumb">
                <a href="{{ route('admin.exams.show', $exam) }}">Examination</a>
                <i class="bi bi-chevron-right"></i>
                <span>Exam Holidays</span>
            </div>
        </div>

        <div class="eh-header-actions">
            <a href="{{ route('admin.exams.show', $exam) }}"
               class="eh-btn eh-btn-secondary">
                <i class="bi bi-arrow-left"></i>
                <span>Back to Exam</span>
            </a>

            <a href="{{ route('admin.exam-holidays.create', $exam) }}"
               class="eh-btn eh-btn-primary">
                <i class="bi bi-plus-lg"></i>
                <span>Add Holiday</span>
            </a>
        </div>

    </div>

    {{-- EXAM SUMMARY --}}
    <div class="eh-summary-grid">

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Examination</div>
                <div class="eh-summary-value">
                    {{ $exam->exam_name }}
                </div>
                <div class="eh-summary-note">Selected examination</div>
            </div>
        </div>

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Academic Year</div>
                <div class="eh-summary-value">
                    {{ $exam->academic_year }}
                </div>
                <div class="eh-summary-note">Current examination year</div>
            </div>
        </div>

        <div class="eh-summary-card">
            <div class="eh-summary-icon">
                <i class="bi bi-calendar-range"></i>
            </div>

            <div class="eh-summary-content">
                <div class="eh-summary-label">Exam Period</div>
                <div class="eh-summary-value">
                    @if($exam->start_date)
                        {{ \Illuminate\Support\Carbon::parse($exam->start_date)->format('d M Y') }}
                    @else
                        Not specified
                    @endif

                    @if($exam->end_date)
                        <span class="eh-date-separator">—</span>
                        {{ \Illuminate\Support\Carbon::parse($exam->end_date)->format('d M Y') }}
                    @endif
                </div>
                <div class="eh-summary-note">Scheduled examination dates</div>
            </div>
        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="eh-alert eh-alert-success" role="alert">
            <div class="eh-alert-icon">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="eh-alert-content">
                <strong>Success</strong>
                <p>{{ session('success') }}</p>
            </div>

            <button type="button"
                    class="eh-alert-close"
                    data-bs-dismiss="alert"
                    aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- VALIDATION / ERROR MESSAGES --}}
    @if($errors->any())
        <div class="eh-alert eh-alert-error" role="alert">
            <div class="eh-alert-icon">
                <i class="bi bi-exclamation-circle"></i>
            </div>

            <div class="eh-alert-content">
                <strong>Please correct the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- SCHEDULING NOTICE --}}
    <div class="eh-notice">
        <div class="eh-notice-icon">
            <i class="bi bi-info-circle"></i>
        </div>

        <div class="eh-notice-content">
            <h3>Timetable Scheduling</h3>
            <p>
                Active holidays added here will be skipped when generating
                the examination timetable, provided the timetable generation
                logic checks these holiday records.
            </p>
        </div>
    </div>

    {{-- HOLIDAYS CARD --}}
    <section class="eh-panel">

        <div class="eh-panel-header">
            <div class="eh-panel-heading">
                <div class="eh-panel-icon">
                    <i class="bi bi-calendar-week"></i>
                </div>

                <div>
                    <h2>Non-working Days</h2>
                    <p>Holidays configured for this examination.</p>
                </div>
            </div>

            <div class="eh-panel-meta">
                <span class="eh-meta-label">Total holidays</span>
                <span class="eh-meta-value">{{ $holidayCount }}</span>
            </div>
        </div>

        <div class="eh-panel-body">

            @if($holidayCount > 0)

                <div class="eh-table-wrap">
                    <table class="eh-table">
                        <thead>
                            <tr>
                                <th class="eh-number-column">#</th>
                                <th>Holiday Date</th>
                                <th>Day</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th class="eh-actions-column">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($holidays as $holiday)
                                <tr class="eh-holiday-row">

                                    {{-- NUMBER --}}
                                    <td>
                                        <span class="eh-row-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- DATE --}}
                                    <td>
                                        <div class="eh-date-cell">
                                            <div class="eh-date-icon">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>

                                            <div class="eh-date-details">
                                                <span class="eh-date-main">
                                                    {{ \Illuminate\Support\Carbon::parse($holiday->holiday_date)->format('d M Y') }}
                                                </span>

                                                <span class="eh-date-caption">
                                                    Holiday date
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- DAY --}}
                                    <td>
                                        <span class="eh-day-badge">
                                            <i class="bi bi-calendar3"></i>
                                            {{ \Illuminate\Support\Carbon::parse($holiday->holiday_date)->format('l') }}
                                        </span>
                                    </td>

                                    {{-- REASON --}}
                                    <td>
                                        @if($holiday->reason)
                                            <div class="eh-reason">
                                                <i class="bi bi-chat-left-text"></i>
                                                <span>{{ $holiday->reason }}</span>
                                            </div>
                                        @else
                                            <span class="eh-empty-value">Not provided</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($holiday->status)
                                            <span class="eh-status eh-status-active">
                                                <span class="eh-status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="eh-status eh-status-inactive">
                                                <span class="eh-status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <div class="eh-actions">

                                            <a href="{{ route('admin.exam-holidays.edit', [$exam, $holiday]) }}"
                                               class="eh-action-btn eh-edit-btn"
                                               title="Edit Holiday"
                                               aria-label="Edit holiday">
                                                <i class="bi bi-pencil"></i>
                                                <span>Edit</span>
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.exam-holidays.destroy', [$exam, $holiday]) }}"
                                                  class="eh-delete-form">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="eh-action-btn eh-delete-btn"
                                                        title="Delete Holiday"
                                                        aria-label="Delete holiday">
                                                    <i class="bi bi-trash3"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- TABLE FOOTER --}}
                <div class="eh-table-footer">
                    <span>
                        Showing <strong>{{ $holidayCount }}</strong>
                        {{ $holidayCount === 1 ? 'holiday' : 'holidays' }}
                    </span>

                    <span class="eh-footer-note">
                        <i class="bi bi-calendar-check"></i>
                        Examination holiday records
                    </span>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="eh-empty-state">
                    <div class="eh-empty-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </div>

                    <span class="eh-empty-eyebrow">HOLIDAY CALENDAR</span>

                    <h3>No Holidays Added</h3>

                    <p>
                        No non-working days have been configured for this
                        examination yet. Add Sundays, public holidays,
                        school holidays, or other non-working days as required.
                    </p>

                    <a href="{{ route('admin.exam-holidays.create', $exam) }}"
                       class="eh-btn eh-btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add First Holiday</span>
                    </a>
                </div>

            @endif

        </div>
    </section>

    <div class="eh-page-footer">
        <span>Exam Holidays</span>
        <span>Manage examination non-working days</span>
    </div>

</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Scoped exclusively to Exam Holidays
========================================================= */

.exam-holidays-page {
    --eh-page: #f7f6f0;
    --eh-paper: #fffefa;
    --eh-chalk: #f5f3e9;
    --eh-ink: #263b35;
    --eh-dark: #1d302b;
    --eh-soft: #40574a;
    --eh-text: #39483e;
    --eh-muted: #7b857b;
    --eh-line: #e5e1d5;
    --eh-sage: #527f70;
    --eh-blue: #426b8a;
    --eh-amber: #a87543;
    --eh-green: #43835d;
    --eh-red: #b95b50;

    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--eh-text);
    font-family: "Public Sans", sans-serif;
}

.exam-holidays-page *,
.exam-holidays-page *::before,
.exam-holidays-page *::after {
    box-sizing: border-box;
}

.exam-holidays-page a {
    text-decoration: none;
}

/* HEADER */

.eh-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 25px;
}

.eh-header-copy {
    min-width: 0;
}

.eh-eyebrow {
    margin-bottom: 8px;
    color: var(--eh-sage);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15em;
}

.eh-title-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.eh-page-title {
    margin: 0;
    color: var(--eh-ink);
    font-family: Georgia, serif;
    font-size: clamp(26px, 3vw, 34px);
    font-weight: 700;
    letter-spacing: -.045em;
    line-height: 1.2;
}

.eh-count-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 11px;
    border: 1px solid var(--eh-line);
    border-radius: 30px;
    background: var(--eh-paper);
    color: var(--eh-soft);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.eh-page-description {
    margin: 9px 0 13px;
    color: var(--eh-muted);
    font-size: 13px;
    line-height: 1.7;
}

.eh-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    color: var(--eh-muted);
    font-size: 11px;
}

.eh-breadcrumb a {
    color: var(--eh-sage);
    font-weight: 700;
}

.eh-breadcrumb a:hover {
    color: var(--eh-dark);
}

.eh-breadcrumb i {
    font-size: 9px;
}

.eh-header-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 9px;
    flex-shrink: 0;
    padding-top: 17px;
}

/* BUTTONS */

.exam-holidays-page .eh-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 10px 16px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.3;
    transition: background .18s ease, border-color .18s ease,
                color .18s ease, transform .18s ease;
}

.exam-holidays-page .eh-btn:hover {
    transform: translateY(-1px);
}

.exam-holidays-page .eh-btn-primary {
    border-color: var(--eh-ink);
    background: var(--eh-ink);
    color: var(--eh-paper);
}

.exam-holidays-page .eh-btn-primary:hover {
    border-color: var(--eh-dark);
    background: var(--eh-dark);
    color: #fff;
}

.exam-holidays-page .eh-btn-secondary {
    border-color: var(--eh-line);
    background: var(--eh-paper);
    color: var(--eh-ink);
}

.exam-holidays-page .eh-btn-secondary:hover {
    border-color: #c9c7b9;
    background: var(--eh-chalk);
    color: var(--eh-dark);
}

/* SUMMARY */

.eh-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 22px;
}

.eh-summary-card {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-width: 0;
    padding: 20px;
    overflow: hidden;
    border: 1px solid var(--eh-line);
    border-radius: 12px;
    background: var(--eh-paper);
    box-shadow: 0 3px 10px rgb(38 59 53 / 3%);
    transition: border-color .2s ease, box-shadow .2s ease,
                transform .2s ease;
}

.eh-summary-card::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--eh-sage);
    content: "";
}

.eh-summary-card:nth-child(2)::before {
    background: var(--eh-blue);
}

.eh-summary-card:nth-child(3)::before {
    background: var(--eh-amber);
}

.eh-summary-card:hover {
    transform: translateY(-2px);
    border-color: #d6d6c9;
    box-shadow: 0 8px 20px rgb(38 59 53 / 6%);
}

.eh-summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    border: 1px solid #dce7df;
    border-radius: 10px;
    background: #edf3ed;
    color: var(--eh-sage);
    font-size: 18px;
}

.eh-summary-card:nth-child(2) .eh-summary-icon {
    border-color: #dce5ec;
    background: #edf2f5;
    color: var(--eh-blue);
}

.eh-summary-card:nth-child(3) .eh-summary-icon {
    border-color: #eee0cf;
    background: #f7efe4;
    color: var(--eh-amber);
}

.eh-summary-content {
    min-width: 0;
    flex: 1;
}

.eh-summary-label {
    margin: 1px 0 6px;
    color: var(--eh-muted);
    font-size: 11px;
    font-weight: 600;
}

.eh-summary-value {
    overflow-wrap: anywhere;
    color: var(--eh-ink);
    font-size: 15px;
    font-weight: 750;
    line-height: 1.55;
}

.eh-summary-note {
    margin-top: 5px;
    color: var(--eh-muted);
    font-size: 10px;
    line-height: 1.5;
}

.eh-date-separator {
    padding: 0 3px;
    color: var(--eh-muted);
}

/* ALERTS */

.eh-alert {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 18px;
    padding: 16px;
    border: 1px solid var(--eh-line);
    border-radius: 10px;
    background: var(--eh-paper);
}

.eh-alert-success {
    border-color: #d4e4d7;
    background: #f4f8f1;
}

.eh-alert-error {
    border-color: #ecd5ce;
    background: #fcf5f1;
}

.eh-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    border: 1px solid #d4e4d7;
    border-radius: 9px;
    background: #e7f0e5;
    color: var(--eh-green);
    font-size: 17px;
}

.eh-alert-error .eh-alert-icon {
    border-color: #ecd5ce;
    background: #f6e7e1;
    color: var(--eh-red);
}

.eh-alert-content {
    min-width: 0;
    flex: 1;
}

.eh-alert-content strong {
    display: block;
    margin: 2px 0 4px;
    color: var(--eh-ink);
    font-size: 12px;
}

.eh-alert-content p {
    margin: 0;
    color: var(--eh-text);
    font-size: 12px;
    line-height: 1.65;
    overflow-wrap: anywhere;
}

.eh-alert-content ul {
    margin: 6px 0 0;
    padding-left: 18px;
    color: var(--eh-text);
    font-size: 12px;
    line-height: 1.8;
}

.eh-alert-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    flex-shrink: 0;
    border: 1px solid transparent;
    border-radius: 6px;
    background: transparent;
    color: var(--eh-muted);
    cursor: pointer;
}

.eh-alert-close:hover {
    border-color: var(--eh-line);
    background: var(--eh-paper);
    color: var(--eh-ink);
}

/* INFORMATION NOTICE */

.eh-notice {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 22px;
    padding: 16px 18px;
    border: 1px solid #dce5dc;
    border-radius: 10px;
    background: #f1f5ee;
}

.eh-notice-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #dce5dc;
    border-radius: 9px;
    background: var(--eh-paper);
    color: var(--eh-sage);
    font-size: 16px;
}

.eh-notice-content {
    min-width: 0;
}

.eh-notice-content h3 {
    margin: 1px 0 5px;
    color: var(--eh-ink);
    font-size: 12px;
    font-weight: 750;
}

.eh-notice-content p {
    margin: 0;
    color: var(--eh-muted);
    font-size: 12px;
    line-height: 1.7;
}

/* MAIN PANEL */

.eh-panel {
    overflow: hidden;
    border: 1px solid var(--eh-line);
    border-radius: 13px;
    background: var(--eh-paper);
    box-shadow: 0 4px 15px rgb(38 59 53 / 3%);
}

.eh-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding: 20px 22px;
    border-bottom: 1px solid var(--eh-line);
    background: var(--eh-paper);
}

.eh-panel-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.eh-panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    border: 1px solid #dce7df;
    border-radius: 10px;
    background: #edf3ed;
    color: var(--eh-sage);
    font-size: 18px;
}

.eh-panel-heading h2 {
    margin: 0 0 5px;
    color: var(--eh-ink);
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.025em;
}

.eh-panel-heading p {
    margin: 0;
    color: var(--eh-muted);
    font-size: 11px;
    line-height: 1.6;
}

.eh-panel-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    padding: 7px 10px;
    border: 1px solid var(--eh-line);
    border-radius: 8px;
    background: var(--eh-chalk);
}

.eh-meta-label {
    color: var(--eh-muted);
    font-size: 10px;
    font-weight: 600;
}

.eh-meta-value {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 25px;
    height: 25px;
    padding: 0 7px;
    border-radius: 6px;
    background: var(--eh-ink);
    color: var(--eh-paper);
    font-size: 11px;
    font-weight: 800;
}

.eh-panel-body {
    min-width: 0;
}

/* TABLE */

.eh-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.eh-table {
    width: 100%;
    min-width: 900px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--eh-text);
}

.eh-table thead th {
    padding: 14px 17px;
    border-bottom: 1px solid var(--eh-line);
    background: #f5f4ec;
    color: var(--eh-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .075em;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.eh-table tbody td {
    padding: 15px 17px;
    border-bottom: 1px solid #eeece3;
    background: var(--eh-paper);
    vertical-align: middle;
}

.eh-table tbody tr:last-child td {
    border-bottom: 0;
}

.eh-holiday-row {
    transition: background .16s ease;
}

.eh-holiday-row:hover td {
    background: #f8f8f1;
}

.eh-number-column {
    width: 65px;
}

.eh-actions-column {
    width: 165px;
}

.eh-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid var(--eh-line);
    border-radius: 7px;
    background: var(--eh-chalk);
    color: var(--eh-soft);
    font-size: 11px;
    font-weight: 750;
}

.eh-date-cell {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 145px;
}

.eh-date-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #eee0cf;
    border-radius: 9px;
    background: #f7efe4;
    color: var(--eh-amber);
    font-size: 16px;
}

.eh-date-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.eh-date-main {
    color: var(--eh-ink);
    font-size: 12px;
    font-weight: 750;
    white-space: nowrap;
}

.eh-date-caption {
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-day-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 9px;
    border: 1px solid var(--eh-line);
    border-radius: 7px;
    background: var(--eh-chalk);
    color: var(--eh-soft);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.eh-reason {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    min-width: 100px;
    max-width: 280px;
    color: var(--eh-text);
    font-size: 12px;
    line-height: 1.65;
    overflow-wrap: anywhere;
}

.eh-reason i {
    margin-top: 3px;
    flex-shrink: 0;
    color: var(--eh-muted);
}

.eh-empty-value {
    color: #9a9f94;
    font-size: 12px;
}

/* STATUS */

.eh-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 750;
    white-space: nowrap;
}

.eh-status-dot {
    width: 7px;
    height: 7px;
    flex: 0 0 7px;
    border-radius: 50%;
    background: currentColor;
}

.eh-status-active {
    border-color: #d4e4d7;
    background: #edf5ec;
    color: var(--eh-green);
}

.eh-status-inactive {
    border-color: var(--eh-line);
    background: #f2f1ea;
    color: var(--eh-muted);
}

/* ACTIONS */

.eh-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.eh-delete-form {
    display: inline-flex;
    margin: 0;
}

.exam-holidays-page .eh-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-height: 33px;
    padding: 6px 9px;
    border: 1px solid var(--eh-line);
    border-radius: 7px;
    background: var(--eh-paper);
    font-family: inherit;
    font-size: 10px;
    font-weight: 750;
    line-height: 1.3;
    white-space: nowrap;
    cursor: pointer;
    transition: background .16s ease, border-color .16s ease,
                color .16s ease;
}

.exam-holidays-page .eh-edit-btn {
    border-color: #d5e0e6;
    color: var(--eh-blue);
}

.exam-holidays-page .eh-edit-btn:hover {
    border-color: var(--eh-blue);
    background: #edf2f5;
    color: var(--eh-dark);
}

.exam-holidays-page .eh-delete-btn {
    border-color: #ead7d0;
    color: var(--eh-red);
}

.exam-holidays-page .eh-delete-btn:hover {
    border-color: var(--eh-red);
    background: #f9efeb;
    color: #923f37;
}

/* TABLE FOOTER */

.eh-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 13px 18px;
    border-top: 1px solid var(--eh-line);
    background: #faf9f3;
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-table-footer strong {
    color: var(--eh-ink);
}

.eh-footer-note {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.eh-footer-note i {
    color: var(--eh-sage);
}

/* EMPTY STATE */

.eh-empty-state {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    min-height: 340px;
    padding: 45px 22px;
    text-align: center;
}

.eh-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 78px;
    height: 78px;
    margin-bottom: 19px;
    border: 1px solid var(--eh-line);
    border-radius: 50%;
    background: var(--eh-chalk);
    color: var(--eh-sage);
    font-size: 29px;
}

.eh-empty-eyebrow {
    margin-bottom: 8px;
    color: var(--eh-sage);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .16em;
}

.eh-empty-state h3 {
    margin: 0 0 10px;
    color: var(--eh-ink);
    font-family: Georgia, serif;
    font-size: 23px;
    font-weight: 700;
}

.eh-empty-state p {
    max-width: 470px;
    margin: 0 0 22px;
    color: var(--eh-muted);
    font-size: 12px;
    line-height: 1.85;
}

/* PAGE FOOTER */

.eh-page-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 17px;
    padding: 0 3px;
    color: var(--eh-muted);
    font-size: 10px;
}

.eh-page-footer span:first-child {
    color: var(--eh-soft);
    font-weight: 750;
}

/* ACCESSIBILITY */

.exam-holidays-page a:focus-visible,
.exam-holidays-page button:focus-visible {
    outline: 3px solid rgb(82 127 112 / 35%);
    outline-offset: 3px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .exam-holidays-page {
        padding: 20px;
    }

    .eh-page-header {
        flex-direction: column;
    }

    .eh-header-actions {
        justify-content: flex-start;
        padding-top: 0;
    }

    .eh-summary-card {
        padding: 17px;
    }
}

@media (max-width: 767.98px) {
    .exam-holidays-page {
        padding: 14px;
    }

    .eh-page-header {
        gap: 17px;
        margin-bottom: 20px;
    }

    .eh-title-row {
        align-items: flex-start;
        gap: 9px;
    }

    .eh-page-title {
        font-size: 27px;
    }

    .eh-page-description {
        font-size: 12px;
    }

    .eh-header-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
        gap: 8px;
    }

    .exam-holidays-page .eh-btn {
        min-height: 41px;
        padding: 9px 10px;
        font-size: 11px;
    }

    .eh-summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
        margin-bottom: 17px;
    }

    .eh-summary-card {
        padding: 15px;
    }

    .eh-summary-value {
        font-size: 14px;
    }

    .eh-notice {
        padding: 13px;
    }

    .eh-panel-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .eh-panel-heading h2 {
        font-size: 18px;
    }

    .eh-panel-meta {
        align-self: flex-start;
    }

    .eh-table {
        min-width: 900px;
    }

    .eh-table thead th {
        padding: 13px;
    }

    .eh-table tbody td {
        padding: 13px;
    }

    .eh-table-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .eh-empty-state {
        min-height: 300px;
        padding: 35px 18px;
    }

    .eh-empty-state h3 {
        font-size: 21px;
    }

    .eh-page-footer {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 380px) {
    .exam-holidays-page {
        padding: 10px;
    }

    .eh-page-title {
        font-size: 24px;
    }

    .eh-count-chip {
        padding: 5px 8px;
        font-size: 10px;
    }

    .eh-header-actions {
        grid-template-columns: 1fr;
    }
}

@media (prefers-reduced-motion: reduce) {
    .exam-holidays-page *,
    .exam-holidays-page *::before,
    .exam-holidays-page *::after {
        scroll-behavior: auto !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

{{-- DELETE CONFIRMATION --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.eh-delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const confirmed = window.confirm(
                'Are you sure you want to delete this holiday?'
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});
</script>

@endsection
