@extends('layouts.app')

@section('title', 'Fee Types')

@section('content')

<!-- =========================================================
     BOOTSTRAP ICONS CDN
========================================================= -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
  
/* =========================================================
   FEE STRUCTURES — SCHOOL DIARY THEME
========================================================= */

.dashboard-container {
    --fee-ink: #263f36;
    --fee-ink-light: #506257;
    --fee-muted: #85877b;
    --fee-paper: #fffefa;
    --fee-bg: #f5f2e9;
    --fee-line: #e3ded1;
    --fee-green: #315c49;
    --fee-green-soft: #e7efe7;
    --fee-red: #c65b45;
    --fee-red-soft: #f8e8e1;
    --fee-amber: #b98536;
    --fee-amber-soft: #f6eedc;

    padding: 28px;
    background: var(--fee-bg);
    color: var(--fee-ink);
    min-height: calc(100vh - 64px);
    font-family: inherit;
}

/* Welcome panel */

.dashboard-container .welcome-card {
    position: relative;
    overflow: hidden;
    padding: 30px;
    margin-bottom: 24px;
    border: 1px solid #d8dfd1;
    border-radius: 5px;
    background: var(--fee-green);
    color: #fffefa;
    box-shadow: 0 5px 14px rgba(38, 63, 54, 0.08);
}

.dashboard-container .welcome-card::after {
    content: "";
    position: absolute;
    right: -35px;
    bottom: -75px;
    width: 220px;
    height: 220px;
    border: 1px solid rgba(255, 254, 250, 0.15);
    border-radius: 50%;
    box-shadow:
        0 0 0 22px rgba(255, 254, 250, 0.035),
        0 0 0 45px rgba(255, 254, 250, 0.025);
    pointer-events: none;
}

.dashboard-container .welcome-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    margin-bottom: 17px;
    border: 1px solid rgba(255, 254, 250, 0.3);
    border-radius: 4px;
    background: rgba(255, 254, 250, 0.1);
    font-size: 21px;
}

.dashboard-container .welcome-card h2 {
    margin: 0 0 8px;
    font-family: Georgia, "Times New Roman", serif;
    font-size: 30px;
    font-weight: 600;
    letter-spacing: -0.5px;
}

.dashboard-container .welcome-card p {
    margin: 0;
    color: rgba(255, 254, 250, 0.8);
    font-size: 14px;
    line-height: 1.7;
}

/* Statistics */

.dashboard-container .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 17px;
    margin-bottom: 24px;
}

.dashboard-container .stat-card {
    position: relative;
    overflow: hidden;
    padding: 23px;
    border: 1px solid var(--fee-line);
    border-radius: 5px;
    background: var(--fee-paper);
    color: var(--fee-ink);
    box-shadow: 0 3px 10px rgba(38, 63, 54, 0.025);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-container .stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(38, 63, 54, 0.07);
}

.dashboard-container .stat-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.dashboard-container .stat-number {
    margin-bottom: 8px;
    color: var(--fee-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 34px;
    font-weight: 700;
    line-height: 1.1;
}

.dashboard-container .stat-title {
    color: var(--fee-muted);
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.4px;
}

.dashboard-container .stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 47px;
    height: 47px;
    border: 1px solid var(--fee-line);
    border-radius: 5px;
    font-size: 21px;
}

.dashboard-container .stat-blue .stat-icon {
    background: var(--fee-green-soft);
    color: var(--fee-green);
}

.dashboard-container .stat-orange .stat-icon {
    background: var(--fee-amber-soft);
    color: var(--fee-amber);
}

.dashboard-container .stat-red .stat-icon {
    background: var(--fee-red-soft);
    color: var(--fee-red);
}

/* Main paper cards */

.dashboard-container .dashboard-card {
    margin-bottom: 24px;
    overflow: hidden;
    border: 1px solid var(--fee-line);
    border-radius: 5px;
    background: var(--fee-paper);
    box-shadow: 0 3px 12px rgba(38, 63, 54, 0.025);
}

.dashboard-container .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 23px;
    border-bottom: 1px solid var(--fee-line);
    background: #fffdf7;
}

.dashboard-container .card-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dashboard-container .card-title > i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 27px;
    height: 27px;
    border-radius: 4px;
    background: var(--fee-green-soft);
    color: var(--fee-green);
    font-size: 14px;
}

.dashboard-container .card-title h3 {
    margin: 0;
    color: var(--fee-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 19px;
    font-weight: 700;
}

.dashboard-container .card-subtitle {
    margin: 6px 0 0 37px;
    color: var(--fee-muted);
    font-size: 12px;
    line-height: 1.6;
}

.dashboard-container .card-body {
    padding: 23px;
}

/* Filters */

.dashboard-container .filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) minmax(145px, 1fr)
        minmax(125px, 1fr) auto;
    align-items: end;
    gap: 14px;
}

.dashboard-container .form-label {
    display: block;
    margin-bottom: 8px;
    color: var(--fee-ink-light);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.2px;
}

.dashboard-container .form-control,
.dashboard-container .form-select {
    width: 100%;
    min-height: 43px;
    padding: 9px 12px;
    border: 1px solid #dcd8cc;
    border-radius: 4px;
    background-color: #fffefa;
    color: var(--fee-ink);
    font-size: 13px;
    box-shadow: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-container .form-control::placeholder {
    color: #aaa99d;
}

.dashboard-container .form-control:focus,
.dashboard-container .form-select:focus {
    border-color: #789782;
    box-shadow: 0 0 0 3px rgba(49, 92, 73, 0.1);
    outline: none;
}

.dashboard-container .filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.dashboard-container .btn-search,
.dashboard-container .btn-add {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 42px;
    padding: 0 16px;
    border: 1px solid var(--fee-green);
    border-radius: 4px;
    background: var(--fee-green);
    color: #fffefa;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.dashboard-container .btn-search:hover,
.dashboard-container .btn-add:hover {
    border-color: var(--fee-ink);
    background: var(--fee-ink);
    color: #fffefa;
    transform: translateY(-1px);
}

.dashboard-container .btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 14px;
    border: 1px solid var(--fee-line);
    border-radius: 4px;
    background: #f8f5ed;
    color: var(--fee-ink-light);
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s ease;
}

.dashboard-container .btn-reset:hover {
    background: #eee9dc;
    color: var(--fee-ink);
}

/* Record count */

.dashboard-container .payment-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 11px;
    border: 1px solid #dce4d8;
    border-radius: 4px;
    background: var(--fee-green-soft);
    color: var(--fee-green);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* Table */

.dashboard-container .table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.dashboard-container .fee-table {
    width: 100%;
    min-width: 1000px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.dashboard-container .fee-table th {
    padding: 15px 16px;
    border-bottom: 1px solid var(--fee-line);
    background: #f5f2e9;
    color: #777b70;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    white-space: nowrap;
}

.dashboard-container .fee-table td {
    padding: 16px;
    border-bottom: 1px solid #eeeae0;
    background: var(--fee-paper);
    color: var(--fee-ink-light);
    font-size: 12px;
    vertical-align: middle;
}

.dashboard-container .fee-table tbody tr:last-child td {
    border-bottom: 0;
}

.dashboard-container .fee-table tbody tr {
    transition: background 0.15s ease;
}

.dashboard-container .fee-table tbody tr:hover td {
    background: #f8f7ef;
}

/* Structure identity */

.dashboard-container .structure-box {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.dashboard-container .structure-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 39px;
    height: 39px;
    border: 1px solid #d9e2d5;
    border-radius: 4px;
    background: var(--fee-green-soft);
    color: var(--fee-green);
    font-size: 16px;
}

.dashboard-container .structure-name {
    color: var(--fee-ink);
    font-size: 13px;
    font-weight: 700;
    line-height: 1.5;
}

.dashboard-container .structure-id {
    margin-top: 3px;
    color: #99998d;
    font-size: 10px;
}

.dashboard-container .serial-box {
    color: #8a8b7f;
    font-size: 12px;
    font-weight: 700;
}

.dashboard-container .academic-year {
    color: var(--fee-green);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-container .class-badge {
    color: #7c6546;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-container .section-box {
    color: var(--fee-ink-light);
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.dashboard-container .fee-count {
    color: #557b70;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-container .amount {
    color: var(--fee-green);
    font-size: 13px;
    font-weight: 800;
    white-space: nowrap;
}

/* Status labels */

.dashboard-container .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.dashboard-container .status-active {
    color: #38764d;
}

.dashboard-container .status-inactive {
    color: var(--fee-red);
}

/* Row actions */

.dashboard-container .action-buttons {
    display: flex;
    align-items: center;
    gap: 7px;
}

.dashboard-container .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid transparent;
    border-radius: 4px;
    text-decoration: none;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.2s ease;
}

.dashboard-container .action-btn:hover {
    transform: translateY(-2px);
}

.dashboard-container .action-view {
    border-color: #d7e4d8;
    background: #edf3e9;
    color: #315c49;
}

.dashboard-container .action-view:hover {
    background: #dce9db;
    color: #25483a;
}

.dashboard-container .action-edit {
    border-color: #e8dfca;
    background: #f7f0df;
    color: #98703b;
}

.dashboard-container .action-edit:hover {
    background: #efe3c5;
    color: #795725;
}

.dashboard-container .action-delete {
    border-color: #efd8ce;
    background: #faece6;
    color: #bc5a43;
}

.dashboard-container .action-delete:hover {
    background: #f4dcd2;
    color: #963f2d;
}

/* Empty state */

.dashboard-container .empty-state {
    padding: 65px 20px;
    text-align: center;
}

.dashboard-container .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 68px;
    height: 68px;
    margin: 0 auto 17px;
    border: 1px solid var(--fee-line);
    border-radius: 50%;
    background: #f6f3e9;
    color: var(--fee-green);
    font-size: 28px;
}

.dashboard-container .empty-state-title {
    color: var(--fee-ink);
    font-family: Georgia, "Times New Roman", serif;
    font-size: 21px;
    font-weight: 700;
}

.dashboard-container .empty-state-text {
    margin: 8px 0 20px;
    color: var(--fee-muted);
    font-size: 13px;
    line-height: 1.7;
}

/* Pagination */

.dashboard-container .pagination-wrapper {
    padding: 17px 22px;
    border-top: 1px solid var(--fee-line);
    background: #fffdf7;
}

.dashboard-container .pagination-wrapper nav {
    display: flex;
    justify-content: flex-end;
}

.dashboard-container .pagination-wrapper .pagination {
    margin-bottom: 0;
    gap: 4px;
}

.dashboard-container .pagination-wrapper .page-link {
    border-color: var(--fee-line);
    border-radius: 4px;
    background: var(--fee-paper);
    color: var(--fee-ink);
    font-size: 12px;
    box-shadow: none;
}

.dashboard-container .pagination-wrapper .page-item.active .page-link {
    border-color: var(--fee-green);
    background: var(--fee-green);
    color: #fffefa;
}

.dashboard-container .pagination-wrapper .page-item.disabled .page-link {
    background: #f5f2e9;
    color: #aaa99d;
}

/* Responsive */

@media (max-width: 1100px) {
    .dashboard-container .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-container .filter-actions {
        grid-column: 1 / -1;
    }

    .dashboard-container .stats-grid {
        gap: 12px;
    }
}

@media (max-width: 700px) {
    .dashboard-container {
        padding: 15px;
    }

    .dashboard-container .welcome-card {
        padding: 23px;
    }

    .dashboard-container .welcome-card h2 {
        font-size: 25px;
    }

    .dashboard-container .stats-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-container .stat-card {
        padding: 19px;
    }

    .dashboard-container .filter-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-container .filter-actions {
        grid-column: auto;
        flex-wrap: wrap;
    }

    .dashboard-container .card-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 18px;
    }

    .dashboard-container .card-body {
        padding: 18px;
    }

    .dashboard-container .card-subtitle {
        margin-left: 0;
    }

    .dashboard-container .card-header > .d-flex {
        flex-wrap: wrap;
        width: 100%;
    }

    .dashboard-container .btn-add,
    .dashboard-container .btn-search,
    .dashboard-container .btn-reset {
        min-height: 40px;
    }

    .dashboard-container .pagination-wrapper {
        padding: 15px;
        overflow-x: auto;
    }
}

/* Accessibility */

@media (prefers-reduced-motion: reduce) {
    .dashboard-container *,
    .dashboard-container *::before,
    .dashboard-container *::after {
        transition: none !important;
    }
}

</style>


<div class="fee-type-container">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="fee-header">

        <div class="fee-header-content">

            <h2>
                <i class="bi bi-cash-stack"></i>
                Fee Types
            </h2>

            <p>
                Manage and organize all fee categories used in the school fee system.
            </p>

        </div>

        <a
            href="{{ route('admin.fees.fee-types.create') }}"
            class="add-fee-btn"
        >
            <i class="bi bi-plus-circle"></i>
            Add Fee Type
        </a>

    </div>


    <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

    @if(session('success'))

        <div class="success-alert">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- =====================================================
         STATISTICS
    ====================================================== -->

    <div class="stats-grid">

        <!-- TOTAL -->

        <div class="stat-card blue">

            <div class="stat-top">

                <div>

                    <div class="stat-number">
                        {{ $totalTypes }}
                    </div>

                    <div class="stat-title">
                        Total Fee Types
                    </div>

                </div>

                <i class="bi bi-collection-fill stat-icon"></i>

            </div>

            <div class="stat-bottom">
                All fee types configured
            </div>

        </div>


        <!-- ACTIVE -->

        <div class="stat-card green">

            <div class="stat-top">

                <div>

                    <div class="stat-number">
                        {{ $activeTypes }}
                    </div>

                    <div class="stat-title">
                        Active Fee Types
                    </div>

                </div>

                <i class="bi bi-check-circle-fill stat-icon"></i>

            </div>

            <div class="stat-bottom">
                Currently available for fee structures
            </div>

        </div>


        <!-- INACTIVE -->

        <div class="stat-card red">

            <div class="stat-top">

                <div>

                    <div class="stat-number">
                        {{ $inactiveTypes }}
                    </div>

                    <div class="stat-title">
                        Inactive Fee Types
                    </div>

                </div>

                <i class="bi bi-pause-circle-fill stat-icon"></i>

            </div>

            <div class="stat-bottom">
                Currently disabled
            </div>

        </div>

    </div>


    <!-- =====================================================
         MAIN CARD
    ====================================================== -->

    <div class="fee-card">

        <!-- CARD HEADER -->

        <div class="fee-card-header">

            <div class="card-title">

                <i class="bi bi-list-ul"></i>

                <div>

                    <h3>
                        Fee Type List
                    </h3>

                    <span>
                        View and manage configured fee types
                    </span>

                </div>

            </div>

        </div>


        <!-- =================================================
             FILTERS
        ================================================== -->

        <div class="filter-section">

            <form
                method="GET"
                action="{{ route('admin.fees.fee-types.index') }}"
                class="filter-form"
            >

                <!-- SEARCH -->

                <div class="search-wrapper">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        name="search"
                        class="filter-input search-input"
                        placeholder="Search fee type, category or frequency..."
                        value="{{ request('search') }}"
                    >

                </div>


                <!-- STATUS -->

                <select
                    name="status"
                    class="filter-select"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="Active"
                        {{ request('status') === 'Active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        {{ request('status') === 'Inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>


                <!-- FILTER -->

                <button
                    type="submit"
                    class="filter-btn"
                >
                    <i class="bi bi-funnel-fill"></i>
                    Filter
                </button>


                <!-- RESET -->

                <a
                    href="{{ route('admin.fees.fee-types.index') }}"
                    class="reset-btn"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>

            </form>

        </div>


        <!-- =================================================
             TABLE
        ================================================== -->

        @if($feeTypes->count())

            <div class="table-wrapper">

                <table class="fee-table">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Fee Type</th>

                            <th>Category</th>

                            <th>Frequency</th>

                            <th>Fee Amount</th>

                            <th>Description</th>

                            <th>Status</th>

                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($feeTypes as $feeType)

                            <tr>

                                <!-- SERIAL -->

                                <td class="serial-number">
                                    {{ $feeTypes->firstItem() + $loop->index }}
                                </td>


                                <!-- FEE TYPE -->

                                <td>

                                    <div class="fee-name">

                                        <div class="fee-icon">

                                            <i class="bi bi-cash-coin"></i>

                                        </div>

                                        <div>

                                            <strong>
                                                {{ $feeType->name }}
                                            </strong>

                                            <small>
                                                Fee Type #{{ $feeType->id }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                <!-- CATEGORY -->

                                <td>

                                    <span class="badge badge-category">

                                        <i class="bi bi-folder2-open"></i>

                                        &nbsp;{{ $feeType->category }}

                                    </span>

                                </td>


                                <!-- FREQUENCY -->

                                <td>

                                    <span class="badge badge-frequency">

                                        <i class="bi bi-calendar3"></i>

                                        &nbsp;{{ $feeType->frequency }}

                                    </span>

                                </td>


                                <!-- FEE AMOUNT -->

                                <td>

                                    <span class="fee-amount">

                                        ₹{{ number_format((float) $feeType->amount, 2) }}

                                    </span>

                                </td>


                                <!-- DESCRIPTION -->

                                <td>

                                    @if($feeType->description)

                                        <div
                                            class="description-text"
                                            title="{{ $feeType->description }}"
                                        >

                                            {{ \Illuminate\Support\Str::limit(
                                                $feeType->description,
                                                70
                                            ) }}

                                        </div>

                                    @else

                                        <span class="description-empty">
                                            No description
                                        </span>

                                    @endif

                                </td>


                                <!-- STATUS -->

                                <td>

                                    @if($feeType->status === 'Active')

                                        <span class="badge badge-active">

                                            <i class="bi bi-check-circle-fill"></i>

                                            &nbsp;Active

                                        </span>

                                    @else

                                        <span class="badge badge-inactive">

                                            <i class="bi bi-x-circle-fill"></i>

                                            &nbsp;Inactive

                                        </span>

                                    @endif

                                </td>


                                <!-- ACTIONS -->

                                <td>

                                    <div class="action-buttons">

                                        <!-- VIEW -->

                                        <a
                                            href="{{ route('admin.fees.fee-types.show', $feeType->id) }}"
                                            class="action-btn view-btn"
                                            title="View Fee Type"
                                        >

                                            <i class="bi bi-eye-fill"></i>

                                        </a>


                                        <!-- EDIT -->

                                        <a
                                            href="{{ route('admin.fees.fee-types.edit', $feeType->id) }}"
                                            class="action-btn edit-btn"
                                            title="Edit Fee Type"
                                        >

                                            <i class="bi bi-pencil-square"></i>

                                        </a>


                                        <!-- DELETE -->

                                        <form
                                            method="POST"
                                            action="{{ route('admin.fees.fee-types.destroy', $feeType->id) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this fee type?');"
                                            style="margin:0;"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                                title="Delete Fee Type"
                                            >

                                                <i class="bi bi-trash3"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <!-- =================================================
                 PAGINATION
            ================================================== -->

            @if($feeTypes->hasPages())

                <div class="pagination-wrapper">

                    <div class="pagination-info">

                        Showing

                        <strong>
                            {{ $feeTypes->firstItem() }}
                        </strong>

                        to

                        <strong>
                            {{ $feeTypes->lastItem() }}
                        </strong>

                        of

                        <strong>
                            {{ $feeTypes->total() }}
                        </strong>

                        fee types

                    </div>

                    <div class="pagination-links">

                        {{ $feeTypes->onEachSide(1)->links() }}

                    </div>

                </div>

            @endif

        @else

            <!-- =================================================
                 EMPTY STATE
            ================================================== -->

            <div class="empty-state">

                <div class="empty-icon">

                    <i class="bi bi-cash-stack"></i>

                </div>

                <h4>
                    No Fee Types Found
                </h4>

                <p>
                    No fee types match your current search or filter.
                </p>

                <a
                    href="{{ route('admin.fees.fee-types.create') }}"
                    class="add-fee-btn"
                >

                    <i class="bi bi-plus-circle"></i>

                    Add First Fee Type

                </a>

            </div>

        @endif

    </div>

</div>

@endsection