<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $reportTitle ?? 'Student Supply Kit Report' }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            background: #f3f4f6;
            color: #1f2937;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }

        .print-page {
            max-width: 1400px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
        }

        /* =========================================================
           SCHOOL HEADER
        ========================================================== */

        .school-header {
            text-align: center;
            border-bottom: 2px solid #1677f0;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }

        .school-header-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
        }

        .school-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            border-radius: 6px;
        }

        .school-details {
            text-align: center;
        }

        .school-name {
            margin: 0;
            font-size: 25px;
            font-weight: 700;
            color: #111827;
        }

        .school-address {
            margin-top: 5px;
            font-size: 13px;
            color: #4b5563;
        }

        .school-contact {
            margin-top: 4px;
            font-size: 12px;
            color: #6b7280;
        }

        .report-title {
            margin: 15px 0 0;
            font-size: 19px;
            font-weight: 700;
            color: #1677f0;
            text-transform: uppercase;
        }

        /* =========================================================
           REPORT INFORMATION
        ========================================================== */

        .report-info {
            border: 1px solid #dfe5ec;
            border-radius: 7px;
            padding: 12px 15px;
            margin-bottom: 18px;
            background: #f8fafc;
        }

        .report-info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .info-item {
            min-width: 0;
        }

        .info-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            word-break: break-word;
        }

        /* =========================================================
           FILTER SUMMARY
        ========================================================== */

        .filter-summary {
            border: 1px solid #dfe5ec;
            border-radius: 7px;
            padding: 11px 14px;
            margin-bottom: 18px;
            background: #ffffff;
        }

        .filter-heading {
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #374151;
        }

        .filter-list {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .filter-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 5px;
            background: #eef6ff;
            color: #0d5fc7;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #d6e9ff;
        }

        /* =========================================================
           SUMMARY
        ========================================================== */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .summary-box {
            border: 1px solid #e5e7eb;
            border-radius: 7px;
            padding: 10px;
            text-align: center;
            background: #ffffff;
        }

        .summary-number {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .summary-label {
            margin-top: 3px;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
        }

        .summary-issued .summary-number {
            color: #198754;
        }

        .summary-pending .summary-number {
            color: #f59e0b;
        }

        .summary-cancelled .summary-number {
            color: #dc3545;
        }

        .summary-not-assigned .summary-number {
            color: #6c757d;
        }

        /* =========================================================
           TABLE
        ========================================================== */

        .report-table-wrapper {
            width: 100%;
            overflow: hidden;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .report-table th {
            background: #1677f0;
            color: #ffffff;
            border: 1px solid #0d5fc7;
            padding: 9px 7px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: left;
            vertical-align: middle;
        }

        .report-table td {
            border: 1px solid #dfe5ec;
            padding: 8px 7px;
            font-size: 11px;
            vertical-align: middle;
            color: #374151;
            word-wrap: break-word;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .report-table .text-center {
            text-align: center;
        }

        .student-name {
            font-weight: 700;
            color: #1f2937;
        }

        .kit-name {
            font-weight: 600;
            color: #374151;
        }

        .scheme-name {
            margin-top: 2px;
            color: #6b7280;
            font-size: 9px;
        }

        /* =========================================================
           STATUS
        ========================================================== */

        .status {
            display: inline-block;
            padding: 4px 7px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-issued {
            color: #146c43;
            background: #d1e7dd;
        }

        .status-pending {
            color: #8a5a00;
            background: #fff3cd;
        }

        .status-cancelled {
            color: #b02a37;
            background: #f8d7da;
        }

        .status-not-assigned {
            color: #495057;
            background: #e9ecef;
        }

        /* =========================================================
           EMPTY
        ========================================================== */

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            border: 1px solid #dfe5ec;
            border-radius: 7px;
        }

        .empty-title {
            font-size: 17px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 5px;
        }

        .empty-text {
            color: #6b7280;
            font-size: 12px;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .report-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #dfe5ec;
            display: flex;
            justify-content: space-between;
            gap: 15px;
            font-size: 10px;
            color: #6b7280;
        }

        /* =========================================================
           BUTTONS
        ========================================================== */

        .print-actions {
            max-width: 1400px;
            margin: 0 auto 15px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .print-button,
        .back-button {
            border: 0;
            border-radius: 6px;
            padding: 9px 15px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .print-button {
            background: #1677f0;
            color: #ffffff;
        }

        .back-button {
            background: #ffffff;
            color: #374151;
            border: 1px solid #dfe5ec;
        }

        /* =========================================================
           PRINT
        ========================================================== */

        @media print {

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                background: #ffffff;
                padding: 0;
                margin: 0;
            }

            .print-actions {
                display: none !important;
            }

            .print-page {
                max-width: none;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            .school-header {
                margin-bottom: 12px;
            }

            .report-info {
                margin-bottom: 10px;
            }

            .filter-summary {
                margin-bottom: 10px;
            }

            .summary-grid {
                margin-bottom: 10px;
            }

            .report-table th {
                background: #1677f0 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .status {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .report-table {
                page-break-inside: auto;
            }

            .report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .report-table thead {
                display: table-header-group;
            }

            .report-footer {
                page-break-inside: avoid;
            }
        }

        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 900px) {

            body {
                padding: 10px;
            }

            .print-page {
                padding: 15px;
            }

            .report-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .summary-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .school-header-inner {
                flex-direction: column;
            }
        }

        @media (max-width: 600px) {

            .report-info-grid {
                grid-template-columns: 1fr;
            }

            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .school-name {
                font-size: 20px;
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
            onclick="window.print()"
            class="print-button"
        >
            Print Report
        </button>

    </div>


    <div class="print-page">

        {{-- =========================================================
             SCHOOL PROFILE
        ========================================================== --}}

        @php

            $school = $schoolSetting
                ?? \App\Models\SchoolSetting::first();

            $schoolName = $school?->school_name
                ?? 'School Name';

            $schoolAddressParts = array_filter([
                $school?->address,
                $school?->city,
                $school?->district,
                $school?->state,
                $school?->pincode,
            ]);

            $schoolAddress = implode(
                ', ',
                $schoolAddressParts
            );

            $contactParts = [];

            if (!empty($school?->phone)) {
                $contactParts[] =
                    'Phone: ' . $school->phone;
            }

            if (!empty($school?->email)) {
                $contactParts[] =
                    'Email: ' . $school->email;
            }

            if (!empty($school?->udise_code)) {
                $contactParts[] =
                    'UDISE: ' . $school->udise_code;
            }

            if (!empty($school?->school_code)) {
                $contactParts[] =
                    'School Code: ' . $school->school_code;
            }

            $schoolLogo = null;

            if (!empty($school?->logo)) {

                if (
                    str_starts_with(
                        $school->logo,
                        'http://'
                    )
                    ||
                    str_starts_with(
                        $school->logo,
                        'https://'
                    )
                ) {

                    $schoolLogo = $school->logo;

                } else {

                    $schoolLogo = asset(
                        'storage/' .
                        ltrim(
                            $school->logo,
                            '/'
                        )
                    );
                }
            }

        @endphp


        {{-- =========================================================
             SCHOOL HEADER
        ========================================================== --}}

        <div class="school-header">

            <div class="school-header-inner">

                @if($schoolLogo)

                    <img
                        src="{{ $schoolLogo }}"
                        alt="School Logo"
                        class="school-logo"
                    >

                @endif


                <div class="school-details">

                    <h1 class="school-name">
                        {{ $schoolName }}
                    </h1>

                    @if($schoolAddress)

                        <div class="school-address">
                            {{ $schoolAddress }}
                        </div>

                    @endif


                    @if(!empty($contactParts))

                        <div class="school-contact">
                            {{ implode(' | ', $contactParts) }}
                        </div>

                    @endif

                </div>

            </div>


            <div class="report-title">
                {{ $reportTitle ?? 'Student Supply Kit Report' }}
            </div>

        </div>


        {{-- =========================================================
             REPORT INFORMATION
        ========================================================== --}}

        <div class="report-info">

            <div class="report-info-grid">

                <div class="info-item">

                    <div class="info-label">
                        Academic Year
                    </div>

                    <div class="info-value">
                        {{ $academicYear ?? '-' }}
                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Class
                    </div>

                    <div class="info-value">

                        @if(!empty($selectedClass))

                            {{ $selectedClass }}

                        @else

                            All Classes

                        @endif

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Section
                    </div>

                    <div class="info-value">

                        @if(!empty($section))

                            {{ $section }}

                        @else

                            All Sections

                        @endif

                    </div>

                </div>


                <div class="info-item">

                    <div class="info-label">
                        Generated On
                    </div>

                    <div class="info-value">
                        {{ now()->format('d-m-Y h:i A') }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}

        @if(
            !empty($search)
            || !empty($status)
            || !empty($schemeId)
            || !empty($kitTemplateId)
        )

            <div class="filter-summary">

                <div class="filter-heading">
                    Applied Filters
                </div>

                <div class="filter-list">

                    @if(!empty($search))

                        <span class="filter-badge">
                            Search: {{ $search }}
                        </span>

                    @endif


                    @if(!empty($status))

                        <span class="filter-badge">
                            Status:
                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $status
                                )
                            ) }}
                        </span>

                    @endif


                    @if(!empty($schemeId))

                        <span class="filter-badge">

                            Scheme:
                            {{ $selectedScheme?->scheme_name ?? '-' }}

                        </span>

                    @endif


                    @if(!empty($kitTemplateId))

                        <span class="filter-badge">

                            Kit:
                            {{ $selectedKitTemplate?->kit_name ?? '-' }}

                        </span>

                    @endif

                </div>

            </div>

        @endif


        {{-- =========================================================
             SUMMARY
        ========================================================== --}}

        <div class="summary-grid">

            <div class="summary-box">

                <div class="summary-number">
                    {{ $summary['total'] ?? 0 }}
                </div>

                <div class="summary-label">
                    Total Students
                </div>

            </div>


            <div class="summary-box summary-issued">

                <div class="summary-number">
                    {{ $summary['issued'] ?? 0 }}
                </div>

                <div class="summary-label">
                    Issued
                </div>

            </div>


            <div class="summary-box summary-pending">

                <div class="summary-number">
                    {{ $summary['pending'] ?? 0 }}
                </div>

                <div class="summary-label">
                    Pending
                </div>

            </div>


            <div class="summary-box summary-cancelled">

                <div class="summary-number">
                    {{ $summary['cancelled'] ?? 0 }}
                </div>

                <div class="summary-label">
                    Cancelled
                </div>

            </div>


            <div class="summary-box summary-not-assigned">

                <div class="summary-number">
                    {{ $summary['not_assigned'] ?? 0 }}
                </div>

                <div class="summary-label">
                    Not Assigned
                </div>

            </div>

        </div>


        {{-- =========================================================
             STUDENT DATA
        ========================================================== --}}

        @if($students && $students->count() > 0)

            <div class="report-table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>

                            <th
                                style="width: 4%;"
                                class="text-center"
                            >
                                #
                            </th>


                            {{-- CLASS COLUMN ONLY FOR ALL-CLASS REPORT --}}

                            @if(($reportMode ?? 'all') === 'all')

                                <th
                                    style="width: 10%;"
                                >
                                    Class
                                </th>

                            @endif


                            <th
                                style="width: 18%;"
                            >
                                Student
                            </th>


                            <th
                                style="width: 10%;"
                            >
                                Student ID
                            </th>


                            <th
                                style="width: 7%;"
                                class="text-center"
                            >
                                Section
                            </th>


                            <th
                                style="width: 10%;"
                                class="text-center"
                            >
                                Academic Year
                            </th>


                            <th
                                style="width: 20%;"
                            >
                                Kit
                            </th>


                            <th
                                style="width: 10%;"
                                class="text-center"
                            >
                                Distribution Date
                            </th>


                            <th
                                style="width: 11%;"
                                class="text-center"
                            >
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($students as $student)

                            @php

                                $studentName = trim(
                                    implode(
                                        ' ',
                                        array_filter([
                                            $student->first_name ?? '',
                                            $student->middle_name ?? '',
                                            $student->last_name ?? '',
                                        ])
                                    )
                                );

                                $kit =
                                    $student->supplyKitRecord
                                    ?? null;

                                $kitStatus =
                                    $kit?->status
                                    ?? 'not_assigned';

                            @endphp


                            <tr>

                                {{-- NUMBER --}}

                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>


                                {{-- CLASS --}}

                                @if(($reportMode ?? 'all') === 'all')

                                    <td>
                                        {{ $student->admission_class ?: '-' }}
                                    </td>

                                @endif


                                {{-- STUDENT --}}

                                <td>

                                    <div class="student-name">
                                        {{ $studentName ?: '-' }}
                                    </div>

                                </td>


                                {{-- STUDENT ID --}}

                                <td>
                                    {{ $student->student_id ?: '-' }}
                                </td>


                                {{-- SECTION --}}

                                <td class="text-center">
                                    {{ $student->section ?: '-' }}
                                </td>


                                {{-- ACADEMIC YEAR --}}

                                <td class="text-center">
                                    {{ $student->academic_year ?: $academicYear }}
                                </td>


                                {{-- KIT --}}

                                <td>

                                    @if($kit)

                                        <div class="kit-name">

                                            {{ $kit->kitTemplate?->kit_name ?? '-' }}

                                        </div>


                                        @if($kit->kitTemplate?->scheme)

                                            <div class="scheme-name">

                                                {{ $kit->kitTemplate->scheme->scheme_name }}

                                            </div>

                                        @endif

                                    @else

                                        <span>
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- DISTRIBUTION DATE --}}

                                <td class="text-center">

                                    @if($kit?->distribution_date)

                                        {{ \Illuminate\Support\Carbon::parse(
                                            $kit->distribution_date
                                        )->format('d-m-Y') }}

                                    @else

                                        -

                                    @endif

                                </td>


                                {{-- STATUS --}}

                                <td class="text-center">

                                    @if($kitStatus === 'issued')

                                        <span class="status status-issued">
                                            Issued
                                        </span>

                                    @elseif($kitStatus === 'pending')

                                        <span class="status status-pending">
                                            Pending
                                        </span>

                                    @elseif($kitStatus === 'cancelled')

                                        <span class="status status-cancelled">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="status status-not-assigned">
                                            Not Assigned
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-title">
                    No Students Found
                </div>

                <div class="empty-text">
                    No students match the selected academic year or filters.
                </div>

            </div>

        @endif


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="report-footer">

            <div>
                Student Supply Kit Management Report
            </div>

            <div>
                Total Records:
                {{ $students?->count() ?? 0 }}
            </div>

        </div>

    </div>

</body>

</html>