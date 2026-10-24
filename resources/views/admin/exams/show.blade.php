@extends('layouts.app')

@section('title', $exam->exam_name)

@section('content')

<div class="container-fluid py-4 exam-page">

    {{-- SIMPLE PAGE HEADER --}}
    <div class="page-header mb-4">
        <div class="header-main">
            <div class="header-title-wrap">
                <a href="{{ route('admin.exams.index') }}"
                   class="back-button"
                   title="Back to Exams"
                   aria-label="Back to Exams">
                    <i class="bi bi-arrow-left"></i>
                </a>

                <div class="header-copy">
                    <div class="breadcrumb-text">
                        <a href="{{ route('admin.exams.index') }}">Examinations</a>
                        <i class="bi bi-chevron-right"></i>
                        <span>Exam Details</span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h4 class="page-title mb-0">{{ $exam->exam_name }}</h4>

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

                    <p class="page-subtitle mb-0">
                        Exam setup and timetable management
                    </p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ route('admin.exams.edit', $exam) }}"
                   class="btn diary-btn-primary">
                    <i class="bi bi-pencil-square me-1"></i>
                    Edit Exam
                </a>

                <a href="{{ route('admin.exams.index') }}"
                   class="btn diary-btn-light">
                    <i class="bi bi-list-ul me-1"></i>
                    All Exams
                </a>
            </div>
        </div>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert custom-alert alert-success alert-dismissible fade show mb-4"
             role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div>
                    <div class="fw-semibold">Success</div>
                    <div class="small">{{ session('success') }}</div>
                </div>
            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert custom-alert alert-danger alert-dismissible fade show mb-4"
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
                    data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- EXAM OVERVIEW --}}
    <div class="overview-card mb-4">

        <div class="overview-top">
            <div>
                <div class="section-kicker">
                    <i class="bi bi-info-circle me-1"></i>
                    EXAM OVERVIEW
                </div>

                <h5 class="overview-title mb-1">{{ $exam->exam_name }}</h5>

                <p class="muted-copy mb-0">
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

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon blue">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="overview-item-copy">
                        <span>Academic Year</span>
                        <strong>{{ $exam->academic_year }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon purple">
                        <i class="bi bi-bookmark-star"></i>
                    </div>
                    <div class="overview-item-copy">
                        <span>Exam Type</span>
                        <strong>{{ $exam->exam_type }}</strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon green">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="overview-item-copy">
                        <span>Start Date</span>
                        <strong>
                            {{ $exam->start_date?->format('d M Y') ?? '-' }}
                        </strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon orange">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="overview-item-copy">
                        <span>End Date</span>
                        <strong>
                            {{ $exam->end_date?->format('d M Y') ?? 'Not calculated' }}
                        </strong>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon teal">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="overview-item-copy">
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

            <div class="col-lg-2 col-md-4 col-6">
                <div class="overview-item">
                    <div class="overview-item-icon slate">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="overview-item-copy">
                        <span>Status</span>
                        <strong>{{ ucfirst($exam->status) }}</strong>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SETUP SECTION HEADER --}}
    @php
        $setupTotal = 4;

        $setupCompleted =
            ($exam->exam_classes_count > 0 ? 1 : 0) +
            ($exam->exam_subjects_count > 0 ? 1 : 0) +
            ($exam->exam_sessions_count > 0 ? 1 : 0) +
            ($exam->exam_holidays_count > 0 ? 1 : 0);

        $setupPercentage = ($setupCompleted / $setupTotal) * 100;
    @endphp

    <div class="section-heading mb-3">
        <div>
            <div class="section-kicker">
                <i class="bi bi-diagram-3 me-1"></i>
                EXAM SETUP
            </div>

            <h5 class="section-title mb-1">Configure Your Examination</h5>

            <p class="muted-copy mb-0">
                Complete each setup step before generating the final timetable.
            </p>
        </div>

        <div class="setup-progress">
            <span>{{ $setupCompleted }}/{{ $setupTotal }} configured</span>
            <div class="progress">
                <div class="progress-bar"
                     role="progressbar"
                     style="width: {{ $setupPercentage }}%"
                     aria-valuenow="{{ $setupPercentage }}"
                     aria-valuemin="0"
                     aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    {{-- SETUP CARDS --}}
    <div class="row g-4 mb-4">

        {{-- STEP 01: CLASSES --}}
        <div class="col-xl-3 col-md-6">
            <div class="setup-card h-100">
                <div class="setup-card-top">
                    <div class="step-icon step-blue">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <span class="step-number">STEP 01</span>
                </div>

                <div class="setup-card-content">
                    <h5>Select Classes</h5>
                    <p>
                        Choose the classes from the existing Classes module
                        that will participate in this examination.
                    </p>

                    <div class="setup-count">
                        <strong>{{ $exam->exam_classes_count }}</strong>
                        <span>
                            {{ Str::plural('Class', $exam->exam_classes_count) }}
                            selected
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.exam-classes.index', $exam) }}"
                   class="setup-action action-blue">
                    <span>Manage Classes</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- STEP 02: SUBJECTS --}}
        <div class="col-xl-3 col-md-6">
            <div class="setup-card h-100">
                <div class="setup-card-top">
                    <div class="step-icon step-green">
                        <i class="bi bi-book"></i>
                    </div>
                    <span class="step-number">STEP 02</span>
                </div>

                <div class="setup-card-content">
                    <h5>Subjects &amp; Marks</h5>
                    <p>
                        Configure subjects, maximum marks, passing marks
                        and duration for each examination paper.
                    </p>

                    <div class="setup-count">
                        <strong>{{ $exam->exam_subjects_count }}</strong>
                        <span>
                            {{ Str::plural('Subject', $exam->exam_subjects_count) }}
                            configured
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                   class="setup-action action-green">
                    <span>Configure Subjects</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- STEP 03: SESSIONS --}}
        <div class="col-xl-3 col-md-6">
            <div class="setup-card h-100">
                <div class="setup-card-top">
                    <div class="step-icon step-orange">
                        <i class="bi bi-clock"></i>
                    </div>
                    <span class="step-number">STEP 03</span>
                </div>

                <div class="setup-card-content">
                    <h5>Exam Sessions</h5>
                    <p>
                        Define morning, afternoon or custom examination
                        session timings for your timetable.
                    </p>

                    <div class="setup-count">
                        <strong>{{ $exam->exam_sessions_count }}</strong>
                        <span>
                            {{ Str::plural('Session', $exam->exam_sessions_count) }}
                            configured
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                   class="setup-action action-orange">
                    <span>Manage Sessions</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- STEP 04: HOLIDAYS --}}
        <div class="col-xl-3 col-md-6">
            <div class="setup-card h-100">
                <div class="setup-card-top">
                    <div class="step-icon step-red">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <span class="step-number">STEP 04</span>
                </div>

                <div class="setup-card-content">
                    <h5>Holidays</h5>
                    <p>
                        Add holidays and non-working days that should be
                        excluded while generating the timetable.
                    </p>

                    <div class="setup-count">
                        <strong>{{ $exam->exam_holidays_count }}</strong>
                        <span>
                            {{ Str::plural('Holiday', $exam->exam_holidays_count) }}
                            configured
                        </span>
                    </div>
                </div>

                <a href="{{ route('admin.exam-holidays.index', $exam) }}"
                   class="setup-action action-red">
                    <span>Manage Holidays</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

    {{-- TIMETABLE SECTION --}}
    <div class="timetable-card">

        <div class="timetable-header">
            <div class="d-flex align-items-center gap-3">
                <div class="timetable-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <div class="section-kicker">TIMETABLE</div>
                    <h5 class="section-title mb-1">Examination Timetable</h5>
                    <p class="muted-copy small mb-0">
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

                    {{-- GENERATE TIMETABLE --}}
                    <div class="col-lg-6">
                        <div class="timetable-option generate-option">
                            <div class="option-icon">
                                <i class="bi bi-magic"></i>
                            </div>

                            <div class="option-content">
                                <span class="option-label">AUTOMATION</span>
                                <h5>Generate Timetable</h5>
                                <p>
                                    Automatically create the examination schedule
                                    using configured classes, subjects, durations,
                                    sessions and holidays.
                                </p>

                                <a href="{{ route('admin.exam-schedules.generate.form', $exam) }}"
                                   class="btn diary-btn-primary">
                                    <i class="bi bi-magic me-1"></i>
                                    Generate Timetable
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- VIEW TIMETABLE --}}
                    <div class="col-lg-6">
                        <div class="timetable-option view-option">
                            <div class="option-icon">
                                <i class="bi bi-calendar-week"></i>
                            </div>

                            <div class="option-content">
                                <span class="option-label">MANAGEMENT</span>
                                <h5>View Timetable</h5>
                                <p>
                                    View the generated schedule and make changes
                                    or print the final examination timetable.
                                </p>

                                @if($exam->exam_timetables_count > 0)
                                    <a href="{{ route('admin.exam-schedules.index', $exam) }}"
                                       class="btn diary-btn-outline-green">
                                        <i class="bi bi-eye me-1"></i>
                                        View Timetable
                                    </a>
                                @else
                                    <button type="button"
                                            class="btn diary-btn-disabled"
                                            disabled>
                                        <i class="bi bi-eye me-1"></i>
                                        No Timetable Yet
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- TIMETABLE WORKFLOW --}}
                <div class="workflow-note mt-4">
                    <div class="workflow-icon">
                        <i class="bi bi-lightbulb"></i>
                    </div>

                    <div>
                        <strong>Timetable workflow</strong>
                        <p class="mb-0 muted-copy small">
                            Classes → Subjects &amp; Marks → Sessions →
                            Holidays → Generate Timetable → Review &amp; Print
                        </p>
                    </div>
                </div>

            @else

                {{-- SETUP INCOMPLETE --}}
                <div class="setup-incomplete">
                    <div class="incomplete-icon">
                        <i class="bi bi-calendar2-x"></i>
                    </div>

                    <h5 class="section-title">Complete Exam Setup First</h5>

                    <p class="muted-copy">
                        Configure the required classes, subjects and sessions
                        before generating the examination timetable.
                    </p>

                    <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">

                        @if($exam->exam_classes_count == 0)
                            <a href="{{ route('admin.exam-classes.index', $exam) }}"
                               class="btn diary-btn-outline-blue">
                                <i class="bi bi-mortarboard me-1"></i>
                                Select Classes
                            </a>
                        @endif

                        @if($exam->exam_subjects_count == 0)
                            <a href="{{ route('admin.exam-subjects.index', $exam) }}"
                               class="btn diary-btn-outline-green">
                                <i class="bi bi-book me-1"></i>
                                Configure Subjects
                            </a>
                        @endif

                        @if($exam->exam_sessions_count == 0)
                            <a href="{{ route('admin.exam-sessions.index', $exam) }}"
                               class="btn diary-btn-outline-amber">
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

{{-- =========================================================
     LOCKED SCHOOL DIARY DESIGN SYSTEM
========================================================= --}}
<style>
    .exam-page {
        --diary-page: #f7f6f0;
        --diary-paper: #fffefa;
        --diary-cream: #f5f3e9;
        --diary-ink: #263b35;
        --diary-dark: #1d302b;
        --diary-forest: #344f43;
        --diary-text: #39483e;
        --diary-muted: #7b857b;
        --diary-line: #e5e1d5;
        --diary-sage: #527f70;
        --diary-blue: #426b8a;
        --diary-amber: #a87543;
        --diary-green: #43835d;
        --diary-red: #b95b50;

        color: var(--diary-text);
        font-family: "Public Sans", sans-serif;
    }

    .exam-page *,
    .exam-page *::before,
    .exam-page *::after {
        box-sizing: border-box;
    }

    .exam-page .muted-copy {
        color: var(--diary-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .exam-page .section-kicker {
        color: var(--diary-sage);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.25px;
        margin-bottom: 7px;
    }

    .exam-page .section-title {
        color: var(--diary-ink);
        font-size: 17px;
        font-weight: 750;
    }

    /* SIMPLE HEADER */

    .exam-page .page-header {
        padding: 0;
    }

    .exam-page .header-main {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .exam-page .header-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .exam-page .back-button {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--diary-line);
        border-radius: 11px;
        color: var(--diary-ink);
        background: var(--diary-paper);
        text-decoration: none;
        transition: .2s ease;
    }

    .exam-page .back-button:hover {
        color: #fff;
        background: var(--diary-forest);
        border-color: var(--diary-forest);
        transform: translateX(-2px);
    }

    .exam-page .header-copy {
        min-width: 0;
    }

    .exam-page .breadcrumb-text {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
        color: var(--diary-muted);
        font-size: 11px;
    }

    .exam-page .breadcrumb-text a {
        color: var(--diary-sage);
        text-decoration: none;
    }

    .exam-page .breadcrumb-text a:hover {
        color: var(--diary-dark);
        text-decoration: underline;
    }

    .exam-page .breadcrumb-text i {
        font-size: 9px;
    }

    .exam-page .page-title {
        color: var(--diary-ink);
        font-size: 23px;
        font-weight: 750;
        letter-spacing: -.45px;
        overflow-wrap: anywhere;
    }

    .exam-page .page-subtitle {
        margin-top: 5px;
        color: var(--diary-muted);
        font-size: 12px;
    }

    .exam-page .header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        flex-shrink: 0;
    }

    .exam-page .btn {
        min-height: 39px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 650;
        padding: 9px 14px;
        transition: background .2s ease, color .2s ease,
                    border-color .2s ease, transform .2s ease;
    }

    .exam-page .diary-btn-primary {
        color: #fff;
        background: var(--diary-forest);
        border: 1px solid var(--diary-forest);
    }

    .exam-page .diary-btn-primary:hover {
        color: var(--diary-dark);
        background: #f1ead8;
        border-color: #f1ead8;
    }

    .exam-page .diary-btn-light {
        color: var(--diary-ink);
        background: var(--diary-paper);
        border: 1px solid var(--diary-line);
    }

    .exam-page .diary-btn-light:hover {
        color: #fff;
        background: var(--diary-forest);
        border-color: var(--diary-forest);
    }

    .exam-page .diary-btn-outline-green {
        color: var(--diary-green);
        background: transparent;
        border: 1px solid rgba(67, 131, 93, .4);
    }

    .exam-page .diary-btn-outline-green:hover {
        color: #fff;
        background: var(--diary-green);
        border-color: var(--diary-green);
    }

    .exam-page .diary-btn-outline-blue {
        color: var(--diary-blue);
        background: transparent;
        border: 1px solid rgba(66, 107, 138, .4);
    }

    .exam-page .diary-btn-outline-blue:hover {
        color: #fff;
        background: var(--diary-blue);
    }

    .exam-page .diary-btn-outline-amber {
        color: var(--diary-amber);
        background: transparent;
        border: 1px solid rgba(168, 117, 67, .4);
    }

    .exam-page .diary-btn-outline-amber:hover {
        color: #fff;
        background: var(--diary-amber);
    }

    .exam-page .diary-btn-disabled {
        color: var(--diary-muted);
        background: var(--diary-cream);
        border: 1px solid var(--diary-line);
        cursor: not-allowed;
    }

    /* STATUS PILLS */

    .exam-page .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .35px;
        text-transform: uppercase;
    }

    .exam-page .status-dot {
        width: 6px;
        height: 6px;
        flex-shrink: 0;
        border-radius: 50%;
        background: currentColor;
    }

    .exam-page .status-draft {
        color: #737b72;
        background: #eeeee7;
    }

    .exam-page .status-scheduled {
        color: var(--diary-blue);
        background: #eaf1f5;
    }

    .exam-page .status-completed {
        color: var(--diary-green);
        background: #eaf3e9;
    }

    .exam-page .status-cancelled {
        color: var(--diary-red);
        background: #f8eae6;
    }

    /* ALERTS */

    .exam-page .custom-alert {
        border: 1px solid var(--diary-line);
        border-radius: 13px;
        padding: 14px 16px;
        box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
    }

    .exam-page .alert-success {
        color: var(--diary-ink);
        background: #f1f6ed;
    }

    .exam-page .alert-danger {
        color: #7e3933;
        background: #fbefeb;
    }

    .exam-page .alert-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        border-radius: 10px;
    }

    .exam-page .success-icon {
        color: var(--diary-green);
        background: #e0eddb;
    }

    .exam-page .danger-icon {
        color: var(--diary-red);
        background: #f4ded8;
    }

    /* OVERVIEW */

    .exam-page .overview-card,
    .exam-page .timetable-card {
        overflow: hidden;
        background: var(--diary-paper);
        border: 1px solid var(--diary-line);
        border-radius: 15px;
        box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
    }

    .exam-page .overview-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px;
    }

    .exam-page .overview-title {
        color: var(--diary-ink);
        font-size: 18px;
        font-weight: 750;
    }

    .exam-page .overview-type {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid var(--diary-line);
        border-radius: 10px;
        color: var(--diary-ink);
        background: var(--diary-cream);
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .exam-page .overview-type i {
        color: var(--diary-sage);
    }

    .exam-page .overview-divider {
        height: 1px;
        background: var(--diary-line);
    }

    .exam-page .overview-item {
        min-height: 92px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 13px;
        border-right: 1px solid var(--diary-line);
    }

    .exam-page .overview-item-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .exam-page .overview-item-icon.blue {
        color: var(--diary-blue);
        background: #eaf0f3;
    }

    .exam-page .overview-item-icon.purple {
        color: #78658d;
        background: #f0ebf3;
    }

    .exam-page .overview-item-icon.green {
        color: var(--diary-green);
        background: #eaf2e6;
    }

    .exam-page .overview-item-icon.orange {
        color: var(--diary-amber);
        background: #f7eee2;
    }

    .exam-page .overview-item-icon.teal {
        color: #397d77;
        background: #e6f1ed;
    }

    .exam-page .overview-item-icon.slate {
        color: #65756c;
        background: #ecefe8;
    }

    .exam-page .overview-item-copy {
        min-width: 0;
    }

    .exam-page .overview-item-copy span {
        display: block;
        margin-bottom: 4px;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.4;
    }

    .exam-page .overview-item-copy strong {
        display: block;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    /* SECTION HEADING AND PROGRESS */

    .exam-page .section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
    }

    .exam-page .setup-progress {
        width: 175px;
        flex-shrink: 0;
    }

    .exam-page .setup-progress > span {
        display: block;
        margin-bottom: 7px;
        color: var(--diary-muted);
        font-size: 11px;
        font-weight: 650;
        text-align: right;
    }

    .exam-page .setup-progress .progress {
        height: 6px;
        overflow: hidden;
        border-radius: 10px;
        background: #e8e6dc;
    }

    .exam-page .setup-progress .progress-bar {
        border-radius: 10px;
        background: var(--diary-sage);
        transition: width .3s ease;
    }

    /* SETUP CARDS */

    .exam-page .setup-card {
        position: relative;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: var(--diary-paper);
        border: 1px solid var(--diary-line);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(38, 59, 53, .035);
        transition: transform .22s ease, box-shadow .22s ease,
                    border-color .22s ease;
    }

    .exam-page .setup-card::before {
        position: absolute;
        top: 0;
        left: 20px;
        width: 43px;
        height: 3px;
        border-radius: 0 0 4px 4px;
        background: var(--diary-sage);
        content: "";
    }

    .exam-page .setup-card:hover {
        transform: translateY(-3px);
        border-color: #d2d9ca;
        box-shadow: 0 10px 24px rgba(38, 59, 53, .075);
    }

    .exam-page .setup-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 21px 20px 0;
    }

    .exam-page .step-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .exam-page .step-blue {
        color: var(--diary-blue);
        background: #eaf0f3;
    }

    .exam-page .step-green {
        color: var(--diary-green);
        background: #eaf2e6;
    }

    .exam-page .step-orange {
        color: var(--diary-amber);
        background: #f7eee2;
    }

    .exam-page .step-red {
        color: var(--diary-red);
        background: #f8eae6;
    }

    .exam-page .step-number {
        color: #93988a;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .9px;
    }

    .exam-page .setup-card-content {
        flex: 1;
        padding: 18px 20px 19px;
    }

    .exam-page .setup-card-content h5 {
        margin-bottom: 8px;
        color: var(--diary-ink);
        font-size: 15px;
        font-weight: 750;
    }

    .exam-page .setup-card-content p {
        min-height: 60px;
        margin-bottom: 17px;
        color: var(--diary-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .exam-page .setup-count {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 7px;
        padding-top: 13px;
        border-top: 1px dashed var(--diary-line);
    }

    .exam-page .setup-count strong {
        color: var(--diary-ink);
        font-size: 25px;
        font-weight: 750;
        line-height: 1;
    }

    .exam-page .setup-count span {
        color: var(--diary-muted);
        font-size: 11px;
    }

    .exam-page .setup-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin: 0 20px 20px;
        padding: 11px 13px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: color .2s ease, background .2s ease,
                    border-color .2s ease;
    }

    .exam-page .setup-action i {
        transition: transform .2s ease;
    }

    .exam-page .setup-action:hover i {
        transform: translateX(3px);
    }

    .exam-page .action-blue {
        color: var(--diary-blue);
        background: #edf2f4;
    }

    .exam-page .action-blue:hover {
        color: #fff;
        background: var(--diary-blue);
    }

    .exam-page .action-green {
        color: var(--diary-green);
        background: #edf3e9;
    }

    .exam-page .action-green:hover {
        color: #fff;
        background: var(--diary-green);
    }

    .exam-page .action-orange {
        color: var(--diary-amber);
        background: #f7efe5;
    }

    .exam-page .action-orange:hover {
        color: #fff;
        background: var(--diary-amber);
    }

    .exam-page .action-red {
        color: var(--diary-red);
        background: #f8eeea;
    }

    .exam-page .action-red:hover {
        color: #fff;
        background: var(--diary-red);
    }

    /* TIMETABLE */

    .exam-page .timetable-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 21px 24px;
        border-bottom: 1px solid var(--diary-line);
        background: #fffefa;
    }

    .exam-page .timetable-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: var(--diary-sage);
        background: #eaf0e7;
        font-size: 20px;
    }

    .exam-page .entries-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border: 1px solid #dbe6d7;
        border-radius: 9px;
        color: var(--diary-green);
        background: #edf3e9;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .exam-page .timetable-body {
        padding: 24px;
    }

    .exam-page .timetable-option {
        height: 100%;
        display: flex;
        align-items: flex-start;
        gap: 17px;
        padding: 22px;
        border: 1px solid var(--diary-line);
        border-radius: 13px;
        background: var(--diary-paper);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .exam-page .timetable-option:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 19px rgba(38, 59, 53, .055);
    }

    .exam-page .generate-option {
        background: #f8f9f3;
    }

    .exam-page .view-option {
        background: #f6f8f1;
    }

    .exam-page .option-icon {
        width: 45px;
        height: 45px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .exam-page .generate-option .option-icon {
        color: var(--diary-blue);
        background: #e7eef1;
    }

    .exam-page .view-option .option-icon {
        color: var(--diary-green);
        background: #e6eee0;
    }

    .exam-page .option-content {
        min-width: 0;
    }

    .exam-page .option-label {
        display: block;
        margin-bottom: 5px;
        color: var(--diary-muted);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .exam-page .option-content h5 {
        margin-bottom: 8px;
        color: var(--diary-ink);
        font-size: 16px;
        font-weight: 750;
    }

    .exam-page .option-content p {
        margin-bottom: 16px;
        color: var(--diary-muted);
        font-size: 12px;
        line-height: 1.7;
    }

    /* WORKFLOW */

    .exam-page .workflow-note {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 15px;
        border: 1px solid var(--diary-line);
        border-radius: 11px;
        background: var(--diary-cream);
    }

    .exam-page .workflow-icon {
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: var(--diary-amber);
        background: #eee3cf;
    }

    .exam-page .workflow-note strong {
        display: block;
        margin-bottom: 3px;
        color: var(--diary-ink);
        font-size: 12px;
    }

    /* INCOMPLETE SETUP */

    .exam-page .setup-incomplete {
        padding: 35px 20px 28px;
        text-align: center;
    }

    .exam-page .incomplete-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        border: 1px solid #e8dfcc;
        border-radius: 16px;
        color: var(--diary-amber);
        background: #f5eddd;
        font-size: 26px;
    }

    .exam-page .setup-incomplete h5 {
        margin-bottom: 8px;
    }

    .exam-page .setup-incomplete > p {
        max-width: 520px;
        margin: 0 auto;
        font-size: 12px;
        line-height: 1.8;
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .exam-page .overview-item {
            border-bottom: 1px solid var(--diary-line);
        }

        .exam-page .overview-item:nth-child(2n) {
            border-right: 0;
        }

        .exam-page .overview-item:nth-last-child(-n+2) {
            border-bottom: 0;
        }
    }

    @media (max-width: 767.98px) {
        .exam-page .header-main {
            align-items: flex-start;
            flex-direction: column;
        }

        .exam-page .header-actions {
            width: 100%;
            justify-content: flex-start;
            padding-left: 54px;
        }

        .exam-page .overview-top {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .exam-page .overview-type {
            max-width: 100%;
            white-space: normal;
        }

        .exam-page .overview-item {
            min-height: 78px;
            padding: 13px 11px;
        }

        .exam-page .section-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .exam-page .setup-progress {
            width: 100%;
        }

        .exam-page .setup-progress > span {
            text-align: left;
        }

        .exam-page .timetable-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .exam-page .timetable-body {
            padding: 18px;
        }

        .exam-page .entries-badge {
            margin-left: 57px;
        }
    }

    @media (max-width: 575.98px) {
        .exam-page {
            padding-right: 12px !important;
            padding-left: 12px !important;
        }

        .exam-page .page-title {
            font-size: 18px;
        }

        .exam-page .header-title-wrap {
            align-items: flex-start;
            gap: 10px;
        }

        .exam-page .back-button {
            width: 36px;
            height: 36px;
        }

        .exam-page .header-actions {
            padding-left: 46px;
        }

        .exam-page .header-actions .btn {
            flex: 1 1 auto;
        }

        .exam-page .overview-item {
            min-height: 76px;
            border-right: 0 !important;
            border-bottom: 1px solid var(--diary-line) !important;
        }

        .exam-page .overview-item:nth-last-child(-n+2) {
            border-bottom: 1px solid var(--diary-line) !important;
        }

        .exam-page .overview-item:last-child {
            border-bottom: 0 !important;
        }

        .exam-page .overview-item-icon {
            width: 32px;
            height: 32px;
        }

        .exam-page .timetable-option {
            flex-direction: column;
            padding: 18px;
        }

        .exam-page .workflow-note {
            align-items: flex-start;
        }

        .exam-page .entries-badge {
            margin-left: 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .exam-page *,
        .exam-page *::before,
        .exam-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

@endsection