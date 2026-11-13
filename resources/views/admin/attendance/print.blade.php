<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Attendance Report -
        {{ $school?->school_name ?? 'School' }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --purple: #7c3aed;
            --purple-light: #f5f3ff;
            --cyan: #0891b2;
            --cyan-light: #ecfeff;
            --dark: #111827;
            --gray-900: #1f2937;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-500: #6b7280;
            --gray-400: #9ca3af;
            --gray-300: #d1d5db;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-50: #f9fafb;
        }

        body {
            margin: 0;
            padding: 24px;
            background:
                linear-gradient(
                    135deg,
                    #eef4ff 0%,
                    #f8fafc 45%,
                    #f1f5f9 100%
                );
            font-family:
                Arial,
                Helvetica,
                sans-serif;
            color: var(--dark);
        }

        /* =========================================================
           ACTION BUTTONS
        ========================================================== */

        .print-actions {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto 18px;

            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
        }

        .print-button,
        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 40px;
            padding: 9px 18px;

            border-radius: 9px;

            font-size: 13px;
            font-weight: 700;

            cursor: pointer;
            text-decoration: none;

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .print-button {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #ffffff;

            box-shadow:
                0 4px 12px rgba(37, 99, 235, 0.20);
        }

        .print-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow:
                0 6px 16px rgba(37, 99, 235, 0.28);
        }

        .back-button {
            border: 1px solid var(--gray-300);
            background: #ffffff;
            color: var(--gray-700);
        }

        .back-button:hover {
            background: var(--gray-50);
            transform: translateY(-1px);
        }

        /* =========================================================
           MAIN PRINT CONTAINER
        ========================================================== */

        .print-container {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;

            background: #ffffff;

            border: 1px solid rgba(203, 213, 225, 0.8);
            border-radius: 18px;

            padding: 30px;

            box-shadow:
                0 15px 40px rgba(15, 23, 42, 0.08);
        }

        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 22px;

            padding: 5px 0 20px;

            border-bottom: 2px solid var(--dark);
        }

        .school-logo-box {
            width: 100px;
            height: 100px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;
            overflow: hidden;

            background: #ffffff;
        }

        .school-logo {
            max-width: 100px;
            max-height: 100px;

            width: auto;
            height: auto;

            object-fit: contain;
        }

        .school-information {
            text-align: center;
            line-height: 1.5;
            flex: 1;
        }

        .school-name {
            margin: 0 0 5px;

            font-size: 28px;
            font-weight: 800;

            color: var(--dark);

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }

        .school-address {
            margin: 2px 0;

            font-size: 13px;

            color: var(--gray-600);
        }

        .school-meta {
            margin-top: 5px;

            font-size: 11.5px;

            color: var(--gray-600);
        }

        .school-meta strong {
            color: var(--gray-900);
        }

        /* =========================================================
           REPORT HEADING
        ========================================================== */

        .report-heading {
            text-align: center;

            margin: 20px 0 18px;
        }

        .report-title-badge {
            display: inline-block;

            padding: 5px 13px;

            margin-bottom: 7px;

            border-radius: 50px;

            background: #eff6ff;

            color: var(--primary-dark);

            font-size: 10px;
            font-weight: 800;

            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .report-heading h2 {
            margin: 0;

            font-size: 24px;

            font-weight: 800;

            color: var(--dark);

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }

        .report-heading p {
            margin: 5px 0 0;

            font-size: 12px;

            color: var(--gray-500);
        }

        /* =========================================================
           REPORT INFORMATION
        ========================================================== */

        .filter-box {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 10px;

            margin-bottom: 18px;
        }

        .filter-item {
            position: relative;

            border: 1px solid var(--gray-200);

            padding: 11px 13px;

            border-radius: 10px;

            background:
                linear-gradient(
                    135deg,
                    #ffffff,
                    #f8fafc
                );

            overflow: hidden;
        }

        .filter-item::before {
            content: "";

            position: absolute;

            left: 0;
            top: 0;
            bottom: 0;

            width: 3px;

            background: var(--primary);
        }

        .filter-label {
            display: block;

            font-size: 9px;

            color: var(--gray-500);

            text-transform: uppercase;

            font-weight: 800;

            letter-spacing: 0.5px;

            margin-bottom: 4px;
        }

        .filter-value {
            display: block;

            font-size: 13px;

            font-weight: 700;

            color: var(--gray-900);

            word-break: break-word;
        }

        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(6, 1fr);

            gap: 9px;

            margin-bottom: 20px;
        }

        .summary-card {
            position: relative;

            border: 1px solid var(--gray-200);

            padding: 12px 7px;

            text-align: center;

            border-radius: 11px;

            overflow: hidden;

            background: #ffffff;
        }

        .summary-card::before {
            content: "";

            position: absolute;

            left: 0;
            right: 0;
            top: 0;

            height: 3px;

            background: var(--primary);
        }

        .summary-card.students::before {
            background: var(--primary);
        }

        .summary-card.days::before {
            background: var(--cyan);
        }

        .summary-card.present::before {
            background: var(--success);
        }

        .summary-card.absent::before {
            background: var(--danger);
        }

        .summary-card.leave::before {
            background: var(--warning);
        }

        .summary-card.percentage::before {
            background: var(--purple);
        }

        .summary-card .number {
            font-size: 19px;

            font-weight: 800;

            margin-bottom: 4px;

            color: var(--gray-900);
        }

        .summary-card.present .number {
            color: var(--success);
        }

        .summary-card.absent .number {
            color: var(--danger);
        }

        .summary-card.leave .number {
            color: var(--warning);
        }

        .summary-card.percentage .number {
            color: var(--purple);
        }

        .summary-card .label {
            font-size: 8.5px;

            text-transform: uppercase;

            font-weight: 800;

            color: var(--gray-500);

            letter-spacing: 0.4px;
        }

        /* =========================================================
           TABLE WRAPPER
        ========================================================== */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;

            border: 1px solid var(--gray-300);

            border-radius: 10px;

            overflow: hidden;
        }

        /* =========================================================
           ATTENDANCE TABLE
        ========================================================== */

        .attendance-table {
            width: 100%;

            border-collapse: collapse;

            margin: 0;

            table-layout: auto;
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid var(--gray-300);

            padding: 8px 6px;

            font-size: 10.5px;
        }

        .attendance-table th {
            background:
                linear-gradient(
                    180deg,
                    #f8fafc,
                    #eef2f7
                );

            color: var(--gray-900);

            font-weight: 800;

            text-align: center;

            text-transform: uppercase;

            letter-spacing: 0.2px;

            white-space: nowrap;
        }

        .attendance-table td {
            vertical-align: middle;

            background: #ffffff;
        }

        .attendance-table tbody tr:nth-child(even) td {
            background: #fafbfc;
        }

        .attendance-table tbody tr:hover td {
            background: #f8fbff;
        }

        .text-center {
            text-align: center;
        }

        .student-name {
            font-weight: 700;

            color: var(--gray-900);

            min-width: 150px;
        }

        .student-id {
            font-size: 9.5px;

            font-weight: 600;

            color: var(--gray-600);

            white-space: nowrap;
        }

        .number-cell {
            font-weight: 600;
        }

        /* =========================================================
           ATTENDANCE PERCENTAGE
        ========================================================== */

        .percentage-badge {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            min-width: 62px;

            padding: 4px 8px;

            border-radius: 50px;

            font-size: 10px;

            font-weight: 800;
        }

        .percentage-high {
            background: var(--success-light);
            color: var(--success);
        }

        .percentage-medium {
            background: var(--warning-light);
            color: var(--warning);
        }

        .percentage-low {
            background: var(--danger-light);
            color: var(--danger);
        }

        /* =========================================================
           EMPTY STATE
        ========================================================== */

        .empty-row {
            padding: 25px !important;

            color: var(--gray-500);

            font-weight: 600;

            background: var(--gray-50) !important;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .report-footer {
            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            margin-top: 40px;

            padding-top: 14px;

            border-top: 1px solid var(--gray-300);

            font-size: 10.5px;

            color: var(--gray-600);
        }

        .generated-info {
            line-height: 1.7;
        }

        .signature-box {
            width: 190px;

            text-align: center;

            padding-top: 32px;

            color: var(--gray-900);

            font-size: 10.5px;

            font-weight: 700;
        }

        .signature-line {
            border-top: 1px solid var(--dark);

            padding-top: 6px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media screen and (max-width: 900px) {

            body {
                padding: 12px;
            }

            .print-container {
                padding: 18px;
                border-radius: 12px;
            }

            .filter-box {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .summary-grid {
                grid-template-columns:
                    repeat(3, 1fr);
            }

            .school-name {
                font-size: 22px;
            }
        }

        @media screen and (max-width: 600px) {

            .print-actions {
                justify-content: stretch;
            }

            .back-button,
            .print-button {
                flex: 1;
            }

            .school-header {
                flex-direction: column;
            }

            .school-information {
                width: 100%;
            }

            .filter-box {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .report-footer {
                flex-direction: column;

                align-items: stretch;

                gap: 25px;
            }

            .signature-box {
                margin-left: auto;
            }
        }

        /* =========================================================
           PRINT
        ========================================================== */

        @media print {

            @page {
                size: A4 landscape;

                margin: 8mm;
            }

            html,
            body {
                width: 100%;

                margin: 0;
                padding: 0;

                background: #ffffff !important;
            }

            body {
                color: #000000;
            }

            .print-actions {
                display: none !important;
            }

            .print-container {
                width: 100%;

                max-width: none;

                margin: 0;

                padding: 0;

                border: none;

                border-radius: 0;

                box-shadow: none;
            }

            .school-header {
                border-bottom: 2px solid #000000;

                padding-bottom: 12px;
            }

            .school-name {
                color: #000000;
            }

            .school-address,
            .school-meta {
                color: #333333;
            }

            .report-heading {
                margin: 12px 0;
            }

            .report-title-badge {
                background: transparent;

                color: #000000;

                padding: 0;
            }

            .filter-item {
                background: #ffffff;

                border: 1px solid #999999;
            }

            .filter-item::before {
                display: none;
            }

            .summary-card {
                border: 1px solid #999999;

                box-shadow: none;
            }

            .summary-card::before {
                display: none;
            }

            .attendance-table {
                page-break-inside: auto;
            }

            .attendance-table thead {
                display: table-header-group;
            }

            .attendance-table tbody {
                display: table-row-group;
            }

            .attendance-table tr {
                page-break-inside: avoid;

                page-break-after: auto;
            }

            .attendance-table th {
                background: #eeeeee !important;

                color: #000000 !important;

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;
            }

            .attendance-table td {
                background: #ffffff !important;
            }

            .percentage-badge {
                background: transparent !important;

                color: #000000 !important;

                padding: 0;

                min-width: auto;
            }

            .report-footer {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- =========================================================
         ACTION BUTTONS
    ========================================================== --}}

    <div class="print-actions">

        <a
            href="{{ url()->previous() }}"
            class="back-button"
        >
            ← Back
        </a>

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            🖨 Print Report
        </button>

    </div>


    {{-- =========================================================
         MAIN PRINT CONTAINER
    ========================================================== --}}

    <div class="print-container">

        {{-- =====================================================
             SCHOOL HEADER
        ====================================================== --}}

        <div class="school-header">

            <div class="school-logo-box">

                <img
                    src="{{ $school?->logo_url ?? asset('images/gurukullogo.png') }}"
                    alt="School Logo"
                    class="school-logo"
                    onerror="
                        this.onerror=null;
                        this.src='{{ asset('images/gurukullogo.png') }}';
                    "
                >

            </div>


            <div class="school-information">

                {{-- SCHOOL NAME --}}

                <h1 class="school-name">
                    {{ $school?->school_name ?? 'Gurukul Vidyalaya' }}
                </h1>


                {{-- ADDRESS --}}

                @if($school?->address)

                    <div class="school-address">
                        {{ $school->address }}
                    </div>

                @endif


                {{-- CITY / DISTRICT / STATE / PINCODE --}}

                @if(
                    $school?->city ||
                    $school?->district ||
                    $school?->state ||
                    $school?->pincode
                )

                    <div class="school-address">

                        {{ collect([
                            $school?->city,
                            $school?->district,
                            $school?->state,
                            $school?->pincode
                        ])->filter()->implode(', ') }}

                    </div>

                @endif


                {{-- SCHOOL CODE / UDISE --}}

                @if(
                    $school?->school_code ||
                    $school?->udise_code
                )

                    <div class="school-meta">

                        @if($school?->school_code)

                            <strong>
                                School Code:
                            </strong>

                            {{ $school->school_code }}

                        @endif


                        @if(
                            $school?->school_code &&
                            $school?->udise_code
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if($school?->udise_code)

                            <strong>
                                UDISE:
                            </strong>

                            {{ $school->udise_code }}

                        @endif

                    </div>

                @endif


                {{-- PHONE / EMAIL / WEBSITE --}}

                @if(
                    $school?->phone ||
                    $school?->email ||
                    $school?->website
                )

                    <div class="school-meta">

                        @if($school?->phone)

                            <strong>
                                Phone:
                            </strong>

                            {{ $school->phone }}

                        @endif


                        @if(
                            $school?->phone &&
                            $school?->email
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if($school?->email)

                            <strong>
                                Email:
                            </strong>

                            {{ $school->email }}

                        @endif


                        @if(
                            ($school?->phone || $school?->email) &&
                            $school?->website
                        )

                            &nbsp; | &nbsp;

                        @endif


                        @if($school?->website)

                            <strong>
                                Website:
                            </strong>

                            {{ $school->website }}

                        @endif

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
             REPORT TITLE
        ====================================================== --}}

        <div class="report-heading">

            <div class="report-title-badge">
                Academic Record
            </div>

            <h2>
                Attendance Report
            </h2>

            <p>
                Student Attendance Summary
            </p>

        </div>


        {{-- =====================================================
             REPORT FILTER INFORMATION
        ====================================================== --}}

        <div class="filter-box">

            <div class="filter-item">

                <span class="filter-label">
                    Academic Year
                </span>

                <span class="filter-value">
                    {{ $academicYear ?? '-' }}
                </span>

            </div>


            <div class="filter-item">

                <span class="filter-label">
                    Class
                </span>

                <span class="filter-value">
                    {{ $className ?? '-' }}
                </span>

            </div>


            <div class="filter-item">

                <span class="filter-label">
                    Section
                </span>

                <span class="filter-value">
                    {{ $section ?? '-' }}
                </span>

            </div>


            <div class="filter-item">

                <span class="filter-label">
                    Attendance Period
                </span>

                <span class="filter-value">

                    @if(isset($fromDate) && isset($toDate))

                        {{ $fromDate->format('d M Y') }}
                        -
                        {{ $toDate->format('d M Y') }}

                    @else

                        -

                    @endif

                </span>

            </div>

        </div>


        {{-- =====================================================
             SUMMARY CARDS
        ====================================================== --}}

        <div class="summary-grid">

            {{-- STUDENTS --}}

            <div class="summary-card students">

                <div class="number">
                    {{ $totalStudents ?? 0 }}
                </div>

                <div class="label">
                    Students
                </div>

            </div>


            {{-- WORKING DAYS --}}

            <div class="summary-card days">

                <div class="number">
                    {{ $workingDays ?? 0 }}
                </div>

                <div class="label">
                    Working Days
                </div>

            </div>


            {{-- PRESENT --}}

            <div class="summary-card present">

                <div class="number">
                    {{ $totalPresent ?? 0 }}
                </div>

                <div class="label">
                    Present
                </div>

            </div>


            {{-- ABSENT --}}

            <div class="summary-card absent">

                <div class="number">
                    {{ $totalAbsent ?? 0 }}
                </div>

                <div class="label">
                    Absent
                </div>

            </div>


            {{-- LEAVE --}}

            <div class="summary-card leave">

                <div class="number">
                    {{ $totalLeave ?? 0 }}
                </div>

                <div class="label">
                    Leave
                </div>

            </div>


            {{-- ATTENDANCE % --}}

            <div class="summary-card percentage">

                <div class="number">
                    {{ number_format($averageAttendance ?? 0, 2) }}%
                </div>

                <div class="label">
                    Attendance
                </div>

            </div>

        </div>


        {{-- =====================================================
             ATTENDANCE TABLE
        ====================================================== --}}

        <div class="table-wrapper">

            <table class="attendance-table">

                <thead>

                    <tr>

                        <th style="width: 45px;">
                            Sr.
                        </th>

                        <th style="width: 75px;">
                            Roll No.
                        </th>

                        <th style="width: 115px;">
                            Student ID
                        </th>

                        <th>
                            Student Name
                        </th>

                        <th style="width: 75px;">
                            Working Days
                        </th>

                        <th style="width: 65px;">
                            Present
                        </th>

                        <th style="width: 65px;">
                            Absent
                        </th>

                        <th style="width: 60px;">
                            Leave
                        </th>

                        <th style="width: 70px;">
                            Half Day
                        </th>

                        <th style="width: 60px;">
                            Late
                        </th>

                        <th style="width: 90px;">
                            Attendance
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($students as $index => $student)

                        @php

                            $attendancePercentage =
                                (float) ($student['attendance_percentage'] ?? 0);

                            if ($attendancePercentage >= 75) {

                                $percentageClass = 'percentage-high';

                            } elseif ($attendancePercentage >= 60) {

                                $percentageClass = 'percentage-medium';

                            } else {

                                $percentageClass = 'percentage-low';

                            }

                        @endphp


                        <tr>

                            {{-- SR NO --}}

                            <td class="text-center number-cell">
                                {{ $index + 1 }}
                            </td>


                            {{-- ROLL NUMBER --}}

                            <td class="text-center">
                                {{ $student['roll_number'] ?? '-' }}
                            </td>


                            {{-- STUDENT ID --}}

                            <td class="text-center student-id">
                                {{ $student['student_id'] ?? '-' }}
                            </td>


                            {{-- STUDENT NAME --}}

                            <td class="student-name">
                                {{ $student['name'] ?? '-' }}
                            </td>


                            {{-- WORKING DAYS --}}

                            <td class="text-center">
                                {{ $student['working_days'] ?? 0 }}
                            </td>


                            {{-- PRESENT --}}

                            <td class="text-center">
                                {{ $student['present'] ?? 0 }}
                            </td>


                            {{-- ABSENT --}}

                            <td class="text-center">
                                {{ $student['absent'] ?? 0 }}
                            </td>


                            {{-- LEAVE --}}

                            <td class="text-center">
                                {{ $student['leave'] ?? 0 }}
                            </td>


                            {{-- HALF DAY --}}

                            <td class="text-center">
                                {{ $student['half_day'] ?? 0 }}
                            </td>


                            {{-- LATE --}}

                            <td class="text-center">
                                {{ $student['late'] ?? 0 }}
                            </td>


                            {{-- ATTENDANCE % --}}

                            <td class="text-center">

                                <span class="percentage-badge {{ $percentageClass }}">

                                    {{ number_format(
                                        $attendancePercentage,
                                        2
                                    ) }}%

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="11"
                                class="text-center empty-row"
                            >

                                No attendance records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             REPORT FOOTER
        ====================================================== --}}

        <div class="report-footer">

            <div class="generated-info">

                <strong>
                    Generated On:
                </strong>

                {{ now()->format('d M Y, h:i A') }}

                <br>

                <strong>
                    Report:
                </strong>

                Student Attendance Summary

            </div>


            <div class="signature-box">

                <div class="signature-line">
                    Principal / Authorized Signatory
                </div>

            </div>

        </div>

    </div>


    <script>

        /*
         * Automatic printing is intentionally disabled.
         *
         * To enable automatic printing after the page loads,
         * uncomment window.print().
         */

        window.addEventListener('load', function () {

            // window.print();

        });

    </script>

</body>

</html>