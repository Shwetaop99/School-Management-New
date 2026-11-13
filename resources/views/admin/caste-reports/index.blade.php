@extends('layouts.app')

@section('title', 'Caste / Category Report | Admin')

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
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — CASTE / CATEGORY REPORT
       LOCKED VERSION
    ========================================================= */

    .caste-report-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .caste-report-page *,
    .caste-report-page *::before,
    .caste-report-page *::after {
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
    .btn-scheme-pdf,
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

    .btn-scheme-print {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-print:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    .btn-scheme-pdf {
        color: #8a3a34;
        background: #fff1ed;
        border-color: #e8b8b0;
    }

    .btn-scheme-pdf:hover {
        background: #fde4de;
        border-color: #dba39a;
    }

    .btn-scheme-apply {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 2px 8px rgba(52, 119, 90, .15);
    }

    .btn-scheme-apply:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
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
    .report-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
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
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
    }

    .filter-title,
    .report-card-header h5 {
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-title i,
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

    .filter-content {
        padding: 18px 20px;
    }

    .filter-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .filter-select {
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

    .filter-select:hover {
        border-color: #c5d1c2;
    }

    .filter-select:focus {
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

    .active-filters {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e9e5d9;
    }

    .active-filter-label {
        font-size: 10px;
        color: #7a857d;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-right: 2px;
    }

    .filter-badge {
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }

    /* ---------- REPORT CARD ---------- */
    .report-count {
        font-size: 11px;
        color: #7a857d;
        font-weight: 600;
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
        background: #34775a;
        color: #fffefa;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        padding: 12px 8px;
        border: 1px solid #2d6a50;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .report-table thead th:first-child {
        text-align: left;
        padding-left: 14px;
    }

    .report-table tbody td {
        padding: 9px 8px;
        border: 1px solid #e5e1d3;
        text-align: center;
        vertical-align: middle;
        font-size: 12px;
        color: #263b35;
        background: #fffefa;
    }

    .report-table tbody td:first-child {
        text-align: left;
        padding-left: 14px;
    }

    .report-table tbody tr:hover td {
        background: #faf9f3;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        min-width: 65px;
        justify-content: center;
        padding: 5px 10px;
        background: #eaf3e9;
        color: #245a43;
        border: 1px solid #c5dcc8;
        border-radius: 7px;
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

    .gender-total {
        color: #263b35 !important;
        font-weight: 750;
    }

    .number-cell {
        font-weight: 600;
    }

    .total-cell {
        background: #f7f5ec !important;
        font-weight: 700 !important;
        color: #263b35 !important;
    }

    .category-total-row td {
        background: #faf8f0 !important;
        font-weight: 700;
    }

    .grand-total-row td {
        background: #34775a !important;
        color: #fffefa !important;
        font-weight: 700;
        font-size: 12px;
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
        margin-bottom: 16px;
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

    /* School profile is only displayed on the printed report. */
    .print-school-profile {
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

    
@media print {
    @page {
        size: A4 landscape;
        margin: 8mm;
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

        .filter-content {
            padding: 16px;
        }

        .btn-scheme-print,
        .btn-scheme-pdf {
            flex: 1;
        }
    }

    @media (max-width: 480px) {
        .report-title {
            font-size: 17px;
        }

        .report-subtitle {
            font-size: 10px;
        }

        .report-actions {
            flex-direction: column;
        }

        .btn-scheme-print,
        .btn-scheme-pdf {
            width: 100%;
        }
    }

    /* ---------- PRINT STYLES ---------- */
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

        .sidebar,
        #sidebar,
        .main-header,
        .app-header,
        .topbar,
        .navbar,
        nav,
        .filter-card,
        .report-actions,
        .report-title-icon,
        .report-header-card,
        .no-print {
            display: none !important;
        }

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
            background: transparent !important;
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
            background: #fff !important;
            overflow-wrap: anywhere;
        }

        .category-badge {
            min-width: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            color: #000 !important;
        }

        .grand-total-row td,
        .category-total-row td,
        .total-cell {
            background: #eee !important;
            color: #000 !important;
            border-color: #777 !important;
        }

        tr {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .caste-report-page *,
        .caste-report-page *::before,
        .caste-report-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="caste-report-page">

    {{-- SCHOOL PROFILE: PRINT / PDF ONLY --}}
    <div class="print-school-profile">
        <img src="{{ $schoolLogo }}"
             alt="School Logo"
             onerror="this.style.display='none'">

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

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="report-header-card no-print">
        <div class="report-header-content">
            <div>
                <div class="report-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Caste Report</span>
                </div>

                <div class="report-title-row">
                    <div class="report-title-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>
                    <div>
                        <h1 class="report-title">Caste / Category Report</h1>
                        <p class="report-subtitle">Class-wise boys and girls summary</p>
                    </div>
                </div>
            </div>

            <div class="report-actions">
                <a href="{{ route('admin.caste-report.print', request()->query()) }}"
                   class="btn-scheme-print">
                    <i class="bi bi-printer"></i>
                    Print Report
                </a>

                <a href="{{ route('admin.caste-report.pdf-download', request()->query()) }}"
                   class="btn-scheme-pdf">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Download PDF
                </a>
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

    {{-- ===================== FILTER CARD ===================== --}}
    <div class="filter-card no-print">
        <div class="filter-card-header">
            <h5 class="filter-title">
                <i class="bi bi-funnel-fill"></i>
                Report Filters
            </h5>

            @if($hasFilters)
                <span class="filter-badge-applied">Filters Applied</span>
            @endif
        </div>

        <div class="filter-content">
            <form method="GET" action="{{ url()->current() }}">
                <div class="row g-3">
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="filter-label">Academic Year</label>
                        <select name="academic_year" class="filter-select">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" @selected(request('academic_year') == $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">Class</label>
                        <select name="class" class="filter-select">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                @php
                                    $classValue = is_object($class)
                                        ? ($class->class_name ?? '')
                                        : $class;
                                @endphp
                                <option value="{{ $classValue }}" @selected(request('class') == $classValue)>
                                    {{ $classValue }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">Section</label>
                        <select name="section" class="filter-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section }}" @selected(request('section') == $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <label class="filter-label">Caste</label>
                        <select name="caste" class="filter-select">
                            <option value="">All Castes</option>
                            @foreach($castes as $casteOption)
                                <option value="{{ $casteOption }}" @selected(request('caste') == $casteOption)>
                                    {{ $casteOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <label class="filter-label">&nbsp;</label>
                        <div class="filter-buttons">
                            <button type="submit" class="btn-scheme-apply">
                                <i class="bi bi-search"></i>
                                Apply
                            </button>
                            <a href="{{ url()->current() }}"
                               class="btn-scheme-reset"
                               title="Reset filters">
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

    {{-- ===================== REPORT CARD ===================== --}}
    <div class="report-card">
        <div class="report-card-header">
            <h5>
                <i class="bi bi-table"></i>
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