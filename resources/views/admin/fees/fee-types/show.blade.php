@extends('layouts.app')

@section('title', 'View Fee Type')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    .fee-show-page {
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
        padding: 28px;
    }

    .show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        flex-wrap: wrap;
    }

    .show-header h2 {
        color: #172b4d;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .show-header p {
        color: #7b8794;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .action-btn {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: .2s ease;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #dfe5ec;
        color: #344054;
    }

    .btn-back:hover {
        color: #1677f0;
        border-color: #cfe0ff;
        background: #f7faff;
    }

    .btn-edit {
        background: linear-gradient(135deg, #1677f0, #6c63ff);
        border: none;
        color: #fff;
    }

    .btn-edit:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(22, 119, 240, .18);
    }

    .info-card {
        background: #fff;
        border: none;
        border-radius: 18px;
        box-shadow: 0 6px 25px rgba(31, 45, 61, .07);
        overflow: hidden;
    }

    .info-card-header {
        padding: 22px 24px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 13px;
        background: #edf5ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .header-title {
        color: #172b4d;
        font-weight: 700;
        margin: 0;
    }

    .header-subtitle {
        color: #98a2b3;
        font-size: 13px;
    }

    .info-card-body {
        padding: 28px;
    }

    .detail-box {
        height: 100%;
        background: #f9fbfd;
        border: 1px solid #edf1f5;
        border-radius: 14px;
        padding: 18px;
        transition: .2s ease;
    }

    .detail-box:hover {
        border-color: #dbe8f8;
        background: #f7faff;
    }

    .detail-label {
        color: #98a2b3;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 7px;
    }

    .detail-value {
        color: #172b4d;
        font-size: 16px;
        font-weight: 600;
        word-break: break-word;
    }

    .detail-value i {
        color: #1677f0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-active {
        background: #e9f9f0;
        color: #16834b;
    }

    .status-inactive {
        background: #fff0f0;
        color: #d64545;
    }

    .description-box {
        background: #f7faff;
        border: 1px solid #e5efff;
        border-radius: 14px;
        padding: 20px;
    }

    .description-title {
        color: #344054;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .description-text {
        color: #667085;
        line-height: 1.7;
        margin: 0;
        white-space: pre-line;
    }

    .amount-box {
        background: linear-gradient(
            135deg,
            rgba(22, 119, 240, .08),
            rgba(108, 99, 255, .08)
        );
        border: 1px solid #dfe9ff;
        border-radius: 14px;
        padding: 20px;
    }

    .amount-label {
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .amount-text {
        color: #172b4d;
        font-size: 24px;
        font-weight: 800;
        margin-top: 4px;
    }

    .amount-note {
        color: #98a2b3;
        font-size: 12px;
        margin-top: 5px;
    }

    .timeline-box {
        background: #fff;
        border: 1px solid #edf1f5;
        border-radius: 14px;
        padding: 18px;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .timeline-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #edf5ff;
        color: #1677f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .timeline-label {
        color: #98a2b3;
        font-size: 12px;
    }

    .timeline-value {
        color: #344054;
        font-size: 14px;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .fee-show-page {
            padding: 18px;
        }

        .info-card-body {
            padding: 20px;
        }

        .header-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="fee-show-page">

    {{-- Page Header --}}
    <div class="show-header mb-4">

        <div>
            <h2>
                <i class="bi bi-eye me-2"></i>
                View Fee Type
            </h2>

            <p class="mb-0">
                View complete information about this fee type.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('admin.fees.fee-types.index') }}"
                class="action-btn btn-back"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

            <a
                href="{{ route('admin.fees.fee-types.edit', $feeType) }}"
                class="action-btn btn-edit"
            >
                <i class="bi bi-pencil-square"></i>
                Edit Fee Type
            </a>

        </div>

    </div>

    {{-- Main Information Card --}}
    <div class="info-card">

        {{-- Card Header --}}
        <div class="info-card-header">

            <div class="header-icon">
                <i class="bi bi-cash-stack"></i>
            </div>

            <div>

                <h5 class="header-title">
                    {{ $feeType->name }}
                </h5>

                <div class="header-subtitle">
                    Fee Type Details
                </div>

            </div>

        </div>

        {{-- Card Body --}}
        <div class="info-card-body">

            <div class="row g-4">

                {{-- Fee Type --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Fee Type
                        </div>

                        <div class="detail-value">
                            <i class="bi bi-cash-coin me-1"></i>
                            {{ $feeType->name }}
                        </div>

                    </div>

                </div>

                {{-- Category --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Category
                        </div>

                        <div class="detail-value">
                            <i class="bi bi-grid me-1"></i>
                            {{ $feeType->category ?: '—' }}
                        </div>

                    </div>

                </div>

                {{-- Frequency --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Frequency
                        </div>

                        <div class="detail-value">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $feeType->frequency ?: '—' }}
                        </div>

                    </div>

                </div>

                {{-- Status --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Status
                        </div>

                        <div class="detail-value">

                            @if($feeType->status === 'Active')

                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Active
                                </span>

                            @else

                                <span class="status-badge status-inactive">
                                    <i class="bi bi-x-circle-fill"></i>
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

                {{-- Created Date --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Created On
                        </div>

                        <div class="detail-value">
                            <i class="bi bi-calendar-plus me-1"></i>

                            {{ $feeType->created_at
                                ? $feeType->created_at->format('d M Y')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

                {{-- Updated Date --}}
                <div class="col-md-6 col-lg-4">

                    <div class="detail-box">

                        <div class="detail-label">
                            Last Updated
                        </div>

                        <div class="detail-value">
                            <i class="bi bi-clock-history me-1"></i>

                            {{ $feeType->updated_at
                                ? $feeType->updated_at->format('d M Y')
                                : '—'
                            }}

                        </div>

                    </div>

                </div>

                {{-- Amount --}}
                <div class="col-12">

                    <div class="amount-box">

                        <div class="amount-label">
                            Fee Amount
                        </div>

                        @if(isset($feeType->amount) && $feeType->amount !== null)

                            <div class="amount-text">
                                ₹{{ number_format((float) $feeType->amount, 2) }}
                            </div>

                            <div class="amount-note">
                                Base amount configured for this fee type.
                            </div>

                        @else

                            <div class="amount-text">
                                Set in Fee Structure
                            </div>

                            <div class="amount-note">
                                The actual amount is assigned according to
                                academic year, class and section.
                            </div>

                        @endif

                    </div>

                </div>

                {{-- Description --}}
                <div class="col-12">

                    <div class="description-box">

                        <div class="description-title">
                            <i class="bi bi-text-paragraph me-1"></i>
                            Description
                        </div>

                        <p class="description-text">
                            {{ $feeType->description ?: 'No description has been added for this fee type.' }}
                        </p>

                    </div>

                </div>

                {{-- Timeline --}}
                <div class="col-12">

                    <div class="timeline-box">

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="timeline-item">

                                    <div class="timeline-icon">
                                        <i class="bi bi-calendar-plus"></i>
                                    </div>

                                    <div>

                                        <div class="timeline-label">
                                            Created
                                        </div>

                                        <div class="timeline-value">

                                            {{ $feeType->created_at
                                                ? $feeType->created_at->format('d M Y, h:i A')
                                                : '—'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="timeline-item">

                                    <div class="timeline-icon">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>

                                    <div>

                                        <div class="timeline-label">
                                            Last Updated
                                        </div>

                                        <div class="timeline-value">

                                            {{ $feeType->updated_at
                                                ? $feeType->updated_at->format('d M Y, h:i A')
                                                : '—'
                                            }}

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection