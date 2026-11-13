
@extends('layouts.app')

@section('title', 'Student Result')

@section('content')

@php
    $publicationStatus = strtolower(
        trim((string) ($result->publication_status ?? 'generated'))
    );

    $resultStatus = strtolower(
        trim((string) ($result->result_status ?? ''))
    );

    $studentName = trim(
        ($result->student?->first_name ?? '') . ' ' .
        ($result->student?->middle_name ?? '') . ' ' .
        ($result->student?->last_name ?? '')
    );

    $studentName = $studentName ?: (
        $result->student?->full_name
        ?? $result->student?->name
        ?? 'N/A'
    );

    $percentage = (float) ($result->percentage ?? 0);
    $percentage = max(0, min(100, $percentage));

    $subjectCount = $result->details?->count() ?? 0;

    $statusConfig = match ($publicationStatus) {
        'generated' => [
            'label' => 'Generated',
            'icon' => 'bi-file-earmark-check',
            'class' => 'status-generated',
        ],
        'verified' => [
            'label' => 'Verified',
            'icon' => 'bi-check-circle',
            'class' => 'status-verified',
        ],
        'approved' => [
            'label' => 'Approved',
            'icon' => 'bi-shield-check',
            'class' => 'status-approved',
        ],
        'published' => [
            'label' => 'Published',
            'icon' => 'bi-globe2',
            'class' => 'status-published',
        ],
        default => [
            'label' => ucfirst($publicationStatus),
            'icon' => 'bi-info-circle',
            'class' => 'status-default',
        ],
    };
@endphp

<style>
    .result-page {
        --primary: #1677f0;
        --primary-dark: #0d63d3;
        --primary-soft: #eef6ff;
        --success: #16803c;
        --success-soft: #edf9f1;
        --warning: #a66b00;
        --warning-soft: #fff8e6;
        --danger: #d92d20;
        --danger-soft: #fff1f0;
        --info: #087f9b;
        --info-soft: #eafaff;
        --text: #172033;
        --muted: #667085;
        --border: #e4e9f0;
        --surface: #ffffff;
        --page: #f6f8fb;
    }

    .result-page {
        color: var(--text);
    }

    .result-hero {
        position: relative;
        overflow: hidden;
        border-radius: 18px;
        padding: 26px;
        margin-bottom: 22px;
        background:
            radial-gradient(circle at 90% 10%, rgba(255,255,255,.18), transparent 28%),
            linear-gradient(135deg, #1677f0 0%, #125fca 100%);
        color: #fff;
        box-shadow: 0 12px 30px rgba(22,119,240,.18);
    }

    .result-hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        right: -65px;
        bottom: -90px;
        border-radius: 50%;
        border: 25px solid rgba(255,255,255,.08);
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-icon {
        width: 54px;
        height: 54px;
        border-radius: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 25px;
        margin-bottom: 14px;
    }

    .hero-title {
        font-size: 25px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .hero-subtitle {
        color: rgba(255,255,255,.82);
        font-size: 14px;
        margin: 0;
    }

    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 20px;
    }

    .hero-actions .btn {
        border-radius: 9px;
        font-weight: 600;
        padding: 8px 14px;
    }

    .hero-actions .btn-light {
        color: #1559b5;
    }

    .hero-actions .btn-outline-light:hover {
        color: #1559b5;
    }

    .result-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        background: var(--surface);
        box-shadow: 0 5px 18px rgba(16,24,40,.05);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .result-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid var(--border);
        background: #fff;
    }

    .result-card-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .result-card-title i {
        color: var(--primary);
        font-size: 19px;
    }

    .result-card-subtitle {
        margin: 3px 0 0 28px;
        color: var(--muted);
        font-size: 12px;
    }

    .result-card-body {
        padding: 20px;
    }

    /* Publication */

    .publication-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .publication-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-generated {
        color: #475467;
        background: #f2f4f7;
    }

    .status-verified {
        color: #087f9b;
        background: var(--info-soft);
    }

    .status-approved {
        color: #8a5a00;
        background: var(--warning-soft);
    }

    .status-published {
        color: var(--success);
        background: var(--success-soft);
    }

    .status-default {
        color: #344054;
        background: #f2f4f7;
    }

    .workflow {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }

    .workflow-step {
        position: relative;
        min-height: 104px;
        padding: 15px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: #fbfcfe;
        transition: .2s ease;
    }

    .workflow-step.active {
        background: #fff;
        box-shadow: 0 4px 14px rgba(16,24,40,.05);
    }

    .workflow-step.completed.generated {
        border-color: #b9c2cc;
    }

    .workflow-step.completed.verified {
        border-color: #80d7e8;
        background: #f7fdff;
    }

    .workflow-step.completed.approved {
        border-color: #efd18a;
        background: #fffdf6;
    }

    .workflow-step.completed.published {
        border-color: #9bd8af;
        background: #f7fcf8;
    }

    .workflow-number {
        width: 39px;
        height: 39px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 11px;
        font-size: 17px;
    }

    .workflow-generated .workflow-number {
        background: #eaecf0;
        color: #475467;
    }

    .workflow-verified .workflow-number {
        background: var(--info-soft);
        color: var(--info);
    }

    .workflow-approved .workflow-number {
        background: var(--warning-soft);
        color: var(--warning);
    }

    .workflow-published .workflow-number {
        background: var(--success-soft);
        color: var(--success);
    }

    .workflow-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .workflow-text {
        font-size: 11px;
        color: var(--muted);
    }

    .publication-actions {
        border-top: 1px solid var(--border);
        margin-top: 20px;
        padding-top: 17px;
    }

    .publication-actions .btn {
        border-radius: 9px;
        font-weight: 600;
    }

    .published-alert {
        border: 1px solid #b9e4c7;
        background: var(--success-soft);
        color: #176b35;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .published-alert-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #d9f2e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--success);
        font-size: 19px;
        flex-shrink: 0;
    }

    .whatsapp-row {
        display: flex;
        justify-content: flex-end;
        margin-top: -8px;
        margin-bottom: 22px;
    }

    .whatsapp-btn {
        background: #25d366;
        border-color: #25d366;
        color: #fff;
        border-radius: 9px;
        font-weight: 600;
        padding: 9px 15px;
        box-shadow: 0 5px 12px rgba(37,211,102,.16);
    }

    .whatsapp-btn:hover {
        background: #1fbd59;
        border-color: #1fbd59;
        color: #fff;
    }

    /* Information */

    .info-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 12px;
    }

    .info-item {
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: #fbfcfe;
        min-height: 77px;
    }

    .info-label {
        font-size: 11px;
        color: var(--muted);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 600;
    }

    .info-value {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        word-break: break-word;
    }

    /* Marks */

    .marks-card {
        overflow: hidden;
    }

    .marks-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px;
    }

    .subject-count {
        background: var(--primary-soft);
        color: var(--primary);
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .marks-table {
        margin: 0;
        min-width: 720px;
    }

    .marks-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid var(--border);
        border-top: 0;
        color: #475467;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        padding: 13px 12px;
        white-space: nowrap;
    }

    .marks-table tbody td {
        padding: 13px 12px;
        border-color: #edf0f4;
        font-size: 13px;
    }

    .marks-table tbody tr:hover {
        background: #fbfdff;
    }

    .subject-number {
        width: 29px;
        height: 29px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f2f4f7;
        color: #475467;
        font-size: 11px;
        font-weight: 700;
    }

    .grade-badge {
        min-width: 35px;
        display: inline-flex;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 7px;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 11px;
        font-weight: 800;
    }

    .percentage-cell {
        font-weight: 700;
    }

    /* Summary */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 13px;
    }

    .summary-item {
        position: relative;
        overflow: hidden;
        padding: 17px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: #fff;
    }

    .summary-item::after {
        content: "";
        position: absolute;
        width: 65px;
        height: 65px;
        right: -25px;
        bottom: -25px;
        border-radius: 50%;
        background: var(--primary-soft);
    }

    .summary-label {
        position: relative;
        z-index: 1;
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 600;
    }

    .summary-value {
        position: relative;
        z-index: 1;
        margin-top: 7px;
        font-size: 23px;
        font-weight: 800;
        color: var(--text);
    }

    .summary-value.primary {
        color: var(--primary);
    }

    .progress-wrap {
        margin-top: 11px;
    }

    .progress {
        height: 6px;
        border-radius: 99px;
        background: #edf1f5;
    }

    .progress-bar {
        border-radius: 99px;
        background: var(--primary);
    }

    /* Final status */

    .final-status-card {
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--border);
        background: #fff;
        box-shadow: 0 5px 18px rgba(16,24,40,.05);
    }

    .final-status-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .final-status-label {
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 600;
    }

    .final-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 7px;
        padding: 8px 15px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
    }

    .final-pass {
        color: var(--success);
        background: var(--success-soft);
    }

    .final-fail {
        color: var(--danger);
        background: var(--danger-soft);
    }

    .final-absent {
        color: #8a5a00;
        background: var(--warning-soft);
    }

    .final-other {
        color: #475467;
        background: #f2f4f7;
    }

    .generated-box {
        text-align: right;
        padding-left: 20px;
        border-left: 1px solid var(--border);
    }

    .generated-label {
        color: var(--muted);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 600;
    }

    .generated-value {
        margin-top: 5px;
        font-size: 13px;
        font-weight: 700;
    }

    /* Footer */

    .footer-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        padding-bottom: 10px;
    }

    .footer-actions .btn {
        border-radius: 9px;
        font-weight: 600;
    }

    @media (max-width: 1199px) {
        .info-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .workflow {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767px) {
        .result-hero {
            padding: 20px;
            border-radius: 14px;
        }

        .hero-title {
            font-size: 21px;
        }

        .hero-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .hero-actions .btn {
            width: 100%;
        }

        .result-card-header,
        .result-card-body {
            padding: 15px;
        }

        .publication-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .workflow {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr 1fr;
        }

        .final-status-inner {
            align-items: flex-start;
            flex-direction: column;
        }

        .generated-box {
            width: 100%;
            text-align: left;
            border-left: 0;
            border-top: 1px solid var(--border);
            padding-left: 0;
            padding-top: 15px;
        }

        .whatsapp-row {
            justify-content: stretch;
        }

        .whatsapp-row .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .info-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .hero-actions {
            grid-template-columns: 1fr;
        }

        .footer-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .footer-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4 result-page">

    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('warning') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
        PAGE HERO
    ========================================================== --}}

    <div class="result-hero">

        <div class="hero-content">

            <div class="hero-icon">
                <i class="bi bi-file-earmark-text"></i>
            </div>

            <h1 class="hero-title">
                Student Result
            </h1>

            <p class="hero-subtitle">
                View examination performance, publication status and result history.
            </p>

            <div class="hero-actions">

                <a href="{{ route('admin.results.print', $result) }}"
                   target="_blank"
                   class="btn btn-light">

                    <i class="bi bi-printer me-1"></i>
                    Print Result

                </a>

                <a href="{{ route('admin.results.pdf', $result) }}"
                   target="_blank"
                   class="btn btn-outline-light">

                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    PDF

                </a>

                <a href="{{ route('admin.results.history', $result) }}"
                   class="btn btn-outline-light">

                    <i class="bi bi-clock-history me-1"></i>
                    History

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        PUBLICATION WORKFLOW
    ========================================================== --}}

    <div class="result-card">

        <div class="result-card-header">

            <div class="publication-header">

                <div>

                    <h5 class="result-card-title">
                        <i class="bi bi-cloud-arrow-up"></i>
                        Result Publication
                    </h5>

                    <p class="result-card-subtitle">
                        Follow the verification and approval workflow before publishing online.
                    </p>

                </div>

                <span class="publication-status {{ $statusConfig['class'] }}">
                    <i class="bi {{ $statusConfig['icon'] }}"></i>
                    {{ $statusConfig['label'] }}
                </span>

            </div>

        </div>

        <div class="result-card-body">

            <div class="workflow">

                {{-- GENERATED --}}

                <div class="workflow-step workflow-generated
                    {{ in_array($publicationStatus, ['generated', 'verified', 'approved', 'published']) ? 'completed generated active' : '' }}">

                    <div class="workflow-number">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>

                    <div class="workflow-title">
                        1. Generated
                    </div>

                    <div class="workflow-text">
                        Result has been generated.
                    </div>

                </div>


                {{-- VERIFIED --}}

                <div class="workflow-step workflow-verified
                    {{ in_array($publicationStatus, ['verified', 'approved', 'published']) ? 'completed verified active' : '' }}">

                    <div class="workflow-number">
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div class="workflow-title">
                        2. Verified
                    </div>

                    <div class="workflow-text">
                        Result has been verified.
                    </div>

                </div>


                {{-- APPROVED --}}

                <div class="workflow-step workflow-approved
                    {{ in_array($publicationStatus, ['approved', 'published']) ? 'completed approved active' : '' }}">

                    <div class="workflow-number">
                        <i class="bi bi-shield-check"></i>
                    </div>

                    <div class="workflow-title">
                        3. Approved
                    </div>

                    <div class="workflow-text">
                        Approved for online publishing.
                    </div>

                </div>


                {{-- PUBLISHED --}}

                <div class="workflow-step workflow-published
                    {{ $publicationStatus === 'published' ? 'completed published active' : '' }}">

                    <div class="workflow-number">
                        <i class="bi bi-globe2"></i>
                    </div>

                    <div class="workflow-title">
                        4. Published
                    </div>

                    <div class="workflow-text">
                        Available for online viewing.
                    </div>

                </div>

            </div>


            {{-- ACTIONS --}}

            <div class="publication-actions">

                @if($publicationStatus === 'generated')

                    <form method="POST"
                          action="{{ route('admin.results.verify', $result) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-info text-white">

                            <i class="bi bi-check-circle me-1"></i>
                            Verify Result

                        </button>

                    </form>


                @elseif($publicationStatus === 'verified')

                    <form method="POST"
                          action="{{ route('admin.results.approve', $result) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-warning">

                            <i class="bi bi-shield-check me-1"></i>
                            Approve Result

                        </button>

                    </form>


                @elseif($publicationStatus === 'approved')

                    <form method="POST"
                          action="{{ route('admin.results.publish', $result) }}">

                        @csrf

                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Are you sure you want to publish this result online?');">

                            <i class="bi bi-cloud-arrow-up me-1"></i>
                            Publish Result

                        </button>

                    </form>


                @elseif($publicationStatus === 'published')

                    <div class="published-alert">

                        <div class="d-flex align-items-start gap-3">

                            <div class="published-alert-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>

                            <div>

                                <div class="fw-bold mb-1">
                                    Result Published Successfully
                                </div>

                                <div class="small">

                                    This result is now available for online viewing.

                                    @if($result->published_at)
                                        <br>
                                        Published on:
                                        <strong>
                                            {{ $result->published_at->format('d M Y, h:i A') }}
                                        </strong>
                                    @endif

                                    @if($result->publisher)
                                        <br>
                                        Published by:
                                        <strong>
                                            {{ $result->publisher->name }}
                                        </strong>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- WHATSAPP --}}

    @if($publicationStatus === 'published')

        <div class="whatsapp-row">

            <a href="{{ route('admin.results.whatsapp', $result) }}"
               class="btn whatsapp-btn"
               onclick="return confirm('Open WhatsApp with the result message for this parent?');"
               target="_blank">

                <i class="bi bi-whatsapp me-1"></i>
                Send WhatsApp Notification

            </a>

        </div>

    @endif


    {{-- =========================================================
        STUDENT INFORMATION
    ========================================================== --}}

    <div class="result-card">

        <div class="result-card-header">

            <h5 class="result-card-title">
                <i class="bi bi-person-vcard"></i>
                Student Information
            </h5>

            <p class="result-card-subtitle">
                Basic student and academic identification details.
            </p>

        </div>

        <div class="result-card-body">

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Student ID</div>
                    <div class="info-value">
                        {{ $result->student?->student_id ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Student Name</div>
                    <div class="info-value">
                        {{ $studentName }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Class</div>
                    <div class="info-value">
                        {{ $result->class_name ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Section</div>
                    <div class="info-value">
                        {{ $result->section ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Roll Number</div>
                    <div class="info-value">
                        {{ $result->student?->roll_number ?? 'N/A' }}
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        EXAMINATION INFORMATION
    ========================================================== --}}

    <div class="result-card">

        <div class="result-card-header">

            <h5 class="result-card-title">
                <i class="bi bi-journal-text"></i>
                Examination Information
            </h5>

            <p class="result-card-subtitle">
                Examination schedule and academic year details.
            </p>

        </div>

        <div class="result-card-body">

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Examination</div>
                    <div class="info-value">
                        {{ $result->exam?->exam_name ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Exam Type</div>
                    <div class="info-value">
                        {{ $result->exam?->exam_type ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Academic Year</div>
                    <div class="info-value">
                        {{ $result->academic_year ?? 'N/A' }}
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Start Date</div>
                    <div class="info-value">

                        @if($result->exam?->start_date)

                            {{ \Carbon\Carbon::parse(
                                $result->exam->start_date
                            )->format('d M Y') }}

                        @else
                            N/A
                        @endif

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">End Date</div>
                    <div class="info-value">

                        @if($result->exam?->end_date)

                            {{ \Carbon\Carbon::parse(
                                $result->exam->end_date
                            )->format('d M Y') }}

                        @else
                            N/A
                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUBJECT-WISE MARKS
    ========================================================== --}}

    <div class="result-card marks-card">

        <div class="result-card-header">

            <div class="marks-toolbar mb-0">

                <div>

                    <h5 class="result-card-title">
                        <i class="bi bi-table"></i>
                        Subject-wise Marks
                    </h5>

                    <p class="result-card-subtitle">
                        Detailed marks and subject performance.
                    </p>

                </div>

                <span class="subject-count">
                    {{ $subjectCount }} {{ $subjectCount === 1 ? 'Subject' : 'Subjects' }}
                </span>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table marks-table align-middle">

                <thead>

                    <tr>

                        <th width="65">#</th>

                        <th>Subject</th>

                        <th class="text-center">
                            Maximum
                        </th>

                        <th class="text-center">
                            Obtained
                        </th>

                        <th class="text-center">
                            Percentage
                        </th>

                        <th class="text-center">
                            Grade
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($result->details as $index => $detail)

                        @php

                            $maximum = (float) (
                                $detail->max_marks
                                ?? $detail->maximum_marks
                                ?? $detail->total_marks
                                ?? 0
                            );

                            $obtained = (float) (
                                $detail->obtained_marks
                                ?? $detail->marks_obtained
                                ?? $detail->marks
                                ?? 0
                            );

                            $subjectPercentage = $maximum > 0
                                ? ($obtained / $maximum) * 100
                                : 0;

                            $detailGrade = $detail->grade ?? null;

                            $displayGrade = $detailGrade;

                            if (!$displayGrade) {
                                $displayGrade = match (true) {
                                    $subjectPercentage >= 90 => 'A+',
                                    $subjectPercentage >= 80 => 'A',
                                    $subjectPercentage >= 70 => 'B+',
                                    $subjectPercentage >= 60 => 'B',
                                    $subjectPercentage >= 50 => 'C',
                                    $subjectPercentage >= 40 => 'D',
                                    default => 'F',
                                };
                            }

                        @endphp

                        <tr>

                            <td>
                                <span class="subject-number">
                                    {{ $index + 1 }}
                                </span>
                            </td>

                            <td>

                                <div class="fw-bold">

                                    {{ $detail->subject_name
                                        ?? $detail->subject?->subject_name
                                        ?? $detail->subject?->name
                                        ?? 'N/A' }}

                                </div>

                            </td>

                            <td class="text-center">
                                {{ number_format($maximum, 2) }}
                            </td>

                            <td class="text-center fw-bold">
                                {{ number_format($obtained, 2) }}
                            </td>

                            <td class="text-center percentage-cell">

                                {{ number_format(
                                    $subjectPercentage,
                                    2
                                ) }}%

                            </td>

                            <td class="text-center">

                                <span class="grade-badge">
                                    {{ $displayGrade }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center py-5">

                                <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>

                                <div class="fw-semibold text-muted">
                                    No subject-wise result details found.
                                </div>

                                <small class="text-muted">
                                    Marks details are not available for this result.
                                </small>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
        RESULT SUMMARY
    ========================================================== --}}

    <div class="result-card">

        <div class="result-card-header">

            <h5 class="result-card-title">
                <i class="bi bi-bar-chart"></i>
                Result Summary
            </h5>

            <p class="result-card-subtitle">
                Overall academic performance for this examination.
            </p>

        </div>

        <div class="result-card-body">

            <div class="summary-grid">

                <div class="summary-item">

                    <div class="summary-label">
                        Total Marks
                    </div>

                    <div class="summary-value">
                        {{ number_format(
                            (float) $result->total_marks,
                            2
                        ) }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Obtained Marks
                    </div>

                    <div class="summary-value">
                        {{ number_format(
                            (float) $result->obtained_marks,
                            2
                        ) }}
                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Percentage
                    </div>

                    <div class="summary-value primary">
                        {{ number_format(
                            $percentage,
                            2
                        ) }}%
                    </div>

                    <div class="progress-wrap">

                        <div class="progress">

                            <div class="progress-bar"
                                 role="progressbar"
                                 style="width: {{ $percentage }}%;"
                                 aria-valuenow="{{ $percentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>

                        </div>

                    </div>

                </div>


                <div class="summary-item">

                    <div class="summary-label">
                        Overall Grade
                    </div>

                    <div class="summary-value">
                        {{ $result->grade ?? 'N/A' }}
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINAL RESULT STATUS
    ========================================================== --}}

    <div class="final-status-card mb-4">

        <div class="final-status-inner">

            <div>

                <div class="final-status-label">
                    Final Result Status
                </div>

                <div>

                    @if(in_array($resultStatus, ['pass', 'passed']))

                        <span class="final-status-badge final-pass">
                            <i class="bi bi-check-circle-fill"></i>
                            PASS
                        </span>

                    @elseif(in_array($resultStatus, ['fail', 'failed']))

                        <span class="final-status-badge final-fail">
                            <i class="bi bi-x-circle-fill"></i>
                            FAIL
                        </span>

                    @elseif($resultStatus === 'absent')

                        <span class="final-status-badge final-absent">
                            <i class="bi bi-dash-circle-fill"></i>
                            ABSENT
                        </span>

                    @else

                        <span class="final-status-badge final-other">
                            {{ strtoupper(
                                $result->result_status ?? 'N/A'
                            ) }}
                        </span>

                    @endif

                </div>

            </div>


            <div class="generated-box">

                <div class="generated-label">
                    Result Generated On
                </div>

                <div class="generated-value">

                    @if($result->generated_at)

                        {{ $result->generated_at->format(
                            'd M Y, h:i A'
                        ) }}

                    @else

                        {{ $result->created_at?->format(
                            'd M Y, h:i A'
                        ) ?? 'N/A' }}

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FOOTER ACTIONS
    ========================================================== --}}

    <div class="footer-actions">

        <a href="{{ route('admin.results.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Results

        </a>


        <a href="{{ route('admin.results.history', $result) }}"
           class="btn btn-outline-primary">

            <i class="bi bi-clock-history me-1"></i>
            View History

        </a>


        <a href="{{ route('admin.results.print', $result) }}"
           target="_blank"
           class="btn btn-outline-secondary">

            <i class="bi bi-printer me-1"></i>
            Print Result

        </a>


        <a href="{{ route('admin.results.pdf', $result) }}"
           target="_blank"
           class="btn btn-outline-danger">

            <i class="bi bi-file-earmark-pdf me-1"></i>
            Download PDF

        </a>

    </div>

</div>

@endsection
