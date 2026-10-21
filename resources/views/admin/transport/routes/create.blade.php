@extends('layouts.app')

@section('title', 'Add Transport Route | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — ADD TRANSPORT ROUTE
    ========================================================= */

    .transport-route-create-page {
        --diary-bg: #f5f3e9;
        --diary-paper: #fffefa;
        --diary-ink: #263b35;
        --diary-green: #34775a;
        --diary-green-dark: #245a43;
        --diary-green-light: #eaf3e9;
        --diary-wood: #a87543;
        --diary-cyan: #527f79;
        --diary-red: #bd5148;
        --diary-border: #e5e1d3;
        --diary-muted: #827e70;

        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 30px;
        background: var(--diary-bg);
        color: var(--diary-ink);
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .transport-route-create-page *,
    .transport-route-create-page *::before,
    .transport-route-create-page *::after {
        box-sizing: border-box;
    }

    .transport-route-create-container {
        width: 100%;
        padding: 22px 25px 32px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .route-create-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 16px;
        padding: 19px 22px;
        background: var(--diary-paper);
        border: 1px solid var(--diary-border);
        border-left: 4px solid var(--diary-green);
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, .045);
    }

    .route-create-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .route-create-icon {
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8e6d7;
        border-radius: 9px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 19px;
    }

    .route-create-heading h1 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 22px;
        font-weight: 750;
        line-height: 1.3;
        letter-spacing: -.45px;
    }

    .route-create-heading p {
        margin: 4px 0 0;
        color: var(--diary-muted);
        font-size: 11px;
        font-weight: 450;
        line-height: 1.6;
    }

    /* HEADER BUTTON */

    .header-btn {
        min-height: 35px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-shrink: 0;
        border-radius: 7px;
        text-decoration: none;
        font-size: 10.5px;
        font-weight: 650;
        white-space: nowrap;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .header-btn.secondary {
        color: var(--diary-green);
        background: var(--diary-paper);
        border: 1px solid #cfdccd;
    }

    .header-btn.secondary:hover {
        color: var(--diary-green-dark);
        background: var(--diary-green-light);
        border-color: #a9c8ad;
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .route-create-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 17px;
        color: var(--diary-muted);
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .35px;
        text-transform: uppercase;
    }

    .route-create-breadcrumb a {
        color: var(--diary-green);
        text-decoration: none;
        transition: color .2s ease;
    }

    .route-create-breadcrumb a:hover {
        color: var(--diary-green-dark);
        text-decoration: underline;
    }

    .route-create-breadcrumb i {
        color: #b2ad9e;
        font-size: 8px;
    }

    /* =========================================================
       VALIDATION ALERT
    ========================================================= */

    .route-error-alert {
        margin-bottom: 17px;
        padding: 13px 15px;
        background: #fff5f2;
        border: 1px solid #ebc9c2;
        border-left: 3px solid var(--diary-red);
        border-radius: 8px;
        color: #843b35;
    }

    .route-error-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 750;
    }

    .route-error-list {
        margin: 0;
        padding-left: 22px;
        font-size: 10px;
        line-height: 1.8;
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .route-form-card {
        overflow: hidden;
        background: var(--diary-paper);
        border: 1px solid var(--diary-border);
        border-radius: 10px;
        box-shadow: 0 3px 13px rgba(38, 59, 53, .045);
    }

    .route-form-card-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 16px 20px;
        background: #fdfbf4;
        border-bottom: 1px solid var(--diary-border);
    }

    .route-form-header-icon {
        width: 35px;
        height: 35px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8e6d7;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 15px;
    }

    .route-form-card-header h2 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 13px;
        font-weight: 750;
    }

    .route-form-card-header p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM BODY AND SECTIONS
    ========================================================= */

    .route-form-body {
        padding: 22px 21px;
    }

    .form-section {
        margin-bottom: 23px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .section-icon {
        width: 31px;
        height: 31px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 13px;
    }

    .section-icon.blue,
    .section-icon.orange,
    .section-icon.purple,
    .section-icon.green {
        color: var(--diary-green);
        background: var(--diary-green-light);
        border-color: #d8e6d7;
    }

    .form-section-heading h3 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4;
    }

    .form-section-heading p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 9.5px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM GRID
    ========================================================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .form-group {
        min-width: 0;
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* =========================================================
       LABELS AND INPUTS
    ========================================================= */

    .transport-route-create-page .form-label {
        display: block;
        margin-bottom: 6px;
        color: var(--diary-ink);
        font-size: 10.5px;
        font-weight: 700;
        line-height: 1.5;
    }

    .transport-route-create-page .required {
        margin-left: 2px;
        color: var(--diary-red);
    }

    .transport-route-create-page .form-control,
    .transport-route-create-page .form-select {
        width: 100%;
        min-width: 0;
        height: 38px;
        padding: 0 11px;
        border: 1px solid #dedbcc;
        border-radius: 7px;
        background: #fffefa;
        color: var(--diary-ink);
        font-family: inherit;
        font-size: 11px;
        font-weight: 500;
        outline: none;
        box-shadow: none;
        transition: border-color .18s ease, background .18s ease, box-shadow .18s ease;
    }

    .transport-route-create-page .form-control::placeholder {
        color: #a29c8d;
        font-size: 10.5px;
        font-weight: 400;
        opacity: 1;
    }

    .transport-route-create-page .form-control:hover,
    .transport-route-create-page .form-select:hover {
        border-color: #c8c1af;
        background: #fffefa;
    }

    .transport-route-create-page .form-control:focus,
    .transport-route-create-page .form-select:focus {
        border-color: var(--diary-green);
        background: #fffefa;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .transport-route-create-page .form-control[readonly] {
        background: #f6f3e9;
        color: #686b5d;
        cursor: default;
    }

    .transport-route-create-page textarea.form-control {
        height: 94px;
        min-height: 94px;
        padding: 10px 11px;
        resize: vertical;
        line-height: 1.6;
    }

    .field-help {
        margin-top: 5px;
        color: var(--diary-muted);
        font-size: 9px;
        line-height: 1.6;
    }

    .field-help i {
        margin-right: 3px;
        color: var(--diary-green);
    }

    .field-help strong {
        color: var(--diary-green-dark);
    }

    .field-error {
        margin-top: 5px;
        color: var(--diary-red);
        font-size: 9.5px;
        font-weight: 600;
        line-height: 1.5;
    }

    .has-error .form-control,
    .has-error .form-select {
        border-color: #d99c94;
        background: #fff9f7;
    }

    .has-error .form-control:focus,
    .has-error .form-select:focus {
        border-color: var(--diary-red);
        box-shadow: 0 0 0 3px rgba(189, 81, 72, .08);
    }

    /* =========================================================
       ROUTE STOPS INFORMATION
    ========================================================= */

    .stops-info-box {
        margin-top: 8px;
        padding: 10px 12px;
        border: 1px solid #dce5d7;
        border-radius: 7px;
        background: #f5f7ee;
        color: #666d5d;
        font-size: 9.5px;
        line-height: 1.65;
    }

    .stops-info-box i {
        margin-right: 4px;
        color: var(--diary-green);
    }

    /* =========================================================
       SELECTED VEHICLE INFORMATION
    ========================================================= */

    .selected-vehicle-card {
        display: none;
        margin-top: 16px;
        padding: 15px;
        border: 1px solid #d7e3d2;
        border-radius: 9px;
        background: #f5f7ee;
    }

    .selected-vehicle-card.show {
        display: block;
    }

    .selected-vehicle-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
        padding-bottom: 11px;
        border-bottom: 1px solid #dfe5d7;
    }

    .selected-vehicle-avatar {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d2e2d0;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 14px;
        font-weight: 750;
    }

    .selected-vehicle-header h6,
    .selected-driver-header h6 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .selected-vehicle-header p,
    .selected-driver-header p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 9px;
        line-height: 1.5;
    }

    .vehicle-detail-box {
        min-height: 57px;
        padding: 9px 10px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
    }

    .vehicle-detail-label,
    .driver-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 5px;
        color: var(--diary-muted);
        font-size: 9px;
        font-weight: 600;
    }

    .vehicle-detail-label i,
    .driver-detail-label i {
        color: var(--diary-green);
        font-size: 10px;
    }

    .vehicle-detail-value,
    .driver-detail-value {
        color: var(--diary-ink);
        font-size: 10.5px;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .vehicle-active-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 7px;
        border: 1px solid #d2e5d0;
        border-radius: 20px;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
        font-size: 9px;
        font-weight: 750;
    }

    .vehicle-active-badge i {
        font-size: 8px;
    }

    .vehicle-info-box,
    .driver-info-box {
        margin-top: 12px;
        padding: 10px 12px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: #fbf9f0;
        color: #706d60;
        font-size: 9px;
        line-height: 1.65;
    }

    .vehicle-info-box i,
    .driver-info-box i {
        margin-right: 4px;
        color: var(--diary-green);
    }

    /* =========================================================
       SELECTED DRIVER INFORMATION
    ========================================================= */

    .selected-driver-card {
        display: none;
        margin-top: 16px;
        padding: 15px;
        border: 1px solid #d7e3d2;
        border-radius: 9px;
        background: #f5f7ee;
    }

    .selected-driver-card.show {
        display: block;
    }

    .selected-driver-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
        padding-bottom: 11px;
        border-bottom: 1px solid #dfe5d7;
    }

    .selected-driver-avatar {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d2e2d0;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 14px;
        font-weight: 750;
    }

    .driver-detail-box {
        min-height: 57px;
        padding: 9px 10px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
    }

    /* =========================================================
       ROUTE STATUS
    ========================================================= */

    .status-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 9px;
    }

    .status-option {
        position: relative;
        min-width: 0;
    }

    .status-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label {
        min-height: 38px;
        padding: 0 11px;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #dedbcc;
        border-radius: 7px;
        background: #fffefa;
        color: #777366;
        font-size: 10.5px;
        font-weight: 650;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
    }

    .status-option label:hover {
        border-color: #c8c1af;
        background: #faf8ef;
    }

    .status-option input:checked + label {
        border-color: #9fbea0;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
    }

    .status-option input:focus-visible + label {
        outline: 2px solid var(--diary-green);
        outline-offset: 2px;
    }

    .status-radio-dot {
        width: 9px;
        height: 9px;
        flex-shrink: 0;
        border: 1px solid #bdb8a8;
        border-radius: 50%;
        background: transparent;
    }

    .status-option input:checked + label .status-radio-dot {
        border: 3px solid var(--diary-green);
        background: var(--diary-paper);
    }

    /* =========================================================
       DIVIDER
    ========================================================= */

    .form-divider {
        height: 1px;
        margin: 22px 0;
        background: var(--diary-border);
    }

    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .route-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding: 14px 21px;
        border-top: 1px solid var(--diary-border);
        background: #faf8ef;
    }

    .footer-btn {
        min-height: 36px;
        padding: 0 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 7px;
        font-family: inherit;
        font-size: 10.5px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s ease, color .18s ease, border-color .18s ease;
    }

    .footer-btn.cancel {
        border: 1px solid #dcd7c8;
        background: var(--diary-paper);
        color: #6f6c60;
    }

    .footer-btn.cancel:hover {
        border-color: #b9cdb7;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
    }

    .footer-btn.submit {
        border: 1px solid var(--diary-green);
        background: var(--diary-green);
        color: #fffefa;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .12);
    }

    .footer-btn.submit:hover {
        border-color: var(--diary-green-dark);
        background: var(--diary-green-dark);
    }

    .footer-btn:focus-visible,
    .header-btn:focus-visible {
        outline: 2px solid var(--diary-green);
        outline-offset: 3px;
    }

    /* =========================================================
       BOOTSTRAP GRID COMPATIBILITY
    ========================================================= */

    .transport-route-create-page .row {
        --bs-gutter-x: 12px;
        --bs-gutter-y: 12px;
    }

    /* =========================================================
       RESPONSIVE DESIGN
    ========================================================= */

    @media (max-width: 991.98px) {
        .transport-route-create-container {
            padding: 19px 18px 28px;
        }

        .route-create-header {
            align-items: flex-start;
        }

        .route-create-heading {
            align-items: flex-start;
        }

        .route-create-header > .header-btn {
            align-self: center;
        }

        .form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767.98px) {
        .transport-route-create-container {
            padding: 16px 14px 24px;
        }

        .route-create-header {
            flex-direction: column;
            align-items: stretch;
            gap: 13px;
            padding: 16px;
        }

        .route-create-header > .header-btn {
            width: fit-content;
            align-self: flex-start;
        }

        .route-create-heading h1 {
            font-size: 19px;
        }

        .route-create-heading p {
            font-size: 10px;
        }

        .route-form-card-header {
            padding: 14px 15px;
        }

        .route-form-body {
            padding: 18px 15px;
        }

        .route-form-footer {
            padding: 13px 15px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-divider {
            margin: 19px 0;
        }
    }

    @media (max-width: 480px) {
        .transport-route-create-container {
            padding: 12px 10px 20px;
        }

        .route-create-heading {
            gap: 10px;
        }

        .route-create-icon {
            width: 37px;
            height: 37px;
            font-size: 16px;
        }

        .route-create-heading h1 {
            font-size: 17px;
        }

        .route-create-heading p {
            font-size: 9.5px;
        }

        .route-create-breadcrumb {
            gap: 5px;
            font-size: 8px;
        }

        .route-form-card-header,
        .route-form-body {
            padding-left: 12px;
            padding-right: 12px;
        }

        .selected-vehicle-card,
        .selected-driver-card {
            padding: 11px;
        }

        .status-options {
            grid-template-columns: 1fr;
        }

        .route-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .footer-btn {
            width: 100%;
        }
    }

    /* =========================================================
       REDUCED MOTION
    ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .transport-route-create-page *,
        .transport-route-create-page *::before,
        .transport-route-create-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

<div class="transport-route-create-page">

    <div class="transport-route-create-container">

        {{-- PAGE HEADER --}}

        <div class="route-create-header">

            <div class="route-create-heading">

                <div class="route-create-icon">
                    <i class="bi bi-signpost-2-fill"></i>
                </div>

                <div>
                    <h1>Add Transport Route</h1>

                    <p>
                        Create a new school transport route with journey,
                        stop, vehicle and driver details.
                    </p>
                </div>

            </div>

            <a
                href="{{ route('admin.transport.routes.index') }}"
                class="header-btn secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Routes
            </a>

        </div>

        {{-- BREADCRUMB --}}

        <div class="route-create-breadcrumb">

            <a href="{{ route('admin.transport.records.index') }}">
                Transport Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.transport.routes.index') }}">
                Routes
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>Add Route</span>

        </div>

        {{-- VALIDATION ERRORS --}}

        @if($errors->any())

            <div class="route-error-alert">

                <div class="route-error-title">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Please correct the following errors:
                </div>

                <ul class="route-error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        @endif

        {{-- FORM CARD --}}

        <div class="route-form-card">

            <div class="route-form-card-header">

                <div class="route-form-header-icon">
                    <i class="bi bi-signpost-2-fill"></i>
                </div>

                <div>
                    <h2>Add Transport Route</h2>
                    <p>
                        Enter the route, vehicle and driver assignment details.
                    </p>
                </div>

            </div>

            <form
                action="{{ route('admin.transport.routes.store') }}"
                method="POST"
                id="transportRouteForm"
            >

                @csrf

                <div class="route-form-body">

                    {{-- 1. ROUTE DETAILS --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-icon blue">
                                <i class="bi bi-signpost-2-fill"></i>
                            </div>

                            <div>
                                <h3>Route Details</h3>
                                <p>
                                    Basic identification and journey information.
                                </p>
                            </div>

                        </div>

                        <div class="form-grid">

                            {{-- ROUTE NUMBER --}}

                            <div class="form-group {{ $errors->has('route_number') ? 'has-error' : '' }}">

                                <label class="form-label" for="route_number">
                                    Route Number <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="route_number"
                                    id="route_number"
                                    class="form-control"
                                    value="{{ old('route_number') }}"
                                    placeholder="e.g. R-001"
                                    maxlength="100"
                                    required
                                >

                                @error('route_number')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- ROUTE NAME --}}

                            <div class="form-group {{ $errors->has('route_name') ? 'has-error' : '' }}">

                                <label class="form-label" for="route_name">
                                    Route Name <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="route_name"
                                    id="route_name"
                                    class="form-control"
                                    value="{{ old('route_name') }}"
                                    placeholder="e.g. City Center Route"
                                    maxlength="255"
                                    required
                                >

                                @error('route_name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- STARTING POINT --}}

                            <div class="form-group {{ $errors->has('starting_point') ? 'has-error' : '' }}">

                                <label class="form-label" for="starting_point">
                                    Starting Point <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="starting_point"
                                    id="starting_point"
                                    class="form-control"
                                    value="{{ old('starting_point') }}"
                                    placeholder="e.g. School Main Gate"
                                    maxlength="255"
                                    required
                                >

                                @error('starting_point')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- DESTINATION --}}

                            <div class="form-group {{ $errors->has('destination') ? 'has-error' : '' }}">

                                <label class="form-label" for="destination">
                                    Destination <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="destination"
                                    id="destination"
                                    class="form-control"
                                    value="{{ old('destination') }}"
                                    placeholder="e.g. Chandgad"
                                    maxlength="255"
                                    required
                                >

                                @error('destination')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- 2. ROUTE STOPS --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-icon orange">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>

                            <div>
                                <h3>Route Stops</h3>
                                <p>
                                    Add the pickup and drop-off stops followed by the route.
                                </p>
                            </div>

                        </div>

                        <div class="form-grid">

                            <div class="form-group full-width {{ $errors->has('stops') ? 'has-error' : '' }}">

                                <label class="form-label" for="stops">
                                    Stops
                                </label>

                                <textarea
                                    name="stops"
                                    id="stops"
                                    class="form-control"
                                    placeholder="Enter route stops, one per line or separated by commas..."
                                >{{ old('stops') }}</textarea>

                                <div class="stops-info-box">
                                    <i class="bi bi-info-circle-fill"></i>
                                    Example: Main Gate, Market Chowk, Bus Stand,
                                    College Road, Station Road
                                </div>

                                @error('stops')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- 3. VEHICLE & DRIVER --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-icon purple">
                                <i class="bi bi-bus-front-fill"></i>
                            </div>

                            <div>
                                <h3>Vehicle &amp; Driver</h3>
                                <p>
                                    Assign the active vehicle and driver responsible for this route.
                                </p>
                            </div>

                        </div>

                        <div class="form-grid">

                            {{-- ACTIVE VEHICLE --}}

                            <div class="form-group {{ $errors->has('vehicle_id') ? 'has-error' : '' }}">

                                <label class="form-label" for="vehicle_id">
                                    Assigned Vehicle
                                </label>

                                <select
                                    name="vehicle_id"
                                    id="vehicle_id"
                                    class="form-select"
                                >

                                    <option value="">Select Active Vehicle</option>

                                    @forelse($vehicles as $vehicle)

                                        <option
                                            value="{{ $vehicle->id }}"
                                            data-vehicle-number="{{ $vehicle->vehicle_number }}"
                                            data-vehicle-type="{{ $vehicle->vehicle_type }}"
                                            data-capacity="{{ $vehicle->capacity }}"
                                            data-status="{{ $vehicle->status }}"
                                            data-driver-id="{{ $vehicle->driver_id }}"
                                            data-driver-name="{{ $vehicle->driver_name }}"
                                            data-driver-contact="{{ $vehicle->driver_contact }}"
                                            data-driver-license="{{ $vehicle->driver_license_number }}"
                                            {{ (string) old('vehicle_id') === (string) $vehicle->id ? 'selected' : '' }}
                                        >
                                            {{ $vehicle->vehicle_number }}

                                            @if($vehicle->vehicle_type)
                                                — {{ $vehicle->vehicle_type }}
                                            @endif
                                        </option>

                                    @empty

                                        <option value="" disabled>
                                            No active vehicles available
                                        </option>

                                    @endforelse

                                </select>

                                <div class="field-help">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Only vehicles with <strong>Active</strong>
                                    status are available for route assignment.
                                </div>

                                @error('vehicle_id')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- DRIVER --}}

                            <div class="form-group {{ $errors->has('driver_id') ? 'has-error' : '' }}">

                                <label class="form-label" for="driver_id">
                                    Assign Driver
                                </label>

                                <select
                                    name="driver_id"
                                    id="driver_id"
                                    class="form-select"
                                >

                                    <option value="">Select Driver</option>

                                    @forelse($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            data-name="{{ $driver->name }}"
                                            data-phone="{{ $driver->phone }}"
                                            data-staff-id="{{ $driver->staff_id }}"
                                            data-designation="{{ $driver->designation }}"
                                            data-department="{{ $driver->department }}"
                                            data-status="{{ $driver->status }}"
                                            {{ (string) old('driver_id') === (string) $driver->id ? 'selected' : '' }}
                                        >
                                            {{ $driver->name }}

                                            @if($driver->staff_id)
                                                — {{ $driver->staff_id }}
                                            @endif
                                        </option>

                                    @empty

                                        <option value="" disabled>
                                            No active drivers available
                                        </option>

                                    @endforelse

                                </select>

                                <div class="field-help">
                                    Select an active driver from the Other Staff module.
                                </div>

                                @error('driver_id')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        {{-- SELECTED VEHICLE INFORMATION --}}

                        <div class="selected-vehicle-card" id="selectedVehicleCard">

                            <div class="selected-vehicle-header">

                                <div class="selected-vehicle-avatar" id="selectedVehicleAvatar">
                                    V
                                </div>

                                <div>
                                    <h6 id="selectedVehicleTitle">Vehicle Details</h6>
                                    <p>Active vehicle information from Transport Vehicles</p>
                                </div>

                            </div>

                            <div class="row g-3">

                                <div class="col-lg-4 col-md-6">
                                    <div class="vehicle-detail-box">
                                        <div class="vehicle-detail-label">
                                            <i class="bi bi-upc-scan"></i>
                                            Vehicle Number
                                        </div>
                                        <div class="vehicle-detail-value" id="vehicleNumber">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="vehicle-detail-box">
                                        <div class="vehicle-detail-label">
                                            <i class="bi bi-bus-front"></i>
                                            Vehicle Type
                                        </div>
                                        <div class="vehicle-detail-value" id="vehicleType">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="vehicle-detail-box">
                                        <div class="vehicle-detail-label">
                                            <i class="bi bi-people-fill"></i>
                                            Capacity
                                        </div>
                                        <div class="vehicle-detail-value" id="vehicleCapacity">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="vehicle-detail-box">
                                        <div class="vehicle-detail-label">
                                            <i class="bi bi-check-circle"></i>
                                            Vehicle Status
                                        </div>
                                        <div class="vehicle-detail-value" id="vehicleStatus">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="vehicle-detail-box">
                                        <div class="vehicle-detail-label">
                                            <i class="bi bi-person-badge"></i>
                                            Vehicle Driver
                                        </div>
                                        <div class="vehicle-detail-value" id="vehicleDriver">—</div>
                                    </div>
                                </div>

                            </div>

                            <div class="vehicle-info-box">
                                <i class="bi bi-info-circle-fill"></i>
                                Vehicle information is automatically loaded from the selected
                                active Transport Vehicle record. Inactive and maintenance
                                vehicles cannot be assigned.
                            </div>

                        </div>

                        {{-- SELECTED DRIVER INFORMATION --}}

                        <div class="selected-driver-card" id="selectedDriverCard">

                            <div class="selected-driver-header">

                                <div class="selected-driver-avatar" id="selectedDriverAvatar">
                                    D
                                </div>

                                <div>
                                    <h6 id="selectedDriverTitle">Driver Details</h6>
                                    <p>Existing Other Staff information</p>
                                </div>

                            </div>

                            <div class="row g-3">

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-person-badge"></i>
                                            Driver Name
                                        </div>
                                        <div class="driver-detail-value" id="driverName">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-card-text"></i>
                                            Staff ID
                                        </div>
                                        <div class="driver-detail-value" id="driverStaffId">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-telephone"></i>
                                            Phone
                                        </div>
                                        <div class="driver-detail-value" id="driverPhone">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-person-workspace"></i>
                                            Designation
                                        </div>
                                        <div class="driver-detail-value" id="driverDesignation">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-building"></i>
                                            Department
                                        </div>
                                        <div class="driver-detail-value" id="driverDepartment">—</div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <div class="driver-detail-box">
                                        <div class="driver-detail-label">
                                            <i class="bi bi-check-circle"></i>
                                            Staff Status
                                        </div>
                                        <div class="driver-detail-value" id="driverStatus">—</div>
                                    </div>
                                </div>

                            </div>

                            <div class="driver-info-box">
                                <i class="bi bi-info-circle-fill"></i>
                                Driver information is automatically loaded from the selected
                                Other Staff record. It is not manually stored with the route.
                            </div>

                        </div>

                        <div class="form-divider"></div>

                        {{-- ROUTE STATUS --}}

                        <div class="form-grid">

                            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">

                                <label class="form-label">
                                    Route Status <span class="required">*</span>
                                </label>

                                <div class="status-options">

                                    <div class="status-option">

                                        <input
                                            type="radio"
                                            id="status-active"
                                            name="status"
                                            value="active"
                                            {{ old('status', 'active') === 'active' ? 'checked' : '' }}
                                        >

                                        <label for="status-active">
                                            <span class="status-radio-dot"></span>
                                            Active
                                        </label>

                                    </div>

                                    <div class="status-option">

                                        <input
                                            type="radio"
                                            id="status-inactive"
                                            name="status"
                                            value="inactive"
                                            {{ old('status') === 'inactive' ? 'checked' : '' }}
                                        >

                                        <label for="status-inactive">
                                            <span class="status-radio-dot"></span>
                                            Inactive
                                        </label>

                                    </div>

                                </div>

                                @error('status')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>

                    <div class="form-divider"></div>

                    {{-- 4. ADDITIONAL INFORMATION --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-icon green">
                                <i class="bi bi-card-text"></i>
                            </div>

                            <div>
                                <h3>Additional Information</h3>
                                <p>Add optional notes related to this route.</p>
                            </div>

                        </div>

                        <div class="form-grid">

                            <div class="form-group full-width {{ $errors->has('remarks') ? 'has-error' : '' }}">

                                <label class="form-label" for="remarks">
                                    Remarks
                                </label>

                                <textarea
                                    name="remarks"
                                    id="remarks"
                                    class="form-control"
                                    placeholder="Enter any additional route information..."
                                >{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FORM FOOTER --}}

                <div class="route-form-footer">

                    <a
                        href="{{ route('admin.transport.routes.index') }}"
                        class="footer-btn cancel"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="footer-btn submit"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Save Transport Route
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- VEHICLE & DRIVER AUTO-FILL SCRIPT --}}

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* VEHICLE ELEMENTS */

    const vehicleSelect =
        document.getElementById('vehicle_id');

    const selectedVehicleCard =
        document.getElementById('selectedVehicleCard');

    const selectedVehicleAvatar =
        document.getElementById('selectedVehicleAvatar');

    const selectedVehicleTitle =
        document.getElementById('selectedVehicleTitle');

    const vehicleNumber =
        document.getElementById('vehicleNumber');

    const vehicleType =
        document.getElementById('vehicleType');

    const vehicleCapacity =
        document.getElementById('vehicleCapacity');

    const vehicleStatus =
        document.getElementById('vehicleStatus');

    const vehicleDriver =
        document.getElementById('vehicleDriver');


    /* DRIVER ELEMENTS */

    const driverSelect =
        document.getElementById('driver_id');

    const selectedDriverCard =
        document.getElementById('selectedDriverCard');

    const selectedDriverAvatar =
        document.getElementById('selectedDriverAvatar');

    const selectedDriverTitle =
        document.getElementById('selectedDriverTitle');

    const driverName =
        document.getElementById('driverName');

    const driverStaffId =
        document.getElementById('driverStaffId');

    const driverPhone =
        document.getElementById('driverPhone');

    const driverDesignation =
        document.getElementById('driverDesignation');

    const driverDepartment =
        document.getElementById('driverDepartment');

    const driverStatus =
        document.getElementById('driverStatus');


    /* DISPLAY VALUE HELPER */

    function displayValue(value) {

        if (
            value === null ||
            value === undefined ||
            String(value).trim() === ''
        ) {
            return 'Not available';
        }

        return String(value);
    }


    /* UPDATE VEHICLE DETAILS */

    function updateVehicleDetails() {

        const selectedOption =
            vehicleSelect.options[
                vehicleSelect.selectedIndex
            ];

        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            vehicleNumber.textContent = '—';
            vehicleType.textContent = '—';
            vehicleCapacity.textContent = '—';
            vehicleStatus.textContent = '—';
            vehicleDriver.textContent = '—';

            selectedVehicleAvatar.textContent = 'V';

            selectedVehicleTitle.textContent =
                'Vehicle Details';

            selectedVehicleCard.classList.remove('show');

            return;
        }

        const number =
            selectedOption.dataset.vehicleNumber || '';

        const type =
            selectedOption.dataset.vehicleType || '';

        const capacity =
            selectedOption.dataset.capacity || '';

        const status =
            selectedOption.dataset.status || '';

        const linkedDriver =
            selectedOption.dataset.driverName || '';

        vehicleNumber.textContent =
            displayValue(number);

        vehicleType.textContent =
            displayValue(type);

        if (capacity) {

            vehicleCapacity.textContent =
                capacity + ' seats';

        } else {

            vehicleCapacity.textContent =
                'Not available';
        }

        if (status.toLowerCase() === 'active') {

            vehicleStatus.innerHTML =
                '<span class="vehicle-active-badge">' +
                '<i class="bi bi-check-circle-fill"></i>' +
                ' Active' +
                '</span>';

        } else {

            vehicleStatus.textContent =
                displayValue(status);
        }

        vehicleDriver.textContent =
            displayValue(linkedDriver);

        selectedVehicleAvatar.textContent =
            number
                ? number.charAt(0).toUpperCase()
                : 'V';

        selectedVehicleTitle.textContent =
            number || 'Vehicle Details';

        selectedVehicleCard.classList.add('show');
    }


    /* UPDATE DRIVER DETAILS */

    function updateDriverDetails() {

        const selectedOption =
            driverSelect.options[
                driverSelect.selectedIndex
            ];

        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            driverName.textContent = '—';
            driverStaffId.textContent = '—';
            driverPhone.textContent = '—';
            driverDesignation.textContent = '—';
            driverDepartment.textContent = '—';
            driverStatus.textContent = '—';

            selectedDriverAvatar.textContent = 'D';

            selectedDriverTitle.textContent =
                'Driver Details';

            selectedDriverCard.classList.remove('show');

            return;
        }

        const name =
            selectedOption.dataset.name || '';

        const staffId =
            selectedOption.dataset.staffId || '';

        const phone =
            selectedOption.dataset.phone || '';

        const designation =
            selectedOption.dataset.designation || '';

        const department =
            selectedOption.dataset.department || '';

        const status =
            selectedOption.dataset.status || '';

        driverName.textContent =
            displayValue(name);

        driverStaffId.textContent =
            displayValue(staffId);

        driverPhone.textContent =
            displayValue(phone);

        driverDesignation.textContent =
            displayValue(designation);

        driverDepartment.textContent =
            displayValue(department);

        driverStatus.textContent =
            displayValue(status);

        selectedDriverAvatar.textContent =
            name
                ? name.charAt(0).toUpperCase()
                : 'D';

        selectedDriverTitle.textContent =
            name || 'Driver Details';

        selectedDriverCard.classList.add('show');
    }


    /* EVENTS */

    vehicleSelect.addEventListener(
        'change',
        updateVehicleDetails
    );

    driverSelect.addEventListener(
        'change',
        updateDriverDetails
    );


    /* INITIAL LOAD */

    updateVehicleDetails();

    updateDriverDetails();

});
</script>

@endsection