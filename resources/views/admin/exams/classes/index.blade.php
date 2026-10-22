
@extends('layouts.app')

@section('content')

<div class="exam-classes-page">

    {{-- PAGE HEADER --}}
    <div class="exam-page-header">
        <div class="exam-heading">
            <div class="exam-page-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div class="exam-heading-content">
                <div class="exam-title-line">
                    <h1>Exam Classes</h1>

                    <span class="exam-count-badge">
                        {{ $classes->count() }} Classes
                    </span>
                </div>

                <p>
                    Configure which classes will participate in this examination.
                </p>
            </div>
        </div>

        <a href="{{ route('admin.exams.show', $exam) }}"
           class="exam-back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Exam</span>
        </a>
    </div>

    {{-- EXAM INFORMATION --}}
    <div class="exam-info-grid">

        <div class="exam-info-card">
            <div class="exam-info-icon forest">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="exam-info-content">
                <span class="exam-info-label">Examination</span>
                <strong>{{ $exam->exam_name }}</strong>
            </div>
        </div>

        <div class="exam-info-card">
            <div class="exam-info-icon blue">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="exam-info-content">
                <span class="exam-info-label">Academic Year</span>
                <strong>{{ $exam->academic_year }}</strong>
            </div>
        </div>

        <div class="exam-info-card">
            <div class="exam-info-icon amber">
                <i class="bi bi-check2-square"></i>
            </div>

            <div class="exam-info-content">
                <span class="exam-info-label">Selected Classes</span>
                <strong>
                    <span id="selectedCount">
                        {{ count($selectedClassIds) }}
                    </span>
                    <span class="exam-total-count">
                        of {{ $classes->count() }}
                    </span>
                </strong>
            </div>
        </div>

    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="exam-alert exam-alert-success" role="alert">
            <div class="exam-alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>
                <strong>Successfully saved</strong>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="exam-alert exam-alert-danger" role="alert">
            <div class="exam-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>
                <strong>Please fix the following errors</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- MAIN CARD --}}
    <section class="exam-panel">

        <div class="exam-panel-header">
            <div class="exam-panel-heading">
                <div class="exam-section-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>

                <div>
                    <h2>Available Classes</h2>
                    <p>
                        Select the classes that should be included in this examination.
                    </p>
                </div>
            </div>

            @if($classes->count())
                <div class="exam-selection-summary">
                    <span>Selected</span>
                    <span class="exam-selection-number" id="selectedBadge">
                        {{ count($selectedClassIds) }}
                    </span>
                </div>
            @endif
        </div>

        <form method="POST"
              action="{{ route('admin.exam-classes.store', $exam) }}">
            @csrf

            @if($classes->count())

                {{-- SELECTION TOOLBAR --}}
                <div class="exam-selection-toolbar">

                    <label class="exam-select-all">
                        <input type="checkbox" id="selectAll">
                        <span class="exam-custom-checkbox"></span>

                        <span>Select All Classes</span>
                    </label>

                    <div class="exam-toolbar-hint">
                        <i class="bi bi-info-circle"></i>
                        Select one or more classes to continue.
                    </div>

                </div>

                {{-- CLASSES TABLE --}}
                <div class="exam-table-wrap">
                    <table class="exam-table">
                        <thead>
                            <tr>
                                <th class="exam-select-column">
                                    <span class="visually-hidden">Select</span>
                                </th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($classes as $class)
                                <tr class="class-row">

                                    {{-- CHECKBOX --}}
                                    <td>
                                        <label class="exam-row-checkbox">
                                            <input
                                                type="checkbox"
                                                name="class_ids[]"
                                                value="{{ $class->id }}"
                                                class="class-checkbox"
                                                {{ in_array($class->id, $selectedClassIds) ? 'checked' : '' }}
                                                aria-label="Select {{ $class->class_name }}"
                                            >
                                            <span class="exam-custom-checkbox"></span>
                                        </label>
                                    </td>

                                    {{-- CLASS NAME --}}
                                    <td>
                                        <div class="exam-class-cell">
                                            <div class="exam-class-avatar">
                                                <i class="bi bi-mortarboard"></i>
                                            </div>

                                            <div>
                                                <strong>{{ $class->class_name }}</strong>
                                                <span>Class ID: {{ $class->id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- SECTION --}}
                                    <td>
                                        <span class="exam-section-badge">
                                            <i class="bi bi-people"></i>
                                            {{ $class->section }}
                                        </span>
                                    </td>

                                    {{-- ACADEMIC YEAR --}}
                                    <td>
                                        <span class="exam-academic-year">
                                            <i class="bi bi-calendar3"></i>
                                            {{ $class->academic_year }}
                                        </span>
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($class->status)
                                            <span class="exam-status active">
                                                <span class="exam-status-dot"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="exam-status inactive">
                                                <span class="exam-status-dot"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- FORM FOOTER --}}
                <div class="exam-panel-footer">
                    <div class="exam-footer-note">
                        <i class="bi bi-shield-check"></i>
                        <span>
                            Your class selection will be saved for this examination.
                        </span>
                    </div>

                    <button type="submit"
                            class="exam-save-btn"
                            id="saveButton">
                        <i class="bi bi-check2-circle"></i>
                        <span>Save &amp; Continue</span>
                    </button>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="exam-empty-state">
                    <div class="exam-empty-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <h3>No Classes Available</h3>

                    <p>
                        No active classes were found for
                        <strong>{{ $exam->academic_year }}</strong>.
                    </p>

                    <a href="{{ route('admin.exams.show', $exam) }}"
                       class="exam-back-btn">
                        <i class="bi bi-arrow-left"></i>
                        Back to Exam
                    </a>
                </div>

            @endif

        </form>
    </section>

    {{-- PAGE FOOTNOTE --}}
    <div class="exam-page-footnote">
        <i class="bi bi-journal-check"></i>
        <span>School Diary <span class="exam-footnote-dot">·</span> Examination Management</span>
    </div>

</div>

<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM
   Exam Classes
========================================================= */

.exam-classes-page {
    --exam-page: #f7f6f0;
    --exam-paper: #fffefa;
    --exam-chalk: #f5f3e9;
    --exam-ink: #263b35;
    --exam-forest: #344f43;
    --exam-forest-dark: #20352c;
    --exam-text: #39483e;
    --exam-muted: #7b857b;
    --exam-line: #e5e1d5;
    --exam-sage: #527f70;
    --exam-blue: #426b8a;
    --exam-amber: #a87543;
    --exam-green: #43835d;
    --exam-red: #b95b50;

    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--exam-text);
    font-family: "Public Sans", Arial, sans-serif;
}

.exam-classes-page *,
.exam-classes-page *::before,
.exam-classes-page *::after {
    box-sizing: border-box;
}

.exam-classes-page h1,
.exam-classes-page h2,
.exam-classes-page h3,
.exam-classes-page p {
    margin-top: 0;
}

.exam-classes-page a {
    text-decoration: none;
}

.exam-classes-page button,
.exam-classes-page input {
    font: inherit;
}

.exam-classes-page button:focus-visible,
.exam-classes-page a:focus-visible,
.exam-classes-page input:focus-visible {
    outline: 3px solid rgba(82, 127, 112, 0.35);
    outline-offset: 3px;
}

/* PAGE HEADER */

.exam-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.exam-heading {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.exam-page-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #d8e1d7;
    border-radius: 16px;
    background: #e9eee5;
    color: var(--exam-forest);
    font-size: 24px;
}

.exam-page-icon::after {
    content: "";
    position: absolute;
    width: 8px;
    height: 8px;
    top: 7px;
    right: 7px;
    border-radius: 50%;
    background: #b4c8a9;
}

.exam-heading-content {
    min-width: 0;
}

.exam-title-line {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 11px;
    margin-bottom: 5px;
}

.exam-title-line h1 {
    margin: 0;
    color: var(--exam-ink);
    font-family: Georgia, serif;
    font-size: clamp(25px, 3vw, 32px);
    font-weight: 700;
    letter-spacing: -0.7px;
}

.exam-heading-content > p {
    margin: 0;
    color: var(--exam-muted);
    font-size: 13px;
    line-height: 1.7;
}

.exam-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 27px;
    padding: 5px 11px;
    border: 1px solid #d9e3d7;
    border-radius: 30px;
    background: #edf2e9;
    color: var(--exam-forest);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* BUTTONS */

.exam-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 10px 16px;
    border: 1px solid #d8d8ca;
    border-radius: 10px;
    background: var(--exam-paper);
    color: var(--exam-ink);
    font-size: 12px;
    font-weight: 700;
    transition: background 0.2s ease, border-color 0.2s ease,
                transform 0.2s ease;
}

.exam-back-btn:hover {
    border-color: #b6c5b6;
    background: #f0f1e8;
    color: var(--exam-forest-dark);
    transform: translateY(-1px);
}

/* INFORMATION CARDS */

.exam-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.exam-info-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
    min-height: 104px;
    padding: 21px;
    overflow: hidden;
    border: 1px solid var(--exam-line);
    border-radius: 15px;
    background: var(--exam-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, 0.025);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.exam-info-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 20px;
    width: 43px;
    height: 3px;
    border-radius: 0 0 5px 5px;
    background: var(--exam-sage);
}

.exam-info-card:nth-child(2)::before {
    background: var(--exam-blue);
}

.exam-info-card:nth-child(3)::before {
    background: var(--exam-amber);
}

.exam-info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(38, 59, 53, 0.07);
}

.exam-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 46px;
    width: 46px;
    height: 46px;
    border-radius: 13px;
    font-size: 20px;
}

.exam-info-icon.forest {
    background: #e9eee5;
    color: var(--exam-forest);
}

.exam-info-icon.blue {
    background: #e9eff3;
    color: var(--exam-blue);
}

.exam-info-icon.amber {
    background: #f5ede0;
    color: var(--exam-amber);
}

.exam-info-content {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
}

.exam-info-label {
    color: var(--exam-muted);
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.2px;
}

.exam-info-content strong {
    overflow-wrap: anywhere;
    color: var(--exam-ink);
    font-size: 15px;
    font-weight: 700;
    line-height: 1.45;
}

.exam-total-count {
    color: var(--exam-muted);
    font-size: 12px;
    font-weight: 500;
}

/* ALERTS */

.exam-alert {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    margin-bottom: 20px;
    padding: 17px 19px;
    border: 1px solid;
    border-radius: 13px;
}

.exam-alert-success {
    border-color: #d4e5d4;
    background: #f0f6ed;
    color: var(--exam-forest);
}

.exam-alert-danger {
    border-color: #eed7d0;
    background: #fbefeb;
    color: #93473e;
}

.exam-alert-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 35px;
    width: 35px;
    height: 35px;
    border-radius: 10px;
    background: rgba(82, 127, 112, 0.1);
    font-size: 17px;
}

.exam-alert-danger .exam-alert-icon {
    background: rgba(185, 91, 80, 0.1);
}

.exam-alert strong {
    display: block;
    margin-bottom: 4px;
    font-size: 13px;
}

.exam-alert p {
    margin: 0;
    font-size: 12px;
    line-height: 1.7;
}

.exam-alert ul {
    margin: 7px 0 0;
    padding-left: 19px;
    font-size: 12px;
    line-height: 1.8;
}

/* MAIN PANEL */

.exam-panel {
    overflow: hidden;
    border: 1px solid var(--exam-line);
    border-radius: 16px;
    background: var(--exam-paper);
    box-shadow: 0 5px 22px rgba(38, 59, 53, 0.035);
}

.exam-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 22px 25px;
    border-bottom: 1px solid var(--exam-line);
    background: var(--exam-paper);
}

.exam-panel-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.exam-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border: 1px solid #dce5d8;
    border-radius: 12px;
    background: #edf1e8;
    color: var(--exam-forest);
    font-size: 18px;
}

.exam-panel-heading h2 {
    margin: 0 0 5px;
    color: var(--exam-ink);
    font-family: Georgia, serif;
    font-size: 19px;
    font-weight: 700;
}

.exam-panel-heading p {
    margin: 0;
    color: var(--exam-muted);
    font-size: 12px;
    line-height: 1.6;
}

.exam-selection-summary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 7px 10px 7px 13px;
    border: 1px solid #e3e4d8;
    border-radius: 30px;
    background: #f8f7f0;
}

.exam-selection-summary > span:first-child {
    color: var(--exam-muted);
    font-size: 11px;
    font-weight: 600;
}

.exam-selection-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 27px;
    padding: 0 8px;
    border-radius: 20px;
    background: var(--exam-forest);
    color: #fffefa;
    font-size: 12px;
    font-weight: 700;
}

/* SELECTION TOOLBAR */

.exam-selection-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 16px 25px;
    border-bottom: 1px solid var(--exam-line);
    background: #f8f7f1;
}

.exam-select-all,
.exam-row-checkbox {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    cursor: pointer;
}

.exam-select-all {
    color: var(--exam-ink);
    font-size: 12px;
    font-weight: 700;
}

.exam-toolbar-hint {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--exam-muted);
    font-size: 11px;
}

.exam-toolbar-hint i {
    color: var(--exam-sage);
    font-size: 14px;
}

/* CUSTOM CHECKBOX */

.exam-classes-page .exam-select-all input,
.exam-classes-page .exam-row-checkbox input {
    position: absolute;
    width: 1px;
    height: 1px;
    opacity: 0;
    pointer-events: none;
}

.exam-custom-checkbox {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 18px;
    width: 18px;
    height: 18px;
    border: 1.5px solid #c7cbbf;
    border-radius: 5px;
    background: var(--exam-paper);
    transition: background 0.15s ease, border-color 0.15s ease;
}

.exam-select-all input:checked + .exam-custom-checkbox,
.exam-row-checkbox input:checked + .exam-custom-checkbox {
    border-color: var(--exam-forest);
    background: var(--exam-forest);
}

.exam-select-all input:checked + .exam-custom-checkbox::after,
.exam-row-checkbox input:checked + .exam-custom-checkbox::after {
    content: "";
    width: 8px;
    height: 5px;
    margin-top: -2px;
    border-bottom: 2px solid #fffefa;
    border-left: 2px solid #fffefa;
    transform: rotate(-45deg);
}

.exam-select-all input:indeterminate + .exam-custom-checkbox {
    border-color: var(--exam-forest);
    background: var(--exam-forest);
}

.exam-select-all input:indeterminate + .exam-custom-checkbox::after {
    content: "";
    width: 8px;
    height: 2px;
    background: #fffefa;
}

.exam-select-all input:focus-visible + .exam-custom-checkbox,
.exam-row-checkbox input:focus-visible + .exam-custom-checkbox {
    outline: 3px solid rgba(82, 127, 112, 0.3);
    outline-offset: 3px;
}

/* TABLE */

.exam-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.exam-table {
    width: 100%;
    min-width: 740px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
    color: var(--exam-text);
}

.exam-table thead th {
    padding: 15px 19px;
    border-bottom: 1px solid var(--exam-line);
    background: #f4f3eb;
    color: #727e72;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.exam-table .exam-select-column {
    width: 62px;
    padding-left: 25px;
}

.exam-table tbody td {
    padding: 16px 19px;
    border-bottom: 1px solid #eeece3;
    background: var(--exam-paper);
    font-size: 12px;
    vertical-align: middle;
    transition: background 0.15s ease;
}

.exam-table tbody tr:last-child td {
    border-bottom: 0;
}

.exam-table tbody tr:hover td {
    background: #f8f8f1;
}

.exam-table tbody tr.selected td {
    background: #edf2e9;
}

.exam-table tbody tr.selected:hover td {
    background: #e7eee2;
}

.exam-table tbody td:first-child {
    padding-left: 25px;
}

.exam-row-checkbox {
    min-height: 25px;
}

/* CLASS CELL */

.exam-class-cell {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 160px;
}

.exam-class-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 41px;
    width: 41px;
    height: 41px;
    border: 1px solid #dce5d8;
    border-radius: 12px;
    background: #edf1e8;
    color: var(--exam-forest);
    font-size: 18px;
}

.exam-class-cell > div:last-child {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.exam-class-cell strong {
    color: var(--exam-ink);
    font-size: 12px;
    font-weight: 700;
}

.exam-class-cell span {
    color: var(--exam-muted);
    font-size: 10px;
}

/* SECTION BADGE */

.exam-section-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid #e4e2d6;
    border-radius: 8px;
    background: #f7f6ef;
    color: var(--exam-ink);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.exam-section-badge i {
    color: var(--exam-sage);
    font-size: 13px;
}

/* ACADEMIC YEAR */

.exam-academic-year {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #657266;
    font-size: 11px;
    white-space: nowrap;
}

.exam-academic-year i {
    color: var(--exam-blue);
    font-size: 14px;
}

/* STATUS BADGES */

.exam-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.exam-status.active {
    border-color: #d5e6d4;
    background: #edf5e9;
    color: #43835d;
}

.exam-status.inactive {
    border-color: #e4e1d9;
    background: #f3f1eb;
    color: #7b8178;
}

.exam-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

/* FOOTER */

.exam-panel-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding: 19px 25px;
    border-top: 1px solid var(--exam-line);
    background: #fcfbf6;
}

.exam-footer-note {
    display: flex;
    align-items: center;
    gap: 9px;
    color: var(--exam-muted);
    font-size: 11px;
    line-height: 1.7;
}

.exam-footer-note i {
    color: var(--exam-green);
    font-size: 16px;
}

/* FOREST-GREEN ACTION BUTTON */

.exam-save-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 44px;
    padding: 12px 20px;
    border: 1px solid var(--exam-forest-dark);
    border-radius: 10px;
    background: linear-gradient(135deg, #344f43 0%, #20352c 100%);
    color: #fffefa;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(32, 53, 44, 0.12);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.exam-save-btn i {
    font-size: 16px;
}

.exam-save-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 16px rgba(32, 53, 44, 0.2);
    color: #fffefa;
}

.exam-save-btn:active {
    transform: translateY(0);
}

/* EMPTY STATE */

.exam-empty-state {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    min-height: 325px;
    padding: 42px 24px;
    text-align: center;
}

.exam-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 78px;
    height: 78px;
    margin-bottom: 20px;
    border: 1px solid #e1e4d8;
    border-radius: 23px;
    background: #f1f1e8;
    color: #81927e;
    font-size: 31px;
}

.exam-empty-state h3 {
    margin-bottom: 9px;
    color: var(--exam-ink);
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 700;
}

.exam-empty-state p {
    margin-bottom: 20px;
    color: var(--exam-muted);
    font-size: 12px;
    line-height: 1.8;
}

.exam-empty-state p strong {
    color: var(--exam-ink);
}

/* PAGE FOOTNOTE */

.exam-page-footnote {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 20px 10px 4px;
    color: #93988c;
    font-size: 10px;
    letter-spacing: 0.2px;
}

.exam-page-footnote > i {
    color: var(--exam-sage);
    font-size: 13px;
}

.exam-footnote-dot {
    padding: 0 3px;
    color: #b2b5a8;
}

/* RESPONSIVE */

@media (max-width: 991.98px) {
    .exam-classes-page {
        padding: 20px;
    }

    .exam-info-grid {
        gap: 12px;
    }

    .exam-info-card {
        padding: 17px;
        gap: 11px;
    }

    .exam-info-icon {
        flex-basis: 40px;
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 767.98px) {
    .exam-classes-page {
        padding: 15px;
    }

    .exam-page-header {
        align-items: flex-start;
        gap: 16px;
    }

    .exam-heading {
        align-items: flex-start;
        gap: 12px;
    }

    .exam-page-icon {
        flex-basis: 44px;
        width: 44px;
        height: 44px;
        border-radius: 13px;
        font-size: 20px;
    }

    .exam-title-line {
        gap: 8px;
    }

    .exam-title-line h1 {
        font-size: 25px;
    }

    .exam-heading-content > p {
        font-size: 12px;
    }

    .exam-page-header > .exam-back-btn {
        margin-left: 56px;
    }

    .exam-info-grid {
        grid-template-columns: 1fr;
        gap: 11px;
        margin-bottom: 19px;
    }

    .exam-info-card {
        min-height: 82px;
        padding: 15px 17px;
    }

    .exam-info-content strong {
        font-size: 14px;
    }

    .exam-panel-header {
        align-items: flex-start;
        padding: 19px 17px;
    }

    .exam-panel-heading {
        align-items: flex-start;
        gap: 11px;
    }

    .exam-section-icon {
        flex-basis: 37px;
        width: 37px;
        height: 37px;
        font-size: 16px;
    }

    .exam-panel-heading h2 {
        font-size: 18px;
    }

    .exam-panel-heading p {
        font-size: 11px;
    }

    .exam-selection-summary {
        margin-left: 48px;
    }

    .exam-selection-toolbar {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px 18px;
    }

    .exam-table {
        min-width: 740px;
    }

    .exam-table thead th {
        padding: 14px;
    }

    .exam-table tbody td {
        padding: 14px;
    }

    .exam-table .exam-select-column,
    .exam-table tbody td:first-child {
        padding-left: 18px;
    }

    .exam-panel-footer {
        align-items: stretch;
        flex-direction: column;
        padding: 18px;
    }

    .exam-footer-note {
        align-items: flex-start;
    }

    .exam-save-btn {
        width: 100%;
    }

    .exam-alert {
        padding: 14px;
    }
}

@media (max-width: 420px) {
    .exam-classes-page {
        padding: 12px;
    }

    .exam-page-icon {
        flex-basis: 40px;
        width: 40px;
        height: 40px;
    }

    .exam-title-line h1 {
        font-size: 22px;
    }

    .exam-count-badge {
        min-height: 24px;
        padding: 4px 8px;
        font-size: 10px;
    }

    .exam-page-header > .exam-back-btn {
        margin-left: 0;
    }

    .exam-panel-header {
        padding: 16px 13px;
    }

    .exam-panel-heading h2 {
        font-size: 16px;
    }

    .exam-selection-summary {
        margin-left: 0;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .exam-classes-page *,
    .exam-classes-page *::before,
    .exam-classes-page *::after {
        scroll-behavior: auto !important;
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.exam-classes-page');

    if (!page) {
        return;
    }

    const selectAll = page.querySelector('#selectAll');
    const checkboxes = Array.from(
        page.querySelectorAll('.class-checkbox')
    );
    const selectedCount = page.querySelector('#selectedCount');
    const selectedBadge = page.querySelector('#selectedBadge');
    const rows = Array.from(page.querySelectorAll('.class-row'));

    if (!selectAll || !checkboxes.length) {
        return;
    }

    function updateSelection() {
        const checkedCount = checkboxes.filter(
            checkbox => checkbox.checked
        ).length;

        if (selectedCount) {
            selectedCount.textContent = checkedCount;
        }

        if (selectedBadge) {
            selectedBadge.textContent = checkedCount;
        }

        selectAll.checked = checkedCount === checkboxes.length;
        selectAll.indeterminate =
            checkedCount > 0 && checkedCount < checkboxes.length;

        rows.forEach(function (row, index) {
            if (checkboxes[index]) {
                row.classList.toggle(
                    'selected',
                    checkboxes[index].checked
                );
            }
        });
    }

    selectAll.addEventListener('change', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = selectAll.checked;
        });

        updateSelection();
    });

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateSelection);
    });

    rows.forEach(function (row, index) {
        row.addEventListener('click', function (event) {
            if (
                event.target.closest('input') ||
                event.target.closest('button') ||
                event.target.closest('a') ||
                event.target.closest('label')
            ) {
                return;
            }

            const checkbox = checkboxes[index];

            if (checkbox) {
                checkbox.checked = !checkbox.checked;
                updateSelection();
            }
        });
    });

    updateSelection();
});
</script>

@endsection
