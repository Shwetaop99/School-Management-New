
@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Create Exam
========================================================= */

.create-exam-page {
    --ce-page: #f7f6f0;
    --ce-paper: #fffefa;
    --ce-cream: #f5f3e9;
    --ce-cream-deep: #f1ead8;
    --ce-ink: #263b35;
    --ce-dark: #1d302b;
    --ce-forest: #344f43;
    --ce-green: #43835d;
    --ce-sage: #527f70;
    --ce-blue: #426b8a;
    --ce-amber: #a87543;
    --ce-red: #b95b50;
    --ce-text: #39483e;
    --ce-muted: #7b857b;
    --ce-line: #e5e1d5;
    --ce-shadow: 0 8px 26px rgba(38, 59, 53, .055);
    --ce-radius: 16px;

    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
    color: var(--ce-text);
    background: var(--ce-page);
    border-radius: 20px;
    font-family: "Public Sans", "Inter", sans-serif;
}

.create-exam-page *,
.create-exam-page *::before,
.create-exam-page *::after {
    box-sizing: border-box;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.create-exam-page .ce-page-header {
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    padding: 28px 30px;
    margin-bottom: 17px;
    overflow: hidden;
    background: var(--ce-paper);
    border: 1px solid var(--ce-line);
    border-left: 5px solid var(--ce-forest);
    border-radius: var(--ce-radius);
    box-shadow: var(--ce-shadow);
}

.create-exam-page .ce-page-header::after {
    content: "";
    position: absolute;
    top: -115px;
    right: 16%;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(82, 127, 112, .13);
    border-radius: 50%;
    pointer-events: none;
}

.create-exam-page .ce-header-content {
    position: relative;
    z-index: 1;
    min-width: 0;
}

.create-exam-page .ce-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 9px;
    color: var(--ce-amber);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.6px;
    text-transform: uppercase;
}

.create-exam-page .ce-page-title {
    margin: 0;
    color: var(--ce-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(25px, 3vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.5px;
}

.create-exam-page .ce-page-subtitle {
    margin: 10px 0 0;
    color: var(--ce-muted);
    font-size: 12px;
    line-height: 1.8;
}

.create-exam-page .ce-header-actions {
    position: relative;
    z-index: 1;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    gap: 10px;
}

/* =========================================================
   BUTTONS
========================================================= */

.create-exam-page .ce-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 43px;
    padding: 11px 17px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 750;
    line-height: 1.4;
    text-decoration: none;
    cursor: pointer;
    transition: background .2s ease, color .2s ease,
                border-color .2s ease, transform .2s ease;
}

.create-exam-page .ce-btn:hover {
    transform: translateY(-1px);
}

.create-exam-page .ce-btn-outline {
    color: var(--ce-ink);
    background: var(--ce-paper);
    border-color: var(--ce-line);
}

.create-exam-page .ce-btn-outline:hover {
    color: var(--ce-dark);
    background: var(--ce-cream);
    border-color: #c9c4b5;
}

.create-exam-page .ce-btn-primary {
    min-height: 48px;
    padding: 12px 22px;
    color: var(--ce-ink);
    background: var(--ce-cream-deep);
    border-color: #e3dac3;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .07);
}

.create-exam-page .ce-btn-primary:hover {
    color: var(--ce-dark);
    background: #fff5dc;
    border-color: #d9cba8;
}

.create-exam-page .ce-btn-primary:disabled {
    opacity: .7;
    cursor: wait;
    transform: none;
}

.create-exam-page .ce-btn:focus-visible,
.create-exam-page .ce-form-control:focus-visible,
.create-exam-page .ce-form-select:focus-visible {
    outline: 3px solid rgba(82, 127, 112, .25);
    outline-offset: 2px;
}

/* =========================================================
   BREADCRUMB
========================================================= */

.create-exam-page .ce-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin: 0 0 22px 4px;
    color: var(--ce-muted);
    font-size: 11px;
}

.create-exam-page .ce-breadcrumb i {
    color: var(--ce-sage);
}

.create-exam-page .ce-breadcrumb-separator {
    color: #c4c1b4;
}

/* =========================================================
   VALIDATION ERRORS
========================================================= */

.create-exam-page .ce-error-card {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 22px;
    padding: 18px 20px;
    color: #8f4139;
    background: #fff6f3;
    border: 1px solid #efd1cb;
    border-left: 4px solid var(--ce-red);
    border-radius: 12px;
}

.create-exam-page .ce-error-icon {
    margin-top: 2px;
    font-size: 17px;
}

.create-exam-page .ce-error-content {
    min-width: 0;
}

.create-exam-page .ce-error-title {
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 800;
}

.create-exam-page .ce-error-list {
    margin: 0;
    padding-left: 21px;
    font-size: 12px;
    line-height: 1.9;
}

/* =========================================================
   MAIN FORM PANEL
========================================================= */

.create-exam-page .ce-panel {
    overflow: hidden;
    background: var(--ce-paper);
    border: 1px solid var(--ce-line);
    border-radius: var(--ce-radius);
    box-shadow: var(--ce-shadow);
}

.create-exam-page .ce-panel-header {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 21px 25px;
    background: #fffdf7;
    border-bottom: 1px solid var(--ce-line);
}

.create-exam-page .ce-panel-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    color: var(--ce-sage);
    background: #edf2eb;
    border: 1px solid #dce7db;
    border-radius: 12px;
    font-size: 18px;
}

.create-exam-page .ce-panel-heading {
    min-width: 0;
}

.create-exam-page .ce-panel-title {
    margin: 0;
    color: var(--ce-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 20px;
    font-weight: 700;
    line-height: 1.4;
}

.create-exam-page .ce-panel-description {
    margin: 5px 0 0;
    color: var(--ce-muted);
    font-size: 11px;
    line-height: 1.7;
}

.create-exam-page .ce-panel-body {
    padding: 27px 25px;
}

/* =========================================================
   FORM SECTIONS
========================================================= */

.create-exam-page .ce-form-section {
    margin-bottom: 29px;
}

.create-exam-page .ce-form-section:last-child {
    margin-bottom: 0;
}

.create-exam-page .ce-section-heading {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 17px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--ce-line);
}

.create-exam-page .ce-section-heading i {
    color: var(--ce-sage);
    font-size: 16px;
}

.create-exam-page .ce-section-title {
    margin: 0;
    color: var(--ce-ink);
    font-size: 13px;
    font-weight: 800;
}

.create-exam-page .ce-section-description {
    margin: 4px 0 0;
    color: var(--ce-muted);
    font-size: 11px;
    line-height: 1.6;
}

/* =========================================================
   FORM LABELS AND INPUTS
========================================================= */

.create-exam-page .ce-field {
    height: 100%;
}

.create-exam-page .ce-field-label {
    display: block;
    margin-bottom: 9px;
    color: var(--ce-ink);
    font-size: 12px;
    font-weight: 800;
}

.create-exam-page .ce-required {
    color: var(--ce-red);
}

.create-exam-page .ce-input-wrap {
    position: relative;
}

.create-exam-page .ce-input-icon {
    position: absolute;
    top: 50%;
    left: 14px;
    z-index: 2;
    color: var(--ce-sage);
    font-size: 15px;
    transform: translateY(-50%);
    pointer-events: none;
}

.create-exam-page .ce-form-control,
.create-exam-page .ce-form-select {
    display: block;
    width: 100%;
    min-height: 47px;
    padding: 11px 13px;
    color: var(--ce-ink);
    background-color: #fffefa;
    border: 1px solid #dcd8cb;
    border-radius: 10px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 550;
    line-height: 1.5;
    box-shadow: none;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.create-exam-page .ce-input-with-icon {
    padding-left: 42px;
}

.create-exam-page .ce-form-select.ce-input-with-icon {
    padding-right: 38px;
}

.create-exam-page .ce-form-control:hover,
.create-exam-page .ce-form-select:hover {
    border-color: #b9c9bb;
}

.create-exam-page .ce-form-control:focus,
.create-exam-page .ce-form-select:focus {
    color: var(--ce-ink);
    background-color: var(--ce-paper);
    border-color: var(--ce-sage);
    box-shadow: 0 0 0 3px rgba(82, 127, 112, .12);
    outline: none;
}

.create-exam-page .ce-form-control::placeholder {
    color: #a1a69d;
    opacity: 1;
}

.create-exam-page .ce-field-help {
    display: block;
    margin-top: 8px;
    color: var(--ce-muted);
    font-size: 10px;
    line-height: 1.75;
}

.create-exam-page .ce-field-help i {
    margin-right: 4px;
    color: var(--ce-sage);
}

.create-exam-page .ce-form-control.is-invalid,
.create-exam-page .ce-form-select.is-invalid {
    border-color: var(--ce-red);
}

/* =========================================================
   DATE PERIOD PREVIEW
========================================================= */

.create-exam-page .ce-date-preview {
    display: none;
    align-items: center;
    gap: 14px;
    margin-top: 22px;
    padding: 16px 18px;
    background: #f0f5ef;
    border: 1px solid #dbe7d8;
    border-radius: 12px;
}

.create-exam-page .ce-date-preview.show {
    display: flex;
}

.create-exam-page .ce-date-preview-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    color: var(--ce-sage);
    background: #e0eadf;
    border: 1px solid #d5e3d3;
    border-radius: 11px;
    font-size: 18px;
}

.create-exam-page .ce-date-preview-content {
    min-width: 0;
}

.create-exam-page .ce-date-preview-label {
    margin-bottom: 4px;
    color: var(--ce-muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.create-exam-page .ce-date-preview-value {
    color: var(--ce-ink);
    font-size: 12px;
    font-weight: 750;
    line-height: 1.7;
    overflow-wrap: anywhere;
}

/* =========================================================
   NEXT STEPS CARD
========================================================= */

.create-exam-page .ce-next-steps {
    margin-top: 27px;
    overflow: hidden;
    background: #fcfbf6;
    border: 1px solid var(--ce-line);
    border-radius: 13px;
}

.create-exam-page .ce-next-steps-header {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 16px 19px;
    color: var(--ce-ink);
    background: #f5f2e7;
    border-bottom: 1px solid var(--ce-line);
}

.create-exam-page .ce-next-steps-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    color: var(--ce-sage);
    background: #e5ebdf;
    border: 1px solid #d9e2d4;
    border-radius: 10px;
    font-size: 15px;
}

.create-exam-page .ce-next-steps-title {
    margin: 0;
    font-size: 13px;
    font-weight: 800;
}

.create-exam-page .ce-next-steps-subtitle {
    margin: 3px 0 0;
    color: var(--ce-muted);
    font-size: 10px;
    line-height: 1.6;
}

.create-exam-page .ce-next-steps-body {
    padding: 19px;
}

.create-exam-page .ce-step-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 15px;
}

.create-exam-page .ce-step-item:last-child {
    margin-bottom: 0;
}

.create-exam-page .ce-step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    flex: 0 0 27px;
    color: var(--ce-forest);
    background: #e9eee4;
    border: 1px solid #dce5d5;
    border-radius: 9px;
    font-size: 11px;
    font-weight: 800;
}

.create-exam-page .ce-step-text {
    padding-top: 4px;
    color: var(--ce-text);
    font-size: 11px;
    line-height: 1.8;
}

.create-exam-page .ce-step-text strong {
    color: var(--ce-ink);
    font-weight: 800;
}

/* =========================================================
   FORM ACTIONS
========================================================= */

.create-exam-page .ce-action-bar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 28px;
    padding-top: 22px;
    border-top: 1px solid var(--ce-line);
}

.create-exam-page .ce-action-note {
    margin-right: auto;
    color: var(--ce-muted);
    font-size: 10px;
    line-height: 1.7;
}

.create-exam-page .ce-action-note i {
    margin-right: 5px;
    color: var(--ce-sage);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991.98px) {
    .create-exam-page {
        padding: 18px;
    }

    .create-exam-page .ce-page-header {
        padding: 23px;
    }

    .create-exam-page .ce-panel-body {
        padding: 23px 20px;
    }
}

@media (max-width: 767.98px) {
    .create-exam-page {
        padding: 13px;
        border-radius: 13px;
    }

    .create-exam-page .ce-page-header {
        align-items: flex-start;
        flex-direction: column;
        gap: 18px;
        padding: 21px 18px;
    }

    .create-exam-page .ce-header-actions,
    .create-exam-page .ce-header-actions .ce-btn {
        width: 100%;
    }

    .create-exam-page .ce-page-title {
        font-size: 26px;
    }

    .create-exam-page .ce-panel-header {
        padding: 17px;
    }

    .create-exam-page .ce-panel-body {
        padding: 20px 16px;
    }

    .create-exam-page .ce-panel-title {
        font-size: 18px;
    }

    .create-exam-page .ce-action-bar {
        align-items: stretch;
        flex-direction: column-reverse;
    }

    .create-exam-page .ce-action-bar .ce-btn {
        width: 100%;
    }

    .create-exam-page .ce-action-note {
        margin: 5px 0 0;
        text-align: center;
    }

    .create-exam-page .ce-next-steps-body {
        padding: 16px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .create-exam-page *,
    .create-exam-page *::before,
    .create-exam-page *::after {
        scroll-behavior: auto !important;
        transition: none !important;
        animation: none !important;
    }
}
</style>

<div class="create-exam-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}
    <div class="ce-page-header">

        <div class="ce-header-content">

            <div class="ce-eyebrow">
                <i class="bi bi-journal-bookmark-fill"></i>
                Examination Management
            </div>

            <h1 class="ce-page-title">
                Create Exam
            </h1>

            <p class="ce-page-subtitle">
                Set up the examination details and dates to get started.
            </p>

        </div>

        <div class="ce-header-actions">

            <a href="{{ route('admin.exams.index') }}"
               class="ce-btn ce-btn-outline">

                <i class="bi bi-arrow-left"></i>
                Back to Exams

            </a>

        </div>

    </div>


    {{-- =====================================================
        BREADCRUMB
    ====================================================== --}}
    <div class="ce-breadcrumb">

        <i class="bi bi-house-door"></i>

        <span>Examinations</span>

        <span class="ce-breadcrumb-separator">/</span>

        <span>Create Exam</span>

    </div>


    {{-- =====================================================
        VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->any())

        <div class="ce-error-card" role="alert">

            <i class="bi bi-exclamation-triangle-fill ce-error-icon"></i>

            <div class="ce-error-content">

                <div class="ce-error-title">
                    Please correct the following errors
                </div>

                <ul class="ce-error-list">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =====================================================
        MAIN FORM PANEL
    ====================================================== --}}
    <section class="ce-panel">

        <div class="ce-panel-header">

            <div class="ce-panel-icon">
                <i class="bi bi-journal-plus"></i>
            </div>

            <div class="ce-panel-heading">

                <h2 class="ce-panel-title">
                    Exam Information
                </h2>

                <p class="ce-panel-description">
                    Enter the basic examination details and choose the examination period.
                </p>

            </div>

        </div>


        <div class="ce-panel-body">

            <form method="POST"
                  action="{{ route('admin.exams.store') }}"
                  id="createExamForm">

                @csrf


                {{-- =================================================
                    BASIC EXAM INFORMATION
                ================================================== --}}
                <div class="ce-form-section">

                    <div class="ce-section-heading">

                        <i class="bi bi-card-list"></i>

                        <div>
                            <h3 class="ce-section-title">
                                Basic Information
                            </h3>

                            <p class="ce-section-description">
                                Enter the academic year, exam name, type, and status.
                            </p>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- ACADEMIC YEAR --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="academic_year" class="ce-field-label">
                                    Academic Year
                                    <span class="ce-required">*</span>
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-calendar3 ce-input-icon"></i>

                                    <input type="text"
                                           id="academic_year"
                                           name="academic_year"
                                           class="ce-form-control ce-input-with-icon"
                                           value="{{ old('academic_year', '2026-2027') }}"
                                           placeholder="2026-2027"
                                           maxlength="20"
                                           required>

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Example: 2026-2027
                                </small>

                            </div>

                        </div>


                        {{-- EXAM NAME --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="exam_name" class="ce-field-label">
                                    Exam Name
                                    <span class="ce-required">*</span>
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-pencil-square ce-input-icon"></i>

                                    <input type="text"
                                           id="exam_name"
                                           name="exam_name"
                                           class="ce-form-control ce-input-with-icon"
                                           value="{{ old('exam_name') }}"
                                           placeholder="Mid Term Examination"
                                           maxlength="150"
                                           required>

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Enter the full name of the examination.
                                </small>

                            </div>

                        </div>


                        {{-- EXAM TYPE --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="exam_type" class="ce-field-label">
                                    Exam Type
                                    <span class="ce-required">*</span>
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-bookmark-star ce-input-icon"></i>

                                    <input type="text"
                                           id="exam_type"
                                           name="exam_type"
                                           class="ce-form-control ce-input-with-icon"
                                           value="{{ old('exam_type') }}"
                                           placeholder="e.g. Unit Test 1, Mid Term, Annual"
                                           maxlength="100"
                                           required>

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Enter any examination category. This is a text field, not a dropdown.
                                </small>

                            </div>

                        </div>


                        {{-- STATUS --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="status" class="ce-field-label">
                                    Status
                                    <span class="ce-required">*</span>
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-flag ce-input-icon"></i>

                                    <select id="status"
                                            name="status"
                                            class="ce-form-select ce-input-with-icon"
                                            required>

                                        <option value="draft"
                                            {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>

                                        <option value="scheduled"
                                            {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                                            Scheduled
                                        </option>

                                        <option value="completed"
                                            {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>

                                        <option value="cancelled"
                                            {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>

                                    </select>

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    You can update the examination status later.
                                </small>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    EXAMINATION PERIOD
                ================================================== --}}
                <div class="ce-form-section">

                    <div class="ce-section-heading">

                        <i class="bi bi-calendar-range"></i>

                        <div>
                            <h3 class="ce-section-title">
                                Examination Period
                            </h3>

                            <p class="ce-section-description">
                                Set the starting and ending dates for the examination.
                            </p>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- START DATE --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="start_date" class="ce-field-label">
                                    Start Date
                                    <span class="ce-required">*</span>
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-calendar-event ce-input-icon"></i>

                                    <input type="date"
                                           name="start_date"
                                           id="start_date"
                                           class="ce-form-control ce-input-with-icon"
                                           value="{{ old('start_date') }}"
                                           required>

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    Initial date on which the examination begins.
                                </small>

                            </div>

                        </div>


                        {{-- END DATE --}}
                        <div class="col-lg-6">

                            <div class="ce-field">

                                <label for="end_date" class="ce-field-label">
                                    End Date
                                </label>

                                <div class="ce-input-wrap">

                                    <i class="bi bi-calendar-check ce-input-icon"></i>

                                    <input type="date"
                                           name="end_date"
                                           id="end_date"
                                           class="ce-form-control ce-input-with-icon"
                                           value="{{ old('end_date') }}">

                                </div>

                                <small class="ce-field-help">
                                    <i class="bi bi-info-circle"></i>
                                    The timetable generator can extend this period when required.
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- DATE PREVIEW --}}
                    <div class="ce-date-preview"
                         id="datePreview"
                         aria-live="polite">

                        <div class="ce-date-preview-icon">
                            <i class="bi bi-calendar-range"></i>
                        </div>

                        <div class="ce-date-preview-content">

                            <div class="ce-date-preview-label">
                                Examination Period
                            </div>

                            <div class="ce-date-preview-value"
                                 id="datePreviewText"></div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    NEXT STEPS
                ================================================== --}}
                <div class="ce-next-steps">

                    <div class="ce-next-steps-header">

                        <div class="ce-next-steps-icon">
                            <i class="bi bi-signpost-split"></i>
                        </div>

                        <div>

                            <h3 class="ce-next-steps-title">
                                What Happens Next?
                            </h3>

                            <p class="ce-next-steps-subtitle">
                                Continue setting up your examination after creating it.
                            </p>

                        </div>

                    </div>


                    <div class="ce-next-steps-body">

                        <div class="ce-step-item">

                            <div class="ce-step-number">1</div>

                            <div class="ce-step-text">
                                <strong>Create the examination.</strong>
                                Save the basic information and examination dates.
                            </div>

                        </div>

                        <div class="ce-step-item">

                            <div class="ce-step-number">2</div>

                            <div class="ce-step-text">
                                <strong>Configure classes and subjects.</strong>
                                Set up the classes and subjects associated with the examination.
                            </div>

                        </div>

                        <div class="ce-step-item">

                            <div class="ce-step-number">3</div>

                            <div class="ce-step-text">
                                <strong>Generate the timetable.</strong>
                                Configure paper slots and generate the schedule using your existing timetable-generation workflow.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION BAR
                ================================================== --}}
                <div class="ce-action-bar">

                    <div class="ce-action-note">
                        <i class="bi bi-shield-check"></i>
                        Review the details before creating the examination.
                    </div>

                    <a href="{{ route('admin.exams.index') }}"
                       class="ce-btn ce-btn-outline">

                        <i class="bi bi-x-circle"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="ce-btn ce-btn-primary"
                            id="createExamBtn">

                        <i class="bi bi-check-circle"></i>
                        Create Exam

                    </button>

                </div>

            </form>

        </div>

    </section>

</div>


{{-- =========================================================
    DATE PREVIEW AND FORM SUBMISSION SCRIPT
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const datePreview = document.getElementById('datePreview');
    const datePreviewText = document.getElementById('datePreviewText');

    const form = document.getElementById('createExamForm');
    const submitButton = document.getElementById('createExamBtn');

    if (!startDate || !endDate || !datePreview || !datePreviewText || !form) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Format a local date without timezone shifting
    |--------------------------------------------------------------------------
    */
    function parseLocalDate(value) {

        if (!value) {
            return null;
        }

        const parts = value.split('-').map(Number);

        if (parts.length !== 3 || parts.some(Number.isNaN)) {
            return null;
        }

        return new Date(parts[0], parts[1] - 1, parts[2]);
    }

    function formatDate(date) {

        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Update the examination date preview
    |--------------------------------------------------------------------------
    */
    function updateDatePreview() {

        const start = parseLocalDate(startDate.value);
        const end = parseLocalDate(endDate.value);

        if (!start) {
            datePreview.classList.remove('show');
            datePreviewText.textContent = '';
            return;
        }

        let text = 'Starts on ' + formatDate(start);

        if (end) {

            if (end >= start) {

                const difference =
                    Math.round((end - start) / 86400000) + 1;

                text =
                    formatDate(start)
                    + ' → '
                    + formatDate(end)
                    + ' · '
                    + difference
                    + (difference === 1 ? ' day' : ' days');

            } else {

                text =
                    formatDate(start)
                    + ' → Please select an end date on or after the start date.';

            }

        }

        datePreviewText.textContent = text;
        datePreview.classList.add('show');

    }

    /*
    |--------------------------------------------------------------------------
    | Validate date ordering and update the minimum end date
    |--------------------------------------------------------------------------
    */
    function updateDateConstraints() {

        if (startDate.value) {

            endDate.min = startDate.value;

            if (endDate.value && endDate.value < startDate.value) {
                endDate.value = '';
            }

        } else {

            endDate.removeAttribute('min');

        }

        updateDatePreview();

    }

    startDate.addEventListener('change', updateDateConstraints);
    endDate.addEventListener('change', updateDatePreview);

    /*
    |--------------------------------------------------------------------------
    | Initialize preview for old input after validation redirects
    |--------------------------------------------------------------------------
    */
    updateDateConstraints();

    /*
    |--------------------------------------------------------------------------
    | Prevent duplicate submissions
    |--------------------------------------------------------------------------
    */
    let submissionStarted = false;

    form.addEventListener('submit', function (event) {

        if (submissionStarted) {
            event.preventDefault();
            return;
        }

        if (!form.checkValidity()) {
            return;
        }

        /*
         * Extra guard in case a date is populated without normal UI interaction.
         */
        if (
            startDate.value &&
            endDate.value &&
            endDate.value < startDate.value
        ) {

            event.preventDefault();

            endDate.setCustomValidity(
                'End date must be on or after the start date.'
            );

            endDate.reportValidity();
            endDate.focus();

            return;

        }

        endDate.setCustomValidity('');

        submissionStarted = true;

        if (submitButton) {

            submitButton.disabled = true;

            submitButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" '
                + 'role="status" aria-hidden="true"></span> '
                + 'Creating Exam...';

        }

    });

    endDate.addEventListener('input', function () {
        endDate.setCustomValidity('');
    });

});
</script>

@endsection
