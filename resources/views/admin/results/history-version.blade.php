
@extends('layouts.app')

@section('title', 'Result Version')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">

            <a href="{{ route('admin.results.history', $result->id) }}"
               class="btn btn-light border rounded-3 shadow-sm">

                <i class="bi bi-arrow-left"></i>

            </a>

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Historical Result
                    </h4>

                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        Version {{ $version->version_no }}
                    </span>

                </div>

                <p class="text-muted mb-0">
                    Historical snapshot of the student's result
                </p>

            </div>

        </div>


        <a href="{{ route('admin.results.history', $result->id) }}"
           class="btn btn-outline-secondary rounded-3">

            <i class="bi bi-arrow-left me-1"></i>
            Back to History

        </a>

    </div>


    {{-- =========================================================
         HISTORICAL SNAPSHOT ALERT
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4 overflow-hidden">

        <div class="card-body p-0">

            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10">

                <div class="icon-box bg-primary text-white me-3">

                    <i class="bi bi-shield-lock-fill"></i>

                </div>

                <div>

                    <h6 class="fw-bold mb-1">
                        Historical Snapshot
                    </h6>

                    <p class="text-muted mb-0 small">

                        Version {{ $version->version_no }} is a preserved
                        historical copy of the result. Changes made to the
                        current result will not modify this version.

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         STUDENT / EXAM INFORMATION
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex align-items-center gap-2">

                <div class="section-icon">

                    <i class="bi bi-person-vcard"></i>

                </div>

                <div>

                    <h5 class="fw-bold mb-0">
                        Student & Examination Information
                    </h5>

                    <small class="text-muted">
                        Details saved with this historical version
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body px-4 pb-4">

            <div class="row g-3">

                {{-- Student ID --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Student ID
                            </small>

                            <strong class="text-dark">
                                {{ $version->student_id_snapshot ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Student Name --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Student Name
                            </small>

                            <strong class="text-dark">
                                {{ $version->student_name_snapshot ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Examination --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-journal-check"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Examination
                            </small>

                            <strong class="text-dark">
                                {{ $version->exam_name_snapshot ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-calendar3"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Academic Year
                            </small>

                            <strong class="text-dark">
                                {{ $version->academic_year ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Class --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-secondary bg-opacity-10 text-secondary">
                            <i class="bi bi-building"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Class
                            </small>

                            <strong class="text-dark">
                                {{ $version->class_name ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Section --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-diagram-3"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Section
                            </small>

                            <strong class="text-dark">
                                {{ $version->section ?? '-' }}
                            </strong>

                        </div>

                    </div>

                </div>


                {{-- Version --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-dark bg-opacity-10 text-dark">
                            <i class="bi bi-layers"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Result Version
                            </small>

                            <span class="badge bg-primary rounded-pill mt-1">

                                Version {{ $version->version_no }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- Generated At --}}
                <div class="col-xl-3 col-md-6">

                    <div class="info-box h-100">

                        <div class="info-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Generated At
                            </small>

                            <strong class="text-dark">

                                {{ $version->generated_at
                                    ? $version->generated_at->format('d M Y, h:i A')
                                    : '-' }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         SUBJECT-WISE MARKS
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-2">

                <div class="d-flex align-items-center gap-2">

                    <div class="section-icon">

                        <i class="bi bi-table"></i>

                    </div>

                    <div>

                        <h5 class="fw-bold mb-0">
                            Subject-wise Marks
                        </h5>

                        <small class="text-muted">
                            Marks preserved in this historical version
                        </small>

                    </div>

                </div>


                <span class="badge bg-light text-dark border rounded-pill px-3 py-2">

                    <i class="bi bi-book me-1"></i>

                    {{ $version->details->count() }}

                    Subject{{ $version->details->count() === 1 ? '' : 's' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($version->details->isEmpty())

                <div class="text-center py-5 px-3">

                    <div class="empty-icon mx-auto mb-3">

                        <i class="bi bi-journal-x"></i>

                    </div>

                    <h6 class="fw-bold">
                        No Subject Details Found
                    </h6>

                    <p class="text-muted mb-0">

                        This historical version does not contain
                        subject-wise marks.

                    </p>

                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4" style="width:70px;">
                                    #
                                </th>

                                <th>
                                    Subject
                                </th>

                                <th class="text-center">
                                    Internal
                                </th>

                                <th class="text-center">
                                    Theory
                                </th>

                                <th class="text-center">
                                    Practical
                                </th>

                                <th class="text-center">
                                    Obtained
                                </th>

                                <th class="text-center">
                                    Maximum
                                </th>

                                <th class="text-center">
                                    Grade
                                </th>

                                <th class="text-center pe-4">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($version->details as $index => $detail)

                                <tr>

                                    <td class="ps-4 text-muted fw-semibold">

                                        {{ str_pad(
                                            $index + 1,
                                            2,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}

                                    </td>


                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <div class="subject-icon">

                                                <i class="bi bi-book"></i>

                                            </div>

                                            <strong>
                                                {{ $detail->subject_name }}
                                            </strong>

                                        </div>

                                    </td>


                                    {{-- Internal --}}
                                    <td class="text-center">

                                        <span class="marks-value">

                                            {{ number_format(
                                                (float) $detail->internal_marks,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Theory --}}
                                    <td class="text-center">

                                        <span class="marks-value">

                                            {{ number_format(
                                                (float) $detail->theory_marks,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Practical --}}
                                    <td class="text-center">

                                        <span class="marks-value">

                                            {{ number_format(
                                                (float) $detail->practical_marks,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Obtained --}}
                                    <td class="text-center">

                                        <span class="obtained-mark">

                                            {{ number_format(
                                                (float) $detail->obtained_marks,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Maximum --}}
                                    <td class="text-center">

                                        <span class="text-muted">

                                            {{ number_format(
                                                (float) $detail->max_marks,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Grade --}}
                                    <td class="text-center">

                                        @if($detail->grade)

                                            <span class="grade-badge">
                                                {{ $detail->grade }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td class="text-center pe-4">

                                        @if($detail->status === 'present')

                                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2">

                                                <i class="bi bi-check-circle me-1"></i>
                                                Present

                                            </span>

                                        @elseif($detail->status === 'absent')

                                            <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning-subtle px-3 py-2">

                                                <i class="bi bi-person-x me-1"></i>
                                                Absent

                                            </span>

                                        @else

                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2">

                                                N/A

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         RESULT SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 py-3 px-4">

            <div class="d-flex align-items-center gap-2">

                <div class="section-icon">

                    <i class="bi bi-bar-chart-line"></i>

                </div>

                <div>

                    <h5 class="fw-bold mb-0">
                        Result Summary
                    </h5>

                    <small class="text-muted">
                        Overall performance recorded in this version
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body px-4 pb-4">

            <div class="row g-3">

                {{-- Total Maximum --}}
                <div class="col-xl-3 col-md-6">

                    <div class="summary-card">

                        <div class="summary-icon bg-primary bg-opacity-10 text-primary">

                            <i class="bi bi-123"></i>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Total Maximum Marks
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ number_format(
                                    (float) $version->total_marks,
                                    2
                                ) }}

                            </h4>

                        </div>

                    </div>

                </div>


                {{-- Obtained --}}
                <div class="col-xl-3 col-md-6">

                    <div class="summary-card">

                        <div class="summary-icon bg-success bg-opacity-10 text-success">

                            <i class="bi bi-check2-all"></i>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Obtained Marks
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ number_format(
                                    (float) $version->obtained_marks,
                                    2
                                ) }}

                            </h4>

                        </div>

                    </div>

                </div>


                {{-- Percentage --}}
                <div class="col-xl-3 col-md-6">

                    <div class="summary-card">

                        <div class="summary-icon bg-info bg-opacity-10 text-info">

                            <i class="bi bi-percent"></i>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Percentage
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ number_format(
                                    (float) $version->percentage,
                                    2
                                ) }}%

                            </h4>

                        </div>

                    </div>

                </div>


                {{-- Grade --}}
                <div class="col-xl-3 col-md-6">

                    <div class="summary-card">

                        <div class="summary-icon bg-warning bg-opacity-10 text-warning">

                            <i class="bi bi-award"></i>

                        </div>

                        <div>

                            <small class="text-muted d-block">
                                Overall Grade
                            </small>

                            <h4 class="fw-bold mb-0">

                                {{ $version->grade ?? '-' }}

                            </h4>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Result Status --}}
            <div class="result-status mt-4">

                <div>

                    <small class="text-muted d-block mb-1">
                        Final Result Status
                    </small>

                    <strong class="text-dark">
                        Historical Result
                    </strong>

                </div>


                <div>

                    @if($version->result_status === 'pass')

                        <span class="status-badge status-pass">

                            <i class="bi bi-check-circle-fill me-1"></i>
                            PASS

                        </span>

                    @elseif($version->result_status === 'fail')

                        <span class="status-badge status-fail">

                            <i class="bi bi-x-circle-fill me-1"></i>
                            FAIL

                        </span>

                    @elseif($version->result_status === 'absent')

                        <span class="status-badge status-absent">

                            <i class="bi bi-person-x-fill me-1"></i>
                            ABSENT

                        </span>

                    @else

                        <span class="status-badge status-other">

                            {{ strtoupper(
                                $version->result_status ?? 'N/A'
                            ) }}

                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     PAGE STYLES
========================================================== --}}
<style>

    :root {
        --school-primary: #1677f0;
    }

    .card {
        transition: all 0.2s ease;
    }

    .rounded-4 {
        border-radius: 16px !important;
    }

    .icon-box {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(22, 119, 240, 0.10);
        color: var(--school-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .info-box {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px;
        border: 1px solid #edf0f4;
        border-radius: 13px;
        background: #fff;
        transition: all 0.2s ease;
    }

    .info-box:hover {
        border-color: rgba(22, 119, 240, 0.25);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .info-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .table {
        --bs-table-hover-bg: rgba(22, 119, 240, 0.035);
    }

    .table thead th {
        background: #f8f9fb;
        color: #495057;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        border-bottom: 1px solid #e9ecef;
        padding: 14px 12px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 15px 12px;
        border-color: #f0f2f5;
    }

    .subject-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: rgba(22, 119, 240, 0.08);
        color: var(--school-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .marks-value {
        font-weight: 600;
        color: #495057;
    }

    .obtained-mark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 58px;
        padding: 5px 9px;
        border-radius: 8px;
        background: rgba(22, 119, 240, 0.08);
        color: var(--school-primary);
        font-weight: 700;
    }

    .grade-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 32px;
        padding: 0 10px;
        border-radius: 8px;
        background: #f1f3f5;
        color: #343a40;
        font-weight: 700;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: #f1f3f5;
        color: #adb5bd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 105px;
        padding: 20px;
        border: 1px solid #edf0f4;
        border-radius: 14px;
        background: #fff;
        transition: all 0.2s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .summary-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .result-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 18px 20px;
        border: 1px solid #edf0f4;
        border-radius: 14px;
        background: #f8f9fb;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 120px;
        padding: 10px 22px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .status-pass {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
    }

    .status-fail {
        background: rgba(220, 53, 69, 0.12);
        color: #dc3545;
    }

    .status-absent {
        background: rgba(255, 193, 7, 0.18);
        color: #997404;
    }

    .status-other {
        background: rgba(108, 117, 125, 0.12);
        color: #6c757d;
    }

    @media (max-width: 767.98px) {

        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .result-status {
            flex-direction: column;
            align-items: flex-start;
        }

        .status-badge {
            width: 100%;
        }

        .table {
            min-width: 900px;
        }

    }

</style>

@endsection
