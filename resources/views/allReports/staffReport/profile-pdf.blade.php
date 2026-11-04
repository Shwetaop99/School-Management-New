 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Other Staff Profile Report</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 28px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #263b35;
            font-size: 10px;
            line-height: 1.5;
            margin: 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #344f43;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 65px;
            max-height: 65px;
            margin-bottom: 6px;
        }

        .school-name {
            font-family: DejaVu Serif, serif;
            font-size: 21px;
            font-weight: bold;
            color: #20352c;
            margin: 0 0 5px;
        }

        .school-contact {
            color: #5c6b64;
            font-size: 9px;
        }

        .report-title {
            margin-top: 13px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #344f43;
        }

        .subtitle {
            color: #7b857b;
            font-size: 9px;
        }

        .hero {
            background: #f8f1e3;
            border: 1px solid #d4cfc2;
            padding: 13px 15px;
            margin-bottom: 16px;
        }

        .hero-name {
            font-size: 16px;
            font-weight: bold;
            color: #20352c;
        }

        .hero-meta {
            color: #5c6b64;
            margin-top: 4px;
        }

        .section {
            margin-top: 16px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #344f43;
            color: #ffffff;
            padding: 7px 10px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .5px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid #e5e1d5;
            padding: 7px 8px;
            vertical-align: top;
        }

        td.label {
            width: 24%;
            background: #f5f4f0;
            color: #5c6b64;
            font-weight: bold;
        }

        td.value {
            width: 26%;
            color: #263b35;
        }

        th {
            background: #f5f4f0;
            text-align: left;
            color: #344f43;
        }

        .empty {
            color: #7b857b;
            text-align: center;
            padding: 12px;
        }

        .footer {
            margin-top: 25px;
            padding-top: 8px;
            border-top: 1px solid #d4cfc2;
            text-align: center;
            color: #7b857b;
            font-size: 8px;
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
        }
    </style>
</head>

<body>

@php
    $isDriver = strtolower(trim($otherStaff->designation ?? '')) === 'driver';
@endphp

<div class="header">
    @if(!empty($schoolSetting?->logo_url))
        <img src="{{ $schoolSetting->logo_url }}" class="logo" alt="School Logo">
    @endif

    <div class="school-name">
        {{ $schoolSetting->school_name ?? 'School Name' }}
    </div>

    <div class="school-contact">
        {{ collect([
            $schoolSetting->address ?? null,
            $schoolSetting->city ?? null,
            $schoolSetting->district ?? null,
            $schoolSetting->state ?? null,
            $schoolSetting->pincode ?? null
        ])->filter()->implode(', ') }}
    </div>

    <div class="school-contact">
        @if($schoolSetting?->phone)
            Phone: {{ $schoolSetting->phone }}
        @endif

        @if($schoolSetting?->email)
            | Email: {{ $schoolSetting->email }}
        @endif
    </div>

    <div class="report-title">OTHER STAFF PROFILE REPORT</div>
    <div class="subtitle">Individual Staff Record</div>
</div>

<div class="hero">
    <div class="hero-name">{{ $otherStaff->name }}</div>
    <div class="hero-meta">
        Staff ID: {{ $otherStaff->staff_id ?? 'N/A' }}
        &nbsp; | &nbsp;
        Profession: {{ $otherStaff->designation ?? 'N/A' }}
        &nbsp; | &nbsp;
        Status: <span class="status">{{ $otherStaff->status ?? 'N/A' }}</span>
    </div>
</div>

<div class="section">
    <div class="section-title">PERSONAL INFORMATION</div>

    <table>
        <tr>
            <td class="label">Full Name</td>
            <td class="value">{{ $otherStaff->name ?? 'N/A' }}</td>
            <td class="label">Staff ID</td>
            <td class="value">{{ $otherStaff->staff_id ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Gender</td>
            <td class="value">{{ $otherStaff->gender ?? 'N/A' }}</td>
            <td class="label">Date of Birth</td>
            <td class="value">{{ $otherStaff->date_of_birth?->format('d M Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Phone</td>
            <td class="value">{{ $otherStaff->phone ?? 'N/A' }}</td>
            <td class="label">Email</td>
            <td class="value">{{ $otherStaff->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Address</td>
            <td colspan="3" class="value">{{ $otherStaff->address ?? 'N/A' }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">EMPLOYMENT INFORMATION</div>

    <table>
        <tr>
            <td class="label">Profession</td>
            <td class="value">{{ $otherStaff->designation ?? 'N/A' }}</td>
            <td class="label">Department</td>
            <td class="value">{{ $otherStaff->department ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Qualification</td>
            <td class="value">{{ $otherStaff->qualification ?? 'N/A' }}</td>
            <td class="label">Joining Date</td>
            <td class="value">{{ $otherStaff->joining_date?->format('d M Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Employment Status</td>
            <td colspan="3" class="value">
                <span class="status">{{ $otherStaff->status ?? 'N/A' }}</span>
            </td>
        </tr>
    </table>
</div>

@if($isDriver)
    <div class="section">
        <div class="section-title">DRIVER LICENCE DETAILS</div>

        <table>
            <tr>
                <td class="label">Licence Number</td>
                <td class="value">{{ $otherStaff->license_number ?? 'N/A' }}</td>
                <td class="label">Licence Expiry</td>
                <td class="value">{{ $otherStaff->license_expiry?->format('d M Y') ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">ASSIGNED VEHICLES</div>

        <table>
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
                        <td>{{ $vehicle->vehicle_number ?? 'N/A' }}</td>
                        <td>{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
                        <td>{{ $vehicle->vehicle_model ?? 'N/A' }}</td>
                        <td>{{ $vehicle->capacity ?? 'N/A' }}</td>
                        <td>{{ $vehicle->status ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No vehicles assigned.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">ASSIGNED TRANSPORT ROUTES</div>

        <table>
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
                        <td>{{ $route->route_number ?? 'N/A' }}</td>
                        <td>{{ $route->route_name ?? 'N/A' }}</td>
                        <td>{{ $route->starting_point ?? 'N/A' }}</td>
                        <td>{{ $route->destination ?? 'N/A' }}</td>
                        <td>{{ $route->vehicle?->vehicle_number ?? $route->assigned_vehicle ?? 'N/A' }}</td>
                        <td>{{ $route->status ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">No routes assigned.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endif

<div class="footer">
    Generated on {{ now()->format('d M Y, h:i A') }}
    <br>
    This is a computer-generated staff profile report.
</div>

</body>
</html>