@extends('layouts.app')

@section('title', 'Add Vehicle | Admin')

@section('content')

<style>
    /* =========================================================
       TRANSPORT VEHICLE CREATE PAGE
    ========================================================= */

    .vehicle-create-page {
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        padding: 28px 0 45px;
        color: #172033;
    }

    .vehicle-create-container {
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

    /* MAIN CARD */

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

    /* SECTION */

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

    /* FORM */

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

    /* DRIVER INFO */

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

    /* STATUS */

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

    /* ALERT */

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

    /* FOOTER */

    .form-footer {
        padding: 18px 25px;
        border-top: 1px solid #e8edf3;
        background: #fbfcfe;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .cancel-btn {
        min-height: 43px;
        padding: 9px 18px;
        border-radius: 10px;
        border: 1px solid #dce4ee;
        background: #fff;
        color: #475569;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: .2s ease;
    }

    .cancel-btn:hover {
        background: #f8fafc;
        color: #172033;
    }

    .save-btn {
        min-height: 43px;
        padding: 9px 20px;
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
        font-size: 14px;
        font-weight: 650;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 7px 16px rgba(23, 105, 209, .18);
        transition: .2s ease;
    }

    .save-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(23, 105, 209, .24);
    }

    /* RESPONSIVE */

    @media (max-width: 767px) {
        .vehicle-create-page {
            padding-top: 18px;
        }

        .vehicle-create-container {
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
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            justify-content: center;
            width: 100%;
        }
    }
</style>

<div class="vehicle-create-page">

    <div class="vehicle-create-container">

        {{-- PAGE HEADER --}}
        <div class="vehicle-page-header">

            <div class="vehicle-heading">

                <div class="vehicle-heading-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div>
                    <h1>Add Transport Vehicle</h1>
                    <p>Add a new vehicle and assign an active driver.</p>
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


        {{-- FORM CARD --}}
        <div class="vehicle-form-card">

            <div class="form-card-header">

                <div class="form-card-header-icon">
                    <i class="bi bi-bus-front"></i>
                </div>

                <div>
                    <h2>Vehicle Information</h2>
                    <p>Enter the vehicle details and assign a driver.</p>
                </div>

            </div>


            <form
                action="{{ route('admin.transport.vehicles.store') }}"
                method="POST"
            >

                @csrf


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


                    {{-- =====================================================
                         BASIC VEHICLE INFORMATION
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-card-text"></i>
                            </div>

                            <h3>Basic Vehicle Information</h3>

                        </div>


                        <div class="row g-4">

                            {{-- VEHICLE NUMBER --}}
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
                                        value="{{ old('vehicle_number') }}"
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


                            {{-- VEHICLE TYPE --}}
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

                                        <option
                                            value="Bus"
                                            {{ old('vehicle_type') == 'Bus' ? 'selected' : '' }}
                                        >
                                            Bus
                                        </option>

                                        <option
                                            value="Mini Bus"
                                            {{ old('vehicle_type') == 'Mini Bus' ? 'selected' : '' }}
                                        >
                                            Mini Bus
                                        </option>

                                        <option
                                            value="Van"
                                            {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}
                                        >
                                            Van
                                        </option>

                                        <option
                                            value="School Van"
                                            {{ old('vehicle_type') == 'School Van' ? 'selected' : '' }}
                                        >
                                            School Van
                                        </option>

                                        <option
                                            value="Other"
                                            {{ old('vehicle_type') == 'Other' ? 'selected' : '' }}
                                        >
                                            Other
                                        </option>

                                    </select>

                                </div>

                                @error('vehicle_type')
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- VEHICLE MODEL --}}
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
                                    value="{{ old('vehicle_model') }}"
                                    placeholder="e.g. Tata Starbus"
                                >

                            </div>


                            {{-- VEHICLE COLOR --}}
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
                                    value="{{ old('vehicle_color') }}"
                                    placeholder="e.g. Yellow"
                                >

                            </div>


                            {{-- CAPACITY --}}
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
                                        value="{{ old('capacity') }}"
                                        min="1"
                                        placeholder="e.g. 40"
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


                    {{-- =====================================================
                         DRIVER INFORMATION
                    ====================================================== --}}
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
                                        Driver details will be filled automatically.
                                    </span>
                                </div>

                            </div>


                            <div class="row g-4">

                                {{-- DRIVER DROPDOWN --}}
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

                                            @foreach ($drivers as $driver)

                                                <option
                                                    value="{{ $driver->id }}"
                                                    data-name="{{ $driver->name }}"
                                                    data-phone="{{ $driver->phone }}"
                                                    data-license="{{ $driver->license_number }}"
                                                    {{ old('driver_id') == $driver->id ? 'selected' : '' }}
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
                                        Only active staff members whose designation is Driver are shown.
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
                                            placeholder="Auto-filled"
                                            readonly
                                        >

                                    </div>

                                </div>


                                {{-- DRIVER LICENSE --}}
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
                                            placeholder="Auto-filled"
                                            readonly
                                        >

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                         DOCUMENT EXPIRY
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>

                            <h3>Vehicle Documents</h3>

                        </div>


                        <div class="row g-4">

                            {{-- INSURANCE --}}
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
                                    value="{{ old('insurance_expiry') }}"
                                >

                            </div>


                            {{-- FITNESS --}}
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
                                    value="{{ old('fitness_expiry') }}"
                                >

                            </div>


                            {{-- PERMIT --}}
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
                                    value="{{ old('permit_expiry') }}"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                         STATUS
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-toggle-on"></i>
                            </div>

                            <h3>Vehicle Status</h3>

                        </div>


                        <div class="status-options">

                            {{-- ACTIVE --}}
                            <div class="status-option">

                                <input
                                    type="radio"
                                    name="status"
                                    id="status_active"
                                    value="active"
                                    {{ old('status', 'active') === 'active' ? 'checked' : '' }}
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
                                    {{ old('status') === 'maintenance' ? 'checked' : '' }}
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
                                    {{ old('status') === 'inactive' ? 'checked' : '' }}
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


                    {{-- =====================================================
                         REMARKS
                    ====================================================== --}}
                    <div class="form-section">

                        <div class="section-title">

                            <div class="section-title-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </div>

                            <h3>Additional Information</h3>

                        </div>


                        <div class="row">

                            <div class="col-12">

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
                                    placeholder="Enter any additional information about this vehicle..."
                                >{{ old('remarks') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- FORM FOOTER --}}
                <div class="form-footer">

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
                        Save Vehicle
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     DRIVER AUTO-FILL SCRIPT
========================================================= --}}
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


    // Fill automatically if old driver value exists
    // after validation failure.
    fillDriverDetails();

});
</script>

@endsection
