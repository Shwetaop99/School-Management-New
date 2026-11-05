<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Route Report</title>

    <style>
        @page {
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #263b35;
            font-size: 10px;
            margin: 0;
        }

        .report-header {
            border-bottom: 2px solid #527f70;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .school-table {
            width: 100%;
            border-collapse: collapse;
        }

        .school-logo-cell {
            width: 85px;
            vertical-align: middle;
            padding-right: 15px;
        }

        .school-logo {
            max-width: 75px;
            max-height: 75px;
        }

        .school-name {
            font-size: 21px;
            font-weight: bold;
            color: #263b35;
            margin-bottom: 5px;
        }

        .school-details {
            font-size: 9px;
            color: #5c6b64;
            margin-top: 4px;
        }

        .report-title {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            margin: 18px 0 4px;
            color: #263b35;
        }

        .report-subtitle {
            text-align: center;
            color: #7b857b;
            font-size: 9px;
            margin-bottom: 18px;
        }

        .section-title {
            background: #f1ead8;
            color: #263b35;
            padding: 8px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            border: 1px solid #e5e1d5;
            padding: 8px;
            width: 25%;
        }

        .field-label {
            color: #7b857b;
            font-size: 8px;
            margin-bottom: 4px;
        }

        .field-value {
            font-weight: bold;
            color: #263b35;
            font-size: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .data-table th {
            background: #344f43;
            color: #ffffff;
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
        }

        .data-table td {
            border: 1px solid #e5e1d5;
            padding: 7px 6px;
            font-size: 8px;
        }

        .data-table tr:nth-child(even) {
            background: #f7f6f0;
        }

        .empty {
            text-align: center;
            padding: 15px;
            color: #7b857b;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #e5e1d5;
            padding-top: 8px;
            color: #7b857b;
            font-size: 8px;
            text-align: center;
        }

        .status {
            font-weight: bold;
        }

        .remarks {
            margin-top: 10px;
            padding: 9px;
            background: #f7f6f0;
            border: 1px solid #e5e1d5;
        }
    </style>
</head>

<body>

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

    $driverName = $driver?->name ?? $route->driver_name ?? 'Not Assigned';
    $driverPhone = $driver?->phone ?? $route->driver_contact ?? 'Not Available';

    $vehicleNumber = $vehicle?->vehicle_number
        ?? $route->assigned_vehicle
        ?? 'Not Assigned';

    $studentCount = $students->pluck('student_id')->unique()->count();
@endphp

<div class="report-header">
    <table class="school-table">
        <tr>
            @if (!empty($logoUrl))
                <td class="school-logo-cell">
                    <img src="{{ $logoUrl }}" class="school-logo" alt="School Logo">
                </td>
            @endif

            <td>
                <div class="school-name">{{ $schoolName }}</div>

                @if ($schoolAddress !== '')
                    <div class="school-details">{{ $schoolAddress }}</div>
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

<div class="report-title">INDIVIDUAL ROUTE REPORT</div>

<div class="report-subtitle">
    Route details and assigned student information
</div>

<div class="section-title">Route Information</div>

<table class="details-table">
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
            <div class="field-value status">{{ ucfirst($route->status ?? 'N/A') }}</div>
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

<div class="section-title">Route Stops</div>

@if ($stops->isNotEmpty())
    <table class="data-table">
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
    <div class="empty">No route stops have been registered.</div>
@endif

<div class="section-title">Students Assigned to This Route</div>

<table class="data-table">
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
                <td>{{ number_format((float) $record->transport_fee, 2) }}</td>
                <td>{{ ucfirst($record->transport_status ?? 'N/A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="empty">
                    No active students are assigned to this route.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if (!empty($route->remarks))
    <div class="section-title">Remarks</div>
    <div class="remarks">{{ $route->remarks }}</div>
@endif

<div class="footer">
    {{ $schoolName }} | Transport Management Report
</div>

</body>
</html>
