@extends('layouts.app')

@section('title', 'Routes Report | Admin')
@section('page-title', 'Routes Report')

@section('content')
<div class="routes-report">
    <div class="container-fluid py-4">

        <div class="topbar">
            <div>
                <div class="eyebrow">TRANSPORT MANAGEMENT / REPORTS</div>
                <h2>Registered Routes</h2>
                <p>Overview of registered transport routes, stops, vehicles and assigned drivers.</p>
            </div>
            <div class="top-actions">
                <a href="{{ route('admin.reports.transport') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Transport Reports
                </a>
                <button type="button" class="btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-route"></i></div>
                <div>
                    <span>Total Registered Routes</span>
                    <strong>{{ $routes->count() }}</strong>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <span>Active Routes</span>
                    <strong>{{ $routes->where('status', 'active')->count() }}</strong>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-bus"></i></div>
                <div>
                    <span>Routes With Vehicles</span>
                    <strong>{{ $routes->filter(fn($route) => $route->vehicle || $route->assigned_vehicle)->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="report-note">
            <i class="fas fa-info-circle"></i>
            Student figures below represent active transport records linked to the vehicle.
            They are not confirmed route-specific student assignments because transport records
            are not directly linked to individual route IDs.
        </div>

        <div class="table-card">
            <div class="table-heading">
                <div>
                    <h3>Route Register</h3>
                    <p>Complete list of registered school transport routes</p>
                </div>
                <span class="record-count">{{ $routes->count() }} Records</span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Route</th>
                            <th>Journey</th>
                            <th>Stops</th>
                            <th>Assigned Vehicle</th>
                            <th>Route Driver</th>
                            <th>Vehicle-linked Students</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $route)
                            @php
                                $stopList = collect(preg_split('/[\r\n,]+/', $route->stops ?? ''))
                                    ->map(fn($stop) => trim($stop))
                                    ->filter()
                                    ->values();
                            @endphp
                            <tr>
                                <td>
                                    <div class="route-number">{{ $route->route_number ?: '—' }}</div>
                                    <div class="route-name">{{ $route->route_name ?: 'Unnamed Route' }}</div>
                                </td>
                                <td>
                                    <div class="journey-point">
                                        <i class="fas fa-circle start-dot"></i>
                                        <span>{{ $route->starting_point ?: 'Not specified' }}</span>
                                    </div>
                                    <div class="journey-line"></div>
                                    <div class="journey-point">
                                        <i class="fas fa-map-marker-alt end-dot"></i>
                                        <span>{{ $route->destination ?: 'Not specified' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="stop-count">{{ $stopList->count() }}</span>
                                    <span class="small-muted">stops</span>
                                    @if($stopList->isNotEmpty())
                                        <div class="stop-preview">
                                            {{ $stopList->take(2)->implode(', ') }}
                                            @if($stopList->count() > 2)
                                                +{{ $stopList->count() - 2 }} more
                                            @endif
                                        </div>
                                    @else
                                        <div class="stop-preview">No stops listed</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="vehicle-number">
                                        {{ $route->vehicle?->vehicle_number ?: ($route->assigned_vehicle ?: 'Not assigned') }}
                                    </div>
                                    <div class="small-muted">
                                        {{ $route->vehicle?->vehicle_type ?: 'Vehicle type unavailable' }}
                                    </div>
                                </td>
                                <td>
                                    @if($route->driver)
                                        <div class="driver-name">{{ $route->driver->name }}</div>
                                        <div class="small-muted">{{ $route->driver->phone ?: 'Contact unavailable' }}</div>
                                    @else
                                        <span class="small-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="student-count">{{ number_format((int) ($route->students_count ?? 0)) }}</span>
                                    <div class="small-muted">Vehicle-linked</div>
                                </td>
                                <td>
                                    <span class="status {{ strtolower($route->status ?? '') === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($route->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.reports.transport.routes.show', $route->id) }}" class="view-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-state">
                                    <i class="fas fa-route"></i>
                                    <strong>No registered routes found</strong>
                                    <span>Registered transport routes will appear here.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-note">
            <i class="fas fa-shield-alt"></i>
            System-generated transport routes report.
        </div>
    </div>
</div>

<style>
.routes-report {
    --page: #f7f6f0;
    --paper: #fffefa;
    --ink: #263b35;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --sage: #527f70;
    background: var(--page);
    min-height: 100vh;
    color: var(--text);
}
.routes-report .container-fluid {
    max-width: 1650px;
}
.routes-report .topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 22px;
}
.routes-report .eyebrow {
    color: #a87543;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
    margin-bottom: 7px;
}
.routes-report h2 {
    color: var(--ink);
    font-size: 27px;
    font-weight: 750;
    margin: 0 0 6px;
}
.routes-report .topbar p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}
.routes-report .top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}
.routes-report .btn-primary,
.routes-report .btn-secondary,
.routes-report .view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 15px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}
.routes-report .btn-primary {
    background: #344f43;
    border: 1px solid #344f43;
    color: white;
}
.routes-report .btn-primary:hover {
    background: #20352c;
    color: white;
}
.routes-report .btn-secondary {
    background: var(--paper);
    border: 1px solid #d4cfc2;
    color: var(--ink);
}
.routes-report .btn-secondary:hover {
    background: #f1ead8;
    color: var(--ink);
}
.routes-report .summary-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 18px;
}
.routes-report .summary-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
}
.routes-report .summary-icon {
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    border-radius: 9px;
    background: #f1ead8;
    color: var(--sage);
    font-size: 18px;
}
.routes-report .summary-card span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 4px;
}
.routes-report .summary-card strong {
    display: block;
    color: var(--ink);
    font-size: 23px;
    font-weight: 800;
}
.routes-report .report-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 15px;
    margin-bottom: 18px;
    border: 1px solid #e5d9bd;
    border-radius: 8px;
    background: #faf5e9;
    color: #75613e;
    font-size: 12px;
    line-height: 1.6;
}
.routes-report .report-note i {
    margin-top: 2px;
}
.routes-report .table-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 11px;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(38, 59, 53, .035);
}
.routes-report .table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 19px 21px;
    border-bottom: 1px solid var(--line);
}
.routes-report .table-heading h3 {
    margin: 0 0 4px;
    color: var(--ink);
    font-size: 16px;
    font-weight: 750;
}
.routes-report .table-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
}
.routes-report .record-count {
    padding: 6px 11px;
    border-radius: 20px;
    background: #edf2ed;
    color: #426b55;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.routes-report table {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
    font-size: 12px;
}
.routes-report th {
    padding: 14px 13px;
    background: #f1efe6;
    color: #59685e;
    border-bottom: 1px solid var(--line);
    text-align: left;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    white-space: nowrap;
}
.routes-report td {
    padding: 15px 13px;
    border-bottom: 1px solid #eeeae1;
    vertical-align: middle;
}
.routes-report tbody tr:last-child td {
    border-bottom: none;
}
.routes-report tbody tr:hover td {
    background: #fbfaf5;
}
.routes-report .route-number {
    color: #527f70;
    font-size: 12px;
    font-weight: 800;
}
.routes-report .route-name {
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
}
.routes-report .journey-point {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #39483e;
    font-weight: 600;
    white-space: nowrap;
}
.routes-report .start-dot {
    color: #527f70;
    font-size: 8px;
}
.routes-report .end-dot {
    color: #b95b50;
    font-size: 11px;
}
.routes-report .journey-line {
    width: 1px;
    height: 10px;
    margin: 2px 0 2px 3px;
    border-left: 1px dashed #b9b9a9;
}
.routes-report .stop-count,
.routes-report .student-count {
    color: var(--ink);
    font-weight: 800;
    font-size: 14px;
}
.routes-report .small-muted {
    display: block;
    color: var(--muted);
    font-size: 10px;
    margin-top: 4px;
}
.routes-report .stop-preview {
    max-width: 160px;
    margin-top: 5px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.5;
}
.routes-report .vehicle-number,
.routes-report .driver-name {
    color: var(--ink);
    font-weight: 700;
    white-space: nowrap;
}
.routes-report .status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}
.routes-report .status.active {
    background: #e8f2e9;
    color: #35724b;
}
.routes-report .status.inactive {
    background: #f5e9e5;
    color: #a54c3f;
}
.routes-report .view-btn {
    padding: 8px 11px;
    background: #344f43;
    color: white;
    white-space: nowrap;
}
.routes-report .view-btn:hover {
    background: #20352c;
    color: white;
}
.routes-report .empty-state {
    padding: 48px 20px;
    text-align: center;
    color: var(--muted);
}
.routes-report .empty-state i,
.routes-report .empty-state strong,
.routes-report .empty-state span {
    display: block;
}
.routes-report .empty-state i {
    margin-bottom: 12px;
    font-size: 27px;
    color: #a5ad9f;
}
.routes-report .empty-state strong {
    color: var(--ink);
    margin-bottom: 5px;
}
.routes-report .empty-state span {
    font-size: 11px;
}
.routes-report .footer-note {
    margin-top: 15px;
    color: var(--muted);
    font-size: 10px;
}
.routes-report .footer-note i {
    margin-right: 5px;
}
@media(max-width: 800px) {
    .routes-report .summary-grid {
        grid-template-columns: 1fr;
    }
    .routes-report .topbar {
        align-items: flex-start;
    }
}
@media print {
    @page {
        size: A4 landscape;
        margin: 12mm;
    }
    body {
        background: white !important;
    }
    .routes-report {
        background: white !important;
    }
    .routes-report .top-actions,
    .routes-report .footer-note {
        display: none !important;
    }
    .routes-report .summary-card,
    .routes-report .table-card {
        box-shadow: none !important;
    }
    .routes-report .table-responsive {
        overflow: visible !important;
    }
    .routes-report table {
        min-width: 0;
        font-size: 9px;
    }
    .routes-report th,
    .routes-report td {
        padding: 7px;
    }
    .routes-report .view-btn {
        display: none;
    }
}
</style>
@endsection