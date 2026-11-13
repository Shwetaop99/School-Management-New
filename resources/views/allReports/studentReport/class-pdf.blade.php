<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Class-wise Student Report</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e2a25;
            font-size: 10px;
            margin: 0;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #2c3e36;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .logo {
            max-width: 75px;
            max-height: 75px;
        }

        .school-info {
            vertical-align: middle;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e36;
            margin-bottom: 5px;
        }

        .school-contact {
            font-size: 9px;
            color: #5c6b64;
            line-height: 1.6;
        }

        .report-title {
            text-align: center;
            margin: 18px 0;
        }

        .report-title h2 {
            color: #2c3e36;
            font-size: 17px;
            margin: 0 0 6px;
        }

        .report-title p {
            color: #5c6b64;
            margin: 0;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .summary td {
            background: #f5f4f0;
            border: 1px solid #d4cfc2;
            padding: 9px;
            width: 33.33%;
        }

        .summary-label {
            color: #5c6b64;
            font-size: 8px;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 11px;
            font-weight: bold;
            color: #2c3e36;
        }

        .section-title {
            background: #2c3e36;
            color: #ffffff;
            padding: 9px 11px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        table.students {
            width: 100%;
            border-collapse: collapse;
        }

        .students th {
            background: #eee9dc;
            color: #2c3e36;
            font-weight: bold;
        }

        .students th,
        .students td {
            border: 1px solid #d4cfc2;
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
            vertical-align: middle;
        }

        .students tbody tr:nth-child(even) {
            background: #faf9f6;
        }

        .student-name {
            font-weight: bold;
            color: #1e2a25;
        }

        .muted {
            color: #5c6b64;
        }

        .empty {
            text-align: center;
            padding: 20px;
            border: 1px solid #d4cfc2;
            color: #5c6b64;
        }

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #d4cfc2;
            color: #5c6b64;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if($schoolSetting && $schoolSetting->logo_url)
                        <img
                            src="{{ $schoolSetting->logo_url }}"
                            class="logo"
                            alt="School Logo"
                        >
                    @endif
                </td>

                <td class="school-info">
                    <div class="school-name">
                        {{ $schoolSetting->school_name ?? 'School Name' }}
                    </div>

                    <div class="school-contact">
                        {{ $schoolSetting->address ?? '' }}

                        @if($schoolSetting && $schoolSetting->city)
                            , {{ $schoolSetting->city }}
                        @endif

                        @if($schoolSetting && $schoolSetting->district)
                            , {{ $schoolSetting->district }}
                        @endif

                        @if($schoolSetting && $schoolSetting->state)
                            , {{ $schoolSetting->state }}
                        @endif

                        @if($schoolSetting && $schoolSetting->pincode)
                            - {{ $schoolSetting->pincode }}
                        @endif

                        <br>

                        @if($schoolSetting && $schoolSetting->phone)
                            Phone: {{ $schoolSetting->phone }}
                        @endif

                        @if($schoolSetting && $schoolSetting->email)
                            | Email: {{ $schoolSetting->email }}
                        @endif

                        @if($schoolSetting && $schoolSetting->website)
                            | {{ $schoolSetting->website }}
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        <h2>Class-wise Student Report</h2>
        <p>Academic Year: {{ $academicYear }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">CLASS</div>
                <div class="summary-value">{{ $class }}</div>
            </td>

            <td>
                <div class="summary-label">SECTION</div>
                <div class="summary-value">{{ $section }}</div>
            </td>

            <td>
                <div class="summary-label">TOTAL STUDENTS</div>
                <div class="summary-value">{{ $students->count() }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">
        Student Information
    </div>

    @if($students->count())

        <table class="students">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Roll No.</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Academic Year</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->roll_number ?? '-' }}</td>
                        <td>{{ $student->student_id ?? '-' }}</td>

                        <td>
                            <div class="student-name">
                                {{ $student->full_name ?: trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}
                            </div>
                        </td>

                        <td>{{ $class }}</td>
                        <td>{{ $student->section ?? '-' }}</td>
                        <td>{{ $student->academic_year ?? '-' }}</td>
                        <td>{{ $student->gender ?? '-' }}</td>
                        <td>{{ $student->phone ?? '-' }}</td>
                        <td>{{ $student->email ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @else

        <div class="empty">
            No students found for the selected class, section and academic year.
        </div>

    @endif

    <div class="footer">
        Generated on: {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>
