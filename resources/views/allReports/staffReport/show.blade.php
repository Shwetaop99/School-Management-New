@extends('layouts.app')

@section('title', 'Staff Profile Report | Admin')
@section('page-title', 'Staff Profile Report')

@section('content')

@php
    $isDriver = strtolower(trim($otherStaff->designation ?? '')) === 'driver';

    /*
    |--------------------------------------------------------------------------
    | Staff Profile Photo URL
    |--------------------------------------------------------------------------
    */
    $staffPhotoUrl = null;
    $photoPath = trim((string) ($otherStaff->profile_photo ?? ''));

    if ($photoPath !== '') {
        if (filter_var($photoPath, FILTER_VALIDATE_URL)) {
            $staffPhotoUrl = $photoPath;
        } elseif (str_starts_with($photoPath, 'other-staff/')) {
            // Legacy images stored on Laravel public storage.
            $staffPhotoUrl = \Illuminate\Support\Facades\Storage::disk('public')
                ->url($photoPath);
        } else {
            // New images stored as Cloudinary public IDs.
            try {
                $staffPhotoUrl = (string) (
                    (new \Cloudinary\Cloudinary())->image($photoPath)
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning(
                    'Staff profile image URL generation failed.',
                    ['staff_id' => $otherStaff->id]
                );
            }
        }
    }

    $staffInitial = strtoupper(substr(
        trim($otherStaff->name ?? 'S') ?: 'S',
        0,
        1
    ));
@endphp

<div class="payroll-simple staff-profile-report-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL PRINT HEADER --}}
        <div class="profile-print-header">
            @if($schoolSetting?->logo_url)
                <div class="profile-print-logo-wrap">
                    <img src="{{ $schoolSetting->logo_url }}" alt="School Logo">
                </div>
            @endif

            <div class="profile-print-school">
                <h1>{{ $schoolSetting->school_name ?? config('app.name') }}</h1>

                <p>
                    {{ collect([
                        $schoolSetting->address ?? null,
                        $schoolSetting->city ?? null,
                        $schoolSetting->district ?? null,
                        $schoolSetting->state ?? null,
                        $schoolSetting->pincode ?? null
                    ])->filter()->implode(', ') }}
                </p>

                <p>
                    @if($schoolSetting?->phone)
                        Phone: {{ $schoolSetting->phone }}
                    @endif

                    @if($schoolSetting?->email)
                        | Email: {{ $schoolSetting->email }}
                    @endif

                    @if($schoolSetting?->website)
                        | {{ $schoolSetting->website }}
                    @endif
                </p>

                <h2>Individual Staff Profile Report</h2>
            </div>
        </div>

        {{-- TOOLBAR --}}
        <div class="profile-toolbar mb-4">

            <a href="{{ route('admin.reports.staff') }}" class="profile-back">
                <i class="bi bi-arrow-left"></i>
                Back to Staff Reports
            </a>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.staff.show.pdf', $otherStaff->id) }}"
                   class="profile-action text-decoration-none">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Download PDF
                </a>

                <a href="{{ route('admin.reports.staff.show.excel', $otherStaff->id) }}"
                   class="profile-action text-decoration-none">
                    <i class="bi bi-file-earmark-excel"></i>
                    Export Excel
                </a>

                <button type="button"
                        class="profile-action"
                        onclick="window.print()">
                    <i class="bi bi-printer"></i>
                    Print Profile
                </button>
            </div>
        </div>

        {{-- STAFF HERO --}}
        <div class="profile-hero mb-4">

            <div class="profile-hero-main">

                <div class="profile-avatar">
                    @if($staffPhotoUrl)
                        <img src="{{ $staffPhotoUrl }}"
                             alt="{{ $otherStaff->name }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='grid';">

                        <span class="profile-avatar-fallback" style="display:none;">
                            {{ $staffInitial }}
                        </span>
                    @else
                        <span>{{ $staffInitial }}</span>
                    @endif
                </div>

                <div class="profile-identity">
                    <span class="profile-eyebrow">STAFF PROFILE</span>

                    <h2>{{ $otherStaff->name ?: 'Staff Name Not Available' }}</h2>

                    <p>
                        {{ $otherStaff->designation ?: 'Designation unavailable' }}
                        ·
                        {{ $otherStaff->department ?: 'Department unavailable' }}
                    </p>

                    <span class="profile-status">
                        {{ ucfirst($otherStaff->status ?? 'Status unavailable') }}
                    </span>
                </div>

            </div>

            <div class="profile-hero-meta">
                <div>
                    <span>Staff ID</span>
                    <strong>{{ $otherStaff->staff_id ?: '—' }}</strong>
                </div>

                <div>
                    <span>Joining Date</span>
                    <strong>{{ $otherStaff->joining_date?->format('d M Y') ?? '—' }}</strong>
                </div>

                <div>
                    <span>Designation</span>
                    <strong>{{ $otherStaff->designation ?: '—' }}</strong>
                </div>
            </div>

        </div>

        {{-- PERSONAL INFORMATION --}}
        <div class="profile-section">

            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">STAFF INFORMATION</span>
                    <h3>Personal Details</h3>
                </div>
            </div>

            <div class="profile-details-grid">

                <div>
                    <span>Full Name</span>
                    <strong>{{ $otherStaff->name ?: '—' }}</strong>
                </div>

                <div>
                    <span>Staff ID</span>
                    <strong>{{ $otherStaff->staff_id ?: '—' }}</strong>
                </div>

                <div>
                    <span>Gender</span>
                    <strong>{{ $otherStaff->gender ? ucfirst($otherStaff->gender) : '—' }}</strong>
                </div>

                <div>
                    <span>Date of Birth</span>
                    <strong>{{ $otherStaff->date_of_birth?->format('d M Y') ?? '—' }}</strong>
                </div>

                <div>
                    <span>Phone Number</span>
                    <strong>{{ $otherStaff->phone ?: '—' }}</strong>
                </div>

                <div>
                    <span>Email Address</span>
                    <strong>{{ $otherStaff->email ?: '—' }}</strong>
                </div>

                <div class="profile-detail-wide">
                    <span>Residential Address</span>
                    <strong>{{ $otherStaff->address ?: '—' }}</strong>
                </div>

            </div>
        </div>

        {{-- EMPLOYMENT INFORMATION --}}
        <div class="profile-section">

            <div class="profile-section-heading">
                <div>
                    <span class="profile-eyebrow">ORGANISATION DETAILS</span>
                    <h3>Employment Information</h3>
                </div>
            </div>

            <div class="profile-details-grid">

                <div>
                    <span>Designation</span>
                    <strong>{{ $otherStaff->designation ?: '—' }}</strong>
                </div>

                <div>
                    <span>Department</span>
                    <strong>{{ $otherStaff->department ?: '—' }}</strong>
                </div>

                <div>
                    <span>Qualification</span>
                    <strong>{{ $otherStaff->qualification ?: '—' }}</strong>
                </div>

                <div>
                    <span>Joining Date</span>
                    <strong>{{ $otherStaff->joining_date?->format('d M Y') ?? '—' }}</strong>
                </div>

                <div>
                    <span>Employment Status</span>
                    <strong>{{ $otherStaff->status ? ucfirst($otherStaff->status) : '—' }}</strong>
                </div>

            </div>
        </div>

        {{-- DRIVER-ONLY TRANSPORT INFORMATION --}}
        @if($isDriver)

            {{-- LICENCE DETAILS --}}
            <div class="profile-section">

                <div class="profile-section-heading">
                    <div>
                        <span class="profile-eyebrow">TRANSPORT STAFF</span>
                        <h3>Driver Licence Details</h3>
                    </div>
                </div>

                <div class="profile-details-grid">

                    <div>
                        <span>Licence Number</span>
                        <strong>{{ $otherStaff->license_number ?: '—' }}</strong>
                    </div>

                    <div>
                        <span>Licence Expiry</span>
                        <strong>{{ $otherStaff->license_expiry?->format('d M Y') ?? '—' }}</strong>
                    </div>

                </div>
            </div>

            {{-- ASSIGNED VEHICLES --}}
            <div class="profile-section">

                <div class="profile-section-heading">
                    <div>
                        <span class="profile-eyebrow">TRANSPORT ASSIGNMENT</span>
                        <h3>Assigned Vehicles</h3>
                    </div>

                    <span class="profile-section-note">
                        {{ $assignedVehicles->count() }} vehicle(s)
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table profile-table">
                        <thead>
                            <tr>
                                <th>Vehicle Number</th>
                                <th>Type</th>
                                <th>Model</th>
                                <th>Capacity</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($assignedVehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->vehicle_number ?: '—' }}</td>
                                    <td>{{ $vehicle->vehicle_type ?: '—' }}</td>
                                    <td>{{ $vehicle->vehicle_model ?: '—' }}</td>
                                    <td>{{ $vehicle->capacity ?? '—' }}</td>
                                    <td>{{ $vehicle->status ? ucfirst($vehicle->status) : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="profile-empty">
                                        No vehicles assigned to this driver.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ASSIGNED ROUTES --}}
            <div class="profile-section">

                <div class="profile-section-heading">
                    <div>
                        <span class="profile-eyebrow">TRANSPORT ASSIGNMENT</span>
                        <h3>Assigned Transport Routes</h3>
                    </div>

                    <span class="profile-section-note">
                        {{ $assignedRoutes->count() }} route(s)
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table profile-table">
                        <thead>
                            <tr>
                                <th>Route Number</th>
                                <th>Route Name</th>
                                <th>Starting Point</th>
                                <th>Destination</th>
                                <th>Vehicle</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($assignedRoutes as $route)
                                <tr>
                                    <td>{{ $route->route_number ?: '—' }}</td>
                                    <td>{{ $route->route_name ?: '—' }}</td>
                                    <td>{{ $route->starting_point ?: '—' }}</td>
                                    <td>{{ $route->destination ?: '—' }}</td>
                                    <td>
                                        {{ $route->vehicle?->vehicle_number ?? $route->assigned_vehicle ?? '—' }}
                                    </td>
                                    <td>{{ $route->status ? ucfirst($route->status) : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="profile-empty">
                                        No transport routes assigned to this driver.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @endif

        {{-- FOOTER --}}
        <div class="profile-footer">
            <span>
                {{ $schoolSetting->school_name ?? config('app.name') }}
                · Staff Profile Report
            </span>

            <span>Generated on {{ now()->format('d M Y') }}</span>
        </div>

    </div>
</div>

<style>
.staff-profile-report-page {
    background: #f5f4f0;
    color: #1e2a25;
    min-height: calc(100vh - 100px);
    padding: 1px 0 24px;
    font-family: 'Public Sans', 'Inter', sans-serif;
}

.staff-profile-report-page .profile-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.staff-profile-report-page .profile-back {
    color: #2c3e36;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
}

.staff-profile-report-page .profile-back:hover {
    color: #527f70;
}

.staff-profile-report-page .profile-action {
    display: inline-block;
    border: 0;
    border-radius: 9px;
    padding: 11px 17px;
    background: #2c3e36;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s ease;
}

.staff-profile-report-page .profile-action:hover {
    background: #3a4f45;
    color: #fff;
}

.staff-profile-report-page .profile-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    padding: 28px;
    border: 1px solid #d4cfc2;
    border-radius: 18px;
    background: linear-gradient(110deg, #fdfbf7 0%, #f8f1e3 58%, #e8dcc6 100%);
}

.staff-profile-report-page .profile-hero-main {
    display: flex;
    align-items: center;
    gap: 20px;
    min-width: 0;
}

.staff-profile-report-page .profile-avatar {
    width: 105px;
    height: 105px;
    flex: 0 0 105px;
    display: grid;
    place-items: center;
    overflow: hidden;
    border: 3px solid #fff;
    border-radius: 22px;
    background: #f3ead8;
    color: #2c3e36;
    font-family: Georgia, serif;
    font-size: 40px;
    font-weight: 700;
}

.staff-profile-report-page .profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.staff-profile-report-page .profile-avatar-fallback {
    width: 100%;
    height: 100%;
    place-items: center;
    font-family: Georgia, serif;
    font-size: 40px;
    font-weight: 700;
}

.staff-profile-report-page .profile-eyebrow {
    color: #5c6b64;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

.staff-profile-report-page .profile-hero h2 {
    margin: 7px 0;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: clamp(23px, 3vw, 32px);
    font-weight: 700;
}

.staff-profile-report-page .profile-hero p {
    margin: 0 0 9px;
    color: #5c6b64;
    font-size: 13px;
}

.staff-profile-report-page .profile-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    background: #f3ead8;
    color: #2c3e36;
    font-size: 11px;
    font-weight: 800;
}

.staff-profile-report-page .profile-hero-meta {
    display: grid;
    gap: 13px;
    min-width: 175px;
}

.staff-profile-report-page .profile-hero-meta div,
.staff-profile-report-page .profile-details-grid div {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

.staff-profile-report-page .profile-hero-meta span,
.staff-profile-report-page .profile-details-grid span {
    color: #7b857b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
}

.staff-profile-report-page .profile-hero-meta strong,
.staff-profile-report-page .profile-details-grid strong {
    color: #1e2a25;
    font-size: 13px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.staff-profile-report-page .profile-section {
    margin-bottom: 20px;
    padding: 23px;
    border: 1px solid #d4cfc2;
    border-radius: 15px;
    background: #fff;
}

.staff-profile-report-page .profile-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.staff-profile-report-page .profile-section-heading h3 {
    margin: 5px 0 0;
    color: #1e2a25;
    font-family: Georgia, serif;
    font-size: 21px;
    font-weight: 700;
}

.staff-profile-report-page .profile-section-note {
    color: #7b857b;
    font-size: 12px;
    font-weight: 700;
}

.staff-profile-report-page .profile-details-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 19px 22px;
}

.staff-profile-report-page .profile-detail-wide {
    grid-column: 1 / -1;
}

.staff-profile-report-page .profile-table {
    margin: 12px 0 0;
    vertical-align: middle;
    min-width: 650px;
}

.staff-profile-report-page .profile-table thead th {
    padding: 12px;
    border-bottom: 1px solid #d4cfc2;
    background: #f5f4f0;
    color: #39483e;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    white-space: nowrap;
}

.staff-profile-report-page .profile-table tbody td {
    padding: 12px;
    border-bottom: 1px solid #eeeae1;
    color: #39483e;
    font-size: 12px;
}

.staff-profile-report-page .profile-empty {
    padding: 20px !important;
    background: #f5f4f0;
    color: #7b857b !important;
    font-size: 13px !important;
    text-align: center;
}

.staff-profile-report-page .profile-footer {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 5px 3px 0;
    color: #7b857b;
    font-size: 11px;
}

.staff-profile-report-page .profile-print-header {
    display: none;
}

@media(max-width: 767px) {
    .staff-profile-report-page .profile-toolbar,
    .staff-profile-report-page .profile-hero {
        align-items: flex-start;
        flex-direction: column;
    }

    .staff-profile-report-page .profile-hero-main {
        align-items: flex-start;
    }

    .staff-profile-report-page .profile-hero-meta {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        width: 100%;
    }

    .staff-profile-report-page .profile-details-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .staff-profile-report-page .profile-section {
        padding: 17px;
    }

    .staff-profile-report-page .profile-footer {
        flex-direction: column;
    }
}

@media(max-width: 480px) {
    .staff-profile-report-page .profile-hero-main {
        flex-direction: column;
    }

    .staff-profile-report-page .profile-details-grid {
        grid-template-columns: 1fr;
    }

    .staff-profile-report-page .profile-detail-wide {
        grid-column: auto;
    }
}

@media print {
    @page {
        size: A4 portrait;
        margin: 12mm 12mm 14mm;
    }

    html,
    body {
        background: #fff !important;
        color: #1f2d26 !important;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif !important;
        font-size: 9px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden !important;
    }

    .staff-profile-report-page,
    .staff-profile-report-page * {
        visibility: visible !important;
    }

    .staff-profile-report-page {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #fff !important;
        color: #1f2d26 !important;
        font-family: "DejaVu Sans", Arial, Helvetica, sans-serif !important;
        font-size: 9px !important;
    }

    .staff-profile-report-page .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .staff-profile-report-page .profile-toolbar,
    .staff-profile-report-page .profile-footer {
        display: none !important;
    }

    .staff-profile-report-page .profile-print-header {
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        gap: 0 !important;
        padding: 0 0 14px !important;
        margin: 0 0 16px !important;
        border-bottom: 2.5px solid #2c3e36 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .staff-profile-report-page .profile-print-logo-wrap {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        margin: 0 0 8px !important;
    }

    .staff-profile-report-page .profile-print-header img {
        display: block !important;
        width: 72px !important;
        height: 72px !important;
        object-fit: contain !important;
        margin: 0 auto !important;
    }

    .staff-profile-report-page .profile-print-school {
        width: 100% !important;
        text-align: center !important;
    }

    .staff-profile-report-page .profile-print-school h1 {
        margin: 0 0 4px !important;
        color: #1a2e25 !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 20px !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
    }

    .staff-profile-report-page .profile-print-school h2 {
        margin: 8px 0 0 !important;
        padding-top: 7px !important;
        border-top: 1px solid #c5cdc6 !important;
        color: #2c3e36 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: 1.2px !important;
        text-transform: uppercase !important;
    }

    .staff-profile-report-page .profile-print-school p {
        margin: 2px 0 !important;
        color: #5a6b62 !important;
        font-size: 8px !important;
        line-height: 1.45 !important;
    }

    .staff-profile-report-page .profile-hero {
        display: block !important;
        padding: 12px !important;
        margin: 0 0 15px !important;
        border: 1px solid #d4cfc2 !important;
        border-radius: 0 !important;
        background: #f3f0e8 !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .staff-profile-report-page .profile-hero-main {
        display: block !important;
    }

    .staff-profile-report-page .profile-avatar,
    .staff-profile-report-page .profile-eyebrow,
    .staff-profile-report-page .profile-status {
        display: none !important;
    }

    .staff-profile-report-page .profile-hero h2 {
        margin: 0 0 5px !important;
        font-size: 16px !important;
        font-family: "DejaVu Sans", Arial, sans-serif !important;
        color: #20352c !important;
    }

    .staff-profile-report-page .profile-hero p {
        margin: 3px 0 !important;
        font-size: 9px !important;
        color: #26352e !important;
    }

    .staff-profile-report-page .profile-hero-meta {
        display: block !important;
        margin-top: 5px !important;
        min-width: 0 !important;
    }

    .staff-profile-report-page .profile-hero-meta div {
        display: inline !important;
        margin: 0 !important;
    }

    .staff-profile-report-page .profile-hero-meta span {
        display: none !important;
    }

    .staff-profile-report-page .profile-hero-meta strong {
        display: inline !important;
        font-size: 9px !important;
        font-weight: 400 !important;
    }

    .staff-profile-report-page .profile-hero-meta div:not(:last-child) strong::after {
        content: " | ";
        color: #68756e !important;
    }

    .staff-profile-report-page .profile-section {
        display: block !important;
        width: 100% !important;
        margin: 0 0 12px !important;
        padding: 0 !important;
        border: 1px solid #cfd6d0 !important;
        border-radius: 4px !important;
        background: #fff !important;
        box-shadow: none !important;
        break-inside: auto !important;
        page-break-inside: auto !important;
        overflow: hidden !important;
    }

    .staff-profile-report-page .profile-section-heading {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 8px 12px !important;
        background: #2c3e36 !important;
        border-bottom: 2px solid #b89b5e !important;
        color: #fff !important;
        break-after: avoid !important;
        page-break-after: avoid !important;
    }

    .staff-profile-report-page .profile-section-heading h3 {
        margin: 0 !important;
        color: #fff !important;
        font-family: Georgia, "Times New Roman", serif !important;
        font-size: 11px !important;
    }

    .staff-profile-report-page .profile-section-heading span,
    .staff-profile-report-page .profile-section-heading .profile-eyebrow,
    .staff-profile-report-page .profile-section-note {
        color: #f0ebe0 !important;
        font-size: 7.5px !important;
    }

    .staff-profile-report-page .profile-details-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 0 !important;
        padding: 0 !important;
    }

    .staff-profile-report-page .profile-details-grid > div {
        display: block !important;
        min-width: 0 !important;
        padding: 7px 10px !important;
        border: 0 !important;
        border-right: 1px solid #e0e6e1 !important;
        border-bottom: 1px solid #e0e6e1 !important;
        background: #fff !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .staff-profile-report-page .profile-details-grid > div:nth-child(even) {
        background: #f6f8f5 !important;
    }

    .staff-profile-report-page .profile-details-grid span {
        display: block !important;
        margin: 0 0 3px !important;
        color: #5f6f66 !important;
        font-size: 7px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .staff-profile-report-page .profile-details-grid strong {
        display: block !important;
        color: #1f2d26 !important;
        font-size: 9px !important;
        font-weight: 600 !important;
        line-height: 1.4 !important;
        overflow-wrap: anywhere !important;
    }

    .staff-profile-report-page .profile-detail-wide {
        grid-column: 1 / -1 !important;
    }

    .staff-profile-report-page .table-responsive {
        overflow: visible !important;
    }

    .staff-profile-report-page .profile-table {
        width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        border-collapse: collapse !important;
        font-size: 8px !important;
    }

    .staff-profile-report-page .profile-table thead {
        display: table-header-group !important;
    }

    .staff-profile-report-page .profile-table tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .staff-profile-report-page .profile-table th,
    .staff-profile-report-page .profile-table td {
        padding: 6px 8px !important;
        border: 1px solid #d0d8d1 !important;
        color: #1f2d26 !important;
        font-size: 8px !important;
        line-height: 1.4 !important;
        text-align: left !important;
        vertical-align: top !important;
    }

    .staff-profile-report-page .profile-table thead th {
        background: #e4ebe5 !important;
        font-size: 7.5px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
    }

    .staff-profile-report-page .profile-empty {
        padding: 7px 10px !important;
        color: #5f6f66 !important;
        font-size: 8px !important;
    }

    .staff-profile-report-page .profile-section,
    .staff-profile-report-page .profile-table th,
    .staff-profile-report-page .profile-table td,
    .staff-profile-report-page .profile-hero {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .staff-profile-report-page a {
        color: #1f2d26 !important;
        text-decoration: none !important;
    }
}
</style>
@endsection
