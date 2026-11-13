<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Class Timetable</title>
    <style>
        @page {
            margin: 22px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1a1a1a;
            margin: 0;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #1d3a33;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #1d3a33;
            margin-bottom: 4px;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 10px;
            color: #666;
        }

        /* Info bar */
        .info-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #d4cfc0;
            padding: 7px 9px;
            background: #f7f4eb;
            font-size: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #1d3a33;
        }

        /* Timetable grid */
        .timetable {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .timetable th {
            background: #1d3a33;
            color: #ffffff;
            border: 1px solid #163028;
            padding: 7px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
        }

        .timetable td {
            border: 1px solid #d4cfc0;
            padding: 5px 3px;
            text-align: center;
            vertical-align: middle;
            height: 54px;
        }

        .day-cell {
            width: 72px;
            background: #f0ebe0;
            font-weight: bold;
            color: #1d3a33;
            font-size: 9px;
        }

        .subject {
            font-weight: bold;
            font-size: 9px;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .teacher {
            font-size: 8px;
            color: #4a4a4a;
            margin-bottom: 1px;
        }

        .room {
            font-size: 7px;
            color: #6b6b6b;
        }

        .time {
            font-size: 7px;
            color: #6b6b6b;
            margin-top: 2px;
        }

        /* Special periods */
        .break-cell {
            background: #fff6e0;
            color: #a05a00;
            font-weight: bold;
        }

        .lunch-cell {
            background: #eef8f0;
            color: #2b6b3a;
            font-weight: bold;
        }

        .activity-cell {
            background: #f3f0ff;
            color: #5b3a9e;
        }

        .free-cell {
            color: #999;
            font-size: 8px;
            font-style: italic;
        }

        /* Legend */
        .legend {
            margin-top: 14px;
            width: 100%;
            border-collapse: collapse;
        }

        .legend td {
            padding: 5px;
            font-size: 8px;
            text-align: center;
            border: 1px solid #e0d9c8;
            background: #faf8f2;
        }

        .legend-box {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #cfc8b6;
            vertical-align: middle;
            margin-right: 4px;
        }

        /* Footer */
        .footer {
            margin-top: 14px;
            text-align: right;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #d4cfc0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="school-name">Gurukul Vidyalaya</div>
    <div class="title">Class Timetable</div>
    <div class="subtitle">Weekly Class Timetable</div>
</div>

<table class="info-table">
    <tr>
        <td>
            <span class="info-label">Class:</span>
            {{ request('class') }}
        </td>
        <td>
            <span class="info-label">Section:</span>
            {{ request('section') ?: 'All Sections' }}
        </td>
        <td>
            <span class="info-label">Generated:</span>
            {{ now()->format('d M Y, h:i A') }}
        </td>
    </tr>
</table>

<table class="timetable">
    <thead>
        <tr>
            <th>Day</th>
            @foreach($periods as $periodNumber => $periodEntries)
                @php $firstEntry = $periodEntries->first(); @endphp
                <th>
                    Period {{ $periodNumber }}
                    @if($firstEntry)
                        <br>
                        <span style="font-weight:normal;">
                            {{ \Carbon\Carbon::parse($firstEntry->start_time)->format('h:i A') }}
                            –
                            {{ \Carbon\Carbon::parse($firstEntry->end_time)->format('h:i A') }}
                        </span>
                    @endif
                </th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach($days as $day)
            <tr>
                <td class="day-cell">{{ $day }}</td>

                @foreach($periods as $periodNumber => $periodEntries)
                    @php $entry = $periodEntries->firstWhere('day', $day); @endphp

                    @if(!$entry)
                        <td class="free-cell">Free</td>

                    @elseif($entry->period_type === 'Break')
                        <td class="break-cell">
                            <div style="font-size:14px;">☕</div>
                            <div>Break</div>
                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>
                        </td>

                    @elseif($entry->period_type === 'Lunch')
                        <td class="lunch-cell">
                            <div style="font-size:14px;">🍱</div>
                            <div>Lunch</div>
                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>
                        </td>

                    @elseif($entry->period_type === 'Activity')
                        <td class="activity-cell">
                            <div class="subject">{{ $entry->subject }}</div>
                            @if($entry->teacher)
                                <div class="teacher">
                                    {{ $entry->teacher->first_name }} {{ $entry->teacher->last_name }}
                                </div>
                            @endif
                            @if($entry->room)
                                <div class="room">Room: {{ $entry->room }}</div>
                            @endif
                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>
                        </td>

                    @else
                        <td>
                            <div class="subject">{{ $entry->subject }}</div>
                            @if($entry->teacher)
                                <div class="teacher">
                                    {{ $entry->teacher->first_name }} {{ $entry->teacher->last_name }}
                                </div>
                            @endif
                            @if($entry->room)
                                <div class="room">Room: {{ $entry->room }}</div>
                            @endif
                            @if($entry->subject_type)
                                <div class="room">{{ $entry->subject_type }}</div>
                            @endif
                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                –
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

<table class="legend">
    <tr>
        <td>
            <span class="legend-box" style="background:#ffffff;"></span>
            Regular Subject
        </td>
        <td>
            <span class="legend-box" style="background:#fff6e0;"></span>
            Break
        </td>
        <td>
            <span class="legend-box" style="background:#eef8f0;"></span>
            Lunch
        </td>
        <td>
            <span class="legend-box" style="background:#f3f0ff;"></span>
            Activity
        </td>
        <td>
            <span class="legend-box" style="background:#f7f4eb;"></span>
            Free Period
        </td>
    </tr>
</table>

<div class="footer">
    Gurukul Vidyalaya — Class Timetable · Generated {{ now()->format('d M Y') }}
</div>

</body>
</html>