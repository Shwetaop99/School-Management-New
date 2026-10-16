@extends('layouts.app')

@section('title', 'Meal Stock Logs | Admin')
@section('page-title', 'Meal Stock Logs')

@section('content')
<div class="meal-logs-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="meal-alert meal-alert-success" role="status">
                <span class="meal-alert-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if(session('error'))
            <div class="meal-alert meal-alert-error" role="alert">
                <span class="meal-alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- LOCKED SCHOOL DIARY HERO --}}
        <section class="meal-logs-hero">
            <div class="meal-hero-content">
                <div class="meal-hero-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>

                <div class="meal-hero-text">
                    <div class="meal-breadcrumb">
                        <span>MEAL MANAGEMENT</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>INVENTORY AUDIT</span>
                    </div>

                    <h2>Meal Stock Logs</h2>

                    <p>
                        Track stock-in and stock-out transactions, review inventory
                        movement, and maintain a clear record of every stock change.
                    </p>

                    <div class="meal-hero-note">
                        <i class="fas fa-shield-halved"></i>
                        <span>Meal Inventory &amp; Transaction Audit</span>
                    </div>
                </div>
            </div>

            <div class="meal-hero-actions">
                <div class="meal-handwritten">Every Item. Every Movement.</div>

                <p>
                    Keep inventory records organised, accurate, and transparent.
                </p>

                <a href="{{ route('admin.admin.meal.items.index') }}"
                   class="meal-primary-btn">
                    <i class="fas fa-utensils"></i>
                    Manage Meal Items
                    <i class="fas fa-arrow-right meal-btn-arrow"></i>
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="meal-logs-kpis">

            <article class="meal-kpi blue">
                <div class="kpi-top">
                    <div>
                        <span class="kpi-eyebrow">INVENTORY</span>
                        <div class="kpi-label">Total Movements</div>
                    </div>
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

            <article class="meal-kpi green">
                <div class="kpi-top">
                    <div>
                        <span class="kpi-eyebrow">RECEIVED</span>
                        <div class="kpi-label">Stock In</div>
                    </div>
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

            <article class="meal-kpi orange">
                <div class="kpi-top">
                    <div>
                        <span class="kpi-eyebrow">ISSUED</span>
                        <div class="kpi-label">Stock Out</div>
                    </div>
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

            <article class="meal-kpi red">
                <div class="kpi-top">
                    <div>
                        <span class="kpi-eyebrow">ATTENTION</span>
                        <div class="kpi-label">Low Stock Items</div>
                    </div>
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

        {{-- MAIN PAPER PANEL --}}
        <section class="meal-logs-paper-panel">

            {{-- PANEL HEADER --}}
            <div class="meal-panel-header">
                <div class="meal-panel-title-wrap">
                    <div class="meal-panel-title-icon">
                        <i class="fas fa-book-open"></i>
                    </div>

                    <div>
                        <div class="meal-section-eyebrow">INVENTORY REGISTER</div>
                        <h3>Stock Movement History</h3>
                        <p>Review, search, and export meal inventory transactions.</p>
                    </div>
                </div>

                <div class="meal-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $logs->total() }}</strong>
                    <span>Records</span>
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.admin.meal.logs.index') }}"
                  class="meal-filter-panel">

                <div class="meal-filter-heading">
                    <div class="meal-filter-heading-icon">
                        <i class="fas fa-sliders"></i>
                    </div>

                    <div>
                        <h4>Search &amp; Filter Transactions</h4>
                        <p>Find records by keyword, date, item, category, or movement type.</p>
                    </div>
                </div>

                <div class="meal-filter-grid">

                    {{-- SEARCH --}}
                    <div class="meal-filter-group meal-search-group">
                        <label for="meal-log-search">Search Transactions</label>
                        <div class="meal-search-input">
                            <i class="fas fa-search"></i>
                            <input
                                id="meal-log-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Item, transaction ID or reason..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    {{-- DATE FROM --}}
                    <div class="meal-filter-group">
                        <label for="meal-date-from">Date From</label>
                        <input
                            id="meal-date-from"
                            type="date"
                            name="date_from"
                            class="meal-control"
                            value="{{ request('date_from') }}"
                        >
                    </div>

                    {{-- DATE TO --}}
                    <div class="meal-filter-group">
                        <label for="meal-date-to">Date To</label>
                        <input
                            id="meal-date-to"
                            type="date"
                            name="date_to"
                            class="meal-control"
                            value="{{ request('date_to') }}"
                        >
                    </div>

                    {{-- ITEM --}}
                    <div class="meal-filter-group">
                        <label for="meal-item-filter">Meal Item</label>
                        <select id="meal-item-filter"
                                name="meal_item_id"
                                class="meal-control">
                            <option value="">All Items</option>

                            @foreach($items as $item)
                                <option
                                    value="{{ $item->id }}"
                                    @selected((string) request('meal_item_id') === (string) $item->id)
                                >
                                    {{ $item->name ?? $item->item_name ?? 'Item #' . $item->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- CATEGORY --}}
                    <div class="meal-filter-group">
                        <label for="meal-category-filter">Category</label>
                        <select id="meal-category-filter"
                                name="category"
                                class="meal-control">
                            <option value="">All Categories</option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category }}"
                                    @selected((string) request('category') === (string) $category)
                                >
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- MOVEMENT TYPE --}}
                    <div class="meal-filter-group">
                        <label for="meal-type-filter">Movement Type</label>
                        <select id="meal-type-filter"
                                name="transaction_type"
                                class="meal-control">
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

                    {{-- FILTER BUTTONS --}}
                    <div class="meal-filter-actions">
                        <button type="submit" class="meal-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.admin.meal.logs.index') }}"
                           class="meal-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny([
                'search',
                'date_from',
                'date_to',
                'meal_item_id',
                'category',
                'transaction_type'
            ]))
                <div class="meal-active-filters">
                    <div class="meal-active-filter-items">
                        <span class="meal-active-filter-label">
                            <i class="fas fa-filter"></i>
                            Active Filters
                        </span>

                        @if(request('search'))
                            <span class="meal-filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request('date_from'))
                            <span class="meal-filter-chip">
                                <i class="fas fa-calendar"></i>
                                From: {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                            </span>
                        @endif

                        @if(request('date_to'))
                            <span class="meal-filter-chip">
                                <i class="fas fa-calendar-check"></i>
                                To: {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                            </span>
                        @endif

                        @if(request('meal_item_id'))
                            @php
                                $selectedMealItem = $items->firstWhere(
                                    'id',
                                    request('meal_item_id')
                                );
                            @endphp

                            <span class="meal-filter-chip">
                                <i class="fas fa-utensils"></i>
                                {{ $selectedMealItem->name ?? $selectedMealItem->item_name ?? 'Selected Item' }}
                            </span>
                        @endif

                        @if(request('category'))
                            <span class="meal-filter-chip">
                                <i class="fas fa-tags"></i>
                                {{ request('category') }}
                            </span>
                        @endif

                        @if(request('transaction_type'))
                            <span class="meal-filter-chip">
                                <i class="fas fa-exchange-alt"></i>
                                {{ request('transaction_type') === 'stock_in' ? 'Stock In' : 'Stock Out' }}
                            </span>
                        @endif
                    </div>

                    <span class="meal-filter-result-count">
                        {{ $logs->total() }} result(s) found
                    </span>
                </div>
            @endif

            {{-- PRINTABLE AREA --}}
            <div id="mealLogsPrintArea">

                <div class="meal-print-header">
                    <h2>Meal Stock Movement Report</h2>
                    <p>School Meal Management — Inventory Transaction History</p>
                    <span>Generated on {{ now()->format('d M Y, h:i A') }}</span>
                </div>

                {{-- RESULTS HEADER --}}
                <div class="meal-results-heading">
                    <div>
                        <div class="meal-section-eyebrow">TRANSACTION REGISTER</div>
                        <h4>
                            <i class="fas fa-clock-rotate-left"></i>
                            Transaction Records
                        </h4>
                        <p>Stock movement history and inventory audit details.</p>
                    </div>

                    <div class="meal-export-actions">
                        <button type="button"
                                class="meal-export-btn print"
                                onclick="printMealLogs()">
                            <i class="fas fa-print"></i>
                            Print
                        </button>

                        <a href="{{ route('admin.admin.meal.logs.pdf', request()->query()) }}"
                           class="meal-export-btn pdf">
                            <i class="fas fa-file-pdf"></i>
                            PDF
                        </a>

                        <a href="{{ route('admin.admin.meal.logs.excel', request()->query()) }}"
                           class="meal-export-btn excel">
                            <i class="fas fa-file-excel"></i>
                            Excel
                        </a>
                    </div>
                </div>

                {{-- TRANSACTION TABLE --}}
                <div class="meal-table-wrapper">
                    <table class="meal-logs-table">
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
                                <th class="meal-actions-heading">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $movementType = strtolower((string) ($log->action ?? ''));

                                    $isStockIn = in_array(
                                        $movementType,
                                        ['stock_in', 'in', 'stock-in'],
                                        true
                                    );

                                    $isStockOut = in_array(
                                        $movementType,
                                        ['stock_out', 'out', 'stock-out'],
                                        true
                                    );

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
                                        <span class="meal-row-number">
                                            {{ $logs->firstItem() + $loop->index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="meal-log-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $log->created_at ? $log->created_at->format('d M Y') : '—' }}
                                        </div>

                                        @if($log->created_at)
                                            <div class="meal-log-time">
                                                <i class="far fa-clock"></i>
                                                {{ $log->created_at->format('h:i A') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if($log->stock_transaction_id)
                                            <span class="meal-transaction-id">
                                                <i class="fas fa-hashtag"></i>
                                                {{ $log->stock_transaction_id }}
                                            </span>
                                        @else
                                            <span class="meal-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="meal-item-info">
                                            <span class="meal-item-avatar">
                                                <i class="fas fa-utensils"></i>
                                            </span>

                                            <div class="meal-item-details">
                                                <span class="meal-item-name">
                                                    {{ $log->mealItem->name
                                                        ?? $log->mealItem->item_name
                                                        ?? 'Deleted / Unavailable Item' }}
                                                </span>

                                                @if($log->unit)
                                                    <span class="meal-item-unit">
                                                        Unit: {{ $log->unit }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($log->mealItem && $log->mealItem->category)
                                            <span class="meal-category-badge">
                                                <i class="fas fa-tag"></i>
                                                {{ $log->mealItem->category }}
                                            </span>
                                        @else
                                            <span class="meal-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="meal-movement-badge {{ $isStockIn ? 'stock-in' : ($isStockOut ? 'stock-out' : 'other') }}">
                                            <span class="movement-dot"></span>
                                            {{ $displayMovement }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="meal-quantity {{ $isStockIn ? 'quantity-in' : ($isStockOut ? 'quantity-out' : '') }}">
                                            {{ $isStockIn ? '+' : ($isStockOut ? '−' : '') }}{{ number_format((float) $log->quantity, 2, '.', '') }}
                                        </span>

                                        @if($log->unit)
                                            <span class="meal-quantity-unit">{{ $log->unit }}</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="meal-stock-value">
                                            {{ $log->previous_stock !== null
                                                ? number_format((float) $log->previous_stock, 2, '.', '')
                                                : '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="meal-stock-after">
                                            {{ $log->updated_stock !== null
                                                ? number_format((float) $log->updated_stock, 2, '.', '')
                                                : '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="meal-reason" title="{{ $log->reason ?? '' }}">
                                            {{ $log->reason ?: 'Not specified' }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($log->performed_by)
                                            <span class="meal-performed-by">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $log->performed_by }}
                                            </span>
                                        @else
                                            <span class="meal-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="meal-row-actions">
                                            <a href="{{ route('admin.admin.meal.logs.show', $log) }}"
                                               class="meal-action-btn view"
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
                                        <div class="meal-empty-state">
                                            <div class="meal-empty-icon">
                                                <i class="fas fa-box-open"></i>
                                            </div>

                                            <div class="meal-empty-eyebrow">NO MATCHING RECORDS</div>
                                            <h4>No Stock Transactions Found</h4>

                                            <p>
                                                No stock movement records match your current filters.
                                                Try changing your search criteria or resetting the filters.
                                            </p>

                                            <a href="{{ route('admin.admin.meal.logs.index') }}"
                                               class="meal-primary-btn">
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
                <div class="meal-pagination">
                    <div class="meal-pagination-info">
                        Showing
                        <strong>{{ $logs->firstItem() }}</strong>
                        to
                        <strong>{{ $logs->lastItem() }}</strong>
                        of
                        <strong>{{ $logs->total() }}</strong>
                        records
                    </div>

                    <div class="meal-pagination-links">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                </div>
            @endif

            {{-- PANEL FOOTER --}}
            @if($logs->total() > 0)
                <div class="meal-card-footer">
                    <div>
                        Showing <strong>{{ $logs->count() }}</strong> record(s) on this page
                    </div>

                    <div class="meal-footer-status">
                        <i class="fas fa-circle-check"></i>
                        Transaction history loaded
                    </div>
                </div>
            @endif

        </section>
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&family=Patrick+Hand&display=swap');

/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   Meal Stock Logs
   Warm paper • Forest ink • Earthy accents
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
    --cream: #f1ead8;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #34775a;
    --red: #bd5148;
    --cyan: #527f79;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #faf9f4;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
}

.meal-logs-page *,
.meal-logs-page *::before,
.meal-logs-page *::after {
    box-sizing: border-box;
}

.meal-logs-page a,
.meal-logs-page button {
    transition: color .18s ease, background .18s ease, border-color .18s ease,
                transform .18s ease, box-shadow .18s ease;
}

.meal-logs-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.meal-logs-hero,
.meal-logs-paper-panel {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.meal-logs-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.45fr) minmax(280px, .9fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 32px;
    border-radius: 10px;
    box-shadow: 0 5px 16px rgba(38, 59, 53, .045);
}

.meal-logs-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
    pointer-events: none;
}

.meal-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
}

.meal-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 10px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 21px;
}

.meal-hero-text {
    min-width: 0;
}

.meal-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 9px;
    color: #899187;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .85px;
}

.meal-breadcrumb i {
    color: #b4b9ac;
    font-size: 8px;
}

.meal-breadcrumb span:last-child {
    color: var(--green);
}

.meal-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 29px;
    font-weight: 400;
    line-height: 1.2;
    letter-spacing: -.3px;
}

.meal-hero-text p {
    max-width: 560px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.meal-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.meal-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 190px;
    padding: 26px;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.meal-handwritten {
    color: var(--cream);
    font-family: 'Patrick Hand', cursive;
    font-size: 25px;
    line-height: 1.2;
}

.meal-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d0d8cd;
    font-size: 11px;
    line-height: 1.8;
}

.meal-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--cream);
    border-radius: 7px;
    color: var(--ink) !important;
    background: var(--cream);
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.meal-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #e8dfc9;
    color: var(--ink-dark) !important;
    background: #e8dfc9;
    box-shadow: 0 6px 15px rgba(0, 0, 0, .12);
}

.meal-btn-arrow {
    margin-left: 3px;
    font-size: 10px;
}

/* ALERTS */

.meal-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
}

.meal-alert-success {
    border: 1px solid #c9e4cf;
    color: #347849;
    background: #f0f8ef;
}

.meal-alert-error {
    border: 1px solid #ecd0c8;
    color: #a94438;
    background: #fff3ef;
}

.meal-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 28px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
}

.meal-alert-success .meal-alert-icon {
    background: #dceedd;
}

.meal-alert-error .meal-alert-icon {
    background: #f9dfd8;
}

/* KPI CARDS */

.meal-logs-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.meal-kpi {
    --kpi-color: var(--blue);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    padding: 22px 20px 16px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(29, 48, 43, .035);
}

.meal-kpi::before {
    content: "";
    position: absolute;
    top: -24px;
    left: 15px;
    width: 86px;
    height: 23px;
    border-radius: 6px 6px 0 0;
    background: var(--kpi-color);
    opacity: .96;
}

.meal-kpi.blue { --kpi-color: #2b5d8a; }
.meal-kpi.green { --kpi-color: #34775a; }
.meal-kpi.orange { --kpi-color: #a87543; }
.meal-kpi.red { --kpi-color: #bd5148; }

.meal-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(29, 48, 43, .08);
}

.meal-logs-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.meal-kpi .kpi-eyebrow {
    display: block;
    margin-bottom: 5px;
    color: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .85px;
}

.meal-logs-page .kpi-label {
    color: #657168;
    font-size: 12px;
    font-weight: 600;
}

.meal-logs-page .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border-radius: 9px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 11%, white);
    font-size: 17px;
}

.meal-logs-page .kpi-value {
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 34px;
    font-weight: 400;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.meal-logs-page .kpi-caption {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    padding-top: 12px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.65;
}

.meal-kpi .kpi-caption i {
    margin-top: 2px;
    color: var(--kpi-color);
}

/* MAIN PAPER PANEL */

.meal-logs-paper-panel {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(29, 48, 43, .04);
}

.meal-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 22px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.meal-panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-panel-title-icon,
.meal-filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dbe4da;
    border-radius: 9px;
    color: var(--green);
    background: #eff3e9;
    font-size: 15px;
}

.meal-section-eyebrow {
    margin-bottom: 5px;
    color: #899187;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .85px;
}

.meal-panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 20px;
    font-weight: 400;
}

.meal-panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.meal-record-count {
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
    white-space: nowrap;
}

.meal-record-count i,
.meal-record-count strong {
    color: var(--green);
}

.meal-record-count strong {
    font-size: 12px;
}

/* FILTERS */

.meal-filter-panel {
    padding: 20px;
    border-bottom: 1px solid var(--line);
    background: #fffefa;
}

.meal-filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #d8d1bf;
}

.meal-filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 19px;
    font-weight: 400;
}

.meal-filter-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.meal-filter-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    align-items: end;
    gap: 15px 12px;
}

.meal-filter-group {
    min-width: 0;
}

.meal-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-control,
.meal-search-input {
    width: 100%;
    min-width: 0;
    height: 42px;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.meal-control {
    padding: 0 11px;
}

.meal-control:hover,
.meal-search-input:hover {
    border-color: #bfcbbd;
}

.meal-control:focus,
.meal-search-input:focus-within {
    border-color: #739985;
    box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
}

.meal-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 12px;
}

.meal-search-input > i {
    flex: 0 0 auto;
    color: #899187;
    font-size: 12px;
}

.meal-search-input input {
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

.meal-search-input input::placeholder {
    color: #929a8e;
}

.meal-filter-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.meal-apply-btn,
.meal-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 0 14px;
    border: 1px solid var(--ink);
    border-radius: 7px;
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.meal-apply-btn {
    color: #fff;
    background: var(--ink);
}

.meal-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.meal-reset-btn {
    border-color: #dedfd4;
    color: #5f6c62;
    background: #fffefa;
}

.meal-reset-btn:hover {
    border-color: #bfcbbd;
    color: var(--ink);
    background: #f5f4ec;
}

/* ACTIVE FILTERS */

.meal-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid #eeece3;
    background: #fcfbf6;
}

.meal-active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}

.meal-active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: var(--muted);
    font-size: 9px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .45px;
}

.meal-active-filter-label i {
    color: var(--green);
}

.meal-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 240px;
    padding: 5px 9px;
    border: 1px solid #e6dfc9;
    border-radius: 6px;
    color: #765e32;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 600;
    overflow-wrap: anywhere;
}

.meal-filter-chip i {
    color: var(--amber);
}

.meal-filter-result-count {
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* RESULTS HEADER */

.meal-results-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #eeece3;
    background: #fffefa;
}

.meal-results-heading h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 19px;
    font-weight: 400;
}

.meal-results-heading h4 i {
    color: var(--green);
    font-size: 15px;
}

.meal-results-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.meal-export-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
}

.meal-export-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 0 11px;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #5f6c62;
    background: #fffefa;
    font-family: 'Inter', sans-serif;
    font-size: 10px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
}

.meal-export-btn:hover {
    transform: translateY(-1px);
}

.meal-export-btn.print:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}

.meal-export-btn.pdf:hover {
    border-color: #e7c7c0;
    color: var(--red);
    background: #fff1ed;
}

.meal-export-btn.excel:hover {
    border-color: #cfe1ce;
    color: var(--green);
    background: #edf5e9;
}

/* TABLE */

.meal-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.meal-logs-table {
    width: 100%;
    min-width: 1500px;
    margin: 0;
    border-collapse: collapse;
}

.meal-logs-table thead th {
    padding: 13px 12px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f4ec;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.meal-logs-table tbody td {
    padding: 13px 12px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.meal-logs-table tbody tr {
    transition: background .18s ease;
}

.meal-logs-table tbody tr:hover {
    background: #fafaf4;
}

.meal-logs-table tbody tr:last-child td {
    border-bottom: 0;
}

.meal-logs-table .meal-actions-heading {
    text-align: right;
}

.meal-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid #e4e2d6;
    border-radius: 7px;
    color: #667269;
    background: #f5f4ec;
    font-size: 10px;
    font-weight: 800;
}

/* DATE AND TIME */

.meal-log-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #34423d;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-log-date i {
    color: var(--blue);
}

.meal-log-time {
    margin-top: 5px;
    color: #858e82;
    font-size: 10px;
    white-space: nowrap;
}

.meal-log-time i {
    margin-right: 3px;
}

/* TRANSACTION ID */

.meal-transaction-id {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 8px;
    border: 1px solid #e0e2d8;
    border-radius: 6px;
    color: #617065;
    background: #f8f8f1;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.meal-transaction-id i {
    color: var(--blue);
}

/* ITEM */

.meal-item-info {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 180px;
}

.meal-item-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 35px;
    width: 35px;
    height: 35px;
    border: 1px solid #d7e2d7;
    border-radius: 8px;
    color: var(--green);
    background: #eff3e9;
    font-size: 12px;
}

.meal-item-details {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.meal-item-name {
    max-width: 190px;
    overflow: hidden;
    color: #26372f;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.meal-item-unit {
    color: #858e82;
    font-size: 10px;
}

/* CATEGORY */

.meal-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 8px;
    border: 1px solid #e6dfc9;
    border-radius: 6px;
    color: #8b641d;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-category-badge i {
    color: var(--amber);
}

/* MOVEMENT BADGES */

.meal-movement-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.meal-movement-badge .movement-dot {
    flex: 0 0 6px;
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.meal-movement-badge.stock-in {
    border: 1px solid #cfe1ce;
    color: #347849;
    background: #edf5e9;
}

.meal-movement-badge.stock-in .movement-dot {
    background: #34775a;
}

.meal-movement-badge.stock-out {
    border: 1px solid #ecd2cb;
    color: #a9443d;
    background: #fff1ed;
}

.meal-movement-badge.stock-out .movement-dot {
    background: #bd5148;
}

.meal-movement-badge.other {
    border: 1px solid #d7e2e9;
    color: var(--blue);
    background: #f0f5f8;
}

.meal-movement-badge.other .movement-dot {
    background: var(--blue);
}

/* QUANTITY AND STOCK */

.meal-quantity {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 8px;
    border-radius: 6px;
    color: var(--ink);
    background: #f0f0e8;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.meal-quantity.quantity-in {
    color: #347849;
    background: #edf5e9;
}

.meal-quantity.quantity-out {
    color: #a9443d;
    background: #fff1ed;
}

.meal-quantity-unit {
    display: block;
    margin-top: 4px;
    color: #858e82;
    font-size: 9px;
}

.meal-stock-value,
.meal-stock-after {
    color: #657269;
    font-size: 11px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

.meal-stock-after {
    color: var(--green);
}

/* REASON AND USER */

.meal-reason {
    max-width: 180px;
    overflow: hidden;
    color: #68746a;
    font-size: 10px;
    line-height: 1.6;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.meal-performed-by {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #647168;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-performed-by i {
    color: var(--blue);
    font-size: 14px;
}

.meal-muted {
    color: #929a8e;
    font-size: 11px;
}

/* ACTION BUTTON */

.meal-row-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.meal-action-btn {
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
    text-decoration: none;
    cursor: pointer;
}

.meal-action-btn:hover {
    transform: translateY(-2px);
}

.meal-action-btn.view:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}

/* EMPTY STATE */

.meal-empty-state {
    padding: 58px 20px;
    text-align: center;
}

.meal-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    margin: 0 auto 17px;
    border: 1px solid #e5dfcc;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 26px;
}

.meal-empty-eyebrow {
    margin-bottom: 7px;
    color: #899187;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .9px;
}

.meal-empty-state h4 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: 'DM Serif Display', Georgia, serif;
    font-size: 23px;
    font-weight: 400;
}

.meal-empty-state p {
    max-width: 460px;
    margin: 0 auto 19px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.8;
}

/* PAGINATION */

.meal-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}

.meal-pagination-info {
    color: var(--muted);
    font-size: 10px;
}

.meal-pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.meal-pagination-links nav {
    margin: 0;
}

.meal-pagination-links .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.meal-pagination-links .page-link {
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
    font-family: 'Inter', sans-serif;
    font-size: 11px;
    box-shadow: none;
}

.meal-pagination-links .page-link:hover {
    border-color: #bfcbbd;
    color: var(--green);
    background: #f5f5eb;
}

.meal-pagination-links .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.meal-pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* PANEL FOOTER */

.meal-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 52px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
}

.meal-card-footer strong {
    color: var(--ink);
}

.meal-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.meal-footer-status i {
    color: var(--green);
}

/* PRINT HEADER */

.meal-print-header {
    display: none;
}

/* TABLET */

@media (max-width: 1200px) {
    .meal-logs-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .meal-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .meal-logs-hero {
        grid-template-columns: 1fr;
    }

    .meal-hero-actions {
        min-height: 165px;
    }

    .meal-results-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .meal-export-actions {
        width: 100%;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .meal-logs-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .meal-logs-hero::before {
        left: 22px;
    }

    .meal-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .meal-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 17px;
    }

    .meal-hero-text h2 {
        font-size: 24px;
    }

    .meal-hero-text p {
        font-size: 11px;
    }

    .meal-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .meal-hero-actions {
        min-height: 150px;
        padding: 20px;
    }

    .meal-handwritten {
        font-size: 23px;
    }

    .meal-logs-kpis {
        gap: 15px;
        margin-top: 30px;
    }

    .meal-kpi {
        padding: 20px 17px 15px;
    }

    .meal-logs-page .kpi-value {
        font-size: 30px;
    }

    .meal-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .meal-panel-title-wrap h3 {
        font-size: 18px;
    }

    .meal-panel-title-wrap p {
        font-size: 10px;
    }

    .meal-record-count {
        gap: 5px;
        padding: 5px 8px;
        font-size: 9px;
    }

    .meal-filter-panel {
        padding: 15px;
    }

    .meal-filter-heading h4 {
        font-size: 17px;
    }

    .meal-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .meal-filter-actions {
        align-items: stretch;
    }

    .meal-filter-actions > * {
        flex: 1;
    }

    .meal-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px 15px;
    }

    .meal-filter-result-count {
        white-space: normal;
    }

    .meal-results-heading {
        padding: 15px;
    }

    .meal-export-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .meal-export-btn {
        gap: 5px;
        padding: 0 7px;
        font-size: 9px;
    }

    .meal-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .meal-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .meal-logs-kpis {
        grid-template-columns: 1fr;
    }

    .meal-panel-header {
        gap: 8px;
    }

    .meal-record-count {
        gap: 4px;
        padding: 5px 7px;
    }

    .meal-panel-title-icon,
    .meal-filter-heading-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
    }

    .meal-filter-actions {
        flex-direction: column;
    }

    .meal-apply-btn,
    .meal-reset-btn {
        width: 100%;
    }

    .meal-active-filter-items {
        align-items: flex-start;
    }

    .meal-filter-chip {
        max-width: 100%;
    }

    .meal-export-btn {
        flex-direction: column;
        gap: 3px;
        padding: 6px 4px;
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
        padding: 0;
        background: #fff !important;
    }

    .meal-logs-page {
        min-height: 0;
        color: #222 !important;
        background: #fff !important;
    }

    .meal-logs-page .container-fluid {
        max-width: none;
        padding: 0 !important;
        margin: 0 !important;
    }

    .meal-logs-hero,
    .meal-logs-kpis,
    .meal-logs-paper-panel > .meal-panel-header,
    .meal-filter-panel,
    .meal-active-filters,
    .meal-results-heading,
    .meal-pagination,
    .meal-card-footer,
    .meal-row-actions {
        display: none !important;
    }

    .meal-logs-paper-panel {
        overflow: visible;
        border: 0;
        border-radius: 0;
        box-shadow: none;
    }

    .meal-print-header {
        display: block !important;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid #263b35;
        color: #222 !important;
        text-align: center;
    }

    .meal-print-header h2 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 700;
    }

    .meal-print-header p {
        margin: 0 0 5px;
        font-size: 11px;
    }

    .meal-print-header span {
        font-size: 10px;
    }

    .meal-table-wrapper {
        overflow: visible;
    }

    .meal-logs-table {
        width: 100%;
        min-width: 0;
        table-layout: auto;
        border-collapse: collapse;
    }

    .meal-logs-table thead {
        display: table-header-group;
    }

    .meal-logs-table tr {
        break-inside: avoid;
    }

    .meal-logs-table th,
    .meal-logs-table td {
        padding: 5px 4px !important;
        border: 1px solid #bbb !important;
        color: #222 !important;
        background: #fff !important;
        font-size: 8px !important;
        overflow-wrap: anywhere;
    }

    .meal-logs-table th {
        font-weight: 700;
    }

    .meal-item-info {
        min-width: 0;
    }

    .meal-item-avatar,
    .meal-item-unit,
    .meal-transaction-id i,
    .meal-category-badge i,
    .meal-log-date i,
    .meal-log-time i,
    .meal-performed-by i {
        display: none !important;
    }

    .meal-item-name,
    .meal-reason,
    .meal-performed-by {
        max-width: none;
        overflow: visible;
        color: #222 !important;
        white-space: normal;
    }

    .meal-movement-badge,
    .meal-category-badge,
    .meal-transaction-id,
    .meal-quantity {
        padding: 0 !important;
        border: 0 !important;
        color: #222 !important;
        background: #fff !important;
        white-space: normal;
    }

    .meal-empty-state {
        padding: 20px 0;
    }

    @page {
        size: landscape;
        margin: 10mm;
    }
}

/* ACCESSIBILITY */

@media (prefers-reduced-motion: reduce) {
    .meal-logs-page *,
    .meal-logs-page *::before,
    .meal-logs-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
function printMealLogs() {
    window.print();
}
</script>
@endsection
