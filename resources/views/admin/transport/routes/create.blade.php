@extends('layouts.app')

@section('title', 'Add Transport Route | Admin')

@section('content')

<style>
    .transport-route-create-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        color: #172033;
        font-family: "Inter", sans-serif;
        padding-bottom: 40px;
    }

    .transport-route-create-container {
        width: 100%;
        padding: 28px 30px 40px;
    }

    /* PAGE HEADER */

    .route-create-header {
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #237de0 55%,
            #16a7c9 100%
        );
        border-radius: 18px;
        padding: 28px 32px;
        margin-bottom: 22px;
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .route-create-header::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -55px;
        top: -75px;
    }

    .route-create-header::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
        right: 130px;
        bottom: -80px;
    }

    .route-create-heading {
        display: flex;
        align-items: center;
        gap: 15px;
        position: relative;
        z-index: 2;
    }

    .route-create-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.20);
        color: #fff;
        font-size: 22px;
        flex-shrink: 0;
        backdrop-filter: blur(5px);
    }

    .route-create-heading h1 {
        margin: 0;
        font-size: 23px;
        font-weight: 750;
        color: #fff;
        letter-spacing: -.3px;
    }

    .route-create-heading p {
        margin: 4px 0 0;
        color: rgba(255,255,255,.80);
        font-size: 12px;
        font-weight: 450;
    }

    /* HEADER BUTTON */

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 11.5px;
        font-weight: 650;
        transition: all .25s ease;
        white-space: nowrap;
        position: relative;
        z-index: 3;
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
    }

    /* BREADCRUMB */

    .route-create-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 22px;
        font-size: 11px;
        color: #94a3b8;
    }

    .route-create-breadcrumb a {
        color: #1769d1;
        text-decoration: none;
        font-weight: 600;
    }

    .route-create-breadcrumb a:hover {
        text-decoration: underline;
    }

    .route-create-breadcrumb i {
        font-size: 9px;
    }

    /* VALIDATION */

    .route-error-alert {
        margin-bottom: 22px;
        padding: 15px 17px;
        border: 1px solid #fecaca;
        border-radius: 12px;
        background: #fff7f7;
        color: #991b1b;
    }

    .route-error-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 12px;
        font-weight: 750;
    }

    .route-error-list {
        margin: 0;
        padding-left: 25px;
        font-size: 11px;
        line-height: 1.7;
    }

    /* FORM CARD */

    .route-form-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15,23,42,.06);
        overflow: hidden;
    }

    .route-form-card-header {
        padding: 21px 25px;
        border-bottom: 1px solid #e8eef5;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .route-form-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #dbeafe;
        color: #1769d1;
        font-size: 17px;
        flex-shrink: 0;
    }

    .route-form-card-header h2 {
        margin: 0;
        font-size: 15px;
        font-weight: 750;
        color: #172033;
    }

    .route-form-card-header p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 11px;
    }

    /* FORM BODY */

    .route-form-body {
        padding: 28px 25px 24px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    /* SECTION HEADING */

    .form-section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .section-icon.blue {
        color: #1769d1;
        background: #dbeafe;
    }

    .section-icon.orange {
        color: #ea580c;
        background: #ffedd5;
    }

    .section-icon.purple {
        color: #7c3aed;
        background: #ede9fe;
    }

    .section-icon.green {
        color: #16a34a;
        background: #dcfce7;
    }

    .form-section-heading h3 {
        margin: 0;
        color: #172033;
        font-size: 13px;
        font-weight: 750;
    }

    .form-section-heading p {
        margin: 2px 0 0;
        color: #94a3b8;
        font-size: 10px;
    }

    /* FORM GRID */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    /* FORM FIELDS */

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
    }

    .required {
        color: #dc2626;
        margin-left: 2px;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 43px;
        padding: 0 13px;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #172033;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: all .2s ease;
        box-sizing: border-box;
    }

    .form-control::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }

    .form-control:hover,
    .form-select:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    .form-control:focus,
    .form-select:focus {
        background: #fff;
        border-color: #8bb8ef;
        box-shadow: 0 0 0 3px rgba(23,105,209,.08);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #475569;
        cursor: default;
    }

    textarea.form-control {
        height: 105px;
        padding: 12px 13px;
        resize: vertical;
        line-height: 1.55;
    }

    .field-help {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.5;
    }

    .field-error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 10.5px;
        font-weight: 550;
    }

    .has-error .form-control,
    .has-error .form-select {
        border-color: #fca5a5;
        background: #fffafa;
    }

    /* STOPS */

    .stops-info-box {
        margin-top: 8px;
        padding: 11px 13px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .stops-info-box i {
        color: #1769d1;
        margin-right: 4px;
    }

    /* VEHICLE INFORMATION */

    .selected-vehicle-card {
        display: none;
        margin-top: 18px;
        border: 1px solid #cfe2ff;
        background: #f8fbff;
        border-radius: 13px;
        padding: 18px;
    }

    .selected-vehicle-card.show {
        display: block;
    }

    .selected-vehicle-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 15px;
        padding-bottom: 13px;
        border-bottom: 1px solid #e1ecfa;
    }

    .selected-vehicle-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .selected-vehicle-header h6 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #172033;
    }

    .selected-vehicle-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 10px;
    }

    .vehicle-detail-box {
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 10px 11px;
        min-height: 61px;
    }

    .vehicle-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #94a3b8;
        font-size: 9.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .vehicle-detail-label i {
        color: #1769d1;
        font-size: 10px;
    }

    .vehicle-detail-value {
        color: #172033;
        font-size: 11px;
        font-weight: 650;
        word-break: break-word;
    }

    .vehicle-active-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 9px;
        font-weight: 700;
    }

    .vehicle-active-badge i {
        font-size: 8px;
    }

    .vehicle-info-box {
        margin-top: 14px;
        padding: 11px 13px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .vehicle-info-box i {
        color: #1769d1;
        margin-right: 4px;
    }

    /* DRIVER INFORMATION */

    .selected-driver-card {
        display: none;
        margin-top: 18px;
        border: 1px solid #e2d8fb;
        background: #fbfaff;
        border-radius: 13px;
        padding: 18px;
    }

    .selected-driver-card.show {
        display: block;
    }

    .selected-driver-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 15px;
        padding-bottom: 13px;
        border-bottom: 1px solid #eee9fa;
    }

    .selected-driver-avatar {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .selected-driver-header h6 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #172033;
    }

    .selected-driver-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 10px;
    }

    .driver-detail-box {
        background: #fff;
        border: 1px solid #e6edf5;
        border-radius: 10px;
        padding: 10px 11px;
        min-height: 61px;
    }

    .driver-detail-label {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #94a3b8;
        font-size: 9.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .driver-detail-label i {
        color: #7c3aed;
        font-size: 10px;
    }

    .driver-detail-value {
        color: #172033;
        font-size: 11px;
        font-weight: 650;
        word-break: break-word;
    }

    .driver-info-box {
        margin-top: 14px;
        padding: 11px 13px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        color: #64748b;
        font-size: 10px;
        line-height: 1.55;
    }

    .driver-info-box i {
        color: #7c3aed;
        margin-right: 4px;
    }

    /* STATUS */

    .status-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .status-option {
        position: relative;
    }

    .status-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label {
        min-height: 43px;
        padding: 0 13px;
        display: flex;
        align-items: center;
        gap: 9px;
        border: 1px solid #dfe6ef;
        border-radius: 10px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }

    .status-option label:hover {
        background: #fff;
        border-color: #cbd5e1;
    }

    .status-option input:checked + label {
        border-color: #8bb8ef;
        background: #f2f7ff;
        color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23,105,209,.06);
    }

    .status-radio-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    .status-option input:checked + label .status-radio-dot {
        background: #1769d1;
        box-shadow: 0 0 0 3px rgba(23,105,209,.12);
    }

    /* DIVIDER */

    .form-divider {
        height: 1px;
        background: #edf1f6;
        margin: 26px 0;
    }

    /* FOOTER */

    .route-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 25px;
        background: #fbfcfe;
        border-top: 1px solid #edf1f6;
    }

    .footer-btn {
        min-height: 40px;
        padding: 0 17px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 11.5px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .footer-btn.cancel {
        color: #64748b;
        background: #fff;
        border: 1px solid #dfe6ef;
    }

    .footer-btn.cancel:hover {
        color: #1769d1;
        border-color: #bfd5ed;
        background: #f2f7ff;
    }

    .footer-btn.submit {
        color: #fff;
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
        border: 0;
        box-shadow: 0 5px 12px rgba(23,105,209,.20);
    }

    .footer-btn.submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(23,105,209,.25);
    }

    /* RESPONSIVE */

    @media (max-width: 900px) {

        .transport-route-create-container {
            padding: 22px 20px 35px;
        }

        .route-create-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .route-create-header > .header-btn {
            width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }
    }

    @media (max-width: 600px) {

        .transport-route-create-container {
            padding: 18px 14px 30px;
        }

        .route-create-header {
            padding: 23px 20px;
        }

        .route-create-heading h1 {
            font-size: 20px;
        }

        .route-create-heading p {
            font-size: 11px;
        }

        .route-create-icon {
            width: 45px;
            height: 45px;
            font-size: 19px;
        }

        .route-form-card-header,
        .route-form-body {
            padding-left: 17px;
            padding-right: 17px;
        }

        .route-form-footer {
            padding: 15px 17px;
            flex-direction: column-reverse;
        }

        .footer-btn {
            width: 100%;
        }

        .status-options {
            grid-template-columns: 1fr;
        }

        .selected-driver-card,
        .selected-vehicle-card {
            padding: 14px;
        }
    }
</style>

<div class="transport-route-create-page">

```
<div class="transport-route-create-container">

    {{-- PAGE HEADER --}}

    <div class="route-create-header">

        <div class="route-create-heading">

            <div class="route-create-icon">
                <i class="bi bi-signpost-2-fill"></i>
            </div>

            <div>

                <h1>
                    Add Transport Route
                </h1>

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

        <span>
            Add Route
        </span>

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

                    <li>
                        {{ $error }}
                    </li>

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

                <h2>
                    Add Transport Route
                </h2>

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

                            <h3>
                                Route Details
                            </h3>

                            <p>
                                Basic identification and journey information.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- ROUTE NUMBER --}}

                        <div class="form-group {{ $errors->has('route_number') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="route_number"
                            >
                                Route Number
                                <span class="required">*</span>
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
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- ROUTE NAME --}}

                        <div class="form-group {{ $errors->has('route_name') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="route_name"
                            >
                                Route Name
                                <span class="required">*</span>
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
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- STARTING POINT --}}

                        <div class="form-group {{ $errors->has('starting_point') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="starting_point"
                            >
                                Starting Point
                                <span class="required">*</span>
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
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- DESTINATION --}}

                        <div class="form-group {{ $errors->has('destination') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="destination"
                            >
                                Destination
                                <span class="required">*</span>
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
                                <div class="field-error">
                                    {{ $message }}
                                </div>
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

                            <h3>
                                Route Stops
                            </h3>

                            <p>
                                Add the pickup and drop-off stops followed by the route.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <div class="form-group full-width {{ $errors->has('stops') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="stops"
                            >
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

                                Example:
                                Main Gate, Market Chowk, Bus Stand,
                                College Road, Station Road

                            </div>

                            @error('stops')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

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

                            <h3>
                                Vehicle & Driver
                            </h3>

                            <p>
                                Assign the active vehicle and driver responsible for this route.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        {{-- ACTIVE VEHICLE --}}

                        <div class="form-group {{ $errors->has('vehicle_id') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="vehicle_id"
                            >
                                Assigned Vehicle
                            </label>

                            <select
                                name="vehicle_id"
                                id="vehicle_id"
                                class="form-select"
                            >

                                <option value="">
                                    Select Active Vehicle
                                </option>

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

                                Only vehicles with
                                <strong>Active</strong>
                                status are available for route assignment.

                            </div>


                            @error('vehicle_id')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- DRIVER --}}

                        <div class="form-group {{ $errors->has('driver_id') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="driver_id"
                            >
                                Assign Driver
                            </label>

                            <select
                                name="driver_id"
                                id="driver_id"
                                class="form-select"
                            >

                                <option value="">
                                    Select Driver
                                </option>

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

                                <div class="field-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    {{-- SELECTED VEHICLE INFORMATION --}}

                    <div
                        class="selected-vehicle-card"
                        id="selectedVehicleCard"
                    >

                        <div class="selected-vehicle-header">

                            <div
                                class="selected-vehicle-avatar"
                                id="selectedVehicleAvatar"
                            >
                                V
                            </div>

                            <div>

                                <h6 id="selectedVehicleTitle">
                                    Vehicle Details
                                </h6>

                                <p>
                                    Active vehicle information from Transport Vehicles
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            {{-- VEHICLE NUMBER --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="vehicle-detail-box">

                                    <div class="vehicle-detail-label">

                                        <i class="bi bi-upc-scan"></i>

                                        Vehicle Number

                                    </div>

                                    <div
                                        class="vehicle-detail-value"
                                        id="vehicleNumber"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- VEHICLE TYPE --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="vehicle-detail-box">

                                    <div class="vehicle-detail-label">

                                        <i class="bi bi-bus-front"></i>

                                        Vehicle Type

                                    </div>

                                    <div
                                        class="vehicle-detail-value"
                                        id="vehicleType"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- CAPACITY --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="vehicle-detail-box">

                                    <div class="vehicle-detail-label">

                                        <i class="bi bi-people-fill"></i>

                                        Capacity

                                    </div>

                                    <div
                                        class="vehicle-detail-value"
                                        id="vehicleCapacity"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- STATUS --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="vehicle-detail-box">

                                    <div class="vehicle-detail-label">

                                        <i class="bi bi-check-circle"></i>

                                        Vehicle Status

                                    </div>

                                    <div
                                        class="vehicle-detail-value"
                                        id="vehicleStatus"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- DRIVER LINKED TO VEHICLE --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="vehicle-detail-box">

                                    <div class="vehicle-detail-label">

                                        <i class="bi bi-person-badge"></i>

                                        Vehicle Driver

                                    </div>

                                    <div
                                        class="vehicle-detail-value"
                                        id="vehicleDriver"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="vehicle-info-box">

                            <i class="bi bi-info-circle-fill"></i>

                            Vehicle information is automatically loaded
                            from the selected active Transport Vehicle record.
                            Inactive and maintenance vehicles cannot be assigned.

                        </div>

                    </div>


                    {{-- SELECTED DRIVER INFORMATION --}}

                    <div
                        class="selected-driver-card"
                        id="selectedDriverCard"
                    >

                        <div class="selected-driver-header">

                            <div
                                class="selected-driver-avatar"
                                id="selectedDriverAvatar"
                            >
                                D
                            </div>

                            <div>

                                <h6 id="selectedDriverTitle">
                                    Driver Details
                                </h6>

                                <p>
                                    Existing Other Staff information
                                </p>

                            </div>

                        </div>


                        <div class="row g-3">

                            {{-- DRIVER NAME --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-person-badge"></i>

                                        Driver Name

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverName"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- STAFF ID --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-card-text"></i>

                                        Staff ID

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverStaffId"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- PHONE --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-telephone"></i>

                                        Phone

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverPhone"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- DESIGNATION --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-person-workspace"></i>

                                        Designation

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverDesignation"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- DEPARTMENT --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-building"></i>

                                        Department

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverDepartment"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>


                            {{-- STATUS --}}

                            <div class="col-lg-4 col-md-6">

                                <div class="driver-detail-box">

                                    <div class="driver-detail-label">

                                        <i class="bi bi-check-circle"></i>

                                        Staff Status

                                    </div>

                                    <div
                                        class="driver-detail-value"
                                        id="driverStatus"
                                    >
                                        —
                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="driver-info-box">

                            <i class="bi bi-info-circle-fill"></i>

                            Driver information is automatically loaded
                            from the selected Other Staff record.
                            It is not manually stored with the route.

                        </div>

                    </div>


                    <div class="form-divider"></div>


                    {{-- ROUTE STATUS --}}

                    <div class="form-grid">

                        <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">

                            <label class="form-label">
                                Route Status
                                <span class="required">*</span>
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

                                <div class="field-error">
                                    {{ $message }}
                                </div>

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

                            <h3>
                                Additional Information
                            </h3>

                            <p>
                                Add optional notes related to this route.
                            </p>

                        </div>

                    </div>


                    <div class="form-grid">

                        <div class="form-group full-width {{ $errors->has('remarks') ? 'has-error' : '' }}">

                            <label
                                class="form-label"
                                for="remarks"
                            >
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                class="form-control"
                                placeholder="Enter any additional route information..."
                            >{{ old('remarks') }}</textarea>

                            @error('remarks')

                                <div class="field-error">
                                    {{ $message }}
                                </div>

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
```

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
