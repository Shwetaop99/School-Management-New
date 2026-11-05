@extends('layouts.app')

@section('title', 'Class Student Transport Report')

@section('content')
@php
    $schoolName = $school?->school_name ?: config('app.name');
    $schoolLogo = $school?->logo_url;

    $totalStudents = $students->count();
    $activeStudents = $students->filter(function ($student) {
        return $student->transportRecords->contains(fn ($r) => strtolower($r->transport_status ?? '') === 'active');
    })->count();
    $inactiveStudents = $totalStudents - $activeStudents;
@endphp

<div class="student-class-transport-report">
    <div class="container-fluid py-4">

        {{-- =========================================
             SCREEN ONLY CONTENT
        ========================================= --}}

        <div class="report-topbar screen-only">
            <div class="school-heading">
                @if(!empty($schoolLogo))
                    <img src="{{ $schoolLogo }}" alt="School Logo" class="school-logo">
                @endif

                <div>
                    <div class="school-name">
                        {{ $schoolName }}
                    </div>
                    <div class="report-subtitle">Student Transport Report</div>
                </div>
            </div>

            <div class="topbar-actions">
                <a href="{{ route('admin.reports.transport.students') }}" class="btn-report btn-light">
                    <i class="fas fa-arrow-left"></i> Back to Classes
                </a>
                <button type="button" onclick="window.print()" class="btn-report btn-dark">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <section class="class-hero screen-only">
            <div>
                <span class="eyebrow">STUDENT TRANSPORT DIRECTORY</span>
                <h1>{{ $className }}</h1>
                <p>Choose a section and academic year to view students who have applied for school transport.</p>
            </div>
            <div class="hero-icon">
                <i class="fas fa-bus"></i>
            </div>
        </section>

        <section class="filter-card screen-only">
            <div class="section-heading">
                <div>
                    <h2>Filter Students</h2>
                    <p>Select the division and academic year.</p>
                </div>
                <span class="class-chip">{{ $className }}</span>
            </div>

            <form method="GET" action="{{ route('admin.reports.transport.students.class') }}">
                <input type="hidden" name="class" value="{{ $classValue }}">

                <div class="filter-grid">
                    <div class="filter-field">
                        <label for="section">Section / Division</label>
                        <select name="section" id="section">
                            <option value="">All Sections</option>
                            @foreach($sections as $item)
                                <option value="{{ $item }}" @selected($section === $item)>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="academic_year">Academic Year</label>
                        <select name="academic_year" id="academic_year">
                            <option value="">All Academic Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" @selected($academicYear === $year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-report btn-primary">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>

                        <a href="{{ route('admin.reports.transport.students.class', ['class' => $classValue]) }}"
                           class="btn-report btn-light">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </section>

        @if($section !== '' || $academicYear !== '')
            <section class="result-summary screen-only">
                <div>
                    <span class="summary-label">Matching Transport Students</span>
                    <strong>{{ $students->count() }}</strong>
                </div>
                <div class="summary-context">
                    <span>{{ $className }}</span>
                    <span>{{ $section !== '' ? 'Section ' . $section : 'All Sections' }}</span>
                    <span>{{ $academicYear !== '' ? $academicYear : 'All Years' }}</span>
                </div>
            </section>

            <section class="student-table-card screen-only">
                <div class="table-heading">
                    <div>
                        <h2>Transport Applicants</h2>
                        <p>Students with registered transport records matching your filters.</p>
                    </div>
                </div>

                @if($students->isNotEmpty())
                    <div class="table-responsive">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Section</th>
                                    <th>Academic Year</th>
                                    <th>Transport Records</th>
                                    <th>Transport Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    @php
                                        $activeRecord = $student->transportRecords
                                            ->firstWhere('transport_status', 'active');
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="student-id">{{ $student->student_id ?? '—' }}</td>
                                        <td class="student-name">
                                            {{ $student->full_name }}
                                        </td>
                                        <td>{{ $student->section ?? '—' }}</td>
                                        <td>{{ $student->academic_year ?? '—' }}</td>
                                        <td>{{ $student->transportRecords->count() }}</td>
                                        <td>
                                            @if($activeRecord)
                                                <span class="status-pill active">Active</span>
                                            @else
                                                <span class="status-pill inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.reports.transport.students.show', $student->id) }}"
                                               class="view-link">
                                                View Profile <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <h3>No Transport Students Found</h3>
                        <p>No students match the selected section and academic year.</p>
                    </div>
                @endif
            </section>
        @else
            <section class="empty-prompt screen-only">
                <div class="prompt-icon"><i class="fas fa-sliders-h"></i></div>
                <h2>Select Your Filters</h2>
                <p>Choose a section, academic year, or both, then click Apply Filter to see the transport applicants.</p>
            </section>
        @endif


        {{-- =========================================
             DEDICATED PRINT PROFILE
             EXACT SAME DESIGN + STRUCTURE AS LOCKED VERSION
        ========================================= --}}

        <div class="print-profile">

            {{-- DYNAMIC SCHOOL HEADER --}}
            <div class="print-school-header">

                @if(!empty($schoolLogo))
                    <img src="{{ $schoolLogo }}"
                         class="print-school-logo"
                         alt="School Logo">
                @endif

                <div class="print-school-name">
                    {{ $schoolName }}
                </div>

                <div class="print-school-details">

                    {{ $school->address ?? '' }}

                    @if(!empty($school?->city))
                        , {{ $school->city }}
                    @endif

                    @if(!empty($school?->district))
                        , {{ $school->district }}
                    @endif

                    @if(!empty($school?->state))
                        , {{ $school->state }}
                    @endif

                    @if(!empty($school?->phone))
                        | Phone: {{ $school->phone }}
                    @endif

                    @if(!empty($school?->email))
                        | Email: {{ $school->email }}
                    @endif

                </div>

            </div>


            {{-- REPORT TITLE --}}
            <div class="print-report-title">
                CLASS STUDENT TRANSPORT REPORT
            </div>


            {{-- CLASS / FILTER HEADING --}}
            <div class="print-student-heading">

                {{ $className }}

                &nbsp; | &nbsp;

                {{ $section !== '' ? 'Section ' . $section : 'All Sections' }}

                &nbsp; | &nbsp;

                {{ $academicYear !== '' ? $academicYear : 'All Academic Years' }}

            </div>


            {{-- SUMMARY TABLE --}}
            <div class="print-section-title">
                Report Summary
            </div>

            <table class="print-summary-table">
                <tr>
                    <td>
                        <div class="print-summary-label">Total Students</div>
                        <div class="print-summary-value">{{ $totalStudents }}</div>
                    </td>
                    <td>
                        <div class="print-summary-label">Active Transport</div>
                        <div class="print-summary-value print-green">{{ $activeStudents }}</div>
                    </td>
                    <td>
                        <div class="print-summary-label">Inactive Transport</div>
                        <div class="print-summary-value print-red">{{ $inactiveStudents }}</div>
                    </td>
                    <td>
                        <div class="print-summary-label">Class</div>
                        <div class="print-summary-value" style="font-size:14px;">{{ $className }}</div>
                    </td>
                </tr>
            </table>


            {{-- STUDENT LIST --}}
            <div class="print-section-title">
                Transport Applicants
            </div>

            @if($students->isNotEmpty())

                <table class="print-attendance-table">
                    <thead>
                        <tr>
                            <th style="width:6%;">#</th>
                            <th style="width:14%;">Student ID</th>
                            <th style="width:28%;">Student Name</th>
                            <th style="width:12%;">Section</th>
                            <th style="width:14%;">Academic Year</th>
                            <th style="width:12%;">Records</th>
                            <th style="width:14%;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php
                                $isActive = $student->transportRecords
                                    ->contains(fn ($r) => strtolower($r->transport_status ?? '') === 'active');
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->student_id ?? '—' }}</td>
                                <td>{{ $student->full_name ?? '—' }}</td>
                                <td>{{ $student->section ?? '—' }}</td>
                                <td>{{ $student->academic_year ?? '—' }}</td>
                                <td>{{ $student->transportRecords->count() }}</td>
                                <td class="{{ $isActive ? 'print-status-present' : 'print-status-absent' }}">
                                    {{ $isActive ? 'Active' : 'Inactive' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else

                <table class="print-attendance-table">
                    <tr>
                        <td class="print-no-records">
                            No transport students found for the selected filters.
                        </td>
                    </tr>
                </table>

            @endif


            {{-- PRINT FOOTER --}}
            <div class="print-footer">
                Generated on {{ now()->format('d M Y, h:i A') }}
            </div>

        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
/* =========================================================
   SCREEN STYLES (your current design)
========================================================= */

.student-class-transport-report {
    --page: #f7f6f0;
    --paper: #fffefa;
    --ink: #263b35;
    --dark: #1d302b;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --sage: #527f70;
    --blue: #426b8a;
    --green: #43835d;
    --red: #b95b50;
    color: var(--text);
    background: var(--page);
    min-height: 100vh;
    font-family: 'Public Sans', 'Inter', sans-serif;
}

.student-class-transport-report .container-fluid {
    max-width: 1650px;
    margin: auto;
}

.student-class-transport-report .report-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    margin-bottom: 22px;
}

.student-class-transport-report .school-heading {
    display: flex;
    align-items: center;
    gap: 14px;
}

.student-class-transport-report .school-logo {
    width: 55px;
    height: 55px;
    object-fit: contain;
}

.student-class-transport-report .school-name {
    color: var(--ink);
    font-size: 19px;
    font-weight: 800;
}

.student-class-transport-report .report-subtitle {
    color: var(--muted);
    font-size: 12px;
    margin-top: 4px;
}

.student-class-transport-report .topbar-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.student-class-transport-report .btn-report {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 42px;
    padding: 10px 16px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid var(--line);
    cursor: pointer;
}

.student-class-transport-report .btn-light {
    color: var(--ink);
    background: var(--paper);
}

.student-class-transport-report .btn-dark {
    color: white;
    background: var(--dark);
    border-color: var(--dark);
}

.student-class-transport-report .btn-primary {
    color: white;
    background: var(--sage);
    border-color: var(--sage);
}

.student-class-transport-report .class-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 32px;
    margin-bottom: 22px;
    color: white;
    border-radius: 17px;
    background: linear-gradient(115deg, #344f43, #20352c);
}

.student-class-transport-report .eyebrow {
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2px;
    opacity: .75;
}

.student-class-transport-report .class-hero h1 {
    margin: 9px 0;
    font-family: Georgia, serif;
    font-size: 34px;
    font-weight: 700;
    color: white;
}

.student-class-transport-report .class-hero p {
    margin: 0;
    max-width: 650px;
    color: #e3e9e2;
    font-size: 13px;
}

.student-class-transport-report .hero-icon {
    width: 76px;
    height: 76px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    background: #ffffff1c;
    font-size: 32px;
}

.student-class-transport-report .filter-card,
.student-class-transport-report .student-table-card {
    padding: 24px;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 15px;
    margin-bottom: 22px;
}

.student-class-transport-report .section-heading,
.student-class-transport-report .table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 20px;
}

.student-class-transport-report h2 {
    color: var(--ink);
    font-size: 18px;
    font-weight: 800;
    margin: 0 0 5px;
}

.student-class-transport-report .section-heading p,
.student-class-transport-report .table-heading p {
    color: var(--muted);
    font-size: 12px;
    margin: 0;
}

.student-class-transport-report .class-chip {
    padding: 8px 13px;
    color: var(--sage);
    background: #edf3ee;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 800;
}

.student-class-transport-report .filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 18px;
    align-items: end;
}

.student-class-transport-report .filter-field label {
    display: block;
    margin-bottom: 8px;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
}

.student-class-transport-report .filter-field select {
    width: 100%;
    height: 45px;
    padding: 0 12px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #fffefa;
    color: var(--text);
    outline: none;
}

.student-class-transport-report .filter-field select:focus {
    border-color: var(--sage);
    box-shadow: 0 0 0 3px #527f701c;
}

.student-class-transport-report .filter-actions {
    display: flex;
    gap: 9px;
}

.student-class-transport-report .result-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    padding: 18px 22px;
    background: #f4f0e5;
    border: 1px solid var(--line);
    border-radius: 13px;
    margin-bottom: 20px;
}

.student-class-transport-report .summary-label {
    display: block;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
}

.student-class-transport-report .result-summary strong {
    display: block;
    color: var(--ink);
    font-size: 26px;
    margin-top: 3px;
}

.student-class-transport-report .summary-context {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.student-class-transport-report .summary-context span {
    padding: 7px 10px;
    border: 1px solid #e5dfd0;
    border-radius: 20px;
    background: #fffefa;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
}

.student-class-transport-report .table-responsive {
    width: 100%;
    overflow-x: auto;
}

.student-class-transport-report .student-table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

.student-class-transport-report .student-table th {
    padding: 13px 12px;
    background: #f5f3e9;
    color: var(--ink);
    font-size: 11px;
    font-weight: 800;
    text-align: left;
    border-bottom: 1px solid var(--line);
}

.student-class-transport-report .student-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line);
    color: var(--text);
    font-size: 12px;
}

.student-class-transport-report .student-table tbody tr:hover {
    background: #faf9f4;
}

.student-class-transport-report .student-id {
    color: var(--blue) !important;
    font-weight: 800;
}

.student-class-transport-report .student-name {
    color: var(--ink) !important;
    font-weight: 800;
}

.student-class-transport-report .status-pill {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
}

.student-class-transport-report .status-pill.active {
    background: #e8f3e9;
    color: var(--green);
}

.student-class-transport-report .status-pill.inactive {
    background: #f8eae7;
    color: var(--red);
}

.student-class-transport-report .view-link {
    color: var(--sage);
    font-weight: 800;
    text-decoration: none;
}

.student-class-transport-report .empty-state,
.student-class-transport-report .empty-prompt {
    text-align: center;
    padding: 48px 20px;
    background: var(--paper);
    border: 1px dashed #d8d3c6;
    border-radius: 14px;
    margin-bottom: 22px;
}

.student-class-transport-report .empty-state > i,
.student-class-transport-report .prompt-icon {
    color: var(--sage);
    font-size: 30px;
    margin-bottom: 14px;
}

.student-class-transport-report .empty-state h3,
.student-class-transport-report .empty-prompt h2 {
    color: var(--ink);
    font-size: 17px;
    font-weight: 800;
}

.student-class-transport-report .empty-state p,
.student-class-transport-report .empty-prompt p {
    color: var(--muted);
    font-size: 12px;
    margin: 7px 0 0;
}

/* Hide print block on screen */
.student-class-transport-report .print-profile {
    display: none;
}

@media(max-width: 900px) {
    .student-class-transport-report .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .student-class-transport-report .filter-actions {
        grid-column: 1 / -1;
    }
}

@media(max-width: 600px) {
    .student-class-transport-report .report-topbar,
    .student-class-transport-report .class-hero,
    .student-class-transport-report .section-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .student-class-transport-report .filter-grid {
        grid-template-columns: 1fr;
    }

    .student-class-transport-report .filter-actions {
        grid-column: auto;
        flex-wrap: wrap;
    }

    .student-class-transport-report .class-hero {
        padding: 24px;
    }

    .student-class-transport-report .class-hero h1 {
        font-size: 28px;
    }

    .student-class-transport-report .filter-card,
    .student-class-transport-report .student-table-card {
        padding: 17px;
    }
}

/* =========================================================
   A4 LANDSCAPE PRINT SETTINGS
   EXACT SAME AS LOCKED / STUDENT ATTENDANCE PROFILE
========================================================= */

@page {
    size: A4 landscape;
    margin: 25px 30px;
}

@media print {

    body * {
        visibility: hidden !important;
    }

    html,
    body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .student-class-transport-report .screen-only {
        display: none !important;
    }

    .student-class-transport-report .print-profile,
    .student-class-transport-report .print-profile * {
        visibility: visible !important;
    }

    .student-class-transport-report .print-profile {
        display: block !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        font-family: Arial, sans-serif !important;
        color: #26352e !important;
        font-size: 10px !important;
    }

    .student-class-transport-report {
        background: #fff !important;
        color: #26352e !important;
        padding: 0 !important;
        min-height: auto !important;
    }

    /* SCHOOL HEADER */

    .student-class-transport-report .print-school-header {
        text-align: center;
        border-bottom: 2px solid #2c3e36;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }

    .student-class-transport-report .print-school-logo {
        max-height: 65px;
        max-width: 90px;
        display: block;
        margin: 0 auto 5px;
        object-fit: contain;
    }

    .student-class-transport-report .print-school-name {
        font-size: 21px;
        font-weight: bold;
        color: #263b35;
    }

    .student-class-transport-report .print-school-details {
        color: #5c6b64;
        font-size: 9px;
        margin-top: 5px;
        line-height: 1.6;
    }

    /* REPORT TITLE */

    .student-class-transport-report .print-report-title {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        margin: 15px 0;
        color: #263b35;
    }

    .student-class-transport-report .print-student-heading {
        background: #f3ead8 !important;
        padding: 10px;
        border: 1px solid #d4cfc2;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    /* SECTION HEADINGS */

    .student-class-transport-report .print-section-title {
        font-size: 12px;
        font-weight: bold;
        color: #263b35;
        margin: 15px 0 8px;
        page-break-after: avoid;
    }

    /* TABLE (SAME STYLE AS ATTENDANCE TABLE) */

    .student-class-transport-report .print-attendance-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 12px;
    }

    .student-class-transport-report .print-attendance-table th {
        background: #2c3e36 !important;
        color: #fff !important;
        padding: 9px 7px;
        text-align: left;
        font-size: 9px;
        border: 1px solid #2c3e36;
    }

    .student-class-transport-report .print-attendance-table td {
        padding: 8px 7px;
        border: 1px solid #e5e1d8;
        font-size: 9px;
        color: #26352e;
        overflow-wrap: anywhere;
    }

    .student-class-transport-report .print-attendance-table tbody tr:nth-child(even) {
        background: #f8f7f3 !important;
    }

    .student-class-transport-report .print-status-present {
        color: #1a6b3c !important;
        font-weight: bold;
    }

    .student-class-transport-report .print-status-absent {
        color: #b3443b !important;
        font-weight: bold;
    }

    /* SUMMARY */

    .student-class-transport-report .print-summary-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin-top: 8px;
        margin-bottom: 16px;
    }

    .student-class-transport-report .print-summary-table td {
        width: 25%;
        padding: 12px 8px;
        border: 1px solid #d4cfc2;
        background: #f8f7f3 !important;
        text-align: center;
    }

    .student-class-transport-report .print-summary-label {
        color: #5c6b64;
        font-size: 8px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .student-class-transport-report .print-summary-value {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e36;
        margin-top: 5px;
    }

    .student-class-transport-report .print-green {
        color: #1a6b3c !important;
    }

    .student-class-transport-report .print-red {
        color: #b3443b !important;
    }

    /* FOOTER */

    .student-class-transport-report .print-footer {
        margin-top: 18px;
        border-top: 1px solid #d4cfc2;
        padding-top: 8px;
        color: #7b857b;
        font-size: 8px;
        text-align: right;
    }

    .student-class-transport-report .print-no-records {
        text-align: center;
        padding: 15px !important;
        color: #777 !important;
    }

    .student-class-transport-report .print-attendance-table thead {
        display: table-header-group;
    }

    .student-class-transport-report .print-attendance-table tr,
    .student-class-transport-report .print-summary-table tr {
        page-break-inside: avoid;
    }
}
</style>
@endpush