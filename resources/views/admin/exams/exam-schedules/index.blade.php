@extends('layouts.app')

@section('title', 'Exam Timetable')

@section('content')

<style>
/* =========================================================
   SCHOOL DIARY — EXAM TIMETABLE
========================================================= */

.exam-timetable-page {
    --et-page: #f7f6f0;
    --et-paper: #fffefa;
    --et-cream: #f5f3e9;
    --et-ink: #263b35;
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

/* Header */

.et-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    margin-bottom: 25px;
    padding: 8px 0 8px 20px;
    border-left: 4px solid var(--et-wood);
}

.et-header-copy {
    min-width: 0;
}

.et-eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 7px;
    color: var(--et-wood);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.et-page-title {
    margin: 0;
    color: var(--et-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 34px);
    font-weight: 700;
    line-height: 1.2;
}

.et-page-icon {
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

.et-page-subtitle {
    margin: 9px 0 0;
    color: var(--et-muted);
    font-size: 13px;
}

/* Buttons */

.et-header-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

.et-btn {
    display: inline-flex;
    min-height: 40px;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 9px 14px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 750;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.et-btn:hover {
    transform: translateY(-1px);
}

.et-btn-primary {
    border-color: var(--et-forest);
    background: var(--et-forest);
    color: #fff;
}

.et-btn-primary:hover {
    background: #1d302b;
    color: #fff;
}

.et-btn-secondary {
    border-color: var(--et-line);
    background: var(--et-paper);
    color: var(--et-ink);
}

.et-btn-secondary:hover {
    border-color: var(--et-sage);
    background: var(--et-cream);
    color: var(--et-ink);
}

.et-btn-print {
    border-color: #d6e3d8;
    background: var(--et-green-soft);
    color: var(--et-green);
}

.et-btn-print:hover {
    border-color: var(--et-green);
    background: var(--et-green);
    color: #fff;
}

.et-btn-bulk {
    border-color: #d6e0eb;
    background: var(--et-blue-soft);
    color: var(--et-blue);
}

.et-btn-bulk:hover {
    border-color: var(--et-blue);
    background: var(--et-blue);
    color: #fff;
}

.et-btn-bulk-pdf {
    border-color: #efd3ce;
    background: var(--et-red-soft);
    color: var(--et-red);
}

.et-btn-bulk-pdf:hover {
    border-color: var(--et-red);
    background: var(--et-red);
    color: #fff;
}

/* Panels */

.et-panel {
    margin-bottom: 22px;
    overflow: hidden;
    border: 1px solid var(--et-line);
    border-radius: 16px;
    background: var(--et-paper);
    box-shadow: var(--et-shadow);
}

.et-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    padding: 17px 21px;
    border-bottom: 1px solid var(--et-line);
    background: rgba(245, 243, 233, .56);
}

.et-panel-heading {
    display: flex;
    min-width: 0;
    align-items: center;
    gap: 12px;
}

.et-panel-icon {
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

.et-icon-blue {
    background: var(--et-blue-soft);
    color: var(--et-blue);
}

.et-icon-green {
    background: var(--et-green-soft);
    color: var(--et-green);
}

.et-panel-title {
    margin: 0;
    color: var(--et-ink);
    font-size: 15px;
    font-weight: 800;
}

.et-panel-description {
    margin: 3px 0 0;
    color: var(--et-muted);
    font-size: 11px;
}

.et-panel-body {
    padding: 21px;
}

.et-no-padding {
    padding: 0;
}

/* Information cards */

.et-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.et-info-item {
    position: relative;
    min-width: 0;
    overflow: hidden;
    padding: 17px;
    border: 1px solid var(--et-line);
    border-radius: 12px;
    background: var(--et-paper);
}

.et-info-item::before {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--et-sage);
    content: "";
}

.et-info-item:nth-child(2)::before {
    background: var(--et-wood);
}

.et-info-item:nth-child(3)::before {
    background: var(--et-blue);
}

.et-info-item:nth-child(4)::before {
    background: var(--et-green);
}

.et-info-label {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;
    color: var(--et-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .6px;
    text-transform: uppercase;
}

.et-info-label i {
    color: var(--et-wood);
    font-size: 13px;
}

.et-info-value {
    overflow-wrap: anywhere;
    color: var(--et-ink);
    font-size: 15px;
    font-weight: 800;
}

/* Count */

.et-count {
    display: inline-flex;
    min-height: 29px;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border: 1px solid #d6e3d8;
    border-radius: 8px;
    background: var(--et-green-soft);
    color: var(--et-green);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

/* Table */

.et-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.et-table {
    width: 100%;
    min-width: 850px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--et-text);
    font-size: 12px;
}

.et-table thead th {
    padding: 13px 12px;
    border-bottom: 1px solid var(--et-line);
    background: #f5f3e9;
    color: var(--et-ink);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.et-table tbody td {
    padding: 13px 12px;
    border-bottom: 1px solid #eeebe2;
    color: var(--et-text);
    vertical-align: middle;
}

.et-table tbody tr:last-child td {
    border-bottom: 0;
}

.et-table tbody tr:hover {
    background: #fbfaf5;
}

.et-table th:first-child,
.et-table td:first-child {
    padding-left: 20px;
}

.et-number {
    display: inline-flex;
    width: 28px;
    height: 28px;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--et-line);
    border-radius: 8px;
    background: var(--et-cream);
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 800;
}

.et-date {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 750;
    white-space: nowrap;
}

.et-time {
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

.et-time i {
    margin-right: 4px;
    color: var(--et-sage);
}

.et-class-chip {
    display: inline-block;
    padding: 6px 9px;
    border: 1px solid #d5e3d7;
    border-radius: 7px;
    background: var(--et-green-soft);
    color: var(--et-forest);
    font-size: 11px;
    font-weight: 750;
    line-height: 1.5;
}

.et-subject {
    color: var(--et-ink);
    font-size: 12px;
    font-weight: 800;
}

.et-mark-chip {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border: 1px solid var(--et-line);
    border-radius: 7px;
    background: var(--et-cream);
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.et-duration {
    color: var(--et-ink);
    font-size: 11px;
    font-weight: 750;
    white-space: nowrap;
}

/* Empty state */

.et-empty-state {
    padding: 45px 22px;
    text-align: center;
}

.et-empty-icon {
    display: inline-flex;
    width: 70px;
    height: 70px;
    align-items: center;
    justify-content: center;
    margin-bottom: 17px;
    border: 1px solid var(--et-line);
    border-radius: 20px;
    background: var(--et-cream);
    color: var(--et-sage);
    font-size: 29px;
}

.et-empty-title {
    margin: 0 0 9px;
    color: var(--et-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 22px;
    font-weight: 700;
}

.et-empty-description {
    max-width: 440px;
    margin: 0 auto 22px;
    color: var(--et-muted);
    font-size: 12px;
    line-height: 1.8;
}

/* Responsive */

@media (max-width: 1199px) {
    .et-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 991px) {
    .exam-timetable-page {
        padding: 20px;
    }

    .et-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .et-header-actions {
        justify-content: flex-start;
    }
}

@media (max-width: 767px) {
    .exam-timetable-page {
        padding: 14px;
        border-radius: 14px;
    }

    .et-page-header {
        padding-left: 13px;
    }

    .et-header-actions {
        display: grid;
        width: 100%;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .et-header-actions .et-btn {
        width: 100%;
        padding: 9px 7px;
        font-size: 11px;
    }

    .et-info-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .et-panel-header {
        padding: 14px;
    }

    .et-panel-body {
        padding: 15px;
    }

    .et-no-padding {
        padding: 0;
    }

    .et-empty-state {
        padding: 32px 15px;
    }
}

@media (max-width: 420px) {
    .et-header-actions {
        grid-template-columns: 1fr;
    }

    .et-page-title {
        font-size: 24px;
    }

    .et-panel-title {
        font-size: 13px;
    }
}

@media print {
    .et-page-header,
    .et-header-actions {
        display: none !important;
    }

    .exam-timetable-page {
        padding: 0;
        background: #fff;
    }

    .et-panel {
        border: 1px solid #ddd;
        box-shadow: none;
    }

    .et-table {
        min-width: 0 !important;
        width: 100% !important;
    }

    .et-table th,
    .et-table td {
        padding: 6px !important;
        font-size: 9px !important;
    }
}
</style>


<div class="exam-timetable-page">

    {{-- PAGE HEADER --}}
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

            <a href="{{ route('admin.exams.index') }}"
               class="et-btn et-btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
               class="et-btn et-btn-primary">
                <i class="bi bi-magic"></i>
                Generate Timetable
            </a>

            @if($timetables->isNotEmpty())

                <a href="{{ route('admin.exam-schedules.print', $exam) }}"
                   target="_blank"
                   rel="noopener"
                   class="et-btn et-btn-print">
                    <i class="bi bi-printer"></i>
                    Print
                </a>

                <a href="{{ route('admin.exam-schedules.bulk-print', $exam) }}"
                   target="_blank"
                   rel="noopener"
                   class="et-btn et-btn-bulk">
                    <i class="bi bi-printer-fill"></i>
                    Bulk Print
                </a>

                <a href="{{ route('admin.exam-schedules.bulk-pdf', $exam) }}"
                   class="et-btn et-btn-bulk-pdf">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Bulk PDF
                </a>

            @endif

        </div>

    </header>


    {{-- EXAM INFORMATION --}}
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


    {{-- TIMETABLE CONTENT --}}
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
                    Generate a timetable to organize examination dates, paper timings,
                    classes and subjects in one place.
                </p>

                <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                   class="et-btn et-btn-primary">
                    <i class="bi bi-magic"></i>
                    Generate Timetable
                </a>

            </div>

        </section>

    @else

        @php
            /*
             * Group schedules having the same date, time, subject,
             * maximum marks and duration.
             *
             * This displays multiple classes in one row when they
             * share the same examination schedule.
             */
            $groupedTimetables = $timetables->groupBy(function ($schedule) {

                return implode('|', [
                    $schedule->exam_date
                        ? \Carbon\Carbon::parse($schedule->exam_date)->format('Y-m-d')
                        : '',

                    $schedule->start_time
                        ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i')
                        : '',

                    $schedule->end_time
                        ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i')
                        : '',

                    $schedule->examSubject?->subject?->subject_name ?? '',
                    $schedule->maximum_marks ?? '',
                    $schedule->duration_minutes ?? '',
                ]);
            })->sortKeys();
        @endphp

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
                            Examination schedule arranged by date and time.
                        </p>
                    </div>

                </div>

                <span class="et-count">
                    {{ $groupedTimetables->count() }}
                    {{ $groupedTimetables->count() === 1 ? 'Schedule' : 'Schedules' }}
                </span>

            </div>

            <div class="et-panel-body et-no-padding">

                <div class="et-table-wrap">

                    <table class="et-table">

                        <thead>
                            <tr>
                                <th width="55">#</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Max Marks</th>
                                <th>Duration</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($groupedTimetables as $group)

                                @php
                                    $first = $group->first();

                                    /*
                                     * Remove duplicate class entries.
                                     */
                                    $classRows = $group
                                        ->sortBy(function ($schedule) {
                                            return (int) ($schedule->schoolClass->class_name ?? 0);
                                        })
                                        ->unique('class_id')
                                        ->values();

                                    $classNames = $classRows->map(function ($schedule) {

                                        $name = $schedule->schoolClass->class_name ?? '-';
                                        $section = $schedule->schoolClass->section ?? '';

                                        return 'Class ' . $name
                                            . ($section !== '' ? ' - ' . $section : '');
                                    });

                                    /*
                                     * Show a class range only when:
                                     * - Class names are numeric.
                                     * - Numbers are consecutive.
                                     * - No sections are assigned.
                                     */
                                    $numericClasses = $classRows->map(function ($schedule) {
                                        return (int) ($schedule->schoolClass->class_name ?? 0);
                                    })->values();

                                    $isNumericRange = $numericClasses->isNotEmpty()
                                        && $numericClasses->every(fn ($number) => $number > 0)
                                        && $numericClasses->count() ===
                                            ($numericClasses->last() - $numericClasses->first() + 1)
                                        && $classRows->every(fn ($schedule) =>
                                            empty($schedule->schoolClass->section)
                                        );

                                    $classLabel = $isNumericRange
                                        ? 'Class ' . $numericClasses->first()
                                            . ' to Class ' . $numericClasses->last()
                                        : $classNames->implode(', ');
                                @endphp

                                <tr>

                                    <td>
                                        <span class="et-number">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="et-date">
                                            {{ $first->exam_date
                                                ? \Carbon\Carbon::parse($first->exam_date)->format('d M Y')
                                                : '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($first->start_time && $first->end_time)

                                            <span class="et-time">
                                                <i class="bi bi-clock"></i>

                                                {{ \Carbon\Carbon::parse($first->start_time)->format('h:i A') }}

                                                –

                                                {{ \Carbon\Carbon::parse($first->end_time)->format('h:i A') }}
                                            </span>

                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <span class="et-class-chip">
                                            {{ $classLabel }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="et-subject">
                                            {{ $first->examSubject?->subject?->subject_name ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="et-mark-chip">
                                            {{ $first->maximum_marks ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="et-duration">
                                            {{ $first->duration_minutes
                                                ? $first->duration_minutes . ' min'
                                                : '-' }}
                                        </span>
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