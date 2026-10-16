@extends('layouts.app')

@section('title', 'Fee Structure Details')

@section('content')

<style>
    .fee-show-page {
        min-height: calc(100vh - 64px);
        background: #f4f7fb;
        padding: 24px;
    }

    /* Header */
    .fee-page-header {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #147cf5 0%, #6c63ff 100%);
        border-radius: 20px;
        padding: 26px 28px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px rgba(46, 91, 255, .16);
    }

    .fee-page-header::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -70px;
        top: -110px;
    }

    .fee-page-header-content {
        position: relative;
        z-index: 2;
    }

    .fee-page-header h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .fee-page-header p {
        margin: 6px 0 0;
        font-size: 14px;
        opacity: .9;
    }

    .header-actions {
        position: relative;
        z-index: 3;
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 15px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: .2s ease;
    }

    .header-btn-light {
        background: rgba(255,255,255,.95);
        color: #1769d1;
    }

    .header-btn-light:hover {
        background: #fff;
        color: #0d5dbd;
        transform: translateY(-1px);
    }

    .header-btn-outline {
        color: #fff;
        border: 1px solid rgba(255,255,255,.55);
        background: rgba(255,255,255,.08);
    }

    .header-btn-outline:hover {
        background: rgba(255,255,255,.16);
        color: #fff;
    }

    /* Summary cards */
    .summary-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 16px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(31,45,61,.05);
    }

    .summary-icon {
        width: 43px;
        height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eef5ff;
        color: #147cf5;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .summary-label {
        color: #7b8798;
        font-size: 12px;
        font-weight: 650;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .summary-value {
        color: #25364a;
        font-size: 18px;
        font-weight: 750;
        margin-top: 3px;
    }

    .summary-total .summary-icon {
        background: #f0edff;
        color: #6c63ff;
    }

    .summary-total .summary-value {
        color: #5c52db;
    }

    /* Main cards */
    .content-card {
        background: #fff;
        border: 1px solid #e7ecf3;
        border-radius: 18px;
        box-shadow: 0 5px 22px rgba(31,45,61,.055);
        overflow: hidden;
    }

    .content-card-header {
        padding: 18px 21px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #26364a;
        font-weight: 750;
        font-size: 16px;
    }

    .card-title i {
        color: #147cf5;
        font-size: 18px;
    }

    .content-card-body {
        padding: 21px;
    }

    /* Information */
    .info-item {
        border: 1px solid #edf1f6;
        background: #f9fbfe;
        border-radius: 12px;
        padding: 14px 15px;
        height: 100%;
    }

    .info-label {
        font-size: 11px;
        color: #8793a3;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 15px;
        color: #26364a;
        font-weight: 650;
        word-break: break-word;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 750;
    }

    .status-active {
        color: #198754;
        background: #eaf8f0;
    }

    .status-inactive {
        color: #dc3545;
        background: #fdeeee;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    /* Table */
    .table-wrapper {
        overflow-x: auto;
    }

    .fee-table {
        width: 100%;
        min-width: 760px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .fee-table thead th {
        background: #f7f9fc;
        color: #697689;
        border-bottom: 1px solid #e6ebf2;
        padding: 13px 15px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .35px;
        font-weight: 750;
        white-space: nowrap;
    }

    .fee-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #edf1f5;
        color: #344054;
        font-size: 14px;
        vertical-align: middle;
    }

    .fee-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .fee-table tbody tr:hover {
        background: #fafcff;
    }

    .item-number {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef5ff;
        color: #147cf5;
        font-size: 12px;
        font-weight: 750;
    }

    .fee-name {
        font-weight: 700;
        color: #26364a;
    }

    .category-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 7px;
        background: #f1f3f7;
        color: #687589;
        font-size: 11px;
        font-weight: 700;
    }

    .frequency {
        color: #59677a;
        font-weight: 600;
    }

    .due-date {
        color: #59677a;
        font-weight: 600;
    }

    .amount {
        color: #147cf5;
        font-weight: 750;
        white-space: nowrap;
    }

    .total-row td {
        background: #f8faff;
        border-top: 1px solid #e5ebf4;
        border-bottom: 0 !important;
        font-weight: 750;
    }

    .total-label {
        color: #445268;
        text-align: right;
    }

    .grand-total {
        color: #5c52db;
        font-size: 17px;
        white-space: nowrap;
    }

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #8a96a7;
    }

    .empty-state i {
        display: block;
        font-size: 35px;
        margin-bottom: 10px;
        color: #b8c3d2;
    }

    /* Footer */
    .page-footer {
        margin-top: 18px;
        color: #8a96a7;
        font-size: 12px;
        text-align: right;
    }

    /* Print */
    @media print {
        .fee-show-page {
            background: #fff;
            padding: 0;
        }

        .fee-page-header {
            color: #000;
            background: #fff;
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .fee-page-header::after,
        .header-actions,
        .page-footer {
            display: none !important;
        }

        .content-card,
        .summary-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .fee-show-page {
            padding: 14px;
        }

        .fee-page-header {
            padding: 20px;
        }

        .fee-page-header h2 {
            font-size: 21px;
        }

        .content-card-body {
            padding: 15px;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
        }
    }
</style>

@php
    $items = $feeStructure->items ?? collect();
    $totalAmount = $items->sum(function ($item) {
        return (float) ($item->amount ?? 0);
    });

    $className = optional($feeStructure->schoolClass)->class_name ?? '—';
    $sectionName = optional($feeStructure->section)->section_name ?? 'All Sections';
@endphp

<div class="fee-show-page">

    {{-- PAGE HEADER --}}
    <div class="fee-page-header">

        <div class="fee-page-header-content">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                <div>
                    <h2>
                        <i class="bi bi-receipt-cutoff me-2"></i>
                        Fee Structure Details
                    </h2>

                    <p>
                        View the complete fee structure and its fee items.
                    </p>
                </div>

                <div class="header-actions">

                    <a href="{{ route('admin.fees.fee-structures.index') }}"
                       class="header-btn header-btn-outline">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="header-btn header-btn-outline">
                        <i class="bi bi-printer"></i>
                        Print
                    </button>

                    <a href="{{ route('admin.fees.fee-structures.edit', $feeStructure->id) }}"
                       class="header-btn header-btn-light">
                        <i class="bi bi-pencil-square"></i>
                        Edit
                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-sm-6 col-xl-3">
            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="summary-label">
                    Academic Year
                </div>

                <div class="summary-value">
                    {{ $feeStructure->academic_year ?: '—' }}
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-mortarboard"></i>
                </div>

                <div class="summary-label">
                    Class
                </div>

                <div class="summary-value">
                    {{ $className }}
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="summary-card">

                <div class="summary-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div class="summary-label">
                    Fee Items
                </div>

                <div class="summary-value">
                    {{ $items->count() }}
                </div>

            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="summary-card summary-total">

                <div class="summary-icon">
                    <i class="bi bi-currency-rupee"></i>
                </div>

                <div class="summary-label">
                    Total Amount
                </div>

                <div class="summary-value">
                    ₹ {{ number_format($totalAmount, 2) }}
                </div>

            </div>
        </div>

    </div>

    {{-- BASIC INFORMATION --}}
    <div class="content-card mb-4">

        <div class="content-card-header">

            <div class="card-title">
                <i class="bi bi-info-circle"></i>
                Basic Information
            </div>

            @if($feeStructure->status === 'Active')

                <span class="status status-active">
                    <span class="status-dot"></span>
                    Active
                </span>

            @else

                <span class="status status-inactive">
                    <span class="status-dot"></span>
                    Inactive
                </span>

            @endif

        </div>

        <div class="content-card-body">

            <div class="row g-3">

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Structure Name
                        </div>

                        <div class="info-value">
                            {{ $feeStructure->structure_name ?: '—' }}
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Academic Year
                        </div>

                        <div class="info-value">
                            {{ $feeStructure->academic_year ?: '—' }}
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Class
                        </div>

                        <div class="info-value">
                            {{ $className }}
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Section
                        </div>

                        <div class="info-value">
                            {{ $sectionName }}
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Created On
                        </div>

                        <div class="info-value">
                            {{ $feeStructure->created_at
                                ? $feeStructure->created_at->format('d M Y, h:i A')
                                : '—' }}
                        </div>

                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="info-item">

                        <div class="info-label">
                            Last Updated
                        </div>

                        <div class="info-value">
                            {{ $feeStructure->updated_at
                                ? $feeStructure->updated_at->format('d M Y, h:i A')
                                : '—' }}
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- FEE ITEMS --}}
    <div class="content-card">

        <div class="content-card-header">

            <div class="card-title">
                <i class="bi bi-wallet2"></i>
                Fee Items
            </div>

            <span class="badge rounded-pill text-bg-primary">
                {{ $items->count() }}
                {{ $items->count() === 1 ? 'Item' : 'Items' }}
            </span>

        </div>

        <div class="content-card-body p-0">

            <div class="table-wrapper">

                <table class="fee-table">

                    <thead>
                        <tr>
                            <th width="70">#</th>
                            <th>Fee Type</th>
                            <th>Category</th>
                            <th>Frequency</th>
                            <th>Due Date</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($items as $index => $item)

                            @php
                                $feeType = $item->feeType;
                            @endphp

                            <tr>

                                <td>
                                    <span class="item-number">
                                        {{ $index + 1 }}
                                    </span>
                                </td>

                                <td>
                                    <span class="fee-name">
                                        {{ optional($feeType)->name ?? 'Fee Type Removed' }}
                                    </span>
                                </td>

                                <td>

                                    @if(optional($feeType)->category)
                                        <span class="category-badge">
                                            {{ $feeType->category }}
                                        </span>
                                    @else
                                        —
                                    @endif

                                </td>

                                <td>
                                    <span class="frequency">
                                        {{ optional($feeType)->frequency ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="due-date">

                                        @if($item->due_date)
                                            {{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}
                                        @else
                                            No due date
                                        @endif

                                    </span>
                                </td>

                                <td class="text-end">

                                    <span class="amount">
                                        ₹ {{ number_format((float) ($item->amount ?? 0), 2) }}
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">

                                    <div class="empty-state">

                                        <i class="bi bi-inbox"></i>

                                        <strong>
                                            No fee items found
                                        </strong>

                                        <div class="mt-1">
                                            This fee structure does not contain any fee items.
                                        </div>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                    @if($items->count() > 0)

                        <tfoot>

                            <tr class="total-row">

                                <td colspan="5" class="total-label">
                                    Grand Total
                                </td>

                                <td class="text-end">

                                    <span class="grand-total">
                                        ₹ {{ number_format($totalAmount, 2) }}
                                    </span>

                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

    <div class="page-footer">
        Fee Structure ID: #{{ $feeStructure->id }}
    </div>

</div>

@endsection