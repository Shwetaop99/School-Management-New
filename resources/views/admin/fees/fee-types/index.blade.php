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
       FEE TYPE PAGE
    ========================================================= */

    .fee-type-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .fee-header {
        position: relative;
        overflow: hidden;

        min-height: 155px;

        padding: 30px 34px;
        margin-bottom: 24px;

        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 100%
        );

        color: #fff;

        box-shadow:
            0 10px 28px rgba(23, 105, 209, 0.18);

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }

    .fee-header::before {
        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        right: -45px;
        top: -105px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .fee-header::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        right: 100px;
        bottom: -82px;

        border-radius: 50%;

        background: rgba(255,255,255,.12);
    }

    .fee-header-content {
        position: relative;
        z-index: 2;
    }

    .fee-header h2 {
        margin: 0 0 7px;

        font-size: 30px;
        font-weight: 800;
    }

    .fee-header p {
        margin: 0;

        font-size: 15px;

        color: rgba(255,255,255,.94);
    }

    .add-fee-btn {
        position: relative;
        z-index: 3;

        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 12px 19px;

        border-radius: 11px;

        background: #fff;

        color: #1769d1;

        font-size: 13px;
        font-weight: 700;

        text-decoration: none;

        box-shadow:
            0 6px 16px rgba(0,0,0,.12);

        transition: .2s ease;
    }

    .add-fee-btn:hover {
        color: #1769d1;

        transform: translateY(-2px);

        box-shadow:
            0 10px 22px rgba(0,0,0,.18);
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .success-alert {
        display: flex;
        align-items: center;
        gap: 10px;

        padding: 14px 18px;

        margin-bottom: 22px;

        border: 1px solid #bbf7d0;

        border-radius: 12px;

        background: #f0fdf4;

        color: #15803d;

        font-size: 13px;
        font-weight: 600;
    }

    .success-alert i {
        font-size: 18px;
    }

    /* =========================================================
       STATISTICS
    ========================================================= */

    .stats-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;

        overflow: hidden;

        min-height: 145px;

        padding: 23px 25px;

        border-radius: 17px;

        color: #fff;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        box-shadow:
            0 8px 22px rgba(15,23,42,.12);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);

        box-shadow:
            0 15px 32px rgba(15,23,42,.18);
    }

    .stat-card::before {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        right: -50px;
        top: -65px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .stat-card::after {
        content: "";

        position: absolute;

        width: 80px;
        height: 80px;

        right: -20px;
        bottom: -38px;

        border-radius: 50%;

        background: rgba(255,255,255,.08);
    }

    .stat-card.blue {
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
    }

    .stat-card.green {
        background: linear-gradient(
            135deg,
            #0f9f62,
            #19b978
        );
    }

    .stat-card.red {
        background: linear-gradient(
            135deg,
            #e94d47,
            #f75d56
        );
    }

    .stat-top {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .stat-number {
        margin: 0 0 7px;

        font-size: 32px;
        line-height: 1;

        font-weight: 800;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;

        color: rgba(255,255,255,.95);
    }

    .stat-icon {
        position: relative;
        z-index: 2;

        font-size: 38px;

        color: rgba(255,255,255,.90);
    }

    .stat-bottom {
        position: relative;
        z-index: 2;

        font-size: 12px;

        color: rgba(255,255,255,.85);
    }

    /* =========================================================
       MAIN TABLE CARD
    ========================================================= */

    .fee-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #e5ebf3;

        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15,23,42,.06);
    }

    /* =========================================================
       CARD HEADER
    ========================================================= */

    .fee-card-header {
        min-height: 70px;

        padding: 17px 22px;

        border-bottom: 1px solid #edf1f6;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        flex-wrap: wrap;
    }

    .card-title {
        display: flex;

        align-items: center;

        gap: 9px;
    }

    .card-title i {
        color: #1769d1;

        font-size: 20px;
    }

    .card-title h3 {
        margin: 0;

        color: #172033;

        font-size: 17px;

        font-weight: 700;
    }

    .card-title span {
        display: block;

        margin-top: 3px;

        color: #718096;

        font-size: 11px;
    }

    /* =========================================================
       FILTERS
    ========================================================= */

    .filter-section {
        padding: 18px 22px;

        background: #f8fafc;

        border-bottom: 1px solid #edf1f6;
    }

    .filter-form {
        display: grid;

        grid-template-columns:
            minmax(0, 1fr)
            190px
            auto
            auto;

        gap: 12px;

        align-items: center;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;

        left: 13px;
        top: 50%;

        transform: translateY(-50%);

        color: #94a3b8;

        font-size: 16px;

        pointer-events: none;
    }

    .filter-input,
    .filter-select {
        width: 100%;

        height: 42px;

        padding: 0 13px;

        border: 1px solid #dce4ee;

        border-radius: 9px;

        background: #fff;

        color: #334155;

        font-size: 13px;

        outline: none;

        transition: .2s ease;
    }

    .search-input {
        padding-left: 39px;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #1769d1;

        box-shadow:
            0 0 0 3px rgba(23,105,209,.10);
    }

    .filter-btn {
        height: 42px;

        padding: 0 17px;

        border: none;

        border-radius: 9px;

        background: #1769d1;

        color: #fff;

        font-size: 13px;

        font-weight: 600;

        cursor: pointer;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        transition: .2s ease;
    }

    .filter-btn:hover {
        background: #1258b0;

        transform: translateY(-1px);
    }

    .reset-btn {
        height: 42px;

        padding: 0 15px;

        border: 1px solid #dce4ee;

        border-radius: 9px;

        background: #fff;

        color: #64748b;

        text-decoration: none;

        font-size: 13px;

        font-weight: 600;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        transition: .2s ease;
    }

    .reset-btn:hover {
        color: #1769d1;

        border-color: #b9d2f5;

        background: #f4f8ff;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;
    }

    .fee-table {
        width: 100%;

        min-width: 1100px;

        border-collapse: collapse;
    }

    .fee-table thead th {
        padding: 15px 18px;

        background: #f8fafc;

        border-bottom: 1px solid #e5ebf3;

        color: #64748b;

        font-size: 11px;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .4px;

        white-space: nowrap;
    }

    .fee-table tbody td {
        padding: 16px 18px;

        border-bottom: 1px solid #edf1f6;

        color: #475569;

        font-size: 13px;

        vertical-align: middle;
    }

    .fee-table tbody tr {
        transition: .2s ease;
    }

    .fee-table tbody tr:hover {
        background: #f8fbff;
    }

    .fee-table tbody tr:last-child td {
        border-bottom: none;
    }

    .serial-number {
        width: 50px;

        color: #94a3b8;

        font-size: 12px;

        font-weight: 700;
    }

    /* =========================================================
       FEE TYPE NAME
    ========================================================= */

    .fee-name {
        display: flex;

        align-items: center;

        gap: 11px;

        min-width: 190px;
    }

    .fee-icon {
        width: 39px;
        height: 39px;

        min-width: 39px;

        border-radius: 10px;

        background: #e7f0ff;

        color: #1769d1;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 18px;
    }

    .fee-name strong {
        display: block;

        color: #172033;

        font-size: 13px;

        font-weight: 700;
    }

    .fee-name small {
        display: block;

        margin-top: 3px;

        color: #94a3b8;

        font-size: 10px;
    }

    /* =========================================================
       BADGES
    ========================================================= */

    .badge {
        display: inline-flex;

        align-items: center;

        justify-content: center;

        padding: 6px 10px;

        border-radius: 20px;

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;
    }

    .badge-category {
        background: #ede9fe;

        color: #7c3aed;
    }

    .badge-frequency {
        background: #dbeafe;

        color: #1769d1;
    }

    .badge-active {
        background: #dcfce7;

        color: #15803d;
    }

    .badge-inactive {
        background: #fee2e2;

        color: #dc2626;
    }

    /* =========================================================
       FEE AMOUNT
    ========================================================= */

    .fee-amount {
        display: inline-flex;

        align-items: center;

        padding: 7px 11px;

        border-radius: 9px;

        background: #ecfdf5;

        color: #059669;

        font-size: 12px;

        font-weight: 800;

        white-space: nowrap;
    }

    /* =========================================================
       DESCRIPTION
    ========================================================= */

    .description-text {
        max-width: 250px;

        color: #64748b;

        font-size: 12px;

        line-height: 1.5;
    }

    .description-empty {
        color: #b0bac7;

        font-style: italic;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .action-buttons {
        display: flex;

        align-items: center;

        gap: 7px;
    }

    .action-btn {
        width: 35px;
        height: 35px;

        border-radius: 9px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        text-decoration: none;

        transition: .2s ease;
    }

    /* VIEW */

    .view-btn {
        background: #f3e8ff;

        color: #7c3aed;

        border: 1px solid #e9d5ff;
    }

    .view-btn:hover {
        background: #7c3aed;

        color: #fff;

        transform: translateY(-2px);
    }

    /* EDIT */

    .edit-btn {
        background: #e7f0ff;

        color: #1769d1;

        border: 1px solid #d4e5fc;
    }

    .edit-btn:hover {
        background: #1769d1;

        color: #fff;

        transform: translateY(-2px);
    }

    /* DELETE */

    .delete-btn {
        border: 1px solid #fee2e2;

        background: #fff1f2;

        color: #e11d48;

        cursor: pointer;
    }

    .delete-btn:hover {
        background: #e11d48;

        color: #fff;

        transform: translateY(-2px);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 70px 30px;

        text-align: center;
    }

    .empty-icon {
        width: 75px;
        height: 75px;

        margin: 0 auto 17px;

        border-radius: 50%;

        background: #edf5ff;

        color: #1769d1;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 31px;
    }

    .empty-state h4 {
        margin: 0 0 7px;

        color: #172033;

        font-size: 17px;

        font-weight: 700;
    }

    .empty-state p {
        margin: 0 0 18px;

        color: #94a3b8;

        font-size: 13px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 18px 20px;

        border-top: 1px solid #edf1f6;

        background: #fff;

        flex-wrap: wrap;
    }

    .pagination-info {
        color: #6b7280;

        font-size: 13px;
    }

    .pagination-info strong {
        color: #1f2937;
    }

    .pagination-links nav {
        display: flex;

        align-items: center;
    }

    .pagination-links svg {
        width: 16px;
        height: 16px;
    }

    .pagination-links a,
    .pagination-links span {
        min-width: 36px;

        height: 36px;

        padding: 0 10px;

        margin: 0 3px;

        border-radius: 9px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        font-size: 13px;

        font-weight: 600;
    }

    .pagination-links a {
        color: #526071;

        background: #f5f7fb;

        border: 1px solid #e7ebf2;

        text-decoration: none;

        transition: .2s ease;
    }

    .pagination-links a:hover {
        color: #fff;

        background: #1769d1;

        border-color: #1769d1;
    }

    .pagination-links span[aria-current="page"] {
        color: #fff;

        background: linear-gradient(
            135deg,
            #1769d1,
            #6c63ff
        );

        border: 1px solid transparent;
    }

    .pagination-links span[aria-disabled="true"] {
        color: #b8c0cc;

        background: #f8f9fb;

        border: 1px solid #edf0f4;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .filter-form {
            grid-template-columns:
                minmax(0, 1fr)
                170px
                auto
                auto;
        }

        .stats-grid {
            grid-template-columns:
                repeat(3, 1fr);
        }
    }

    @media (max-width: 900px) {

        .fee-type-container {
            padding: 18px;
        }

        .fee-header {
            align-items: flex-start;

            flex-direction: column;

            padding: 25px;
        }

        .stats-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns:
                1fr 1fr;
        }

        .filter-form .search-wrapper {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 600px) {

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .filter-form .search-wrapper {
            grid-column: auto;
        }

        .fee-header h2 {
            font-size: 24px;
        }

        .pagination-wrapper {
            flex-direction: column;

            align-items: flex-start;
        }

        .pagination-links {
            width: 100%;

            overflow-x: auto;
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