@extends('layouts.app')

@section('title', 'Transport Routes | Admin')
@section('page-title', 'Transport Routes')

@section('content')

<div class="transport-routes-page">

    <div class="container-fluid py-4">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="transport-alert" role="status">
                <span class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </span>
                <span>{{ session('success') }}</span>

                <button type="button"
                        class="transport-alert-close"
                        aria-label="Dismiss success message"
                        onclick="this.closest('.transport-alert').remove();">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="transport-hero">

            <div class="hero-content">
                <div class="hero-icon-wrap">
                    <i class="bi bi-signpost-2-fill"></i>
                </div>

                <div class="hero-text">
                    <h2>Transport Routes</h2>

                    <p>
                        Manage school transport routes, stops, assigned
                        vehicles, drivers and route status from one place.
                    </p>

                    <div class="hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>School Transport Management</span>
                    </div>
                </div>
            </div>

            <div class="hero-actions">
                <div class="hero-handwritten">
                    Every Mile Matters..!
                </div>

                <p>
                    Organise routes, coordinate drivers and keep
                    school transportation running smoothly.
                </p>

                <a href="{{ route('admin.transport.routes.create') }}"
                   class="diary-primary-btn">
                    <i class="bi bi-plus-lg"></i>
                    Add Transport Route
                </a>
            </div>

        </section>

        {{-- KPI CARDS --}}
        <section class="transport-summary-grid">

            <article class="summary-card blue">
                <div class="summary-top">
                    <span class="summary-label">Total Routes</span>
                    <span class="summary-icon">
                        <i class="bi bi-signpost-2-fill"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($totalRoutes) }}
                </div>

                <div class="summary-footer">
                    All registered transport routes
                </div>
            </article>

            <article class="summary-card green">
                <div class="summary-top">
                    <span class="summary-label">Active Routes</span>
                    <span class="summary-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($activeRoutes) }}
                </div>

                <div class="summary-footer">
                    Currently active routes
                </div>
            </article>

            <article class="summary-card orange">
                <div class="summary-top">
                    <span class="summary-label">Inactive Routes</span>
                    <span class="summary-icon">
                        <i class="bi bi-pause-circle-fill"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($inactiveRoutes) }}
                </div>

                <div class="summary-footer">
                    Currently inactive routes
                </div>
            </article>

            <article class="summary-card cyan">
                <div class="summary-top">
                    <span class="summary-label">Assigned Vehicles</span>
                    <span class="summary-icon">
                        <i class="bi bi-bus-front-fill"></i>
                    </span>
                </div>

                <div class="summary-number">
                    {{ number_format($assignedVehicles) }}
                </div>

                <div class="summary-footer">
                    Routes with assigned vehicles
                </div>
            </article>

        </section>

        {{-- MAIN PAPER PANEL --}}
        <section class="routes-section">

            {{-- PANEL HEADER --}}
            <div class="routes-section-header">

                <div class="section-heading">
                    <div class="section-heading-icon">
                        <i class="bi bi-list-ul"></i>
                    </div>

                    <div>
                        <h3>Transport Route List</h3>
                        <p>
                            View and manage all school transport routes.
                        </p>
                    </div>
                </div>

                <div class="route-count">
                    <i class="bi bi-folder2-open"></i>
                    <strong>{{ number_format($routes->total()) }}</strong>
                    Records
                </div>

            </div>

            {{-- SEARCH AND FILTERS --}}
            <form action="{{ route('admin.transport.routes.index') }}"
                  method="GET"
                  class="routes-filter-bar">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="bi bi-funnel-fill"></i>
                    </div>

                    <div>
                        <h4>Search &amp; Filter Routes</h4>
                        <p>
                            Find routes by route number, vehicle,
                            driver or status.
                        </p>
                    </div>
                </div>

                <div class="filter-grid">

                    <div class="filter-group search-group">
                        <label for="route-search">Search Routes</label>

                        <div class="search-box">
                            <i class="bi bi-search"></i>

                            <input
                                id="route-search"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Route, vehicle or driver..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="route-status">Route Status</label>

                        <select id="route-status"
                                class="filter-select"
                                name="status">
                            <option value="">All Statuses</option>

                            <option value="active"
                                @selected(request('status') === 'active')>
                                Active
                            </option>

                            <option value="inactive"
                                @selected(request('status') === 'inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-funnel-fill"></i>
                            Apply
                        </button>

                        <a href="{{ route('admin.transport.routes.index') }}"
                           class="filter-reset-btn">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if(request('search') || request('status'))
                <div class="active-filters">

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

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="bi bi-circle-half"></i>
                                {{ ucfirst(request('status')) }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ number_format($routes->total()) }} result(s)
                    </span>

                </div>
            @endif

            {{-- ROUTES TABLE --}}
            <div class="routes-table-wrapper">

                <table class="routes-table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Route</th>
                            <th>Journey</th>
                            <th>Stops</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($routes as $route)

                            <tr>

                                {{-- NUMBER --}}
                                <td>
                                    <span class="route-row-number">
                                        {{ ($routes->firstItem() ?? 0) + $loop->index }}
                                    </span>
                                </td>

                                {{-- ROUTE DETAILS --}}
                                <td>
                                    <div class="route-info">

                                        <div class="route-avatar">
                                            <i class="bi bi-signpost-2-fill"></i>
                                        </div>

                                        <div class="route-details">
                                            <div class="route-title">
                                                {{ $route->route_number }}
                                            </div>

                                            <div class="route-name">
                                                {{ $route->route_name }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- JOURNEY --}}
                                <td>
                                    <div class="journey-cell">

                                        <div class="journey-point">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>{{ $route->starting_point ?: 'Not specified' }}</span>
                                        </div>

                                        <div class="journey-arrow">
                                            <i class="bi bi-arrow-down"></i>
                                        </div>

                                        <div class="journey-point destination">
                                            <i class="bi bi-flag-fill"></i>
                                            <span>{{ $route->destination ?: 'Not specified' }}</span>
                                        </div>

                                    </div>
                                </td>

                                {{-- STOPS --}}
                                <td>
                                    @if($route->stops)
                                        @php
                                            $stops = preg_split(
                                                '/\r\n|\r|\n|,/',
                                                $route->stops
                                            );

                                            $stops = array_values(
                                                array_filter(
                                                    array_map('trim', $stops),
                                                    function ($stop) {
                                                        return $stop !== '';
                                                    }
                                                )
                                            );
                                        @endphp

                                        <span class="stops-badge"
                                              title="{{ implode(', ', $stops) }}">
                                            <i class="bi bi-geo-alt-fill"></i>

                                            {{ count($stops) }}
                                            {{ count($stops) === 1 ? 'Stop' : 'Stops' }}
                                        </span>
                                    @else
                                        <span class="stops-badge">
                                            <i class="bi bi-dash-circle"></i>
                                            No Stops
                                        </span>
                                    @endif
                                </td>

                                {{-- VEHICLE --}}
                                <td>
                                    <div class="info-cell">

                                        <div class="info-cell-icon vehicle-icon">
                                            <i class="bi bi-bus-front-fill"></i>
                                        </div>

                                        <div class="info-cell-text">
                                            @if($route->vehicle)
                                                <strong>
                                                    {{ $route->vehicle->vehicle_number }}
                                                </strong>

                                                <span>
                                                    {{ $route->vehicle->vehicle_type ?? 'Assigned Vehicle' }}
                                                </span>
                                            @else
                                                <strong>Not Assigned</strong>
                                                <span>No Vehicle</span>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- DRIVER --}}
                                <td>
                                    <div class="info-cell">

                                        <div class="info-cell-icon driver-icon">
                                            <i class="bi bi-person-fill"></i>
                                        </div>

                                        <div class="info-cell-text">
                                            @if($route->driver)
                                                <strong>
                                                    {{ $route->driver->name }}
                                                </strong>

                                                <span>
                                                    {{ $route->driver->phone ?? 'No Contact' }}
                                                </span>
                                            @else
                                                <strong>Not Assigned</strong>
                                                <span>No Driver</span>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($route->status === 'active')
                                        <span class="status-badge active">
                                            <span class="status-dot"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge inactive">
                                            <span class="status-dot"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                {{-- ACTIONS --}}
                                <td>
                                    <div class="action-buttons">

                                        <a
                                            href="{{ route('admin.transport.routes.show', ['transportRoute' => $route->id]) }}"
                                            class="action-btn view"
                                            title="View Route"
                                            aria-label="View route"
                                        >
                                            <i class="bi bi-eye-fill"></i>
                                        </a>

                                        <a
                                            href="{{ route('admin.transport.routes.edit', ['transportRoute' => $route->id]) }}"
                                            class="action-btn edit"
                                            title="Edit Route"
                                            aria-label="Edit route"
                                        >
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <form
                                            action="{{ route('admin.transport.routes.destroy', ['transportRoute' => $route->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this route?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete"
                                                title="Delete Route"
                                                aria-label="Delete route"
                                            >
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8">

                                    <div class="empty-state">

                                        <div class="empty-icon-wrapper">
                                            <div class="empty-icon-bg"></div>

                                            <div class="empty-icon">
                                                <i class="bi bi-signpost-2-fill"></i>
                                            </div>
                                        </div>

                                        @if(request('search') || request('status'))

                                            <h4>No Matching Routes</h4>

                                            <p>
                                                No transport routes match your
                                                current filters. Try changing
                                                your search or clearing the filters.
                                            </p>

                                            <a
                                                href="{{ route('admin.transport.routes.index') }}"
                                                class="diary-primary-btn"
                                            >
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                Clear Filters
                                            </a>

                                        @else

                                            <h4>No Transport Routes Yet</h4>

                                            <p>
                                                There are currently no school
                                                transport routes. Add your first
                                                route to start managing school
                                                transportation.
                                            </p>

                                            <a
                                                href="{{ route('admin.transport.routes.create') }}"
                                                class="diary-primary-btn"
                                            >
                                                <i class="bi bi-plus-lg"></i>
                                                Add First Transport Route
                                            </a>

                                        @endif

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- PAGINATION --}}
            @if($routes->hasPages())

                <div class="pagination-wrapper">

                    <div class="pagination-info">
                        Showing
                        <strong>{{ $routes->firstItem() }}</strong>
                        to
                        <strong>{{ $routes->lastItem() }}</strong>
                        of
                        <strong>{{ $routes->total() }}</strong>
                        records
                    </div>

                    <div class="pagination-controls">

                        {{-- PREVIOUS --}}
                        @if($routes->onFirstPage())
                            <span class="pagination-btn pagination-disabled">
                                <i class="bi bi-chevron-left"></i>
                                <span>Previous</span>
                            </span>
                        @else
                            <a href="{{ $routes->appends(request()->query())->previousPageUrl() }}"
                               class="pagination-btn">
                                <i class="bi bi-chevron-left"></i>
                                <span>Previous</span>
                            </a>
                        @endif

                        {{-- FIRST PAGE --}}
                        @if($routes->currentPage() > 3)

                            <a href="{{ $routes->appends(request()->query())->url(1) }}"
                               class="pagination-number">
                                1
                            </a>

                            @if($routes->currentPage() > 4)
                                <span class="pagination-dots">...</span>
                            @endif

                        @endif

                        {{-- PAGE NUMBERS --}}
                        @foreach(
                            range(
                                max(1, $routes->currentPage() - 2),
                                min($routes->lastPage(), $routes->currentPage() + 2)
                            ) as $page
                        )

                            @if($page == $routes->currentPage())
                                <span class="pagination-number active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $routes->appends(request()->query())->url($page) }}"
                                   class="pagination-number">
                                    {{ $page }}
                                </a>
                            @endif

                        @endforeach

                        {{-- LAST PAGE --}}
                        @if($routes->currentPage() < $routes->lastPage() - 2)

                            @if($routes->currentPage() < $routes->lastPage() - 3)
                                <span class="pagination-dots">...</span>
                            @endif

                            <a href="{{ $routes->appends(request()->query())->url($routes->lastPage()) }}"
                               class="pagination-number">
                                {{ $routes->lastPage() }}
                            </a>

                        @endif

                        {{-- NEXT --}}
                        @if($routes->hasMorePages())
                            <a href="{{ $routes->appends(request()->query())->nextPageUrl() }}"
                               class="pagination-btn">
                                <span>Next</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-btn pagination-disabled">
                                <span>Next</span>
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif

                    </div>

                </div>

            @endif

            {{-- FOOTER --}}
            @if($routes->total() > 0)
                <div class="routes-card-footer">

                    <div>
                        Showing
                        <strong>{{ $routes->count() }}</strong>
                        record(s) on this page
                    </div>

                    <div class="footer-status">
                        <i class="bi bi-check-circle-fill"></i>
                        Data loaded successfully
                    </div>

                </div>
            @endif

        </section>

    </div>
</div>

<style>
/* ==========================================================
   TRANSPORT ROUTES — MATCHED TRANSPORT RECORDS DESIGN
   Locked School Diary Design System
========================================================== */

.transport-routes-page {
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

.transport-routes-page *,
.transport-routes-page *::before,
.transport-routes-page *::after {
    box-sizing: border-box;
}

.transport-routes-page a,
.transport-routes-page button {
    transition:
        color .2s ease,
        background .2s ease,
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}

.transport-routes-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

/* HERO */

.transport-routes-page .transport-hero,
.transport-routes-page .routes-section {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
}

.transport-routes-page .transport-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 0;
}

.transport-routes-page .transport-hero::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(228, 87, 46, .35);
}

.transport-routes-page .hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 20px 28px 20px 56px;
}

.transport-routes-page .hero-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.transport-routes-page .hero-text {
    min-width: 0;
}

.transport-routes-page .hero-text h2 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.35px;
}

.transport-routes-page .hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.transport-routes-page .hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 800;
}

.transport-routes-page .hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 28px;
    overflow: hidden;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        var(--ink);
    text-align: center;
}

.transport-routes-page .hero-actions::before {
    content: "";
    position: absolute;
    top: -55px;
    right: -35px;
    width: 160px;
    height: 160px;
    border: 1px solid rgba(255,255,255,.09);
    border-radius: 50%;
}

.transport-routes-page .hero-handwritten {
    position: relative;
    z-index: 1;
    color: var(--chalk);
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.transport-routes-page .hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* BUTTONS — SAME AS TRANSPORT RECORDS */

.transport-routes-page .diary-primary-btn {
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

.transport-routes-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29,48,43,.13);
}

/* Cream CTA inside the dark hero */

.transport-routes-page .hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.transport-routes-page .hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

/* SUCCESS MESSAGE */

.transport-routes-page .transport-alert {
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

.transport-routes-page .success-icon {
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

.transport-routes-page .transport-alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    padding: 0;
    border: 0;
    border-radius: 8px;
    color: #347849;
    background: transparent;
    cursor: pointer;
}

.transport-routes-page .transport-alert-close:hover {
    background: #deeedb;
}

/* KPI CARDS */

.transport-routes-page .transport-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.transport-routes-page .summary-card {
    --kpi-color: #426b8a;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38,59,53,.025);
    transition: transform .22s ease, box-shadow .22s ease;
}

.transport-routes-page .summary-card::before {
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

.transport-routes-page .summary-card.blue {
    --kpi-color: #426b8a;
}

.transport-routes-page .summary-card.green {
    --kpi-color: #43835d;
}

.transport-routes-page .summary-card.orange {
    --kpi-color: #a87543;
}

.transport-routes-page .summary-card.cyan {
    --kpi-color: #527f70;
}

.transport-routes-page .summary-card.blue::before {
    content: "ROUTES";
}

.transport-routes-page .summary-card.green::before {
    content: "ACTIVE";
}

.transport-routes-page .summary-card.orange::before {
    content: "INACTIVE";
}

.transport-routes-page .summary-card.cyan::before {
    content: "VEHICLES";
}

.transport-routes-page .summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38,59,53,.07);
}

.transport-routes-page .summary-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.transport-routes-page .summary-label {
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
}

.transport-routes-page .summary-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 18%, white);
    border-radius: 11px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    font-size: 18px;
}

.transport-routes-page .summary-number {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 34px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.transport-routes-page .summary-footer {
    padding-top: 12px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* MAIN PAPER PANEL */

.transport-routes-page .routes-section {
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(38,59,53,.025);
}

.transport-routes-page .routes-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 19px 22px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.transport-routes-page .section-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.transport-routes-page .section-heading-icon,
.transport-routes-page .filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dfe5dc;
    border-radius: 10px;
    color: var(--sage);
    background: #f0f4ec;
    font-size: 15px;
}

.transport-routes-page .section-heading h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
}

.transport-routes-page .section-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.transport-routes-page .route-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.transport-routes-page .route-count i,
.transport-routes-page .route-count strong {
    color: var(--sage);
}

/* SEARCH AND FILTERS */

.transport-routes-page .routes-filter-bar {
    display: block;
    padding: 21px 22px;
    border-bottom: 1px solid var(--line);
    background: #fffefa;
}

.transport-routes-page .filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    padding-bottom: 15px;
    border-bottom: 1px dashed #d8d1bf;
}

.transport-routes-page .filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 18px;
    font-weight: 700;
}

.transport-routes-page .filter-heading p {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.transport-routes-page .filter-grid {
    display: grid;
    grid-template-columns: minmax(200px, 2fr) minmax(150px, 1fr) auto;
    align-items: end;
    gap: 12px;
}

.transport-routes-page .filter-group {
    min-width: 0;
}

.transport-routes-page .filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 800;
}

.transport-routes-page .search-box {
    position: relative;
    width: 100%;
    min-width: 0;
}

.transport-routes-page .search-box > i {
    position: absolute;
    z-index: 1;
    top: 50%;
    left: 13px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.transport-routes-page .search-box input,
.transport-routes-page .filter-select {
    display: block;
    width: 100%;
    height: 42px;
    border: 1px solid #dcded3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.transport-routes-page .search-box input {
    padding: 0 12px 0 36px;
}

.transport-routes-page .search-box input::placeholder {
    color: #929b8f;
}

.transport-routes-page .filter-select {
    padding: 0 11px;
    cursor: pointer;
}

.transport-routes-page .search-box input:hover,
.transport-routes-page .filter-select:hover {
    border-color: #bfcbbd;
}

.transport-routes-page .search-box input:focus,
.transport-routes-page .filter-select:focus {
    border-color: #6e9a86;
    box-shadow: 0 0 0 3px rgba(82,127,112,.12);
}

/* Apply and Reset — matching Records buttons */

.transport-routes-page .filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

.transport-routes-page .search-btn,
.transport-routes-page .filter-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.transport-routes-page .search-btn {
    border: 1px solid var(--ink);
    color: #fff;
    background: var(--ink);
}

.transport-routes-page .search-btn:hover {
    transform: translateY(-2px);
    border-color: #426652;
    color: #fff;
    background: #426652;
    box-shadow: 0 5px 12px rgba(29,48,43,.10);
}

.transport-routes-page .filter-reset-btn {
    border: 1px solid #d9ded4;
    color: #53665a;
    background: var(--paper);
}

.transport-routes-page .filter-reset-btn:hover {
    border-color: #bfcbbd;
    color: var(--ink);
    background: #f1f5ed;
}

/* ACTIVE FILTERS */

.transport-routes-page .active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 22px;
    border-bottom: 1px solid #eeece3;
    background: #fffefa;
}

.transport-routes-page .active-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    min-width: 0;
}

.transport-routes-page .active-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-right: 3px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.transport-routes-page .active-filter-label i {
    color: var(--sage);
}

.transport-routes-page .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 220px;
    padding: 5px 9px;
    overflow: hidden;
    border: 1px solid #e6dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
    overflow-wrap: anywhere;
}

.transport-routes-page .filter-chip i {
    color: var(--amber);
}

.transport-routes-page .filter-result-count {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

/* TABLE */

.transport-routes-page .routes-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-color: #c7d1c4 #f5f3e9;
    scrollbar-width: thin;
}

.transport-routes-page .routes-table {
    width: 100%;
    min-width: 1120px;
    margin: 0;
    border-collapse: collapse;
}

.transport-routes-page .routes-table thead th {
    padding: 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.transport-routes-page .routes-table tbody td {
    padding: 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.transport-routes-page .routes-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.transport-routes-page .routes-table tbody tr:hover {
    background: #fafaf4;
}

.transport-routes-page .routes-table tbody tr:last-child td {
    border-bottom: none;
}

.transport-routes-page .routes-table .actions-heading {
    text-align: right;
}

.transport-routes-page .route-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e3e5db;
    border-radius: 8px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

/* ROUTE DETAILS */

.transport-routes-page .route-info {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 180px;
}

.transport-routes-page .route-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 38px;
    width: 38px;
    height: 38px;
    border: 1px solid #d7e2e9;
    border-radius: 10px;
    color: var(--blue);
    background: #edf3f8;
    font-size: 14px;
}

.transport-routes-page .route-details {
    min-width: 0;
}

.transport-routes-page .route-title {
    max-width: 190px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.transport-routes-page .route-name {
    margin-top: 4px;
    color: var(--muted);
    font-size: 10px;
    overflow-wrap: anywhere;
}

/* JOURNEY */

.transport-routes-page .journey-cell {
    min-width: 205px;
}

.transport-routes-page .journey-point {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: var(--ink);
    font-size: 10.5px;
    font-weight: 700;
    line-height: 1.7;
    overflow-wrap: anywhere;
}

.transport-routes-page .journey-point i {
    flex: 0 0 12px;
    margin-top: 2px;
    color: var(--green);
    font-size: 12px;
}

.transport-routes-page .journey-point.destination i {
    color: var(--amber);
}

.transport-routes-page .journey-arrow {
    margin: 3px 0 3px 3px;
    color: #b6b9aa;
    font-size: 10px;
}

/* STOPS */

.transport-routes-page .stops-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid #e6dfc9;
    border-radius: 7px;
    color: #8b641d;
    background: #faf5e7;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.transport-routes-page .stops-badge i {
    color: var(--amber);
}

/* VEHICLE AND DRIVER */

.transport-routes-page .info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 145px;
}

.transport-routes-page .info-cell-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-size: 12px;
}

.transport-routes-page .vehicle-icon {
    border-color: #ead8c2;
    color: var(--amber);
    background: #f5eadb;
}

.transport-routes-page .driver-icon {
    border-color: #d7e5d9;
    color: var(--green);
    background: #e6f0e8;
}

.transport-routes-page .info-cell-text {
    min-width: 0;
}

.transport-routes-page .info-cell-text strong {
    display: block;
    color: var(--ink-dark);
    font-size: 10.5px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.transport-routes-page .info-cell-text span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 9.5px;
    overflow-wrap: anywhere;
}

/* STATUS */

.transport-routes-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.transport-routes-page .status-badge.active {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.transport-routes-page .status-badge.inactive {
    border-color: #efd2cc;
    color: var(--red);
    background: #fff1ed;
}

.transport-routes-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
    background: currentColor;
}

/* VIEW / EDIT / DELETE BUTTONS */

.transport-routes-page .action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.transport-routes-page .action-buttons form {
    display: inline-flex;
    margin: 0;
}

.transport-routes-page .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 33px;
    width: 33px;
    height: 33px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 8px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.transport-routes-page .action-btn:hover {
    transform: translateY(-2px);
}

.transport-routes-page .action-btn.view:hover {
    border-color: #bdcfd9;
    color: var(--blue);
    background: #f1f6f8;
}

.transport-routes-page .action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.transport-routes-page .action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* EMPTY STATE */

.transport-routes-page .empty-state {
    padding: 60px 20px;
    text-align: center;
}

.transport-routes-page .empty-icon-wrapper {
    position: relative;
    width: 72px;
    height: 72px;
    margin: 0 auto 18px;
}

.transport-routes-page .empty-icon-bg {
    position: absolute;
    inset: 0;
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    background: var(--chalk);
    transform: rotate(4deg);
}

.transport-routes-page .empty-icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    color: var(--ink);
    background: #fffefa;
    font-size: 27px;
}

.transport-routes-page .empty-state h4 {
    margin: 0 0 9px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 21px;
    font-weight: 700;
}

.transport-routes-page .empty-state p {
    max-width: 450px;
    margin: 0 auto 20px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

/* PAGINATION */

.transport-routes-page .pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px dashed #d8d1bf;
    background: var(--paper);
}

.transport-routes-page .pagination-info {
    color: var(--muted);
    font-size: 11px;
}

.transport-routes-page .pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.transport-routes-page .pagination-controls {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
}

.transport-routes-page .pagination-btn,
.transport-routes-page .pagination-number,
.transport-routes-page .pagination-dots {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-width: 32px;
    min-height: 32px;
    padding: 5px 9px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--ink);
    background: var(--paper);
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
}

.transport-routes-page .pagination-btn:hover,
.transport-routes-page .pagination-number:hover {
    border-color: #bfcbbd;
    color: var(--green);
    background: #f1f5ed;
}

.transport-routes-page .pagination-number.active {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.transport-routes-page .pagination-dots {
    border-color: transparent;
    color: #92988b;
    background: transparent;
}

.transport-routes-page .pagination-disabled,
.transport-routes-page .pagination-disabled:hover {
    color: #a9aea5;
    background: #f7f6f0;
    cursor: not-allowed;
}

/* FOOTER */

.transport-routes-page .routes-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 53px;
    padding: 12px 22px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #f7f6f0;
    font-size: 11px;
}

.transport-routes-page .routes-card-footer strong {
    color: var(--ink);
}

.transport-routes-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.transport-routes-page .footer-status i {
    color: var(--green);
}

/* ACCESSIBILITY */

.transport-routes-page a:focus-visible,
.transport-routes-page button:focus-visible,
.transport-routes-page input:focus-visible,
.transport-routes-page select:focus-visible {
    outline: 3px solid rgba(82,127,112,.25);
    outline-offset: 2px;
}

/* TABLET */

@media (max-width: 1200px) {
    .transport-routes-page .transport-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .transport-routes-page .filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .transport-routes-page .filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .transport-routes-page .transport-hero {
        grid-template-columns: 1fr;
    }

    .transport-routes-page .hero-actions {
        min-height: 175px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .transport-routes-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
        padding-right: 15px;
        padding-left: 15px;
    }

    .transport-routes-page .transport-hero::before {
        left: 22px;
    }

    .transport-routes-page .hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .transport-routes-page .hero-icon-wrap {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 10px;
        font-size: 18px;
    }

    .transport-routes-page .hero-text h2 {
        font-size: 23px;
    }

    .transport-routes-page .hero-text p {
        font-size: 12px;
    }

    .transport-routes-page .hero-note {
        align-items: flex-start;
        font-size: 11px;
    }

    .transport-routes-page .hero-actions {
        min-height: 160px;
        padding: 22px 18px;
    }

    .transport-routes-page .hero-actions .diary-primary-btn {
        width: 100%;
        white-space: normal;
    }

    .transport-routes-page .transport-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px;
        margin-top: 30px;
    }

    .transport-routes-page .summary-card {
        padding: 20px 17px 15px;
    }

    .transport-routes-page .summary-number {
        font-size: 30px;
    }

    .transport-routes-page .routes-section-header {
        align-items: flex-start;
        padding: 15px;
    }

    .transport-routes-page .section-heading h3 {
        font-size: 16px;
    }

    .transport-routes-page .section-heading p {
        font-size: 11px;
    }

    .transport-routes-page .route-count {
        gap: 5px;
        padding: 5px 7px;
        font-size: 10px;
    }

    .transport-routes-page .routes-filter-bar {
        padding: 15px;
    }

    .transport-routes-page .filter-heading h4 {
        font-size: 16px;
    }

    .transport-routes-page .filter-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .transport-routes-page .filter-actions {
        grid-column: auto;
    }

    .transport-routes-page .filter-actions > * {
        flex: 1;
    }

    .transport-routes-page .active-filters {
        align-items: flex-start;
        flex-direction: column;
        padding: 12px 15px;
    }

    .transport-routes-page .filter-result-count {
        white-space: normal;
    }

    .transport-routes-page .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .transport-routes-page .routes-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .transport-routes-page .transport-summary-grid {
        grid-template-columns: 1fr;
    }

    .transport-routes-page .routes-section-header {
        gap: 8px;
    }

    .transport-routes-page .section-heading-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .transport-routes-page .filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .transport-routes-page .search-btn,
    .transport-routes-page .filter-reset-btn {
        width: 100%;
    }

    .transport-routes-page .active-filter-items {
        align-items: flex-start;
    }

    .transport-routes-page .filter-chip {
        max-width: 100%;
    }

    .transport-routes-page .pagination-controls {
        gap: 4px;
    }

    .transport-routes-page .pagination-btn span {
        display: none;
    }

    .transport-routes-page .pagination-number {
        min-width: 30px;
        padding: 0 7px;
    }
}

/* REDUCED MOTION */

@media (prefers-reduced-motion: reduce) {
    .transport-routes-page *,
    .transport-routes-page *::before,
    .transport-routes-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

</style>

@endsection
