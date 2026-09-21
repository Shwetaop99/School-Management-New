@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       AGE REPORT - A4 LANDSCAPE PRINT
    ========================================================== */

    .age-print-page {
        background: #e5e7eb;
        min-height: 100vh;
        padding: 20px 0;
    }

    .a4-sheet {
        width: 297mm;
        min-height: 210mm;
        margin: 0 auto;
        background: #ffffff;
        padding: 10mm;
        box-sizing: border-box;
        color: #111827;
        position: relative;
    }

    /* =========================================================
       SCHOOL HEADER
    ========================================================== */

    .school-header {
        text-align: center;
        border-bottom: 2px solid #111827;
        padding-bottom: 7px;
        margin-bottom: 8px;
    }

    .school-logo {
        max-width: 65px;
        max-height: 65px;
        object-fit: contain;
        margin-bottom: 3px;
    }

    .school-name {
        font-size: 21px;
        font-weight: 800;
        text-transform: uppercase;
        margin: 0;
        line-height: 1.2;
    }

    .school-address {
        font-size: 10px;
        margin-top: 3px;
        color: #374151;
    }

    .school-contact {
        font-size: 9px;
        color: #4b5563;
        margin-top: 2px;
    }

    /* =========================================================
       REPORT TITLE
    ========================================================== */

    .report-title-section {
        text-align: center;
        margin-bottom: 8px;
    }

    .report-title {
        font-size: 17px;
        font-weight: 800;
        text-transform: uppercase;
        text-decoration: underline;
        margin: 0;
    }

    .report-subtitle {
        font-size: 10px;
        margin-top: 2px;
        color: #4b5563;
    }

    /* =========================================================
       REPORT INFORMATION
    ========================================================== */

    .report-info {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        border: 1px solid #6b7280;
        margin-bottom: 8px;
        font-size: 9px;
    }

    .report-info-item {
        padding: 5px 7px;
        border-right: 1px solid #9ca3af;
    }

    .report-info-item:last-child {
        border-right: none;
    }

    .report-info-label {
        font-weight: 700;
    }

    /* =========================================================
       AGE REPORT TABLE
    ========================================================== */

    .age-report-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .age-report-table th,
    .age-report-table td {
        border: 1px solid #374151;
        padding: 4px 3px;
        font-size: 8px;
        vertical-align: middle;
    }

    .age-report-table thead th {
        background: #e5e7eb;
        text-align: center;
        font-weight: 800;
        line-height: 1.15;
    }

    .age-report-table td {
        text-align: center;
        height: 22px;
    }

    /* =========================================================
       COLUMN WIDTHS
    ========================================================== */

    .col-age-group {
        width: 9%;
    }

    .col-gender {
        width: 7%;
    }

    .col-class {
        width: 4.8%;
    }

    .col-total {
        width: 6%;
    }

    /* =========================================================
       AGE GROUP
    ========================================================== */

    .age-group-cell {
        font-weight: 800;
        text-align: left !important;
        padding-left: 5px !important;
        background: #f9fafb;
    }

    .gender-cell {
        font-weight: 700;
    }

    .boys-row td {
        background: #f8fbff;
    }

    .girls-row td {
        background: #fffafb;
    }

    .total-row td {
        background: #f3f4f6;
        font-weight: 800;
    }

    .total-row .gender-cell {
        text-transform: uppercase;
    }

    .grand-total-row td {
        background: #e5e7eb;
        font-weight: 900;
        font-size: 9px;
        border-top: 2px solid #111827;
    }

    .number-cell {
        text-align: center;
    }

    .total-cell {
        font-weight: 800;
    }

    /* =========================================================
       SUMMARY
    ========================================================== */

    .summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    .summary-table th,
    .summary-table td {
        border: 1px solid #4b5563;
        padding: 5px;
        font-size: 9px;
    }

    .summary-table th {
        background: #f3f4f6;
        font-weight: 800;
        text-align: center;
    }

    .summary-table td {
        text-align: center;
        font-weight: 800;
    }

    /* =========================================================
       SIGNATURES
    ========================================================== */

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
        padding: 0 30px;
    }

    .signature-box {
        width: 180px;
        text-align: center;
        font-size: 9px;
        font-weight: 700;
    }

    .signature-space {
        height: 20px;
    }

    .signature-line {
        border-top: 1px solid #111827;
        margin-bottom: 4px;
    }

    /* =========================================================
       FOOTER
    ========================================================== */

    .print-footer {
        position: absolute;
        bottom: 5mm;
        left: 10mm;
        right: 10mm;
        border-top: 1px solid #9ca3af;
        padding-top: 3px;
        display: flex;
        justify-content: space-between;
        font-size: 8px;
        color: #6b7280;
    }

    /* =========================================================
       TOOLBAR
    ========================================================== */

    .print-toolbar {
        width: 297mm;
        margin: 0 auto 12px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    /* =========================================================
       SCREEN
    ========================================================== */

    @media screen {

        .a4-sheet {
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        }
    }

    /* =========================================================
       PRINT
    ========================================================== */

    @page {
        size: A4 landscape;
        margin: 0;
    }

    @media print {

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        body * {
            visibility: hidden;
        }

        .a4-sheet,
        .a4-sheet * {
            visibility: visible;
        }

        .age-print-page {
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
            min-height: auto !important;
        }

        .a4-sheet {
            width: 297mm;
            min-height: 210mm;
            margin: 0;
            padding: 10mm;
            box-shadow: none;
        }

        .print-toolbar,
        .no-print {
            display: none !important;
        }

        .age-report-table {
            page-break-inside: auto;
        }

        .age-report-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .age-report-table thead {
            display: table-header-group;
        }

        .summary-table {
            page-break-inside: avoid;
        }

        .signature-section {
            page-break-inside: avoid;
        }
    }
</style>


<div class="age-print-page">

    {{-- =========================================================
         PRINT TOOLBAR
    ========================================================== --}}

    <div class="print-toolbar no-print">

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary"
        >
            <i class="bi bi-printer-fill me-1"></i>
            Print Report
        </button>

        <button
            type="button"
            onclick="window.close()"
            class="btn btn-secondary"
        >
            <i class="bi bi-x-lg me-1"></i>
            Close
        </button>

    </div>


    {{-- =========================================================
         A4 LANDSCAPE SHEET
    ========================================================== --}}

    <div class="a4-sheet">


        {{-- =====================================================
             SCHOOL HEADER
        ====================================================== --}}

        <div class="school-header">

            @if(!empty($schoolSetting?->logo_url))

                <img
                    src="{{ $schoolSetting->logo_url }}"
                    class="school-logo"
                    alt="School Logo"
                >

            @elseif(!empty($schoolSetting?->logo))

                <img
                    src="{{ asset($schoolSetting->logo) }}"
                    class="school-logo"
                    alt="School Logo"
                >

            @elseif(file_exists(public_path('images/gurukullogo.png')))

                <img
                    src="{{ asset('images/gurukullogo.png') }}"
                    class="school-logo"
                    alt="School Logo"
                >

            @endif


            <h1 class="school-name">
                {{ $schoolSetting?->school_name ?? 'School Management System' }}
            </h1>


            @if(!empty($schoolSetting?->address))

                <div class="school-address">
                    {{ $schoolSetting->address }}
                </div>

            @endif


            <div class="school-contact">

                @if(!empty($schoolSetting?->phone))
                    Phone: {{ $schoolSetting->phone }}
                @endif

                @if(!empty($schoolSetting?->email))

                    @if(!empty($schoolSetting?->phone))
                        &nbsp; | &nbsp;
                    @endif

                    Email: {{ $schoolSetting->email }}

                @endif

            </div>

        </div>


        {{-- =====================================================
             REPORT TITLE
        ====================================================== --}}

        <div class="report-title-section">

            <h2 class="report-title">
                Student Age Report
            </h2>

            <div class="report-subtitle">
                Age-wise class and gender distribution of active students
            </div>

        </div>


        {{-- =====================================================
             REPORT INFORMATION
        ====================================================== --}}

        <div class="report-info">

            <div class="report-info-item">

                <span class="report-info-label">
                    Academic Year:
                </span>

                {{ request('academic_year') ?: 'All Academic Years' }}

            </div>


            <div class="report-info-item">

                <span class="report-info-label">
                    Class:
                </span>

                {{ request('class') ?: 'All Classes' }}

            </div>


            <div class="report-info-item">

                <span class="report-info-label">
                    Section:
                </span>

                {{ request('section') ?: 'All Sections' }}

            </div>


            <div class="report-info-item">

                <span class="report-info-label">
                    Gender:
                </span>

                {{ request('gender') ?: 'All' }}

            </div>


            <div class="report-info-item">

                <span class="report-info-label">
                    Age As On:
                </span>

                {{ $ageAsOn->format('d-m-Y') }}

            </div>

        </div>


        {{-- =====================================================
             FORMAT 1 AGE REPORT TABLE
        ====================================================== --}}

        @php

            $reportClasses = [
                'Nursery',
                'LKG',
                'UKG',
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12',
            ];

            $reportAgeGroups = [
                'Below 5',
                '5–6',
                '7–8',
                '9–10',
                '11–12',
                '13–14',
                '15–16',
                '17+',
            ];

            $groupedReport = $report->groupBy(function ($row) {
                return trim($row->age_group);
            });

            $grandClassTotals = [];

            foreach ($reportClasses as $class) {
                $grandClassTotals[$class] = 0;
            }

        @endphp


        <table class="age-report-table">

            <thead>

                <tr>

                    <th class="col-age-group">
                        Age Group
                    </th>

                    <th class="col-gender">
                        Gender
                    </th>

                    @foreach($reportClasses as $class)

                        <th class="col-class">
                            {{ $class }}
                        </th>

                    @endforeach

                    <th class="col-total">
                        Total
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($reportAgeGroups as $ageGroup)

                    @php

                        $groupRows = $groupedReport->get(
                            $ageGroup,
                            collect()
                        );

                        $classData = [];

                        foreach ($reportClasses as $class) {

                            $classRow = $groupRows->first(
                                function ($row) use ($class) {
                                    return trim($row->class) === $class;
                                }
                            );

                            $classData[$class] = [
                                'boys' => $classRow
                                    ? (int) $classRow->boys
                                    : 0,

                                'girls' => $classRow
                                    ? (int) $classRow->girls
                                    : 0,

                                'total' => $classRow
                                    ? (int) $classRow->total
                                    : 0,
                            ];
                        }

                    @endphp


                    {{-- =================================================
                         BOYS
                    ================================================== --}}

                    <tr class="boys-row">

                        <td
                            rowspan="3"
                            class="age-group-cell"
                        >
                            {{ $ageGroup }}
                        </td>

                        <td class="gender-cell">
                            Boys
                        </td>

                        @php
                            $boysGrandTotal = 0;
                        @endphp

                        @foreach($reportClasses as $class)

                            @php
                                $boysCount =
                                    $classData[$class]['boys'];

                                $boysGrandTotal += $boysCount;
                            @endphp

                            <td class="number-cell">
                                {{ $boysCount }}
                            </td>

                        @endforeach

                        <td class="total-cell">
                            {{ $boysGrandTotal }}
                        </td>

                    </tr>


                    {{-- =================================================
                         GIRLS
                    ================================================== --}}

                    <tr class="girls-row">

                        <td class="gender-cell">
                            Girls
                        </td>

                        @php
                            $girlsGrandTotal = 0;
                        @endphp

                        @foreach($reportClasses as $class)

                            @php
                                $girlsCount =
                                    $classData[$class]['girls'];

                                $girlsGrandTotal += $girlsCount;
                            @endphp

                            <td class="number-cell">
                                {{ $girlsCount }}
                            </td>

                        @endforeach

                        <td class="total-cell">
                            {{ $girlsGrandTotal }}
                        </td>

                    </tr>


                    {{-- =================================================
                         TOTAL
                    ================================================== --}}

                    <tr class="total-row">

                        <td class="gender-cell">
                            Total
                        </td>

                        @php
                            $ageGroupGrandTotal = 0;
                        @endphp

                        @foreach($reportClasses as $class)

                            @php

                                $classTotal =
                                    $classData[$class]['total'];

                                $grandClassTotals[$class] +=
                                    $classTotal;

                                $ageGroupGrandTotal +=
                                    $classTotal;

                            @endphp

                            <td class="number-cell">
                                {{ $classTotal }}
                            </td>

                        @endforeach

                        <td class="total-cell">
                            {{ $ageGroupGrandTotal }}
                        </td>

                    </tr>

                @endforeach


                {{-- =====================================================
                     GRAND TOTAL
                ====================================================== --}}

                @php
                    $grandTotal = 0;
                @endphp

                <tr class="grand-total-row">

                    <td
                        colspan="2"
                        class="text-center"
                    >
                        GRAND TOTAL
                    </td>

                    @foreach($reportClasses as $class)

                        @php
                            $grandTotal +=
                                $grandClassTotals[$class];
                        @endphp

                        <td class="number-cell">
                            {{ $grandClassTotals[$class] }}
                        </td>

                    @endforeach

                    <td class="total-cell">
                        {{ $grandTotal }}
                    </td>

                </tr>

            </tbody>

        </table>


        {{-- =====================================================
             SUMMARY
        ====================================================== --}}

        <table class="summary-table">

            <thead>

                <tr>

                    <th>
                        Total Students
                    </th>

                    <th>
                        Boys
                    </th>

                    <th>
                        Girls
                    </th>

                    <th>
                        Age As On
                    </th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        {{ $totalStudents }}
                    </td>

                    <td>
                        {{ $maleStudents }}
                    </td>

                    <td>
                        {{ $femaleStudents }}
                    </td>

                    <td>
                        {{ $ageAsOn->format('d-m-Y') }}
                    </td>

                </tr>

            </tbody>

        </table>


        {{-- =====================================================
             SIGNATURES
        ====================================================== --}}

        <div class="signature-section">

            <div class="signature-box">

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                Class Teacher

            </div>


            <div class="signature-box">

                <div class="signature-space"></div>

                <div class="signature-line"></div>

                Principal / Headmaster

            </div>

        </div>


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="print-footer">

            <span>
                Student Age Report
            </span>

            <span>
                Generated on
                {{ $reportDate ?? now()->format('d-m-Y') }}
            </span>

        </div>

    </div>

</div>

@endsection