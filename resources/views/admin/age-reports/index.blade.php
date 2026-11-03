
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

    // Show only the selected class when a class filter is applied.
    $tableClasses = $selectedClass !== ''
        ? $classes->filter(function ($class) use ($normalizeClass, $selectedClass) {
            return $normalizeClass($class) === $normalizeClass($selectedClass);
        })->values()
        : $classes;

    $reportLookup = [];

    foreach ($report as $row) {
        $ageKey = trim((string) ($row->age_group ?? ''));
        $classKey = $normalizeClass($row->class ?? '');
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
        if (
            $selectedClass !== '' &&
            $normalizeClass($row->class ?? '') !== $normalizeClass($selectedClass)
        ) {
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

    $hasFilters = $selectedAcademicYear
        || $selectedClass
        || $selectedSection
        || $selectedGender
        || request('age_as_on');
@endphp

<style>
    /* ==========================================
       AGE REPORT PAGE
    ========================================== */

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

    /* ==========================================
       COMPACT HERO HEADER
    ========================================== */

    .age-report-page .marks-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 32%;
        gap: 16px;
        align-items: stretch;
        min-height: 220px;
        padding: 18px 8px 18px 30px;
        margin-bottom: 22px;
        overflow: hidden;
        background: #fffefa;
        border: 1px solid #e8dfce;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(40, 55, 45, .04);
    }

    .age-report-page .marks-hero::before,
    .age-report-page .marks-hero::after {
        content: "";
        position: absolute;
        left: 20px;
        width: 1px;
        height: 30px;
        background: #f0e5d2;
    }

    .age-report-page .marks-hero::before { top: 0; }
    .age-report-page .marks-hero::after { bottom: 0; }

    .age-report-page .marks-hero-left {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        min-width: 0;
        padding: 15px 15px 15px 28px;
        border-left: 3px solid #e2b39b;
    }

    .age-report-page .marks-hero-icon {
        display: flex;
        flex: 0 0 70px;
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        color: #234437;
        background: #f6f3e9;
        border: 1px solid #e7ddca;
        border-radius: 18px;
        font-size: 29px;
    }

    .age-report-page .marks-hero-content {
        min-width: 0;
    }

    .age-report-page .marks-hero-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
        margin-bottom: 12px;
        color: #78827e;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .age-report-page .marks-hero-breadcrumb a {
        color: #34775a;
        text-decoration: none;
    }

    .age-report-page .marks-hero-breadcrumb a:hover {
        text-decoration: underline;
    }

    .age-report-page .marks-hero-breadcrumb i {
        color: #a6a69c;
        font-size: 11px;
    }

    .age-report-page .marks-hero-title {
        margin: 0;
        color: #203c32;
        font-family: Georgia, "Times New Roman", serif;
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -.5px;
    }

    .age-report-page .marks-hero-description {
        max-width: 550px;
        margin: 12px 0;
        color: #718078;
        font-size: 12px;
        line-height: 1.7;
    }

    .age-report-page .marks-hero-trust {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #286044;
        font-size: 11px;
        font-weight: 750;
    }

    .age-report-page .marks-hero-trust i {
        color: #438a73;
        font-size: 15px;
    }

    /* RIGHT GREEN PANEL */

    .age-report-page .marks-hero-right {
        position: relative;
        isolation: isolate;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        padding: 18px 15px;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(145deg, #304f42, #193a30);
        border-radius: 13px;
        text-align: center;
    }

    .age-report-page .marks-hero-right::before,
    .age-report-page .marks-hero-right::after {
        position: absolute;
        z-index: -1;
        content: "";
        border: 1px solid rgba(255, 255, 255, .10);
        border-radius: 50%;
        pointer-events: none;
    }

    .age-report-page .marks-hero-right::before {
        top: -155px;
        right: -45px;
        width: 230px;
        height: 230px;
        box-shadow:
            0 0 0 17px rgba(255,255,255,.025),
            0 0 0 35px rgba(255,255,255,.025);
    }

    .age-report-page .marks-hero-right::after {
        bottom: -165px;
        left: -65px;
        width: 190px;
        height: 190px;
    }

    .age-report-page .marks-hero-right-icon {
        display: grid;
        place-items: center;
        width: 43px;
        height: 43px;
        margin-bottom: 10px;
        color: #f3d18e;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.23);
        border-radius: 13px;
        font-size: 19px;
    }

    .age-report-page .marks-hero-tagline {
        margin: 0 0 8px;
        color: #f6d39a;
        font-family: Georgia, "Times New Roman", serif;
        font-size: 19px;
        font-style: italic;
        font-weight: 800;
    }

    .age-report-page .marks-hero-right-description {
        max-width: 300px;
        margin: 0 auto 13px;
        color: #f1f4f1;
        font-size: 11px;
        line-height: 1.6;
    }

    .age-report-page .marks-hero-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .age-report-page .marks-hero-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 0 15px;
        color: #fff;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 9px;
        font-size: 11px;
        font-weight: 750;
        text-decoration: none;
        transition: .2s ease;
        white-space: nowrap;
    }

    .age-report-page .marks-hero-action:hover {
        color: #fff;
        background: rgba(255,255,255,.16);
        transform: translateY(-2px);
    }

    .age-report-page .marks-hero-action-primary {
        color: #233e33;
        background: #f3ead5;
        border-color: #f3ead5;
    }

    .age-report-page .marks-hero-action-primary:hover {
        color: #233e33;
        background: #fff4dc;
    }

    /* ==========================================
       BUTTONS
    ========================================== */

    .age-report-page .btn-scheme-print,
    .age-report-page .btn-scheme-apply,
    .age-report-page .btn-scheme-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 0 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        transition: all .18s ease;
        white-space: nowrap;
    }

    .age-report-page .btn-scheme-print,
    .age-report-page .btn-scheme-apply {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .age-report-page .btn-scheme-print:hover,
    .age-report-page .btn-scheme-apply:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        transform: translateY(-1px);
    }

    .age-report-page .btn-scheme-reset {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .age-report-page .btn-scheme-reset:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    /* ==========================================
       FILTER AND REPORT CARDS
    ========================================== */

    .age-report-page .filter-card,
    .age-report-page .report-card,
    .age-report-page .summary-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .age-report-page .filter-card {
        margin-bottom: 18px;
        overflow: hidden;
    }

    .age-report-page .filter-card-header,
    .age-report-page .report-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        padding: 16px 20px;
        background: linear-gradient(180deg, #fffefa 0%, #faf8f0 100%);
        border-bottom: 1px solid #e9e5d9;
    }

    .age-report-page .filter-card-header h5,
    .age-report-page .report-card-header h5 {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #263b35;
        font-size: 13px;
        font-weight: 750;
    }

    .age-report-page .filter-card-header h5 i,
    .age-report-page .report-card-header h5 i {
        color: #34775a;
    }

    .age-report-page .filter-badge-applied {
        display: inline-flex;
        align-items: center;
        padding: 5px 11px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .age-report-page .filter-card-body {
        padding: 18px 20px;
    }

    .age-report-page .filter-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .age-report-page .filter-control {
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

    .age-report-page .filter-control:hover {
        border-color: #c5d1c2;
    }

    .age-report-page .filter-control:focus {
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .age-report-page .filter-buttons {
        display: flex;
        align-items: end;
        gap: 8px;
        height: 100%;
    }

    .age-report-page .filter-buttons .btn-scheme-apply,
    .age-report-page .filter-buttons .btn-scheme-reset {
        min-height: 40px;
    }

    /* ==========================================
       SUMMARY CARDS
    ========================================== */

    .age-report-page .summary-card {
        height: 100%;
        padding: 18px 20px;
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .age-report-page .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(38, 59, 53, .08);
    }

    .age-report-page .summary-label {
        margin-bottom: 6px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .age-report-page .summary-value {
        color: #263b35;
        font-size: 24px;
        font-weight: 750;
        line-height: 1.2;
    }

    .age-report-page .summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 11px;
        font-size: 18px;
    }

    .age-report-page .summary-cards-print {
        display: none;
    }

    /* ==========================================
       REPORT TABLE
    ========================================== */

    .age-report-page .report-card {
        margin-bottom: 18px;
        overflow: hidden;
    }

    .age-report-page .report-meta {
        color: #7a857d;
        font-size: 11px;
        font-weight: 600;
    }

    .age-report-page .report-meta strong {
        color: #263b35;
    }

    .age-report-page .report-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .age-report-page .report-table {
        width: 100%;
        min-width: 850px;
        margin: 0;
        border-collapse: collapse;
    }

    .age-report-page .report-table thead th {
        padding: 12px 8px;
        color: #fffefa;
        background: #34775a;
        border: 1px solid #2d6a50;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: .3px;
        white-space: nowrap;
    }

    .age-report-page .report-table tbody td {
        padding: 9px 8px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        font-size: 12px;
        text-align: center;
        vertical-align: middle;
    }

    .age-report-page .report-table tbody tr:hover td {
        background: #faf9f3;
    }

    .age-report-page .report-table .age-group-cell {
        padding-left: 14px;
        font-weight: 700;
        text-align: left;
    }

    .age-report-page .age-group-badge {
        display: inline-block;
        padding: 5px 10px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .age-report-page .gender-boys {
        color: #1769d1 !important;
        font-weight: 650;
    }

    .age-report-page .gender-girls {
        color: #b13c78 !important;
        font-weight: 650;
    }

    .age-report-page .gender-total,
    .age-report-page .total-cell {
        font-weight: 700;
    }

    .age-report-page .total-cell {
        color: #263b35 !important;
        background: #f7f5ec !important;
    }

    .age-report-page .age-total-row td {
        background: #faf8f0 !important;
        font-weight: 700;
    }

    .age-report-page .grand-total-row td {
        color: #fffefa !important;
        background: #34775a !important;
        border-color: #2d6a50 !important;
        font-weight: 700;
    }

    /* ==========================================
       EMPTY STATE
    ========================================== */

    .age-report-page .empty-report {
        padding: 56px 24px;
        text-align: center;
    }

    .age-report-page .empty-report-icon {
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

    .age-report-page .empty-report h6 {
        margin: 0 0 6px;
        color: #263b35;
        font-size: 15px;
        font-weight: 750;
    }

    .age-report-page .empty-report p {
        margin: 0;
        color: #7a857d;
        font-size: 12px;
        line-height: 1.5;
    }

    .age-report-page .print-report-header {
        display: none;
    }

    /* ==========================================
       RESPONSIVE
    ========================================== */

    @media (max-width: 850px) {
        .age-report-page .marks-hero {
            grid-template-columns: 1fr;
            padding: 18px;
        }

        .age-report-page .marks-hero-left {
            padding: 15px 10px 15px 20px;
        }

        .age-report-page .marks-hero-right {
            min-height: 165px;
        }
    }

    @media (max-width: 767.98px) {
        .age-report-page .filter-card-body {
            padding: 16px;
        }

        .age-report-page .filter-buttons {
            margin-top: 8px;
        }
    }

    @media (max-width: 600px) {
        .age-report-page .marks-hero {
            padding: 12px;
            border-radius: 14px;
        }

        .age-report-page .marks-hero-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 8px 15px 15px;
        }

        .age-report-page .marks-hero-icon {
            flex-basis: 55px;
            width: 55px;
            height: 55px;
            border-radius: 14px;
            font-size: 23px;
        }

        .age-report-page .marks-hero-title {
            font-size: 27px;
        }

        .age-report-page .marks-hero-description {
            margin: 9px 0;
            font-size: 11px;
        }

        .age-report-page .marks-hero-right {
            padding: 18px 12px;
        }

        .age-report-page .marks-hero-actions {
            width: 100%;
        }

        .age-report-page .marks-hero-action {
            flex: 1;
        }

        .age-report-page .filter-buttons {
            width: 100%;
        }

        .age-report-page .filter-buttons .btn-scheme-apply {
            flex: 1;
        }

        .age-report-page .summary-value {
            font-size: 21px;
        }
    }

    /* ==========================================
       PRINT ONLY THE REPORT AREA
    ========================================== */

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
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            color: #000 !important;
            background: #fff !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        .no-print {
            display: none !important;
        }

        .age-report-page .print-report-header {
            display: block !important;
            margin-bottom: 12px;
            color: #000 !important;
            text-align: center;
        }

        .age-report-page .print-report-header h2 {
            margin: 0 0 4px;
            font-size: 19px;
            font-weight: 700;
        }

        .age-report-page .print-report-header p {
            margin: 3px 0;
            font-size: 10px;
        }

        .age-report-page .summary-cards-print {
            display: flex !important;
            gap: 10px;
            margin: 10px 0;
        }

        .age-report-page .summary-cards-print > div {
            flex: 1;
        }

        .age-report-page .summary-card {
            padding: 7px !important;
            background: #fff !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .age-report-page .summary-label {
            color: #333 !important;
            font-size: 9px !important;
        }

        .age-report-page .summary-value {
            color: #000 !important;
            font-size: 14px !important;
        }

        .age-report-page .summary-icon {
            display: none !important;
        }

        .age-report-page .report-card {
            overflow: visible !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .age-report-page .report-card-header {
            padding: 5px 0 !important;
            background: transparent !important;
            border-bottom: 1px solid #444 !important;
        }

        .age-report-page .report-card-header h5,
        .age-report-page .report-meta {
            color: #000 !important;
            font-size: 10px !important;
        }

        .age-report-page .report-table-wrapper {
            width: 100% !important;
            overflow: visible !important;
        }

        .age-report-page .report-table {
            width: 100% !important;
            min-width: 0 !important;
            table-layout: auto !important;
            border-collapse: collapse !important;
        }

        .age-report-page .report-table thead {
            display: table-header-group;
        }

        .age-report-page .report-table thead th {
            padding: 4px 3px !important;
            color: #000 !important;
            background: #e8e8e8 !important;
            border: 1px solid #555 !important;
            font-size: 8px !important;
            white-space: normal !important;
        }

        .age-report-page .report-table tbody td {
            padding: 4px 3px !important;
            color: #000 !important;
            background: #fff !important;
            border: 1px solid #777 !important;
            font-size: 8px !important;
        }

        .age-report-page .age-group-badge {
            padding: 0 !important;
            color: #000 !important;
            background: transparent !important;
            border: none !important;
        }

        .age-report-page .total-cell,
        .age-report-page .age-total-row td,
        .age-report-page .grand-total-row td {
            color: #000 !important;
            background: #eee !important;
            border-color: #777 !important;
        }

        .age-report-page .report-table tr {
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

    {{-- ==========================================
         COMPACT HERO HEADER
    ========================================== --}}

    <div class="marks-hero no-print">

        <div class="marks-hero-left">

            <div class="marks-hero-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="marks-hero-content">

                <div class="marks-hero-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Reports</span>
                    <i class="bi bi-chevron-right"></i>
                    <span>Age Report</span>
                </div>

                <h2 class="marks-hero-title">Age Report</h2>

                <p class="marks-hero-description">
                    View age-group-wise student distribution with separate
                    boys, girls and total counts across classes.
                </p>

                <div class="marks-hero-trust">
                    <i class="bi bi-shield-check"></i>
                    Accurate student age statistics
                </div>

            </div>
        </div>

        <div class="marks-hero-right">

            <div class="marks-hero-right-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <h4 class="marks-hero-tagline">Every Age Tells a Story</h4>

            <p class="marks-hero-right-description">
                Analyse student age groups and prepare
                organized academic reports.
            </p>

            <div class="marks-hero-actions">

                <a href="{{ route('admin.student-documents.index') }}"
                   class="marks-hero-action">
                    <i class="bi bi-arrow-left"></i>
                    Back to Documents
                </a>

                <a href="{{ route('admin.age-report.print', request()->query()) }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="marks-hero-action marks-hero-action-primary">
                    <i class="bi bi-printer-fill"></i>
                    Print Report
                </a>

            </div>
        </div>

    </div>

    {{-- ==========================================
         FILTER CARD
    ========================================== --}}

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
                                <option value="{{ $year }}"
                                    @selected((string) $selectedAcademicYear === (string) $year)>
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
                                <option value="{{ $class }}"
                                    @selected((string) $selectedClass === (string) $class)>
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
                                <option value="{{ $section }}"
                                    @selected((string) $selectedSection === (string) $section)>
                                    {{ $section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label class="filter-label">Gender</label>

                        <select name="gender" class="filter-control">
                            <option value="">All Genders</option>

                            <option value="Male"
                                @selected(strtolower((string) $selectedGender) === 'male')>
                                Male
                            </option>

                            <option value="Female"
                                @selected(strtolower((string) $selectedGender) === 'female')>
                                Female
                            </option>
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
                               title="Reset Filters"
                               aria-label="Reset Filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- ==========================================
         PRINT AREA
    ========================================== --}}

    <div id="age-report-print-area">

        {{-- PRINT SCHOOL HEADER --}}

        <div class="print-report-header">

            <h2>{{ $schoolName }}</h2>

            @if($schoolAddress)
                <p>{{ $schoolAddress }}</p>
            @endif

            @if($schoolPhone)
                <p>Phone: {{ $schoolPhone }}</p>
            @endif

            <h3 style="font-size:14px; margin:10px 0 3px;">
                STUDENT AGE REPORT
            </h3>

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

        {{-- PRINT SUMMARY CARDS --}}

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

        {{-- SCREEN SUMMARY CARDS --}}

        <div class="row g-3 mb-4 no-print">

            <div class="col-xl-4 col-md-4">
                <div class="summary-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="summary-label">Total Students</div>
                            <div class="summary-value">{{ $displayTotal }}</div>
                        </div>

                        <div class="summary-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
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

                        <div class="summary-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
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

                        <div class="summary-icon">
                            <i class="bi bi-person-heart"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==========================================
             REPORT TABLE
        ========================================== --}}

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

                                {{-- BOYS ROW --}}

                                <tr>

                                    <td class="age-group-cell" rowspan="3">
                                        <span class="age-group-badge">
                                            {{ $ageGroup }}
                                        </span>
                                    </td>

                                    <td class="gender-boys">Boys</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['boys'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageBoys }}</td>

                                </tr>

                                {{-- GIRLS ROW --}}

                                <tr>

                                    <td class="gender-girls">Girls</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['girls'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageGirls }}</td>

                                </tr>

                                {{-- AGE GROUP TOTAL ROW --}}

                                <tr class="age-total-row">

                                    <td class="gender-total">Total</td>

                                    @foreach($tableClasses as $class)
                                        <td>{{ $classData[$class]['total'] }}</td>
                                    @endforeach

                                    <td class="total-cell">{{ $ageTotal }}</td>

                                </tr>

                            @endforeach

                            {{-- GRAND TOTAL --}}

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
