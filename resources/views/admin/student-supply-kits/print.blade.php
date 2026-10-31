
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Student Supply Kit Report' }}</title>

    <style>
        
* {
    box-sizing: border-box;
}

html {
    width: 100%;
}

body {
    margin: 0;
    padding: 20px;
    background: #eeeeee;
    color: #111111;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
}

/* =====================================================
   MAIN PAGE - DOUBLE BLACK BORDER
===================================================== */

.print-page {
    position: relative;
    isolation: isolate;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 25px;
    background: #ffffff;
    border: 4px double #111111;
}

.print-page::after {
    content: "";
    position: fixed;
    inset: 1mm;
    border: 4px double #111111;
    pointer-events: none;
    z-index: 99999;
}

/* =====================================================
   WATERMARK
===================================================== */

.print-watermark {
    position: absolute;
    z-index: 0;
    top: 50%;
    left: 50%;
    width: 420px;
    height: 420px;
    transform: translate(-50%, -50%);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    user-select: none;
    overflow: hidden;
}

.print-watermark img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    opacity: 0.08;
    filter: grayscale(100%);
}

.print-page > :not(.print-watermark) {
    position: relative;
    z-index: 1;
}

/* =====================================================
   SCHOOL HEADER
===================================================== */

.school-header {
    text-align: center;
    border-bottom: 2px solid #111111;
    padding-bottom: 15px;
    margin-bottom: 18px;
    background: transparent;
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
    filter: grayscale(100%);
}

.school-details {
    text-align: center;
    min-width: 0;
}

.school-name {
    margin: 0;
    font-size: 25px;
    font-weight: 700;
    color: #000000;
}

.school-address {
    margin-top: 5px;
    font-size: 13px;
    color: #333333;
}

.school-contact {
    margin-top: 4px;
    font-size: 12px;
    color: #555555;
    overflow-wrap: anywhere;
}

.report-title {
    margin: 15px 0 0;
    font-size: 19px;
    font-weight: 700;
    color: #000000;
    text-transform: uppercase;
}

/* =====================================================
   REPORT INFORMATION
===================================================== */

.report-info {
    border: 1px solid #999999;
    border-radius: 5px;
    padding: 12px 15px;
    margin-bottom: 18px;
    background: rgba(245, 245, 245, 0.65);
}

.report-info-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}

.info-item {
    min-width: 0;
}

.info-label {
    margin-bottom: 3px;
    font-size: 10px;
    color: #555555;
    text-transform: uppercase;
    font-weight: 700;
}

.info-value {
    font-size: 13px;
    font-weight: 600;
    color: #111111;
    overflow-wrap: anywhere;
}

/* =====================================================
   FILTERS
===================================================== */

.filter-summary {
    border: 1px solid #999999;
    border-radius: 5px;
    padding: 11px 14px;
    margin-bottom: 18px;
    background: rgba(255, 255, 255, 0.65);
}

.filter-heading {
    margin-bottom: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #111111;
}

.filter-list {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}

.filter-badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 4px;
    background: #eeeeee;
    color: #111111;
    font-size: 11px;
    font-weight: 600;
    border: 1px solid #bbbbbb;
}

/* =====================================================
   SUMMARY CARDS
===================================================== */

.summary-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 18px;
}

.summary-box {
    border: 1px solid #999999;
    border-radius: 5px;
    padding: 10px;
    text-align: center;
    background: rgba(250, 250, 250, 0.75);
}

.summary-number {
    font-size: 20px;
    font-weight: 700;
    line-height: 1.2;
    color: #000000;
}

.summary-label {
    margin-top: 3px;
    font-size: 10px;
    color: #444444;
    text-transform: uppercase;
    font-weight: 600;
}

.summary-issued .summary-number,
.summary-pending .summary-number,
.summary-cancelled .summary-number,
.summary-not-assigned .summary-number {
    color: #000000;
}

/* =====================================================
   TABLE
===================================================== */

.report-table-wrapper {
    width: 100%;
    overflow-x: auto;
    background: transparent;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    background: transparent;
}

.report-table th {
    background: #222222;
    color: #ffffff;
    border: 1px solid #000000;
    padding: 9px 7px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    text-align: left;
    vertical-align: middle;
    overflow-wrap: anywhere;
}

.report-table td {
    border: 1px solid #bbbbbb;
    padding: 8px 7px;
    font-size: 11px;
    vertical-align: middle;
    color: #222222;
    overflow-wrap: anywhere;
    background: rgba(255, 255, 255, 0.45);
}

.report-table tbody tr:nth-child(even) {
    background: rgba(235, 235, 235, 0.65);
}

.report-table .text-center {
    text-align: center;
}

.student-name {
    font-weight: 700;
    color: #000000;
}

.kit-name {
    font-weight: 600;
    color: #222222;
}

.scheme-name {
    margin-top: 2px;
    color: #555555;
    font-size: 9px;
}

/* =====================================================
   STATUS - MONOCHROME
===================================================== */

.status {
    display: inline-block;
    padding: 4px 7px;
    border-radius: 12px;
    font-size: 9px;
    font-weight: 700;
    white-space: nowrap;
    border: 1px solid #777777;
    color: #111111;
    background: #eeeeee;
}

.status-issued {
    color: #000000;
    background: #e5e5e5;
    border-color: #777777;
}

.status-pending {
    color: #222222;
    background: #f3f3f3;
    border-color: #999999;
}

.status-cancelled {
    color: #000000;
    background: #d9d9d9;
    border-color: #555555;
}

.status-not-assigned {
    color: #333333;
    background: #eeeeee;
    border-color: #aaaaaa;
}

/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {
    text-align: center;
    padding: 50px 20px;
    border: 1px solid #999999;
    border-radius: 5px;
    background: transparent;
}

.empty-title {
    margin-bottom: 5px;
    font-size: 17px;
    font-weight: 700;
    color: #111111;
}

.empty-text {
    color: #555555;
    font-size: 12px;
}

/* =====================================================
   FOOTER
===================================================== */

.report-footer {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px solid #777777;
    display: flex;
    justify-content: space-between;
    gap: 15px;
    font-size: 10px;
    color: #444444;
    background: transparent;
}

/* =====================================================
   ACTION BUTTONS
===================================================== */

.print-actions {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto 15px;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

.print-button,
.back-button {
    display: inline-block;
    border: 0;
    border-radius: 5px;
    padding: 9px 15px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
}

.print-button {
    background: #111111;
    color: #ffffff;
}

.back-button {
    background: #ffffff;
    color: #111111;
    border: 1px solid #777777;
}

/* =====================================================
   A4 PRINT SETTINGS
===================================================== */

@page {
    size: A4 portrait;
    margin: 7mm;
}

@media print {

    html,
    body {
        width: auto !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        color: #000000 !important;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8px;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .print-actions {
        display: none !important;
    }

    .print-page {
        position: relative !important;
        isolation: isolate !important;
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* DOUBLE BLACK BORDER ON PRINTED PAGES */

    .print-page::after {
        content: "";
        position: fixed !important;
        top: 1mm !important;
        right: 1mm !important;
        bottom: 1mm !important;
        left: 1mm !important;
        border: 4px double #111111 !important;
        pointer-events: none !important;
        z-index: 99999 !important;
    }

    /* WATERMARK */

    .print-watermark {
        display: flex !important;
        position: fixed !important;
        z-index: 0 !important;
        top: 50% !important;
        left: 50% !important;
        width: 125mm !important;
        height: 125mm !important;
        transform: translate(-50%, -50%) !important;
        align-items: center !important;
        justify-content: center !important;
        overflow: visible !important;
        pointer-events: none !important;
    }

    .print-watermark img {
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        max-width: none !important;
        max-height: none !important;
        object-fit: contain !important;
        opacity: 0.08 !important;
        filter: grayscale(100%) !important;
    }

    .print-page > :not(.print-watermark) {
        position: relative !important;
        z-index: 1 !important;
    }

    /* SCHOOL HEADER */

    .school-header {
        padding-bottom: 5px;
        margin-bottom: 7px;
        break-inside: avoid;
        page-break-inside: avoid;
        background: transparent !important;
        border-bottom: 2px solid #111111 !important;
    }

    .school-header-inner {
        flex-direction: row;
        gap: 9px;
    }

    .school-logo {
        width: 45px;
        height: 45px;
        filter: grayscale(100%);
    }

    .school-name {
        font-size: 16px;
        color: #000000 !important;
    }

    .school-address {
        margin-top: 3px;
        font-size: 8px;
        color: #333333 !important;
    }

    .school-contact {
        margin-top: 2px;
        font-size: 7px;
        color: #444444 !important;
    }

    .report-title {
        font-size: 12px;
        margin-top: 5px;
        color: #000000 !important;
    }

    /* REPORT INFORMATION */

    .report-info {
        padding: 5px 7px;
        margin-bottom: 6px;
        background: rgba(245, 245, 245, 0.35) !important;
        border: 1px solid #999999 !important;
        border-radius: 2px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .report-info-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 4px;
    }

    .info-label {
        font-size: 7px;
        color: #555555 !important;
    }

    .info-value {
        font-size: 8px;
        color: #000000 !important;
    }

    /* FILTERS */

    .filter-summary {
        padding: 5px 7px;
        margin-bottom: 6px;
        background: transparent !important;
        border: 1px solid #999999 !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .filter-heading {
        font-size: 8px;
        margin-bottom: 3px;
        color: #111111 !important;
    }

    .filter-list {
        gap: 4px;
    }

    .filter-badge {
        padding: 2px 4px;
        font-size: 7px;
        background: #eeeeee !important;
        color: #111111 !important;
        border: 1px solid #bbbbbb !important;
    }

    /* SUMMARY */

    .summary-grid {
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 4px;
        margin-bottom: 6px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .summary-box {
        padding: 4px 2px;
        border: 1px solid #999999 !important;
        border-radius: 2px;
        background: transparent !important;
    }

    .summary-number {
        font-size: 12px;
        color: #000000 !important;
    }

    .summary-label {
        font-size: 6px;
        color: #444444 !important;
    }

    /* TABLE */

    .report-table-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow: visible !important;
        background: transparent !important;
    }

    .report-table {
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        page-break-inside: auto;
        background: transparent !important;
    }

    .report-table thead {
        display: table-header-group;
    }

    .report-table tfoot {
        display: table-footer-group;
    }

    .report-table tr {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .report-table th {
        padding: 4px 2px;
        font-size: 6.5px;
        line-height: 1.15;
        background: #222222 !important;
        color: #ffffff !important;
        border: 1px solid #000000 !important;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .report-table td {
        padding: 3px 2px;
        font-size: 7px;
        line-height: 1.15;
        color: #111111 !important;
        border: 1px solid #aaaaaa !important;
        overflow-wrap: anywhere;
        word-break: normal;
        background-color: transparent !important;
    }

    .report-table tbody tr,
    .report-table tbody tr:nth-child(even) {
        background: transparent !important;
    }

    .student-name,
    .kit-name {
        font-size: 7px;
        color: #000000 !important;
    }

    .scheme-name {
        font-size: 6px;
        color: #555555 !important;
    }

    /* STATUS */

    .status {
        padding: 2px;
        font-size: 6px;
        white-space: normal;
        border-radius: 2px;
        background: #eeeeee !important;
        color: #111111 !important;
        border: 1px solid #777777 !important;
    }

    /* EMPTY STATE */

    .empty-state {
        padding: 15px;
        background: transparent !important;
        border: 1px solid #999999 !important;
        break-inside: avoid;
    }

    .empty-title {
        font-size: 12px;
        color: #111111 !important;
    }

    .empty-text {
        font-size: 9px;
        color: #555555 !important;
    }

    /* FOOTER */

    .report-footer {
        margin-top: 6px;
        padding-top: 4px;
        font-size: 7px;
        background: transparent !important;
        border-top: 1px solid #777777 !important;
        color: #444444 !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .report-page,
    .report-info,
    .filter-summary,
    .summary-box,
    .report-table,
    .report-table tbody,
    .report-table tr,
    .report-table td {
        background-color: transparent !important;
    }

    .print-page *,
    .report-table-wrapper {
        max-width: 100%;
    }

    a {
        color: #000000 !important;
        text-decoration: none !important;
    }
}

/* =====================================================
   SCREEN RESPONSIVENESS
===================================================== */

@media screen and (max-width: 900px) {

    body {
        padding: 10px;
    }

    .print-page {
        padding: 15px;
    }

    .report-info-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .summary-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .school-header-inner {
        flex-direction: column;
    }

    .print-watermark {
        width: 300px;
        height: 300px;
    }
}

@media screen and (max-width: 600px) {

    .report-info-grid {
        grid-template-columns: 1fr;
    }

    .summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .school-name {
        font-size: 20px;
    }

    .print-page {
        padding: 12px;
    }

    .report-table {
        min-width: 650px;
    }
}

    </style>
</head>

<body>

@php
    $school = $schoolSetting ?? \App\Models\SchoolSetting::first();

    $schoolName = $school?->school_name ?? 'School Name';

    $schoolAddress = implode(', ', array_filter([
        $school?->address,
        $school?->city,
        $school?->district,
        $school?->state,
        $school?->pincode,
    ]));

    $contactParts = [];

    if ($school?->phone) {
        $contactParts[] = 'Phone: ' . $school->phone;
    }

    if ($school?->email) {
        $contactParts[] = 'Email: ' . $school->email;
    }

    if ($school?->udise_code) {
        $contactParts[] = 'UDISE: ' . $school->udise_code;
    }

    if ($school?->school_code) {
        $contactParts[] = 'School Code: ' . $school->school_code;
    }

    $schoolLogo = null;

    if ($school?->logo) {
        $schoolLogo = preg_match('~^https?://~i', $school->logo)
            ? $school->logo
            : asset('storage/' . ltrim($school->logo, '/'));
    }

    $watermarkLogo = $schoolLogo ?: asset('images/gurukullogo.png');

    $displayAcademicYear = $academicYear ?? '—';
    $reportStudents = $students ?? collect();
    $reportSummary = $summary ?? [];
    $reportMode = $reportMode ?? 'all';
@endphp

{{-- ACTION BUTTONS --}}

<div class="print-actions">
    <button type="button" onclick="window.print()" class="print-button">
        Print Report
    </button>
</div>

{{-- REPORT PAGE --}}

<div class="print-page">

    {{-- FAINT BACKGROUND SCHOOL LOGO --}}

    <div class="print-watermark" aria-hidden="true">
        <img src="{{ $watermarkLogo }}" alt="">
    </div>

    {{-- SCHOOL HEADER --}}

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

                @if(count($contactParts))
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

    {{-- REPORT INFORMATION --}}

    <div class="report-info">

        <div class="report-info-grid">

            <div class="info-item">
                <div class="info-label">Academic Year</div>
                <div class="info-value">
                    {{ $displayAcademicYear }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Class</div>
                <div class="info-value">
                    {{ !empty($selectedClass) ? $selectedClass : 'All Classes' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Section</div>
                <div class="info-value">
                    {{ !empty($section) ? $section : 'All Sections' }}
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Generated On</div>
                <div class="info-value">
                    {{ now()->format('d-m-Y h:i A') }}
                </div>
            </div>

        </div>

    </div>

    {{-- APPLIED FILTERS --}}

    @if(!empty($search) || !empty($status) || !empty($schemeId) || !empty($kitTemplateId))

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
                        Status: {{ ucwords(str_replace('_', ' ', $status)) }}
                    </span>
                @endif

                @if(!empty($schemeId))
                    <span class="filter-badge">
                        Scheme: {{ $selectedScheme?->scheme_name ?? '—' }}
                    </span>
                @endif

                @if(!empty($kitTemplateId))
                    <span class="filter-badge">
                        Kit: {{ $selectedKitTemplate?->kit_name ?? '—' }}
                    </span>
                @endif

            </div>

        </div>

    @endif

    {{-- SUMMARY --}}

    <div class="summary-grid">

        <div class="summary-box">
            <div class="summary-number">
                {{ $reportSummary['total'] ?? 0 }}
            </div>
            <div class="summary-label">
                Total Students
            </div>
        </div>

        <div class="summary-box summary-issued">
            <div class="summary-number">
                {{ $reportSummary['issued'] ?? 0 }}
            </div>
            <div class="summary-label">
                Issued
            </div>
        </div>

        <div class="summary-box summary-pending">
            <div class="summary-number">
                {{ $reportSummary['pending'] ?? 0 }}
            </div>
            <div class="summary-label">
                Pending
            </div>
        </div>

        <div class="summary-box summary-cancelled">
            <div class="summary-number">
                {{ $reportSummary['cancelled'] ?? 0 }}
            </div>
            <div class="summary-label">
                Cancelled
            </div>
        </div>

        <div class="summary-box summary-not-assigned">
            <div class="summary-number">
                {{ $reportSummary['not_assigned'] ?? 0 }}
            </div>
            <div class="summary-label">
                Not Assigned
            </div>
        </div>

    </div>

    {{-- STUDENT DATA --}}

    @if($reportStudents->isNotEmpty())

        <div class="report-table-wrapper">

            <table class="report-table">

                <thead>
                    <tr>

                        <th style="width: 4%;" class="text-center">
                            #
                        </th>

                        @if($reportMode === 'all')
                            <th style="width: 10%;">
                                Class
                            </th>
                        @endif

                        <th style="width: 18%;">
                            Student
                        </th>

                        <th style="width: 10%;">
                            Student ID
                        </th>

                        <th style="width: 7%;" class="text-center">
                            Section
                        </th>

                        <th style="width: 10%;" class="text-center">
                            Academic Year
                        </th>

                        <th style="width: 20%;">
                            Kit
                        </th>

                        <th style="width: 10%;" class="text-center">
                            Distribution Date
                        </th>

                        <th style="width: 11%;" class="text-center">
                            Status
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @foreach($reportStudents as $student)

                        @php
                            $studentName = trim(implode(' ', array_filter([
                                $student->first_name ?? '',
                                $student->middle_name ?? '',
                                $student->last_name ?? '',
                            ])));

                            $kit = $student->supplyKitRecord ?? null;
                            $kitStatus = $kit?->status ?? 'not_assigned';
                        @endphp

                        <tr>

                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>

                            @if($reportMode === 'all')
                                <td>
                                    {{ $student->admission_class ?: '—' }}
                                </td>
                            @endif

                            <td>
                                <div class="student-name">
                                    {{ $studentName ?: '—' }}
                                </div>
                            </td>

                            <td>
                                {{ $student->student_id ?: '—' }}
                            </td>

                            <td class="text-center">
                                {{ $student->section ?: '—' }}
                            </td>

                            <td class="text-center">
                                {{ $student->academic_year ?: $displayAcademicYear }}
                            </td>

                            <td>

                                @if($kit)

                                    <div class="kit-name">
                                        {{ $kit->kitTemplate?->kit_name ?? '—' }}
                                    </div>

                                    @if($kit->kitTemplate?->scheme)
                                        <div class="scheme-name">
                                            {{ $kit->kitTemplate->scheme->scheme_name }}
                                        </div>
                                    @endif

                                @else

                                    Not Assigned

                                @endif

                            </td>

                            <td class="text-center">

                                @if($kit?->distribution_date)
                                    {{ \Illuminate\Support\Carbon::parse($kit->distribution_date)->format('d-m-Y') }}
                                @else
                                    —
                                @endif

                            </td>

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

    {{-- FOOTER --}}

    <div class="report-footer">

        <div>
            Student Supply Kit Management Report
        </div>

        <div>
            Total Records: {{ $reportStudents->count() }}
        </div>

    </div>

</div>

</body>
</html>
