<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Attendance Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin-top: 5px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f1f5f9;
            font-weight: bold;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: left;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            text-align: right;
            color: #666;
            font-size: 9px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Attendance Report</h1>
        <p>Student Attendance Records</p>
    </div>


    <table>

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

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ $record->student->student_id ?? '-' }}
                    </td>

                    <td>
                        @if($record->student)
                            {{ $record->student->first_name }}
                            {{ $record->student->last_name }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        {{ $record->academic_year ?? '-' }}
                    </td>

                    <td>
                        {{ $record->class ?? '-' }}
                    </td>

                    <td>
                        {{ $record->section ?? '-' }}
                    </td>

                    <td>
                        @if($record->attendance_date)
                            {{ $record->attendance_date->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>

                    <td class="status">
                        {{ ucfirst($record->status ?? '-') }}
                    </td>

                    <td>
                        {{ $record->remarks ?? '-' }}
                    </td>

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