@extends('layouts.app')
 
@section('title', 'Meal Items | Admin')
@section('page-title', 'Meal Items')
 
@section('content')
 
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
 
    $activeItems = $itemsCollection->where('status', 'active')->count();
 
    $lowStockItems = $itemsCollection
        ->filter(function ($item) {
            return (float) ($item->current_stock ?? 0)
                <= (float) ($item->minimum_stock ?? 0);
        })
        ->count();
 
    $stockInCount  = $transactionsCollection->where('transaction_type', 'stock_in')->count();
    $stockOutCount = $transactionsCollection->where('transaction_type', 'stock_out')->count();
    $stockMovementCount = $stockInCount + $stockOutCount;
 
    $hasFilters = request()->hasAny(['search', 'status']);
@endphp
 
<div class="meal-items-page">
    <div class="container-fluid py-4">
 
        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="meal-success-alert" role="status">
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
            <div class="meal-error-alert" role="alert">
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
 
        @if($errors->any())
            <div class="meal-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
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
                    <i class="fas fa-bowl-food"></i>
                </div>
 
                <div class="meal-hero-text">
                    <div class="meal-breadcrumb">
                        INVENTORY <span>/</span> MEAL ITEMS
                    </div>
 
                    <h2>Meal Items</h2>
 
                    <p>
                        Manage meal inventory, check available stock and keep
                        your school supplies organised from one place.
                    </p>
 
                    <div class="meal-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Meal Inventory Management</span>
                    </div>
                </div>
            </div>
 
            <div class="meal-hero-actions">
                <div class="hero-handwritten">Simple. Organised. Efficient.</div>
 
                <p>
                    Keep track of inventory and identify the items
                    that need attention.
                </p>
 
                <a href="{{ route('admin.meal.items.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Meal Item
                </a>
            </div>
        </section>
 
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
                    <i class="fas fa-layer-group"></i>
                    Registered inventory items
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
                    <i class="fas fa-circle-check"></i>
                    Active items on this page
                </div>
            </article>
 
            <article class="meal-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Low Stock</span>
                    <span class="kpi-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                </div>
 
                <div class="kpi-value">{{ $lowStockItems }}</div>
 
                <div class="kpi-caption">
                    <i class="fas fa-arrow-trend-down"></i>
                    At or below minimum stock
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
                    <i class="fas fa-clock-rotate-left"></i>
                    Movements in the loaded records
                </div>
            </article>
 
        </section>
 
        {{-- SEARCH AND FILTERS --}}
        <section class="meal-filter-card">
 
            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>
 
                <div class="filter-heading-copy">
                    <div class="section-eyebrow">RECORD MANAGEMENT</div>
                    <h3>Search &amp; Filter Meal Items</h3>
                    <p>Find items by name or category and filter by status.</p>
                </div>
 
                @if($hasFilters)
                    <a href="{{ route('admin.meal.items.index') }}"
                       class="diary-reset-btn reset-heading-btn">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif
            </div>
 
            <form method="GET" action="{{ route('admin.meal.items.index') }}">
 
                <div class="meal-filter-grid">
 
                    <div class="diary-filter-group search-group">
                        <label for="meal-search">Search Records</label>
 
                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>
 
                            <input
                                type="text"
                                name="search"
                                id="meal-search"
                                value="{{ request('search') }}"
                                placeholder="Item name or category..."
                                autocomplete="off"
                            >
                        </div>
                    </div>
 
                    <div class="diary-filter-group">
                        <label for="meal-status">Record Status</label>
 
                        <select name="status"
                                id="meal-status"
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
 
                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>
 
                        <a href="{{ route('admin.meal.items.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>
 
                </div>
            </form>
 
            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
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
                        {{ $totalItems }} {{ $totalItems == 1 ? 'result' : 'results' }}
                    </span>
                </div>
            @endif
 
        </section>
 
        {{-- MEAL ITEM RECORDS --}}
        <section class="meal-table-card">
 
            <div class="meal-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
 
                    <div>
                        <div class="section-eyebrow">INVENTORY REGISTER</div>
                        <h3>All Meal Items</h3>
                        <p>Review, edit or remove inventory items.</p>
                    </div>
                </div>
 
                <div class="meal-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalItems }}</strong>
                    {{ $totalItems == 1 ? 'Item' : 'Items' }}
                </div>
            </div>
 
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
                                $isLowStock   = $currentStock <= $minimumStock;
                                $isActive     = $item->status === 'active';
                            @endphp
 
                            <tr>
                                <td>
                                    <span class="record-number">
                                        @if(isset($items) && method_exists($items, 'firstItem'))
                                            {{ $items->firstItem() + $loop->index }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </span>
                                </td>
 
                                <td>
                                    <div class="meal-info">
                                        <div class="meal-mini-icon">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>
 
                                        <div class="meal-details">
                                            <div class="meal-name">
                                                {{ $item->item_name }}
                                            </div>
 
                                            <div class="meal-type">
                                                <i class="fas fa-scale-balanced"></i>
                                                {{ $item->unit ?: 'Unit not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
 
                                <td>
                                    @if($item->category)
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $item->category }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>
 
                                <td>
                                    <div class="stock-value">
                                        <strong class="{{ $isLowStock ? 'low' : 'normal' }}">
                                            {{ number_format($currentStock, 2) }}
                                        </strong>
                                        <span>{{ $item->unit }}</span>
                                    </div>
 
                                    @if($isLowStock)
                                        <div class="low-stock-label">
                                            <i class="fas fa-triangle-exclamation"></i>
                                            Low Stock
                                        </div>
                                    @endif
                                </td>
 
                                <td>
                                    <span class="minimum-stock">
                                        {{ number_format($minimumStock, 2) }}
                                        {{ $item->unit }}
                                    </span>
                                </td>
 
                                <td>
                                    <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
 
                                <td>
                                    <div class="meal-actions">
 
                                        {{-- EDIT AND DELETE ONLY: NO STOCK IN / OUT BUTTON --}}
                                        <a
                                            href="{{ route('admin.meal.items.edit', $item) }}"
                                            class="game-action-btn edit"
                                            title="Edit Meal Item"
                                            aria-label="Edit {{ $item->item_name }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
 
                                        <form
                                            method="POST"
                                            action="{{ route('admin.meal.items.destroy', $item) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this meal item?');">
                                            @csrf
                                            @method('DELETE')
 
                                            <button
                                                type="submit"
                                                class="game-action-btn delete"
                                                title="Delete Meal Item"
                                                aria-label="Delete {{ $item->item_name }}">
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
                                            <i class="fas fa-box-open"></i>
                                        </div>
 
                                        <h4>No Meal Items Found</h4>
 
                                        <p>
                                            No meal items match your current search
                                            or filters.
                                        </p>
 
                                        <div class="empty-state-actions">
                                            @if($hasFilters)
                                                <a href="{{ route('admin.meal.items.index') }}"
                                                   class="diary-reset-btn">
                                                    <i class="fas fa-rotate-left"></i>
                                                    Clear Filters
                                                </a>
                                            @endif
 
                                            <a href="{{ route('admin.meal.items.create') }}">
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add Meal Item
                                            </a>
                                        </div>
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
                        {{ $items->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
 
            {{-- FOOTER --}}
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
 
        {{-- RECENT STOCK MOVEMENTS: HISTORY ONLY --}}
        @if(isset($transactions))
            <section class="meal-table-card meal-movements-card">
 
                <div class="meal-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </div>
 
                        <div>
                            <div class="section-eyebrow">TRANSACTION HISTORY</div>
                            <h3>Recent Stock Movements</h3>
                            <p>Review previously recorded stock-in and stock-out transactions.</p>
                        </div>
                    </div>
 
                    <a href="{{ route('admin.meal.items.stock.index') }}"
                       class="diary-reset-btn">
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
                                        <div class="meal-info">
                                            <div class="meal-mini-icon">
                                                <i class="fas fa-bowl-food"></i>
                                            </div>
 
                                            <div class="meal-details">
                                                <div class="meal-name">
                                                    {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                </div>
 
                                                <div class="meal-type">
                                                    <i class="fas fa-tag"></i>
                                                    {{ $transaction->mealItem->category ?? 'Uncategorized' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
 
                                    <td>
                                        @if($transaction->transaction_type === 'stock_in')
                                            <span class="transaction-badge stock-in">
                                                <i class="fas fa-arrow-down"></i>
                                                Stock In
                                            </span>
                                        @else
                                            <span class="transaction-badge stock-out">
                                                <i class="fas fa-arrow-up"></i>
                                                Stock Out
                                            </span>
                                        @endif
                                    </td>
 
                                    <td>
                                        <div class="stock-value">
                                            <strong class="normal">
                                                {{ number_format((float) $transaction->quantity, 2) }}
                                            </strong>
                                            <span>{{ $transaction->unit }}</span>
                                        </div>
                                    </td>
 
                                    <td>
                                        @if($transaction->transaction_date)
                                            <div class="event-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                            </div>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        @php
                                            $reference = $transaction->transaction_type === 'stock_in'
                                                ? $transaction->supplier
                                                : $transaction->reason;
                                        @endphp
 
                                        @if($reference)
                                            <span class="reference-text">{{ $reference }}</span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="meal-empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </div>
 
                                            <h4>No Stock Movements Yet</h4>
 
                                            <p>
                                                Recorded stock transactions will
                                                appear here.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
 
                @if(method_exists($transactions, 'hasPages') && $transactions->hasPages())
                    <div class="meal-pagination">
                        <div class="pagination-info">
                            Showing
                            <strong>{{ $transactions->firstItem() }}</strong>
                            to
                            <strong>{{ $transactions->lastItem() }}</strong>
                            of
                            <strong>{{ $transactions->total() }}</strong>
                            movements
                        </div>
 
                        <div>
                            {{ $transactions->withQueryString()->onEachSide(1)->links() }}
                        </div>
                    </div>
                @endif
 
            </section>
        @endif
 
    </div>
</div>
 
<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   MEAL ITEMS — INDEX PAGE
   Same tokens, cards, buttons and layout as Sports Achievements.
   Every selector is scoped under .meal-items-page.
========================================================= */
 
.meal-items-page {
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
 
.meal-items-page *,
.meal-items-page *::before,
.meal-items-page *::after {
    box-sizing: border-box;
}
 
.meal-items-page a,
.meal-items-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}
 
.meal-items-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}
 
.meal-items-page .meal-hero,
.meal-items-page .meal-filter-card,
.meal-items-page .meal-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}
 
/* HERO */
 
.meal-items-page .meal-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}
 
.meal-items-page .meal-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}
 
.meal-items-page .meal-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}
 
.meal-items-page .meal-hero-icon {
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
 
.meal-items-page .meal-hero-text {
    min-width: 0;
}
 
.meal-items-page .meal-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}
 
.meal-items-page .meal-breadcrumb span {
    color: #b8b09e;
}
 
.meal-items-page .meal-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}
 
.meal-items-page .meal-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}
 
.meal-items-page .meal-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}
 
.meal-items-page .meal-hero-actions {
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
 
.meal-items-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}
 
.meal-items-page .meal-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}
 
/* LOCKED BUTTON STYLES */
 
.meal-items-page .diary-primary-btn {
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
 
.meal-items-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}
 
.meal-items-page .meal-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}
 
.meal-items-page .meal-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}
 
/* ALERTS */
 
.meal-items-page .meal-success-alert,
.meal-items-page .meal-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}
 
.meal-items-page .meal-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}
 
.meal-items-page .meal-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}
 
.meal-items-page .meal-error-alert ul {
    padding-left: 18px;
    font-weight: 600;
}
 
.meal-items-page .success-icon,
.meal-items-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}
 
.meal-items-page .success-icon { background: #dcefd9; }
.meal-items-page .error-icon { background: #fbe0d8; }
 
.meal-items-page .alert-close {
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
 
.meal-items-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}
 
/* KPI CARDS */
 
.meal-items-page .meal-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}
 
.meal-items-page .meal-kpi {
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
 
.meal-items-page .meal-kpi::before {
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
 
.meal-items-page .meal-kpi.blue  { --kpi-color: #2b5d8a; }
.meal-items-page .meal-kpi.green { --kpi-color: #3a8060; }
.meal-items-page .meal-kpi.amber { --kpi-color: #a87543; }
.meal-items-page .meal-kpi.red   { --kpi-color: #bd5148; }
 
.meal-items-page .meal-kpi.blue::before  { content: "Items"; }
.meal-items-page .meal-kpi.green::before { content: "Active"; }
.meal-items-page .meal-kpi.amber::before { content: "Low Stock"; }
.meal-items-page .meal-kpi.red::before   { content: "Movements"; }
 
.meal-items-page .meal-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}
 
.meal-items-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
 
.meal-items-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}
 
.meal-items-page .meal-kpi .kpi-icon {
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
 
.meal-items-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}
 
.meal-items-page .kpi-caption {
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
 
.meal-items-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}
 
/* FILTER PANEL */
 
.meal-items-page .meal-filter-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035);
}
 
.meal-items-page .filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 19px 20px 16px;
    border-bottom: 1px dashed #d8d1bf;
    background: #fffefa;
}
 
.meal-items-page .filter-heading-icon,
.meal-items-page .panel-title-icon {
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
 
.meal-items-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}
 
.meal-items-page .filter-heading h3,
.meal-items-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}
 
.meal-items-page .filter-heading p,
.meal-items-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}
 
.meal-items-page .reset-heading-btn {
    margin-left: auto;
}
 
.meal-items-page .meal-filter-card form {
    padding: 19px 20px 20px;
}
 
.meal-items-page .meal-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(200px, 2fr)
        minmax(130px, 1fr)
        auto;
    align-items: end;
    gap: 12px;
}
 
.meal-items-page .diary-filter-group {
    min-width: 0;
}
 
.meal-items-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}
 
.meal-items-page .diary-control,
.meal-items-page .diary-search-input {
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
 
.meal-items-page .diary-control {
    padding: 0 10px;
}
 
.meal-items-page .diary-control:focus,
.meal-items-page .diary-search-input:focus-within {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}
 
.meal-items-page .diary-search-input {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 11px;
}
 
.meal-items-page .diary-search-input > i {
    flex: 0 0 auto;
    color: #929b8f;
    font-size: 12px;
}
 
.meal-items-page .diary-search-input input {
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
 
.meal-items-page .diary-search-input input::placeholder {
    color: #929b8f;
}
 
.meal-items-page .diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}
 
.meal-items-page .diary-apply-btn,
.meal-items-page .diary-reset-btn {
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
 
.meal-items-page .diary-apply-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-items-page .diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
}
 
.meal-items-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: #fffefa;
}
 
.meal-items-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink);
    background: #f5f4eb;
}
 
/* ACTIVE FILTERS */
 
.meal-items-page .meal-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 11px 20px;
    border-top: 1px solid #eeece3;
    background: #faf9f4;
}
 
.meal-items-page .active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}
 
.meal-items-page .active-filter-label {
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
 
.meal-items-page .active-filter-label i {
    color: var(--green);
}
 
.meal-items-page .filter-chip {
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
 
.meal-items-page .filter-chip i {
    color: var(--amber);
}
 
.meal-items-page .filter-result-count {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
/* RECORDS PANEL */
 
.meal-items-page .meal-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}
 
.meal-items-page .meal-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-items-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
 
.meal-items-page .meal-record-count {
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
 
.meal-items-page .meal-record-count i,
.meal-items-page .meal-record-count strong {
    color: var(--ink);
}
 
/* TABLE */
 
.meal-items-page .meal-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
 
.meal-items-page .meal-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: collapse;
}
 
.meal-items-page .movements-table {
    min-width: 820px;
}
 
.meal-items-page .meal-table thead th {
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
 
.meal-items-page .meal-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}
 
.meal-items-page .meal-table tbody tr {
    transition: background .18s ease;
}
 
.meal-items-page .meal-table tbody tr:hover {
    background: #faf9f2;
}
 
.meal-items-page .meal-table tbody tr:last-child td {
    border-bottom: none;
}
 
.meal-items-page .meal-table .actions-heading {
    text-align: right;
}
 
.meal-items-page .record-number {
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
 
/* MEAL ITEM */
 
.meal-items-page .meal-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}
 
.meal-items-page .meal-mini-icon {
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
 
.meal-items-page .meal-details {
    min-width: 0;
}
 
.meal-items-page .meal-name {
    max-width: 210px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}
 
.meal-items-page .meal-type {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
}
 
.meal-items-page .meal-type i {
    color: var(--amber);
}
 
/* CATEGORY */
 
.meal-items-page .category-badge {
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
 
/* STOCK */
 
.meal-items-page .stock-value {
    display: flex;
    align-items: baseline;
    gap: 5px;
    white-space: nowrap;
}
 
.meal-items-page .stock-value strong {
    font-size: 12px;
    font-weight: 800;
}
 
.meal-items-page .stock-value strong.normal { color: var(--green); }
.meal-items-page .stock-value strong.low    { color: var(--red); }
 
.meal-items-page .stock-value span {
    color: var(--muted);
    font-size: 10px;
}
 
.meal-items-page .low-stock-label {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 6px;
    padding: 3px 8px;
    border: 1px solid #f0d5ce;
    border-radius: 20px;
    color: var(--red);
    background: #fff1ed;
    font-size: 9px;
    font-weight: 800;
}
 
.meal-items-page .minimum-stock {
    color: #647168;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
/* DATE AND REFERENCE */
 
.meal-items-page .event-date {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
 
.meal-items-page .event-date i {
    color: var(--blue);
}
 
.meal-items-page .reference-text {
    color: #647168;
    font-size: 11px;
}
 
/* STATUS AND TRANSACTION BADGES */
 
.meal-items-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}
 
.meal-items-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}
 
.meal-items-page .status-badge.active {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-items-page .status-badge.active .status-dot {
    background: #3a8060;
}
 
.meal-items-page .status-badge.inactive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}
 
.meal-items-page .status-badge.inactive .status-dot {
    background: #bd5148;
}
 
.meal-items-page .transaction-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}
 
.meal-items-page .transaction-badge.stock-in {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}
 
.meal-items-page .transaction-badge.stock-out {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}
 
.meal-items-page .diary-muted {
    color: #929a8e;
    font-size: 11px;
}
 
/* ACTION BUTTONS */
 
.meal-items-page .meal-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}
 
.meal-items-page .meal-actions form {
    display: inline-flex;
    margin: 0;
}
 
.meal-items-page .game-action-btn {
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
 
.meal-items-page .game-action-btn:hover {
    transform: translateY(-2px);
}
 
.meal-items-page .game-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}
 
.meal-items-page .game-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}
 
/* EMPTY STATE */
 
.meal-items-page .meal-empty-state {
    padding: 60px 20px;
    text-align: center;
}
 
.meal-items-page .empty-state-icon {
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
 
.meal-items-page .meal-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}
 
.meal-items-page .meal-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}
 
.meal-items-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}
 
/* PAGINATION */
 
.meal-items-page .meal-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}
 
.meal-items-page .pagination-info {
    color: var(--muted);
    font-size: 11px;
}
 
.meal-items-page .pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}
 
.meal-items-page .meal-pagination nav {
    margin: 0;
}
 
.meal-items-page .meal-pagination .pagination {
    flex-wrap: wrap;
    gap: 4px;
    margin: 0;
}
 
.meal-items-page .meal-pagination .page-link {
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
 
.meal-items-page .meal-pagination .page-link:hover {
    border-color: #bfcbbd;
    color: var(--ink-hover);
    background: #f5f4eb;
}
 
.meal-items-page .meal-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}
 
.meal-items-page .meal-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}
 
/* FOOTER */
 
.meal-items-page .meal-card-footer {
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
 
.meal-items-page .meal-card-footer strong {
    color: var(--ink);
}
 
.meal-items-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}
 
.meal-items-page .footer-status i {
    color: var(--green);
}
 
/* TABLET */
 
@media (max-width: 1250px) {
    .meal-items-page .meal-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-items-page .meal-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
 
    .meal-items-page .diary-filter-actions {
        grid-column: span 2;
    }
}
 
@media (max-width: 900px) {
    .meal-items-page .meal-hero {
        grid-template-columns: 1fr;
    }
 
    .meal-items-page .meal-hero-actions {
        min-height: 165px;
    }
}
 
/* MOBILE */
 
@media (max-width: 650px) {
    .meal-items-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }
 
    .meal-items-page .meal-hero::before {
        left: 22px;
    }
 
    .meal-items-page .meal-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }
 
    .meal-items-page .meal-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }
 
    .meal-items-page .meal-hero-text h2 {
        font-size: 23px;
    }
 
    .meal-items-page .meal-hero-text p {
        font-size: 12px;
    }
 
    .meal-items-page .meal-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }
 
    .meal-items-page .meal-hero-actions {
        min-height: 155px;
        padding: 20px;
    }
 
    .meal-items-page .meal-kpis {
        gap: 15px;
        margin-top: 30px;
    }
 
    .meal-items-page .meal-kpi {
        padding: 20px 16px 15px;
    }
 
    .meal-items-page .kpi-value {
        font-size: 30px;
    }
 
    .meal-items-page .filter-heading {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }
 
    .meal-items-page .filter-heading h3 {
        font-size: 16px;
    }
 
    .meal-items-page .reset-heading-btn {
        margin-left: 0;
    }
 
    .meal-items-page .meal-filter-card form {
        padding: 15px;
    }
 
    .meal-items-page .meal-filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
 
    .meal-items-page .diary-filter-actions {
        grid-column: auto;
    }
 
    .meal-items-page .diary-filter-actions > * {
        flex: 1;
    }
 
    .meal-items-page .meal-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 11px 15px;
    }
 
    .meal-items-page .filter-result-count {
        white-space: normal;
    }
 
    .meal-items-page .meal-panel-header {
        align-items: flex-start;
        padding: 15px;
    }
 
    .meal-items-page .panel-title-wrap h3 {
        font-size: 16px;
    }
 
    .meal-items-page .meal-record-count {
        gap: 5px;
        padding: 5px 7px;
    }
 
    .meal-items-page .meal-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }
 
    .meal-items-page .meal-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}
 
@media (max-width: 400px) {
    .meal-items-page .meal-kpis {
        grid-template-columns: 1fr;
    }
 
    .meal-items-page .meal-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }
 
    .meal-items-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
 
    .meal-items-page .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }
 
    .meal-items-page .diary-apply-btn,
    .meal-items-page .diary-reset-btn {
        width: 100%;
    }
 
    .meal-items-page .active-filter-items {
        align-items: flex-start;
    }
 
    .meal-items-page .filter-chip {
        max-width: 100%;
    }
 
    .meal-items-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }
 
    .meal-items-page .empty-state-actions > a {
        width: 100%;
    }
}
 
/* ACCESSIBILITY */
 
.meal-items-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}
 
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
 

