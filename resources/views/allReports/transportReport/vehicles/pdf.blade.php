<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registered Vehicle Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 22px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #39483e;
            font-size: 9px;
            line-height: 1.45;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #527f70;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .school-name {
            color: #263b35;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .school-details {
            color: #7b857b;
            font-size: 9px;
        }

        .report-title {
            color: #263b35;
            font-size: 16px;
            font-weight: bold;
            margin-top: 12px;
        }

        .generated {
            text-align: right;
            color: #7b857b;
            font-size: 8px;
            margin-bottom: 12px;
        }

        .section-title {
            background: #344f43;
            color: #fffefa;
            padding: 7px 9px;
            font-size: 10px;
            font-weight: bold;
            margin: 13px 0 7px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 9px;
        }

        th, td {
            border: 1px solid #e5e1d5;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f5f3e9;
            color: #263b35;
            font-weight: bold;
        }

        .summary td {
            width: 25%;
            background: #fffefa;
            text-align: center;
            padding: 10px 5px;
        }

        .summary strong {
            display: block;
            color: #263b35;
            font-size: 15px;
        }

        .summary span {
            color: #7b857b;
            font-size: 8px;
        }

        .muted {
            color: #7b857b;
        }

        .status {
            font-weight: bold;
        }

        .remarks {
            border: 1px solid #e5e1d5;
            padding: 9px;
            min-height: 30px;
        }

        .footer {
            border-top: 1px solid #e5e1d5;
            margin-top: 18px;
            padding-top: 7px;
            text-align: center;
            color: #7b857b;
            font-size: 8px;
        }

        .empty {
            text-align: center;
            color: #7b857b;
            padding: 12px;
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
    ])->filter()->implode(', ');

    $schoolPhone = $school->phone ?? $school->contact_number ?? '';
    $schoolEmail = $school->email ?? '';

    $vehicleNumber = $vehicle->vehicle_number ?? 'N/A';
    $driverName = $vehicle->driver?->name
        ?? $vehicle->driver_name
        ?? 'Not Assigned';
@endphp

<div class="header">
    <div class="school-name">{{ $schoolName }}</div>

    @if($schoolAddress)
        <div class="school-details">{{ $schoolAddress }}</div>
    @endif

    @if($schoolPhone || $schoolEmail)
        <div class="school-details">
            {{ $schoolPhone }}
            @if($schoolPhone && $schoolEmail) | @endif
            {{ $schoolEmail }}
        </div>
    @endif

    <div class="report-title">INDIVIDUAL REGISTERED VEHICLE REPORT</div>
    <div class="school-details">Vehicle Registration: {{ $vehicleNumber }}</div>
</div>

<div class="generated">
    Generated on: {{ now()->format('d M Y, h:i A') }}
</div>

<div class="section-title">VEHICLE INFORMATION</div>

<table>
    <tr>
        <th>Vehicle Number</th>
        <th>Vehicle Type</th>
        <th>Model</th>
        <th>Color</th>
        <th>Capacity</th>
        <th>Status</th>
    </tr>
    <tr>
        <td>{{ $vehicleNumber }}</td>
        <td>{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
        <td>{{ $vehicle->vehicle_model ?? 'N/A' }}</td>
        <td>{{ $vehicle->vehicle_color ?? 'N/A' }}</td>
        <td>{{ $vehicle->capacity ?? 'N/A' }}</td>
        <td class="status">{{ ucfirst($vehicle->status ?? 'N/A') }}</td>
    </tr>
</table>

<div class="section-title">DRIVER INFORMATION</div>

<table>
    <tr>
        <th>Driver Name</th>
        <th>Contact</th>
        <th>License Number</th>
    </tr>
    <tr>
        <td>{{ $driverName }}</td>
        <td>{{ $vehicle->driver_contact ?? 'N/A' }}</td>
        <td>{{ $vehicle->driver_license_number ?? 'N/A' }}</td>
    </tr>
</table>

<div class="section-title">DOCUMENT EXPIRY INFORMATION</div>

<table>
    <tr>
        <th>Insurance Expiry</th>
        <th>Fitness Expiry</th>
        <th>Permit Expiry</th>
    </tr>
    <tr>
        <td>{{ $vehicle->insurance_expiry?->format('d M Y') ?? 'Not Available' }}</td>
        <td>{{ $vehicle->fitness_expiry?->format('d M Y') ?? 'Not Available' }}</td>
        <td>{{ $vehicle->permit_expiry?->format('d M Y') ?? 'Not Available' }}</td>
    </tr>
</table>

<div class="section-title">TRANSPORT SUMMARY</div>

<table class="summary">
    <tr>
        <td>
            <strong>{{ number_format($assignedStudents) }}</strong>
            <span>Assigned Students</span>
        </td>
        <td>
            <strong>{{ number_format($activeStudents) }}</strong>
            <span>Active Students</span>
        </td>
        <td>
            <strong>{{ number_format($inactiveStudents) }}</strong>
            <span>Inactive Students</span>
        </td>
        <td>
            <strong>₹{{ number_format((float) $totalTransportFees, 2) }}</strong>
            <span>Total Transport Fees</span>
        </td>
    </tr>
</table>

<div class="section-title">ASSIGNED ROUTES</div>

<table>
    <thead>
        <tr>
            <th>Route Number</th>
            <th>Route Name</th>
            <th>Starting Point</th>
            <th>Destination</th>
            <th>Driver</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($routes as $route)
            <tr>
                <td>{{ $route->route_number ?? 'N/A' }}</td>
                <td>{{ $route->route_name ?? 'N/A' }}</td>
                <td>{{ $route->starting_point ?? 'N/A' }}</td>
                <td>{{ $route->destination ?? 'N/A' }}</td>
                <td>{{ $route->driver?->name ?? $route->driver_name ?? 'N/A' }}</td>
                <td>{{ ucfirst($route->status ?? 'N/A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="empty">No routes assigned to this vehicle.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">ASSIGNED STUDENTS</div>

<table>
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Transport Status</th>
            <th>Pickup Point</th>
            <th>Drop Point</th>
            <th>Transport Fee</th>
            <th>Payment Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $record)
            @php
                $student = $record->student;

                $studentName = $student
                    ? trim(collect([
                        $student->first_name ?? null,
                        $student->middle_name ?? null,
                        $student->last_name ?? null,
                    ])->filter()->implode(' '))
                    : 'Student Not Found';
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->student_id ?? 'N/A' }}</td>
                <td>{{ $studentName }}</td>
                <td>{{ ucfirst($record->transport_status ?? 'N/A') }}</td>
                <td>{{ $record->pickup_point ?? 'N/A' }}</td>
                <td>{{ $record->drop_point ?? 'N/A' }}</td>
                <td>₹{{ number_format((float) ($record->transport_fee ?? 0), 2) }}</td>
                <td>{{ ucfirst($record->payment_status ?? 'N/A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty">
                    No students are assigned to this vehicle.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if(!empty($vehicle->remarks))
    <div class="section-title">REMARKS</div>
    <div class="remarks">{{ $vehicle->remarks }}</div>
@endif

<div class="footer">
    {{ $schoolName }} | Registered Vehicle Report | Generated {{ now()->format('d M Y') }}
</div>

</body>
</html>
