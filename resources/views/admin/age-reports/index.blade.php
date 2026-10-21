@extends('layouts.app')

@section('title', 'Age Report | Admin')

@section('content')

@php
    $selectedAcademicYear = $selectedAcademicYear ?? request('academic_year', '');
    $selectedClass = $selectedClass ?? request('class', '');
    $selectedSection = $selectedSection ?? request('section', '');
    $selectedGender = $selectedGender ?? request('gender', '');
    $ageAsOn = $ageAsOn ?? request('age_as_on', now()->format('Y-m-d'));

    $report = collect($report ?? []);
    $classes = collect($classes ?? []);
    $sections = collect($sections ?? []);
    $academicYears = collect($academicYears ?? []);
    $ageGroups = collect($ageGroups ?? []);

    $normalizeClass = function ($value) {
        return strtolower(trim(preg_replace('/^class\s*/i', '', (string) $value)));
    };

    $tableClasses = $selectedClass !== ''
        ? $classes->filter(function ($class) use ($normalizeClass, $selectedClass) {
            return $normalizeClass($class) === $normalizeClass($selectedClass);
        })->values()
        : $classes;

    $reportLookup = [];

    foreach ($report as $row) {
        $ageKey = trim((string) $row->age_group);
        $classKey = $normalizeClass($row->class);
        $reportLookup[$ageKey][$classKey] = $row;
    }

    $grandClassTotals = [];

    foreach ($tableClasses as $class) {
        $grandClassTotals[$class] = [
            'boys' => 0,
            'girls' => 0,
            'total' => 0,
        ];
    }

    $displayTotal = 0;
    $displayBoys = 0;
    $displayGirls = 0;

    foreach ($report as $row) {
        if ($selectedClass !== '' && $normalizeClass($row->class) !== $normalizeClass($selectedClass)) {
            continue;
        }

        $displayTotal += (int) ($row->total ?? 0);
        $displayBoys += (int) ($row->boys ?? 0);
        $displayGirls += (int) ($row->girls ?? 0);
    }

    $schoolSetting = $schoolSetting ?? null;

    $getSchoolValue = function (array $keys, $default = '') use ($schoolSetting) {
        foreach ($keys as $key) {
            $value = is_array($schoolSetting)
                ? ($schoolSetting[$key] ?? null)
                : data_get($schoolSetting, $key);

            if (is_string($value) || is_numeric($value)) {
                if (trim((string) $value) !== '') {
                    return trim((string) $value);
                }
            }
        }

        return $default;
    };

    $schoolName = $getSchoolValue(
        ['school_name', 'name', 'institution_name'],
        config('app.name', 'School Management System')
    );

    $schoolAddress = $getSchoolValue(['address', 'school_address']);
    $schoolPhone = $getSchoolValue(['phone', 'phone_number', 'mobile', 'contact_number']);

    $hasFilters = $selectedAcademicYear || $selectedClass || $selectedSection || $selectedGender || request('age_as_on');
@endphp

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — AGE REPORT
       LOCKED VERSION
    ========================================================= */

    .age-report-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .age-report-page *,
    .age-report-page *::before,
    .age-report-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .report-header-card {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .report-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .report-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .report-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .report-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .report-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .report-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .report-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .report-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .report-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    .report-actions {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
        flex-wrap: wrap;
    }

    /* ---------- BUTTONS ---------- */
    .btn-scheme-print,
    .btn-scheme-apply,
    .btn-scheme-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-print,
    .btn-scheme-apply {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-print:hover,
    .btn-scheme-apply:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-reset {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-reset:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    /* ---------- FILTER CARD ---------- */
    .filter-card,
    .report-card,
    .summary-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .filter-card {
        margin-bottom: 18px;
        overflow: hidden;
    }

    .filter-card-header,
    .report-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e9e5d9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
    }

    .filter-card-header h5,
    .report-card-header h5 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-card-header h5 i,
    .report-card-header h5 i {
        color: #34775a;
    }

    .filter-badge-applied {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .filter-card-body {
        padding: 18px 20px;
    }

    .filter-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .filter-control {
        display: block;
        width: 100%;
        height: 40px;
        padding: 0 12px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        outline: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .filter-control:hover {
        border-color: #c5d1c2;
    }

    .filter-control:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .filter-buttons {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    .filter-buttons .btn-scheme-apply,
    .filter-buttons .btn-scheme-reset {
        min-height: 40px;
    }

    /* ---------- SUMMARY CARDS ---------- */
    .summary-card {
        padding: 18px 20px;
        height: 100%;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(38, 59, 53, .08);
    }

    .summary-label {
        color: #7a857d;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 24px;
        line-height: 1.2;
        font-weight: 750;
        color: #263b35;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        color: #34775a;
        border-radius: 11px;
        font-size: 18px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    /* ---------- REPORT CARD ---------- */
    .report-card {
        overflow: hidden;
        margin-bottom: 18px;
    }

    .report-meta {
        font-size: 11px;
        color: #7a857d;
        font-weight: 600;
    }

    .report-meta strong {
        color: #263b35;
    }

    .report-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .report-table {
        width: 100%;
        min-width: 850px;
        margin: 0;
        border-collapse: collapse;
    }

    .report-table thead th {
        background: #34775a;
        color: #fffefa;
        border: 1px solid #2d6a50;
        padding: 12px 8px;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .report-table tbody td {
        padding: 9px 8px;
        border: 1px solid #e5e1d3;
        font-size: 12px;
        text-align: center;
        vertical-align: middle;
        color: #263b35;
        background: #fffefa;
    }

    .report-table tbody tr:hover td {
        background: #faf9f3;
    }

    .report-table .age-group-cell {
        text-align: left;
        font-weight: 700;
        padding-left: 14px;
    }

    .age-group-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 7px;
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
        font-weight: 700;
        font-size: 11px;
    }

    .gender-boys {
        color: #1769d1 !important;
        font-weight: 650;
    }

    .gender-girls {
        color: #b13c78 !important;
        font-weight: 650;
    }

    .gender-total,
    .total-cell {
        font-weight: 700;
    }

    .total-cell {
        background: #f7f5ec !important;
        color: #263b35 !important;
    }

    .age-total-row td {
        background: #faf8f0 !important;
        font-weight: 700;
    }

    .grand-total-row td {
        background: #34775a !important;
        color: #fffefa !important;
        font-weight: 700;
        border-color: #2d6a50 !important;
    }

    /* ---------- EMPTY STATE ---------- */
    .empty-report {
        padding: 56px 24px;
        text-align: center;
    }

    .empty-report-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        color: #7a857d;
        background: #f0eee6;
        border: 1px solid #e0ddcf;
        border-radius: 14px;
        font-size: 26px;
    }

    .empty-report h6 {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 15px;
        font-weight: 750;
    }

    .empty-report p {
        margin: 0;
        color: #7a857d;
        font-size: 12px;
        line-height: 1.5;
    }

    /* PRINT-ONLY REPORT HEADER */
    .print-report-header {
        display: none;
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 991px) {
        .report-header-content {
            flex-direction: column;
        }

        .report-actions {
            width: 100%;
        }

        .filter-buttons {
            margin-top: 8px;
        }
    }

    @media (max-width: 767.98px) {
        .report-header-card {
            padding: 18px 16px;
        }

        .report-title {
            font-size: 19px;
        }

        .report-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .filter-card-body {
            padding: 16px;
        }

        .btn-scheme-print {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .report-title {
            font-size: 17px;
        }

        .report-subtitle {
            font-size: 10px;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT ONLY THE REPORT
    |--------------------------------------------------------------------------
    */
    @media print {
        @page {
            size: A4 landscape;
            margin: 7mm;
        }

        html,
        body {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #age-report-print-area,
        #age-report-print-area * {
            visibility: visible !important;
        }

        #age-report-print-area {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
            color: #000 !important;
            border: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        .no-print {
            display: none !important;
        }

        .print-report-header {
            display: block !important;
            text-align: center;
            margin-bottom: 12px;
            color: #000 !important;
        }

        .print-report-header h2 {
            margin: 0 0 4px;
            font-size: 19px;
            font-weight: 700;
        }

        .print-report-header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .report-header-card {
            display: none !important;
        }

        .summary-cards-print {
            display: flex !important;
            gap: 10px;
            margin: 10px 0;
        }

        .summary-cards-print > div {
            flex: 1;
        }

        .summary-card {
            padding: 7px !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: #fff !important;
        }

        .summary-label {
            color: #333 !important;
            font-size: 9px !important;
        }

        .summary-value {
            color: #000 !important;
            font-size: 14px !important;
        }

        .summary-icon {
            display: none !important;
        }

        .report-card {
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }

        .report-card-header {
            padding: 5px 0 !important;
            border-bottom: 1px solid #444 !important;
            background: transparent !important;
        }

        .report-card-header h5,
        .report-meta {
            color: #000 !important;
            font-size: 10px !important;
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
            background: #e8e8e8 !important;
            color: #000 !important;
            border: 1px solid #555 !important;
            font-size: 8px !important;
            padding: 4px 3px !important;
            white-space: normal !important;
        }

        .report-table tbody td {
            color: #000 !important;
            border: 1px solid #777 !important;
            font-size: 8px !important;
            padding: 4px 3px !important;
            background: #fff !important;
        }

        .age-group-badge {
            background: transparent !important;
            border: none !important;
            color: #000 !important;
            padding: 0 !important;
        }

        .total-cell,
        .age-total-row td,
        .grand-total-row td {
            background: #eee !important;
            color: #000 !important;
            border-color: #777 !important;
        }

        .report-table tr {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .age-report-page *,
        .age-report-page *::before,
        .age-report-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="age-report-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="report-header-card no-print">
        <div class="report-header-content">
            <div>
                <div class="report-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Age Report</span>
                </div>

                <div class="report-title-row">
                    <div class="report-title-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div>
                        <h1 class="report-title">Age Report</h1>
                        <p class="report-subtitle">Age-group-wise boys and girls summary</p>
                    </div>
                </div>
            </div>

            <div class="report-actions">
                <button type="button" class="btn-scheme-print" onclick="printAgeReport()">
                    <i class="bi bi-printer-fill"></i>
                    Print Report
                </button>
            </div>
        </div>
    </div>

    {{-- ===================== FILTER CARD ===================== --}}
    <div class="filter-card no-print">
        <div class="filter-card-header">
            <h5>
                <i class="bi bi-funnel-fill"></i>
                Report Filters
            </h5>

            @if($hasFilters)
                <span class="filter-badge-applied">Filters Applied</span>
            @endif
        </div>

        <div class="filter-card-body">
            <form method="GET" action="{{ route('admin.age-report.index') }}">
                <div class="row g-3">
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Academic Year</label>
                        <select name="academic_year" class="filter-control">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" @selected((string) $selectedAcademicYear === (string) $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Class</label>
                        <select name="class" class="filter-control">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" @selected((string) $selectedClass === (string) $class)>
                                    {{ $class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Section</label>
                        <select name="section" class="filter-control">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" @selected((string) $selectedSection === (string) $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Gender</label>
                        <select name="gender" class="filter-control">
                            <option value="">All Genders</option>
                            <option value="Male" @selected(strtolower((string) $selectedGender) === 'male')>Male</option>
                            <option value="Female" @selected(strtolower((string) $selectedGender) === 'female')>Female</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Age As On</label>
                        <input type="date"
                               name="age_as_on"
                               class="filter-control"
                               value="{{ $ageAsOn }}"
                               max="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">&nbsp;</label>
                        <div class="filter-buttons">
                            <button type="submit" class="btn-scheme-apply">
                                <i class="bi bi-search"></i>
                                Apply
                            </button>
                            <a href="{{ route('admin.age-report.index') }}"
                               class="btn-scheme-reset"
                               title="Reset Filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Everything inside this element is the only content printed. --}}
    <div id="age-report-print-area">

        <div class="print-report-header">
            <h2>{{ $schoolName }}</h2>

            @if($schoolAddress)
                <p>{{ $schoolAddress }}</p>
            @endif

            @if($schoolPhone)
                <p>Phone: {{ $schoolPhone }}</p>
            @endif

            <h3 style="font-size:14px; margin:10px 0 3px;">STUDENT AGE REPORT</h3>

            <p>
                Academic Year:
                {{ $selectedAcademicYear ?: 'All Academic Years' }}
                &nbsp; | &nbsp;
                Age As On: {{ \Carbon\Carbon::parse($ageAsOn)->format('d-m-Y') }}
            </p>

            @if($selectedClass)
                <p>Class: {{ $selectedClass }}</p>
            @endif

            @if($selectedSection)
                <p>Section: {{ $selectedSection }}</p>
            @endif

            @if($selectedGender)
                <p>Gender: {{ $selectedGender }}</p>
            @endif
        </div>

        {{-- Print summary cards --}}
        <div class="summary-cards-print row g-3 mb-4">
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Total Students</div>
                    <div class="summary-value">{{ $displayTotal }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Boys</div>
                    <div class="summary-value">{{ $displayBoys }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="summary-label">Girls</div>
                    <div class="summary-value">{{ $displayGirls }}</div>
                </div>
            </div>
        </div>

        {{-- Screen summary cards --}}
        <div class="row g-3 mb-4 no-print">
            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Total Students</div>
                            <div class="summary-value">{{ $displayTotal }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-people-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Boys</div>
                            <div class="summary-value">{{ $displayBoys }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-person-fill"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Girls</div>
                            <div class="summary-value">{{ $displayGirls }}</div>
                        </div>
                        <div class="summary-icon"><i class="bi bi-person-heart"></i></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- REPORT TABLE --}}
        <div class="report-card">
            <div class="report-card-header">
                <h5>
                    <i class="bi bi-table"></i>
                    Age Group Summary
                </h5>
                <span class="report-meta">
                    Age As On:
                    <strong>{{ \Carbon\Carbon::parse($ageAsOn)->format('d-m-Y') }}</strong>
                </span>
            </div>

            @if($report->isNotEmpty() && $tableClasses->isNotEmpty())

                <div class="report-table-wrapper">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Age Group</th>
                                <th>Gender</th>
                                @foreach($tableClasses as $class)
                                    <th>{{ $class }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ageGroups as $ageGroup)
                                @php
                                    $ageBoys = 0;
                                    $ageGirls = 0;
                                    $ageTotal = 0;
                                    $classData = [];

                                    foreach ($tableClasses as $class) {
                                        $classKey = $normalizeClass($class);
                                        $matchedRow = $reportLookup[$ageGroup][$classKey] ?? null;

                                        $boys = (int) ($matchedRow->boys ?? 0);
                                        $girls = (int) ($matchedRow->girls ?? 0);
                                        $total = (int) ($matchedRow->total ?? ($boys + $girls));

                                        $classData[$class] = [
                                            'boys' => $boys,
                                            'girls' => $girls,
                                            'total' => $total,
                                        ];

                                        $ageBoys += $boys;
                                        $ageGirls += $girls;
                                        $ageTotal += $total;

                                        $grandClassTotals[$class]['boys'] += $boys;
                                        $grandClassTotals[$class]['girls'] += $girls;
                                        $grandClassTotals[$class]['total'] += $total;
                                    }
                                @endphp

                                <tr>
                                    <td class="age-group-cell" rowspan="3">
                                        <span class="age-group-badge">{{ $ageGroup }}</span>
                                    </td>
                                    <td class="gender-boys">Boys</td>
                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['boys'] }}</td>
                                    @endforeach
                                    <td class="total-cell">{{ $ageBoys }}</td>
                                </tr>

                                <tr>
                                    <td class="gender-girls">Girls</td>
                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['girls'] }}</td>
                                    @endforeach
                                    <td class="total-cell">{{ $ageGirls }}</td>
                                </tr>

                                <tr class="age-total-row">
                                    <td class="gender-total">Total</td>
                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['total'] }}</td>
                                    @endforeach
                                    <td class="total-cell">{{ $ageTotal }}</td>
                                </tr>
                            @endforeach

                            <tr class="grand-total-row">
                                <td colspan="2">Grand Total</td>
                                @foreach($tableClasses as $class)
                                    <td>{{ $grandClassTotals[$class]['total'] }}</td>
                                @endforeach
                                <td>{{ $displayTotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            @else
                <div class="empty-report">
                    <div class="empty-report-icon">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h6>No Age Report Data Found</h6>
                    <p>No students match the selected filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function printAgeReport() {
    window.print();
}
</script>

@endsection