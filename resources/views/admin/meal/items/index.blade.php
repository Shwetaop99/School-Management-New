
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

    $lowStockItems = $itemsCollection->filter(function ($item) {
        return (float) ($item->current_stock ?? 0)
            <= (float) ($item->minimum_stock ?? 0);
    })->count();

    $stockInCount = $transactionsCollection
        ->where('transaction_type', 'stock_in')->count();

    $stockOutCount = $transactionsCollection
        ->where('transaction_type', 'stock_out')->count();

    $stockMovementCount = $stockInCount + $stockOutCount;

    $hasFilters = request()->filled('search') || request()->filled('status');
@endphp

<div class="meal-items-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="meal-alert success" role="status">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="meal-alert error" role="alert">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="meal-alert error">
                <i class="fas fa-triangle-exclamation"></i>
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

        {{-- HERO SECTION --}}
        <section class="meal-hero">

            <div class="hero-main">

                <div class="hero-icon">
                    <i class="fas fa-bowl-food"></i>
                </div>

                <div class="hero-copy">
                    <div class="hero-breadcrumb">
                        INVENTORY <span>/</span> MEAL ITEMS
                    </div>

                    <h2>Meal Items</h2>

                    <p>
                        Manage your school's meal inventory, monitor available
                        stock and maintain organised supply records.
                    </p>

                    <div class="hero-note">
                        <i class="fas fa-shield-alt"></i>
                        School Meal Inventory Management
                    </div>
                </div>

            </div>

            <div class="hero-side">
                <div class="hero-handwritten">
                    Simple. Organised. Efficient.
                </div>

                <p>
                    Keep your inventory updated and make sure
                    essential meal supplies are available.
                </p>

                <a href="{{ route('admin.meal.items.create') }}"
                   class="meal-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Meal Item
                </a>
            </div>

        </section>

        {{-- KPI CARDS --}}
        <section class="meal-kpis">

            <article class="meal-kpi blue">
                <div class="kpi-top">
                    <span>Total Meal Items</span>
                    <i class="fas fa-boxes-stacked"></i>
                </div>

                <div class="kpi-value">{{ $totalItems }}</div>

                <div class="kpi-footer">
                    <i class="fas fa-layer-group"></i>
                    Registered inventory items
                </div>
            </article>

            <article class="meal-kpi green">
                <div class="kpi-top">
                    <span>Active Items</span>
                    <i class="fas fa-circle-check"></i>
                </div>

                <div class="kpi-value">{{ $activeItems }}</div>

                <div class="kpi-footer">
                    <i class="fas fa-check-circle"></i>
                    Active items on this page
                </div>
            </article>

            <article class="meal-kpi amber">
                <div class="kpi-top">
                    <span>Low Stock</span>
                    <i class="fas fa-triangle-exclamation"></i>
                </div>

                <div class="kpi-value">{{ $lowStockItems }}</div>

                <div class="kpi-footer">
                    <i class="fas fa-arrow-trend-down"></i>
                    Items at minimum stock
                </div>
            </article>

            <article class="meal-kpi red">
                <div class="kpi-top">
                    <span>Stock Movements</span>
                    <i class="fas fa-right-left"></i>
                </div>

                <div class="kpi-value">{{ $stockMovementCount }}</div>

                <div class="kpi-footer">
                    <i class="fas fa-clock-rotate-left"></i>
                    Loaded transaction records
                </div>
            </article>

        </section>

        {{-- SEARCH AND FILTER --}}
        <section class="meal-card filter-card">

            <div class="section-heading">

                <div class="heading-icon">
                    <i class="fas fa-filter"></i>
                </div>

                <div class="heading-content">
                    <div class="section-eyebrow">RECORD MANAGEMENT</div>
                    <h3>Search &amp; Filter Meal Items</h3>
                    <p>Find meal items and filter inventory records by status.</p>
                </div>

                @if($hasFilters)
                    <a href="{{ route('admin.meal.items.index') }}"
                       class="meal-outline-btn heading-reset">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif

            </div>

            <form method="GET" action="{{ route('admin.meal.items.index') }}">

                <div class="filter-grid">

                    <div class="filter-group">
                        <label for="meal-search">Search Records</label>

                        <div class="search-control">
                            <i class="fas fa-search"></i>

                            <input type="text"
                                   name="search"
                                   id="meal-search"
                                   value="{{ request('search') }}"
                                   placeholder="Search item name or category..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="meal-status">Record Status</label>

                        <select name="status"
                                id="meal-status"
                                class="meal-control">

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

                    <div class="filter-actions">

                        <button type="submit" class="meal-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.meal.items.index') }}"
                           class="meal-outline-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

            @if($hasFilters)
                <div class="active-filters">

                    <div class="active-filter-list">
                        <span class="active-filter-title">
                            <i class="fas fa-sliders-h"></i>
                            Active Filters
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

                    <span class="filter-results">
                        {{ $totalItems }} {{ $totalItems === 1 ? 'result' : 'results' }}
                    </span>

                </div>
            @endif

        </section>

        {{-- MEAL ITEMS TABLE --}}
        <section class="meal-card table-card">

            {{-- BULK DELETE FORM --}}
            <form id="bulkDeleteMealItemsForm"
                  method="POST"
                  action="{{ route('admin.meal.items.bulk-destroy') }}">
                @csrf
                @method('DELETE')
            </form>

            <div class="table-heading">

                <div class="table-heading-main">

                    <div class="heading-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">INVENTORY REGISTER</div>
                        <h3>All Meal Items</h3>
                        <p>Review, update or remove meal inventory records.</p>
                    </div>

                </div>

                <div class="table-heading-actions">

                    <span class="record-counter">
                        <i class="fas fa-folder-open"></i>
                        {{ $totalItems }} Items
                    </span>

                    <button type="submit"
                            form="bulkDeleteMealItemsForm"
                            class="meal-bulk-delete-btn"
                            onclick="return confirmMealBulkDelete()">

                        <i class="fas fa-trash-alt"></i>
                        Bulk Delete

                        <span id="selectedMealCount">0</span>

                    </button>

                </div>

            </div>

            <div class="table-responsive">

                <table class="meal-table">

                    <thead>
                        <tr>
                            <th class="checkbox-column">
                                <input type="checkbox"
                                       id="selectAllMealItems"
                                       class="meal-checkbox"
                                       aria-label="Select all meal items">
                            </th>

                            <th>#</th>
                            <th>Meal Item</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Minimum Stock</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($itemsCollection as $item)

                            @php
                                $currentStock = (float) ($item->current_stock ?? 0);
                                $minimumStock = (float) ($item->minimum_stock ?? 0);

                                $isLowStock = $currentStock <= $minimumStock;
                                $isActive = $item->status === 'active';

                                $recordNumber = isset($items)
                                    && method_exists($items, 'firstItem')
                                    ? $items->firstItem() + $loop->index
                                    : $loop->iteration;
                            @endphp

                            <tr>

                                {{-- SELECT RECORD --}}
                                <td class="checkbox-column">

                                    <input type="checkbox"
                                           name="item_ids[]"
                                           value="{{ $item->id }}"
                                           class="meal-checkbox meal-item-checkbox"
                                           form="bulkDeleteMealItemsForm"
                                           aria-label="Select {{ $item->item_name }}">

                                </td>

                                {{-- NUMBER --}}
                                <td>
                                    <span class="record-number">
                                        {{ $recordNumber }}
                                    </span>
                                </td>

                                {{-- ITEM NAME --}}
                                <td>

                                    <div class="meal-info">

                                        <div class="meal-item-icon">
                                            <i class="fas fa-bowl-food"></i>
                                        </div>

                                        <div class="meal-item-details">

                                            <strong>
                                                {{ $item->item_name }}
                                            </strong>

                                            <span>
                                                <i class="fas fa-scale-balanced"></i>
                                                {{ $item->unit ?: 'Unit not specified' }}
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                {{-- CATEGORY --}}
                                <td>

                                    @if($item->category)
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $item->category }}
                                        </span>
                                    @else
                                        <span class="muted-text">—</span>
                                    @endif

                                </td>

                                {{-- CURRENT STOCK --}}
                                <td>

                                    <div class="stock-value">
                                        <strong class="{{ $isLowStock ? 'stock-low' : 'stock-normal' }}">
                                            {{ number_format($currentStock, 2) }}
                                        </strong>

                                        <span>{{ $item->unit }}</span>
                                    </div>

                                    @if($isLowStock)
                                        <span class="low-stock-label">
                                            <i class="fas fa-triangle-exclamation"></i>
                                            Low Stock
                                        </span>
                                    @endif

                                </td>

                                {{-- MINIMUM STOCK --}}
                                <td>
                                    <span class="minimum-stock">
                                        {{ number_format($minimumStock, 2) }}
                                        {{ $item->unit }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>

                                    <span class="meal-status {{ $isActive ? 'active' : 'inactive' }}">
                                        <span></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>

                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    <div class="meal-row-actions">

                                        <a href="{{ route('admin.meal.items.edit', $item) }}"
                                           class="meal-action-btn edit"
                                           title="Edit Meal Item">

                                            <i class="fas fa-pen"></i>

                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.meal.items.destroy', $item) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this meal item?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="meal-action-btn delete"
                                                    title="Delete Meal Item">

                                                <i class="fas fa-trash-alt"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">

                                    <div class="meal-empty-state">

                                        <div class="empty-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>

                                        <h4>No Meal Items Found</h4>

                                        <p>
                                            No inventory records match your current
                                            search or filters.
                                        </p>

                                        <div class="empty-actions">

                                            @if($hasFilters)
                                                <a href="{{ route('admin.meal.items.index') }}"
                                                   class="meal-outline-btn">
                                                    <i class="fas fa-rotate-left"></i>
                                                    Clear Filters
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.meal.items.create') }}"
                                               class="meal-primary-btn">
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

            @if($totalItems > 0)

                <div class="table-footer">

                    <span>
                        Showing <strong>{{ $itemsCollection->count() }}</strong>
                        item(s) on this page
                    </span>

                    <span class="footer-success">
                        <i class="fas fa-circle-check"></i>
                        Inventory loaded successfully
                    </span>

                </div>

            @endif

        </section>

        {{-- RECENT STOCK MOVEMENTS --}}
        @if(isset($transactions))

            <section class="meal-card movements-card">

                <div class="table-heading">

                    <div class="table-heading-main">

                        <div class="heading-icon">
                            <i class="fas fa-clock-rotate-left"></i>
                        </div>

                        <div>
                            <div class="section-eyebrow">TRANSACTION HISTORY</div>
                            <h3>Recent Stock Movements</h3>
                            <p>Review stock-in and stock-out transactions.</p>
                        </div>

                    </div>

                    <a href="{{ route('admin.meal.items.stock.index') }}"
                       class="meal-outline-btn">
                        View All
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>

                <div class="table-responsive">

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

                                            <div class="meal-item-icon">
                                                <i class="fas fa-bowl-food"></i>
                                            </div>

                                            <div class="meal-item-details">

                                                <strong>
                                                    {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                </strong>

                                                <span>
                                                    <i class="fas fa-tag"></i>
                                                    {{ $transaction->mealItem->category ?? 'Uncategorized' }}
                                                </span>

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
                                            <strong class="stock-normal">
                                                {{ number_format((float) $transaction->quantity, 2) }}
                                            </strong>

                                            <span>{{ $transaction->unit }}</span>
                                        </div>

                                    </td>

                                    <td>

                                        @if($transaction->transaction_date)

                                            <span class="movement-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}
                                            </span>

                                        @else

                                            <span class="muted-text">—</span>

                                        @endif

                                    </td>

                                    <td>

                                        @php
                                            $reference = $transaction->transaction_type === 'stock_in'
                                                ? $transaction->supplier
                                                : $transaction->reason;
                                        @endphp

                                        {{ $reference ?: '—' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5">

                                        <div class="meal-empty-state">

                                            <div class="empty-icon">
                                                <i class="fas fa-clock-rotate-left"></i>
                                            </div>

                                            <h4>No Stock Movements Yet</h4>

                                            <p>
                                                Stock transactions will appear here
                                                after inventory movements are recorded.
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
   SCHOOL DIARY DESIGN SYSTEM
   MEAL ITEMS DASHBOARD
   Warm Paper | Forest Ink | Cream | Earthy Accents
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
    --green: #3a8060;
    --amber: #a87543;
    --red: #bd5148;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;

    min-height: calc(100vh - 70px);
    background: var(--paper-bg);
    color: var(--text);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.meal-items-page *,
.meal-items-page *::before,
.meal-items-page *::after {
    box-sizing: border-box;
}

.meal-items-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: auto;
}

.meal-items-page a,
.meal-items-page button {
    transition: all .2s ease;
}

.meal-items-page .meal-card,
.meal-items-page .meal-hero {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.meal-items-page .meal-hero {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 5px 18px rgba(38,59,53,.045);
}

.meal-items-page .hero-main {
    position: relative;
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 32px 28px 32px 55px;
    background:
        radial-gradient(ellipse at top left, rgba(244,240,223,.7), transparent 65%),
        var(--paper);
}

.meal-items-page .hero-main::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 33px;
    width: 2px;
    background: rgba(189,81,72,.35);
}

.meal-items-page .hero-icon,
.meal-items-page .heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 45px;
    width: 45px;
    height: 45px;
    border: 1px solid #e6dfce;
    border-radius: 10px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 18px;
}

.meal-items-page .hero-copy {
    min-width: 0;
}

.meal-items-page .hero-breadcrumb,
.meal-items-page .section-eyebrow {
    margin-bottom: 6px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.meal-items-page .hero-breadcrumb span {
    padding: 0 6px;
    color: #b8b09e;
}

.meal-items-page .hero-copy h2 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
}

.meal-items-page .hero-copy p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.meal-items-page .hero-note {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.meal-items-page .hero-side {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 235px;
    padding: 25px;
    color: #e9efe2;
    background: linear-gradient(135deg,#344f43,#20352c);
    text-align: center;
}

.meal-items-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 22px;
}

.meal-items-page .hero-side p {
    max-width: 310px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.8;
}

/* BUTTONS */

.meal-items-page .meal-primary-btn,
.meal-items-page .meal-outline-btn,
.meal-items-page .meal-apply-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none !important;
    cursor: pointer;
    white-space: nowrap;
}

.meal-items-page .meal-primary-btn {
    border: 1px solid var(--ink);
    color: white !important;
    background: var(--ink);
}

.meal-items-page .meal-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    background: var(--ink-hover);
    color: white !important;
}

.meal-items-page .hero-side .meal-primary-btn {
    border-color: var(--chalk);
    color: var(--ink) !important;
    background: var(--chalk);
}

.meal-items-page .meal-outline-btn {
    border: 1px solid #deded2;
    color: #59675d;
    background: var(--paper);
}

.meal-items-page .meal-outline-btn:hover {
    border-color: var(--ink);
    color: var(--ink);
    background: #f4f3e9;
}

.meal-items-page .meal-apply-btn {
    border: 1px solid var(--ink);
    color: white;
    background: var(--ink);
}

/* ALERTS */

.meal-items-page .meal-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 18px;
    padding: 13px 16px;
    border: 1px solid;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
}

.meal-items-page .meal-alert.success {
    border-color: #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.meal-items-page .meal-alert.error {
    align-items: flex-start;
    border-color: #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.meal-items-page .meal-alert button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: inherit;
    background: transparent;
    cursor: pointer;
}

/* KPI */

.meal-items-page .meal-kpis {
    display: grid;
    grid-template-columns: repeat(4,minmax(0,1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.meal-items-page .meal-kpi {
    --kpi-color: var(--blue);
    display: flex;
    flex-direction: column;
    gap: 13px;
    min-width: 0;
    padding: 21px 19px 15px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38,59,53,.045);
}

.meal-items-page .meal-kpi.blue { --kpi-color: #2b5d8a; }
.meal-items-page .meal-kpi.green { --kpi-color: #3a8060; }
.meal-items-page .meal-kpi.amber { --kpi-color: #a87543; }
.meal-items-page .meal-kpi.red { --kpi-color: #bd5148; }

.meal-items-page .meal-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 20px rgba(38,59,53,.08);
}

.meal-items-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
}

.meal-items-page .kpi-top i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid color-mix(in srgb,var(--kpi-color) 15%,white);
    border-radius: 9px;
    color: var(--kpi-color);
    background: color-mix(in srgb,var(--kpi-color) 10%,white);
    font-size: 16px;
}

.meal-items-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 34px;
    font-weight: 700;
    line-height: 1.1;
}

.meal-items-page .kpi-footer {
    display: flex;
    align-items: center;
    gap: 7px;
    min-height: 34px;
    padding-top: 10px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 10px;
}

.meal-items-page .kpi-footer i {
    color: var(--kpi-color);
}

/* GENERAL CARDS */

.meal-items-page .meal-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(38,59,53,.035);
}

.meal-items-page .section-heading,
.meal-items-page .table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 18px 20px;
    border-bottom: 1px dashed #d8d1bf;
}

.meal-items-page .section-heading {
    justify-content: flex-start;
}

.meal-items-page .heading-content h3,
.meal-items-page .table-heading h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.meal-items-page .heading-content p,
.meal-items-page .table-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
}

.meal-items-page .heading-reset {
    margin-left: auto;
}

/* FILTERS */

.meal-items-page .filter-card form {
    padding: 19px 20px;
}

.meal-items-page .filter-grid {
    display: grid;
    grid-template-columns: minmax(200px,2fr) minmax(140px,1fr) auto;
    align-items: end;
    gap: 13px;
}

.meal-items-page .filter-group {
    min-width: 0;
}

.meal-items-page .filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.meal-items-page .meal-control,
.meal-items-page .search-control {
    width: 100%;
    height: 41px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: var(--paper);
    font-family: inherit;
    font-size: 12px;
}

.meal-items-page .meal-control {
    padding: 0 10px;
}

.meal-items-page .search-control {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 11px;
}

.meal-items-page .search-control i {
    color: #929b8f;
}

.meal-items-page .search-control input {
    width: 100%;
    height: 100%;
    padding: 0;
    border: 0;
    outline: none;
    color: var(--text);
    background: transparent;
    font: inherit;
}

.meal-items-page .meal-control:focus,
.meal-items-page .search-control:focus-within {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58,128,96,.1);
}

.meal-items-page .filter-actions {
    display: flex;
    gap: 8px;
}

.meal-items-page .active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 11px 20px;
    border-top: 1px solid #eeece3;
    background: #faf9f4;
}

.meal-items-page .active-filter-list {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.meal-items-page .active-filter-title,
.meal-items-page .filter-results {
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
}

.meal-items-page .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 230px;
    padding: 5px 9px;
    overflow-wrap: anywhere;
    border: 1px solid #e6dfc9;
    border-radius: 6px;
    color: #765e32;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
}

/* TABLE HEADER */

.meal-items-page .table-heading-main {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-items-page .table-heading-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
}

.meal-items-page .record-counter {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 32px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

/* BULK DELETE */

.meal-items-page .meal-bulk-delete-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 36px;
    padding: 8px 13px;
    border: 1px solid #bd5148;
    border-radius: 7px;
    color: white;
    background: #bd5148;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
}

.meal-items-page .meal-bulk-delete-btn:hover {
    transform: translateY(-2px);
    border-color: #963b34;
    background: #963b34;
}

.meal-items-page #selectedMealCount {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 5px;
    border-radius: 20px;
    color: #bd5148;
    background: white;
    font-size: 10px;
}

/* CHECKBOX */

.meal-items-page .meal-checkbox {
    width: 17px;
    height: 17px;
    accent-color: #bd5148;
    cursor: pointer;
    vertical-align: middle;
}

.meal-items-page .checkbox-column {
    width: 45px;
    text-align: center !important;
}

/* TABLE */

.meal-items-page .table-responsive {
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
    letter-spacing: .4px;
    white-space: nowrap;
}

.meal-items-page .meal-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.meal-items-page .meal-table tbody tr:hover {
    background: #faf9f2;
}

.meal-items-page .meal-table tbody tr:last-child td {
    border-bottom: none;
}

/* ITEM INFORMATION */

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

.meal-items-page .meal-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.meal-items-page .meal-item-icon {
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

.meal-items-page .meal-item-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.meal-items-page .meal-item-details strong {
    max-width: 220px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.meal-items-page .meal-item-details span {
    color: #858e82;
    font-size: 10px;
}

.meal-items-page .meal-item-details span i {
    margin-right: 4px;
    color: var(--amber);
}

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

.meal-items-page .stock-normal {
    color: var(--green);
}

.meal-items-page .stock-low {
    color: var(--red);
}

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

/* STATUS */

.meal-items-page .meal-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.meal-items-page .meal-status > span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.meal-items-page .meal-status.active {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.meal-items-page .meal-status.active > span {
    background: #3a8060;
}

.meal-items-page .meal-status.inactive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.meal-items-page .meal-status.inactive > span {
    background: #bd5148;
}

.meal-items-page .muted-text {
    color: #929a8e;
}

/* ACTION BUTTONS */

.meal-items-page .meal-row-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.meal-items-page .meal-row-actions form {
    display: inline-flex;
    margin: 0;
}

.meal-items-page .meal-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 33px;
    height: 33px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: var(--paper);
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.meal-items-page .meal-action-btn:hover {
    transform: translateY(-2px);
}

.meal-items-page .meal-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.meal-items-page .meal-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* TRANSACTIONS */

.meal-items-page .transaction-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
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

.meal-items-page .movement-date {
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.meal-items-page .movement-date i {
    margin-right: 5px;
    color: var(--blue);
}

/* EMPTY STATE */

.meal-items-page .meal-empty-state {
    padding: 55px 20px;
    text-align: center;
}

.meal-items-page .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 68px;
    height: 68px;
    margin: 0 auto 15px;
    border: 1px solid #e5dfcc;
    border-radius: 14px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 25px;
}

.meal-items-page .meal-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
}

.meal-items-page .meal-empty-state p {
    max-width: 440px;
    margin: 0 auto 17px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.meal-items-page .empty-actions {
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
    flex-wrap: wrap;
    gap: 15px;
    padding: 15px 20px;
    border-top: 1px dashed #d8d1bf;
}

.meal-items-page .pagination-info {
    color: var(--muted);
    font-size: 11px;
}

.meal-items-page .pagination-info strong {
    color: var(--ink);
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

.meal-items-page .meal-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: white;
    background: var(--ink);
}

/* FOOTER */

.meal-items-page .table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    min-height: 52px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.meal-items-page .table-footer strong {
    color: var(--ink);
}

.meal-items-page .footer-success {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.meal-items-page .footer-success i {
    color: var(--green);
}

/* RESPONSIVE */

@media(max-width:1200px) {
    .meal-items-page .meal-kpis {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .meal-items-page .filter-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .meal-items-page .filter-actions {
        grid-column: span 2;
    }
}

@media(max-width:900px) {
    .meal-items-page .meal-hero {
        grid-template-columns: 1fr;
    }

    .meal-items-page .hero-side {
        min-height: 175px;
    }
}

@media(max-width:650px) {
    .meal-items-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .meal-items-page .hero-main {
        align-items: flex-start;
        padding: 22px 15px 22px 43px;
    }

    .meal-items-page .hero-main::before {
        left: 22px;
    }

    .meal-items-page .hero-icon {
        flex-basis: 42px;
        width: 42px;
        height: 42px;
    }

    .meal-items-page .hero-copy h2 {
        font-size: 23px;
    }

    .meal-items-page .hero-copy p {
        font-size: 12px;
    }

    .meal-items-page .meal-kpis {
        gap: 15px;
        margin-top: 30px;
    }

    .meal-items-page .meal-kpi {
        padding: 18px 14px 14px;
    }

    .meal-items-page .kpi-value {
        font-size: 29px;
    }

    .meal-items-page .section-heading,
    .meal-items-page .table-heading {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 15px;
    }

    .meal-items-page .heading-content h3,
    .meal-items-page .table-heading h3 {
        font-size: 16px;
    }

    .meal-items-page .heading-reset {
        margin-left: 0;
    }

    .meal-items-page .filter-card form {
        padding: 15px;
    }

    .meal-items-page .filter-grid {
        grid-template-columns: 1fr;
    }

    .meal-items-page .filter-actions {
        grid-column: auto;
    }

    .meal-items-page .filter-actions > * {
        flex: 1;
    }

    .meal-items-page .table-heading-actions {
        width: 100%;
        justify-content: space-between;
    }

    .meal-items-page .active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px 15px;
    }

    .meal-items-page .meal-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .meal-items-page .table-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media(max-width:400px) {
    .meal-items-page .meal-kpis {
        grid-template-columns: 1fr;
    }

    .meal-items-page .table-heading-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .meal-items-page .record-counter,
    .meal-items-page .meal-bulk-delete-btn {
        justify-content: center;
        width: 100%;
    }

    .meal-items-page .filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .meal-items-page .filter-actions > * {
        width: 100%;
    }

    .meal-items-page .empty-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .meal-items-page .empty-actions > * {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.meal-items-page :focus-visible {
    outline: 3px solid rgba(82,127,121,.45);
    outline-offset: 3px;
}

@media(prefers-reduced-motion:reduce) {
    .meal-items-page *,
    .meal-items-page *::before,
    .meal-items-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectAll = document.getElementById('selectAllMealItems');

    const checkboxes = Array.from(
        document.querySelectorAll('.meal-item-checkbox')
    );

    const selectedCount = document.getElementById('selectedMealCount');

    function updateMealSelection() {

        const selected = checkboxes.filter(function (checkbox) {
            return checkbox.checked;
        });

        if (selectedCount) {
            selectedCount.textContent = selected.length;
        }

        if (selectAll) {

            selectAll.checked =
                checkboxes.length > 0 &&
                selected.length === checkboxes.length;

            selectAll.indeterminate =
                selected.length > 0 &&
                selected.length < checkboxes.length;
        }
    }

    if (selectAll) {

        selectAll.addEventListener('change', function () {

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAll.checked;
            });

            updateMealSelection();
        });
    }

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', updateMealSelection);
    });

    updateMealSelection();

});

function confirmMealBulkDelete() {

    const selected = document.querySelectorAll(
        '.meal-item-checkbox:checked'
    );

    if (selected.length === 0) {
        alert('Please select at least one meal item.');
        return false;
    }

    return confirm(
        'Are you sure you want to delete ' +
        selected.length +
        ' selected meal item(s)? This action cannot be undone.'
    );
}
</script>

@endsection
