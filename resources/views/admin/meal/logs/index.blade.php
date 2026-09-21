@extends('layouts.app')

@section('title', 'Meal Stock Logs | Admin')

@section('content')

<div class="meal-logs-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
             ========================================================= --}}
        <div class="meal-page-header mb-4">

            <div class="meal-heading-wrapper">

                <div class="meal-heading-icon">
                    <i class="fas fa-clock-rotate-left"></i>
                </div>

                <div>
                    <h1 class="meal-page-title">
                        Meal Stock Logs
                    </h1>

                    <p class="meal-page-subtitle mb-0">
                        Track, monitor and audit all meal inventory movements.
                    </p>
                </div>

            </div>

            <a href="{{ route('admin.meal.items.index') }}"
               class="btn meal-secondary-btn">

                <i class="fas fa-boxes-stacked me-1"></i>
                Meal Items

            </a>

        </div>


        {{-- =========================================================
             SUCCESS / ERROR MESSAGES
             ========================================================= --}}
        @if(session('success'))

            <div class="alert meal-alert meal-alert-success mb-4">

                <i class="fas fa-circle-check"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        @if(session('error'))

            <div class="alert meal-alert meal-alert-danger mb-4">

                <i class="fas fa-circle-exclamation"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>

        @endif


        {{-- =========================================================
             KPI CARDS
             ========================================================= --}}
        <div class="meal-log-stats-grid mb-4">

            {{-- Total Movements --}}
            <div class="meal-log-stat-card blue-card">

                <div class="stat-card-top">

                    <div>

                        <div class="meal-log-stat-label">
                            Total Movements
                        </div>

                        <div class="meal-log-stat-value">
                            {{ number_format($totalMovements) }}
                        </div>

                        <div class="meal-log-stat-note">
                            All stock movements
                        </div>

                    </div>

                    <div class="meal-log-stat-icon">
                        <i class="fas fa-arrow-right-arrow-left"></i>
                    </div>

                </div>

                <div class="stat-decoration"></div>

            </div>


            {{-- Stock In --}}
            <div class="meal-log-stat-card green-card">

                <div class="stat-card-top">

                    <div>

                        <div class="meal-log-stat-label">
                            Stock In
                        </div>

                        <div class="meal-log-stat-value">
                            {{ number_format((float) $totalStockIn, 2) }}
                        </div>

                        <div class="meal-log-stat-note">
                            Incoming quantity
                        </div>

                    </div>

                    <div class="meal-log-stat-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>

                </div>

                <div class="stat-decoration"></div>

            </div>


            {{-- Stock Out --}}
            <div class="meal-log-stat-card red-card">

                <div class="stat-card-top">

                    <div>

                        <div class="meal-log-stat-label">
                            Stock Out
                        </div>

                        <div class="meal-log-stat-value">
                            {{ number_format((float) $totalStockOut, 2) }}
                        </div>

                        <div class="meal-log-stat-note">
                            Outgoing quantity
                        </div>

                    </div>

                    <div class="meal-log-stat-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>

                </div>

                <div class="stat-decoration"></div>

            </div>


            {{-- Low Stock --}}
            <div class="meal-log-stat-card orange-card">

                <div class="stat-card-top">

                    <div>

                        <div class="meal-log-stat-label">
                            Low Stock Items
                        </div>

                        <div class="meal-log-stat-value">
                            {{ number_format($lowStockItems) }}
                        </div>

                        <div class="meal-log-stat-note">
                            At or below minimum level
                        </div>

                    </div>

                    <div class="meal-log-stat-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>

                </div>

                <div class="stat-decoration"></div>

            </div>

        </div>


        {{-- =========================================================
             FILTER CARD
             ========================================================= --}}
        <div class="meal-filter-card mb-4">

            <div class="meal-filter-header">

                <div class="meal-filter-title">

                    <div class="meal-filter-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>

                        <h5>
                            Filter Stock Logs
                        </h5>

                        <span>
                            Search and filter meal inventory movements
                        </span>

                    </div>

                </div>

            </div>


            <div class="meal-filter-body">

                <form method="GET"
                      action="{{ route('admin.meal.logs.index') }}">

                    <div class="row g-3">

                        {{-- Date From --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="date_from"
                                   class="meal-form-label">

                                Date From

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-calendar-day"></i>

                                <input type="date"
                                       name="date_from"
                                       id="date_from"
                                       class="form-control meal-form-control"
                                       value="{{ request('date_from') }}">

                            </div>

                        </div>


                        {{-- Date To --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="date_to"
                                   class="meal-form-label">

                                Date To

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-calendar-check"></i>

                                <input type="date"
                                       name="date_to"
                                       id="date_to"
                                       class="form-control meal-form-control"
                                       value="{{ request('date_to') }}">

                            </div>

                        </div>


                        {{-- Meal Item --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="meal_item_id"
                                   class="meal-form-label">

                                Meal Item

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-box"></i>

                                <select name="meal_item_id"
                                        id="meal_item_id"
                                        class="form-select meal-form-control">

                                    <option value="">
                                        All Items
                                    </option>

                                    @foreach($items as $item)

                                        <option value="{{ $item->id }}"
                                            {{ (string) request('meal_item_id') === (string) $item->id ? 'selected' : '' }}>

                                            {{ $item->item_name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Category --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="category"
                                   class="meal-form-label">

                                Category

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-layer-group"></i>

                                <select name="category"
                                        id="category"
                                        class="form-select meal-form-control">

                                    <option value="">
                                        All Categories
                                    </option>

                                    @foreach($categories as $category)

                                        <option value="{{ $category }}"
                                            {{ request('category') === $category ? 'selected' : '' }}>

                                            {{ $category }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Transaction Type --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="action"
                                   class="meal-form-label">

                                Transaction Type

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-right-left"></i>

                                <select name="action"
                                        id="action"
                                        class="form-select meal-form-control">

                                    <option value="">
                                        All Types
                                    </option>

                                    <option value="stock_in"
                                        {{ request('action') === 'stock_in' ? 'selected' : '' }}>

                                        Stock In

                                    </option>

                                    <option value="stock_out"
                                        {{ request('action') === 'stock_out' ? 'selected' : '' }}>

                                        Stock Out

                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Search --}}
                        <div class="col-xl-2 col-lg-4 col-md-6">

                            <label for="search"
                                   class="meal-form-label">

                                Search

                            </label>

                            <div class="filter-input-wrapper">

                                <i class="fas fa-magnifying-glass"></i>

                                <input type="text"
                                       name="search"
                                       id="search"
                                       class="form-control meal-form-control"
                                       placeholder="Item, reason, remarks..."
                                       value="{{ request('search') }}">

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="col-12">

                            <div class="meal-filter-actions">

                                <button type="submit"
                                        class="btn meal-filter-btn">

                                    <i class="fas fa-magnifying-glass me-1"></i>
                                    Apply Filter

                                </button>


                                <a href="{{ route('admin.meal.logs.index') }}"
                                   class="btn meal-reset-btn">

                                    <i class="fas fa-rotate-left me-1"></i>
                                    Reset

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =========================================================
             STOCK MOVEMENT HISTORY
             ========================================================= --}}
        <div class="meal-table-card">

            <div class="meal-table-header">

                <div class="meal-table-title">

                    <div class="meal-table-title-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>

                    <div>

                        <h5>
                            Stock Movement History
                        </h5>

                        <span>
                            Complete audit trail of meal inventory changes
                        </span>

                    </div>

                </div>


                <div class="meal-record-count">

                    <i class="fas fa-database me-1"></i>

                    {{ number_format($logs->total()) }}

                    {{ $logs->total() == 1 ? 'Record' : 'Records' }}

                </div>

            </div>


            <div class="table-responsive">

                <table class="table meal-log-table mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Date & Time</th>

                            <th>Transaction ID</th>

                            <th>Item</th>

                            <th>Category</th>

                            <th>Type</th>

                            <th>Quantity</th>

                            <th>Stock Before</th>

                            <th>Stock After</th>

                            <th>Reason</th>

                            <th>Performed By</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $log)

                            <tr>

                                {{-- Number --}}
                                <td>

                                    <span class="log-number">

                                        {{ $logs->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- Date --}}
                                <td>

                                    <div class="log-date">

                                        {{ $log->created_at?->format('d M Y') ?? '—' }}

                                    </div>

                                    <div class="log-time">

                                        {{ $log->created_at?->format('h:i A') ?? '—' }}

                                    </div>

                                </td>


                                {{-- Transaction ID --}}
                                <td>

                                    @if($log->stock_transaction_id)

                                        <span class="transaction-id">

                                            <i class="fas fa-hashtag me-1"></i>

                                            {{ str_pad($log->stock_transaction_id, 5, '0', STR_PAD_LEFT) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Item --}}
                                <td>

                                    @if($log->mealItem)

                                        <div class="log-item">

                                            <div class="log-item-icon">

                                                <i class="fas fa-utensils"></i>

                                            </div>

                                            <div>

                                                <div class="log-item-name">

                                                    {{ $log->mealItem->item_name }}

                                                </div>

                                                <div class="log-item-category">

                                                    {{ $log->unit }}

                                                </div>

                                            </div>

                                        </div>

                                    @else

                                        <span class="deleted-item">

                                            <i class="fas fa-trash-can me-1"></i>
                                            Item Deleted

                                        </span>

                                    @endif

                                </td>


                                {{-- Category --}}
                                <td>

                                    @if($log->mealItem)

                                        <span class="category-badge">

                                            {{ $log->mealItem->category ?: 'Other' }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    @if($log->action === 'stock_in')

                                        <span class="log-action-badge stock-in">

                                            <i class="fas fa-arrow-down"></i>

                                            Stock In

                                        </span>

                                    @elseif($log->action === 'stock_out')

                                        <span class="log-action-badge stock-out">

                                            <i class="fas fa-arrow-up"></i>

                                            Stock Out

                                        </span>

                                    @else

                                        <span class="log-action-badge">

                                            {{ ucfirst(str_replace('_', ' ', $log->action ?? 'Unknown')) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- Quantity --}}
                                <td>

                                    <div class="quantity-wrapper">

                                        <span class="log-quantity">

                                            {{ number_format((float) $log->quantity, 2) }}

                                        </span>

                                        <span class="log-unit">
                                            {{ $log->unit }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Stock Before --}}
                                <td>

                                    <div class="stock-value-wrapper">

                                        <span class="stock-value">

                                            {{ number_format((float) $log->previous_stock, 2) }}

                                        </span>

                                        <span class="stock-unit">
                                            {{ $log->unit }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Stock After --}}
                                <td>

                                    <div class="stock-value-wrapper updated-stock">

                                        <span class="stock-value">

                                            {{ number_format((float) $log->updated_stock, 2) }}

                                        </span>

                                        <span class="stock-unit">
                                            {{ $log->unit }}
                                        </span>

                                    </div>

                                </td>


                                {{-- Reason --}}
                                <td>

                                    @if($log->reason)

                                        <span class="log-reason"
                                              title="{{ $log->reason }}">

                                            {{ \Illuminate\Support\Str::limit($log->reason, 25) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Performed By --}}
                                <td>

                                    @if($log->performed_by)

                                        <span class="performed-by">

                                            <span class="performed-icon">
                                                <i class="fas fa-user-shield"></i>
                                            </span>

                                            Admin #{{ $log->performed_by }}

                                        </span>

                                    @else

                                        <span class="performed-by system">

                                            <span class="performed-icon">
                                                <i class="fas fa-gear"></i>
                                            </span>

                                            System

                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    <a href="{{ route('admin.meal.logs.show', $log) }}"
                                       class="log-view-btn"
                                       title="View Details">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="12">

                                    <div class="meal-empty-state">

                                        <div class="meal-empty-icon">

                                            <i class="fas fa-clock-rotate-left"></i>

                                        </div>

                                        <h5>
                                            No Stock Logs Found
                                        </h5>

                                        <p>
                                            There are no stock movement logs
                                            matching your current filters.
                                        </p>

                                        <a href="{{ route('admin.meal.logs.index') }}"
                                           class="btn meal-reset-empty-btn">

                                            <i class="fas fa-rotate-left me-1"></i>
                                            Clear Filters

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($logs->hasPages())

                <div class="meal-pagination">

                    {{ $logs->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   BASE PAGE
   ========================================================= */

.meal-logs-page {
    min-height: calc(100vh - 70px);
    background: #f6f8fb;
    color: #263142;
    font-family: 'Inter', sans-serif;
}

.meal-logs-page .container-fluid {
    max-width: 100%;
}


/* =========================================================
   PAGE HEADER
   ========================================================= */

.meal-page-header {
    min-height: 54px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.meal-heading-wrapper {
    display: flex;
    align-items: center;
    gap: 13px;
}

.meal-heading-icon {
    width: 46px;
    height: 46px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );
    color: #ffffff;
    font-size: 18px;
    box-shadow:
        0 6px 15px rgba(20, 124, 245, 0.18);
}

.meal-page-title {
    margin: 0;
    color: #1f2937;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: -0.4px;
}

.meal-page-subtitle {
    margin-top: 4px;
    color: #7a8494;
    font-size: 13px;
    line-height: 1.5;
}

.meal-secondary-btn {
    min-height: 41px;
    padding: 9px 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 3px;
    background: #ffffff;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    color: #586474 !important;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 2px 7px rgba(31, 41, 55, 0.03);
    transition: all 0.2s ease;
}

.meal-secondary-btn:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #147cf5 !important;
    transform: translateY(-1px);
}


/* =========================================================
   ALERTS
   ========================================================= */

.meal-alert {
    min-height: 45px;
    padding: 11px 15px;
    display: flex;
    align-items: center;
    gap: 9px;
    border: 0;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
}

.meal-alert-success {
    background: #eaf8f1;
    color: #168657;
}

.meal-alert-danger {
    background: #fff0ef;
    color: #dc4436;
}


/* =========================================================
   KPI CARDS
   ========================================================= */

.meal-log-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    width: 100%;
}

.meal-log-stat-card {
    position: relative;
    min-height: 138px;
    padding: 20px;
    overflow: hidden;
    border-radius: 12px;
    color: #ffffff;
    box-shadow:
        0 6px 18px rgba(31, 41, 55, 0.08);
}

.blue-card {
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );
}

.green-card {
    background: linear-gradient(
        135deg,
        #20b77a,
        #159a65
    );
}

.red-card {
    background: linear-gradient(
        135deg,
        #ff6d61,
        #f65343
    );
}

.orange-card {
    background: linear-gradient(
        135deg,
        #ffb238,
        #ff9d1c
    );
}

.stat-card-top {
    position: relative;
    z-index: 2;
    height: 100%;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
}

.meal-log-stat-label {
    margin-bottom: 7px;
    color: rgba(255, 255, 255, 0.86);
    font-size: 12px;
    font-weight: 600;
}

.meal-log-stat-value {
    margin: 0;
    color: #ffffff;
    font-size: 29px;
    font-weight: 750;
    line-height: 1.05;
    letter-spacing: -0.5px;
}

.meal-log-stat-note {
    display: block;
    margin-top: 8px;
    color: rgba(255, 255, 255, 0.74);
    font-size: 10.5px;
}

.meal-log-stat-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.17);
    color: #ffffff;
    font-size: 18px;
    backdrop-filter: blur(4px);
}

.stat-decoration {
    position: absolute;
    right: -34px;
    bottom: -42px;
    width: 118px;
    height: 118px;
    border: 18px solid rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}


/* =========================================================
   FILTER CARD
   ========================================================= */

.meal-filter-card {
    background: #ffffff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow:
        0 4px 16px rgba(31, 41, 55, 0.045);
}

.meal-filter-header {
    min-height: 70px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #edf0f5;
}

.meal-filter-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.meal-filter-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 14px;
}

.meal-filter-title h5 {
    margin: 0 0 3px;
    color: #273142;
    font-size: 15px;
    font-weight: 700;
}

.meal-filter-title span {
    color: #8a94a3;
    font-size: 11.5px;
}

.meal-filter-body {
    padding: 21px;
}

.meal-form-label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 12.5px;
    font-weight: 650;
}

.filter-input-wrapper {
    position: relative;
}

.filter-input-wrapper > i {
    position: absolute;
    left: 13px;
    top: 50%;
    z-index: 2;
    transform: translateY(-50%);
    color: #9aa4b2;
    font-size: 12px;
    pointer-events: none;
}

.meal-form-control {
    width: 100%;
    min-height: 42px;
    padding: 9px 12px 9px 37px;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    background: #ffffff;
    color: #374151;
    font-size: 12.5px;
    box-shadow: none;
    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.meal-form-control::placeholder {
    color: #a3abb6;
}

.meal-form-control:hover {
    border-color: #cbd2db;
}

.meal-form-control:focus {
    border-color: #147cf5;
    background: #ffffff;
    color: #263142;
    box-shadow:
        0 0 0 3px rgba(20, 124, 245, 0.10);
    outline: none;
}

select.meal-form-control {
    cursor: pointer;
}

.meal-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-top: 2px;
}

.meal-filter-btn {
    min-height: 41px;
    padding: 8px 17px;
    background: linear-gradient(
        135deg,
        #147cf5,
        #1268ca
    );
    border: 0;
    border-radius: 8px;
    color: #ffffff !important;
    font-size: 12.5px;
    font-weight: 650;
    box-shadow:
        0 4px 11px rgba(20, 124, 245, 0.15);
    transition: all 0.2s ease;
}

.meal-filter-btn:hover {
    background: linear-gradient(
        135deg,
        #1268ca,
        #0f5db4
    );
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow:
        0 6px 14px rgba(20, 124, 245, 0.20);
}

.meal-reset-btn {
    min-height: 41px;
    padding: 8px 16px;
    background: #ffffff;
    border: 1px solid #dfe4ea;
    border-radius: 8px;
    color: #657181 !important;
    font-size: 12.5px;
    font-weight: 650;
    transition: all 0.2s ease;
}

.meal-reset-btn:hover {
    background: #f6f8fb;
    border-color: #cbd2db;
    color: #263142 !important;
}


/* =========================================================
   TABLE CARD
   ========================================================= */

.meal-table-card {
    background: #ffffff;
    border: 1px solid #edf0f5;
    border-radius: 12px;
    overflow: hidden;
    box-shadow:
        0 4px 16px rgba(31, 41, 55, 0.045);
}

.meal-table-header {
    min-height: 73px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    border-bottom: 1px solid #edf0f5;
}

.meal-table-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.meal-table-title-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 9px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 14px;
}

.meal-table-title h5 {
    margin: 0 0 3px;
    color: #273142;
    font-size: 15px;
    font-weight: 700;
}

.meal-table-title span {
    color: #8a94a3;
    font-size: 11.5px;
}

.meal-record-count {
    padding: 6px 10px;
    display: inline-flex;
    align-items: center;
    background: #f5f7fa;
    border: 1px solid #e7ebf0;
    border-radius: 7px;
    color: #687385;
    font-size: 11px;
    font-weight: 650;
    white-space: nowrap;
}


/* =========================================================
   TABLE
   ========================================================= */

.meal-log-table {
    min-width: 1500px;
    margin: 0;
}

.meal-log-table thead th {
    padding: 13px 15px;
    background: #fafbfc;
    border-bottom: 1px solid #e9edf2;
    color: #687385;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.35px;
    white-space: nowrap;
}

.meal-log-table tbody td {
    padding: 14px 15px;
    border-bottom: 1px solid #f0f2f5;
    color: #4b5563;
    font-size: 12px;
    vertical-align: middle;
    white-space: nowrap;
}

.meal-log-table tbody tr {
    transition: background 0.15s ease;
}

.meal-log-table tbody tr:hover {
    background: #fafcff;
}

.meal-log-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   TABLE CONTENT
   ========================================================= */

.log-number {
    color: #8a94a3;
    font-size: 11.5px;
    font-weight: 600;
}

.log-date {
    color: #374151;
    font-size: 11.5px;
    font-weight: 650;
}

.log-time {
    margin-top: 3px;
    color: #9aa3af;
    font-size: 10px;
}

.transaction-id {
    min-height: 27px;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
    background: #f4f7fb;
    border: 1px solid #e5eaf0;
    border-radius: 6px;
    color: #566273;
    font-size: 10.5px;
    font-weight: 700;
}

.log-item {
    display: flex;
    align-items: center;
    gap: 9px;
}

.log-item-icon {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 12px;
}

.log-item-name {
    color: #273142;
    font-size: 11.5px;
    font-weight: 650;
}

.log-item-category {
    margin-top: 2px;
    color: #98a1ad;
    font-size: 10px;
}

.deleted-item {
    color: #9aa3af;
    font-size: 11px;
    font-style: italic;
}

.category-badge {
    min-height: 25px;
    padding: 4px 8px;
    display: inline-flex;
    align-items: center;
    background: #f4f7fb;
    border: 1px solid #e6ebf1;
    border-radius: 6px;
    color: #687385;
    font-size: 10px;
    font-weight: 650;
}

.log-action-badge {
    min-height: 27px;
    padding: 5px 9px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.log-action-badge.stock-in {
    background: #e8f8f1;
    color: #159a65;
}

.log-action-badge.stock-out {
    background: #fff0ef;
    color: #f65343;
}

.quantity-wrapper,
.stock-value-wrapper {
    display: flex;
    align-items: baseline;
    gap: 3px;
}

.log-quantity {
    color: #273142;
    font-weight: 700;
}

.log-unit {
    color: #8d97a5;
    font-size: 9.5px;
}

.stock-value {
    color: #4b5563;
    font-weight: 650;
}

.updated-stock .stock-value {
    color: #147cf5;
}

.stock-unit {
    color: #98a1ad;
    font-size: 9.5px;
}

.log-reason {
    display: inline-block;
    max-width: 145px;
    overflow: hidden;
    color: #687385;
    font-size: 10.5px;
    text-overflow: ellipsis;
    vertical-align: middle;
}

.performed-by {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #566273;
    font-size: 10.5px;
    font-weight: 650;
}

.performed-icon {
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #eaf3ff;
    color: #147cf5;
    font-size: 9px;
}

.performed-by.system .performed-icon {
    background: #f2f4f7;
    color: #8a94a3;
}


/* =========================================================
   VIEW BUTTON
   ========================================================= */

.log-view-btn {
    width: 33px;
    height: 33px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eaf3ff;
    border: 1px solid #d8e9ff;
    border-radius: 7px;
    color: #147cf5 !important;
    font-size: 11px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.log-view-btn:hover {
    background: #147cf5;
    border-color: #147cf5;
    color: #ffffff !important;
    transform: translateY(-1px);
    box-shadow:
        0 4px 10px rgba(20, 124, 245, 0.16);
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.meal-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.meal-empty-icon {
    width: 68px;
    height: 68px;
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f0f5fb;
    color: #8fa1b5;
    font-size: 24px;
}

.meal-empty-state h5 {
    margin-bottom: 7px;
    color: #374151;
    font-size: 15px;
    font-weight: 700;
}

.meal-empty-state p {
    max-width: 420px;
    margin: 0 auto 18px;
    color: #8a94a3;
    font-size: 12px;
}

.meal-reset-empty-btn {
    min-height: 38px;
    padding: 7px 14px;
    background: #ffffff;
    border: 1px solid #dfe4ea;
    border-radius: 7px;
    color: #586474 !important;
    font-size: 12px;
    font-weight: 600;
}

.meal-reset-empty-btn:hover {
    background: #f6f8fb;
    border-color: #cbd2db;
}


/* =========================================================
   PAGINATION
   ========================================================= */

.meal-pagination {
    padding: 15px 20px;
    border-top: 1px solid #edf0f5;
}

.meal-pagination .pagination {
    margin: 0;
    justify-content: flex-end;
}

.meal-pagination .page-link {
    min-width: 34px;
    height: 34px;
    margin-left: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #dfe4ea;
    border-radius: 7px !important;
    background: #ffffff;
    color: #657181;
    font-size: 11.5px;
    box-shadow: none;
    transition: all 0.2s ease;
}

.meal-pagination .page-link:hover {
    background: #f0f6ff;
    border-color: #cfe2ff;
    color: #147cf5;
}

.meal-pagination .page-item.active .page-link {
    background: #147cf5;
    border-color: #147cf5;
    color: #ffffff;
}

.meal-pagination .page-item.disabled .page-link {
    background: #f8fafc;
    color: #b0b7c1;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1400px) {

    .meal-log-table {
        min-width: 1450px;
    }

}

@media (max-width: 1200px) {

    .meal-log-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 992px) {

    .meal-page-header {
        align-items: flex-start;
    }

    .meal-filter-body {
        padding: 18px;
    }

}

@media (max-width: 768px) {

    .meal-page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .meal-secondary-btn {
        width: 100%;
    }

    .meal-page-title {
        font-size: 21px;
    }

    .meal-page-subtitle {
        font-size: 12px;
    }

    .meal-log-stats-grid {
        grid-template-columns: 1fr;
    }

    .meal-log-stat-card {
        min-height: 125px;
    }

    .meal-filter-header {
        padding: 14px 16px;
    }

    .meal-filter-body {
        padding: 16px;
    }

    .meal-filter-actions {
        width: 100%;
    }

    .meal-filter-btn,
    .meal-reset-btn {
        flex: 1;
    }

    .meal-table-header {
        padding: 14px 16px;
    }

    .meal-record-count {
        display: none;
    }

    .meal-pagination {
        padding: 13px 15px;
        overflow-x: auto;
    }

    .meal-pagination .pagination {
        justify-content: flex-start;
        min-width: max-content;
    }

}

@media (max-width: 576px) {

    .meal-logs-page .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .meal-heading-wrapper {
        align-items: flex-start;
    }

    .meal-heading-icon {
        width: 40px;
        height: 40px;
        font-size: 15px;
    }

    .meal-page-title {
        font-size: 19px;
    }

    .meal-filter-actions {
        flex-direction: column;
    }

    .meal-filter-btn,
    .meal-reset-btn {
        width: 100%;
        flex: unset;
    }

    .meal-log-stat-card {
        padding: 17px;
    }

}

</style>

@endpush