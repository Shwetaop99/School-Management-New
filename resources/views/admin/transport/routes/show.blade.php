@extends('layouts.app')

@section('title', 'Route Details | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — TRANSPORT ROUTE DETAILS
    ========================================================= */

    .transport-route-show-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 24px;
        background: #f5f3e9;
        color: #263b35;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
    }

    .transport-route-show-page *,
    .transport-route-show-page *::before,
    .transport-route-show-page *::after {
        box-sizing: border-box;
    }

    .transport-route-show-container {
        width: 100%;
        padding: 24px 26px 32px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .route-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 15px;
        padding: 17px 19px;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .route-show-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .route-show-icon {
        width: 43px;
        height: 43px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eaf3e9;
        color: #34775a;
        font-size: 19px;
    }

    .route-show-heading h1 {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.3;
        letter-spacing: -.35px;
    }

    .route-show-heading p {
        margin: 4px 0 0;
        color: #827e70;
        font-size: 11px;
        line-height: 1.5;
    }

    .route-show-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .header-btn {
        min-height: 36px;
        padding: 0 12px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 10.5px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, transform .2s ease;
    }

    .header-btn.secondary {
        color: #34775a;
        background: #fffefa;
        border: 1px solid #e5e1d3;
    }

    .header-btn.secondary:hover {
        color: #245a43;
        background: #eaf3e9;
        border-color: #b8d0bb;
        transform: translateY(-1px);
    }

    .header-btn.edit {
        color: #fffefa;
        background: #34775a;
        border: 1px solid #34775a;
    }

    .header-btn.edit:hover {
        color: #fffefa;
        background: #245a43;
        border-color: #245a43;
        transform: translateY(-1px);
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .route-show-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin: 0 0 17px;
        padding: 0 2px;
        color: #827e70;
        font-size: 10px;
    }

    .route-show-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        font-weight: 650;
    }

    .route-show-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .route-show-breadcrumb i {
        color: #aaa493;
        font-size: 9px;
    }

    /* =========================================================
       ROUTE HERO
    ========================================================= */

    .route-hero-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 17px;
        padding: 22px 24px;
        border: 1px solid #c9d8c7;
        border-radius: 11px;
        background: #eaf3e9;
        color: #263b35;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .route-hero-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 22px;
    }

    .route-hero-left {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .route-hero-icon {
        width: 55px;
        height: 55px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #fffefa;
        border: 1px solid #d9e4d5;
        color: #34775a;
        font-size: 23px;
    }

    .route-hero-number {
        margin-bottom: 4px;
        color: #527f79;
        font-size: 9px;
        font-weight: 750;
        letter-spacing: .8px;
        text-transform: uppercase;
    }

    .route-hero-name {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.35;
        letter-spacing: -.35px;
        overflow-wrap: anywhere;
    }

    .route-hero-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 5px 10px;
        border: 1px solid #c9d8c7;
        border-radius: 6px;
        background: #fffefa;
        color: #34775a;
        font-size: 10px;
        font-weight: 700;
    }

    .route-hero-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #34775a;
    }

    .route-hero-meta {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 25px;
        flex-shrink: 0;
    }

    .hero-meta-item {
        min-width: 78px;
        text-align: right;
    }

    .hero-meta-label {
        margin-bottom: 5px;
        color: #6e8274;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .55px;
        text-transform: uppercase;
    }

    .hero-meta-value {
        color: #263b35;
        font-size: 11px;
        font-weight: 750;
    }

    /* =========================================================
       MAIN GRID
    ========================================================= */

    .route-details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(280px, .85fr);
        gap: 17px;
        align-items: start;
    }

    .route-details-grid > div {
        min-width: 0;
    }

    .route-card {
        overflow: hidden;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
        transition: box-shadow .2s ease;
    }

    .route-card:hover {
        box-shadow: 0 5px 16px rgba(38, 59, 53, .07);
    }

    .route-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 17px;
        background: #fffefa;
        border-bottom: 1px solid #e5e1d3;
    }

    .route-card-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }

    .route-card-icon.blue {
        color: #34775a;
        background: #eaf3e9;
    }

    .route-card-icon.orange {
        color: #a87543;
        background: #f5eadb;
    }

    .route-card-icon.purple {
        color: #527f79;
        background: #e8f0ec;
    }

    .route-card-icon.green {
        color: #34775a;
        background: #eaf3e9;
    }

    .route-card-header h2 {
        margin: 0;
        color: #263b35;
        font-size: 12.5px;
        font-weight: 750;
        line-height: 1.4;
    }

    .route-card-header p {
        margin: 3px 0 0;
        color: #827e70;
        font-size: 10px;
        line-height: 1.45;
    }

    .route-card-body {
        padding: 17px;
    }

    /* =========================================================
       JOURNEY DETAILS
    ========================================================= */

    .journey-box {
        padding: 16px;
        border: 1px solid #e5e1d3;
        border-radius: 8px;
        background: #faf8ef;
    }

    .journey-point-row {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .journey-point-icon {
        width: 37px;
        height: 37px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .journey-point-icon.start {
        color: #34775a;
        background: #eaf3e9;
        border: 1px solid #d7e6d4;
    }

    .journey-point-icon.destination {
        color: #527f79;
        background: #e8f0ec;
        border: 1px solid #d6e2dc;
    }

    .journey-point-label {
        margin-bottom: 3px;
        color: #827e70;
        font-size: 9px;
        font-weight: 750;
        letter-spacing: .65px;
    }

    .journey-point-value {
        color: #263b35;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .journey-connector {
        height: 25px;
        margin-left: 17px;
        border-left: 2px dashed #c9d8c7;
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
        gap: 15px;
        min-height: 43px;
        padding: 8px 0;
        border-bottom: 1px solid #eeeade;
    }

    .detail-row:last-child {
        border-bottom: 0;
    }

    .detail-label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #827e70;
        font-size: 10.5px;
        font-weight: 550;
    }

    .detail-label i {
        color: #34775a;
        font-size: 12px;
    }

    .detail-value {
        color: #263b35;
        font-size: 10.5px;
        font-weight: 750;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .muted-value {
        color: #9a9585;
        font-weight: 500;
    }

    /* =========================================================
       ROUTE STOPS
    ========================================================= */

    .stops-list {
        position: relative;
        display: grid;
        gap: 0;
    }

    .stop-item {
        position: relative;
        display: flex;
        gap: 10px;
        padding-bottom: 15px;
        min-width: 0;
    }

    .stop-item:last-child {
        padding-bottom: 0;
    }

    .stop-marker-column {
        position: relative;
        display: flex;
        justify-content: center;
        width: 17px;
        flex-shrink: 0;
    }

    .stop-marker {
        position: relative;
        z-index: 2;
        width: 9px;
        height: 9px;
        margin-top: 5px;
        border-radius: 50%;
        background: #34775a;
        border: 2px solid #d5e5d2;
        box-sizing: content-box;
    }

    .stop-item:not(:last-child) .stop-marker-column::after {
        content: "";
        position: absolute;
        top: 19px;
        bottom: -3px;
        left: 8px;
        border-left: 1px dashed #c9d8c7;
    }

    .stop-text {
        min-width: 0;
        padding-top: 1px;
        color: #263b35;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .stop-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 23px;
        height: 21px;
        margin-right: 6px;
        padding: 0 4px;
        border: 1px solid #d7e6d4;
        border-radius: 5px;
        background: #eaf3e9;
        color: #34775a;
        font-size: 9px;
        font-weight: 750;
        vertical-align: middle;
    }

    /* =========================================================
       VEHICLE AND DRIVER
    ========================================================= */

    .person-info-card,
    .vehicle-info-card {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 13px;
        border: 1px solid #e5e1d3;
        border-radius: 8px;
        background: #faf8ef;
        transition: background .2s ease, border-color .2s ease;
    }

    .person-info-card:hover,
    .vehicle-info-card:hover {
        background: #f5f3e9;
        border-color: #d3ddca;
    }

    .info-avatar {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 17px;
    }

    .info-avatar.driver {
        color: #527f79;
        background: #e8f0ec;
        border: 1px solid #d6e2dc;
    }

    .info-avatar.vehicle {
        color: #a87543;
        background: #f5eadb;
        border: 1px solid #e9d8c0;
    }

    .info-label {
        margin-bottom: 3px;
        color: #827e70;
        font-size: 9px;
        font-weight: 750;
        letter-spacing: .55px;
    }

    .info-value {
        color: #263b35;
        font-size: 11.5px;
        font-weight: 750;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .info-secondary {
        margin-top: 4px;
        color: #827e70;
        font-size: 10px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .info-secondary .bi-check-circle-fill {
        color: #34775a;
    }

    .info-secondary .bi-telephone-fill {
        color: #527f79;
    }

    /* =========================================================
       REMARKS
    ========================================================= */

    .remarks-box {
        padding: 13px;
        border: 1px solid #e5e1d3;
        border-left: 3px solid #527f79;
        border-radius: 7px;
        background: #faf8ef;
        color: #5e685d;
        font-size: 11px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .remarks-box > i {
        color: #527f79;
    }

    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px;
        border: 1px solid transparent;
        border-radius: 5px;
        font-size: 9.5px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-badge.active {
        color: #34775a;
        background: #eaf3e9;
        border-color: #d5e5d2;
    }

    .status-badge.inactive {
        color: #bd5148;
        background: #f9e9e5;
        border-color: #efd0c9;
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
        padding: 17px 12px;
        border: 1px dashed #d8d2c1;
        border-radius: 8px;
        background: #faf8ef;
        text-align: center;
    }

    .empty-info-icon {
        width: 38px;
        height: 38px;
        margin: 0 auto 8px;
        border: 1px solid #e5e1d3;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f3e9;
        color: #827e70;
        font-size: 16px;
    }

    .empty-info-title {
        color: #263b35;
        font-size: 10.5px;
        font-weight: 750;
    }

    .empty-info-text {
        margin-top: 4px;
        color: #827e70;
        font-size: 10px;
        line-height: 1.5;
    }

    /* =========================================================
       BOTTOM ACTIONS
    ========================================================= */

    .route-bottom-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 17px;
        padding: 14px 17px;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .bottom-btn {
        min-height: 35px;
        padding: 0 12px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-family: inherit;
        font-size: 10.5px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, transform .2s ease;
    }

    .bottom-btn.back {
        color: #527f79;
        background: #fffefa;
        border: 1px solid #e5e1d3;
    }

    .bottom-btn.back:hover {
        color: #245a43;
        background: #eaf3e9;
        border-color: #c9d8c7;
    }

    .bottom-btn.edit {
        color: #fffefa;
        background: #34775a;
        border: 1px solid #34775a;
    }

    .bottom-btn.edit:hover {
        color: #fffefa;
        background: #245a43;
        border-color: #245a43;
        transform: translateY(-1px);
    }

    .bottom-btn.delete {
        color: #bd5148;
        background: #fffefa;
        border: 1px solid #e8c9c2;
    }

    .bottom-btn.delete:hover {
        color: #fffefa;
        background: #bd5148;
        border-color: #bd5148;
        transform: translateY(-1px);
    }

    .route-bottom-actions form {
        display: inline-flex;
        margin: 0;
    }

    /* =========================================================
       RESPONSIVE — TABLET
    ========================================================= */

    @media (max-width: 991.98px) {
        .transport-route-show-container {
            padding: 20px 18px 28px;
        }

        .route-details-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .route-hero-content {
            align-items: flex-start;
            flex-direction: column;
            gap: 18px;
        }

        .route-hero-meta {
            width: 100%;
            justify-content: flex-start;
            padding-top: 14px;
            border-top: 1px solid #d5e2d0;
        }

        .hero-meta-item {
            text-align: left;
        }
    }

    /* =========================================================
       RESPONSIVE — MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {
        .transport-route-show-container {
            padding: 16px 13px 24px;
        }

        .route-show-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 15px;
        }

        .route-show-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .route-show-actions .header-btn {
            flex: 1;
        }

        .route-show-heading h1 {
            font-size: 19px;
        }

        .route-hero-card {
            padding: 18px;
        }

        .route-hero-left {
            align-items: flex-start;
        }

        .route-hero-name {
            font-size: 19px;
        }

        .route-hero-meta {
            gap: 20px;
            flex-wrap: wrap;
        }

        .route-card-header {
            padding: 13px 14px;
        }

        .route-card-body {
            padding: 14px;
        }

        .detail-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
            padding: 10px 0;
        }

        .detail-value {
            text-align: left;
        }

        .route-bottom-actions {
            justify-content: stretch;
            padding: 12px;
        }

        .route-bottom-actions > .bottom-btn,
        .route-bottom-actions > form {
            flex: 1 1 calc(50% - 8px);
        }

        .route-bottom-actions form .bottom-btn {
            width: 100%;
        }
    }

    /* =========================================================
       RESPONSIVE — SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {
        .transport-route-show-container {
            padding: 12px 10px 20px;
        }

        .route-show-heading {
            align-items: flex-start;
            gap: 10px;
        }

        .route-show-icon {
            width: 38px;
            height: 38px;
            font-size: 17px;
        }

        .route-show-heading h1 {
            font-size: 17px;
        }

        .route-show-heading p {
            font-size: 10px;
        }

        .route-show-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .route-show-actions .header-btn {
            width: 100%;
            flex: initial;
        }

        .route-hero-card {
            padding: 15px;
        }

        .route-hero-left {
            gap: 10px;
        }

        .route-hero-icon {
            width: 43px;
            height: 43px;
            border-radius: 8px;
            font-size: 18px;
        }

        .route-hero-name {
            font-size: 16px;
        }

        .route-hero-meta {
            gap: 14px;
        }

        .hero-meta-item {
            min-width: 0;
        }

        .hero-meta-label {
            font-size: 8px;
        }

        .hero-meta-value {
            font-size: 10px;
        }

        .route-card-header {
            gap: 8px;
            padding: 12px;
        }

        .route-card-icon {
            width: 30px;
            height: 30px;
            font-size: 13px;
        }

        .route-card-header h2 {
            font-size: 11.5px;
        }

        .route-card-body {
            padding: 12px;
        }

        .journey-box {
            padding: 12px;
        }

        .person-info-card,
        .vehicle-info-card {
            align-items: flex-start;
            padding: 10px;
        }

        .info-avatar {
            width: 34px;
            height: 34px;
            font-size: 14px;
        }

        .route-bottom-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .route-bottom-actions > .bottom-btn,
        .route-bottom-actions > form {
            width: 100%;
            flex: initial;
        }
    }

    /* =========================================================
       REDUCED MOTION
    ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .transport-route-show-page *,
        .transport-route-show-page *::before,
        .transport-route-show-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
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

                {{-- JOURNEY DETAILS --}}

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

                {{-- ROUTE STOPS --}}

                <div class="route-card" style="margin-top:17px;">

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

                {{-- REMARKS --}}

                @if($transportRoute->remarks)

                    <div class="route-card" style="margin-top:17px;">

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

                {{-- ROUTE INFORMATION --}}

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

                {{-- ASSIGNED VEHICLE --}}

                <div class="route-card" style="margin-top:17px;">

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

                {{-- DRIVER INFORMATION --}}

                <div class="route-card" style="margin-top:17px;">

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