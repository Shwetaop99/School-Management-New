
@extends('layouts.app')

@section('title', 'Exam Management')

@section('content')

@php
    $totalExams = $exams->count();

    $draftExams = $exams->where('status', 'draft')->count();

    $scheduledExams = $exams->where('status', 'scheduled')->count();

    $completedExams = $exams->where('status', 'completed')->count();
@endphp


<div class="container-fluid py-4 exam-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="exam-header mb-4">

        <div class="header-left">

            <div class="header-icon">
                <i class="bi bi-journal-text"></i>
            </div>

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <h4 class="page-title mb-0">
                        Exam Management
                    </h4>

                    <span class="module-badge">
                        <i class="bi bi-shield-check me-1"></i>
                        Academic
                    </span>

                </div>

                <p class="page-subtitle mb-0">
                    Manage examinations, classes, subjects, sessions and timetables.
                </p>

            </div>

        </div>


        <div class="header-actions">

            <a href="{{ route('admin.exams.create') }}"
               class="btn create-exam-btn">

                <i class="bi bi-plus-lg me-2"></i>

                Create Exam

            </a>

        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert custom-success-alert alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-center">

                <div class="alert-icon success">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div>

                    <div class="alert-title">
                        Success
                    </div>

                    <div class="alert-text">
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
         ERROR MESSAGES
    ========================================================== --}}
    @if($errors->any())

        <div class="alert custom-error-alert alert-dismissible fade show mb-4"
             role="alert">

            <div class="d-flex align-items-start">

                <div class="alert-icon danger">
                    <i class="bi bi-exclamation-lg"></i>
                </div>

                <div>

                    <div class="alert-title">
                        Please correct the following
                    </div>

                    <ul class="mb-0 ps-3 alert-text">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

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
         SUMMARY CARDS
    ========================================================== --}}
    <div class="summary-grid mb-4">


        {{-- Total --}}
        <div class="summary-card">

            <div class="summary-icon blue">
                <i class="bi bi-journal-text"></i>
            </div>

            <div class="summary-content">

                <div class="summary-label">
                    Total Exams
                </div>

                <div class="summary-value">
                    {{ $totalExams }}
                </div>

                <div class="summary-note">
                    All examinations
                </div>

            </div>

        </div>


        {{-- Draft --}}
        <div class="summary-card">

            <div class="summary-icon gray">
                <i class="bi bi-file-earmark"></i>
            </div>

            <div class="summary-content">

                <div class="summary-label">
                    Draft
                </div>

                <div class="summary-value">
                    {{ $draftExams }}
                </div>

                <div class="summary-note">
                    Still being configured
                </div>

            </div>

        </div>


        {{-- Scheduled --}}
        <div class="summary-card">

            <div class="summary-icon green">
                <i class="bi bi-calendar-check"></i>
            </div>

            <div class="summary-content">

                <div class="summary-label">
                    Scheduled
                </div>

                <div class="summary-value">
                    {{ $scheduledExams }}
                </div>

                <div class="summary-note">
                    Upcoming examinations
                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="summary-card">

            <div class="summary-icon purple">
                <i class="bi bi-check2-circle"></i>
            </div>

            <div class="summary-content">

                <div class="summary-label">
                    Completed
                </div>

                <div class="summary-value">
                    {{ $completedExams }}
                </div>

                <div class="summary-note">
                    Finished examinations
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         EXAM LIST CARD
    ========================================================== --}}
    <div class="exam-list-card">


        {{-- =====================================================
             CARD HEADER
        ====================================================== --}}
        <div class="exam-list-header">

            <div class="list-heading">

                <div class="list-heading-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>

                    <h5 class="list-title mb-0">
                        Examination List
                    </h5>

                    <p class="list-subtitle mb-0">
                        View and manage all examinations
                    </p>

                </div>

            </div>


            <div class="exam-count">

                <i class="bi bi-journal-bookmark me-1"></i>

                {{ $totalExams }}

                {{ Str::plural('Exam', $totalExams) }}

            </div>

        </div>


        {{-- =====================================================
             CONTENT
        ====================================================== --}}
        <div class="exam-list-body">

            @if($exams->isEmpty())

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}
                <div class="empty-state">

                    <div class="empty-icon">

                        <i class="bi bi-journal-x"></i>

                    </div>


                    <h5 class="empty-title">
                        No Exams Found
                    </h5>


                    <p class="empty-description">

                        There are currently no examinations in the system.
                        Create your first exam to start configuring classes,
                        subjects, sessions and timetables.

                    </p>


                    <a href="{{ route('admin.exams.create') }}"
                       class="btn btn-primary empty-button">

                        <i class="bi bi-plus-circle me-1"></i>

                        Create Your First Exam

                    </a>

                </div>


            @else


                {{-- =================================================
                     TABLE
                ================================================== --}}
                <div class="table-responsive">

                    <table class="table exam-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="number-column ps-4">
                                    #
                                </th>

                                <th>
                                    Examination
                                </th>

                                <th>
                                    Academic Year
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Exam Period
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($exams as $exam)

                                @php

                                    $statusClass = match($exam->status) {

                                        'draft' =>
                                            'status-draft',

                                        'scheduled' =>
                                            'status-scheduled',

                                        'completed' =>
                                            'status-completed',

                                        'cancelled' =>
                                            'status-cancelled',

                                        default =>
                                            'status-draft',

                                    };


                                    $statusIcon = match($exam->status) {

                                        'draft' =>
                                            'bi-file-earmark',

                                        'scheduled' =>
                                            'bi-calendar-check',

                                        'completed' =>
                                            'bi-check-circle',

                                        'cancelled' =>
                                            'bi-x-circle',

                                        default =>
                                            'bi-circle',

                                    };

                                @endphp


                                <tr>


                                    {{-- =================================================
                                         NUMBER
                                    ================================================== --}}
                                    <td class="ps-4">

                                        <div class="exam-number">

                                            {{ $loop->iteration }}

                                        </div>

                                    </td>


                                    {{-- =================================================
                                         EXAM NAME
                                    ================================================== --}}
                                    <td>

                                        <div class="exam-name-wrap">

                                            <div class="exam-row-icon">

                                                <i class="bi bi-journal-bookmark"></i>

                                            </div>


                                            <div class="exam-name-content">

                                                <div class="exam-name">

                                                    {{ $exam->exam_name }}

                                                </div>


                                                <div class="exam-id">

                                                    <i class="bi bi-hash"></i>

                                                    {{ $exam->id }}

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                         ACADEMIC YEAR
                                    ================================================== --}}
                                    <td>

                                        <div class="academic-year">

                                            <div class="small-cell-icon">
                                                <i class="bi bi-calendar3"></i>
                                            </div>

                                            <span>
                                                {{ $exam->academic_year }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                         TYPE
                                    ================================================== --}}
                                    <td>

                                        <span class="type-badge">

                                            <i class="bi bi-bookmark-star"></i>

                                            {{ $exam->exam_type }}

                                        </span>

                                    </td>


                                    {{-- =================================================
                                         EXAM PERIOD
                                    ================================================== --}}
                                    <td>

                                        <div class="period-wrap">

                                            <div class="period-start">

                                                <i class="bi bi-calendar-event me-1"></i>

                                                {{ $exam->start_date?->format('d M Y') ?? '-' }}

                                            </div>


                                            <div class="period-end">

                                                <i class="bi bi-arrow-right me-1"></i>

                                                {{ $exam->end_date?->format('d M Y') ?? 'End date not set' }}

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                         STATUS
                                    ================================================== --}}
                                    <td>

                                        <span class="status-badge {{ $statusClass }}">

                                            <span class="status-dot"></span>

                                            <i class="bi {{ $statusIcon }}"></i>

                                            {{ ucfirst($exam->status) }}

                                        </span>

                                    </td>


                                    {{-- =================================================
                                         ACTIONS
                                    ================================================== --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="action-btn"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                title="Exam Actions"
                                            >

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">


                                                {{-- View --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.exams.show', $exam) }}"
                                                    >

                                                        <span class="dropdown-icon view">
                                                            <i class="bi bi-eye"></i>
                                                        </span>

                                                        <span>
                                                            View Exam
                                                        </span>

                                                    </a>

                                                </li>


                                                {{-- Edit --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.exams.edit', $exam) }}"
                                                    >

                                                        <span class="dropdown-icon edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </span>

                                                        <span>
                                                            Edit Exam
                                                        </span>

                                                    </a>

                                                </li>


                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.exams.destroy', $exam) }}"
                                                        onsubmit="return confirm('Are you sure you want to delete this exam? All related exam setup and timetable data will also be removed.');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="submit"
                                                            class="dropdown-item delete-item"
                                                        >

                                                            <span class="dropdown-icon delete">
                                                                <i class="bi bi-trash"></i>
                                                            </span>

                                                            <span>
                                                                Delete Exam
                                                            </span>

                                                        </button>

                                                    </form>

                                                </li>


                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

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
        --primary-dark: #0f5fc4;
        --soft-blue: #eef6ff;

        --text-dark: #1f2937;
        --text-muted: #6b7280;

        --border-color: #e5eaf0;

        --page-bg: #f7f9fc;
    }


    /* =========================================================
       PAGE
    ========================================================== */

    .exam-page {
        max-width: 1500px;
        margin: 0 auto;
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .exam-header {
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f7fbff 100%
        );

        border: 1px solid var(--border-color);

        border-radius: 18px;

        padding: 21px 24px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 20px;

        box-shadow:
            0 5px 22px rgba(15, 23, 42, .045);
    }


    .header-left {
        display: flex;

        align-items: center;

        gap: 14px;
    }


    .header-icon {
        width: 52px;
        height: 52px;

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

        font-weight: 750;

        letter-spacing: -.3px;
    }


    .page-subtitle {
        color: var(--text-muted);

        font-size: 12.5px;

        line-height: 1.5;
    }


    .module-badge {
        display: inline-flex;

        align-items: center;

        padding: 4px 8px;

        border-radius: 20px;

        background: #f1f5f9;

        border: 1px solid #e2e8f0;

        color: #64748b;

        font-size: 9.5px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .4px;
    }


    .create-exam-btn {
        min-height: 43px;

        padding: 9px 18px;

        border: 0;

        border-radius: 10px;

        background: var(--primary-blue);

        color: #fff;

        font-size: 12.5px;

        font-weight: 700;

        box-shadow:
            0 5px 14px rgba(22,119,240,.20);

        transition: all .2s ease;
    }


    .create-exam-btn:hover {
        background: var(--primary-dark);

        color: #fff;

        transform: translateY(-1px);

        box-shadow:
            0 7px 18px rgba(22,119,240,.27);
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .custom-success-alert,
    .custom-error-alert {
        position: relative;

        border: 0;

        border-radius: 13px;

        padding: 13px 45px 13px 14px;
    }


    .custom-success-alert {
        background: #f0fff7;

        border-left: 4px solid #198754;

        color: #146c43;
    }


    .custom-error-alert {
        background: #fff5f5;

        border-left: 4px solid #dc3545;

        color: #842029;
    }


    .alert-icon {
        width: 35px;
        height: 35px;

        border-radius: 10px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-right: 11px;

        flex-shrink: 0;
    }


    .alert-icon.success {
        background: rgba(25,135,84,.12);

        color: #198754;
    }


    .alert-icon.danger {
        background: rgba(220,53,69,.12);

        color: #dc3545;
    }


    .alert-title {
        font-size: 12px;

        font-weight: 700;

        margin-bottom: 2px;
    }


    .alert-text {
        font-size: 11.5px;

        line-height: 1.6;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 15px;
    }


    .summary-card {
        background: #fff;

        border: 1px solid var(--border-color);

        border-radius: 15px;

        padding: 17px;

        display: flex;

        align-items: center;

        gap: 13px;

        box-shadow:
            0 4px 17px rgba(15,23,42,.035);

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .summary-card:hover {
        transform: translateY(-3px);

        box-shadow:
            0 9px 24px rgba(15,23,42,.075);
    }


    .summary-icon {
        width: 44px;
        height: 44px;

        border-radius: 12px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 18px;

        flex-shrink: 0;
    }


    .summary-icon.blue {
        background: #eaf3ff;

        color: #1677f0;
    }


    .summary-icon.gray {
        background: #f1f3f5;

        color: #6c757d;
    }


    .summary-icon.green {
        background: #eaf8f0;

        color: #198754;
    }


    .summary-icon.purple {
        background: #f2edff;

        color: #7952d6;
    }


    .summary-label {
        color: #8b95a3;

        font-size: 9.5px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .55px;

        margin-bottom: 3px;
    }


    .summary-value {
        color: var(--text-dark);

        font-size: 20px;

        line-height: 1.1;

        font-weight: 750;
    }


    .summary-note {
        color: #98a2af;

        font-size: 10px;

        margin-top: 3px;
    }


    /* =========================================================
       EXAM LIST CARD
    ========================================================== */

    .exam-list-card {
        background: #fff;

        border: 1px solid var(--border-color);

        border-radius: 18px;

        overflow: hidden;

        box-shadow:
            0 6px 25px rgba(15,23,42,.05);
    }


    .exam-list-header {
        padding: 18px 22px;

        border-bottom: 1px solid var(--border-color);

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;
    }


    .list-heading {
        display: flex;

        align-items: center;

        gap: 11px;
    }


    .list-heading-icon {
        width: 37px;
        height: 37px;

        border-radius: 10px;

        background: var(--soft-blue);

        color: var(--primary-blue);

        display: flex;

        align-items: center;

        justify-content: center;
    }


    .list-title {
        color: var(--text-dark);

        font-size: 16px;

        font-weight: 750;
    }


    .list-subtitle {
        color: #98a2af;

        font-size: 10.5px;

        margin-top: 2px;
    }


    .exam-count {
        display: inline-flex;

        align-items: center;

        padding: 7px 11px;

        border-radius: 20px;

        border: 1px solid #dfe5eb;

        background: #f8fafc;

        color: #5d6978;

        font-size: 10.5px;

        font-weight: 700;

        white-space: nowrap;
    }


    .exam-count i {
        color: var(--primary-blue);
    }


    .exam-list-body {
        width: 100%;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .exam-table {
        min-width: 1000px;
    }


    .exam-table thead th {
        background: #f8fafc;

        border-bottom: 1px solid var(--border-color);

        color: #697586;

        font-size: 10px;

        font-weight: 750;

        text-transform: uppercase;

        letter-spacing: .4px;

        padding: 13px 13px;

        white-space: nowrap;
    }


    .exam-table tbody td {
        padding: 15px 13px;

        border-color: #edf0f3;

        color: #465261;

        font-size: 12px;
    }


    .exam-table tbody tr {
        transition: background .15s ease;
    }


    .exam-table tbody tr:hover {
        background: #fbfdff;
    }


    .number-column {
        width: 55px;
    }


    /* =========================================================
       NUMBER
    ========================================================== */

    .exam-number {
        width: 33px;
        height: 33px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;

        background: #f3f6f9;

        color: #687585;

        font-size: 10.5px;

        font-weight: 750;
    }


    /* =========================================================
       EXAM NAME
    ========================================================== */

    .exam-name-wrap {
        display: flex;

        align-items: center;

        gap: 11px;

        min-width: 210px;
    }


    .exam-row-icon {
        width: 39px;
        height: 39px;

        border-radius: 10px;

        background: var(--soft-blue);

        color: var(--primary-blue);

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

        font-size: 16px;
    }


    .exam-name-content {
        min-width: 0;
    }


    .exam-name {
        color: #263244;

        font-size: 12.5px;

        font-weight: 700;

        margin-bottom: 3px;

        white-space: nowrap;
    }


    .exam-id {
        color: #9aa3af;

        font-size: 9.5px;
    }


    /* =========================================================
       ACADEMIC YEAR
    ========================================================== */

    .academic-year {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        color: #465261;

        font-size: 11.5px;

        font-weight: 650;

        white-space: nowrap;
    }


    .small-cell-icon {
        width: 27px;
        height: 27px;

        border-radius: 7px;

        background: #f5f7fa;

        color: #7d8896;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 11px;
    }


    /* =========================================================
       TYPE
    ========================================================== */

    .type-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 6px 9px;

        border-radius: 8px;

        border: 1px solid #dfe5eb;

        background: #f8fafc;

        color: #596575;

        font-size: 10px;

        font-weight: 700;

        white-space: nowrap;
    }


    .type-badge i {
        color: var(--primary-blue);
    }


    /* =========================================================
       PERIOD
    ========================================================== */

    .period-wrap {
        white-space: nowrap;
    }


    .period-start {
        color: #354152;

        font-size: 11.5px;

        font-weight: 650;

        margin-bottom: 4px;
    }


    .period-start i {
        color: var(--primary-blue);
    }


    .period-end {
        color: #9aa3af;

        font-size: 9.8px;
    }


    .period-end i {
        color: #a4adb9;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 9.8px;

        font-weight: 750;

        white-space: nowrap;
    }


    .status-dot {
        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
    }


    .status-draft {
        background: #f1f3f5;

        color: #6c757d;
    }


    .status-scheduled {
        background: #eaf3ff;

        color: #1677f0;
    }


    .status-completed {
        background: #eaf8f0;

        color: #198754;
    }


    .status-cancelled {
        background: #fff0f1;

        color: #dc3545;
    }


    /* =========================================================
       ACTION BUTTON
    ========================================================== */

    .action-btn {
        width: 35px;
        height: 35px;

        border: 1px solid #dce3eb;

        border-radius: 9px;

        background: #fff;

        color: #687585;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        transition: all .2s ease;
    }


    .action-btn:hover,
    .action-btn:focus {
        background: var(--soft-blue);

        border-color: #bcd8fb;

        color: var(--primary-blue);

        box-shadow: none;
    }


    /* =========================================================
       DROPDOWN
    ========================================================== */

    .dropdown-menu {
        min-width: 185px;

        padding: 6px;

        border: 1px solid #e3e8ee;

        border-radius: 11px;

        box-shadow:
            0 12px 32px rgba(15,23,42,.13);
    }


    .dropdown-item {
        display: flex;

        align-items: center;

        gap: 9px;

        padding: 8px 9px;

        border-radius: 7px;

        color: #465261;

        font-size: 11.5px;

        font-weight: 550;
    }


    .dropdown-item:hover {
        background: #f4f8fc;
    }


    .dropdown-icon {
        width: 27px;
        height: 27px;

        border-radius: 7px;

        display: inline-flex;

        align-items: center;

        justify-content: center;
    }


    .dropdown-icon.view {
        background: #eaf3ff;

        color: #1677f0;
    }


    .dropdown-icon.edit {
        background: #fff4df;

        color: #d58a0b;
    }


    .dropdown-icon.delete {
        background: #fff0f1;

        color: #dc3545;
    }


    .delete-item {
        color: #dc3545;
    }


    .delete-item:hover {
        background: #fff5f5;

        color: #dc3545;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        text-align: center;

        padding: 70px 20px;
    }


    .empty-icon {
        width: 80px;
        height: 80px;

        margin: 0 auto 18px;

        border-radius: 21px;

        background: #f3f7fb;

        color: #a3adba;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 33px;
    }


    .empty-title {
        color: var(--text-dark);

        font-size: 17px;

        font-weight: 750;

        margin-bottom: 7px;
    }


    .empty-description {
        max-width: 470px;

        margin: 0 auto 21px;

        color: #8993a1;

        font-size: 12px;

        line-height: 1.7;
    }


    .empty-button {
        border-radius: 10px;

        padding: 9px 17px;

        font-size: 12px;

        font-weight: 700;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1100px) {

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

    }


    @media (max-width: 767.98px) {

        .exam-header {
            align-items: flex-start;

            flex-direction: column;

            padding: 18px;
        }


        .header-left {
            width: 100%;
        }


        .header-actions {
            width: 100%;
        }


        .create-exam-btn {
            width: 100%;

            justify-content: center;

            display: flex;

            align-items: center;
        }


        .summary-grid {
            grid-template-columns: 1fr;
        }


        .exam-list-header {
            align-items: flex-start;

            flex-direction: column;

            padding: 17px;
        }


        .exam-table {
            min-width: 1000px;
        }

    }


    @media (max-width: 575.98px) {

        .exam-page {
            padding-left: 10px !important;

            padding-right: 10px !important;
        }


        .header-icon {
            width: 45px;
            height: 45px;

            border-radius: 12px;

            font-size: 19px;
        }


        .page-title {
            font-size: 18px;
        }


        .page-subtitle {
            font-size: 11px;
        }


        .module-badge {
            display: none;
        }


        .summary-card {
            padding: 15px;
        }


        .summary-icon {
            width: 40px;
            height: 40px;
        }


        .list-subtitle {
            display: none;
        }


        .exam-count {
            align-self: flex-start;
        }


        .empty-state {
            padding: 55px 18px;
        }

    }

</style>

@endsection
