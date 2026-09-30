<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Student Report</title>

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
            color: #1f2937;
            font-size: 10px;
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h1 {
            margin: 0 0 4px;
            font-size: 20px;
            color: #1e3a8a;
        }

        .header p {
            margin: 0;
            color: #64748b;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            margin-bottom: 15px;
        }

        .summary-box {
            border: 1px solid #dbe3ef;
            background: #f8fafc;
            padding: 8px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e3a8a;
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 7px 9px;
            margin: 16px 0 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            color: #334155;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
            font-size: 8px;
        }

        .student-name {
            font-weight: bold;
            font-size: 10px;
        }

        .student-id {
            color: #64748b;
            font-size: 8px;
            margin-top: 2px;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 8px;
            background: #eef2ff;
        }

        .active {
            color: #15803d;
            background: #dcfce7;
        }

        .inactive {
            color: #b91c1c;
            background: #fee2e2;
        }

        .empty {
            padding: 8px;
            border: 1px solid #e5e7eb;
            color: #64748b;
        }

        .student-block {
            page-break-inside: avoid;
            margin-bottom: 12px;
        }

        .detail-table {
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
            color: #64748b;
            font-size: 8px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Student Report</h1>
        <p>Student information and available academic records</p>
    </div>

    @if($students->count())

        <div class="section-title">
            Student Information
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student</th>
                    <th>Student ID</th>
                    <th>Academic Year</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Roll No.</th>
                    <th>Admission Date</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($students as $index => $student)

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <div class="student-name">
                                {{ $student->full_name }}
                            </div>

                            <div class="student-id">
                                {{ $student->student_id ?? '-' }}
                            </div>
                        </td>

                        <td>
                            {{ $student->student_id ?? '-' }}
                        </td>

                        <td>
                            {{ $student->academic_year ?? '-' }}
                        </td>

                        <td>
                            {{ $student->class ?? '-' }}
                        </td>

                        <td>
                            {{ $student->section ?? '-' }}
                        </td>

                        <td>
                            {{ $student->roll_number ?? '-' }}
                        </td>

                        <td>
                            @if($student->admission_date)
                                {{ $student->admission_date->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $student->gender ?? '-' }}
                        </td>

                        <td>
                            {{ $student->phone ?? '-' }}
                        </td>

                        <td>
                            @if(strtolower($student->status ?? '') === 'active')
                                <span class="badge active">Active</span>
                            @else
                                <span class="badge inactive">
                                    {{ ucfirst($student->status ?? 'Inactive') }}
                                </span>
                            @endif
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>


        @foreach($students as $student)

            <div class="student-block">

                <div class="section-title">
                    Attendance — {{ $student->full_name }}
                </div>

                @if($student->attendanceRecords->count())

                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Attendance Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($student->attendanceRecords as $index => $attendance)

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $attendance->attendance_date
                                            ? $attendance->attendance_date->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->status ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $attendance->remarks ?? '-' }}
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                @else

                    <div class="empty">
                        No attendance records available.
                    </div>

                @endif


                <div class="section-title">
                    Library — {{ $student->full_name }}
                </div>

                @if($student->libraryRecords->count())

                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Fine</th>
                                <th>Fine Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($student->libraryRecords as $index => $library)

                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>
                                        {{ $library->issue_date
                                            ? $library->issue_date->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $library->due_date
                                            ? $library->due_date->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $library->return_date
                                            ? $library->return_date->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $library->status ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $library->fine !== null
                                            ? '₹' . number_format($library->fine, 2)
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $library->fine_status ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $library->remarks ?? '-' }}
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                @else

                    <div class="empty">
                        No library records available.
                    </div>

                @endif

            </div>

        @endforeach

    @else

        <div class="empty">
            No students found for the selected filters.
        </div>

    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>

</body>
</html>
