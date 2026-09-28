@extends('layouts.app')

@section('title', 'Transport Vehicles | Admin')

@section('content')

<style>
    /* =========================================================
       TRANSPORT VEHICLES PAGE
       SAME DESIGN SYSTEM AS TRANSPORT RECORDS
    ========================================================= */

    .transport-vehicles-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding: 26px 28px 40px;
        background: #f4f7fb;
        color: #172033;
    }

    /* =========================================================
       HERO HEADER
    ========================================================= */

    .vehicles-hero {
        min-height: 165px;
        margin-bottom: 22px;
        padding: 28px 32px;
        border-radius: 18px;
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 100%
        );
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .vehicles-hero::before {
        content: "";
        position: absolute;
        width: 230px;
        height: 230px;
        border-radius: 50%;
        right: -75px;
        top: -105px;
        background: rgba(255, 255, 255, .08);
    }

    .vehicles-hero::after {
        content: "";
        position: absolute;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        right: 130px;
        bottom: -115px;
        background: rgba(255, 255, 255, .06);
    }

    .vehicles-hero-content {
        position: relative;
        z-index: 2;
    }

    .vehicles-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 10px;
        font-size: 11px;
        color: rgba(255, 255, 255, .78);
    }

    .vehicles-breadcrumb a {
        color: rgba(255, 255, 255, .82);
        text-decoration: none;
    }

    .vehicles-breadcrumb a:hover {
        color: #fff;
    }

    .vehicles-breadcrumb i {
        font-size: 9px;
        opacity: .7;
    }

    .vehicles-hero h1 {
        margin: 0;
        font-size: 29px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .vehicles-hero p {
        margin: 8px 0 0;
        max-width: 650px;
        font-size: 13px;
        line-height: 1.6;
        color: rgba(255, 255, 255, .82);
    }

    .vehicles-hero-icon {
        width: 105px;
        height: 105px;
        border-radius: 28px;
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .10);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 47px;
        color: rgba(255, 255, 255, .94);
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        margin-right: 18px;
    }

    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .vehicles-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .vehicles-summary-card {
        min-height: 125px;
        padding: 19px 20px;
        border-radius: 15px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .11);
        transition: transform .2s ease,
                    box-shadow .2s ease;
    }

    .vehicles-summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 27px rgba(15, 23, 42, .15);
    }

    .vehicles-summary-card::after {
        content: "";
        position: absolute;
        width: 105px;
        height: 105px;
        border-radius: 50%;
        right: -35px;
        top: -35px;
        background: rgba(255, 255, 255, .10);
    }

    .vehicles-summary-card.blue {
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
    }

    .vehicles-summary-card.green {
        background: linear-gradient(
            135deg,
            #16a34a,
            #22c55e
        );
    }

    .vehicles-summary-card.orange {
        background: linear-gradient(
            135deg,
            #ed9208,
            #f7aa25
        );
    }

    .vehicles-summary-card.cyan {
        background: linear-gradient(
            135deg,
            #079dbd,
            #16b5d0
        );
    }

    .summary-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        z-index: 2;
    }

    .summary-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .45px;
        opacity: .90;
    }

    .summary-icon {
        width: 39px;
        height: 39px;
        border-radius: 11px;
        background: rgba(255, 255, 255, .18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        position: relative;
        z-index: 2;
    }

    .summary-number {
        margin-top: 14px;
        font-size: 28px;
        font-weight: 800;
        line-height: 1;
        position: relative;
        z-index: 2;
    }

    .summary-footer {
        margin-top: 7px;
        font-size: 11px;
        opacity: .78;
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       SUCCESS ALERT
    ========================================================= */

    .vehicle-alert {
        margin-bottom: 22px;
        padding: 12px 15px;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 12.5px;
        display: flex;
        align-items: center;
    }

    /* =========================================================
       MAIN VEHICLES SECTION
    ========================================================= */

    .vehicles-section {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    /* =========================================================
       SECTION HEADER
    ========================================================= */

    .vehicles-section-header {
        padding: 20px 22px 18px;
        border-bottom: 1px solid #edf1f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .vehicles-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .vehicles-heading-icon {
        width: 43px;
        height: 43px;
        border-radius: 12px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .vehicles-heading h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 750;
        color: #172033;
    }

    .vehicles-heading p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 12px;
    }

    .vehicle-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 20px;
        background: #edf5ff;
        color: #1769d1;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       FILTER BAR
    ========================================================= */

    .vehicles-filter-bar {
        padding: 16px 22px;
        background: #fbfcfe;
        border-bottom: 1px solid #edf1f6;
    }

    .vehicles-filter-bar form {
        width: 100%;
    }

    .vehicle-search {
        position: relative;
    }

    .vehicle-search i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
    }

    .vehicle-filter-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #e2e8f0;
        border-radius: 11px;
        background: #f8fafc;
        color: #172033;
        font-size: 12.5px;
        outline: none;
        transition: .2s ease;
    }

    .vehicle-search input {
        padding-left: 40px;
    }

    .vehicle-filter-control:focus {
        background: #fff;
        border-color: #8bb8ef;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .08);
    }

    .vehicle-filter-btn,
    .vehicle-reset-btn {
        height: 42px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        transition: .2s ease;
    }

    .vehicle-filter-btn {
        border: none;
        background: #1769d1;
        color: #fff;
        box-shadow: 0 5px 13px rgba(23, 105, 209, .15);
    }

    .vehicle-filter-btn:hover {
        background: #1268ca;
        color: #fff;
        transform: translateY(-1px);
    }

    .vehicle-reset-btn {
        border: 1px solid #dbe3ed;
        background: #fff;
        color: #64748b;
        text-decoration: none;
    }

    .vehicle-reset-btn:hover {
        background: #f2f7ff;
        color: #1769d1;
        border-color: #bfd8fa;
    }

    /* =========================================================
       ADD VEHICLE BUTTON
    ========================================================= */

    .add-vehicle-btn {
        height: 42px;
        padding: 0 15px;
        border: none;
        border-radius: 11px;
        background: #1769d1;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 5px 13px rgba(23, 105, 209, .15);
        transition: .2s ease;
        white-space: nowrap;
    }

    .add-vehicle-btn:hover {
        background: #1268ca;
        color: #fff;
        transform: translateY(-1px);
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .vehicles-table-wrap {
        overflow-x: auto;
    }

    .vehicles-table {
        width: 100%;
        min-width: 1000px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .vehicles-table thead th {
        padding: 13px 17px;
        background: #f8fafc;
        border-bottom: 1px solid #e6edf5;
        color: #64748b;
        font-size: 10px;
        line-height: 1.2;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .vehicles-table tbody td {
        padding: 14px 17px;
        border-bottom: 1px solid #edf1f5;
        background: #fff;
        color: #334155;
        font-size: 12px;
        vertical-align: middle;
    }

    .vehicles-table tbody tr {
        transition: .18s ease;
    }

    .vehicles-table tbody tr:hover td {
        background: #f8fbff;
    }

    .vehicles-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       VEHICLE INFO
    ========================================================= */

    .vehicle-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 185px;
    }

    .vehicle-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .vehicle-info strong {
        display: block;
        color: #172033;
        font-size: 12.5px;
        font-weight: 750;
    }

    .vehicle-info small {
        display: block;
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10.5px;
    }

    /* =========================================================
       TYPE
    ========================================================= */

    .vehicle-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        font-size: 10.5px;
        font-weight: 700;
        white-space: nowrap;
    }

    .vehicle-type-badge i {
        color: #1769d1;
        font-size: 10px;
    }

    /* =========================================================
       CAPACITY
    ========================================================= */

    .vehicle-capacity {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
    }

    .vehicle-capacity i {
        color: #7c3aed;
    }

    .vehicle-capacity small {
        color: #94a3b8;
        font-size: 10px;
        font-weight: 500;
    }

    /* =========================================================
       DRIVER
    ========================================================= */

    .vehicle-driver strong {
        display: block;
        color: #172033;
        font-size: 12px;
        font-weight: 650;
    }

    .vehicle-driver small {
        display: block;
        margin-top: 3px;
        color: #64748b;
        font-size: 10.5px;
    }

    /* =========================================================
       CONTACT
    ========================================================= */

    .vehicle-contact {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        font-size: 11.5px;
        white-space: nowrap;
    }

    .vehicle-contact i {
        color: #1769d1;
        font-size: 11px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .vehicle-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .vehicle-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .vehicle-status.active {
        background: #dcfce7;
        color: #15803d;
    }

    .vehicle-status.active::before {
        background: #16a34a;
    }

    .vehicle-status.maintenance {
        background: #ffedd5;
        color: #c2410c;
    }

    .vehicle-status.maintenance::before {
        background: #f97316;
    }

    .vehicle-status.inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .vehicle-status.inactive::before {
        background: #ef4444;
    }

    /* =========================================================
       ACTIONS
    ========================================================= */

    .vehicle-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .vehicle-action {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        text-decoration: none;
        font-size: 12px;
        transition: .18s ease;
    }

    .vehicle-action.view {
        background: #eff6ff;
        color: #1769d1;
    }

    .vehicle-action.view:hover {
        background: #dbeafe;
        color: #1268ca;
    }

    .vehicle-action.edit {
        background: #fef3c7;
        color: #b45309;
    }

    .vehicle-action.edit:hover {
        background: #fde68a;
        color: #92400e;
    }

    .vehicle-action.delete {
        background: #fef2f2;
        color: #dc2626;
    }

    .vehicle-action.delete:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .vehicle-empty {
        text-align: center;
        padding: 70px 20px;
    }

    .vehicle-empty-icon {
        width: 82px;
        height: 82px;
        margin: 0 auto 18px;
        border-radius: 20px;
        background: #edf5ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        transform: rotate(-2deg);
    }

    .vehicle-empty h5 {
        margin: 0 0 7px;
        color: #172033;
        font-size: 18px;
        font-weight: 800;
    }

    .vehicle-empty p {
        max-width: 440px;
        margin: 0 auto 20px;
        color: #64748b;
        font-size: 12.5px;
        line-height: 1.6;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .vehicles-pagination {
        padding: 17px 22px;
        border-top: 1px solid #edf1f5;
        background: #fff;
    }

    .vehicles-pagination-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #64748b;
        font-size: 11.5px;
        font-weight: 500;
        white-space: nowrap;
    }

    .pagination-info strong {
        color: #172033;
        font-weight: 750;
    }

    .pagination-links {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex: 1;
    }

    .pagination-links .pagination {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .vehicles-pagination .page-item {
        margin: 0;
    }

    .vehicles-pagination .page-link {
        min-width: 35px;
        height: 35px;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        color: #64748b;
        background: #fff;
        font-size: 11px;
        font-weight: 600;
        margin: 0;
        border-radius: 9px !important;
        box-shadow: none;
        transition: .18s ease;
    }

    .vehicles-pagination .page-link:hover {
        background: #f2f7ff;
        color: #1769d1;
        border-color: #bfd8fa;
    }

    .vehicles-pagination .page-item.active .page-link {
        background: #1769d1;
        border-color: #1769d1;
        color: #fff;
        box-shadow: 0 4px 10px rgba(23, 105, 209, .16);
    }

    .vehicles-pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #e2e8f0;
        cursor: not-allowed;
    }

    .vehicles-pagination .page-item.disabled .page-link:hover {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #e2e8f0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {
        .vehicles-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .vehicles-hero-icon {
            width: 90px;
            height: 90px;
            font-size: 40px;
        }
    }

    @media (max-width: 1000px) {
        .vehicles-filter-bar .row > div {
            margin-bottom: 2px;
        }

        .vehicle-filter-btn,
        .vehicle-reset-btn {
            width: 100%;
        }
    }

    @media (max-width: 850px) {
        .transport-vehicles-page {
            padding: 20px 18px 30px;
        }

        .vehicles-hero {
            padding: 25px;
        }

        .vehicles-hero-icon {
            display: none;
        }

        .vehicles-hero h1 {
            font-size: 25px;
        }

        .vehicles-pagination-inner {
            justify-content: center;
        }

        .pagination-info {
            width: 100%;
            text-align: center;
        }

        .pagination-links {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 600px) {
        .transport-vehicles-page {
            padding: 16px 12px 25px;
        }

        .vehicles-hero {
            min-height: auto;
            padding: 22px 20px;
            border-radius: 15px;
        }

        .vehicles-hero h1 {
            font-size: 22px;
        }

        .vehicles-hero p {
            font-size: 12px;
        }

        .vehicles-summary-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .vehicles-section-header {
            padding: 17px 15px;
        }

        .vehicles-filter-bar {
            padding: 14px 15px;
        }

        .vehicle-count {
            display: none;
        }

        .vehicles-pagination {
            padding: 15px;
        }

        .vehicles-pagination-inner {
            gap: 12px;
        }

        .pagination-info {
            width: 100%;
            text-align: center;
        }

        .pagination-links {
            width: 100%;
            justify-content: center;
        }

        .pagination-links .pagination {
            gap: 2px;
        }

        .vehicles-pagination .page-link {
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            font-size: 10px;
        }
    }
</style>

<div class="transport-vehicles-page">

{{-- =====================================================
     HERO HEADER
====================================================== --}}

<div class="vehicles-hero">

    <div class="vehicles-hero-content">

        {{-- BREADCRUMB --}}
        <div class="vehicles-breadcrumb">

            <a href="{{ route('admin.transport.records.index') }}">
                Transport Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                Vehicles
            </span>

        </div>

        {{-- TITLE --}}
        <h1>
            Transport Vehicles
        </h1>

        <p>
            Manage school transport vehicles, driver details,
            seating capacity and vehicle availability.
        </p>

    </div>

    {{-- HERO ICON --}}
    <div class="vehicles-hero-icon">
        <i class="bi bi-bus-front-fill"></i>
    </div>

</div>


{{-- =====================================================
     SUCCESS MESSAGE
====================================================== --}}

@if(session('success'))

    <div class="vehicle-alert">

        <i class="bi bi-check-circle-fill me-2"></i>

        <span>
            {{ session('success') }}
        </span>

    </div>

@endif


{{-- =====================================================
     SUMMARY CARDS
====================================================== --}}

<div class="vehicles-summary-grid">

    {{-- TOTAL VEHICLES --}}
    <div class="vehicles-summary-card blue">

        <div class="summary-top">

            <div class="summary-label">
                Total Vehicles
            </div>

            <div class="summary-icon">
                <i class="bi bi-bus-front-fill"></i>
            </div>

        </div>

        <div class="summary-number">
            {{ $totalVehicles }}
        </div>

        <div class="summary-footer">
            All registered vehicles
        </div>

    </div>


    {{-- ACTIVE VEHICLES --}}
    <div class="vehicles-summary-card green">

        <div class="summary-top">

            <div class="summary-label">
                Active Vehicles
            </div>

            <div class="summary-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>

        </div>

        <div class="summary-number">
            {{ $activeVehicles }}
        </div>

        <div class="summary-footer">
            Currently active vehicles
        </div>

    </div>


    {{-- MAINTENANCE --}}
    <div class="vehicles-summary-card orange">

        <div class="summary-top">

            <div class="summary-label">
                Maintenance
            </div>

            <div class="summary-icon">
                <i class="bi bi-tools"></i>
            </div>

        </div>

        <div class="summary-number">
            {{ $maintenanceVehicles }}
        </div>

        <div class="summary-footer">
            Vehicles under maintenance
        </div>

    </div>


    {{-- INACTIVE --}}
    <div class="vehicles-summary-card cyan">

        <div class="summary-top">

            <div class="summary-label">
                Inactive Vehicles
            </div>

            <div class="summary-icon">
                <i class="bi bi-x-circle-fill"></i>
            </div>

        </div>

        <div class="summary-number">
            {{ $inactiveVehicles }}
        </div>

        <div class="summary-footer">
            Currently unavailable
        </div>

    </div>

</div>


{{-- =====================================================
     MAIN VEHICLES SECTION
====================================================== --}}

<div class="vehicles-section">

    {{-- SECTION HEADER --}}
    <div class="vehicles-section-header">

        <div class="vehicles-heading">

            <div class="vehicles-heading-icon">
                <i class="bi bi-bus-front-fill"></i>
            </div>

            <div>

                <h5>
                    Vehicle List
                </h5>

                <p>
                    View and manage all registered school vehicles.
                </p>

            </div>

        </div>


        <div class="d-flex align-items-center gap-2">

            {{-- VEHICLE COUNT --}}
            <div class="vehicle-count">

                <i class="bi bi-bus-front-fill"></i>

                {{ number_format($vehicles->total()) }}

                Vehicles

            </div>


            {{-- ADD VEHICLE --}}
            <a
                href="{{ route('admin.transport.vehicles.create') }}"
                class="add-vehicle-btn"
            >

                <i class="bi bi-plus-lg"></i>

                Add Vehicle

            </a>

        </div>

    </div>


    {{-- =================================================
         FILTER BAR
    ================================================== --}}

    <div class="vehicles-filter-bar">

        <form
            method="GET"
            action="{{ route('admin.transport.vehicles.index') }}"
        >

            <div class="row g-2 align-items-center">

                {{-- SEARCH --}}
                <div class="col-xl-5 col-lg-4">

                    <div class="vehicle-search">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control vehicle-filter-control"
                            placeholder="Search vehicle number, type or driver..."
                        >

                    </div>

                </div>


                {{-- VEHICLE TYPE --}}
                <div class="col-xl-3 col-lg-3">

                    <select
                        name="vehicle_type"
                        class="form-select vehicle-filter-control"
                    >

                        <option value="">
                            All Vehicle Types
                        </option>

                        <option
                            value="Bus"
                            {{ request('vehicle_type') == 'Bus' ? 'selected' : '' }}
                        >
                            Bus
                        </option>

                        <option
                            value="Van"
                            {{ request('vehicle_type') == 'Van' ? 'selected' : '' }}
                        >
                            Van
                        </option>

                        <option
                            value="Mini Bus"
                            {{ request('vehicle_type') == 'Mini Bus' ? 'selected' : '' }}
                        >
                            Mini Bus
                        </option>

                        <option
                            value="Other"
                            {{ request('vehicle_type') == 'Other' ? 'selected' : '' }}
                        >
                            Other
                        </option>

                    </select>

                </div>


                {{-- STATUS --}}
                <div class="col-xl-2 col-lg-2">

                    <select
                        name="status"
                        class="form-select vehicle-filter-control"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="maintenance"
                            {{ request('status') == 'maintenance' ? 'selected' : '' }}
                        >
                            Maintenance
                        </option>

                        <option
                            value="inactive"
                            {{ request('status') == 'inactive' ? 'selected' : '' }}
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- FILTER --}}
                <div class="col-xl-1 col-lg-1">

                    <button
                        type="submit"
                        class="vehicle-filter-btn"
                    >

                        <i class="bi bi-funnel-fill"></i>

                        <span class="d-none d-xl-inline">
                            Filter
                        </span>

                    </button>

                </div>


                {{-- RESET --}}
                <div class="col-xl-1 col-lg-1">

                    <a
                        href="{{ route('admin.transport.vehicles.index') }}"
                        class="vehicle-reset-btn"
                        title="Reset Filters"
                    >

                        <i class="bi bi-arrow-counterclockwise"></i>

                        <span class="d-none d-xl-inline">
                            Reset
                        </span>

                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =================================================
         VEHICLE TABLE
    ================================================== --}}

    <div class="vehicles-table-wrap">

        @if($vehicles->count())

            <table class="vehicles-table">

                <thead>

                    <tr>

                        <th>
                            Vehicle
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Capacity
                        </th>

                        <th>
                            Driver
                        </th>

                        <th>
                            Contact
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

                    @foreach($vehicles as $vehicle)

                        <tr>

                            {{-- VEHICLE --}}
                            <td>

                                <div class="vehicle-info">

                                    <div class="vehicle-avatar">
                                        <i class="bi bi-bus-front-fill"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $vehicle->vehicle_number }}
                                        </strong>

                                        <small>
                                            Vehicle #{{ $vehicle->id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- TYPE --}}
                            <td>

                                <span class="vehicle-type-badge">

                                    <i class="bi bi-truck-front-fill"></i>

                                    {{ $vehicle->vehicle_type }}

                                </span>

                            </td>


                            {{-- CAPACITY --}}
                            <td>

                                <span class="vehicle-capacity">

                                    <i class="bi bi-people-fill"></i>

                                    {{ $vehicle->capacity }}

                                    <small>
                                        seats
                                    </small>

                                </span>

                            </td>


                            {{-- DRIVER --}}
                            <td>

                                <div class="vehicle-driver">

                                    @if($vehicle->driver_name)

                                        <strong>
                                            {{ $vehicle->driver_name }}
                                        </strong>

                                        @if($vehicle->driver_license_number)

                                            <small>

                                                License:

                                                {{ $vehicle->driver_license_number }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            Not Assigned
                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- CONTACT --}}
                            <td>

                                @if($vehicle->driver_contact)

                                    <span class="vehicle-contact">

                                        <i class="bi bi-telephone-fill"></i>

                                        {{ $vehicle->driver_contact }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span class="vehicle-status {{ $vehicle->status }}">

                                    {{ ucfirst($vehicle->status) }}

                                </span>

                            </td>


                            {{-- ACTIONS --}}
                            <td>

                                <div class="vehicle-actions">

                                    {{-- VIEW --}}
                                    <a
                                        href="{{ route('admin.transport.vehicles.show', ['transportVehicle' => $vehicle->id]) }}"
                                        class="vehicle-action view"
                                        title="View Vehicle"
                                    >

                                        <i class="bi bi-eye-fill"></i>

                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.transport.vehicles.edit', ['transportVehicle' => $vehicle->id]) }}"
                                        class="vehicle-action edit"
                                        title="Edit Vehicle"
                                    >

                                        <i class="bi bi-pencil-fill"></i>

                                    </a>


                                    {{-- DELETE --}}
                                    <form
                                        action="{{ route('admin.transport.vehicles.destroy', ['transportVehicle' => $vehicle->id]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this vehicle?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="vehicle-action delete"
                                            title="Delete Vehicle"
                                        >

                                            <i class="bi bi-trash-fill"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        @else

            {{-- EMPTY STATE --}}
            <div class="vehicle-empty">

                <div class="vehicle-empty-icon">

                    <i class="bi bi-bus-front"></i>

                </div>

                <h5>
                    No Vehicles Found
                </h5>

                <p>
                    No transport vehicles match your current
                    search or filter criteria.
                </p>

                <a
                    href="{{ route('admin.transport.vehicles.create') }}"
                    class="add-vehicle-btn"
                >

                    <i class="bi bi-plus-lg"></i>

                    Add First Vehicle

                </a>

            </div>

        @endif

    </div>


    {{-- =================================================
         PAGINATION
    ================================================== --}}

    @if($vehicles->total() > 0)

        <div class="vehicles-pagination">

            <div class="vehicles-pagination-inner">

                {{-- PAGINATION INFO --}}
                <div class="pagination-info">

                    Showing

                    <strong>
                        {{ $vehicles->firstItem() ?? 0 }}
                    </strong>

                    to

                    <strong>
                        {{ $vehicles->lastItem() ?? 0 }}
                    </strong>

                    of

                    <strong>
                        {{ $vehicles->total() }}
                    </strong>

                    vehicles

                </div>


                {{-- PAGINATION LINKS --}}
                @if($vehicles->hasPages())

                    <div class="pagination-links">

                        {{ $vehicles
                            ->appends(request()->query())
                            ->onEachSide(1)
                            ->links('pagination::bootstrap-5')
                        }}

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

</div>

@endsection
