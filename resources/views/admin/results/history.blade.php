
@extends('layouts.app')

@section('title', 'Result History')

@section('content')

@php
    $versionsCount = $result->versions->count();

    $studentName = $result->student
        ? trim(
            $result->student->first_name . ' ' .
            $result->student->middle_name . ' ' .
            $result->student->last_name
        )
        : '-';
@endphp

<style>
    /* =========================================================
       RESULT HISTORY PAGE
    ========================================================== */

    .result-history-page {
        --rh-primary: #1677f0;
        --rh-primary-dark: #0f5fc4;
        --rh-text: #1f2937;
        --rh-muted: #6b7280;
        --rh-border: #e8edf3;
        --rh-soft: #f6f9fc;
        --rh-radius: 16px;
    }

    /* Header */
    .rh-page-header {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%);
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
    }

    .rh-page-header::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -70px;
        top: -90px;
        border-radius: 50%;
        background: rgba(22, 119, 240, 0.07);
    }

    .rh-title-row {
        position: relative;
        z-index: 1;
    }

    .rh-title-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(22, 119, 240, 0.10);
        color: var(--rh-primary);
        font-size: 24px;
    }

    .rh-page-title {
        color: var(--rh-text);
        font-weight: 700;
        font-size: 24px;
        margin: 0;
    }

    .rh-page-subtitle {
        color: var(--rh-muted);
        font-size: 14px;
        margin: 4px 0 0;
    }

    .rh-back-btn {
        position: relative;
        z-index: 2;
        border-radius: 10px;
        padding: 9px 15px;
        font-weight: 600;
        background: #fff;
    }

    /* Common Card */
    .rh-card {
        background: #fff;
        border: 1px solid var(--rh-border);
        border-radius: var(--rh-radius);
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .rh-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid var(--rh-border);
        background: #fff;
    }

    .rh-section-title {
        color: var(--rh-text);
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .rh-section-title i {
        color: var(--rh-primary);
    }

    .rh-section-subtitle {
        color: var(--rh-muted);
        font-size: 12px;
        margin-top: 3px;
    }

    /* Student information */
    .rh-info-body {
        padding: 22px;
    }

    .rh-info-item {
        height: 100%;
        background: var(--rh-soft);
        border: 1px solid var(--rh-border);
        border-radius: 12px;
        padding: 15px 16px;
        transition: all .2s ease;
    }

    .rh-info-item:hover {
        border-color: rgba(22, 119, 240, 0.25);
        transform: translateY(-1px);
    }

    .rh-info-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: var(--rh-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 7px;
    }

    .rh-info-label i {
        color: var(--rh-primary);
        font-size: 14px;
    }

    .rh-info-value {
        color: var(--rh-text);
        font-size: 14px;
        font-weight: 600;
        word-break: break-word;
    }

    /* Version header */
    .rh-version-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 20px;
        background: rgba(22, 119, 240, 0.09);
        color: var(--rh-primary);
        font-size: 12px;
        font-weight: 700;
    }

    /* Empty state */
    .rh-empty {
        padding: 65px 20px;
        text-align: center;
    }

    .rh-empty-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 18px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--rh-soft);
        color: #9aa6b2;
        font-size: 32px;
    }

    .rh-empty-title {
        color: var(--rh-text);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 6px;
    }

    .rh-empty-text {
        color: var(--rh-muted);
        font-size: 13px;
        margin: 0;
    }

    /* Table */
    .rh-table-wrapper {
        overflow-x: auto;
    }

    .rh-table {
        min-width: 950px;
        margin: 0;
    }

    .rh-table thead th {
        background: #f8fafc;
        color: #64748b;
        border-bottom: 1px solid var(--rh-border);
        border-top: 0;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: 15px 14px;
        white-space: nowrap;
    }

    .rh-table tbody td {
        color: #475569;
        border-color: #eef2f6;
        padding: 16px 14px;
        font-size: 13px;
        vertical-align: middle;
    }

    .rh-table tbody tr {
        transition: background .18s ease;
    }

    .rh-table tbody tr:hover {
        background: #f8fbff;
    }

    .rh-version-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 10px;
        border-radius: 8px;
        background: rgba(22, 119, 240, 0.10);
        color: var(--rh-primary);
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .rh-mark-value {
        color: var(--rh-text);
        font-weight: 600;
    }

    .rh-percentage {
        color: var(--rh-primary);
        font-weight: 700;
    }

    .rh-grade {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #f1f5f9;
        color: var(--rh-text);
        font-weight: 700;
    }

    /* Status badges */
    .rh-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .03em;
    }

    .rh-status-pass {
        background: #eaf8ef;
        color: #198754;
    }

    .rh-status-fail {
        background: #fdecec;
        color: #dc3545;
    }

    .rh-status-absent {
        background: #fff4d8;
        color: #996c00;
    }

    .rh-status-other {
        background: #eef1f5;
        color: #64748b;
    }

    /* View button */
    .rh-view-btn {
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        padding: 7px 12px;
        white-space: nowrap;
    }

    /* Timeline indicator */
    .rh-timeline-dot {
        position: relative;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(22, 119, 240, 0.10);
        color: var(--rh-primary);
        font-size: 13px;
        font-weight: 700;
    }

    /* Mobile */
    @media (max-width: 767.98px) {

        .result-history-page {
            padding-top: 10px !important;
        }

        .rh-page-header {
            padding: 18px;
        }

        .rh-page-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
        }

        .rh-back-btn {
            width: 100%;
        }

        .rh-page-title {
            font-size: 20px;
        }

        .rh-info-body {
            padding: 15px;
        }

        .rh-card-header {
            padding: 16px;
        }

        .rh-section-title {
            font-size: 15px;
        }
    }
</style>


<div class="container-fluid py-4 result-history-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="rh-page-header">

        <div class="d-flex justify-content-between align-items-center gap-3">

            <div class="rh-title-row d-flex align-items-center gap-3">

                <div class="rh-title-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>
                    <h4 class="rh-page-title">
                        Result History
                    </h4>

                    <p class="rh-page-subtitle">
                        View and review historical versions of this student's result
                    </p>
                </div>

            </div>

            <a href="{{ route('admin.results.index') }}"
               class="btn btn-outline-secondary rh-back-btn">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Results

            </a>

        </div>

    </div>


    {{-- =========================================================
         STUDENT / RESULT INFORMATION
    ========================================================== --}}
    <div class="rh-card mb-4">

        <div class="rh-card-header">

            <div class="d-flex align-items-center gap-2">

                <i class="bi bi-person-vcard fs-5 text-primary"></i>

                <div>
                    <h5 class="rh-section-title">
                        Student & Result Information
                    </h5>

                    <div class="rh-section-subtitle">
                        Details associated with this result record
                    </div>
                </div>

            </div>

        </div>


        <div class="rh-info-body">

            <div class="row g-3">

                {{-- Student ID --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-person-badge"></i>
                            Student ID
                        </div>

                        <div class="rh-info-value">
                            {{ $result->student?->student_id ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Student Name --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-person"></i>
                            Student Name
                        </div>

                        <div class="rh-info-value">
                            {{ $studentName }}
                        </div>

                    </div>

                </div>


                {{-- Examination --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-journal-text"></i>
                            Examination
                        </div>

                        <div class="rh-info-value">
                            {{ $result->exam?->exam_name ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Academic Year --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-calendar3"></i>
                            Academic Year
                        </div>

                        <div class="rh-info-value">
                            {{ $result->academic_year ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Class --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-mortarboard"></i>
                            Class
                        </div>

                        <div class="rh-info-value">
                            {{ $result->class_name ?? '-' }}
                        </div>

                    </div>

                </div>


                {{-- Section --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-grid-3x3-gap"></i>
                            Section
                        </div>

                        <div class="rh-info-value">
                            {{ $result->section ?: '-' }}
                        </div>

                    </div>

                </div>


                {{-- History Count --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-layers"></i>
                            Saved Versions
                        </div>

                        <div class="rh-info-value">
                            {{ $versionsCount }}
                            {{ $versionsCount === 1 ? 'Version' : 'Versions' }}
                        </div>

                    </div>

                </div>


                {{-- History Type --}}
                <div class="col-sm-6 col-lg-3">

                    <div class="rh-info-item">

                        <div class="rh-info-label">
                            <i class="bi bi-shield-check"></i>
                            Record Type
                        </div>

                        <div class="rh-info-value">
                            Historical Snapshot
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         VERSION HISTORY
    ========================================================== --}}
    <div class="rh-card">

        <div class="rh-card-header">

            <div class="d-flex justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-layers fs-5 text-primary"></i>

                    <div>
                        <h5 class="rh-section-title">
                            Generated Versions
                        </h5>

                        <div class="rh-section-subtitle">
                            Previously generated snapshots of this student's result
                        </div>
                    </div>

                </div>

                <span class="rh-version-count">

                    <i class="bi bi-archive"></i>

                    {{ $versionsCount }}
                    {{ $versionsCount === 1 ? 'Version' : 'Versions' }}

                </span>

            </div>

        </div>


        <div>

            @if($result->versions->isEmpty())

                {{-- Empty State --}}
                <div class="rh-empty">

                    <div class="rh-empty-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>

                    <h6 class="rh-empty-title">
                        No Result History Available
                    </h6>

                    <p class="rh-empty-text">
                        No historical result versions have been generated for this student yet.
                    </p>

                </div>

            @else

                <div class="rh-table-wrapper">

                    <table class="table rh-table align-middle">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    Version
                                </th>

                                <th>
                                    Generated Date
                                </th>

                                <th>
                                    Total Marks
                                </th>

                                <th>
                                    Obtained
                                </th>

                                <th>
                                    Percentage
                                </th>

                                <th>
                                    Grade
                                </th>

                                <th>
                                    Result Status
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($result->versions as $version)

                                <tr>

                                    {{-- Version --}}
                                    <td class="ps-4">

                                        <div class="d-flex align-items-center gap-2">

                                            <span class="rh-timeline-dot">
                                                {{ $version->version_no }}
                                            </span>

                                            <span class="rh-version-badge">
                                                Version {{ $version->version_no }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Generated Date --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <i class="bi bi-calendar-event text-muted"></i>

                                            <span>
                                                {{ $version->generated_at
                                                    ? $version->generated_at->format('d M Y, h:i A')
                                                    : '-' }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Total Marks --}}
                                    <td>

                                        <span class="rh-mark-value">
                                            {{ number_format(
                                                (float) $version->total_marks,
                                                2
                                            ) }}
                                        </span>

                                    </td>


                                    {{-- Obtained Marks --}}
                                    <td>

                                        <span class="rh-mark-value">
                                            {{ number_format(
                                                (float) $version->obtained_marks,
                                                2
                                            ) }}
                                        </span>

                                    </td>


                                    {{-- Percentage --}}
                                    <td>

                                        <span class="rh-percentage">

                                            {{ number_format(
                                                (float) $version->percentage,
                                                2
                                            ) }}%

                                        </span>

                                    </td>


                                    {{-- Grade --}}
                                    <td>

                                        <span class="rh-grade">
                                            {{ $version->grade ?? '-' }}
                                        </span>

                                    </td>


                                    {{-- Result Status --}}
                                    <td>

                                        @if($version->result_status === 'pass')

                                            <span class="rh-status rh-status-pass">
                                                <i class="bi bi-check-circle-fill"></i>
                                                PASS
                                            </span>

                                        @elseif($version->result_status === 'fail')

                                            <span class="rh-status rh-status-fail">
                                                <i class="bi bi-x-circle-fill"></i>
                                                FAIL
                                            </span>

                                        @elseif($version->result_status === 'absent')

                                            <span class="rh-status rh-status-absent">
                                                <i class="bi bi-dash-circle-fill"></i>
                                                ABSENT
                                            </span>

                                        @else

                                            <span class="rh-status rh-status-other">

                                                <i class="bi bi-info-circle-fill"></i>

                                                {{ strtoupper(
                                                    $version->result_status ?? '-'
                                                ) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end pe-4">

                                        <a href="{{ route(
                                            'admin.results.history-version',
                                            [
                                                'result' => $result->id,
                                                'version' => $version->id,
                                            ]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary rh-view-btn">

                                            <i class="bi bi-eye me-1"></i>
                                            View Version

                                        </a>

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

@endsection
