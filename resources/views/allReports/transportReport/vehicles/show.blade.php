
@extends('layouts.app')

@section('title', 'Individual Vehicle Report | Admin')
@section('page-title', 'Individual Vehicle Report')

@section('content')
@php
    $schoolName = trim((string) ($school->school_name ?? ''));
    $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

    $schoolAddress = collect([
        $school->address ?? null,
        $school->city ?? null,
        $school->district ?? null,
        $school->state ?? null,
    ])->filter(fn($value) => trim((string) $value) !== '')
      ->unique()
      ->implode(', ');

    $schoolPhone = $school->phone ?? $school->contact_number ?? '';
    $schoolEmail = $school->email ?? '';
    $logoUrl = $school->logo_url ?? null;

    $driver = $vehicle->driver;

    $driverName = $driver?->name ?: ($vehicle->driver_name ?: 'Not assigned');
    $driverPhone = $driver?->phone ?: ($vehicle->driver_contact ?: 'Not available');
    $driverLicense = $vehicle->driver_license_number ?: 'Not available';

    $money = fn($amount) => '₹' . number_format((float) ($amount ?? 0), 2);

    $status = strtolower(trim($vehicle->status ?? ''));

    $vehicleNumber = $vehicle->vehicle_number ?: 'Not specified';
@endphp

<div class="vehicle-detail-page">
    <div class="container-fluid py-4">

        {{-- TOOLBAR --}}
        <div class="report-toolbar screen-only">
            <div>
                <div class="eyebrow">TRANSPORT MANAGEMENT / REGISTERED VEHICLES</div>
                <h2>Vehicle Details</h2>
                <p>Complete vehicle, driver, route and student information.</p>
            </div>

            <div class="toolbar-actions">
                <a href="{{ route('admin.reports.transport.vehicles') }}"
                   class="action-btn secondary">
                    <i class="fas fa-arrow-left"></i> Back to Vehicles
                </a>

                <a href="{{ route('admin.reports.transport.vehicles.pdf', $vehicle->id) }}"
                   class="action-btn primary">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>

                <a href="{{ route('admin.reports.transport.vehicles.excel', $vehicle->id) }}"
                   class="action-btn excel">
                    <i class="fas fa-file-excel"></i> Excel
                </a>

                <button type="button" class="action-btn print" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="vehicle-summary-grid screen-only">
            <div class="summary-card">
                <span>Vehicle Number</span>
                <strong>{{ $vehicleNumber }}</strong>
            </div>

            <div class="summary-card">
                <span>Assigned Students</span>
                <strong>{{ number_format($assignedStudents) }}</strong>
            </div>

            <div class="summary-card">
                <span>Active Students</span>
                <strong>{{ number_format($activeStudents) }}</strong>
            </div>

            <div class="summary-card">
                <span>Inactive Students</span>
                <strong>{{ number_format($inactiveStudents) }}</strong>
            </div>
        </div>

        {{-- VEHICLE INFORMATION --}}
        <div class="report-card screen-only">
            <div class="card-heading">
                <h3><i class="fas fa-bus"></i> Vehicle Information</h3>
                <span class="status-pill {{ $status === 'active' ? 'active' : 'inactive' }}">
                    {{ ucfirst($vehicle->status ?? 'Unknown') }}
                </span>
            </div>

            <div class="details-grid">
                <div class="detail-item">
                    <span>Vehicle Number</span>
                    <strong>{{ $vehicleNumber }}</strong>
                </div>

                <div class="detail-item">
                    <span>Vehicle Type</span>
                    <strong>{{ $vehicle->vehicle_type ?: 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Vehicle Model</span>
                    <strong>{{ $vehicle->vehicle_model ?: 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Vehicle Color</span>
                    <strong>{{ $vehicle->vehicle_color ?: 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Seating Capacity</span>
                    <strong>{{ $vehicle->capacity ?? 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Registration Record ID</span>
                    <strong>{{ $vehicle->id }}</strong>
                </div>

                <div class="detail-item">
                    <span>Vehicle Status</span>
                    <strong>{{ ucfirst($vehicle->status ?? 'Unknown') }}</strong>
                </div>

                <div class="detail-item">
                    <span>Registered On</span>
                    <strong>{{ $vehicle->created_at?->format('d M Y') ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>

        {{-- DRIVER INFORMATION --}}
        <div class="report-card screen-only">
            <div class="card-heading">
                <h3><i class="fas fa-id-card"></i> Driver Information</h3>
            </div>

            <div class="details-grid">
                <div class="detail-item">
                    <span>Driver Name</span>
                    <strong>{{ $driverName }}</strong>
                </div>

                <div class="detail-item">
                    <span>Driver Contact</span>
                    <strong>{{ $driverPhone }}</strong>
                </div>

                <div class="detail-item">
                    <span>Driver Licence Number</span>
                    <strong>{{ $driverLicense }}</strong>
                </div>

                <div class="detail-item">
                    <span>Driver Record ID</span>
                    <strong>{{ $vehicle->driver_id ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>

        {{-- DOCUMENT EXPIRY --}}
        <div class="report-card screen-only">
            <div class="card-heading">
                <h3><i class="fas fa-file-contract"></i> Vehicle Documents & Expiry</h3>
            </div>

            <div class="details-grid">
                <div class="detail-item">
                    <span>Insurance Expiry</span>
                    <strong>{{ $vehicle->insurance_expiry?->format('d M Y') ?? 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Fitness Certificate Expiry</span>
                    <strong>{{ $vehicle->fitness_expiry?->format('d M Y') ?? 'N/A' }}</strong>
                </div>

                <div class="detail-item">
                    <span>Permit Expiry</span>
                    <strong>{{ $vehicle->permit_expiry?->format('d M Y') ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>

        {{-- ASSIGNED ROUTES --}}
        <div class="report-card screen-only">
            <div class="card-heading">
                <h3><i class="fas fa-route"></i> Routes Assigned to This Vehicle</h3>
                <span>{{ $routes->count() }} Routes</span>
            </div>

            <div class="table-responsive">
                <table class="vehicle-table">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Route Number</th>
                            <th>Route Name</th>
                            <th>Starting Point</th>
                            <th>Destination</th>
                            <th>Route Driver</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($routes as $index => $route)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $route->route_number ?: 'N/A' }}</td>
                                <td>{{ $route->route_name ?: 'N/A' }}</td>
                                <td>{{ $route->starting_point ?: 'N/A' }}</td>
                                <td>{{ $route->destination ?: 'N/A' }}</td>
                                <td>
                                    {{ $route->driver?->name ?: ($route->driver_name ?: 'Not assigned') }}
                                </td>
                                <td>
                                    <span class="status-pill {{ strtolower($route->status ?? '') === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($route->status ?? 'Unknown') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    No registered routes are linked to this vehicle.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- STUDENT SUMMARY --}}
        <div class="student-summary">
            <div>
                <span>Total Assigned Students</span>
                <strong>{{ number_format($assignedStudents) }}</strong>
            </div>
            <div>
                <span>Active Students</span>
                <strong class="green-text">{{ number_format($activeStudents) }}</strong>
            </div>
            <div>
                <span>Inactive Students</span>
                <strong class="red-text">{{ number_format($inactiveStudents) }}</strong>
            </div>
            <div>
                <span>Total Transport Fees</span>
                <strong>{{ $money($totalTransportFees) }}</strong>
            </div>
        </div>

        {{-- STUDENT DETAILS --}}
        <div class="report-card screen-only">
            <div class="card-heading">
                <h3><i class="fas fa-user-graduate"></i> Students Linked to This Vehicle</h3>
                <span>{{ $students->count() }} Records</span>
            </div>

            <div class="table-responsive">
                <table class="vehicle-table">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Pickup Point</th>
                            <th>Drop Point</th>
                            <th>Transport Fee</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $index => $record)
                            @php
                                $student = $record->student;

                                $studentName = $student
                                    ? trim(collect([
                                        $student->first_name,
                                        $student->middle_name,
                                        $student->last_name,
                                    ])->filter(fn($value) => trim((string) $value) !== '')
                                      ->implode(' '))
                                    : 'Student Not Found';
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student?->student_id ?? 'N/A' }}</td>
                                <td>{{ $studentName }}</td>
                                <td>{{ $student?->class ?? 'N/A' }}</td>
                                <td>{{ $student?->section ?? 'N/A' }}</td>
                                <td>{{ $record->pickup_point ?: 'N/A' }}</td>
                                <td>{{ $record->drop_point ?: 'N/A' }}</td>
                                <td>{{ $money($record->transport_fee) }}</td>
                                <td>{{ ucfirst($record->payment_status ?? 'N/A') }}</td>
                                <td>
                                    <span class="status-pill {{ strtolower($record->transport_status ?? '') === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($record->transport_status ?? 'Unknown') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">
                                    No student transport records are linked to this vehicle.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- REMARKS --}}
        @if($vehicle->remarks)
            <div class="report-card screen-only">
                <div class="card-heading">
                    <h3><i class="fas fa-sticky-note"></i> Remarks</h3>
                </div>
                <p class="remarks-text">{{ $vehicle->remarks }}</p>
            </div>
        @endif

        {{-- PRINT REPORT --}}
        <div class="print-report">

            <div class="print-school-header">
                <table class="school-table">
                    <tr>
                        @if(!empty($logoUrl))
                            <td class="school-logo-cell">
                                <img src="{{ $logoUrl }}" class="school-logo" alt="School Logo">
                            </td>
                        @endif

                        <td>
                            <div class="school-name">{{ $schoolName }}</div>

                            @if($schoolAddress !== '')
                                <div class="school-details">{{ $schoolAddress }}</div>
                            @endif

                            @if($schoolPhone !== '' || $schoolEmail !== '')
                                <div class="school-details">
                                    @if($schoolPhone !== '')
                                        Phone: {{ $schoolPhone }}
                                    @endif
                                    @if($schoolPhone !== '' && $schoolEmail !== '')
                                        |
                                    @endif
                                    @if($schoolEmail !== '')
                                        Email: {{ $schoolEmail }}
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="print-title">INDIVIDUAL REGISTERED VEHICLE REPORT</div>
            <div class="print-subtitle">
                Vehicle, driver, route and student information
            </div>

            <div class="print-section-title">Vehicle Information</div>

            <table class="print-details-table">
                <tr>
                    <td><span>Vehicle Number</span><strong>{{ $vehicleNumber }}</strong></td>
                    <td><span>Vehicle Type</span><strong>{{ $vehicle->vehicle_type ?: 'N/A' }}</strong></td>
                    <td><span>Vehicle Model</span><strong>{{ $vehicle->vehicle_model ?: 'N/A' }}</strong></td>
                    <td><span>Vehicle Color</span><strong>{{ $vehicle->vehicle_color ?: 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <td><span>Capacity</span><strong>{{ $vehicle->capacity ?? 'N/A' }}</strong></td>
                    <td><span>Status</span><strong>{{ ucfirst($vehicle->status ?? 'Unknown') }}</strong></td>
                    <td><span>Registration ID</span><strong>{{ $vehicle->id }}</strong></td>
                    <td><span>Registered On</span><strong>{{ $vehicle->created_at?->format('d M Y') ?? 'N/A' }}</strong></td>
                </tr>
            </table>

            <div class="print-section-title">Driver Information</div>

            <table class="print-details-table">
                <tr>
                    <td><span>Driver Name</span><strong>{{ $driverName }}</strong></td>
                    <td><span>Contact</span><strong>{{ $driverPhone }}</strong></td>
                    <td><span>Licence Number</span><strong>{{ $driverLicense }}</strong></td>
                    <td><span>Driver ID</span><strong>{{ $vehicle->driver_id ?? 'N/A' }}</strong></td>
                </tr>
            </table>

            <div class="print-section-title">Vehicle Documents & Expiry</div>

            <table class="print-details-table">
                <tr>
                    <td><span>Insurance Expiry</span><strong>{{ $vehicle->insurance_expiry?->format('d M Y') ?? 'N/A' }}</strong></td>
                    <td><span>Fitness Expiry</span><strong>{{ $vehicle->fitness_expiry?->format('d M Y') ?? 'N/A' }}</strong></td>
                    <td><span>Permit Expiry</span><strong>{{ $vehicle->permit_expiry?->format('d M Y') ?? 'N/A' }}</strong></td>
                    <td><span>Vehicle Status</span><strong>{{ ucfirst($vehicle->status ?? 'Unknown') }}</strong></td>
                </tr>
            </table>

            <div class="print-section-title">Assigned Routes ({{ $routes->count() }})</div>

            <table class="print-data-table">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Route Number</th>
                        <th>Route Name</th>
                        <th>Starting Point</th>
                        <th>Destination</th>
                        <th>Driver</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($routes as $index => $route)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $route->route_number ?: 'N/A' }}</td>
                            <td>{{ $route->route_name ?: 'N/A' }}</td>
                            <td>{{ $route->starting_point ?: 'N/A' }}</td>
                            <td>{{ $route->destination ?: 'N/A' }}</td>
                            <td>{{ $route->driver?->name ?: ($route->driver_name ?: 'Not assigned') }}</td>
                            <td>{{ ucfirst($route->status ?? 'Unknown') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="print-empty">No registered routes linked.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="print-section-title">Student Summary</div>

            <table class="print-details-table">
                <tr>
                    <td><span>Assigned Students</span><strong>{{ $assignedStudents }}</strong></td>
                    <td><span>Active Students</span><strong>{{ $activeStudents }}</strong></td>
                    <td><span>Inactive Students</span><strong>{{ $inactiveStudents }}</strong></td>
                    <td><span>Total Transport Fees</span><strong>{{ $money($totalTransportFees) }}</strong></td>
                </tr>
            </table>

            <div class="print-section-title">Students Linked to This Vehicle ({{ $students->count() }} Records)</div>

            <table class="print-data-table">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Pickup</th>
                        <th>Drop</th>
                        <th>Fee</th>
                        <th>Payment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $record)
                        @php
                            $student = $record->student;
                            $studentName = $student
                                ? trim(collect([
                                    $student->first_name,
                                    $student->middle_name,
                                    $student->last_name,
                                ])->filter(fn($value) => trim((string) $value) !== '')
                                  ->implode(' '))
                                : 'Student Not Found';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student?->student_id ?? 'N/A' }}</td>
                            <td>{{ $studentName }}</td>
                            <td>{{ $student?->class ?? 'N/A' }}</td>
                            <td>{{ $student?->section ?? 'N/A' }}</td>
                            <td>{{ $record->pickup_point ?: 'N/A' }}</td>
                            <td>{{ $record->drop_point ?: 'N/A' }}</td>
                            <td>{{ $money($record->transport_fee) }}</td>
                            <td>{{ ucfirst($record->payment_status ?? 'N/A') }}</td>
                            <td>{{ ucfirst($record->transport_status ?? 'Unknown') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="print-empty">No student records linked to this vehicle.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if($vehicle->remarks)
                <div class="print-section-title">Remarks</div>
                <div class="print-remarks">{{ $vehicle->remarks }}</div>
            @endif

            <div class="print-footer">
                {{ $schoolName }} | Registered Vehicle Report | Generated {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>

    </div>
</div>

<style>
.vehicle-detail-page {
    background: #f7f6f0;
    min-height: 100vh;
    color: #263b35;
}

.vehicle-detail-page .container-fluid {
    max-width: 1650px;
}

.vehicle-detail-page .eyebrow {
    color: #a87543;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.7px;
    margin-bottom: 7px;
}

.vehicle-detail-page .report-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.vehicle-detail-page .report-toolbar h2 {
    font-size: 25px;
    font-weight: 750;
    margin: 0 0 5px;
    color: #263b35;
}

.vehicle-detail-page .report-toolbar p {
    color: #7b857b;
    margin: 0;
    font-size: 13px;
}

.vehicle-detail-page .toolbar-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.vehicle-detail-page .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
    cursor: pointer;
}

.vehicle-detail-page .action-btn.primary {
    background: #344f43;
    color: white;
}

.vehicle-detail-page .action-btn.primary:hover {
    background: #20352c;
    color: white;
}

.vehicle-detail-page .action-btn.secondary {
    background: #fffefa;
    color: #263b35;
    border-color: #d4cfc2;
}

.vehicle-detail-page .action-btn.secondary:hover {
    background: #f1ead8;
}

.vehicle-detail-page .action-btn.excel {
    background: #43835d;
    color: white;
}

.vehicle-detail-page .action-btn.excel:hover {
    background: #326b49;
    color: white;
}

.vehicle-detail-page .action-btn.print {
    background: #426b8a;
    color: white;
}

.vehicle-detail-page .action-btn.print:hover {
    background: #34566f;
}

.vehicle-detail-page .vehicle-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.vehicle-detail-page .summary-card,
.vehicle-detail-page .report-card {
    background: #fffefa;
    border: 1px solid #e5e1d5;
    border-radius: 11px;
    padding: 18px;
    margin-bottom: 16px;
}

.vehicle-detail-page .summary-card span,
.vehicle-detail-page .detail-item span {
    display: block;
    color: #7b857b;
    font-size: 11px;
    margin-bottom: 7px;
}

.vehicle-detail-page .summary-card strong {
    display: block;
    color: #263b35;
    font-size: 18px;
    font-weight: 800;
    overflow-wrap: anywhere;
}

.vehicle-detail-page .card-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 17px;
}

.vehicle-detail-page .card-heading h3 {
    color: #263b35;
    font-size: 15px;
    font-weight: 750;
    margin: 0;
}

.vehicle-detail-page .card-heading h3 i {
    color: #527f70;
    margin-right: 7px;
}

.vehicle-detail-page .card-heading > span:not(.status-pill) {
    color: #527f70;
    font-size: 11px;
    font-weight: 700;
}

.vehicle-detail-page .details-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 19px;
}

.vehicle-detail-page .detail-item strong {
    display: block;
    color: #263b35;
    font-size: 12px;
    overflow-wrap: anywhere;
}

.vehicle-detail-page .status-pill {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.vehicle-detail-page .status-pill.active {
    background: #e8f2e9;
    color: #35724b;
}

.vehicle-detail-page .status-pill.inactive {
    background: #f5e9e5;
    color: #a54c3f;
}

.vehicle-detail-page .student-summary {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 12px;
    margin: 5px 0 18px;
}

.vehicle-detail-page .student-summary > div {
    background: #fffefa;
    border: 1px solid #e5e1d5;
    border-radius: 9px;
    padding: 15px;
}

.vehicle-detail-page .student-summary span {
    display: block;
    color: #7b857b;
    font-size: 10px;
    margin-bottom: 6px;
}

.vehicle-detail-page .student-summary strong {
    display: block;
    color: #263b35;
    font-size: 18px;
    font-weight: 800;
}

.vehicle-detail-page .green-text {
    color: #43835d !important;
}

.vehicle-detail-page .red-text {
    color: #b95b50 !important;
}

.vehicle-detail-page .table-responsive {
    overflow-x: auto;
}

.vehicle-detail-page .vehicle-table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
}

.vehicle-detail-page .vehicle-table th {
    background: #344f43;
    color: white;
    padding: 12px 10px;
    text-align: left;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .3px;
    white-space: nowrap;
}

.vehicle-detail-page .vehicle-table td {
    padding: 11px 10px;
    border-bottom: 1px solid #e5e1d5;
    color: #39483e;
    font-size: 11px;
    vertical-align: middle;
}

.vehicle-detail-page .vehicle-table tbody tr:nth-child(even) {
    background: #f7f6f0;
}

.vehicle-detail-page .vehicle-table tbody tr:hover {
    background: #f1efe6;
}

.vehicle-detail-page .empty-state {
    text-align: center;
    color: #7b857b;
    padding: 25px !important;
}

.vehicle-detail-page .remarks-text {
    color: #5c6b64;
    font-size: 12px;
    line-height: 1.7;
    margin: 0;
}

/* PRINT REPORT */

.vehicle-detail-page .print-report {
    display: none;
    color: #263b35;
    font-family: Arial, sans-serif;
    font-size: 9px;
}

.vehicle-detail-page .print-report table {
    width: 100%;
    border-collapse: collapse;
}

.vehicle-detail-page .print-school-header {
    border-bottom: 2px solid #527f70;
    padding-bottom: 12px;
    margin-bottom: 16px;
}

.vehicle-detail-page .school-logo-cell {
    width: 85px;
    vertical-align: middle;
    padding-right: 15px;
}

.vehicle-detail-page .school-logo {
    max-width: 75px;
    max-height: 75px;
    object-fit: contain;
}

.vehicle-detail-page .school-name {
    font-size: 21px;
    font-weight: bold;
    color: #263b35;
    margin-bottom: 5px;
}

.vehicle-detail-page .school-details {
    color: #5c6b64;
    font-size: 9px;
    margin-top: 4px;
}

.vehicle-detail-page .print-title {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    margin: 15px 0 4px;
}

.vehicle-detail-page .print-subtitle {
    text-align: center;
    color: #7b857b;
    font-size: 9px;
    margin-bottom: 16px;
}

.vehicle-detail-page .print-section-title {
    background: #f1ead8;
    color: #263b35;
    padding: 7px 9px;
    font-size: 10px;
    font-weight: bold;
    margin: 13px 0 7px;
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
}

.vehicle-detail-page .print-details-table td {
    width: 25%;
    border: 1px solid #e5e1d5;
    padding: 7px;
    vertical-align: top;
}

.vehicle-detail-page .print-details-table span,
.vehicle-detail-page .print-details-table strong {
    display: block;
}

.vehicle-detail-page .print-details-table span {
    color: #7b857b;
    font-size: 8px;
    margin-bottom: 4px;
}

.vehicle-detail-page .print-details-table strong {
    color: #263b35;
    font-size: 9px;
    overflow-wrap: anywhere;
}

.vehicle-detail-page .print-data-table {
    margin-top: 7px;
}

.vehicle-detail-page .print-data-table th {
    background: #344f43;
    color: white;
    padding: 7px 5px;
    text-align: left;
    font-size: 7px;
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
}

.vehicle-detail-page .print-data-table td {
    border: 1px solid #e5e1d5;
    padding: 6px 5px;
    font-size: 7px;
    overflow-wrap: anywhere;
}

.vehicle-detail-page .print-data-table tbody tr:nth-child(even) {
    background: #f7f6f0;
}

.vehicle-detail-page .print-empty {
    text-align: center;
    padding: 12px !important;
    color: #7b857b;
}

.vehicle-detail-page .print-remarks {
    border: 1px solid #e5e1d5;
    background: #f7f6f0;
    padding: 8px;
    line-height: 1.5;
}

.vehicle-detail-page .print-footer {
    border-top: 1px solid #e5e1d5;
    padding-top: 8px;
    margin-top: 20px;
    text-align: center;
    color: #7b857b;
    font-size: 8px;
}

@media(max-width: 1000px) {
    .vehicle-detail-page .vehicle-summary-grid,
    .vehicle-detail-page .details-grid,
    .vehicle-detail-page .student-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media(max-width: 560px) {
    .vehicle-detail-page .vehicle-summary-grid,
    .vehicle-detail-page .details-grid,
    .vehicle-detail-page .student-summary {
        grid-template-columns: 1fr;
    }

    .vehicle-detail-page .report-toolbar {
        align-items: flex-start;
    }

    .vehicle-detail-page .toolbar-actions {
        width: 100%;
    }

    .vehicle-detail-page .action-btn {
        flex: 1 1 auto;
    }
}

@page {
    size: A4 landscape;
    margin: 12mm;
}

@media print {
    html, body {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body * {
        visibility: hidden !important;
    }

    .vehicle-detail-page .print-report,
    .vehicle-detail-page .print-report * {
        visibility: visible !important;
    }

    .vehicle-detail-page .print-report {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .vehicle-detail-page .screen-only {
        display: none !important;
    }

    .vehicle-detail-page .print-section-title,
    .vehicle-detail-page .print-data-table th,
    .vehicle-detail-page .print-data-table tbody tr:nth-child(even) {
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .vehicle-detail-page .print-report tr,
    .vehicle-detail-page .print-report td,
    .vehicle-detail-page .print-report th {
        break-inside: avoid;
    }

    .vehicle-detail-page .print-report img {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
