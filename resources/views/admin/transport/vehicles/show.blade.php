@extends('layouts.app')

@section('title', 'Vehicle Details | Admin')

@section('content')

<style>
    .vehicle-show-page {
        min-height: calc(100vh - 60px);
        background: #f4f7fb;
        padding: 28px 0 45px;
        color: #172033;
    }

    .vehicle-show-container {
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

    .header-actions {
        display: flex;
        gap: 9px;
        align-items: center;
    }

    .header-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .back-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #475569;
    }

    .back-btn:hover {
        background: #f2f7ff;
        color: #1769d1;
        border-color: #cfe0fa;
    }

    .edit-btn {
        background: linear-gradient(135deg, #1769d1, #237de0);
        color: #fff;
        box-shadow: 0 6px 15px rgba(23, 105, 209, .16);
    }

    .edit-btn:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .vehicle-summary-card {
        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #237de0 55%,
            #159cc7 100%
        );
        border-radius: 18px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 10px 28px rgba(23, 105, 209, .18);
        margin-bottom: 20px;
    }

    .vehicle-summary-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
    }

    .vehicle-summary-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .vehicle-large-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        background: rgba(255,255,255,.17);
        border: 1px solid rgba(255,255,255,.20);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .vehicle-summary-left h2 {
        margin: 0 0 5px;
        font-size: 25px;
        font-weight: 750;
    }

    .vehicle-summary-left p {
        margin: 0;
        font-size: 14px;
        opacity: .9;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        background: rgba(255,255,255,.17);
        border: 1px solid rgba(255,255,255,.20);
    }

    .status-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #fff;
    }

    .info-card {
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        overflow: hidden;
        height: 100%;
    }

    .info-card-header {
        padding: 19px 21px;
        border-bottom: 1px solid #e8edf3;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .info-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .blue-icon {
        background: #dbeafe;
        color: #1769d1;
    }

    .purple-icon {
        background: #ede9fe;
        color: #7c3aed;
    }

    .orange-icon {
        background: #ffedd5;
        color: #ea580c;
    }

    .green-icon {
        background: #dcfce7;
        color: #16a34a;
    }

    .info-card-header h3 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #172033;
    }

    .info-card-header p {
        margin: 3px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .info-card-body {
        padding: 21px;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 12px 0;
        border-bottom: 1px solid #edf1f5;
    }

    .info-row:first-child {
        padding-top: 0;
    }

    .info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        color: #64748b;
        font-size: 13px;
    }

    .info-value {
        color: #172033;
        font-size: 13px;
        font-weight: 650;
        text-align: right;
        word-break: break-word;
    }

    .empty-value {
        color: #94a3b8;
        font-weight: 500;
    }

    .driver-profile {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e6edf5;
        margin-bottom: 17px;
    }

    .driver-avatar {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .driver-profile strong {
        display: block;
        font-size: 14px;
        color: #172033;
    }

    .driver-profile span {
        display: block;
        margin-top: 3px;
        font-size: 12px;
        color: #64748b;
    }

    .document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid #edf1f5;
    }

    .document-item:last-child {
        border-bottom: 0;
    }

    .document-name {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #475569;
        font-size: 13px;
    }

    .document-name i {
        color: #1769d1;
    }

    .document-date {
        font-size: 13px;
        font-weight: 650;
        color: #172033;
    }

    .document-date.empty {
        color: #94a3b8;
        font-weight: 500;
    }

    .remarks-box {
        background: #f8fafc;
        border: 1px solid #e6edf5;
        border-radius: 12px;
        padding: 15px;
        color: #475569;
        font-size: 13px;
        line-height: 1.7;
        min-height: 90px;
    }

    .bottom-actions {
        margin-top: 20px;
        background: #fff;
        border: 1px solid #e5ebf3;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
        padding: 17px 21px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .bottom-actions-text {
        color: #64748b;
        font-size: 13px;
    }

    .delete-btn {
        border: 1px solid #fecaca;
        background: #fff;
        color: #dc2626;
        padding: 10px 17px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        transition: .2s ease;
    }

    .delete-btn:hover {
        background: #fff1f0;
        border-color: #fca5a5;
    }

    @media (max-width: 767px) {

        .vehicle-show-page {
            padding-top: 18px;
        }

        .vehicle-show-container {
            padding: 0 13px;
        }

        .vehicle-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .header-actions {
            width: 100%;
        }

        .header-btn {
            flex: 1;
            justify-content: center;
        }

        .vehicle-summary-card {
            padding: 22px;
        }

        .vehicle-summary-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .vehicle-summary-left h2 {
            font-size: 21px;
        }

        .bottom-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .delete-btn {
            width: 100%;
            justify-content: center;
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

                    <h2>
                        {{ $transportVehicle->vehicle_number }}
                    </h2>

                    <p>
                        {{ $transportVehicle->vehicle_type }}
                    </p>

                </div>

            </div>

            <div>

                <span class="status-badge">

                    <span class="status-badge-dot"></span>

                    {{ ucfirst($transportVehicle->status) }}

                </span>

            </div>

        </div>

    </div>

    <div class="row g-4">

        {{-- BASIC INFORMATION --}}
        <div class="col-lg-6">

            <div class="info-card">

                <div class="info-card-header">

                    <div class="info-card-icon blue-icon">
                        <i class="bi bi-card-text"></i>
                    </div>

                    <div>
                        <h3>Vehicle Information</h3>
                        <p>Basic vehicle details</p>
                    </div>

                </div>

                <div class="info-card-body">

                    <div class="info-row">

                        <span class="info-label">
                            Vehicle Number
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->vehicle_number }}
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Vehicle Type
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->vehicle_type }}
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Seating Capacity
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->capacity }} Seats
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Status
                        </span>

                        <span class="info-value">
                            {{ ucfirst($transportVehicle->status) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- DRIVER INFORMATION --}}
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

                            <span class="info-label">
                                Contact Number
                            </span>

                            <span class="info-value">
                                {{ $transportVehicle->driver->phone ?: 'Not provided' }}
                            </span>

                        </div>

                        <div class="info-row">

                            <span class="info-label">
                                Licence Number
                            </span>

                            <span class="info-value">
                                {{ $transportVehicle->driver->license_number ?: 'Not provided' }}
                            </span>

                        </div>

                        <div class="info-row">

                            <span class="info-label">
                                Licence Expiry
                            </span>

                            <span class="info-value">

                                @if($transportVehicle->driver->license_expiry)

                                    {{ $transportVehicle->driver->license_expiry->format('d M Y') }}

                                @else

                                    <span class="empty-value">
                                        Not provided
                                    </span>

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

        {{-- VEHICLE DOCUMENTS --}}
        <div class="col-lg-6">

            <div class="info-card">

                <div class="info-card-header">

                    <div class="info-card-icon orange-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>

                    <div>
                        <h3>Vehicle Documents</h3>
                        <p>Expiry information</p>
                    </div>

                </div>

                <div class="info-card-body">

                    {{-- INSURANCE ONLY --}}
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

        {{-- STORED DRIVER DETAILS --}}
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

                        <span class="info-label">
                            Driver Name
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->driver_name ?: 'Not assigned' }}
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Driver Contact
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->driver_contact ?: 'Not provided' }}
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Driving Licence
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->driver_license_number ?: 'Not provided' }}
                        </span>

                    </div>

                    <div class="info-row">

                        <span class="info-label">
                            Vehicle Added
                        </span>

                        <span class="info-value">
                            {{ $transportVehicle->created_at->format('d M Y, h:i A') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- REMARKS --}}
        <div class="col-12">

            <div class="info-card">

                <div class="info-card-header">

                    <div class="info-card-icon blue-icon">
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

            <i class="bi bi-info-circle me-1"></i>

            Manage this vehicle from the actions on this page.

        </div>

        <form
            action="{{ route('admin.transport.vehicles.destroy', $transportVehicle) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to delete this vehicle? This action cannot be undone.');"
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

    </div>

</div>

</div>

@endsection
