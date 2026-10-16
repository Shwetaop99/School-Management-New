
@extends('layouts.app')

@section('title', 'Stock In / Out | Admin')
@section('page-title', 'Stock In / Out')

@section('content')

<div class="meal-stock-page">
    <div class="container-fluid py-4">

        {{-- PAGE HEADER --}}
        <div class="meal-page-header">
            <div class="meal-heading-wrapper">
                <div class="meal-page-icon">
                    <i class="fas fa-right-left"></i>
                </div>

                <div class="meal-heading-copy">
                    <div class="meal-breadcrumb">
                        <span>MEAL MANAGEMENT</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>INVENTORY</span>
                        <i class="fas fa-chevron-right"></i>
                        <span class="breadcrumb-current">STOCK MOVEMENTS</span>
                    </div>

                    <h4 class="meal-page-title">Stock In / Stock Out</h4>

                    <p class="meal-page-subtitle">
                        Manage inventory receipts, issues, and stock movement records.
                    </p>
                </div>
            </div>

            <div class="meal-header-actions">
                <a href="{{ route('admin.admin.meal.items.index') }}"
                   class="meal-back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Meal Items
                </a>

                <a href="{{ route('admin.admin.meal.items.stock.create') }}"
                   class="meal-stock-btn">
                    <i class="fas fa-plus"></i>
                    Add Stock Movement
                </a>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div class="meal-alert meal-alert-success" role="status">
                <div class="meal-alert-icon">
                    <i class="fas fa-circle-check"></i>
                </div>

                <div class="meal-alert-copy">
                    <strong>Operation completed</strong>
                    <p>{{ session('success') }}</p>
                </div>

                <button type="button"
                        class="meal-alert-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="meal-alert meal-alert-danger" role="alert">
                <div class="meal-alert-icon">
                    <i class="fas fa-circle-exclamation"></i>
                </div>

                <div class="meal-alert-copy">
                    <strong>Something needs attention</strong>
                    <p>{{ session('error') }}</p>
                </div>

                <button type="button"
                        class="meal-alert-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
        @endif

        {{-- SUMMARY CARDS --}}
        <div class="meal-summary-grid">

            <div class="meal-summary-card summary-green">
                <div class="summary-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>

                <div class="summary-content">
                    <span class="summary-label">Stock In Records</span>
                    <strong>{{ $totalStockIn }}</strong>
                    <small>Inventory receipts</small>
                </div>

                <div class="summary-decoration">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>

            <div class="meal-summary-card summary-red">
                <div class="summary-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>

                <div class="summary-content">
                    <span class="summary-label">Stock Out Records</span>
                    <strong>{{ $totalStockOut }}</strong>
                    <small>Inventory issues</small>
                </div>

                <div class="summary-decoration">
                    <i class="fas fa-box"></i>
                </div>
            </div>

            <div class="meal-summary-card summary-wood">
                <div class="summary-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>

                <div class="summary-content">
                    <span class="summary-label">Total Quantity In</span>
                    <strong>{{ number_format((float) $stockInQuantity, 2) }}</strong>
                    <small>Total quantity received</small>
                </div>

                <div class="summary-decoration">
                    <i class="fas fa-warehouse"></i>
                </div>
            </div>

            <div class="meal-summary-card summary-cyan">
                <div class="summary-icon">
                    <i class="fas fa-dolly"></i>
                </div>

                <div class="summary-content">
                    <span class="summary-label">Total Quantity Out</span>
                    <strong>{{ number_format((float) $stockOutQuantity, 2) }}</strong>
                    <small>Total quantity issued</small>
                </div>

                <div class="summary-decoration">
                    <i class="fas fa-truck-loading"></i>
                </div>
            </div>

        </div>

        {{-- FILTER CARD --}}
        <div class="meal-card meal-filter-card">

            <div class="meal-card-header">
                <div class="meal-card-title">
                    <div class="meal-title-icon section-green">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>
                        <div class="card-eyebrow">RECORD LOOKUP</div>
                        <h5>Stock Movement Filters</h5>
                        <span>Find transactions by item, movement type, or date.</span>
                    </div>
                </div>

                <div class="filter-heading-tag">
                    <i class="fas fa-magnifying-glass"></i>
                    Search
                </div>
            </div>

            <div class="meal-filter-wrapper">
                <form method="GET"
                      action="{{ route('admin.admin.meal.items.stock.index') }}"
                      class="meal-filter-form">

                    {{-- SEARCH --}}
                    <div class="filter-field filter-search">
                        <label for="stock-search">Search records</label>

                        <div class="filter-input-wrap">
                            <i class="fas fa-search"></i>

                            <input type="text"
                                   id="stock-search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Item, supplier, or reason...">
                        </div>
                    </div>

                    {{-- MOVEMENT TYPE --}}
                    <div class="filter-field filter-control">
                        <label for="transaction_type">Movement Type</label>

                        <select id="transaction_type"
                                name="transaction_type"
                                class="form-select">

                            <option value="">All Stock Types</option>

                            <option value="stock_in"
                                {{ request('transaction_type') === 'stock_in' ? 'selected' : '' }}>
                                Stock In
                            </option>

                            <option value="stock_out"
                                {{ request('transaction_type') === 'stock_out' ? 'selected' : '' }}>
                                Stock Out
                            </option>
                        </select>
                    </div>

                    {{-- DATE --}}
                    <div class="filter-field filter-date">
                        <label for="stock-date">Transaction Date</label>

                        <input type="date"
                               id="stock-date"
                               name="date"
                               value="{{ request('date') }}"
                               class="form-control">
                    </div>

                    {{-- FILTER BUTTON --}}
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        @if(request()->hasAny(['search', 'transaction_type', 'date']))
                            <a href="{{ route('admin.admin.meal.items.stock.index') }}"
                               class="reset-filter-btn"
                               title="Clear Filters"
                               aria-label="Clear filters">
                                <i class="fas fa-rotate-left"></i>
                                Reset
                            </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        {{-- SECTION 01: STOCK IN --}}
        <div class="meal-card movement-card">

            <div class="meal-card-header">
                <div class="meal-card-title">
                    <div class="section-number">01</div>

                    <div class="meal-title-icon section-green">
                        <i class="fas fa-arrow-down"></i>
                    </div>

                    <div>
                        <div class="card-eyebrow">INVENTORY RECEIPTS</div>
                        <h5>Stock In</h5>
                        <span>Products received into inventory.</span>
                    </div>
                </div>

                <div class="movement-count stock-in-count">
                    <i class="fas fa-file-circle-check"></i>
                    {{ $totalStockIn }}
                    {{ $totalStockIn == 1 ? 'Record' : 'Records' }}
                </div>
            </div>

            <div class="meal-card-body">

                @if($stockIn->count())

                    <div class="table-responsive movement-table-wrap">
                        <table class="table movement-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($stockIn as $transaction)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="item-number">
                                                {{ $stockIn->firstItem() + $loop->index }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="movement-item">
                                                <div class="movement-icon stock-in-icon">
                                                    <i class="fas fa-bowl-food"></i>
                                                </div>

                                                <div class="movement-item-copy">
                                                    <strong>
                                                        {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                    </strong>
                                                    <small>{{ $transaction->unit }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            @if($transaction->mealItem?->category)
                                                <span class="category-badge">
                                                    {{ $transaction->mealItem->category }}
                                                </span>
                                            @else
                                                <span class="empty-value">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="quantity-cell">
                                                <strong class="quantity-value quantity-in">
                                                    {{ number_format((float) $transaction->quantity, 2) }}
                                                </strong>
                                                <span class="quantity-unit">
                                                    {{ $transaction->unit }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            @if($transaction->rate !== null)
                                                <span class="currency-value">
                                                    ₹{{ number_format((float) $transaction->rate, 2) }}
                                                </span>
                                            @else
                                                <span class="empty-value">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($transaction->total_amount !== null)
                                                <strong class="amount-value">
                                                    ₹{{ number_format((float) $transaction->total_amount, 2) }}
                                                </strong>
                                            @else
                                                <span class="empty-value">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="movement-date">
                                                {{ optional($transaction->transaction_date)->format('d M Y') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="table-secondary-text">
                                                {{ $transaction->supplier ?: '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- STOCK IN PAGINATION --}}
                    @if($stockIn->hasPages())
                        <div class="meal-pagination">
                            {{ $stockIn->links() }}
                        </div>
                    @endif

                @else

                    <div class="empty-state">
                        <div class="empty-icon stock-in-empty">
                            <i class="fas fa-box-open"></i>
                        </div>

                        <div class="empty-eyebrow">NO RECEIPTS FOUND</div>
                        <h5>No Stock In Records</h5>

                        <p>
                            No stock in transactions match the current selection.
                            You can record a new stock receipt below.
                        </p>

                        <a href="{{ route('admin.admin.meal.items.stock.create') }}"
                           class="meal-stock-btn">
                            <i class="fas fa-plus"></i>
                            Add Stock In
                        </a>
                    </div>

                @endif
            </div>
        </div>

        {{-- SECTION 02: STOCK OUT --}}
        <div class="meal-card movement-card">

            <div class="meal-card-header">
                <div class="meal-card-title">
                    <div class="section-number">02</div>

                    <div class="meal-title-icon section-red">
                        <i class="fas fa-arrow-up"></i>
                    </div>

                    <div>
                        <div class="card-eyebrow">INVENTORY ISSUES</div>
                        <h5>Stock Out</h5>
                        <span>Products issued from inventory.</span>
                    </div>
                </div>

                <div class="movement-count stock-out-count">
                    <i class="fas fa-file-circle-minus"></i>
                    {{ $totalStockOut }}
                    {{ $totalStockOut == 1 ? 'Record' : 'Records' }}
                </div>
            </div>

            <div class="meal-card-body">

                @if($stockOut->count())

                    <div class="table-responsive movement-table-wrap">
                        <table class="table movement-table">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($stockOut as $transaction)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="item-number">
                                                {{ $stockOut->firstItem() + $loop->index }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="movement-item">
                                                <div class="movement-icon stock-out-icon">
                                                    <i class="fas fa-bowl-food"></i>
                                                </div>

                                                <div class="movement-item-copy">
                                                    <strong>
                                                        {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                    </strong>
                                                    <small>{{ $transaction->unit }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            @if($transaction->mealItem?->category)
                                                <span class="category-badge">
                                                    {{ $transaction->mealItem->category }}
                                                </span>
                                            @else
                                                <span class="empty-value">—</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="quantity-cell">
                                                <strong class="quantity-value quantity-out">
                                                    {{ number_format((float) $transaction->quantity, 2) }}
                                                </strong>
                                                <span class="quantity-unit">
                                                    {{ $transaction->unit }}
                                                </span>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="movement-date">
                                                {{ optional($transaction->transaction_date)->format('d M Y') }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="table-secondary-text">
                                                {{ $transaction->reason ?: '—' }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="table-secondary-text remarks-cell">
                                                {{ $transaction->remarks ?: '—' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- STOCK OUT PAGINATION --}}
                    @if($stockOut->hasPages())
                        <div class="meal-pagination">
                            {{ $stockOut->links() }}
                        </div>
                    @endif

                @else

                    <div class="empty-state">
                        <div class="empty-icon stock-out-empty">
                            <i class="fas fa-box"></i>
                        </div>

                        <div class="empty-eyebrow">NO ISSUES FOUND</div>
                        <h5>No Stock Out Records</h5>

                        <p>
                            No stock out transactions match the current selection.
                            You can record a new stock issue below.
                        </p>

                        <a href="{{ route('admin.admin.meal.items.stock.create') }}"
                           class="meal-stock-btn">
                            <i class="fas fa-plus"></i>
                            Add Stock Out
                        </a>
                    </div>

                @endif
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="meal-page-footer">
            <i class="fas fa-book-open"></i>
            <span>Meal Inventory</span>
            <span class="footer-dot">·</span>
            <span>Stock Movement Register</span>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
/* =========================================================
   SCHOOL DIARY DESIGN SYSTEM — STOCK MOVEMENTS
========================================================= */

.meal-stock-page {
    --meal-paper: #fffefa;
    --meal-background: #f5f3e9;
    --meal-ink: #263b35;
    --meal-green: #34775a;
    --meal-green-dark: #245a43;
    --meal-green-light: #eaf3e9;
    --meal-wood: #a87543;
    --meal-cyan: #527f79;
    --meal-red: #bd5148;
    --meal-border: #e5e1d3;
    --meal-muted: #858b7f;

    min-height: calc(100vh - 70px);
    background: var(--meal-background);
    color: var(--meal-ink);
    font-family: 'Inter', sans-serif;
    font-size: 12px;
}

.meal-stock-page .container-fluid {
    max-width: 1700px;
}

/* HEADER */
.meal-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 20px;
    padding: 4px 0 4px 14px;
    border-left: 4px solid var(--meal-green);
}

.meal-heading-wrapper {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.meal-page-icon {
    width: 43px;
    height: 43px;
    flex: 0 0 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 9px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 16px;
}

.meal-heading-copy {
    min-width: 0;
}

.meal-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 4px;
    color: #8b8c7e;
    font-size: 8.5px;
    font-weight: 750;
    letter-spacing: .65px;
}

.meal-breadcrumb i {
    color: #b0ad9e;
    font-size: 7px;
}

.meal-breadcrumb .breadcrumb-current {
    color: var(--meal-green);
}

.meal-page-title {
    margin: 0 0 3px;
    color: var(--meal-ink);
    font-size: 22px;
    font-weight: 750;
    letter-spacing: -.4px;
}

.meal-page-subtitle {
    margin: 0;
    color: #777e72;
    font-size: 11px;
    line-height: 1.5;
}

.meal-header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    flex-shrink: 0;
}

.meal-stock-btn,
.meal-back-btn {
    min-height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 8px 12px;
    border: 1px solid var(--meal-border);
    border-radius: 6px;
    font-family: inherit;
    font-size: 10.5px;
    font-weight: 700;
    line-height: 1.3;
    text-decoration: none;
    transition: background .18s ease, border-color .18s ease, color .18s ease;
}

.meal-back-btn {
    background: var(--meal-paper);
    color: var(--meal-ink) !important;
}

.meal-back-btn:hover {
    border-color: #b7cfb6;
    background: var(--meal-green-light);
    color: var(--meal-green-dark) !important;
}

.meal-stock-btn {
    border-color: var(--meal-green);
    background: var(--meal-green);
    color: #fff !important;
    box-shadow: 0 3px 8px rgba(52, 119, 90, .10);
}

.meal-stock-btn:hover {
    border-color: var(--meal-green-dark);
    background: var(--meal-green-dark);
    color: #fff !important;
}

/* ALERTS */
.meal-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
    padding: 12px 14px;
    border: 1px solid var(--meal-border);
    border-left: 3px solid var(--meal-green);
    border-radius: 7px;
    background: var(--meal-paper);
}

.meal-alert-success {
    border-color: #d6e5d3;
    border-left-color: var(--meal-green);
    background: #f4f8ef;
}

.meal-alert-danger {
    border-color: #ead0c9;
    border-left-color: var(--meal-red);
    background: #fff8f4;
}

.meal-alert-icon {
    width: 29px;
    height: 29px;
    flex: 0 0 29px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 12px;
}

.meal-alert-danger .meal-alert-icon {
    background: #f7e5de;
    color: var(--meal-red);
}

.meal-alert-copy {
    flex: 1;
    min-width: 0;
}

.meal-alert-copy strong {
    display: block;
    margin-bottom: 3px;
    color: var(--meal-ink);
    font-size: 11px;
    font-weight: 750;
}

.meal-alert-copy p {
    margin: 0;
    color: #6f786c;
    font-size: 10.5px;
    line-height: 1.6;
}

.meal-alert-danger .meal-alert-copy strong,
.meal-alert-danger .meal-alert-copy p {
    color: #823d35;
}

.meal-alert-close {
    padding: 4px 5px;
    border: 0;
    background: transparent;
    color: #7d8477;
    font-size: 12px;
    cursor: pointer;
}

/* SUMMARY CARDS */
.meal-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.meal-summary-card {
    position: relative;
    min-width: 0;
    min-height: 106px;
    display: flex;
    align-items: center;
    gap: 12px;
    overflow: hidden;
    padding: 15px;
    border: 1px solid var(--meal-border);
    border-radius: 9px;
    background: var(--meal-paper);
    box-shadow: 0 3px 12px rgba(56, 60, 42, .035);
}

.summary-green {
    border-top: 3px solid var(--meal-green);
}

.summary-red {
    border-top: 3px solid var(--meal-red);
}

.summary-wood {
    border-top: 3px solid var(--meal-wood);
}

.summary-cyan {
    border-top: 3px solid var(--meal-cyan);
}

.summary-icon {
    position: relative;
    z-index: 1;
    width: 39px;
    height: 39px;
    flex: 0 0 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 8px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 14px;
}

.summary-red .summary-icon {
    border-color: #ead0c9;
    background: #f9e9e3;
    color: var(--meal-red);
}

.summary-wood .summary-icon {
    border-color: #e8d9c5;
    background: #f7eee2;
    color: var(--meal-wood);
}

.summary-cyan .summary-icon {
    border-color: #d5e5df;
    background: #eaf2ed;
    color: var(--meal-cyan);
}

.summary-content {
    position: relative;
    z-index: 1;
    min-width: 0;
}

.summary-label {
    display: block;
    margin-bottom: 4px;
    color: #747b6e;
    font-size: 9.5px;
    font-weight: 650;
}

.summary-content strong {
    display: block;
    overflow-wrap: anywhere;
    color: var(--meal-ink);
    font-size: 22px;
    font-weight: 800;
    line-height: 1.15;
    font-variant-numeric: tabular-nums;
}

.summary-content small {
    display: block;
    margin-top: 5px;
    color: #929385;
    font-size: 9px;
    line-height: 1.4;
}

.summary-decoration {
    position: absolute;
    right: 10px;
    bottom: 5px;
    color: rgba(52, 119, 90, .07);
    font-size: 35px;
}

.summary-red .summary-decoration {
    color: rgba(189, 81, 72, .07);
}

.summary-wood .summary-decoration {
    color: rgba(168, 117, 67, .08);
}

.summary-cyan .summary-decoration {
    color: rgba(82, 127, 121, .08);
}

/* CARDS */
.meal-card {
    overflow: hidden;
    margin-bottom: 18px;
    border: 1px solid var(--meal-border);
    border-radius: 9px;
    background: var(--meal-paper);
    box-shadow: 0 3px 13px rgba(56, 60, 42, .035);
}

.meal-card:last-of-type {
    margin-bottom: 0;
}

.meal-card-header {
    min-height: 68px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 17px;
    border-bottom: 1px solid var(--meal-border);
    background: #fffdf6;
}

.meal-card-title {
    min-width: 0;
    display: flex;
    align-items: center;
    gap: 9px;
}

.section-number {
    width: 25px;
    height: 25px;
    flex: 0 0 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--meal-border);
    border-radius: 5px;
    background: #f5f1e5;
    color: #777765;
    font-size: 9px;
    font-weight: 800;
}

.meal-title-icon {
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 7px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 12px;
}

.section-green {
    border-color: #d4e2d2;
    background: var(--meal-green-light);
    color: var(--meal-green);
}

.section-red {
    border-color: #ead0c9;
    background: #f9e9e3;
    color: var(--meal-red);
}

.card-eyebrow {
    margin-bottom: 3px;
    color: var(--meal-green);
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .7px;
}

.meal-card-title h5 {
    margin: 0 0 3px;
    color: var(--meal-ink);
    font-size: 12px;
    font-weight: 800;
}

.meal-card-title span {
    color: #85897e;
    font-size: 9.5px;
    line-height: 1.4;
}

.filter-heading-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid var(--meal-border);
    border-radius: 5px;
    background: #f8f5eb;
    color: #777b6c;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
}

.filter-heading-tag i {
    color: var(--meal-green);
}

/* FILTERS */
.meal-filter-wrapper {
    padding: 15px 17px;
}

.meal-filter-form {
    display: flex;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 12px;
}

.filter-field {
    min-width: 0;
}

.filter-search {
    flex: 1 1 250px;
}

.filter-control {
    flex: 0 1 175px;
}

.filter-date {
    flex: 0 1 160px;
}

.filter-field label {
    display: block;
    margin-bottom: 6px;
    color: #4c584b;
    font-size: 9.5px;
    font-weight: 750;
}

.filter-input-wrap {
    position: relative;
}

.filter-input-wrap > i {
    position: absolute;
    top: 50%;
    left: 11px;
    transform: translateY(-50%);
    color: #8c9384;
    font-size: 10px;
    pointer-events: none;
}

.meal-filter-form .form-control,
.meal-filter-form .form-select {
    width: 100%;
    height: 36px;
    padding: 7px 10px;
    border: 1px solid #dedecf;
    border-radius: 6px;
    background-color: #fffefa;
    color: var(--meal-ink);
    font-family: inherit;
    font-size: 10px;
    box-shadow: none;
}

.meal-filter-form .filter-input-wrap .form-control {
    padding-left: 32px;
}

.meal-filter-form .form-control::placeholder {
    color: #a5a697;
}

.meal-filter-form .form-control:focus,
.meal-filter-form .form-select:focus {
    border-color: var(--meal-green);
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .08);
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.filter-btn,
.reset-filter-btn {
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 7px 11px;
    border: 1px solid var(--meal-green);
    border-radius: 6px;
    background: var(--meal-green);
    color: #fff;
    font-family: inherit;
    font-size: 10px;
    font-weight: 750;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background .18s ease, border-color .18s ease;
}

.filter-btn:hover {
    border-color: var(--meal-green-dark);
    background: var(--meal-green-dark);
    color: #fff;
}

.reset-filter-btn {
    border-color: var(--meal-border);
    background: var(--meal-paper);
    color: #6f776a;
}

.reset-filter-btn:hover {
    border-color: #d4e2d2;
    background: var(--meal-green-light);
    color: var(--meal-green-dark);
}

/* TABLE BODY */
.meal-card-body {
    background: var(--meal-paper);
}

.movement-table-wrap {
    width: 100%;
}

.movement-table {
    width: 100%;
    min-width: 950px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.movement-table thead th {
    padding: 12px 11px;
    border-bottom: 1px solid var(--meal-border);
    background: #f5f1e5;
    color: #707365;
    font-size: 8.5px;
    font-weight: 800;
    letter-spacing: .45px;
    text-transform: uppercase;
    white-space: nowrap;
}

.movement-table tbody td {
    padding: 12px 11px;
    border-bottom: 1px solid #eeeade;
    background: var(--meal-paper);
    color: #4e5a4e;
    font-size: 10px;
    vertical-align: middle;
}

.movement-table tbody tr {
    transition: background .15s ease;
}

.movement-table tbody tr:hover td {
    background: #f8f7ef;
}

.movement-table tbody tr:last-child td {
    border-bottom: 0;
}

/* TABLE CONTENT */
.item-number {
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--meal-border);
    border-radius: 5px;
    background: #f5f1e5;
    color: #777b6c;
    font-size: 9px;
    font-weight: 750;
    font-variant-numeric: tabular-nums;
}

.movement-item {
    min-width: 145px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.movement-icon {
    width: 30px;
    height: 30px;
    flex: 0 0 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #d4e2d2;
    border-radius: 6px;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 11px;
}

.movement-icon.stock-out-icon {
    border-color: #ead0c9;
    background: #f9e9e3;
    color: var(--meal-red);
}

.movement-item-copy {
    min-width: 0;
}

.movement-item strong {
    display: block;
    max-width: 190px;
    overflow-wrap: anywhere;
    color: var(--meal-ink);
    font-size: 10px;
    font-weight: 750;
    line-height: 1.4;
}

.movement-item small {
    display: block;
    margin-top: 2px;
    color: #929385;
    font-size: 9px;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 7px;
    border: 1px solid #e5e1d3;
    border-radius: 4px;
    background: #f8f5eb;
    color: #747866;
    font-size: 9px;
    font-weight: 700;
}

.empty-value {
    color: #aaa99a;
}

.quantity-cell {
    white-space: nowrap;
}

.quantity-value {
    font-size: 10.5px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}

.quantity-in {
    color: var(--meal-green);
}

.quantity-out {
    color: var(--meal-red);
}

.quantity-unit {
    margin-left: 3px;
    color: #85897e;
    font-size: 9px;
}

.currency-value,
.amount-value {
    color: var(--meal-ink);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}

.movement-date {
    color: #5d685a;
    font-size: 9.5px;
    font-weight: 700;
    white-space: nowrap;
}

.table-secondary-text {
    display: inline-block;
    max-width: 200px;
    color: #687163;
    font-size: 9.5px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.remarks-cell {
    max-width: 240px;
}

/* RECORD COUNTS */
.movement-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
    padding: 6px 9px;
    border: 1px solid #d4e2d2;
    border-radius: 5px;
    background: var(--meal-green-light);
    color: var(--meal-green-dark);
    font-size: 9px;
    font-weight: 750;
    white-space: nowrap;
}

.stock-out-count {
    border-color: #ead0c9;
    background: #f9e9e3;
    color: var(--meal-red);
}

/* PAGINATION */
.meal-pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 12px 15px;
    border-top: 1px solid var(--meal-border);
    background: #fffdf6;
}

.meal-pagination .pagination {
    flex-wrap: wrap;
    gap: 3px;
    margin: 0;
}

.meal-pagination .page-link {
    border-color: var(--meal-border);
    border-radius: 4px;
    background: var(--meal-paper);
    color: #657061;
    font-size: 10px;
    padding: 5px 9px;
    box-shadow: none;
}

.meal-pagination .page-link:hover {
    border-color: #c8d9c4;
    background: var(--meal-green-light);
    color: var(--meal-green-dark);
}

.meal-pagination .page-item.active .page-link {
    border-color: var(--meal-green);
    background: var(--meal-green);
    color: #fff;
}

.meal-pagination .page-item.disabled .page-link {
    background: #f5f1e5;
    color: #aaa99a;
}

/* EMPTY STATE */
.empty-state {
    padding: 42px 18px;
    text-align: center;
}

.empty-icon {
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    border: 1px solid #d4e2d2;
    border-radius: 50%;
    background: var(--meal-green-light);
    color: var(--meal-green);
    font-size: 20px;
}

.stock-out-empty {
    border-color: #ead0c9;
    background: #f9e9e3;
    color: var(--meal-red);
}

.empty-eyebrow {
    margin-bottom: 5px;
    color: #929181;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: .8px;
}

.empty-state h5 {
    margin: 0 0 7px;
    color: var(--meal-ink);
    font-size: 13px;
    font-weight: 800;
}

.empty-state p {
    max-width: 400px;
    margin: 0 auto 15px;
    color: #85897e;
    font-size: 10.5px;
    line-height: 1.7;
}

.empty-state .meal-stock-btn {
    display: inline-flex;
}

/* FOOTER */
.meal-page-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 7px;
    padding: 15px 0 2px;
    color: #929181;
    font-size: 9px;
}

.meal-page-footer i {
    color: var(--meal-green);
}

.footer-dot {
    color: #b5b09f;
}

/* TABLET */
@media (max-width: 1100px) {
    .meal-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .meal-filter-form {
        align-items: flex-end;
    }

    .filter-search {
        flex-basis: 100%;
    }

    .filter-control,
    .filter-date {
        flex: 1 1 150px;
    }
}

/* SMALL TABLET */
@media (max-width: 767.98px) {
    .meal-page-header {
        align-items: flex-start;
    }

    .meal-page-title {
        font-size: 20px;
    }

    .meal-card-header {
        padding: 12px;
    }

    .meal-filter-wrapper {
        padding: 13px;
    }

    .movement-table {
        min-width: 950px;
    }

    .movement-count {
        font-size: 8px;
        padding: 5px 7px;
    }
}

/* MOBILE */
@media (max-width: 480px) {
    .meal-stock-page .container-fluid {
        padding-right: 10px !important;
        padding-left: 10px !important;
    }

    .meal-page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
        padding-left: 10px;
    }

    .meal-heading-wrapper {
        align-items: flex-start;
        gap: 9px;
    }

    .meal-page-icon {
        width: 37px;
        height: 37px;
        flex-basis: 37px;
        font-size: 14px;
    }

    .meal-breadcrumb {
        gap: 5px;
        font-size: 7.5px;
    }

    .meal-page-title {
        font-size: 19px;
    }

    .meal-page-subtitle {
        font-size: 10px;
    }

    .meal-header-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        width: 100%;
    }

    .meal-back-btn,
    .meal-stock-btn {
        width: 100%;
        padding: 8px 6px;
        font-size: 9px;
    }

    .meal-summary-grid {
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .meal-summary-card {
        min-height: 98px;
        align-items: flex-start;
        gap: 8px;
        padding: 11px 9px;
    }

    .summary-icon {
        width: 30px;
        height: 30px;
        flex-basis: 30px;
        border-radius: 6px;
        font-size: 11px;
    }

    .summary-label {
        font-size: 8px;
    }

    .summary-content strong {
        font-size: 17px;
    }

    .summary-content small {
        font-size: 8px;
    }

    .summary-decoration {
        right: 4px;
        bottom: 3px;
        font-size: 25px;
    }

    .meal-card {
        border-radius: 7px;
    }

    .meal-card-header {
        align-items: flex-start;
        gap: 7px;
    }

    .meal-card-title {
        gap: 6px;
    }

    .meal-card-title span {
        font-size: 8.5px;
    }

    .meal-title-icon {
        width: 29px;
        height: 29px;
        flex-basis: 29px;
        font-size: 10px;
    }

    .section-number {
        width: 22px;
        height: 22px;
        flex-basis: 22px;
        font-size: 8px;
    }

    .meal-card-title h5 {
        font-size: 11px;
    }

    .movement-count {
        max-width: 90px;
        white-space: normal;
        text-align: center;
        line-height: 1.4;
    }

    .filter-heading-tag {
        display: none;
    }

    .meal-filter-form {
        align-items: stretch;
        flex-direction: column;
        gap: 11px;
    }

    .filter-search,
    .filter-control,
    .filter-date {
        width: 100%;
        flex: 1 1 auto;
    }

    .filter-actions {
        width: 100%;
    }

    .filter-btn {
        flex: 1;
    }

    .reset-filter-btn {
        min-width: 72px;
    }

    .empty-state {
        padding: 30px 13px;
    }

    .meal-pagination {
        justify-content: center;
        padding: 10px;
    }
}

/* REDUCED MOTION */
@media (prefers-reduced-motion: reduce) {
    .meal-stock-page *,
    .meal-stock-page *::before,
    .meal-stock-page *::after {
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>
@endpush
