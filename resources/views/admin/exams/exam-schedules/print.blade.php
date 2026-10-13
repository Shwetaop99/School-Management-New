```blade
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $exam->exam_name ?? 'Exam Schedule' }}
        - Exam Timetable
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .print-container {
            width: 100%;
            margin: 0 auto;
        }

        .school-header {
            text-align: center;
            margin-bottom: 12px;
        }

        .school-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .school-address {
            margin-top: 4px;
            font-size: 12px;
        }

        .school-contact {
            margin-top: 3px;
            font-size: 11px;
        }

        .report-title {
            text-align: center;
            margin: 15px 0 12px;
        }

        .report-title h2 {
            margin: 0;
            font-size: 19px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .report-title p {
            margin: 4px 0 0;
            font-size: 12px;
        }

        .exam-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .exam-info td {
            border: 1px solid #333;
            padding: 7px 9px;
            vertical-align: middle;
        }

        .exam-info .label {
            width: 14%;
            font-weight: 700;
            background: #f3f4f6;
        }

        .exam-info .value {
            width: 19%;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .schedule-table th,
        .schedule-table td {
            border: 1px solid #222;
            padding: 7px 6px;
            text-align: center;
            vertical-align: middle;
        }

        .schedule-table th {
            background: #e5e7eb;
            font-weight: 700;
            font-size: 11px;
        }

        .schedule-table td {
            font-size: 11px;
        }

        .schedule-table .date {
            width: 10%;
        }

        .schedule-table .day {
            width: 9%;
        }

        .schedule-table .time {
            width: 13%;
        }

        .schedule-table .session {
            width: 13%;
        }

        .schedule-table .class {
            width: 11%;
        }

        .schedule-table .subject {
            width: 18%;
        }

        .schedule-table .marks {
            width: 8%;
        }

        .schedule-table .duration {
            width: 9%;
        }

        .schedule-table .teacher {
            width: 13%;
        }

        .no-data {
            text-align: center;
            padding: 25px;
            font-weight: 600;
        }

        .footer {
            margin-top: 35px;
            width: 100%;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            width: 33.33%;
            text-align: center;
            padding-top: 35px;
            font-size: 11px;
            font-weight: 600;
        }

        .generated-info {
            margin-top: 15px;
            text-align: right;
            font-size: 9px;
            color: #555;
        }

        @media print {

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .schedule-table tr {
                page-break-inside: avoid;
            }

            .school-header,
            .report-title,
            .exam-info {
                page-break-inside: avoid;
            }
        }

        .print-button {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #1677f0;
            color: #ffffff;
            border: 0;
            border-radius: 5px;
            padding: 9px 15px;
            cursor: pointer;
            font-size: 13px;
        }

        .print-button:hover {
            background: #0d5ec7;
        }
    </style>
</head>

<body>

    <button
        type="button"
        class="print-button no-print"
        onclick="window.print()"
    >
        Print
    </button>

    <div class="print-container">

        {{-- =========================================================
            SCHOOL HEADER
        ========================================================== --}}

        <div class="school-header">

            @if(!empty($schoolSetting?->logo))
                <img
                    src="{{ $schoolSetting->logo }}"
                    alt="School Logo"
                    class="school-logo"
                >
            @elseif(file_exists(public_path('images/gurukullogo.png')))
                <img
                    src="{{ asset('images/gurukullogo.png') }}"
                    alt="School Logo"
                    class="school-logo"
                >
            @endif

            <h1 class="school-name">
                {{ $schoolSetting->school_name ?? 'School Name' }}
            </h1>

            @php
                $addressParts = array_filter([
                    $schoolSetting->address ?? null,
                    $schoolSetting->city ?? null,
                    $schoolSetting->district ?? null,
                    $schoolSetting->state ?? null,
                    $schoolSetting->pincode ?? null,
                ]);
            @endphp

            @if(count($addressParts))
                <div class="school-address">
                    {{ implode(', ', $addressParts) }}
                </div>
            @endif

            @php
                $contactParts = [];

                if (!empty($schoolSetting->phone)) {
                    $contactParts[] = 'Phone: ' . $schoolSetting->phone;
                }

                if (!empty($schoolSetting->email)) {
                    $contactParts[] = 'Email: ' . $schoolSetting->email;
                }

                if (!empty($schoolSetting->udise_code)) {
                    $contactParts[] = 'UDISE: ' . $schoolSetting->udise_code;
                }

                if (!empty($schoolSetting->school_code)) {
                    $contactParts[] = 'School Code: ' . $schoolSetting->school_code;
                }
            @endphp

            @if(count($contactParts))
                <div class="school-contact">
                    {{ implode(' | ', $contactParts) }}
                </div>
            @endif

        </div>


        {{-- =========================================================
            REPORT TITLE
        ========================================================== --}}

        <div class="report-title">

            <h2>
                Examination Timetable
            </h2>

            <p>
                {{ $exam->exam_name ?? 'Examination' }}
                -
                {{ $exam->academic_year ?? '' }}
            </p>

        </div>


        {{-- =========================================================
            EXAM INFORMATION
        ========================================================== --}}

        <table class="exam-info">

            <tr>

                <td class="label">
                    Examination
                </td>

                <td class="value">
                    {{ $exam->exam_name ?? '-' }}
                </td>

                <td class="label">
                    Exam Type
                </td>

                <td class="value">
                    {{ $exam->exam_type ?? '-' }}
                </td>

                <td class="label">
                    Academic Year
                </td>

                <td class="value">
                    {{ $exam->academic_year ?? '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Start Date
                </td>

                <td class="value">
                    {{ $exam->start_date ? $exam->start_date->format('d-m-Y') : '-' }}
                </td>

                <td class="label">
                    End Date
                </td>

                <td class="value">
                    {{ $exam->end_date ? $exam->end_date->format('d-m-Y') : '-' }}
                </td>

                <td class="label">
                    Status
                </td>

                <td class="value">
                    {{ ucfirst($exam->status ?? '-') }}
                </td>

            </tr>

        </table>


        {{-- =========================================================
            TIMETABLE
        ========================================================== --}}

        <table class="schedule-table">

            <thead>

                <tr>

                    <th class="date">
                        Date
                    </th>

                    <th class="day">
                        Day
                    </th>

                    <th class="time">
                        Time
                    </th>

                    <th class="session">
                        Session
                    </th>

                    <th class="class">
                        Class
                    </th>

                    <th class="subject">
                        Subject
                    </th>

                    <th class="marks">
                        Max Marks
                    </th>

                    <th class="duration">
                        Duration
                    </th>

                    <th class="teacher">
                        Teacher
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($timetables as $timetable)

                    <tr>

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

                        <td>
                            {{ $timetable->start_time
                                ? \Carbon\Carbon::parse($timetable->start_time)->format('h:i A')
                                : '-' }}

                            @if($timetable->end_time)
                                -
                                {{ \Carbon\Carbon::parse($timetable->end_time)->format('h:i A') }}
                            @endif
                        </td>

                        <td>
                            {{ $timetable->session->session_name ?? '-' }}
                        </td>

                        <td>
                            @if($timetable->schoolClass)
                                {{ $timetable->schoolClass->class_name ?? '-' }}

                                @if(!empty($timetable->schoolClass->section))
                                    - {{ $timetable->schoolClass->section }}
                                @endif
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $timetable->examSubject->subject->subject_name ?? '-' }}
                        </td>

                        <td>
                            {{ $timetable->maximum_marks ?? '-' }}
                        </td>

                        <td>
                            {{ $timetable->duration_minutes
                                ? $timetable->duration_minutes . ' min'
                                : '-' }}
                        </td>

                        <td>
                            {{ $timetable->teacher->name ?? 'Not Assigned' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9" class="no-data">
                            No examination timetable has been generated.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- =========================================================
            SIGNATURES
        ========================================================== --}}

        <div class="footer">

            <table class="footer-table">

                <tr>

                    <td>
                        Class Teacher
                    </td>

                    <td>
                        Examination In-Charge
                    </td>

                    <td>
                        Principal / Headmaster
                    </td>

                </tr>

            </table>

        </div>


        <div class="generated-info">

            Generated on:
            {{ now()->format('d-m-Y h:i A') }}

        </div>

    </div>


    <script>
        window.addEventListener('load', function () {

            // Uncomment the next line if you want the print
            // dialog to open automatically.

            // window.print();

        });
    </script>

</body>

</html>
```
