```blade
@extends('layouts.app')

@section('title', 'Supply Item Details')

@section('content')

@php
    $stock = $supplyItem->stock;
    $quantity = $stock ? (float) $stock->quantity : 0;
    $minimum = $stock ? (float) $stock->minimum_quantity : 0;

    $isLowStock = $stock
        && $stock->status
        && $quantity <= $minimum;

    $isStockAvailable = $stock && $stock->status;
@endphp

<style>
    :root {
        --supply-dark: #174c3a;
        --supply-green: #21664c;
        --supply-light: #eaf4ee;
        --supply-border: #e2ebe5;
        --supply-text: #263b35;
        --supply-muted: #78877e;
    }

    .supply-detail-page {
        padding: 24px 0 40px;
        color: var(--supply-text);
    }

    /* Header */
    .supply-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 25px;
    }

    .supply-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .supply-title-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: var(--supply-light);
        color: var(--supply-green);
        font-size: 24px;
    }

    .supply-title-wrap h3 {
        margin: 0 0 5px;
        color: var(--supply-dark);
        font-size: 24px;
        font-weight: 800;
    }

    .supply-title-wrap p {
        margin: 0;
        color: var(--supply-muted);
        font-size: 13px;
    }

    .supply-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .supply-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        transition: .2s ease;
    }

    .supply-btn-primary {
        border: 1px solid var(--supply-dark);
        background: var(--supply-dark);
        color: #fff;
    }

    .supply-btn-primary:hover {
        background: #103b2c;
        color: #fff;
        transform: translateY(-1px);
    }

    .supply-btn-outline {
        border: 1px solid var(--supply-border);
        background: #fff;
        color: var(--supply-dark);
    }

    .supply-btn-outline:hover {
        background: var(--supply-light);
        color: var(--supply-dark);
    }

    /* Cards */
    .supply-detail-card {
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--supply-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(25,70,49,.045);
    }

    .supply-card-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 17px 21px;
        border-bottom: 1px solid var(--supply-border);
        background: #fff;
    }

    .supply-card-header-icon {
        width: 37px;
        height: 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: var(--supply-light);
        color: var(--supply-green);
        font-size: 17px;
    }

    .supply-card-header h5 {
        margin: 0;
        color: var(--supply-dark);
        font-size: 15px;
        font-weight: 800;
    }

    .supply-card-header p {
        margin: 3px 0 0;
        color: var(--supply-muted);
        font-size: 11px;
    }

    .supply-card-body {
        padding: 23px;
    }

    /* Item name panel */
    .supply-item-highlight {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 18px;
        margin-bottom: 23px;
        border: 1px solid #dcebe1;
        border-radius: 12px;
        background: #f5faf6;
    }

    .supply-item-highlight-icon {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #e1f0e5;
        color: var(--supply-green);
        font-size: 24px;
    }

    .supply-item-highlight h4 {
        margin: 0 0 5px;
        color: var(--supply-dark);
        font-size: 19px;
        font-weight: 800;
    }

    .supply-item-highlight p {
        margin: 0;
        color: var(--supply-muted);
        font-size: 12px;
    }

    /* Details */
    .supply-detail-field {
        min-height: 80px;
        padding: 14px;
        border: 1px solid #edf2ee;
        border-radius: 10px;
        background: #fcfdfc;
    }

    .supply-field-label {
        display: block;
        margin-bottom: 8px;
        color: #829087;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .supply-field-value {
        color: #30483b;
        font-size: 14px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .supply-code {
        display: inline-block;
        padding: 5px 9px;
        border: 1px solid #dcebe1;
        border-radius: 6px;
        background: #f2f8f3;
        color: var(--supply-dark);
        font-size: 12px;
        font-weight: 750;
    }

    .supply-description-box {
        padding: 15px;
        border: 1px solid #edf2ee;
        border-radius: 10px;
        background: #fcfdfc;
        color: #52665a;
        font-size: 13px;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .supply-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 750;
    }

    .supply-status-active {
        background: #e6f4ea;
        color: #217447;
    }

    .supply-status-inactive {
        background: #f0f1f2;
        color: #69737b;
    }

    /* Stock summary */
    .supply-stock-main {
        padding: 24px 18px;
        border: 1px solid #e1eee5;
        border-radius: 13px;
        background: #f6faf7;
        text-align: center;
    }

    .supply-stock-main-label {
        color: var(--supply-muted);
        font-size: 12px;
        font-weight: 700;
    }

    .supply-stock-number {
        margin: 9px 0 4px;
        color: var(--supply-dark);
        font-size: 38px;
        font-weight: 850;
        line-height: 1.2;
    }

    .supply-stock-number.low {
        color: #c18419;
    }

    .supply-stock-unit {
        color: var(--supply-muted);
        font-size: 12px;
        font-weight: 650;
    }

    .supply-stock-progress {
        height: 7px;
        margin-top: 17px;
        overflow: hidden;
        border-radius: 20px;
        background: #e5eee7;
    }

    .supply-stock-progress-bar {
        height: 100%;
        border-radius: 20px;
        background: #2b8a57;
    }

    .supply-stock-progress-bar.low {
        background: #e0a12e;
    }

    .supply-stock-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 14px 0;
        border-bottom: 1px solid #edf2ee;
        font-size: 12px;
    }

    .supply-stock-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .supply-stock-row span {
        color: var(--supply-muted);
    }

    .supply-stock-row strong {
        color: #30483b;
        font-weight: 750;
        text-align: right;
    }

    .supply-stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 750;
    }

    .stock-good {
        background: #e6f4ea;
        color: #217447;
    }

    .stock-low {
        background: #fff2dc;
        color: #a86c0b;
    }

    .stock-disabled {
        background: #f0f1f2;
        color: #69737b;
    }

    .stock-missing {
        background: #f0f2f1;
        color: #69776e;
    }

    .supply-stock-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px;
        margin-top: 18px;
        border: 1px solid #dcebe1;
        border-radius: 10px;
        background: #f2f8f4;
        color: #567061;
        font-size: 11px;
        line-height: 1.6;
    }

    .supply-stock-note i {
        color: var(--supply-green);
        font-size: 16px;
    }

    /* Delete section */
    .supply-delete-card {
        margin-top: 22px;
        padding: 19px 21px;
        border: 1px solid #f0dede;
        border-radius: 13px;
        background: #fffafa;
    }

    .supply-delete-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .supply-delete-info {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .supply-delete-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 11px;
        background: #fbeaea;
        color: #c43c3c;
        font-size: 19px;
    }

    .supply-delete-info h6 {
        margin: 0 0 5px;
        color: #823535;
        font-size: 13px;
        font-weight: 800;
    }

    .supply-delete-info p {
        margin: 0;
        color: #967575;
        font-size: 11px;
        line-height: 1.5;
    }

    .supply-delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 15px;
        border: 1px solid #d9534f;
        border-radius: 9px;
        background: #fff;
        color: #c43c3c;
        font-size: 12px;
        font-weight: 750;
        transition: .2s ease;
    }

    .supply-delete-btn:hover {
        background: #c43c3c;
        color: #fff;
    }

    @media (max-width: 767px) {
        .supply-detail-page {
            padding-top: 15px;
        }

        .supply-title-wrap h3 {
            font-size: 20px;
        }

        .supply-title-icon {
            width: 46px;
            height: 46px;
            font-size: 21px;
        }

        .supply-header-actions {
            width: 100%;
        }

        .supply-header-actions .supply-btn {
            flex: 1;
        }

        .supply-card-body {
            padding: 17px;
        }

        .supply-delete-content {
            align-items: stretch;
        }

        .supply-delete-btn {
            width: 100%;
        }

        .supply-stock-number {
            font-size: 33px;
        }
    }
</style>

<div class="container-fluid supply-detail-page">

    {{-- PAGE HEADER --}}
    <div class="supply-detail-header">

        <div class="supply-title-wrap">
            <div class="supply-title-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3>Supply Item Details</h3>
                <p>View item information and current stock details.</p>
            </div>
        </div>

        <div class="supply-header-actions">

            <a href="{{ route('admin.supply-items.edit', $supplyItem) }}"
               class="supply-btn supply-btn-primary">
                <i class="bi bi-pencil-square"></i>
                Edit Item
            </a>

            <a href="{{ route('admin.supply-items.index') }}"
               class="supply-btn supply-btn-outline">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>

    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- ITEM INFORMATION --}}
        <div class="col-lg-8">

            <div class="supply-detail-card">

                <div class="supply-card-header">

                    <div class="supply-card-header-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div>
                        <h5>Item Information</h5>
                        <p>Basic details of this supply item</p>
                    </div>

                </div>

                <div class="supply-card-body">

                    {{-- ITEM HIGHLIGHT --}}
                    <div class="supply-item-highlight">

                        <div class="supply-item-highlight-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div>
                            <h4>{{ $supplyItem->item_name }}</h4>

                            <p>
                                @if($supplyItem->item_code)
                                    Item Code: {{ $supplyItem->item_code }}
                                @else
                                    Supply Item
                                @endif
                            </p>
                        </div>

                    </div>

                    {{-- DETAILS GRID --}}
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="supply-detail-field">
                                <span class="supply-field-label">Item Code</span>

                                <div class="supply-field-value">
                                    @if($supplyItem->item_code)
                                        <span class="supply-code">
                                            {{ $supplyItem->item_code }}
                                        </span>
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="supply-detail-field">
                                <span class="supply-field-label">Item Name</span>

                                <div class="supply-field-value">
                                    {{ $supplyItem->item_name }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="supply-detail-field">
                                <span class="supply-field-label">Unit</span>

                                <div class="supply-field-value">
                                    {{ $supplyItem->unit }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="supply-detail-field">
                                <span class="supply-field-label">Item Status</span>

                                <div class="supply-field-value">

                                    @if($supplyItem->status === 'active')
                                        <span class="supply-status supply-status-active">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Active
                                        </span>
                                    @else
                                        <span class="supply-status supply-status-inactive">
                                            <i class="bi bi-pause-circle-fill"></i>
                                            Inactive
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="col-12">

                            <span class="supply-field-label">Description</span>

                            <div class="supply-description-box">
                                {{ $supplyItem->description ?: 'No description available.' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- STOCK INFORMATION --}}
        <div class="col-lg-4">

            <div class="supply-detail-card">

                <div class="supply-card-header">

                    <div class="supply-card-header-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>

                    <div>
                        <h5>Stock Information</h5>
                        <p>Current availability and stock level</p>
                    </div>

                </div>

                <div class="supply-card-body">

                    @if(!$stock)

                        <div class="supply-stock-main">

                            <div class="supply-stock-main-label">
                                Current Stock
                            </div>

                            <div class="supply-stock-number">—</div>

                            <div class="supply-stock-unit">
                                Stock record not created
                            </div>

                        </div>

                        <div class="supply-stock-row">
                            <span>Minimum Stock</span>
                            <strong>—</strong>
                        </div>

                        <div class="supply-stock-row">
                            <span>Stock Status</span>
                            <span class="supply-stock-badge stock-missing">
                                Not Added
                            </span>
                        </div>

                    @else

                        <div class="supply-stock-main">

                            <div class="supply-stock-main-label">
                                Current Stock
                            </div>

                            <div class="supply-stock-number {{ $isLowStock ? 'low' : '' }}">
                                {{ number_format($quantity, 2) }}
                            </div>

                            <div class="supply-stock-unit">
                                {{ $supplyItem->unit }}
                            </div>

                            @if($isStockAvailable && $minimum > 0)

                                @php
                                    $stockPercentage = min(100, ($quantity / $minimum) * 100);
                                @endphp

                                <div class="supply-stock-progress">
                                    <div
                                        class="supply-stock-progress-bar {{ $isLowStock ? 'low' : '' }}"
                                        style="width: {{ $stockPercentage }}%">
                                    </div>
                                </div>

                                <small class="supply-stock-unit d-block mt-2">
                                    Compared with minimum stock level
                                </small>

                            @endif

                        </div>

                        <div class="supply-stock-row">
                            <span>Minimum Stock</span>
                            <strong>{{ number_format($minimum, 2) }} {{ $supplyItem->unit }}</strong>
                        </div>

                        <div class="supply-stock-row">
                            <span>Stock Status</span>

                            @if(!$isStockAvailable)
                                <span class="supply-stock-badge stock-disabled">
                                    <i class="bi bi-pause-circle"></i>
                                    Disabled
                                </span>
                            @elseif($isLowStock)
                                <span class="supply-stock-badge stock-low">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Low Stock
                                </span>
                            @else
                                <span class="supply-stock-badge stock-good">
                                    <i class="bi bi-check-circle"></i>
                                    Available
                                </span>
                            @endif

                        </div>

                    @endif

                    <div class="supply-stock-note">
                        <i class="bi bi-info-circle-fill"></i>

                        <span>
                            Stock quantities are managed separately.
                            Use Supply Stock Management to receive or adjust stock.
                        </span>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- DELETE SECTION --}}
    <div class="supply-delete-card">

        <div class="supply-delete-content">

            <div class="supply-delete-info">

                <div class="supply-delete-icon">
                    <i class="bi bi-trash3"></i>
                </div>

                <div>
                    <h6>Delete Supply Item</h6>

                    <p>
                        Delete this item only if it is not being used in any kit
                        or related records. This action cannot be undone.
                    </p>
                </div>

            </div>

            <form method="POST"
                  action="{{ route('admin.supply-items.destroy', $supplyItem) }}"
                  onsubmit="return confirm('Are you sure you want to delete this supply item?');">

                @csrf
                @method('DELETE')

                <button type="submit" class="supply-delete-btn">
                    <i class="bi bi-trash"></i>
                    Delete Item
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
```