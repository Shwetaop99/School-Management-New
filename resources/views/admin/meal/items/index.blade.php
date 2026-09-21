@extends('layouts.app')

@section('title', 'Meal Items | Admin')

@section('content')

<div class="meal-items-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
             ========================================================= --}}
        <div class="meal-page-header mb-4">

            <div class="meal-heading-wrapper">

                <div class="meal-page-icon">
                    <i class="fas fa-utensils"></i>
                </div>

                <div>
                    <h4 class="meal-page-title mb-1">
                        Meal Items
                    </h4>

                    <p class="meal-page-subtitle mb-0">
                        Manage meal inventory, stock in and stock out activities.
                    </p>
                </div>

            </div>

            <div class="meal-header-actions">

                <a href="{{ route('admin.meal.items.stock.create') }}"
                   class="btn meal-stock-btn">

                    <i class="fas fa-right-left me-1"></i>
                    Stock In / Out

                </a>

                <a href="{{ route('admin.meal.items.create') }}"
                   class="btn meal-add-btn">

                    <i class="fas fa-plus me-1"></i>
                    Add Meal Item

                </a>

            </div>

        </div>


        {{-- =========================================================
             ALERTS
             ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show meal-alert"
                 role="alert">

                <div class="d-flex align-items-center">

                    <i class="fas fa-circle-check me-2"></i>

                    <span>{{ session('success') }}</span>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show meal-alert"
                 role="alert">

                <div class="d-flex align-items-center">

                    <i class="fas fa-circle-exclamation me-2"></i>

                    <span>{{ session('error') }}</span>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger meal-alert">

                <div class="d-flex align-items-start">

                    <i class="fas fa-circle-exclamation me-2 mt-1"></i>

                    <div>

                        <strong class="d-block mb-1">
                            Please check the following:
                        </strong>

                        <ul class="mb-0 ps-3">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
             SAFE COLLECTIONS
             ========================================================= --}}

        @php

            $itemsCollection = $items ?? collect();

            $transactionsCollection = isset($transactions)
                ? $transactions->getCollection()
                : collect();

            $totalItems = isset($items)
                ? $items->total()
                : $itemsCollection->count();

            $activeItems = $itemsCollection
                ->where('status', 'active')
                ->count();

            $lowStockItems = $itemsCollection
                ->filter(function ($item) {
                    return (float) $item->current_stock
                        <= (float) $item->minimum_stock;
                })
                ->count();

            $stockInCount = $transactionsCollection
                ->where('transaction_type', 'stock_in')
                ->count();

            $stockOutCount = $transactionsCollection
                ->where('transaction_type', 'stock_out')
                ->count();

        @endphp


        {{-- =========================================================
             SUMMARY CARDS
             ========================================================= --}}

        <div class="meal-summary-grid">

            {{-- TOTAL ITEMS --}}
            <div class="meal-summary-card summary-blue">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-boxes-stacked"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Total Meal Items
                    </span>

                    <strong>
                        {{ $totalItems }}
                    </strong>

                    <small>
                        Inventory items
                    </small>

                </div>

            </div>


            {{-- ACTIVE ITEMS --}}
            <div class="meal-summary-card summary-cyan">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-circle-check"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Active Items
                    </span>

                    <strong>
                        {{ $activeItems }}
                    </strong>

                    <small>
                        Currently available
                    </small>

                </div>

            </div>


            {{-- LOW STOCK --}}
            <div class="meal-summary-card summary-orange">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Low Stock
                    </span>

                    <strong>
                        {{ $lowStockItems }}
                    </strong>

                    <small>
                        Need attention
                    </small>

                </div>

            </div>


            {{-- STOCK MOVEMENTS --}}
            <div class="meal-summary-card summary-red">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-right-left"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Stock Movements
                    </span>

                    <strong>
                        {{ $stockInCount + $stockOutCount }}
                    </strong>

                    <small>
                        In / Out records
                    </small>

                </div>

            </div>

        </div>


        {{-- =========================================================
             MEAL ITEMS CARD
             ========================================================= --}}

        <div class="meal-card mb-4">

            <div class="meal-card-header">

                <div class="meal-card-title">

                    <div class="meal-title-icon">
                        <i class="fas fa-bowl-food"></i>
                    </div>

                    <div>

                        <h5>
                            Meal Items
                        </h5>

                        <span>
                            Manage available meal inventory items
                        </span>

                    </div>

                </div>

                <div class="meal-item-count">

                    <i class="fas fa-box me-1"></i>

                    {{ $totalItems }}

                    {{ $totalItems == 1 ? 'Item' : 'Items' }}

                </div>

            </div>


            {{-- =====================================================
                 ITEM FILTER
                 ===================================================== --}}

            <div class="meal-filter-wrapper">

                <form method="GET"
                      action="{{ route('admin.meal.items.index') }}"
                      class="meal-filter-form">

                    <div class="filter-search">

                        <i class="fas fa-search"></i>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search meal item or category...">

                    </div>


                    <div class="filter-control">

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <button type="submit"
                            class="filter-btn">

                        <i class="fas fa-filter me-1"></i>
                        Filter

                    </button>


                    @if(request()->hasAny(['search', 'status']))

                        <a href="{{ route('admin.meal.items.index') }}"
                           class="reset-filter-btn"
                           title="Clear Filters">

                            <i class="fas fa-rotate-left"></i>

                        </a>

                    @endif

                </form>

            </div>


            {{-- =====================================================
                 ITEMS TABLE
                 ===================================================== --}}

            <div class="meal-card-body">

                @if($itemsCollection->count())

                    <div class="table-responsive">

                        <table class="table meal-table">

                            <thead>

                                <tr>

                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Meal Item
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Current Stock
                                    </th>

                                    <th>
                                        Minimum Stock
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-center">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($itemsCollection as $item)

                                    @php

                                        $currentStock = (float) $item->current_stock;
                                        $minimumStock = (float) $item->minimum_stock;

                                        $isLowStock = $currentStock <= $minimumStock;

                                    @endphp

                                    <tr>

                                        {{-- NUMBER --}}
                                        <td class="ps-4">

                                            <span class="item-number">

                                                @if(isset($items) && method_exists($items, 'firstItem'))

                                                    {{ $items->firstItem() + $loop->index }}

                                                @else

                                                    {{ $loop->iteration }}

                                                @endif

                                            </span>

                                        </td>


                                        {{-- ITEM --}}
                                        <td>

                                            <div class="item-name-wrapper">

                                                <div class="item-icon">

                                                    <i class="fas fa-bowl-food"></i>

                                                </div>

                                                <div class="item-details">

                                                    <span class="item-name">

                                                        {{ $item->item_name }}

                                                    </span>

                                                    <small class="item-description">

                                                        {{ $item->unit }}

                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- CATEGORY --}}
                                        <td>

                                            @if($item->category)

                                                <span class="category-badge">

                                                    {{ $item->category }}

                                                </span>

                                            @else

                                                <span class="empty-value">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- CURRENT STOCK --}}
                                        <td>

                                            <div class="stock-value-wrapper">

                                                <strong class="{{ $isLowStock ? 'stock-low' : 'stock-normal' }}">

                                                    {{ number_format($currentStock, 2) }}

                                                </strong>

                                                <span>
                                                    {{ $item->unit }}
                                                </span>

                                            </div>

                                            @if($isLowStock)

                                                <small class="low-stock-label">

                                                    <i class="fas fa-triangle-exclamation me-1"></i>
                                                    Low Stock

                                                </small>

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

                                            @if($item->status === 'active')

                                                <span class="status-badge status-active">

                                                    <span class="status-dot"></span>
                                                    Active

                                                </span>

                                            @else

                                                <span class="status-badge status-inactive">

                                                    <span class="status-dot"></span>
                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ACTIONS --}}
                                        <td class="text-center">

                                            <div class="action-wrapper">

                                                {{-- STOCK --}}
                                                <a href="{{ route('admin.meal.items.stock.create') }}"
                                                   class="action-btn stock-action"
                                                   title="Stock In / Stock Out">

                                                    <i class="fas fa-right-left"></i>

                                                </a>


                                                {{-- EDIT --}}
                                                <a href="{{ route('admin.meal.items.edit', $item) }}"
                                                   class="action-btn edit-btn"
                                                   title="Edit Item">

                                                    <i class="fas fa-pen"></i>

                                                </a>


                                                {{-- DELETE --}}
                                                <form method="POST"
                                                      action="{{ route('admin.meal.items.destroy', $item) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this meal item?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="action-btn delete-btn"
                                                            title="Delete Item">

                                                        <i class="fas fa-trash"></i>

                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}

                    @if(isset($items) && $items->hasPages())

                        <div class="meal-pagination">

                            {{ $items->links() }}

                        </div>

                    @endif


                @else

                    <div class="empty-state">

                        <div class="empty-icon">

                            <i class="fas fa-bowl-food"></i>

                        </div>

                        <h5>
                            No Meal Items Found
                        </h5>

                        <p>
                            No meal items match your current filters.
                            Add your first meal item to start managing inventory.
                        </p>

                        <a href="{{ route('admin.meal.items.create') }}"
                           class="btn meal-add-btn">

                            <i class="fas fa-plus me-1"></i>
                            Add Meal Item

                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================================================
             RECENT STOCK MOVEMENTS
             ========================================================= --}}

        @if(isset($transactions))

            <div class="meal-card">

                <div class="meal-card-header">

                    <div class="meal-card-title">

                        <div class="meal-title-icon">
                            <i class="fas fa-right-left"></i>
                        </div>

                        <div>

                            <h5>
                                Recent Stock Movements
                            </h5>

                            <span>
                                Stock In and Stock Out activity
                            </span>

                        </div>

                    </div>

                    <a href="{{ route('admin.meal.items.stock.index') }}"
                       class="view-all-link">

                        View All
                        <i class="fas fa-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="meal-card-body">

                    @if($transactions->count())

                        <div class="table-responsive">

                            <table class="table movement-table">

                                <thead>

                                    <tr>

                                        <th class="ps-4">
                                            Item
                                        </th>

                                        <th>
                                            Type
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Supplier / Reason
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($transactions as $transaction)

                                        <tr>

                                            <td class="ps-4">

                                                <div class="movement-item">

                                                    <div class="movement-icon">

                                                        <i class="fas fa-bowl-food"></i>

                                                    </div>

                                                    <div>

                                                        <strong>
                                                            {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                        </strong>

                                                        <small>
                                                            {{ $transaction->mealItem->category ?? 'Uncategorized' }}
                                                        </small>

                                                    </div>

                                                </div>

                                            </td>


                                            <td>

                                                @if($transaction->transaction_type === 'stock_in')

                                                    <span class="transaction-badge transaction-in">

                                                        <i class="fas fa-arrow-down"></i>
                                                        Stock In

                                                    </span>

                                                @else

                                                    <span class="transaction-badge transaction-out">

                                                        <i class="fas fa-arrow-up"></i>
                                                        Stock Out

                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                <strong class="quantity-value">

                                                    {{ number_format((float) $transaction->quantity, 2) }}

                                                </strong>

                                                <span class="quantity-unit">

                                                    {{ $transaction->unit }}

                                                </span>

                                            </td>


                                            <td>

                                                <span class="movement-date">

                                                    {{ optional($transaction->transaction_date)->format('d M Y') }}

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

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="movement-empty">

                            <i class="fas fa-right-left"></i>

                            <span>
                                No stock movements recorded yet.
                            </span>

                            <a href="{{ route('admin.meal.items.stock.create') }}">
                                Add Stock Movement
                            </a>

                        </div>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.meal-items-page {
    min-height: calc(100vh - 70px);
    background: #f6f8fb;
    color: #263142;
    font-family: 'Inter', sans-serif;
}


/* =========================================================
   HEADER
   ========================================================= */

.meal-page-header {
    min-height: 52px;
    width: 100%;
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.meal-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 13px;
}

.meal-page-icon {
    width: 44px;
    height: 44px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: linear-gradient(135deg, #147cf5, #1268ca);
    color: #ffffff;
    font-size: 18px;
    box-shadow: 0 6px 16px rgba(20, 124, 245, 0.18);
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


/* =========================================================
   HEADER BUTTONS
   ========================================================= */

.meal-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-left: auto;
}

.meal-add-btn,
.meal-stock-btn {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    min-height: 40px;
    padding: 9px 16px;
    border-radius: 7px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.18s ease;
}

.meal-add-btn {
    background: linear-gradient(135deg, #147cf5, #1268ca);
    border: 1px solid #147cf5;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(20, 124, 245, 0.16);
}

.meal-add-btn:hover {
    background: linear-gradient(135deg, #1268ca, #1058ad);
    border-color: #1268ca;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow: 0 7px 16px rgba(20, 124, 245, 0.22);
}

.meal-stock-btn {
    background: #ffffff;
    border: 1px solid #d8e5f5;
    color: #147cf5 !important;
}

.meal-stock-btn:hover {
    background: #f1f7ff;
    border-color: #bcd8fa;
    color: #1268ca !important;
    transform: translateY(-1px);
}


/* =========================================================
   ALERTS
   ========================================================= */

.meal-alert {
    border: 0;
    border-radius: 8px;
    padding: 13px 16px;
    margin-bottom: 18px;
    font-size: 13.5px;
    box-shadow: 0 2px 8px rgba(31, 41, 55, 0.04);
}

.meal-alert.alert-success {
    background: #eaf8f0;
    color: #198754;
}

.meal-alert.alert-danger {
    background: #fff0ef;
    color: #dc3545;
}


/* =========================================================
   SUMMARY
   ========================================================= */

.meal-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 22px;
}

.meal-summary-card {
    position: relative;
    min-height: 118px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    overflow: hidden;
    border-radius: 10px;
    color: #ffffff;
    box-shadow: 0 6px 16px rgba(25, 55, 95, 0.08);
    transition: transform 0.22s ease, box-shadow 0.22s ease;
}

.meal-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 11px 25px rgba(25, 55, 95, 0.14);
}

.summary-blue {
    background: linear-gradient(135deg, #147cf5, #1268ca);
}

.summary-orange {
    background: linear-gradient(135deg, #ffb238, #ff9d1c);
}

.summary-red {
    background: linear-gradient(135deg, #ff6d61, #f65343);
}

.summary-cyan {
    background: linear-gradient(135deg, #2bcfe8, #18b5d5);
}

.summary-pattern {
    position: absolute;
    width: 125px;
    height: 125px;
    right: -42px;
    top: -50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.10);
}

.summary-pattern::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    right: 48px;
    top: 72px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.07);
}

.summary-icon {
    position: relative;
    z-index: 2;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: rgba(255, 255, 255, 0.18);
    border: 1px solid rgba(255, 255, 255, 0.15);
    font-size: 19px;
}

.summary-content {
    position: relative;
    z-index: 2;
    min-width: 0;
}

.summary-label {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 500;
    opacity: 0.92;
}

.summary-content strong {
    display: block;
    font-size: 26px;
    line-height: 1;
    font-weight: 700;
    letter-spacing: -0.4px;
}

.summary-content small {
    display: block;
    margin-top: 6px;
    font-size: 10.5px;
    font-weight: 500;
    opacity: 0.78;
}


/* =========================================================
   CARD
   ========================================================= */

.meal-card {
    background: #ffffff;
    border: 1px solid #edf0f5;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(31, 41, 55, 0.045);
}

.meal-card-header {
    min-height: 68px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.meal-card-title {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.meal-title-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 15px;
}

.meal-card-title h5 {
    margin: 0 0 2px;
    color: #273142;
    font-size: 15.5px;
    font-weight: 650;
}

.meal-card-title span {
    color: #8a94a3;
    font-size: 12px;
}

.meal-item-count {
    flex-shrink: 0;
    color: #7a8494;
    background: #f6f8fb;
    border: 1px solid #edf0f5;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: 600;
}


/* =========================================================
   FILTER
   ========================================================= */

.meal-filter-wrapper {
    padding: 14px 20px;
    background: #fbfcfe;
    border-bottom: 1px solid #edf0f5;
}

.meal-filter-form {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

.filter-search {
    position: relative;
    flex: 1;
    min-width: 260px;
}

.filter-search i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa3af;
    font-size: 12px;
    pointer-events: none;
}

.filter-search .form-control {
    padding-left: 35px;
}

.filter-control {
    width: 165px;
}

.meal-filter-form .form-control,
.meal-filter-form .form-select {
    height: 38px;
    border: 1px solid #e3e8ef;
    border-radius: 7px;
    color: #4d5868;
    background: #ffffff;
    font-size: 12.5px;
    box-shadow: none;
}

.meal-filter-form .form-control:focus,
.meal-filter-form .form-select:focus {
    border-color: #9bc5fa;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.08);
}

.filter-btn {
    height: 38px;
    padding: 0 14px;
    border: 1px solid #147cf5;
    border-radius: 7px;
    background: #147cf5;
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 600;
    transition: all 0.18s ease;
}

.filter-btn:hover {
    background: #1268ca;
    border-color: #1268ca;
    transform: translateY(-1px);
}

.reset-filter-btn {
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e3e8ef;
    border-radius: 7px;
    background: #ffffff;
    color: #7a8494;
    text-decoration: none;
    font-size: 12px;
    transition: all 0.18s ease;
}

.reset-filter-btn:hover {
    color: #147cf5;
    background: #f1f7ff;
    border-color: #cfe3ff;
}


/* =========================================================
   TABLE
   ========================================================= */

.meal-card-body {
    background: #ffffff;
}

.meal-table {
    width: 100%;
    min-width: 980px;
    margin: 0;
    font-size: 13px;
}

.meal-table thead th {
    padding: 13px 12px;
    background: #f8f9fb;
    color: #687386;
    border-bottom: 1px solid #edf0f5;
    font-size: 11px;
    font-weight: 650;
    text-transform: uppercase;
    letter-spacing: 0.35px;
    white-space: nowrap;
}

.meal-table tbody td {
    padding: 14px 12px;
    color: #4d5868;
    border-bottom: 1px solid #f0f2f5;
    vertical-align: middle;
}

.meal-table tbody tr {
    transition: background 0.15s ease;
}

.meal-table tbody tr:hover {
    background: #f8fbff;
}

.meal-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   ITEM
   ========================================================= */

.item-number {
    width: 29px;
    height: 29px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #f4f7fb;
    color: #8993a2;
    font-size: 11.5px;
    font-weight: 600;
}

.item-name-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 190px;
}

.item-icon {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eef5ff;
    color: #147cf5;
    font-size: 13px;
    transition: all 0.18s ease;
}

.meal-table tbody tr:hover .item-icon {
    background: #147cf5;
    color: #ffffff;
    transform: scale(1.04);
}

.item-details {
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.item-name {
    display: block;
    max-width: 210px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #263142;
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1.4;
}

.item-description {
    display: block;
    margin-top: 2px;
    color: #9aa3af;
    font-size: 10.5px;
}


/* =========================================================
   CATEGORY
   ========================================================= */

.category-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 6px;
    background: #f1f7ff;
    color: #147cf5;
    font-size: 11px;
    font-weight: 600;
}


/* =========================================================
   STOCK
   ========================================================= */

.stock-value-wrapper {
    display: flex;
    align-items: baseline;
    gap: 5px;
}

.stock-value-wrapper strong {
    font-size: 13.5px;
    font-weight: 700;
}

.stock-value-wrapper span {
    color: #8a94a3;
    font-size: 10.5px;
}

.stock-normal {
    color: #198754;
}

.stock-low {
    color: #dc3545;
}

.low-stock-label {
    display: block;
    margin-top: 3px;
    color: #dc3545;
    font-size: 9.5px;
    font-weight: 600;
}

.minimum-stock {
    color: #687386;
    font-size: 12px;
    font-weight: 500;
}


/* =========================================================
   STATUS
   ========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 9px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.status-active {
    background: #eaf8f0;
    color: #198754;
}

.status-inactive {
    background: #f1f3f5;
    color: #7a8494;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}


/* =========================================================
   ACTIONS
   ========================================================= */

.action-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.action-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 6px;
    background: #ffffff;
    font-size: 11.5px;
    text-decoration: none;
    transition: all 0.18s ease;
}

.stock-action {
    color: #147cf5;
    border: 1px solid #cfe3ff;
}

.stock-action:hover {
    color: #ffffff;
    background: #147cf5;
    border-color: #147cf5;
    transform: translateY(-1px);
}

.edit-btn {
    color: #ff9d1c;
    border: 1px solid #ffe0ad;
}

.edit-btn:hover {
    color: #ffffff;
    background: #ff9d1c;
    border-color: #ff9d1c;
    transform: translateY(-1px);
}

.delete-btn {
    color: #dc3545;
    border: 1px solid #ffd0cc;
}

.delete-btn:hover {
    color: #ffffff;
    background: #dc3545;
    border-color: #dc3545;
    transform: translateY(-1px);
}


/* =========================================================
   RECENT MOVEMENTS
   ========================================================= */

.view-all-link {
    display: inline-flex;
    align-items: center;
    color: #147cf5;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
}

.view-all-link:hover {
    color: #1268ca;
}

.movement-table {
    width: 100%;
    min-width: 780px;
    margin: 0;
}

.movement-table thead th {
    padding: 12px;
    background: #f8f9fb;
    color: #687386;
    border-bottom: 1px solid #edf0f5;
    font-size: 10.5px;
    font-weight: 650;
    text-transform: uppercase;
    letter-spacing: 0.35px;
}

.movement-table tbody td {
    padding: 13px 12px;
    border-bottom: 1px solid #f0f2f5;
    color: #4d5868;
    font-size: 12.5px;
    vertical-align: middle;
}

.movement-table tbody tr:last-child td {
    border-bottom: 0;
}

.movement-table tbody tr:hover {
    background: #f8fbff;
}

.movement-item {
    display: flex;
    align-items: center;
    gap: 9px;
}

.movement-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 7px;
    background: #eef5ff;
    color: #147cf5;
}

.movement-item strong {
    display: block;
    color: #263142;
    font-size: 12.5px;
    font-weight: 600;
}

.movement-item small {
    display: block;
    margin-top: 2px;
    color: #9aa3af;
    font-size: 9.5px;
}

.transaction-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    min-width: 82px;
    padding: 5px 8px;
    border-radius: 6px;
    font-size: 10.5px;
    font-weight: 650;
    white-space: nowrap;
}

.transaction-in {
    background: #e8f8ef;
    color: #198754;
}

.transaction-out {
    background: #fff0ef;
    color: #dc3545;
}

.quantity-value {
    color: #263142;
    font-size: 12.5px;
    font-weight: 650;
}

.quantity-unit {
    margin-left: 4px;
    color: #8a94a3;
    font-size: 10px;
}

.movement-date {
    color: #586474;
    font-size: 11.5px;
    font-weight: 600;
}

.movement-empty {
    padding: 35px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    color: #8a94a3;
    font-size: 12.5px;
}

.movement-empty > i {
    color: #147cf5;
}

.movement-empty a {
    color: #147cf5;
    font-weight: 600;
    text-decoration: none;
}

.movement-empty a:hover {
    color: #1268ca;
}


/* =========================================================
   PAGINATION
   ========================================================= */

.meal-pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 14px 18px;
    border-top: 1px solid #edf0f5;
    background: #ffffff;
}

.meal-pagination .pagination {
    margin: 0;
}

.meal-pagination .page-link {
    border-color: #e7ebf0;
    color: #687386;
    font-size: 12.5px;
    padding: 6px 10px;
}

.meal-pagination .page-link:hover {
    background: #f1f7ff;
    color: #147cf5;
    border-color: #cfe3ff;
}

.meal-pagination .page-item.active .page-link {
    background: #147cf5;
    border-color: #147cf5;
    color: #ffffff;
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-icon {
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    border-radius: 50%;
    background: #eef5ff;
    color: #147cf5;
    font-size: 27px;
}

.empty-state h5 {
    margin-bottom: 7px;
    color: #303846;
    font-size: 16px;
    font-weight: 650;
}

.empty-state p {
    max-width: 470px;
    margin: 0 auto 18px;
    color: #8a94a3;
    font-size: 13.5px;
    line-height: 1.6;
}


/* =========================================================
   RESPONSIVE — TABLET
   ========================================================= */

@media (max-width: 1100px) {

    .meal-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .meal-table {
        min-width: 980px;
    }
}


/* =========================================================
   RESPONSIVE — MOBILE
   ========================================================= */

@media (max-width: 768px) {

    .meal-page-header {
        align-items: flex-start !important;
    }

    .meal-header-actions {
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .meal-page-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }

    .meal-page-title {
        font-size: 20px;
    }

    .meal-page-subtitle {
        font-size: 12.5px;
    }

    .meal-card-header {
        padding: 13px 15px;
    }

    .meal-item-count {
        display: none;
    }

    .meal-filter-wrapper {
        padding: 12px 15px;
    }

    .filter-search {
        min-width: 100%;
        flex: 100%;
    }

    .filter-control {
        width: calc(50% - 5px);
    }

    .filter-btn {
        flex: 1;
    }

    .meal-table {
        min-width: 980px;
    }
}


/* =========================================================
   RESPONSIVE — SMALL MOBILE
   ========================================================= */

@media (max-width: 576px) {

    .meal-items-page .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .meal-page-header {
        flex-direction: column;
        align-items: stretch !important;
    }

    .meal-heading-wrapper {
        width: 100%;
    }

    .meal-header-actions {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .meal-add-btn,
    .meal-stock-btn {
        width: 100%;
    }

    .meal-summary-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .meal-summary-card {
        min-height: 105px;
    }

    .meal-card {
        border-radius: 8px;
    }

    .meal-card-title span {
        display: none;
    }

    .filter-control {
        width: 100%;
    }

    .filter-btn {
        width: 100%;
        flex: none;
    }

    .reset-filter-btn {
        width: 100%;
    }

    .movement-empty {
        flex-direction: column;
        text-align: center;
    }
}

</style>

@endpush
