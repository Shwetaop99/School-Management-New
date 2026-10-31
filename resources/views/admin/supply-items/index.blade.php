```blade
@extends('layouts.app')

@section('title', 'Supply Items')

@section('content')

<style>
    :root {
        --supply-dark: #174c3a;
        --supply-green: #21664c;
        --supply-light: #eaf4ee;
        --supply-bg: #f5f8f5;
        --supply-border: #e2ebe5;
        --supply-text: #263b35;
        --supply-muted: #78877e;
    }

    .supply-page {
        padding: 24px 0 40px;
        color: var(--supply-text);
    }

    /* Header */
    .supply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 18px;
        margin-bottom: 25px;
    }

    .supply-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .supply-title-icon {
        width: 54px;
        height: 54px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: var(--supply-light);
        color: var(--supply-green);
        font-size: 24px;
    }

    .supply-title-wrap h3 {
        margin: 0 0 5px;
        color: var(--supply-dark);
        font-size: 24px;
        font-weight: 800;
    }

    .supply-title-wrap p {
        margin: 0;
        color: var(--supply-muted);
        font-size: 13px;
    }

    .supply-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 19px;
        border: 1px solid var(--supply-dark);
        border-radius: 10px;
        background: var(--supply-dark);
        color: #fff;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        transition: .2s ease;
    }

    .supply-add-btn:hover {
        background: #103b2c;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(23,76,58,.18);
    }

    /* Alerts */
    .supply-alert {
        padding: 13px 16px;
        border-radius: 11px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    /* Statistics */
    .supply-stat-card {
        position: relative;
        height: 100%;
        min-height: 125px;
        padding: 19px;
        border: 1px solid var(--supply-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(25,70,49,.045);
        overflow: hidden;
        transition: .2s ease;
    }

    .supply-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(25,70,49,.09);
    }

    .supply-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .supply-stat-label {
        color: var(--supply-muted);
        font-size: 12px;
        font-weight: 650;
    }

    .supply-stat-value {
        margin-top: 9px;
        color: var(--supply-dark);
        font-size: 27px;
        font-weight: 800;
        line-height: 1.2;
    }

    .supply-stat-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
    }

    .stat-green .supply-stat-icon {
        background: #e7f4eb;
        color: #21834e;
    }

    .stat-blue .supply-stat-icon {
        background: #e9f2fb;
        color: #3578b9;
    }

    .stat-orange .supply-stat-icon {
        background: #fff3df;
        color: #d18a20;
    }

    .stat-purple .supply-stat-icon {
        background: #f1ebfb;
        color: #8060b6;
    }

    /* Cards */
    .supply-card {
        border: 1px solid var(--supply-border);
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(25,70,49,.045);
        overflow: hidden;
    }

    .supply-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 17px 21px;
        border-bottom: 1px solid var(--supply-border);
        background: #fff;
    }

    .supply-card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--supply-dark);
        font-size: 15px;
        font-weight: 800;
    }

    .supply-card-heading i {
        color: var(--supply-green);
        font-size: 18px;
    }

    /* Filters */
    .supply-filter-body {
        padding: 20px;
    }

    .supply-label {
        display: block;
        margin-bottom: 7px;
        color: #40564a;
        font-size: 12px;
        font-weight: 750;
    }

    .supply-input,
    .supply-select {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #dce7df;
        border-radius: 9px;
        background: #fbfdfb;
        color: var(--supply-text);
        font-size: 13px;
        transition: .2s ease;
    }

    .supply-input:focus,
    .supply-select:focus {
        outline: none;
        border-color: var(--supply-green);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(33,102,76,.09);
    }

    .supply-filter-btn {
        min-height: 43px;
        padding: 9px 17px;
        border: 1px solid var(--supply-dark);
        border-radius: 9px;
        background: var(--supply-dark);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
    }

    .supply-filter-btn:hover {
        background: #103b2c;
        color: #fff;
    }

    .supply-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        border: 1px solid var(--supply-border);
        border-radius: 9px;
        background: #fff;
        color: #617268;
        text-decoration: none;
    }

    .supply-reset-btn:hover {
        background: var(--supply-light);
        color: var(--supply-dark);
    }

    /* Table */
    .supply-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        white-space: nowrap;
    }

    .supply-table thead th {
        padding: 14px 15px;
        background: #f3f8f4;
        color: #496153;
        border-bottom: 1px solid var(--supply-border);
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .supply-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #edf2ee;
        color: #46584d;
        font-size: 12px;
        vertical-align: middle;
    }

    .supply-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .supply-table tbody tr:hover {
        background: #f8fbf8;
    }

    .supply-number {
        color: #8b9a90;
        font-weight: 700;
    }

    .supply-code {
        display: inline-block;
        padding: 5px 9px;
        border: 1px solid #e1ebe4;
        border-radius: 6px;
        background: #f6faf7;
        color: var(--supply-dark);
        font-size: 11px;
        font-weight: 750;
    }

    .supply-item-name {
        color: #263f32;
        font-size: 13px;
        font-weight: 750;
    }

    .supply-description {
        display: block;
        max-width: 240px;
        margin-top: 4px;
        overflow: hidden;
        color: #89968d;
        font-size: 11px;
        text-overflow: ellipsis;
    }

    .supply-unit {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f0f5f1;
        color: #53695b;
        font-size: 11px;
        font-weight: 700;
    }

    .supply-stock-badge,
    .supply-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 750;
    }

    .stock-good {
        background: #e6f4ea;
        color: #217447;
    }

    .stock-low {
        background: #fff2dc;
        color: #a86c0b;
    }

    .stock-missing {
        background: #f0f2f1;
        color: #6f7d74;
    }

    .status-active {
        background: #e6f4ea;
        color: #217447;
    }

    .status-inactive {
        background: #f0f1f2;
        color: #69737b;
    }

    /* Actions */
.supply-actions {
    display: inline-flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.supply-actions form {
    display: inline-flex;
    margin: 0;
}

.supply-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 35px;
    height: 35px;
    border: 1px solid #e1e9e3;
    border-radius: 8px;
    background: #fff;
    font-size: 15px;
    text-decoration: none;
    cursor: pointer;
    transition: all .2s ease;
}

.action-view {
    color: #3578b9;
}

.action-view:hover {
    background: #e9f2fb;
    border-color: #c9dff2;
    color: #245d91;
}

.action-edit {
    color: #21834e;
}

.action-edit:hover {
    background: #e7f4eb;
    border-color: #c8e6d1;
    color: #17663a;
}

.action-delete {
    color: #dc3545;
}

.action-delete:hover {
    background: #fff0f0;
    border-color: #f2caca;
    color: #b02a37;
}

    .supply-dropdown {
        min-width: 155px;
        padding: 6px;
        border: 1px solid var(--supply-border);
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,.09);
    }

    .supply-dropdown .dropdown-item {
        padding: 9px 10px;
        border-radius: 7px;
        font-size: 12px;
    }

    .supply-dropdown .dropdown-item:hover {
        background: var(--supply-light);
    }

    /* Empty state */
    .supply-empty {
        padding: 55px 20px !important;
        text-align: center;
        white-space: normal;
    }

    .supply-empty-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border-radius: 18px;
        background: var(--supply-light);
        color: var(--supply-green);
        font-size: 29px;
    }

    .supply-empty h6 {
        margin-bottom: 7px;
        color: var(--supply-dark);
        font-size: 15px;
        font-weight: 800;
    }

    .supply-empty p {
        margin-bottom: 17px;
        color: var(--supply-muted);
        font-size: 12px;
    }

    /* Pagination */
    .supply-pagination {
        padding: 15px 20px;
        border-top: 1px solid var(--supply-border);
    }

    .supply-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .supply-pagination .pagination {
        margin-bottom: 0;
    }

    .supply-pagination .page-link {
        border-color: var(--supply-border);
        color: var(--supply-dark);
        font-size: 12px;
    }

    .supply-pagination .active .page-link {
        border-color: var(--supply-dark);
        background: var(--supply-dark);
        color: #fff;
    }

    @media (max-width: 767px) {
        .supply-page {
            padding-top: 15px;
        }

        .supply-title-wrap h3 {
            font-size: 20px;
        }

        .supply-title-icon {
            width: 46px;
            height: 46px;
            font-size: 21px;
        }

        .supply-header {
            align-items: flex-start;
        }

        .supply-add-btn {
            width: 100%;
        }

        .supply-filter-body {
            padding: 16px;
        }

        .supply-card-header {
            padding: 15px;
        }

        .supply-stat-card {
            min-height: 110px;
            padding: 15px;
        }

        .supply-stat-value {
            font-size: 23px;
        }

        .supply-pagination nav {
            justify-content: center;
        }
    }

    .supply-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 17px;
    border: 1px solid #dce7df;
    border-radius: 10px;
    background: #fff;
    color: var(--supply-dark);
    font-size: 13px;
    font-weight: 750;
    text-decoration: none;
    transition: all .2s ease;
}

.supply-back-btn:hover {
    background: var(--supply-light);
    border-color: var(--supply-green);
    color: var(--supply-dark);
    transform: translateY(-1px);
}

@media (max-width: 767px) {
    .supply-header > div:last-child {
        width: 100%;
    }

    .supply-header > div:last-child a {
        flex: 1;
    }
}
</style>

<div class="container-fluid supply-page">

    {{-- PAGE HEADER --}}
    <div class="supply-header">

        <div class="supply-title-wrap">
            <div class="supply-title-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <div>
                <h3>Supply Items</h3>
                <p>Manage school supply items and monitor available stock.</p>
            </div>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2">

    <a href="{{ route('admin.supply-stocks.index') }}"
       class="supply-back-btn">
        <i class="bi bi-arrow-left"></i>
        Back to Stock
    </a>

    <a href="{{ route('admin.supply-items.create') }}"
       class="supply-add-btn">
        <i class="bi bi-plus-lg"></i>
        Add Supply Item
    </a>

</div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show supply-alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show supply-alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-stat-card stat-blue">
                <div class="supply-stat-top">
                    <div>
                        <div class="supply-stat-label">Total Items</div>
                        <div class="supply-stat-value">{{ $totalItems }}</div>
                    </div>
                    <div class="supply-stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-stat-card stat-green">
                <div class="supply-stat-top">
                    <div>
                        <div class="supply-stat-label">Active Items</div>
                        <div class="supply-stat-value">{{ $activeItems }}</div>
                    </div>
                    <div class="supply-stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-stat-card stat-orange">
                <div class="supply-stat-top">
                    <div>
                        <div class="supply-stat-label">Low Stock Items</div>
                        <div class="supply-stat-value">{{ $lowStockCount }}</div>
                    </div>
                    <div class="supply-stat-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="supply-stat-card stat-purple">
                <div class="supply-stat-top">
                    <div>
                        <div class="supply-stat-label">Total Stock</div>
                        <div class="supply-stat-value">
                            {{ number_format((float) $totalStock, 2) }}
                        </div>
                    </div>
                    <div class="supply-stat-icon">
                        <i class="bi bi-stack"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- SEARCH AND FILTER --}}
    <div class="supply-card mb-4">

        <div class="supply-card-header">
            <div class="supply-card-heading">
                <i class="bi bi-funnel"></i>
                Search & Filter Items
            </div>
        </div>

        <div class="supply-filter-body">

            <form method="GET" action="{{ route('admin.supply-items.index') }}">

                <div class="row g-3 align-items-end">

                    <div class="col-12 col-md-6">
                        <label class="supply-label">Search Items</label>
                        <input
                            type="text"
                            name="search"
                            class="supply-input"
                            value="{{ request('search') }}"
                            placeholder="Search by item name or item code..."
                        >
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="supply-label">Item Status</label>
                        <select name="status" class="supply-select">
                            <option value="">All Statuses</option>
                            <option value="active" @selected(request('status') === 'active')>
                                Active
                            </option>
                            <option value="inactive" @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="supply-filter-btn flex-grow-1">
                                <i class="bi bi-search me-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.supply-items.index') }}"
                               class="supply-reset-btn"
                               title="Clear filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- SUPPLY ITEMS TABLE --}}
    <div class="supply-card">

        <div class="supply-card-header">

            <div class="supply-card-heading">
                <i class="bi bi-list-ul"></i>
                Supply Item List
            </div>

            <span class="text-muted small">
                <strong>{{ $supplyItems->total() }}</strong> item(s) found
            </span>

        </div>

        <div class="table-responsive">

            <table class="table supply-table align-middle">

                <thead>
                    <tr>
                        <th class="ps-3">#</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Unit</th>
                        <th>Available Stock</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($supplyItems as $item)

                        @php
                            $stock = $item->stock;
                            $quantity = $stock ? (float) $stock->quantity : 0;
                            $minimum = $stock ? (float) $stock->minimum_quantity : 0;

                            $isLowStock = $stock
                                && $stock->status
                                && $quantity <= $minimum;
                        @endphp

                        <tr>

                            <td class="ps-3">
                                <span class="supply-number">
                                    {{ $supplyItems->firstItem() + $loop->index }}
                                </span>
                            </td>

                            <td>
                                @if($item->item_code)
                                    <span class="supply-code">
                                        {{ $item->item_code }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                <div class="supply-item-name">
                                    {{ $item->item_name }}
                                </div>

                                @if($item->description)
                                    <span class="supply-description">
                                        {{ \Illuminate\Support\Str::limit($item->description, 55) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="supply-unit">
                                    {{ $item->unit }}
                                </span>
                            </td>

                            <td>
                                @if(!$stock)
                                    <span class="supply-stock-badge stock-missing">
                                        <i class="bi bi-dash-circle"></i>
                                        Not Added
                                    </span>
                                @elseif($isLowStock)
                                    <span class="supply-stock-badge stock-low">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ number_format($quantity, 2) }}
                                    </span>
                                @else
                                    <span class="supply-stock-badge stock-good">
                                        <i class="bi bi-check-circle"></i>
                                        {{ number_format($quantity, 2) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($stock)
                                    {{ number_format($minimum, 2) }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <td>
                                @if($item->status === 'active')
                                    <span class="supply-status-badge status-active">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="supply-status-badge status-inactive">
                                        <i class="bi bi-pause-circle-fill"></i>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="text-end pe-3">
    <div class="supply-actions">

        {{-- View --}}
        <a href="{{ route('admin.supply-items.show', $item) }}"
           class="supply-action-btn action-view"
           title="View Details"
           aria-label="View Details">
            <i class="bi bi-eye"></i>
        </a>

        {{-- Edit --}}
        <a href="{{ route('admin.supply-items.edit', $item) }}"
           class="supply-action-btn action-edit"
           title="Edit Item"
           aria-label="Edit Item">
            <i class="bi bi-pencil-square"></i>
        </a>

        {{-- Delete --}}
        <form method="POST"
              action="{{ route('admin.supply-items.destroy', $item) }}"
              onsubmit="return confirm('Are you sure you want to delete this supply item?');">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="supply-action-btn action-delete"
                    title="Delete Item"
                    aria-label="Delete Item">
                <i class="bi bi-trash"></i>
            </button>
        </form>

    </div>
</td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="supply-empty">

                                <div class="supply-empty-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <h6>No Supply Items Found</h6>

                                <p>
                                    No items match your current search.
                                    Try changing your filters or add a new supply item.
                                </p>

                                <a href="{{ route('admin.supply-items.create') }}"
                                   class="supply-add-btn">
                                    <i class="bi bi-plus-lg"></i>
                                    Add Supply Item
                                </a>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($supplyItems->hasPages())
            <div class="supply-pagination">
                {{ $supplyItems->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
```