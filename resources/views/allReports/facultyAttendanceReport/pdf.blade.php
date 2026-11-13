<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Faculty Attendance Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e2a25;
            font-size: 10px;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .logo {
            max-height: 65px;
            max-width: 85px;
            margin-bottom: 6px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e36;
            margin-bottom: 5px;
        }

        .school-contact {
            color: #5c6b64;
            font-size: 9px;
            line-height: 1.6;
        }

        .report-title {
            font-size: 17px;
            font-weight: bold;
            color: #2c3e36;
            text-align: center;
            margin: 15px 0 5px;
        }

        .report-period {
            text-align: center;
            color: #5c6b64;
            font-size: 10px;
            margin-bottom: 16px;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px;
            margin: 0 -6px 18px;
        }

        .summary td {
            width: 20%;
            background: #f5f4f0;
            border: 1px solid #d4cfc2;
            padding: 10px;
            text-align: center;
        }

        .summary-label {
            color: #5c6b64;
            font-size: 9px;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 17px;
            font-weight: bold;
            color: #2c3e36;
        }

        .faculty-section {
            margin-bottom: 22px;
            page-break-inside: avoid;
        }

        .faculty-heading {
            background: #f5f4f0;
            border: 1px solid #d4cfc2;
            border-left: 4px solid #2c3e36;
            padding: 10px 12px;
            margin-bottom: 8px;
        }

        .faculty-name {
            font-size: 13px;
            font-weight: bold;
            color: #2c3e36;
            margin-bottom: 5px;
        }

        .faculty-info {
            color: #5c6b64;
            font-size: 9px;
        }

        .faculty-stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .faculty-stats td {
            width: 25%;
            padding: 6px;
            border: 1px solid #e5e1d5;
            text-align: center;
            background: #fff;
            font-size: 9px;
        }

        .faculty-stats strong {
            display: block;
            color: #2c3e36;
            font-size: 12px;
            margin-top: 3px;
        }

        table.records {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .records th {
            background: #2c3e36;
            color: #fff;
            padding: 9px 6px;
            font-size: 9px;
            text-align: left;
        }

        .records td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e1d5;
            font-size: 9px;
        }

        .records tr:nth-child(even) {
            background: #f8f7f3;
        }

        .status {
            font-weight: bold;
        }

        .present {
            color: #1a6b3c;
        }

        .absent {
            color: #b95b50;
        }

        .half-day {
            color: #a87543;
        }

        .leave {
            color: #426b8a;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: #7b857b;
        }

        .footer {
            margin-top: 22px;
            padding-top: 8px;
            border-top: 1px solid #d4cfc2;
            text-align: center;
            color: #5c6b64;
            font-size: 8px;
        }
    </style>
</head>

<body>

    {{-- SCHOOL HEADER --}}
    <div class="header">

        @if(!empty($schoolSetting?->logo_url))
            <img src="{{ $schoolSetting->logo_url }}" class="logo" alt="School Logo">
        @endif

        <div class="school-name">
            {{ $schoolSetting->school_name ?? 'School Name' }}
        </div>

        <div class="school-contact">
            {{ $schoolSetting->address ?? '' }}<br>
            {{ $schoolSetting->phone ?? '' }}
            {{ $schoolSetting->email ?? '' }}
        </div>

    </div>

    {{-- REPORT TITLE --}}
    <div class="report-title">
        Faculty Attendance Report
    </div>

    <div class="report-period">
        Period:
        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
        to
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>

    {{-- OVERALL SUMMARY --}}
    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">Present</div>
                <div class="summary-value">{{ $summary['present'] ?? 0 }}</div>
            </td>

            <td>
                <div class="summary-label">Absent</div>
                <div class="summary-value">{{ $summary['absent'] ?? 0 }}</div>
            </td>

            <td>
                <div class="summary-label">Half Days</div>
                <div class="summary-value">{{ $summary['half_days'] ?? 0 }}</div>
            </td>

            <td>
                <div class="summary-label">Leave</div>
                <div class="summary-value">{{ $summary['leave'] ?? 0 }}</div>
            </td>

            <td>
                <div class="summary-label">Overtime Hours</div>
                <div class="summary-value">
                    {{ number_format((float) ($summary['overtime_hours'] ?? 0), 2) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- INDIVIDUAL FACULTY ATTENDANCE --}}
    @php
        $facultyGroups = $attendanceRecords->groupBy('teacher_id');
    @endphp

    @forelse($facultyGroups as $teacherId => $records)

        @php
            $faculty = $records->first()->teacher;

            $presentCount = $records->where('status', 'Present')->count();
            $absentCount = $records->where('status', 'Absent')->count();
            $halfDayCount = $records->where('status', 'Half Day')->count();
            $leaveCount = $records->where('status', 'Leave')->count();

            $overtimeTotal = $records->sum('overtime_hours');
        @endphp

        <div class="faculty-section">

            {{-- FACULTY DETAILS --}}
            <div class="faculty-heading">

                <div class="faculty-name">
                    {{ trim(($faculty->first_name ?? '') . ' ' . ($faculty->last_name ?? '')) }}
                </div>

                <div class="faculty-info">
                    Faculty ID: {{ $faculty->teacher_id ?? '—' }}
                    &nbsp; | &nbsp;
                    Subject: {{ $faculty->subject ?? '—' }}
                </div>

            </div>

            {{-- INDIVIDUAL SUMMARY --}}
            <table class="faculty-stats">
                <tr>
                    <td>
                        Present
                        <strong>{{ $presentCount }}</strong>
                    </td>

                    <td>
                        Absent
                        <strong>{{ $absentCount }}</strong>
                    </td>

                    <td>
                        Half Days
                        <strong>{{ $halfDayCount }}</strong>
                    </td>

                    <td>
                        Leave
                        <strong>{{ $leaveCount }}</strong>
                    </td>
                </tr>
            </table>

            {{-- INDIVIDUAL ATTENDANCE TABLE --}}
            <table class="records">

                <thead>
                    <tr>
                        <th style="width: 7%;">#</th>
                        <th style="width: 20%;">Attendance Date</th>
                        <th style="width: 20%;">Day</th>
                        <th style="width: 18%;">Status</th>
                        <th style="width: 15%;">Overtime Hours</th>
                        <th style="width: 20%;">Remarks</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($records->sortBy('attendance_date') as $index => $record)

                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                            </td>

                            <td class="status {{ strtolower(str_replace(' ', '-', $record->status)) }}">
                                {{ $record->status }}
                            </td>

                            <td>
                                {{ number_format((float) ($record->overtime_hours ?? 0), 2) }}
                            </td>

                            <td>
                                {{ $record->remarks ?: '—' }}
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @empty

        <div class="empty-state">
            No faculty attendance records found for this period.
        </div>

    @endforelse

    {{-- FOOTER --}}
    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
        | Faculty Attendance Report
    </div>

</body>
</html>