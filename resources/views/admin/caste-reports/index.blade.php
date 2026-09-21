@extends('layouts.app')

@section('title', 'Caste / Category Report')

@section('content')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .caste-report-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 22px;
    }

    .report-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .report-icon {
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

    .report-title {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #172033;
    }

    .report-subtitle {
        margin: 4px 0 0;
        color: #7b8497;
        font-size: 14px;
    }


    /* =========================================================
       HEADER ACTIONS
    ========================================================= */

    .report-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .report-actions .btn {
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
        gap: 10px;
    }

    .filter-title {
        margin: 0;
        color: #202a3c;
        font-size: 16px;
        font-weight: 700;
    }

    .filter-title i {
        color: #1677f0;
        margin-right: 7px;
    }

    .filter-content {
        padding: 19px 20px 20px;
    }

    .filter-label {
        display: block;
        margin-bottom: 7px;
        color: #505b70;
        font-size: 13px;
        font-weight: 600;
    }

    .filter-select {
        height: 43px;
        border: 1px solid #dfe4ec;
        border-radius: 9px;
        color: #303a4e;
        font-size: 14px;
        box-shadow: none !important;
    }

    .filter-select:focus {
        border-color: #1677f0;
    }

    .filter-buttons {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    .filter-buttons .btn {
        height: 43px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        padding: 0 17px;
    }


    /* =========================================================
       ACTIVE FILTERS
    ========================================================= */

    .active-filters {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .active-filter-label {
        font-size: 12px;
        color: #727c8e;
        font-weight: 600;
        margin-right: 3px;
    }

    .filter-badge {
        background: #edf5ff;
        color: #1769d1;
        border: 1px solid #d6e8ff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
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
       IMPORTANT: STRUCTURE KEPT SAME
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

    .category-badge {
        display: inline-flex;
        align-items: center;
        min-width: 65px;
        justify-content: center;
        padding: 5px 10px;
        background: #eef5ff;
        color: #1769d1;
        border-radius: 7px;
        font-weight: 700;
        font-size: 12px;
    }

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

    .number-cell {
        font-weight: 600;
    }

    .total-cell {
        background: #f7f9fc;
        font-weight: 700 !important;
        color: #172033 !important;
    }

    .category-total-row td {
        background: #f8fafc;
        font-weight: 700;
    }

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

        .caste-report-page {
            padding: 16px;
        }

        .report-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .report-actions {
            width: 100%;
        }

        .filter-buttons {
            margin-top: 10px;
        }
    }


    @media (max-width: 575px) {

        .caste-report-page {
            padding: 12px;
        }

        .report-title {
            font-size: 21px;
        }

        .report-icon {
            width: 45px;
            height: 45px;
        }

        .report-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .report-actions .btn {
            flex: 1;
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

        .caste-report-page {
            padding: 0 !important;
            background: #ffffff !important;
            min-height: auto !important;
        }

        .report-header {
            margin-bottom: 10px !important;
        }

        .report-icon {
            display: none !important;
        }

        .report-actions,
        .filter-card,
        .no-print {
            display: none !important;
        }

        .report-title {
            font-size: 18px !important;
        }

        .report-subtitle {
            font-size: 10px !important;
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

        .category-badge {
            background: transparent !important;
            color: #000000 !important;
            padding: 0 !important;
        }

        .grand-total-row td {
            background: #eeeeee !important;
            color: #000000 !important;
        }

        .report-table {
            page-break-inside: auto;
        }

        .report-table tr {
            page-break-inside: avoid;
        }
    }

</style>


<div class="caste-report-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <div class="report-header">

        <div class="report-title-wrap">

            <div class="report-icon">
                <i class="bi bi-bar-chart-fill"></i>
            </div>

            <div>

                <h3 class="report-title">
                    Caste Report
                </h3>

                <p class="report-subtitle">
                    Class-wise boys and girls summary
                </p>

            </div>

        </div>


        <div class="report-actions">

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.print()"
            >
                <i class="bi bi-printer me-1"></i>
                Print Report
            </button>

        </div>

    </div>


    {{-- =====================================================
        FILTER CARD
    ====================================================== --}}

    <div class="filter-card">

        <div class="filter-card-header">

            <h5 class="filter-title">
                <i class="bi bi-funnel-fill"></i>
                Report Filters
            </h5>

            @if($hasFilters)

                <span class="badge bg-primary">
                    Filters Applied
                </span>

            @endif

        </div>


        <div class="filter-content">

            <form
                method="GET"
                action="{{ url()->current() }}"
            >

                <div class="row g-3">

                    {{-- Academic Year --}}
                    <div class="col-xl-3 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Academic Year
                        </label>

                        <select
                            name="academic_year"
                            class="form-select filter-select"
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


                    {{-- Class --}}
                    <div class="col-xl-2 col-lg-2 col-md-6">

                        <label class="filter-label">
                            Class
                        </label>

                        <select
                            name="class"
                            class="form-select filter-select"
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


                    {{-- Section --}}
                    <div class="col-xl-2 col-lg-2 col-md-6">

                        <label class="filter-label">
                            Section
                        </label>

                        <select
                            name="section"
                            class="form-select filter-select"
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


                    {{-- Caste --}}
                    <div class="col-xl-3 col-lg-3 col-md-6">

                        <label class="filter-label">
                            Caste 
                        </label>

                        <select
                            name="caste"
                            class="form-select filter-select"
                        >

                            <option value="">
                                All Castes 
                            </option>

                            @foreach($castes as $caste)

                                <option
                                    value="{{ $caste }}"
                                    {{ request('caste') == $caste ? 'selected' : '' }}
                                >
                                    {{ $caste }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-2 col-lg-2 col-md-6">

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
                                href="{{ url()->current() }}"
                                class="btn btn-outline-secondary"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTIVE FILTER BADGES
                ================================================== --}}

                @if($hasFilters)

                    <div class="active-filters">

                        <span class="active-filter-label">
                            Active:
                        </span>


                        @if(request('academic_year'))

                            <span class="filter-badge">
                                Academic Year:
                                {{ request('academic_year') }}
                            </span>

                        @endif


                        @if(request('class'))

                            <span class="filter-badge">
                                Class:
                                {{ request('class') }}
                            </span>

                        @endif


                        @if(request('section'))

                            <span class="filter-badge">
                                Section:
                                {{ request('section') }}
                            </span>

                        @endif


                        @if(request('caste'))

                            <span class="filter-badge">
                                Caste:
                                {{ request('caste') }}
                            </span>

                        @endif

                    </div>

                @endif

            </form>

        </div>

    </div>


    {{-- =====================================================
        REPORT CARD
    ====================================================== --}}

    <div class="report-card">

        <div class="report-card-header">

            <h5>
                <i class="bi bi-table me-2 text-primary"></i>
                Caste / Category Summary
            </h5>

            <span class="report-count">

                {{ $report->count() }}

                report rows

                @if($grandTotal > 0)
                    · {{ $grandTotal }} students
                @endif

            </span>

        </div>


        {{-- =================================================
            REPORT TABLE
            STRUCTURE KEPT SAME
        ================================================== --}}

        @if($report->count())

            @php

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
                    '12'
                ];

                /*
                |--------------------------------------------------------------------------
                | Group Data By Caste
                |--------------------------------------------------------------------------
                */

                $groupedReport = $report->groupBy(function ($row) {
                    return trim($row->caste);
                });


                /*
                |--------------------------------------------------------------------------
                | Grand Class Totals
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

                            <th>Caste</th>

                            <th>Gender</th>

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

                        @foreach($groupedReport as $caste => $casteRows)

                            @php

                                $classData = [];

                                foreach ($classes as $class) {

                                    $row = $casteRows->first(function ($item) use ($class) {
                                        return trim($item->class) === $class;
                                    });

                                    $classData[$class] = [
                                        'boys' => $row ? (int) $row->boys : 0,
                                        'girls' => $row ? (int) $row->girls : 0,
                                        'total' => $row ? (int) $row->total : 0,
                                    ];
                                }


                                $casteBoys = 0;
                                $casteGirls = 0;
                                $casteTotal = 0;


                                foreach ($classes as $class) {

                                    $casteBoys += $classData[$class]['boys'];

                                    $casteGirls += $classData[$class]['girls'];

                                    $casteTotal += $classData[$class]['total'];


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

                                    <span class="category-badge">
                                        {{ $caste }}
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

                                    {{ $casteBoys }}

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

                                    {{ $casteGirls }}

                                </td>

                            </tr>


                            {{-- =================================================
                                TOTAL
                            ================================================== --}}

                            <tr class="category-total-row">

                                <td class="gender-total">
                                    Total
                                </td>


                                @foreach($classes as $class)

                                    <td class="number-cell">

                                        {{ $classData[$class]['total'] }}

                                    </td>

                                @endforeach


                                <td class="total-cell">

                                    {{ $casteTotal }}

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

                                {{ $grandTotal }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        @else

            {{-- =================================================
                EMPTY REPORT
            ================================================== --}}

            <div class="empty-report">

                <div class="empty-report-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <h6>
                    No Report Data Found
                </h6>

                <p>
                    No active students match the selected filters.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection