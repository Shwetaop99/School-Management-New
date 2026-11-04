@extends('layouts.app')

@section('title', 'Create Weekly Timetable')
@section('page-title', 'Create Weekly Timetable')

@section('content')
@php
    $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $periodNumbers = range(1, 8);
@endphp

<style>
    :root {
        --ink: #1d3a33;
        --ink-2: #16302a;
        --text: #2c3e3a;
        --muted: #6b7c75;
        --accent: #e4572e;
        --chalk: #e9efe2;
        --line: #d8d1bf;
        --serif: 'Libre Baskerville', Georgia, serif;
        --hand: 'Caveat', cursive;
    }

    .tt-page {
        max-width: 1600px;
        margin: 0 auto;
        padding-bottom: 28px;
        color: var(--text);
    }
    .tt-page * { box-sizing: border-box; }

    .paper {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 10px;
    }

    /* ── Hero ─────────────────────────────────────────── */
    .hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        flex-wrap: wrap;
        padding: 22px 28px 22px 56px;
        margin-bottom: 18px;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content: "";
        position: absolute;
        left: 34px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: rgba(228, 87, 46, .35);
    }
    .hero-left .eyebrow {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 6px;
    }
    .hero-left h1 {
        font-family: var(--serif);
        font-size: 26px;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 4px;
    }
    .hero-left p {
        color: var(--muted);
        margin: 0;
        font-size: 13.5px;
        line-height: 1.45;
    }
    .hero-btns {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* ── Buttons ──────────────────────────────────────── */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
        font-family: inherit;
        white-space: nowrap;
        transition: all .15s ease;
    }
    .btn i { font-size: 14px; }

    .btn-add, .btn-solid {
        background: var(--ink);
        color: #fff;
        border-color: var(--ink);
    }
    .btn-add:hover, .btn-solid:hover {
        background: var(--ink-2);
        border-color: var(--ink-2);
        color: #fff;
    }

    .btn-line {
        background: #fff;
        border: 1px solid #cfc8b6;
        color: var(--text);
    }
    .btn-line:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    .btn-fetch {
        background: #2b5d8a;
        color: #fff;
        border-color: #2b5d8a;
    }
    .btn-fetch:hover {
        background: #1f4669;
        border-color: #1f4669;
        color: #fff;
    }

    .btn-excel {
        background: #217346;
        color: #fff;
        border-color: #217346;
    }
    .btn-excel:hover {
        background: #1a5c38;
        border-color: #1a5c38;
        color: #fff;
    }

    .btn-pdf {
        background: #e4572e;
        color: #fff;
        border-color: #e4572e;
    }
    .btn-pdf:hover {
        background: #c93f1a;
        border-color: #c93f1a;
        color: #fff;
    }

    .btn-import {
        background: #5b7a8c;
        color: #fff;
        border-color: #5b7a8c;
    }
    .btn-import:hover {
        background: #4a6575;
        border-color: #4a6575;
        color: #fff;
    }

    .btn:disabled {
        opacity: .65;
        cursor: wait;
    }

    /* ── Cards / sections ─────────────────────────────── */
    .tt-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 10px;
        margin-bottom: 18px;
        overflow: hidden;
    }
    .tt-section {
        padding: 22px 24px;
    }
    .tt-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }
    .tt-section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        background: color-mix(in srgb, #2b5d8a 14%, #fff);
        color: #2b5d8a;
    }
    .tt-section-heading h2 {
        margin: 0 0 3px;
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
    }
    .tt-section-heading p {
        margin: 0;
        color: var(--muted);
        font-size: 12.5px;
    }

    .tt-meta-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 15px;
    }
    .tt-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }
    .tt-label {
        font-size: 12px;
        font-weight: 650;
        color: #334155;
    }
    .tt-required { color: #dc2626; }
    .tt-control {
        width: 100%;
        min-width: 0;
        height: 40px;
        padding: 0 10px;
        border: 1px solid #cfc8b6;
        border-radius: 8px;
        background: #fff;
        color: #1e293b;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }
    .tt-control:focus {
        border-color: var(--ink);
        box-shadow: 0 0 0 3px rgba(29, 58, 51, .10);
    }
    .tt-control:disabled {
        background: #f4f1ea;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .tt-help {
        color: var(--muted);
        font-size: 11px;
        line-height: 1.45;
    }
    .tt-error {
        color: #dc2626;
        font-size: 11px;
    }

    .tt-alert, .tt-success {
        padding: 13px 16px;
        margin-bottom: 16px;
        border-radius: 10px;
        font-size: 13px;
    }
    .tt-alert {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }
    .tt-success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }
    .tt-alert ul { margin: 8px 0 0; padding-left: 20px; }

    /* ── Tools card ───────────────────────────────────── */
    .tt-tools-card {
        padding: 20px 24px;
        margin-bottom: 18px;
    }
    .tt-tools-card h2 {
        margin: 0 0 4px;
        font-family: var(--serif);
        font-size: 17px;
        font-weight: 700;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .tt-tools-card > p {
        margin: 0 0 14px;
        color: var(--muted);
        font-size: 12.5px;
    }
    .tt-weekly-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    /* Hidden file input for import */
    #weeklyExcelFile {
        display: none;
    }

    .tt-hint {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 11px 13px;
        margin-bottom: 14px;
        border: 1px solid #e8d9b8;
        border-radius: 9px;
        background: #fffdf6;
        color: #5c5346;
        font-size: 12px;
        line-height: 1.5;
    }
    .tt-hint i { color: var(--accent); margin-top: 1px; }

    .tt-stats {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
        margin-bottom: 17px;
    }
    .tt-stat {
        padding: 9px 12px;
        border: 1px solid var(--line);
        border-radius: 9px;
        background: #faf8f3;
        color: #5c5346;
        font-size: 12px;
    }
    .tt-stat strong {
        color: var(--ink);
        font-family: var(--serif);
        font-size: 14px;
    }

    /* ── Grid ─────────────────────────────────────────── */
    .tt-grid-wrap {
        overflow: auto;
        border: 1px solid var(--line);
        border-radius: 10px;
    }
    .tt-grid {
        width: 100%;
        min-width: 1850px;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }
    .tt-grid th {
        position: sticky;
        top: 0;
        z-index: 3;
        padding: 12px 8px;
        background: #f4f1ea;
        color: var(--ink);
        border-right: 1px solid #e5dfd0;
        border-bottom: 1px solid #e5dfd0;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        font-family: var(--serif);
    }
    .tt-grid th:first-child { left: 0; z-index: 5; width: 105px; }
    .tt-grid td {
        padding: 7px;
        vertical-align: top;
        border-right: 1px solid #ebe6d9;
        border-bottom: 1px solid #ebe6d9;
        background: #fff;
    }
    .tt-grid tr:last-child td { border-bottom: 0; }
    .tt-grid .tt-day {
        position: sticky;
        left: 0;
        z-index: 2;
        width: 105px;
        background: #faf8f3;
        text-align: center;
        vertical-align: middle;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink);
        font-family: var(--serif);
    }
    .tt-day-date {
        display: block;
        margin-top: 5px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 500;
        font-family: 'Public Sans', sans-serif;
    }

    .tt-cell {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 190px;
        padding: 8px;
        border: 1px solid #e5dfd0;
        border-radius: 9px;
        background: #fffdf8;
    }
    .tt-cell:focus-within {
        border-color: #2b5d8a;
        box-shadow: 0 0 0 2px rgba(43, 93, 138, .12);
    }
    .tt-cell-title {
        display: flex;
        justify-content: space-between;
        gap: 5px;
        color: #2b5d8a;
        font-size: 11px;
        font-weight: 700;
    }
    .tt-cell .tt-control { height: 34px; padding: 0 7px; font-size: 11.5px; }
    .tt-time-row { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
    .tt-cell.tt-special {
        background: #fff7ed;
        border-color: #fed7aa;
    }
    .tt-cell.tt-special .tt-cell-title { color: #9a3412; }
    .tt-subject-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 32px;
        gap: 5px;
        align-items: center;
    }
    .tt-add-subject {
        display: grid;
        place-items: center;
        width: 32px;
        height: 34px;
        border: 1px solid #cfc8b6;
        border-radius: 7px;
        background: #faf8f3;
        color: var(--ink);
        cursor: pointer;
    }
    .tt-add-subject:hover { background: #f0ebe0; }

    /* ── Footer ───────────────────────────────────────── */
    .tt-footer {
        position: sticky;
        bottom: 0;
        z-index: 6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 24px;
        background: rgba(255, 255, 255, .97);
        border-top: 1px solid var(--line);
        box-shadow: 0 -5px 15px rgba(29, 58, 51, .04);
    }
    .tt-footer-note { color: var(--muted); font-size: 11px; }
    .tt-footer-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    /* ── Modal ────────────────────────────────────────── */
    .tt-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 10000;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(29, 58, 51, .45);
    }
    .tt-modal-backdrop.open { display: flex; }
    .tt-modal {
        width: min(440px, 100%);
        padding: 20px;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 20px 60px rgba(29, 58, 51, .25);
        border: 1px solid var(--line);
    }
    .tt-modal h3 {
        margin: 0 0 5px;
        font-family: var(--serif);
        font-size: 17px;
        color: var(--ink);
    }
    .tt-modal p {
        margin: 0 0 15px;
        color: var(--muted);
        font-size: 12px;
    }
    .tt-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 17px;
    }

    @media (max-width: 1000px) {
        .tt-meta-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .hero { padding-left: 44px; }
        .hero::before { left: 24px; }
    }
    @media (max-width: 600px) {
        .hero { padding: 18px 16px 18px 40px; }
        .hero-left h1 { font-size: 22px; }
        .tt-meta-grid { grid-template-columns: 1fr; }
        .tt-footer {
            position: static;
            padding: 16px;
            align-items: stretch;
            flex-direction: column;
        }
        .tt-footer-actions, .tt-weekly-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="tt-page">
    {{-- Hero --}}
    <header class="paper hero">
        <div class="hero-left">
            <div class="eyebrow">Academic Management</div>
            <h1>Create Weekly Timetable</h1>
            <p>Create, fetch, export and import all 48 periods for one selected week.</p>
        </div>
        <div class="hero-btns">
            <a href="{{ route('admin.timetable.index') }}" class="btn btn-line">
                <i class="bi bi-arrow-left"></i> Back to Timetable
            </a>
        </div>
    </header>

    @if (session('success'))
        <div class="tt-success" role="status">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="tt-alert" role="alert">
            <strong><i class="bi bi-exclamation-triangle"></i> Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.timetable.save') }}" method="POST" id="weeklyTimetableForm">
        @csrf

        <form
            action="{{ route('admin.timetable.store') }}"
            method="POST"
            id="timetableForm"
        >

            @csrf


            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}

            <div class="form-section">

                <div class="section-title">

                    <div class="section-icon">
                        <i class="bi bi-clipboard-data"></i>
        {{-- Details --}}
        <div class="tt-card">
            <section class="tt-section">
                <div class="tt-section-heading">
                    <div class="tt-section-icon"><i class="bi bi-clipboard-data"></i></div>
                    <div>
                        <h2>Weekly Timetable Details</h2>
                        <p>Choose the academic year, class, section, and Monday start date.</p>
                    </div>
                </div>

                <div class="tt-meta-grid">
                    <div class="tt-field">
                        <label class="tt-label" for="academic_year">Academic Year <span class="tt-required">*</span></label>
                        <input class="tt-control" type="text" id="academic_year" name="academic_year"
                               value="{{ old('academic_year', '2026-2027') }}" placeholder="2026-2027" required>
                        @error('academic_year') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="class">Class / Standard <span class="tt-required">*</span></label>
                        <select class="tt-control" id="class" name="class" required>
                            <option value="">Select Class</option>
                            @foreach ($classes as $classItem)
                                @php $classValue = $classItem->class_name ?? $classItem->name ?? ''; @endphp
                                <option value="{{ $classValue }}" @selected(old('class') == $classValue)>{{ $classValue }}</option>
                            @endforeach
                        </select>
                        @error('class') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="section">Section <span class="tt-required">*</span></label>
                        <select class="tt-control" id="section" name="section" required>
                            <option value="">Select Section</option>
                        </select>
                        <div id="sectionHelp" class="tt-help">Select a class to load its sections.</div>
                        @error('section') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="tt-field">
                        <label class="tt-label" for="week_start_date">Week Starting (Monday) <span class="tt-required">*</span></label>
                        <input class="tt-control" type="date" id="week_start_date" name="week_start_date"
                               value="{{ old('week_start_date', now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')) }}" required>
                        <div id="weekDateHelp" class="tt-help">Choose Monday. The dates for all six days appear below.</div>
                        @error('week_start_date') <div class="tt-error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </section>
        </div>

        {{-- Tools (Save removed, Import added here) --}}
        <section class="paper tt-tools-card">
            <h2><i class="bi bi-tools"></i> Weekly Timetable Tools</h2>
            <p>These tools use the academic year, class, section and week selected above.</p>

            <div class="tt-weekly-actions">
                <button type="button" class="btn btn-fetch" id="fetchWeeklyBtn">
                    <i class="bi bi-arrow-repeat"></i> Fetch Existing Records
                </button>

                <button type="button" class="btn btn-excel" id="weeklyExcelBtn">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </button>

                <button type="button" class="btn btn-pdf" id="weeklyPdfBtn">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>

                {{-- Import button (replaces Save in this row) --}}
                <input type="file" id="weeklyExcelFile" accept=".xlsx,.xls,.csv">
                <button type="button" class="btn btn-import" id="importSubmitBtn">
                    <i class="bi bi-upload"></i> Import and Replace Selected Week
                </button>
            </div>
        </section>

        {{-- Grid --}}
        <div class="tt-card">
            <section class="tt-section">
                <div class="tt-section-heading">
                    <div class="tt-section-icon" style="background:color-mix(in srgb,#d58a0b 14%,#fff);color:#d58a0b">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                    <div>
                        <h2>Weekly Timetable Grid</h2>
                        <p>Choose a subject, teacher, period type and time for each slot.</p>
                    </div>
                </div>

                <div class="tt-hint">
                    <i class="bi bi-info-circle"></i>
                    <div><strong>Note:</strong> The grid contains Monday–Saturday and eight periods per day. Break and Lunch do not require a subject or teacher. Fetch loads saved records into the grid; it does not save changes by itself.</div>
                </div>

                <div class="tt-stats">
                    <div class="tt-stat"><strong>6</strong> working days</div>
                    <div class="tt-stat"><strong>8</strong> periods per day</div>
                    <div class="tt-stat"><strong>48</strong> timetable slots</div>
                    <div class="tt-stat">One form · One save</div>
                </div>

                <div class="tt-grid-wrap">
                    <table class="tt-grid">
                        <thead>
                            <tr>
                                <th>Day</th>
                                @foreach ($periodNumbers as $periodNumber)
                                    <th>Period {{ $periodNumber }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($weekDays as $dayIndex => $day)
                                <tr>
                                    <td class="tt-day">
                                        {{ $day }}
                                        <span class="tt-day-date day-date" data-day-index="{{ $dayIndex }}">--</span>
                                    </td>

                                    @foreach ($periodNumbers as $periodNumber)
                                        @php
                                            $old = old("timetable.{$day}.{$periodNumber}", []);
                                            if (!is_array($old)) $old = [];
                                            $prefix = "timetable[{$day}][{$periodNumber}]";
                                            $type = $old['period_type'] ?? 'Regular';
                                        @endphp
                                        <td>
                                            <div class="tt-cell {{ in_array($type, ['Break', 'Lunch'], true) ? 'tt-special' : '' }}">
                                                <div class="tt-cell-title">
                                                    <span>{{ $day }}</span><span>P{{ $periodNumber }}</span>
                                                </div>

                                                <select class="tt-control period-type" name="{{ $prefix }}[period_type]" required aria-label="{{ $day }} period {{ $periodNumber }} type">
                                                    <option value="Regular" @selected($type === 'Regular')>Class</option>
                                                    <option value="Break" @selected($type === 'Break')>Break</option>
                                                    <option value="Lunch" @selected($type === 'Lunch')>Lunch</option>
                                                    <option value="Activity" @selected($type === 'Activity')>Activity</option>
                                                </select>

                                                <div class="tt-subject-row">
                                                    <select class="tt-control period-subject" name="{{ $prefix }}[subject]" aria-label="{{ $day }} period {{ $periodNumber }} subject">
                                                        <option value="">Select Subject</option>
                                                        @foreach (($subjects ?? []) as $subjectItem)
                                                            @php
                                                                $subjectName = is_string($subjectItem)
                                                                    ? $subjectItem
                                                                    : ($subjectItem->subject_name ?? $subjectItem->name ?? $subjectItem->subject ?? '');
                                                            @endphp
                                                            @if ($subjectName !== '')
                                                                <option value="{{ $subjectName }}"
                                                                        data-subject-name="{{ $subjectName }}"
                                                                        data-class-id="{{ is_object($subjectItem) ? ($subjectItem->class_id ?? '') : '' }}"
                                                                        @selected(($old['subject'] ?? '') === $subjectName)>
                                                                    {{ $subjectName }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                        <option value="__add_subject__">+ Add subject</option>
                                                    </select>
                                                    <button type="button" class="tt-add-subject" title="Add a subject" aria-label="Add subject">
                                                        <i class="bi bi-plus-lg"></i>
                                                    </button>
                                                </div>

                                                <select class="tt-control period-teacher" name="{{ $prefix }}[teacher_id]" aria-label="{{ $day }} period {{ $periodNumber }} teacher">
                                                    <option value="">Select Teacher</option>
                                                    @foreach ($teachers as $teacher)
                                                        @php
                                                            $teacherName = trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''));
                                                            $teacherSubjects = $teacher->subject ?? $teacher->subjects ?? $teacher->subject_names ?? [];
                                                            if (is_string($teacherSubjects)) {
                                                                $teacherSubjects = array_map('trim', explode(',', $teacherSubjects));
                                                            }
                                                            if (!is_array($teacherSubjects)) $teacherSubjects = [];
                                                        @endphp
                                                        <option value="{{ $teacher->id }}"
                                                                data-subjects="{{ implode('|', $teacherSubjects) }}"
                                                                @selected((string)($old['teacher_id'] ?? '') === (string)$teacher->id)>
                                                            {{ $teacherName !== '' ? $teacherName : ($teacher->name ?? 'Teacher') }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" class="period-subject-type"
                                                       name="{{ $prefix }}[subject_type]"
                                                       value="{{ $old['subject_type'] ?? ($type === 'Activity' ? 'Activity' : 'Theory') }}">

                                                <input type="hidden" class="period-lecture-type"
                                                       name="{{ $prefix }}[lecture_type]"
                                                       value="{{ $old['lecture_type'] ?? ($type === 'Activity' ? 'activity' : strtolower($type)) }}">

                                                <div class="tt-time-row">
                                                    <input class="tt-control period-start" type="time"
                                                           name="{{ $prefix }}[start_time]" value="{{ $old['start_time'] ?? '' }}"
                                                           required aria-label="{{ $day }} period {{ $periodNumber }} start time">
                                                    <input class="tt-control period-end" type="time"
                                                           name="{{ $prefix }}[end_time]" value="{{ $old['end_time'] ?? '' }}"
                                                           required aria-label="{{ $day }} period {{ $periodNumber }} end time">
                                                </div>

                                                <input class="tt-control period-room" type="text" name="{{ $prefix }}[room]"
                                                       value="{{ $old['room'] ?? '' }}" placeholder="Room (optional)"
                                                       maxlength="100" aria-label="{{ $day }} period {{ $periodNumber }} room">

                                                <input type="hidden" name="{{ $prefix }}[day]" value="{{ $day }}">
                                                <input type="hidden" name="{{ $prefix }}[period_number]" value="{{ $periodNumber }}">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Save only in footer --}}
            <footer class="tt-footer">
                <div class="tt-footer-note">
                    <i class="bi bi-info-circle"></i>
                    Save submits the full 48-slot grid. Fetching does not save until you click Save Weekly Timetable.
                </div>
                <div class="tt-footer-actions">
                    <a href="{{ route('admin.timetable.index') }}" class="btn btn-line">Cancel</a>
                    <button type="submit" class="btn btn-add" id="footerSaveBtn">
                        <i class="bi bi-check-circle"></i> Save Weekly Timetable
                    </button>
                </div>
            </footer>
        </div>
    </form>

    {{-- Separate import form --}}
    <form id="weeklyImportForm"
          action="{{ route('admin.timetable.weekly.import') }}"
          method="POST" enctype="multipart/form-data" style="display:none;">
        @csrf
        <input type="hidden" name="academic_year" id="import_academic_year">
        <input type="hidden" name="class" id="import_class">
        <input type="hidden" name="section" id="import_section">
        <input type="hidden" name="week_start_date" id="import_week_start_date">
        <input type="file" name="file" id="import_file_hidden" accept=".xlsx,.xls,.csv">
    </form>
</div>

<div class="tt-modal-backdrop" id="addSubjectModal" aria-hidden="true">
    <div class="tt-modal" role="dialog" aria-modal="true" aria-labelledby="addSubjectTitle">
        <h3 id="addSubjectTitle">Add subject to this period</h3>
        <p>This adds the subject as an option in the current form only. To save it permanently, use your school's subject management page.</p>
        <div class="tt-field">
            <label class="tt-label" for="newSubjectName">Subject name</label>
            <input class="tt-control" type="text" id="newSubjectName" maxlength="100" placeholder="e.g. Mathematics">
        </div>
        <div class="tt-modal-actions">
            <button type="button" class="btn btn-line" id="cancelAddSubject">Cancel</button>
            <button type="button" class="btn btn-add" id="confirmAddSubject">Add to timetable</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSelect = document.getElementById('class');
    const sectionSelect = document.getElementById('section');
    const sectionHelp = document.getElementById('sectionHelp');
    const weekStart = document.getElementById('week_start_date');
    const weekDateHelp = document.getElementById('weekDateHelp');
    const form = document.getElementById('weeklyTimetableForm');
    const footerSaveBtn = document.getElementById('footerSaveBtn');

    const allClasses = @json($classes);
    const allSections = @json($sections);
    const oldSection = @json(old('section'));

    const weeklyFetchUrl = @json(route('admin.timetable.weekly.fetch'));
    const weeklyExcelUrl = @json(route('admin.timetable.weekly.excel'));
    const weeklyPdfUrl = @json(route('admin.timetable.weekly.pdf'));

    let activeSubjectSelect = null;

    function getFilters() {
        return {
            academic_year: document.getElementById('academic_year').value.trim(),
            class: classSelect.value,
            section: sectionSelect.value,
            week_start_date: weekStart.value
        };
    }

    function validFilters(filters) {
        if (!filters.academic_year || !filters.class || !filters.section || !filters.week_start_date) {
            alert('Please select the academic year, class, section and week start date.');
            return false;
        }

        const date = new Date(filters.week_start_date + 'T12:00:00');
        if (date.getDay() !== 1) {
            alert('Please choose a Monday as the week start date.');
            weekStart.focus();
            return false;
        }

        return true;
    }

    function downloadFile(url) {
        const filters = getFilters();
        if (!validFilters(filters)) return;
        window.location.href = url + '?' + new URLSearchParams(filters).toString();
    }

    function loadSections(className, selectedSection = '') {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';

        if (!className) {
            sectionHelp.textContent = 'Select a class to load its sections.';
            return;
        }

        const selectedClass = allClasses.find(function (item) {
            return String(item.class_name ?? item.name ?? '').trim() === String(className).trim();
        });

        if (!selectedClass) {
            sectionHelp.textContent = 'Could not find the selected class record.';
            return;
        }

        const sections = allSections.filter(function (item) {
            return String(item.class_id) === String(selectedClass.id);
        });

        sections.forEach(function (item) {
            const option = document.createElement('option');
            option.value = item.section_name;
            option.textContent = item.section_name;
            option.selected = String(item.section_name) === String(selectedSection);
            sectionSelect.appendChild(option);
        });

        sectionHelp.textContent = sections.length
            ? 'Choose the section for this class.'
            : 'No sections are available for this class.';
    }

    function formatDate(date) {
        return new Intl.DateTimeFormat('en-IN', {
            day: '2-digit', month: 'short', year: 'numeric'
        }).format(date);
    }

    function updateWeekDates() {
        if (!weekStart.value) return;

        const start = new Date(weekStart.value + 'T12:00:00');
        const monday = start.getDay() === 1;

        weekDateHelp.textContent = monday
            ? 'Monday to Saturday: ' + formatDate(start) + ' – ' +
              formatDate(new Date(start.getTime() + 5 * 86400000))
            : 'Please choose a Monday as the week start date.';
        weekDateHelp.style.color = monday ? '#6b7c75' : '#dc2626';

        document.querySelectorAll('.day-date').forEach(function (element) {
            const offset = Number(element.dataset.dayIndex);
            element.textContent = formatDate(
                new Date(start.getTime() + offset * 86400000)
            );
        });
    }

    function subjectLabel(select) {
        const option = select.options[select.selectedIndex];
        return option ? (option.dataset.subjectName || option.textContent.trim()) : '';
    }

    function filterTeachers(cell) {
        const subjectSelect = cell.querySelector('.period-subject');
        const teacherSelect = cell.querySelector('.period-teacher');
        const selectedSubject = subjectLabel(subjectSelect).trim().toLowerCase();
        const currentTeacher = teacherSelect.value;

        Array.from(teacherSelect.options).forEach(function (option, index) {
            if (index === 0) return;

            const mappings = (option.dataset.subjects || '')
                .split('|').map(value => value.trim().toLowerCase()).filter(Boolean);

            const shouldFilter = selectedSubject && mappings.length > 0;
            option.hidden = Boolean(shouldFilter && !mappings.includes(selectedSubject));
            option.disabled = option.hidden;
        });

        if (currentTeacher) {
            const selected = teacherSelect.options[teacherSelect.selectedIndex];
            if (selected && selected.disabled) teacherSelect.value = '';
        }
    }

    function calculateEndTime(cell) {
        const type = cell.querySelector('.period-type').value;
        const start = cell.querySelector('.period-start');
        const end = cell.querySelector('.period-end');

        if (!start.value) return;

        const duration = type === 'Break' ? 15 : type === 'Lunch' ? 60 : 45;
        const parts = start.value.split(':').map(Number);
        const total = parts[0] * 60 + parts[1] + duration;

        if (total >= 1440) {
            end.value = '';
            return;
        }

        end.value = String(Math.floor(total / 60)).padStart(2, '0') + ':' +
            String(total % 60).padStart(2, '0');
    }

    function configureCell(cell) {
        const type = cell.querySelector('.period-type');
        const subject = cell.querySelector('.period-subject');
        const teacher = cell.querySelector('.period-teacher');
        const subjectType = cell.querySelector('.period-subject-type');
        const lectureType = cell.querySelector('.period-lecture-type');
        const room = cell.querySelector('.period-room');
        const special = ['Break', 'Lunch'].includes(type.value);
        const activity = type.value === 'Activity';

        cell.classList.toggle('tt-special', special);
        subject.required = !special;
        teacher.required = !special;

        [subject, teacher, room].forEach(function (field) {
            field.disabled = special;
        });

        if (special) {
            subject.value = '';
            teacher.value = '';
            room.value = '';
            subjectType.value = 'Activity';
            lectureType.value = type.value.toLowerCase();
        } else {
            subjectType.value = activity ? 'Activity' : 'Theory';
            lectureType.value = activity ? 'activity' : 'regular';
            filterTeachers(cell);
        }

        if (cell.querySelector('.period-start').value &&
            !cell.querySelector('.period-end').value) {
            calculateEndTime(cell);
        }
    }

    document.querySelectorAll('.tt-cell').forEach(function (cell) {
        const type = cell.querySelector('.period-type');
        const subject = cell.querySelector('.period-subject');
        const start = cell.querySelector('.period-start');
        const end = cell.querySelector('.period-end');

        type.addEventListener('change', function () {
            configureCell(cell);
            if (start.value) calculateEndTime(cell);
        });

        subject.addEventListener('change', function () {
            if (subject.value === '__add_subject__') {
                subject.value = '';
                openSubjectModal(subject);
                return;
            }
            filterTeachers(cell);
        });

        cell.querySelector('.tt-add-subject').addEventListener('click', function () {
            openSubjectModal(subject);
        });

        start.addEventListener('change', function () {
            calculateEndTime(cell);
        });

        end.addEventListener('change', function () {
            if (start.value && end.value && end.value <= start.value) {
                end.setCustomValidity('End time must be after start time.');
            } else {
                end.setCustomValidity('');
            }
        });

        configureCell(cell);
    });

    const modal = document.getElementById('addSubjectModal');
    const newSubjectName = document.getElementById('newSubjectName');

    function openSubjectModal(select) {
        activeSubjectSelect = select;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        newSubjectName.value = '';
        newSubjectName.focus();
    }

    function closeSubjectModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        activeSubjectSelect = null;
    }

    document.getElementById('cancelAddSubject').addEventListener('click', closeSubjectModal);
    modal.addEventListener('click', function (event) {
        if (event.target === modal) closeSubjectModal();
    });

    document.getElementById('confirmAddSubject').addEventListener('click', function () {
        const name = newSubjectName.value.trim();

        if (!name || !activeSubjectSelect) {
            alert('Please enter a subject name.');
            newSubjectName.focus();
            return;
        }

        const normalized = name.toLowerCase();

        document.querySelectorAll('.period-subject').forEach(function (select) {
            let existing = Array.from(select.options).find(function (option) {
                return option.value !== '__add_subject__' &&
                    (option.dataset.subjectName || option.textContent).trim().toLowerCase() === normalized;
            });

            if (!existing) {
                existing = new Option(name, name);
                existing.dataset.subjectName = name;
                const addOption = Array.from(select.options).find(option => option.value === '__add_subject__');
                select.add(existing, addOption || null);
            }
        });

        const chosen = Array.from(activeSubjectSelect.options).find(function (option) {
            return option.value !== '__add_subject__' &&
                (option.dataset.subjectName || option.textContent).trim().toLowerCase() === normalized;
        });

        if (chosen) activeSubjectSelect.value = chosen.value;
        const cell = activeSubjectSelect.closest('.tt-cell');
        if (cell) filterTeachers(cell);
        closeSubjectModal();
    });

    newSubjectName.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('confirmAddSubject').click();
        } else if (event.key === 'Escape') {
            closeSubjectModal();
        }
    });

    async function fetchWeeklyRecords() {
        const filters = getFilters();
        if (!validFilters(filters)) return;

        const button = document.getElementById('fetchWeeklyBtn');
        const oldText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i> Fetching...';

        try {
            const response = await fetch(weeklyFetchUrl + '?' + new URLSearchParams(filters), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.message || 'Unable to fetch saved timetable records.');
            }

            const records = result.records || [];
            if (!records.length) {
                alert('No saved records were found for this class, section and week.');
                return;
            }

            records.forEach(function (record) {
                const cell = Array.from(document.querySelectorAll('.tt-cell')).find(function (candidate) {
                    const dayField = candidate.querySelector('input[name$="[day]"]');
                    const periodField = candidate.querySelector('input[name$="[period_number]"]');
                    return dayField && periodField &&
                        dayField.value === record.day &&
                        Number(periodField.value) === Number(record.period_number);
                });

                if (!cell) return;

                const type = cell.querySelector('.period-type');
                const subject = cell.querySelector('.period-subject');
                const teacher = cell.querySelector('.period-teacher');
                const subjectType = cell.querySelector('.period-subject-type');
                const lectureType = cell.querySelector('.period-lecture-type');
                const start = cell.querySelector('.period-start');
                const end = cell.querySelector('.period-end');
                const room = cell.querySelector('.period-room');

                type.value = record.period_type || 'Regular';
                configureCell(cell);

                if (!['Break', 'Lunch'].includes(type.value)) {
                    const savedSubject = record.subject == null ? '' : String(record.subject);
                    if (savedSubject) {
                        let option = Array.from(subject.options).find(item => item.value === savedSubject);
                        if (!option) {
                            option = new Option(savedSubject, savedSubject);
                            option.dataset.subjectName = savedSubject;
                            const addOption = Array.from(subject.options).find(item => item.value === '__add_subject__');
                            subject.add(option, addOption || null);
                        }
                        subject.value = savedSubject;
                    } else {
                        subject.value = '';
                    }

                    teacher.value = record.teacher_id == null ? '' : String(record.teacher_id);
                    subjectType.value = record.subject_type || (type.value === 'Activity' ? 'Activity' : 'Theory');
                    lectureType.value = record.lecture_type || (type.value === 'Activity' ? 'activity' : 'regular');
                    filterTeachers(cell);
                }

                start.value = record.start_time || '';
                end.value = record.end_time || '';
                room.value = ['Break', 'Lunch'].includes(type.value) ? '' : (record.room || '');
                configureCell(cell);
            });

            alert('Saved timetable records loaded. Review the grid and click Save Weekly Timetable to submit changes.');
        } catch (error) {
            alert(error.message || 'An error occurred while fetching timetable records.');
        } finally {
            button.disabled = false;
            button.innerHTML = oldText;
        }
    }

    document.getElementById('fetchWeeklyBtn').addEventListener('click', fetchWeeklyRecords);
    document.getElementById('weeklyExcelBtn').addEventListener('click', function () {
        downloadFile(weeklyExcelUrl);
    });
    document.getElementById('weeklyPdfBtn').addEventListener('click', function () {
        downloadFile(weeklyPdfUrl);
    });

    // ── Import: button opens file picker → auto submit ─
    const visibleFile = document.getElementById('weeklyExcelFile');
    const hiddenFile  = document.getElementById('import_file_hidden');
    const importForm  = document.getElementById('weeklyImportForm');
    const importBtn   = document.getElementById('importSubmitBtn');

    if (importBtn && visibleFile && importForm && hiddenFile) {
        importBtn.addEventListener('click', function () {
            const filters = getFilters();
            if (!validFilters(filters)) return;
            visibleFile.click();
        });

        visibleFile.addEventListener('change', function () {
            if (!this.files || !this.files.length) return;

            if (!confirm('Importing will replace records for this selected class, section and week. Continue?')) {
                this.value = '';
                return;
            }

            const filters = getFilters();
            document.getElementById('import_academic_year').value = filters.academic_year;
            document.getElementById('import_class').value = filters.class;
            document.getElementById('import_section').value = filters.section;
            document.getElementById('import_week_start_date').value = filters.week_start_date;

            const dt = new DataTransfer();
            dt.items.add(this.files[0]);
            hiddenFile.files = dt.files;

            importBtn.disabled = true;
            importBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Importing...';
            importForm.submit();
        });
    }

    classSelect.addEventListener('change', function () {
        loadSections(classSelect.value);
    });
    weekStart.addEventListener('change', updateWeekDates);

    function handleFormSubmit(event) {
        const filters = getFilters();

        if (!filters.week_start_date ||
            new Date(filters.week_start_date + 'T12:00:00').getDay() !== 1) {
            event.preventDefault();
            alert('The week start date must be a Monday.');
            weekStart.focus();
            return;
        }

        let invalidField = null;

        document.querySelectorAll('.tt-cell').forEach(function (cell) {
            if (invalidField) return;

            const type = cell.querySelector('.period-type').value;
            const subject = cell.querySelector('.period-subject');
            const teacher = cell.querySelector('.period-teacher');
            const start = cell.querySelector('.period-start');
            const end = cell.querySelector('.period-end');

            if (!start.value) invalidField = start;
            else if (!end.value || end.value <= start.value) invalidField = end;
            else if (!['Break', 'Lunch'].includes(type) && !subject.value.trim()) invalidField = subject;
            else if (!['Break', 'Lunch'].includes(type) && !teacher.value) invalidField = teacher;
        });

        if (invalidField) {
            event.preventDefault();
            alert('Complete every period: subject, teacher, start time and end time. End time must be after start time.');
            invalidField.focus();
            return;
        }

        if (footerSaveBtn) {
            footerSaveBtn.disabled = true;
            footerSaveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving Weekly Timetable...';
        }
    }

    form.addEventListener('submit', handleFormSubmit);

    if (classSelect.value) loadSections(classSelect.value, oldSection);
    updateWeekDates();
});
</script>
@endsection