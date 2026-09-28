```blade
@extends('layouts.app')

@section('title', 'Generate Exam Timetable')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Safe timetable collection
    |--------------------------------------------------------------------------
    | The controller should pass $timetables after timetable generation.
    | This fallback prevents an undefined-variable error.
    */
    $timetableRows = $timetables ?? collect();

    $classesCount = $examClasses?->count() ?? 0;
    $subjectsCount = $examSubjects?->count() ?? 0;
    $sessionsCount = $sessions?->count() ?? 0;
    $teachersCount = $activeTeachers?->count() ?? 0;
@endphp

<style>
    .timetable-page {
        max-width: 1600px;
        margin: 0 auto;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {
        background: linear-gradient(135deg, #1677f0 0%, #0d6efd 100%);
        border-radius: 18px;
        padding: 24px 26px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(13, 110, 253, .16);
    }

    .page-header h4 {
        font-weight: 700;
        margin-bottom: 4px;
    }

    .page-header p {
        margin-bottom: 0;
        opacity: .88;
        font-size: 14px;
    }

    .page-header .back-btn {
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.28);
        color: #fff;
        border-radius: 10px;
        padding: 9px 15px;
        text-decoration: none;
        transition: .2s ease;
    }

    .page-header .back-btn:hover {
        background: rgba(255,255,255,.22);
        color: #fff;
        transform: translateY(-1px);
    }

    /* =========================================================
       OVERVIEW CARDS
    ========================================================== */

    .overview-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 4px 14px rgba(0,0,0,.04);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .overview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,.07);
    }

    .overview-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef5ff;
        color: #1677f0;
        font-size: 20px;
        flex-shrink: 0;
    }

    .overview-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .overview-value {
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        margin-top: 2px;
    }

    /* =========================================================
       GENERAL CARD
    ========================================================== */

    .section-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,.04);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .section-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .section-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .section-card-header p {
        margin: 3px 0 0;
        font-size: 12px;
        color: #6c757d;
    }

    .section-card-body {
        padding: 20px;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef5ff;
        color: #1677f0;
        margin-right: 9px;
    }

    /* =========================================================
       INFORMATION GRID
    ========================================================== */

    .info-item {
        padding: 14px 15px;
        background: #f8f9fa;
        border-radius: 11px;
        border: 1px solid #edf0f2;
        height: 100%;
    }

    .info-label {
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .info-value {
        color: #212529;
        font-size: 14px;
        font-weight: 600;
    }

    /* =========================================================
       CLASS CHIPS
    ========================================================== */

    .class-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 9px 12px;
        margin: 0 7px 7px 0;
        font-size: 13px;
        font-weight: 600;
        color: #343a40;
    }

    .class-chip i {
        color: #1677f0;
    }

    .section-badge {
        background: #eaf3ff;
        color: #1677f0;
        border-radius: 6px;
        padding: 3px 7px;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================================
       SESSION TABLE
    ========================================================== */

    .mini-table {
        margin-bottom: 0;
    }

    .mini-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 700;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 14px;
        white-space: nowrap;
    }

    .mini-table tbody td {
        padding: 12px 14px;
        vertical-align: middle;
        font-size: 13px;
        border-color: #f0f1f2;
    }

    .session-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef5ff;
        color: #1677f0;
        border-radius: 7px;
        padding: 5px 9px;
        font-size: 12px;
        font-weight: 700;
    }

    /* =========================================================
       TIMETABLE
    ========================================================== */

    .timetable-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    .timetable-table {
        width: 100%;
        min-width: 1050px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .timetable-table thead th {
        background: #f4f7fb;
        color: #343a40;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        padding: 13px 14px;
        border-bottom: 1px solid #dee2e6;
        white-space: nowrap;
        vertical-align: middle;
    }

    .timetable-table tbody td {
        padding: 13px 14px;
        font-size: 13px;
        color: #343a40;
        border-bottom: 1px solid #edf0f2;
        vertical-align: middle;
        white-space: nowrap;
    }

    .timetable-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .timetable-table tbody tr:hover {
        background: #fafcff;
    }

    .date-cell {
        font-weight: 700;
        color: #212529;
    }

    .day-cell {
        color: #6c757d;
        font-weight: 600;
    }

    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f8f9fa;
        border: 1px solid #e4e7ea;
        border-radius: 7px;
        padding: 5px 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .timetable-class {
        font-weight: 700;
        color: #212529;
    }

    .timetable-section {
        display: inline-block;
        margin-left: 4px;
        color: #6c757d;
        font-size: 12px;
    }

    .subject-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef7f1;
        color: #198754;
        border-radius: 7px;
        padding: 5px 9px;
        font-weight: 700;
    }

    .marks-badge {
        background: #fff4e5;
        color: #b76e00;
        border-radius: 7px;
        padding: 5px 8px;
        font-weight: 700;
    }

    .duration-text {
        color: #495057;
        font-weight: 600;
    }

    .teacher-text {
        font-weight: 600;
        color: #495057;
    }

    .empty-timetable {
        text-align: center;
        padding: 48px 20px;
        color: #6c757d;
    }

    .empty-timetable i {
        font-size: 42px;
        color: #adb5bd;
        display: block;
        margin-bottom: 12px;
    }

    .empty-timetable h6 {
        font-weight: 700;
        color: #495057;
    }

    .table-count-badge {
        background: #eef5ff;
        color: #1677f0;
        border-radius: 20px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    /* =========================================================
       GENERATION FORM
    ========================================================== */

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #343a40;
    }

    .form-control,
    .form-select {
        border-radius: 9px;
        border-color: #dee2e6;
        min-height: 42px;
        font-size: 13px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.10);
    }

    .generation-note {
        background: #f0f7ff;
        border: 1px solid #d7eaff;
        color: #315b85;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 12px;
    }

    /* =========================================================
       TEACHERS
    ========================================================== */

    .teacher-toolbar {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 11px;
        padding: 12px;
        margin-bottom: 15px;
    }

    .teacher-search {
        position: relative;
    }

    .teacher-search i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #8b9298;
        pointer-events: none;
    }

    .teacher-search input {
        padding-left: 36px;
    }

    .teacher-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px;
        height: 100%;
        background: #fff;
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
    }

    .teacher-card:hover {
        border-color: #b7d3f8;
        background: #fbfdff;
    }

    .teacher-card:has(input:checked) {
        border-color: #86b7fe;
        background: #f5f9ff;
        box-shadow: 0 0 0 2px rgba(13,110,253,.06);
    }

    .teacher-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #eef5ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .teacher-name {
        font-size: 13px;
        font-weight: 700;
        color: #212529;
    }

    .teacher-id {
        font-size: 11px;
        color: #6c757d;
        margin-top: 2px;
    }

    .teacher-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .selected-count {
        font-size: 12px;
        font-weight: 700;
        color: #1677f0;
    }

    /* =========================================================
       ACTION AREA
    ========================================================== */

    .action-area {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .generate-btn {
        border: 0;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 700;
        background: #1677f0;
        color: #fff;
        box-shadow: 0 5px 12px rgba(22,119,240,.18);
        transition: .2s ease;
    }

    .generate-btn:hover:not(:disabled) {
        background: #0d6efd;
        transform: translateY(-1px);
    }

    .generate-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        box-shadow: none;
    }

    .replace-option {
        font-size: 12px;
        color: #495057;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 25px 15px;
        text-align: center;
        color: #6c757d;
        border: 1px dashed #dfe3e7;
        border-radius: 11px;
        background: #fafbfc;
    }

    .empty-state i {
        font-size: 28px;
        display: block;
        margin-bottom: 8px;
        color: #adb5bd;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767.98px) {
        .page-header {
            padding: 19px;
        }

        .page-header .back-btn {
            width: 100%;
            text-align: center;
        }

        .section-card-body {
            padding: 15px;
        }

        .section-card-header {
            padding: 15px;
        }

        .action-area {
            align-items: stretch;
        }

        .generate-btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 timetable-page">

    ```blade
{{-- =========================================================
    PAGE HEADER
========================================================== --}}
<div class="page-header mb-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

        <div>
            <h4>
                <i class="bi bi-calendar2-week me-2"></i>
                Generate Exam Timetable
            </h4>

            <p>
                Create and manage the examination schedule for
                <strong>{{ $exam->exam_name }}</strong>.
            </p>
        </div>

        <a href="{{ route('admin.exam-schedules.index', $exam) }}"
           class="back-btn">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Exam
        </a>

    </div>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div
        class="alert alert-success alert-dismissible fade show d-flex align-items-start gap-2 mb-4"
        role="alert"
    >

        <i class="bi bi-check-circle-fill fs-5"></i>

        <div class="flex-grow-1">
            <strong>Success!</strong>
            <div class="mt-1">
                {{ session('success') }}
            </div>
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================== --}}
@if(session('error'))

    <div
        class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4"
        role="alert"
    >

        <i class="bi bi-exclamation-triangle-fill fs-5"></i>

        <div class="flex-grow-1">
            <strong>Error!</strong>
            <div class="mt-1">
                {{ session('error') }}
            </div>
        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div
        class="alert alert-danger alert-dismissible fade show mb-4"
        role="alert"
    >

        <div class="d-flex align-items-start gap-2">

            <i class="bi bi-exclamation-triangle-fill fs-5"></i>

            <div>

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2 ps-3">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>

    </div>

@endif


    {{-- =========================================================
        OVERVIEW CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="overview-card">

                <div class="d-flex align-items-center gap-3">

                    <div class="overview-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                    <div>
                        <div class="overview-label">
                            Academic Year
                        </div>

                        <div class="overview-value">
                            {{ $exam->academic_year ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="overview-card">

                <div class="d-flex align-items-center gap-3">

                    <div class="overview-icon">
                        <i class="bi bi-journal-text"></i>
                    </div>

                    <div>
                        <div class="overview-label">
                            Examination
                        </div>

                        <div class="overview-value">
                            {{ $exam->exam_name ?? '-' }}
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="overview-card">

                <div class="d-flex align-items-center gap-3">

                    <div class="overview-icon">
                        <i class="bi bi-people"></i>
                    </div>

                    <div>
                        <div class="overview-label">
                            Classes
                        </div>

                        <div class="overview-value">
                            {{ $classesCount }}
                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="col-12 col-sm-6 col-xl-3">
            <div class="overview-card">

                <div class="d-flex align-items-center gap-3">

                    <div class="overview-icon">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>
                        <div class="overview-label">
                            Exam Subjects
                        </div>

                        <div class="overview-value">
                            {{ $subjectsCount }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>


    {{-- =========================================================
        EXAM INFORMATION
    ========================================================== --}}
    <div class="section-card">

        <div class="section-card-header">

            <div>
                <h5>
                    <span class="section-icon">
                        <i class="bi bi-info-circle"></i>
                    </span>
                    Exam Information
                </h5>

                <p>
                    Basic information about this examination.
                </p>
            </div>

        </div>

        <div class="section-card-body">

            <div class="row g-3">

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="info-item">
                        <div class="info-label">Academic Year</div>
                        <div class="info-value">
                            {{ $exam->academic_year ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="info-item">
                        <div class="info-label">Exam Name</div>
                        <div class="info-value">
                            {{ $exam->exam_name ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="info-item">
                        <div class="info-label">Exam Type</div>
                        <div class="info-value">
                            {{ $exam->exam_type ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-3">
                    <div class="info-item">

                        <div class="info-label">
                            Examination Period
                        </div>

                        <div class="info-value">
                            @if($exam->start_date)
                                {{ \Carbon\Carbon::parse($exam->start_date)->format('d M Y') }}

                                @if($exam->end_date)
                                    <span class="text-muted mx-1">→</span>
                                    {{ \Carbon\Carbon::parse($exam->end_date)->format('d M Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CLASSES
    ========================================================== --}}
    <div class="section-card">

        <div class="section-card-header">

            <div>
                <h5>
                    <span class="section-icon">
                        <i class="bi bi-people"></i>
                    </span>
                    Classes Included
                </h5>

                <p>
                    Classes selected for this examination.
                </p>
            </div>

            <span class="table-count-badge">
                {{ $classesCount }} Classes
            </span>

        </div>

        <div class="section-card-body">

            @if($classesCount > 0)

                <div>

                    @foreach($examClasses as $examClass)

                        <span class="class-chip">

                            <i class="bi bi-mortarboard-fill"></i>

                            <span>
                                {{ $examClass->schoolClass?->class_name ?? 'Class' }}
                            </span>

                            @if($examClass->schoolClass?->section)
                                <span class="section-badge">
                                    {{ $examClass->schoolClass->section }}
                                </span>
                            @endif

                        </span>

                    @endforeach

                </div>

            @else

                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    No classes have been assigned to this examination.
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        EXAM SESSIONS
    ========================================================== --}}
    <div class="section-card">

        <div class="section-card-header">

            <div>
                <h5>
                    <span class="section-icon">
                        <i class="bi bi-clock"></i>
                    </span>
                    Exam Sessions
                </h5>

                <p>
                    Sessions available for timetable generation.
                </p>
            </div>

            <span class="table-count-badge">
                {{ $sessionsCount }} Sessions
            </span>

        </div>

        <div class="section-card-body p-0">

            @if($sessionsCount > 0)

                <div class="table-responsive">

                    <table class="table mini-table">

                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th>Session</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($sessions as $index => $session)

                                <tr>

                                    <td class="text-muted">
                                        {{ $index + 1 }}
                                    </td>

                                    <td>
                                        <span class="session-badge">
                                            <i class="bi bi-clock"></i>
                                            {{ $session->session_name ?? 'Session' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $session->start_time
                                            ? \Carbon\Carbon::parse($session->start_time)->format('h:i A')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $session->end_time
                                            ? \Carbon\Carbon::parse($session->end_time)->format('h:i A')
                                            : '-' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-state m-3">
                    <i class="bi bi-clock"></i>
                    No exam sessions are available.
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        GENERATED TIMETABLE
    ========================================================== --}}
    <div class="section-card">

        <div class="section-card-header">

            <div>
                <h5>
                    <span class="section-icon">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                    Generated Examination Timetable
                </h5>

                <p>
                    View the generated examination schedule in a single table.
                </p>
            </div>

            <span class="table-count-badge">
                {{ $timetableRows->count() }} Entries
            </span>

        </div>

        <div class="section-card-body p-0">

            @if($timetableRows->count() > 0)

                <div class="timetable-wrapper">

                    <table class="table timetable-table">

                        <thead>
                            <tr>

                                <th>#</th>

                                <th>
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Date
                                </th>

                                <th>Day</th>

                                <th>
                                    <i class="bi bi-clock me-1"></i>
                                    Session
                                </th>

                                <th>Time</th>

                                <th>
                                    <i class="bi bi-people me-1"></i>
                                    Class
                                </th>

                                <th>
                                    <i class="bi bi-book me-1"></i>
                                    Subject
                                </th>

                                <th>Max Marks</th>

                                <th>Duration</th>

                                <th>
                                    <i class="bi bi-person-badge me-1"></i>
                                    Teacher
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @foreach($timetableRows as $index => $row)

                                @php
                                    /*
                                    |--------------------------------------------------------------------------
                                    | Date
                                    |--------------------------------------------------------------------------
                                    */
                                    $rowDate =
                                        $row->exam_date
                                        ?? $row->date
                                        ?? $row->scheduled_date
                                        ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Session
                                    |--------------------------------------------------------------------------
                                    */
                                    $sessionName =
                                        $row->session?->session_name
                                        ?? $row->examSession?->session_name
                                        ?? $row->session_name
                                        ?? 'Session';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Session times
                                    |--------------------------------------------------------------------------
                                    */
                                    $startTime =
                                        $row->session?->start_time
                                        ?? $row->examSession?->start_time
                                        ?? $row->start_time
                                        ?? null;

                                    $endTime =
                                        $row->session?->end_time
                                        ?? $row->examSession?->end_time
                                        ?? $row->end_time
                                        ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Class
                                    |--------------------------------------------------------------------------
                                    */
                                    $className =
                                        $row->schoolClass?->class_name
                                        ?? $row->class_name
                                        ?? $row->class
                                        ?? '-';

                                    $section =
                                        $row->schoolClass?->section
                                        ?? $row->section
                                        ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Subject
                                    |--------------------------------------------------------------------------
                                    */
                                    $subjectName =
                                        $row->subject?->subject_name
                                        ?? $row->examSubject?->subject?->subject_name
                                        ?? $row->subject_name
                                        ?? '-';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Marks
                                    |--------------------------------------------------------------------------
                                    */
                                    $maximumMarks =
                                        $row->maximum_marks
                                        ?? $row->max_marks
                                        ?? $row->examSubject?->maximum_marks
                                        ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Duration
                                    |--------------------------------------------------------------------------
                                    */
                                    $duration =
                                        $row->duration_minutes
                                        ?? $row->examSubject?->duration_minutes
                                        ?? null;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Teacher
                                    |--------------------------------------------------------------------------
                                    */
                                    $teacherName =
                                        $row->teacher?->name
                                        ?? $row->teacher?->full_name
                                        ?? $row->teacher_name
                                        ?? '-';

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Day
                                    |--------------------------------------------------------------------------
                                    */
                                    $dayName = $rowDate
                                        ? \Carbon\Carbon::parse($rowDate)->format('l')
                                        : '-';
                                @endphp

                                <tr>

                                    <td class="text-muted">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="date-cell">

                                        @if($rowDate)
                                            {{ \Carbon\Carbon::parse($rowDate)->format('d M Y') }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td class="day-cell">
                                        {{ $dayName }}
                                    </td>

                                    <td>

                                        <span class="session-badge">
                                            <i class="bi bi-clock"></i>
                                            {{ $sessionName }}
                                        </span>

                                    </td>

                                    <td>

                                        <span class="time-badge">

                                            <i class="bi bi-clock-history"></i>

                                            @if($startTime)
                                                {{ \Carbon\Carbon::parse($startTime)->format('h:i A') }}
                                            @else
                                                -
                                            @endif

                                            @if($endTime)
                                                <span class="text-muted">–</span>
                                                {{ \Carbon\Carbon::parse($endTime)->format('h:i A') }}
                                            @endif

                                        </span>

                                    </td>

                                    <td>

                                        <span class="timetable-class">
                                            {{ $className }}
                                        </span>

                                        @if($section)
                                            <span class="timetable-section">
                                                ({{ $section }})
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <span class="subject-badge">
                                            <i class="bi bi-book"></i>
                                            {{ $subjectName }}
                                        </span>

                                    </td>

                                    <td>

                                        @if($maximumMarks !== null)
                                            <span class="marks-badge">
                                                {{ $maximumMarks }}
                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td>

                                        @if($duration !== null)
                                            <span class="duration-text">
                                                {{ $duration }} min
                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td>

                                        <span class="teacher-text">
                                            <i class="bi bi-person me-1 text-muted"></i>
                                            {{ $teacherName }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="empty-timetable">

                    <i class="bi bi-calendar-x"></i>

                    <h6>No Timetable Generated Yet</h6>

                    <div class="small">
                        Configure the generation settings below and generate
                        the examination timetable.
                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        GENERATION SETTINGS
    ========================================================== --}}
    <div class="section-card">

        <div class="section-card-header">

            <div>
                <h5>
                    <span class="section-icon">
                        <i class="bi bi-sliders"></i>
                    </span>
                    Timetable Generation Settings
                </h5>

                <p>
                    Configure how papers should be distributed across the
                    examination days.
                </p>
            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.exam-schedules.generate', $exam) }}"
            id="generateTimetableForm"
        >

            @csrf

            <div class="section-card-body">

                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-4 col-lg-3">

                        <label for="papers_per_day" class="form-label">
                            Papers Per Day
                        </label>

                        <select
                            name="papers_per_day"
                            id="papers_per_day"
                            class="form-select"
                            required
                        >

                            <option value="1"
                                {{ old('papers_per_day', 1) == 1 ? 'selected' : '' }}>
                                1 Paper Per Day
                            </option>

                            <option value="2"
                                {{ old('papers_per_day') == 2 ? 'selected' : '' }}>
                                2 Papers Per Day
                            </option>

                            <option value="3"
                                {{ old('papers_per_day') == 3 ? 'selected' : '' }}>
                                3 Papers Per Day
                            </option>

                        </select>

                    </div>


                    <div class="col-12 col-md-8 col-lg-5">

                        <div class="generation-note">

                            <i class="bi bi-lightbulb me-1"></i>

                            The system will automatically distribute the
                            examination papers according to the available
                            dates, sessions and holidays.

                        </div>

                    </div>


                    <div class="col-12 col-lg-4">

                        <div class="form-check replace-option mt-2">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="replace_existing"
                                value="1"
                                id="replace_existing"
                                {{ old('replace_existing') ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="replace_existing"
                            >
                                Replace existing timetable
                            </label>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                TEACHERS
            ====================================================== --}}
            <div class="section-card-header">

                <div>
                    <h5>
                        <span class="section-icon">
                            <i class="bi bi-person-badge"></i>
                        </span>
                        Select Teachers / Supervisors
                    </h5>

                    <p>
                        Select teachers who can be assigned to the generated
                        timetable.
                    </p>
                </div>

                <span class="selected-count">
                    <span id="selectedTeacherCount">0</span>
                    Selected
                </span>

            </div>


            <div class="section-card-body">

                @if($teachersCount > 0)

                    {{-- Teacher toolbar --}}
                    <div class="teacher-toolbar">

                        <div class="row g-2 align-items-center">

                            <div class="col-12 col-md-6">

                                <div class="teacher-search">

                                    <i class="bi bi-search"></i>

                                    <input
                                        type="text"
                                        id="teacherSearch"
                                        class="form-control"
                                        placeholder="Search teacher by name or employee ID..."
                                    >

                                </div>

                            </div>

                            <div class="col-12 col-md-6 text-md-end">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary me-1"
                                    id="selectAllTeachers"
                                >
                                    <i class="bi bi-check2-all me-1"></i>
                                    Select All Visible
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    id="clearAllTeachers"
                                >
                                    <i class="bi bi-x-lg me-1"></i>
                                    Clear All
                                </button>

                            </div>

                        </div>

                    </div>


                    {{-- Teacher cards --}}
                    <div class="row g-3" id="teacherList">

                        @foreach($activeTeachers as $teacher)

                            @php
                                $teacherName =
                                    $teacher->name
                                    ?? $teacher->full_name
                                    ?? trim(
                                        ($teacher->first_name ?? '') . ' ' .
                                        ($teacher->middle_name ?? '') . ' ' .
                                        ($teacher->last_name ?? '')
                                    );

                                $employeeId =
                                    $teacher->employee_id
                                    ?? $teacher->employee_code
                                    ?? $teacher->emp_id
                                    ?? '-';

                                $initial =
                                    strtoupper(
                                        substr(
                                            trim($teacherName ?: 'T'),
                                            0,
                                            1
                                        )
                                    );
                            @endphp

                            <div
                                class="col-12 col-sm-6 col-lg-4 col-xl-3 teacher-item"
                                data-teacher-search="{{ strtolower($teacherName . ' ' . $employeeId) }}"
                            >

                                <label class="teacher-card w-100">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="teacher-avatar">
                                            {{ $initial }}
                                        </div>

                                        <div class="flex-grow-1 min-width-0">

                                            <div class="teacher-name text-truncate">
                                                {{ $teacherName ?: 'Teacher' }}
                                            </div>

                                            <div class="teacher-id">
                                                Employee ID:
                                                {{ $employeeId }}
                                            </div>

                                        </div>

                                        <input
                                            type="checkbox"
                                            class="form-check-input teacher-checkbox teacher-check"
                                            name="teacher_ids[]"
                                            value="{{ $teacher->id }}"
                                            {{ in_array(
                                                $teacher->id,
                                                old('teacher_ids', [])
                                            ) ? 'checked' : '' }}
                                        >

                                    </div>

                                </label>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="empty-state">

                        <i class="bi bi-person-x"></i>

                        <div class="fw-semibold mb-1">
                            No Active Teachers Found
                        </div>

                        <div class="small">
                            Please add or activate teachers before generating
                            the examination timetable.
                        </div>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                ACTION AREA
            ====================================================== --}}
            <div class="action-area">

                <div class="small text-muted">

                    <i class="bi bi-info-circle me-1"></i>

                    Make sure classes, sessions and teachers are configured
                    before generating the timetable.

                </div>

                <button
                    type="submit"
                    class="generate-btn"
                    id="generateBtn"
                    {{ ($classesCount === 0 || $sessionsCount === 0 || $teachersCount === 0)
                        ? 'disabled'
                        : '' }}
                >

                    <span id="generateBtnText">
                        <i class="bi bi-calendar-plus me-1"></i>
                        Generate Timetable
                    </span>

                    <span
                        id="generateBtnLoading"
                        class="d-none"
                    >
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"
                        ></span>

                        Generating...
                    </span>

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const teacherChecks = document.querySelectorAll('.teacher-check');
    const selectedTeacherCount =
        document.getElementById('selectedTeacherCount');

    const selectAllButton =
        document.getElementById('selectAllTeachers');

    const clearAllButton =
        document.getElementById('clearAllTeachers');

    const teacherSearch =
        document.getElementById('teacherSearch');

    const generateForm =
        document.getElementById('generateTimetableForm');

    const generateBtn =
        document.getElementById('generateBtn');

    const generateBtnText =
        document.getElementById('generateBtnText');

    const generateBtnLoading =
        document.getElementById('generateBtnLoading');

    const replaceExisting =
        document.getElementById('replace_existing');


    /* =========================================================
       UPDATE SELECTED TEACHER COUNT
    ========================================================== */

    function updateTeacherCount() {

        const checked =
            document.querySelectorAll('.teacher-check:checked').length;

        if (selectedTeacherCount) {
            selectedTeacherCount.textContent = checked;
        }
    }


    /* =========================================================
       TEACHER CHECKBOX EVENTS
    ========================================================== */

    teacherChecks.forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {
            updateTeacherCount();
        });

    });


    /* =========================================================
       SELECT ALL VISIBLE
    ========================================================== */

    if (selectAllButton) {

        selectAllButton.addEventListener('click', function () {

            document
                .querySelectorAll('.teacher-item')
                .forEach(function (item) {

                    if (item.style.display !== 'none') {

                        const checkbox =
                            item.querySelector('.teacher-check');

                        if (checkbox) {
                            checkbox.checked = true;
                        }

                    }

                });

            updateTeacherCount();

        });

    }


    /* =========================================================
       CLEAR ALL
    ========================================================== */

    if (clearAllButton) {

        clearAllButton.addEventListener('click', function () {

            document
                .querySelectorAll('.teacher-check')
                .forEach(function (checkbox) {

                    checkbox.checked = false;

                });

            updateTeacherCount();

        });

    }


    /* =========================================================
       TEACHER SEARCH
    ========================================================== */

    if (teacherSearch) {

        teacherSearch.addEventListener('input', function () {

            const search =
                this.value.toLowerCase().trim();

            document
                .querySelectorAll('.teacher-item')
                .forEach(function (item) {

                    const value =
                        item.getAttribute('data-teacher-search') || '';

                    item.style.display =
                        value.includes(search)
                            ? ''
                            : 'none';

                });

        });

    }


    /* =========================================================
       FORM SUBMISSION
    ========================================================== */

    if (generateForm) {

        generateForm.addEventListener('submit', function (event) {

            const selectedTeachers =
                document.querySelectorAll(
                    '.teacher-check:checked'
                ).length;

            if (selectedTeachers === 0) {

                event.preventDefault();

                alert(
                    'Please select at least one teacher before generating the timetable.'
                );

                return;
            }


            if (
                replaceExisting &&
                replaceExisting.checked
            ) {

                const confirmed = confirm(
                    'Existing timetable entries will be replaced. Do you want to continue?'
                );

                if (!confirmed) {
                    event.preventDefault();
                    return;
                }

            }


            if (generateBtn) {

                generateBtn.disabled = true;

                if (generateBtnText) {
                    generateBtnText.classList.add('d-none');
                }

                if (generateBtnLoading) {
                    generateBtnLoading.classList.remove('d-none');
                }

            }

        });

    }


    /* =========================================================
       INITIAL COUNT
    ========================================================== */

    updateTeacherCount();

});
</script>

@endsection
