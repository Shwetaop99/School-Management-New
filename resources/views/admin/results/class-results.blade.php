
@extends('layouts.app')

@section('title', 'Class Results')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    $totalStudents = $students->count();

    $generatedStudents = $results
        ->where('publication_status', 'generated')
        ->count();

    $verifiedStudents = $results
        ->where('publication_status', 'verified')
        ->count();

    $approvedStudents = $results
        ->where('publication_status', 'approved')
        ->count();

    $publishedStudents = $results
        ->where('publication_status', 'published')
        ->count();

    $resultsCount = $results->count();

    $pendingStudents = max(
        0,
        $totalStudents - $resultsCount
    );

    $allResultsGenerated =
        $totalStudents > 0 &&
        $resultsCount === $totalStudents;

    $hasGeneratedResults =
        $generatedStudents > 0;

    $allResultsVerifiedOrLater =
        $totalStudents > 0 &&
        $resultsCount === $totalStudents &&
        (
            $verifiedStudents +
            $approvedStudents +
            $publishedStudents
        ) === $totalStudents;

    $hasVerifiedResults =
        $verifiedStudents > 0;

    $readyToPublish =
        $totalStudents > 0 &&
        $resultsCount === $totalStudents &&
        (
            $approvedStudents +
            $publishedStudents
        ) === $totalStudents;

    $hasUnpublishedApprovedResults =
        $approvedStudents > 0;

    $allResultsPublished =
        $totalStudents > 0 &&
        $publishedStudents === $totalStudents;

@endphp


<div class="container-fluid py-4 class-results-page">

    {{-- ================================================================
        PAGE HEADER
    ================================================================= --}}

    <div class="page-header mb-4">

        <div class="d-flex flex-wrap justify-content-between
                    align-items-center gap-3">

            <div class="d-flex align-items-center gap-3">

                <a href="{{ route('admin.results.index') }}"
                   class="back-btn">

                    <i class="bi bi-arrow-left"></i>

                </a>

                <div>

                    <div class="d-flex align-items-center flex-wrap gap-2">

                        <h3 class="fw-bold mb-0">

                            {{ $schoolClass->class_name }}

                        </h3>

                        @if($examClass->section)

                            <span class="section-badge">

                                Section {{ $examClass->section }}

                            </span>

                        @endif

                    </div>

                    <div class="text-muted mt-1">

                        <i class="bi bi-journal-text me-1"></i>

                        {{ $exam->exam_name }}

                        <span class="mx-2">•</span>

                        <i class="bi bi-calendar3 me-1"></i>

                        {{ $exam->academic_year }}

                    </div>

                </div>

            </div>


            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route('admin.results.bulk-whatsapp', [
                        'exam_id' => $exam->id,
                        'exam_class_id' => $examClass->id,
                        'class_id' => $schoolClass->id,
                        'section' => $examClass->section,
                    ]) }}"
                    class="btn btn-success px-3"
                >

                    <i class="bi bi-whatsapp me-2"></i>

                    Bulk WhatsApp

                </a>


                <a href="{{ route('admin.results.generate') }}"
                   class="btn btn-primary px-3">

                    <i class="bi bi-file-earmark-plus me-2"></i>

                    Generate Result

                </a>

            </div>

        </div>

    </div>


    {{-- ================================================================
        FLASH MESSAGES
    ================================================================= --}}

    @if(session('success'))

        <div class="alert alert-success custom-alert
                    alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon success-icon">

                    <i class="bi bi-check-lg"></i>

                </div>

                <div>

                    <strong>Success</strong>

                    <div>
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


    @if(session('error'))

        <div class="alert alert-danger custom-alert
                    alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon danger-icon">

                    <i class="bi bi-exclamation-lg"></i>

                </div>

                <div>

                    <strong>Error</strong>

                    <div>
                        {{ session('error') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('warning'))

        <div class="alert alert-warning custom-alert
                    alert-dismissible fade show">

            <div class="d-flex align-items-center">

                <div class="alert-icon warning-icon">

                    <i class="bi bi-exclamation-triangle"></i>

                </div>

                <div>

                    <strong>Warning</strong>

                    <div>
                        {{ session('warning') }}
                    </div>

                </div>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- ================================================================
        STATISTICS
    ================================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon blue">

                    <i class="bi bi-people-fill"></i>

                </div>

                <div class="stat-content">

                    <span>Total Students</span>

                    <h3>{{ $totalStudents }}</h3>

                    <small>
                        Class strength
                    </small>

                </div>

            </div>

        </div>


        {{-- Generated --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon primary">

                    <i class="bi bi-file-earmark-check-fill"></i>

                </div>

                <div class="stat-content">

                    <span>Generated</span>

                    <h3>{{ $generatedStudents }}</h3>

                    <small>
                        Results generated
                    </small>

                </div>

            </div>

        </div>


        {{-- Verified --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon info">

                    <i class="bi bi-shield-check"></i>

                </div>

                <div class="stat-content">

                    <span>Verified</span>

                    <h3>{{ $verifiedStudents }}</h3>

                    <small>
                        Results verified
                    </small>

                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon warning">

                    <i class="bi bi-check2-square"></i>

                </div>

                <div class="stat-content">

                    <span>Approved</span>

                    <h3>{{ $approvedStudents }}</h3>

                    <small>
                        Ready to publish
                    </small>

                </div>

            </div>

        </div>


        {{-- Published --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon success">

                    <i class="bi bi-cloud-check-fill"></i>

                </div>

                <div class="stat-content">

                    <span>Published</span>

                    <h3>{{ $publishedStudents }}</h3>

                    <small>
                        Online results
                    </small>

                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">

            <div class="stat-card">

                <div class="stat-icon danger">

                    <i class="bi bi-hourglass-split"></i>

                </div>

                <div class="stat-content">

                    <span>Pending</span>

                    <h3>{{ $pendingStudents }}</h3>

                    <small>
                        Awaiting result
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================
        RESULT WORKFLOW
    ================================================================= --}}

    <div class="card professional-card mb-4">

        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-start gap-3">

                <div>

                    <div class="section-title">

                        <div class="title-icon">

                            <i class="bi bi-shield-lock-fill"></i>

                        </div>

                        <div>

                            <h5 class="mb-1 fw-bold">

                                Result Verification & Publication

                            </h5>

                            <p class="text-muted mb-0">

                                Complete the result workflow for

                                <strong>
                                    {{ $schoolClass->class_name }}
                                </strong>

                                @if($examClass->section)

                                    • Section {{ $examClass->section }}

                                @endif

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Workflow Action --}}
                <div class="workflow-actions d-flex flex-wrap gap-2">


                    {{-- VERIFY --}}
                    @if($allResultsGenerated && $hasGeneratedResults)

                        <form method="POST"
                              action="{{ route(
                                  'admin.results.verify-class'
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to verify all generated results for this class and section?'
                              );">

                            @csrf

                            <input type="hidden"
                                   name="exam_id"
                                   value="{{ $exam->id }}">

                            <input type="hidden"
                                   name="exam_class_id"
                                   value="{{ $examClass->id }}">

                            <input type="hidden"
                                   name="class_id"
                                   value="{{ $schoolClass->id }}">

                            <input type="hidden"
                                   name="section"
                                   value="{{ $examClass->section }}">

                            <button type="submit"
                                    class="btn btn-info text-white">

                                <i class="bi bi-shield-check me-1"></i>

                                Verify All

                            </button>

                        </form>


                    @elseif(!$allResultsGenerated)

                        <span class="workflow-message warning">

                            <i class="bi bi-exclamation-circle me-1"></i>

                            Generate all results first

                        </span>


                    @elseif($allResultsVerifiedOrLater)

                        <span class="workflow-message info">

                            <i class="bi bi-check-circle me-1"></i>

                            All Results Verified

                        </span>

                    @endif


                    {{-- APPROVE --}}
                    @if($allResultsGenerated && $hasVerifiedResults)

                        <form method="POST"
                              action="{{ route(
                                  'admin.results.approve-class'
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to approve all verified results for this class and section?'
                              );">

                            @csrf

                            <input type="hidden"
                                   name="exam_id"
                                   value="{{ $exam->id }}">

                            <input type="hidden"
                                   name="exam_class_id"
                                   value="{{ $examClass->id }}">

                            <input type="hidden"
                                   name="class_id"
                                   value="{{ $schoolClass->id }}">

                            <input type="hidden"
                                   name="section"
                                   value="{{ $examClass->section }}">

                            <button type="submit"
                                    class="btn btn-warning">

                                <i class="bi bi-check2-square me-1"></i>

                                Approve All

                            </button>

                        </form>

                    @endif


                    {{-- PUBLISH --}}
                    @if(
                        $readyToPublish &&
                        $hasUnpublishedApprovedResults
                    )

                        <form method="POST"
                              action="{{ route(
                                  'admin.results.publish-class'
                              ) }}"
                              onsubmit="return confirm(
                                  'Are you sure you want to publish all approved results for this class and section?'
                              );">

                            @csrf

                            <input type="hidden"
                                   name="exam_id"
                                   value="{{ $exam->id }}">

                            <input type="hidden"
                                   name="exam_class_id"
                                   value="{{ $examClass->id }}">

                            <input type="hidden"
                                   name="class_id"
                                   value="{{ $schoolClass->id }}">

                            <input type="hidden"
                                   name="section"
                                   value="{{ $examClass->section }}">

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="bi bi-cloud-upload me-1"></i>

                                Publish All

                            </button>

                        </form>


                    @elseif($allResultsPublished)

                        <span class="workflow-message success">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            All Results Published

                        </span>

                    @endif

                </div>

            </div>


            {{-- Workflow Progress --}}
            @if($totalStudents > 0)

                <div class="workflow-progress mt-4">

                    <div class="workflow-step">

                        <div class="step-circle blue">

                            <i class="bi bi-file-earmark-check"></i>

                        </div>

                        <div>

                            <strong>
                                Generated
                            </strong>

                            <small>
                                {{ $resultsCount }}/{{ $totalStudents }}
                            </small>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-step">

                        <div class="step-circle info">

                            <i class="bi bi-shield-check"></i>

                        </div>

                        <div>

                            <strong>
                                Verified
                            </strong>

                            <small>
                                {{ $verifiedStudents }}/{{ $totalStudents }}
                            </small>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-step">

                        <div class="step-circle warning">

                            <i class="bi bi-check2-square"></i>

                        </div>

                        <div>

                            <strong>
                                Approved
                            </strong>

                            <small>
                                {{ $approvedStudents }}/{{ $totalStudents }}
                            </small>

                        </div>

                    </div>


                    <div class="workflow-line"></div>


                    <div class="workflow-step">

                        <div class="step-circle success">

                            <i class="bi bi-cloud-check"></i>

                        </div>

                        <div>

                            <strong>
                                Published
                            </strong>

                            <small>
                                {{ $publishedStudents }}/{{ $totalStudents }}
                            </small>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
        STUDENT RESULTS
    ================================================================= --}}

    <div class="card professional-card overflow-hidden">

        {{-- Table Header --}}
        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3">

                <div class="section-title mb-0">

                    <div class="title-icon table-icon">

                        <i class="bi bi-person-lines-fill"></i>

                    </div>

                    <div>

                        <h5 class="mb-1 fw-bold">
                            Student Results
                        </h5>

                        <p class="text-muted mb-0">

                            {{ $exam->exam_name }}

                            • {{ $schoolClass->class_name }}

                            @if($examClass->section)

                                • Section {{ $examClass->section }}

                            @endif

                        </p>

                    </div>

                </div>


                <div class="d-flex align-items-center gap-2">

                    <span class="student-count">

                        <i class="bi bi-people me-1"></i>

                        {{ $totalStudents }}

                        Student{{ $totalStudents === 1 ? '' : 's' }}

                    </span>

                    <a
                        href="{{ route('admin.results.bulk-whatsapp', [
                            'exam_id' => $exam->id,
                            'exam_class_id' => $examClass->id,
                            'class_id' => $schoolClass->id,
                            'section' => $examClass->section,
                        ]) }}"
                        class="btn btn-success btn-sm"
                    >

                        <i class="bi bi-whatsapp me-1"></i>

                        Send Result on WhatsApp

                    </a>

                </div>

            </div>

        </div>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="table student-table align-middle mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Roll No.
                        </th>

                        <th>
                            Result
                        </th>

                        <th>
                            Publication
                        </th>

                        <th>
                            Percentage
                        </th>

                        <th>
                            Grade
                        </th>

                        <th class="text-end pe-4">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($students as $index => $student)

                        @php

                            $result =
                                $results->get(
                                    $student->id
                                );

                            $fullName =
                                collect([
                                    $student->first_name,
                                    $student->middle_name,
                                    $student->last_name
                                ])
                                ->filter()
                                ->implode(' ');

                        @endphp


                        <tr>

                            {{-- Number --}}
                            <td class="ps-4">

                                <span class="row-number">

                                    {{ str_pad(
                                        $index + 1,
                                        2,
                                        '0',
                                        STR_PAD_LEFT
                                    ) }}

                                </span>

                            </td>


                            {{-- Student --}}
                            <td>

                                <div class="student-info">

                                    <div class="student-avatar">

                                        {{ strtoupper(
                                            substr(
                                                $student->first_name ?? 'S',
                                                0,
                                                1
                                            )
                                        ) }}

                                    </div>

                                    <div>

                                        <div class="student-name">

                                            {{ $fullName ?: '-' }}

                                        </div>

                                        <div class="student-id">

                                            <i class="bi bi-person-badge me-1"></i>

                                            {{ $student->student_id }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Roll --}}
                            <td>

                                @if($student->roll_number)

                                    <span class="roll-badge">

                                        {{ $student->roll_number }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Result Status --}}
                            <td>

                                @if($result)

                                    @if(
                                        strtolower(
                                            $result->result_status ?? ''
                                        ) === 'pass'
                                    )

                                        <span class="status-badge pass">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Pass

                                        </span>

                                    @elseif(
                                        strtolower(
                                            $result->result_status ?? ''
                                        ) === 'absent'
                                    )

                                        <span class="status-badge absent">

                                            <i class="bi bi-dash-circle-fill"></i>

                                            Absent

                                        </span>

                                    @else

                                        <span class="status-badge fail">

                                            <i class="bi bi-x-circle-fill"></i>

                                            Fail

                                        </span>

                                    @endif

                                @else

                                    <span class="status-badge pending">

                                        <i class="bi bi-clock"></i>

                                        Pending

                                    </span>

                                @endif

                            </td>


                            {{-- Publication --}}
                            <td>

                                @if(!$result)

                                    <span class="publication-badge none">

                                        Not Generated

                                    </span>

                                @else

                                    @switch($result->publication_status)

                                        @case('generated')

                                            <span class="publication-badge generated">

                                                <i class="bi bi-file-earmark-check"></i>

                                                Generated

                                            </span>

                                            @break


                                        @case('verified')

                                            <span class="publication-badge verified">

                                                <i class="bi bi-shield-check"></i>

                                                Verified

                                            </span>

                                            @break


                                        @case('approved')

                                            <span class="publication-badge approved">

                                                <i class="bi bi-check2-square"></i>

                                                Approved

                                            </span>

                                            @break


                                        @case('published')

                                            <span class="publication-badge published">

                                                <i class="bi bi-cloud-check"></i>

                                                Published

                                            </span>

                                            @break


                                        @default

                                            <span class="publication-badge none">

                                                {{ ucfirst(
                                                    $result->publication_status
                                                ) }}

                                            </span>

                                    @endswitch

                                @endif

                            </td>


                            {{-- Percentage --}}
                            <td>

                                @if($result)

                                    <div class="percentage-value">

                                        {{ number_format(
                                            (float) $result->percentage,
                                            2
                                        ) }}%

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Grade --}}
                            <td>

                                @if($result)

                                    <span class="grade-value">

                                        {{ $result->grade ?: '—' }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end pe-4">

                                @if($result)

                                    <div class="d-inline-flex gap-2">

                                        <a
                                            href="{{ route(
                                                'admin.results.show',
                                                $result->id
                                            ) }}"
                                            class="action-btn view"
                                            title="View Result"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <a
                                            href="{{ route(
                                                'admin.results.history',
                                                $result->id
                                            ) }}"
                                            class="action-btn history"
                                            title="Result History"
                                        >

                                            <i class="bi bi-clock-history"></i>

                                        </a>

                                    </div>

                                @else

                                    <a
                                        href="{{ route(
                                            'admin.results.generate'
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary">

                                        <i class="bi bi-file-earmark-plus me-1"></i>

                                        Generate

                                    </a>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="8"
                                class="empty-state">

                                <div class="empty-icon">

                                    <i class="bi bi-person-x"></i>

                                </div>

                                <h5>
                                    No Students Found
                                </h5>

                                <p>
                                    No students are available for this
                                    class and section.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Card Footer --}}
        <div class="card-footer bg-white border-0 p-4">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-3">

                <div class="footer-info">

                    <i class="bi bi-info-circle me-2"></i>

                    Results must be generated, verified and approved
                    before they can be published online.

                </div>


                <a href="{{ route('admin.results.index') }}"
                   class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-grid me-1"></i>

                    Back to Classes

                </a>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================
    PAGE STYLES
================================================================= --}}

<style>

    .class-results-page {
        --primary: #1677f0;
        --dark: #172033;
        --muted: #718096;
        --border: #e8edf4;
    }


    /* ------------------------------------------------------------
       Header
    ------------------------------------------------------------ */

    .page-header {
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f7faff 100%
        );

        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px 24px;

        box-shadow:
            0 5px 20px rgba(25, 42, 70, .05);
    }


    .back-btn {
        width: 42px;
        height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #dfe5ee;
        border-radius: 12px;

        color: #526174;
        background: #fff;

        text-decoration: none;

        transition: .2s ease;
    }


    .back-btn:hover {
        color: var(--primary);
        border-color: var(--primary);
        transform: translateX(-2px);
    }


    .section-badge {
        display: inline-flex;
        align-items: center;

        padding: 5px 10px;

        border-radius: 8px;

        background: #eaf3ff;
        color: var(--primary);

        font-size: 12px;
        font-weight: 700;
    }


    /* ------------------------------------------------------------
       Alerts
    ------------------------------------------------------------ */

    .custom-alert {
        border: 0;
        border-radius: 14px;
        padding: 14px 18px;

        box-shadow:
            0 4px 15px rgba(25, 42, 70, .05);
    }


    .alert-icon {
        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        margin-right: 12px;
    }


    .success-icon {
        background: rgba(25, 135, 84, .12);
    }


    .danger-icon {
        background: rgba(220, 53, 69, .12);
    }


    .warning-icon {
        background: rgba(255, 193, 7, .18);
    }


    /* ------------------------------------------------------------
       Statistic Cards
    ------------------------------------------------------------ */

    .stat-card {
        height: 100%;

        display: flex;
        align-items: center;

        gap: 14px;

        padding: 18px;

        background: #fff;

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow:
            0 5px 18px rgba(25, 42, 70, .045);

        transition: .2s ease;
    }


    .stat-card:hover {
        transform: translateY(-3px);

        box-shadow:
            0 10px 25px rgba(25, 42, 70, .08);
    }


    .stat-icon {
        width: 48px;
        height: 48px;

        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        font-size: 20px;
    }


    .stat-icon.blue {
        background: #eaf3ff;
        color: var(--primary);
    }


    .stat-icon.primary {
        background: #eef2ff;
        color: #4f46e5;
    }


    .stat-icon.info {
        background: #e7f8fb;
        color: #0aa2b5;
    }


    .stat-icon.warning {
        background: #fff7df;
        color: #d99a00;
    }


    .stat-icon.success {
        background: #e8f8ef;
        color: #198754;
    }


    .stat-icon.danger {
        background: #fff0f1;
        color: #dc3545;
    }


    .stat-content span {
        display: block;

        color: var(--muted);

        font-size: 12px;
        font-weight: 600;
    }


    .stat-content h3 {
        margin: 2px 0;

        color: var(--dark);

        font-size: 25px;
        font-weight: 800;
    }


    .stat-content small {
        color: #98a2b3;
        font-size: 11px;
    }


    /* ------------------------------------------------------------
       Professional Cards
    ------------------------------------------------------------ */

    .professional-card {
        border: 1px solid var(--border) !important;

        border-radius: 18px !important;

        box-shadow:
            0 6px 24px rgba(25, 42, 70, .05) !important;
    }


    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }


    .title-icon {
        width: 44px;
        height: 44px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eaf3ff;
        color: var(--primary);

        font-size: 19px;
    }


    .title-icon.table-icon {
        background: #edf7f1;
        color: #198754;
    }


    /* ------------------------------------------------------------
       Workflow
    ------------------------------------------------------------ */

    .workflow-actions {
        max-width: 100%;
    }


    .workflow-message {
        display: inline-flex;
        align-items: center;

        padding: 9px 13px;

        border-radius: 10px;

        font-size: 13px;
        font-weight: 600;
    }


    .workflow-message.warning {
        background: #fff7df;
        color: #956c00;
    }


    .workflow-message.info {
        background: #e7f8fb;
        color: #087b89;
    }


    .workflow-message.success {
        background: #e8f8ef;
        color: #198754;
    }


    .workflow-progress {
        display: flex;
        align-items: center;

        padding: 18px;

        background: #f8fafc;

        border: 1px solid #edf1f6;
        border-radius: 14px;
    }


    .workflow-step {
        display: flex;
        align-items: center;
        gap: 10px;

        min-width: 135px;
    }


    .workflow-step strong {
        display: block;

        color: var(--dark);

        font-size: 13px;
    }


    .workflow-step small {
        display: block;

        color: var(--muted);

        font-size: 11px;
    }


    .step-circle {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        font-size: 15px;
    }


    .step-circle.blue {
        background: #eaf3ff;
        color: var(--primary);
    }


    .step-circle.info {
        background: #e7f8fb;
        color: #0aa2b5;
    }


    .step-circle.warning {
        background: #fff7df;
        color: #d99a00;
    }


    .step-circle.success {
        background: #e8f8ef;
        color: #198754;
    }


    .workflow-line {
        flex: 1;

        height: 1px;

        margin: 0 14px;

        background: #dce3ec;
    }


    /* ------------------------------------------------------------
       Student Table
    ------------------------------------------------------------ */

    .student-table {
        border-top: 1px solid var(--border);
    }


    .student-table thead th {
        padding: 14px 12px;

        background: #f8fafc;

        color: #64748b;

        border-bottom: 1px solid var(--border);

        font-size: 11px;
        font-weight: 700;

        text-transform: uppercase;
        letter-spacing: .04em;

        white-space: nowrap;
    }


    .student-table tbody td {
        padding: 15px 12px;

        border-bottom: 1px solid #eef2f6;

        color: #334155;

        font-size: 13px;
    }


    .student-table tbody tr {
        transition: .15s ease;
    }


    .student-table tbody tr:hover {
        background: #f9fbff;
    }


    .row-number {
        color: #94a3b8;

        font-size: 12px;
        font-weight: 700;
    }


    .student-info {
        display: flex;
        align-items: center;
        gap: 11px;

        min-width: 220px;
    }


    .student-avatar {
        width: 40px;
        height: 40px;

        min-width: 40px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eaf3ff;
        color: var(--primary);

        font-size: 14px;
        font-weight: 800;
    }


    .student-name {
        color: var(--dark);

        font-size: 13px;
        font-weight: 700;
    }


    .student-id {
        margin-top: 2px;

        color: #94a3b8;

        font-size: 11px;
    }


    .roll-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 32px;
        padding: 5px 8px;

        border-radius: 7px;

        background: #f1f5f9;

        color: #475569;

        font-size: 12px;
        font-weight: 700;
    }


    /* ------------------------------------------------------------
       Result Status
    ------------------------------------------------------------ */

    .status-badge,
    .publication-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        padding: 6px 9px;

        border-radius: 8px;

        font-size: 11px;
        font-weight: 700;

        white-space: nowrap;
    }


    .status-badge.pass {
        background: #e8f8ef;
        color: #198754;
    }


    .status-badge.absent {
        background: #fff7df;
        color: #956c00;
    }


    .status-badge.fail {
        background: #fff0f1;
        color: #dc3545;
    }


    .status-badge.pending {
        background: #f1f5f9;
        color: #64748b;
    }


    .publication-badge.generated {
        background: #eaf3ff;
        color: var(--primary);
    }


    .publication-badge.verified {
        background: #e7f8fb;
        color: #087b89;
    }


    .publication-badge.approved {
        background: #fff7df;
        color: #956c00;
    }


    .publication-badge.published {
        background: #e8f8ef;
        color: #198754;
    }


    .publication-badge.none {
        background: #f1f5f9;
        color: #64748b;
    }


    .percentage-value {
        color: var(--dark);

        font-size: 13px;
        font-weight: 800;
    }


    .grade-value {
        display: inline-flex;

        min-width: 34px;

        align-items: center;
        justify-content: center;

        padding: 5px 8px;

        border-radius: 7px;

        background: #f1f5f9;

        color: #334155;

        font-size: 12px;
        font-weight: 800;
    }


    /* ------------------------------------------------------------
       Action Buttons
    ------------------------------------------------------------ */

    .action-btn {
        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        text-decoration: none;

        transition: .2s ease;
    }


    .action-btn.view {
        background: #eaf3ff;
        color: var(--primary);
    }


    .action-btn.history {
        background: #f1f5f9;
        color: #64748b;
    }


    .action-btn:hover {
        transform: translateY(-2px);
    }


    /* ------------------------------------------------------------
       Student Count
    ------------------------------------------------------------ */

    .student-count {
        display: inline-flex;
        align-items: center;

        padding: 7px 11px;

        border-radius: 9px;

        background: #eaf3ff;
        color: var(--primary);

        font-size: 12px;
        font-weight: 700;
    }


    /* ------------------------------------------------------------
       Empty State
    ------------------------------------------------------------ */

    .empty-state {
        padding: 65px 20px !important;

        text-align: center;
    }


    .empty-icon {
        width: 68px;
        height: 68px;

        margin: 0 auto 15px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 18px;

        background: #f1f5f9;

        color: #94a3b8;

        font-size: 28px;
    }


    .empty-state h5 {
        color: var(--dark);

        font-weight: 700;
    }


    .empty-state p {
        color: var(--muted);

        margin-bottom: 0;
    }


    /* ------------------------------------------------------------
       Footer
    ------------------------------------------------------------ */

    .footer-info {
        color: #7b8798;

        font-size: 12px;
    }


    /* ------------------------------------------------------------
       Buttons
    ------------------------------------------------------------ */

    .btn {
        border-radius: 9px;
        font-weight: 600;
    }


    /* ------------------------------------------------------------
       Responsive
    ------------------------------------------------------------ */

    @media (max-width: 991px) {

        .workflow-progress {
            overflow-x: auto;
        }

        .workflow-step {
            min-width: 130px;
        }

        .workflow-line {
            min-width: 30px;
        }

    }


    @media (max-width: 767px) {

        .page-header {
            padding: 18px;
        }


        .page-header .btn {
            width: 100%;
        }


        .page-header > div:last-child {
            width: 100%;
        }


        .stat-card {
            padding: 15px;
        }


        .workflow-progress {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }


        .workflow-line {
            width: 1px;
            min-width: 1px;
            height: 20px;

            margin: 0 0 0 18px;
        }


        .workflow-actions {
            width: 100%;
        }


        .workflow-actions form,
        .workflow-actions button {
            width: 100%;
        }


        .card-header {
            padding: 18px !important;
        }

    }

</style>

@endsection
