
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Timetable</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2, p { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #eaf2ff; }
    </style>
</head>
<body>
    <h2>Weekly Timetable</h2>
    <p>
        Class: {{ $class }} |
        Section: {{ $section }} |
        Academic Year: {{ $academicYear }}
    </p>
    <p>
        Week: {{ $weekStartDate->format('d M Y') }}
        – {{ $weekStartDate->copy()->addDays(5)->format('d M Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Period</th>
                <th>Type</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Start</th>
                <th>End</th>
                <th>Room</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->timetable_date)->format('d-m-Y') }}</td>
                    <td>{{ $item->day }}</td>
                    <td>{{ $item->period_number }}</td>
                    <td>{{ $item->period_type }}</td>
                    <td>{{ $item->subject }}</td>
                    <td>
                        {{ $item->teacher
                            ? trim(($item->teacher->first_name ?? '') . ' ' . ($item->teacher->last_name ?? ''))
                            : '—' }}
                    </td>
                    <td>{{ $item->start_time ? substr($item->start_time, 0, 5) : '—' }}</td>
                    <td>{{ $item->end_time ? substr($item->end_time, 0, 5) : '—' }}</td>
                    <td>{{ $item->room ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No timetable records found for this week.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
