```blade
@extends('layouts.app')

@section('title', 'View Student Supply Kit')

@section('content')

<div class="container-fluid py-4 supply-view-page">

    {{-- PAGE HEADER --}}
    <div class="supply-view-header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="supply-view-header-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <div class="supply-view-eyebrow">SUPPLY MANAGEMENT</div>
                    <h3 class="supply-view-title mb-1">Student Supply Kit</h3>
                    <p class="mb-0">
                        View student details, kit information and issued supplies.
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 supply-header-actions">
                <a href="{{ route('admin.student-supply-kits.index') }}"
                   class="btn supply-btn-outline">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>

                <a href="{{ route('admin.student-supply-kits.edit', $studentSupplyKit) }}"
                   class="btn supply-btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Kit
                </a>

                <a href="{{ route('admin.student-supply-kits.print', $studentSupplyKit) }}"
                   target="_blank"
                   rel="noopener"
                   class="btn supply-btn-print">
                    <i class="bi bi-printer me-1"></i> Print
                </a>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show supply-alert" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show supply-alert" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    @endif

    {{-- QUICK SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-summary-card">
                <div class="supply-summary-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="supply-summary-label">Student</div>
                    <div class="supply-summary-value supply-summary-name">
                        {{ trim(
                            ($studentSupplyKit->student->first_name ?? '') . ' ' .
                            ($studentSupplyKit->student->middle_name ?? '') . ' ' .
                            ($studentSupplyKit->student->last_name ?? '')
                        ) ?: '—' }}
                    </div>
                    <div class="supply-summary-caption">Kit recipient</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-summary-card">
                <div class="supply-summary-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="supply-summary-label">Kit Items</div>
                    <div class="supply-summary-value">
                        {{ $studentSupplyKit->items->count() }}
                    </div>
                    <div class="supply-summary-caption">Items assigned to this kit</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-summary-card">
                <div class="supply-summary-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="supply-summary-label">Academic Year</div>
                    <div class="supply-summary-value">
                        {{ $studentSupplyKit->academic_year ?: '—' }}
                    </div>
                    <div class="supply-summary-caption">Distribution session</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-summary-card">
                <div class="supply-summary-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="supply-summary-label">Distribution Status</div>
                    <div class="supply-summary-value supply-summary-status">
                        @if($studentSupplyKit->status === 'issued')
                            <span class="supply-status supply-status-issued">
                                <span class="supply-status-dot"></span> Issued
                            </span>
                        @elseif($studentSupplyKit->status === 'pending')
                            <span class="supply-status supply-status-pending">
                                <span class="supply-status-dot"></span> Pending
                            </span>
                        @else
                            <span class="supply-status supply-status-cancelled">
                                <span class="supply-status-dot"></span> Cancelled
                            </span>
                        @endif
                    </div>
                    <div class="supply-summary-caption">Current kit status</div>
                </div>
            </div>
        </div>

    </div>

    {{-- STUDENT INFORMATION --}}
    <div class="supply-view-panel mb-4">

        <div class="supply-view-panel-heading">
            <div class="d-flex align-items-center gap-3">
                <div class="supply-view-section-icon">
                    <i class="bi bi-person-vcard"></i>
                </div>
                <div>
                    <h5 class="mb-1">Student Information</h5>
                    <small>Personal and academic details of the kit recipient.</small>
                </div>
            </div>
            <span class="supply-section-number">01</span>
        </div>

        <div class="supply-view-panel-body">
            <div class="row g-4">

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-person me-1"></i> Student Name
                    </div>
                    <div class="supply-detail-value">
                        {{ trim(
                            ($studentSupplyKit->student->first_name ?? '') . ' ' .
                            ($studentSupplyKit->student->middle_name ?? '') . ' ' .
                            ($studentSupplyKit->student->last_name ?? '')
                        ) ?: '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-person-badge me-1"></i> Student ID
                    </div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->student->student_id ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-mortarboard me-1"></i> Class / Section
                    </div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->student->admission_class ?? '—' }}
                        @if($studentSupplyKit->student?->section)
                            <span class="supply-detail-separator">/</span>
                            {{ $studentSupplyKit->student->section }}
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-person me-1"></i> Father's Name
                    </div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->student->father_name ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-person me-1"></i> Mother's Name
                    </div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->student->mother_name ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">
                        <i class="bi bi-calendar-range me-1"></i> Academic Year
                    </div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->academic_year ?: '—' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- KIT INFORMATION --}}
    <div class="supply-view-panel mb-4">

        <div class="supply-view-panel-heading">
            <div class="d-flex align-items-center gap-3">
                <div class="supply-view-section-icon">
                    <i class="bi bi-box2-heart"></i>
                </div>
                <div>
                    <h5 class="mb-1">Kit Information</h5>
                    <small>Government scheme, kit details and distribution status.</small>
                </div>
            </div>
            <span class="supply-section-number">02</span>
        </div>

        <div class="supply-view-panel-body">
            <div class="row g-4">

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Kit Name</div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->kitTemplate->kit_name ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Government Scheme</div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->kitTemplate->scheme->scheme_name ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Kit Academic Year</div>
                    <div class="supply-detail-value">
                        {{ $studentSupplyKit->kitTemplate->academic_year ?? '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Distribution Date</div>
                    <div class="supply-detail-value">
                        <i class="bi bi-calendar-event supply-detail-green-icon me-1"></i>
                        {{ $studentSupplyKit->distribution_date
                            ? $studentSupplyKit->distribution_date->format('d-m-Y')
                            : '—' }}
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Status</div>
                    <div class="supply-detail-value">
                        @if($studentSupplyKit->status === 'issued')
                            <span class="supply-status supply-status-issued">
                                <span class="supply-status-dot"></span> Issued
                            </span>
                        @elseif($studentSupplyKit->status === 'pending')
                            <span class="supply-status supply-status-pending">
                                <span class="supply-status-dot"></span> Pending
                            </span>
                        @else
                            <span class="supply-status supply-status-cancelled">
                                <span class="supply-status-dot"></span> Cancelled
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="supply-detail-label">Total Kit Items</div>
                    <div class="supply-detail-value">
                        <span class="supply-count-badge">
                            <i class="bi bi-boxes me-1"></i>
                            {{ $studentSupplyKit->items->count() }}
                            {{ $studentSupplyKit->items->count() === 1 ? 'Item' : 'Items' }}
                        </span>
                    </div>
                </div>

                @if($studentSupplyKit->remarks)
                    <div class="col-12">
                        <div class="supply-remarks-box">
                            <div class="supply-detail-label">
                                <i class="bi bi-chat-left-text me-1"></i> Remarks
                            </div>
                            <div class="supply-detail-value supply-remarks-text">
                                {{ $studentSupplyKit->remarks }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- SUPPLY ITEMS --}}
    <div class="supply-view-panel mb-4">

        <div class="supply-view-panel-heading">
            <div class="d-flex align-items-center gap-3">
                <div class="supply-view-section-icon">
                    <i class="bi bi-boxes"></i>
                </div>
                <div>
                    <h5 class="mb-1">Supply Items</h5>
                    <small>Items assigned to this kit and their issued quantities.</small>
                </div>
            </div>

            <span class="supply-count-badge">
                <i class="bi bi-collection me-1"></i>
                {{ $studentSupplyKit->items->count() }}
                {{ $studentSupplyKit->items->count() === 1 ? 'Item' : 'Items' }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table supply-items-table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Supply Item</th>
                        <th>Item Code</th>
                        <th>Unit</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Issued</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($studentSupplyKit->items as $item)
                        <tr>
                            <td class="ps-4 supply-row-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="supply-item-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="supply-item-name">
                                        {{ $item->supplyItem->item_name ?? '—' }}
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($item->supplyItem->item_code ?? false)
                                    <span class="supply-code-badge">
                                        {{ $item->supplyItem->item_code }}
                                    </span>
                                @else
                                    <span class="supply-muted">—</span>
                                @endif
                            </td>

                            <td>
                                {{ $item->supplyItem->unit ?? '—' }}
                            </td>

                            <td class="text-center">
                                <span class="supply-quantity-badge">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($item->issued_quantity > 0)
                                    <span class="supply-issued-badge">
                                        <i class="bi bi-check2 me-1"></i>
                                        {{ $item->issued_quantity }}
                                    </span>
                                @else
                                    <span class="supply-not-issued">0</span>
                                @endif
                            </td>

                            <td>
                                <span class="supply-item-remarks">
                                    {{ $item->remarks ?: '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="supply-empty-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="supply-empty-title">No Supply Items Found</div>
                                <div class="supply-empty-text">
                                    There are no supply items assigned to this kit.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- BOTTOM ACTIONS --}}
    <div class="supply-bottom-actions mb-4">
        <a href="{{ route('admin.student-supply-kits.index') }}"
           class="btn supply-btn-outline">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.student-supply-kits.edit', $studentSupplyKit) }}"
               class="btn supply-btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit Kit
            </a>

            <a href="{{ route('admin.student-supply-kits.print', $studentSupplyKit) }}"
               target="_blank"
               rel="noopener"
               class="btn supply-btn-print">
                <i class="bi bi-printer me-1"></i> Print Kit
            </a>
        </div>
    </div>

</div>

<style>
    /* =====================================================
       STUDENT SUPPLY KIT VIEW
       Styled to match Government Kit Templates UI
    ===================================================== */

    .supply-view-page {
        --supply-green: #176238;
        --supply-green-dark: #0b3b23;
        --supply-green-light: #e8f5eb;
        --supply-border: #dce8dd;
        --supply-text: #193e28;
        --supply-muted: #718778;

        padding: 28px !important;
        color: var(--supply-text);
        background-color: #faf8f1;
        background-image: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent 59px,
            rgba(22, 83, 48, .035) 60px
        );
    }

    /* HEADER */
    .supply-view-header {
        padding: 25px 26px;
        color: #fff;
        background: linear-gradient(110deg, #0b3b23 0%, #145a34 60%, #1d6840 100%);
        border: 1px solid #1c6038;
        border-radius: 17px;
        box-shadow: 0 8px 22px rgba(15, 65, 36, .13);
    }

    .supply-view-header-icon {
        width: 56px;
        height: 56px;
        flex: 0 0 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        background: rgba(255, 255, 255, .12);
        border: 1px solid rgba(255, 255, 255, .24);
        border-radius: 15px;
        font-size: 26px;
    }

    .supply-view-eyebrow {
        margin-bottom: 4px;
        color: #9cf2c0;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.4px;
    }

    .supply-view-title {
        color: #fff;
        font-size: clamp(1.3rem, 2vw, 1.8rem);
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .supply-view-header p {
        color: #d7f0df;
        font-size: 13px;
    }

    /* BUTTONS */
    .supply-view-page .btn {
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 15px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        transition: background .2s ease, transform .2s ease;
    }

    .supply-btn-outline {
        color: #fff;
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .4);
    }

    .supply-btn-outline:hover {
        color: #104426;
        background: #fff;
        border-color: #fff;
    }

    .supply-btn-primary {
        color: #14532d;
        background: #fff;
        border: 1px solid #fff;
    }

    .supply-btn-primary:hover {
        color: #0b3b23;
        background: #e8f7ec;
        border-color: #e8f7ec;
        transform: translateY(-1px);
    }

    .supply-btn-print {
        color: #fff;
        background: rgba(255, 255, 255, .09);
        border: 1px solid rgba(255, 255, 255, .4);
    }

    .supply-btn-print:hover {
        color: #104426;
        background: #fff;
        border-color: #fff;
    }

    .supply-alert {
        border-radius: 11px;
        box-shadow: 0 3px 12px rgba(20, 60, 35, .05);
    }

    /* SUMMARY CARDS */
    .supply-summary-card {
        display: flex;
        align-items: center;
        gap: 14px;
        height: 100%;
        min-height: 112px;
        padding: 19px;
        background: #fff;
        border: 1px solid #dce8dd;
        border-left: 3px solid #2b8a50;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(24, 62, 37, .045);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .supply-summary-card:hover {
        transform: translateY(-2px);
        border-color: #a9d3b4;
        box-shadow: 0 7px 20px rgba(24, 62, 37, .09);
    }

    .supply-summary-icon {
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #217448;
        background: #e8f5eb;
        border: 1px solid #d6e9da;
        border-radius: 12px;
        font-size: 21px;
    }

    .supply-summary-label {
        color: #6b8171;
        font-size: 11px;
        font-weight: 700;
    }

    .supply-summary-value {
        margin-top: 5px;
        color: #164b2b;
        font-size: 24px;
        line-height: 1.3;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .supply-summary-name {
        font-size: 14px;
    }

    .supply-summary-caption {
        margin-top: 4px;
        color: #829487;
        font-size: 10px;
    }

    .supply-summary-status {
        font-size: 13px;
    }

    /* SECTION PANELS */
    .supply-view-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #dce8dd;
        border-radius: 15px;
        box-shadow: 0 3px 13px rgba(24, 62, 37, .045);
    }

    .supply-view-panel-heading {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 17px 22px;
        background: #fbfdfb;
        border-bottom: 1px solid #e2ece4;
    }

    .supply-view-panel-heading h5 {
        color: #193e28;
        font-size: 14px;
        font-weight: 800;
    }

    .supply-view-panel-heading small {
        color: #7b8e80;
        font-size: 11px;
    }

    .supply-view-section-icon {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #217448;
        background: #e7f4e9;
        border: 1px solid #dcecdf;
        border-radius: 11px;
        font-size: 18px;
    }

    .supply-section-number {
        color: #91a89a;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .supply-view-panel-body {
        padding: 25px 24px;
    }

    /* DETAIL FIELDS */
    .supply-detail-label {
        margin-bottom: 7px;
        color: #728778;
        font-size: 11px;
        font-weight: 700;
    }

    .supply-detail-label i,
    .supply-detail-green-icon {
        color: #398253;
    }

    .supply-detail-value {
        color: #244b32;
        font-size: 13px;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .supply-detail-separator {
        margin: 0 4px;
        color: #91a497;
    }

    .supply-remarks-box {
        padding: 15px 17px;
        background: #f6faf6;
        border: 1px solid #e0ece2;
        border-radius: 11px;
    }

    .supply-remarks-text {
        line-height: 1.7;
        white-space: pre-line;
    }

    /* STATUS BADGES */
    .supply-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .supply-status-dot {
        width: 7px;
        height: 7px;
        flex: 0 0 7px;
        border-radius: 50%;
    }

    .supply-status-issued {
        color: #176238;
        background: #e4f4e8;
    }

    .supply-status-issued .supply-status-dot {
        background: #16a34a;
    }

    .supply-status-pending {
        color: #8a6415;
        background: #fff5d9;
    }

    .supply-status-pending .supply-status-dot {
        background: #e5a313;
    }

    .supply-status-cancelled {
        color: #a42b32;
        background: #fdebec;
    }

    .supply-status-cancelled .supply-status-dot {
        background: #dc3545;
    }

    .supply-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 7px 10px;
        color: #176238;
        background: #e8f5eb;
        border: 1px solid #d1e8d7;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* ITEMS TABLE */
    .supply-items-table {
        --bs-table-bg: #fff;
        --bs-table-color: #40594a;
        --bs-table-hover-bg: #f5faf5;
        --bs-table-hover-color: #183d27;
    }

    .supply-items-table thead th {
        padding: 14px 12px;
        color: #42634d;
        background: #f0f7f1;
        border-bottom: 1px solid #dce8dd;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .supply-items-table tbody td {
        padding: 14px 12px;
        color: #455b4b;
        border-bottom: 1px solid #edf2ed;
        font-size: 12px;
        vertical-align: middle;
    }

    .supply-items-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .supply-items-table tbody tr:hover > * {
        background-color: #f5faf5;
    }

    .supply-row-number {
        color: #84978a !important;
        font-size: 11px !important;
    }

    .supply-item-icon {
        width: 37px;
        height: 37px;
        flex: 0 0 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #217448;
        background: #e9f5eb;
        border: 1px solid #d6e9da;
        border-radius: 10px;
        font-size: 16px;
    }

    .supply-item-name {
        color: #1c5531;
        font-size: 12px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .supply-code-badge {
        display: inline-block;
        padding: 5px 8px;
        color: #496b54;
        background: #f4f8f4;
        border: 1px solid #e0e9e1;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
    }

    .supply-quantity-badge {
        display: inline-block;
        min-width: 34px;
        padding: 6px 9px;
        color: #176238;
        background: #e8f5eb;
        border: 1px solid #d1e8d7;
        border-radius: 7px;
        font-weight: 800;
    }

    .supply-issued-badge {
        display: inline-block;
        padding: 6px 9px;
        color: #176238;
        background: #e4f4e8;
        border: 1px solid #cfe8d5;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .supply-not-issued,
    .supply-muted {
        color: #98a59b;
    }

    .supply-item-remarks {
        color: #687e6d;
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    /* EMPTY STATE */
    .supply-empty-icon {
        width: 55px;
        height: 55px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: #217448;
        background: #e8f5eb;
        border: 1px solid #d6e9da;
        border-radius: 16px;
        font-size: 25px;
    }

    .supply-empty-title {
        margin-bottom: 5px;
        color: #164b2b;
        font-size: 14px;
        font-weight: 800;
    }

    .supply-empty-text {
        color: #819184;
        font-size: 12px;
    }

    /* BOTTOM ACTIONS */
    .supply-bottom-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 17px 20px;
        background: #fff;
        border: 1px solid #dce8dd;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(24, 62, 37, .04);
    }

    .supply-bottom-actions .supply-btn-outline {
        color: #176238;
        background: #fff;
        border-color: #d0e1d4;
    }

    .supply-bottom-actions .supply-btn-outline:hover {
        color: #104426;
        background: #eaf5ec;
        border-color: #b7d7bf;
    }

    .supply-bottom-actions .supply-btn-primary {
        color: #fff;
        background: #176238;
        border-color: #176238;
    }

    .supply-bottom-actions .supply-btn-primary:hover {
        background: #0e4829;
        border-color: #0e4829;
    }

    .supply-bottom-actions .supply-btn-print {
        color: #176238;
        background: #fff;
        border-color: #d0e1d4;
    }

    .supply-bottom-actions .supply-btn-print:hover {
        background: #eaf5ec;
        border-color: #b7d7bf;
    }

    /* RESPONSIVE */
    @media (max-width: 767.98px) {
        .supply-view-page {
            padding: 14px !important;
        }

        .supply-view-header {
            padding: 20px 16px;
            border-radius: 14px;
        }

        .supply-view-header-icon {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            font-size: 22px;
        }

        .supply-header-actions {
            width: 100%;
        }

        .supply-header-actions .btn {
            flex: 1 1 auto;
        }

        .supply-view-panel-heading {
            padding: 15px;
        }

        .supply-view-panel-body {
            padding: 20px 16px;
        }

        .supply-summary-card {
            min-height: 100px;
            padding: 15px;
        }

        .supply-summary-value {
            font-size: 21px;
        }

        .supply-items-table thead th,
        .supply-items-table tbody td {
            padding: 12px 10px;
        }

        .supply-bottom-actions {
            padding: 14px;
        }

        .supply-bottom-actions > a,
        .supply-bottom-actions > div {
            width: 100%;
        }

        .supply-bottom-actions > a,
        .supply-bottom-actions > div .btn {
            flex: 1 1 auto;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .supply-summary-card,
        .supply-view-page .btn {
            transition: none;
        }
    }
</style>

@endsection
```