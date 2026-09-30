
@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')

<style>
    :root {
        --primary-blue: #1677f0;
        --primary-dark: #0f5fc4;
        --soft-blue: #eef6ff;
        --border-color: #e5eaf0;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --success: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
    }

    .create-exam-page {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */
    .page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f7fbff 100%);
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    .page-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .page-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        flex-shrink: 0;
    }

    .page-title {
        color: var(--text-dark);
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-size: 13px;
        margin: 4px 0 0;
    }

    .back-btn {
        border: 1px solid #d9e1ea;
        background: #fff;
        color: #4b5563;
        font-weight: 600;
        border-radius: 10px;
        padding: 9px 15px;
        transition: all .2s ease;
    }

    .back-btn:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        background: var(--soft-blue);
    }

    /* =========================================================
       ERROR ALERT
    ========================================================= */
    .error-card {
        border: 0;
        border-left: 4px solid var(--danger);
        border-radius: 12px;
        background: #fff5f5;
        color: #842029;
        box-shadow: 0 3px 12px rgba(220, 53, 69, .06);
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */
    .exam-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 5px 24px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .exam-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
        background: #fff;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-dark);
        font-size: 17px;
        font-weight: 700;
        margin: 0;
    }

    .section-title-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .section-description {
        color: var(--text-muted);
        font-size: 12px;
        margin: 5px 0 0 44px;
    }

    .exam-card-body {
        padding: 28px 24px;
    }

    /* =========================================================
       FORM
    ========================================================= */
    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .field-label {
        color: #374151;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 8px;
    }

    .required-star {
        color: var(--danger);
    }

    .input-wrap {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #8b98a9;
        font-size: 16px;
        z-index: 2;
        pointer-events: none;
    }

    .form-control,
    .form-select {
        min-height: 46px;
        border: 1px solid #dce3eb;
        border-radius: 10px;
        color: #263244;
        font-size: 14px;
        padding: 10px 13px;
        box-shadow: none;
        transition: all .2s ease;
    }

    .input-with-icon {
        padding-left: 40px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(22, 119, 240, .10);
    }

    .form-control::placeholder {
        color: #a1aab6;
    }

    .field-help {
        display: block;
        margin-top: 6px;
        color: #8993a1;
        font-size: 11.5px;
    }

    /* =========================================================
       DATE PREVIEW
    ========================================================= */
    .date-preview {
        display: none;
        margin-top: 20px;
        padding: 14px 16px;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        background: #f7fbff;
    }

    .date-preview.show {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .date-preview-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #e7f1ff;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .date-preview-label {
        color: #7b8794;
        font-size: 11px;
        margin-bottom: 2px;
    }

    .date-preview-value {
        color: #253041;
        font-size: 13px;
        font-weight: 600;
    }

    /* =========================================================
       INFORMATION CARD
    ========================================================= */
    .next-steps-card {
        margin-top: 28px;
        border: 1px solid #d9eaff;
        border-radius: 14px;
        background: linear-gradient(135deg, #f5faff 0%, #ffffff 100%);
        overflow: hidden;
    }

    .next-steps-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 18px;
        border-bottom: 1px solid #e1efff;
        color: #165ca8;
        font-weight: 700;
        font-size: 13px;
    }

    .next-steps-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #e4f1ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-blue);
    }

    .next-steps-body {
        padding: 17px 18px;
    }

    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 13px;
    }

    .step-item:last-child {
        margin-bottom: 0;
    }

    .step-number {
        width: 25px;
        height: 25px;
        min-width: 25px;
        border-radius: 50%;
        background: #eaf3ff;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
    }

    .step-text {
        color: #596575;
        font-size: 12.5px;
        line-height: 1.6;
        padding-top: 2px;
    }

    /* =========================================================
       ACTION BAR
    ========================================================= */
    .action-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 28px;
        padding-top: 22px;
        border-top: 1px solid var(--border-color);
    }

    .btn-cancel {
        min-height: 44px;
        padding: 9px 18px;
        border-radius: 10px;
        border: 1px solid #dce2e8;
        background: #fff;
        color: #596575;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #374151;
    }

    .btn-create {
        min-height: 44px;
        padding: 9px 20px;
        border-radius: 10px;
        border: 0;
        background: var(--primary-blue);
        color: #fff;
        font-size: 13px;
        font-weight: 650;
        box-shadow: 0 4px 12px rgba(22, 119, 240, .20);
        transition: all .2s ease;
    }

    .btn-create:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(22, 119, 240, .25);
    }

    .btn-create:disabled {
        opacity: .75;
        cursor: not-allowed;
        transform: none;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */
    @media (max-width: 767.98px) {
        .page-header {
            padding: 18px;
        }

        .page-header-inner {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 15px;
        }

        .back-btn {
            width: 100%;
            text-align: center;
        }

        .page-title {
            font-size: 19px;
        }

        .exam-card-body {
            padding: 22px 16px;
        }

        .exam-card-header {
            padding: 17px 16px;
        }

        .action-bar {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-create {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="create-exam-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}
        <div class="page-header">
            <div class="page-header-inner d-flex justify-content-between align-items-center">

                <div class="page-title-wrap">
                    <div class="page-icon">
                        <i class="bi bi-journal-plus"></i>
                    </div>

                    <div>
                        <h4 class="page-title">
                            Create Exam
                        </h4>

                        <p class="page-subtitle">
                            Create the basic examination information to get started.
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.exams.index') }}"
                   class="btn back-btn">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Exams
                </a>

            </div>
        </div>

        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}
        @if ($errors->any())
            <div class="alert error-card mb-4">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                    <div>
                        <div class="fw-semibold mb-2">
                            Please correct the following errors:
                        </div>

                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li class="mb-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- =====================================================
             MAIN FORM CARD
        ====================================================== --}}
        <div class="exam-card">

            {{-- Card Header --}}
            <div class="exam-card-header">

                <h5 class="section-title">
                    <span class="section-title-icon">
                        <i class="bi bi-info-circle"></i>
                    </span>

                    Exam Information
                </h5>

                <p class="section-description">
                    Enter the basic details of the examination.
                </p>

            </div>

            {{-- Card Body --}}
            <div class="exam-card-body">

                <form method="POST"
                      action="{{ route('admin.exams.store') }}"
                      id="createExamForm">

                    @csrf

                    {{-- =================================================
                         BASIC INFORMATION
                    ================================================== --}}
                    <div class="form-section">

                        <div class="row g-4">

                            {{-- Academic Year --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Academic Year
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-calendar3 input-icon"></i>

                                    <input type="text"
                                           name="academic_year"
                                           class="form-control input-with-icon"
                                           value="{{ old('academic_year', '2026-2027') }}"
                                           placeholder="2026-2027"
                                           required>

                                </div>

                                <small class="field-help">
                                    Example: 2026-2027
                                </small>

                            </div>

                            {{-- Exam Name --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Exam Name
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-pencil-square input-icon"></i>

                                    <input type="text"
                                           name="exam_name"
                                           class="form-control input-with-icon"
                                           value="{{ old('exam_name') }}"
                                           placeholder="Mid Term Examination"
                                           required>

                                </div>

                                <small class="field-help">
                                    Enter a clear name for this examination.
                                </small>

                            </div>

                            {{-- Exam Type --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Exam Type
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-bookmark-star input-icon"></i>

                                    <select name="exam_type"
                                            class="form-select input-with-icon"
                                            required>

                                        <option value="">
                                            Select Exam Type
                                        </option>

                                        <option value="Unit Test"
                                            {{ old('exam_type') == 'Unit Test' ? 'selected' : '' }}>
                                            Unit Test
                                        </option>

                                        <option value="Terminal"
                                            {{ old('exam_type') == 'Terminal' ? 'selected' : '' }}>
                                            Terminal
                                        </option>

                                        <option value="Mid Term"
                                            {{ old('exam_type') == 'Mid Term' ? 'selected' : '' }}>
                                            Mid Term
                                        </option>

                                        <option value="Preliminary"
                                            {{ old('exam_type') == 'Preliminary' ? 'selected' : '' }}>
                                            Preliminary
                                        </option>

                                        <option value="Annual"
                                            {{ old('exam_type') == 'Annual' ? 'selected' : '' }}>
                                            Annual
                                        </option>

                                        <option value="Final"
                                            {{ old('exam_type') == 'Final' ? 'selected' : '' }}>
                                            Final
                                        </option>

                                        <option value="Other"
                                            {{ old('exam_type') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                </div>

                                <small class="field-help">
                                    Select the category of the examination.
                                </small>

                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Status
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-flag input-icon"></i>

                                    <select name="status"
                                            class="form-select input-with-icon"
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

                                <small class="field-help">
                                    You can update the examination status later.
                                </small>

                            </div>

                        </div>

                    </div>

                    {{-- =================================================
                         EXAM PERIOD
                    ================================================== --}}
                    <div class="form-section">

                        <div class="row g-4">

                            {{-- Start Date --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Start Date
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-calendar-event input-icon"></i>

                                    <input type="date"
                                           name="start_date"
                                           id="start_date"
                                           class="form-control input-with-icon"
                                           value="{{ old('start_date') }}"
                                           required>

                                </div>

                                <small class="field-help">
                                    Initial date on which the examination begins.
                                </small>

                            </div>

                            {{-- End Date --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    End Date
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-calendar-check input-icon"></i>

                                    <input type="date"
                                           name="end_date"
                                           id="end_date"
                                           class="form-control input-with-icon"
                                           value="{{ old('end_date') }}">

                                </div>

                                <small class="field-help">
                                    The timetable generator can extend this period when required.
                                </small>

                            </div>

                        </div>

                        {{-- Date Preview --}}
                        <div class="date-preview" id="datePreview">

                            <div class="date-preview-icon">
                                <i class="bi bi-calendar-range"></i>
                            </div>

                            <div>
                                <div class="date-preview-label">
                                    Examination Period
                                </div>

                                <div class="date-preview-value"
                                     id="datePreviewText">
                                </div>
                            </div>

                        </div>

                    </div>

                   

                    {{-- =================================================
                         ACTION BAR
                    ================================================== --}}
                    <div class="action-bar">

                        <a href="{{ route('admin.exams.index') }}"
                           class="btn btn-cancel">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-create"
                                id="createExamBtn">

                            <i class="bi bi-check-circle me-1"></i>
                            Create Exam

                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const datePreview = document.getElementById('datePreview');
    const datePreviewText = document.getElementById('datePreviewText');

    const form = document.getElementById('createExamForm');
    const submitButton = document.getElementById('createExamBtn');

    /*
    |--------------------------------------------------------------------------
    | Date Preview
    |--------------------------------------------------------------------------
    */
    function updateDatePreview() {

        if (!startDate.value) {
            datePreview.classList.remove('show');
            return;
        }

        const start = new Date(startDate.value + 'T00:00:00');

        let text = 'Starts on ' + formatDate(start);

        if (endDate.value) {

            const end = new Date(endDate.value + 'T00:00:00');

            if (end >= start) {

                const difference =
                    Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;

                text =
                    formatDate(start) +
                    ' → ' +
                    formatDate(end) +
                    ' • ' +
                    difference +
                    (difference === 1 ? ' day' : ' days');

            }
        }

        datePreviewText.textContent = text;
        datePreview.classList.add('show');
    }

    function formatDate(date) {

        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

    }

    startDate.addEventListener('change', function () {

        /*
         * Prevent an end date earlier than the start date.
         */
        if (startDate.value) {
            endDate.min = startDate.value;

            if (endDate.value && endDate.value < startDate.value) {
                endDate.value = '';
            }
        }

        updateDatePreview();
    });

    endDate.addEventListener('change', function () {

        if (
            startDate.value &&
            endDate.value &&
            endDate.value < startDate.value
        ) {
            endDate.value = startDate.value;
        }

        updateDatePreview();
    });

    /*
    |--------------------------------------------------------------------------
    | Initial Preview
    |--------------------------------------------------------------------------
    */
    if (startDate.value) {
        endDate.min = startDate.value;
        updateDatePreview();
    }

    /*
    |--------------------------------------------------------------------------
    | Prevent Double Submit
    |--------------------------------------------------------------------------
    */
    form.addEventListener('submit', function () {

        submitButton.disabled = true;

        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" ' +
            'role="status" aria-hidden="true"></span>' +
            'Creating Exam...';

    });

});
</script>

@endsection

