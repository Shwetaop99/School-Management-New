@extends('layouts.app')

@section('title', 'Age Report')

@section('content')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .age-report-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .age-report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .age-report-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .age-report-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8f1ff;
        color: #1677f0;
        font-size: 23px;
    }

    .age-report-title h3 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .age-report-title p {
        margin: 4px 0 0;
        color: #7b8497;
        font-size: 14px;
    }

    .age-report-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .age-report-actions .btn {
        border-radius: 9px;
        font-weight: 600;
        padding: 9px 15px;
    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .filter-card {
        background: #ffffff;
        border: 1px solid #e8ebf2;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(26, 39, 65, 0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .filter-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filter-card-header h5 {
        margin: 0;
        color: #202a3c;
        font-size: 16px;
        font-weight: 700;
    }

    .filter-card-header h5 i {
        color: #1677f0;
        margin-right: 7px;
    }

    .filter-card-body {
        padding: 20px;
    }

    .filter-label {
        display: block;
        margin-bottom: 7px;
        color: #505b70;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-control {
        height: 43px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        color: #303a4e;
        font-size: 14px;
        box-shadow: none !important;
    }

    .filter-control:focus {
        border-color: #1677f0;
        box-shadow: 0 0 0 3px rgba(22, 119, 240, 0.08) !important;
    }

    .filter-buttons {
        display: flex;
        align-items: end;
        gap: 8px;
    }

    .filter-buttons .btn {
        height: 43px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
    }


    /* =========================================================
       SUMMARY CARDS
    ========================================================= */

    .summary-card {
        background: #ffffff;
        border: 1px solid #e8ebf2;
        border-radius: 14px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 5px 18px rgba(26, 39, 65, 0.04);
    }

    .summary-label {
        color: #737d90;
        font-size: 13px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .summary-value {
        color: #172033;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.2;
    }

    .summary-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef5ff;
        color: #1769d1;
        font-size: 20px;
    }


    /* =========================================================
       REPORT CARD
    ========================================================= */

    .report-card {
        background: #ffffff;
        border: 1px solid #e8ebf2;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(26, 39, 65, 0.05);
        overflow: hidden;
    }

    .report-card-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .report-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #202a3c;
    }

    .report-count {
        font-size: 13px;
        color: #697386;
    }


    /* =========================================================
       TABLE
       FORMAT 1:
       AGE GROUP | GENDER | CLASSES | TOTAL
    ========================================================= */

    .report-table-wrapper {
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        min-width: 1150px;
        border-collapse: collapse;
        margin: 0;
    }

    .report-table thead th {
        background: #172033;
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 12px 8px;
        border: 1px solid #30394c;
        white-space: nowrap;
    }

    .report-table thead th:first-child {
        text-align: left;
        padding-left: 14px;
    }

    .report-table tbody td {
        padding: 9px 8px;
        border: 1px solid #e2e6ed;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
        color: #384255;
    }

    .report-table tbody td:first-child {
        text-align: left;
        padding-left: 14px;
    }

    .report-table tbody tr:hover td {
        background: #f8faff;
    }


    /* =========================================================
       AGE GROUP BADGE
    ========================================================= */

    .age-group-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 75px;
        padding: 5px 10px;
        background: #eef5ff;
        color: #1769d1;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
    }


    /* =========================================================
       GENDER
    ========================================================= */

    .gender-boys {
        color: #1769d1;
        font-weight: 600;
    }

    .gender-girls {
        color: #b13c78;
        font-weight: 600;
    }

    .gender-total {
        color: #202a3c;
        font-weight: 700;
    }


    /* =========================================================
       NUMBER CELLS
    ========================================================= */

    .number-cell {
        font-weight: 600;
    }

    .total-cell {
        background: #f7f9fc;
        font-weight: 700 !important;
        color: #172033 !important;
    }

    .age-total-row td {
        background: #f8fafc;
        font-weight: 700;
    }


    /* =========================================================
       GRAND TOTAL
    ========================================================= */

    .grand-total-row td {
        background: #172033 !important;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13px;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-report {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-report-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #f0f3f8;
        color: #8791a3;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .empty-report h6 {
        color: #3a4457;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .empty-report p {
        color: #818a9c;
        margin: 0;
        font-size: 13px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .age-report-page {
            padding: 16px;
        }

        .age-report-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .age-report-actions {
            width: 100%;
        }

        .filter-buttons {
            margin-top: 5px;
        }
    }


    @media (max-width: 575px) {

        .age-report-page {
            padding: 12px;
        }

        .age-report-title h3 {
            font-size: 21px;
        }

        .age-report-icon {
            width: 45px;
            height: 45px;
        }

        .report-card-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }


    /* =========================================================
       PRINT
    ========================================================= */

    @media print {

        @page {
            size: A4 landscape;
            margin: 7mm;
        }

        body {
            background: #ffffff !important;
        }

        .age-report-page {
            padding: 0 !important;
            background: #ffffff !important;
            min-height: auto !important;
        }

        .no-print,
        .filter-card,
        .age-report-actions {
            display: none !important;
        }

        .age-report-header {
            margin-bottom: 10px !important;
        }

        .age-report-icon {
            display: none !important;
        }

        .age-report-title h3 {
            font-size: 18px !important;
        }

        .age-report-title p {
            font-size: 10px !important;
        }

        .summary-card {
            box-shadow: none !important;
            padding: 5px !important;
        }

        .summary-card .summary-value {
            font-size: 15px !important;
        }

        .report-card {
            border: 0 !important;
            box-shadow: none !important;
        }

        .report-card-header {
            padding: 5px 0 !important;
            border-bottom: 1px solid #222 !important;
        }

        .report-table-wrapper {
            overflow: visible !important;
        }

        .report-table {
            min-width: 0 !important;
            width: 100% !important;
            table-layout: fixed;
        }

        .report-table thead th {
            font-size: 8px !important;
            padding: 5px 3px !important;
        }

        .report-table tbody td {
            font-size: 8px !important;
            padding: 4px 3px !important;
        }

        .age-group-badge {
            background: transparent !important;
            color: #000000 !important;
            padding: 0 !important;
        }

        .grand-total-row td {
            background: #eeeeee !important;
            color: #000000 !important;
        }

        .report-table tr {
            page-break-inside: avoid;
        }
    }

</style>


<div class="age-report-page">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="age-report-header">

        <div class="age-report-title-wrap">

            <div class="age-report-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="age-report-title">

                <h3>
                    Age Report
                </h3>

                <p>
                    Age-group wise boys and girls summary
                </p>

            </div>

        </div>


        <div class="age-report-actions no-print">

            <a
                href="{{ route('admin.age-report.print', request()->query()) }}"
                target="_blank"
                class="btn btn-primary"
            >
                <i class="bi bi-printer-fill me-1"></i>
                Print Report
            </a>

        </div>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <div class="filter-card no-print">

        <div class="filter-card-header">

            <h5>
                <i class="bi bi-funnel-fill"></i>
                Report Filters
            </h5>

            @if(
                request('academic_year') ||
                request('class') ||
                request('section') ||
                request('gender') ||
                request('age_as_on')
            )

                <span class="badge bg-primary">
                    Filters Applied
                </span>

            @endif

        </div>


        <div class="filter-card-body">

            <form
                method="GET"
                action="{{ route('admin.age-report.index') }}"
            >

                <div class="row g-3">


                    {{-- =================================================
                         ACADEMIC YEAR
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Academic Year
                        </label>

                        <select
                            name="academic_year"
                            class="form-select filter-control"
                        >

                            <option value="">
                                All Academic Years
                            </option>

                            @foreach($academicYears as $year)

                                <option
                                    value="{{ $year }}"
                                    {{ request('academic_year') == $year ? 'selected' : '' }}
                                >
                                    {{ $year }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         CLASS
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Class
                        </label>

                        <select
                            name="class"
                            class="form-select filter-control"
                        >

                            <option value="">
                                All Classes
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class }}"
                                    {{ request('class') == $class ? 'selected' : '' }}
                                >
                                    {{ $class }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         SECTION
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Section
                        </label>

                        <select
                            name="section"
                            class="form-select filter-control"
                        >

                            <option value="">
                                All Sections
                            </option>

                            @foreach($sections as $section)

                                <option
                                    value="{{ $section }}"
                                    {{ request('section') == $section ? 'selected' : '' }}
                                >
                                    {{ $section }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         GENDER
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select filter-control"
                        >

                            <option value="">
                                All Genders
                            </option>

                            <option
                                value="Male"
                                {{ request('gender') === 'Male' ? 'selected' : '' }}
                            >
                                Male
                            </option>

                            <option
                                value="Female"
                                {{ request('gender') === 'Female' ? 'selected' : '' }}
                            >
                                Female
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         AGE AS ON
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Age As On
                        </label>

                        <input
                            type="date"
                            name="age_as_on"
                            class="form-control filter-control"
                            value="{{ request('age_as_on', $ageAsOn ?? now()->format('Y-m-d')) }}"
                        >

                    </div>


                    {{-- =================================================
                         BUTTONS
                    ================================================== --}}

                    <div class="col-xl-2 col-lg-3 col-md-6">

                        <label class="filter-label">
                            &nbsp;
                        </label>

                        <div class="filter-buttons">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-search me-1"></i>
                                Apply
                            </button>


                            <a
                                href="{{ route('admin.age-report.index') }}"
                                class="btn btn-outline-secondary"
                                title="Reset Filters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY CARDS
    ====================================================== --}}

    <div class="row g-3 mb-4">


        {{-- TOTAL --}}

        <div class="col-xl-4 col-md-4">

            <div class="summary-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="summary-label">
                            Total Students
                        </div>

                        <div class="summary-value">
                            {{ $totalStudents }}
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- BOYS --}}

        <div class="col-xl-4 col-md-4">

            <div class="summary-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="summary-label">
                            Boys
                        </div>

                        <div class="summary-value">
                            {{ $maleStudents }}
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-person-fill"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- GIRLS --}}

        <div class="col-xl-4 col-md-4">

            <div class="summary-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="summary-label">
                            Girls
                        </div>

                        <div class="summary-value">
                            {{ $femaleStudents }}
                        </div>

                    </div>

                    <div class="summary-icon">
                        <i class="bi bi-person-heart"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         REPORT TABLE
    ====================================================== --}}

    <div class="report-card">


        <div class="report-card-header">

            <h5>

                <i class="bi bi-table me-2 text-primary"></i>

                Age Group Summary

            </h5>


            <span class="report-count">

                Age As On:

                <strong>
                    {{ \Carbon\Carbon::parse($ageAsOn ?? now())->format('d-m-Y') }}
                </strong>

            </span>

        </div>


        @if($report->count())


            @php

                /*
                |--------------------------------------------------------------------------
                | AGE GROUPS
                |--------------------------------------------------------------------------
                */

                $ageGroups = [
                    'Below 5',
                    '5–6',
                    '7–8',
                    '9–10',
                    '11–12',
                    '13–14',
                    '15–16',
                    '17+',
                ];


                /*
                |--------------------------------------------------------------------------
                | CLASSES
                |--------------------------------------------------------------------------
                */

                $classes = [
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


                /*
                |--------------------------------------------------------------------------
                | GROUP REPORT BY AGE GROUP
                |--------------------------------------------------------------------------
                */

                $groupedReport = $report->groupBy(function ($row) {

                    return trim($row->age_group);

                });


                /*
                |--------------------------------------------------------------------------
                | GRAND CLASS TOTALS
                |--------------------------------------------------------------------------
                */

                $grandClassTotals = [];

                foreach ($classes as $class) {

                    $grandClassTotals[$class] = [
                        'boys' => 0,
                        'girls' => 0,
                        'total' => 0,
                    ];

                }

            @endphp


            <div class="report-table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>

                            <th>
                                Age Group
                            </th>

                            <th>
                                Gender
                            </th>

                            @foreach($classes as $class)

                                <th>
                                    {{ $class }}
                                </th>

                            @endforeach

                            <th>
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @foreach($ageGroups as $ageGroup)

                            @php

                                $ageRows = $groupedReport->get(
                                    $ageGroup,
                                    collect()
                                );


                                $classData = [];

                                foreach ($classes as $class) {

                                    $row = $ageRows->first(
                                        function ($item) use ($class) {

                                            return trim($item->class) === $class;

                                        }
                                    );


                                    $classData[$class] = [
                                        'boys' => $row
                                            ? (int) $row->boys
                                            : 0,

                                        'girls' => $row
                                            ? (int) $row->girls
                                            : 0,

                                        'total' => $row
                                            ? (int) $row->total
                                            : 0,
                                    ];

                                }


                                $ageBoys = 0;

                                $ageGirls = 0;

                                $ageTotal = 0;


                                foreach ($classes as $class) {

                                    $ageBoys +=
                                        $classData[$class]['boys'];

                                    $ageGirls +=
                                        $classData[$class]['girls'];

                                    $ageTotal +=
                                        $classData[$class]['total'];


                                    $grandClassTotals[$class]['boys']
                                        += $classData[$class]['boys'];

                                    $grandClassTotals[$class]['girls']
                                        += $classData[$class]['girls'];

                                    $grandClassTotals[$class]['total']
                                        += $classData[$class]['total'];

                                }

                            @endphp


                            {{-- =================================================
                                 BOYS
                            ================================================== --}}

                            <tr>

                                <td rowspan="3">

                                    <span class="age-group-badge">
                                        {{ $ageGroup }}
                                    </span>

                                </td>


                                <td class="gender-boys">
                                    Boys
                                </td>


                                @foreach($classes as $class)

                                    <td class="number-cell">

                                        {{ $classData[$class]['boys'] }}

                                    </td>

                                @endforeach


                                <td class="total-cell">

                                    {{ $ageBoys }}

                                </td>

                            </tr>


                            {{-- =================================================
                                 GIRLS
                            ================================================== --}}

                            <tr>

                                <td class="gender-girls">
                                    Girls
                                </td>


                                @foreach($classes as $class)

                                    <td class="number-cell">

                                        {{ $classData[$class]['girls'] }}

                                    </td>

                                @endforeach


                                <td class="total-cell">

                                    {{ $ageGirls }}

                                </td>

                            </tr>


                            {{-- =================================================
                                 TOTAL
                            ================================================== --}}

                            <tr class="age-total-row">

                                <td class="gender-total">
                                    Total
                                </td>


                                @foreach($classes as $class)

                                    <td class="number-cell">

                                        {{ $classData[$class]['total'] }}

                                    </td>

                                @endforeach


                                <td class="total-cell">

                                    {{ $ageTotal }}

                                </td>

                            </tr>


                        @endforeach


                        {{-- =====================================================
                             GRAND TOTAL
                        ====================================================== --}}

                        <tr class="grand-total-row">

                            <td>
                                Grand Total
                            </td>

                            <td>
                                Boys + Girls
                            </td>


                            @foreach($classes as $class)

                                <td>

                                    {{ $grandClassTotals[$class]['total'] }}

                                </td>

                            @endforeach


                            <td>

                                {{ $report->sum('total') }}

                            </td>

                        </tr>


                    </tbody>

                </table>

            </div>


        @else


            {{-- =================================================
                 EMPTY STATE
            ================================================== --}}

            <div class="empty-report">

                <div class="empty-report-icon">

                    <i class="bi bi-calendar-x"></i>

                </div>

                <h6>
                    No Age Report Data Found
                </h6>

                <p>
                    No active students match the selected filters.
                </p>

            </div>


        @endif


    </div>

</div>

@endsection