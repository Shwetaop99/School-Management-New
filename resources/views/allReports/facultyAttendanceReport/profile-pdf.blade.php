<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Faculty Attendance Profile</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 25px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #26352e;
            font-size: 10px;
        }

        .school-header {
            text-align: center;
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .school-logo {
            max-height: 65px;
            max-width: 90px;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 21px;
            font-weight: bold;
            color: #263b35;
        }

        .school-details {
            color: #5c6b64;
            font-size: 9px;
            margin-top: 5px;
        }

        .report-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 15px 0;
            color: #263b35;
        }

        .faculty-heading {
            background: #f3ead8;
            padding: 10px;
            border: 1px solid #d4cfc2;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .details-table,
        .attendance-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table {
            margin-bottom: 16px;
        }

        .details-table td {
            padding: 7px 8px;
            border: 1px solid #e2ded4;
            width: 25%;
        }

        .field-label {
            display: block;
            color: #68766e;
            font-size: 8px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .field-value {
            font-weight: bold;
            color: #2c3e36;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #263b35;
            margin: 15px 0 8px;
        }

        .attendance-table th {
            background: #2c3e36;
            color: #fff;
            padding: 9px 7px;
            text-align: left;
            font-size: 9px;
        }

        .attendance-table td {
            padding: 8px 7px;
            border: 1px solid #e5e1d8;
            font-size: 9px;
        }

        .attendance-table tr:nth-child(even) {
            background: #f8f7f3;
        }

        .status-present {
            color: #1a6b3c;
            font-weight: bold;
        }

        .status-absent {
            color: #b3443b;
            font-weight: bold;
        }

        .summary-table {
            margin-top: 8px;
        }

        .summary-table td {
            width: 25%;
            padding: 12px 8px;
            border: 1px solid #d4cfc2;
            background: #f8f7f3;
            text-align: center;
        }

        .summary-label {
            color: #5c6b64;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e36;
            margin-top: 5px;
        }

        .green {
            color: #1a6b3c;
        }

        .footer {
            margin-top: 18px;
            border-top: 1px solid #d4cfc2;
            padding-top: 8px;
            color: #7b857b;
            font-size: 8px;
            text-align: right;
        }

        .no-records {
            text-align: center;
            padding: 15px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="school-header">
        @if(!empty($schoolSetting?->logo_url))
            <img src="{{ $schoolSetting->logo_url }}" class="school-logo" alt="School Logo">
        @endif

        <div class="school-name">
            {{ $schoolSetting->school_name ?? 'School Name' }}
        </div>

        <div class="school-details">
            {{ $schoolSetting->address ?? '' }}
            @if(!empty($schoolSetting?->city)), {{ $schoolSetting->city }}@endif
            @if(!empty($schoolSetting?->district)), {{ $schoolSetting->district }}@endif
            @if(!empty($schoolSetting?->state)), {{ $schoolSetting->state }}@endif
            @if(!empty($schoolSetting?->phone)) | Phone: {{ $schoolSetting->phone }}@endif
            @if(!empty($schoolSetting?->email)) | Email: {{ $schoolSetting->email }}@endif
        </div>
    </div>

    <div class="report-title">FACULTY ATTENDANCE PROFILE</div>

    <div class="faculty-heading">
        {{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Faculty Member' }}
        &nbsp; | &nbsp;
        Faculty ID: {{ $teacher->teacher_id ?? $teacher->id }}
    </div>

    <table class="details-table">
        <tr>
            <td>
                <span class="field-label">Faculty Name</span>
                <span class="field-value">{{ trim(($teacher->first_name ?? '') . ' ' . ($teacher->last_name ?? '')) ?: 'Not available' }}</span>
            </td>
            <td>
                <span class="field-label">Faculty ID</span>
                <span class="field-value">{{ $teacher->teacher_id ?? $teacher->id }}</span>
            </td>
            <td>
                <span class="field-label">Subject</span>
                <span class="field-value">{{ $teacher->subject ?? 'Not specified' }}</span>
            </td>
            <td>
                <span class="field-label">Email</span>
                <span class="field-value">{{ $teacher->email ?? 'Not available' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="field-label">Phone</span>
                <span class="field-value">{{ $teacher->phone ?? 'Not available' }}</span>
            </td>
            <td>
                <span class="field-label">Qualification</span>
                <span class="field-value">{{ $teacher->qualification ?? 'Not available' }}</span>
            </td>
            <td>
                <span class="field-label">Gender</span>
                <span class="field-value">{{ $teacher->gender ?? 'Not available' }}</span>
            </td>
            <td>
                <span class="field-label">Date of Birth</span>
                <span class="field-value">
                    {{ $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') : 'Not available' }}
                </span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="field-label">Joining Date</span>
                <span class="field-value">
                    {{ $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') : 'Not available' }}
                </span>
            </td>
            <td>
                <span class="field-label">Status</span>
                <span class="field-value">{{ ucfirst($teacher->status ?? 'Active') }}</span>
            </td>
            <td colspan="2">
                <span class="field-label">Address</span>
                <span class="field-value">{{ $teacher->address ?? 'Not available' }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Daily Attendance History</div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="17%">Attendance Date</th>
                <th width="15%">Day</th>
                <th width="15%">Status</th>
                <th width="15%">Overtime Hours</th>
                <th width="33%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendanceRecords as $index => $attendance)
                @php
                    $status = strtolower(trim($attendance->status ?? ''));
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l') }}</td>
                    <td class="{{ $status === 'present' ? 'status-present' : ($status === 'absent' ? 'status-absent' : '') }}">
                        {{ $attendance->status ?? 'Not specified' }}
                    </td>
                    <td>{{ number_format((float) ($attendance->overtime_hours ?? 0), 2) }}</td>
                    <td>{{ $attendance->remarks ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-records">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Attendance Summary</div>

    <table class="summary-table">
        <tr>
            <td>
                <div class="summary-label">Present Days</div>
                <div class="summary-value green">{{ $attendanceSummary['present'] ?? 0 }}</div>
            </td>
            <td>
                <div class="summary-label">Absent Days</div>
                <div class="summary-value">{{ $attendanceSummary['absent'] ?? 0 }}</div>
            </td>
            <td>
                <div class="summary-label">Working Days</div>
                <div class="summary-value">{{ $attendanceSummary['working_days'] ?? 0 }}</div>
            </td>
            <td>
                <div class="summary-label">Attendance Percentage</div>
                <div class="summary-value green">{{ number_format($attendanceSummary['percentage'] ?? 0, 2) }}%</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>