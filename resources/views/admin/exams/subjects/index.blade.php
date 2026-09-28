
@extends('layouts.app')

@section('title', 'Subjects & Marks')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-2">

                <a href="{{ route('admin.exams.show', $exam) }}"
                   class="back-link"
                   title="Back to Exam">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <span class="page-icon">
                    <i class="bi bi-bookmarks"></i>
                </span>

                <div>
                    <h4 class="mb-0 fw-bold">
                        Subjects & Marks
                    </h4>

                    <div class="text-muted small mt-1">
                        Configure subjects, marks and duration for this examination.
                    </div>
                </div>

            </div>

            <div class="exam-breadcrumb">

                <i class="bi bi-journal-text me-1"></i>

                {{ $exam->exam_name }}

                <span class="mx-2">•</span>

                {{ $exam->academic_year }}

                @if(!empty($exam->exam_type))

                    <span class="mx-2">•</span>

                    {{ $exam->exam_type }}

                @endif

            </div>

        </div>


        <a href="{{ route('admin.exams.show', $exam) }}"
           class="btn btn-outline-secondary px-3">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Exam

        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="custom-alert success-alert mb-4">

            <div class="alert-icon success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="flex-grow-1">

                <div class="fw-semibold">
                    Configuration Saved
                </div>

                <div class="small mt-1">
                    {{ session('success') }}
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

        <div class="custom-alert danger-alert mb-4">

            <div class="alert-icon danger-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div>

                <div class="fw-semibold mb-1">
                    Please correct the following errors
                </div>

                <ul class="mb-0 ps-3 small">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
         INFORMATION CARD
    ========================================================== --}}
    <div class="information-card mb-4">

        <div class="information-icon">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div class="flex-grow-1">

            <div class="fw-semibold mb-1">
                Subjects are managed from the Classes module
            </div>

            <div class="small text-muted">

                Subjects shown below are fetched automatically from
                Class Management. You only need to configure which
                subjects are included in this exam and set their
                maximum marks, passing marks and duration.

            </div>

        </div>

    </div>


    {{-- =========================================================
         NO CLASSES
    ========================================================== --}}
    @if($examClasses->isEmpty())

        <div class="card border-0 shadow-sm empty-card">

            <div class="card-body text-center py-5">

                <div class="empty-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <h5 class="fw-bold mt-3 mb-2">
                    No Classes Selected
                </h5>

                <p class="text-muted mb-4">
                    Select at least one class before configuring
                    subjects and marks for this examination.
                </p>

                <a href="{{ route('admin.exam-classes.index', $exam) }}"
                   class="btn btn-primary px-4">

                    <i class="bi bi-mortarboard me-1"></i>
                    Select Classes

                </a>

            </div>

        </div>

    @else


        {{-- =====================================================
             QUICK SUMMARY
        ====================================================== --}}
        @php

            $totalClasses = $examClasses->count();

            $totalAvailableSubjects = 0;

            foreach ($examClasses as $summaryExamClass) {

                if ($summaryExamClass->schoolClass) {

                    $totalAvailableSubjects +=
                        ($summaryExamClass->schoolClass->subjects ?? collect())->count();

                }

            }

        @endphp


        <div class="row g-3 mb-4">

            {{-- Classes --}}
            <div class="col-xl-4 col-md-6">

                <div class="summary-card">

                    <div class="summary-icon blue">
                        <i class="bi bi-mortarboard"></i>
                    </div>

                    <div>

                        <div class="summary-label">
                            Selected Classes
                        </div>

                        <div class="summary-value">
                            {{ $totalClasses }}
                        </div>

                        <div class="summary-text">
                            Classes included in this exam
                        </div>

                    </div>

                </div>

            </div>


            {{-- Subjects --}}
            <div class="col-xl-4 col-md-6">

                <div class="summary-card">

                    <div class="summary-icon purple">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>

                        <div class="summary-label">
                            Available Subjects
                        </div>

                        <div class="summary-value">
                            {{ $totalAvailableSubjects }}
                        </div>

                        <div class="summary-text">
                            Subjects fetched from Classes
                        </div>

                    </div>

                </div>

            </div>


            {{-- Configuration --}}
            <div class="col-xl-4 col-md-6">

                <div class="summary-card">

                    <div class="summary-icon green">
                        <i class="bi bi-sliders"></i>
                    </div>

                    <div>

                        <div class="summary-label">
                            Configuration
                        </div>

                        <div class="summary-value">
                            Exam
                        </div>

                        <div class="summary-text">
                            Marks & duration are exam-specific
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FORM
        ====================================================== --}}
        <form method="POST"
              action="{{ route('admin.exam-subjects.store', $exam) }}"
              id="examSubjectsForm">

            @csrf


            {{-- =================================================
                 EACH CLASS
            ================================================== --}}
            @foreach($examClasses as $examClass)

                @php

                    $class = $examClass->schoolClass;

                    $classSubjects = $class?->subjects ?? collect();

                    $activeSubjectCount = $classSubjects->count();

                @endphp


                @if($class)

                    <div class="class-card mb-4">

                        {{-- =========================================
                             CLASS HEADER
                        ========================================== --}}
                        <div class="class-header">

                            <div class="d-flex align-items-center gap-3">

                                <div class="class-icon">
                                    <i class="bi bi-mortarboard-fill"></i>
                                </div>

                                <div>

                                    <div class="d-flex flex-wrap align-items-center gap-2">

                                        <h5 class="fw-bold mb-0">

                                            {{ $class->class_name }}

                                            @if($class->section)

                                                <span class="section-label">
                                                    Section {{ $class->section }}
                                                </span>

                                            @endif

                                        </h5>

                                    </div>

                                    <div class="text-muted small mt-1">

                                        <i class="bi bi-book me-1"></i>

                                        {{ $activeSubjectCount }}

                                        {{ Str::plural('subject', $activeSubjectCount) }}
                                        available

                                    </div>

                                </div>

                            </div>


                            @if($classSubjects->isNotEmpty())

                                <button type="button"
                                        class="btn btn-sm btn-outline-primary select-all-class"
                                        data-class-id="{{ $class->id }}">

                                    <i class="bi bi-check2-all me-1"></i>
                                    Select All

                                </button>

                            @endif

                        </div>


                        {{-- =========================================
                             SUBJECTS
                        ========================================== --}}
                        <div class="class-body">

                            @if($classSubjects->isEmpty())

                                <div class="class-empty">

                                    <div class="class-empty-icon">
                                        <i class="bi bi-book"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            No Active Subjects
                                        </div>

                                        <div class="small text-muted mt-1">
                                            No active subjects are currently
                                            available for this class.
                                        </div>

                                    </div>

                                </div>

                            @else

                                <div class="table-responsive">

                                    <table class="table subject-table align-middle mb-0">

                                        <thead>

                                            <tr>

                                                <th class="ps-4 include-column">
                                                    Include
                                                </th>

                                                <th>
                                                    Subject
                                                </th>

                                                <th>
                                                    Code
                                                </th>

                                                <th class="marks-column">
                                                    Maximum Marks
                                                </th>

                                                <th class="marks-column">
                                                    Passing Marks
                                                </th>

                                                <th class="duration-column">
                                                    Duration
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                            @foreach($classSubjects as $subject)

                                                @php

                                                    $key =
                                                        $class->id . '_' . $subject->id;

                                                    $existing =
                                                        $examSubjects[$key] ?? null;

                                                @endphp


                                                <tr class="subject-row">


                                                    {{-- =================================
                                                         INCLUDE
                                                    ================================== --}}
                                                    <td class="ps-4">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][class_id]"
                                                            value="{{ $class->id }}">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][subject_id]"
                                                            value="{{ $subject->id }}">

                                                        <input
                                                            type="hidden"
                                                            name="subjects[{{ $class->id }}_{{ $subject->id }}][status]"
                                                            value="0">


                                                        <div class="form-check subject-check">

                                                            <input
                                                                class="form-check-input subject-checkbox"
                                                                type="checkbox"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][status]"
                                                                value="1"
                                                                data-class-id="{{ $class->id }}"
                                                                {{ $existing
                                                                    ? ($existing->status ? 'checked' : '')
                                                                    : 'checked'
                                                                }}>

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         SUBJECT
                                                    ================================== --}}
                                                    <td>

                                                        <div class="d-flex align-items-center gap-3">

                                                            <div class="subject-icon">
                                                                <i class="bi bi-book"></i>
                                                            </div>

                                                            <div>

                                                                <div class="fw-semibold subject-name">
                                                                    {{ $subject->subject_name }}
                                                                </div>

                                                                <div class="small text-muted">
                                                                    Exam subject
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         CODE
                                                    ================================== --}}
                                                    <td>

                                                        @if($subject->subject_code)

                                                            <span class="subject-code">

                                                                {{ $subject->subject_code }}

                                                            </span>

                                                        @else

                                                            <span class="text-muted">
                                                                —
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- =================================
                                                         MAXIMUM MARKS
                                                    ================================== --}}
                                                    <td>

                                                        <div class="mark-input">

                                                            <span class="input-prefix">
                                                                <i class="bi bi-award"></i>
                                                            </span>

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][maximum_marks]"
                                                                class="form-control maximum-marks"
                                                                min="1"
                                                                max="1000"
                                                                value="{{ old(
                                                                    'subjects.' . $class->id . '_' . $subject->id . '.maximum_marks',
                                                                    $existing?->maximum_marks ?? 50
                                                                ) }}"
                                                                required>

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         PASSING MARKS
                                                    ================================== --}}
                                                    <td>

                                                        <div class="mark-input">

                                                            <span class="input-prefix">
                                                                <i class="bi bi-check-circle"></i>
                                                            </span>

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][passing_marks]"
                                                                class="form-control passing-marks"
                                                                min="0"
                                                                max="1000"
                                                                value="{{ old(
                                                                    'subjects.' . $class->id . '_' . $subject->id . '.passing_marks',
                                                                    $existing?->passing_marks ?? 17
                                                                ) }}"
                                                                required>

                                                        </div>

                                                    </td>


                                                    {{-- =================================
                                                         DURATION
                                                    ================================== --}}
                                                    <td>

                                                        <div class="duration-input">

                                                            <input
                                                                type="number"
                                                                name="subjects[{{ $class->id }}_{{ $subject->id }}][duration_minutes]"
                                                                class="form-control"
                                                                min="1"
                                                                max="600"
                                                                value="{{ old(
                                                                    'subjects.' . $class->id . '_' . $subject->id . '.duration_minutes',
                                                                    $existing?->duration_minutes ?? 60
                                                                ) }}"
                                                                required>

                                                            <span class="duration-unit">
                                                                min
                                                            </span>

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

                @endif

            @endforeach


            {{-- =================================================
                 SAVE PANEL
            ================================================== --}}
            <div class="save-panel">

                <div class="save-panel-info">

                    <div class="save-panel-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div>

                        <div class="fw-semibold">
                            Exam-specific configuration
                        </div>

                        <div class="small text-muted">
                            These settings apply only to
                            <strong>{{ $exam->exam_name }}</strong>.
                        </div>

                    </div>

                </div>


                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.exams.show', $exam) }}"
                       class="btn btn-light border px-4">

                        <i class="bi bi-x-lg me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4"
                            id="saveSubjectsButton">

                        <i class="bi bi-check-circle me-1"></i>
                        Save Subjects & Marks

                    </button>

                </div>

            </div>


        </form>

    @endif

</div>


{{-- =============================================================
     STYLES
============================================================= --}}
<style>

    :root {
        --primary-blue: #1677f0;
        --primary-dark: #0f5fc4;
        --soft-blue: #eef6ff;
        --border-color: #e6ebf0;
        --text-dark: #263238;
        --muted: #6c757d;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 21px;
    }

    .back-link {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        background: #f1f4f7;
        color: var(--primary-blue);
    }

    .exam-breadcrumb {
        margin-left: 78px;
        color: #707b85;
        font-size: 13px;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .custom-alert {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        position: relative;
        padding: 15px 50px 15px 16px;
        border-radius: 12px;
        border: 1px solid transparent;
    }

    .success-alert {
        background: #f0faf4;
        border-color: #cdebd9;
        color: #1d6b43;
    }

    .danger-alert {
        background: #fff5f5;
        border-color: #f3d0d4;
        color: #9b2c35;
    }

    .alert-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .success-icon {
        background: #dcf5e7;
        color: #198754;
    }

    .danger-icon {
        background: #fbe1e4;
        color: #dc3545;
    }


    /* =========================================================
       INFORMATION
    ========================================================== */

    .information-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 17px 19px;
        border-radius: 13px;
        background: #f7fbff;
        border: 1px solid #dcecff;
    }

    .information-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #e5f1ff;
        color: var(--primary-blue);
        font-size: 18px;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================== */

    .summary-card {
        height: 100%;
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(30, 50, 70, 0.04);
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(30, 50, 70, 0.08);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .summary-icon.blue {
        background: #eaf3ff;
        color: var(--primary-blue);
    }

    .summary-icon.purple {
        background: #f1edff;
        color: #7257d9;
    }

    .summary-icon.green {
        background: #eaf8f0;
        color: #198754;
    }

    .summary-label {
        color: #7a858f;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .summary-value {
        color: var(--text-dark);
        font-size: 23px;
        font-weight: 700;
        line-height: 1.25;
        margin-top: 2px;
    }

    .summary-text {
        color: #929aa3;
        font-size: 11px;
        margin-top: 2px;
    }


    /* =========================================================
       CLASS CARD
    ========================================================== */

    .class-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(30, 50, 70, 0.05);
    }

    .class-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 17px 20px;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f8fbff 100%
        );
        border-bottom: 1px solid var(--border-color);
    }

    .class-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 19px;
    }

    .section-label {
        display: inline-flex;
        align-items: center;
        margin-left: 5px;
        padding: 4px 8px;
        border-radius: 6px;
        background: #f0f3f7;
        color: #68737d;
        font-size: 11px;
        font-weight: 600;
        vertical-align: middle;
    }

    .class-body {
        background: #ffffff;
    }


    /* =========================================================
       SUBJECT TABLE
    ========================================================== */

    .subject-table {
        color: var(--text-dark);
    }

    .subject-table thead th {
        padding-top: 12px;
        padding-bottom: 12px;
        background: #f8fafc;
        color: #737e88;
        border-bottom: 1px solid var(--border-color);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        white-space: nowrap;
    }

    .subject-table tbody td {
        padding-top: 13px;
        padding-bottom: 13px;
        border-color: #edf0f3;
    }

    .subject-row {
        transition: background-color 0.15s ease;
    }

    .subject-row:hover {
        background: #fbfdff;
    }

    .include-column {
        width: 90px;
    }

    .marks-column {
        width: 180px;
    }

    .duration-column {
        width: 160px;
    }


    /* =========================================================
       CHECKBOX
    ========================================================== */

    .subject-check {
        padding-left: 1.7rem;
    }

    .subject-check .form-check-input {
        width: 19px;
        height: 19px;
        margin-top: -0.05em;
        cursor: pointer;
        border-color: #b9c3cc;
    }

    .subject-check .form-check-input:checked {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }


    /* =========================================================
       SUBJECT
    ========================================================== */

    .subject-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #f2f6fa;
        color: #63707c;
    }

    .subject-name {
        color: var(--text-dark);
        font-size: 13px;
    }

    .subject-code {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #f4f6f8;
        border: 1px solid #e4e8ec;
        color: #5e6974;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2px;
    }


    /* =========================================================
       MARK INPUTS
    ========================================================== */

    .mark-input {
        display: flex;
        align-items: stretch;
        max-width: 155px;
    }

    .input-prefix {
        width: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dce2e7;
        border-right: 0;
        border-radius: 8px 0 0 8px;
        background: #f8fafc;
        color: #7a858f;
        font-size: 13px;
    }

    .mark-input .form-control {
        min-height: 39px;
        border-color: #dce2e7;
        border-radius: 0 8px 8px 0;
        box-shadow: none;
        font-size: 13px;
        font-weight: 600;
    }

    .mark-input:focus-within .input-prefix {
        border-color: var(--primary-blue);
        background: var(--soft-blue);
        color: var(--primary-blue);
    }

    .mark-input:focus-within .form-control {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.15rem rgba(22, 119, 240, 0.08);
    }


    /* =========================================================
       DURATION
    ========================================================== */

    .duration-input {
        display: flex;
        align-items: stretch;
        max-width: 145px;
    }

    .duration-input .form-control {
        min-height: 39px;
        border-color: #dce2e7;
        border-radius: 8px 0 0 8px;
        box-shadow: none;
        font-size: 13px;
        font-weight: 600;
    }

    .duration-unit {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 43px;
        padding: 0 8px;
        border: 1px solid #dce2e7;
        border-left: 0;
        border-radius: 0 8px 8px 0;
        background: #f8fafc;
        color: #737e88;
        font-size: 11px;
        font-weight: 600;
    }

    .duration-input:focus-within .form-control {
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 0.15rem rgba(22, 119, 240, 0.08);
    }

    .duration-input:focus-within .duration-unit {
        border-color: var(--primary-blue);
        background: var(--soft-blue);
        color: var(--primary-blue);
    }


    /* =========================================================
       CLASS EMPTY
    ========================================================== */

    .class-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 13px;
        padding: 35px 20px;
        color: #6c757d;
    }

    .class-empty-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #f2f4f6;
        color: #89939c;
        font-size: 19px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-card {
        border-radius: 16px;
    }

    .empty-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 22px;
        background: var(--soft-blue);
        color: var(--primary-blue);
        font-size: 37px;
    }


    /* =========================================================
       SAVE PANEL
    ========================================================== */

    .save-panel {
        position: sticky;
        bottom: 15px;
        z-index: 20;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 16px 18px;
        margin-top: 10px;
        background: rgba(255, 255, 255, 0.97);
        border: 1px solid #dfe5eb;
        border-radius: 14px;
        box-shadow: 0 8px 30px rgba(30, 50, 70, 0.12);
        backdrop-filter: blur(8px);
    }

    .save-panel-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .save-panel-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eaf8f0;
        color: #198754;
        font-size: 18px;
    }


    /* =========================================================
       BUTTONS
    ========================================================== */

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991.98px) {

        .exam-breadcrumb {
            margin-left: 0;
        }

        .save-panel {
            position: static;
        }

    }


    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .class-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .class-header .btn {
            width: 100%;
        }

        .subject-table {
            min-width: 900px;
        }

        .save-panel {
            flex-direction: column;
            align-items: stretch;
        }

        .save-panel-info {
            align-items: flex-start;
        }

        .save-panel > div:last-child {
            width: 100%;
        }

        .save-panel > div:last-child .btn {
            flex: 1;
        }

    }

</style>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       SELECT / UNSELECT ALL SUBJECTS FOR CLASS
    ========================================================== */

    document.querySelectorAll('.select-all-class')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const classId = this.dataset.classId;

                const checkboxes = document.querySelectorAll(
                    '.subject-checkbox[data-class-id="' +
                    classId +
                    '"]'
                );

                if (!checkboxes.length) {
                    return;
                }

                let allChecked = true;

                checkboxes.forEach(function (checkbox) {

                    if (!checkbox.checked) {
                        allChecked = false;
                    }

                });


                checkboxes.forEach(function (checkbox) {

                    checkbox.checked = !allChecked;

                });


                if (allChecked) {

                    this.innerHTML =
                        '<i class="bi bi-check2-all me-1"></i> Select All';

                } else {

                    this.innerHTML =
                        '<i class="bi bi-x-circle me-1"></i> Unselect All';

                }

            });

        });


    /* =========================================================
       MAXIMUM MARKS / PASSING MARKS
    ========================================================== */

    document.querySelectorAll('.maximum-marks')
        .forEach(function (maximumInput) {

            maximumInput.addEventListener('input', function () {

                const row = this.closest('tr');

                if (!row) {
                    return;
                }

                const passingInput =
                    row.querySelector('.passing-marks');

                if (!passingInput) {
                    return;
                }

                const maximum =
                    parseInt(this.value || 0);

                passingInput.max = maximum;


                if (
                    maximum > 0 &&
                    parseInt(passingInput.value || 0) > maximum
                ) {

                    passingInput.value = maximum;

                }

            });

        });


    /* =========================================================
       INITIAL PASSING MARK LIMIT
    ========================================================== */

    document.querySelectorAll('.subject-row')
        .forEach(function (row) {

            const maximumInput =
                row.querySelector('.maximum-marks');

            const passingInput =
                row.querySelector('.passing-marks');

            if (!maximumInput || !passingInput) {
                return;
            }

            const maximum =
                parseInt(maximumInput.value || 0);

            if (maximum > 0) {
                passingInput.max = maximum;
            }

        });


    /* =========================================================
       CHECKBOX VISUAL STATE
    ========================================================== */

    document.querySelectorAll('.subject-checkbox')
        .forEach(function (checkbox) {

            function updateRow() {

                const row =
                    checkbox.closest('.subject-row');

                if (!row) {
                    return;
                }

                if (checkbox.checked) {

                    row.classList.add('subject-selected');

                } else {

                    row.classList.remove('subject-selected');

                }

            }

            checkbox.addEventListener(
                'change',
                updateRow
            );

            updateRow();

        });


    /* =========================================================
       FORM SUBMIT PROTECTION
    ========================================================== */

    const form =
        document.getElementById('examSubjectsForm');

    const saveButton =
        document.getElementById('saveSubjectsButton');

    if (form && saveButton) {

        form.addEventListener('submit', function () {

            saveButton.disabled = true;

            saveButton.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Saving...';

        });

    }

});

</script>

@endsection

