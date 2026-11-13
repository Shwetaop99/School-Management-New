@extends('layouts.app')

@section('title', 'Edit Transport Route | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — EDIT TRANSPORT ROUTE
    ========================================================= */

    .transport-route-edit-page {
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
        background: var(--diary-bg);
        color: var(--diary-ink);
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        padding-bottom: 24px;
    }

    .transport-route-edit-page *,
    .transport-route-edit-page *::before,
    .transport-route-edit-page *::after {
        box-sizing: border-box;
    }

    .transport-route-edit-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 24px 28px 36px;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .route-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 17px 20px;
        margin-bottom: 15px;
        background: var(--diary-paper);
        border: 1px solid var(--diary-border);
        border-left: 4px solid var(--diary-green);
        border-radius: 9px;
        box-shadow: 0 3px 12px rgba(38, 59, 53, 0.045);
    }

    .route-edit-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .route-edit-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 20px;
        border: 1px solid #d7e7d5;
    }

    .route-edit-heading h1 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 22px;
        line-height: 1.3;
        font-weight: 750;
        letter-spacing: -0.45px;
    }

    .route-edit-heading p {
        margin: 4px 0 0;
        color: var(--diary-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    .route-edit-header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .transport-route-edit-page .header-btn {
        min-height: 36px;
        padding: 0 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
        color: var(--diary-green);
        text-decoration: none;
        font-size: 11px;
        font-weight: 650;
        white-space: nowrap;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, transform .2s ease;
    }

    .transport-route-edit-page .header-btn:hover {
        background: var(--diary-green-light);
        border-color: #bfd4be;
        color: var(--diary-green-dark);
        transform: translateY(-1px);
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .route-edit-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin: 0 0 18px;
        padding: 0 2px;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.7;
        text-transform: uppercase;
        letter-spacing: .35px;
    }

    .route-edit-breadcrumb a {
        color: var(--diary-green);
        text-decoration: none;
        font-weight: 700;
        transition: color .2s ease;
    }

    .route-edit-breadcrumb a:hover {
        color: var(--diary-green-dark);
        text-decoration: underline;
    }

    .route-edit-breadcrumb i {
        color: #b5ad9c;
        font-size: 9px;
    }

    /* =========================================================
       VALIDATION ALERT
    ========================================================= */

    .transport-route-edit-page .validation-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        margin-bottom: 16px;
        border: 1px solid #e9c8c2;
        border-radius: 8px;
        background: #fbefeb;
        color: var(--diary-red);
        font-size: 11px;
        line-height: 1.6;
    }

    .transport-route-edit-page .validation-alert > i {
        margin-top: 1px;
        font-size: 15px;
        flex-shrink: 0;
    }

    .transport-route-edit-page .validation-alert strong {
        display: block;
        margin-bottom: 5px;
        color: #9f4039;
        font-size: 11px;
        font-weight: 750;
    }

    .transport-route-edit-page .validation-alert ul {
        margin: 0;
        padding-left: 17px;
    }

    .transport-route-edit-page .validation-alert li {
        margin-bottom: 2px;
    }

    /* =========================================================
       MAIN FORM CARD
    ========================================================= */

    .transport-route-edit-page .route-edit-form-card {
        overflow: hidden;
        background: var(--diary-paper);
        border: 1px solid var(--diary-border);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(38, 59, 53, 0.045);
    }

    .transport-route-edit-page .form-section {
        padding: 22px 24px;
        border-bottom: 1px solid var(--diary-border);
    }

    .transport-route-edit-page .form-section:last-of-type {
        border-bottom: 0;
    }

    /* =========================================================
       NUMBERED-STYLE SECTION HEADERS
    ========================================================= */

    .transport-route-edit-page .form-section-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
    }

    .transport-route-edit-page .form-section-icon {
        width: 36px;
        height: 36px;
        border: 1px solid #d8e6d5;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 15px;
    }

    .transport-route-edit-page .form-section-icon.blue,
    .transport-route-edit-page .form-section-icon.orange,
    .transport-route-edit-page .form-section-icon.purple,
    .transport-route-edit-page .form-section-icon.green {
        background: var(--diary-green-light);
        color: var(--diary-green);
        border-color: #d8e6d5;
    }

    .transport-route-edit-page .form-section-title h2 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 13px;
        line-height: 1.4;
        font-weight: 750;
    }

    .transport-route-edit-page .form-section-title p {
        margin: 3px 0 0;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    /* =========================================================
       FORM GRID
    ========================================================= */

    .transport-route-edit-page .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px 20px;
    }

    .transport-route-edit-page .form-grid.single {
        grid-template-columns: 1fr;
    }

    .transport-route-edit-page .form-group {
        min-width: 0;
    }

    .transport-route-edit-page .form-group.full {
        grid-column: 1 / -1;
    }

    .transport-route-edit-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: var(--diary-ink);
        font-size: 11px;
        line-height: 1.45;
        font-weight: 700;
    }

    .transport-route-edit-page .required {
        margin-left: 2px;
        color: var(--diary-red);
        font-weight: 750;
    }

    /* =========================================================
       INPUTS
    ========================================================= */

    .transport-route-edit-page .form-control-custom {
        display: block;
        width: 100%;
        height: 40px;
        padding: 0 12px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: #fffefa;
        color: var(--diary-ink);
        font-family: inherit;
        font-size: 11.5px;
        line-height: 1.5;
        outline: none;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease,
                    background .2s ease;
    }

    .transport-route-edit-page .form-control-custom::placeholder {
        color: #aaa493;
        opacity: 1;
    }

    .transport-route-edit-page .form-control-custom:hover {
        border-color: #cfc9b8;
    }

    .transport-route-edit-page .form-control-custom:focus {
        border-color: var(--diary-green);
        background: #fffefa;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .11);
    }

    .transport-route-edit-page textarea.form-control-custom {
        height: auto;
        min-height: 125px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.65;
    }

    .transport-route-edit-page select.form-control-custom {
        cursor: pointer;
    }

    .transport-route-edit-page .field-help {
        margin-top: 6px;
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    .transport-route-edit-page .field-error {
        margin-top: 5px;
        color: var(--diary-red);
        font-size: 10px;
        line-height: 1.5;
        font-weight: 600;
    }

    /* =========================================================
       INPUT PREFIX ICONS
    ========================================================= */

    .transport-route-edit-page .input-prefix-wrapper {
        position: relative;
    }

    .transport-route-edit-page .input-prefix-wrapper .form-control-custom {
        padding-left: 37px;
    }

    .transport-route-edit-page .input-prefix-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--diary-cyan);
        font-size: 13px;
        pointer-events: none;
        transition: color .2s ease;
    }

    .transport-route-edit-page .input-prefix-wrapper:focus-within .input-prefix-icon {
        color: var(--diary-green);
    }

    /* =========================================================
       ROUTE STATUS
    ========================================================= */

    .transport-route-edit-page .status-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .transport-route-edit-page .status-option {
        position: relative;
    }

    .transport-route-edit-page .status-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .transport-route-edit-page .status-option label {
        min-width: 112px;
        min-height: 40px;
        padding: 0 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
        color: var(--diary-muted);
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, box-shadow .2s ease;
    }

    .transport-route-edit-page .status-option label:hover {
        background: #faf8ef;
        border-color: #cec8b8;
    }

    .transport-route-edit-page .status-option input:focus-visible + label {
        outline: 2px solid var(--diary-green);
        outline-offset: 3px;
    }

    .transport-route-edit-page .status-option input:checked + label.active-option {
        color: var(--diary-green-dark);
        background: var(--diary-green-light);
        border-color: #b8d4b7;
        box-shadow: inset 0 0 0 1px rgba(52, 119, 90, .05);
    }

    .transport-route-edit-page .status-option input:checked + label.inactive-option {
        color: var(--diary-red);
        background: #fbefeb;
        border-color: #e9c8c2;
    }

    .transport-route-edit-page .status-option-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .transport-route-edit-page .status-option-dot.active {
        background: var(--diary-green);
    }

    .transport-route-edit-page .status-option-dot.inactive {
        background: var(--diary-red);
    }

    /* =========================================================
       ROUTE INFORMATION BOX
    ========================================================= */

    .transport-route-edit-page .route-info-box {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 11px 12px;
        margin-top: 11px;
        border: 1px solid #d8e6d5;
        border-radius: 7px;
        background: var(--diary-green-light);
        color: #486451;
        font-size: 10.5px;
        line-height: 1.6;
    }

    .transport-route-edit-page .route-info-box i {
        margin-top: 2px;
        color: var(--diary-green);
        font-size: 13px;
        flex-shrink: 0;
    }

    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .transport-route-edit-page .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 16px 24px;
        background: #faf8ef;
        border-top: 1px solid var(--diary-border);
    }

    .transport-route-edit-page .form-footer-note {
        color: var(--diary-muted);
        font-size: 10px;
        line-height: 1.6;
    }

    .transport-route-edit-page .form-footer-note i {
        margin-right: 4px;
        color: var(--diary-cyan);
    }

    .transport-route-edit-page .form-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
    }

    .transport-route-edit-page .form-btn {
        min-height: 38px;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border: 1px solid transparent;
        border-radius: 7px;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.4;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease,
                    color .2s ease, transform .2s ease,
                    box-shadow .2s ease;
    }

    .transport-route-edit-page .form-btn.cancel {
        color: var(--diary-ink);
        background: var(--diary-paper);
        border-color: var(--diary-border);
    }

    .transport-route-edit-page .form-btn.cancel:hover {
        color: var(--diary-green-dark);
        background: var(--diary-green-light);
        border-color: #bfd4be;
    }

    .transport-route-edit-page .form-btn.update {
        color: #fffefa;
        background: var(--diary-green);
        border-color: var(--diary-green);
        box-shadow: 0 3px 8px rgba(52, 119, 90, .13);
    }

    .transport-route-edit-page .form-btn.update:hover {
        color: #fffefa;
        background: var(--diary-green-dark);
        border-color: var(--diary-green-dark);
        box-shadow: 0 5px 12px rgba(36, 90, 67, .17);
        transform: translateY(-1px);
    }

    .transport-route-edit-page .form-btn:focus-visible,
    .transport-route-edit-page .header-btn:focus-visible {
        outline: 2px solid var(--diary-green);
        outline-offset: 3px;
    }

    /* =========================================================
       RESPONSIVE — TABLET
    ========================================================= */

    @media (max-width: 991.98px) {
        .transport-route-edit-container {
            padding: 21px 20px 30px;
        }

        .route-edit-header {
            align-items: flex-start;
        }

        .route-edit-header-actions {
            max-width: 48%;
        }

        .transport-route-edit-page .form-section {
            padding: 20px;
        }

        .transport-route-edit-page .form-grid {
            gap: 16px;
        }

        .transport-route-edit-page .form-footer {
            padding: 15px 20px;
        }
    }

    /* =========================================================
       RESPONSIVE — MOBILE
    ========================================================= */

    @media (max-width: 767.98px) {
        .transport-route-edit-container {
            padding: 17px 15px 25px;
        }

        .route-edit-header {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
            padding: 15px;
        }

        .route-edit-heading h1 {
            font-size: 20px;
        }

        .route-edit-header-actions {
            max-width: none;
            width: 100%;
            justify-content: flex-start;
        }

        .route-edit-header-actions .header-btn {
            flex: 1 1 auto;
        }

        .route-edit-breadcrumb {
            gap: 6px;
            margin-bottom: 14px;
            font-size: 9px;
        }

        .transport-route-edit-page .form-grid {
            grid-template-columns: 1fr;
        }

        .transport-route-edit-page .form-group.full {
            grid-column: auto;
        }

        .transport-route-edit-page .form-section {
            padding: 18px 16px;
        }

        .transport-route-edit-page .form-section-header {
            margin-bottom: 17px;
        }

        .transport-route-edit-page .form-footer {
            align-items: stretch;
            flex-direction: column;
            padding: 15px 16px;
        }

        .transport-route-edit-page .form-footer-actions {
            width: 100%;
        }

        .transport-route-edit-page .form-footer-actions .form-btn {
            flex: 1;
        }
    }

    /* =========================================================
       RESPONSIVE — SMALL MOBILE
    ========================================================= */

    @media (max-width: 480px) {
        .transport-route-edit-container {
            padding: 13px 10px 22px;
        }

        .route-edit-header {
            padding: 13px;
        }

        .route-edit-heading {
            gap: 10px;
        }

        .route-edit-icon {
            width: 39px;
            height: 39px;
            font-size: 17px;
        }

        .route-edit-heading h1 {
            font-size: 18px;
        }

        .route-edit-heading p {
            font-size: 10px;
        }

        .route-edit-header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .route-edit-header-actions .header-btn {
            width: 100%;
        }

        .transport-route-edit-page .form-section {
            padding: 16px 12px;
        }

        .transport-route-edit-page .form-section-icon {
            width: 33px;
            height: 33px;
            font-size: 14px;
        }

        .transport-route-edit-page .form-section-title h2 {
            font-size: 12px;
        }

        .transport-route-edit-page .form-section-title p {
            font-size: 9.5px;
        }

        .transport-route-edit-page .status-options {
            flex-direction: column;
            align-items: stretch;
        }

        .transport-route-edit-page .status-option label {
            width: 100%;
        }

        .transport-route-edit-page .form-footer-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .transport-route-edit-page .form-footer-actions .form-btn {
            width: 100%;
            min-height: 40px;
        }
    }

    /* =========================================================
       ACCESSIBILITY
    ========================================================= */

    @media (prefers-reduced-motion: reduce) {
        .transport-route-edit-page *,
        .transport-route-edit-page *::before,
        .transport-route-edit-page *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>

<div class="transport-route-edit-page">

    <div class="transport-route-edit-container">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="route-edit-header">

            <div class="route-edit-heading">

                <div class="route-edit-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h1>Edit Transport Route</h1>

                    <p>
                        Update the details of this transport route.
                    </p>
                </div>

            </div>

            <div class="route-edit-header-actions">

                <a
                    href="{{ route('admin.transport.routes.show', ['transportRoute' => $transportRoute->id]) }}"
                    class="header-btn secondary"
                >
                    <i class="bi bi-eye"></i>
                    View Route
                </a>

                <a
                    href="{{ route('admin.transport.routes.index') }}"
                    class="header-btn secondary"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back to Routes
                </a>

            </div>

        </div>

        {{-- =====================================================
             BREADCRUMB
        ====================================================== --}}
        <div class="route-edit-breadcrumb">

            <a href="{{ route('admin.transport.records.index') }}">
                Transport Management
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.transport.routes.index') }}">
                Routes
            </a>

            <i class="bi bi-chevron-right"></i>

            <a href="{{ route('admin.transport.routes.show', ['transportRoute' => $transportRoute->id]) }}">
                {{ $transportRoute->route_number }}
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>Edit</span>

        </div>

        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}
        @if($errors->any())

            <div class="validation-alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <div>

                    <strong>
                        Please correct the following errors:
                    </strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>

            </div>

        @endif

        {{-- =====================================================
             FORM
        ====================================================== --}}
        <form
            action="{{ route('admin.transport.routes.update', ['transportRoute' => $transportRoute->id]) }}"
            method="POST"
            class="route-edit-form-card"
        >

            @csrf

            @method('PUT')

            {{-- =================================================
                 ROUTE DETAILS
            ================================================== --}}
            <div class="form-section">

                <div class="form-section-header">

                    <div class="form-section-icon blue">
                        <i class="bi bi-signpost-2-fill"></i>
                    </div>

                    <div class="form-section-title">

                        <h2>Route Details</h2>

                        <p>
                            Basic identification and journey information.
                        </p>

                    </div>

                </div>

                <div class="form-grid">

                    {{-- Route Number --}}
                    <div class="form-group">

                        <label class="form-label">
                            Route Number
                            <span class="required">*</span>
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-hash input-prefix-icon"></i>

                            <input
                                type="text"
                                name="route_number"
                                class="form-control-custom"
                                value="{{ old('route_number', $transportRoute->route_number) }}"
                                placeholder="e.g. RT-001"
                                maxlength="100"
                                required
                            >

                        </div>

                        @error('route_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Route Name --}}
                    <div class="form-group">

                        <label class="form-label">
                            Route Name
                            <span class="required">*</span>
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-signpost input-prefix-icon"></i>

                            <input
                                type="text"
                                name="route_name"
                                class="form-control-custom"
                                value="{{ old('route_name', $transportRoute->route_name) }}"
                                placeholder="e.g. Chandgad - School Route"
                                maxlength="255"
                                required
                            >

                        </div>

                        @error('route_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Starting Point --}}
                    <div class="form-group">

                        <label class="form-label">
                            Starting Point
                            <span class="required">*</span>
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-geo-alt input-prefix-icon"></i>

                            <input
                                type="text"
                                name="starting_point"
                                class="form-control-custom"
                                value="{{ old('starting_point', $transportRoute->starting_point) }}"
                                placeholder="Enter starting point"
                                maxlength="255"
                                required
                            >

                        </div>

                        @error('starting_point')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Destination --}}
                    <div class="form-group">

                        <label class="form-label">
                            Destination
                            <span class="required">*</span>
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-flag input-prefix-icon"></i>

                            <input
                                type="text"
                                name="destination"
                                class="form-control-custom"
                                value="{{ old('destination', $transportRoute->destination) }}"
                                placeholder="Enter destination"
                                maxlength="255"
                                required
                            >

                        </div>

                        @error('destination')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- =================================================
                 ROUTE STOPS
            ================================================== --}}
            <div class="form-section">

                <div class="form-section-header">

                    <div class="form-section-icon orange">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div class="form-section-title">

                        <h2>Route Stops</h2>

                        <p>
                            Add all pickup and drop-off points along the route.
                        </p>

                    </div>

                </div>

                <div class="form-grid single">

                    <div class="form-group full">

                        <label class="form-label">
                            Stops
                        </label>

                        <textarea
                            name="stops"
                            class="form-control-custom"
                            placeholder="Enter one stop per line&#10;&#10;Example:&#10;Chandgad&#10;Nesari&#10;Ajara&#10;School"
                        >{{ old('stops', $transportRoute->stops) }}</textarea>

                        <div class="route-info-box">

                            <i class="bi bi-info-circle-fill"></i>

                            <div>
                                For better display, enter each stop on a separate line.
                                Comma-separated stops are also supported.
                            </div>

                        </div>

                        @error('stops')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- =================================================
                 VEHICLE & DRIVER
            ================================================== --}}
            <div class="form-section">

                <div class="form-section-header">

                    <div class="form-section-icon purple">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>

                    <div class="form-section-title">

                        <h2>Vehicle & Driver</h2>

                        <p>
                            Assign the vehicle and driver responsible for this route.
                        </p>

                    </div>

                </div>

                <div class="form-grid">

                    {{-- Vehicle --}}
                    <div class="form-group">

                        <label class="form-label">
                            Assigned Vehicle
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-bus-front input-prefix-icon"></i>

                            <input
                                type="text"
                                name="assigned_vehicle"
                                class="form-control-custom"
                                value="{{ old('assigned_vehicle', $transportRoute->assigned_vehicle) }}"
                                placeholder="e.g. MH-09-AB-1234"
                                maxlength="255"
                            >

                        </div>

                        @error('assigned_vehicle')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Driver --}}
                    <div class="form-group">

                        <label class="form-label">
                            Driver Name
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-person-badge input-prefix-icon"></i>

                            <input
                                type="text"
                                name="driver_name"
                                class="form-control-custom"
                                value="{{ old('driver_name', $transportRoute->driver_name) }}"
                                placeholder="Enter driver name"
                                maxlength="255"
                            >

                        </div>

                        @error('driver_name')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Driver Contact --}}
                    <div class="form-group">

                        <label class="form-label">
                            Driver Contact
                        </label>

                        <div class="input-prefix-wrapper">

                            <i class="bi bi-telephone input-prefix-icon"></i>

                            <input
                                type="text"
                                name="driver_contact"
                                class="form-control-custom"
                                value="{{ old('driver_contact', $transportRoute->driver_contact) }}"
                                placeholder="Enter driver contact number"
                                maxlength="30"
                            >

                        </div>

                        @error('driver_contact')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="form-group">

                        <label class="form-label">
                            Route Status
                            <span class="required">*</span>
                        </label>

                        @php
                            $selectedStatus = old(
                                'status',
                                $transportRoute->status
                            );
                        @endphp

                        <div class="status-options">

                            <div class="status-option">

                                <input
                                    type="radio"
                                    id="status-active"
                                    name="status"
                                    value="active"
                                    {{ $selectedStatus === 'active' ? 'checked' : '' }}
                                >

                                <label
                                    for="status-active"
                                    class="active-option"
                                >
                                    <span class="status-option-dot active"></span>
                                    Active
                                </label>

                            </div>

                            <div class="status-option">

                                <input
                                    type="radio"
                                    id="status-inactive"
                                    name="status"
                                    value="inactive"
                                    {{ $selectedStatus === 'inactive' ? 'checked' : '' }}
                                >

                                <label
                                    for="status-inactive"
                                    class="inactive-option"
                                >
                                    <span class="status-option-dot inactive"></span>
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

            {{-- =================================================
                 ADDITIONAL INFORMATION
            ================================================== --}}
            <div class="form-section">

                <div class="form-section-header">

                    <div class="form-section-icon green">
                        <i class="bi bi-card-text"></i>
                    </div>

                    <div class="form-section-title">

                        <h2>Additional Information</h2>

                        <p>
                            Add any additional notes or information about this route.
                        </p>

                    </div>

                </div>

                <div class="form-grid single">

                    <div class="form-group full">

                        <label class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            class="form-control-custom"
                            placeholder="Enter any additional remarks..."
                        >{{ old('remarks', $transportRoute->remarks) }}</textarea>

                        @error('remarks')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- =================================================
                 FOOTER
            ================================================== --}}
            <div class="form-footer">

                <div class="form-footer-note">
                    <i class="bi bi-info-circle"></i>
                    Fields marked with <span style="color:#bd5148;">*</span> are required.
                </div>

                <div class="form-footer-actions">

                    <a
                        href="{{ route('admin.transport.routes.index') }}"
                        class="form-btn cancel"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="form-btn update"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Update Transport Route
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection