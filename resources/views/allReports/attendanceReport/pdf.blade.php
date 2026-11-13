<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>

    <style>
        @page {
            margin: 25px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #263b35;
        }

        .report-header {
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 2px solid #527f70;
        }

        .school-table {
            width: 100%;
            border-collapse: collapse;
        }

        .school-table td {
            border: none;
            padding: 3px;
            vertical-align: middle;
        }

        .school-logo-cell {
            width: 85px;
            text-align: center;
        }

        .school-logo {
            width: 68px;
            height: 68px;
        }

        .school-name {
            font-size: 19px;
            font-weight: bold;
            color: #263b35;
            margin-bottom: 5px;
        }

        .school-details {
            font-size: 9px;
            color: #5c6b64;
            line-height: 1.6;
        }

        .report-title {
            text-align: center;
            margin-top: 14px;
        }

        .report-title h2 {
            font-size: 16px;
            margin: 0 0 5px;
            color: #263b35;
        }

        .report-title p {
            margin: 0;
            color: #5c6b64;
            font-size: 10px;
        }

        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }

        .attendance-table th {
            background: #e9eee8;
            color: #263b35;
            font-weight: bold;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #d4cfc2;
            padding: 6px 5px;
            text-align: left;
        }

        .attendance-table tr {
            page-break-inside: avoid;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 18px;
            padding-top: 7px;
            border-top: 1px solid #d4cfc2;
            text-align: right;
            color: #5c6b64;
            font-size: 8px;
        }
    </style>
</head>

<body>

    @php
        $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
        $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';

        $schoolAddress = collect([
            $schoolSetting->address ?? null,
            $schoolSetting->city ?? null,
            $schoolSetting->district ?? null,
            $schoolSetting->state ?? null,
            $schoolSetting->pincode ?? null,
        ])->filter(function ($value) {
            return trim((string) $value) !== '';
        })->implode(', ');

        $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
        $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
        $logoUrl = $schoolSetting->logo_url ?? null;
    @endphp

    <div class="report-header">
        <table class="school-table">
            <tr>
                @if (!empty($logoUrl))
                    <td class="school-logo-cell">
                        <img
                            src="{{ $logoUrl }}"
                            class="school-logo"
                            alt="School Logo"
                        >
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

        <div class="report-title">
            <h2>Attendance Report</h2>
            <p>Student Attendance Records</p>
        </div>
    </div>

    <table class="attendance-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Academic Year</th>
                <th>Class</th>
                <th>Section</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody>
            @forelse($attendance as $record)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $record->student->student_id ?? '-' }}</td>

                    <td>
                        @if ($record->student)
                            {{ $record->student->first_name }}
                            {{ $record->student->last_name }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $record->academic_year ?? '-' }}</td>
                    <td>{{ $record->class ?? '-' }}</td>
                    <td>{{ $record->section ?? '-' }}</td>

                    <td>
                        @if ($record->attendance_date)
                            {{ $record->attendance_date->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="status">{{ ucfirst($record->status ?? '-') }}</td>
                    <td>{{ $record->remarks ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">
                        No attendance records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>
