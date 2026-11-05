
@extends('layouts.app')

@section('title', 'Individual Route Report | Admin')
@section('page-title', 'Individual Route Report')

@section('content')
@php
    $schoolName = trim((string) ($school->school_name ?? ''));
    $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

    $schoolAddress = collect([
        $school->address ?? null,
        $school->city ?? null,
        $school->district ?? null,
        $school->state ?? null,
        $school->pincode ?? null,
    ])->filter(fn ($value) => trim((string) $value) !== '')
      ->implode(', ');

    $schoolPhone = trim((string) ($school->phone ?? ''));
    $schoolEmail = trim((string) ($school->email ?? ''));
    $logoUrl = $school->logo_url ?? null;

    $vehicle = $route->vehicle;
    $driver = $route->driver;

    $vehicleNumber = $vehicle?->vehicle_number
        ?? $route->assigned_vehicle
        ?? 'Not Assigned';

    $driverName = $driver?->name
        ?? $route->driver_name
        ?? 'Not Assigned';

    $driverPhone = $driver?->phone
        ?? $route->driver_contact
        ?? 'Not Available';

    $studentCount = $students->pluck('student_id')->unique()->count();

    $money = fn ($amount) => '₹' . number_format((float) $amount, 2);
@endphp

<div class="route-detail-page">
    <div class="container-fluid py-4">

        {{-- TOP LEFT BACK BUTTON --}}
        <div class="route-back-row screen-only">
            <a href="{{ route('admin.reports.transport.routes') }}"
               class="back-btn">
                <span class="back-arrow">&larr;</span>
                <span>Back to Routes</span>
            </a>
        </div>

        {{-- SCREEN TOOLBAR --}}
        <div class="report-toolbar screen-only">
            <div>
                <h2>Individual Route Report</h2>
                <p>Complete information about this registered transport route.</p>
            </div>

            <div class="toolbar-actions">

                <button type="button"
                        onclick="window.print()"
                        class="action-btn secondary">
                    <span>⎙</span> Print Report
                </button>

                <a href="{{ route('admin.reports.transport.routes.pdf', $route->id) }}"
                   class="action-btn primary">
                    <span>↓</span> Download PDF
                </a>

                <a href="{{ route('admin.reports.transport.routes.excel', $route->id) }}"
                   class="action-btn excel">
                    <span>▦</span> Download Excel
                </a>

            </div>
        </div>

        {{-- NORMAL SCREEN REPORT --}}
        <div class="screen-report screen-only">

            {{-- SUMMARY --}}
            <div class="route-summary-grid">
                <div class="summary-card">
                    <span>Route Number</span>
                    <strong>{{ $route->route_number ?? 'N/A' }}</strong>
                </div>

                <div class="summary-card">
                    <span>Assigned Students</span>
                    <strong>{{ $studentCount }}</strong>
                </div>

                <div class="summary-card">
                    <span>Assigned Vehicle</span>
                    <strong>{{ $vehicleNumber }}</strong>
                </div>

                <div class="summary-card">
                    <span>Route Status</span>
                    <strong>{{ ucfirst($route->status ?? 'N/A') }}</strong>
                </div>
            </div>

            {{-- ROUTE INFORMATION --}}
            <div class="report-card">
                <div class="card-heading">
                    <h3>Route Information</h3>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <span>Route Name</span>
                        <strong>{{ $route->route_name ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Starting Point</span>
                        <strong>{{ $route->starting_point ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Destination</span>
                        <strong>{{ $route->destination ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Route Number</span>
                        <strong>{{ $route->route_number ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            {{-- VEHICLE AND DRIVER --}}
            <div class="report-card">
                <div class="card-heading">
                    <h3>Vehicle & Driver Information</h3>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <span>Vehicle Number</span>
                        <strong>{{ $vehicleNumber }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Vehicle Type</span>
                        <strong>{{ $vehicle?->vehicle_type ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Vehicle Model</span>
                        <strong>{{ $vehicle?->vehicle_model ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Vehicle Capacity</span>
                        <strong>{{ $vehicle?->capacity ?? 'N/A' }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Assigned Driver</span>
                        <strong>{{ $driverName }}</strong>
                    </div>

                    <div class="detail-item">
                        <span>Driver Contact</span>
                        <strong>{{ $driverPhone }}</strong>
                    </div>
                </div>
            </div>

            {{-- ROUTE STOPS --}}
            <div class="report-card">
                <div class="card-heading">
                    <h3>Route Stops</h3>
                    <span>{{ $stops->count() }} Stops</span>
                </div>

                @if ($stops->isNotEmpty())
                    <div class="stops-list">
                        @foreach ($stops as $index => $stop)
                            <div class="stop-item">
                                <span class="stop-number">{{ $index + 1 }}</span>
                                <strong>{{ $stop }}</strong>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        No stops registered for this route.
                    </div>
                @endif
            </div>

            {{-- ASSIGNED STUDENTS --}}
            <div class="report-card">
                <div class="card-heading">
                    <h3>Students Assigned to This Route</h3>
                    <span>{{ $studentCount }} Students</span>
                </div>

                <div class="table-responsive">
                    <table class="route-table">
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
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($students as $index => $record)
                                @php
                                    $student = $record->student;

                                    $studentName = $student
                                        ? trim(collect([
                                            $student->first_name,
                                            $student->middle_name,
                                            $student->last_name,
                                        ])->filter()->implode(' '))
                                        : 'Student Not Found';
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student?->student_id ?? 'N/A' }}</td>
                                    <td>{{ $studentName }}</td>
                                    <td>{{ $student?->class ?? 'N/A' }}</td>
                                    <td>{{ $student?->section ?? 'N/A' }}</td>
                                    <td>{{ $record->pickup_point ?? 'N/A' }}</td>
                                    <td>{{ $record->drop_point ?? 'N/A' }}</td>
                                    <td>{{ $money($record->transport_fee) }}</td>
                                    <td>
                                        <span class="status-pill">
                                            {{ ucfirst($record->transport_status ?? 'N/A') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="empty-state">
                                        No active students are assigned to this route.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- REMARKS --}}
            @if ($route->remarks)
                <div class="report-card">
                    <div class="card-heading">
                        <h3>Remarks</h3>
                    </div>

                    <p class="remarks-text">{{ $route->remarks }}</p>
                </div>
            @endif

        </div>

        {{-- PRINT REPORT: SAME STRUCTURE AS PDF --}}
        <div class="print-report">

            {{-- SCHOOL HEADER --}}
            <div class="print-school-header">
                <table class="school-table">
                    <tr>
                        @if (!empty($logoUrl))
                            <td class="school-logo-cell">
                                <img src="{{ $logoUrl }}"
                                     class="school-logo"
                                     alt="School Logo">
                            </td>
                        @endif

                        <td>
                            <div class="school-name">{{ $schoolName }}</div>

                            @if ($schoolAddress !== '')
                                <div class="school-details">
                                    {{ $schoolAddress }}
                                </div>
                            @endif

                            @if ($schoolPhone !== '' || $schoolEmail !== '')
                                <div class="school-details">
                                    @if ($schoolPhone !== '')
                                        Phone: {{ $schoolPhone }}
                                    @endif

                                    @if ($schoolPhone !== '' && $schoolEmail !== '')
                                        |
                                    @endif

                                    @if ($schoolEmail !== '')
                                        Email: {{ $schoolEmail }}
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="print-report-title">
                INDIVIDUAL ROUTE REPORT
            </div>

            <div class="print-report-subtitle">
                Route details and assigned student information
            </div>

            {{-- ROUTE INFORMATION --}}
            <div class="print-section-title">
                Route Information
            </div>

            <table class="print-details-table">
                <tr>
                    <td>
                        <div class="field-label">Route Number</div>
                        <div class="field-value">{{ $route->route_number ?? 'N/A' }}</div>
                    </td>

                    <td>
                        <div class="field-label">Route Name</div>
                        <div class="field-value">{{ $route->route_name ?? 'N/A' }}</div>
                    </td>

                    <td>
                        <div class="field-label">Starting Point</div>
                        <div class="field-value">{{ $route->starting_point ?? 'N/A' }}</div>
                    </td>

                    <td>
                        <div class="field-label">Destination</div>
                        <div class="field-value">{{ $route->destination ?? 'N/A' }}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="field-label">Assigned Vehicle</div>
                        <div class="field-value">{{ $vehicleNumber }}</div>
                    </td>

                    <td>
                        <div class="field-label">Vehicle Type</div>
                        <div class="field-value">{{ $vehicle?->vehicle_type ?? 'N/A' }}</div>
                    </td>

                    <td>
                        <div class="field-label">Assigned Driver</div>
                        <div class="field-value">{{ $driverName }}</div>
                    </td>

                    <td>
                        <div class="field-label">Driver Contact</div>
                        <div class="field-value">{{ $driverPhone }}</div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="field-label">Route Status</div>
                        <div class="field-value">{{ ucfirst($route->status ?? 'N/A') }}</div>
                    </td>

                    <td>
                        <div class="field-label">Assigned Students</div>
                        <div class="field-value">{{ $studentCount }}</div>
                    </td>

                    <td colspan="2">
                        <div class="field-label">Report Generated</div>
                        <div class="field-value">{{ now()->format('d M Y, h:i A') }}</div>
                    </td>
                </tr>
            </table>

            {{-- ROUTE STOPS --}}
            <div class="print-section-title">
                Route Stops
            </div>

            @if ($stops->isNotEmpty())
                <table class="print-data-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;">Stop No.</th>
                            <th>Stop Name</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($stops as $index => $stop)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $stop }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="print-empty">
                    No route stops have been registered.
                </div>
            @endif

            {{-- ASSIGNED STUDENTS --}}
            <div class="print-section-title">
                Students Assigned to This Route
            </div>

            <table class="print-data-table">
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
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($students as $index => $record)
                        @php
                            $student = $record->student;

                            $studentName = $student
                                ? trim(collect([
                                    $student->first_name,
                                    $student->middle_name,
                                    $student->last_name,
                                ])->filter()->implode(' '))
                                : 'Student Not Found';
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student?->student_id ?? 'N/A' }}</td>
                            <td>{{ $studentName }}</td>
                            <td>{{ $student?->class ?? 'N/A' }}</td>
                            <td>{{ $student?->section ?? 'N/A' }}</td>
                            <td>{{ $record->pickup_point ?? 'N/A' }}</td>
                            <td>{{ $record->drop_point ?? 'N/A' }}</td>
                            <td>{{ $money($record->transport_fee) }}</td>
                            <td>{{ ucfirst($record->transport_status ?? 'N/A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="print-empty">
                                No active students are assigned to this route.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- REMARKS --}}
            @if (!empty($route->remarks))
                <div class="print-section-title">Remarks</div>

                <div class="print-remarks">
                    {{ $route->remarks }}
                </div>
            @endif

            <div class="print-footer">
                {{ $schoolName }} | Transport Management Report
            </div>

        </div>

    </div>
</div>

<style>
/* =========================================
   MAIN PAGE
========================================= */

.route-detail-page {
    background: #f7f6f0;
    min-height: 100vh;
    color: #263b35;
}

/* BACK BUTTON - TOP LEFT */

.route-detail-page .route-back-row {
    margin-bottom: 18px;
}

.route-detail-page .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 9px 15px;
    background: #fffefa;
    color: #263b35;
    border: 1px solid #d4cfc2;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    transition: all .2s ease;
}

.route-detail-page .back-btn:hover {
    background: #344f43;
    color: #fff;
    border-color: #344f43;
}

.route-detail-page .back-arrow {
    font-size: 19px;
    line-height: 1;
}

/* TOOLBAR */

.route-detail-page .report-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.route-detail-page .report-toolbar h2 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 5px;
}

.route-detail-page .report-toolbar p {
    color: #7b857b;
    margin: 0;
    font-size: 13px;
}

.route-detail-page .toolbar-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.route-detail-page .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid #d4cfc2;
    cursor: pointer;
    transition: all .2s ease;
}

.route-detail-page .action-btn.primary {
    background: #2c3e36;
    color: #fff;
    border-color: #2c3e36;
}

.route-detail-page .action-btn.primary:hover {
    background: #3a4f45;
}

.route-detail-page .action-btn.secondary {
    background: #fffefa;
    color: #263b35;
}

.route-detail-page .action-btn.secondary:hover {
    background: #f1ead8;
}

.route-detail-page .action-btn.excel {
    background: #43835d;
    color: #fff;
    border-color: #43835d;
}

.route-detail-page .action-btn.excel:hover {
    background: #326b49;
}

/* SUMMARY */

.route-detail-page .route-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}

.route-detail-page .summary-card,
.route-detail-page .report-card {
    background: #fffefa;
    border: 1px solid #e5e1d5;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 16px;
}

.route-detail-page .summary-card span,
.route-detail-page .detail-item span {
    display: block;
    color: #7b857b;
    font-size: 11px;
    margin-bottom: 7px;
}

.route-detail-page .summary-card strong {
    font-size: 17px;
    overflow-wrap: anywhere;
}

/* CARDS */

.route-detail-page .card-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.route-detail-page .card-heading h3 {
    font-size: 15px;
    font-weight: 700;
    margin: 0;
}

.route-detail-page .card-heading > span {
    color: #527f70;
    font-size: 11px;
    font-weight: 700;
}

/* DETAILS */

.route-detail-page .details-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
}

.route-detail-page .detail-item strong {
    font-size: 12px;
    overflow-wrap: anywhere;
}

/* STOPS */

.route-detail-page .stops-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.route-detail-page .stop-item {
    display: flex;
    align-items: center;
    gap: 9px;
    background: #f7f6f0;
    border: 1px solid #e5e1d5;
    border-radius: 8px;
    padding: 9px 13px;
    font-size: 12px;
}

.route-detail-page .stop-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 23px;
    height: 23px;
    border-radius: 50%;
    background: #344f43;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
}

/* STUDENT TABLE */

.route-detail-page .table-responsive {
    overflow-x: auto;
}

.route-detail-page .route-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 850px;
}

.route-detail-page .route-table th {
    background: #344f43;
    color: #fff;
    padding: 11px 9px;
    text-align: left;
    font-size: 10px;
    white-space: nowrap;
}

.route-detail-page .route-table td {
    padding: 11px 9px;
    border-bottom: 1px solid #e5e1d5;
    font-size: 11px;
}

.route-detail-page .route-table tbody tr:nth-child(even) {
    background: #f7f6f0;
}

.route-detail-page .status-pill {
    display: inline-block;
    background: #e8f1e9;
    color: #43835d;
    border-radius: 20px;
    padding: 4px 9px;
    font-size: 10px;
    font-weight: 700;
}

.route-detail-page .empty-state {
    text-align: center;
    color: #7b857b;
    padding: 22px !important;
}

.route-detail-page .remarks-text {
    margin: 0;
    color: #5c6b64;
    font-size: 12px;
}

/* =========================================
   PRINT REPORT
   Same structure as PDF
========================================= */

.print-report {
    display: none;
    color: #263b35;
    font-family: Arial, sans-serif;
    font-size: 10px;
}

.print-report table {
    width: 100%;
    border-collapse: collapse;
}

.print-school-header {
    border-bottom: 2px solid #527f70;
    padding-bottom: 12px;
    margin-bottom: 18px;
}

.print-report .school-table {
    width: 100%;
    border-collapse: collapse;
}

.print-report .school-logo-cell {
    width: 85px;
    vertical-align: middle;
    padding-right: 15px;
}

.print-report .school-logo {
    max-width: 75px;
    max-height: 75px;
    object-fit: contain;
}

.print-report .school-name {
    font-size: 21px;
    font-weight: bold;
    color: #263b35;
    margin-bottom: 5px;
}

.print-report .school-details {
    font-size: 9px;
    color: #5c6b64;
    margin-top: 4px;
}

.print-report-title {
    text-align: center;
    font-size: 17px;
    font-weight: bold;
    margin: 18px 0 4px;
    color: #263b35;
}

.print-report-subtitle {
    text-align: center;
    color: #7b857b;
    font-size: 9px;
    margin-bottom: 18px;
}

.print-section-title {
    background: #f1ead8;
    color: #263b35;
    padding: 8px 10px;
    font-size: 11px;
    font-weight: bold;
    margin: 15px 0 8px;
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
}

.print-details-table td {
    border: 1px solid #e5e1d5;
    padding: 8px;
    width: 25%;
}

.print-report .field-label {
    color: #7b857b;
    font-size: 8px;
    margin-bottom: 4px;
}

.print-report .field-value {
    font-weight: bold;
    color: #263b35;
    font-size: 10px;
    overflow-wrap: anywhere;
}

.print-data-table {
    margin-top: 8px;
}

.print-data-table th {
    background: #344f43;
    color: #fff;
    padding: 8px 6px;
    text-align: left;
    font-size: 8px;
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
}

.print-data-table td {
    border: 1px solid #e5e1d5;
    padding: 7px 6px;
    font-size: 8px;
    overflow-wrap: anywhere;
}

.print-data-table tbody tr:nth-child(even) {
    background: #f7f6f0;
}

.print-empty {
    text-align: center;
    padding: 15px;
    color: #7b857b;
}

.print-remarks {
    margin-top: 10px;
    padding: 9px;
    background: #f7f6f0;
    border: 1px solid #e5e1d5;
    font-size: 9px;
}

.print-footer {
    margin-top: 25px;
    border-top: 1px solid #e5e1d5;
    padding-top: 8px;
    color: #7b857b;
    font-size: 8px;
    text-align: center;
}

/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 900px) {
    .route-detail-page .route-summary-grid,
    .route-detail-page .details-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 520px) {
    .route-detail-page .route-summary-grid,
    .route-detail-page .details-grid {
        grid-template-columns: 1fr;
    }

    .route-detail-page .report-toolbar {
        align-items: flex-start;
    }

    .route-detail-page .toolbar-actions {
        width: 100%;
    }

    .route-detail-page .action-btn {
        flex: 1 1 auto;
    }
}

/* =========================================
   PRINT SETTINGS
========================================= */

@page {
    size: A4 landscape;
    margin: 25px 30px;
}

@media print {

    html,
    body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #263b35 !important;
    }

    body * {
        visibility: hidden !important;
    }

    .print-report,
    .print-report * {
        visibility: visible !important;
    }

    .print-report {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        font-family: Arial, sans-serif;
        color: #263b35;
        font-size: 10px;
    }

    .screen-only {
        display: none !important;
    }

    .print-section-title,
    .print-data-table th,
    .print-data-table tbody tr:nth-child(even) {
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }

    .print-report tr,
    .print-report td,
    .print-report th {
        break-inside: avoid;
    }

    .print-school-header,
    .print-report-title,
    .print-report-subtitle {
        break-inside: avoid;
    }

    .print-data-table {
        page-break-inside: auto;
    }

    .print-data-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    .print-report img {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection
