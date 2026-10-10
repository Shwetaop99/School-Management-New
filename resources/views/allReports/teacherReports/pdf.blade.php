<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Teacher Report</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 30px;
            color: #222;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #147cf5;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            color: #147cf5;
            font-size: 24px;
        }

        .header p {
            margin: 6px 0 0;
            color: #666;
            font-size: 12px;
        }

        .teacher-header {
            background: #f3f7fc;
            border: 1px solid #d9e5f2;
            padding: 15px;
            margin-bottom: 20px;
        }

        .teacher-name {
            font-size: 20px;
            font-weight: bold;
            color: #1268ca;
            margin-bottom: 6px;
        }

        .teacher-id {
            color: #666;
        }

        .section-title {
            background: #147cf5;
            color: white;
            padding: 9px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #d9e1ea;
            padding: 9px;
            text-align: left;
        }

        th {
            width: 35%;
            background: #f3f7fc;
            font-weight: bold;
            color: #333;
        }

        td {
            color: #444;
        }

        .status {
            font-weight: bold;
        }

        .active {
            color: #16a34a;
        }

        .inactive {
            color: #dc2626;
        }

        .summary-table td {
            text-align: center;
            width: 33.33%;
        }

        .summary-number {
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: #147cf5;
            margin-bottom: 4px;
        }

        .summary-label {
            color: #666;
            font-size: 10px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #777;
            font-size: 10px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Teacher Report</h1>
        <p>Gurukul Vidyalaya - School Management System</p>
    </div>


    {{-- TEACHER BASIC INFO --}}
    <div class="teacher-header">

        <div class="teacher-name">
            {{ $teacher->first_name }} {{ $teacher->last_name }}
        </div>

        <div class="teacher-id">
            Teacher ID: {{ $teacher->teacher_id }}
        </div>

    </div>


    {{-- PERSONAL INFORMATION --}}
    <div class="section-title">
        Personal Information
    </div>

    <table>

        <tr>
            <th>First Name</th>
            <td>{{ $teacher->first_name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Last Name</th>
            <td>{{ $teacher->last_name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Teacher ID</th>
            <td>{{ $teacher->teacher_id ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Date of Birth</th>
            <td>
                @if($teacher->date_of_birth)
                    {{ \Carbon\Carbon::parse($teacher->date_of_birth)->format('d M Y') }}
                @else
                    N/A
                @endif
            </td>
        </tr>

        <tr>
            <th>Gender</th>
            <td>{{ $teacher->gender ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Qualification</th>
            <td>{{ $teacher->qualification ?? 'N/A' }}</td>
        </tr>

        <tr>
            <th>Joining Date</th>
            <td>
                @if($teacher->joining_date)
                    {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d M Y') }}
                @else
                    N/A
                @endif
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td class="status {{ strtolower($teacher->status ?? '') == 'active' ? 'active' : 'inactive' }}">
                {{ ucfirst($teacher->status ?? 'N/A') }}
            </td>
        </tr>

    </table>


    {{-- CONTACT INFORMATION --}}
    <div class="section-title">
        Contact Information
    </div>

    <table>

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


    {{-- REPORT SUMMARY --}}
    <div class="section-title">
        Report Summary
    </div>

    <table class="summary-table">

        <tr>

            <td>
                <span class="summary-number">0</span>
                <span class="summary-label">
                    Attendance Records
                </span>
            </td>

            <td>
                <span class="summary-number">0</span>
                <span class="summary-label">
                    Salary Records
                </span>
            </td>

            <td>
                <span class="summary-number">0</span>
                <span class="summary-label">
                    Timetable Entries
                </span>
            </td>

        </tr>

    </table>


    {{-- FOOTER --}}
    <div class="footer">
        Generated from Gurukul Vidyalaya School Management System
    </div>

</body>
</html>