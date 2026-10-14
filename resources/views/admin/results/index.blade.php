
@extends('layouts.app')

@section('title', 'Result Dashboard')

@section('content')

<style>
    /* =========================================================
       RESULT DASHBOARD
    ========================================================== */

    .result-dashboard {
        --rd-primary: #1677f0;
        --rd-primary-dark: #0f5fc4;
        --rd-primary-soft: #eef6ff;

        --rd-success: #198754;
        --rd-success-soft: #edf9f2;

        --rd-warning: #b77900;
        --rd-warning-soft: #fff8e6;

        --rd-info: #0d9fc1;
        --rd-info-soft: #eafaff;

        --rd-danger: #dc3545;
        --rd-danger-soft: #fff0f1;

        --rd-text: #172033;
        --rd-muted: #667085;
        --rd-border: #e5eaf0;
        --rd-soft: #f7f9fc;

        --rd-radius: 18px;

        color: var(--rd-text);
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .rd-header {
        position: relative;
        overflow: hidden;
        border: 1px solid #dfe8f2;
        border-radius: 20px;
        padding: 25px 26px;
        margin-bottom: 22px;

        background:
            radial-gradient(
                circle at 92% 15%,
                rgba(22,119,240,.10),
                transparent 28%
            ),
            linear-gradient(
                135deg,
                #ffffff 0%,
                #f4f8ff 100%
            );

        box-shadow: 0 8px 30px rgba(15,23,42,.055);
    }

    .rd-header-content {
        position: relative;
        z-index: 2;
    }

    .rd-title-icon {
        width: 58px;
        height: 58px;
        min-width: 58px;
        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            135deg,
            rgba(22,119,240,.14),
            rgba(22,119,240,.06)
        );

        color: var(--rd-primary);
        font-size: 26px;
    }

    .rd-title {
        color: var(--rd-text);
        font-size: 24px;
        font-weight: 750;
        letter-spacing: -.3px;
        margin: 0;
    }

    .rd-subtitle {
        color: var(--rd-muted);
        font-size: 13px;
        margin: 5px 0 0;
    }

    .rd-generate-btn {
        min-height: 44px;
        border: 0;
        border-radius: 11px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 650;

        box-shadow: 0 7px 18px rgba(22,119,240,.20);

        transition: .2s ease;
    }

    .rd-generate-btn:hover {
        transform: translateY(-1px);
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .rd-alert {
        border: 0;
        border-radius: 12px;
        padding: 13px 16px;
        font-size: 13px;
        box-shadow: 0 5px 18px rgba(15,23,42,.045);
    }

    .rd-loading {
        background: #eef7ff;
        color: #145da0;
        border: 1px solid #d8ecff;
    }

    .rd-error {
        background: #fff1f2;
        color: #b42318;
        border: 1px solid #ffd8dc;
    }


    /* =========================================================
       SELECTOR
    ========================================================== */

    .rd-selector-card {
        background: #fff;
        border: 1px solid var(--rd-border);
        border-radius: var(--rd-radius);
        box-shadow: 0 7px 25px rgba(15,23,42,.05);
        overflow: hidden;
    }

    .rd-selector-header {
        padding: 17px 21px;
        border-bottom: 1px solid var(--rd-border);

        background: linear-gradient(
            180deg,
            #ffffff,
            #fbfcfe
        );
    }

    .rd-selector-body {
        padding: 21px;
    }

    .rd-selector-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        background: var(--rd-primary-soft);
        color: var(--rd-primary);
        font-size: 18px;
    }

    .rd-section-title {
        color: var(--rd-text);
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .rd-section-subtitle {
        color: var(--rd-muted);
        font-size: 11px;
        margin-top: 3px;
    }

    .rd-label {
        color: #475467;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .rd-select {
        min-height: 47px;
        border-radius: 11px;
        border-color: #d8e1eb;
        color: var(--rd-text);
        font-size: 13px;
        font-weight: 550;

        box-shadow: none;
        transition: .2s ease;
    }

    .rd-select:focus {
        border-color: var(--rd-primary);
        box-shadow: 0 0 0 .20rem rgba(22,119,240,.10);
    }


    /* =========================================================
       EXAM INFORMATION
    ========================================================== */

    .rd-exam-card {
        background: #fff;
        border: 1px solid var(--rd-border);
        border-radius: var(--rd-radius);
        box-shadow: 0 7px 25px rgba(15,23,42,.05);
        overflow: hidden;
    }

    .rd-exam-body {
        padding: 18px 21px;
    }

    .rd-exam-item {
        height: 100%;
        background: var(--rd-soft);
        border: 1px solid var(--rd-border);
        border-radius: 12px;
        padding: 14px 15px;
    }

    .rd-exam-label {
        color: var(--rd-muted);
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }

    .rd-exam-value {
        color: var(--rd-text);
        font-size: 14px;
        font-weight: 650;
    }


    /* =========================================================
       DYNAMIC STATUS BADGE
    ========================================================== */

    .rd-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        width: fit-content;

        padding: 6px 11px;
        border-radius: 20px;

        font-size: 11px;
        font-weight: 750;
        text-transform: capitalize;
    }

    .rd-status-badge i {
        font-size: 7px;
    }

    .rd-status-scheduled {
        background: #eef6ff;
        color: #1677f0;
    }

    .rd-status-draft {
        background: #f2f4f7;
        color: #667085;
    }

    .rd-status-completed {
        background: #edf9f2;
        color: #198754;
    }

    .rd-status-cancelled {
        background: #fff0f1;
        color: #dc3545;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .rd-summary-card {
        position: relative;
        height: 100%;
        overflow: hidden;

        background: #fff;
        border: 1px solid var(--rd-border);
        border-radius: 15px;

        box-shadow: 0 6px 22px rgba(15,23,42,.045);

        transition: .2s ease;
    }

    .rd-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(15,23,42,.08);
    }

    .rd-summary-body {
        position: relative;
        z-index: 1;
        padding: 18px;
    }

    .rd-summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 13px;
        font-size: 18px;
    }

    .rd-summary-total .rd-summary-icon {
        background: var(--rd-primary-soft);
        color: var(--rd-primary);
    }

    .rd-summary-generated .rd-summary-icon {
        background: #edf4ff;
        color: #0d6efd;
    }

    .rd-summary-approved .rd-summary-icon {
        background: var(--rd-warning-soft);
        color: var(--rd-warning);
    }

    .rd-summary-published .rd-summary-icon {
        background: var(--rd-success-soft);
        color: var(--rd-success);
    }

    .rd-summary-label {
        color: var(--rd-muted);
        font-size: 11px;
        font-weight: 650;
    }

    .rd-summary-value {
        color: var(--rd-text);
        font-size: 26px;
        line-height: 1.1;
        font-weight: 750;
        margin-top: 4px;
    }


    /* =========================================================
       SUMMARY PROGRESS
    ========================================================== */

    .rd-overall-progress {
        margin-top: 13px;
    }

    .rd-overall-progress-label {
        color: var(--rd-muted);
        font-size: 10px;
        font-weight: 650;
    }

    .rd-overall-progress-value {
        color: var(--rd-success);
        font-size: 10px;
        font-weight: 750;
    }

    .rd-overall-progress-bar {
        height: 6px;
        overflow: hidden;
        border-radius: 20px;
        background: #e9eef4;
    }

    .rd-overall-progress-bar .progress-bar {
        border-radius: 20px;
        transition: width .5s ease;
    }


    /* =========================================================
       CLASS HEADER
    ========================================================== */

    .rd-class-heading {
        margin-top: 5px;
        margin-bottom: 18px;
    }

    .rd-class-heading-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        background: var(--rd-primary-soft);
        color: var(--rd-primary);
        font-size: 19px;
    }

    .rd-class-title {
        color: var(--rd-text);
        font-size: 17px;
        font-weight: 750;
        margin: 0;
    }

    .rd-class-description {
        color: var(--rd-muted);
        font-size: 12px;
        margin: 3px 0 0;
    }


    /* =========================================================
       CLASS CARD
    ========================================================== */

    .rd-class-card {
        position: relative;
        height: 100%;
        overflow: hidden;

        background: #fff;
        border: 1px solid var(--rd-border);
        border-radius: 17px;

        box-shadow: 0 7px 25px rgba(15,23,42,.055);

        transition: .22s ease;
    }

    .rd-class-card:hover {
        transform: translateY(-4px);
        border-color: rgba(22,119,240,.18);
        box-shadow: 0 15px 34px rgba(15,23,42,.095);
    }

    .rd-class-card::before {
        content: "";
        position: absolute;

        left: 0;
        right: 0;
        top: 0;

        height: 3px;

        background: var(--rd-primary);
    }

    .rd-class-card.status-completed::before {
        background: var(--rd-success);
    }

    .rd-class-card.status-progress::before {
        background: var(--rd-warning);
    }

    .rd-class-card.status-pending::before {
        background: var(--rd-danger);
    }

    .rd-class-top {
        padding: 21px 20px 18px;
    }

    .rd-class-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--rd-primary-soft);
        color: var(--rd-primary);
        font-size: 19px;
    }

    .rd-class-name {
        color: var(--rd-text);
        font-size: 16px;
        font-weight: 750;
        line-height: 1.25;
        margin: 0;
    }

    .rd-class-year {
        color: var(--rd-muted);
        font-size: 11px;
        margin-top: 5px;
    }

    .rd-student-badge {
        padding: 6px 10px;
        border-radius: 20px;

        background: var(--rd-primary-soft);
        color: var(--rd-primary);

        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }


    /* =========================================================
       CLASS STATUS
    ========================================================== */

    .rd-class-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        margin-top: 12px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 10px;
        font-weight: 750;
    }

    .rd-class-status.completed {
        color: var(--rd-success);
        background: var(--rd-success-soft);
    }

    .rd-class-status.progress {
        color: var(--rd-warning);
        background: var(--rd-warning-soft);
    }

    .rd-class-status.pending {
        color: var(--rd-danger);
        background: var(--rd-danger-soft);
    }

    .rd-class-status.empty {
        color: var(--rd-muted);
        background: #f2f4f7;
    }


    /* =========================================================
       STATISTICS
    ========================================================== */

    .rd-stat {
        position: relative;

        background: #f9fafc;
        border: 1px solid #edf1f5;
        border-radius: 11px;

        padding: 11px 8px;
        text-align: center;

        height: 100%;
    }

    .rd-stat-label {
        display: block;
        color: var(--rd-muted);
        font-size: 10px;
        font-weight: 650;
        margin-bottom: 4px;
    }

    .rd-stat-value {
        display: block;
        font-size: 18px;
        line-height: 1.1;
        font-weight: 750;
    }

    .rd-stat-generated {
        color: #0d6efd;
    }

    .rd-stat-verified {
        color: var(--rd-info);
    }

    .rd-stat-approved {
        color: var(--rd-warning);
    }

    .rd-stat-published {
        color: var(--rd-success);
    }


    /* =========================================================
       PUBLICATION PROGRESS
    ========================================================== */

    .rd-progress-area {
        margin-top: 19px;
    }

    .rd-progress-label {
        color: var(--rd-muted);
        font-size: 11px;
        font-weight: 650;
    }

    .rd-progress-value {
        color: var(--rd-text);
        font-size: 11px;
        font-weight: 750;
    }

    .rd-progress {
        height: 8px;
        overflow: hidden;
        border-radius: 20px;
        background: #e9eef4;
    }

    .rd-progress .progress-bar {
        border-radius: 20px;
        transition: width .5s ease;
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .rd-card-footer {
        padding: 15px 20px 20px;
        border-top: 1px solid #eff2f5;
        background: #fff;
    }

    .rd-action-btn {
        min-height: 41px;
        border-radius: 10px;

        font-size: 12px;
        font-weight: 650;

        padding: 9px 12px;

        transition: .18s ease;
    }

    .rd-action-btn:hover {
        transform: translateY(-1px);
    }

    .rd-whatsapp-btn {
        border-color: var(--rd-success);
        box-shadow: 0 5px 14px rgba(25,135,84,.10);
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .rd-empty {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--rd-border);
        border-radius: var(--rd-radius);
        box-shadow: 0 7px 25px rgba(15,23,42,.05);
    }

    .rd-empty-body {
        padding: 70px 20px;
        text-align: center;
    }

    .rd-empty-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 19px;

        border-radius: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: var(--rd-soft);
        color: #98a2b3;

        font-size: 35px;
    }

    .rd-empty-title {
        color: var(--rd-text);
        font-size: 17px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .rd-empty-text {
        color: var(--rd-muted);
        font-size: 13px;
        margin: 0;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767.98px) {

        .result-dashboard {
            padding-top: 10px !important;
        }

        .rd-header {
            padding: 19px;
            border-radius: 16px;
        }

        .rd-header .d-flex {
            align-items: flex-start !important;
            flex-direction: column;
        }

        .rd-title {
            font-size: 20px;
        }

        .rd-generate-btn {
            width: 100%;
        }

        .rd-selector-body,
        .rd-exam-body {
            padding: 15px;
        }

        .rd-class-top,
        .rd-card-footer {
            padding-left: 16px;
            padding-right: 16px;
        }

        .rd-action-btn {
            width: 100%;
        }
    }
</style>


<div class="container-fluid py-4 result-dashboard">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="rd-header">

        <div class="rd-header-content">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="rd-title-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>

                    <div>

                        <h4 class="rd-title">
                            Result Dashboard
                        </h4>

                        <p class="rd-subtitle">
                            Manage generated, verified, approved and published student results.
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('admin.results.generate') }}"
                    class="btn btn-primary rd-generate-btn"
                >
                    <i class="bi bi-plus-circle me-1"></i>
                    Generate Result
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show rd-alert mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show rd-alert mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- =========================================================
        EXAMINATION SELECTION
    ========================================================== --}}

    <div class="rd-selector-card mb-4">

        <div class="rd-selector-header">

            <div class="d-flex align-items-center gap-2">

                <div class="rd-selector-icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>

                <div>

                    <h5 class="rd-section-title">
                        Examination Selection
                    </h5>

                    <div class="rd-section-subtitle">
                        Select an examination to view its live result workflow
                    </div>

                </div>

            </div>

        </div>


        <div class="rd-selector-body">

            <div class="row align-items-end">

                <div class="col-lg-7 col-md-8">

                    <label
                        for="exam_id"
                        class="rd-label"
                    >
                        Select Examination
                    </label>

                    <select
                        id="exam_id"
                        class="form-select rd-select"
                    >

                        <option value="">
                            -- Select Examination --
                        </option>

                        @foreach($exams as $exam)

                            <option value="{{ $exam->id }}">

                                {{ $exam->exam_name }}

                                @if($exam->academic_year)
                                    - {{ $exam->academic_year }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        LOADING
    ========================================================== --}}

    <div
        id="loadingMessage"
        class="alert rd-alert rd-loading d-none mb-4"
        aria-live="polite"
    >

        <div class="d-flex align-items-center">

            <div
                class="spinner-border spinner-border-sm me-2"
                role="status"
            ></div>

            <span>
                Loading live examination status...
            </span>

        </div>

    </div>


    {{-- =========================================================
        ERROR
    ========================================================== --}}

    <div
        id="errorMessage"
        class="alert rd-alert rd-error d-none mb-4"
        role="alert"
    ></div>


    {{-- =========================================================
        EXAMINATION INFORMATION
    ========================================================== --}}

    <div
        id="examInfo"
        class="rd-exam-card mb-4 d-none"
    >

        <div class="rd-exam-body">

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="rd-exam-item">

                        <div class="rd-exam-label">
                            Examination
                        </div>

                        <div
                            id="examName"
                            class="rd-exam-value"
                        >
                            -
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="rd-exam-item">

                        <div class="rd-exam-label">
                            Academic Year
                        </div>

                        <div
                            id="academicYear"
                            class="rd-exam-value"
                        >
                            -
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="rd-exam-item">

                        <div class="rd-exam-label">
                            Examination Status
                        </div>

                        <div
                            id="examStatus"
                            class="rd-status-badge rd-status-draft"
                        >
                            <i class="bi bi-circle-fill"></i>
                            -
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PUBLICATION SUMMARY
    ========================================================== --}}

    <div
        id="publicationSummary"
        class="row g-3 mb-4 d-none"
    >

        {{-- TOTAL --}}

        <div class="col-xl-3 col-md-6">

            <div class="rd-summary-card rd-summary-total">

                <div class="rd-summary-body">

                    <div class="rd-summary-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="rd-summary-label">
                        Total Students
                    </div>

                    <div
                        id="summaryTotal"
                        class="rd-summary-value"
                    >
                        0
                    </div>

                </div>

            </div>

        </div>


        {{-- GENERATED --}}

        <div class="col-xl-3 col-md-6">

            <div class="rd-summary-card rd-summary-generated">

                <div class="rd-summary-body">

                    <div class="rd-summary-icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>

                    <div class="rd-summary-label">
                        Generated
                    </div>

                    <div
                        id="summaryGenerated"
                        class="rd-summary-value"
                    >
                        0
                    </div>

                </div>

            </div>

        </div>


        {{-- APPROVED --}}

        <div class="col-xl-3 col-md-6">

            <div class="rd-summary-card rd-summary-approved">

                <div class="rd-summary-body">

                    <div class="rd-summary-icon">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>

                    <div class="rd-summary-label">
                        Approved
                    </div>

                    <div
                        id="summaryApproved"
                        class="rd-summary-value"
                    >
                        0
                    </div>

                </div>

            </div>

        </div>


        {{-- PUBLISHED --}}

        <div class="col-xl-3 col-md-6">

            <div class="rd-summary-card rd-summary-published">

                <div class="rd-summary-body">

                    <div class="rd-summary-icon">
                        <i class="bi bi-globe2"></i>
                    </div>

                    <div class="rd-summary-label">
                        Published
                    </div>

                    <div
                        id="summaryPublished"
                        class="rd-summary-value"
                    >
                        0
                    </div>

                    <div class="rd-overall-progress">

                        <div class="d-flex justify-content-between mb-1">

                            <span class="rd-overall-progress-label">
                                Overall Publication
                            </span>

                            <span
                                id="overallProgressValue"
                                class="rd-overall-progress-value"
                            >
                                0%
                            </span>

                        </div>

                        <div class="progress rd-overall-progress-bar">

                            <div
                                id="overallProgressBar"
                                class="progress-bar bg-success"
                                style="width:0%"
                            ></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CLASS HEADER
    ========================================================== --}}

    <div
        id="classHeader"
        class="rd-class-heading d-none"
    >

        <div class="d-flex align-items-center gap-3">

            <div class="rd-class-heading-icon">
                <i class="bi bi-mortarboard-fill"></i>
            </div>

            <div>

                <h5 class="rd-class-title">
                    Classes
                </h5>

                <p class="rd-class-description">
                    Live status of result generation, verification, approval and publication.
                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CLASS CARDS
    ========================================================== --}}

    <div
        id="classCards"
        class="row g-4"
    ></div>


    {{-- =========================================================
        EMPTY STATE
    ========================================================== --}}

    <div
        id="emptyState"
        class="rd-empty d-none"
    >

        <div class="rd-empty-body">

            <div class="rd-empty-icon">
                <i class="bi bi-inbox"></i>
            </div>

            <h5 class="rd-empty-title">
                No Classes Found
            </h5>

            <p class="rd-empty-text">
                No classes are available for this examination.
            </p>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const examSelect = document.getElementById('exam_id');
    const classCards = document.getElementById('classCards');
    const loadingMessage = document.getElementById('loadingMessage');
    const errorMessage = document.getElementById('errorMessage');
    const examInfo = document.getElementById('examInfo');
    const publicationSummary = document.getElementById('publicationSummary');
    const classHeader = document.getElementById('classHeader');
    const emptyState = document.getElementById('emptyState');

    let selectedExamId = '';
    let refreshTimer = null;


    /* =========================================================
       HTML ESCAPE
    ========================================================== */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /* =========================================================
       NUMBER
    ========================================================== */

    function number(value) {
        return Number(value || 0);
    }


    /* =========================================================
       EXAM STATUS
    ========================================================== */

    function getExamStatusClass(status) {

        const value = String(status || '').toLowerCase();

        if (value === 'scheduled') {
            return 'rd-status-scheduled';
        }

        if (value === 'completed') {
            return 'rd-status-completed';
        }

        if (value === 'cancelled') {
            return 'rd-status-cancelled';
        }

        return 'rd-status-draft';
    }


    function getExamStatusIcon(status) {

        const value = String(status || '').toLowerCase();

        if (value === 'completed') {
            return 'bi-check-circle-fill';
        }

        if (value === 'cancelled') {
            return 'bi-x-circle-fill';
        }

        if (value === 'scheduled') {
            return 'bi-clock-fill';
        }

        return 'bi-circle-fill';
    }


    /* =========================================================
       CLASS WORKFLOW STATUS
    ========================================================== */

    function getClassWorkflowStatus(
        studentCount,
        generatedCount,
        verifiedCount,
        approvedCount,
        publishedCount
    ) {

        if (studentCount <= 0) {

            return {
                text: 'No Students',
                className: 'empty',
                cardClass: ''
            };

        }


        if (publishedCount >= studentCount) {

            return {
                text: 'All Results Published',
                className: 'completed',
                cardClass: 'status-completed'
            };

        }


        if (approvedCount > 0) {

            return {
                text: 'Approval / Publication in Progress',
                className: 'progress',
                cardClass: 'status-progress'
            };

        }


        if (verifiedCount > 0) {

            return {
                text: 'Verification Completed Partially',
                className: 'progress',
                cardClass: 'status-progress'
            };

        }


        if (generatedCount > 0) {

            return {
                text: 'Results Generated',
                className: 'progress',
                cardClass: 'status-progress'
            };

        }


        return {
            text: 'Results Pending',
            className: 'pending',
            cardClass: 'status-pending'
        };
    }


    /* =========================================================
       PROGRESS COLOR
    ========================================================== */

    function getProgressClass(percentage) {

        if (percentage >= 100) {
            return 'bg-success';
        }

        if (percentage >= 50) {
            return 'bg-warning';
        }

        if (percentage > 0) {
            return 'bg-info';
        }

        return 'bg-danger';
    }


    /* =========================================================
       ERROR
    ========================================================== */

    function showError(message) {

        errorMessage.textContent = message;
        errorMessage.classList.remove('d-none');
    }


    function hideError() {

        errorMessage.classList.add('d-none');
        errorMessage.textContent = '';
    }


    /* =========================================================
       RESET
    ========================================================== */

    function resetDashboard() {

        classCards.innerHTML = '';

        examInfo.classList.add('d-none');
        publicationSummary.classList.add('d-none');
        classHeader.classList.add('d-none');
        emptyState.classList.add('d-none');

        hideError();

        document.getElementById('summaryTotal').textContent = '0';
        document.getElementById('summaryGenerated').textContent = '0';
        document.getElementById('summaryApproved').textContent = '0';
        document.getElementById('summaryPublished').textContent = '0';

        document.getElementById('overallProgressValue').textContent = '0%';
        document.getElementById('overallProgressBar').style.width = '0%';

    }


    /* =========================================================
       EXAM CHANGE
    ========================================================== */

    examSelect.addEventListener('change', function () {

        selectedExamId = this.value;

        resetDashboard();

        if (!selectedExamId) {

            if (refreshTimer) {
                clearInterval(refreshTimer);
                refreshTimer = null;
            }

            return;
        }

        loadExamClasses(selectedExamId);

        /*
         * Automatically refresh every 10 seconds.
         * This keeps the dashboard status updated after
         * generating / verifying / approving / publishing
         * results from another page or browser tab.
         */

        if (refreshTimer) {
            clearInterval(refreshTimer);
        }

        refreshTimer = setInterval(function () {

            if (selectedExamId) {
                loadExamClasses(
                    selectedExamId,
                    true
                );
            }

        }, 10000);

    });


    /* =========================================================
       LOAD EXAM CLASSES
    ========================================================== */

    function loadExamClasses(examId, silent = false) {

        if (!silent) {
            loadingMessage.classList.remove('d-none');
        }

        hideError();

        fetch(
            `{{ route('admin.results.exam-classes') }}` +
            `?exam_id=${encodeURIComponent(examId)}`,
            {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Unable to load examination classes.'
                );
            }

            return response.json();

        })

        .then(function (data) {

            loadingMessage.classList.add('d-none');

            renderDashboard(
                data,
                examId
            );

        })

        .catch(function (error) {

            loadingMessage.classList.add('d-none');

            if (!silent) {

                showError(
                    error.message ||
                    'Something went wrong while loading classes.'
                );

            }

        });

    }


    /* =========================================================
       RENDER DASHBOARD
    ========================================================== */

    function renderDashboard(data, examId) {

        const classes =
            Array.isArray(data.classes)
                ? data.classes
                : [];


        if (classes.length === 0) {

            classCards.innerHTML = '';

            examInfo.classList.add('d-none');
            publicationSummary.classList.add('d-none');
            classHeader.classList.add('d-none');

            emptyState.classList.remove('d-none');

            return;
        }


        classHeader.classList.remove('d-none');
        publicationSummary.classList.remove('d-none');
        examInfo.classList.remove('d-none');
        emptyState.classList.add('d-none');


        /* =====================================================
           EXAM INFORMATION
        ====================================================== */

        if (data.exam) {

            const examStatus =
                data.exam.status || 'draft';

            const statusClass =
                getExamStatusClass(examStatus);

            const statusIcon =
                getExamStatusIcon(examStatus);


            document.getElementById('examName')
                .textContent =
                data.exam.exam_name || '-';


            document.getElementById('academicYear')
                .textContent =
                data.exam.academic_year || '-';


            const examStatusElement =
                document.getElementById('examStatus');


            examStatusElement.className =
                `rd-status-badge ${statusClass}`;


            examStatusElement.innerHTML = `
                <i class="bi ${statusIcon}"></i>
                ${escapeHtml(examStatus)}
            `;

        }


        /* =====================================================
           SUMMARY COUNTS
        ====================================================== */

        let totalStudents = 0;
        let totalGenerated = 0;
        let totalApproved = 0;
        let totalPublished = 0;


        classes.forEach(function (item) {

            totalStudents +=
                number(item.student_count);

            totalGenerated +=
                number(item.generated_count);

            totalApproved +=
                number(item.approved_count);

            totalPublished +=
                number(item.published_count);

        });


        document.getElementById('summaryTotal')
            .textContent =
            totalStudents;


        document.getElementById('summaryGenerated')
            .textContent =
            totalGenerated;


        document.getElementById('summaryApproved')
            .textContent =
            totalApproved;


        document.getElementById('summaryPublished')
            .textContent =
            totalPublished;


        /* =====================================================
           OVERALL PUBLICATION
        ====================================================== */

        const overallPercentage =
            totalStudents > 0
                ? Math.min(
                    100,
                    Math.round(
                        (
                            totalPublished /
                            totalStudents
                        ) * 100
                    )
                )
                : 0;


        document.getElementById('overallProgressValue')
            .textContent =
            `${overallPercentage}%`;


        const overallProgressBar =
            document.getElementById('overallProgressBar');


        overallProgressBar.style.width =
            `${overallPercentage}%`;


        overallProgressBar.className =
            `progress-bar ${getProgressClass(overallPercentage)}`;


        /* =====================================================
           CLASS CARDS
        ====================================================== */

        classCards.innerHTML = '';


        classes.forEach(function (item) {

            const className =
                item.class_name || 'Class';

            const section =
                item.section || '';

            const studentCount =
                number(item.student_count);

            const generatedCount =
                number(item.generated_count);

            const verifiedCount =
                number(item.verified_count);

            const approvedCount =
                number(item.approved_count);

            const publishedCount =
                number(item.published_count);


            const currentExamId =
                item.exam_id || examId;

            const examClassId =
                item.exam_class_id || '';

            const classId =
                item.class_id || '';


            /* =================================================
               PUBLICATION %
            ================================================== */

            const publicationPercentage =
                studentCount > 0
                    ? Math.min(
                        100,
                        Math.round(
                            (
                                publishedCount /
                                studentCount
                            ) * 100
                        )
                    )
                    : 0;


            /* =================================================
               WORKFLOW STATUS
            ================================================== */

            const workflow =
                getClassWorkflowStatus(
                    studentCount,
                    generatedCount,
                    verifiedCount,
                    approvedCount,
                    publishedCount
                );


            const progressClass =
                getProgressClass(
                    publicationPercentage
                );


            const card =
                document.createElement('div');


            card.className =
                'col-xl-4 col-lg-6 col-md-6';


            card.innerHTML = `

                <div class="rd-class-card h-100 ${workflow.cardClass}">

                    <div class="rd-class-top">

                        <div
                            class="d-flex justify-content-between align-items-start gap-3"
                        >

                            <div
                                class="d-flex align-items-center gap-3"
                            >

                                <div class="rd-class-icon">

                                    <i class="bi bi-mortarboard-fill"></i>

                                </div>

                                <div>

                                    <h5 class="rd-class-name">

                                        ${escapeHtml(className)}

                                        ${
                                            section
                                                ? `
                                                    <span class="text-muted">
                                                        - Section ${escapeHtml(section)}
                                                    </span>
                                                  `
                                                : ''
                                        }

                                    </h5>

                                    <div class="rd-class-year">

                                        <i class="bi bi-calendar3 me-1"></i>

                                        Academic Year:
                                        ${escapeHtml(
                                            item.academic_year || '-'
                                        )}

                                    </div>

                                </div>

                            </div>


                            <span class="rd-student-badge">

                                <i class="bi bi-people-fill me-1"></i>

                                ${studentCount}

                                Students

                            </span>

                        </div>


                        {{-- DYNAMIC CLASS STATUS --}}

                        <div>

                            <span class="rd-class-status ${workflow.className}">

                                <i class="bi bi-circle-fill"></i>

                                ${escapeHtml(workflow.text)}

                            </span>

                        </div>


                        {{-- STATISTICS --}}

                        <div class="row g-2 mt-3">

                            <div class="col-6">

                                <div class="rd-stat">

                                    <span class="rd-stat-label">
                                        Generated
                                    </span>

                                    <span
                                        class="rd-stat-value rd-stat-generated"
                                    >
                                        ${generatedCount}
                                    </span>

                                </div>

                            </div>


                            <div class="col-6">

                                <div class="rd-stat">

                                    <span class="rd-stat-label">
                                        Verified
                                    </span>

                                    <span
                                        class="rd-stat-value rd-stat-verified"
                                    >
                                        ${verifiedCount}
                                    </span>

                                </div>

                            </div>


                            <div class="col-6">

                                <div class="rd-stat">

                                    <span class="rd-stat-label">
                                        Approved
                                    </span>

                                    <span
                                        class="rd-stat-value rd-stat-approved"
                                    >
                                        ${approvedCount}
                                    </span>

                                </div>

                            </div>


                            <div class="col-6">

                                <div class="rd-stat">

                                    <span class="rd-stat-label">
                                        Published
                                    </span>

                                    <span
                                        class="rd-stat-value rd-stat-published"
                                    >
                                        ${publishedCount}
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- PUBLICATION PROGRESS --}}

                        <div class="rd-progress-area">

                            <div
                                class="d-flex justify-content-between align-items-center mb-2"
                            >

                                <span class="rd-progress-label">

                                    <i class="bi bi-globe2 me-1"></i>

                                    Publication Progress

                                </span>

                                <span class="rd-progress-value">

                                    ${publishedCount} /
                                    ${studentCount}

                                    &nbsp;(${publicationPercentage}%)

                                </span>

                            </div>


                            <div class="progress rd-progress">

                                <div
                                    class="progress-bar ${progressClass}"
                                    role="progressbar"
                                    style="width:${publicationPercentage}%"
                                    aria-valuenow="${publicationPercentage}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>

                            </div>

                        </div>

                    </div>


                    {{-- ACTIONS --}}

                    <div class="rd-card-footer">

                        <div class="d-flex flex-wrap gap-2">

                            <a
                                href="#"
                                class="btn btn-outline-primary rd-action-btn show-class-results flex-grow-1"
                                data-exam-class-id="${escapeHtml(examClassId)}"
                                data-exam-id="${escapeHtml(currentExamId)}"
                                data-class-id="${escapeHtml(classId)}"
                                data-section="${escapeHtml(section)}"
                            >

                                <i class="bi bi-eye me-1"></i>

                                Show Results

                            </a>


                            <a
                                href="#"
                                class="btn btn-success rd-action-btn rd-whatsapp-btn bulk-whatsapp flex-grow-1"
                                data-exam-class-id="${escapeHtml(examClassId)}"
                                data-exam-id="${escapeHtml(currentExamId)}"
                                data-class-id="${escapeHtml(classId)}"
                                data-section="${escapeHtml(section)}"
                            >

                                <i class="bi bi-whatsapp me-1"></i>

                                WhatsApp

                            </a>

                        </div>

                    </div>

                </div>

            `;


            classCards.appendChild(card);

        });

    }


    /* =========================================================
       SHOW CLASS RESULTS
    ========================================================== */

    classCards.addEventListener('click', function (event) {

        const button =
            event.target.closest(
                '.show-class-results'
            );


        if (!button) {
            return;
        }


        event.preventDefault();


        const examId =
            button.dataset.examId ||
            examSelect.value;

        const examClassId =
            button.dataset.examClassId;

        const classId =
            button.dataset.classId;

        const section =
            button.dataset.section;


        if (!examId) {

            showError(
                'Please select an examination.'
            );

            return;
        }


        if (!examClassId) {

            showError(
                'Exam class information is missing.'
            );

            return;
        }


        if (!classId) {

            showError(
                'Class information is missing.'
            );

            return;
        }


        const url =
            `{{ route('admin.results.class-results') }}` +
            `?exam_id=${encodeURIComponent(examId)}` +
            `&exam_class_id=${encodeURIComponent(examClassId)}` +
            `&class_id=${encodeURIComponent(classId)}` +
            `&section=${encodeURIComponent(section || '')}`;


        window.location.href = url;

    });


    /* =========================================================
       BULK WHATSAPP
    ========================================================== */

    classCards.addEventListener('click', function (event) {

        const button =
            event.target.closest(
                '.bulk-whatsapp'
            );


        if (!button) {
            return;
        }


        event.preventDefault();


        const examId =
            button.dataset.examId ||
            examSelect.value;

        const examClassId =
            button.dataset.examClassId;

        const classId =
            button.dataset.classId;

        const section =
            button.dataset.section;


        if (!examId) {

            showError(
                'Please select an examination.'
            );

            return;
        }


        if (!examClassId) {

            showError(
                'Exam class information is missing.'
            );

            return;
        }


        if (!classId) {

            showError(
                'Class information is missing.'
            );

            return;
        }


        const url =
            `{{ route('admin.results.bulk-whatsapp') }}` +
            `?exam_id=${encodeURIComponent(examId)}` +
            `&exam_class_id=${encodeURIComponent(examClassId)}` +
            `&class_id=${encodeURIComponent(classId)}` +
            `&section=${encodeURIComponent(section || '')}`;


        window.location.href = url;

    });


    /* =========================================================
       OPTIONAL: AUTO LOAD FIRST EXAM
    ========================================================== */

    if (examSelect.options.length === 2) {

        examSelect.selectedIndex = 1;

        selectedExamId =
            examSelect.value;

        loadExamClasses(
            selectedExamId
        );

        refreshTimer =
            setInterval(function () {

                if (selectedExamId) {

                    loadExamClasses(
                        selectedExamId,
                        true
                    );

                }

            }, 10000);

    }

});
</script>

@endsection
