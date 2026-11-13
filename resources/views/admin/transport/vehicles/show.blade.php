
@extends('layouts.app')

@section('title', 'Vehicle Details | Admin')
@section('page-title', 'Vehicle Details')

@section('content')

<style>
    .vehicle-show-page {
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

    .vehicle-show-page *,
    .vehicle-show-page *::before,
    .vehicle-show-page *::after {
        box-sizing: border-box;
    }

    .vehicle-show-container {
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

    .header-actions {
        display: flex;
        align-items: center;
        flex: 0 0 auto;
        gap: 8px;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 8px 13px;
        border: 1px solid var(--diary-border);
        border-radius: 8px;
        font-size: 11px;
        font-weight: 750;
        text-decoration: none;
        transition: background .2s ease, border-color .2s ease;
    }

    .back-btn {
        background: var(--diary-paper);
        color: var(--diary-ink);
    }

    .back-btn:hover {
        border-color: #b7d0b9;
        background: var(--diary-green-light);
        color: var(--diary-green-dark);
    }

    .edit-btn {
        border-color: var(--diary-green);
        background: var(--diary-green);
        color: #fffefa;
    }

    .edit-btn:hover {
        border-color: var(--diary-green-dark);
        background: var(--diary-green-dark);
        color: #fffefa;
    }

    /* VEHICLE SUMMARY */

    .vehicle-summary-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
        padding: 23px 25px;
        border: 1px solid #2c694d;
        border-radius: 11px;
        background: var(--diary-green);
        color: #fffefa;
        box-shadow: 0 4px 14px rgba(36, 90, 67, .10);
    }

    .vehicle-summary-card::after {
        position: absolute;
        top: -48px;
        right: -28px;
        width: 180px;
        height: 180px;
        border: 1px solid rgba(255, 254, 250, .12);
        border-radius: 50%;
        content: "";
        pointer-events: none;
    }

    .vehicle-summary-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .vehicle-summary-left {
        display: flex;
        align-items: center;
        min-width: 0;
        gap: 15px;
    }

    .vehicle-large-icon {
        display: flex;
        flex: 0 0 58px;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        border: 1px solid rgba(255, 254, 250, .25);
        border-radius: 10px;
        background: rgba(255, 254, 250, .12);
        color: #fffefa;
        font-size: 25px;
    }

    .vehicle-summary-left h2 {
        margin: 0 0 5px;
        color: #fffefa;
        font-size: 22px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .vehicle-summary-left p {
        margin: 0;
        color: #e7f0e8;
        font-size: 11px;
    }

    .summary-caption {
        margin-bottom: 5px;
        color: #e1ebe1;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 31px;
        padding: 7px 11px;
        border: 1px solid rgba(255, 254, 250, .30);
        border-radius: 7px;
        background: rgba(255, 254, 250, .12);
        color: #fffefa;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #fffefa;
    }

    /* INFORMATION CARDS */

    .info-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        border: 1px solid var(--diary-border);
        border-radius: 10px;
        background: var(--diary-paper);
        box-shadow: 0 3px 12px rgba(54, 64, 45, .035);
    }

    .info-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 17px;
        border-bottom: 1px solid var(--diary-border);
        background: #fbfaf3;
    }

    .info-card-icon {
        display: flex;
        flex: 0 0 34px;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border: 1px solid #d4e3d4;
        border-radius: 8px;
        font-size: 15px;
    }

    .green-icon {
        background: var(--diary-green-light);
        color: var(--diary-green);
    }

    .purple-icon {
        background: #eef0e4;
        color: var(--diary-cyan);
    }

    .orange-icon {
        background: #f7eee2;
        color: var(--diary-wood);
    }

    .red-icon {
        background: #f9eae7;
        color: var(--diary-red);
    }

    .info-card-header h3 {
        margin: 0;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 800;
    }

    .info-card-header p {
        margin: 3px 0 0;
        color: #829087;
        font-size: 10px;
        line-height: 1.4;
    }

    .info-card-body {
        flex: 1;
        padding: 16px 17px;
    }

    /* INFORMATION ROWS */

    .info-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 11px 0;
        border-bottom: 1px solid #eeebdf;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .info-label {
        flex: 0 0 auto;
        color: #718078;
        font-size: 10px;
        line-height: 1.6;
    }

    .info-value {
        max-width: 65%;
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 750;
        line-height: 1.6;
        text-align: right;
        overflow-wrap: anywhere;
    }

    .empty-value {
        color: #92978b;
        font-weight: 500;
    }

    /* DRIVER PROFILE */

    .driver-profile {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 15px;
        padding: 12px;
        border: 1px solid #dce6d8;
        border-radius: 8px;
        background: #f8f8ef;
    }

    .driver-avatar {
        display: flex;
        flex: 0 0 40px;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #d4e3d4;
        border-radius: 8px;
        background: var(--diary-green-light);
        color: var(--diary-green);
        font-size: 18px;
    }

    .driver-profile strong {
        display: block;
        color: var(--diary-ink);
        font-size: 12px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .driver-profile span {
        display: block;
        margin-top: 4px;
        color: #77857b;
        font-size: 10px;
    }

    /* DOCUMENTS */

    .document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid #eeebdf;
    }

    .document-item:first-child {
        padding-top: 0;
    }

    .document-item:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .document-name {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #52645a;
        font-size: 11px;
    }

    .document-name i {
        color: var(--diary-green);
        font-size: 13px;
    }

    .document-date {
        color: var(--diary-ink);
        font-size: 11px;
        font-weight: 750;
        text-align: right;
    }

    .document-date.empty {
        color: #92978b;
        font-weight: 500;
    }

    /* REMARKS */

    .remarks-box {
        min-height: 75px;
        padding: 13px;
        border: 1px solid var(--diary-border);
        border-radius: 8px;
        background: #f8f7ef;
        color: #52645a;
        font-size: 11px;
        line-height: 1.8;
        overflow-wrap: anywhere;
        white-space: normal;
    }

    /* BOTTOM ACTIONS */

    .bottom-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 20px;
        padding: 15px 17px;
        border: 1px solid var(--diary-border);
        border-radius: 10px;
        background: var(--diary-paper);
        box-shadow: 0 3px 12px rgba(54, 64, 45, .035);
    }

    .bottom-actions-text {
        color: #718078;
        font-size: 10px;
        line-height: 1.6;
    }

    .bottom-actions-text i {
        margin-right: 5px;
        color: var(--diary-green);
    }

    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 8px 13px;
        border: 1px solid #e8c7c0;
        border-radius: 7px;
        background: var(--diary-paper);
        color: var(--diary-red);
        font-size: 11px;
        font-weight: 750;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .delete-btn:hover {
        border-color: var(--diary-red);
        background: #f9eae7;
        color: #9e3e37;
    }

    /* RESPONSIVE */

    @media (max-width: 991.98px) {
        .vehicle-show-container {
            padding: 0 16px;
        }

        .vehicle-summary-card {
            padding: 20px;
        }
    }

    @media (max-width: 767.98px) {
        .vehicle-show-page {
            padding-top: 18px;
        }

        .vehicle-show-container {
            padding: 0 13px;
        }

        .vehicle-page-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 13px;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
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

        .vehicle-summary-card {
            padding: 18px;
        }

        .vehicle-summary-content {
            align-items: flex-start;
            flex-direction: column;
            gap: 15px;
        }

        .vehicle-summary-left h2 {
            font-size: 19px;
        }

        .bottom-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .delete-btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .vehicle-heading h1 {
            font-size: 17px;
        }

        .vehicle-heading p {
            font-size: 10px;
        }

        .info-card-header {
            padding: 13px;
        }

        .info-card-body {
            padding: 14px 13px;
        }

        .info-row {
            gap: 10px;
        }

        .info-value {
            max-width: 60%;
        }

        .vehicle-large-icon {
            flex-basis: 48px;
            width: 48px;
            height: 48px;
            font-size: 21px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .vehicle-show-page *,
        .vehicle-show-page *::before,
        .vehicle-show-page *::after {
            scroll-behavior: auto !important;
            transition: none !important;
        }
    }
</style>

<div class="vehicle-show-page">
    <div class="vehicle-show-container">

        {{-- PAGE HEADER --}}
        <div class="vehicle-page-header">

            <div class="vehicle-heading">
                <div class="vehicle-heading-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div>
                    <div class="vehicle-breadcrumb">
                        Transport Management / Vehicles / Details
                    </div>

                    <h1>Vehicle Details</h1>
                    <p>View complete transport vehicle information.</p>
                </div>
            </div>

            <div class="header-actions">

                <a
                    href="{{ route('admin.transport.vehicles.index') }}"
                    class="header-btn back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>

                <a
                    href="{{ route('admin.transport.vehicles.edit', $transportVehicle) }}"
                    class="header-btn edit-btn"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit Vehicle
                </a>

            </div>
        </div>

        {{-- VEHICLE SUMMARY --}}
        <div class="vehicle-summary-card">

            <div class="vehicle-summary-content">

                <div class="vehicle-summary-left">
                    <div class="vehicle-large-icon">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>

                    <div>
                        <div class="summary-caption">Vehicle Registration</div>

                        <h2>{{ $transportVehicle->vehicle_number }}</h2>

                        <p>{{ $transportVehicle->vehicle_type }}</p>
                    </div>
                </div>

                <div>
                    <div class="summary-caption">Current Status</div>

                    <span class="status-badge">
                        <span class="status-badge-dot"></span>
                        {{ ucfirst($transportVehicle->status) }}
                    </span>
                </div>

            </div>
        </div>

        <div class="row g-3">

            {{-- 01: BASIC INFORMATION --}}
            <div class="col-lg-6">

                <div class="info-card">

                    <div class="info-card-header">
                        <div class="info-card-icon green-icon">
                            <i class="bi bi-card-text"></i>
                        </div>

                        <div>
                            <h3>Vehicle Information</h3>
                            <p>Basic vehicle details</p>
                        </div>
                    </div>

                    <div class="info-card-body">

                        <div class="info-row">
                            <span class="info-label">Vehicle Number</span>
                            <span class="info-value">
                                {{ $transportVehicle->vehicle_number }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Vehicle Type</span>
                            <span class="info-value">
                                {{ $transportVehicle->vehicle_type }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Seating Capacity</span>
                            <span class="info-value">
                                {{ $transportVehicle->capacity }} Seats
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                {{ ucfirst($transportVehicle->status) }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 02: DRIVER INFORMATION --}}
            <div class="col-lg-6">

                <div class="info-card">

                    <div class="info-card-header">
                        <div class="info-card-icon purple-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <div>
                            <h3>Driver Information</h3>
                            <p>Assigned transport driver</p>
                        </div>
                    </div>

                    <div class="info-card-body">

                        @if($transportVehicle->driver)

                            <div class="driver-profile">

                                <div class="driver-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>

                                <div>
                                    <strong>
                                        {{ $transportVehicle->driver->name }}
                                    </strong>

                                    <span>
                                        Staff ID:
                                        {{ $transportVehicle->driver->staff_id ?? 'N/A' }}
                                    </span>
                                </div>

                            </div>

                            <div class="info-row">
                                <span class="info-label">Contact Number</span>

                                <span class="info-value">
                                    {{ $transportVehicle->driver->phone ?: 'Not provided' }}
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Licence Number</span>

                                <span class="info-value">
                                    {{ $transportVehicle->driver->license_number ?: 'Not provided' }}
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Licence Expiry</span>

                                <span class="info-value">
                                    @if($transportVehicle->driver->license_expiry)
                                        {{ $transportVehicle->driver->license_expiry->format('d M Y') }}
                                    @else
                                        <span class="empty-value">Not provided</span>
                                    @endif
                                </span>
                            </div>

                        @else

                            <div class="remarks-box">
                                <i class="bi bi-person-x me-1"></i>
                                No driver is currently assigned to this vehicle.
                            </div>

                        @endif

                    </div>
                </div>
            </div>

            {{-- 03: VEHICLE DOCUMENTS --}}
            <div class="col-lg-6">

                <div class="info-card">

                    <div class="info-card-header">
                        <div class="info-card-icon orange-icon">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>

                        <div>
                            <h3>Vehicle Documents</h3>
                            <p>Insurance expiry information</p>
                        </div>
                    </div>

                    <div class="info-card-body">

                        <div class="document-item">

                            <div class="document-name">
                                <i class="bi bi-shield-check"></i>
                                Insurance
                            </div>

                            @if($transportVehicle->insurance_expiry)

                                <div class="document-date">
                                    {{ $transportVehicle->insurance_expiry->format('d M Y') }}
                                </div>

                            @else

                                <div class="document-date empty">
                                    Not provided
                                </div>

                            @endif

                        </div>

                    </div>
                </div>
            </div>

            {{-- 04: STORED DRIVER DETAILS --}}
            <div class="col-lg-6">

                <div class="info-card">

                    <div class="info-card-header">
                        <div class="info-card-icon green-icon">
                            <i class="bi bi-database-check"></i>
                        </div>

                        <div>
                            <h3>Assigned Driver Record</h3>
                            <p>Driver details stored with vehicle</p>
                        </div>
                    </div>

                    <div class="info-card-body">

                        <div class="info-row">
                            <span class="info-label">Driver Name</span>

                            <span class="info-value">
                                {{ $transportVehicle->driver_name ?: 'Not assigned' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Driver Contact</span>

                            <span class="info-value">
                                {{ $transportVehicle->driver_contact ?: 'Not provided' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Driving Licence</span>

                            <span class="info-value">
                                {{ $transportVehicle->driver_license_number ?: 'Not provided' }}
                            </span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Vehicle Added</span>

                            <span class="info-value">
                                {{ $transportVehicle->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 05: REMARKS --}}
            <div class="col-12">

                <div class="info-card">

                    <div class="info-card-header">
                        <div class="info-card-icon green-icon">
                            <i class="bi bi-chat-left-text"></i>
                        </div>

                        <div>
                            <h3>Remarks</h3>
                            <p>Additional vehicle information</p>
                        </div>
                    </div>

                    <div class="info-card-body">

                        <div class="remarks-box">
                            @if($transportVehicle->remarks)
                                {{ $transportVehicle->remarks }}
                            @else
                                <span class="empty-value">
                                    No remarks have been added for this vehicle.
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- BOTTOM ACTIONS --}}
        <div class="bottom-actions">

            <div class="bottom-actions-text">
                <i class="bi bi-info-circle"></i>
                Manage this vehicle from the actions on this page.
            </div>

            <form
                action="{{ route('admin.transport.vehicles.destroy', $transportVehicle) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.');"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="delete-btn">
                    <i class="bi bi-trash3"></i>
                    Delete Vehicle
                </button>
            </form>

        </div>

    </div>
</div>

@endsection
