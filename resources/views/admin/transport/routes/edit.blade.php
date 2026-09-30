@extends('layouts.app')

@section('title', 'Edit Transport Route | Admin')

@section('content')

<style>
    /* =========================================================
       EDIT TRANSPORT ROUTE
    ========================================================= */

    .transport-route-edit-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        color: #172033;
        font-family: 'Inter', sans-serif;
    }

    .transport-route-edit-container {
        width: 100%;
        padding: 28px 30px 40px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .route-edit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .route-edit-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .route-edit-icon {
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

    .route-edit-heading h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 750;
        color: #172033;
        letter-spacing: -.3px;
    }

    .route-edit-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .route-edit-header-actions {
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
    }

    /* =========================================================
       BREADCRUMB
    ========================================================= */

    .route-edit-breadcrumb {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 22px;
        font-size: 12px;
        color: #94a3b8;
    }

    .route-edit-breadcrumb a {
        color: #1769d1;
        text-decoration: none;
        font-weight: 600;
    }

    .route-edit-breadcrumb i {
        font-size: 10px;
    }

    /* =========================================================
       VALIDATION
    ========================================================= */

    .validation-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 14px 16px;
        margin-bottom: 20px;
        border: 1px solid #fecaca;
        border-radius: 12px;
        background: #fff1f2;
        color: #b91c1c;
        font-size: 12px;
    }

    .validation-alert i {
        margin-top: 1px;
        font-size: 15px;
    }

    .validation-alert strong {
        display: block;
        margin-bottom: 5px;
        font-size: 12px;
    }

    .validation-alert ul {
        margin: 0;
        padding-left: 17px;
    }

    .validation-alert li {
        margin-bottom: 2px;
    }

    /* =========================================================
       MAIN FORM
    ========================================================= */

    .route-edit-form-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-section {
        padding: 24px 25px;
        border-bottom: 1px solid #edf1f6;
    }

    .form-section:last-of-type {
        border-bottom: 0;
    }

    .form-section-header {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
    }

    .form-section-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 16px;
    }

    .form-section-icon.blue {
        background: #dbeafe;
        color: #1769d1;
    }

    .form-section-icon.orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .form-section-icon.purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .form-section-icon.green {
        background: #dcfce7;
        color: #16a34a;
    }

    .form-section-title h2 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 750;
    }

    .form-section-title p {
        margin: 3px 0 0;
        color: #94a3b8;
        font-size: 10.5px;
    }

    /* =========================================================
       FORM GRID
    ========================================================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 20px;
    }

    .form-grid.single {
        grid-template-columns: 1fr;
    }

    .form-group {
        min-width: 0;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 11.5px;
        font-weight: 650;
    }

    .required {
        color: #ef4444;
        margin-left: 2px;
    }

    .form-control-custom {
        width: 100%;
        height: 43px;
        padding: 0 13px;
        border: 1px solid #dce5ef;
        border-radius: 10px;
        background: #fff;
        color: #172033;
        font-family: inherit;
        font-size: 12px;
        outline: none;
        transition: all .22s ease;
        box-sizing: border-box;
    }

    .form-control-custom::placeholder {
        color: #a5b1c2;
    }

    .form-control-custom:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .10);
    }

    textarea.form-control-custom {
        height: auto;
        min-height: 120px;
        padding: 12px 13px;
        resize: vertical;
        line-height: 1.5;
    }

    select.form-control-custom {
        cursor: pointer;
    }

    .field-help {
        margin-top: 6px;
        color: #94a3b8;
        font-size: 10px;
        line-height: 1.45;
    }

    .field-error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 10px;
        font-weight: 550;
    }

    .input-prefix-wrapper {
        position: relative;
    }

    .input-prefix-wrapper .form-control-custom {
        padding-left: 38px;
    }

    .input-prefix-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .status-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
        min-width: 125px;
        height: 43px;
        padding: 0 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border: 1px solid #dce5ef;
        border-radius: 10px;
        background: #fff;
        color: #64748b;
        font-size: 11.5px;
        font-weight: 650;
        cursor: pointer;
        transition: all .2s ease;
    }

    .status-option label:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .status-option input:checked + label.active-option {
        color: #15803d;
        background: #f0fdf4;
        border-color: #86efac;
    }

    .status-option input:checked + label.inactive-option {
        color: #b91c1c;
        background: #fef2f2;
        border-color: #fca5a5;
    }

    .status-option-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .status-option-dot.active {
        background: #16a34a;
    }

    .status-option-dot.inactive {
        background: #ef4444;
    }

    /* =========================================================
       INFO BOX
    ========================================================= */

    .route-info-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px;
        margin-top: 13px;
        border-radius: 11px;
        background: #f2f7ff;
        border: 1px solid #dbeafe;
        color: #52657d;
        font-size: 10.5px;
        line-height: 1.55;
    }

    .route-info-box i {
        color: #1769d1;
        font-size: 14px;
        margin-top: 1px;
        flex-shrink: 0;
    }

    /* =========================================================
       FORM FOOTER
    ========================================================= */

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 25px;
        background: #fbfcfe;
        border-top: 1px solid #edf1f6;
    }

    .form-footer-note {
        color: #94a3b8;
        font-size: 10.5px;
    }

    .form-footer-actions {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .form-btn {
        min-height: 42px;
        padding: 0 17px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: inherit;
        font-size: 11.5px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: all .22s ease;
    }

    .form-btn.cancel {
        color: #64748b;
        background: #fff;
        border: 1px solid #dce5ef;
    }

    .form-btn.cancel:hover {
        color: #1769d1;
        background: #f2f7ff;
        border-color: #c2d7ed;
    }

    .form-btn.update {
        color: #fff;
        background: linear-gradient(135deg, #1769d1, #237de0);
        border: 1px solid #1769d1;
        box-shadow: 0 6px 15px rgba(23, 105, 209, .18);
    }

    .form-btn.update:hover {
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(23, 105, 209, .24);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 800px) {

        .transport-route-edit-container {
            padding: 22px 20px 35px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .route-edit-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .route-edit-header-actions {
            width: 100%;
        }

        .route-edit-header-actions .header-btn {
            flex: 1;
        }

        .form-footer {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-footer-actions {
            width: 100%;
        }

        .form-footer-actions .form-btn {
            flex: 1;
        }
    }

    @media (max-width: 550px) {

        .transport-route-edit-container {
            padding: 18px 14px 30px;
        }

        .route-edit-heading h1 {
            font-size: 21px;
        }

        .form-section {
            padding: 20px 17px;
        }

        .form-footer {
            padding: 16px 17px;
        }

        .status-options {
            flex-direction: column;
        }

        .status-option label {
            width: 100%;
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
                    Fields marked with <span style="color:#ef4444;">*</span> are required.
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