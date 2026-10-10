<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">

<title>Class Timetable</title>

<style>
    @page {
        margin: 25px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 10px;
        color: #1f2937;
        margin: 0;
    }

    .header {
        text-align: center;
        margin-bottom: 18px;
    }

    .school-name {
        font-size: 20px;
        font-weight: bold;
        color: #147cf5;
        margin-bottom: 5px;
    }

    .title {
        font-size: 16px;
        font-weight: bold;
        color: #111827;
        margin-bottom: 5px;
    }

    .subtitle {
        font-size: 10px;
        color: #6b7280;
    }

    .info-table {
        width: 100%;
        margin-bottom: 15px;
        border-collapse: collapse;
    }

    .info-table td {
        border: 1px solid #dbe3ef;
        padding: 7px;
        background: #f4f7fb;
    }

    .info-label {
        font-weight: bold;
        color: #374151;
    }

    .timetable {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .timetable th {
        background: #147cf5;
        color: #ffffff;
        border: 1px solid #0f67d1;
        padding: 7px 4px;
        text-align: center;
        font-size: 9px;
    }

    .timetable td {
        border: 1px solid #d5dce7;
        padding: 5px 3px;
        text-align: center;
        vertical-align: middle;
        height: 55px;
    }

    .day-cell {
        width: 75px;
        background: #f4f7fb;
        font-weight: bold;
        color: #374151;
    }

    .period-cell {
        background: #f8fafc;
        font-weight: bold;
        color: #374151;
        font-size: 8px;
    }

    .subject {
        font-weight: bold;
        font-size: 9px;
        color: #111827;
        margin-bottom: 3px;
    }

    .teacher {
        font-size: 8px;
        color: #4b5563;
        margin-bottom: 2px;
    }

    .room {
        font-size: 7px;
        color: #6b7280;
    }

    .time {
        font-size: 7px;
        color: #6b7280;
        margin-top: 3px;
    }

    .break-cell {
        background: #fff7ed;
        color: #c2410c;
        font-weight: bold;
    }

    .lunch-cell {
        background: #fefce8;
        color: #a16207;
        font-weight: bold;
    }

    .activity-cell {
        background: #f5f3ff;
        color: #6d28d9;
    }

    .free-cell {
        color: #9ca3af;
        font-size: 8px;
    }

    .legend {
        margin-top: 15px;
        width: 100%;
        border-collapse: collapse;
    }

    .legend td {
        padding: 5px;
        font-size: 8px;
        text-align: center;
    }

    .legend-box {
        display: inline-block;
        width: 10px;
        height: 10px;
        border: 1px solid #d1d5db;
        vertical-align: middle;
        margin-right: 4px;
    }

    .footer {
        margin-top: 15px;
        text-align: right;
        font-size: 8px;
        color: #9ca3af;
    }
</style>


</head>

<body>


<div class="header">
    <div class="school-name">
        Gurukul Vidyalaya
    </div>

    <div class="title">
        Class Timetable
    </div>

    <div class="subtitle">
        Weekly Class Timetable
    </div>
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

                @php
                    $firstEntry = $periodEntries->first();
                @endphp

                <th>
                    Period {{ $periodNumber }}

                    @if($firstEntry)
                        <br>
                        <span style="font-weight: normal;">
                            {{ \Carbon\Carbon::parse($firstEntry->start_time)->format('h:i A') }}
                            -
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

                <td class="day-cell">
                    {{ $day }}
                </td>


                @foreach($periods as $periodNumber => $periodEntries)

                    @php
                        $entry = $periodEntries->firstWhere('day', $day);
                    @endphp


                    @if(!$entry)

                        <td class="free-cell">
                            Free
                        </td>


                    @elseif($entry->period_type === 'Break')

                        <td class="break-cell">

                            <div style="font-size: 16px;">
                                ☕
                            </div>

                            <div>
                                Break
                            </div>

                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>

                        </td>


                    @elseif($entry->period_type === 'Lunch')

                        <td class="lunch-cell">

                            <div style="font-size: 16px;">
                                🍱
                            </div>

                            <div>
                                Lunch
                            </div>

                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>

                        </td>


                    @elseif($entry->period_type === 'Activity')

                        <td class="activity-cell">

                            <div class="subject">
                                {{ $entry->subject }}
                            </div>

                            @if($entry->teacher)
                                <div class="teacher">
                                    {{ $entry->teacher->first_name }}
                                    {{ $entry->teacher->last_name }}
                                </div>
                            @endif

                            @if($entry->room)
                                <div class="room">
                                    Room: {{ $entry->room }}
                                </div>
                            @endif

                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                -
                                {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                            </div>

                        </td>


                    @else

                        <td>

                            <div class="subject">
                                {{ $entry->subject }}
                            </div>

                            @if($entry->teacher)
                                <div class="teacher">
                                    {{ $entry->teacher->first_name }}
                                    {{ $entry->teacher->last_name }}
                                </div>
                            @endif

                            @if($entry->room)
                                <div class="room">
                                    Room: {{ $entry->room }}
                                </div>
                            @endif

                            @if($entry->subject_type)
                                <div class="room">
                                    {{ $entry->subject_type }}
                                </div>
                            @endif

                            <div class="time">
                                {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }}
                                -
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
            <span
                class="legend-box"
                style="background:#ffffff;"
            ></span>
            Regular Subject
        </td>

        <td>
            <span
                class="legend-box"
                style="background:#fff7ed;"
            ></span>
            Break
        </td>

        <td>
            <span
                class="legend-box"
                style="background:#fefce8;"
            ></span>
            Lunch
        </td>

        <td>
            <span
                class="legend-box"
                style="background:#f5f3ff;"
            ></span>
            Activity
        </td>

        <td>
            <span
                class="legend-box"
                style="background:#f8fafc;"
            ></span>
            Free Period
        </td>

    </tr>

</table>


<div class="footer">
    Gurukul Vidyalaya — Class Timetable
</div>


</body>
</html>
