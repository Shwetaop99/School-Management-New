
@extends('layouts.app')

@section('title', 'Registered Vehicles Report | Admin')
@section('page-title', 'Registered Vehicles Report')

@section('content')
<div class="registered-vehicles-report">
    <div class="container-fluid py-4">

        <div class="topbar">
            <div>
                <div class="eyebrow">TRANSPORT MANAGEMENT / REPORTS</div>
                <h2>Registered Vehicles</h2>
                <p>Complete register of school transport vehicles, drivers and assigned students.</p>
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

        @php
            $totalVehicles = $vehicles->count();

            $activeVehicles = $vehicles->filter(
                fn($vehicle) => strtolower(trim($vehicle->status ?? '')) === 'active'
            )->count();

            $inactiveVehicles = $totalVehicles - $activeVehicles;

            $totalAssignedStudents = $vehicles->sum('assigned_students');
            $totalActiveStudents = $vehicles->sum('active_students');
            $totalInactiveStudents = $vehicles->sum('inactive_students');
        @endphp

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-bus"></i></div>
                <div>
                    <span>Total Registered Vehicles</span>
                    <strong>{{ number_format($totalVehicles) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <span>Active Vehicles</span>
                    <strong>{{ number_format($activeVehicles) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon red"><i class="fas fa-ban"></i></div>
                <div>
                    <span>Inactive Vehicles</span>
                    <strong>{{ number_format($inactiveVehicles) }}</strong>
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-icon blue"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <span>Vehicle-linked Students</span>
                    <strong>{{ number_format($totalAssignedStudents) }}</strong>
                </div>
            </div>
        </div>

        <div class="report-note">
            <i class="fas fa-info-circle"></i>
            <span>
                This report displays vehicles registered in the vehicle master register,
                including inactive vehicles. Student counts are based on transport records
                matching the registered vehicle number. Counts are calculated separately
                for each vehicle and may include students in different transport statuses.
            </span>
        </div>

        <div class="table-card">
            <div class="table-heading">
                <div>
                    <h3>Vehicle Register</h3>
                    <p>Complete list of registered school transport vehicles</p>
                </div>
                <span class="record-count">{{ $totalVehicles }} Records</span>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Vehicle Details</th>
                            <th>Type / Model</th>
                            <th>Capacity</th>
                            <th>Assigned Driver</th>
                            <th>Assigned Students</th>
                            <th>Active / Inactive</th>
                            <th>Routes</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($vehicles as $vehicle)
                            @php
                                $status = strtolower(trim($vehicle->status ?? ''));
                                $driver = $vehicle->driver;
                            @endphp

                            <tr>
                                <td>
                                    <div class="vehicle-number">
                                        {{ $vehicle->vehicle_number ?: 'Not specified' }}
                                    </div>
                                    <div class="small-muted">
                                        ID: {{ $vehicle->id }}
                                    </div>
                                </td>

                                <td>
                                    <div class="detail-main">
                                        {{ $vehicle->vehicle_type ?: 'Not specified' }}
                                    </div>
                                    <div class="small-muted">
                                        {{ $vehicle->vehicle_model ?: 'Model unavailable' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="capacity">
                                        {{ $vehicle->capacity ?? '—' }}
                                    </span>
                                    <div class="small-muted">Seats</div>
                                </td>

                                <td>
                                    <div class="detail-main">
                                        {{ $driver?->name ?: ($vehicle->driver_name ?: 'Not assigned') }}
                                    </div>
                                    <div class="small-muted">
                                        {{ $driver?->phone ?: ($vehicle->driver_contact ?: 'Contact unavailable') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="student-count">
                                        {{ number_format((int) ($vehicle->assigned_students ?? 0)) }}
                                    </span>
                                    <div class="small-muted">Unique students</div>
                                </td>

                                <td>
                                    <div class="student-status active-text">
                                        <i class="fas fa-circle"></i>
                                        {{ number_format((int) ($vehicle->active_students ?? 0)) }} Active
                                    </div>
                                    <div class="student-status inactive-text">
                                        <i class="fas fa-circle"></i>
                                        {{ number_format((int) ($vehicle->inactive_students ?? 0)) }} Inactive
                                    </div>
                                </td>

                                <td>
                                    <span class="route-count">
                                        {{ number_format((int) ($vehicle->routes_count ?? 0)) }}
                                    </span>
                                    <div class="small-muted">Registered routes</div>
                                </td>

                                <td>
                                    <span class="status {{ $status === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($vehicle->status ?? 'Unknown') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('admin.reports.transport.vehicles.show', $vehicle->id) }}"
                                       class="view-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <i class="fas fa-bus"></i>
                                    <strong>No registered vehicles found</strong>
                                    <span>Registered transport vehicles will appear here.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer-note">
            <i class="fas fa-shield-alt"></i>
            System-generated registered vehicles report.
        </div>

    </div>
</div>

<style>
.registered-vehicles-report {
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

.registered-vehicles-report .container-fluid {
    max-width: 1650px;
}

.registered-vehicles-report .topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 22px;
}

.registered-vehicles-report .eyebrow {
    color: #a87543;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
    margin-bottom: 7px;
}

.registered-vehicles-report h2 {
    color: var(--ink);
    font-size: 27px;
    font-weight: 750;
    margin: 0 0 6px;
}

.registered-vehicles-report .topbar p {
    color: var(--muted);
    font-size: 13px;
    margin: 0;
}

.registered-vehicles-report .top-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.registered-vehicles-report .btn-primary,
.registered-vehicles-report .btn-secondary,
.registered-vehicles-report .view-btn {
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

.registered-vehicles-report .btn-primary {
    background: #344f43;
    border: 1px solid #344f43;
    color: white;
}

.registered-vehicles-report .btn-primary:hover {
    background: #20352c;
    color: white;
}

.registered-vehicles-report .btn-secondary {
    background: var(--paper);
    border: 1px solid #d4cfc2;
    color: var(--ink);
}

.registered-vehicles-report .btn-secondary:hover {
    background: #f1ead8;
    color: var(--ink);
}

.registered-vehicles-report .summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 18px;
}

.registered-vehicles-report .summary-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 10px;
}

.registered-vehicles-report .summary-icon {
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

.registered-vehicles-report .summary-icon.green {
    background: #e8f2e9;
    color: #43835d;
}

.registered-vehicles-report .summary-icon.red {
    background: #f5e9e5;
    color: #b95b50;
}

.registered-vehicles-report .summary-icon.blue {
    background: #eaf0f4;
    color: #426b8a;
}

.registered-vehicles-report .summary-card span {
    display: block;
    color: var(--muted);
    font-size: 11px;
    margin-bottom: 4px;
}

.registered-vehicles-report .summary-card strong {
    display: block;
    color: var(--ink);
    font-size: 23px;
    font-weight: 800;
}

.registered-vehicles-report .report-note {
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

.registered-vehicles-report .report-note i {
    margin-top: 2px;
}

.registered-vehicles-report .table-card {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 11px;
    overflow: hidden;
    box-shadow: 0 3px 14px rgba(38, 59, 53, .035);
}

.registered-vehicles-report .table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 19px 21px;
    border-bottom: 1px solid var(--line);
}

.registered-vehicles-report .table-heading h3 {
    margin: 0 0 4px;
    color: var(--ink);
    font-size: 16px;
    font-weight: 750;
}

.registered-vehicles-report .table-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
}

.registered-vehicles-report .record-count {
    padding: 6px 11px;
    border-radius: 20px;
    background: #edf2ed;
    color: #426b55;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.registered-vehicles-report table {
    width: 100%;
    min-width: 1150px;
    border-collapse: collapse;
    font-size: 12px;
}

.registered-vehicles-report th {
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

.registered-vehicles-report td {
    padding: 15px 13px;
    border-bottom: 1px solid #eeeae1;
    vertical-align: middle;
}

.registered-vehicles-report tbody tr:last-child td {
    border-bottom: none;
}

.registered-vehicles-report tbody tr:hover td {
    background: #fbfaf5;
}

.registered-vehicles-report .vehicle-number {
    color: #527f70;
    font-size: 13px;
    font-weight: 800;
    white-space: nowrap;
}

.registered-vehicles-report .detail-main {
    color: var(--ink);
    font-weight: 700;
}

.registered-vehicles-report .small-muted {
    display: block;
    color: var(--muted);
    font-size: 10px;
    margin-top: 4px;
}

.registered-vehicles-report .capacity,
.registered-vehicles-report .student-count,
.registered-vehicles-report .route-count {
    color: var(--ink);
    font-size: 14px;
    font-weight: 800;
}

.registered-vehicles-report .student-status {
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
    margin: 3px 0;
}

.registered-vehicles-report .student-status i {
    font-size: 6px;
    margin-right: 4px;
    vertical-align: middle;
}

.registered-vehicles-report .active-text {
    color: #43835d;
}

.registered-vehicles-report .inactive-text {
    color: #b95b50;
}

.registered-vehicles-report .status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.registered-vehicles-report .status.active {
    background: #e8f2e9;
    color: #35724b;
}

.registered-vehicles-report .status.inactive {
    background: #f5e9e5;
    color: #a54c3f;
}

.registered-vehicles-report .view-btn {
    padding: 8px 11px;
    background: #344f43;
    color: white;
    white-space: nowrap;
}

.registered-vehicles-report .view-btn:hover {
    background: #20352c;
    color: white;
}

.registered-vehicles-report .empty-state {
    padding: 48px 20px;
    text-align: center;
    color: var(--muted);
}

.registered-vehicles-report .empty-state i,
.registered-vehicles-report .empty-state strong,
.registered-vehicles-report .empty-state span {
    display: block;
}

.registered-vehicles-report .empty-state i {
    margin-bottom: 12px;
    font-size: 27px;
    color: #a5ad9f;
}

.registered-vehicles-report .empty-state strong {
    color: var(--ink);
    margin-bottom: 5px;
}

.registered-vehicles-report .empty-state span {
    font-size: 11px;
}

.registered-vehicles-report .footer-note {
    margin-top: 15px;
    color: var(--muted);
    font-size: 10px;
}

.registered-vehicles-report .footer-note i {
    margin-right: 5px;
}

@media(max-width: 1100px) {
    .registered-vehicles-report .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 600px) {
    .registered-vehicles-report .summary-grid {
        grid-template-columns: 1fr;
    }

    .registered-vehicles-report .topbar {
        align-items: flex-start;
    }
}

@media print {
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body,
    .registered-vehicles-report {
        background: white !important;
    }

    .registered-vehicles-report .top-actions,
    .registered-vehicles-report .footer-note {
        display: none !important;
    }

    .registered-vehicles-report .summary-card,
    .registered-vehicles-report .table-card {
        box-shadow: none !important;
    }

    .registered-vehicles-report .table-responsive {
        overflow: visible !important;
    }

    .registered-vehicles-report table {
        min-width: 0;
        font-size: 8px;
    }

    .registered-vehicles-report th,
    .registered-vehicles-report td {
        padding: 6px;
    }

    .registered-vehicles-report .view-btn {
        display: none;
    }
}
</style>
@endsection
