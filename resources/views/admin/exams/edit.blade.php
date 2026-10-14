
@extends('layouts.app')

@section('title', 'Edit Exam')

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

    .edit-exam-page {
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

    .page-header-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
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
        font-size: 22px;
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
        font-size: 13px;
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
       EXAM SUMMARY
    ========================================================= */
    .exam-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
    }

    .summary-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .summary-label {
        font-size: 10px;
        color: #8993a1;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 2px;
    }

    .summary-value {
        color: #273142;
        font-size: 13px;
        font-weight: 650;
    }

    /* =========================================================
       MAIN FORM CARD
    ========================================================= */
    .exam-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 5px 24px rgba(15, 23, 42, .05);
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

    .invalid-feedback {
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
       SETUP INFORMATION
    ========================================================= */
    .setup-card {
        margin-top: 24px;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .setup-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--border-color);
    }

    .setup-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .setup-title {
        color: var(--text-dark);
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .setup-subtitle {
        color: var(--text-muted);
        font-size: 11px;
        margin: 2px 0 0;
    }

    .setup-body {
        padding: 20px 22px;
    }

    .setup-item {
        height: 100%;
        padding: 15px;
        border: 1px solid #e7ebf0;
        border-radius: 12px;
        background: #fbfcfe;
        transition: all .2s ease;
    }

    .setup-item:hover {
        border-color: #cfe2ff;
        background: #f8fbff;
        transform: translateY(-1px);
    }

    .setup-item-top {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 9px;
    }

    .setup-item-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eef6ff;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .setup-label {
        color: #7b8794;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .45px;
        margin-bottom: 2px;
    }

    .setup-value {
        color: #273142;
        font-size: 12.5px;
        font-weight: 650;
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

    .btn-update {
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

    .btn-update:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(22, 119, 240, .25);
    }

    .btn-update:disabled {
        opacity: .75;
        cursor: not-allowed;
        transform: none;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */
    @media (max-width: 991.98px) {
        .exam-summary {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .page-header {
            padding: 18px;
        }

        .page-header-inner {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-btn {
            width: 100%;
            text-align: center;
        }

        .page-title {
            font-size: 19px;
        }

        .exam-summary {
            grid-template-columns: 1fr;
        }

        .exam-card-header {
            padding: 17px 16px;
        }

        .exam-card-body {
            padding: 22px 16px;
        }

        .setup-body {
            padding: 16px;
        }

        .action-bar {
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-update {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <div class="edit-exam-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}
        <div class="page-header">

            <div class="page-header-inner">

                <div class="page-title-wrap">

                    <div class="page-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>
                        <h4 class="page-title">
                            Edit Exam
                        </h4>

                        <p class="page-subtitle">
                            Update the examination information and schedule.
                        </p>
                    </div>

                </div>

                <a href="{{ route('admin.exams.show', $exam) }}"
                   class="btn back-btn">
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Exam
                </a>

            </div>

        </div>

        {{-- =====================================================
             EXAM SUMMARY
        ====================================================== --}}
        <div class="exam-summary">

            {{-- Academic Year --}}
            <div class="summary-item">

                <div class="summary-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Academic Year
                    </div>

                    <div class="summary-value">
                        {{ $exam->academic_year }}
                    </div>
                </div>

            </div>

            {{-- Exam Type --}}
            <div class="summary-item">

                <div class="summary-icon">
                    <i class="bi bi-bookmark-star"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Exam Type
                    </div>

                    <div class="summary-value">
                        {{ $exam->exam_type ?: 'Not specified' }}
                    </div>
                </div>

            </div>

            {{-- Start Date --}}
            <div class="summary-item">

                <div class="summary-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Start Date
                    </div>

                    <div class="summary-value">
                        {{ $exam->start_date?->format('d M Y') ?? 'Not set' }}
                    </div>
                </div>

            </div>

            {{-- Status --}}
            <div class="summary-item">

                <div class="summary-icon">
                    <i class="bi bi-flag"></i>
                </div>

                <div>
                    <div class="summary-label">
                        Current Status
                    </div>

                    <div class="summary-value">
                        {{ ucfirst($exam->status ?? 'draft') }}
                    </div>
                </div>

            </div>

        </div>

        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}
        @if($errors->any())

            <div class="alert error-card mb-4">

                <div class="d-flex align-items-start">

                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>

                    <div>

                        <div class="fw-semibold mb-2">
                            Please fix the following errors:
                        </div>

                        <ul class="mb-0 ps-3">

                            @foreach($errors->all() as $error)
                                <li class="mb-1">
                                    {{ $error }}
                                </li>
                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif

        {{-- =====================================================
             EDIT FORM
        ====================================================== --}}
        <div class="exam-card">

            {{-- Card Header --}}
            <div class="exam-card-header">

                <h5 class="section-title">

                    <span class="section-title-icon">
                        <i class="bi bi-calendar-event"></i>
                    </span>

                    Exam Information

                </h5>

                <p class="section-description">
                    Update the basic details, status and examination period.
                </p>

            </div>

            {{-- Card Body --}}
            <div class="exam-card-body">

                <form
                    method="POST"
                    action="{{ route('admin.exams.update', $exam) }}"
                    id="editExamForm"
                >

                    @csrf
                    @method('PUT')

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

                                    <input
                                        type="text"
                                        name="academic_year"
                                        class="form-control input-with-icon @error('academic_year') is-invalid @enderror"
                                        value="{{ old('academic_year', $exam->academic_year) }}"
                                        placeholder="2026-2027"
                                        required
                                    >

                                </div>

                                @error('academic_year')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        Example: 2026-2027
                                    </small>
                                @enderror

                            </div>

                            {{-- Exam Name --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Exam Name
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-pencil-square input-icon"></i>

                                    <input
                                        type="text"
                                        name="exam_name"
                                        class="form-control input-with-icon @error('exam_name') is-invalid @enderror"
                                        value="{{ old('exam_name', $exam->exam_name) }}"
                                        placeholder="Mid Term Examination"
                                        required
                                    >

                                </div>

                                @error('exam_name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        Enter the official examination name.
                                    </small>
                                @enderror

                            </div>

                            {{-- Exam Type --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Exam Type
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-bookmark-star input-icon"></i>

                                    <select
                                        name="exam_type"
                                        class="form-select input-with-icon @error('exam_type') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Exam Type
                                        </option>

                                        @php
                                            $examTypes = [
                                                'Unit Test',
                                                'First Term',
                                                'Mid Term',
                                                'Second Term',
                                                'Annual Examination',
                                                'Preliminary',
                                                'Pre-Board',
                                                'Final Examination',
                                                'Other',
                                            ];
                                        @endphp

                                        @foreach($examTypes as $type)

                                            <option
                                                value="{{ $type }}"
                                                @selected(old('exam_type', $exam->exam_type) === $type)
                                            >
                                                {{ $type }}
                                            </option>

                                        @endforeach

                                        {{-- Keep custom existing value if it is not in the list --}}
                                        @if(
                                            $exam->exam_type &&
                                            !in_array($exam->exam_type, $examTypes)
                                        )

                                            <option
                                                value="{{ $exam->exam_type }}"
                                                selected
                                            >
                                                {{ $exam->exam_type }}
                                            </option>

                                        @endif

                                    </select>

                                </div>

                                @error('exam_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        Select the category of the examination.
                                    </small>
                                @enderror

                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    Status
                                    <span class="required-star">*</span>
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-flag input-icon"></i>

                                    <select
                                        name="status"
                                        class="form-select input-with-icon @error('status') is-invalid @enderror"
                                        required
                                    >

                                        <option
                                            value="draft"
                                            @selected(old('status', $exam->status) === 'draft')
                                        >
                                            Draft
                                        </option>

                                        <option
                                            value="scheduled"
                                            @selected(old('status', $exam->status) === 'scheduled')
                                        >
                                            Scheduled
                                        </option>

                                        <option
                                            value="completed"
                                            @selected(old('status', $exam->status) === 'completed')
                                        >
                                            Completed
                                        </option>

                                        <option
                                            value="cancelled"
                                            @selected(old('status', $exam->status) === 'cancelled')
                                        >
                                            Cancelled
                                        </option>

                                    </select>

                                </div>

                                @error('status')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        Update the current examination status.
                                    </small>
                                @enderror

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

                                    <input
                                        type="date"
                                        name="start_date"
                                        id="start_date"
                                        class="form-control input-with-icon @error('start_date') is-invalid @enderror"
                                        value="{{ old(
                                            'start_date',
                                            $exam->start_date?->format('Y-m-d')
                                        ) }}"
                                        required
                                    >

                                </div>

                                @error('start_date')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        Initial date on which the examination begins.
                                    </small>
                                @enderror

                            </div>

                            {{-- End Date --}}
                            <div class="col-lg-6">

                                <label class="field-label">
                                    End Date
                                </label>

                                <div class="input-wrap">

                                    <i class="bi bi-calendar-check input-icon"></i>

                                    <input
                                        type="date"
                                        name="end_date"
                                        id="end_date"
                                        class="form-control input-with-icon @error('end_date') is-invalid @enderror"
                                        value="{{ old(
                                            'end_date',
                                            $exam->end_date?->format('Y-m-d')
                                        ) }}"
                                    >

                                </div>

                                @error('end_date')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @else
                                    <small class="field-help">
                                        The timetable generator can update this date
                                        according to the generated timetable.
                                    </small>
                                @enderror

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

                        <a
                            href="{{ route('admin.exams.show', $exam) }}"
                            class="btn btn-cancel"
                        >
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-update"
                            id="updateExamBtn"
                        >
                            <i class="bi bi-check-circle me-1"></i>
                            Update Exam
                        </button>

                    </div>

                </form>

            </div>

        </div>

        {{-- =====================================================
             EXAM SETUP INFORMATION
        ====================================================== --}}
        <div class="setup-card">

            <div class="setup-header">

                <div class="setup-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>

                <div>

                    <h6 class="setup-title">
                        Exam Setup
                    </h6>

                    <p class="setup-subtitle">
                        Configure the remaining examination components from Exam Details.
                    </p>

                </div>

            </div>

            <div class="setup-body">

                <div class="row g-3">

                    {{-- Classes --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="setup-item">

                            <div class="setup-item-top">

                                <div class="setup-item-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <div>
                                    <div class="setup-label">
                                        Classes
                                    </div>

                                    <div class="setup-value">
                                        Exam Classes
                                    </div>
                                </div>

                            </div>

                            <div class="small text-muted">
                                Select which classes will participate in this exam.
                            </div>

                        </div>

                    </div>

                    {{-- Subjects --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="setup-item">

                            <div class="setup-item-top">

                                <div class="setup-item-icon">
                                    <i class="bi bi-journal-text"></i>
                                </div>

                                <div>
                                    <div class="setup-label">
                                        Subjects
                                    </div>

                                    <div class="setup-value">
                                        Marks & Duration
                                    </div>
                                </div>

                            </div>

                            <div class="small text-muted">
                                Configure maximum marks, passing marks and duration.
                            </div>

                        </div>

                    </div>

                    {{-- Sessions --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="setup-item">

                            <div class="setup-item-top">

                                <div class="setup-item-icon">
                                    <i class="bi bi-clock"></i>
                                </div>

                                <div>
                                    <div class="setup-label">
                                        Sessions
                                    </div>

                                    <div class="setup-value">
                                        Exam Timing
                                    </div>
                                </div>

                            </div>

                            <div class="small text-muted">
                                Configure morning, afternoon or other exam sessions.
                            </div>

                        </div>

                    </div>

                    {{-- Holidays --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="setup-item">

                            <div class="setup-item-top">

                                <div class="setup-item-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>

                                <div>
                                    <div class="setup-label">
                                        Holidays
                                    </div>

                                    <div class="setup-value">
                                        Non-working Days
                                    </div>
                                </div>

                            </div>

                            <div class="small text-muted">
                                Add holidays that should be skipped by the timetable.
                            </div>

                        </div>

                    </div>

                </div>

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

    const form = document.getElementById('editExamForm');
    const updateButton = document.getElementById('updateExamBtn');

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
                    Math.floor(
                        (end - start) /
                        (1000 * 60 * 60 * 24)
                    ) + 1;

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

    /*
    |--------------------------------------------------------------------------
    | Format Date
    |--------------------------------------------------------------------------
    */
    function formatDate(date) {

        return date.toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });

    }

    /*
    |--------------------------------------------------------------------------
    | Start Date
    |--------------------------------------------------------------------------
    */
    startDate.addEventListener('change', function () {

        if (startDate.value) {

            endDate.min = startDate.value;

            if (
                endDate.value &&
                endDate.value < startDate.value
            ) {
                endDate.value = '';
            }

        }

        updateDatePreview();

    });

    /*
    |--------------------------------------------------------------------------
    | End Date
    |--------------------------------------------------------------------------
    */
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
    | Initial Date Setup
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

        updateButton.disabled = true;

        updateButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2" ' +
            'role="status" aria-hidden="true"></span>' +
            'Updating Exam...';

    });

});
</script>

@endsection

