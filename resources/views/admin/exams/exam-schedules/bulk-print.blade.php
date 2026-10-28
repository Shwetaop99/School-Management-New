
@php
    /*
    |--------------------------------------------------------------------------
    | Prepare timetable entries
    |--------------------------------------------------------------------------
    */
    $rows = collect($timetables ?? [])
        ->flatten()
        ->filter(fn ($row) => is_object($row) && data_get($row, 'exam_date'))
        ->sortBy(fn ($row) =>
            \Carbon\Carbon::parse(data_get($row, 'exam_date'))->format('Y-m-d')
            . ' ' . data_get($row, 'start_time', '')
            . ' ' . str_pad((string) data_get($row, 'class_id', ''), 5, '0', STR_PAD_LEFT)
        )
        ->values();

    $schoolName = data_get($school ?? null, 'school_name')
        ?? data_get($school ?? null, 'name')
        ?? config('app.name', 'YOUR SCHOOL NAME');

    $examName = data_get($exam ?? null, 'name')
        ?? data_get($exam ?? null, 'exam_name')
        ?? 'Examination';

    $academicYear = data_get($exam ?? null, 'academic_year')
        ?? data_get($exam ?? null, 'academicYear.name')
        ?? '—';

    $schoolAddress = data_get($school ?? null, 'address')
        ?? data_get($school ?? null, 'school_address')
        ?? '';

    $schoolPhone = data_get($school ?? null, 'phone')
        ?? data_get($school ?? null, 'phone_number')
        ?? data_get($school ?? null, 'contact_number')
        ?? '';

    $schoolEmail = data_get($school ?? null, 'email') ?? '';
    $schoolWebsite = data_get($school ?? null, 'website') ?? '';

    $schoolCode = data_get($school ?? null, 'school_code')
        ?? data_get($school ?? null, 'code')
        ?? '';

    $principalName = data_get($school ?? null, 'principal_name')
        ?? data_get($school ?? null, 'principal')
        ?? '';

    $motto = data_get($school ?? null, 'motto')
        ?? 'Education Builds the Future';

    $logoPath = data_get($school ?? null, 'logo_path')
        ?? data_get($school ?? null, 'logo')
        ?? data_get($school ?? null, 'school_logo')
        ?? '';

    $logoUrl = '';

    if ($logoPath) {
        $logoUrl = \Illuminate\Support\Str::startsWith(
            $logoPath,
            ['http://', 'https://']
        )
            ? $logoPath
            : asset('storage/' . ltrim($logoPath, '/'));
    }

    /*
    |--------------------------------------------------------------------------
    | Group identical subject + time + date + marks rows
    |--------------------------------------------------------------------------
    | Multiple classes with the same subject, date, time and marks are
    | combined into one display row. Original database records are untouched.
    |--------------------------------------------------------------------------
    */
    $getSubjectName = function ($row) {
        return data_get($row, 'examSubject.subject.name')
            ?? data_get($row, 'examSubject.subject.subject_name')
            ?? data_get($row, 'examSubject.subject.title')
            ?? data_get($row, 'examSubject.subject_name')
            ?? data_get($row, 'examSubject.name')
            ?? data_get($row, 'subject.name')
            ?? data_get($row, 'subject.subject_name')
            ?? data_get($row, 'subject_name')
            ?? '—';
    };

    $getClassName = function ($row) {
        $name = data_get($row, 'schoolClass.name')
            ?? data_get($row, 'schoolClass.class_name')
            ?? data_get($row, 'schoolClass.title')
            ?? data_get($row, 'schoolClass.class')
            ?? data_get($row, 'class.name')
            ?? data_get($row, 'class.class_name')
            ?? data_get($row, 'class_name')
            ?? '—';

        $section = data_get($row, 'section.name')
            ?? data_get($row, 'section_name');

        return $section ? $name . ' - ' . $section : $name;
    };

    $getMarks = function ($row) {
        $candidates = [
            data_get($row, 'max_marks'),
            data_get($row, 'maximum_marks'),
            data_get($row, 'total_marks'),
            data_get($row, 'full_marks'),
            data_get($row, 'max_mark'),
            data_get($row, 'marks'),

            data_get($row, 'examSubject.max_marks'),
            data_get($row, 'examSubject.maximum_marks'),
            data_get($row, 'examSubject.total_marks'),
            data_get($row, 'examSubject.full_marks'),
            data_get($row, 'examSubject.max_mark'),
            data_get($row, 'examSubject.marks'),
            data_get($row, 'examSubject.pivot.max_marks'),
            data_get($row, 'examSubject.pivot.total_marks'),
            data_get($row, 'examSubject.pivot.marks'),

            data_get($row, 'examSubject.subject.max_marks'),
            data_get($row, 'examSubject.subject.total_marks'),
            data_get($row, 'examSubject.subject.full_marks'),
            data_get($row, 'examSubject.subject.marks'),

            data_get($row, 'subject.max_marks'),
            data_get($row, 'subject.total_marks'),
            data_get($row, 'subject.full_marks'),
            data_get($row, 'subject.marks'),
        ];

        foreach ($candidates as $value) {
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        return '—';
    };

    $getTimeKey = function ($row) {
        return implode('|', [
            (string) data_get($row, 'start_time', ''),
            (string) data_get($row, 'end_time', ''),
        ]);
    };

    $groupKey = function ($row) use ($getSubjectName, $getMarks, $getTimeKey) {
        $date = \Carbon\Carbon::parse(
            data_get($row, 'exam_date')
        )->format('Y-m-d');

        return implode('|', [
            $date,
            mb_strtolower(trim((string) $getSubjectName($row))),
            $getTimeKey($row),
            (string) $getMarks($row),
        ]);
    };

    $displayRows = $rows
        ->groupBy(fn ($row) => $groupKey($row))
        ->map(function ($items) use ($getClassName) {
            $firstRow = $items->first();

            $classes = $items
                ->map(fn ($item) => $getClassName($item))
                ->filter(fn ($name) => $name !== '—' && $name !== '')
                ->unique()
                ->values();

            /*
             * Sort class names naturally, so Class 2 appears before Class 10.
             */
            $classes = $classes->sort(function ($a, $b) {
                return strnatcasecmp($a, $b);
            })->values();

            return [
                'row' => $firstRow,
                'class_name' => $classes->isNotEmpty()
                    ? $classes->implode(', ')
                    : '—',
            ];
        })
        ->sortBy(function ($item) {
            $row = $item['row'];

            return \Carbon\Carbon::parse(
                data_get($row, 'exam_date')
            )->format('Y-m-d')
                . ' ' . data_get($row, 'start_time', '');
        })
        ->values();

    $groupedRows = $displayRows->groupBy(function ($item) {
        return \Carbon\Carbon::parse(
            data_get($item['row'], 'exam_date')
        )->format('Y-m-d');
    });

    $serial = 1;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $examName }} - Examination Timetable</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --navy: #12356a;
            --navy-dark: #0b2853;
            --border: #45658e;
            --pale-blue: #e5edf8;
            --text: #172c49;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #edf1f6;
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        body {
            padding: 20px;
        }

        .toolbar {
            max-width: 1000px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .toolbar h2 {
            margin: 0 0 4px;
            font-size: 18px;
        }

        .toolbar small {
            color: #667085;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .toolbar button {
            padding: 9px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
            color: #24364d;
            cursor: pointer;
            font-size: 12px;
        }

        .toolbar .print-button {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        .paper {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 16px;
            background: #fff;
            box-shadow: 0 2px 14px rgba(20, 35, 60, .10);
        }

        /* School letterhead */

        .letterhead {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1.5px solid var(--navy);
            color: var(--navy);
        }

        .letterhead td {
            border: 1px solid var(--navy);
            padding: 7px;
            vertical-align: middle;
        }

        .logo-cell {
            width: 19%;
            text-align: center;
        }

        .school-info-cell {
            width: 57%;
            text-align: center;
        }

        .contact-cell {
            width: 24%;
            font-size: 10px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .school-logo {
            display: block;
            width: 105px;
            height: 105px;
            max-width: 100%;
            object-fit: contain;
            margin: auto;
        }

        .logo-placeholder {
            width: 90px;
            height: 90px;
            margin: auto;
            border: 2px solid var(--navy);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            font-weight: 800;
        }

        .motto {
            margin: 0 0 7px;
            font-size: 12px;
            font-style: italic;
        }

        .school-name {
            margin: 0;
            color: var(--navy-dark);
            font-family: Georgia, "Times New Roman", serif;
            font-size: 28px;
            line-height: 1.15;
            font-weight: 800;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .school-subtitle {
            margin-top: 6px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 14px;
            font-weight: bold;
        }

        .school-tagline {
            margin-top: 7px;
            font-size: 11px;
        }

        .school-address {
            margin-top: 5px;
            font-size: 10px;
        }

        .contact-line {
            margin-bottom: 4px;
        }

        .contact-principal {
            margin-top: 9px;
        }

        .letterhead-bottom td {
            padding: 7px 4px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
        }

        .code-cell {
            width: 19%;
        }

        .website-cell {
            width: 57%;
            overflow-wrap: anywhere;
        }

        .letterhead-bottom .issued-cell {
            width: 24%;
            white-space: nowrap;
        }

        /* Exam heading */

        .exam-heading {
            margin: 23px 0 20px;
            text-align: center;
            color: var(--navy-dark);
            line-height: 1.5;
        }

        .exam-heading h2 {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 24px;
            font-weight: 800;
        }

        .exam-heading h3 {
            margin: 5px 0 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 18px;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .exam-heading .year-line {
            margin-top: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Timetable table */

        .timetable {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1.5px solid var(--navy);
            color: var(--text);
        }

        .timetable col.serial {
            width: 7%;
        }

        .timetable col.day {
            width: 12%;
        }

        .timetable col.date {
            width: 12%;
        }

        .timetable col.subject {
            width: 24%;
        }

        .timetable col.class {
            width: 16%;
        }

        .timetable col.time {
            width: 20%;
        }

        .timetable col.marks {
            width: 9%;
        }

        .timetable thead {
            display: table-header-group;
        }

        .timetable thead th {
            padding: 9px 4px;
            border: 1px solid var(--navy);
            background: var(--pale-blue);
            color: var(--navy-dark);
            text-align: center;
            font-size: 12px;
            font-weight: 800;
        }

        .timetable tbody td {
            padding: 7px 6px;
            border: 1px solid var(--border);
            font-size: 11px;
            line-height: 1.35;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .serial-cell,
        .day-cell,
        .date-cell,
        .class-cell,
        .time-cell,
        .marks-cell {
            text-align: center;
        }

        .serial-cell,
        .day-cell,
        .date-cell,
        .marks-cell {
            font-weight: 700;
        }

        .day-cell,
        .date-cell {
            background: #f8fafd;
        }

        .subject-cell {
            text-align: left;
            padding-left: 9px !important;
            font-weight: 600;
        }

        .class-cell {
            text-align: center;
            font-weight: 600;
        }

        .time-cell {
            white-space: nowrap;
        }

        .marks-cell {
            font-weight: 700;
            white-space: nowrap;
        }

        .timetable tbody tr {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .empty-message {
            padding: 25px;
            border: 1px solid var(--navy);
            text-align: center;
            color: var(--navy);
            font-weight: bold;
        }

        /* Note and signatures */

        .exam-note {
            margin-top: 18px;
            color: var(--navy-dark);
            font-size: 12px;
            line-height: 1.6;
            font-weight: bold;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-top: 32px;
            color: var(--navy-dark);
        }

        .place-date {
            font-size: 12px;
            line-height: 1.8;
        }

        .authorized-signature {
            width: 220px;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
            font-weight: bold;
        }

        .signature-space {
            height: 28px;
        }

        .signature-line {
            width: 155px;
            margin: 0 auto 6px;
            border-bottom: 1px solid var(--navy);
        }

        .footer-note {
            margin-top: 12px;
            text-align: center;
            color: #667085;
            font-size: 9px;
        }

        @media (max-width: 700px) {
            body {
                padding: 6px;
            }

            .toolbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .paper {
                padding: 6px;
                overflow-x: auto;
            }

            .letterhead,
            .exam-heading,
            .timetable,
            .exam-note,
            .signature-section {
                min-width: 760px;
            }
        }

        
/* A4 PORTRAIT PDF ALIGNMENT FIX */

/* A4 PORTRAIT PDF ALIGNMENT FIX */
@media print {
    @page {
        size: A4 portrait;
        margin: 7mm;
    }

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .toolbar {
        display: none !important;
    }

    .paper {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .letterhead,
    .exam-heading,
    .timetable,
    .exam-note,
    .signature-section {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .letterhead {
        table-layout: fixed !important;
        border-collapse: collapse !important;
    }

    .letterhead td {
        overflow-wrap: anywhere !important;
        word-break: normal !important;
    }

    .timetable {
        table-layout: fixed !important;
        border-collapse: collapse !important;
        font-size: 9px !important;
    }

    .timetable th,
    .timetable td {
        padding: 5px 3px !important;
        font-size: 9px !important;
        line-height: 1.25 !important;
        white-space: normal !important;
        overflow-wrap: anywhere !important;
        word-break: normal !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }

    .timetable thead {
        display: table-header-group;
    }

    .timetable tr {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .exam-heading {
        text-align: center !important;
    }

    .signature-section {
        break-inside: avoid;
        page-break-inside: avoid;
    }
}


    </style>
</head>

<body>
    <div class="toolbar">
        <div>
            <h2>Examination Timetable</h2>
            <small>Professional standalone print layout</small>
        </div>

        <div class="toolbar-actions">
            <button type="button" onclick="window.history.back()">Back</button>

            <button type="button"
                    class="print-button"
                    onclick="window.print()">
                Print / Save PDF
            </button>
        </div>
    </div>

    <main class="paper">

        {{-- SCHOOL LETTERHEAD --}}
        <table class="letterhead">
            <tbody>
                <tr>
                    <td class="logo-cell">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}"
                                 alt="School Logo"
                                 class="school-logo">
                        @else
                            <div class="logo-placeholder">
                                {{ \Illuminate\Support\Str::upper(
                                    \Illuminate\Support\Str::substr($schoolName, 0, 1)
                                ) }}
                            </div>
                        @endif
                    </td>

                    <td class="school-info-cell">
                        <p class="motto">“{{ $motto }}”</p>

                        <h1 class="school-name">{{ $schoolName }}</h1>

                        <div class="school-subtitle">
                            {{ data_get($school ?? null, 'school_type')
                                ?? 'School Examination Department' }}
                        </div>

                        <div class="school-tagline">
                            Learn &nbsp; • &nbsp; Grow &nbsp; • &nbsp; Succeed
                        </div>

                        @if ($schoolAddress)
                            <div class="school-address">{{ $schoolAddress }}</div>
                        @endif
                    </td>

                    <td class="contact-cell">
                        @if ($schoolPhone)
                            <div class="contact-line">
                                Phone: {{ $schoolPhone }}
                            </div>
                        @endif

                        @if ($schoolEmail)
                            <div class="contact-line">
                                Email: {{ $schoolEmail }}
                            </div>
                        @endif

                        @if ($schoolWebsite)
                            <div class="contact-line">{{ $schoolWebsite }}</div>
                        @endif

                        @if ($principalName)
                            <div class="contact-principal">
                                <strong>Principal</strong><br>
                                {{ $principalName }}
                            </div>
                        @endif
                    </td>
                </tr>

                <tr class="letterhead-bottom">
                    <td class="code-cell">
                        {{ $schoolCode ? 'School Code: '.$schoolCode : 'Examination Department' }}
                    </td>

                    <td class="website-cell">
                        @if ($schoolWebsite)
                            Website: {{ $schoolWebsite }}
                        @endif

                        @if ($schoolWebsite && $schoolEmail)
                            &nbsp; | &nbsp;
                        @endif

                        @if ($schoolEmail)
                            Email: {{ $schoolEmail }}
                        @endif
                    </td>

                    <td class="issued-cell">
                        Issued: {{ now()->format('d/m/Y') }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- EXAMINATION HEADING --}}
        <header class="exam-heading">
            <h2>{{ $examName }} ({{ $academicYear }})</h2>
            <h3>EXAMINATION TIMETABLE</h3>
            <div class="year-line">Official Date-wise Examination Schedule</div>
        </header>

        {{-- COMBINED TIMETABLE --}}
        @if ($displayRows->isEmpty())
            <div class="empty-message">
                No examination timetable entries found.
            </div>
        @else
            <table class="timetable">
                <colgroup>
                    <col class="serial">
                    <col class="day">
                    <col class="date">
                    <col class="subject">
                    <col class="class">
                    <col class="time">
                    <col class="marks">
                </colgroup>

                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Day</th>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Class</th>
                        <th>Time</th>
                        <th>Marks</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($groupedRows as $date => $dayRows)
                        @php
                            $dateObject = \Carbon\Carbon::parse($date);
                            $rowCount = $dayRows->count();
                            $firstRow = true;
                        @endphp

                        @foreach ($dayRows as $entry)
                            @php
                                $row = $entry['row'];

                                $subjectName = $getSubjectName($row);
                                $className = $entry['class_name'];
                                $marks = $getMarks($row);

                                $startTime = data_get($row, 'start_time');
                                $endTime = data_get($row, 'end_time');

                                $formatTime = function ($time) {
                                    if (!$time) {
                                        return '';
                                    }

                                    try {
                                        return \Carbon\Carbon::parse($time)->format('g:i A');
                                    } catch (\Throwable $e) {
                                        return (string) $time;
                                    }
                                };

                                $timeLabel = '—';

                                if ($startTime || $endTime) {
                                    $timeLabel =
                                        $formatTime($startTime)
                                        . (($startTime && $endTime) ? ' to ' : '')
                                        . $formatTime($endTime);
                                }
                            @endphp

                            <tr>
                                @if ($firstRow)
                                    <td class="serial-cell" rowspan="{{ $rowCount }}">
                                        {{ $serial++ }}
                                    </td>

                                    <td class="day-cell" rowspan="{{ $rowCount }}">
                                        {{ $dateObject->format('l') }}
                                    </td>

                                    <td class="date-cell" rowspan="{{ $rowCount }}">
                                        {{ $dateObject->format('d/m/Y') }}
                                    </td>
                                @endif

                                <td class="subject-cell">{{ $subjectName }}</td>

                                <td class="class-cell">{{ $className }}</td>

                                <td class="time-cell">{{ $timeLabel }}</td>

                                <td class="marks-cell">{{ $marks }}</td>
                            </tr>

                            @php $firstRow = false; @endphp
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- NOTE --}}
        <div class="exam-note">
            Note: Students must attend examinations according to the published
            timetable and follow all instructions issued by the school.
        </div>

        {{-- SIGNATURES --}}
        <footer class="signature-section">
            <div class="place-date">
                Place: {{ $schoolAddress ?: $schoolName }}<br>
                Date: {{ now()->format('d/m/Y') }}
            </div>

            <div class="authorized-signature">
                <div class="signature-space"></div>
                <div class="signature-line"></div>
                Examination In-charge<br>
                {{ $schoolName }}
            </div>

            <div class="authorized-signature">
                <div class="signature-space"></div>
                <div class="signature-line"></div>
                Principal
            </div>
        </footer>

        <div class="footer-note">
            {{ $schoolName }} &mdash; Official Examination Timetable
        </div>
    </main>
</body>
</html>
