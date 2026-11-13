
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teacher Report</title>

    <style>
        @page {
            margin: 28px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e2a25;
            font-size: 9px;
            margin: 0;
        }

        .school-header {
            width: 100%;
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .school-header table,
        .school-header td {
            border: none;
            padding: 0;
        }

        .school-logo {
            width: 75px;
            height: 65px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e36;
            margin-bottom: 5px;
        }

        .school-contact {
            font-size: 8px;
            color: #5c6b64;
            line-height: 1.6;
        }

        .report-heading {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #2c3e36;
            margin: 15px 0;
        }

        .teacher-header {
            background: #f5f4f0;
            border: 1px solid #d4cfc2;
            padding: 12px;
            margin-bottom: 14px;
        }

        .teacher-name {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e36;
            margin-bottom: 5px;
        }

        .teacher-id {
            color: #5c6b64;
            font-size: 9px;
        }

        .section-title {
            background: #2c3e36;
            color: #ffffff;
            padding: 8px 10px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 0;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d4cfc2;
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            background: #f5f4f0;
            color: #2c3e36;
            font-weight: bold;
        }

        .info-table th {
            width: 25%;
        }

        .paid {
            color: #1a6b3c;
            font-weight: bold;
        }

        .pending {
            color: #a87543;
            font-weight: bold;
        }

        .active {
            color: #1a6b3c;
            font-weight: bold;
        }

        .inactive {
            color: #b95b50;
            font-weight: bold;
        }

        .empty {
            text-align: center;
            padding: 12px;
            color: #7b857b;
            border: 1px solid #d4cfc2;
        }

        .footer {
            margin-top: 22px;
            padding-top: 8px;
            border-top: 1px solid #d4cfc2;
            text-align: center;
            color: #7b857b;
            font-size: 8px;
        }
    </style>
</head>

<body>

@php
    $schoolName = $schoolSetting->school_name ?? config('app.name', 'School Management System');
    $schoolLogo = $schoolSetting->logo_url ?? null;

    $schoolAddress = collect([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ])->filter()->implode(', ');

    $schoolContact = collect([
        $schoolSetting->phone ?? null,
        $schoolSetting->email ?? null,
        $schoolSetting->website ?? null,
    ])->filter()->implode(' | ');

    $teacherName = trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? ''));
@endphp

{{-- DYNAMIC SCHOOL HEADER --}}
<div class="school-header">
    <table style="width:100%;">
        <tr>
            @if($schoolLogo)
                <td style="width:90px; vertical-align:middle;">
                    <img src="{{ $schoolLogo }}" class="school-logo" alt="School Logo">
                </td>
            @endif

            <td style="vertical-align:middle;">
                <div class="school-name">{{ $schoolName }}</div>

                @if($schoolAddress)
                    <div class="school-contact">{{ $schoolAddress }}</div>
                @endif

                @if($schoolContact)
                    <div class="school-contact">{{ $schoolContact }}</div>
                @endif
            </td>
        </tr>
    </table>
</div>

<div class="report-heading">INDIVIDUAL TEACHER REPORT</div>

<div class="teacher-header">
    <div class="teacher-name">{{ $teacherName ?: 'Teacher' }}</div>
    <div class="teacher-id">Teacher ID: {{ $teacher->teacher_id ?? 'N/A' }}</div>
</div>

{{-- PERSONAL INFORMATION --}}
<div class="section-title">Personal Information</div>

<table class="data-table info-table">
    <tr>
        <th>First Name</th>
        <td>{{ $teacher->first_name ?? 'N/A' }}</td>
        <th>Last Name</th>
        <td>{{ $teacher->last_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Teacher ID</th>
        <td>{{ $teacher->teacher_id ?? 'N/A' }}</td>
        <th>Date of Birth</th>
        <td>{{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'N/A' }}</td>
    </tr>
    <tr>
        <th>Gender</th>
        <td>{{ $teacher->gender ?? 'N/A' }}</td>
        <th>Qualification</th>
        <td>{{ $teacher->qualification ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Joining Date</th>
        <td>{{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'N/A' }}</td>
        <th>Status</th>
        <td class="{{ strtolower($teacher->status ?? '') === 'active' ? 'active' : 'inactive' }}">
            {{ ucfirst($teacher->status ?? 'N/A') }}
        </td>
    </tr>
    <tr>
        <th>Subject</th>
        <td colspan="3">{{ $teacher->subject ?? 'N/A' }}</td>
    </tr>
</table>

{{-- CONTACT INFORMATION --}}
<div class="section-title">Contact Information</div>

<table class="data-table info-table">
    <tr>
        <th>Email</th>
        <td>{{ $teacher->email ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{ $teacher->phone ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{ $teacher->address ?? 'N/A' }}</td>
    </tr>
</table>

{{-- ATTENDANCE DETAILS --}}
<div class="section-title">Teacher Attendance Details</div>

@if($attendanceRecords->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Overtime Hours</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceRecords as $record)
                <tr>
                    <td>{{ $record->attendance_date ? \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') : 'N/A' }}</td>
                    <td>{{ $record->status ?? 'N/A' }}</td>
                    <td>{{ $record->overtime_hours ?? 0 }}</td>
                    <td>{{ $record->remarks ?? '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty">No attendance records available.</div>
@endif

{{-- MONTH-WISE SALARY DETAILS --}}
<div class="section-title">Month-wise Salary Details</div>

@if($salaryRecords->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th>Salary Month</th>
                <th>Basic Salary</th>
                <th>Allowances</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaryRecords as $salary)
                <tr>
                    <td>
                        {{ $salary->salary_month ? \Carbon\Carbon::parse($salary->salary_month)->format('F Y') : 'N/A' }}
                    </td>
                    <td>{{ number_format((float) ($salary->basic_salary ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($salary->allowances ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($salary->deductions ?? 0), 2) }}</td>
                    <td>{{ number_format((float) ($salary->net_salary ?? 0), 2) }}</td>
                    <td class="{{ strtolower($salary->payment_status ?? '') === 'paid' ? 'paid' : 'pending' }}">
                        {{ ucfirst($salary->payment_status ?? 'N/A') }}
                    </td>
                    <td>
                        {{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty">No salary records available.</div>
@endif

<div class="footer">
    {{ $schoolName }} | Teacher Report | Generated on {{ now()->format('d M Y, h:i A') }}
</div>

</body>
</html>
