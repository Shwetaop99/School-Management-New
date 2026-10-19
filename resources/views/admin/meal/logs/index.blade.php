@extends('layouts.app')
 
@section('title', 'Meal Stock Logs | Admin')
@section('page-title', 'Meal Stock Logs')
 
@section('content')
 
@php
    $filterKeys = ['search', 'date_from', 'date_to', 'meal_item_id', 'category', 'transaction_type'];
    $hasFilters = request()->hasAny($filterKeys);
@endphp
 
<div class="meal-logs-page">
    <div class="container-fluid py-4">
 
        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="logs-success-alert" role="status">
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
            <div class="logs-error-alert" role="alert">
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
        <section class="logs-hero">
            <div class="logs-hero-content">
                <div class="logs-hero-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>
 
                <div class="logs-hero-text">
                    <div class="logs-breadcrumb">
                        INVENTORY <span>/</span> STOCK LOGS
                    </div>
 
                    <h2>Meal Stock Logs</h2>
 
                    <p>
                        Track stock-in and stock-out transactions, review
                        inventory movement and keep a clear record of every
                        stock change.
                    </p>
 
                    <div class="logs-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Meal Inventory &amp; Transaction Audit</span>
                    </div>
                </div>
            </div>
 
            <div class="logs-hero-actions">
                <div class="hero-handwritten">Every Item. Every Movement.</div>
 
                <p>
                    Keep inventory records organised, accurate
                    and transparent.
                </p>
 
                <a href="{{ route('admin.meal.items.index') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-utensils"></i>
                    Manage Meal Items
                </a>
            </div>
        </section>
 
        {{-- KPI CARDS --}}
        <section class="logs-kpis">
 
            <article class="logs-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Movements</span>
                    <span class="kpi-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $totalMovements }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-list-check"></i>
                    Total recorded stock transactions
                </div>
            </article>
 
            <article class="logs-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Stock In</span>
                    <span class="kpi-icon">
                        <i class="fas fa-arrow-down"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $totalStockIn }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-circle-plus"></i>
                    Inventory additions recorded
                </div>
            </article>
 
            <article class="logs-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Stock Out</span>
                    <span class="kpi-icon">
                        <i class="fas fa-arrow-up"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $totalStockOut }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    Inventory issues and usage
                </div>
            </article>
 
            <article class="logs-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Low Stock Items</span>
                    <span class="kpi-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $lowStockItems }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-bell"></i>
                    Items requiring stock attention
                </div>
            </article>
 
        </section>
 
        {{-- SEARCH AND FILTERS --}}
        <section class="logs-filter-card">
 
            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>
 
                <div class="filter-heading-copy">
                    <div class="section-eyebrow">RECORD MANAGEMENT</div>
                    <h3>Search &amp; Filter Transactions</h3>
                    <p>
                        Find records by keyword, date, item, category or
                        movement type.
                    </p>
                </div>
 
                @if($hasFilters)
                    <a href="{{ route('admin.meal.logs.index') }}"
                       class="diary-reset-btn reset-heading-btn">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif
            </div>
 
            <form method="GET" action="{{ route('admin.meal.logs.index') }}">
 
                <div class="logs-filter-grid">
 
                    {{-- SEARCH --}}
                    <div class="diary-filter-group search-group">
                        <label for="logs-search">Search Transactions</label>
 
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
 
                            <input
                                type="text"
                                name="search"
                                id="logs-search"
                                value="{{ request('search') }}"
                                placeholder="Item, transaction ID or reason..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
 
                    {{-- DATE FROM --}}
                    <div class="diary-filter-group">
                        <label for="logs-date-from">Date From</label>
 
                        <input
                            type="date"
                            name="date_from"
                            id="logs-date-from"
                            class="diary-control"
                            value="{{ request('date_from') }}"
                        >
                    </div>
 
                    {{-- DATE TO --}}
                    <div class="diary-filter-group">
                        <label for="logs-date-to">Date To</label>
 
                        <input
                            type="date"
                            name="date_to"
                            id="logs-date-to"
                            class="diary-control"
                            value="{{ request('date_to') }}"
                        >
                    </div>
 
                    {{-- ITEM --}}
                    <div class="diary-filter-group">
                        <label for="logs-item">Meal Item</label>
 
                        <select name="meal_item_id"
                                id="logs-item"
                                class="diary-control">
                            <option value="">All Items</option>
 
                            @foreach($items as $item)
                                <option value="{{ $item->id }}"
                                    @selected((string) request('meal_item_id') === (string) $item->id)>
                                    {{ $item->name ?? $item->item_name ?? 'Item #' . $item->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
 
                    {{-- CATEGORY --}}
                    <div class="diary-filter-group">
                        <label for="logs-category">Category</label>
 
                        <select name="category"
                                id="logs-category"
                                class="diary-control">
                            <option value="">All Categories</option>
 
                            @foreach($categories as $category)
                                <option value="{{ $category }}"
                                    @selected((string) request('category') === (string) $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
 
                    {{-- MOVEMENT TYPE --}}
                    <div class="diary-filter-group">
                        <label for="logs-type">Movement Type</label>
 
                        <select name="transaction_type"
                                id="logs-type"
                                class="diary-control">
                            <option value="">All Movements</option>
 
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
 
                    {{-- ACTIONS --}}
                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
 
                        <a href="{{ route('admin.meal.logs.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>
 
                </div>
            </form>
 
            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="logs-active-filters">
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
 
                        @if(request('date_from'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar"></i>
                                From: {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                            </span>
                        @endif
 
                        @if(request('date_to'))
                            <span class="filter-chip">
                                <i class="fas fa-calendar-check"></i>
                                To: {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                            </span>
                        @endif
 
                        @if(request('meal_item_id'))
                            @php
                                $selectedMealItem = $items->firstWhere('id', request('meal_item_id'));
                            @endphp
 
                            <span class="filter-chip">
                                <i class="fas fa-utensils"></i>
                                {{ $selectedMealItem->name ?? $selectedMealItem->item_name ?? 'Selected Item' }}
                            </span>
                        @endif
 
                        @if(request('category'))
                            <span class="filter-chip">
                                <i class="fas fa-tags"></i>
                                {{ request('category') }}
                            </span>
                        @endif
 
                        @if(request('transaction_type'))
                            <span class="filter-chip">
                                <i class="fas fa-right-left"></i>
                                {{ request('transaction_type') === 'stock_in' ? 'Stock In' : 'Stock Out' }}
                            </span>
                        @endif
                    </div>
 
                    <span class="filter-result-count">
                        {{ $logs->total() }} result(s)
                    </span>
                </div>
            @endif
 
        </section>
 
        {{-- TRANSACTION RECORDS --}}
        <section class="logs-table-card">
 
            <div class="logs-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
 
                    <div>
                        <div class="section-eyebrow">INVENTORY REGISTER</div>
                        <h3>Stock Movement History</h3>
                        <p>Review, search and export meal inventory transactions.</p>
                    </div>
                </div>
 
                <div class="logs-header-tools">
                    <div class="logs-record-count">
                        <i class="fas fa-folder-open"></i>
                        <strong>{{ $logs->total() }}</strong>
                        Records
                    </div>
 
                    <div class="logs-export-actions">
                        <button type="button"
                                class="logs-export-btn print"
                                onclick="printMealLogs()">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
 
                        <a href="{{ route('admin.meal.logs.pdf', request()->query()) }}"
                           class="logs-export-btn pdf">
                            <i class="fas fa-file-pdf"></i>
                            PDF
                        </a>
 
                        <a href="{{ route('admin.meal.logs.excel', request()->query()) }}"
                           class="logs-export-btn excel">
                            <i class="fas fa-file-excel"></i>
                            Excel
                        </a>
                    </div>
                </div>
            </div>
 
            {{-- PRINTABLE AREA --}}
            <div id="mealLogsPrintArea">
 
                <div class="logs-print-header">
                    <h2>Meal Stock Movement Report</h2>
                    <p>School Meal Management — Inventory Transaction History</p>
                    <span>Generated on {{ now()->format('d M Y, h:i A') }}</span>
                </div>
 
                <div class="logs-table-wrapper">
                    <table class="logs-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date &amp; Time</th>
                                <th>Transaction ID</th>
                                <th>Meal Item</th>
                                <th>Category</th>
                                <th>Movement</th>
                                <th>Quantity</th>
                                <th>Stock Before</th>
                                <th>Stock After</th>
                                <th>Reason</th>
                                <th>Performed By</th>
                                <th class="actions-heading">Action</th>
                            </tr>
                        </thead>
 
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $movementType = strtolower((string) ($log->action ?? ''));
 
                                    $isStockIn = in_array($movementType, ['stock_in', 'in', 'stock-in'], true);
                                    $isStockOut = in_array($movementType, ['stock_out', 'out', 'stock-out'], true);
 
                                    $displayMovement = $isStockIn
                                        ? 'Stock In'
                                        : ($isStockOut
                                            ? 'Stock Out'
                                            : ($movementType
                                                ? ucfirst(str_replace('_', ' ', $movementType))
                                                : 'Other'));
                                @endphp
 
                                <tr>
                                    <td>
                                        <span class="record-number">
                                            {{ $logs->firstItem() + $loop->index }}
                                        </span>
                                    </td>
 
                                    <td>
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $log->created_at ? $log->created_at->format('d M Y') : '—' }}
                                        </div>
 
                                        @if($log->created_at)
                                            <div class="event-time">
                                                <i class="far fa-clock"></i>
                                                {{ $log->created_at->format('h:i A') }}
                                            </div>
                                        @endif
                                    </td>
 
                                    <td>
                                        @if($log->stock_transaction_id)
                                            <span class="transaction-id">
                                                <i class="fas fa-hashtag"></i>
                                                {{ $log->stock_transaction_id }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        <div class="logs-info">
                                            <div class="logs-mini-icon">
                                                <i class="fas fa-utensils"></i>
                                            </div>
 
                                            <div class="logs-details">
                                                <div class="logs-name">
                                                    {{ $log->mealItem->name
                                                        ?? $log->mealItem->item_name
                                                        ?? 'Deleted / Unavailable Item' }}
                                                </div>
 
                                                @if($log->unit)
                                                    <div class="logs-type">
                                                        <i class="fas fa-scale-balanced"></i>
                                                        {{ $log->unit }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
 
                                    <td>
                                        @if($log->mealItem && $log->mealItem->category)
                                            <span class="category-badge">
                                                <i class="fas fa-tag"></i>
                                                {{ $log->mealItem->category }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        <span class="movement-badge {{ $isStockIn ? 'stock-in' : ($isStockOut ? 'stock-out' : 'other') }}">
                                            <span class="status-dot"></span>
                                            {{ $displayMovement }}
                                        </span>
                                    </td>
 
                                    <td>
                                        <span class="quantity-badge {{ $isStockIn ? 'quantity-in' : ($isStockOut ? 'quantity-out' : '') }}">
                                            {{ $isStockIn ? '+' : ($isStockOut ? '−' : '') }}{{ number_format((float) $log->quantity, 2, '.', '') }}
                                        </span>
 
                                        @if($log->unit)
                                            <div class="quantity-unit">{{ $log->unit }}</div>
                                        @endif
                                    </td>
 
                                    <td>
                                        <span class="stock-before">
                                            {{ $log->previous_stock !== null
                                                ? number_format((float) $log->previous_stock, 2, '.', '')
                                                : '—' }}
                                        </span>
                                    </td>
 
                                    <td>
                                        <span class="stock-after">
                                            {{ $log->updated_stock !== null
                                                ? number_format((float) $log->updated_stock, 2, '.', '')
                                                : '—' }}
                                        </span>
                                    </td>
 
                                    <td>
                                        <div class="logs-reason" title="{{ $log->reason ?? '' }}">
                                            {{ $log->reason ?: 'Not specified' }}
                                        </div>
                                    </td>
 
                                    <td>
                                        @if($log->performed_by)
                                            <span class="performed-by">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $log->performed_by }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        <div class="logs-actions">
                                            <a href="{{ route('admin.meal.logs.show', $log) }}"
                                               class="game-action-btn view"
                                               title="View Transaction Details"
                                               aria-label="View transaction details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12">
                                        <div class="logs-empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-box-open"></i>
                                            </div>
 
                                            <h4>No Stock Transactions Found</h4>
 
                                            <p>
                                                No stock movement records match your
                                                current search or filters.
                                            </p>
 
                                            <a href="{{ route('admin.meal.logs.index') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-rotate-left"></i>
                                                View All Transactions
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
 
            {{-- PAGINATION --}}
            @if($logs->hasPages())
                <div class="logs-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $logs->firstItem() }}</strong>
                        to
                        <strong>{{ $logs->lastItem() }}</strong>
                        of
                        <strong>{{ $logs->total() }}</strong>
                        records
                    </div>
 
                    <div>
                        {{ $logs->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
 
            {{-- FOOTER --}}
            @if($logs->total() > 0)
                <div class="logs-card-footer">
                    <div>
                        Showing
                        <strong>{{ $logs->count() }}</strong>
                        record(s) on this page
                    </div>
 
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Transaction history loaded
                    </div>
                </div>
            @endif
 
        </section>
 
    </div>
</div>
 
<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL STOCK LOGS — INDEX PAGE
   Same tokens, cards, buttons and layout as Sports Achievements.
   Every selector is scoped under .meal-logs-page.
========================================================= */
 
.meal-logs-page {
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
 
.meal-logs-page *,
.meal-logs-page *::before,
.meal-logs-page *::after {
    box-sizing: border-box;
}
 
.meal-logs-page a,
.meal-logs-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}
 
.meal-logs-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}
 
.meal-logs-page .logs-hero,
.meal-logs-page .logs-filter-card,
.meal-logs-page .logs-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}
 
/* HERO */
 
.meal-logs-page .logs-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}
 
.meal-logs-page .logs-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}
 
.meal-logs-page .logs-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}
 
.meal-logs-page .logs-hero-icon {
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
 
.meal-logs-page .logs-hero-text {
    min-width: 0;
}
 
.meal-logs-page .logs-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}
 
.meal-logs-page .logs-breadcrumb span {
    color: #b8b09e;
}
 
.meal-logs-page .logs-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}
 
.meal-logs-page .logs-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}
 
.meal-logs-page .logs-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}
 
.meal-logs-page .logs-hero-actions {
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
 
.meal-logs-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}
 
.meal-logs-page .logs-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}
 
/* LOCKED BUTTON STYLES */
 
.meal-logs-page .diary-primary-btn {
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
 
.meal-logs-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}
 
.meal-logs-page .logs-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}
 
.meal-logs-page .logs-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}
 
/* ALERTS */
 
.meal-logs-page .logs-success-alert,
.meal-logs-page .logs-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}
 
.meal-logs-page .logs-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}
 
.meal-logs-page .logs-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}
 
.meal-logs-page .success-icon,
.meal-logs-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}
 
.meal-logs-page .success-icon { background: #dcefd9; }
.meal-logs-page .error-icon { background: #fbe0d8; }
 
.meal-logs-page .alert-close {
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
 
.meal-logs-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}
 
/* KPI CARDS */
 
.meal-logs-page .logs-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}
 
.meal-logs-page .logs-kpi {
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
 
.meal-logs-page .logs-kpi::before {
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
 
.meal-logs-page .logs-kpi.blue  { --kpi-color: #2b5d8a; }
.meal-logs-page .logs-kpi.green { --kpi-color: #3a8060; }
.meal-logs-page .logs-kpi.amber { --kpi-color: #a87543; }
.meal-logs-page .logs-kpi.red   { --kpi-color: #bd5148; }
 
.meal-logs-page .logs-kpi.blue::before  { content: "Movements"; }
.meal-logs-page .logs-kpi.green::before { content: "Stock In"; }
.meal-logs-page .logs-kpi.amber::before { content: "Stock Out"; }
.meal-logs-page .logs-kpi.red::before   { content: "Low Stock"; }
 
.meal-logs-page .logs-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}
 
.meal-logs-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
 
.meal-logs-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}
 
.meal-logs-page .logs-kpi .kpi-icon {
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
 
.meal-logs-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}
 
.meal-logs-page .kpi-caption {
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
 
.meal-logs-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}
 
/* FILTER PANEL */
 
.meal-logs-page .logs-filter-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}
 
.meal-logs-page .filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 19px 20px 16px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fffefa;
}
 
.meal-logs-page .filter-heading-icon,
.meal-logs-page .panel-title-icon {
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
 
.meal-logs-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}
 
.meal-logs-page .filter-heading h3,
.meal-logs-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}
 
.meal-logs-page .filter-heading p,
.meal-logs-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}
 
.meal-logs-page .reset-heading-btn {
    margin-left: auto;
}
 
.meal-logs-page .logs-filter-card form {
    padding: 19px 20px 20px;
}
 
.meal-logs-page .logs-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    align-items: end;
    gap: 14px 12px;
}
 
.meal-logs-page .logs-filter-grid .search-group {
    grid-column: span 2;
}
 
.meal-logs-page .diary-filter-group {
    min-width: 0;
}
 
.meal-logs-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}
 
.meal-logs-page .diary-control,
.meal-logs-page .diary-search-input {
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
 
.meal-logs-page .diary-control {
    padding: 0 10px;
}
 
.meal-logs-page .diary-control:focus,
.meal-logs-page .diary-search-input:focus-within {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}
 
.meal-logs-page .diary-search-input {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 11px;
}
 
.meal-logs-page .diary-search-input > i {
    flex: 0 0 auto;
    color: #929b8f;
    font-size: 12px;
}
 
.meal-logs-page .diary-search-input input {
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
 
.meal-logs-page .diary-search-input input::placeholder {
    color: #929b8f;
}
 
.meal-logs-page .diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
 
.meal-logs-page .diary-apply-btn,
.meal-logs-page .diary-reset-btn {
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
 
.meal-logs-page .diary-apply-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-logs-page .diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
}
 
.meal-logs-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: #fffefa;
}
 
.meal-logs-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}
 
/* ACTIVE FILTERS */
 
.meal-logs-page .logs-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 11px 20px;
    border-top: 1px solid #eeece3;
    background: #faf9f4;
}
 
.meal-logs-page .active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}
 
.meal-logs-page .active-filter-label {
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
 
.meal-logs-page .active-filter-label i {
    color: var(--green);
}
 
.meal-logs-page .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 240px;
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
 
.meal-logs-page .filter-chip i {
    color: var(--amber);
}
 
.meal-logs-page .filter-result-count {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
/* RECORDS PANEL */
 
.meal-logs-page .logs-table-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}
 
.meal-logs-page .logs-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-logs-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
 
.meal-logs-page .logs-header-tools {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 10px;
}
 
.meal-logs-page .logs-record-count {
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
 
.meal-logs-page .logs-record-count i,
.meal-logs-page .logs-record-count strong {
    color: var(--ink);
}
 
.meal-logs-page .logs-export-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
}
 
.meal-logs-page .logs-export-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 34px;
    padding: 0 11px;
    border: 1px solid #deded2;
    border-radius: 7px;
    color: #59675d;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}
 
.meal-logs-page .logs-export-btn:hover {
    transform: translateY(-1px);
}
 
.meal-logs-page .logs-export-btn.print:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}
 
.meal-logs-page .logs-export-btn.pdf:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}
 
.meal-logs-page .logs-export-btn.excel:hover {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
/* TABLE */
 
.meal-logs-page .logs-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
 
.meal-logs-page .logs-table {
    width: 100%;
    min-width: 1500px;
    margin: 0;
    border-collapse: collapse;
}
 
.meal-logs-page .logs-table thead th {
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
 
.meal-logs-page .logs-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}
 
.meal-logs-page .logs-table tbody tr {
    transition: background .18s ease;
}
 
.meal-logs-page .logs-table tbody tr:hover {
    background: #faf9f2;
}
 
.meal-logs-page .logs-table tbody tr:last-child td {
    border-bottom: none;
}
 
.meal-logs-page .logs-table .actions-heading {
    text-align: right;
}
 
.meal-logs-page .record-number {
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
 
/* DATE AND TIME */
 
.meal-logs-page .event-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
.meal-logs-page .event-date i {
    color: var(--blue);
}
 
.meal-logs-page .event-time {
    margin-top: 5px;
    color: #858e82;
    font-size: 10px;
    white-space: nowrap;
}
 
.meal-logs-page .event-time i {
    margin-right: 3px;
}
 
/* TRANSACTION ID */
 
.meal-logs-page .transaction-id {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 9px;
    border: 1px solid #e6e4d8;
    border-radius: 6px;
    color: #617065;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}
 
.meal-logs-page .transaction-id i {
    color: var(--blue);
}
 
/* MEAL ITEM */
 
.meal-logs-page .logs-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}
 
.meal-logs-page .logs-mini-icon {
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
 
.meal-logs-page .logs-details {
    min-width: 0;
}
 
.meal-logs-page .logs-name {
    max-width: 210px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}
 
.meal-logs-page .logs-type {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}
 
.meal-logs-page .logs-type i {
    color: var(--amber);
}
 
/* CATEGORY */
 
.meal-logs-page .category-badge {
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
 
/* MOVEMENT BADGE */
 
.meal-logs-page .movement-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}
 
.meal-logs-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}
 
.meal-logs-page .movement-badge.stock-in {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-logs-page .movement-badge.stock-in .status-dot {
    background: #3a8060;
}
 
.meal-logs-page .movement-badge.stock-out {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}
 
.meal-logs-page .movement-badge.stock-out .status-dot {
    background: #bd5148;
}
 
.meal-logs-page .movement-badge.other {
    border: 1px solid #d7e2e9;
    color: var(--blue);
    background: #f0f5f8;
}
 
.meal-logs-page .movement-badge.other .status-dot {
    background: var(--blue);
}
 
/* QUANTITY AND STOCK */
 
.meal-logs-page .quantity-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 9px;
    border: 1px solid #e6e4d8;
    border-radius: 6px;
    color: var(--ink);
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
 
.meal-logs-page .quantity-badge.quantity-in {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-logs-page .quantity-badge.quantity-out {
    border-color: #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}
 
.meal-logs-page .quantity-unit {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}
 
.meal-logs-page .stock-before,
.meal-logs-page .stock-after {
    font-size: 12px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
 
.meal-logs-page .stock-before { color: #647168; }
.meal-logs-page .stock-after  { color: var(--green); }
 
/* REASON AND USER */
 
.meal-logs-page .logs-reason {
    max-width: 190px;
    overflow: hidden;
    color: #647168;
    font-size: 11px;
    line-height: 1.6;
    text-overflow: ellipsis;
    white-space: nowrap;
}
 
.meal-logs-page .performed-by {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #647168;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
.meal-logs-page .performed-by i {
    color: var(--blue);
    font-size: 14px;
}
 
.meal-logs-page .diary-muted {
    color: #929a8e;
    font-size: 11px;
}
 
/* ACTION BUTTON */
 
.meal-logs-page .logs-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}
 
.meal-logs-page .game-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none !important;
    cursor: pointer;
}
 
.meal-logs-page .game-action-btn:hover {
    transform: translateY(-2px);
}
 
.meal-logs-page .game-action-btn.view:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}
 
/* EMPTY STATE */
 
.meal-logs-page .logs-empty-state {
    padding: 60px 20px;
    text-align: center;
}
 
.meal-logs-page .empty-state-icon {
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
 
.meal-logs-page .logs-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}
 
.meal-logs-page .logs-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}
 
/* PAGINATION */
 
.meal-logs-page .logs-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-logs-page .pagination-info {
    color: var(--muted);
    font-size: 11px;
}
 
.meal-logs-page .pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}
 
.meal-logs-page .logs-pagination nav {
    margin: 0;
}
 
.meal-logs-page .logs-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}
 
.meal-logs-page .logs-pagination .page-link {
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
 
.meal-logs-page .logs-pagination .page-link:hover {
    border-color: #bfcbbd;
    color: var(--ink-hover);
    background: #f5f4eb;
}
 
.meal-logs-page .logs-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-logs-page .logs-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}
 
/* FOOTER */
 
.meal-logs-page .logs-card-footer {
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
 
.meal-logs-page .logs-card-footer strong {
    color: var(--ink);
}
 
.meal-logs-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}
 
.meal-logs-page .footer-status i {
    color: var(--green);
}
 
/* PRINT HEADER (hidden on screen) */
 
.meal-logs-page .logs-print-header {
    display: none;
}
 
/* TABLET */
 
@media (max-width: 1250px) {
    .meal-logs-page .logs-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-logs-page .logs-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-logs-page .logs-filter-grid .search-group,
    .meal-logs-page .diary-filter-actions {
        grid-column: span 2;
    }
}
 
@media (max-width: 900px) {
    .meal-logs-page .logs-hero {
        grid-template-columns: 1fr;
    }
 
    .meal-logs-page .logs-hero-actions {
        min-height: 165px;
    }
 
    .meal-logs-page .logs-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }
 
    .meal-logs-page .logs-header-tools {
        justify-content: flex-start;
        width: 100%;
    }
}
 
/* MOBILE */
 
@media (max-width: 650px) {
    .meal-logs-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }
 
    .meal-logs-page .logs-hero::before {
        left: 22px;
    }
 
    .meal-logs-page .logs-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }
 
    .meal-logs-page .logs-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }
 
    .meal-logs-page .logs-hero-text h2 {
        font-size: 23px;
    }
 
    .meal-logs-page .logs-hero-text p {
        font-size: 12px;
    }
 
    .meal-logs-page .logs-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }
 
    .meal-logs-page .logs-hero-actions {
        min-height: 155px;
        padding: 20px;
    }
 
    .meal-logs-page .logs-kpis {
        gap: 15px;
        margin-top: 30px;
    }
 
    .meal-logs-page .logs-kpi {
        padding: 20px 16px 15px;
    }
 
    .meal-logs-page .kpi-value {
        font-size: 30px;
    }
 
    .meal-logs-page .filter-heading {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }
 
    .meal-logs-page .filter-heading h3 {
        font-size: 16px;
    }
 
    .meal-logs-page .reset-heading-btn {
        margin-left: 0;
    }
 
    .meal-logs-page .logs-filter-card form {
        padding: 15px;
    }
 
    .meal-logs-page .logs-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
 
    .meal-logs-page .logs-filter-grid .search-group,
    .meal-logs-page .diary-filter-actions {
        grid-column: auto;
    }
 
    .meal-logs-page .diary-filter-actions > * {
        flex: 1;
    }
 
    .meal-logs-page .logs-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 11px 15px;
    }
 
    .meal-logs-page .filter-result-count {
        white-space: normal;
    }
 
    .meal-logs-page .logs-panel-header {
        padding: 15px;
    }
 
    .meal-logs-page .panel-title-wrap h3 {
        font-size: 16px;
    }
 
    .meal-logs-page .logs-header-tools {
        align-items: stretch;
        flex-direction: column;
    }
 
    .meal-logs-page .logs-export-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
 
    .meal-logs-page .logs-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }
 
    .meal-logs-page .logs-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}
 
@media (max-width: 400px) {
    .meal-logs-page .logs-kpis {
        grid-template-columns: 1fr;
    }
 
    .meal-logs-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
 
    .meal-logs-page .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }
 
    .meal-logs-page .diary-apply-btn,
    .meal-logs-page .diary-reset-btn {
        width: 100%;
    }
 
    .meal-logs-page .active-filter-items {
        align-items: flex-start;
    }
 
    .meal-logs-page .filter-chip {
        max-width: 100%;
    }
 
    .meal-logs-page .logs-export-btn {
        flex-direction: column;
        gap: 3px;
        padding: 6px 4px;
    }
}
 
/* ACCESSIBILITY */
 
.meal-logs-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}
 
@media (prefers-reduced-motion: reduce) {
    .meal-logs-page *,
    .meal-logs-page *::before,
    .meal-logs-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
 
/* PRINT */
 
@media print {
    body * {
        visibility: hidden !important;
    }
 
    #mealLogsPrintArea,
    #mealLogsPrintArea * {
        visibility: visible !important;
    }
 
    #mealLogsPrintArea {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: #fff !important;
    }
 
    .meal-logs-page {
        min-height: 0;
        color: #222 !important;
        background: #fff !important;
    }
 
    .meal-logs-page .container-fluid {
        max-width: none;
        margin: 0 !important;
        padding: 0 !important;
    }
 
    .meal-logs-page .logs-table-card {
        overflow: visible;
        border: 0;
        border-radius: 0;
        box-shadow: none;
    }
 
    .meal-logs-page .logs-print-header {
        display: block !important;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #263b35;
        color: #222 !important;
        text-align: center;
    }
 
    .meal-logs-page .logs-print-header h2 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 700;
    }
 
    .meal-logs-page .logs-print-header p {
        margin: 0 0 5px;
        font-size: 11px;
    }
 
    .meal-logs-page .logs-print-header span {
        font-size: 10px;
    }
 
    .meal-logs-page .logs-table-wrapper {
        overflow: visible;
    }
 
    .meal-logs-page .logs-table {
        width: 100%;
        min-width: 0;
        table-layout: auto;
    }
 
    .meal-logs-page .logs-table thead {
        display: table-header-group;
    }
 
    .meal-logs-page .logs-table tr {
        break-inside: avoid;
    }
 
    .meal-logs-page .logs-table th,
    .meal-logs-page .logs-table td {
        padding: 5px 4px !important;
        border: 1px solid #bbb !important;
        color: #222 !important;
        background: #fff !important;
        font-size: 8px !important;
        overflow-wrap: anywhere;
    }
 
    .meal-logs-page .logs-table th:last-child,
    .meal-logs-page .logs-table td:last-child {
        display: none !important;
    }
 
    .meal-logs-page .logs-info {
        min-width: 0;
    }
 
    .meal-logs-page .logs-mini-icon,
    .meal-logs-page .logs-type,
    .meal-logs-page .status-dot,
    .meal-logs-page .transaction-id i,
    .meal-logs-page .category-badge i,
    .meal-logs-page .event-date i,
    .meal-logs-page .event-time i,
    .meal-logs-page .performed-by i {
        display: none !important;
    }
 
    .meal-logs-page .logs-name,
    .meal-logs-page .logs-reason,
    .meal-logs-page .performed-by {
        max-width: none;
        overflow: visible;
        color: #222 !important;
        white-space: normal;
    }
 
    .meal-logs-page .movement-badge,
    .meal-logs-page .category-badge,
    .meal-logs-page .transaction-id,
    .meal-logs-page .quantity-badge {
        padding: 0 !important;
        border: 0 !important;
        color: #222 !important;
        background: #fff !important;
        white-space: normal;
    }
 
    .meal-logs-page .logs-empty-state {
        padding: 20px 0;
    }
 
    @page {
        size: landscape;
        margin: 10mm;
    }
}
</style>
 
<script>
function printMealLogs() {
    window.print();
}
</script>
 
@endsection
