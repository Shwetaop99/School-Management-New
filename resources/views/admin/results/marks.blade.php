
@extends('layouts.app')

@section('title', 'Enter Marks')

@section('content')

<style>
    /* ============================================================
       ENTER MARKS PAGE
    ============================================================ */

    .marks-page {
        --primary: #1677f0;
        --primary-dark: #0d5fc7;
        --soft-blue: #eef6ff;
        --border: #e8edf3;
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --success: #198754;
        --warning: #f59e0b;
        --danger: #dc3545;
        --info: #0dcaf0;
    }

    .marks-page .page-header {
        background: linear-gradient(135deg, #1677f0 0%, #125dcc 100%);
        border-radius: 18px;
        padding: 24px 26px;
        color: #fff;
        box-shadow: 0 8px 24px rgba(22, 119, 240, .16);
    }

    .marks-page .page-header h4 {
        color: #fff;
        font-size: 1.35rem;
    }

    .marks-page .page-header p {
        color: rgba(255, 255, 255, .82) !important;
    }

    .marks-page .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, .16);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .marks-page .page-header .btn {
        border-color: rgba(255, 255, 255, .55);
        color: #fff;
        background: rgba(255, 255, 255, .08);
    }

    .marks-page .page-header .btn:hover {
        background: #fff;
        color: var(--primary);
        border-color: #fff;
    }

    .marks-page .modern-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .055);
        overflow: hidden;
        background: #fff;
    }

    .marks-page .card-header-modern {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: 18px 22px;
    }

    .marks-page .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }

    .marks-page .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 2px;
    }

    .marks-page .section-subtitle {
        font-size: .78rem;
        color: var(--text-muted);
        margin: 0;
    }

    .marks-page .form-label {
        color: #374151;
        font-size: .83rem;
        margin-bottom: 7px;
    }

    .marks-page .form-select,
    .marks-page .form-control {
        min-height: 44px;
        border-color: #dfe5ec;
        border-radius: 10px;
        font-size: .88rem;
        box-shadow: none;
    }

    .marks-page .form-select:focus,
    .marks-page .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(22, 119, 240, .10);
    }

    .marks-page .form-select:disabled {
        background-color: #f5f7fa;
        cursor: not-allowed;
    }

    .marks-page .required {
        color: var(--danger);
    }

    .marks-page .info-box {
        border: 1px solid var(--border);
        background: #f9fbfd;
        border-radius: 12px;
        padding: 13px 15px;
        height: 100%;
    }

    .marks-page .info-box .label {
        color: var(--text-muted);
        font-size: .74rem;
        display: block;
        margin-bottom: 4px;
    }

    .marks-page .info-box .value {
        color: var(--text-dark);
        font-weight: 700;
        font-size: .92rem;
    }

    .marks-page .info-box.primary {
        background: var(--soft-blue);
        border-color: rgba(22, 119, 240, .15);
    }

    .marks-page .info-box.primary .value {
        color: var(--primary);
    }

    .marks-page .selection-footer {
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid var(--border);
    }

    .marks-page .btn-primary {
        background: var(--primary);
        border-color: var(--primary);
        border-radius: 10px;
        font-weight: 600;
    }

    .marks-page .btn-primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    .marks-page .btn {
        border-radius: 10px;
        font-weight: 600;
    }

    /* ============================================================
       EXAM INFORMATION
    ============================================================ */

    .marks-page .exam-banner {
        background: linear-gradient(135deg, #f8fbff 0%, #eef6ff 100%);
        border: 1px solid #dcecff;
        border-radius: 16px;
        padding: 22px;
    }

    .marks-page .exam-title {
        color: var(--text-dark);
        font-size: 1.1rem;
        font-weight: 750;
    }

    .marks-page .exam-meta {
        font-size: .8rem;
        color: var(--text-muted);
    }

    .marks-page .exam-badge {
        border-radius: 8px;
        padding: 7px 11px;
        font-size: .74rem;
        font-weight: 700;
    }

    .marks-page .badge-class {
        background: #e9f2ff;
        color: #1264d6;
    }

    .marks-page .badge-section {
        background: #f0f2f5;
        color: #4b5563;
    }

    .marks-page .badge-subject {
        background: #e8f8fb;
        color: #087990;
    }

    /* ============================================================
       MARKS TABLE
    ============================================================ */

    .marks-page .marks-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .055);
        overflow: hidden;
    }

    .marks-page .marks-table-wrapper {
        max-height: 68vh;
        overflow: auto;
    }

    .marks-page .marks-table {
        min-width: 1180px;
        margin-bottom: 0;
    }

    .marks-page .marks-table thead th {
        background: #f7f9fc;
        border-bottom: 1px solid #dde4ec;
        color: #4b5563;
        font-size: .74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
        padding: 13px 11px;
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .marks-page .marks-table tbody td {
        border-color: #edf0f4;
        padding: 11px;
        font-size: .84rem;
        vertical-align: middle;
    }

    .marks-page .marks-table tbody tr {
        transition: background .15s ease;
    }

    .marks-page .marks-table tbody tr:hover {
        background: #f8fbff;
    }

    .marks-page .serial {
        width: 48px;
        text-align: center;
        color: #6b7280;
        font-weight: 600;
    }

    .marks-page .student-id {
        font-weight: 700;
        color: var(--primary);
        white-space: nowrap;
    }

    .marks-page .roll-number {
        color: #8a94a3;
        font-size: .72rem;
        margin-top: 2px;
    }

    .marks-page .student-name {
        font-weight: 650;
        color: #263241;
        white-space: nowrap;
    }

    .marks-page .mark-input {
        width: 108px;
        min-height: 39px;
        text-align: center;
        font-weight: 650;
        border-radius: 8px;
        margin: auto;
    }

    .marks-page .mark-input:disabled {
        background: #f1f3f5;
        color: #9aa2ad;
    }

    .marks-page .total-input {
        width: 92px;
        min-height: 39px;
        margin: auto;
        background: #f4f8ff;
        color: var(--primary);
        border-color: #d9e8ff;
    }

    .marks-page .status-select {
        min-height: 39px;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 600;
        min-width: 105px;
    }

    .marks-page .remarks-input {
        min-width: 180px;
        min-height: 39px;
    }

    .marks-page .table-heading-icon {
        color: var(--primary);
        margin-right: 5px;
    }

    /* ============================================================
       STATUS
    ============================================================ */

    .marks-page .status-present {
        background: #eaf8f0;
        color: #137a43;
    }

    .marks-page .status-absent {
        background: #fff4e5;
        color: #a45a00;
    }

    .marks-page .status-na {
        background: #f0f1f3;
        color: #626b76;
    }

    /* ============================================================
       TABLE FOOTER
    ============================================================ */

    .marks-page .marks-footer {
        background: #fff;
        border-top: 1px solid var(--border);
        padding: 15px 20px;
    }

    .marks-page .save-button {
        min-width: 165px;
        padding: 10px 18px;
    }

    .marks-page .footer-note {
        color: var(--text-muted);
        font-size: .76rem;
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */

    .marks-page .empty-state {
        padding: 70px 20px;
        text-align: center;
    }

    .marks-page .empty-icon {
        width: 78px;
        height: 78px;
        border-radius: 20px;
        background: #f2f5f8;
        color: #8b95a3;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */

    @media (max-width: 767.98px) {

        .marks-page .page-header {
            padding: 18px;
            border-radius: 14px;
        }

        .marks-page .page-header h4 {
            font-size: 1.1rem;
        }

        .marks-page .page-header .header-actions {
            width: 100%;
            margin-top: 15px;
        }

        .marks-page .page-header .header-actions .btn {
            width: 100%;
        }

        .marks-page .exam-banner {
            padding: 17px;
        }

        .marks-page .card-header-modern {
            padding: 15px 17px;
        }

        .marks-page .marks-footer {
            padding: 14px;
        }

        .marks-page .marks-footer .btn {
            width: 100%;
        }

        .marks-page .footer-note {
            display: none;
        }
    }
</style>


<div class="container-fluid py-4 marks-page">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="page-header mb-4">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div class="d-flex align-items-center">

                <div class="header-icon me-3">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h4 class="mb-1 fw-bold">
                        Enter Marks
                    </h4>

                    <p class="mb-0">
                        Enter and manage Internal, Theory and Practical marks.
                    </p>
                </div>

            </div>

            <div class="header-actions">

                <a
                    href="{{ route('admin.results.index') }}"
                    class="btn"
                >
                    <i class="bi bi-arrow-left me-1"></i>
                    Back to Results
                </a>

            </div>

        </div>

    </div>


    {{-- ============================================================
         FLASH MESSAGES
    ============================================================ --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4"
            role="alert"
        >
            <i class="bi bi-check-circle-fill me-2"></i>

            <strong>Success:</strong>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4"
            role="alert"
        >
            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <strong>Error:</strong>
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- ============================================================
         SELECTION MODE
    ============================================================ --}}
    @if($selectionMode)

        <div class="modern-card">

            {{-- CARD HEADER --}}
            <div class="card-header-modern">

                <div class="d-flex align-items-center">

                    <div class="section-icon me-3">
                        <i class="bi bi-funnel-fill"></i>
                    </div>

                    <div>
                        <div class="section-title">
                            Select Marks Entry Details
                        </div>

                        <p class="section-subtitle">
                            Choose examination, class, section and subject.
                        </p>
                    </div>

                </div>

            </div>


            {{-- CARD BODY --}}
            <div class="p-4">

                <form
                    method="GET"
                    action="{{ route('admin.results.marks') }}"
                    id="marksSelectionForm"
                >

                    {{-- ====================================================
                         MAIN SELECTION
                    ===================================================== --}}
                    <div class="row g-3">

                        {{-- EXAM --}}
                        <div class="col-xl-3 col-md-6">

                            <label
                                for="exam_id"
                                class="form-label fw-semibold"
                            >
                                Examination
                                <span class="required">*</span>
                            </label>

                            <select
                                name="exam_id"
                                id="exam_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Examination
                                </option>

                                @foreach($exams as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        {{ request('exam_id') == $item->id ? 'selected' : '' }}
                                    >
                                        {{ $item->exam_name }}
                                        @if($item->academic_year)
                                            ({{ $item->academic_year }})
                                        @endif
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- CLASS --}}
                        <div class="col-xl-3 col-md-6">

                            <label
                                for="class_id"
                                class="form-label fw-semibold"
                            >
                                Class
                                <span class="required">*</span>
                            </label>

                            <select
                                name="class_id"
                                id="class_id"
                                class="form-select"
                                {{ !$exam ? 'disabled' : '' }}
                                required
                            >

                                <option value="">
                                    Select Class
                                </option>

                                @foreach($examClasses as $item)

                                    @if($item->schoolClass)

                                        <option
                                            value="{{ $item->class_id }}"
                                            data-section="{{ $item->schoolClass->section }}"
                                            {{ request('class_id') == $item->class_id ? 'selected' : '' }}
                                        >
                                            {{ $item->schoolClass->class_name }}
                                        </option>

                                    @endif

                                @endforeach

                            </select>

                        </div>


                        {{-- SECTION --}}
                        <div class="col-xl-3 col-md-6">

                            <label
                                for="section"
                                class="form-label fw-semibold"
                            >
                                Section
                                <span class="required">*</span>
                            </label>

                            <select
                                name="section"
                                id="section"
                                class="form-select"
                                {{ !$exam ? 'disabled' : '' }}
                                required
                            >

                                <option value="">
                                    Select Section
                                </option>

                                @if(isset($sections) && $sections->count())

                                    @foreach($sections as $sectionItem)

                                        <option
                                            value="{{ $sectionItem }}"
                                            {{ request('section') == $sectionItem ? 'selected' : '' }}
                                        >
                                            Section {{ $sectionItem }}
                                        </option>

                                    @endforeach

                                @endif

                            </select>

                        </div>


                        {{-- SUBJECT --}}
                        <div class="col-xl-3 col-md-6">

                            <label
                                for="subject_id"
                                class="form-label fw-semibold"
                            >
                                Subject
                                <span class="required">*</span>
                            </label>

                            <select
                                name="subject_id"
                                id="subject_id"
                                class="form-select"
                                {{ !$exam ? 'disabled' : '' }}
                                required
                            >

                                <option value="">
                                    Select Subject
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- ====================================================
                         MARK CONFIGURATION
                    ===================================================== --}}
                    <div class="mt-4">

                        <div class="d-flex align-items-center mb-3">

                            <div class="section-icon me-3">
                                <i class="bi bi-bar-chart-line-fill"></i>
                            </div>

                            <div>
                                <div class="section-title">
                                    Marks Configuration
                                </div>

                                <p class="section-subtitle">
                                    Maximum and passing marks for the selected subject.
                                </p>
                            </div>

                        </div>


                        <div class="row g-3">

                            <div class="col-md-3">

                                <div class="info-box primary">

                                    <span class="label">
                                        <i class="bi bi-award me-1"></i>
                                        Maximum Marks
                                    </span>

                                    <span
                                        class="value"
                                        id="maximum_marks"
                                    >
                                        -
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="info-box">

                                    <span class="label">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Passing Marks
                                    </span>

                                    <span
                                        class="value"
                                        id="passing_marks"
                                    >
                                        -
                                    </span>

                                </div>

                            </div>

                        </div>

                        {{-- Hidden readonly fields for existing JS --}}
                        <input
                            type="hidden"
                            id="maximum_marks"
                            value=""
                        >

                        <input
                            type="hidden"
                            id="passing_marks"
                            value=""
                        >

                    </div>


                    {{-- ====================================================
                         LOAD STUDENTS
                    ===================================================== --}}
                    <div class="selection-footer">

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                            <div class="text-muted small">

                                <i class="bi bi-info-circle me-1"></i>

                                Select all required fields before loading students.

                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                                id="loadStudentsBtn"
                            >
                                <i class="bi bi-people-fill me-2"></i>
                                Load Students
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


    {{-- ============================================================
         MARKS ENTRY MODE
    ============================================================ --}}
    @else

        {{-- ============================================================
             EXAM INFORMATION
        ============================================================ --}}
        <div class="exam-banner mb-4">

            <div class="row align-items-center g-3">

                <div class="col-lg-7">

                    <div class="d-flex align-items-center">

                        <div class="section-icon me-3">

                            <i class="bi bi-journal-text"></i>

                        </div>

                        <div>

                            <div class="exam-title">
                                {{ $exam->exam_name }}
                            </div>

                            <div class="exam-meta mt-1">

                                <i class="bi bi-calendar3 me-1"></i>

                                Academic Year:
                                <strong>
                                    {{ $exam->academic_year }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-5">

                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">

                        <span class="exam-badge badge-class">

                            <i class="bi bi-mortarboard-fill me-1"></i>

                            {{ $examClass->schoolClass->class_name }}

                        </span>


                        <span class="exam-badge badge-section">

                            <i class="bi bi-diagram-3 me-1"></i>

                            Section {{ $section }}

                        </span>


                        <span class="exam-badge badge-subject">

                            <i class="bi bi-book me-1"></i>

                            {{ $examSubject->subject->subject_name ?? 'Subject' }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- ========================================================
                 INFORMATION STRIP
            ========================================================= --}}
            <div class="row g-3 mt-3 pt-3 border-top">

                <div class="col-md-3">

                    <div class="info-box">

                        <span class="label">
                            <i class="bi bi-book me-1"></i>
                            Subject
                        </span>

                        <span class="value">
                            {{ $examSubject->subject->subject_name ?? '-' }}
                        </span>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="info-box">

                        <span class="label">
                            <i class="bi bi-bullseye me-1"></i>
                            Maximum Marks
                        </span>

                        <span class="value">
                            {{ $examSubject->maximum_marks }}
                        </span>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="info-box">

                        <span class="label">
                            <i class="bi bi-check-circle me-1"></i>
                            Passing Marks
                        </span>

                        <span class="value">
                            {{ $examSubject->passing_marks }}
                        </span>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="info-box primary">

                        <span class="label">
                            <i class="bi bi-people-fill me-1"></i>
                            Students
                        </span>

                        <span class="value">
                            {{ $students->count() }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================
             MARKS FORM
        ============================================================ --}}
        <form
            method="POST"
            action="{{ route('admin.results.save-marks') }}"
            id="marksForm"
        >

            @csrf


            <input
                type="hidden"
                name="exam_id"
                value="{{ $exam->id }}"
            >


            <input
                type="hidden"
                name="exam_class_id"
                value="{{ $examClass->id }}"
            >


            <input
                type="hidden"
                name="class_id"
                value="{{ $examClass->class_id }}"
            >


            <input
                type="hidden"
                name="section"
                value="{{ $section }}"
            >


            <input
                type="hidden"
                name="subject_id"
                value="{{ $examSubject->subject_id }}"
            >


            {{-- ========================================================
                 STUDENT MARKS CARD
            ========================================================= --}}
            <div class="marks-card">

                {{-- CARD HEADER --}}
                <div class="card-header-modern">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div class="d-flex align-items-center">

                            <div class="section-icon me-3">

                                <i class="bi bi-table"></i>

                            </div>

                            <div>

                                <div class="section-title">
                                    Student Marks
                                </div>

                                <p class="section-subtitle">
                                    Enter marks for each student and select the attendance status.
                                </p>

                            </div>

                        </div>


                        <div>

                            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">

                                <i class="bi bi-people-fill me-1"></i>

                                {{ $students->count() }}
                                Students

                            </span>

                        </div>

                    </div>

                </div>


                {{-- TABLE BODY --}}
                <div class="card-body p-0">

                    @if($students->count())

                        <div class="table-responsive marks-table-wrapper">

                            <table class="table align-middle marks-table">

                                <thead>

                                    <tr>

                                        <th
                                            class="text-center"
                                            style="width:55px;"
                                        >
                                            #
                                        </th>

                                        <th>
                                            <i class="bi bi-person-badge table-heading-icon"></i>
                                            Student ID
                                        </th>

                                        <th>
                                            <i class="bi bi-person table-heading-icon"></i>
                                            Student Name
                                        </th>

                                        <th
                                            class="text-center"
                                            style="width:125px;"
                                        >
                                            Internal
                                        </th>

                                        <th
                                            class="text-center"
                                            style="width:125px;"
                                        >
                                            Theory
                                        </th>

                                        <th
                                            class="text-center"
                                            style="width:125px;"
                                        >
                                            Practical
                                        </th>

                                        <th
                                            class="text-center"
                                            style="width:105px;"
                                        >
                                            Total
                                        </th>

                                        <th
                                            class="text-center"
                                            style="width:125px;"
                                        >
                                            Status
                                        </th>

                                        <th>
                                            <i class="bi bi-chat-left-text table-heading-icon"></i>
                                            Remarks
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                @foreach($students as $index => $student)

                                    @php

                                        $mark = $student->examMark;

                                        $status = $mark->status ?? 'present';

                                        $internal = $mark->internal_marks ?? 0;

                                        $theory = $mark->theory_marks ?? 0;

                                        $practical = $mark->practical_marks ?? 0;

                                        $fullName = trim(
                                            collect([
                                                $student->first_name,
                                                $student->middle_name,
                                                $student->last_name
                                            ])
                                            ->filter()
                                            ->implode(' ')
                                        );

                                        $total =
                                            (float) $internal +
                                            (float) $theory +
                                            (float) $practical;

                                    @endphp


                                    <tr>

                                        {{-- NUMBER --}}
                                        <td class="serial">
                                            {{ $index + 1 }}
                                        </td>


                                        {{-- STUDENT ID --}}
                                        <td>

                                            <div class="student-id">
                                                {{ $student->student_id }}
                                            </div>

                                            @if($student->roll_number)

                                                <div class="roll-number">
                                                    Roll No: {{ $student->roll_number }}
                                                </div>

                                            @endif

                                        </td>


                                        {{-- STUDENT NAME --}}
                                        <td>

                                            <div class="student-name">

                                                {{ $fullName ?: '-' }}

                                            </div>

                                        </td>


                                        {{-- INTERNAL --}}
                                        <td>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="{{ $examSubject->maximum_marks }}"
                                                class="form-control mark-input internal-input"
                                                name="marks[{{ $student->id }}][internal_marks]"
                                                value="{{ $internal }}"
                                                data-student="{{ $student->id }}"
                                                {{ in_array($status, ['absent', 'na']) ? 'disabled' : '' }}
                                            >

                                        </td>


                                        {{-- THEORY --}}
                                        <td>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="{{ $examSubject->maximum_marks }}"
                                                class="form-control mark-input theory-input"
                                                name="marks[{{ $student->id }}][theory_marks]"
                                                value="{{ $theory }}"
                                                data-student="{{ $student->id }}"
                                                {{ in_array($status, ['absent', 'na']) ? 'disabled' : '' }}
                                            >

                                        </td>


                                        {{-- PRACTICAL --}}
                                        <td>

                                            <input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                max="{{ $examSubject->maximum_marks }}"
                                                class="form-control mark-input practical-input"
                                                name="marks[{{ $student->id }}][practical_marks]"
                                                value="{{ $practical }}"
                                                data-student="{{ $student->id }}"
                                                {{ in_array($status, ['absent', 'na']) ? 'disabled' : '' }}
                                            >

                                        </td>


                                        {{-- TOTAL --}}
                                        <td>

                                            <input
                                                type="text"
                                                class="form-control total-input text-center fw-bold"
                                                value="{{ number_format($total, 2) }}"
                                                data-student="{{ $student->id }}"
                                                readonly
                                            >

                                        </td>


                                        {{-- STATUS --}}
                                        <td>

                                            <select
                                                class="form-select status-select"
                                                name="marks[{{ $student->id }}][status]"
                                                data-student="{{ $student->id }}"
                                            >

                                                <option
                                                    value="present"
                                                    {{ $status == 'present' ? 'selected' : '' }}
                                                >
                                                    Present
                                                </option>

                                                <option
                                                    value="absent"
                                                    {{ $status == 'absent' ? 'selected' : '' }}
                                                >
                                                    Absent
                                                </option>

                                                <option
                                                    value="na"
                                                    {{ $status == 'na' ? 'selected' : '' }}
                                                >
                                                    N/A
                                                </option>

                                            </select>

                                        </td>


                                        {{-- REMARKS --}}
                                        <td>

                                            <input
                                                type="text"
                                                class="form-control remarks-input"
                                                name="marks[{{ $student->id }}][remarks]"
                                                value="{{ $mark->remarks ?? '' }}"
                                                placeholder="Optional remark"
                                            >

                                        </td>

                                    </tr>

                                @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        {{-- EMPTY STATE --}}
                        <div class="empty-state">

                            <div class="empty-icon">

                                <i class="bi bi-people"></i>

                            </div>

                            <h5 class="mt-4 fw-bold">
                                No Students Found
                            </h5>

                            <p class="text-muted mb-4">
                                No active students were found for this
                                class, section and academic year.
                            </p>

                            <a
                                href="{{ route('admin.results.marks') }}"
                                class="btn btn-outline-primary"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Change Selection
                            </a>

                        </div>

                    @endif

                </div>


                {{-- ====================================================
                     FOOTER
                ===================================================== --}}
                <div class="marks-footer">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div class="footer-note">

                            <i class="bi bi-info-circle me-1"></i>

                            Marks are calculated automatically from
                            Internal + Theory + Practical.

                        </div>


                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('admin.results.marks') }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="bi bi-arrow-left me-1"></i>
                                Back
                            </a>


                            @if($students->count())

                                <button
                                    type="submit"
                                    class="btn btn-primary save-button"
                                    id="saveMarksBtn"
                                >
                                    <i class="bi bi-check2-circle me-1"></i>
                                    Save All Marks
                                </button>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </form>

    @endif

</div>


{{-- ================================================================
     JAVASCRIPT
================================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SELECTION PAGE ELEMENTS
    |--------------------------------------------------------------------------
    */

    const examSelect =
        document.getElementById('exam_id');

    const classSelect =
        document.getElementById('class_id');

    const sectionSelect =
        document.getElementById('section');

    const subjectSelect =
        document.getElementById('subject_id');

    /*
     * These IDs are used by the existing JavaScript.
     * The visible marks configuration uses separate display elements.
     */
    const maximumMarksInput =
        document.querySelector(
            '#maximum_marks[type="hidden"]'
        );

    const passingMarksInput =
        document.querySelector(
            '#passing_marks[type="hidden"]'
        );

    const maximumMarksDisplay =
        document.querySelector(
            '.marks-page .info-box.primary .value'
        );

    const passingMarksDisplay =
        document.querySelector(
            '.marks-page .info-box:not(.primary) .value'
        );


    /*
    |--------------------------------------------------------------------------
    | UPDATE MARK DISPLAY
    |--------------------------------------------------------------------------
    */

    function updateMarksDisplay(maximum, passing) {

        if (maximumMarksInput) {
            maximumMarksInput.value = maximum || '';
        }

        if (passingMarksInput) {
            passingMarksInput.value = passing || '';
        }

        if (maximumMarksDisplay) {
            maximumMarksDisplay.textContent =
                maximum || '-';
        }

        if (passingMarksDisplay) {
            passingMarksDisplay.textContent =
                passing || '-';
        }

    }


    /*
    |--------------------------------------------------------------------------
    | EXAM CHANGE
    |--------------------------------------------------------------------------
    */

    if (examSelect) {

        examSelect.addEventListener(
            'change',
            function () {

                const examId =
                    this.value;

                if (!examId) {

                    window.location.href =
                        "{{ route('admin.results.marks') }}";

                    return;
                }


                const url =
                    "{{ route('admin.results.marks') }}" +
                    "?exam_id=" +
                    encodeURIComponent(examId);


                window.location.href =
                    url;

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLASS CHANGE
    |--------------------------------------------------------------------------
    |
    | Section comes directly from the selected class option.
    |
    */

    if (classSelect) {

        classSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[this.selectedIndex];

                const classId =
                    this.value;

                const section =
                    selectedOption
                        ? selectedOption.dataset.section
                        : '';


                /*
                | Clear subject
                */

                if (subjectSelect) {

                    subjectSelect.innerHTML =
                        '<option value="">Select Subject</option>';

                    subjectSelect.disabled =
                        true;

                }


                /*
                | Clear marks information
                */

                updateMarksDisplay('', '');


                /*
                | Clear section
                */

                if (sectionSelect) {

                    sectionSelect.innerHTML =
                        '<option value="">Select Section</option>';

                }


                /*
                | No class selected
                */

                if (!classId) {

                    if (sectionSelect) {
                        sectionSelect.disabled = true;
                    }

                    return;

                }


                /*
                | Section not found
                */

                if (!section) {

                    if (sectionSelect) {

                        sectionSelect.innerHTML =
                            '<option value="">No Section Found</option>';

                        sectionSelect.disabled =
                            true;

                    }

                    return;

                }


                /*
                | Add section
                */

                if (sectionSelect) {

                    const option =
                        document.createElement('option');

                    option.value =
                        section;

                    option.textContent =
                        'Section ' + section;

                    option.selected =
                        true;

                    sectionSelect.appendChild(option);

                    sectionSelect.disabled =
                        false;

                }


                /*
                | Load subjects
                */

                if (
                    examSelect &&
                    examSelect.value
                ) {

                    loadSubjects(
                        examSelect.value,
                        classId
                    );

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SECTION CHANGE
    |--------------------------------------------------------------------------
    */

    if (sectionSelect) {

        sectionSelect.addEventListener(
            'change',
            function () {

                const examId =
                    examSelect
                        ? examSelect.value
                        : '';

                const classId =
                    classSelect
                        ? classSelect.value
                        : '';

                const section =
                    this.value;


                if (subjectSelect) {

                    subjectSelect.innerHTML =
                        '<option value="">Select Subject</option>';

                    subjectSelect.disabled =
                        true;

                }


                updateMarksDisplay('', '');


                if (
                    !examId ||
                    !classId ||
                    !section
                ) {

                    return;

                }


                loadSubjects(
                    examId,
                    classId
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SUBJECT CHANGE
    |--------------------------------------------------------------------------
    */

    if (subjectSelect) {

        subjectSelect.addEventListener(
            'change',
            function () {

                const selectedOption =
                    this.options[this.selectedIndex];


                if (
                    !selectedOption ||
                    !selectedOption.value
                ) {

                    updateMarksDisplay('', '');

                    return;

                }


                updateMarksDisplay(
                    selectedOption.dataset.maximum || '',
                    selectedOption.dataset.passing || ''
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | LOAD SUBJECTS
    |--------------------------------------------------------------------------
    */

    function loadSubjects(
        examId,
        classId,
        selectedSubjectId = ''
    ) {

        if (!subjectSelect) {
            return;
        }


        subjectSelect.innerHTML =
            '<option value="">Loading Subjects...</option>';

        subjectSelect.disabled =
            true;


        const url =
            "{{ route('admin.results.load-subjects') }}" +
            "?exam_id=" +
            encodeURIComponent(examId) +
            "&class_id=" +
            encodeURIComponent(classId);


        fetch(
            url,
            {
                method: 'GET',

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Unable to load subjects.'
                );

            }

            return response.json();

        })

        .then(function (data) {

            subjectSelect.innerHTML =
                '<option value="">Select Subject</option>';


            const subjects =
                Array.isArray(data.subjects)
                    ? data.subjects
                    : [];


            if (subjects.length === 0) {

                subjectSelect.innerHTML =
                    '<option value="">No Subjects Found</option>';

                subjectSelect.disabled =
                    true;

                updateMarksDisplay('', '');

                return;

            }


            subjects.forEach(
                function (subject) {

                    const option =
                        document.createElement('option');


                    option.value =
                        subject.id;


                    option.textContent =
                        subject.name +
                        ' (' +
                        subject.maximum_marks +
                        ' Marks)';


                    option.dataset.maximum =
                        subject.maximum_marks;


                    option.dataset.passing =
                        subject.passing_marks;


                    if (
                        selectedSubjectId &&
                        String(subject.id) ===
                        String(selectedSubjectId)
                    ) {

                        option.selected =
                            true;


                        updateMarksDisplay(
                            subject.maximum_marks,
                            subject.passing_marks
                        );

                    }


                    subjectSelect.appendChild(option);

                }
            );


            subjectSelect.disabled =
                false;

        })

        .catch(function (error) {

            console.error(error);


            subjectSelect.innerHTML =
                '<option value="">Unable to load subjects</option>';

            subjectSelect.disabled =
                true;

            updateMarksDisplay('', '');

        });

    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE EXISTING SELECTION
    |--------------------------------------------------------------------------
    */

    if (
        examSelect &&
        classSelect &&
        sectionSelect &&
        subjectSelect
    ) {

        const existingClassId =
            "{{ request('class_id') }}";

        const existingSection =
            "{{ request('section') }}";

        const existingSubjectId =
            "{{ request('subject_id') }}";


        if (
            existingClassId &&
            classSelect.value === existingClassId
        ) {

            const selectedOption =
                classSelect.options[
                    classSelect.selectedIndex
                ];


            const section =
                selectedOption
                    ? selectedOption.dataset.section
                    : '';


            /*
            | Fill section
            */

            if (section) {

                sectionSelect.innerHTML =
                    '<option value="">Select Section</option>';


                const option =
                    document.createElement('option');


                option.value =
                    section;

                option.textContent =
                    'Section ' + section;


                sectionSelect.appendChild(option);

                sectionSelect.disabled =
                    false;


                /*
                | Restore requested section
                */

                if (existingSection) {

                    sectionSelect.value =
                        existingSection;

                }


                /*
                | Load subjects
                */

                if (
                    examSelect.value &&
                    classSelect.value &&
                    sectionSelect.value
                ) {

                    loadSubjects(
                        examSelect.value,
                        classSelect.value,
                        existingSubjectId
                    );

                }

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | MARKS ENTRY
    |--------------------------------------------------------------------------
    */

    const marksForm =
        document.getElementById('marksForm');


    if (marksForm) {

        const maxMarks =
            parseFloat(
                "{{ $examSubject->maximum_marks ?? 0 }}"
            ) || 0;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE TOTAL
        |--------------------------------------------------------------------------
        */

        function calculateTotal(studentId) {

            const internal =
                document.querySelector(
                    '.internal-input[data-student="' +
                    studentId +
                    '"]'
                );


            const theory =
                document.querySelector(
                    '.theory-input[data-student="' +
                    studentId +
                    '"]'
                );


            const practical =
                document.querySelector(
                    '.practical-input[data-student="' +
                    studentId +
                    '"]'
                );


            const total =
                document.querySelector(
                    '.total-input[data-student="' +
                    studentId +
                    '"]'
                );


            if (
                !internal ||
                !theory ||
                !practical ||
                !total
            ) {

                return;

            }


            const internalValue =
                parseFloat(internal.value) || 0;


            const theoryValue =
                parseFloat(theory.value) || 0;


            const practicalValue =
                parseFloat(practical.value) || 0;


            let calculated =
                internalValue +
                theoryValue +
                practicalValue;


            /*
            | Prevent total above maximum marks
            */

            if (calculated > maxMarks) {
                calculated = maxMarks;
            }


            total.value =
                calculated.toFixed(2);

        }


        /*
        |--------------------------------------------------------------------------
        | MARK INPUT EVENTS
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.mark-input')
            .forEach(
                function (input) {

                    input.addEventListener(
                        'input',
                        function () {

                            calculateTotal(
                                this.dataset.student
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | STATUS CHANGE
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.status-select')
            .forEach(
                function (select) {

                    select.addEventListener(
                        'change',
                        function () {

                            const studentId =
                                this.dataset.student;


                            const inputs =
                                document.querySelectorAll(
                                    '.mark-input[data-student="' +
                                    studentId +
                                    '"]'
                                );


                            if (
                                this.value === 'absent' ||
                                this.value === 'na'
                            ) {

                                inputs.forEach(
                                    function (input) {

                                        input.value =
                                            0;

                                        input.disabled =
                                            true;

                                    }
                                );

                            } else {

                                inputs.forEach(
                                    function (input) {

                                        input.disabled =
                                            false;

                                    }
                                );

                            }


                            calculateTotal(
                                studentId
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | SAVE ALL MARKS
        |--------------------------------------------------------------------------
        */

        marksForm.addEventListener(
            'submit',
            function () {

                const saveButton =
                    document.getElementById(
                        'saveMarksBtn'
                    );


                if (saveButton) {

                    saveButton.disabled =
                        true;


                    saveButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-1"></span>' +
                        'Saving...';

                }

            }
        );

    }

});

</script>

@endpush

@endsection
