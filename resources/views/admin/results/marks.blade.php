
@extends('layouts.app')

@section('title', 'Enter Examination Marks')

@section('content')

@php
    $selectionMode = !(
        isset($selectedExam, $selectedClass, $selectedSection, $examSubject)
        && $selectedExam
        && $selectedClass
        && $selectedSection !== null
        && $examSubject
    );

    $internalMaximum = (float) ($examSubject->internal_max_marks ?? 0);
    $theoryMaximum = (float) ($examSubject->theory_max_marks ?? 0);
    $totalMaximum = (float) ($examSubject->maximum_marks ?? 0);
    $passingMarks = (float) ($examSubject->passing_marks ?? 0);

    $distributionValid = $totalMaximum > 0
        && abs(($internalMaximum + $theoryMaximum) - $totalMaximum) < 0.001
        && $passingMarks <= $totalMaximum;

    $formatMarks = function ($value) {
        return rtrim(
            rtrim(number_format((float) $value, 2, '.', ''), '0'),
            '.'
        ) ?: '0';
    };
@endphp

<div class="marks-page">

<style>
    .marks-page {
        --marks-gold: #c49a45;
        --marks-dark: #171717;
        --marks-muted: #727783;
        --marks-border: #e8eaf0;
        color: #242631;
        padding: 8px 0 30px;
    }

    .marks-page * {
        box-sizing: border-box;
    }

    .marks-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 22px;
    }

    .marks-title {
        margin: 0;
        font-size: clamp(23px, 3vw, 30px);
        font-weight: 800;
        color: var(--marks-dark);
        letter-spacing: -.5px;
    }

    .marks-subtitle {
        color: var(--marks-muted);
        margin: 6px 0 0;
        font-size: 13px;
    }

    .marks-panel {
        background: #fff;
        border: 1px solid var(--marks-border);
        border-radius: 16px;
        box-shadow: 0 5px 22px rgba(24,31,45,.045);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .marks-panel-heading {
        padding: 18px 21px;
        border-bottom: 1px solid var(--marks-border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .marks-heading-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        color: #8a641c;
        background: #fbf3e3;
        font-size: 19px;
    }

    .marks-panel-heading h2 {
        font-size: 16px;
        font-weight: 750;
        margin: 0;
        color: var(--marks-dark);
    }

    .marks-panel-heading p {
        margin: 4px 0 0;
        font-size: 12px;
        color: var(--marks-muted);
    }

    .marks-panel-body {
        padding: 21px;
    }

    .marks-filters {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .marks-field label {
        display: block;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 750;
        color: #444957;
    }

    .marks-field label .required {
        color: #d64545;
    }

    .marks-control {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #dfe2e9;
        border-radius: 10px;
        background: #fff;
        color: #242631;
        font-size: 13px;
        outline: none;
    }

    .marks-control:focus {
        border-color: var(--marks-gold);
        box-shadow: 0 0 0 3px rgba(196,154,69,.14);
    }

    .marks-control:disabled {
        background: #f5f6f8;
        color: #8b909a;
        cursor: not-allowed;
    }

    .marks-help {
        display: block;
        margin-top: 6px;
        color: #858a95;
        font-size: 11px;
    }

    .marks-button {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        border: 0;
        border-radius: 10px;
        padding: 11px 17px;
        min-height: 42px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 750;
        cursor: pointer;
        transition: .2s ease;
    }

    .marks-button-primary {
        background: var(--marks-dark);
        color: #fff;
    }

    .marks-button-primary:hover {
        background: #303030;
        color: #fff;
    }

    .marks-button-gold {
        background: var(--marks-gold);
        color: #171717;
    }

    .marks-button-gold:hover {
        background: #d4ad5f;
        color: #171717;
    }

    .marks-button:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    .marks-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .marks-distribution {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .marks-distribution-item {
        border: 1px solid var(--marks-border);
        border-radius: 12px;
        padding: 18px;
        background: #fff;
    }

    .marks-distribution-label {
        color: var(--marks-muted);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .marks-distribution-value {
        font-size: 26px;
        font-weight: 850;
        color: var(--marks-dark);
    }

    .marks-distribution-item small {
        display: block;
        margin-top: 6px;
        color: var(--marks-muted);
        font-size: 11px;
    }

    .marks-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .marks-stat {
        border: 1px solid var(--marks-border);
        background: #fff;
        padding: 15px 17px;
        border-radius: 12px;
    }

    .marks-stat-label {
        font-size: 11px;
        color: var(--marks-muted);
        margin-bottom: 6px;
    }

    .marks-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--marks-dark);
    }

    .marks-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 17px;
    }

    .marks-toolbar h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: var(--marks-dark);
    }

    .marks-toolbar p {
        margin: 5px 0 0;
        color: var(--marks-muted);
        font-size: 12px;
    }

    .marks-info-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 7px;
        padding: 5px 8px;
        background: #f3f4f6;
        color: #555b68;
        font-size: 11px;
        font-weight: 700;
        margin-right: 4px;
    }

    .marks-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--marks-border);
        border-radius: 12px;
    }

    .marks-table {
        width: 100%;
        min-width: 950px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .marks-table th {
        background: #f7f8fa;
        color: #555b68;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
        padding: 13px 12px;
        border-bottom: 1px solid var(--marks-border);
        text-align: left;
    }

    .marks-table td {
        padding: 11px 12px;
        border-bottom: 1px solid #eff0f4;
        vertical-align: middle;
        font-size: 12px;
    }

    .marks-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .marks-table tbody tr:hover {
        background: #fdfbf7;
    }

    .marks-student-name {
        font-size: 13px;
        font-weight: 750;
        color: #292d37;
    }

    .marks-student-id {
        font-size: 11px;
        color: var(--marks-muted);
        margin-top: 4px;
    }

    .marks-number {
        width: 90px;
        min-width: 75px;
        padding: 9px 8px;
        border: 1px solid #dfe2e9;
        border-radius: 8px;
        outline: none;
        font-size: 12px;
    }

    .marks-number:focus {
        border-color: var(--marks-gold);
        box-shadow: 0 0 0 2px rgba(196,154,69,.13);
    }

    .marks-number:disabled {
        background: #f2f3f5;
        cursor: not-allowed;
    }

    .marks-status {
        min-width: 110px;
        padding: 9px 7px;
        border: 1px solid #dfe2e9;
        border-radius: 8px;
        background: #fff;
        font-size: 12px;
    }

    .marks-total {
        font-weight: 800;
        color: #282c35;
        white-space: nowrap;
    }

    .marks-alert {
        padding: 13px 15px;
        border-radius: 10px;
        margin-bottom: 17px;
        font-size: 13px;
    }

    .marks-empty {
        padding: 42px 20px;
        text-align: center;
        color: var(--marks-muted);
    }

    .marks-empty-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        margin: 0 auto 13px;
        border-radius: 15px;
        background: #fbf3e3;
        color: #8a641c;
        font-size: 23px;
    }

    .marks-empty h3 {
        color: var(--marks-dark);
        font-size: 16px;
        font-weight: 800;
        margin: 0 0 7px;
    }

    .marks-empty p {
        margin: 0;
        font-size: 12px;
    }

    .marks-loading {
        display: none;
        font-size: 12px;
        color: #8a641c;
        margin-top: 8px;
    }

    .marks-loading.show {
        display: block;
    }

    .marks-not-applicable {
        color: #858a95;
        font-size: 11px;
        font-weight: 700;
    }

    .marks-total-warning {
        background: #fff0f0 !important;
    }

    @media(max-width:1000px) {
        .marks-filters,
        .marks-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .marks-distribution {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width:600px) {
        .marks-panel-heading,
        .marks-panel-body {
            padding: 15px;
        }

        .marks-filters,
        .marks-stats,
        .marks-distribution {
            grid-template-columns: 1fr;
        }

        .marks-header {
            align-items: flex-start;
        }

        .marks-footer .marks-button {
            width: 100%;
        }
    }

    /* EDITABLE MARKS DISTRIBUTION */

.marks-distribution-form {
    background: #fbfbfc;
    border: 1px solid var(--marks-border);
    border-radius: 13px;
    padding: 18px;
    margin-bottom: 18px;
}

.marks-distribution-fields {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
}

.marks-distribution-fields label {
    display: block;
    font-size: 12px;
    font-weight: 750;
    color: #444957;
    margin-bottom: 8px;
}

.marks-distribution-fields input {
    width: 100%;
    min-height: 44px;
    border: 1px solid #dfe2e9;
    border-radius: 9px;
    padding: 10px 12px;
    outline: none;
    font-size: 14px;
    background: #fff;
}

.marks-distribution-fields input:focus {
    border-color: var(--marks-gold);
    box-shadow: 0 0 0 3px rgba(196,154,69,.14);
}

.marks-distribution-fields input[readonly] {
    background: #f1f2f4;
    font-weight: 800;
}

.marks-distribution-total {
    color: #8a641c !important;
    font-weight: 850 !important;
}

@media(max-width:600px) {
    .marks-distribution-fields {
        grid-template-columns: 1fr;
    }
}

/* ==========================================
   COMPACT RESULT DASHBOARD HERO HEADER
========================================== */

.marks-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1fr) 34%;
    gap: 18px;
    align-items: stretch;
    min-height: 270px;
    padding: 22px 9px 22px 40px;
    margin-bottom: 22px;
    background: #fffefa;
    border: 1px solid #e8dfce;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(40, 55, 45, .045);
}

.marks-hero::before,
.marks-hero::after {
    content: "";
    position: absolute;
    left: 30px;
    width: 1px;
    height: 38px;
    background: #f0e5d2;
}

.marks-hero::before { top: 0; }
.marks-hero::after { bottom: 0; }

.marks-hero-left {
    position: relative;
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 18px 12px 18px 32px;
    border-left: 3px solid #e2b39b;
    min-width: 0;
}

.marks-hero-icon {
    flex: 0 0 76px;
    width: 76px;
    height: 76px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #e7ddca;
    border-radius: 19px;
    background: #f6f3e9;
    color: #234437;
    font-size: 30px;
    box-shadow: 0 5px 14px rgba(40, 55, 45, .04);
}

.marks-hero-content {
    min-width: 0;
}

.marks-hero-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
    color: #78827e;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.marks-hero-breadcrumb i {
    font-size: 11px;
    color: #a6a69c;
}

.marks-hero-title {
    margin: 0;
    color: #203c32;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(27px, 3vw, 39px);
    line-height: 1.15;
    font-weight: 800;
    letter-spacing: -.5px;
}

.marks-hero-description {
    max-width: 560px;
    margin: 13px 0 14px;
    color: #718078;
    font-size: 13px;
    line-height: 1.7;
}

.marks-hero-trust {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #286044;
    font-size: 11px;
    font-weight: 750;
}

.marks-hero-trust i {
    font-size: 16px;
    color: #438a73;
}

/* RIGHT GREEN PANEL */

.marks-hero-right {
    position: relative;
    isolation: isolate;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 225px;
    padding: 20px 16px;
    overflow: hidden;
    background: linear-gradient(145deg, #304f42, #193a30);
    color: #fff;
    text-align: center;
}

.marks-hero-right::before,
.marks-hero-right::after {
    content: "";
    position: absolute;
    z-index: -1;
    border: 1px solid rgba(255, 255, 255, .10);
    border-radius: 50%;
    pointer-events: none;
}

.marks-hero-right::before {
    width: 280px;
    height: 280px;
    top: -185px;
    right: -45px;
    box-shadow:
        0 0 0 20px rgba(255,255,255,.025),
        0 0 0 38px rgba(255,255,255,.025),
        0 0 0 55px rgba(255,255,255,.02);
}

.marks-hero-right::after {
    width: 230px;
    height: 230px;
    bottom: -200px;
    left: -80px;
}

.marks-hero-right-icon {
    width: 49px;
    height: 49px;
    display: grid;
    place-items: center;
    margin-bottom: 15px;
    border: 1px solid rgba(255,255,255,.23);
    border-radius: 15px;
    background: rgba(255,255,255,.08);
    color: #f3d18e;
    font-size: 21px;
}

.marks-hero-tagline {
    margin: 0 0 12px;
    color: #f6d39a;
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(17px, 1.7vw, 22px);
    font-style: italic;
    font-weight: 800;
}

.marks-hero-right-description {
    max-width: 340px;
    margin: 0 auto 17px;
    color: #f1f4f1;
    font-size: 11px;
    line-height: 1.7;
}

.marks-hero-actions {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
}

.marks-hero-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 40px;
    padding: 0 16px;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 10px;
    background: rgba(255,255,255,.08);
    color: #fff;
    text-decoration: none;
    font-size: 11px;
    font-weight: 750;
    transition: .2s ease;
    cursor: pointer;
}

.marks-hero-action:hover {
    background: rgba(255,255,255,.16);
    color: #fff;
    transform: translateY(-2px);
}

.marks-hero-action-primary {
    background: #f3ead5;
    border-color: #f3ead5;
    color: #233e33;
}

.marks-hero-action-primary:hover {
    background: #fff4dc;
    color: #233e33;
}

/* RESPONSIVE */

@media(max-width: 1000px) {
    .marks-hero {
        grid-template-columns: 1fr;
        padding: 20px;
    }

    .marks-hero-left {
        padding: 18px 12px 18px 22px;
    }

    .marks-hero-right {
        min-height: 220px;
        border-radius: 12px;
    }
}

@media(max-width: 600px) {
    .marks-hero {
        padding: 12px;
        border-radius: 15px;
    }

    .marks-hero-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 13px;
        padding: 12px 8px 16px 15px;
    }

    .marks-hero-icon {
        width: 60px;
        height: 60px;
        flex-basis: 60px;
        font-size: 25px;
        border-radius: 15px;
    }

    .marks-hero-breadcrumb {
        margin-bottom: 10px;
        font-size: 9px;
    }

    .marks-hero-title {
        font-size: 29px;
    }

    .marks-hero-description {
        font-size: 12px;
        margin: 10px 0;
    }

    .marks-hero-right {
        padding: 20px 12px;
        min-height: 220px;
    }

    .marks-hero-actions {
        width: 100%;
    }

    .marks-hero-action {
        flex: 1;
        padding: 0 10px;
    }
}
</style>

{{-- RESULT DASHBOARD STYLE HERO HEADER --}}
<div class="marks-hero">

    {{-- LEFT CONTENT --}}
    <div class="marks-hero-left">

        <div class="marks-hero-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>

        <div class="marks-hero-content">

            <div class="marks-hero-breadcrumb">
                <span>Academic Management</span>
                <i class="bi bi-chevron-right"></i>
                <span>Enter Examination Marks</span>
            </div>

            <h1 class="marks-hero-title">
                Enter Examination Marks
            </h1>

            <p class="marks-hero-description">
                Record student examination marks, manage internal and theory
                assessments, monitor academic performance and maintain
                accurate marks records from one place.
            </p>

            <div class="marks-hero-trust">
                <i class="bi bi-shield-fill-check"></i>
                <span>School Records & Examination Management</span>
            </div>

        </div>

    </div>

    {{-- RIGHT GREEN PANEL --}}
    <div class="marks-hero-right">

        <div class="marks-hero-right-icon">
            <i class="bi bi-journal-check"></i>
        </div>

        <h2 class="marks-hero-tagline">
            Every Mark. Every Achievement.
        </h2>

        <p class="marks-hero-right-description">
            Manage examination marks, track student progress and
            keep every academic record organized in one place.
        </p>

        <div class="marks-hero-actions">

            <a href="#marksSelectionForm"
               class="marks-hero-action marks-hero-action-primary">
                <i class="bi bi-plus-circle"></i>
                Enter Marks
            </a>

            <button type="button"
                    class="marks-hero-action"
                    onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise"></i>
                Refresh
            </button>

        </div>

    </div>

</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div class="alert alert-success marks-alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger marks-alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger marks-alert">
        <strong>Please correct the following:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- SELECTION PANEL --}}
<div class="marks-panel">
    <div class="marks-panel-heading">
        <div class="marks-heading-icon">
            <i class="bi bi-funnel"></i>
        </div>
        <div>
            <h2>Marks Selection</h2>
            <p>Choose the examination and class details before entering marks.</p>
        </div>
    </div>

    <div class="marks-panel-body">
        <form id="marksSelectionForm"
              method="GET"
              action="{{ route('admin.results.marks') }}">

            <div class="marks-filters">

                {{-- EXAM --}}
                <div class="marks-field">
                    <label for="exam_id">
                        Examination <span class="required">*</span>
                    </label>

                    <select id="exam_id"
                            name="exam_id"
                            class="marks-control"
                            required>
                        <option value="">Select examination</option>

                        @foreach($exams as $examOption)
                            <option value="{{ $examOption->id }}"
                                @selected((string)($selectedExam?->id ?? request('exam_id')) === (string)$examOption->id)>
                                {{ $examOption->exam_name }}
                                @if($examOption->academic_year)
                                    — {{ $examOption->academic_year }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CLASS --}}
                <div class="marks-field">
                    <label for="class_id">
                        Class <span class="required">*</span>
                    </label>

                    <select id="class_id"
                            name="class_id"
                            class="marks-control"
                            data-initial="{{ request('class_id') }}"
                            required
                            {{ !$selectedExam ? 'disabled' : '' }}>
                        <option value="">Select class</option>

                        @foreach($classes as $classOption)
                            <option value="{{ $classOption->id }}"
                                @selected((string)($selectedClass?->id ?? request('class_id')) === (string)$classOption->id)>
                                {{ $classOption->class_name }}
                            </option>
                        @endforeach
                    </select>

                    <small class="marks-help">
                        Only classes assigned to this examination are listed.
                    </small>
                </div>

                {{-- SECTION --}}
                <div class="marks-field">
                    <label for="section">
                        Section <span class="required">*</span>
                    </label>

                    <select id="section"
                            name="section"
                            class="marks-control"
                            data-initial="{{ request('section') }}"
                            required
                            {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">Select section</option>

                        @foreach($sections as $sectionOption)
                            <option value="{{ $sectionOption }}"
                                @selected((string)($selectedSection ?? request('section')) === (string)$sectionOption)>
                                {{ $sectionOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- SUBJECT --}}
                <div class="marks-field">
                    <label for="subject_id">
                        Subject <span class="required">*</span>
                    </label>

                    <select id="subject_id"
                            name="subject_id"
                            class="marks-control"
                            data-initial="{{ request('subject_id') }}"
                            required
                            {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">Select subject</option>

                        @foreach($subjects as $subjectOption)
                            <option value="{{ $subjectOption->subject_id }}"
                                @selected((string)($selectedSubject?->id ?? request('subject_id')) === (string)$subjectOption->subject_id)>
                                {{ $subjectOption->subject->subject_name ?? 'Unnamed Subject' }}
                                — Max {{ $formatMarks($subjectOption->maximum_marks) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div id="selectionLoading"
                 class="marks-loading"
                 role="status">
                <i class="bi bi-arrow-repeat"></i>
                Loading selection options…
            </div>

            <div class="marks-footer">
                <button type="button"
                        id="resetSelection"
                        class="marks-button marks-button-primary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </button>

                <button type="submit"
                        class="marks-button marks-button-gold">
                    <i class="bi bi-people"></i>
                    Load Students
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MARKS ENTRY --}}
@if(!$selectionMode && $examSubject)

    {{-- EDITABLE MARKS DISTRIBUTION --}}
<div class="marks-panel">

    <div class="marks-panel-heading">
        <div class="marks-heading-icon">
            <i class="bi bi-calculator"></i>
        </div>

        <div>
            <h2>Configure Marks Distribution</h2>
            <p>
                Set the maximum internal, theory and passing marks
                for this exam, class and subject.
            </p>
        </div>
    </div>

    <div class="marks-panel-body">

        <form method="POST"
              action="{{ route('admin.results.save-marks-distribution') }}"
              id="distributionForm">

            @csrf

            <input type="hidden"
                   name="exam_id"
                   value="{{ $selectedExam->id }}">

            <input type="hidden"
                   name="class_id"
                   value="{{ $selectedClass->id }}">

            <input type="hidden"
                   name="section"
                   value="{{ $selectedSection }}">

            <input type="hidden"
                   name="subject_id"
                   value="{{ $examSubject->subject_id }}">

            <div class="marks-distribution-form">

                <div class="marks-distribution-fields">

                    <div>
                        <label for="internal_max_marks">
                            Internal Maximum Marks
                        </label>

                        <input type="number"
                               id="internal_max_marks"
                               name="internal_max_marks"
                               min="0"
                               step="0.01"
                               required
                               value="{{ old('internal_max_marks', $internalMaximum) }}">
                    </div>

                    <div>
                        <label for="theory_max_marks">
                            Theory Maximum Marks
                        </label>

                        <input type="number"
                               id="theory_max_marks"
                               name="theory_max_marks"
                               min="0"
                               step="0.01"
                               required
                               value="{{ old('theory_max_marks', $theoryMaximum) }}">
                    </div>

                    <div>
                        <label for="passing_marks">
                            Passing Marks
                        </label>

                        <input type="number"
                               id="passing_marks"
                               name="passing_marks"
                               min="0"
                               step="0.01"
                               required
                               value="{{ old('passing_marks', $passingMarks) }}">
                    </div>

                    <div>
                        <label for="calculated_total">
                            Total Maximum Marks
                        </label>

                        <input type="number"
                               id="calculated_total"
                               class="marks-distribution-total"
                               value="{{ $totalMaximum }}"
                               readonly>
                    </div>

                </div>

                <div class="mt-3">
                    <small class="text-muted">
                        Total Maximum = Internal Maximum + Theory Maximum.
                        These settings are saved separately for the selected
                        examination, class and subject.
                    </small>
                </div>

                <div id="distributionCalculationError"
                     class="alert alert-danger mt-3 mb-0"
                     style="display:none">
                </div>

            </div>

            <div class="marks-footer">

                <button type="submit"
                        class="marks-button marks-button-gold"
                        id="saveDistributionButton">

                    <i class="bi bi-save"></i>
                    Save Marks Distribution

                </button>

            </div>

        </form>

        <div class="marks-distribution mt-3">

            <div class="marks-distribution-item">
                <div class="marks-distribution-label">
                    Internal Maximum
                </div>

                <div class="marks-distribution-value"
                     id="displayInternalMaximum">
                    {{ $formatMarks($internalMaximum) }}
                </div>

                <small>Internal assessment marks</small>
            </div>

            <div class="marks-distribution-item">
                <div class="marks-distribution-label">
                    Theory Maximum
                </div>

                <div class="marks-distribution-value"
                     id="displayTheoryMaximum">
                    {{ $formatMarks($theoryMaximum) }}
                </div>

                <small>Written examination marks</small>
            </div>

            <div class="marks-distribution-item">
                <div class="marks-distribution-label">
                    Total Maximum
                </div>

                <div class="marks-distribution-value"
                     id="displayTotalMaximum">
                    {{ $formatMarks($totalMaximum) }}
                </div>

                <small>Internal + Theory</small>
            </div>

        </div>

        @if(!$distributionValid)

            <div class="alert alert-warning marks-alert mt-3 mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>

                <strong>Marks distribution needs configuration.</strong>

                <p class="mb-0 mt-2">
                    Save a valid distribution before entering student marks.
                </p>
            </div>

        @else

            <div class="alert alert-success marks-alert mt-3 mb-0">
                <i class="bi bi-check-circle me-2"></i>

                Marks distribution is configured correctly.

                Passing Marks:
                <strong>{{ $formatMarks($passingMarks) }}</strong>
            </div>

        @endif

    </div>
</div>

    {{-- STUDENT SUMMARY --}}
   <div class="marks-stats">

    <div class="marks-stat">
        <div class="marks-stat-label">Students</div>
        <div class="marks-stat-value">{{ $students->count() }}</div>
    </div>

    <div class="marks-stat">
        <div class="marks-stat-label">Internal Maximum</div>
        <div class="marks-stat-value">
            {{ $formatMarks($internalMaximum) }}
        </div>
    </div>

    <div class="marks-stat">
        <div class="marks-stat-label">Theory Maximum</div>
        <div class="marks-stat-value">
            {{ $formatMarks($theoryMaximum) }}
        </div>
    </div>

    <div class="marks-stat">
        <div class="marks-stat-label">Total Maximum</div>
        <div class="marks-stat-value">
            {{ $formatMarks($totalMaximum) }}
        </div>
    </div>

</div>

    {{-- STUDENT MARKS PANEL --}}
    <div class="marks-panel">

        <div class="marks-panel-heading">
            <div class="marks-heading-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h2>Student Marks Entry</h2>

                <p>
                    {{ $selectedExam->exam_name }}

                    <span class="marks-info-chip">
                        {{ $selectedClass->class_name }}
                    </span>

                    <span class="marks-info-chip">
                        Section {{ $selectedSection }}
                    </span>

                    <span class="marks-info-chip">
                        {{ $selectedSubject->subject_name ?? 'Subject' }}
                    </span>
                </p>
            </div>
        </div>

        <div class="marks-panel-body">

            @if($students->isEmpty())

                <div class="marks-empty">
                    <div class="marks-empty-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <h3>No students found</h3>

                    <p>
                        Check the selected class, section and academic year.
                    </p>
                </div>

            @elseif(!$distributionValid)

                <div class="marks-empty">
                    <div class="marks-empty-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>

                    <h3>Marks distribution is incomplete</h3>

                    <p>
                        Correct the subject distribution before entering student marks.
                    </p>
                </div>

            @else

                <form method="POST"
                      action="{{ route('admin.results.save-marks') }}"
                      id="saveMarksForm">

                    @csrf

                    <input type="hidden" name="exam_id" value="{{ $selectedExam->id }}">
                    <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                    <input type="hidden" name="section" value="{{ $selectedSection }}">
                    <input type="hidden" name="subject_id" value="{{ $examSubject->subject_id }}">

                    <div class="marks-toolbar">
                        <div>
                            <h3>Marks Register</h3>
                            <p>
                                Enter Internal and Theory marks separately.
                                Total calculates automatically.
                            </p>
                        </div>

                        <span class="marks-info-chip">
                            Maximum: {{ $formatMarks($totalMaximum) }}
                        </span>
                    </div>

                    <div class="marks-table-wrap">
                        <table class="marks-table">

                            <thead>
                                <tr>
                                    <th style="width:55px">#</th>
                                    <th>Student</th>
                                    <th>Roll No.</th>
                                    <th>Internal / {{ $formatMarks($internalMaximum) }}</th>
                                    <th>Theory / {{ $formatMarks($theoryMaximum) }}</th>
                                    <th>Total / {{ $formatMarks($totalMaximum) }}</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($students as $student)

                                    @php
                                        $savedMark = $existingMarks->get($student->id);

                                        $studentFullName = $student->full_name
                                            ?? trim(collect([
                                                $student->first_name ?? '',
                                                $student->middle_name ?? '',
                                                $student->last_name ?? '',
                                            ])->filter()->implode(' '));

                                        $markStatus = old(
                                            "marks.{$student->id}.status",
                                            $savedMark->status ?? 'present'
                                        );

                                        $internal = old(
                                            "marks.{$student->id}.internal_marks",
                                            $savedMark->internal_marks ?? 0
                                        );

                                        $theory = old(
                                            "marks.{$student->id}.theory_marks",
                                            $savedMark->theory_marks ?? 0
                                        );

                                        $remarks = old(
                                            "marks.{$student->id}.remarks",
                                            $savedMark->remarks ?? ''
                                        );

                                        $savedTotal = $savedMark
                                            ? (float) $savedMark->total_marks
                                            : ((float) $internal + (float) $theory);
                                    @endphp

                                    <tr class="student-mark-row"
                                        data-internal-max="{{ $internalMaximum }}"
                                        data-theory-max="{{ $theoryMaximum }}"
                                        data-max="{{ $totalMaximum }}">

                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <div class="marks-student-name">
                                                {{ $studentFullName ?: 'Unnamed Student' }}
                                            </div>

                                            <div class="marks-student-id">
                                                {{ $student->student_id }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ $student->roll_number ?: '—' }}
                                        </td>

                                        {{-- INTERNAL MARKS --}}
                                        <td>
                                            @if($internalMaximum > 0)

                                                <input type="number"
                                                       class="marks-number mark-input internal-input"
                                                       name="marks[{{ $student->id }}][internal_marks]"
                                                       value="{{ $internal }}"
                                                       min="0"
                                                       max="{{ $internalMaximum }}"
                                                       step="0.01"
                                                       required
                                                       aria-label="Internal marks for {{ $studentFullName }}">

                                            @else

                                                <input type="hidden"
                                                       class="internal-input"
                                                       name="marks[{{ $student->id }}][internal_marks]"
                                                       value="0">

                                                <span class="marks-not-applicable">
                                                    N/A
                                                </span>

                                            @endif
                                        </td>

                                        {{-- THEORY MARKS --}}
                                        <td>
                                            @if($theoryMaximum > 0)

                                                <input type="number"
                                                       class="marks-number mark-input theory-input"
                                                       name="marks[{{ $student->id }}][theory_marks]"
                                                       value="{{ $theory }}"
                                                       min="0"
                                                       max="{{ $theoryMaximum }}"
                                                       step="0.01"
                                                       required
                                                       aria-label="Theory marks for {{ $studentFullName }}">

                                            @else

                                                <input type="hidden"
                                                       class="theory-input"
                                                       name="marks[{{ $student->id }}][theory_marks]"
                                                       value="0">

                                                <span class="marks-not-applicable">
                                                    N/A
                                                </span>

                                            @endif
                                        </td>

                                        {{-- TOTAL --}}
                                        <td>
                                            <span class="marks-total js-row-total">
                                                {{ number_format($savedTotal, 2) }}
                                            </span>
                                        </td>

                                        {{-- STATUS --}}
                                        <td>
                                            <select class="marks-status mark-status"
                                                    name="marks[{{ $student->id }}][status]">

                                                <option value="present"
                                                    @selected($markStatus === 'present')>
                                                    Present
                                                </option>

                                                <option value="absent"
                                                    @selected($markStatus === 'absent')>
                                                    Absent
                                                </option>

                                                <option value="na"
                                                    @selected($markStatus === 'na')>
                                                    Not Applicable
                                                </option>

                                            </select>
                                        </td>

                                        {{-- REMARKS --}}
                                        <td>
                                            <input type="text"
                                                   class="marks-number"
                                                   style="width:125px"
                                                   name="marks[{{ $student->id }}][remarks]"
                                                   value="{{ $remarks }}"
                                                   maxlength="255"
                                                   placeholder="Optional">
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="marks-footer">

                        <button type="button"
                                id="clearMarks"
                                class="marks-button marks-button-primary">
                            <i class="bi bi-eraser"></i>
                            Clear Unsaved Inputs
                        </button>

                        <button type="submit"
                                class="marks-button marks-button-gold"
                                id="saveMarksButton">
                            <i class="bi bi-check2-circle"></i>
                            Save Marks
                        </button>

                    </div>

                </form>

            @endif

        </div>
    </div>

@else

    <div class="marks-panel">
        <div class="marks-empty">
            <div class="marks-empty-icon">
                <i class="bi bi-journal-check"></i>
            </div>

            <h3>Select the exam details first</h3>

            <p>
                Choose an examination, class, section and subject,
                then click Load Students.
            </p>
        </div>
    </div>

@endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');
    const sectionSelect = document.getElementById('section');
    const subjectSelect = document.getElementById('subject_id');
    const loading = document.getElementById('selectionLoading');

    const urls = {
        classes: @json(route('admin.results.load-classes')),
        sections: @json(route('admin.results.load-sections')),
        subjects: @json(route('admin.results.load-subjects'))
    };

    const requestVersion = {
        classes: 0,
        sections: 0,
        subjects: 0
    };

    function setLoading(show) {
        loading?.classList.toggle('show', show);
    }

    function resetSelect(select, placeholder, disabled = true) {
        if (!select) return;

        select.replaceChildren(new Option(placeholder, ''));
        select.disabled = disabled;
    }

    function showError(error) {
        console.error('Marks selection error:', error);

        alert(
            error.message ||
            'Unable to load selection options. Please try again.'
        );
    }

    async function getJson(url, params) {

        const query = new URLSearchParams();

        Object.entries(params).forEach(([key, value]) => {
            if (value !== undefined && value !== null && value !== '') {
                query.set(key, value);
            }
        });

        const response = await fetch(
            `${url}?${query.toString()}`,
            {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            }
        );

        const rawText = await response.text();
        const cleanText = rawText.replace(/^[\s\uFEFF]+/, '');

        let data;

        try {
            data = JSON.parse(cleanText);
        } catch (error) {

            console.error('Invalid JSON response:', {
                url: response.url,
                status: response.status,
                contentType: response.headers.get('content-type'),
                response: rawText
            });

            throw new Error(
                'Invalid server response. Check the Network tab for the request.'
            );
        }

        if (!response.ok || data.success === false) {
            throw new Error(
                data.message || `Request failed (${response.status}).`
            );
        }

        return data;
    }

    function getItemId(item) {
        return item?.class_id
            ?? item?.school_class_id
            ?? item?.id
            ?? item?.subject_id
            ?? '';
    }

    function getClassName(item) {
        return item?.name
            ?? item?.class_name
            ?? item?.school_class?.class_name
            ?? item?.schoolClass?.class_name
            ?? '';
    }

    function getSectionValue(item) {
        return typeof item === 'object'
            ? (item.section ?? item.name ?? item.id ?? '')
            : item;
    }

    function getSubjectName(item) {
        return item?.name
            ?? item?.subject_name
            ?? item?.subject?.subject_name
            ?? item?.subject?.name
            ?? 'Unnamed subject';
    }

    function addOption(select, value, label, selected = false) {
        const option = new Option(
            String(label ?? ''),
            String(value ?? '')
        );

        option.selected = selected;
        select.add(option);
    }

    // ==========================================
    // LOAD CLASSES
    // ==========================================

    async function loadClasses(selectedClass = '') {

        const version = ++requestVersion.classes;

        requestVersion.sections++;
        requestVersion.subjects++;

        resetSelect(classSelect, 'Select class');
        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value) return;

        setLoading(true);

        try {

            const data = await getJson(urls.classes, {
                exam_id: examSelect.value
            });

            if (version !== requestVersion.classes) return;

            if (!Array.isArray(data.classes)) {
                throw new Error(
                    data.message || 'Classes array is missing from the response.'
                );
            }

            const seen = new Set();

            data.classes.forEach(item => {

                const id = getItemId(item);
                const name = getClassName(item);

                if (id === '' || !name) return;

                const key = String(id);

                if (seen.has(key)) return;

                seen.add(key);

                const section =
                    item.section ??
                    item.school_class?.section ??
                    item.schoolClass?.section ??
                    '';

                const label = section
                    ? `${name} — Section ${section}`
                    : name;

                addOption(
                    classSelect,
                    id,
                    label,
                    String(id) === String(selectedClass)
                );
            });

            classSelect.disabled = false;

            if (selectedClass && classSelect.value) {
                await loadSections(
                    classSelect.value,
                    sectionSelect.dataset.initial || ''
                );
            }

        } catch (error) {

            if (version === requestVersion.classes) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.classes) {
                setLoading(false);
            }
        }
    }

    // ==========================================
    // LOAD SECTIONS
    // ==========================================

    async function loadSections(classId, selectedSection = '') {

        const version = ++requestVersion.sections;

        requestVersion.subjects++;

        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value || !classId) return;

        setLoading(true);

        try {

            const data = await getJson(urls.sections, {
                exam_id: examSelect.value,
                class_id: classId
            });

            if (version !== requestVersion.sections) return;

            if (!Array.isArray(data.sections)) {
                throw new Error(
                    data.message || 'Sections array is missing from the response.'
                );
            }

            const seen = new Set();

            data.sections.forEach(item => {

                const value = getSectionValue(item);

                if (value === '' || value === null || value === undefined) {
                    return;
                }

                const key = String(value);

                if (seen.has(key)) return;

                seen.add(key);

                const label = typeof item === 'object'
                    ? (item.name ?? item.section ?? item.label ?? value)
                    : value;

                addOption(
                    sectionSelect,
                    value,
                    label,
                    String(value) === String(selectedSection)
                );
            });

            sectionSelect.disabled = false;

            if (selectedSection && sectionSelect.value) {
                await loadSubjects(
                    classId,
                    subjectSelect.dataset.initial || ''
                );
            }

        } catch (error) {

            if (version === requestVersion.sections) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.sections) {
                setLoading(false);
            }
        }
    }

    // ==========================================
    // LOAD SUBJECTS
    // ==========================================

    async function loadSubjects(classId, selectedSubject = '') {

        const version = ++requestVersion.subjects;

        resetSelect(subjectSelect, 'Select subject');

        if (!examSelect?.value || !classId) return;

        setLoading(true);

        try {

            const data = await getJson(urls.subjects, {
                exam_id: examSelect.value,
                class_id: classId
            });

            if (version !== requestVersion.subjects) return;

            if (!Array.isArray(data.subjects)) {
                throw new Error(
                    data.message || 'Subjects array is missing from the response.'
                );
            }

            const seen = new Set();

            data.subjects.forEach(item => {

                const id = item.subject_id ?? item.id ?? '';

                if (id === '') return;

                const key = String(id);

                if (seen.has(key)) return;

                seen.add(key);

                const name = getSubjectName(item);
                const maximum = Number(item.maximum_marks ?? 0);

                let label = name;

                if (item.code) {
                    label += ` (${item.code})`;
                }

                label += ` — Max: ${maximum}`;

                addOption(
                    subjectSelect,
                    id,
                    label,
                    String(id) === String(selectedSubject)
                );
            });

            subjectSelect.disabled = false;

        } catch (error) {

            if (version === requestVersion.subjects) {
                showError(error);
            }

        } finally {

            if (version === requestVersion.subjects) {
                setLoading(false);
            }
        }
    }

    // ==========================================
    // EXAMINATION CHANGE
    // ==========================================

    examSelect?.addEventListener('change', function () {

        classSelect.dataset.initial = '';
        sectionSelect.dataset.initial = '';
        subjectSelect.dataset.initial = '';

        resetSelect(classSelect, 'Loading classes...');
        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (this.value) {
            loadClasses('');
        }
    });

    // ==========================================
    // CLASS CHANGE
    // ==========================================

    classSelect?.addEventListener('change', function () {

        sectionSelect.dataset.initial = '';
        subjectSelect.dataset.initial = '';

        resetSelect(sectionSelect, 'Select section');
        resetSelect(subjectSelect, 'Select subject');

        if (this.value) {
            loadSections(this.value, '');
        }
    });

    // ==========================================
    // SECTION CHANGE
    // ==========================================

    sectionSelect?.addEventListener('change', function () {

        subjectSelect.dataset.initial = '';

        if (classSelect.value && this.value) {
            loadSubjects(classSelect.value, '');
        } else {
            requestVersion.subjects++;
            resetSelect(subjectSelect, 'Select subject');
        }
    });

    // ==========================================
    // RESET SELECTION
    // ==========================================

    document.getElementById('resetSelection')
        ?.addEventListener('click', function () {

            window.location.href = @json(route('admin.results.marks'));
        });

    // ==========================================
    // INITIAL PAGE LOAD
    // ==========================================

    if (examSelect?.value) {

        const initialClass =
            classSelect.dataset.initial || classSelect.value || '';

        const initialSection =
            sectionSelect.dataset.initial || '';

        const initialSubject =
            subjectSelect.dataset.initial || '';

        sectionSelect.dataset.initial = initialSection;
        subjectSelect.dataset.initial = initialSubject;

        loadClasses(initialClass);
    }

    // ==========================================
    // AUTOMATIC TOTAL CALCULATION
    // ==========================================

    function calculateRowTotal(row) {

        const internalInput = row.querySelector('.internal-input');
        const theoryInput = row.querySelector('.theory-input');
        const totalElement = row.querySelector('.js-row-total');

        const internal = parseFloat(internalInput?.value) || 0;
        const theory = parseFloat(theoryInput?.value) || 0;

        const maximum = parseFloat(row.dataset.max) || 0;
        const total = internal + theory;

        totalElement.textContent = total.toFixed(2);

        totalElement.classList.toggle(
            'text-danger',
            total > maximum
        );

        row.classList.toggle(
            'marks-total-warning',
            total > maximum
        );
    }

    document.querySelectorAll('.student-mark-row').forEach(row => {

        row.querySelectorAll('.mark-input').forEach(input => {

            input.addEventListener('input', function () {
                calculateRowTotal(row);
            });
        });

        calculateRowTotal(row);
    });

    // ==========================================
    // CLEAR UNSAVED INPUTS
    // ==========================================

    document.getElementById('clearMarks')
        ?.addEventListener('click', function () {

            const confirmed = confirm(
                'Clear all entered marks from this form? Saved database marks will not be deleted.'
            );

            if (!confirmed) return;

            document.querySelectorAll('.student-mark-row').forEach(row => {

                row.querySelectorAll('.mark-input').forEach(input => {
                    input.value = '';
                });

                const status = row.querySelector('.mark-status');

                if (status) {
                    status.value = 'present';
                }

                const remarks = row.querySelector(
                    'input[name$="[remarks]"]'
                );

                if (remarks) {
                    remarks.value = '';
                }

                calculateRowTotal(row);
            });
        });

    // ==========================================
    // VALIDATE BEFORE SUBMIT
    // ==========================================

    document.getElementById('saveMarksForm')
        ?.addEventListener('submit', function (event) {

            let invalid = false;
            let invalidMessage = '';

            document.querySelectorAll('.student-mark-row').forEach(row => {

                const internalInput = row.querySelector('.internal-input');
                const theoryInput = row.querySelector('.theory-input');

                const internal = parseFloat(internalInput?.value) || 0;
                const theory = parseFloat(theoryInput?.value) || 0;

                const internalMax = parseFloat(row.dataset.internalMax) || 0;
                const theoryMax = parseFloat(row.dataset.theoryMax) || 0;
                const totalMax = parseFloat(row.dataset.max) || 0;

                if (internal < 0 || internal > internalMax) {
                    invalid = true;
                    invalidMessage =
                        `Internal marks must be between 0 and ${internalMax}.`;
                    return;
                }

                if (theory < 0 || theory > theoryMax) {
                    invalid = true;
                    invalidMessage =
                        `Theory marks must be between 0 and ${theoryMax}.`;
                    return;
                }

                if ((internal + theory) > totalMax) {
                    invalid = true;
                    invalidMessage =
                        `Total marks cannot exceed ${totalMax}.`;
                }
            });

            if (invalid) {
                event.preventDefault();
                alert(invalidMessage);
                return;
            }

            const button = document.getElementById('saveMarksButton');

            if (button) {
                button.disabled = true;
                button.innerHTML =
                    '<i class="bi bi-arrow-repeat"></i> Saving Marks...';
            }
        });

        // ==========================================
// MARKS DISTRIBUTION CALCULATION
// ==========================================

const internalMaximumInput =
    document.getElementById('internal_max_marks');

const theoryMaximumInput =
    document.getElementById('theory_max_marks');

const passingMarksInput =
    document.getElementById('passing_marks');

const calculatedTotalInput =
    document.getElementById('calculated_total');

const distributionError =
    document.getElementById('distributionCalculationError');

function calculateDistribution() {

    if (
        !internalMaximumInput ||
        !theoryMaximumInput ||
        !passingMarksInput ||
        !calculatedTotalInput
    ) {
        return;
    }

    const internal = Number(internalMaximumInput.value) || 0;
    const theory = Number(theoryMaximumInput.value) || 0;
    const passing = Number(passingMarksInput.value) || 0;

    const total = internal + theory;

    calculatedTotalInput.value = total.toFixed(2);

    document.getElementById('displayInternalMaximum').textContent =
        internal.toFixed(2);

    document.getElementById('displayTheoryMaximum').textContent =
        theory.toFixed(2);

    document.getElementById('displayTotalMaximum').textContent =
        total.toFixed(2);

    let error = '';

    if (internal < 0 || theory < 0 || passing < 0) {
        error = 'Marks cannot be negative.';
    } else if (total <= 0) {
        error = 'Internal and Theory maximum marks cannot both be zero.';
    } else if (passing > total) {
        error = 'Passing marks cannot exceed total maximum marks.';
    }

    if (error) {
        distributionError.textContent = error;
        distributionError.style.display = 'block';
    } else {
        distributionError.textContent = '';
        distributionError.style.display = 'none';
    }

    return !error;
}

[
    internalMaximumInput,
    theoryMaximumInput,
    passingMarksInput
].forEach(input => {
    input?.addEventListener('input', calculateDistribution);
});

document.getElementById('distributionForm')
    ?.addEventListener('submit', function(event) {

        if (!calculateDistribution()) {
            event.preventDefault();
            return;
        }

        const button = document.getElementById(
            'saveDistributionButton'
        );

        if (button) {
            button.disabled = true;
            button.innerHTML =
                '<i class="bi bi-arrow-repeat"></i> Saving Distribution...';
        }
    });

});
</script>

@endsection
