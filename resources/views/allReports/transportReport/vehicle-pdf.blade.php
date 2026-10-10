<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Vehicle Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 8px;
            border: 1px solid #ccc;
        }

        td {
            padding: 7px;
            border: 1px solid #ccc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h2>Vehicle Report</h2>

    <div class="subtitle">
        Vehicle-wise transport assignment and fee information
    </div>

    <table>

        <thead>
            <tr>
                <th>Vehicle</th>
                <th>Transport Type</th>
                <th>Assigned Students</th>
                <th>Active Students</th>
                <th>Inactive Students</th>
                <th>Total Fees</th>
            </tr>
        </thead>

        <tbody>

            @forelse($vehicles as $vehicle)

                <tr>
                    <td>
                        {{ $vehicle->vehicle }}
                    </td>

                    <td>
                        {{ ucwords(str_replace('_', ' ', $vehicle->transport_type)) }}
                    </td>

                    <td class="text-center">
                        {{ $vehicle->assigned_students }}
                    </td>

                    <td class="text-center">
                        {{ $vehicle->active_students }}
                    </td>

                    <td class="text-center">
                        {{ $vehicle->inactive_students }}
                    </td>

                    <td class="text-right">
                        ₹{{ number_format((float) $vehicle->total_fees, 2) }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center">
                        No Vehicle Records Found
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>