@extends('layouts.app')

@section('title', 'Caste / Category Report')

@section('content')

@php
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $schoolLogo = $school->logo ?? null;

    if ($schoolLogo && !preg_match('~^(https?://|/)~i', $schoolLogo)) {
        $schoolLogo = asset('storage/' . ltrim($schoolLogo, '/'));
    }

    if (!$schoolLogo) {
        $schoolLogo = asset('images/gurukullogo.png');
    }

    $schoolAddress = collect([
        $school->address ?? null,
        $school->city ?? null,
        $school->district ?? null,
        $school->state ?? null,
        $school->pincode ?? null,
    ])->filter()->implode(', ');

    $classNames = collect($classes)
        ->map(function ($item) {
            return trim((string) (
                is_object($item) ? ($item->class_name ?? '') : $item
            ));
        })
        ->filter()
        ->unique(fn ($name) => strtolower($name))
        ->values();

    $reportRows = collect($report);

    $groupedReport = $reportRows->groupBy(function ($row) {
        return trim((string) ($row->caste ?? 'Not Specified'))
            ?: 'Not Specified';
    });

    $grandClassTotals = [];

    foreach ($classNames as $className) {
        $grandClassTotals[$className] = [
            'boys' => 0,
            'girls' => 0,
            'total' => 0,
        ];
    }

    $calculatedGrandBoys = 0;
    $calculatedGrandGirls = 0;
    $calculatedGrandTotal = 0;
@endphp

<style>
    .caste-report-page {
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        padding: 24px;
    }

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

    .filter-card,
    .report-card {
        background: #fff;
        border: 1px solid #e8ebf2;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(26, 39, 65, .05);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .filter-card-header,
    .report-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .filter-title,
    .report-card-header h5 {
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

    .report-card-header {
        padding: 17px 20px;
    }

    .report-count {
        font-size: 13px;
        color: #697386;
    }

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
        color: #fff;
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
        color: #1769d1 !important;
        font-weight: 600;
    }

    .gender-girls {
        color: #b13c78 !important;
        font-weight: 600;
    }

    .gender-total {
        color: #202a3c !important;
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
        color: #fff !important;
        font-weight: 700;
        font-size: 13px;
    }

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

    /* School profile is only displayed on the printed report. */
    .print-school-profile {
        display: none;
    }

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

    ```css
@media print {
    @page {
        size: A4 landscape;
        margin: 8mm;
    }

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .caste-report-page {
        width: 100% !important;
        min-height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    /* Hide navigation and filter controls */
    .sidebar,
    #sidebar,
    .main-header,
    .app-header,
    .topbar,
    .navbar,
    nav,
    .filter-card,
    .report-actions,
    .report-icon,
    .no-print {
        display: none !important;
    }

    /* School profile */
    .print-school-profile {
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100% !important;
        margin-bottom: 8px !important;
        padding-bottom: 8px !important;
        border-bottom: 1px solid #333;
        color: #000 !important;
    }

    .print-school-profile img {
        width: 65px !important;
        height: 65px !important;
        object-fit: contain;
    }

    .print-school-details h2 {
        margin: 0 0 3px !important;
        font-size: 17px !important;
        color: #000 !important;
    }

    .print-school-details p {
        margin: 2px 0 !important;
        font-size: 9px !important;
        color: #000 !important;
    }

    .print-school-details h3 {
        margin: 5px 0 0 !important;
        font-size: 12px !important;
        color: #000 !important;
    }

    /* Report table */
    .report-card {
        width: 100% !important;
        margin: 0 !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }

    .report-card-header {
        padding: 5px 0 !important;
        border-bottom: 1px solid #333 !important;
    }

    .report-card-header h5,
    .report-count {
        font-size: 9px !important;
        color: #000 !important;
    }

    .report-table-wrapper {
        width: 100% !important;
        overflow: visible !important;
    }

    .report-table {
        width: 100% !important;
        min-width: 0 !important;
        table-layout: auto !important;
        border-collapse: collapse !important;
    }

    .report-table thead {
        display: table-header-group;
    }

    .report-table thead th {
        padding: 4px 2px !important;
        font-size: 7px !important;
        background: #e5e5e5 !important;
        color: #000 !important;
        border: 1px solid #555 !important;
        white-space: normal !important;
    }

    .report-table tbody td {
        padding: 3px 2px !important;
        font-size: 7px !important;
        color: #000 !important;
        border: 1px solid #777 !important;
        overflow-wrap: anywhere;
    }

    .category-badge {
        min-width: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        color: #000 !important;
    }

    .grand-total-row td,
    .category-total-row td,
    .total-cell {
        background: #eee !important;
        color: #000 !important;
    }

    tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }
}

</style>

<div class="caste-report-page">

    {{-- SCHOOL PROFILE: PRINT / PDF ONLY --}}
    <div class="print-school-profile">
        <img
            src="{{ $schoolLogo }}"
            alt="School Logo"
            onerror="this.style.display='none'"
        >

        <div class="print-school-details">
            <h2>{{ $school->school_name ?? 'School Name' }}</h2>

            @if($schoolAddress)
                <p>{{ $schoolAddress }}</p>
            @endif

            <p>
                @if(!empty($school->phone))
                    Phone: {{ $school->phone }}
                @endif

                @if(!empty($school->phone) && !empty($school->email))
                    &nbsp; | &nbsp;
                @endif

                @if(!empty($school->email))
                    Email: {{ $school->email }}
                @endif
            </p>

            @if(!empty($school->udise_code))
                <p>UDISE Code: {{ $school->udise_code }}</p>
            @endif

            <h3>CASTE / CATEGORY STUDENT REPORT</h3>
        </div>
    </div>

    {{-- PAGE HEADER --}}
    <div class="report-header">
        <div class="report-title-wrap">
            <div class="report-icon">
                <i class="bi bi-bar-chart-fill"></i>
            </div>

            <div>
                <h3 class="report-title">Caste Report</h3>
                <p class="report-subtitle">
                    Class-wise boys and girls summary
                </p>
            </div>
        </div>
<div class="report-actions no-print">
    <a href="{{ route('admin.caste-report.print', request()->query()) }}"
       class="btn btn-primary">
        <i class="bi bi-printer me-1"></i>
        Print Report
    </a>

    <a href="{{ route('admin.caste-report.pdf-download', request()->query()) }}"
       class="btn btn-danger">
        <i class="bi bi-file-earmark-pdf me-1"></i>
        Download PDF
    </a>
</div>
    </div>

    {{-- FILTER CARD --}}
    <div class="filter-card no-print">
        <div class="filter-card-header">
            <h5 class="filter-title">
                <i class="bi bi-funnel-fill"></i>
                Report Filters
            </h5>

            @if($hasFilters)
                <span class="badge bg-primary">Filters Applied</span>
            @endif
        </div>

        <div class="filter-content">
            <form method="GET" action="{{ url()->current() }}">
                <div class="row g-3">

                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="filter-label">Academic Year</label>

                        <select
                            name="academic_year"
                            class="form-select filter-select"
                        >
                            <option value="">All Academic Years</option>

                            @foreach($academicYears as $year)
                                <option
                                    value="{{ $year }}"
                                    @selected(request('academic_year') == $year)
                                >
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">Class</label>

                        <select name="class" class="form-select filter-select">
                            <option value="">All Classes</option>

                            @foreach($classes as $class)
                                @php
                                    $classValue = is_object($class)
                                        ? ($class->class_name ?? '')
                                        : $class;
                                @endphp

                                <option
                                    value="{{ $classValue }}"
                                    @selected(request('class') == $classValue)
                                >
                                    {{ $classValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">Section</label>

                        <select
                            name="section"
                            class="form-select filter-select"
                        >
                            <option value="">All Sections</option>

                            @foreach($sections as $section)
                                <option
                                    value="{{ $section }}"
                                    @selected(request('section') == $section)
                                >
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="filter-label">Caste</label>

                        <select name="caste" class="form-select filter-select">
                            <option value="">All Castes</option>

                            @foreach($castes as $casteOption)
                                <option
                                    value="{{ $casteOption }}"
                                    @selected(request('caste') == $casteOption)
                                >
                                    {{ $casteOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">&nbsp;</label>

                        <div class="filter-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i>
                                Apply
                            </button>

                            <a
                                href="{{ url()->current() }}"
                                class="btn btn-outline-secondary"
                                title="Reset filters"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>

                @if($hasFilters)
                    <div class="active-filters">
                        <span class="active-filter-label">Active:</span>

                        @if(request('academic_year'))
                            <span class="filter-badge">
                                Academic Year: {{ request('academic_year') }}
                            </span>
                        @endif

                        @if(request('class'))
                            <span class="filter-badge">
                                Class: {{ request('class') }}
                            </span>
                        @endif

                        @if(request('section'))
                            <span class="filter-badge">
                                Section: {{ request('section') }}
                            </span>
                        @endif

                        @if(request('caste'))
                            <span class="filter-badge">
                                Caste: {{ request('caste') }}
                            </span>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>

    {{-- REPORT CARD --}}
    <div class="report-card">
        <div class="report-card-header">
            <h5>
                <i class="bi bi-table me-2 text-primary"></i>
                Caste / Category Summary
            </h5>

            <span class="report-count">
                {{ (int) $grandTotal }} students
            </span>
        </div>

        @if($reportRows->isNotEmpty() && $classNames->isNotEmpty())
            <div class="report-table-wrapper">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Caste</th>
                            <th>Gender</th>

                            @foreach($classNames as $className)
                                <th>{{ $className }}</th>
                            @endforeach

                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($groupedReport as $casteName => $casteRows)
                            @php
                                $classData = [];
                                $casteBoys = 0;
                                $casteGirls = 0;
                                $casteTotal = 0;

                                foreach ($classNames as $className) {
                                    $matchingRows = $casteRows->filter(
                                        function ($item) use ($className) {
                                            return strcasecmp(
                                                trim((string) ($item->class ?? '')),
                                                trim((string) $className)
                                            ) === 0;
                                        }
                                    );

                                    $boys = (int) $matchingRows->sum('boys');
                                    $girls = (int) $matchingRows->sum('girls');
                                    $total = $boys + $girls;

                                    $classData[$className] = [
                                        'boys' => $boys,
                                        'girls' => $girls,
                                        'total' => $total,
                                    ];

                                    $casteBoys += $boys;
                                    $casteGirls += $girls;
                                    $casteTotal += $total;

                                    $grandClassTotals[$className]['boys'] += $boys;
                                    $grandClassTotals[$className]['girls'] += $girls;
                                    $grandClassTotals[$className]['total'] += $total;
                                }

                                $calculatedGrandBoys += $casteBoys;
                                $calculatedGrandGirls += $casteGirls;
                                $calculatedGrandTotal += $casteTotal;
                            @endphp

                            {{-- BOYS --}}
                            <tr>
                                <td rowspan="3">
                                    <span class="category-badge">
                                        {{ $casteName }}
                                    </span>
                                </td>

                                <td class="gender-boys">Boys</td>

                                @foreach($classNames as $className)
                                    <td class="number-cell">
                                        {{ $classData[$className]['boys'] }}
                                    </td>
                                @endforeach

                                <td class="total-cell">{{ $casteBoys }}</td>
                            </tr>

                            {{-- GIRLS --}}
                            <tr>
                                <td class="gender-girls">Girls</td>

                                @foreach($classNames as $className)
                                    <td class="number-cell">
                                        {{ $classData[$className]['girls'] }}
                                    </td>
                                @endforeach

                                <td class="total-cell">{{ $casteGirls }}</td>
                            </tr>

                            {{-- CASTE TOTAL --}}
                            <tr class="category-total-row">
                                <td class="gender-total">Total</td>

                                @foreach($classNames as $className)
                                    <td class="number-cell">
                                        {{ $classData[$className]['total'] }}
                                    </td>
                                @endforeach

                                <td class="total-cell">{{ $casteTotal }}</td>
                            </tr>
                        @endforeach

                        {{-- GRAND TOTAL --}}
                        <tr class="grand-total-row">
                            <td>Grand Total</td>
                            <td>Boys + Girls</td>

                            @foreach($classNames as $className)
                                <td>
                                    {{ $grandClassTotals[$className]['total'] }}
                                </td>
                            @endforeach

                            <td>{{ $calculatedGrandTotal }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-report">
                <div class="empty-report-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <h6>No Report Data Found</h6>

                <p>
                    @if($classNames->isEmpty())
                        No classes were found in Class Management. Please add classes first.
                    @else
                        No active students match the selected filters.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@endsection
