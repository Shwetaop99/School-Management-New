
@extends('layouts.app')

@section('title', $exam->exam_name)

@section('content')

<div class="container-fluid py-4 exam-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-header mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('admin.exams.index') }}"
                   class="back-button"
                   title="Back to Exams">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div class="page-icon">
                    <i class="bi bi-journal-text"></i>
                </div>

                <div>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                        <h4 class="fw-bold mb-0">
                            {{ $exam->exam_name }}
                        </h4>

                        @php
                            $headerStatusClass = match($exam->status) {
                                'draft' => 'status-draft',
                                'scheduled' => 'status-scheduled',
                                'completed' => 'status-completed',
                                'cancelled' => 'status-cancelled',
                                default => 'status-draft',
                            };
                        @endphp

                        <span class="status-pill {{ $headerStatusClass }}">
                            <span class="status-dot"></span>
                            {{ ucfirst($exam->status) }}
                        </span>
                    </div>

                    <p class="text-muted mb-0">
                        <i class="bi bi-sliders2 me-1"></i>
                        Exam setup and timetable management
                    </p>
                </div>

            </div>


            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="btn btn-primary px-3">

                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Exam

                </a>

                <a href="{{ route('admin.exams.index') }}"
                   class="btn btn-light border px-3">

                    <i class="bi bi-list-ul me-1"></i>
                    All Exams

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success custom-alert alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-center">

                <div class="alert-icon success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div>
                    <div class="fw-semibold">Success</div>
                    <div class="small">
                        {{ session('success') }}
                    </div>
                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger custom-alert alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-start">

                <div class="alert-icon danger-icon">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div>

                    <div class="fw-semibold mb-1">
                        Please check the following:
                    </div>

                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         EXAM OVERVIEW
    ========================================================== --}}
    <div class="overview-card mb-4">

        <div class="overview-top">

            <div>
                <div class="section-kicker">
                    <i class="bi bi-info-circle me-1"></i>
                    EXAM OVERVIEW
                </div>

                <h5 class="fw-bold mb-1">
                    {{ $exam->exam_name }}
                </h5>

                <p class="text-muted small mb-0">
                    Review the basic examination information before completing
                    the setup.
                </p>
            </div>

            <div class="overview-type">
                <i class="bi bi-award"></i>
                <span>{{ $exam->exam_type }}</span>
            </div>

        </div>


        <div class="overview-divider"></div>


        <div class="row g-0">

            {{-- Academic Year --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon blue">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div>
                        <span>Academic Year</span>
                        <strong>{{ $exam->academic_year }}</strong>
                    </div>
                </div>
            </div>


            {{-- Exam Type --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon purple">
                        <i class="bi bi-bookmark-star"></i>
                    </div>

                    <div>
                        <span>Exam Type</span>
                        <strong>{{ $exam->exam_type }}</strong>
                    </div>
                </div>
            </div>


            {{-- Start Date --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon green">
                        <i class="bi bi-calendar-check"></i>
                    </div>

                    <div>
                        <span>Start Date</span>
                        <strong>
                            {{ $exam->start_date?->format('d M Y') ?? '-' }}
                        </strong>
                    </div>
                </div>
            </div>


            {{-- End Date --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon orange">
                        <i class="bi bi-calendar-event"></i>
                    </div>

                    <div>
                        <span>End Date</span>
                        <strong>
                            {{ $exam->end_date?->format('d M Y') ?? 'Not calculated' }}
                        </strong>
                    </div>
                </div>
            </div>


            {{-- Duration --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon teal">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <div>
                        <span>Exam Period</span>

                        <strong>
                            @if($exam->start_date && $exam->end_date)
                                {{ $exam->start_date->diffInDays($exam->end_date) + 1 }}
                                {{ Str::plural('Day', $exam->start_date->diffInDays($exam->end_date) + 1) }}
                            @else
                                —
                            @endif
                        </strong>
                    </div>
                </div>
            </div>


            {{-- Status --}}
            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon slate">
                        <i class="bi bi-activity"></i>
                    </div>

                    <div>
                        <span>Status</span>

                        <strong>
                            {{ ucfirst($exam->status) }}
                        </strong>
                    </div>
                </div>
            </div>

        </div>

    </div>


    {{-- =========================================================
         SETUP SECTION HEADER
    ========================================================== --}}
    <div class="section-heading mb-3">

        <div>
            <div class="section-kicker">
                <i class="bi bi-diagram-3 me-1"></i>
                EXAM SETUP
            </div>

            <h5 class="fw-bold mb-1">
                Configure Your Examination
            </h5>

            <p class="text-muted small mb-0">
                Complete each setup step before generating the final timetable.
            </p>
        </div>

        @php
            $setupTotal = 4;

            $setupCompleted =
                ($exam->exam_classes_count > 0 ? 1 : 0) +
                ($exam->exam_subjects_count > 0 ? 1 : 0) +
                ($exam->exam_sessions_count > 0 ? 1 : 0) +
                ($exam->exam_holidays_count > 0 ? 1 : 0);
        @endphp

        <div class="setup-progress">

            <span>
                {{ $setupCompleted }}/{{ $setupTotal }} configured
            </span>

            <div class="progress">
                <div class="progress-bar"
                     style="width: {{ ($setupCompleted / $setupTotal) * 100 }}%">
                </div>
            </div>

        </div>

    </div>


    {{-- =========================================================
         SETUP CARDS
    ========================================================== --}}
    <div class="row g-4 mb-4">


        {{-- =====================================================
             STEP 1 - CLASSES
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="setup-card h-100">

                <div class="setup-card-top">

                    <div class="step-icon step-blue">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div class="step-number">
                        STEP 01
                    </div>

                </div>


                <div class="setup-card-content">

                    <h5>
                        Select Classes
                    </h5>

                    <p>
                        Choose the classes from the existing Classes module
                        that will participate in this examination.
                    </p>


                    <div class="setup-count">

                        <strong>
                            {{ $exam->exam_classes_count }}
                        </strong>

                        <span>
                            {{ Str::plural('Class', $exam->exam_classes_count) }}
                            selected
                        </span>

                    </div>

                </div>


                <a href="{{ route('admin.exam-classes.index', $exam) }}"
                   class="setup-action action-blue">

                    <span>
                        Manage Classes
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        {{-- =====================================================
             STEP 2 - SUBJECTS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="setup-card h-100">

                <div class="setup-card-top">

                    <div class="step-icon step-green">
                        <i class="bi bi-book"></i>
                    </div>

                    <div class="step-number">
                        STEP 02
                    </div>

                </div>


                <div class="setup-card-content">

                    <h5>
                        Subjects & Marks
                    </h5>

                    <p>
                        Configure subjects, maximum marks, passing marks
                        and duration for each examination paper.
                    </p>


                    <div class="setup-count">

                        <strong>
                            {{ $exam->exam_subjects_count }}
                        </strong>

                        <span>
                            {{ Str::plural('Subject', $exam->exam_subjects_count) }}
                            configured
                        </span>

                    </div>

                </div>


                <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                   class="setup-action action-green">

                    <span>
                        Configure Subjects
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        {{-- =====================================================
             STEP 3 - SESSIONS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="setup-card h-100">

                <div class="setup-card-top">

                    <div class="step-icon step-orange">
                        <i class="bi bi-clock"></i>
                    </div>

                    <div class="step-number">
                        STEP 03
                    </div>

                </div>


                <div class="setup-card-content">

                    <h5>
                        Exam Sessions
                    </h5>

                    <p>
                        Define morning, afternoon or custom examination
                        session timings for your timetable.
                    </p>


                    <div class="setup-count">

                        <strong>
                            {{ $exam->exam_sessions_count }}
                        </strong>

                        <span>
                            {{ Str::plural('Session', $exam->exam_sessions_count) }}
                            configured
                        </span>

                    </div>

                </div>


                <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                   class="setup-action action-orange">

                    <span>
                        Manage Sessions
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>


        {{-- =====================================================
             STEP 4 - HOLIDAYS
        ====================================================== --}}
        <div class="col-xl-3 col-md-6">

            <div class="setup-card h-100">

                <div class="setup-card-top">

                    <div class="step-icon step-red">
                        <i class="bi bi-calendar-x"></i>
                    </div>

                    <div class="step-number">
                        STEP 04
                    </div>

                </div>


                <div class="setup-card-content">

                    <h5>
                        Holidays
                    </h5>

                    <p>
                        Add holidays and non-working days that should be
                        excluded while generating the timetable.
                    </p>


                    <div class="setup-count">

                        <strong>
                            {{ $exam->exam_holidays_count }}
                        </strong>

                        <span>
                            {{ Str::plural('Holiday', $exam->exam_holidays_count) }}
                            configured
                        </span>

                    </div>

                </div>


                <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                   class="setup-action action-red">

                    <span>
                        Manage Holidays
                    </span>

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         TIMETABLE SECTION
    ========================================================== --}}
    <div class="timetable-card">

        <div class="timetable-header">

            <div class="d-flex align-items-center gap-3">

                <div class="timetable-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>

                    <div class="section-kicker">
                        TIMETABLE
                    </div>

                    <h5 class="fw-bold mb-1">
                        Examination Timetable
                    </h5>

                    <p class="text-muted small mb-0">
                        Generate, review, edit and print your examination schedule.
                    </p>

                </div>

            </div>


            @if($exam->exam_timetables_count > 0)

                <div class="entries-badge">

                    <i class="bi bi-check-circle-fill"></i>

                    {{ $exam->exam_timetables_count }}
                    {{ Str::plural('Entry', $exam->exam_timetables_count) }}

                </div>

            @endif

        </div>


        <div class="timetable-body">

            @if(
                $exam->exam_classes_count > 0 &&
                $exam->exam_subjects_count > 0 &&
                $exam->exam_sessions_count > 0
            )

                <div class="row g-4">


                    {{-- =================================================
                         GENERATE TIMETABLE
                    ================================================== --}}
                    <div class="col-lg-6">

                        <div class="timetable-option generate-option">

                            <div class="option-icon">
                                <i class="bi bi-magic"></i>
                            </div>

                            <div class="option-content">

                                <span class="option-label">
                                    AUTOMATION
                                </span>

                                <h5>
                                    Generate Timetable
                                </h5>

                                <p>
                                    Automatically create the examination
                                    schedule using configured classes,
                                    subjects, durations, sessions and holidays.
                                </p>

                                <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                                   class="btn btn-primary">

                                    <i class="bi bi-magic me-1"></i>
                                    Generate Timetable

                                </a>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         VIEW TIMETABLE
                    ================================================== --}}
                    <div class="col-lg-6">

                        <div class="timetable-option view-option">

                            <div class="option-icon">
                                <i class="bi bi-calendar-week"></i>
                            </div>

                            <div class="option-content">

                                <span class="option-label">
                                    MANAGEMENT
                                </span>

                                <h5>
                                    View Timetable
                                </h5>

                                <p>
                                    View the generated schedule and make
                                    changes or print the final examination
                                    timetable.
                                </p>

                                @if($exam->exam_timetables_count > 0)

                                    <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                                       class="btn btn-outline-success">

                                        <i class="bi bi-eye me-1"></i>
                                        View Timetable

                                    </a>

                                @else

                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            disabled>

                                        <i class="bi bi-eye me-1"></i>
                                        No Timetable Yet

                                    </button>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Timetable workflow --}}
                <div class="workflow-note mt-4">

                    <div class="workflow-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div>

                        <strong>
                            Timetable workflow
                        </strong>

                        <p class="mb-0 text-muted small">
                            Classes → Subjects & Marks → Sessions →
                            Holidays → Generate Timetable → Review & Print
                        </p>

                    </div>

                </div>


            @else

                {{-- =================================================
                     SETUP INCOMPLETE
                ================================================== --}}
                <div class="setup-incomplete">

                    <div class="incomplete-icon">
                        <i class="bi bi-calendar2-x"></i>
                    </div>

                    <h5 class="fw-bold">
                        Complete Exam Setup First
                    </h5>

                    <p class="text-muted">
                        Configure the required classes, subjects and sessions
                        before generating the examination timetable.
                    </p>


                    <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">


                        @if($exam->exam_classes_count == 0)

                            <a href="{{ route('admin.exam-classes.index', $exam) }}"
                               class="btn btn-outline-primary">

                                <i class="bi bi-mortarboard me-1"></i>
                                Select Classes

                            </a>

                        @endif


                        @if($exam->exam_subjects_count == 0)

                            <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                               class="btn btn-outline-success">

                                <i class="bi bi-book me-1"></i>
                                Configure Subjects

                            </a>

                        @endif


                        @if($exam->exam_sessions_count == 0)

                            <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                               class="btn btn-outline-warning">

                                <i class="bi bi-clock me-1"></i>
                                Create Sessions

                            </a>

                        @endif

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ================================================================
     PAGE STYLES
================================================================ --}}
<style>

    :root {
        --primary-blue: #1677f0;
        --primary-dark: #125fc4;
        --text-dark: #172033;
        --text-muted: #6b7280;
        --border-color: #e7ebf2;
        --page-bg: #f7f9fc;
    }


    .exam-page {
        color: var(--text-dark);
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {
        padding: 4px 0;
    }


    .back-button {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        background: #fff;
        border: 1px solid var(--border-color);
        text-decoration: none;
        transition: all .2s ease;
    }


    .back-button:hover {
        color: var(--primary-blue);
        border-color: rgba(22,119,240,.25);
        background: #f5f9ff;
        transform: translateX(-2px);
    }


    .page-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(22,119,240,.10);
        color: var(--primary-blue);
        font-size: 23px;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }


    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }


    .status-draft {
        color: #64748b;
        background: #f1f5f9;
    }


    .status-scheduled {
        color: #1677f0;
        background: #eaf3ff;
    }


    .status-completed {
        color: #16834a;
        background: #eaf8f0;
    }


    .status-cancelled {
        color: #dc3545;
        background: #fff0f1;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .custom-alert {
        border: 0;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 4px 18px rgba(20, 30, 50, .04);
    }


    .alert-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }


    .success-icon {
        background: rgba(25,135,84,.12);
        color: #198754;
    }


    .danger-icon {
        background: rgba(220,53,69,.12);
        color: #dc3545;
    }


    /* =========================================================
       OVERVIEW CARD
    ========================================================== */

    .overview-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(28, 39, 60, .05);
        overflow: hidden;
    }


    .overview-top {
        padding: 22px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }


    .section-kicker {
        color: #7b8495;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }


    .overview-type {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 10px;
        background: #f5f8fd;
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }


    .overview-type i {
        color: var(--primary-blue);
    }


    .overview-divider {
        height: 1px;
        background: var(--border-color);
    }


    .overview-item {
        min-height: 92px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 11px;
        border-right: 1px solid var(--border-color);
    }


    .overview-item:last-child {
        border-right: 0;
    }


    .overview-item-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }


    .overview-item-icon.blue {
        background: #eaf3ff;
        color: #1677f0;
    }


    .overview-item-icon.purple {
        background: #f2edff;
        color: #7952d6;
    }


    .overview-item-icon.green {
        background: #eaf8f0;
        color: #198754;
    }


    .overview-item-icon.orange {
        background: #fff4e5;
        color: #e58b17;
    }


    .overview-item-icon.teal {
        background: #e8f8f7;
        color: #159a91;
    }


    .overview-item-icon.slate {
        background: #eef1f5;
        color: #64748b;
    }


    .overview-item span {
        display: block;
        color: #8a93a3;
        font-size: 11px;
        margin-bottom: 3px;
    }


    .overview-item strong {
        display: block;
        color: #263143;
        font-size: 13px;
        font-weight: 700;
    }


    /* =========================================================
       SECTION HEADING
    ========================================================== */

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
    }


    .setup-progress {
        width: 170px;
        flex-shrink: 0;
    }


    .setup-progress span {
        display: block;
        text-align: right;
        color: #6b7280;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 6px;
    }


    .setup-progress .progress {
        height: 6px;
        border-radius: 10px;
        background: #e9eef5;
        overflow: hidden;
    }


    .setup-progress .progress-bar {
        background: var(--primary-blue);
        border-radius: 10px;
    }


    /* =========================================================
       SETUP CARDS
    ========================================================== */

    .setup-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 6px 22px rgba(28, 39, 60, .045);
        transition: all .25s ease;
    }


    .setup-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 13px 30px rgba(28, 39, 60, .09);
        border-color: #dce4ef;
    }


    .setup-card-top {
        padding: 20px 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }


    .step-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }


    .step-blue {
        color: #1677f0;
        background: #eaf3ff;
    }


    .step-green {
        color: #198754;
        background: #eaf8f0;
    }


    .step-orange {
        color: #e58b17;
        background: #fff4e5;
    }


    .step-red {
        color: #dc3545;
        background: #fff0f1;
    }


    .step-number {
        color: #9aa3b1;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .8px;
    }


    .setup-card-content {
        padding: 18px 20px 20px;
        flex: 1;
    }


    .setup-card-content h5 {
        font-size: 16px;
        margin-bottom: 8px;
    }


    .setup-card-content p {
        color: #747e8e;
        font-size: 12px;
        line-height: 1.65;
        min-height: 59px;
        margin-bottom: 18px;
    }


    .setup-count {
        display: flex;
        align-items: baseline;
        gap: 7px;
        padding-top: 13px;
        border-top: 1px dashed #e5e9f0;
    }


    .setup-count strong {
        font-size: 24px;
        line-height: 1;
    }


    .setup-count span {
        color: #7c8595;
        font-size: 11px;
    }


    .setup-action {
        margin: 0 20px 20px;
        padding: 11px 13px;
        border-radius: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 700;
        transition: all .2s ease;
    }


    .setup-action i {
        transition: transform .2s ease;
    }


    .setup-action:hover i {
        transform: translateX(3px);
    }


    .action-blue {
        color: #1677f0;
        background: #f2f7ff;
    }


    .action-blue:hover {
        color: #fff;
        background: #1677f0;
    }


    .action-green {
        color: #198754;
        background: #f0faf5;
    }


    .action-green:hover {
        color: #fff;
        background: #198754;
    }


    .action-orange {
        color: #d9820b;
        background: #fff8ed;
    }


    .action-orange:hover {
        color: #fff;
        background: #e58b17;
    }


    .action-red {
        color: #dc3545;
        background: #fff4f5;
    }


    .action-red:hover {
        color: #fff;
        background: #dc3545;
    }


    /* =========================================================
       TIMETABLE CARD
    ========================================================== */

    .timetable-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        box-shadow: 0 7px 25px rgba(28, 39, 60, .05);
        overflow: hidden;
    }


    .timetable-header {
        padding: 21px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }


    .timetable-icon {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        background: #eaf3ff;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }


    .entries-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 9px;
        background: #eaf8f0;
        color: #16834a;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }


    .timetable-body {
        padding: 24px;
    }


    .timetable-option {
        height: 100%;
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 23px;
        border: 1px solid var(--border-color);
        border-radius: 15px;
        transition: all .2s ease;
    }


    .timetable-option:hover {
        box-shadow: 0 8px 22px rgba(28,39,60,.06);
        transform: translateY(-2px);
    }


    .generate-option {
        background: linear-gradient(135deg, #f8fbff 0%, #fff 100%);
    }


    .view-option {
        background: linear-gradient(135deg, #f8fffb 0%, #fff 100%);
    }


    .option-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 21px;
    }


    .generate-option .option-icon {
        background: #eaf3ff;
        color: var(--primary-blue);
    }


    .view-option .option-icon {
        background: #eaf8f0;
        color: #198754;
    }


    .option-label {
        display: block;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        color: #98a1af;
        margin-bottom: 5px;
    }


    .option-content h5 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 7px;
    }


    .option-content p {
        color: #737d8c;
        font-size: 12px;
        line-height: 1.65;
        margin-bottom: 17px;
    }


    .option-content .btn {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        padding: 9px 14px;
    }


    /* =========================================================
       WORKFLOW
    ========================================================== */

    .workflow-note {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        border-radius: 11px;
        background: #f7f9fc;
        border: 1px solid #edf0f5;
    }


    .workflow-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #fff4d9;
        color: #d99100;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }


    .workflow-note strong {
        display: block;
        font-size: 12px;
        margin-bottom: 2px;
    }


    /* =========================================================
       INCOMPLETE SETUP
    ========================================================== */

    .setup-incomplete {
        text-align: center;
        padding: 35px 20px 28px;
    }


    .incomplete-icon {
        width: 68px;
        height: 68px;
        border-radius: 18px;
        background: #fff6df;
        color: #e39a15;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 17px;
    }


    .setup-incomplete h5 {
        margin-bottom: 8px;
    }


    .setup-incomplete p {
        max-width: 520px;
        margin: 0 auto;
        font-size: 13px;
        line-height: 1.7;
    }


    .setup-incomplete .btn {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991.98px) {

        .overview-item {
            border-bottom: 1px solid var(--border-color);
        }

        .overview-item:nth-child(2n) {
            border-right: 0;
        }

        .overview-item:nth-last-child(-n+2) {
            border-bottom: 0;
        }

    }


    @media (max-width: 767.98px) {

        .page-header .d-flex {
            align-items: flex-start !important;
        }


        .overview-top {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }


        .overview-type {
            width: 100%;
            justify-content: center;
        }


        .overview-item {
            min-height: 80px;
            padding: 14px;
        }


        .section-heading {
            align-items: flex-start;
            flex-direction: column;
        }


        .setup-progress {
            width: 100%;
        }


        .setup-progress span {
            text-align: left;
        }


        .timetable-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }


        .timetable-body {
            padding: 18px;
        }


        .entries-badge {
            margin-left: 59px;
        }

    }


    @media (max-width: 575.98px) {

        .exam-page {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }


        .page-header .page-icon {
            display: none;
        }


        .page-header h4 {
            font-size: 18px;
        }


        .page-header .btn {
            width: 100%;
        }


        .page-header > .d-flex {
            width: 100%;
        }


        .overview-item {
            border-right: 0 !important;
            border-bottom: 1px solid var(--border-color);
        }


        .overview-item:nth-last-child(-n+2) {
            border-bottom: 1px solid var(--border-color);
        }


        .overview-item:last-child {
            border-bottom: 0;
        }


        .timetable-option {
            padding: 18px;
            flex-direction: column;
        }


        .workflow-note {
            align-items: flex-start;
        }

    }

</style>

@endsection
