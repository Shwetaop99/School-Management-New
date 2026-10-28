<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $exam->exam_name ?? 'Exam Timetable' }} - Examination Timetable</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,600;9..144,700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        :root {
            --ink: #1d3a33;
            --ink-dark: #142b25;
            --ink-light: #2a4a41;
            --chalk: #f2b632;
            --wood: #8a5a2c;
            --cream: #fbf8f1;
            --soft-green: #e8eee7;
            --border: #d9dfd1;
            --text: #26302c;
            --muted: #6a756f;
            --white: #ffffff;
            --serif: 'Fraunces', Georgia, serif;
            --sans: 'Public Sans', Arial, Helvetica, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--cream);
            color: var(--text);
            font-family: var(--sans);
            font-size: 11px;
            line-height: 1.5;
        }

        /* PRINT TOOLBAR */

        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 24px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            box-shadow: 0 2px 8px rgba(29, 58, 51, .06);
        }

        .toolbar-label {
            color: var(--ink);
            font-family: var(--serif);
            font-size: 16px;
            font-weight: 700;
        }

        .toolbar-label span {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-family: var(--sans);
            font-size: 11px;
            font-weight: 400;
        }

        .print-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 20px;
            border: 0;
            border-radius: 8px;
            background: var(--ink);
            color: #fff;
            font-family: var(--sans);
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s ease, transform .2s ease;
        }

        .print-button:hover {
            background: var(--ink-light);
            transform: translateY(-1px);
        }

        .print-button .button-icon {
            font-size: 16px;
        }

        /* PRINT PAGE */

        .page {
            position: relative;
            isolation: isolate;
            width: 190mm;
            max-width: 100%;
            min-height: 255mm;
            margin: 20px auto;
            padding: 10mm;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eee9de;
            box-shadow: 0 8px 32px rgba(29, 58, 51, .10);
        }

        /*
         * Real image watermark.
         * The image is an actual HTML element, not a CSS variable.
         * This makes the watermark easier to verify and troubleshoot.
         */

        .watermark-logo {
            position: absolute;
            z-index: 0;
            top: 50%;
            left: 50%;
            width: 125mm;
            height: 125mm;
            object-fit: contain;
            transform: translate(-50%, -50%);
            opacity: .065;
            pointer-events: none;
            user-select: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* SCHOOL HEADER */

        .school-header {
            display: table;
            width: 100%;
            padding: 0 0 12px;
            border-bottom: 3px solid var(--ink);
        }

        .logo-cell {
            display: table-cell;
            width: 82px;
            vertical-align: middle;
            text-align: left;
        }

        .school-logo {
            display: block;
            width: 68px;
            height: 68px;
            object-fit: contain;
        }

        .school-details {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .school-name {
            margin: 0;
            color: var(--ink);
            font-family: var(--serif);
            font-size: 21px;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: .3px;
            text-transform: uppercase;
            overflow-wrap: anywhere;
        }

        .school-motto {
            margin-top: 4px;
            color: var(--wood);
            font-size: 10px;
            font-weight: 600;
        }

        .school-address {
            margin-top: 5px;
            color: #47574e;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .school-contact {
            margin-top: 3px;
            color: var(--muted);
            font-size: 9px;
            overflow-wrap: anywhere;
        }

        .gold-rule {
            height: 4px;
            margin-top: 3px;
            background: linear-gradient(
                90deg,
                var(--wood) 0%,
                var(--chalk) 18%,
                var(--chalk) 82%,
                var(--wood) 100%
            );
        }

        /* REPORT TITLE */

        .report-heading {
            margin: 18px 0 14px;
            text-align: center;
        }

        .report-heading h2 {
            margin: 0;
            color: var(--ink);
            font-family: var(--serif);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .exam-name {
            margin-top: 5px;
            color: var(--ink-light);
            font-size: 14px;
            font-weight: 700;
        }

        .academic-year {
            margin-top: 3px;
            color: var(--muted);
            font-size: 10px;
        }

        /* CLASS BANNER */

        .class-banner {
            margin: 14px 0 13px;
            padding: 12px;
            border: 1px solid #d6dfd0;
            border-left: 6px solid var(--chalk);
            border-radius: 7px;
            background: var(--soft-green);
            text-align: center;
        }

        .class-banner h3 {
            margin: 0;
            color: var(--ink);
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .class-banner p {
            margin: 4px 0 0;
            color: var(--ink-light);
            font-size: 10px;
        }

        /* EXAM INFORMATION */

        .exam-info {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .exam-info th,
        .exam-info td {
            padding: 8px;
            border: 1px solid var(--border);
            text-align: left;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .exam-info th {
            width: 23%;
            background: #edf1e9;
            color: var(--ink);
            font-weight: 700;
        }

        .exam-info td {
            width: 27%;
            background: rgba(255, 255, 255, .92);
        }

        /* TABLE SECTION TITLE */

        .section-heading {
            margin: 15px 0 0;
            padding: 9px 11px;
            border-bottom: 3px solid var(--chalk);
            border-radius: 7px 7px 0 0;
            background: var(--ink);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .4px;
            text-transform: uppercase;
        }

        /* EXAM SCHEDULE TABLE */

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 9px 5px;
            border: 1px solid var(--border);
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .schedule-table th {
            background: #e8eee7;
            color: var(--ink);
            text-align: center;
            font-size: 9px;
            font-weight: 800;
            line-height: 1.4;
        }

        .schedule-table td {
            background: rgba(255, 255, 255, .90);
            color: var(--text);
            text-align: center;
            font-size: 10px;
        }

        .schedule-table tbody tr:nth-child(even) td {
            background: rgba(242, 246, 239, .94);
        }

        .schedule-table .col-no {
            width: 7%;
        }

        .schedule-table .col-date {
            width: 16%;
        }

        .schedule-table .col-day {
            width: 15%;
        }

        .schedule-table .col-subject {
            width: 23%;
        }

        .schedule-table .col-time {
            width: 22%;
        }

        .schedule-table .col-marks {
            width: 8%;
        }

        .schedule-table .col-duration {
            width: 9%;
        }

        .subject-cell {
            padding-left: 9px !important;
            text-align: left !important;
            font-weight: 700;
        }

        .time-cell {
            font-weight: 600;
        }

        .marks-cell {
            color: var(--ink);
            font-weight: 800;
        }

        /* NOTES */

        .duration-note {
            margin-top: 9px;
            padding: 9px 11px;
            border-left: 4px solid var(--chalk);
            border-radius: 4px;
            background: #f1f4ec;
            color: #526057;
            font-size: 9px;
        }

        .no-data {
            padding: 22px 16px;
            border: 1px solid var(--border);
            border-left: 5px solid var(--chalk);
            border-radius: 7px;
            background: #f1f4ec;
            color: var(--ink);
            text-align: center;
            font-size: 12px;
            font-weight: 600;
        }

        /* SIGNATURES */

        .signature-area {
            display: table;
            width: 100%;
            margin-top: 45px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-cell {
            display: table-cell;
            width: 33.333%;
            padding: 0 8px;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-line {
            width: 90%;
            margin: 0 auto 7px;
            border-top: 1px solid var(--wood);
        }

        .signature-title {
            color: var(--ink);
            font-size: 10px;
            font-weight: 700;
        }

        /* FOOTER */

        .footer-note {
            margin-top: 12px;
            color: var(--muted);
            font-size: 9px;
            text-align: center;
        }

        .generated-info {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 8px;
            text-align: right;
        }

        .page-bottom-rule {
            height: 5px;
            margin-top: 9px;
            background: linear-gradient(
                90deg,
                var(--ink) 0%,
                var(--ink) 72%,
                var(--chalk) 72%,
                var(--chalk) 92%,
                var(--wood) 92%,
                var(--wood) 100%
            );
        }

        .class-page + .class-page {
            page-break-before: always;
            break-before: page;
        }

        /* RESPONSIVE */

        @media screen and (max-width: 700px) {
            .print-toolbar {
                padding: 10px 12px;
            }

            .toolbar-label {
                font-size: 13px;
            }

            .toolbar-label span {
                font-size: 10px;
            }

            .print-button {
                padding: 10px 12px;
            }

            .page {
                width: 100%;
                min-height: 0;
                margin: 0;
                padding: 15px;
                border: 0;
                box-shadow: none;
            }

            .school-name {
                font-size: 16px;
            }

            .school-logo {
                width: 54px;
                height: 54px;
            }

            .logo-cell {
                width: 62px;
            }

            .schedule-table th,
            .schedule-table td {
                padding: 6px 3px;
                font-size: 8px;
            }

            .watermark-logo {
                width: 90%;
                height: auto;
                aspect-ratio: 1;
            }
        }

        /* PRINT SETTINGS */

        @media print {
            html,
            body {
                margin: 0;
                padding: 0;
                background: #fff !important;
                color: #111;
                font-size: 10px;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .page {
                width: 100%;
                min-height: 0;
                margin: 0;
                padding: 0;
                border: 0;
                box-shadow: none;
                overflow: visible;
                break-inside: auto;
            }

            .watermark-logo {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 125mm;
                height: 125mm;
                opacity: .065 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .page-content {
                position: relative;
                z-index: 1;
            }

            .class-page + .class-page {
                page-break-before: always;
                break-before: page;
            }

            .school-header,
            .report-heading,
            .class-banner,
            .exam-info,
            .section-heading,
            .signature-area {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .schedule-table {
                page-break-inside: auto;
                break-inside: auto;
            }

            .schedule-table thead {
                display: table-header-group;
            }

            .schedule-table tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .schedule-table th {
                background: #e8eee7 !important;
                color: var(--ink) !important;
            }

            .section-heading {
                background: var(--ink) !important;
                color: #fff !important;
            }

            .class-banner {
                background: #e8eee7 !important;
            }

            .duration-note {
                background: #f1f4ec !important;
            }

            .page-bottom-rule {
                background: var(--ink) !important;
            }
        }
    </style>
</head>

<body>

@php
    /*
     * Resolve the school settings and logo once.
     * The same image is used in the header and watermark.
     */
    $schoolSetting = $schoolSetting ?? $school ?? null;

    $fallbackLogo = asset('images/gurukullogo.png');

    $watermarkLogo = !empty($logoData)
        ? $logoData
        : (
            !empty($schoolLogo)
                ? $schoolLogo
                : (
                    !empty($schoolSetting?->logo)
                        ? $schoolSetting->logo
                        : $fallbackLogo
                )
        );

    $durationByMarks = [
        20 => 60,
        40 => 120,
        50 => 120,
        80 => 180,
        100 => 180,
    ];

    $timetablesByClass = $timetables
        ->sortBy(function ($item) {
            return ($item->schoolClass->class_name ?? '')
                . '|'
                . ($item->schoolClass->section ?? '');
        })
        ->groupBy('class_id');

    $addressParts = array_filter([
        $schoolSetting->address ?? null,
        $schoolSetting->city ?? null,
        $schoolSetting->district ?? null,
        $schoolSetting->state ?? null,
        $schoolSetting->pincode ?? null,
    ]);

    $contactParts = [];

    if (!empty($schoolSetting?->phone)) {
        $contactParts[] = 'Phone: ' . $schoolSetting->phone;
    }

    if (!empty($schoolSetting?->email)) {
        $contactParts[] = 'Email: ' . $schoolSetting->email;
    }

    if (!empty($schoolSetting?->udise_code)) {
        $contactParts[] = 'UDISE: ' . $schoolSetting->udise_code;
    }

    if (!empty($schoolSetting?->school_code)) {
        $contactParts[] = 'School Code: ' . $schoolSetting->school_code;
    }

    $schoolDisplayName = $schoolSetting->school_name
        ?? $schoolName
        ?? 'Gurukul Vidyalaya';
@endphp

<div class="print-toolbar">
    <div class="toolbar-label">
        Examination Timetable
        <span>Official school examination schedule</span>
    </div>

    <button type="button" class="print-button" onclick="window.print()">
        <span class="button-icon">&#128424;</span>
        Print Timetable
    </button>
</div>

@if($timetablesByClass->isEmpty())

    <div class="page">
        <img
            class="watermark-logo"
            src="{{ $watermarkLogo }}"
            alt=""
            aria-hidden="true"
            onerror="this.style.display='none';"
        >

        <div class="page-content">

            <div class="school-header">
                <div class="logo-cell">
                    <img
                        src="{{ $watermarkLogo }}"
                        alt="School Logo"
                        class="school-logo"
                        onerror="this.style.display='none';"
                    >
                </div>

                <div class="school-details">
                    <h1 class="school-name">{{ $schoolDisplayName }}</h1>

                    @if(count($addressParts))
                        <div class="school-address">
                            {{ implode(', ', $addressParts) }}
                        </div>
                    @endif

                    @if(count($contactParts))
                        <div class="school-contact">
                            {{ implode(' | ', $contactParts) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="gold-rule"></div>

            <div class="report-heading">
                <h2>Examination Timetable</h2>

                <div class="exam-name">
                    {{ $exam->exam_name ?? 'Examination' }}
                </div>

                <div class="academic-year">
                    Academic Year: {{ $exam->academic_year ?? '-' }}
                </div>
            </div>

            <div class="no-data">
                No examination timetable has been generated for this examination.
            </div>

            <div class="generated-info">
                Generated on {{ now()->format('d-m-Y h:i A') }}
            </div>

            <div class="page-bottom-rule"></div>
        </div>
    </div>

@else

    @foreach($timetablesByClass as $classId => $classTimetables)

        @php
            $classTimetables = $classTimetables
                ->sortBy(function ($item) {
                    $date = $item->exam_date
                        ? $item->exam_date->format('Y-m-d')
                        : '9999-12-31';

                    $time = $item->start_time ?? '23:59:59';

                    return $date . ' ' . $time;
                })
                ->values();

            $firstTimetable = $classTimetables->first();
            $classRecord = $firstTimetable->schoolClass;

            $className = $classRecord->class_name ?? 'Class';
            $sectionName = $classRecord->section ?? null;
        @endphp

        <div class="page class-page">

            {{-- ACTUAL SCHOOL LOGO WATERMARK --}}
            <img
                class="watermark-logo"
                src="{{ $watermarkLogo }}"
                alt=""
                aria-hidden="true"
                onerror="this.style.display='none';"
            >

            <div class="page-content">

                {{-- SCHOOL HEADER --}}

                <div class="school-header">

                    <div class="logo-cell">
                        <img
                            src="{{ $watermarkLogo }}"
                            alt="School Logo"
                            class="school-logo"
                            onerror="this.style.display='none';"
                        >
                    </div>

                    <div class="school-details">
                        <h1 class="school-name">
                            {{ $schoolDisplayName }}
                        </h1>

                        @if(count($addressParts))
                            <div class="school-address">
                                {{ implode(', ', $addressParts) }}
                            </div>
                        @endif

                        @if(count($contactParts))
                            <div class="school-contact">
                                {{ implode(' | ', $contactParts) }}
                            </div>
                        @endif
                    </div>

                </div>

                <div class="gold-rule"></div>

                {{-- EXAMINATION HEADING --}}

                <div class="report-heading">
                    <h2>Examination Timetable</h2>

                    <div class="exam-name">
                        {{ $exam->exam_name ?? 'Examination' }}
                    </div>

                    <div class="academic-year">
                        Academic Year:
                        {{ $exam->academic_year ?? '-' }}
                    </div>
                </div>

                {{-- CLASS IDENTIFICATION --}}

                <div class="class-banner">
                    <h3>
                        {{ $className }}
                        @if($sectionName)
                            — Section {{ $sectionName }}
                        @endif
                    </h3>

                    <p>Official Examination Schedule</p>
                </div>

                {{-- EXAM DETAILS --}}

                <table class="exam-info">
                    <tbody>
                        <tr>
                            <th>Examination</th>
                            <td>{{ $exam->exam_name ?? '-' }}</td>

                            <th>Exam Type</th>
                            <td>{{ $exam->exam_type ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Academic Year</th>
                            <td>{{ $exam->academic_year ?? '-' }}</td>

                            <th>Class</th>
                            <td>
                                {{ $className }}
                                @if($sectionName)
                                    - {{ $sectionName }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Exam Period</th>
                            <td colspan="3">
                                {{ $exam->start_date
                                    ? $exam->start_date->format('d-m-Y')
                                    : '-' }}

                                &nbsp; to &nbsp;

                                {{ $exam->end_date
                                    ? $exam->end_date->format('d-m-Y')
                                    : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- TIMETABLE --}}

                <div class="section-heading">
                    Subject-wise Examination Schedule
                </div>

                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th class="col-no">Sr. No.</th>
                            <th class="col-date">Date</th>
                            <th class="col-day">Day</th>
                            <th class="col-subject">Subject</th>
                            <th class="col-time">Examination Time</th>
                            <th class="col-marks">Marks</th>
                            <th class="col-duration">Duration</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($classTimetables as $timetable)

                            @php
                                $duration = $timetable->duration_minutes;

                                $subjectName =
                                    $timetable->examSubject->subject->subject_name
                                    ?? $timetable->examSubject->subject_name
                                    ?? '-';
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $timetable->exam_date
                                        ? $timetable->exam_date->format('d-m-Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $timetable->exam_date
                                        ? $timetable->exam_date->format('l')
                                        : '-' }}
                                </td>

                                <td class="subject-cell">
                                    {{ $subjectName }}
                                </td>

                                <td class="time-cell">
                                    @if($timetable->start_time)
                                        {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }}
                                    @else
                                        -
                                    @endif

                                    &ndash;

                                    @if($timetable->end_time)
                                        {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="marks-cell">
                                    {{ $timetable->maximum_marks ?? '-' }}
                                </td>

                                <td>
                                    @if($duration)
                                        @if((int) $duration >= 60 && (int) $duration % 60 === 0)
                                            {{ intdiv((int) $duration, 60) }}
                                            hour{{ (int) $duration > 60 ? 's' : '' }}
                                        @else
                                            {{ (int) $duration }} min
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>

                <div class="duration-note">
                    <strong>Note:</strong>
                    Examination timings must follow the approved school timetable.
                </div>

                {{-- SIGNATURES --}}

                <div class="signature-area">
                    <div class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">Class Teacher</div>
                    </div>

                    <div class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">Examination In-Charge</div>
                    </div>

                    <div class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">Principal / Headmaster</div>
                    </div>
                </div>

                <div class="footer-note">
                    Please verify the examination date and time before the examination.
                </div>

                <div class="generated-info">
                    Generated on {{ now()->format('d-m-Y h:i A') }}
                </div>

                <div class="page-bottom-rule"></div>

            </div>
        </div>

    @endforeach

@endif

</body>
</html>