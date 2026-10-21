
@extends('layouts.app')

@section('title', 'Edit Vehicle | Admin')
@section('page-title', 'Edit Transport Vehicle')

@section('content')

<style>
    .vehicle-edit-page {
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

        min-height: calc(100vh - 60px);
        padding: 24px 0 40px;
        background: var(--diary-bg);
        color: var(--diary-ink);
        font-size: 12px;
    }

    .vehicle-edit-page *,
    .vehicle-edit-page *::before,
    .vehicle-edit-page *::after {
        box-sizing: border-box;
    }

    .vehicle-edit-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* PAGE HEADER */

    .vehicle-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 20px;
    }

    .vehicle-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding-left: 13px;
        border-left: 3px solid var(--diary-green);
    }

    .vehicle-heading-icon {
        display: flex;
        flex: 0 0 46px;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        border: 1px solid #d4e3d4;
        border-radius: 10px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 20px;
    }

    .vehicle-breadcrumb {
        margin-bottom: 4px;
        color: var(--diary-wood);
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.1px;
        text-transform: uppercase;
    }

    .vehicle-heading h1 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 22px;
        font-weight: 800;
        line-height: 1.3;
    }

    .vehicle-heading p {
        margin: 4px 0 0;
        color: #718078;
        font-size: 11px;
        line-height: 1.5;
    }

    .back-btn {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 8px 13px;
        border: 1px solid var(--diary-border);
        border-radius: 8px;
        background: var(--diary-paper);
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s ease, border-color .2s ease;
    }

    .back-btn:hover {
        border-color: #b7d0b9;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
    }

    /* MAIN PAPER CARD */

    .vehicle-form-card {
        overflow: hidden;
        border: 1px solid var(--diary-border);
        border-radius: 11px;
        background: var(--diary-paper);
        box-shadow: 0 4px 14px rgba(54, 64, 45, .045);
    }

    .form-card-header {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 17px 22px;
        border-bottom: 1px solid var(--diary-border);
        background: #fbfaf3;
    }

    .form-card-header-icon {
        display: flex;
        flex: 0 0 37px;
        align-items: center;
        justify-content: center;
        width: 37px;
        height: 37px;
        border: 1px solid #d4e3d4;
        border-radius: 9px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 16px;
    }

    .form-card-header h2 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 14px;
        font-weight: 800;
    }

    .form-card-header p {
        margin: 4px 0 0;
        color: #718078;
        font-size: 10px;
    }

    .form-card-header p strong {
        color: var(--diary-green-dark);
    }

    .vehicle-form-body {
        padding: 23px 22px;
    }

    /* FORM SECTIONS */

    .form-section {
        margin-bottom: 25px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--diary-border);
    }

    .section-number {
        display: flex;
        flex: 0 0 28px;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border: 1px solid #d4e3d4;
        border-radius: 7px;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
        font-size: 10px;
        font-weight: 800;
    }

    .section-title h3 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 800;
    }

    .section-title p {
        margin: 3px 0 0;
        color: #829087;
        font-size: 10px;
    }

    /* FORM CONTROLS */

    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #40534a;
        font-size: 11px;
        font-weight: 750;
    }

    .required {
        color: var(--diary-red);
    }

    .vehicle-edit-page .form-control,
    .vehicle-edit-page .form-select {
        width: 100%;
        min-height: 39px;
        padding: 8px 11px;
        border: 1px solid #dcdccf;
        border-radius: 7px;
        background-color: #fffefa;
        color: var(--diary-ink);
        font-size: 11px;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .vehicle-edit-page .form-select {
        padding-right: 30px;
    }

    .vehicle-edit-page textarea.form-control {
        min-height: 95px;
        resize: vertical;
    }

    .vehicle-edit-page .form-control:focus,
    .vehicle-edit-page .form-select:focus {
        border-color: var(--diary-green);
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
        outline: none;
    }

    .vehicle-edit-page .form-control[readonly] {
        border-color: #e5e1d3;
        background: #f5f3e9;
        color: #66756c;
        cursor: not-allowed;
    }

    .vehicle-edit-page .form-control.is-invalid,
    .vehicle-edit-page .form-select.is-invalid {
        border-color: var(--diary-red);
    }

    .vehicle-edit-page .form-control.is-invalid:focus,
    .vehicle-edit-page .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(189, 81, 72, .09);
    }

    .form-text {
        margin-top: 5px;
        color: #849087;
        font-size: 10px;
        line-height: 1.5;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .form-control,
    .input-icon-wrapper .form-select {
        padding-left: 34px;
    }

    .input-icon {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 12px;
        color: var(--diary-cyan);
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .field-error {
        margin-top: 5px;
        color: var(--diary-red);
        font-size: 10px;
        font-weight: 600;
    }

    /* DRIVER INFORMATION */

    .driver-info-box {
        padding: 16px;
        border: 1px solid #dce6d8;
        border-radius: 9px;
        background: #f8f8ef;
    }

    .driver-info-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
    }

    .driver-info-heading-icon {
        display: flex;
        flex: 0 0 32px;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid #d4e3d4;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 14px;
    }

    .driver-info-heading strong {
        display: block;
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 800;
    }

    .driver-info-heading span {
        display: block;
        margin-top: 3px;
        color: #77857b;
        font-size: 10px;
        line-height: 1.5;
    }

    /* STATUS OPTIONS */

    .status-options {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .status-option {
        position: relative;
    }

    .status-option input {
        position: absolute;
        width: 1px;
        height: 1px;
        opacity: 0;
        pointer-events: none;
    }

    .status-option label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 8px 13px;
        border: 1px solid var(--diary-border);
        border-radius: 7px;
        background: var(--diary-paper);
        color: #59685f;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #a4aaa3;
    }

    .status-option input:checked + label {
        border-color: var(--diary-green);
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
    }

    .status-option input:checked + label .status-dot {
        background: var(--diary-green);
    }

    .status-option.maintenance input:checked + label {
        border-color: var(--diary-wood);
        background: #f7eee2;
        color: #855b30;
    }

    .status-option.maintenance input:checked + label .status-dot {
        background: var(--diary-wood);
    }

    .status-option.inactive input:checked + label {
        border-color: var(--diary-red);
        background: #f9eae7;
        color: #9e3e37;
    }

    .status-option.inactive input:checked + label .status-dot {
        background: var(--diary-red);
    }

    .status-option input:focus-visible + label {
        outline: 2px solid var(--diary-green);
        outline-offset: 3px;
    }

    /* VALIDATION */

    .validation-alert {
        margin-bottom: 22px;
        padding: 13px 15px;
        border: 1px solid #e9c4bd;
        border-radius: 8px;
        background: #fbefeb;
        color: #923f37;
        font-size: 11px;
    }

    .validation-alert strong {
        font-weight: 800;
    }

    .validation-alert ul {
        margin: 7px 0 0 17px;
        padding: 0;
    }

    .validation-alert li {
        margin-bottom: 3px;
    }

    /* FORM FOOTER */

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 22px;
        border-top: 1px solid var(--diary-border);
        background: #fbfaf3;
    }

    .footer-right {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .cancel-btn,
    .save-btn,
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 8px 14px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 750;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .cancel-btn {
        border: 1px solid var(--diary-border);
        background: var(--diary-paper);
        color: #59685f;
    }

    .cancel-btn:hover {
        border-color: #c8c9b8;
        background: #f5f3e9;
        color: var(--diary-ink);
    }

    .save-btn {
        border: 1px solid var(--diary-green);
        background: var(--diary-green);
        color: #fffefa;
    }

    .save-btn:hover {
        border-color: var(--diary-green-dark);
        background: var(--diary-green-dark);
        color: #fffefa;
    }

    .delete-btn {
        border: 1px solid #e8c7c0;
        background: #fffefa;
        color: var(--diary-red);
    }

    .delete-btn:hover {
        border-color: var(--diary-red);
        background: #f9eae7;
        color: #9e3e37;
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .vehicle-edit-container {
            padding: 0 16px;
        }

        .vehicle-form-body {
            padding: 20px 18px;
        }

        .form-card-header,
        .form-footer {
            padding-right: 18px;
            padding-left: 18px;
        }
    }

    @media (max-width: 767.98px) {
        .vehicle-edit-page {
            padding-top: 18px;
        }

        .vehicle-edit-container {
            padding: 0 13px;
        }

        .vehicle-page-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 13px;
        }

        .vehicle-heading {
            gap: 10px;
            padding-left: 10px;
        }

        .vehicle-heading-icon {
            flex-basis: 40px;
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .vehicle-heading h1 {
            font-size: 19px;
        }

        .back-btn {
            margin-left: 13px;
        }

        .vehicle-form-body {
            padding: 18px 15px;
        }

        .form-card-header {
            padding: 15px;
        }

        .form-section {
            margin-bottom: 22px;
        }

        .driver-info-box {
            padding: 12px;
        }

        .form-footer {
            align-items: stretch;
            flex-direction: column;
            padding: 14px 15px;
        }

        .delete-btn {
            width: 100%;
        }

        .footer-right {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
            padding-right: 8px;
            padding-left: 8px;
        }
    }

    @media (max-width: 480px) {
        .vehicle-heading h1 {
            font-size: 17px;
        }

        .vehicle-heading p {
            font-size: 10px;
        }

        .vehicle-form-body {
            padding: 16px 12px;
        }

        .form-card-header {
            padding: 13px 12px;
        }

        .form-card-header h2 {
            font-size: 12px;
        }

        .section-title h3 {
            font-size: 11px;
        }

        .status-options {
            gap: 7px;
        }

        .status-option label {
            padding: 8px 10px;
        }

        .footer-right {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .vehicle-edit-page *,
        .vehicle-edit-page *::before,
        .vehicle-edit-page *::after {
            scroll-behavior: auto !important;
            transition: none !important;
        }
    }
</style>

<div class="vehicle-edit-page">
    <div class="vehicle-edit-container">

        {{-- PAGE HEADER --}}
        <div class="vehicle-page-header">

            <div class="vehicle-heading">
                <div class="vehicle-heading-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div>
                    <div class="vehicle-breadcrumb">
                        Transport Management / Vehicles / Edit
                    </div>

                    <h1>Edit Transport Vehicle</h1>

                    <p>Update vehicle details and driver assignment.</p>
                </div>
            </div>

            <a
                href="{{ route('admin.transport.vehicles.index') }}"
                class="back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Vehicles
            </a>

        </div>

        {{-- MAIN CARD --}}
        <div class="vehicle-form-card">

            {{-- CARD HEADER --}}
            <div class="form-card-header">
                <div class="form-card-header-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2>Update Vehicle Information</h2>
                    <p>
                        Vehicle:
                        <strong>{{ $transportVehicle->vehicle_number }}</strong>
                    </p>
                </div>
            </div>

            {{-- UPDATE FORM --}}
            <form
                id="updateVehicleForm"
                action="{{ route('admin.transport.vehicles.update', $transportVehicle) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="vehicle-form-body">

                    {{-- VALIDATION ERRORS --}}
                    @if ($errors->any())
                        <div class="validation-alert" role="alert">
                            <strong>
                                <i class="bi bi-exclamation-circle me-1"></i>
                                Please correct the following errors:
                            </strong>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 01: BASIC VEHICLE INFORMATION --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-number">01</div>
                            <div>
                                <h3>Basic Vehicle Information</h3>
                                <p>Vehicle identification and seating details</p>
                            </div>
                        </div>

                        <div class="row g-3">

                            {{-- VEHICLE NUMBER --}}
                            <div class="col-md-6">
                                <label for="vehicle_number" class="form-label">
                                    Vehicle Number <span class="required">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="bi bi-hash input-icon"></i>

                                    <input
                                        type="text"
                                        name="vehicle_number"
                                        id="vehicle_number"
                                        class="form-control @error('vehicle_number') is-invalid @enderror"
                                        value="{{ old('vehicle_number', $transportVehicle->vehicle_number) }}"
                                        placeholder="e.g. MH-09-AB-1234"
                                        required
                                    >
                                </div>

                                @error('vehicle_number')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- VEHICLE TYPE --}}
                            <div class="col-md-6">
                                <label for="vehicle_type" class="form-label">
                                    Vehicle Type <span class="required">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="bi bi-truck input-icon"></i>

                                    <select
                                        name="vehicle_type"
                                        id="vehicle_type"
                                        class="form-select @error('vehicle_type') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">Select Vehicle Type</option>

                                        @foreach([
                                            'Bus',
                                            'Mini Bus',
                                            'Van',
                                            'School Van',
                                            'Other'
                                        ] as $type)
                                            <option
                                                value="{{ $type }}"
                                                {{ old('vehicle_type', $transportVehicle->vehicle_type) === $type ? 'selected' : '' }}
                                            >
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('vehicle_type')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- CAPACITY --}}
                            <div class="col-md-6">
                                <label for="capacity" class="form-label">
                                    Seating Capacity <span class="required">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="bi bi-people input-icon"></i>

                                    <input
                                        type="number"
                                        name="capacity"
                                        id="capacity"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $transportVehicle->capacity) }}"
                                        min="1"
                                        required
                                    >
                                </div>

                                @error('capacity')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- 02: DRIVER ASSIGNMENT --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-number">02</div>
                            <div>
                                <h3>Driver Assignment</h3>
                                <p>Choose a driver and review the assigned details</p>
                            </div>
                        </div>

                        <div class="driver-info-box">

                            <div class="driver-info-heading">
                                <div class="driver-info-heading-icon">
                                    <i class="bi bi-person-vcard"></i>
                                </div>

                                <div>
                                    <strong>Assign Driver</strong>
                                    <span>
                                        Select an active driver from Other Staff.
                                        Details are automatically loaded.
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3">

                                {{-- DRIVER SELECT --}}
                                <div class="col-12">
                                    <label for="driver_id" class="form-label">
                                        Select Driver
                                    </label>

                                    <div class="input-icon-wrapper">
                                        <i class="bi bi-person-check input-icon"></i>

                                        <select
                                            name="driver_id"
                                            id="driver_id"
                                            class="form-select @error('driver_id') is-invalid @enderror"
                                        >
                                            <option value="">No Driver Assigned</option>

                                            @foreach($drivers as $driver)
                                                <option
                                                    value="{{ $driver->id }}"
                                                    data-name="{{ $driver->name }}"
                                                    data-phone="{{ $driver->phone }}"
                                                    data-license="{{ $driver->license_number }}"
                                                    {{ old('driver_id', $transportVehicle->driver_id) == $driver->id ? 'selected' : '' }}
                                                >
                                                    {{ $driver->name }}

                                                    @if($driver->staff_id)
                                                        — {{ $driver->staff_id }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-text">
                                        Only active staff members with Driver designation are available.
                                    </div>

                                    @error('driver_id')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- DRIVER NAME --}}
                                <div class="col-md-4">
                                    <label for="driver_name" class="form-label">
                                        Driver Name
                                    </label>

                                    <div class="input-icon-wrapper">
                                        <i class="bi bi-person input-icon"></i>

                                        <input
                                            type="text"
                                            id="driver_name"
                                            class="form-control"
                                            value="{{ old('driver_name', $transportVehicle->driver_name) }}"
                                            placeholder="Auto-filled"
                                            readonly
                                        >
                                    </div>
                                </div>

                                {{-- DRIVER CONTACT --}}
                                <div class="col-md-4">
                                    <label for="driver_contact" class="form-label">
                                        Driver Contact
                                    </label>

                                    <div class="input-icon-wrapper">
                                        <i class="bi bi-telephone input-icon"></i>

                                        <input
                                            type="text"
                                            id="driver_contact"
                                            class="form-control"
                                            value="{{ old('driver_contact', $transportVehicle->driver_contact) }}"
                                            placeholder="Auto-filled"
                                            readonly
                                        >
                                    </div>
                                </div>

                                {{-- DRIVER LICENCE --}}
                                <div class="col-md-4">
                                    <label for="driver_license_number" class="form-label">
                                        Driving Licence Number
                                    </label>

                                    <div class="input-icon-wrapper">
                                        <i class="bi bi-credit-card-2-front input-icon"></i>

                                        <input
                                            type="text"
                                            id="driver_license_number"
                                            class="form-control"
                                            value="{{ old('driver_license_number', $transportVehicle->driver_license_number) }}"
                                            placeholder="Auto-filled"
                                            readonly
                                        >
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- 03: VEHICLE DOCUMENTS --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-number">03</div>
                            <div>
                                <h3>Vehicle Documents</h3>
                                <p>Keep track of vehicle insurance validity</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="insurance_expiry" class="form-label">
                                    Insurance Expiry
                                </label>

                                <input
                                    type="date"
                                    name="insurance_expiry"
                                    id="insurance_expiry"
                                    class="form-control @error('insurance_expiry') is-invalid @enderror"
                                    value="{{ old('insurance_expiry', optional($transportVehicle->insurance_expiry)->format('Y-m-d')) }}"
                                >

                                @error('insurance_expiry')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- 04: VEHICLE STATUS --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-number">04</div>
                            <div>
                                <h3>Vehicle Status</h3>
                                <p>Set the current operational status</p>
                            </div>
                        </div>

                        <div class="status-options">

                            {{-- ACTIVE --}}
                            <div class="status-option">
                                <input
                                    type="radio"
                                    name="status"
                                    id="status_active"
                                    value="active"
                                    {{ old('status', $transportVehicle->status) === 'active' ? 'checked' : '' }}
                                >

                                <label for="status_active">
                                    <span class="status-dot"></span>
                                    Active
                                </label>
                            </div>

                            {{-- MAINTENANCE --}}
                            <div class="status-option maintenance">
                                <input
                                    type="radio"
                                    name="status"
                                    id="status_maintenance"
                                    value="maintenance"
                                    {{ old('status', $transportVehicle->status) === 'maintenance' ? 'checked' : '' }}
                                >

                                <label for="status_maintenance">
                                    <span class="status-dot"></span>
                                    Maintenance
                                </label>
                            </div>

                            {{-- INACTIVE --}}
                            <div class="status-option inactive">
                                <input
                                    type="radio"
                                    name="status"
                                    id="status_inactive"
                                    value="inactive"
                                    {{ old('status', $transportVehicle->status) === 'inactive' ? 'checked' : '' }}
                                >

                                <label for="status_inactive">
                                    <span class="status-dot"></span>
                                    Inactive
                                </label>
                            </div>

                        </div>

                        @error('status')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 05: ADDITIONAL INFORMATION --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-number">05</div>
                            <div>
                                <h3>Additional Information</h3>
                                <p>Add optional notes about this vehicle</p>
                            </div>
                        </div>

                        <label for="remarks" class="form-label">
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter any additional information..."
                        >{{ old('remarks', $transportVehicle->remarks) }}</textarea>

                        @error('remarks')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- FORM FOOTER --}}
                <div class="form-footer">

                    {{-- DELETE BUTTON --}}
                    <button
                        type="submit"
                        form="deleteVehicleForm"
                        class="delete-btn"
                        onclick="return confirm('Are you sure you want to delete this vehicle?');"
                    >
                        <i class="bi bi-trash3"></i>
                        Delete Vehicle
                    </button>

                    <div class="footer-right">

                        <a
                            href="{{ route('admin.transport.vehicles.index') }}"
                            class="cancel-btn"
                        >
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>

                        <button type="submit" class="save-btn">
                            <i class="bi bi-check2-circle"></i>
                            Update Vehicle
                        </button>

                    </div>
                </div>

            </form>

            {{-- SEPARATE DELETE FORM --}}
            <form
                id="deleteVehicleForm"
                action="{{ route('admin.transport.vehicles.destroy', $transportVehicle) }}"
                method="POST"
                style="display: none;"
            >
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const driverSelect = document.getElementById('driver_id');
    const driverName = document.getElementById('driver_name');
    const driverContact = document.getElementById('driver_contact');
    const driverLicense = document.getElementById('driver_license_number');

    function fillDriverDetails() {

        if (!driverSelect) {
            return;
        }

        const selectedOption =
            driverSelect.options[driverSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            driverName.value = '';
            driverContact.value = '';
            driverLicense.value = '';
            return;
        }

        driverName.value =
            selectedOption.dataset.name || '';

        driverContact.value =
            selectedOption.dataset.phone || '';

        driverLicense.value =
            selectedOption.dataset.license || '';
    }

    if (driverSelect) {
        driverSelect.addEventListener('change', fillDriverDetails);
        fillDriverDetails();
    }

});
</script>

@endsection
