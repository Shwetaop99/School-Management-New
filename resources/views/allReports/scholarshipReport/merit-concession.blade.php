@extends('layouts.app')

@section('title', 'Merit Concession Report | Admin')
@section('page-title', 'Merit Concession Report')

@section('content')
<div class="merit-simple">
    <div class="container-fluid py-4">

        @php
            $recordsCount = method_exists($results, 'total') ? $results->total() : $results->count();
            $firstOffset = method_exists($results, 'firstItem') ? (($results->firstItem() ?? 1) - 1) : 0;

            $selSearch = trim((string) request('search'));
            $selYear = request('academic_year');
            $selClass = request('class');
            $selSection = request('section');
            $selMin = request('min_percentage');
            $selMax = request('max_percentage');

            $percentageLabel = 'All percentages';
            if ($selMin !== null && $selMin !== '' || $selMax !== null && $selMax !== '') {
                $percentageLabel = (($selMin !== null && $selMin !== '') ? $selMin . '%' : '0%')
                    . ' – ' .
                    (($selMax !== null && $selMax !== '') ? $selMax . '%' : '100%');
            }

            // Fetch school identity for the browser print view.
            $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();
            $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
            $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';
            $schoolLogo = $schoolSetting->logo_url
                ?? $schoolSetting->logo
                ?? $schoolSetting->school_logo
                ?? null;

            if ($schoolLogo && !filter_var($schoolLogo, FILTER_VALIDATE_URL)
                && !str_starts_with($schoolLogo, 'data:')
                && !str_starts_with($schoolLogo, '/')) {
                $schoolLogo = \Illuminate\Support\Facades\Storage::url($schoolLogo);
            }

            $schoolAddress = collect([
                $schoolSetting->address ?? null,
                $schoolSetting->city ?? null,
                $schoolSetting->district ?? null,
                $schoolSetting->state ?? null,
                $schoolSetting->pincode ?? null,
            ])->filter(fn ($value) => trim((string) $value) !== '')->implode(', ');
            $schoolPhone = trim((string) ($schoolSetting->phone ?? ''));
            $schoolEmail = trim((string) ($schoolSetting->email ?? ''));
        @endphp

        {{-- PRINT ONLY SCHOOL BRANDING AND REPORT HEADER --}}
        <div class="print-only">
            <div class="print-header">
                <div class="print-brand">
                    @if (!empty($schoolLogo))
                        <img class="print-school-logo" src="{{ $schoolLogo }}" alt="School Logo">
                    @endif
                    <div class="print-school-identity">
                        <div class="print-school-name">{{ $schoolName }}</div>
                        @if ($schoolAddress !== '')
                            <div class="print-school-contact">{{ $schoolAddress }}</div>
                        @endif
                        @if ($schoolPhone !== '' || $schoolEmail !== '')
                            <div class="print-school-contact">
                                @if ($schoolPhone !== '') Phone: {{ $schoolPhone }} @endif
                                @if ($schoolPhone !== '' && $schoolEmail !== '') &nbsp; | &nbsp; @endif
                                @if ($schoolEmail !== '') Email: {{ $schoolEmail }} @endif
                            </div>
                        @endif
                    </div>
                </div>
                <div class="print-report-title">
                    <div class="print-brand-name">Merit Concession Report</div>
                    <div class="print-brand-sub">Academic Performance &amp; Student Support</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Records:</span>
                    <span class="print-value">{{ number_format($recordsCount) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Academic Year:</span>
                    <span class="print-value">{{ $selYear ?: 'All years' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Class:</span>
                    <span class="print-value">{{ $selClass ?: 'All classes' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Section:</span>
                    <span class="print-value">{{ $selSection ?: 'All sections' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Percentage:</span>
                    <span class="print-value">{{ $percentageLabel }}</span>
                </div>
                @if ($selSearch !== '')
                    <div class="print-meta-row">
                        <span class="print-label">Search:</span>
                        <span class="print-value">{{ $selSearch }}</span>
                    </div>
                @endif
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="merit-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.scholarship') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Scholarship Reports
            </a>
            <div></div>
        </div>

        {{-- HEADER (simple, full pearl) --}}
        <section class="hero-section hero-simple">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="hero-text">
                    <h2>Merit Concession Report</h2>
                    <p>Student-wise annual examination performance, concession eligibility and recorded concession percentages.</p>
                </div>
            </div>
            <div class="hero-actions">
                <span class="hero-count">
                    <strong>{{ number_format($recordsCount) }}</strong> matching records
                </span>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printMeritReport()">
                        <i class="fas fa-print"></i> Print / Save as PDF
                    </button>
                </div>
            </div>
        </section>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.reports.scholarship.merit-concession') }}" class="filters">
            <div class="filter-item filter-search">
                <label for="mr-search">Search Student</label>
                <input id="mr-search" type="text" name="search" value="{{ request('search') }}" placeholder="Name, admission no. or roll no.">
            </div>

            <div class="filter-item">
                <label for="mr-year">Academic Year</label>
                <select id="mr-year" name="academic_year">
                    <option value="">All academic years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" @selected((string) request('academic_year') === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="mr-class">Class</label>
                <select id="mr-class" name="class">
                    <option value="">All classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}" @selected((string) request('class') === (string) $class)>{{ $class }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="mr-section">Section</label>
                <select id="mr-section" name="section">
                    <option value="">All sections</option>
                    @foreach($sections as $section)
                        <option value="{{ $section }}" @selected((string) request('section') === (string) $section)>{{ $section }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item filter-small">
                <label for="mr-min">Min %</label>
                <input id="mr-min" type="number" name="min_percentage" min="0" max="100" step="0.01" value="{{ request('min_percentage') }}" placeholder="0–100">
            </div>

            <div class="filter-item filter-small">
                <label for="mr-max">Max %</label>
                <input id="mr-max" type="number" name="max_percentage" min="0" max="100" step="0.01" value="{{ request('max_percentage') }}" placeholder="0–100">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.reports.scholarship.merit-concession') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Admission No.</th>
                        <th>Class / Section</th>
                        <th>Examination</th>
                        <th>Academic Year</th>
                        <th>Obtained</th>
                        <th>Total</th>
                        <th>Percentage</th>
                        <th>Concession %</th>
                        <th>Eligibility</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        @php
                            $obtainedMarks = (float) ($result->obtained_marks ?? 0);
                            $totalMarks = (float) ($result->total_marks ?? 0);
                            $percentage = (float) ($result->percentage ?? ($totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0));
                            $isEligible = $percentage >= 60;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + $firstOffset }}</td>
                            <td>
                                <div class="emp-name">{{ trim(($result->first_name ?? '') . ' ' . ($result->last_name ?? '')) ?: 'Student' }}</div>
                                <div class="emp-id">Roll No: {{ $result->roll_number ?: '—' }}</div>
                            </td>
                            <td>{{ $result->admission_student_id ?: '—' }}</td>
                            <td>
                                {{ $result->class_name ?: '—' }}
                                @if(!empty($result->section))
                                    / {{ $result->section }}
                                @endif
                            </td>
                            <td>{{ $result->exam_name ?: ($result->exam_type ?: 'Annual Examination') }}</td>
                            <td>{{ $result->academic_year ?: '—' }}</td>
                            <td>{{ number_format($obtainedMarks, 2) }}</td>
                            <td>{{ number_format($totalMarks, 2) }}</td>
                            <td class="strong">{{ number_format($percentage, 2) }}%</td>
                            <td>{{ number_format((float) ($result->scholarship_percentage ?? 0), 2) }}%</td>
                            <td>
                                @if($isEligible)
                                    <span class="status paid">Eligible</span>
                                @else
                                    <span class="status unpaid">Not Eligible</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="empty">
                                No merit concession records found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-bar">
            <span>
                @if(method_exists($results, 'total'))
                    Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} records
                @else
                    {{ $results->count() }} records
                @endif
            </span>
            @if(method_exists($results, 'links'))
                <div>{{ $results->withQueryString()->links() }}</div>
            @endif
        </div>

        {{-- NOTE --}}
        <div class="report-note">
            <span class="report-note-mark"><i class="fas fa-info"></i></span>
            <div>
                <strong>Eligibility note:</strong>
                Eligibility is shown using the 60% threshold used by this report. The concession percentage displayed is the percentage calculated by the existing report controller. Confirm the school’s approved concession policy before using these figures for financial decisions.
            </div>
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated merit concession report. For official use only.
        </div>

    </div>
</div>

<style>
.merit-simple {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    --net: #1a6b3c;
    background: var(--bg);
    color: var(--text);
    min-height: calc(100vh - 70px);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.merit-simple *,
.merit-simple *::before,
.merit-simple *::after {
    box-sizing: border-box;
}

.merit-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.merit-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.merit-simple .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border: 1px solid var(--border);
    border-radius: 6px;
    background: #fff;
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
}

.merit-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.merit-simple .btn-primary,
.merit-simple .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    border: 1px solid transparent;
}

.merit-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.merit-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}

.merit-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.merit-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HEADER (simple, full pearl) ========== */
.merit-simple .hero-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    padding: 24px 28px;
    margin-bottom: 24px;
    background: #fbf8f1;
    border: 1px solid #e5d9c4;
    border-radius: 10px;
}

.merit-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
}

.merit-simple .hero-icon {
    width: 54px;
    height: 54px;
    border-radius: 12px;
    background: #f3ead8;
    border: 1px solid #e5d9c0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5c4a32;
    font-size: 22px;
    flex-shrink: 0;
}

.merit-simple .hero-text h2 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.merit-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 520px;
}

.merit-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.merit-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}

.merit-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}

.merit-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.merit-simple .hero-actions .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.merit-simple .hero-actions .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

/* Filters */
.merit-simple .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: flex-end;
    padding: 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 20px;
}

.merit-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.merit-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.merit-simple .filter-item input,
.merit-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.merit-simple .filter-item.filter-search input {
    min-width: 240px;
}

.merit-simple .filter-item.filter-small input {
    min-width: 0;
    width: 110px;
}

.merit-simple .filter-item input:focus,
.merit-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.merit-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.merit-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.merit-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1100px;
}

.merit-simple th {
    background: #f0eee8;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 12px 14px;
    text-align: left;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}

.merit-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.merit-simple tbody tr:last-child td {
    border-bottom: none;
}

.merit-simple tbody tr:hover td {
    background: #faf9f5;
}

.merit-simple .emp-name {
    font-weight: 600;
}

.merit-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.merit-simple .strong {
    font-weight: 700;
}

.merit-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.merit-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.merit-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.merit-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.merit-simple .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--muted);
}

/* Note */
.merit-simple .report-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 20px;
    padding: 14px 16px;
    background: #fdfbf7;
    border: 1px solid #e5d9c0;
    border-radius: 8px;
    color: var(--muted);
    font-size: 12.5px;
    line-height: 1.6;
}

.merit-simple .report-note strong {
    color: var(--text);
}

.merit-simple .report-note-mark {
    width: 24px;
    height: 24px;
    min-width: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3ead8;
    color: #5c4a32;
    font-size: 11px;
}

/* Print only (hidden on screen) */
.merit-simple .print-only,
.merit-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .merit-simple .hero-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .merit-simple .hero-actions {
        align-items: flex-start;
    }
    .merit-simple .hero-buttons {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .merit-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .merit-simple .filter-item input,
    .merit-simple .filter-item select,
    .merit-simple .filter-item.filter-search input,
    .merit-simple .filter-item.filter-small input {
        width: 100%;
        min-width: 0;
    }
    .merit-simple .hero-text h2 {
        font-size: 20px;
    }
}

/* ================= PRINT — Professional Layout ================= */
@media print {
    @page {
        size: A4 landscape;
        margin: 10mm 12mm 12mm 12mm;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body * {
        visibility: hidden;
    }

    .merit-simple,
    .merit-simple * {
        visibility: visible;
    }

    .merit-simple {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: #fff !important;
        color: #1a1a1a;
        font-family: "Inter", "Segoe UI", Arial, Helvetica, sans-serif;
        font-size: 10.5px;
        line-height: 1.35;
    }

    /* Hide non-print elements */
    .merit-simple .top-bar,
    .merit-simple .hero-section,
    .merit-simple .filters,
    .merit-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .merit-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .merit-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .merit-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .merit-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .merit-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .merit-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .merit-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .merit-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .merit-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .merit-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .merit-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .merit-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .merit-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .merit-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .merit-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .merit-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .merit-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .merit-simple th {
        background: #2c3e36 !important;
        color: #fff !important;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 7px 8px;
        border: 1px solid #1e2a25;
        text-align: left;
        white-space: nowrap;
    }

    .merit-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .merit-simple tr {
        break-inside: avoid;
    }

    .merit-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .merit-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .merit-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .merit-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }

    .merit-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .merit-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .merit-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .merit-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    /* Note + footer */
    .merit-simple .report-note {
        margin-top: 10px;
        padding: 7px 10px;
        background: #f7f6f2 !important;
        border: 1px solid #ccc;
        border-radius: 0;
        font-size: 8.5px;
        color: #444;
        break-inside: avoid;
    }

    .merit-simple .report-note-mark {
        display: none !important;
    }

    .merit-simple .print-footer {
        display: block !important;
        margin-top: 10px;
        font-size: 8.5px;
        color: #777;
        text-align: center;
        border-top: 1px solid #ddd;
        padding-top: 6px;
    }
}
</style>

<script>
(function () {
    function setMeritPrintDate() {
        const el = document.getElementById('merit-print-date');

        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printMeritReport = function () {
        setMeritPrintDate();

        const logo = document.querySelector('.merit-simple .print-school-logo');

        if (logo && typeof logo.decode === 'function') {
            logo.decode().catch(function () {}).finally(function () {
                window.print();
            });
            return;
        }

        window.print();
    };
})();
</script>
@endsection