@extends('layouts.app')

@section('title', 'Stock Log Details | Admin')

@section('content')

<div class="meal-log-details-page">

```
<div class="container-fluid py-4">

    {{-- =========================================================
         PAGE HEADER
         ========================================================= --}}
    <div class="meal-page-header d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="meal-page-title mb-1">
                Stock Log Details
            </h4>

            <p class="meal-page-subtitle mb-0">
                View complete information about this stock movement.
            </p>
        </div>

        <a href="{{ route('admin.meal.logs.index') }}"
           class="btn meal-back-btn">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Logs

        </a>

    </div>


    {{-- =========================================================
         MAIN DETAILS CARD
         ========================================================= --}}
    <div class="meal-details-card">

        {{-- Card Header --}}
        <div class="meal-details-header">

            <div class="meal-details-title">

                <div class="meal-details-icon">
                    <i class="fas fa-history"></i>
                </div>

                <div>

                    <h5>
                        Stock Movement Information
                    </h5>

                    <span>
                        Log #{{ $mealStockLog->id }}
                    </span>

                </div>

            </div>


            {{-- Transaction Type --}}
            @if($mealStockLog->action === 'stock_in')

                <span class="details-action-badge stock-in">
                    <i class="fas fa-arrow-down me-1"></i>
                    Stock In
                </span>

            @elseif($mealStockLog->action === 'stock_out')

                <span class="details-action-badge stock-out">
                    <i class="fas fa-arrow-up me-1"></i>
                    Stock Out
                </span>

            @else

                <span class="details-action-badge neutral">
                    {{ ucfirst(str_replace('_', ' ', $mealStockLog->action ?? 'Unknown')) }}
                </span>

            @endif

        </div>


        {{-- =====================================================
             CARD BODY
             ===================================================== --}}
        <div class="meal-details-body">


            {{-- =================================================
                 MEAL ITEM
                 ================================================= --}}
            <div class="details-section">

                <div class="details-section-title">

                    <i class="fas fa-utensils"></i>

                    Meal Item

                </div>


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

                                <span class="item-meta-separator">
                                    •
                                </span>

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

                            <strong>
                                Item Deleted
                            </strong>

                            <span>
                                This meal item no longer exists.
                            </span>

                        </div>

                    </div>

                @endif

            </div>


            {{-- =================================================
                 STOCK INFORMATION
                 ================================================= --}}
            <div class="details-section">

                <div class="details-section-title">

                    <i class="fas fa-boxes"></i>

                    Stock Information

                </div>


                <div class="row g-3">

                    {{-- Movement Quantity --}}
                    <div class="col-xl-3 col-md-6">

                        <div class="detail-info-box">

                            <span class="detail-label">
                                Movement Quantity
                            </span>

                            <div class="detail-value quantity-value">

                                {{ number_format((float) $mealStockLog->quantity, 2) }}

                                <small>
                                    {{ $mealStockLog->unit }}
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Previous Stock --}}
                    <div class="col-xl-3 col-md-6">

                        <div class="detail-info-box">

                            <span class="detail-label">
                                Previous Stock
                            </span>

                            <div class="detail-value">

                                {{ number_format((float) $mealStockLog->previous_stock, 2) }}

                                <small>
                                    {{ $mealStockLog->unit }}
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Stock Change --}}
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

                                <small>
                                    {{ $mealStockLog->unit }}
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Updated Stock --}}
                    <div class="col-xl-3 col-md-6">

                        <div class="detail-info-box updated-box">

                            <span class="detail-label">
                                Updated Stock
                            </span>

                            <div class="detail-value updated-value">

                                {{ number_format((float) $mealStockLog->updated_stock, 2) }}

                                <small>
                                    {{ $mealStockLog->unit }}
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 MOVEMENT DETAILS
                 ================================================= --}}
            <div class="details-section">

                <div class="details-section-title">

                    <i class="fas fa-info-circle"></i>

                    Movement Details

                </div>


                <div class="row g-3">

                    {{-- Transaction ID --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-receipt"></i>
                            </div>

                            <div>

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


                    {{-- Transaction Date --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>

                            <div>

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


                    {{-- Log Created At --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-clock"></i>
                            </div>

                            <div>

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


                    {{-- Reason --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>

                            <div>

                                <span class="detail-row-label">
                                    Reason
                                </span>

                                <strong>
                                    {{ $mealStockLog->reason ?: 'Not specified' }}
                                </strong>

                            </div>

                        </div>

                    </div>


                    {{-- Supplier --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-truck"></i>
                            </div>

                            <div>

                                <span class="detail-row-label">
                                    Supplier
                                </span>

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


                    {{-- Performed By --}}
                    <div class="col-xl-4 col-md-6">

                        <div class="detail-row">

                            <div class="detail-row-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>

                            <div>

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


            {{-- =================================================
                 TRANSACTION AMOUNT
                 ================================================= --}}
            @if(
                $mealStockLog->stockTransaction &&
                (
                    $mealStockLog->stockTransaction->rate !== null ||
                    $mealStockLog->stockTransaction->total_amount !== null
                )
            )

                <div class="details-section">

                    <div class="details-section-title">

                        <i class="fas fa-money-bill-wave"></i>

                        Transaction Amount

                    </div>


                    <div class="row g-3">

                        {{-- Rate --}}
                        @if($mealStockLog->stockTransaction->rate !== null)

                            <div class="col-md-6">

                                <div class="detail-info-box">

                                    <span class="detail-label">
                                        Rate
                                    </span>

                                    <div class="detail-value amount-value">

                                        ₹{{ number_format((float) $mealStockLog->stockTransaction->rate, 2) }}

                                        <small>
                                            / {{ $mealStockLog->unit }}
                                        </small>

                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- Total Amount --}}
                        @if($mealStockLog->stockTransaction->total_amount !== null)

                            <div class="col-md-6">

                                <div class="detail-info-box amount-highlight-box">

                                    <span class="detail-label">
                                        Total Amount
                                    </span>

                                    <div class="detail-value total-amount-value">

                                        ₹{{ number_format((float) $mealStockLog->stockTransaction->total_amount, 2) }}

                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- =================================================
                 REMARKS
                 ================================================= --}}
            <div class="details-section remarks-section">

                <div class="details-section-title">

                    <i class="fas fa-sticky-note"></i>

                    Remarks

                </div>


                <div class="details-remarks">

                    @if($mealStockLog->remarks)

                        {{ $mealStockLog->remarks }}

                    @else

                        <span>
                            No remarks were added for this stock movement.
                        </span>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 FOOTER
                 ================================================= --}}
            <div class="details-footer">

                <a href="{{ route('admin.meal.logs.index') }}"
                   class="btn details-back-btn">

                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Logs

                </a>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection

@push('styles')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.meal-log-details-page {
    min-height: calc(100vh - 70px);
    background: #f6f8fb;
    font-family: 'Inter', sans-serif;
    color: #263142;
}


/* =========================================================
   HEADER
   ========================================================= */

.meal-page-header {
    min-height: 52px;
}

.meal-page-title {
    color: #1f2937;
    font-size: 23px;
    font-weight: 650;
    letter-spacing: -0.3px;
}

.meal-page-subtitle {
    color: #7a8494 !important;
    font-size: 13.5px;
    line-height: 1.5;
}

.meal-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
    padding: 8px 16px;
    background: #ffffff;
    border: 1px solid #dfe4ea;
    border-radius: 7px;
    color: #586474 !important;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.meal-back-btn:hover {
    background: #f8fafc;
    border-color: #c8d0da;
    color: #263142 !important;
    transform: translateY(-1px);
}


/* =========================================================
   MAIN CARD
   ========================================================= */

.meal-details-card {
    background: #ffffff;
    border: 1px solid #edf0f5;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(31, 41, 55, 0.045);
}


/* =========================================================
   CARD HEADER
   ========================================================= */

.meal-details-header {
    min-height: 76px;
    padding: 15px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.meal-details-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.meal-details-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 15px;
}

.meal-details-title h5 {
    margin: 0 0 2px;
    color: #273142;
    font-size: 15.5px;
    font-weight: 650;
}

.meal-details-title span {
    color: #8a94a3;
    font-size: 12px;
}


/* =========================================================
   ACTION BADGE
   ========================================================= */

.details-action-badge {
    display: inline-flex;
    align-items: center;
    padding: 7px 12px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 650;
    white-space: nowrap;
}

.details-action-badge.stock-in {
    background: #e8f8f1;
    color: #159a65;
}

.details-action-badge.stock-out {
    background: #fff0ef;
    color: #f65343;
}

.details-action-badge.neutral {
    background: #f4f7fb;
    color: #687385;
}


/* =========================================================
   BODY
   ========================================================= */

.meal-details-body {
    padding: 25px;
}


/* =========================================================
   SECTIONS
   ========================================================= */

.details-section {
    padding-bottom: 24px;
    margin-bottom: 24px;
    border-bottom: 1px solid #edf0f5;
}

.details-section:last-of-type {
    margin-bottom: 0;
}

.details-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 15px;
    color: #374151;
    font-size: 13px;
    font-weight: 650;
}

.details-section-title i {
    color: #147cf5;
    font-size: 13px;
}


/* =========================================================
   MEAL ITEM
   ========================================================= */

.details-item-box {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px;
    background: #fafcff;
    border: 1px solid #edf0f5;
    border-radius: 8px;
}

.details-item-icon {
    width: 46px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 9px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 17px;
}

.details-item-content h5 {
    margin: 0 0 4px;
    color: #273142;
    font-size: 14px;
    font-weight: 650;
}

.item-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    color: #8a94a3;
    font-size: 11.5px;
}

.item-meta-separator {
    color: #c3cad3;
}


/* =========================================================
   DELETED ITEM
   ========================================================= */

.deleted-item-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #fff9f0;
    border: 1px solid #ffe8c2;
    border-radius: 8px;
}

.deleted-item-icon {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    background: #fff0d6;
    color: #e49a24;
}

.deleted-item-box strong {
    display: block;
    margin-bottom: 3px;
    color: #7a5a22;
    font-size: 13px;
}

.deleted-item-box span {
    color: #9a8155;
    font-size: 11.5px;
}


/* =========================================================
   STOCK INFORMATION
   ========================================================= */

.detail-info-box {
    min-height: 92px;
    padding: 16px;
    background: #fafbfc;
    border: 1px solid #edf0f5;
    border-radius: 8px;
}

.updated-box {
    background: #f8fcff;
    border-color: #dceeff;
}

.detail-label {
    display: block;
    margin-bottom: 8px;
    color: #8a94a3;
    font-size: 11.5px;
    font-weight: 500;
}

.detail-value {
    color: #273142;
    font-size: 18px;
    font-weight: 700;
}

.detail-value small {
    color: #8a94a3;
    font-size: 11px;
    font-weight: 500;
}

.quantity-value {
    color: #147cf5;
}

.movement-value {
    color: #147cf5;
}

.updated-value {
    color: #159a65;
}

.amount-value {
    color: #586474;
}

.total-amount-value {
    color: #147cf5;
}

.amount-highlight-box {
    background: #f8fbff;
    border-color: #dcecff;
}


/* =========================================================
   DETAIL ROWS
   ========================================================= */

.detail-row {
    min-height: 72px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    background: #fafbfc;
    border: 1px solid #edf0f5;
    border-radius: 8px;
}

.detail-row-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 7px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 12px;
}

.detail-row-label {
    display: block;
    margin-bottom: 3px;
    color: #8a94a3;
    font-size: 10.5px;
}

.detail-row strong {
    display: block;
    color: #374151;
    font-size: 12.5px;
    font-weight: 600;
    word-break: break-word;
}


/* =========================================================
   REMARKS
   ========================================================= */

.remarks-section {
    border-bottom: 0;
    padding-bottom: 0;
}

.details-remarks {
    min-height: 90px;
    padding: 15px;
    background: #fafbfc;
    border: 1px solid #edf0f5;
    border-radius: 8px;
    color: #596575;
    font-size: 13px;
    line-height: 1.6;
    white-space: pre-line;
}

.details-remarks span {
    color: #9aa3af;
}


/* =========================================================
   FOOTER
   ========================================================= */

.details-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding-top: 22px;
}

.details-back-btn {
    min-height: 40px;
    padding: 8px 17px;
    background: #ffffff;
    border: 1px solid #dfe4ea;
    border-radius: 7px;
    color: #586474 !important;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.details-back-btn:hover {
    background: #f6f8fb;
    border-color: #cbd2db;
    color: #374151 !important;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 768px) {

    .meal-page-header {
        align-items: flex-start !important;
        gap: 15px;
    }

    .meal-page-title {
        font-size: 20px;
    }

    .meal-page-subtitle {
        font-size: 12.5px;
    }

    .meal-back-btn {
        min-height: 38px;
        padding: 7px 12px;
        white-space: nowrap;
    }

    .meal-details-header {
        align-items: flex-start;
    }

    .meal-details-body {
        padding: 20px;
    }

}


@media (max-width: 576px) {

    .meal-log-details-page .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .meal-page-header {
        flex-direction: column;
    }

    .meal-back-btn {
        width: 100%;
    }

    .meal-details-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px 17px;
    }

    .meal-details-body {
        padding: 17px;
    }

    .details-action-badge {
        align-self: flex-start;
    }

    .details-footer {
        justify-content: stretch;
    }

    .details-back-btn {
        width: 100%;
    }

}

</style>

@endpush
