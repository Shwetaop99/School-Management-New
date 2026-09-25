@extends('layouts.app')

@section('title', 'Edit Vehicle | Admin')

@section('content')

<style>
    .vehicle-edit-page {
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        padding: 28px 0 45px;
        color: #172033;
    }

    .vehicle-edit-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .vehicle-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .vehicle-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .vehicle-heading-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        box-shadow: 0 8px 20px rgba(23, 105, 209, .18);
    }

    .vehicle-heading h1 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .vehicle-heading p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 17px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: .2s ease;
    }

    .back-btn:hover {
        background: #f2f7ff;
        color: #1769d1;
        border-color: #cfe0fa;
    }

    .vehicle-form-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-card-header {
        padding: 21px 25px;
        border-bottom: 1px solid #e8edf3;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-card-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: #dbeafe;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .form-card-header h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #172033;
    }

    .form-card-header p {
        margin: 3px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .vehicle-form-body {
        padding: 28px 25px;
    }

    .form-section {
        margin-bottom: 28px;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 17px;
        padding-bottom: 11px;
        border-bottom: 1px solid #edf1f5;
    }

    .section-title-icon {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #f2f7ff;
        color: #1769d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .section-title h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #172033;
    }

    .form-label {
        color: #334155;
        font-size: 13px;
        font-weight: 650;
        margin-bottom: 7px;
    }

    .required {
        color: #e94d47;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #dce4ee;
        border-radius: 10px;
        color: #172033;
        font-size: 14px;
        padding: 9px 13px;
        background-color: #fff;
        box-shadow: none;
        transition: .2s ease;
    }

    textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1769d1;
        box-shadow: 0 0 0 3px rgba(23, 105, 209, .10);
    }

    .form-control[readonly] {
        background: #f8fafc;
        color: #475569;
        cursor: not-allowed;
    }

    .form-text {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 6px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .form-control,
    .input-icon-wrapper .form-select {
        padding-left: 40px;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        z-index: 2;
        pointer-events: none;
    }

    .driver-info-box {
        background: #f8fafc;
        border: 1px solid #e5ebf3;
        border-radius: 13px;
        padding: 18px;
        margin-top: 4px;
    }

    .driver-info-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
    }

    .driver-info-heading-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .driver-info-heading strong {
        font-size: 14px;
        color: #172033;
    }

    .driver-info-heading span {
        display: block;
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .status-options {
        display: flex;
        gap: 10px;
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid #dce4ee;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #94a3b8;
    }

    .status-option input:checked + label {
        background: #f2f7ff;
        border-color: #1769d1;
        color: #1769d1;
    }

    .status-option input:checked + label .status-dot {
        background: #1769d1;
    }

    .status-option.maintenance input:checked + label {
        background: #fff7e8;
        border-color: #ed9208;
        color: #d17a00;
    }

    .status-option.maintenance input:checked + label .status-dot {
        background: #ed9208;
    }

    .status-option.inactive input:checked + label {
        background: #fff1f0;
        border-color: #e94d47;
        color: #d63d37;
    }

    .status-option.inactive input:checked + label .status-dot {
        background: #e94d47;
    }

    .validation-alert {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #b42318;
        border-radius: 11px;
        padding: 13px 15px;
        margin-bottom: 22px;
        font-size: 13px;
    }

    .validation-alert ul {
        margin: 7px 0 0 18px;
        padding: 0;
    }

    .field-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    .form-footer {
        padding: 18px 25px;
        border-top: 1px solid #e8edf3;
        background: #fbfcfe;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .footer-right {
        display: flex;
        gap: 10px;
    }

    .cancel-btn,
    .save-btn {
        min-height: 43px;
        padding: 9px 19px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .cancel-btn {
        border: 1px solid #dce4ee;
        background: #fff;
        color: #475569;
    }

    .cancel-btn:hover {
        background: #f8fafc;
        color: #172033;
    }

    .save-btn {
        border: 0;
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
        box-shadow: 0 7px 16px rgba(23, 105, 209, .18);
    }

    .save-btn:hover {
        transform: translateY(-1px);
        color: #fff;
        box-shadow: 0 9px 20px rgba(23, 105, 209, .24);
    }

    .delete-btn {
        min-height: 43px;
        padding: 9px 17px;
        border-radius: 10px;
        border: 1px solid #fecaca;
        background: #fff;
        color: #dc2626;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: .2s ease;
    }

    .delete-btn:hover {
        background: #fff1f0;
        border-color: #fca5a5;
    }

    @media (max-width: 767px) {

        .vehicle-edit-page {
            padding-top: 18px;
        }

        .vehicle-edit-container {
            padding: 0 13px;
        }

        .vehicle-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .vehicle-heading h1 {
            font-size: 21px;
        }

        .vehicle-form-body {
            padding: 21px 17px;
        }

        .form-card-header {
            padding: 18px;
        }

        .form-footer {
            padding: 16px 17px;
            flex-direction: column;
            align-items: stretch;
        }

        .footer-right {
            flex-direction: column;
        }

        .cancel-btn,
        .save-btn,
        .delete-btn {
            width: 100%;
            justify-content: center;
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


            <form
                action="{{ route('admin.transport.vehicles.update', $transportVehicle) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="vehicle-form-body">

                    {{-- VALIDATION ERRORS --}}
                    @if ($errors->any())

                        <div class="validation-alert">

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


                    {{-- BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-card-text"></i>
                            </div>

                            <h3>Basic Vehicle Information</h3>

                        </div>


                        <div class="row g-4">

                            <div class="col-md-6">

                                <label
                                    for="vehicle_number"
                                    class="form-label"
                                >
                                    Vehicle Number
                                    <span class="required">*</span>
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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="vehicle_type"
                                    class="form-label"
                                >
                                    Vehicle Type
                                    <span class="required">*</span>
                                </label>

                                <div class="input-icon-wrapper">

                                    <i class="bi bi-truck input-icon"></i>

                                    <select
                                        name="vehicle_type"
                                        id="vehicle_type"
                                        class="form-select @error('vehicle_type') is-invalid @enderror"
                                        required
                                    >

                                        <option value="">
                                            Select Vehicle Type
                                        </option>

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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="vehicle_model"
                                    class="form-label"
                                >
                                    Vehicle Model
                                </label>

                                <input
                                    type="text"
                                    name="vehicle_model"
                                    id="vehicle_model"
                                    class="form-control"
                                    value="{{ old('vehicle_model', $transportVehicle->vehicle_model) }}"
                                    placeholder="e.g. Tata Starbus"
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="vehicle_color"
                                    class="form-label"
                                >
                                    Vehicle Color
                                </label>

                                <input
                                    type="text"
                                    name="vehicle_color"
                                    id="vehicle_color"
                                    class="form-control"
                                    value="{{ old('vehicle_color', $transportVehicle->vehicle_color) }}"
                                    placeholder="e.g. Yellow"
                                >

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="capacity"
                                    class="form-label"
                                >
                                    Seating Capacity
                                    <span class="required">*</span>
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
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- DRIVER --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <h3>Driver Assignment</h3>

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


                            <div class="row g-4">

                                {{-- DRIVER SELECT --}}
                                <div class="col-md-12">

                                    <label
                                        for="driver_id"
                                        class="form-label"
                                    >
                                        Select Driver
                                    </label>

                                    <div class="input-icon-wrapper">

                                        <i class="bi bi-person-check input-icon"></i>

                                        <select
                                            name="driver_id"
                                            id="driver_id"
                                            class="form-select @error('driver_id') is-invalid @enderror"
                                        >

                                            <option value="">
                                                No Driver Assigned
                                            </option>

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
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- DRIVER NAME --}}
                                <div class="col-md-4">

                                    <label
                                        for="driver_name"
                                        class="form-label"
                                    >
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

                                    <label
                                        for="driver_contact"
                                        class="form-label"
                                    >
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


                                {{-- LICENSE --}}
                                <div class="col-md-4">

                                    <label
                                        for="driver_license_number"
                                        class="form-label"
                                    >
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


                    {{-- DOCUMENTS --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>

                            <h3>Vehicle Documents</h3>

                        </div>


                        <div class="row g-4">

                            <div class="col-md-4">

                                <label
                                    for="insurance_expiry"
                                    class="form-label"
                                >
                                    Insurance Expiry
                                </label>

                                <input
                                    type="date"
                                    name="insurance_expiry"
                                    id="insurance_expiry"
                                    class="form-control"
                                    value="{{ old(
                                        'insurance_expiry',
                                        optional($transportVehicle->insurance_expiry)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="fitness_expiry"
                                    class="form-label"
                                >
                                    Fitness Expiry
                                </label>

                                <input
                                    type="date"
                                    name="fitness_expiry"
                                    id="fitness_expiry"
                                    class="form-control"
                                    value="{{ old(
                                        'fitness_expiry',
                                        optional($transportVehicle->fitness_expiry)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="permit_expiry"
                                    class="form-label"
                                >
                                    Permit Expiry
                                </label>

                                <input
                                    type="date"
                                    name="permit_expiry"
                                    id="permit_expiry"
                                    class="form-control"
                                    value="{{ old(
                                        'permit_expiry',
                                        optional($transportVehicle->permit_expiry)->format('Y-m-d')
                                    ) }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-toggle-on"></i>
                            </div>

                            <h3>Vehicle Status</h3>

                        </div>


                        <div class="status-options">

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
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- REMARKS --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </div>

                            <h3>Additional Information</h3>

                        </div>


                        <label
                            for="remarks"
                            class="form-label"
                        >
                            Remarks
                        </label>

                        <textarea
                            name="remarks"
                            id="remarks"
                            class="form-control"
                            placeholder="Enter any additional information..."
                        >{{ old('remarks', $transportVehicle->remarks) }}</textarea>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="form-footer">

                    <form
                        action="{{ route('admin.transport.vehicles.destroy', $transportVehicle) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this vehicle?');"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="delete-btn"
                        >
                            <i class="bi bi-trash3"></i>
                            Delete Vehicle
                        </button>

                    </form>


                    <div class="footer-right">

                        <a
                            href="{{ route('admin.transport.vehicles.index') }}"
                            class="cancel-btn"
                        >
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="save-btn"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Update Vehicle
                        </button>

                    </div>

                </div>

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

    driverSelect.addEventListener(
        'change',
        fillDriverDetails
    );

    fillDriverDetails();

});
</script>

@endsection
