@extends('layouts.app')

@section('title', 'Transport Management')

@section('content')

<style>
    /* =========================================================
       TRANSPORT MANAGEMENT
       Matches Main Dashboard UI
    ========================================================= */

    .transport-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
    }

    /* =========================================================
       PAGE HEADER / WELCOME BANNER
    ========================================================= */

    .transport-welcome {
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
    }

    .transport-welcome::before {
        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        right: -45px;
        top: -105px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .transport-welcome::after {
        content: "";

        position: absolute;

        width: 120px;
        height: 120px;

        right: 100px;
        bottom: -82px;

        border-radius: 50%;

        background: rgba(255,255,255,.12);
    }

    .transport-heading {
        position: relative;
        z-index: 2;
    }

    .transport-heading h1 {
        margin: 0 0 7px;

        font-size: 30px;
        font-weight: 800;
    }

    .transport-heading p {
        margin: 0;

        color: rgba(255,255,255,.94);

        font-size: 15px;
    }

    .transport-main-icon {
        position: relative;
        z-index: 2;

        margin-right: 35px;

        font-size: 70px;

        color: rgba(255,255,255,.20);
    }

    /* =========================================================
       STAT CARDS
    ========================================================= */

    .transport-stats {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 24px;
    }

    .transport-stat {
        position: relative;
        overflow: hidden;

        min-height: 155px;

        padding: 24px 25px;

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

    .transport-stat:hover {
        transform: translateY(-5px);

        box-shadow:
            0 15px 32px rgba(15,23,42,.18);
    }

    .transport-stat::before {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        right: -50px;
        top: -65px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .transport-stat::after {
        content: "";

        position: absolute;

        width: 80px;
        height: 80px;

        right: -20px;
        bottom: -38px;

        border-radius: 50%;

        background: rgba(255,255,255,.08);
    }

    .transport-stat.blue {
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
    }

    .transport-stat.orange {
        background: linear-gradient(
            135deg,
            #ed9208,
            #f7aa25
        );
    }

    .transport-stat.cyan {
        background: linear-gradient(
            135deg,
            #079dbd,
            #16b5d0
        );
    }

    .transport-stat.red {
        background: linear-gradient(
            135deg,
            #e94d47,
            #f75d56
        );
    }

    .transport-stat-top {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .transport-stat-number {
        margin: 0 0 7px;

        font-size: 32px;
        line-height: 1;

        font-weight: 800;
    }

    .transport-stat-title {
        font-size: 14px;
        font-weight: 600;

        color: rgba(255,255,255,.95);
    }

    .transport-stat-icon {
        position: relative;
        z-index: 2;

        font-size: 38px;

        color: rgba(255,255,255,.90);
    }

    .transport-stat-link {
        position: relative;
        z-index: 2;

        width: fit-content;

        margin-top: 15px;

        color: #fff;
        text-decoration: none;

        font-size: 12px;
        font-weight: 600;

        transition: .2s ease;
    }

    .transport-stat-link:hover {
        color: #fff;

        transform: translateX(4px);
    }

    /* =========================================================
       MODULE CARDS
    ========================================================= */

    .transport-module-grid {
        display: grid;

        grid-template-columns:
            repeat(3, minmax(0,1fr));

        gap: 22px;

        margin-bottom: 22px;
    }

    .transport-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #e5ebf3;

        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15,23,42,.06);

        transition: .25s ease;
    }

    .transport-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 10px 25px rgba(15,23,42,.10);
    }

    .transport-card-header {
        min-height: 70px;

        padding: 17px 22px;

        border-bottom: 1px solid #edf1f6;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .transport-card-title {
        display: flex;
        align-items: center;

        gap: 9px;
    }

    .transport-card-title i {
        font-size: 19px;
    }

    .transport-card-title h3 {
        margin: 0;

        color: #172033;

        font-size: 17px;
        font-weight: 700;
    }

    .transport-card-body {
        padding: 22px;
    }

    .transport-card-description {
        min-height: 45px;

        margin-bottom: 20px;

        color: #64748b;

        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================================================
       MODULE ICON
    ========================================================= */

    .module-icon {
        width: 46px;
        height: 46px;

        margin-bottom: 16px;

        border-radius: 12px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 21px;
    }

    .module-icon.blue {
        background: #dbeafe;
        color: #1769d1;
    }

    .module-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .module-icon.cyan {
        background: #cffafe;
        color: #079dbd;
    }

    /* =========================================================
       MODULE BUTTON
    ========================================================= */

    .module-button {
        width: 100%;

        padding: 11px 15px;

        border-radius: 10px;

        text-decoration: none;

        display: flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        font-size: 12px;
        font-weight: 700;

        transition: .2s ease;
    }

    .module-button.blue {
        background: #edf5ff;
        color: #1769d1;

        border: 1px solid #c9dcfa;
    }

    .module-button.orange {
        background: #fff7ed;
        color: #ea580c;

        border: 1px solid #fed7aa;
    }

    .module-button.cyan {
        background: #ecfeff;
        color: #079dbd;

        border: 1px solid #bae6fd;
    }

    .module-button:hover {
        color: #fff;
    }

    .module-button.blue:hover {
        background: #1769d1;
        border-color: #1769d1;
    }

    .module-button.orange:hover {
        background: #ed9208;
        border-color: #ed9208;
    }

    .module-button.cyan:hover {
        background: #079dbd;
        border-color: #079dbd;
    }

    /* =========================================================
       INFORMATION CARD
    ========================================================= */

    .transport-info-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #e5ebf3;

        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15,23,42,.06);
    }

    .transport-info-header {
        min-height: 70px;

        padding: 17px 22px;

        border-bottom: 1px solid #edf1f6;

        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .transport-info-title {
        display: flex;
        align-items: center;

        gap: 9px;
    }

    .transport-info-title i {
        color: #1769d1;

        font-size: 19px;
    }

    .transport-info-title h3 {
        margin: 0;

        color: #172033;

        font-size: 17px;
        font-weight: 700;
    }

    .transport-info-body {
        padding: 22px;
    }

    .transport-info-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0,1fr));

        gap: 15px;
    }

    .transport-info-item {
        padding: 18px;

        background: #f8fafc;

        border: 1px solid #e6edf5;

        border-radius: 13px;

        transition: .2s ease;
    }

    .transport-info-item:hover {
        transform: translateY(-3px);

        background: #f2f7ff;
    }

    .transport-info-icon {
        width: 42px;
        height: 42px;

        margin-bottom: 12px;

        border-radius: 11px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 19px;
    }

    .transport-info-item strong {
        display: block;

        margin-bottom: 3px;

        color: #172033;

        font-size: 15px;
        font-weight: 800;
    }

    .transport-info-item span {
        color: #64748b;

        font-size: 12px;
    }

    .blue-bg {
        background: #dbeafe;
        color: #1769d1;
    }

    .orange-bg {
        background: #ffedd5;
        color: #ea580c;
    }

    .green-bg {
        background: #dcfce7;
        color: #16a34a;
    }

    .purple-bg {
        background: #ede9fe;
        color: #7c3aed;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .transport-stats {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .transport-module-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .transport-info-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }
    }

    @media (max-width: 850px) {

        .transport-container {
            padding: 18px;
        }

        .transport-module-grid {
            grid-template-columns: 1fr;
        }

        .transport-welcome {
            padding: 25px;
        }

        .transport-main-icon {
            display: none;
        }
    }

    @media (max-width: 650px) {

        .transport-stats {
            grid-template-columns: 1fr;
        }

        .transport-info-grid {
            grid-template-columns: 1fr;
        }

        .transport-welcome {
            min-height: 140px;
        }

        .transport-heading h1 {
            font-size: 24px;
        }

        .transport-stat {
            min-height: 150px;
        }
    }
</style>


@php

    /*
    |--------------------------------------------------------------------------
    | Dynamic Transport Statistics
    |--------------------------------------------------------------------------
    */

    $transportRecords = (int) ($transportRecords ?? 0);

    $activeRoutes = (int) ($activeRoutes ?? 0);

    $vehicleCount = (int) ($vehicleCount ?? 0);

    $activeVehicles = (int) ($activeVehicles ?? 0);

@endphp


<div class="transport-container">

    {{-- =====================================================
         TRANSPORT WELCOME
    ====================================================== --}}

    <div class="transport-welcome">

        <div class="transport-heading">

            <h1>
                Transport Management
            </h1>

            <p>
                Manage student transportation, routes and school vehicles.
            </p>

        </div>

        <i class="bi bi-bus-front-fill transport-main-icon"></i>

    </div>


    {{-- =====================================================
         TRANSPORT STATISTICS
    ====================================================== --}}

    <div class="transport-stats">

        {{-- RECORDS --}}
        <div class="transport-stat blue">

            <div class="transport-stat-top">

                <div>

                    <div class="transport-stat-number">
                        {{ number_format($transportRecords) }}
                    </div>

                    <div class="transport-stat-title">
                        Transport Records
                    </div>

                </div>

                <i class="bi bi-person-vcard-fill transport-stat-icon"></i>

            </div>

            <a
                href="#"
                class="transport-stat-link"
            >
                View records →
            </a>

        </div>


        {{-- ROUTES --}}
        <div class="transport-stat orange">

            <div class="transport-stat-top">

                <div>

                    <div class="transport-stat-number">
                        {{ number_format($activeRoutes) }}
                    </div>

                    <div class="transport-stat-title">
                        Active Routes
                    </div>

                </div>

                <i class="bi bi-signpost-split-fill transport-stat-icon"></i>

            </div>

            <a
                href="#"
                class="transport-stat-link"
            >
                View routes →
            </a>

        </div>


        {{-- VEHICLES --}}
        <div class="transport-stat cyan">

            <div class="transport-stat-top">

                <div>

                    <div class="transport-stat-number">
                        {{ number_format($vehicleCount) }}
                    </div>

                    <div class="transport-stat-title">
                        Total Vehicles
                    </div>

                </div>

                <i class="bi bi-bus-front-fill transport-stat-icon"></i>

            </div>

            <a
                href="#"
                class="transport-stat-link"
            >
                View vehicles →
            </a>

        </div>


        {{-- ACTIVE VEHICLES --}}
        <div class="transport-stat red">

            <div class="transport-stat-top">

                <div>

                    <div class="transport-stat-number">
                        {{ number_format($activeVehicles) }}
                    </div>

                    <div class="transport-stat-title">
                        Active Vehicles
                    </div>

                </div>

                <i class="bi bi-check-circle-fill transport-stat-icon"></i>

            </div>

            <a
                href="#"
                class="transport-stat-link"
            >
                Vehicle status →
            </a>

        </div>

    </div>


    {{-- =====================================================
         TRANSPORT MODULES
    ====================================================== --}}

    <div class="transport-module-grid">


        {{-- =================================================
             TRANSPORT RECORDS
        ================================================== --}}

        <div class="transport-card">

            <div class="transport-card-header">

                <div class="transport-card-title">

                    <i
                        class="bi bi-person-vcard-fill"
                        style="color:#1769d1;"
                    ></i>

                    <h3>
                        Transport Records
                    </h3>

                </div>

            </div>


            <div class="transport-card-body">

                <div class="module-icon blue">

                    <i class="bi bi-person-vcard-fill"></i>

                </div>

                <div class="transport-card-description">

                    Assign students to transport services and manage
                    pickup points, drop points, routes and transport status.

                </div>

                <a
                    href="#"
                    class="module-button blue"
                >
                    View Transport Records

                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>


        {{-- =================================================
             ROUTES
        ================================================== --}}

        <div class="transport-card">

            <div class="transport-card-header">

                <div class="transport-card-title">

                    <i
                        class="bi bi-signpost-split-fill"
                        style="color:#ea580c;"
                    ></i>

                    <h3>
                        Routes
                    </h3>

                </div>

            </div>


            <div class="transport-card-body">

                <div class="module-icon orange">

                    <i class="bi bi-signpost-split-fill"></i>

                </div>

                <div class="transport-card-description">

                    Create and manage school transport routes,
                    stops, assigned vehicles and drivers.

                </div>

                <a
                    href="#"
                    class="module-button orange"
                >
                    Manage Routes

                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>


        {{-- =================================================
             VEHICLES
        ================================================== --}}

        <div class="transport-card">

            <div class="transport-card-header">

                <div class="transport-card-title">

                    <i
                        class="bi bi-bus-front-fill"
                        style="color:#079dbd;"
                    ></i>

                    <h3>
                        Vehicles
                    </h3>

                </div>

            </div>


            <div class="transport-card-body">

                <div class="module-icon cyan">

                    <i class="bi bi-bus-front-fill"></i>

                </div>

                <div class="transport-card-description">

                    Manage buses and vehicles including capacity,
                    drivers, insurance, RC, fitness and permit details.

                </div>

                <a
                    href="#"
                    class="module-button cyan"
                >
                    Manage Vehicles

                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>


    </div>


    {{-- =====================================================
         TRANSPORT INFORMATION
    ====================================================== --}}

    <div class="transport-info-card">

        <div class="transport-info-header">

            <div>

                <div class="transport-info-title">

                    <i class="bi bi-info-circle-fill"></i>

                    <h3>
                        Transport Management
                    </h3>

                </div>

            </div>

        </div>


        <div class="transport-info-body">

            <div class="transport-info-grid">


                {{-- STUDENTS --}}
                <div class="transport-info-item">

                    <div class="transport-info-icon blue-bg">

                        <i class="bi bi-people-fill"></i>

                    </div>

                    <strong>
                        Student Assignments
                    </strong>

                    <span>
                        Manage student transport assignments
                    </span>

                </div>


                {{-- ROUTES --}}
                <div class="transport-info-item">

                    <div class="transport-info-icon orange-bg">

                        <i class="bi bi-signpost-split-fill"></i>

                    </div>

                    <strong>
                        Route Management
                    </strong>

                    <span>
                        Manage routes and transportation stops
                    </span>

                </div>


                {{-- VEHICLES --}}
                <div class="transport-info-item">

                    <div class="transport-info-icon green-bg">

                        <i class="bi bi-bus-front-fill"></i>

                    </div>

                    <strong>
                        Vehicle Management
                    </strong>

                    <span>
                        Manage school buses and vehicles
                    </span>

                </div>


                {{-- SAFETY --}}
                <div class="transport-info-item">

                    <div class="transport-info-icon purple-bg">

                        <i class="bi bi-shield-check"></i>

                    </div>

                    <strong>
                        Vehicle Documents
                    </strong>

                    <span>
                        Track insurance, RC, fitness and permits
                    </span>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection