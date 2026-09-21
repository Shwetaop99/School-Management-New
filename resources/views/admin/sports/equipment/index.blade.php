@extends('layouts.app')

@section('title', 'Sports Equipment | Admin')

@section('content')

<style>

    /* =========================================================
       SPORTS EQUIPMENT PAGE
    ========================================================= */

    .sports-equipment-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f6f8fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
        padding: 24px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .equipment-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .equipment-heading-wrapper {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .equipment-page-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        font-size: 20px;
        box-shadow: 0 6px 16px rgba(20, 124, 245, 0.20);
    }

    .equipment-breadcrumb {
        margin: 0 0 3px;
        font-size: 12px;
        font-weight: 500;
        color: #94a3b8;
    }

    .equipment-page-title {
        margin: 0;
        font-size: 23px;
        line-height: 1.2;
        font-weight: 700;
        color: #172033;
    }

    .equipment-page-subtitle {
        margin: 5px 0 0;
        font-size: 13px;
        color: #718096;
    }

    .equipment-add-btn {
        height: 42px;
        padding: 0 17px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(135deg, #147cf5, #1268ca);
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        box-shadow: 0 5px 14px rgba(20, 124, 245, 0.18);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .equipment-add-btn:hover {
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(20, 124, 245, 0.24);
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .equipment-alert {
        border: 1px solid #cfe8d8;
        background: #f1fbf4;
        color: #20733c;
        border-radius: 11px;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    /* =========================================================
       STAT CARDS
    ========================================================= */

    .equipment-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .equipment-stat-card {
        border-radius: 17px;
        padding: 24px 25px;
        min-height: 165px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .equipment-stat-card:hover {
        transform: translateY(-2px);
    }

    /* STATIC DASHBOARD CARD COLORS */

    .equipment-stat-blue {
        background: linear-gradient(135deg, #147cf5, #1268ca);
        box-shadow: 0 7px 20px rgba(20, 124, 245, 0.20);
    }

    .equipment-stat-orange {
        background: linear-gradient(135deg, #ffb238, #ff9d1c);
        box-shadow: 0 7px 20px rgba(255, 178, 56, 0.20);
    }

    .equipment-stat-cyan {
        background: linear-gradient(135deg, #2bcfe8, #18b5d5);
        box-shadow: 0 7px 20px rgba(43, 207, 232, 0.20);
    }

    .equipment-stat-red {
        background: linear-gradient(135deg, #ff6d61, #f65343);
        box-shadow: 0 7px 20px rgba(246, 83, 67, 0.20);
    }

    .equipment-stat-content {
        min-width: 0;
        position: relative;
        z-index: 2;
    }

    .equipment-stat-label {
        margin-bottom: 7px;
        color: rgba(255, 255, 255, 0.88);
        font-size: 12px;
        font-weight: 500;
    }

    .equipment-stat-value {
        color: #ffffff;
        font-size: 24px;
        line-height: 1;
        font-weight: 700;
    }

    .equipment-stat-icon {
        width: 43px;
        height: 43px;
        min-width: 43px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.20);
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .equipment-filter-card {
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .equipment-filter-header {
        padding: 16px 18px 14px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .equipment-filter-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .equipment-filter-title i {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #eaf3ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .equipment-filter-hint {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 500;
    }

    .equipment-filter-body {
        padding: 17px 18px 18px;
    }

    .equipment-filter-grid {
        display: grid;
        grid-template-columns:
            minmax(250px, 2.4fr)
            minmax(150px, 1.25fr)
            minmax(150px, 1.25fr)
            minmax(145px, 1.15fr)
            auto;
        gap: 12px;
        align-items: end;
    }

    .equipment-filter-field {
        min-width: 0;
    }

    .equipment-filter-label {
        display: block;
        margin-bottom: 7px;
        color: #475569;
        font-size: 11px;
        line-height: 1;
        font-weight: 600;
    }

    .equipment-filter-control {
        width: 100%;
        height: 42px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #ffffff;
        color: #172033;
        padding: 0 12px;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
    }

    .equipment-filter-control::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    .equipment-filter-control:hover {
        border-color: #cbd5e1;
    }

    .equipment-filter-control:focus {
        border-color: #147cf5;
        box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10);
    }

    select.equipment-filter-control {
        cursor: pointer;
        appearance: auto;
    }

    .equipment-search-wrapper {
        position: relative;
    }

    .equipment-search-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
    }

    .equipment-search-input {
        padding-left: 36px;
    }

    .equipment-filter-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        height: 42px;
    }

    .equipment-filter-btn,
    .equipment-reset-btn {
        height: 42px;
        border-radius: 9px;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .equipment-filter-btn {
        border: 1px solid #147cf5;
        background: #147cf5;
        color: #ffffff;
    }

    .equipment-filter-btn:hover {
        background: #1268ca;
        border-color: #1268ca;
        color: #ffffff;
    }

    .equipment-reset-btn {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
    }

    .equipment-reset-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #172033;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .equipment-table-card {
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .equipment-table-header {
        min-height: 62px;
        padding: 15px 18px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .equipment-table-title {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #172033;
    }

    .equipment-table-count {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 500;
    }

    .equipment-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .equipment-table {
        width: 100%;
        min-width: 920px;
        border-collapse: collapse;
    }

    .equipment-table thead th {
        background: #fafbfc;
        color: #718096;
        border-bottom: 1px solid #edf0f5;
        padding: 12px 16px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 700;
        white-space: nowrap;
    }

    .equipment-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        vertical-align: middle;
        font-size: 12px;
        color: #475569;
    }

    .equipment-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .equipment-table tbody tr {
        transition: background 0.15s ease;
    }

    .equipment-table tbody tr:hover {
        background: #fbfdff;
    }

    .equipment-name {
        color: #172033;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .equipment-brand {
        color: #94a3b8;
        font-size: 10px;
    }

    .equipment-category {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 7px;
        background: #f1f5f9;
        color: #475569;
        font-size: 10px;
        font-weight: 600;
    }

    /* =========================================================
       STOCK
    ========================================================= */

    .equipment-stock {
        min-width: 120px;
    }

    .equipment-stock-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 6px;
    }

    .equipment-stock-number {
        color: #172033;
        font-size: 11px;
        font-weight: 700;
    }

    .equipment-stock-total {
        color: #94a3b8;
        font-size: 10px;
    }

    .equipment-stock-bar {
        width: 100%;
        height: 5px;
        border-radius: 20px;
        background: #edf2f7;
        overflow: hidden;
    }

    .equipment-stock-progress {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #147cf5, #21a0ff);
    }

    /* =========================================================
       BADGES
    ========================================================= */

    .equipment-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .equipment-badge-good {
        background: #eaf8ef;
        color: #239653;
    }

    .equipment-badge-fair {
        background: #fff7e8;
        color: #d98b0b;
    }

    .equipment-badge-damaged {
        background: #fff0ee;
        color: #e65343;
    }

    .equipment-badge-active {
        background: #eaf3ff;
        color: #147cf5;
    }

    .equipment-badge-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .equipment-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .equipment-action-btn {
        width: 31px;
        height: 31px;
        border-radius: 8px;
        border: 1px solid #e8edf3;
        background: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 11px;
        transition: all 0.18s ease;
    }

    .equipment-action-view {
        color: #147cf5;
    }

    .equipment-action-view:hover {
        background: #eaf3ff;
        border-color: #cfe2ff;
        color: #1268ca;
    }

    .equipment-action-edit {
        color: #d98b0b;
    }

    .equipment-action-edit:hover {
        background: #fff7e8;
        border-color: #f8dfad;
        color: #bd7600;
    }

    .equipment-action-delete {
        color: #f65343;
    }

    .equipment-action-delete:hover {
        background: #fff0ee;
        border-color: #ffd5d0;
        color: #d83c2d;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .equipment-empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .equipment-empty-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 13px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .equipment-empty-title {
        margin: 0 0 5px;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .equipment-empty-text {
        margin: 0;
        color: #94a3b8;
        font-size: 12px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .equipment-pagination {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
    }

    .equipment-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .equipment-pagination .pagination {
        margin: 0;
    }

    .equipment-pagination .page-link {
        min-width: 32px;
        height: 32px;
        margin: 0 2px;
        border-radius: 7px !important;
        border: 1px solid #e2e8f0;
        color: #64748b;
        background: #ffffff;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .equipment-pagination .page-item.active .page-link {
        background: #147cf5;
        border-color: #147cf5;
        color: #ffffff;
    }

    .equipment-pagination .page-link:hover {
        background: #f1f6ff;
        color: #147cf5;
        border-color: #cfe0f8;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .equipment-filter-grid {
            grid-template-columns:
                minmax(220px, 2fr)
                minmax(145px, 1.2fr)
                minmax(145px, 1.2fr)
                minmax(140px, 1.1fr);
        }

        .equipment-filter-actions {
            grid-column: 1 / -1;
            justify-content: flex-end;
            margin-top: 2px;
        }

        .equipment-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {

        .sports-equipment-page {
            padding: 16px;
        }

        .equipment-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .equipment-add-btn {
            width: 100%;
        }

        .equipment-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .equipment-filter-field:first-child {
            grid-column: 1 / -1;
        }

        .equipment-filter-actions {
            grid-column: 1 / -1;
            justify-content: stretch;
        }

        .equipment-filter-btn,
        .equipment-reset-btn {
            flex: 1;
        }

        .equipment-table-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {

        .equipment-stats-grid {
            grid-template-columns: 1fr;
        }

        .equipment-filter-grid {
            grid-template-columns: 1fr;
        }

        .equipment-filter-field:first-child {
            grid-column: auto;
        }

        .equipment-filter-actions {
            grid-column: auto;
            flex-direction: column;
            height: auto;
        }

        .equipment-filter-btn,
        .equipment-reset-btn {
            width: 100%;
        }

        .equipment-heading-wrapper {
            align-items: flex-start;
        }

        .equipment-page-title {
            font-size: 20px;
        }
    }

</style>


<div class="sports-equipment-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="equipment-page-header">

        <div class="equipment-heading-wrapper">

            <div class="equipment-page-icon">
                <i class="fas fa-dumbbell"></i>
            </div>

            <div>

                <div class="equipment-breadcrumb">
                    Sports Management / Equipment
                </div>

                <h1 class="equipment-page-title">
                    Sports Equipment
                </h1>

                <p class="equipment-page-subtitle">
                    Manage sports equipment, stock, condition and availability.
                </p>

            </div>

        </div>

        <a href="{{ route('admin.sports.equipment.create') }}"
           class="equipment-add-btn">

            <i class="fas fa-plus"></i>

            Add Equipment

        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if(session('success'))

        <div class="equipment-alert">

            <i class="fas fa-check-circle"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="equipment-stats-grid">

        {{-- Total --}}

        <div class="equipment-stat-card equipment-stat-blue">

            <div class="equipment-stat-content">

                <div class="equipment-stat-label">
                    Total Equipment
                </div>

                <div class="equipment-stat-value">
                    {{ $equipment->total() }}
                </div>

            </div>

            <div class="equipment-stat-icon">
                <i class="fas fa-boxes"></i>
            </div>

        </div>


        {{-- Active --}}

        <div class="equipment-stat-card equipment-stat-orange">

            <div class="equipment-stat-content">

                <div class="equipment-stat-label">
                    Active Equipment
                </div>

                <div class="equipment-stat-value">
                    {{ \App\Models\Sports\Equipment\Equipment::where('status', 'active')->count() }}
                </div>

            </div>

            <div class="equipment-stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>

        </div>


        {{-- Available Units --}}

        <div class="equipment-stat-card equipment-stat-cyan">

            <div class="equipment-stat-content">

                <div class="equipment-stat-label">
                    Available Units
                </div>

                <div class="equipment-stat-value">
                    {{ \App\Models\Sports\Equipment\Equipment::sum('available_quantity') }}
                </div>

            </div>

            <div class="equipment-stat-icon">
                <i class="fas fa-cubes"></i>
            </div>

        </div>


        {{-- Damaged --}}

        <div class="equipment-stat-card equipment-stat-red">

            <div class="equipment-stat-content">

                <div class="equipment-stat-label">
                    Damaged Equipment
                </div>

                <div class="equipment-stat-value">
                    {{ \App\Models\Sports\Equipment\Equipment::where('condition', 'damaged')->count() }}
                </div>

            </div>

            <div class="equipment-stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>

        </div>

    </div>


    {{-- =====================================================
         FILTER CARD
    ====================================================== --}}

    <div class="equipment-filter-card">

        <div class="equipment-filter-header">

            <div class="equipment-filter-title">

                <i class="fas fa-sliders-h"></i>

                <span>
                    Filter Equipment
                </span>

            </div>

            <div class="equipment-filter-hint">
                Search and filter inventory
            </div>

        </div>


        <div class="equipment-filter-body">

            <form method="GET"
                  action="{{ route('admin.sports.equipment.index') }}">

                <div class="equipment-filter-grid">

                    {{-- SEARCH --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label">
                            Search Equipment
                        </label>

                        <div class="equipment-search-wrapper">

                            <i class="fas fa-search equipment-search-icon"></i>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="equipment-filter-control equipment-search-input"
                                placeholder="Search by equipment, brand or model..."
                            >

                        </div>

                    </div>


                    {{-- CATEGORY --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label">
                            Category
                        </label>

                        <select
                            name="category"
                            class="equipment-filter-control"
                        >

                            <option value="">
                                All Categories
                            </option>

                            @foreach($categories as $category)

                                <option
                                    value="{{ $category }}"
                                    {{ request('category') === $category ? 'selected' : '' }}
                                >
                                    {{ $category }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CONDITION --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label">
                            Condition
                        </label>

                        <select
                            name="condition"
                            class="equipment-filter-control"
                        >

                            <option value="">
                                All Conditions
                            </option>

                            <option
                                value="good"
                                {{ request('condition') === 'good' ? 'selected' : '' }}
                            >
                                Good
                            </option>

                            <option
                                value="fair"
                                {{ request('condition') === 'fair' ? 'selected' : '' }}
                            >
                                Fair
                            </option>

                            <option
                                value="damaged"
                                {{ request('condition') === 'damaged' ? 'selected' : '' }}
                            >
                                Damaged
                            </option>

                        </select>

                    </div>


                    {{-- STATUS --}}

                    <div class="equipment-filter-field">

                        <label class="equipment-filter-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="equipment-filter-control"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- ACTIONS --}}

                    <div class="equipment-filter-actions">

                        <button
                            type="submit"
                            class="equipment-filter-btn"
                        >

                            <i class="fas fa-filter"></i>

                            Filter

                        </button>

                        <a
                            href="{{ route('admin.sports.equipment.index') }}"
                            class="equipment-reset-btn"
                        >

                            <i class="fas fa-redo"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         EQUIPMENT TABLE
    ====================================================== --}}

    <div class="equipment-table-card">

        <div class="equipment-table-header">

            <h2 class="equipment-table-title">
                Equipment Inventory
            </h2>

            <div class="equipment-table-count">

                Showing
                {{ $equipment->firstItem() ?? 0 }}
                -
                {{ $equipment->lastItem() ?? 0 }}
                of
                {{ $equipment->total() }}

            </div>

        </div>


        @if($equipment->count())

            <div class="equipment-table-wrapper">

                <table class="equipment-table">

                    <thead>

                        <tr>

                            <th>
                                Equipment
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Condition
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($equipment as $item)

                            @php

                                $totalQuantity = (int) ($item->quantity ?? 0);

                                $availableQuantity = (int) ($item->available_quantity ?? 0);

                                $stockPercentage = $totalQuantity > 0
                                    ? ($availableQuantity / $totalQuantity) * 100
                                    : 0;

                                $stockPercentage = min(
                                    100,
                                    max(0, $stockPercentage)
                                );

                            @endphp


                            <tr>

                                {{-- EQUIPMENT --}}

                                <td>

                                    <div class="equipment-name">
                                        {{ $item->equipment_name }}
                                    </div>

                                    @if($item->brand || $item->model)

                                        <div class="equipment-brand">

                                            @if($item->brand)
                                                {{ $item->brand }}
                                            @endif

                                            @if($item->brand && $item->model)
                                                ·
                                            @endif

                                            @if($item->model)
                                                {{ $item->model }}
                                            @endif

                                        </div>

                                    @endif

                                </td>


                                {{-- CATEGORY --}}

                                <td>

                                    <span class="equipment-category">

                                        {{ $item->category ?: '—' }}

                                    </span>

                                </td>


                                {{-- STOCK --}}

                                <td>

                                    <div class="equipment-stock">

                                        <div class="equipment-stock-top">

                                            <span class="equipment-stock-number">

                                                {{ $availableQuantity }}

                                                @if($item->unit)
                                                    {{ $item->unit }}
                                                @endif

                                            </span>

                                            <span class="equipment-stock-total">

                                                / {{ $totalQuantity }}

                                            </span>

                                        </div>

                                        <div class="equipment-stock-bar">

                                            <div
                                                class="equipment-stock-progress"
                                                style="width: {{ $stockPercentage }}%;"
                                            ></div>

                                        </div>

                                    </div>

                                </td>


                                {{-- CONDITION --}}

                                <td>

                                    @if($item->condition === 'good')

                                        <span class="equipment-badge equipment-badge-good">

                                            <i class="fas fa-check"></i>

                                            Good

                                        </span>

                                    @elseif($item->condition === 'fair')

                                        <span class="equipment-badge equipment-badge-fair">

                                            <i class="fas fa-minus-circle"></i>

                                            Fair

                                        </span>

                                    @elseif($item->condition === 'damaged')

                                        <span class="equipment-badge equipment-badge-damaged">

                                            <i class="fas fa-exclamation-circle"></i>

                                            Damaged

                                        </span>

                                    @else

                                        <span class="equipment-badge equipment-badge-inactive">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- LOCATION --}}

                                <td>

                                    {{ $item->location ?: '—' }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($item->status === 'active')

                                        <span class="equipment-badge equipment-badge-active">

                                            <i class="fas fa-circle" style="font-size: 5px;"></i>

                                            Active

                                        </span>

                                    @else

                                        <span class="equipment-badge equipment-badge-inactive">

                                            <i class="fas fa-circle" style="font-size: 5px;"></i>

                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td>

                                    <div class="equipment-actions">

                                        {{-- VIEW --}}

                                        <a
                                            href="{{ route('admin.sports.equipment.show', $item->id) }}"
                                            class="equipment-action-btn equipment-action-view"
                                            title="View"
                                        >

                                            <i class="fas fa-eye"></i>

                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route('admin.sports.equipment.edit', $item->id) }}"
                                            class="equipment-action-btn equipment-action-edit"
                                            title="Edit"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        {{-- DELETE --}}

                                        <form
                                            action="{{ route('admin.sports.equipment.destroy', $item->id) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this equipment?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="equipment-action-btn equipment-action-delete"
                                                title="Delete"
                                            >

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

            @if($equipment->hasPages())

                <div class="equipment-pagination">

                    {{ $equipment->links() }}

                </div>

            @endif

        @else

            {{-- EMPTY STATE --}}

            <div class="equipment-empty-state">

                <div class="equipment-empty-icon">

                    <i class="fas fa-dumbbell"></i>

                </div>

                <h3 class="equipment-empty-title">
                    No Equipment Found
                </h3>

                <p class="equipment-empty-text">
                    No equipment matches your current filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection

