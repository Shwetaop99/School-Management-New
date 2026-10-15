
@extends('layouts.app')

@section('title', 'Transport Vehicles | Admin')
@section('page-title', 'Transport Vehicles')

@section('content')

<div class="transport-vehicles-page">
    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="vehicle-alert" role="status">
                <span class="vehicle-alert-icon">
                    <i class="bi bi-check-lg"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="vehicles-hero">

            <div class="vehicles-hero-content">
                <div class="vehicles-hero-icon">
                    <i class="bi bi-bus-front-fill"></i>
                </div>

                <div class="vehicles-hero-text">
                    <h2>Transport Vehicles</h2>

                    <p>
                        Manage school transport vehicles, driver details,
                        seating capacity and vehicle availability from one place.
                    </p>

                    <div class="vehicles-hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>School Transport &amp; Fleet Management</span>
                    </div>
                </div>
            </div>

            <div class="vehicles-hero-actions">
                <div class="hero-handwritten">
                    Safe. Reliable. Connected.
                </div>

                <p>
                    Keep every school journey organised with reliable vehicles
                    and well-managed driver information.
                </p>

                <a href="{{ route('admin.transport.vehicles.create') }}"
                   class="diary-primary-btn">
                    <i class="bi bi-plus-lg"></i>
                    Add Vehicle
                </a>
            </div>

        </section>

        {{-- KPI CARDS --}}
        <section class="vehicles-summary-grid">

            <article class="vehicles-summary-card blue">
                <div class="summary-top">
                    <span class="summary-label">Total Vehicles</span>
                    <span class="summary-icon">
                        <i class="bi bi-bus-front-fill"></i>
                    </span>
                </div>

                <div class="summary-number">{{ $totalVehicles }}</div>

                <div class="summary-footer">
                    All registered vehicles
                </div>
            </article>

            <article class="vehicles-summary-card green">
                <div class="summary-top">
                    <span class="summary-label">Active Vehicles</span>
                    <span class="summary-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>

                <div class="summary-number">{{ $activeVehicles }}</div>

                <div class="summary-footer">
                    Currently active vehicles
                </div>
            </article>

            <article class="vehicles-summary-card orange">
                <div class="summary-top">
                    <span class="summary-label">Maintenance</span>
                    <span class="summary-icon">
                        <i class="bi bi-tools"></i>
                    </span>
                </div>

                <div class="summary-number">{{ $maintenanceVehicles }}</div>

                <div class="summary-footer">
                    Vehicles under maintenance
                </div>
            </article>

            <article class="vehicles-summary-card red">
                <div class="summary-top">
                    <span class="summary-label">Inactive Vehicles</span>
                    <span class="summary-icon">
                        <i class="bi bi-x-circle-fill"></i>
                    </span>
                </div>

                <div class="summary-number">{{ $inactiveVehicles }}</div>

                <div class="summary-footer">
                    Currently unavailable
                </div>
            </article>

        </section>

        {{-- MAIN PAPER PANEL --}}
        <section class="vehicles-section">

            {{-- PANEL HEADER --}}
            <div class="vehicles-section-header">

                <div class="vehicles-heading">
                    <div class="vehicles-heading-icon">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>

                    <div>
                        <h3>Vehicle List</h3>
                        <p>
                            View and manage all registered school vehicles.
                        </p>
                    </div>
                </div>

                <div class="vehicles-header-actions">

                    <div class="vehicle-count">
                        <i class="bi bi-folder2-open"></i>
                        <strong>{{ number_format($vehicles->total()) }}</strong>
                        Vehicles
                    </div>

                    <a href="{{ route('admin.transport.vehicles.create') }}"
                       class="diary-primary-btn">
                        <i class="bi bi-plus-lg"></i>
                        Add Vehicle
                    </a>

                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.transport.vehicles.index') }}"
                  class="vehicles-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="bi bi-funnel"></i>
                    </div>

                    <div>
                        <h4>Search &amp; Filter Vehicles</h4>
                        <p>
                            Find vehicles by registration number, type or status.
                        </p>
                    </div>
                </div>

                <div class="vehicles-filter-grid">

                    {{-- SEARCH --}}
                    <div class="diary-filter-group search-group">
                        <label for="vehicle-search">Search Vehicles</label>

                        <div class="diary-search-input">
                            <i class="bi bi-search"></i>

                            <input
                                id="vehicle-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Vehicle number, type or driver..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    {{-- VEHICLE TYPE --}}
                    <div class="diary-filter-group">
                        <label for="vehicle-type">Vehicle Type</label>

                        <select id="vehicle-type"
                                name="vehicle_type"
                                class="diary-control">

                            <option value="">All Vehicle Types</option>

                            <option value="Bus"
                                @selected(request('vehicle_type') == 'Bus')>
                                Bus
                            </option>

                            <option value="Van"
                                @selected(request('vehicle_type') == 'Van')>
                                Van
                            </option>

                            <option value="Mini Bus"
                                @selected(request('vehicle_type') == 'Mini Bus')>
                                Mini Bus
                            </option>

                            <option value="Other"
                                @selected(request('vehicle_type') == 'Other')>
                                Other
                            </option>

                        </select>
                    </div>

                    {{-- STATUS --}}
                    <div class="diary-filter-group">
                        <label for="vehicle-status">Vehicle Status</label>

                        <select id="vehicle-status"
                                name="status"
                                class="diary-control">

                            <option value="">All Statuses</option>

                            <option value="active"
                                @selected(request('status') == 'active')>
                                Active
                            </option>

                            <option value="maintenance"
                                @selected(request('status') == 'maintenance')>
                                Maintenance
                            </option>

                            <option value="inactive"
                                @selected(request('status') == 'inactive')>
                                Inactive
                            </option>

                        </select>
                    </div>

                    {{-- FILTER ACTIONS --}}
                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="bi bi-funnel-fill"></i>
                            Apply
                        </button>

                        <a href="{{ route('admin.transport.vehicles.index') }}"
                           class="diary-reset-btn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request()->hasAny(['search', 'vehicle_type', 'status']))
                <div class="vehicles-active-filters">

                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="bi bi-sliders"></i>
                            Active filters
                        </span>

                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="bi bi-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request('vehicle_type'))
                            <span class="filter-chip">
                                <i class="bi bi-truck-front"></i>
                                {{ request('vehicle_type') }}
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="bi bi-circle-half"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ $vehicles->total() }} result(s)
                    </span>

                </div>
            @endif

            {{-- VEHICLE TABLE --}}
            <div class="vehicles-table-wrap">

                <table class="vehicles-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Driver</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($vehicles as $vehicle)

                            @php
                                $vehicleStatus = strtolower(
                                    (string) ($vehicle->status ?? 'inactive')
                                );

                                $vehicleStatusClass = in_array(
                                    $vehicleStatus,
                                    ['active', 'maintenance', 'inactive'],
                                    true
                                ) ? $vehicleStatus : 'inactive';
                            @endphp

                            <tr>

                                {{-- ROW NUMBER --}}
                                <td>
                                    <span class="vehicle-row-number">
                                        {{ $vehicles->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                {{-- VEHICLE INFORMATION --}}
                                <td>
                                    <div class="vehicle-info">

                                        <div class="vehicle-avatar">
                                            <i class="bi bi-bus-front-fill"></i>
                                        </div>

                                        <div class="vehicle-details">
                                            <div class="vehicle-title">
                                                {{ $vehicle->vehicle_number }}
                                            </div>

                                            <div class="vehicle-subtitle">
                                                <i class="bi bi-hash"></i>
                                                Vehicle ID: {{ $vehicle->id }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- VEHICLE TYPE --}}
                                <td>
                                    @if($vehicle->vehicle_type)
                                        <span class="vehicle-type-badge">
                                            <i class="bi bi-truck-front-fill"></i>
                                            {{ $vehicle->vehicle_type }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                {{-- CAPACITY --}}
                                <td>
                                    @if($vehicle->capacity !== null)
                                        <span class="vehicle-capacity">
                                            <i class="bi bi-people-fill"></i>
                                            {{ $vehicle->capacity }}
                                            <small>seats</small>
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                {{-- DRIVER --}}
                                <td>
                                    <div class="vehicle-driver">

                                        @if($vehicle->driver_name)

                                            <strong>{{ $vehicle->driver_name }}</strong>

                                            @if($vehicle->driver_license_number)
                                                <small>
                                                    <i class="bi bi-card-text"></i>
                                                    License:
                                                    {{ $vehicle->driver_license_number }}
                                                </small>
                                            @endif

                                        @else
                                            <span class="diary-muted">
                                                Not Assigned
                                            </span>
                                        @endif

                                    </div>
                                </td>

                                {{-- CONTACT --}}
                                <td>
                                    @if($vehicle->driver_contact)
                                        <span class="vehicle-contact">
                                            <i class="bi bi-telephone-fill"></i>
                                            {{ $vehicle->driver_contact }}
                                        </span>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="vehicle-status {{ $vehicleStatusClass }}">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($vehicleStatus) }}
                                    </span>
                                </td>

                                {{-- ACTIONS --}}
                                <td>
                                    <div class="vehicle-actions">

                                        <a
                                            href="{{ route('admin.transport.vehicles.show', ['transportVehicle' => $vehicle->id]) }}"
                                            class="vehicle-action view"
                                            title="View Vehicle"
                                            aria-label="View vehicle"
                                        >
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.transport.vehicles.edit', ['transportVehicle' => $vehicle->id]) }}"
                                            class="vehicle-action edit"
                                            title="Edit Vehicle"
                                            aria-label="Edit vehicle"
                                        >
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.transport.vehicles.destroy', ['transportVehicle' => $vehicle->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this vehicle?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="vehicle-action delete"
                                                title="Delete Vehicle"
                                                aria-label="Delete vehicle"
                                            >
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            {{-- EMPTY STATE --}}
                            <tr>
                                <td colspan="8">
                                    <div class="vehicle-empty">

                                        <div class="vehicle-empty-icon">
                                            <i class="bi bi-bus-front"></i>
                                        </div>

                                        <h4>No Vehicles Found</h4>

                                        <p>
                                            No transport vehicles match your current
                                            search or filter criteria.
                                        </p>

                                        <a
                                            href="{{ route('admin.transport.vehicles.create') }}"
                                            class="diary-primary-btn"
                                        >
                                            <i class="bi bi-plus-lg"></i>
                                            Add First Vehicle
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>

            {{-- PAGINATION --}}
            @if($vehicles->hasPages())
                <div class="vehicles-pagination">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $vehicles->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $vehicles->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $vehicles->total() }}</strong>
                        vehicles
                    </div>

                    <div class="pagination-links">
                        {{ $vehicles->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>

                </div>
            @endif

            {{-- PANEL FOOTER --}}
            @if($vehicles->total() > 0)
                <div class="vehicles-card-footer">

                    <div>
                        Showing
                        <strong>{{ $vehicles->count() }}</strong>
                        vehicle(s) on this page
                    </div>

                    <div class="footer-status">
                        <i class="bi bi-check-circle-fill"></i>
                        Vehicle data loaded successfully
                    </div>

                </div>
            @endif

        </section>

    </div>
</div>

<style>
    
/* =========================================================
   TRANSPORT VEHICLES — LOCKED SCHOOL DIARY DESIGN SYSTEM
   Reference: Transport Records page
   Preserve existing Blade markup and business logic
========================================================= */

.transport-vehicles-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.transport-vehicles-page *,
.transport-vehicles-page *::before,
.transport-vehicles-page *::after {
    box-sizing: border-box;
}

.transport-vehicles-page a,
.transport-vehicles-page button {
    transition:
        color .2s ease,
        background .2s ease,
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}

.transport-vehicles-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* =========================================================
   HERO
========================================================= */

.vehicles-hero,
.vehicles-section {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

.vehicles-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.vehicles-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.vehicles-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.vehicles-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.vehicles-hero-text {
    min-width: 0;
}

.vehicles-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.vehicles-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.vehicles-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.vehicles-hero-note i {
    font-size: 13px;
}

.vehicles-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.vehicles-hero-actions::before,
.vehicles-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.vehicles-hero-actions::before {
    top: -115px;
    right: -60px;
}

.vehicles-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.vehicles-hero-actions > * {
    position: relative;
    z-index: 1;
}

.vehicles-hero-actions .hero-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.hero-handwritten {
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.vehicles-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* =========================================================
   PRIMARY BUTTONS
========================================================= */

.diary-primary-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.vehicles-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.vehicles-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* =========================================================
   SUCCESS ALERT
========================================================= */

.vehicle-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    font-weight: 700;
}

.vehicle-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    color: #347849;
    background: #deeedb;
}

/* =========================================================
   KPI CARDS
========================================================= */

.vehicles-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.vehicles-summary-card {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    margin-top: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
    transition: transform .2s ease, box-shadow .2s ease;
}

.vehicles-summary-card::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.vehicles-summary-card.blue {
    --kpi-color: #426b8a;
}

.vehicles-summary-card.green {
    --kpi-color: #43835d;
}

.vehicles-summary-card.orange {
    --kpi-color: #a87543;
}

.vehicles-summary-card.red {
    --kpi-color: #b95b50;
}

.vehicles-summary-card.blue::before {
    content: "FLEET";
}

.vehicles-summary-card.green::before {
    content: "AVAILABLE";
}

.vehicles-summary-card.orange::before {
    content: "SERVICE";
}

.vehicles-summary-card.red::before {
    content: "UNAVAILABLE";
}

.vehicles-summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.summary-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.summary-number {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.summary-footer {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* =========================================================
   MAIN PAPER PANEL
========================================================= */

.vehicles-section {
    overflow: hidden;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* SECTION HEADER */

.vehicles-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: #fffefa;
}

.vehicles-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.vehicles-heading-icon,
.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.vehicles-heading h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.vehicles-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.vehicles-header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
}

.vehicle-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.vehicle-count i,
.vehicle-count strong {
    color: var(--ink);
}

/* =========================================================
   FILTER PANEL
========================================================= */

.vehicles-filter-panel {
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.vehicles-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(220px, 2fr)
        minmax(160px, .9fr)
        minmax(160px, .9fr)
        auto;
    align-items: end;
    gap: 16px;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.diary-control,
.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.diary-control {
    padding: 0 12px;
    cursor: pointer;
}

.diary-control:hover,
.diary-search-input:hover {
    border-color: #bdcabb;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #829083;
    font-size: 13px;
}

.diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font: inherit;
}

.diary-search-input input::placeholder {
    color: #9a9f93;
}

.diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.diary-apply-btn,
.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-apply-btn {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.diary-apply-btn:hover {
    transform: translateY(-1px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 4px 10px rgba(38, 59, 53, .12);
}

.diary-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: #fffefa;
}

.diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* =========================================================
   ACTIVE FILTERS
========================================================= */

.vehicles-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
    background: #fffefa;
}

.active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.active-filter-label i {
    color: var(--green);
}

.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 260px;
    padding: 6px 10px;
    overflow-wrap: anywhere;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 700;
}

.filter-chip i {
    color: var(--amber);
}

.filter-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* =========================================================
   TABLE
========================================================= */

.vehicles-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.vehicles-table {
    width: 100%;
    min-width: 1100px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.vehicles-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.vehicles-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.vehicles-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.vehicles-table tbody tr:hover {
    background: #f8f8f1;
}

.vehicles-table tbody tr:last-child td {
    border-bottom: none;
}

.vehicles-table .actions-heading {
    text-align: right;
}

/* ROW NUMBER */

.vehicle-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #e3e1d6;
    border-radius: 9px;
    color: #68776a;
    background: #f5f4ec;
    font-size: 10px;
    font-weight: 800;
}

/* VEHICLE INFORMATION */

.vehicle-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.vehicle-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 14px;
    font-weight: 800;
}

.vehicle-details {
    min-width: 0;
}

.vehicle-title {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.vehicle-subtitle {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.vehicle-subtitle i {
    color: var(--amber);
}

/* VEHICLE TYPE */

.vehicle-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 10px;
    border: 1px solid #dce3d8;
    border-radius: 7px;
    color: #527f70;
    background: #edf3e9;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.vehicle-type-badge i {
    font-size: 10px;
}

/* CAPACITY */

.vehicle-capacity {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #47584e;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.vehicle-capacity i {
    color: #527f70;
}

.vehicle-capacity small {
    color: #929a8e;
    font-size: 10px;
    font-weight: 500;
}

/* DRIVER */

.vehicle-driver strong {
    display: block;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
}

.vehicle-driver small {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #858e82;
    font-size: 10px;
    white-space: nowrap;
}

.vehicle-driver small i {
    color: var(--amber);
}

/* CONTACT */

.vehicle-contact {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #47584e;
    font-size: 10px;
    white-space: nowrap;
}

.vehicle-contact i {
    color: var(--blue);
    font-size: 11px;
}

/* =========================================================
   VEHICLE STATUS
========================================================= */

.vehicle-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.vehicle-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.vehicle-status.active .status-dot {
    background: #43835d;
}

.vehicle-status.maintenance {
    border-color: #eadcc4;
    color: #91642f;
    background: #faf2e5;
}

.vehicle-status.maintenance .status-dot {
    background: #a87543;
}

.vehicle-status.inactive {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.vehicle-status.inactive .status-dot {
    background: #b95b50;
}

/* =========================================================
   ACTION BUTTONS
========================================================= */

.vehicle-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.vehicle-actions form {
    display: inline-flex;
    margin: 0;
}

.vehicle-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e2e1d7;
    border-radius: 9px;
    color: #657369;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.vehicle-action:hover {
    transform: translateY(-2px);
}

.vehicle-action.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.vehicle-action.edit:hover {
    border-color: #ead7b9;
    color: #a87543;
    background: #faf3e6;
}

.vehicle-action.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* =========================================================
   EMPTY STATE
========================================================= */

.vehicle-empty {
    padding: 65px 20px;
    text-align: center;
}

.vehicle-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.vehicle-empty h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.vehicle-empty p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* =========================================================
   PAGINATION
========================================================= */

.vehicles-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
    background: #fffefa;
}

.pagination-info {
    color: #7b857b;
    font-size: 10px;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.pagination-links {
    min-width: 0;
}

.pagination-links nav {
    margin: 0;
}

.pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.vehicles-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--line);
    border-radius: 8px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
    box-shadow: none;
}

.vehicles-pagination .page-link:hover {
    border-color: #b5c5b0;
    color: #43835d;
    background: #f2f4e9;
}

.vehicles-pagination .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.vehicles-pagination .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* =========================================================
   PANEL FOOTER
========================================================= */

.vehicles-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.vehicles-card-footer strong {
    color: var(--ink);
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.footer-status i {
    color: #43835d;
}

/* =========================================================
   TABLET
========================================================= */

@media (max-width: 1200px) {
    .vehicles-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .vehicles-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .vehicles-hero {
        grid-template-columns: 1fr;
    }

    .vehicles-hero-actions {
        min-height: 220px;
    }

    .vehicles-section-header {
        align-items: flex-start;
    }

    .vehicles-header-actions {
        justify-content: flex-end;
    }
}

/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 650px) {
    .transport-vehicles-page .container-fluid {
        padding: 18px 14px !important;
    }

    .vehicles-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .vehicles-hero::before {
        left: 18px;
    }

    .vehicles-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .vehicles-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .vehicles-hero-text h2 {
        font-size: 24px;
    }

    .vehicles-hero-text p {
        font-size: 11px;
    }

    .vehicles-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .vehicles-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .vehicles-summary-grid {
        gap: 15px;
        margin-top: 31px;
    }

    .vehicles-summary-card {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .summary-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .summary-number {
        font-size: 29px;
    }

    .summary-footer {
        font-size: 9px;
    }

    .vehicles-section-header {
        align-items: flex-start;
        flex-direction: column;
        padding: 16px;
    }

    .vehicles-heading {
        gap: 10px;
    }

    .vehicles-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .vehicles-heading h3 {
        font-size: 16px;
    }

    .vehicles-heading p {
        font-size: 10px;
    }

    .vehicles-header-actions {
        width: 100%;
        justify-content: space-between;
    }

    .vehicle-count {
        font-size: 9px;
    }

    .vehicles-filter-panel {
        padding: 17px 15px;
    }

    .filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .filter-heading h4 {
        font-size: 16px;
    }

    .vehicles-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions {
        grid-column: auto;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .vehicles-active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }

    .filter-result-count {
        white-space: normal;
    }

    .vehicles-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .vehicles-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .vehicle-empty {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .vehicles-summary-grid {
        grid-template-columns: 1fr;
    }

    .vehicles-header-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .vehicle-count {
        align-self: flex-start;
    }

    .vehicles-filter-grid {
        grid-template-columns: 1fr;
    }

    .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .diary-apply-btn,
    .diary-reset-btn {
        width: 100%;
    }

    .active-filter-items {
        align-items: flex-start;
    }

    .filter-chip {
        max-width: 100%;
    }
}

/* =========================================================
   ACCESSIBILITY
========================================================= */

.transport-vehicles-page a:focus-visible,
.transport-vehicles-page button:focus-visible,
.transport-vehicles-page input:focus-visible,
.transport-vehicles-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .transport-vehicles-page *,
    .transport-vehicles-page *::before,
    .transport-vehicles-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

</style>

@endsection
