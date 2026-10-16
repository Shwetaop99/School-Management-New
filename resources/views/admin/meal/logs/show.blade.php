
@extends('layouts.app')

@section('title', 'Stock Log Details | Admin')
@section('page-title', 'Stock Log Details')

@section('content')

<style>
/* =========================================================
   STOCK LOG DETAILS — LOCKED SCHOOL DIARY DESIGN SYSTEM
========================================================= */
.meal-log-details-page {
    --paper: #fffefa;
    --ink: #263b35;
    --muted: #849087;
    --wood: #a87543;
    --green: #34775a;
    --green-dark: #245a43;
    --green-light: #eaf3e9;
    --red: #bd5148;
    --red-light: #fff0ec;
    --cyan: #527f79;
    --border: #e5e1d3;

    min-height: calc(100vh - 70px);
    padding: 24px;
    background: #f5f3e9;
    color: var(--ink);
    font-family: inherit;
    font-size: 12px;
}

.meal-log-details-page *,
.meal-log-details-page *::before,
.meal-log-details-page *::after {
    box-sizing: border-box;
}

.meal-log-details-page .container-fluid {
    max-width: 1500px;
}

/* PAGE HEADER */
.meal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
    padding: 22px 24px;
    border: 1px solid var(--border);
    border-left: 4px solid var(--green);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.04);
}

.meal-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 0;
}

.meal-header-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    border: 1px solid #dce8d9;
    border-radius: 12px;
    background: var(--green-light);
    color: var(--green);
    font-size: 19px;
}

.meal-header-content {
    min-width: 0;
}

.meal-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 6px;
    color: #879084;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .7px;
    text-transform: uppercase;
}

.meal-breadcrumb i {
    color: #b1b8a9;
    font-size: 8px;
}

.meal-page-title {
    margin: 0;
    color: var(--ink);
    font-size: 22px;
    line-height: 1.4;
    font-weight: 800;
}

.meal-page-subtitle {
    margin: 5px 0 0;
    color: #7b857a;
    font-size: 11px;
    line-height: 1.7;
}

/* BACK BUTTON */
.meal-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-shrink: 0;
    padding: 9px 13px 9px 9px;
    border: 1px solid #dcd9ca;
    border-radius: 8px;
    background: var(--paper);
    color: #52665c !important;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    transition: .2s ease;
}

.meal-back-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 7px;
    background: var(--green-light);
    color: var(--green);
}

.meal-back-btn:hover {
    border-color: #a8c0a5;
    background: #eef5e9;
    color: var(--green-dark) !important;
}

.meal-back-btn:hover .meal-back-icon {
    background: var(--green);
    color: #fff;
}

/* MAIN DETAILS CARD */
.meal-details-card {
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54,64,43,.035);
}

/* DETAILS CARD HEADER */
.meal-details-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 18px 20px;
    border-bottom: 1px solid #ece8dc;
    background: #faf8f0;
}

.meal-details-title {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-details-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 15px;
}

.meal-details-title h5 {
    margin: 0;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.meal-details-title > div:last-child > span {
    display: block;
    margin-top: 4px;
    color: #879084;
    font-size: 10px;
}

/* TRANSACTION BADGES */
.details-action-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.details-action-badge.stock-in {
    border-color: #cfe4d1;
    background: var(--green-light);
    color: var(--green);
}

.details-action-badge.stock-out {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

.details-action-badge.neutral {
    border-color: #e5e1d3;
    background: #f5f3e9;
    color: #687466;
}

/* CARD BODY */
.meal-details-body {
    padding: 20px;
}

/* NUMBERED SECTION CARDS */
.details-section {
    overflow: hidden;
    margin-bottom: 19px;
    border: 1px solid var(--border);
    border-radius: 11px;
    background: var(--paper);
}

.details-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 15px 18px;
    border-bottom: 1px solid #ece8dc;
    background: #faf8f0;
}

.details-section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.details-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 14px;
}

.details-section-icon.cyan {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: var(--cyan);
}

.details-section-icon.orange {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.details-section-icon.red {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

.details-section-heading h5 {
    margin: 0;
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.details-section-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

.details-section-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 25px;
    padding: 0 7px;
    border: 1px solid #e6e2d5;
    border-radius: 7px;
    background: #fffefa;
    color: #92998b;
    font-size: 10px;
    font-weight: 800;
}

.details-section-body {
    padding: 20px;
}

/* MEAL ITEM */
.details-item-box {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px;
    border: 1px solid #dce8d9;
    border-radius: 9px;
    background: #f8fbf5;
}

.details-item-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    border: 1px solid #dce8d9;
    border-radius: 10px;
    background: var(--green-light);
    color: var(--green);
    font-size: 17px;
}

.details-item-content {
    min-width: 0;
}

.details-item-content h5 {
    margin: 0 0 5px;
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.item-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    color: #7b857a;
    font-size: 10px;
}

.item-meta-separator {
    color: #b5b8aa;
}

.deleted-item-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    border: 1px solid #efd0c8;
    border-radius: 9px;
    background: var(--red-light);
}

.deleted-item-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    flex: 0 0 42px;
    border-radius: 9px;
    background: #ffe4de;
    color: var(--red);
}

.deleted-item-box strong {
    display: block;
    margin-bottom: 4px;
    color: #8f3e37;
    font-size: 11px;
    font-weight: 800;
}

.deleted-item-box span {
    color: #a56e65;
    font-size: 10px;
    line-height: 1.6;
}

/* STOCK SUMMARY */
.detail-info-box {
    height: 100%;
    min-height: 95px;
    padding: 15px;
    border: 1px solid #e5e1d3;
    border-radius: 9px;
    background: #fffefa;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.detail-info-box:hover {
    border-color: #d7d3c2;
    box-shadow: 0 3px 10px rgba(38,59,53,.035);
}

.detail-info-box.updated-box {
    border-color: #cfe4d1;
    background: #f4f9f1;
}

.detail-info-box.amount-highlight-box {
    border-color: #d7e6d4;
    background: #f8fbf5;
}

.detail-label {
    display: block;
    margin-bottom: 9px;
    color: #7b857a;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.6;
}

.detail-value {
    color: var(--ink);
    font-size: 18px;
    font-weight: 800;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.detail-value small {
    color: #879084;
    font-size: 10px;
    font-weight: 600;
}

.quantity-value {
    color: var(--green);
}

.movement-value {
    color: var(--wood);
}

.updated-value {
    color: var(--green-dark);
}

.amount-value {
    color: var(--wood);
}

.total-amount-value {
    color: var(--green);
}

/* DETAIL ROWS */
.detail-row {
    display: flex;
    align-items: center;
    gap: 11px;
    height: 100%;
    min-height: 72px;
    padding: 12px;
    border: 1px solid #e5e1d3;
    border-radius: 9px;
    background: #fffefa;
    transition: border-color .2s ease, background .2s ease;
}

.detail-row:hover {
    border-color: #d7d3c2;
    background: #faf8f0;
}

.detail-row-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #dce8d9;
    border-radius: 8px;
    background: var(--green-light);
    color: var(--green);
    font-size: 12px;
}

.detail-row-icon.cyan {
    border-color: #d7e6e4;
    background: #edf5f1;
    color: var(--cyan);
}

.detail-row-icon.orange {
    border-color: #edddc5;
    background: #fbf0df;
    color: var(--wood);
}

.detail-row-icon.red {
    border-color: #efd0c8;
    background: var(--red-light);
    color: var(--red);
}

.detail-row-content {
    min-width: 0;
    flex: 1;
}

.detail-row-label {
    display: block;
    margin-bottom: 5px;
    color: #879084;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .35px;
    line-height: 1.5;
    text-transform: uppercase;
}

.detail-row strong {
    display: block;
    color: var(--ink);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.7;
    overflow-wrap: anywhere;
    word-break: break-word;
}

/* REMARKS */
.details-remarks {
    min-height: 85px;
    padding: 15px;
    border: 1px solid #e5e1d3;
    border-radius: 9px;
    background: #fffefa;
    color: #52665c;
    font-size: 12px;
    line-height: 1.8;
    overflow-wrap: anywhere;
    white-space: pre-line;
}

.details-remarks .empty-remarks {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #92998b;
    font-size: 11px;
}

.empty-remarks i {
    margin-top: 4px;
    color: var(--wood);
}

/* FOOTER ACTION BAR */
.details-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 19px;
    padding: 15px 17px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(54,64,43,.035);
}

.details-footer-note {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.details-footer-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    border: 1px solid #dce8d9;
    border-radius: 9px;
    background: var(--green-light);
    color: var(--green);
}

.details-footer-note strong {
    display: block;
    color: #35483b;
    font-size: 11px;
    font-weight: 800;
}

.details-footer-note span {
    display: block;
    margin-top: 4px;
    color: #879084;
    font-size: 9px;
    line-height: 1.6;
}

.details-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 15px;
    border: 1px solid #dcd9ca;
    border-radius: 8px;
    background: #fffefa;
    color: #687466 !important;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    transition: .2s ease;
}

.details-back-btn:hover {
    border-color: #a8c0a5;
    background: #eef5e9;
    color: var(--green-dark) !important;
}

/* RESPONSIVE */
@media (max-width: 991.98px) {
    .meal-log-details-page {
        padding: 18px;
    }

    .meal-page-header {
        align-items: flex-start;
    }

    .details-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .details-back-btn {
        align-self: flex-end;
    }
}

@media (max-width: 767.98px) {
    .meal-log-details-page {
        padding: 14px;
    }

    .meal-page-header {
        align-items: stretch;
        flex-direction: column;
        gap: 14px;
        padding: 17px;
    }

    .meal-header-left {
        align-items: flex-start;
        gap: 11px;
    }

    .meal-header-icon {
        width: 43px;
        height: 43px;
        flex-basis: 43px;
        border-radius: 10px;
        font-size: 16px;
    }

    .meal-page-title {
        font-size: 19px;
    }

    .meal-page-subtitle {
        font-size: 10px;
    }

    .meal-back-btn {
        width: 100%;
    }

    .meal-details-header {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 15px;
    }

    .meal-details-body {
        padding: 14px;
    }

    .details-section-header {
        padding: 14px 15px;
    }

    .details-section-icon {
        width: 35px;
        height: 35px;
        flex-basis: 35px;
        border-radius: 9px;
        font-size: 12px;
    }

    .details-section-heading h5 {
        font-size: 12px;
    }

    .details-section-heading p {
        font-size: 9px;
    }

    .details-section-number {
        display: none;
    }

    .details-section-body {
        padding: 15px;
    }

    .details-footer {
        padding: 14px;
    }

    .details-back-btn {
        align-self: stretch;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .meal-log-details-page {
        padding: 10px;
    }

    .meal-page-header {
        padding: 15px;
    }

    .meal-page-title {
        font-size: 17px;
    }

    .meal-breadcrumb {
        gap: 6px;
        font-size: 8px;
    }

    .meal-details-header {
        flex-direction: column;
        gap: 12px;
    }

    .meal-details-body {
        padding: 10px;
    }

    .details-section {
        margin-bottom: 14px;
    }

    .details-section-header {
        padding: 12px;
    }

    .details-section-body {
        padding: 12px;
    }

    .details-item-box,
    .deleted-item-box {
        padding: 12px;
    }

    .detail-info-box {
        min-height: 82px;
        padding: 12px;
    }

    .detail-value {
        font-size: 16px;
    }

    .detail-row {
        min-height: 65px;
        padding: 10px;
    }

    .details-remarks {
        padding: 12px;
    }

    .details-footer-note {
        align-items: flex-start;
    }
}

@media (prefers-reduced-motion: reduce) {
    .meal-log-details-page *,
    .meal-log-details-page *::before,
    .meal-log-details-page *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
</style>

<div class="meal-log-details-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header">

            <div class="meal-header-left">
                <div class="meal-header-icon">
                    <i class="fas fa-history"></i>
                </div>

                <div class="meal-header-content">
                    <div class="meal-breadcrumb">
                        <span>Meal Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Stock Logs</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>Log Details</span>
                    </div>

                    <h2 class="meal-page-title">Stock Log Details</h2>

                    <p class="meal-page-subtitle">
                        View complete information about this stock movement.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.admin.meal.logs.index') }}"
               class="meal-back-btn">
                <span class="meal-back-icon">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span>Back to Logs</span>
            </a>

        </div>

        {{-- MAIN DETAILS CARD --}}
        <div class="meal-details-card">

            {{-- CARD HEADER --}}
            <div class="meal-details-header">

                <div class="meal-details-title">
                    <div class="meal-details-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>

                    <div>
                        <h5>Stock Movement Information</h5>
                        <span>Log #{{ $mealStockLog->id }}</span>
                    </div>
                </div>

                {{-- TRANSACTION TYPE --}}
                @if($mealStockLog->action === 'stock_in')
                    <span class="details-action-badge stock-in">
                        <i class="fas fa-arrow-down"></i>
                        Stock In
                    </span>
                @elseif($mealStockLog->action === 'stock_out')
                    <span class="details-action-badge stock-out">
                        <i class="fas fa-arrow-up"></i>
                        Stock Out
                    </span>
                @else
                    <span class="details-action-badge neutral">
                        {{ ucfirst(str_replace('_', ' ', $mealStockLog->action ?? 'Unknown')) }}
                    </span>
                @endif

            </div>

            <div class="meal-details-body">

                {{-- =================================================
                     SECTION 01 — MEAL ITEM
                ================================================== --}}
                <div class="details-section">

                    <div class="details-section-header">
                        <div class="details-section-heading">
                            <div class="details-section-icon">
                                <i class="fas fa-utensils"></i>
                            </div>

                            <div>
                                <h5>Meal Item</h5>
                                <p>Item associated with this stock movement.</p>
                            </div>
                        </div>

                        <span class="details-section-number">01</span>
                    </div>

                    <div class="details-section-body">

                        @if($mealStockLog->mealItem)

                            <div class="details-item-box">

                                <div class="details-item-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>

                                <div class="details-item-content">
                                    <h5>
                                        {{ $mealStockLog->mealItem->item_name }}
                                    </h5>

                                    <div class="item-meta">
                                        <span>
                                            {{ $mealStockLog->mealItem->category ?: 'Other' }}
                                        </span>

                                        <span class="item-meta-separator">•</span>

                                        <span>
                                            Unit: {{ $mealStockLog->unit }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                        @else

                            <div class="deleted-item-box">
                                <div class="deleted-item-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>

                                <div>
                                    <strong>Item Deleted</strong>
                                    <span>
                                        This meal item no longer exists.
                                    </span>
                                </div>
                            </div>

                        @endif

                    </div>
                </div>

                {{-- =================================================
                     SECTION 02 — STOCK INFORMATION
                ================================================== --}}
                <div class="details-section">

                    <div class="details-section-header">
                        <div class="details-section-heading">
                            <div class="details-section-icon cyan">
                                <i class="fas fa-boxes"></i>
                            </div>

                            <div>
                                <h5>Stock Information</h5>
                                <p>Review stock levels before and after the movement.</p>
                            </div>
                        </div>

                        <span class="details-section-number">02</span>
                    </div>

                    <div class="details-section-body">
                        <div class="row g-3">

                            {{-- MOVEMENT QUANTITY --}}
                            <div class="col-xl-3 col-md-6">
                                <div class="detail-info-box">
                                    <span class="detail-label">
                                        Movement Quantity
                                    </span>

                                    <div class="detail-value quantity-value">
                                        {{ number_format((float) $mealStockLog->quantity, 2) }}
                                        <small>{{ $mealStockLog->unit }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- PREVIOUS STOCK --}}
                            <div class="col-xl-3 col-md-6">
                                <div class="detail-info-box">
                                    <span class="detail-label">
                                        Previous Stock
                                    </span>

                                    <div class="detail-value">
                                        {{ number_format((float) $mealStockLog->previous_stock, 2) }}
                                        <small>{{ $mealStockLog->unit }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- STOCK CHANGE --}}
                            <div class="col-xl-3 col-md-6">
                                <div class="detail-info-box">
                                    <span class="detail-label">
                                        @if($mealStockLog->action === 'stock_in')
                                            Quantity Added
                                        @elseif($mealStockLog->action === 'stock_out')
                                            Quantity Issued
                                        @else
                                            Quantity Changed
                                        @endif
                                    </span>

                                    <div class="detail-value movement-value">
                                        @if($mealStockLog->action === 'stock_in')
                                            +
                                        @elseif($mealStockLog->action === 'stock_out')
                                            -
                                        @endif

                                        {{ number_format((float) $mealStockLog->quantity, 2) }}
                                        <small>{{ $mealStockLog->unit }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- UPDATED STOCK --}}
                            <div class="col-xl-3 col-md-6">
                                <div class="detail-info-box updated-box">
                                    <span class="detail-label">
                                        Updated Stock
                                    </span>

                                    <div class="detail-value updated-value">
                                        {{ number_format((float) $mealStockLog->updated_stock, 2) }}
                                        <small>{{ $mealStockLog->unit }}</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- =================================================
                     SECTION 03 — MOVEMENT DETAILS
                ================================================== --}}
                <div class="details-section">

                    <div class="details-section-header">
                        <div class="details-section-heading">
                            <div class="details-section-icon orange">
                                <i class="fas fa-info-circle"></i>
                            </div>

                            <div>
                                <h5>Movement Details</h5>
                                <p>Transaction, timing and audit information.</p>
                            </div>
                        </div>

                        <span class="details-section-number">03</span>
                    </div>

                    <div class="details-section-body">
                        <div class="row g-3">

                            {{-- TRANSACTION ID --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">
                                            Stock Transaction ID
                                        </span>

                                        <strong>
                                            @if($mealStockLog->stock_transaction_id)
                                                #TRX-{{ str_pad($mealStockLog->stock_transaction_id, 5, '0', STR_PAD_LEFT) }}
                                            @else
                                                Not available
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- TRANSACTION DATE --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon cyan">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">
                                            Transaction Date
                                        </span>

                                        <strong>
                                            @if(
                                                $mealStockLog->stockTransaction &&
                                                $mealStockLog->stockTransaction->transaction_date
                                            )
                                                {{ $mealStockLog->stockTransaction->transaction_date->format('d M Y') }}
                                            @else
                                                Not specified
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- LOG CREATED --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon orange">
                                        <i class="fas fa-clock"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">
                                            Log Created
                                        </span>

                                        <strong>
                                            @if($mealStockLog->created_at)
                                                {{ $mealStockLog->created_at->format('d M Y, h:i A') }}
                                            @else
                                                Not available
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- REASON --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon">
                                        <i class="fas fa-comment-alt"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">Reason</span>

                                        <strong>
                                            {{ $mealStockLog->reason ?: 'Not specified' }}
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- SUPPLIER --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon cyan">
                                        <i class="fas fa-truck"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">Supplier</span>

                                        <strong>
                                            @if(
                                                $mealStockLog->stockTransaction &&
                                                $mealStockLog->stockTransaction->supplier
                                            )
                                                {{ $mealStockLog->stockTransaction->supplier }}
                                            @else
                                                Not specified
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                            {{-- PERFORMED BY --}}
                            <div class="col-xl-4 col-md-6">
                                <div class="detail-row">
                                    <div class="detail-row-icon orange">
                                        <i class="fas fa-user-shield"></i>
                                    </div>

                                    <div class="detail-row-content">
                                        <span class="detail-row-label">
                                            Performed By
                                        </span>

                                        <strong>
                                            @if($mealStockLog->performed_by)
                                                Admin #{{ $mealStockLog->performed_by }}
                                            @else
                                                System
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- =================================================
                     SECTION 04 — TRANSACTION AMOUNT
                ================================================== --}}
                @if(
                    $mealStockLog->stockTransaction &&
                    (
                        $mealStockLog->stockTransaction->rate !== null ||
                        $mealStockLog->stockTransaction->total_amount !== null
                    )
                )

                    <div class="details-section">

                        <div class="details-section-header">
                            <div class="details-section-heading">
                                <div class="details-section-icon orange">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>

                                <div>
                                    <h5>Transaction Amount</h5>
                                    <p>Financial information for this stock transaction.</p>
                                </div>
                            </div>

                            <span class="details-section-number">04</span>
                        </div>

                        <div class="details-section-body">
                            <div class="row g-3">

                                {{-- RATE --}}
                                @if($mealStockLog->stockTransaction->rate !== null)
                                    <div class="col-md-6">
                                        <div class="detail-info-box">
                                            <span class="detail-label">Rate</span>

                                            <div class="detail-value amount-value">
                                                ₹{{ number_format((float) $mealStockLog->stockTransaction->rate, 2) }}
                                                <small>/ {{ $mealStockLog->unit }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- TOTAL AMOUNT --}}
                                @if($mealStockLog->stockTransaction->total_amount !== null)
                                    <div class="col-md-6">
                                        <div class="detail-info-box amount-highlight-box">
                                            <span class="detail-label">Total Amount</span>

                                            <div class="detail-value total-amount-value">
                                                ₹{{ number_format((float) $mealStockLog->stockTransaction->total_amount, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                @endif

                {{-- =================================================
                     REMARKS
                ================================================== --}}
                <div class="details-section">

                    <div class="details-section-header">
                        <div class="details-section-heading">
                            <div class="details-section-icon red">
                                <i class="fas fa-sticky-note"></i>
                            </div>

                            <div>
                                <h5>Remarks</h5>
                                <p>Additional notes recorded for this movement.</p>
                            </div>
                        </div>

                        <span class="details-section-number">
                            @if(
                                $mealStockLog->stockTransaction &&
                                (
                                    $mealStockLog->stockTransaction->rate !== null ||
                                    $mealStockLog->stockTransaction->total_amount !== null
                                )
                            )
                                05
                            @else
                                04
                            @endif
                        </span>
                    </div>

                    <div class="details-section-body">
                        <div class="details-remarks">
                            @if($mealStockLog->remarks)
                                {{ $mealStockLog->remarks }}
                            @else
                                <div class="empty-remarks">
                                    <i class="fas fa-info-circle"></i>
                                    <span>
                                        No remarks were added for this stock movement.
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- FOOTER ACTION BAR --}}
        <div class="details-footer">

            <div class="details-footer-note">
                <div class="details-footer-note-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>

                <div>
                    <strong>Stock Movement Record</strong>
                    <span>
                        Review the recorded details above.
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.admin.meal.logs.index') }}"
               class="details-back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Logs
            </a>

        </div>

    </div>
</div>

@endsection
