@extends('layouts.app')

@section('title', 'Meal Items | Admin')
@section('page-title', 'Meal Items')

@section('content')

<div class="meal-items-page">
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
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="meal-alert meal-alert-error" role="alert">
                <i class="fas fa-circle-exclamation"></i>
                <div>
                    <strong>Please check the following:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="meal-hero">

            <div class="meal-hero-content">
                <div class="meal-hero-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div class="meal-hero-text">
                    <span class="meal-eyebrow">SCHOOL OPERATIONS / INVENTORY</span>

                    <h2>Meal Items</h2>

                    <p>
                        Manage school meal inventory, monitor available stock,
                        and organise stock-in and stock-out activities.
                    </p>

                    <div class="meal-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Meal Inventory Management</span>
                    </div>
                </div>
            </div>

            <div class="meal-hero-actions">
                <div class="meal-handwritten">
                    Manage. Monitor. Maintain.
                </div>

                <p>
                    Keep inventory organised, track stock movements,
                    and identify items that need replenishment.
                </p>

                <div class="meal-hero-buttons">
                    <a href="{{ route('admin.admin.meal.items.stock.create') }}"
                       class="meal-primary-btn">
                        <i class="fas fa-right-left"></i>
                        Stock In / Out
                    </a>

                    <a href="{{ route('admin.admin.meal.items.create') }}"
                       class="meal-secondary-btn">
                        <i class="fas fa-plus"></i>
                        Add Meal Item
                    </a>
                </div>
            </div>

        </section>

        {{-- COLLECTIONS AND STATISTICS --}}
        @php
            $itemsCollection = $items ?? collect();

            $transactionsCollection = collect();

            if (isset($transactions)) {
                $transactionsCollection = method_exists($transactions, 'getCollection')
                    ? $transactions->getCollection()
                    : collect($transactions);
            }

            $totalItems = isset($items) && method_exists($items, 'total')
                ? $items->total()
                : $itemsCollection->count();

            $activeItems = $itemsCollection
                ->where('status', 'active')
                ->count();

            $lowStockItems = $itemsCollection
                ->filter(function ($item) {
                    return (float) ($item->current_stock ?? 0)
                        <= (float) ($item->minimum_stock ?? 0);
                })
                ->count();

            $stockInCount = $transactionsCollection
                ->where('transaction_type', 'stock_in')
                ->count();

            $stockOutCount = $transactionsCollection
                ->where('transaction_type', 'stock_out')
                ->count();

            $stockMovementCount = $stockInCount + $stockOutCount;
        @endphp

        {{-- KPI CARDS --}}
        <section class="meal-kpis">

            <article class="meal-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Meal Items</span>
                    <span class="kpi-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalItems }}</div>

                <div class="kpi-caption">
                    All registered inventory items
                </div>
            </article>

            <article class="meal-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Items</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $activeItems }}</div>

                <div class="kpi-caption">
                    Currently available items
                </div>
            </article>

            <article class="meal-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Low Stock</span>
                    <span class="kpi-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $lowStockItems }}</div>

                <div class="kpi-caption">
                    Items requiring attention
                </div>
            </article>

            <article class="meal-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Stock Movements</span>
                    <span class="kpi-icon">
                        <i class="fas fa-right-left"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $stockMovementCount }}</div>

                <div class="kpi-caption">
                    Stock-in and stock-out records
                </div>
            </article>

        </section>

        {{-- MAIN INVENTORY PANEL --}}
        <section class="meal-paper-panel">

            <div class="meal-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-bowl-food"></i>
                    </div>

                    <div>
                        <span class="meal-section-kicker">INVENTORY REGISTER</span>
                        <h3>Meal Inventory</h3>
                        <p>View and manage all school meal inventory items.</p>
                    </div>
                </div>

                <div class="meal-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalItems }}</strong>
                    {{ $totalItems == 1 ? 'Item' : 'Items' }}
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.admin.meal.items.index') }}"
                  class="meal-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>
                        <h4>Search &amp; Filter Items</h4>
                        <p>Find inventory items by name, category, or status.</p>
                    </div>
                </div>

                <div class="meal-filter-grid">

                    <div class="diary-filter-group">
                        <label for="meal-search">Search Meal Items</label>

                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>

                            <input
                                id="meal-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Item name or category..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="meal-status">Item Status</label>

                        <select id="meal-status"
                                name="status"
                                class="diary-control">
                            <option value="">All Statuses</option>

                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="meal-filter-actions">
                        <button type="submit" class="meal-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.admin.meal.items.index') }}"
                           class="meal-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny(['search', 'status']))
                <div class="meal-active-filters">
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

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="fas fa-circle-half-stroke"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ $totalItems }}
                        {{ $totalItems == 1 ? 'result' : 'results' }}
                    </span>
                </div>
            @endif

            {{-- INVENTORY TABLE --}}
            <div class="meal-table-wrapper">
                <table class="meal-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Meal Item</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Minimum Stock</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($itemsCollection as $item)

                            @php
                                $currentStock = (float) ($item->current_stock ?? 0);
                                $minimumStock = (float) ($item->minimum_stock ?? 0);

                                $isLowStock = $currentStock <= $minimumStock;
                                $isActive = $item->status === 'active';
                            @endphp

                            <tr>
                                <td>
                                    <span class="meal-row-number">
                                        @if(isset($items) && method_exists($items, 'firstItem'))
                                            {{ $items->firstItem() + $loop->index }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <div class="meal-item-info">
                                        <div class="meal-item-avatar">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>

                                        <div class="meal-item-details">
                                            <div class="meal-item-title">
                                                {{ $item->item_name }}
                                            </div>

                                            <div class="meal-item-unit">
                                                <i class="fas fa-scale-balanced"></i>
                                                {{ $item->unit ?: 'Unit not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($item->category)
                                        <span class="meal-category-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $item->category }}
                                        </span>
                                    @else
                                        <span class="meal-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="meal-stock-value">
                                        <strong class="{{ $isLowStock ? 'low' : 'normal' }}">
                                            {{ number_format($currentStock, 2) }}
                                        </strong>
                                        <span>{{ $item->unit }}</span>
                                    </div>

                                    @if($isLowStock)
                                        <div class="meal-low-stock-label">
                                            <i class="fas fa-triangle-exclamation"></i>
                                            Low Stock
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="meal-minimum-stock">
                                        {{ number_format($minimumStock, 2) }}
                                        {{ $item->unit }}
                                    </span>
                                </td>

                                <td>
                                    <span class="meal-status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="meal-actions">
                                        <a
                                            href="{{ route('admin.admin.meal.items.stock.create') }}"
                                            class="meal-action-btn stock"
                                            title="Stock In / Stock Out"
                                            aria-label="Stock in or stock out">
                                            <i class="fas fa-right-left"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.admin.meal.items.edit', $item) }}"
                                            class="meal-action-btn edit"
                                            title="Edit Meal Item"
                                            aria-label="Edit meal item">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.admin.meal.items.destroy', $item) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this meal item?');">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="meal-action-btn delete"
                                                title="Delete Meal Item"
                                                aria-label="Delete meal item">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="meal-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>

                                        <h4>No Meal Items Found</h4>

                                        <p>
                                            No meal items match your current filters.
                                            Add your first inventory item to get started.
                                        </p>

                                        <a href="{{ route('admin.admin.meal.items.create') }}"
                                           class="meal-primary-btn">
                                            <i class="fas fa-plus"></i>
                                            Add Meal Item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($items) && method_exists($items, 'hasPages') && $items->hasPages())
                <div class="meal-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $items->firstItem() }}</strong>
                        to
                        <strong>{{ $items->lastItem() }}</strong>
                        of
                        <strong>{{ $items->total() }}</strong>
                        items
                    </div>

                    <div>
                        {{ $items->withQueryString()->links() }}
                    </div>
                </div>
            @endif

            {{-- PANEL FOOTER --}}
            @if($totalItems > 0)
                <div class="meal-card-footer">
                    <div>
                        Showing
                        <strong>{{ $itemsCollection->count() }}</strong>
                        item(s) on this page
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Inventory loaded successfully
                    </div>
                </div>
            @endif

        </section>

        {{-- RECENT STOCK MOVEMENTS --}}
        @if(isset($transactions))
            <section class="meal-paper-panel meal-movements-panel">

                <div class="meal-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon movements-icon">
                            <i class="fas fa-right-left"></i>
                        </div>

                        <div>
                            <span class="meal-section-kicker">TRANSACTION HISTORY</span>
                            <h3>Recent Stock Movements</h3>
                            <p>Review recent stock-in and stock-out activity.</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.admin.meal.items.stock.index') }}"
                       class="meal-view-all">
                        View All
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="meal-table-wrapper">
                    <table class="meal-table movements-table">
                        <thead>
                            <tr>
                                <th>Meal Item</th>
                                <th>Transaction Type</th>
                                <th>Quantity</th>
                                <th>Date</th>
                                <th>Supplier / Reason</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="meal-item-info">
                                            <div class="meal-item-avatar">
                                                <i class="fas fa-bowl-food"></i>
                                            </div>

                                            <div class="meal-item-details">
                                                <div class="meal-item-title">
                                                    {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                </div>

                                                <div class="meal-item-unit">
                                                    {{ $transaction->mealItem->category ?? 'Uncategorized' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($transaction->transaction_type === 'stock_in')
                                            <span class="meal-transaction-badge stock-in">
                                                <i class="fas fa-arrow-down"></i>
                                                Stock In
                                            </span>
                                        @else
                                            <span class="meal-transaction-badge stock-out">
                                                <i class="fas fa-arrow-up"></i>
                                                Stock Out
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="movement-quantity">
                                            <strong>
                                                {{ number_format((float) $transaction->quantity, 2) }}
                                            </strong>
                                            <span>{{ $transaction->unit }}</span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="movement-date">
                                            {{ $transaction->transaction_date
                                                ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y')
                                                : '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($transaction->transaction_type === 'stock_in')
                                            {{ $transaction->supplier ?: '—' }}
                                        @else
                                            {{ $transaction->reason ?: '—' }}
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="meal-movement-empty">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-right-left"></i>
                                            </div>

                                            <strong>No stock movements recorded yet.</strong>

                                            <span>Stock transactions will appear here.</span>

                                            <a href="{{ route('admin.admin.meal.items.stock.create') }}"
                                               class="meal-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add Stock Movement
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
                    <div class="meal-pagination">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                @endif

            </section>
        @endif

    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Inter:wght@400;500;600;700;800&display=swap');

/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL ITEMS — PAGE-SCOPED STYLES
========================================================= */

.meal-items-page {
    --ink: #263b35;
    --ink-dark: #20352c;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #788078;
    --line: #e5e1d5;
    --paper: #fffefa;
    --page: #f7f6f0;
    --page-soft: #f5f3e9;
    --chalk: #f1ead8;
    --chalk-hover: #e8dfc9;
    --wood: #a87543;
    --blue: #2b5d8a;
    --green: #43815b;
    --amber: #b77a24;
    --red: #bd5148;
    --cyan: #527f79;
    --hand: 'DM Serif Display', Georgia, serif;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--page);
    font-family: 'Inter', 'Segoe UI', sans-serif;
    font-size: 13px;
}

.meal-items-page *,
.meal-items-page *::before,
.meal-items-page *::after {
    box-sizing: border-box;
}

.meal-items-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.meal-items-page a,
.meal-items-page button {
    transition: background .18s ease, color .18s ease,
        border-color .18s ease, box-shadow .18s ease,
        transform .18s ease;
}

.meal-items-page button,
.meal-items-page input,
.meal-items-page select {
    font-family: inherit;
}

.meal-items-page a:focus-visible,
.meal-items-page button:focus-visible,
.meal-items-page input:focus-visible,
.meal-items-page select:focus-visible {
    outline: 3px solid rgba(69, 104, 84, .28);
    outline-offset: 3px;
}

/* ALERTS */

.meal-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    font-size: 12px;
    font-weight: 600;
}

.meal-alert-success {
    border-color: #c9dfca;
    color: #386d48;
    background: #f1f7ed;
}

.meal-alert-error {
    align-items: flex-start;
    border-color: #e9c9bf;
    color: #a84840;
    background: #fff4ef;
}

.meal-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #e1eddc;
}

/* =========================================================
   HERO — WARM PAPER + FOREST GREEN
========================================================= */

.meal-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.35fr) minmax(320px, .9fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 35px;
    border: 1px solid var(--line);
    border-radius: 11px;
    background: var(--paper);
    box-shadow: 0 7px 22px rgba(38, 59, 53, .055);
}

.meal-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 30px;
    width: 2px;
    background: rgba(189, 81, 72, .30);
}

.meal-hero-content {
    display: flex;
    align-items: center;
    gap: 19px;
    min-width: 0;
    padding: 30px 28px 30px 52px;
}

.meal-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 55px;
    width: 55px;
    height: 55px;
    border: 1px solid #e5decc;
    border-radius: 11px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.meal-hero-text {
    min-width: 0;
}

.meal-eyebrow,
.meal-section-kicker {
    display: block;
    margin-bottom: 6px;
    color: var(--wood);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.25px;
    line-height: 1.5;
}

.meal-hero-text h2 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--hand);
    font-size: 32px;
    font-weight: 400;
    line-height: 1.15;
    letter-spacing: -.3px;
}

.meal-hero-text p {
    max-width: 520px;
    margin: 0;
    color: #727a70;
    font-size: 12px;
    line-height: 1.85;
}

.meal-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 15px;
    color: var(--ink-hover);
    font-size: 11px;
    font-weight: 700;
}

.meal-hero-note i {
    color: var(--wood);
}

.meal-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 215px;
    padding: 27px 25px;
    color: var(--chalk);
    background: linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.meal-handwritten {
    position: relative;
    z-index: 1;
    color: #fffefa;
    font-family: var(--hand, "Brush Script MT"), "Segoe Script", cursive !important;
    font-size: 25px;
    font-weight: 600;
    letter-spacing: .2px;
    line-height: 1.35;
}


.meal-hero-actions p {
    max-width: 330px;
    margin: 0;
    color: #e0e6d9;
    font-size: 11px;
    line-height: 1.8;
}

.meal-hero-buttons {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 3px;
}

/* BUTTON SYSTEM */

.meal-primary-btn,
.meal-secondary-btn,
.meal-apply-btn,
.meal-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 10px 14px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
}

.meal-primary-btn,
.meal-apply-btn {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
    box-shadow: 0 2px 5px rgba(38, 59, 53, .08);
}

.meal-primary-btn:hover,
.meal-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fffefa;
    background: var(--ink-hover);
    box-shadow: 0 5px 12px rgba(38, 59, 53, .12);
}

/* Cream buttons only in the dark hero */

.meal-hero-actions .meal-primary-btn {
    border-color: var(--chalk);
    color: var(--ink);
    background: var(--chalk);
}

.meal-hero-actions .meal-primary-btn:hover {
    border-color: var(--chalk-hover);
    color: var(--ink-dark);
    background: var(--chalk-hover);
}

.meal-secondary-btn {
    border-color: rgba(255, 254, 250, .45);
    color: #fffefa;
    background: transparent;
}

.meal-secondary-btn:hover {
    transform: translateY(-1px);
    border-color: var(--chalk);
    color: var(--ink);
    background: var(--chalk);
}

.meal-reset-btn {
    border-color: #d8d5c8;
    color: var(--ink);
    background: var(--paper);
}

.meal-reset-btn:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: var(--page-soft);
}

/* =========================================================
   KPI CARDS — FOLDER TAB DESIGN
========================================================= */

.meal-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
    margin: 35px 0 25px;
}

.meal-kpi {
    --kpi: var(--blue);
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 13px;
    min-width: 0;
    padding: 23px 20px 17px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 12px rgba(38, 59, 53, .035);
    transition: transform .2s ease, box-shadow .2s ease;
}

.meal-kpi::before {
    content: "";
    position: absolute;
    top: -23px;
    left: 15px;
    width: 94px;
    height: 22px;
    border: 1px solid var(--line);
    border-bottom: 0;
    border-radius: 7px 7px 0 0;
    background: var(--paper);
}

.meal-kpi::after {
    content: "";
    position: absolute;
    top: -3px;
    left: 0;
    width: 100%;
    height: 3px;
    border-radius: 9px 9px 0 0;
    background: var(--kpi);
}

.meal-kpi.blue { --kpi: #2b5d8a; }
.meal-kpi.green { --kpi: #43815b; }
.meal-kpi.orange { --kpi: #b77a24; }
.meal-kpi.red { --kpi: #bd5148; }

.meal-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 20px rgba(38, 59, 53, .08);
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.kpi-label {
    color: #727a70;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.meal-items-page .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border: 1px solid var(--line);
    border-radius: 9px;
    color: var(--kpi);
    background: var(--page-soft);
    font-size: 17px;
}

.kpi-value {
    color: var(--ink);
    font-family: var(--hand);
    font-size: 35px;
    font-weight: 400;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.kpi-caption {
    padding-top: 11px;
    border-top: 1px dashed #ddd8c9;
    color: #7b8177;
    font-size: 10px;
    line-height: 1.6;
}

/* =========================================================
   PAPER PANELS
========================================================= */

.meal-paper-panel {
    overflow: hidden;
    margin-bottom: 25px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    box-shadow: 0 5px 17px rgba(38, 59, 53, .035);
}

.meal-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 19px 21px;
    border-bottom: 1px solid var(--line);
    background: #fffefa;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #dfe3d6;
    border-radius: 9px;
    color: var(--ink);
    background: var(--page-soft);
    font-size: 17px;
}

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--hand);
    font-size: 21px;
    font-weight: 400;
    line-height: 1.3;
}

.panel-title-wrap p {
    margin: 4px 0 0;
    color: #7b8177;
    font-size: 11px;
    line-height: 1.65;
}

.meal-section-kicker {
    margin-bottom: 3px;
    font-size: 8px;
}

.meal-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 32px;
    padding: 7px 11px;
    border: 1px solid #e2ddce;
    border-radius: 7px;
    color: #6e776c;
    background: var(--page);
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-record-count i,
.meal-record-count strong {
    color: var(--ink);
}

/* =========================================================
   FILTER PANEL
========================================================= */

.meal-filter-panel {
    padding: 20px 21px;
    border-bottom: 1px solid var(--line);
    background: #fbfaf5;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 17px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #ddd8c9;
}

.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 39px;
    width: 39px;
    height: 39px;
    border: 1px solid #e0ddcf;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--hand);
    font-size: 19px;
    font-weight: 400;
    line-height: 1.35;
}

.filter-heading p {
    margin: 4px 0 0;
    color: #7b8177;
    font-size: 10px;
    line-height: 1.6;
}

.meal-filter-grid {
    display: grid;
    grid-template-columns: minmax(200px, 1.6fr) minmax(160px, .9fr) auto;
    align-items: end;
    gap: 13px;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #58675d;
    font-size: 10px;
    font-weight: 700;
}

.diary-control,
.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 41px;
    border: 1px solid #dedbce;
    border-radius: 7px;
    color: var(--text);
    background: #fffefa;
    font-size: 11px;
}

.diary-control {
    padding: 0 11px;
    outline: none;
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 12px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #858b80;
    font-size: 12px;
}

.diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font-size: 11px;
}

.diary-search-input input::placeholder {
    color: #96998e;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #829b85;
    box-shadow: 0 0 0 3px rgba(69, 104, 84, .10);
}

.meal-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.meal-apply-btn,
.meal-reset-btn {
    min-height: 41px;
    padding: 0 13px;
}

/* ACTIVE FILTERS */

.meal-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 21px;
    border-bottom: 1px solid #e9e5d9;
    background: #fffefa;
}

.active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #737a70;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .45px;
    text-transform: uppercase;
}

.active-filter-label i {
    color: var(--wood);
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 250px;
    padding: 6px 9px;
    overflow-wrap: anywhere;
    border: 1px solid #e5ddc7;
    border-radius: 6px;
    color: #705d3b;
    background: #f8f3e6;
    font-size: 10px;
    font-weight: 600;
}

.filter-chip i {
    color: var(--wood);
}

.filter-result-count {
    color: #777e73;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* =========================================================
   INVENTORY TABLE
========================================================= */

.meal-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.meal-table {
    width: 100%;
    min-width: 990px;
    margin: 0;
    border-collapse: collapse;
}

.meal-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e2dfd3;
    color: #697369;
    background: #f4f2e9;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .65px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.meal-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.meal-table tbody tr {
    transition: background .16s ease;
}

.meal-table tbody tr:hover {
    background: #faf9f3;
}

.meal-table tbody tr:last-child td {
    border-bottom: 0;
}

.meal-table .actions-heading {
    text-align: right;
}

.meal-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid #e3dfd2;
    border-radius: 7px;
    color: var(--ink);
    background: var(--page-soft);
    font-size: 10px;
    font-weight: 700;
}

/* ITEM INFORMATION */

.meal-item-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 175px;
}

.meal-item-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 37px;
    width: 37px;
    height: 37px;
    border: 1px solid #e3dfd2;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.meal-item-details {
    min-width: 0;
}

.meal-item-title {
    max-width: 220px;
    overflow: hidden;
    color: #2e4137;
    font-size: 11px;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.meal-item-unit {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858b80;
    font-size: 9px;
}

.meal-item-unit i {
    color: var(--wood);
}

.meal-muted {
    color: #92968b;
    font-size: 11px;
}

/* CATEGORY */

.meal-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid #dcded0;
    border-radius: 6px;
    color: #526b5a;
    background: #f2f3e9;
    font-size: 9px;
    font-weight: 700;
}

.meal-category-badge i {
    color: var(--wood);
}

/* STOCK */

.meal-stock-value {
    display: flex;
    align-items: baseline;
    gap: 5px;
    white-space: nowrap;
}

.meal-stock-value strong {
    font-size: 11px;
    font-weight: 800;
}

.meal-stock-value strong.normal {
    color: #43815b;
}

.meal-stock-value strong.low {
    color: var(--red);
}

.meal-stock-value span {
    color: #7d8478;
    font-size: 9px;
}

.meal-low-stock-label {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: var(--red);
    font-size: 9px;
    font-weight: 800;
}

.meal-minimum-stock {
    color: #647267;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

/* STATUS */

.meal-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

.status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.meal-status-badge.active {
    border-color: #d2e2cc;
    color: #3d7149;
    background: #f0f5e9;
}

.meal-status-badge.active .status-dot {
    background: #43815b;
}

.meal-status-badge.inactive {
    border-color: #e2ded2;
    color: #777b70;
    background: #f4f2ea;
}

.meal-status-badge.inactive .status-dot {
    background: #92968b;
}

/* ACTION BUTTONS */

.meal-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.meal-actions form {
    display: inline-flex;
    margin: 0;
}

.meal-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #e0ddd1;
    border-radius: 7px;
    color: var(--ink);
    background: #fffefa;
    font-size: 10px;
    text-decoration: none;
    cursor: pointer;
}

.meal-action-btn:hover {
    transform: translateY(-1px);
}

.meal-action-btn.stock:hover {
    border-color: #b8cdbd;
    color: #fffefa;
    background: var(--ink);
}

.meal-action-btn.edit:hover {
    border-color: #d8c39e;
    color: #fffefa;
    background: var(--wood);
}

.meal-action-btn.delete:hover {
    border-color: var(--red);
    color: #fffefa;
    background: var(--red);
}

/* =========================================================
   RECENT STOCK MOVEMENTS
========================================================= */

.movements-icon {
    border-color: #d4e0d0;
    color: var(--ink);
    background: #edf2e7;
}

.meal-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 12px;
    border: 1px solid #dedbce;
    border-radius: 7px;
    color: var(--ink);
    background: #fffefa;
    font-size: 10px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.meal-view-all:hover {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
}

.movements-table {
    min-width: 800px;
}

.meal-transaction-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 6px;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

.meal-transaction-badge.stock-in {
    border-color: #d2e2cc;
    color: #3d7149;
    background: #f0f5e9;
}

.meal-transaction-badge.stock-out {
    border-color: #e9d2c7;
    color: #a84c40;
    background: #fff1e9;
}

.movement-quantity {
    display: flex;
    align-items: baseline;
    gap: 5px;
    white-space: nowrap;
}

.movement-quantity strong {
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
}

.movement-quantity span {
    color: var(--muted);
    font-size: 9px;
}

.movement-date {
    color: #586b5e;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* EMPTY STATES */

.meal-empty-state,
.meal-movement-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 11px;
    padding: 48px 20px;
    text-align: center;
}

.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 67px;
    height: 67px;
    margin-bottom: 4px;
    border: 1px solid #e5decc;
    border-radius: 13px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 25px;
}

.meal-empty-state h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--hand);
    font-size: 23px;
    font-weight: 400;
}

.meal-empty-state p {
    max-width: 430px;
    margin: 0 0 5px;
    color: #7b8177;
    font-size: 11px;
    line-height: 1.8;
}

.meal-movement-empty > strong {
    color: var(--ink);
    font-size: 12px;
}

.meal-movement-empty > span {
    color: #7b8177;
    font-size: 10px;
}

/* =========================================================
   PAGINATION AND FOOTER
========================================================= */

.meal-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 15px 20px;
    border-top: 1px dashed #ddd8c9;
    background: #fffefa;
}

.meal-pagination nav {
    margin: 0;
}

.meal-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}

.meal-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    min-height: 32px;
    padding: 5px 9px;
    border: 1px solid #e0ddd1;
    border-radius: 6px !important;
    color: var(--ink);
    background: #fffefa;
    font-size: 11px;
    box-shadow: none;
}

.meal-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: #fffefa;
    background: var(--ink-hover);
}

.meal-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fffefa;
    background: var(--ink);
}

.meal-pagination .page-item.disabled .page-link {
    color: #aaa99d;
    background: #f7f6f0;
}

.pagination-info {
    color: #7b8177;
    font-size: 10px;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.meal-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    min-height: 48px;
    padding: 12px 20px;
    border-top: 1px dashed #ddd8c9;
    color: #7b8177;
    background: #f9f8f2;
    font-size: 10px;
}

.meal-card-footer strong {
    color: var(--ink);
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #627367;
    font-size: 10px;
}

.footer-status i {
    color: #43815b;
}

/* =========================================================
   RESPONSIVE DESIGN
========================================================= */

@media (max-width: 1200px) {
    .meal-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .meal-filter-grid {
        grid-template-columns: minmax(180px, 1.4fr) minmax(145px, 1fr);
    }

    .meal-filter-actions {
        grid-column: 1 / -1;
    }

    .meal-filter-actions > * {
        flex: 0 0 auto;
    }
}

@media (max-width: 900px) {
    .meal-hero {
        grid-template-columns: 1fr;
    }

    .meal-hero-actions {
        min-height: 185px;
    }
}

@media (max-width: 650px) {
    .meal-items-page .container-fluid {
        padding-top: 19px !important;
        padding-bottom: 18px !important;
    }

    .meal-hero::before {
        left: 21px;
    }

    .meal-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 24px 16px 23px 39px;
    }

    .meal-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        font-size: 18px;
    }

    .meal-hero-text h2 {
        font-size: 27px;
    }

    .meal-hero-text p {
        font-size: 11px;
    }

    .meal-hero-note {
        font-size: 10px;
    }

    .meal-handwritten {
        font-size: 23px;
    }

    .meal-hero-actions {
        min-height: 175px;
        padding: 22px 17px;
    }

    .meal-kpis {
        gap: 19px 12px;
        margin-top: 31px;
    }

    .meal-kpi {
        padding: 20px 14px 14px;
    }

    .kpi-label {
        font-size: 10px;
    }

    .meal-items-page .kpi-icon {
        flex-basis: 35px;
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .kpi-value {
        font-size: 30px;
    }

    .kpi-caption {
        font-size: 9px;
    }

    .meal-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .panel-title-wrap {
        gap: 10px;
    }

    .panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
        font-size: 14px;
    }

    .panel-title-wrap h3 {
        font-size: 19px;
    }

    .panel-title-wrap p {
        font-size: 10px;
    }

    .meal-record-count {
        gap: 5px;
        padding: 6px 8px;
        font-size: 9px;
    }

    .meal-filter-panel {
        padding: 15px;
    }

    .filter-heading h4 {
        font-size: 17px;
    }

    .meal-filter-grid {
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .meal-filter-actions {
        grid-column: auto;
        flex-wrap: wrap;
    }

    .meal-filter-actions > * {
        flex: 1;
    }

    .meal-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px 15px;
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

    .meal-view-all {
        padding: 8px;
        font-size: 9px;
    }
}

@media (max-width: 400px) {
    .meal-kpis {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .meal-kpi {
        padding: 21px 18px 15px;
    }

    .meal-items-page .kpi-icon {
        flex-basis: 40px;
        width: 40px;
        height: 40px;
    }

    .meal-panel-header {
        flex-wrap: wrap;
    }

    .meal-record-count {
        margin-left: 46px;
    }

    .meal-hero-buttons {
        width: 100%;
        flex-direction: column;
    }

    .meal-hero-buttons a {
        width: 100%;
    }

    .meal-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .meal-apply-btn,
    .meal-reset-btn {
        width: 100%;
    }

    .active-filter-items {
        align-items: flex-start;
    }

    .filter-chip {
        max-width: 100%;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .meal-items-page *,
    .meal-items-page *::before,
    .meal-items-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection
