@extends('layouts.app')

@section('title', 'Route Details | Admin')

@section('content')

<style>
    /* =========================================================
       TRANSPORT ROUTE DETAILS
    ========================================================= */

    .transport-route-show-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
    }

    .transport-route-show-container {
        width: 100%;
        padding: 28px 30px 40px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .route-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .route-show-heading {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .route-show-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
        font-size: 21px;
        box-shadow: 0 7px 18px rgba(23, 105, 209, .20);
        flex-shrink: 0;
    }

    .route-show-heading h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 750;
        color: #172033;
        letter-spacing: -.3px;
    }

    .route-show-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .route-show-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .header-btn {
        min-height: 42px;
        padding: 0 16px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        transition: all .25s ease;
        white-space: nowrap;
    }

    .header-btn.secondary {
        color: #1769d1;
        background: #fff;
        border: 1px solid #dce6f1;
    }

    .header-btn.secondary:hover {
        color: #1769d1;
        background: #f2f7ff;
        border-color: #bcd3ee;
        transform: translateY(-1px);
    }

    .header-btn.edit {
        color: #fff;
        background: linear-gradient(135deg, #ed9208, #f7aa25);
        box-shadow: 0 7px 16px rgba(237, 146, 8, .18);
    }

    .header-btn.edit:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(237, 146, 8, .24);
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .route-show-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 22px;
        font-size: 12px;
        color: #94a3b8;
    }

    .route-show-breadcrumb a {
        color: #1769d1;
        text-decoration: none;
        font-weight: 600;
    }

    .route-show-breadcrumb a:hover {
        color: #0f58b4;
    }

    .route-show-breadcrumb i {
        font-size: 10px;
    }

    /* =========================================================
       HERO CARD
    ========================================================= */

    .route-hero-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        padding: 27px 30px;
        border-radius: 18px;
        background: linear-gradient(135deg, #1769d1 0%, #159cc7 100%);
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
    }

    .route-hero-card::before {
        content: "";
        position: absolute;
        width: 270px;
        height: 270px;
        right: -100px;
        bottom: -160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
    }

    .route-hero-card::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        right: -60px;
        top: -90px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .route-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .route-hero-left {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .route-hero-icon {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .16);
        border: 1px solid rgba(255, 255, 255, .18);
        font-size: 26px;
        flex-shrink: 0;
        backdrop-filter: blur(8px);
    }

    .route-hero-number {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        opacity: .82;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .route-hero-name {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -.45px;
        word-break: break-word;
    }

    .route-hero-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 10px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .16);
        font-size: 10.5px;
        font-weight: 700;
    }

    .route-hero-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255,255,255,.12);
    }

    .route-hero-meta {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-shrink: 0;
    }

    .hero-meta-item {
        text-align: right;
        min-width: 85px;
    }

    .hero-meta-label {
        font-size: 10px;
        opacity: .72;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .hero-meta-value {
        font-size: 13px;
        font-weight: 700;
    }

    /* =========================================================
       GRID
    ========================================================= */

    .route-details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, .85fr);
        gap: 20px;
        align-items: start;
    }

    .route-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
        transition: box-shadow .22s ease, transform .22s ease;
    }

    .route-card:hover {
        box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
    }

    .route-card-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 19px 21px;
        border-bottom: 1px solid #edf1f6;
    }

    .route-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .route-card-icon.blue {
        background: #dbeafe;
        color: #1769d1;
    }

    .route-card-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .route-card-icon.purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .route-card-icon.green {
        background: #dcfce7;
        color: #16a34a;
    }

    .route-card-header h2 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 750;
    }

    .route-card-header p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 10.5px;
    }

    .route-card-body {
        padding: 21px;
    }

    /* =========================================================
       JOURNEY
    ========================================================= */

    .journey-box {
        padding: 19px;
        border: 1px solid #e6edf5;
        border-radius: 13px;
        background: #f8fafc;
    }

    .journey-point-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .journey-point-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }

    .journey-point-icon.start {
        background: #dbeafe;
        color: #1769d1;
    }

    .journey-point-icon.destination {
        background: #dcfce7;
        color: #16a34a;
    }

    .journey-point-label {
        color: #94a3b8;
        font-size: 9.5px;
        font-weight: 700;
        margin-bottom: 3px;
        letter-spacing: .4px;
    }

    .journey-point-value {
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .journey-connector {
        height: 29px;
        margin-left: 20px;
        border-left: 2px dashed #cbd5e1;
    }

    /* =========================================================
       DETAIL LIST
    ========================================================= */

    .detail-list {
        display: grid;
        gap: 0;
    }

    .detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        min-height: 49px;
        border-bottom: 1px solid #edf1f6;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 550;
    }

    .detail-label i {
        color: #1769d1;
        font-size: 13px;
    }

    .detail-value {
        color: #172033;
        font-size: 12px;
        font-weight: 700;
        text-align: right;
        word-break: break-word;
    }

    .muted-value {
        color: #94a3b8;
        font-weight: 500;
    }

    /* =========================================================
       STOPS
    ========================================================= */

    .stops-list {
        position: relative;
        display: grid;
        gap: 0;
    }

    .stop-item {
        position: relative;
        display: flex;
        gap: 13px;
        padding-bottom: 18px;
    }

    .stop-item:last-child {
        padding-bottom: 0;
    }

    .stop-marker-column {
        position: relative;
        width: 18px;
        flex-shrink: 0;
        display: flex;
        justify-content: center;
    }

    .stop-marker {
        position: relative;
        z-index: 2;
        width: 13px;
        height: 13px;
        margin-top: 3px;
        border-radius: 50%;
        background: #1769d1;
        border: 3px solid #dbeafe;
        box-sizing: content-box;
    }

    .stop-item:not(:last-child) .stop-marker-column::after {
        content: "";
        position: absolute;
        top: 18px;
        bottom: -4px;
        left: 9px;
        border-left: 1px dashed #cbd5e1;
    }

    .stop-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 23px;
        height: 23px;
        margin-right: 8px;
        border-radius: 7px;
        background: #edf5ff;
        color: #1769d1;
        font-size: 9px;
        font-weight: 750;
    }

    .stop-text {
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.45;
        padding-top: 1px;
    }

    /* =========================================================
       VEHICLE / DRIVER CARDS
    ========================================================= */

    .person-info-card,
    .vehicle-info-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 15px;
        border: 1px solid #e6edf5;
        border-radius: 13px;
        background: #f8fafc;
        transition: all .2s ease;
    }

    .person-info-card:hover,
    .vehicle-info-card:hover {
        background: #f2f7ff;
        border-color: #d8e6f5;
    }

    .info-avatar {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .info-avatar.driver {
        color: #7c3aed;
        background: #ede9fe;
    }

    .info-avatar.vehicle {
        color: #ea580c;
        background: #ffedd5;
    }

    .info-label {
        color: #94a3b8;
        font-size: 9.5px;
        margin-bottom: 3px;
        font-weight: 700;
        letter-spacing: .35px;
    }

    .info-value {
        color: #172033;
        font-size: 13px;
        font-weight: 750;
        word-break: break-word;
    }

    .info-secondary {
        margin-top: 4px;
        color: #64748b;
        font-size: 10.5px;
    }

    /* =========================================================
       REMARKS
    ========================================================= */

    .remarks-box {
        padding: 14px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        color: #475569;
        font-size: 12px;
        line-height: 1.65;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 700;
    }

    .status-badge.active {
        color: #15803d;
        background: #dcfce7;
    }

    .status-badge.inactive {
        color: #b91c1c;
        background: #fee2e2;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-info {
        padding: 17px;
        border-radius: 13px;
        background: #f8fafc;
        border: 1px dashed #d9e2ec;
        text-align: center;
    }

    .empty-info-icon {
        width: 42px;
        height: 42px;
        margin: 0 auto 9px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #edf2f7;
        color: #94a3b8;
        font-size: 17px;
    }

    .empty-info-title {
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
    }

    .empty-info-text {
        margin-top: 3px;
        color: #94a3b8;
        font-size: 10px;
    }

    /* =========================================================
       BOTTOM ACTIONS
    ========================================================= */

    .route-bottom-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        padding: 18px 21px;
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
    }

    .bottom-btn {
        min-height: 40px;
        padding: 0 15px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: all .22s ease;
    }

    .bottom-btn.back {
        color: #64748b;
        background: #fff;
        border: 1px solid #e2e8f0;
    }

    .bottom-btn.back:hover {
        color: #1769d1;
        background: #f2f7ff;
        border-color: #bfd5ed;
    }

    .bottom-btn.edit {
        color: #fff;
        background: #ed9208;
        border: 1px solid #ed9208;
    }

    .bottom-btn.edit:hover {
        color: #fff;
        background: #d98100;
        border-color: #d98100;
        transform: translateY(-1px);
    }

    .bottom-btn.delete {
        color: #dc2626;
        background: #fff;
        border: 1px solid #fecaca;
    }

    .bottom-btn.delete:hover {
        color: #fff;
        background: #dc2626;
        border-color: #dc2626;
        transform: translateY(-1px);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 950px) {

        .transport-route-show-container {
            padding: 22px 20px 35px;
        }

        .route-details-grid {
            grid-template-columns: 1fr;
        }

        .route-hero-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .route-hero-meta {
            width: 100%;
            justify-content: flex-start;
        }

        .hero-meta-item {
            text-align: left;
        }
    }

    @media (max-width: 650px) {

        .transport-route-show-container {
            padding: 18px 14px 30px;
        }

        .route-show-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .route-show-actions {
            width: 100%;
        }

        .route-show-actions .header-btn {
            flex: 1;
        }

        .route-show-heading h1 {
            font-size: 21px;
        }

        .route-hero-card {
            padding: 21px;
        }

        .route-hero-left {
            align-items: flex-start;
        }

        .route-hero-name {
            font-size: 20px;
        }

        .route-hero-meta {
            gap: 20px;
            flex-wrap: wrap;
        }

        .route-card-body {
            padding: 17px;
        }

        .detail-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
            padding: 12px 0;
        }

        .detail-value {
            text-align: left;
        }

        .route-bottom-actions {
            padding: 15px;
            flex-wrap: wrap;
        }

        .bottom-btn {
            flex: 1;
        }
    }
</style>

<div class="transport-route-show-page">

    <div class="transport-route-show-container">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="route-show-header">

            <div class="route-show-heading">

                <div class="route-show-icon">
                    <i class="bi bi-signpost-2-fill"></i>
                </div>

                <div>
                    <h1>Route Details</h1>

                    <p>
                        View complete information about this transport route.
                    </p>
                </div>

            </div>

            <div class="route-show-actions">

                <a
                    href="{{ route('admin.transport.routes.index') }}"
                    class="header-btn secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Routes
                </a>

                <a
                    href="{{ route('admin.transport.routes.edit', ['transportRoute' => $transportRoute->id]) }}"
                    class="header-btn edit"
                >
                    <i class="bi bi-pencil-fill"></i>
                    Edit Route
                </a>

            </div>

        </div>

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}

        <div class="route-show-breadcrumb">

            <a href="{{ route('admin.transport.records.index') }}">
                Transport Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.transport.routes.index') }}">
                Routes
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>{{ $transportRoute->route_number }}</span>

        </div>

        {{-- =====================================================
             ROUTE HERO
        ====================================================== --}}

        <div class="route-hero-card">

            <div class="route-hero-content">

                <div class="route-hero-left">

                    <div class="route-hero-icon">
                        <i class="bi bi-signpost-2-fill"></i>
                    </div>

                    <div>

                        <div class="route-hero-number">
                            Route {{ $transportRoute->route_number }}
                        </div>

                        <h2 class="route-hero-name">
                            {{ $transportRoute->route_name }}
                        </h2>

                        <div class="route-hero-status">
                            <span class="route-hero-status-dot"></span>
                            {{ ucfirst($transportRoute->status) }}
                        </div>

                    </div>

                </div>

                <div class="route-hero-meta">

                    <div class="hero-meta-item">

                        <div class="hero-meta-label">
                            Created
                        </div>

                        <div class="hero-meta-value">
                            {{ $transportRoute->created_at?->format('d M Y') ?? '—' }}
                        </div>

                    </div>

                    <div class="hero-meta-item">

                        <div class="hero-meta-label">
                            Last Updated
                        </div>

                        <div class="hero-meta-value">
                            {{ $transportRoute->updated_at?->format('d M Y') ?? '—' }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- =====================================================
             DETAILS GRID
        ====================================================== --}}

        <div class="route-details-grid">

            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

            <div>

                {{-- Journey --}}

                <div class="route-card">

                    <div class="route-card-header">

                        <div class="route-card-icon blue">
                            <i class="bi bi-signpost-split-fill"></i>
                        </div>

                        <div>
                            <h2>Journey Details</h2>

                            <p>
                                Starting point and final destination.
                            </p>
                        </div>

                    </div>

                    <div class="route-card-body">

                        <div class="journey-box">

                            <div class="journey-point-row">

                                <div class="journey-point-icon start">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>

                                <div>

                                    <div class="journey-point-label">
                                        STARTING POINT
                                    </div>

                                    <div class="journey-point-value">
                                        {{ $transportRoute->starting_point ?: 'Not specified' }}
                                    </div>

                                </div>

                            </div>

                            <div class="journey-connector"></div>

                            <div class="journey-point-row">

                                <div class="journey-point-icon destination">
                                    <i class="bi bi-flag-fill"></i>
                                </div>

                                <div>

                                    <div class="journey-point-label">
                                        DESTINATION
                                    </div>

                                    <div class="journey-point-value">
                                        {{ $transportRoute->destination ?: 'Not specified' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Stops --}}

                <div class="route-card" style="margin-top:20px;">

                    <div class="route-card-header">

                        <div class="route-card-icon orange">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <h2>Route Stops</h2>

                            <p>
                                Pickup and drop-off points along the route.
                            </p>
                        </div>

                    </div>

                    <div class="route-card-body">

                        @if($transportRoute->stops)

                            @php
                                $stops = preg_split(
                                    '/\r\n|\r|\n|,/',
                                    $transportRoute->stops
                                );

                                $stops = array_values(
                                    array_filter(
                                        array_map(
                                            'trim',
                                            $stops
                                        )
                                    )
                                );
                            @endphp

                            @if(count($stops))

                                <div class="stops-list">

                                    @foreach($stops as $index => $stop)

                                        <div class="stop-item">

                                            <div class="stop-marker-column">

                                                <span class="stop-marker"></span>

                                            </div>

                                            <div class="stop-text">

                                                <span class="stop-number">
                                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                </span>

                                                {{ $stop }}

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            @else

                                <div class="empty-info">

                                    <div class="empty-info-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>

                                    <div class="empty-info-title">
                                        No route stops available
                                    </div>

                                    <div class="empty-info-text">
                                        No pickup or drop-off points have been added.
                                    </div>

                                </div>

                            @endif

                        @else

                            <div class="empty-info">

                                <div class="empty-info-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div class="empty-info-title">
                                    No route stops available
                                </div>

                                <div class="empty-info-text">
                                    No pickup or drop-off points have been added.
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- Remarks --}}

                @if($transportRoute->remarks)

                    <div class="route-card" style="margin-top:20px;">

                        <div class="route-card-header">

                            <div class="route-card-icon green">
                                <i class="bi bi-card-text"></i>
                            </div>

                            <div>
                                <h2>Remarks</h2>

                                <p>
                                    Additional route information.
                                </p>
                            </div>

                        </div>

                        <div class="route-card-body">

                            <div class="remarks-box">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ $transportRoute->remarks }}
                            </div>

                        </div>

                    </div>

                @endif

            </div>

            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div>

                {{-- Route Information --}}

                <div class="route-card">

                    <div class="route-card-header">

                        <div class="route-card-icon blue">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>

                        <div>
                            <h2>Route Information</h2>

                            <p>
                                Basic route details.
                            </p>
                        </div>

                    </div>

                    <div class="route-card-body">

                        <div class="detail-list">

                            <div class="detail-row">

                                <div class="detail-label">
                                    <i class="bi bi-hash"></i>
                                    Route Number
                                </div>

                                <div class="detail-value">
                                    {{ $transportRoute->route_number }}
                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">
                                    <i class="bi bi-signpost-2"></i>
                                    Route Name
                                </div>

                                <div class="detail-value">
                                    {{ $transportRoute->route_name }}
                                </div>

                            </div>

                            <div class="detail-row">

                                <div class="detail-label">
                                    <i class="bi bi-toggle-on"></i>
                                    Status
                                </div>

                                <div class="detail-value">

                                    <span
                                        class="status-badge {{ $transportRoute->status === 'active' ? 'active' : 'inactive' }}"
                                    >

                                        <span class="status-dot"></span>

                                        {{ ucfirst($transportRoute->status) }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- Vehicle --}}

                <div class="route-card" style="margin-top:20px;">

                    <div class="route-card-header">

                        <div class="route-card-icon orange">
                            <i class="bi bi-bus-front-fill"></i>
                        </div>

                        <div>
                            <h2>Assigned Vehicle</h2>

                            <p>
                                Vehicle assigned to this route.
                            </p>
                        </div>

                    </div>

                    <div class="route-card-body">

                        @if($transportRoute->assigned_vehicle)

                            <div class="vehicle-info-card">

                                <div class="info-avatar vehicle">
                                    <i class="bi bi-bus-front-fill"></i>
                                </div>

                                <div>

                                    <div class="info-label">
                                        VEHICLE NUMBER
                                    </div>

                                    <div class="info-value">
                                        {{ $transportRoute->assigned_vehicle }}
                                    </div>

                                    <div class="info-secondary">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Assigned to this route
                                    </div>

                                </div>

                            </div>

                        @else

                            <div class="empty-info">

                                <div class="empty-info-icon">
                                    <i class="bi bi-bus-front"></i>
                                </div>

                                <div class="empty-info-title">
                                    Vehicle not assigned
                                </div>

                                <div class="empty-info-text">
                                    No vehicle has been assigned to this route.
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

                {{-- Driver --}}

                <div class="route-card" style="margin-top:20px;">

                    <div class="route-card-header">

                        <div class="route-card-icon purple">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>

                        <div>
                            <h2>Driver Information</h2>

                            <p>
                                Driver assigned to this route.
                            </p>
                        </div>

                    </div>

                    <div class="route-card-body">

                        @if($transportRoute->driver_name)

                            <div class="person-info-card">

                                <div class="info-avatar driver">
                                    <i class="bi bi-person-fill"></i>
                                </div>

                                <div>

                                    <div class="info-label">
                                        DRIVER
                                    </div>

                                    <div class="info-value">
                                        {{ $transportRoute->driver_name }}
                                    </div>

                                    @if($transportRoute->driver_contact)

                                        <div class="info-secondary">
                                            <i class="bi bi-telephone-fill me-1"></i>
                                            {{ $transportRoute->driver_contact }}
                                        </div>

                                    @else

                                        <div class="info-secondary">
                                            Contact number not available
                                        </div>

                                    @endif

                                </div>

                            </div>

                        @else

                            <div class="empty-info">

                                <div class="empty-info-icon">
                                    <i class="bi bi-person"></i>
                                </div>

                                <div class="empty-info-title">
                                    Driver not assigned
                                </div>

                                <div class="empty-info-text">
                                    No driver has been assigned to this route.
                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- =====================================================
             BOTTOM ACTIONS
        ====================================================== --}}

        <div class="route-bottom-actions">

            <a
                href="{{ route('admin.transport.routes.index') }}"
                class="bottom-btn back"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Routes
            </a>

            <a
                href="{{ route('admin.transport.routes.edit', ['transportRoute' => $transportRoute->id]) }}"
                class="bottom-btn edit"
            >
                <i class="bi bi-pencil-fill"></i>
                Edit Route
            </a>

            <form
                action="{{ route('admin.transport.routes.destroy', ['transportRoute' => $transportRoute->id]) }}"
                method="POST"
                style="display:inline;"
                onsubmit="return confirm('Are you sure you want to delete this route?');"
            >

                @csrf

                @method('DELETE')

                <button
                    type="submit"
                    class="bottom-btn delete"
                >
                    <i class="bi bi-trash-fill"></i>
                    Delete Route
                </button>

            </form>

        </div>

    </div>

</div>

@endsection