
@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

<style>
/* =========================================================
   SCHOOL DIARY — EXAM TIMETABLE
   Scoped styles: exam-timetable-page
========================================================= */

.exam-timetable-page {
    --et-page: #f7f6f0;
    --et-paper: #fffefa;
    --et-cream: #f5f3e9;
    --et-cream-dark: #f1ead8;
    --et-ink: #263b35;
    --et-ink-dark: #1d302b;
    --et-forest: #344f43;
    --et-green: #34775a;
    --et-green-soft: #e8f1e9;
    --et-sage: #527f70;
    --et-wood: #a87543;
    --et-blue: #426b8a;
    --et-blue-soft: #eaf0f5;
    --et-red: #b95b50;
    --et-red-soft: #f8eae7;
    --et-muted: #7b857b;
    --et-text: #39483e;
    --et-line: #e5e1d5;
    --et-shadow: 0 8px 26px rgba(38, 59, 53, .055);
    --et-radius: 16px;

    padding: 26px;
    border-radius: 20px;
    background: var(--et-page);
    color: var(--et-text);
    font-family: Inter, "Public Sans", -apple-system, BlinkMacSystemFont,
        "Segoe UI", sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

.exam-timetable-page *,
.exam-timetable-page *::before,
.exam-timetable-page *::after {
    box-sizing: border-box;
}

/* Page heading */

.exam-timetable-page .et-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 26px;
    padding: 8px 0 8px 20px;
    border-left: 4px solid var(--et-wood);
}

.exam-timetable-page .et-header-copy {
    min-width: 0;
}

.exam-timetable-page .et-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
    color: var(--et-wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.exam-timetable-page .et-page-title {
    margin: 0;
    color: var(--et-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.exam-timetable-page .et-page-icon {
    display: inline-flex;
    width: 36px;
    height: 36px;
    align-items: center;
    justify-content: center;
    margin-right: 7px;
    border: 1px solid var(--et-line);
    border-radius: 11px;
    background: var(--et-cream);
    color: var(--et-forest);
    font-size: 18px;
    vertical-align: middle;
}

.exam-timetable-page .et-page-subtitle {
    margin: 9px 0 0;
    color: var(--et-muted);
    font-size: 13px;
}

.exam-timetable-page .et-page-subtitle strong {
    color: var(--et-ink);
    font-weight: 750;
}

/* Buttons */

.exam-timetable-page .et-header-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 9px;
}

.exam-timetable-page .et-btn {
    display: inline-flex;
    min-height: 41px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 750;
    line-height: 1.3;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, color .2s ease,
        border-color .2s ease, transform .2s ease;
}

.exam-timetable-page .et-btn:hover {
    transform: translateY(-1px);
}

.exam-timetable-page .et-btn:focus-visible {
    outline: 3px solid rgba(82, 127, 112, .28);
    outline-offset: 2px;
}

.exam-timetable-page .et-btn-primary {
    border-color: var(--et-forest);
    background: var(--et-forest);
    color: #fffefa;
}

.exam-timetable-page .et-btn-primary:hover {
    border-color: var(--et-ink-dark);
    background: var(--et-ink-dark);
    color: #fffefa;
}

.exam-timetable-page .et-btn-secondary {
    border-color: var(--et-line);
    background: var(--et-paper);
    color: var(--et-ink);
}

.exam-timetable-page .et-btn-secondary:hover {
    border-color: var(--et-sage);
    background: var(--et-cream);
    color: var(--et-ink-dark);
}

.exam-timetable-page .et-btn-cream {
    border-color: var(--et-cream-dark);
    background: var(--et-cream-dark);
    color: var(--et-ink);
}

.exam-timetable-page .et-btn-cream:hover {
    background: #fff5dc;
    color: var(--et-ink-dark);
}

.exam-timetable-page .et-btn-pdf {
    border-color: #efd3ce;
    background: var(--et-red-soft);
    color: var(--et-red);
}

.exam-timetable-page .et-btn-pdf:hover {
    border-color: var(--et-red);
    background: var(--et-red);
    color: #fff;
}

/* Panels */

.exam-timetable-page .et-panel {
    margin-bottom: 22px;
    overflow: hidden;
    border: 1px solid var(--et-line);
    border-radius: var(--et-radius);
    background: var(--et-paper);
    box-shadow: var(--et-shadow);
}

.exam-timetable-page .et-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 18px 22px;
    border-bottom: 1px solid var(--et-line);
    background: rgba(245, 243, 233, .56);
}

.exam-timetable-page .et-panel-heading {
    display: flex;
    min-width: 0;
    align-items: center;
    gap: 12px;
}

.exam-timetable-page .et-panel-icon {
    display: inline-flex;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: var(--et-paper);
    color: var(--et-forest);
    font-size: 17px;
}

.exam-timetable-page .et-icon-blue {
    background: var(--et-blue-soft);
    color: var(--et-blue);
}

.exam-timetable-page .et-icon-green {
    background: var(--et-green-soft);
    color: var(--et-green);
}

.exam-timetable-page .et-icon-wood {
    background: #f7eee0;
    color: var(--et-wood);
}

.exam-timetable-page .et-panel-title {
    margin: 0;
    color: var(--et-ink);
    font-size: 15px;
    font-weight: 800;
    line-height: 1.4;
}

.exam-timetable-page .et-panel-description {
    margin: 3px 0 0;
    color: var(--et-muted);
    font-size: 11px;
}

.exam-timetable-page .et-panel-body {
    padding: 22px;
}

.exam-timetable-page .et-panel-body.et-no-padding {
    padding: 0;
}

/* Counts and badges */

.exam-timetable-page .et-count {
    display: inline-flex;
    min-height: 29px;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 5px 10px;
    border: 1px solid #d6e3d8;
    border-radius: 8px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-count-neutral {
    border-color: var(--et-line);
    background: var(--et-cream);
    color: var(--et-ink);
}

.exam-timetable-page .et-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #d6e3d8;
    border-radius: 8px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-status::before {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    content: "";
}

.exam-timetable-page .et-status-inactive {
    border-color: var(--et-line);
    background: var(--et-cream);
    color: var(--et-muted);
}

/* Information cards */

.exam-timetable-page .et-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.exam-timetable-page .et-info-item {
    min-width: 0;
    padding: 17px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: #fffefa;
    position: relative;
    overflow: hidden;
}

.exam-timetable-page .et-info-item::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--et-sage);
    content: "";
}

.exam-timetable-page .et-info-item:nth-child(2)::before {
    background: var(--et-wood);
}

.exam-timetable-page .et-info-item:nth-child(3)::before {
    background: var(--et-blue);
}

.exam-timetable-page .et-info-item:nth-child(4)::before {
    background: var(--et-green);
}

.exam-timetable-page .et-info-label {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;
    color: var(--et-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.exam-timetable-page .et-info-label i {
    color: var(--et-wood);
    font-size: 13px;
}

.exam-timetable-page .et-info-value {
    overflow-wrap: anywhere;
    color: var(--et-ink);
    font-size: 15px;
    font-weight: 800;
}

/* Timetable table */

.exam-timetable-page .et-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.exam-timetable-page .et-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--et-text);
    font-size: 12px;
}

.exam-timetable-page .et-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid var(--et-line);
    background: #f5f3e9;
    color: var(--et-ink);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .55px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.exam-timetable-page .et-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #eeebe2;
    vertical-align: middle;
}

.exam-timetable-page .et-table tbody tr:last-child td {
    border-bottom: 0;
}

.exam-timetable-page .et-table tbody tr {
    transition: background .15s ease;
}

.exam-timetable-page .et-table tbody tr:hover {
    background: #fbfaf5;
}

.exam-timetable-page .et-table thead th:first-child,
.exam-timetable-page .et-table tbody td:first-child {
    padding-left: 22px;
}

.exam-timetable-page .et-table-primary {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-timetable-page .et-table-muted {
    margin-top: 3px;
    color: var(--et-muted);
    font-size: 10px;
}

.exam-timetable-page .et-number {
    display: inline-flex;
    width: 29px;
    height: 29px;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--et-line);
    border-radius: 8px;
    background: var(--et-cream);
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 800;
}

.exam-timetable-page .et-date {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-time {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.exam-timetable-page .et-time i {
    color: var(--et-sage);
}

.exam-timetable-page .et-class-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 10px;
    border: 1px solid #d5e3d7;
    border-radius: 8px;
    background: var(--et-green-soft);
    color: var(--et-forest);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-subject {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
}

.exam-timetable-page .et-mark-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid var(--et-line);
    border-radius: 7px;
    background: var(--et-cream);
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.exam-timetable-page .et-duration {
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.exam-timetable-page .et-teacher {
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 125px;
}

.exam-timetable-page .et-teacher-avatar {
    display: inline-flex;
    width: 31px;
    height: 31px;
    flex: 0 0 31px;
    align-items: center;
    justify-content: center;
    border: 1px solid #d6e3d8;
    border-radius: 10px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 13px;
}

.exam-timetable-page .et-teacher-name {
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
}

.exam-timetable-page .et-unassigned {
    color: var(--et-muted);
    font-size: 11px;
    font-style: italic;
}

/* Actions dropdown */

.exam-timetable-page .et-action-cell {
    text-align: right;
    white-space: nowrap;
}

.exam-timetable-page .et-dropdown {
    position: relative;
    display: inline-block;
}

.exam-timetable-page .et-action-toggle {
    display: inline-flex;
    width: 35px;
    height: 35px;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--et-line);
    border-radius: 9px;
    background: var(--et-paper);
    color: var(--et-ink);
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease;
}

.exam-timetable-page .et-action-toggle:hover,
.exam-timetable-page .et-action-toggle[aria-expanded="true"] {
    border-color: var(--et-sage);
    background: var(--et-cream);
}

.exam-timetable-page .dropdown-menu {
    min-width: 165px;
    padding: 6px;
    border: 1px solid var(--et-line);
    border-radius: 11px;
    background: var(--et-paper);
    box-shadow: 0 12px 28px rgba(38, 59, 53, .12);
}

.exam-timetable-page .dropdown-item {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 9px 10px;
    border-radius: 7px;
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 650;
}

.exam-timetable-page .dropdown-item:hover,
.exam-timetable-page .dropdown-item:focus {
    background: var(--et-cream);
    color: var(--et-ink-dark);
}

.exam-timetable-page .dropdown-item.et-delete-item {
    color: var(--et-red);
}

.exam-timetable-page .dropdown-item.et-delete-item:hover,
.exam-timetable-page .dropdown-item.et-delete-item:focus {
    background: var(--et-red-soft);
    color: var(--et-red);
}

.exam-timetable-page .dropdown-divider {
    border-top-color: var(--et-line);
}

/* Empty state */

.exam-timetable-page .et-empty-state {
    padding: 45px 22px;
    text-align: center;
}

.exam-timetable-page .et-empty-icon {
    display: inline-flex;
    width: 70px;
    height: 70px;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    border: 1px solid var(--et-line);
    border-radius: 21px;
    background: var(--et-cream);
    color: var(--et-sage);
    font-size: 29px;
}

.exam-timetable-page .et-empty-title {
    margin: 0 0 9px;
    color: var(--et-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 22px;
    font-weight: 700;
}

.exam-timetable-page .et-empty-description {
    max-width: 440px;
    margin: 0 auto 22px;
    color: var(--et-muted);
    font-size: 12px;
    line-height: 1.8;
}

.exam-timetable-page .et-empty-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

/* Responsive */

@media (max-width: 1199.98px) {
    .exam-timetable-page .et-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 991.98px) {
    .exam-timetable-page {
        padding: 20px;
    }

    .exam-timetable-page .et-table {
        min-width: 1100px;
    }
}

@media (max-width: 767.98px) {
    .exam-timetable-page {
        padding: 15px;
        border-radius: 14px;
    }

    .exam-timetable-page .et-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 16px;
        padding-left: 14px;
    }

    .exam-timetable-page .et-page-title {
        font-size: 26px;
    }

    .exam-timetable-page .et-header-actions {
        display: grid;
        width: 100%;
        grid-template-columns: 1fr;
    }

    .exam-timetable-page .et-header-actions .et-btn {
        width: 100%;
    }

    .exam-timetable-page .et-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .exam-timetable-page .et-panel-body {
        padding: 16px;
    }

    .exam-timetable-page .et-panel-body.et-no-padding {
        padding: 0;
    }

    .exam-timetable-page .et-info-grid {
        grid-template-columns: 1fr;
        gap: 11px;
    }

    .exam-timetable-page .et-empty-state {
        padding: 32px 15px;
    }

    .exam-timetable-page .et-empty-title {
        font-size: 20px;
    }
}

@media (max-width: 420px) {
    .exam-timetable-page .et-panel-heading {
        align-items: flex-start;
    }

    .exam-timetable-page .et-panel-title {
        font-size: 13px;
    }

    .exam-timetable-page .et-count {
        font-size: 10px;
    }
}

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


<div class="exam-timetable-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}
    <header class="et-page-header">

        <div class="et-header-copy">

            <div class="et-eyebrow">
                <i class="bi bi-journal-check"></i>
                School Diary / Examination Management
            </div>

            <h1 class="et-page-title">
                <span class="et-page-icon">
                    <i class="bi bi-calendar3"></i>
                </span>
                Exam Timetable
            </h1>

            <p class="et-page-subtitle">

                {{ $exam->exam_name }}

                @if($exam->academic_year)
                    <span class="mx-1">·</span>
                    {{ $exam->academic_year }}
                @endif

            </p>

        </div>

        <div class="et-header-actions">

            <a
                href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                class="et-btn et-btn-primary"
            >
                <i class="bi bi-magic"></i>
                Generate Timetable
            </a>

            @if($timetables->count())

                <a
                    href="{{ route('admin.exam-schedules.print', $exam) }}"
                    target="_blank"
                    rel="noopener"
                    class="et-btn et-btn-secondary"
                >
                    <i class="bi bi-printer"></i>
                    Print
                </a>

                <a
                    href="{{ route('admin.exam-schedules.pdf', $exam) }}"
                    class="et-btn et-btn-pdf"
                >
                    <i class="bi bi-file-earmark-pdf"></i>
                    PDF
                </a>

            @endif

        </div>

    </header>


    {{-- =====================================================
        EXAM INFORMATION
    ====================================================== --}}
    <section class="et-panel">

        <div class="et-panel-header">

            <div class="et-panel-heading">

                <span class="et-panel-icon et-icon-blue">
                    <i class="bi bi-info-circle"></i>
                </span>

                <div>
                    <h2 class="et-panel-title">
                        Examination Information
                    </h2>

                    <p class="et-panel-description">
                        Overview of this examination and its timetable.
                    </p>
                </div>

            </div>

        </div>

        <div class="et-panel-body">

            <div class="et-info-grid">

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-journal-bookmark"></i>
                        Examination
                    </div>

                    <div class="et-info-value">
                        {{ $exam->exam_name }}
                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-calendar3"></i>
                        Academic Year
                    </div>

                    <div class="et-info-value">
                        {{ $exam->academic_year ?? '-' }}
                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-bookmark-check"></i>
                        Exam Type
                    </div>

                    <div class="et-info-value">
                        {{ $exam->exam_type ?? '-' }}
                    </div>

                </div>

                <div class="et-info-item">

                    <div class="et-info-label">
                        <i class="bi bi-files"></i>
                        Timetable Entries
                    </div>

                    <div class="et-info-value">
                        {{ $timetables->count() }}
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
        EMPTY STATE
    ====================================================== --}}
    @if($timetables->isEmpty())

        <section class="et-panel">

            <div class="et-empty-state">

                <div class="et-empty-icon">
                    <i class="bi bi-calendar-x"></i>
                </div>

                <h2 class="et-empty-title">
                    No Exam Timetable Generated
                </h2>

                <p class="et-empty-description">
                    The timetable for this examination has not been generated yet.
                    Generate a timetable to organize exam dates, paper timings,
                    classes and teacher assignments in one place.
                </p>

                <div class="et-empty-actions">

                    <a
                        href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                        class="et-btn et-btn-primary"
                    >
                        <i class="bi bi-magic"></i>
                        Generate Timetable
                    </a>

                </div>

            </div>

        </section>

    @else

        {{-- =================================================
            GENERATED TIMETABLE
        ================================================== --}}
        <section class="et-panel">

            <div class="et-panel-header">

                <div class="et-panel-heading">

                    <span class="et-panel-icon et-icon-green">
                        <i class="bi bi-calendar2-week"></i>
                    </span>

                    <div>

                        <h2 class="et-panel-title">
                            Generated Examination Timetable
                        </h2>

                        <p class="et-panel-description">
                            Ordered by date, time and class.
                        </p>

                    </div>

                </div>

                <span class="et-count">
                    <i class="bi bi-files"></i>
                    {{ $timetables->count() }} Papers
                </span>

            </div>


            <div class="et-panel-body et-no-padding">

                <div class="et-table-wrap">

                    <table class="et-table">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Date</th>

                                <th>Time</th>

                                <th>Class</th>

                                <th>Subject</th>

                                <th>Maximum Marks</th>

                                <th>Duration</th>

                                <th>Teacher</th>

                                <th>Status</th>

                                <th style="text-align: right; padding-right: 22px;">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($timetables as $index => $schedule)

                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        <span class="et-number">
                                            {{ $index + 1 }}
                                        </span>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        @if($schedule->exam_date)

                                            <div class="et-date">
                                                {{ $schedule->exam_date->format('d M Y') }}
                                            </div>

                                            <div class="et-table-muted">
                                                <i class="bi bi-calendar-week me-1"></i>
                                                {{ $schedule->exam_date->format('l') }}
                                            </div>

                                        @else

                                            <span class="et-table-muted">-</span>

                                        @endif

                                    </td>


                                    {{-- Time --}}
                                    <td>

                                        @if($schedule->start_time && $schedule->end_time)

                                            <div class="et-time">
                                                <i class="bi bi-clock"></i>

                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}

                                                <span>–</span>

                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                            </div>

                                        @else

                                            <span class="et-table-muted">-</span>

                                        @endif

                                    </td>


                                    {{-- Class --}}
                                    <td>

                                        <span class="et-class-chip">

                                            <i class="bi bi-mortarboard"></i>

                                            {{ $schedule->schoolClass->class_name ?? '-' }}

                                            @if(!empty($schedule->schoolClass->section))
                                                - {{ $schedule->schoolClass->section }}
                                            @endif

                                        </span>

                                    </td>


                                    {{-- Subject --}}
                                    <td>

                                        <div class="et-subject">
                                            {{ $schedule->examSubject->subject->subject_name ?? '-' }}
                                        </div>

                                        @if(!empty($schedule->examSubject->subject->subject_code))

                                            <div class="et-table-muted">
                                                Code:
                                                {{ $schedule->examSubject->subject->subject_code }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Maximum Marks --}}
                                    <td>

                                        <span class="et-mark-chip">
                                            <i class="bi bi-award"></i>
                                            {{ $schedule->maximum_marks ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- Duration --}}
                                    <td>

                                        @if($schedule->duration_minutes)

                                            <span class="et-duration">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                {{ $schedule->duration_minutes }} min
                                            </span>

                                        @else

                                            <span class="et-table-muted">-</span>

                                        @endif

                                    </td>


                                    {{-- Teacher --}}
                                    <td>

                                        @if($schedule->teacher)

                                            <div class="et-teacher">

                                                <span class="et-teacher-avatar">
                                                    <i class="bi bi-person"></i>
                                                </span>

                                                <span class="et-teacher-name">

                                                    {{ trim(
                                                        ($schedule->teacher->first_name ?? '') .
                                                        ' ' .
                                                        ($schedule->teacher->last_name ?? '')
                                                    ) }}

                                                </span>

                                            </div>

                                        @else

                                            <span class="et-unassigned">
                                                <i class="bi bi-person-dash me-1"></i>
                                                Not Assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($schedule->status)

                                            <span class="et-status">
                                                Active
                                            </span>

                                        @else

                                            <span class="et-status et-status-inactive">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="et-action-cell">

                                        <div class="dropdown et-dropdown">

                                            <button
                                                class="et-action-toggle"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                data-bs-boundary="viewport"
                                                aria-expanded="false"
                                                aria-label="Actions for timetable entry {{ $index + 1 }}"
                                            >
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

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
                                                        <i class="bi bi-pencil"></i>
                                                        Edit
                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                <li>

                                                    <form
                                                        action="{{ route(
                                                            'admin.exam-schedules.destroy',
                                                            [
                                                                'exam' => $exam->id,
                                                                'schedule' => $schedule->id
                                                            ]
                                                        ) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Delete this timetable entry?');"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item et-delete-item"
                                                        >
                                                            <i class="bi bi-trash"></i>
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

        </section>

    @endif

</div>

@endsection
