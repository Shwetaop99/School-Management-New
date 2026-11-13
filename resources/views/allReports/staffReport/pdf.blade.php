<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Staff Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #222;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 7px;
            border: 1px solid #ddd;
            text-align: left;
        }

        td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }
    </style>
</head>

<body>

    <h1>Staff Report</h1>

    <div class="subtitle">
        Non-Teaching Staff Information
    </div>

    <table>

        <thead>
            <tr>
                <th>#</th>
                <th>Staff ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Qualification</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Joining Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @foreach($staff as $index => $member)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td>{{ $member->staff_id ?? '-' }}</td>

                    <td>{{ $member->name ?? '-' }}</td>

                    <td>{{ $member->designation ?? '-' }}</td>

                    <td>{{ $member->department ?? '-' }}</td>

                    <td>{{ $member->qualification ?? '-' }}</td>

                    <td>{{ $member->gender ?? '-' }}</td>

                    <td>{{ $member->phone ?? '-' }}</td>

                    <td>{{ $member->email ?? '-' }}</td>

                    <td>
                        {{ $member->joining_date?->format('d M Y') ?? '-' }}
                    </td>

                    <td>{{ $member->status ?? '-' }}</td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>