@extends('layouts.app')

@section('title', 'Stock In / Out | Admin')

@section('content')

<div class="meal-stock-page">

    <div class="container-fluid py-4">

        {{-- =========================================================
             PAGE HEADER
             ========================================================= --}}
        <div class="meal-page-header mb-4">

            <div class="meal-heading-wrapper">

                <div class="meal-page-icon">
                    <i class="fas fa-right-left"></i>
                </div>

                <div>
                    <h4 class="meal-page-title mb-1">
                        Stock In / Stock Out
                    </h4>

                    <p class="meal-page-subtitle mb-0">
                        Manage all stock in and stock out activities.
                    </p>
                </div>

            </div>

            <div class="meal-header-actions">

                <a href="{{ route('admin.meal.items.index') }}"
                   class="btn meal-back-btn">

                    <i class="fas fa-arrow-left me-1"></i>
                    Meal Items

                </a>

                <a href="{{ route('admin.meal.items.stock.create') }}"
                   class="btn meal-stock-btn">

                    <i class="fas fa-plus me-1"></i>
                    Add Stock In / Out

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

                    <span>
                        {{ session('success') }}
                    </span>

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

                    <span>
                        {{ session('error') }}
                    </span>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>

            </div>

        @endif


        {{-- =========================================================
             SUMMARY CARDS
             ========================================================= --}}

        <div class="meal-summary-grid">

            {{-- TOTAL STOCK IN --}}

            <div class="meal-summary-card summary-cyan">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Stock In
                    </span>

                    <strong>
                        {{ $totalStockIn }}
                    </strong>

                    <small>
                        Stock In records
                    </small>

                </div>

            </div>


            {{-- TOTAL STOCK OUT --}}

            <div class="meal-summary-card summary-red">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Stock Out
                    </span>

                    <strong>
                        {{ $totalStockOut }}
                    </strong>

                    <small>
                        Stock Out records
                    </small>

                </div>

            </div>


            {{-- STOCK IN QUANTITY --}}

            <div class="meal-summary-card summary-blue">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-box-open"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Total Stock In
                    </span>

                    <strong>
                        {{ number_format((float) $stockInQuantity, 2) }}
                    </strong>

                    <small>
                        Total quantity received
                    </small>

                </div>

            </div>


            {{-- STOCK OUT QUANTITY --}}

            <div class="meal-summary-card summary-orange">

                <div class="summary-pattern"></div>

                <div class="summary-icon">
                    <i class="fas fa-box"></i>
                </div>

                <div class="summary-content">

                    <span class="summary-label">
                        Total Stock Out
                    </span>

                    <strong>
                        {{ number_format((float) $stockOutQuantity, 2) }}
                    </strong>

                    <small>
                        Total quantity issued
                    </small>

                </div>

            </div>

        </div>


        {{-- =========================================================
             FILTER CARD
             ========================================================= --}}

        <div class="meal-card mb-4">

            <div class="meal-card-header">

                <div class="meal-card-title">

                    <div class="meal-title-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>

                        <h5>
                            Stock Movement Filters
                        </h5>

                        <span>
                            Search and filter stock transactions
                        </span>

                    </div>

                </div>

            </div>


            <div class="meal-filter-wrapper">

                <form method="GET"
                      action="{{ route('admin.meal.items.stock.index') }}"
                      class="meal-filter-form">

                    {{-- SEARCH --}}

                    <div class="filter-search">

                        <i class="fas fa-search"></i>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Search item, supplier or reason...">

                    </div>


                    {{-- TYPE --}}

                    <div class="filter-control">

                        <select name="transaction_type"
                                class="form-select">

                            <option value="">
                                All Stock
                            </option>

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

                    <div class="filter-date">

                        <input type="date"
                               name="date"
                               value="{{ request('date') }}"
                               class="form-control">

                    </div>


                    {{-- FILTER BUTTON --}}

                    <button type="submit"
                            class="filter-btn">

                        <i class="fas fa-filter me-1"></i>
                        Filter

                    </button>


                    {{-- RESET --}}

                    @if(request()->hasAny([
                        'search',
                        'transaction_type',
                        'date'
                    ]))

                        <a href="{{ route('admin.meal.items.stock.index') }}"
                           class="reset-filter-btn"
                           title="Clear Filters">

                            <i class="fas fa-rotate-left"></i>

                        </a>

                    @endif

                </form>

            </div>

        </div>


        {{-- =========================================================
             STOCK IN
             ========================================================= --}}

        <div class="meal-card mb-4">

            <div class="meal-card-header">

                <div class="meal-card-title">

                    <div class="meal-title-icon stock-in-icon">
                        <i class="fas fa-arrow-down"></i>
                    </div>

                    <div>

                        <h5>
                            Stock In
                        </h5>

                        <span>
                            Products received into inventory
                        </span>

                    </div>

                </div>

                <div class="movement-count stock-in-count">

                    {{ $totalStockIn }}
                    {{ $totalStockIn == 1 ? 'Record' : 'Records' }}

                </div>

            </div>


            <div class="meal-card-body">

                @if($stockIn->count())

                    <div class="table-responsive">

                        <table class="table movement-table">

                            <thead>

                                <tr>

                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Rate
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Supplier
                                    </th>

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

                                                <div>

                                                    <strong>
                                                        {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                    </strong>

                                                    <small>
                                                        {{ $transaction->unit }}
                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            @if($transaction->mealItem?->category)

                                                <span class="category-badge">
                                                    {{ $transaction->mealItem->category }}
                                                </span>

                                            @else

                                                <span class="empty-value">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <strong class="quantity-value quantity-in">

                                                {{ number_format((float) $transaction->quantity, 2) }}

                                            </strong>

                                            <span class="quantity-unit">
                                                {{ $transaction->unit }}
                                            </span>

                                        </td>


                                        <td>

                                            @if($transaction->rate !== null)

                                                ₹{{ number_format((float) $transaction->rate, 2) }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        <td>

                                            @if($transaction->total_amount !== null)

                                                <strong class="amount-value">
                                                    ₹{{ number_format((float) $transaction->total_amount, 2) }}
                                                </strong>

                                            @else

                                                —

                                            @endif

                                        </td>


                                        <td>

                                            <span class="movement-date">

                                                {{ optional($transaction->transaction_date)->format('d M Y') }}

                                            </span>

                                        </td>


                                        <td>

                                            {{ $transaction->supplier ?: '—' }}

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

                            <i class="fas fa-arrow-down"></i>

                        </div>

                        <h5>
                            No Stock In Records
                        </h5>

                        <p>
                            No stock in transactions have been recorded yet.
                        </p>

                        <a href="{{ route('admin.meal.items.stock.create') }}"
                           class="btn meal-stock-btn">

                            <i class="fas fa-plus me-1"></i>
                            Add Stock In

                        </a>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================================================
             STOCK OUT
             ========================================================= --}}

        <div class="meal-card">

            <div class="meal-card-header">

                <div class="meal-card-title">

                    <div class="meal-title-icon stock-out-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>

                    <div>

                        <h5>
                            Stock Out
                        </h5>

                        <span>
                            Products issued from inventory
                        </span>

                    </div>

                </div>

                <div class="movement-count stock-out-count">

                    {{ $totalStockOut }}
                    {{ $totalStockOut == 1 ? 'Record' : 'Records' }}

                </div>

            </div>


            <div class="meal-card-body">

                @if($stockOut->count())

                    <div class="table-responsive">

                        <table class="table movement-table">

                            <thead>

                                <tr>

                                    <th class="ps-4">
                                        #
                                    </th>

                                    <th>
                                        Product
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Quantity
                                    </th>

                                    <th>
                                        Date
                                    </th>

                                    <th>
                                        Reason
                                    </th>

                                    <th>
                                        Remarks
                                    </th>

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

                                                <div>

                                                    <strong>
                                                        {{ $transaction->mealItem->item_name ?? 'Unknown Item' }}
                                                    </strong>

                                                    <small>
                                                        {{ $transaction->unit }}
                                                    </small>

                                                </div>

                                            </div>

                                        </td>


                                        <td>

                                            @if($transaction->mealItem?->category)

                                                <span class="category-badge">
                                                    {{ $transaction->mealItem->category }}
                                                </span>

                                            @else

                                                <span class="empty-value">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            <strong class="quantity-value quantity-out">

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

                                            {{ $transaction->reason ?: '—' }}

                                        </td>


                                        <td>

                                            {{ $transaction->remarks ?: '—' }}

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

                            <i class="fas fa-arrow-up"></i>

                        </div>

                        <h5>
                            No Stock Out Records
                        </h5>

                        <p>
                            No stock out transactions have been recorded yet.
                        </p>

                        <a href="{{ route('admin.meal.items.stock.create') }}"
                           class="btn meal-stock-btn">

                            <i class="fas fa-plus me-1"></i>
                            Add Stock Out

                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.meal-stock-page {
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

.meal-stock-btn,
.meal-back-btn {
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

.meal-stock-btn {
    background: linear-gradient(135deg, #147cf5, #1268ca);
    border: 1px solid #147cf5;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(20, 124, 245, 0.16);
}

.meal-stock-btn:hover {
    background: linear-gradient(135deg, #1268ca, #1058ad);
    border-color: #1268ca;
    color: #ffffff !important;
    transform: translateY(-1px);
}

.meal-back-btn {
    background: #ffffff;
    border: 1px solid #d8e5f5;
    color: #147cf5 !important;
}

.meal-back-btn:hover {
    background: #f1f7ff;
    border-color: #bcd8fa;
    color: #1268ca !important;
    transform: translateY(-1px);
}


/* =========================================================
   ALERT
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

.stock-in-icon {
    background: #e8f8ef !important;
    color: #198754 !important;
}

.stock-out-icon {
    background: #fff0ef !important;
    color: #dc3545 !important;
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

.movement-count {
    flex-shrink: 0;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}

.stock-in-count {
    background: #eaf8f0;
    color: #198754;
}

.stock-out-count {
    background: #fff0ef;
    color: #dc3545;
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

.filter-date {
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

.movement-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
}

.movement-table thead th {
    padding: 13px 12px;
    background: #f8f9fb;
    color: #687386;
    border-bottom: 1px solid #edf0f5;
    font-size: 10.5px;
    font-weight: 650;
    text-transform: uppercase;
    letter-spacing: 0.35px;
    white-space: nowrap;
}

.movement-table tbody td {
    padding: 14px 12px;
    color: #4d5868;
    border-bottom: 1px solid #f0f2f5;
    vertical-align: middle;
    font-size: 12.5px;
}

.movement-table tbody tr {
    transition: background 0.15s ease;
}

.movement-table tbody tr:hover {
    background: #f8fbff;
}

.movement-table tbody tr:last-child td {
    border-bottom: 0;
}


/* =========================================================
   NUMBER
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


/* =========================================================
   MOVEMENT ITEM
   ========================================================= */

.movement-item {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 180px;
}

.movement-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 8px;
    font-size: 13px;
}

.movement-item strong {
    display: block;
    color: #263142;
    font-size: 13px;
    font-weight: 600;
}

.movement-item small {
    display: block;
    margin-top: 2px;
    color: #9aa3af;
    font-size: 10px;
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

.empty-value {
    color: #a0a8b3;
}


/* =========================================================
   QUANTITY
   ========================================================= */

.quantity-value {
    font-size: 13px;
    font-weight: 700;
}

.quantity-in {
    color: #198754;
}

.quantity-out {
    color: #dc3545;
}

.quantity-unit {
    margin-left: 4px;
    color: #8a94a3;
    font-size: 10px;
}


/* =========================================================
   AMOUNT
   ========================================================= */

.amount-value {
    color: #263142;
    font-size: 12.5px;
    font-weight: 650;
}


/* =========================================================
   DATE
   ========================================================= */

.movement-date {
    color: #586474;
    font-size: 11.5px;
    font-weight: 600;
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
    padding: 55px 20px;
    text-align: center;
}

.empty-icon {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    border-radius: 50%;
    font-size: 24px;
}

.stock-in-empty {
    background: #e8f8ef;
    color: #198754;
}

.stock-out-empty {
    background: #fff0ef;
    color: #dc3545;
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
    font-size: 13px;
    line-height: 1.6;
}


/* =========================================================
   RESPONSIVE — TABLET
   ========================================================= */

@media (max-width: 1100px) {

    .meal-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .movement-table {
        min-width: 1000px;
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

    .meal-filter-wrapper {
        padding: 12px 15px;
    }

    .filter-search {
        min-width: 100%;
        flex: 100%;
    }

    .filter-control,
    .filter-date {
        width: calc(50% - 5px);
    }

    .filter-btn {
        flex: 1;
    }

    .movement-table {
        min-width: 1000px;
    }

}


/* =========================================================
   RESPONSIVE — SMALL MOBILE
   ========================================================= */

@media (max-width: 576px) {

    .meal-stock-page .container-fluid {
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

    .meal-stock-btn,
    .meal-back-btn {
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

    .filter-control,
    .filter-date {
        width: 100%;
    }

    .filter-btn {
        width: 100%;
        flex: none;
    }

    .reset-filter-btn {
        width: 100%;
    }

    .movement-count {
        display: none;
    }

}

</style>

@endpush