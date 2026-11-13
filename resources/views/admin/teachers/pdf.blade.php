<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Teachers Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #edf2f7;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <h2>Gurukul Vidyalaya — Teachers Report</h2>
    <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Qualification</th>
                <th>Subject</th>
                <th>Joining Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->teacher_id }}</td>
                    <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone ?? '—' }}</td>
                    <td>{{ $teacher->qualification ?? '—' }}</td>
                    <td>{{ $teacher->subject ?? '—' }}</td>
                    <td>{{ $teacher->joining_date ?? '—' }}</td>
                    <td>{{ $teacher->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No teachers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>