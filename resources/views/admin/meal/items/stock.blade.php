@extends('layouts.app')
 
@section('title', 'Stock In / Out | Admin')
@section('page-title', 'Stock In / Out')
 
@section('content')
 
@php
    $hasFilters = request()->hasAny(['search', 'transaction_type', 'date']);
@endphp
 
<div class="meal-stock-page">
    <div class="container-fluid py-4">
 
        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="stock-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
 
        @if(session('error'))
            <div class="stock-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>{{ session('error') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif
 
        {{-- SCHOOL DIARY HERO --}}
        <section class="stock-hero">
            <div class="stock-hero-content">
                <div class="stock-hero-icon">
                    <i class="fas fa-right-left"></i>
                </div>
 
                <div class="stock-hero-text">
                    <div class="stock-breadcrumb">
                        INVENTORY <span>/</span> STOCK MOVEMENTS
                    </div>
 
                    <h2>Stock In / Stock Out</h2>
 
                    <p>
                        Manage inventory receipts, issues and stock movement
                        records from one place.
                    </p>
 
                    <div class="stock-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Meal Inventory Management</span>
                    </div>
                </div>
            </div>
 
            <div class="stock-hero-actions">
                <div class="hero-handwritten">Receive. Issue. Record.</div>
 
                <p>
                    Keep every receipt and issue on record so stock
                    levels always stay accurate.
                </p>
 
                <div class="hero-button-row">
                    <a href="{{ route('admin.meal.items.stock.create') }}"
                       class="diary-primary-btn hero-primary-btn">
                        <i class="fas fa-plus"></i>
                        Add Stock Movement
                    </a>
 
                    <a href="{{ route('admin.meal.items.index') }}"
                       class="diary-primary-btn hero-secondary-btn">
                        <i class="fas fa-arrow-left"></i>
                        Meal Items
                    </a>
                </div>
            </div>
        </section>
 
        {{-- KPI CARDS --}}
        <section class="stock-kpis">
 
            <article class="stock-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Stock In Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $totalStockIn }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-box-open"></i>
                    Inventory receipts
                </div>
            </article>
 
            <article class="stock-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Stock Out Records</span>
                    <span class="kpi-icon">
                        <i class="fas fa-arrow-up"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $totalStockOut }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-box"></i>
                    Inventory issues
                </div>
            </article>
 
            <article class="stock-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Total Quantity In</span>
                    <span class="kpi-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ number_format((float) $stockInQuantity, 2) }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-warehouse"></i>
                    Total quantity received
                </div>
            </article>
 
            <article class="stock-kpi cyan">
                <div class="kpi-top">
                    <span class="kpi-label">Total Quantity Out</span>
                    <span class="kpi-icon">
                        <i class="fas fa-dolly"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ number_format((float) $stockOutQuantity, 2) }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-truck-loading"></i>
                    Total quantity issued
                </div>
            </article>
 
        </section>
 
        {{-- SEARCH AND FILTERS --}}
        <section class="stock-filter-card">
 
            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>
 
                <div class="filter-heading-copy">
                    <div class="section-eyebrow">RECORD LOOKUP</div>
                    <h3>Search &amp; Filter Movements</h3>
                    <p>Find transactions by item, movement type or date.</p>
                </div>
 
                @if($hasFilters)
                    <a href="{{ route('admin.meal.items.stock.index') }}"
                       class="diary-reset-btn reset-heading-btn">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif
            </div>
 
            <form method="GET" action="{{ route('admin.meal.items.stock.index') }}">
 
                <div class="stock-filter-grid">
 
                    <div class="diary-filter-group search-group">
                        <label for="stock-search">Search Records</label>
 
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
 
                            <input
                                type="text"
                                name="search"
                                id="stock-search"
                                value="{{ request('search') }}"
                                placeholder="Item, supplier or reason..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
 
                    <div class="diary-filter-group">
                        <label for="transaction_type">Movement Type</label>
 
                        <select name="transaction_type"
                                id="transaction_type"
                                class="diary-control">
                            <option value="">All Stock Types</option>
 
                            <option value="stock_in"
                                @selected(request('transaction_type') === 'stock_in')>
                                Stock In
                            </option>
 
                            <option value="stock_out"
                                @selected(request('transaction_type') === 'stock_out')>
                                Stock Out
                            </option>
                        </select>
                    </div>
 
                    <div class="diary-filter-group">
                        <label for="stock-date">Transaction Date</label>
 
                        <input
                            type="date"
                            name="date"
                            id="stock-date"
                            value="{{ request('date') }}"
                            class="diary-control"
                        >
                    </div>
 
                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
 
                        <a href="{{ route('admin.meal.items.stock.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>
 
                </div>
            </form>
 
            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="stock-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-sliders-h"></i>
                            Active filters
                        </span>
 
                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif
 
                        @if(request('transaction_type'))
                            <span class="filter-chip">
                                <i class="fas fa-right-left"></i>
                                {{ request('transaction_type') === 'stock_in' ? 'Stock In' : 'Stock Out' }}
                            </span>
                        @endif
 
                        @if(request('date'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar"></i>
                                {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                            </span>
                        @endif
                    </div>
 
                    <span class="filter-result-count">
                        {{ $totalStockIn + $totalStockOut }} result(s)
                    </span>
                </div>
            @endif
 
        </section>
 
        {{-- STOCK IN RECORDS --}}
        <section class="stock-table-card">
 
            <div class="stock-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon in">
                        <i class="fas fa-arrow-down"></i>
                    </div>
 
                    <div>
                        <div class="section-eyebrow">INVENTORY RECEIPTS</div>
                        <h3>Stock In</h3>
                        <p>Products received into inventory.</p>
                    </div>
                </div>
 
                <div class="stock-record-count">
                    <i class="fas fa-file-circle-check"></i>
                    <strong>{{ $totalStockIn }}</strong>
                    {{ $totalStockIn == 1 ? 'Record' : 'Records' }}
                </div>
            </div>
 
            <div class="stock-table-wrapper">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>#</th>
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
                        @forelse($stockIn as $transaction)
                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $stockIn->firstItem() + $loop->index }}
                                    </span>
                                </td>
 
                                <td>
                                    <div class="stock-info">
                                        <div class="stock-mini-icon in">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>
 
                                        <div class="stock-details">
                                            <div class="stock-name">
                                                {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                            </div>
 
                                            <div class="stock-type">
                                                <i class="fas fa-scale-balanced"></i>
                                                {{ $transaction->unit ?: 'Unit not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
 
                                <td>
                                    @if($transaction->mealItem?->category)
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $transaction->mealItem->category }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    <div class="quantity-cell">
                                        <strong class="quantity-in">
                                            {{ number_format((float) $transaction->quantity, 2) }}
                                        </strong>
                                        <span>{{ $transaction->unit }}</span>
                                    </div>
                                </td>
 
                                <td>
                                    @if($transaction->rate !== null)
                                        <span class="currency-value">
                                            ₹{{ number_format((float) $transaction->rate, 2) }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    @if($transaction->total_amount !== null)
                                        <strong class="amount-value">
                                            ₹{{ number_format((float) $transaction->total_amount, 2) }}
                                        </strong>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    @if($transaction->transaction_date)
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $transaction->transaction_date->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    @if($transaction->supplier)
                                        <span class="reference-text">{{ $transaction->supplier }}</span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="stock-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>
 
                                        <h4>No Stock In Records</h4>
 
                                        <p>
                                            No stock in transactions match the
                                            current selection.
                                        </p>
 
                                        <a href="{{ route('admin.meal.items.stock.create') }}"
                                           class="diary-primary-btn">
                                            <i class="fas fa-plus"></i>
                                            Add Stock In
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
 
            {{-- STOCK IN PAGINATION --}}
            @if($stockIn->hasPages())
                <div class="stock-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $stockIn->firstItem() }}</strong>
                        to
                        <strong>{{ $stockIn->lastItem() }}</strong>
                        of
                        <strong>{{ $stockIn->total() }}</strong>
                        records
                    </div>
 
                    <div>
                        {{ $stockIn->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
 
            {{-- FOOTER --}}
            @if($stockIn->count())
                <div class="stock-card-footer">
                    <div>
                        Showing
                        <strong>{{ $stockIn->count() }}</strong>
                        record(s) on this page
                    </div>
 
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>
            @endif
 
        </section>
 
        {{-- STOCK OUT RECORDS --}}
        <section class="stock-table-card">
 
            <div class="stock-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon out">
                        <i class="fas fa-arrow-up"></i>
                    </div>
 
                    <div>
                        <div class="section-eyebrow">INVENTORY ISSUES</div>
                        <h3>Stock Out</h3>
                        <p>Products issued from inventory.</p>
                    </div>
                </div>
 
                <div class="stock-record-count">
                    <i class="fas fa-file-circle-minus"></i>
                    <strong>{{ $totalStockOut }}</strong>
                    {{ $totalStockOut == 1 ? 'Record' : 'Records' }}
                </div>
            </div>
 
            <div class="stock-table-wrapper">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
 
                    <tbody>
                        @forelse($stockOut as $transaction)
                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $stockOut->firstItem() + $loop->index }}
                                    </span>
                                </td>
 
                                <td>
                                    <div class="stock-info">
                                        <div class="stock-mini-icon out">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>
 
                                        <div class="stock-details">
                                            <div class="stock-name">
                                                {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                            </div>
 
                                            <div class="stock-type">
                                                <i class="fas fa-scale-balanced"></i>
                                                {{ $transaction->unit ?: 'Unit not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
 
                                <td>
                                    @if($transaction->mealItem?->category)
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $transaction->mealItem->category }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    <div class="quantity-cell">
                                        <strong class="quantity-out">
                                            {{ number_format((float) $transaction->quantity, 2) }}
                                        </strong>
                                        <span>{{ $transaction->unit }}</span>
                                    </div>
                                </td>
 
                                <td>
                                    @if($transaction->transaction_date)
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $transaction->transaction_date->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    @if($transaction->reason)
                                        <span class="reference-text">{{ $transaction->reason }}</span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    @if($transaction->remarks)
                                        <span class="reference-text remarks-cell">{{ $transaction->remarks }}</span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="stock-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
 
                                        <h4>No Stock Out Records</h4>
 
                                        <p>
                                            No stock out transactions match the
                                            current selection.
                                        </p>
 
                                        <a href="{{ route('admin.meal.items.stock.create') }}"
                                           class="diary-primary-btn">
                                            <i class="fas fa-plus"></i>
                                            Add Stock Out
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
 
            {{-- STOCK OUT PAGINATION --}}
            @if($stockOut->hasPages())
                <div class="stock-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $stockOut->firstItem() }}</strong>
                        to
                        <strong>{{ $stockOut->lastItem() }}</strong>
                        of
                        <strong>{{ $stockOut->total() }}</strong>
                        records
                    </div>
 
                    <div>
                        {{ $stockOut->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
 
            {{-- FOOTER --}}
            @if($stockOut->count())
                <div class="stock-card-footer">
                    <div>
                        Showing
                        <strong>{{ $stockOut->count() }}</strong>
                        record(s) on this page
                    </div>
 
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>
            @endif
 
        </section>
 
    </div>
</div>
 
<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL STOCK IN / OUT — INDEX PAGE
   Same tokens, cards, buttons and layout as Sports Achievements.
   Every selector is scoped under .meal-stock-page.
========================================================= */
 
.meal-stock-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;
 
    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}
 
.meal-stock-page *,
.meal-stock-page *::before,
.meal-stock-page *::after {
    box-sizing: border-box;
}
 
.meal-stock-page a,
.meal-stock-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}
 
.meal-stock-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}
 
.meal-stock-page .stock-hero,
.meal-stock-page .stock-filter-card,
.meal-stock-page .stock-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}
 
/* HERO */
 
.meal-stock-page .stock-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}
 
.meal-stock-page .stock-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}
 
.meal-stock-page .stock-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}
 
.meal-stock-page .stock-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}
 
.meal-stock-page .stock-hero-text {
    min-width: 0;
}
 
.meal-stock-page .stock-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}
 
.meal-stock-page .stock-breadcrumb span {
    color: #b8b09e;
}
 
.meal-stock-page .stock-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}
 
.meal-stock-page .stock-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}
 
.meal-stock-page .stock-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}
 
.meal-stock-page .stock-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 25px;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}
 
.meal-stock-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}
 
.meal-stock-page .stock-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}
 
.meal-stock-page .hero-button-row {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}
 
/* LOCKED BUTTON STYLES */
 
.meal-stock-page .diary-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--ink);
    border-radius: 7px;
    color: #fff !important;
    background: var(--ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}
 
.meal-stock-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}
 
.meal-stock-page .stock-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}
 
.meal-stock-page .stock-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}
 
.meal-stock-page .stock-hero-actions .hero-secondary-btn {
    border-color: rgba(241, 234, 216, .45);
    color: #f1ead8 !important;
    background: transparent;
}
 
.meal-stock-page .stock-hero-actions .hero-secondary-btn:hover {
    border-color: #f1ead8;
    color: #fff !important;
    background: rgba(255, 255, 255, .1);
    box-shadow: none;
}
 
/* ALERTS */
 
.meal-stock-page .stock-success-alert,
.meal-stock-page .stock-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}
 
.meal-stock-page .stock-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}
 
.meal-stock-page .stock-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}
 
.meal-stock-page .success-icon,
.meal-stock-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}
 
.meal-stock-page .success-icon { background: #dcefd9; }
.meal-stock-page .error-icon { background: #fbe0d8; }
 
.meal-stock-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}
 
.meal-stock-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}
 
/* KPI CARDS */
 
.meal-stock-page .stock-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}
 
.meal-stock-page .stock-kpi {
    --kpi-color: var(--blue);
 
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    padding: 22px 20px 16px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}
 
/* Folder tabs from the locked design */
 
.meal-stock-page .stock-kpi::before {
    position: absolute;
    top: -25px;
    left: 15px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .25px;
}
 
.meal-stock-page .stock-kpi.green { --kpi-color: #3a8060; }
.meal-stock-page .stock-kpi.red   { --kpi-color: #bd5148; }
.meal-stock-page .stock-kpi.amber { --kpi-color: #a87543; }
.meal-stock-page .stock-kpi.cyan  { --kpi-color: #527f79; }
 
.meal-stock-page .stock-kpi.green::before { content: "Stock In"; }
.meal-stock-page .stock-kpi.red::before   { content: "Stock Out"; }
.meal-stock-page .stock-kpi.amber::before { content: "Qty In"; }
.meal-stock-page .stock-kpi.cyan::before  { content: "Qty Out"; }
 
.meal-stock-page .stock-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}
 
.meal-stock-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
 
.meal-stock-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}
 
.meal-stock-page .stock-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white);
    border-radius: 10px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    font-size: 17px;
}
 
.meal-stock-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}
 
.meal-stock-page .kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 35px;
    padding-top: 11px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}
 
.meal-stock-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}
 
/* FILTER PANEL */
 
.meal-stock-page .stock-filter-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}
 
.meal-stock-page .filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 19px 20px 16px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fffefa;
}
 
.meal-stock-page .filter-heading-icon,
.meal-stock-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}
 
.meal-stock-page .panel-title-icon.in {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-stock-page .panel-title-icon.out {
    border-color: #f0d5ce;
    color: var(--red);
    background: #fff1ed;
}
 
.meal-stock-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}
 
.meal-stock-page .filter-heading h3,
.meal-stock-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}
 
.meal-stock-page .filter-heading p,
.meal-stock-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}
 
.meal-stock-page .reset-heading-btn {
    margin-left: auto;
}
 
.meal-stock-page .stock-filter-card form {
    padding: 19px 20px 20px;
}
 
.meal-stock-page .stock-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(200px, 2fr)
        minmax(130px, 1fr)
        minmax(135px, 1fr)
        auto;
    align-items: end;
    gap: 12px;
}
 
.meal-stock-page .diary-filter-group {
    min-width: 0;
}
 
.meal-stock-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}
 
.meal-stock-page .diary-control,
.meal-stock-page .diary-search-input {
    width: 100%;
    min-width: 0;
    height: 41px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .18s ease, box-shadow .18s ease;
}
 
.meal-stock-page .diary-control {
    padding: 0 10px;
}
 
.meal-stock-page .diary-control:focus,
.meal-stock-page .diary-search-input:focus-within {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}
 
.meal-stock-page .diary-search-input {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 11px;
}
 
.meal-stock-page .diary-search-input > i {
    flex: 0 0 auto;
    color: #929b8f;
    font-size: 12px;
}
 
.meal-stock-page .diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font: inherit;
}
 
.meal-stock-page .diary-search-input input::placeholder {
    color: #929b8f;
}
 
.meal-stock-page .diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
 
.meal-stock-page .diary-apply-btn,
.meal-stock-page .diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 41px;
    padding: 0 13px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}
 
.meal-stock-page .diary-apply-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-stock-page .diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
}
 
.meal-stock-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: #fffefa;
}
 
.meal-stock-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}
 
/* ACTIVE FILTERS */
 
.meal-stock-page .stock-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 11px 20px;
    border-top: 1px solid #eeece3;
    background: #faf9f4;
}
 
.meal-stock-page .active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}
 
.meal-stock-page .active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .3px;
}
 
.meal-stock-page .active-filter-label i {
    color: var(--green);
}
 
.meal-stock-page .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 220px;
    padding: 5px 9px;
    overflow: hidden;
    border: 1px solid #e6dfc9;
    border-radius: 6px;
    color: #765e32;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}
 
.meal-stock-page .filter-chip i {
    color: var(--amber);
}
 
.meal-stock-page .filter-result-count {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
/* RECORDS PANEL */
 
.meal-stock-page .stock-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}
 
.meal-stock-page .stock-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-stock-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
 
.meal-stock-page .stock-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}
 
.meal-stock-page .stock-record-count i,
.meal-stock-page .stock-record-count strong {
    color: var(--ink);
}
 
/* TABLE */
 
.meal-stock-page .stock-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
 
.meal-stock-page .stock-table {
    width: 100%;
    min-width: 1050px;
    margin: 0;
    border-collapse: collapse;
}
 
.meal-stock-page .stock-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}
 
.meal-stock-page .stock-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}
 
.meal-stock-page .stock-table tbody tr {
    transition: background .18s ease;
}
 
.meal-stock-page .stock-table tbody tr:hover {
    background: #faf9f2;
}
 
.meal-stock-page .stock-table tbody tr:last-child td {
    border-bottom: none;
}
 
.meal-stock-page .record-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e7e4d8;
    border-radius: 7px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}
 
/* PRODUCT */
 
.meal-stock-page .stock-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}
 
.meal-stock-page .stock-mini-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #e6dfce;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}
 
.meal-stock-page .stock-mini-icon.in {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-stock-page .stock-mini-icon.out {
    border-color: #f0d5ce;
    color: var(--red);
    background: #fff1ed;
}
 
.meal-stock-page .stock-details {
    min-width: 0;
}
 
.meal-stock-page .stock-name {
    max-width: 210px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}
 
.meal-stock-page .stock-type {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}
 
.meal-stock-page .stock-type i {
    color: var(--amber);
}
 
/* CATEGORY */
 
.meal-stock-page .category-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid #d7e2e9;
    border-radius: 6px;
    color: var(--blue);
    background: #f0f5f8;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}
 
/* QUANTITY, MONEY, DATE */
 
.meal-stock-page .quantity-cell {
    display: flex;
    align-items: baseline;
    gap: 5px;
    white-space: nowrap;
}
 
.meal-stock-page .quantity-cell strong {
    font-size: 12px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
}
 
.meal-stock-page .quantity-in  { color: var(--green); }
.meal-stock-page .quantity-out { color: var(--red); }
 
.meal-stock-page .quantity-cell span {
    color: var(--muted);
    font-size: 10px;
}
 
.meal-stock-page .currency-value,
.meal-stock-page .amount-value {
    color: var(--ink);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    font-variant-numeric: tabular-nums;
}
 
.meal-stock-page .amount-value {
    font-weight: 800;
}
 
.meal-stock-page .event-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
.meal-stock-page .event-date i {
    color: var(--blue);
}
 
.meal-stock-page .reference-text {
    display: inline-block;
    max-width: 220px;
    color: #647168;
    font-size: 11px;
    line-height: 1.5;
    overflow-wrap: anywhere;
}
 
.meal-stock-page .remarks-cell {
    max-width: 260px;
}
 
.meal-stock-page .diary-muted {
    color: #929a8e;
    font-size: 11px;
}
 
/* EMPTY STATE */
 
.meal-stock-page .stock-empty-state {
    padding: 60px 20px;
    text-align: center;
}
 
.meal-stock-page .empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border: 1px solid #e5dfcc;
    border-radius: 14px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 27px;
}
 
.meal-stock-page .stock-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}
 
.meal-stock-page .stock-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}
 
/* PAGINATION */
 
.meal-stock-page .stock-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-stock-page .pagination-info {
    color: var(--muted);
    font-size: 11px;
}
 
.meal-stock-page .pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}
 
.meal-stock-page .stock-pagination nav {
    margin: 0;
}
 
.meal-stock-page .stock-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}
 
.meal-stock-page .stock-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    min-height: 32px;
    padding: 5px 9px;
    border: 1px solid var(--line);
    border-radius: 7px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 12px;
    box-shadow: none;
}
 
.meal-stock-page .stock-pagination .page-link:hover {
    border-color: #bfcbbd;
    color: var(--ink-hover);
    background: #f5f4eb;
}
 
.meal-stock-page .stock-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-stock-page .stock-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}
 
/* FOOTER */
 
.meal-stock-page .stock-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}
 
.meal-stock-page .stock-card-footer strong {
    color: var(--ink);
}
 
.meal-stock-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}
 
.meal-stock-page .footer-status i {
    color: var(--green);
}
 
/* TABLET */
 
@media (max-width: 1250px) {
    .meal-stock-page .stock-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-stock-page .stock-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-stock-page .diary-filter-actions {
        grid-column: span 2;
    }
}
 
@media (max-width: 900px) {
    .meal-stock-page .stock-hero {
        grid-template-columns: 1fr;
    }
 
    .meal-stock-page .stock-hero-actions {
        min-height: 165px;
    }
}
 
/* MOBILE */
 
@media (max-width: 650px) {
    .meal-stock-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }
 
    .meal-stock-page .stock-hero::before {
        left: 22px;
    }
 
    .meal-stock-page .stock-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }
 
    .meal-stock-page .stock-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }
 
    .meal-stock-page .stock-hero-text h2 {
        font-size: 23px;
    }
 
    .meal-stock-page .stock-hero-text p {
        font-size: 12px;
    }
 
    .meal-stock-page .stock-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }
 
    .meal-stock-page .stock-hero-actions {
        min-height: 155px;
        padding: 20px;
    }
 
    .meal-stock-page .stock-kpis {
        gap: 15px;
        margin-top: 30px;
    }
 
    .meal-stock-page .stock-kpi {
        padding: 20px 16px 15px;
    }
 
    .meal-stock-page .kpi-value {
        font-size: 30px;
    }
 
    .meal-stock-page .filter-heading {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }
 
    .meal-stock-page .filter-heading h3 {
        font-size: 16px;
    }
 
    .meal-stock-page .reset-heading-btn {
        margin-left: 0;
    }
 
    .meal-stock-page .stock-filter-card form {
        padding: 15px;
    }
 
    .meal-stock-page .stock-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
 
    .meal-stock-page .diary-filter-actions {
        grid-column: auto;
    }
 
    .meal-stock-page .diary-filter-actions > * {
        flex: 1;
    }
 
    .meal-stock-page .stock-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 11px 15px;
    }
 
    .meal-stock-page .filter-result-count {
        white-space: normal;
    }
 
    .meal-stock-page .stock-panel-header {
        align-items: flex-start;
        padding: 15px;
    }
 
    .meal-stock-page .panel-title-wrap h3 {
        font-size: 16px;
    }
 
    .meal-stock-page .stock-record-count {
        gap: 5px;
        padding: 5px 7px;
    }
 
    .meal-stock-page .stock-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }
 
    .meal-stock-page .stock-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}
 
@media (max-width: 400px) {
    .meal-stock-page .stock-kpis {
        grid-template-columns: 1fr;
    }
 
    .meal-stock-page .stock-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }
 
    .meal-stock-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
 
    .meal-stock-page .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }
 
    .meal-stock-page .diary-apply-btn,
    .meal-stock-page .diary-reset-btn {
        width: 100%;
    }
 
    .meal-stock-page .hero-button-row {
        flex-direction: column;
        width: 100%;
    }
 
    .meal-stock-page .hero-button-row > a {
        width: 100%;
    }
 
    .meal-stock-page .active-filter-items {
        align-items: flex-start;
    }
 
    .meal-stock-page .filter-chip {
        max-width: 100%;
    }
}
 
/* ACCESSIBILITY */
 
.meal-stock-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}
 
@media (prefers-reduced-motion: reduce) {
    .meal-stock-page *,
    .meal-stock-page *::before,
    .meal-stock-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>
 
@endsection
 

