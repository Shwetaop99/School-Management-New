
@extends('layouts.app')

@section('title', 'Add Vehicle | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — TRANSPORT VEHICLE CREATE
    ========================================================= */

    .vehicle-create-page {
        min-height: calc(100vh - 60px);
        background: #f5f3e9;
        padding: 22px 0 36px;
        color: #263b35;
        font-size: 12px;
    }

    .vehicle-create-page,
    .vehicle-create-page * {
        box-sizing: border-box;
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
        gap: 16px;
        margin-bottom: 18px;
    }

    .vehicle-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .vehicle-heading-icon {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        border-radius: 10px;
        background: #eaf3e9;
        border: 1px solid #d7e6d5;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .vehicle-breadcrumb {
        margin-bottom: 4px;
        color: #a87543;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1.1px;
        text-transform: uppercase;
    }

    .vehicle-heading h1 {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.25;
    }

    .vehicle-heading p {
        margin: 5px 0 0;
        color: #718078;
        font-size: 11px;
        line-height: 1.5;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-shrink: 0;
        padding: 9px 13px;
        border-radius: 8px;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        color: #34775a;
        text-decoration: none;
        font-size: 11px;
        font-weight: 750;
        transition: background .2s ease, border-color .2s ease;
    }

    .back-btn:hover {
        background: #eaf3e9;
        border-color: #b9d2bb;
        color: #245a43;
    }

    /* MAIN CARD */

    .vehicle-form-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 11px;
        box-shadow: 0 4px 15px rgba(38, 59, 53, .045);
        overflow: hidden;
    }

    .form-card-header {
        padding: 17px 22px;
        border-bottom: 1px solid #e5e1d3;
        display: flex;
        align-items: center;
        gap: 11px;
        background: #fffefa;
    }

    .form-card-header-icon {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        border-radius: 8px;
        background: #eaf3e9;
        border: 1px solid #d7e6d5;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .form-card-header h2 {
        margin: 0;
        color: #263b35;
        font-size: 14px;
        font-weight: 800;
    }

    .form-card-header p {
        margin: 4px 0 0;
        color: #7a857d;
        font-size: 10px;
        line-height: 1.5;
    }

    .vehicle-form-body {
        padding: 22px;
    }

    /* SECTION */

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
        border-bottom: 1px solid #e9e5d9;
    }

    .section-title-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 8px;
        background: #eaf3e9;
        border: 1px solid #dbe7d8;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .section-title h3 {
        margin: 0;
        color: #263b35;
        font-size: 12px;
        font-weight: 800;
    }

    /* FORM CONTROLS */

    .form-label {
        display: block;
        color: #40534a;
        font-size: 11px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .required {
        color: #bd5148;
    }

    .form-control,
    .form-select {
        width: 100%;
        min-height: 39px;
        border: 1px solid #dedbce;
        border-radius: 7px;
        color: #263b35;
        font-size: 11px;
        padding: 8px 11px;
        background-color: #fffefa;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .form-control::placeholder {
        color: #a1a69d;
        opacity: 1;
    }

    textarea.form-control {
        min-height: 95px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #719b7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
        background-color: #fffefa;
        color: #263b35;
    }

    .form-control[readonly] {
        background: #f5f3e9;
        color: #65736a;
        cursor: not-allowed;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #bd5148;
        background-image: none;
    }

    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
    }

    .form-text {
        color: #899188;
        font-size: 10px;
        line-height: 1.5;
        margin-top: 5px;
    }

    .input-icon-wrapper {
        position: relative;
    }

    .input-icon-wrapper .form-control,
    .input-icon-wrapper .form-select {
        padding-left: 35px;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #718b79;
        font-size: 12px;
        z-index: 2;
        pointer-events: none;
    }

    /* DRIVER INFORMATION */

    .driver-info-box {
        background: #faf9f2;
        border: 1px solid #e5e1d3;
        border-radius: 9px;
        padding: 16px;
        margin-top: 2px;
    }

    .driver-info-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
    }

    .driver-info-heading-icon {
        width: 32px;
        height: 32px;
        flex: 0 0 32px;
        border-radius: 8px;
        background: #eaf3e9;
        border: 1px solid #d7e6d5;
        color: #34775a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    .driver-info-heading strong {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #263b35;
    }

    .driver-info-heading span {
        display: block;
        font-size: 10px;
        color: #7a857d;
        margin-top: 3px;
        line-height: 1.5;
    }

    /* VALIDATION ALERTS */

    .validation-alert {
        border: 1px solid #e7c4bd;
        background: #fbefeb;
        color: #98453e;
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 20px;
        font-size: 11px;
        line-height: 1.6;
    }

    .validation-alert strong {
        font-weight: 800;
    }

    .validation-alert ul {
        margin: 6px 0 0 18px;
        padding: 0;
    }

    .field-error {
        color: #bd5148;
        font-size: 10px;
        font-weight: 650;
        margin-top: 5px;
    }

    /* FORM FOOTER */

    .form-footer {
        padding: 15px 22px;
        border-top: 1px solid #e5e1d3;
        background: #faf9f2;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
    }

    .cancel-btn,
    .save-btn {
        min-height: 37px;
        padding: 8px 15px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition: background .2s ease, border-color .2s ease;
    }

    .cancel-btn {
        border: 1px solid #dedbce;
        background: #fffefa;
        color: #59685f;
    }

    .cancel-btn:hover {
        background: #f0eee4;
        border-color: #cbc7b8;
        color: #263b35;
    }

    .save-btn {
        border: 1px solid #34775a;
        background: #34775a;
        color: #fffefa;
        cursor: pointer;
    }

    .save-btn:hover {
        background: #245a43;
        border-color: #245a43;
        color: #fffefa;
    }

    .save-btn:focus-visible,
    .cancel-btn:focus-visible,
    .back-btn:focus-visible {
        outline: 3px solid rgba(82, 127, 121, .3);
        outline-offset: 2px;
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .vehicle-create-container {
            padding: 0 16px;
        }

        .vehicle-form-body {
            padding: 19px;
        }

        .form-card-header {
            padding: 16px 19px;
        }

        .form-footer {
            padding: 14px 19px;
        }
    }

    @media (max-width: 767.98px) {
        .vehicle-create-page {
            padding: 17px 0 28px;
        }

        .vehicle-create-container {
            padding: 0 13px;
        }

        .vehicle-page-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 15px;
        }

        .vehicle-heading {
            align-items: flex-start;
        }

        .vehicle-heading-icon {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
            font-size: 17px;
        }

        .vehicle-heading h1 {
            font-size: 19px;
        }

        .vehicle-heading p {
            font-size: 10px;
        }

        .back-btn {
            padding: 8px 11px;
        }

        .vehicle-form-body {
            padding: 17px 14px;
        }

        .form-card-header {
            padding: 15px 14px;
        }

        .form-section {
            margin-bottom: 22px;
        }

        .driver-info-box {
            padding: 12px;
        }

        .form-footer {
            padding: 13px 14px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .vehicle-heading h1 {
            font-size: 17px;
        }

        .vehicle-heading-icon {
            width: 37px;
            height: 37px;
            flex-basis: 37px;
        }

        .form-card-header h2 {
            font-size: 13px;
        }

        .section-title h3 {
            font-size: 11px;
        }

        .vehicle-form-body {
            padding: 15px 12px;
        }

        .driver-info-heading {
            align-items: flex-start;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .vehicle-create-page *,
        .vehicle-create-page *::before,
        .vehicle-create-page *::after {
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
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
                    <div class="vehicle-breadcrumb">
                        Transport Management / Vehicles / Add Vehicle
                    </div>

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

                    {{-- BASIC VEHICLE INFORMATION --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-title-icon">
                                <i class="bi bi-card-text"></i>
                            </div>
                            <h3>Basic Vehicle Information</h3>
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
                                        value="{{ old('vehicle_number') }}"
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

                                        <option value="Bus" {{ old('vehicle_type') == 'Bus' ? 'selected' : '' }}>
                                            Bus
                                        </option>

                                        <option value="Mini Bus" {{ old('vehicle_type') == 'Mini Bus' ? 'selected' : '' }}>
                                            Mini Bus
                                        </option>

                                        <option value="Van" {{ old('vehicle_type') == 'Van' ? 'selected' : '' }}>
                                            Van
                                        </option>

                                        <option value="School Van" {{ old('vehicle_type') == 'School Van' ? 'selected' : '' }}>
                                            School Van
                                        </option>

                                        <option value="Other" {{ old('vehicle_type') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                </div>

                                @error('vehicle_type')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- SEATING CAPACITY --}}
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
                                        value="{{ old('capacity') }}"
                                        min="1"
                                        placeholder="e.g. 40"
                                        required
                                    >
                                </div>

                                @error('capacity')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="col-md-6">
                                <label for="status" class="form-label">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="input-icon-wrapper">
                                    <i class="bi bi-toggle-on input-icon"></i>

                                    <select
                                        name="status"
                                        id="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required
                                    >
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                            Maintenance
                                        </option>
                                    </select>
                                </div>

                                <div class="form-text">
                                    Select the current status of this vehicle.
                                </div>

                                @error('status')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- DRIVER INFORMATION --}}
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

                            <div class="row g-3">

                                {{-- DRIVER DROPDOWN --}}
                                <div class="col-md-12">
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
                                            placeholder="Auto-filled"
                                            readonly
                                        >
                                    </div>
                                </div>

                                {{-- DRIVER LICENSE --}}
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
                                            placeholder="Auto-filled"
                                            readonly
                                        >
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- VEHICLE DOCUMENTS --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-title-icon">
                                <i class="bi bi-file-earmark-check"></i>
                            </div>
                            <h3>Vehicle Documents</h3>
                        </div>

                        <div class="row g-3">

                            {{-- INSURANCE --}}
                            <div class="col-md-6">
                                <label for="insurance_expiry" class="form-label">
                                    Insurance Expiry
                                </label>

                                <input
                                    type="date"
                                    name="insurance_expiry"
                                    id="insurance_expiry"
                                    class="form-control @error('insurance_expiry') is-invalid @enderror"
                                    value="{{ old('insurance_expiry') }}"
                                >

                                @error('insurance_expiry')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- ADDITIONAL INFORMATION --}}
                    <div class="form-section">

                        <div class="section-title">
                            <div class="section-title-icon">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <h3>Additional Information</h3>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="remarks" class="form-label">
                                    Remarks
                                </label>

                                <textarea
                                    name="remarks"
                                    id="remarks"
                                    class="form-control @error('remarks') is-invalid @enderror"
                                    placeholder="Enter any additional information about this vehicle..."
                                >{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
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

                    <button type="submit" class="save-btn">
                        <i class="bi bi-check2-circle"></i>
                        Save Vehicle
                    </button>

                </div>

            </form>
        </div>

    </div>
</div>

{{-- DRIVER AUTO-FILL SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const driverSelect = document.getElementById('driver_id');
    const driverName = document.getElementById('driver_name');
    const driverContact = document.getElementById('driver_contact');
    const driverLicense = document.getElementById('driver_license_number');

    if (!driverSelect || !driverName || !driverContact || !driverLicense) {
        return;
    }

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

    driverSelect.addEventListener('change', fillDriverDetails);

    // Fill automatically if old driver value exists
    // after validation failure.
    fillDriverDetails();

});
</script>

@endsection
