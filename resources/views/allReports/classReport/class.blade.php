@extends('layouts.app')

@section('title', 'Class Report | Admin')
@section('page-title', 'Class Report')

@section('content')
<div class="class-simple">
    <div class="container-fluid py-4">

        @php
            $reportClasses = $classes ?? collect();
            $recordsCount = method_exists($reportClasses, 'total')
                ? $reportClasses->total()
                : $reportClasses->count();
            $firstOffset = method_exists($reportClasses, 'firstItem')
                ? (($reportClasses->firstItem() ?? 1) - 1)
                : 0;

            $selSearch = trim((string) request('search'));
            $selYear   = request('academic_year');
            $selStatus = request('status');

            $statusLabel = 'All statuses';
            if ($selStatus === '1') {
                $statusLabel = 'Active';
            } elseif ($selStatus === '0') {
                $statusLabel = 'Inactive';
            }

            // School identity for print
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
                    <div class="print-brand-name">Class Report</div>
                    <div class="print-brand-sub">Class &amp; Section Overview</div>
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
                    <span class="print-label">Status:</span>
                    <span class="print-value">{{ $statusLabel }}</span>
                </div>
                @if ($selSearch !== '')
                    <div class="print-meta-row">
                        <span class="print-label">Search:</span>
                        <span class="print-value">{{ $selSearch }}</span>
                    </div>
                @endif
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="class-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.class-reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back 
            </a>
            <div></div>
        </div>

        {{-- HEADER --}}
        <section class="hero-section hero-simple">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <div class="hero-text">
                    <h2>Class Report</h2>
                    <p>Class-wise overview of sections, academic years, subject counts and active status from existing school records.</p>
                </div>
            </div>
            <div class="hero-actions">
                <span class="hero-count">
                    <strong>{{ number_format($recordsCount) }}</strong> matching records
                </span>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printClassReport()">
                        <i class="fas fa-print"></i> Print / Save as PDF
                    </button>
                </div>
            </div>
        </section>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.class-reports.classes') }}" class="filters">
            <div class="filter-item filter-search">
                <label for="cr-search">Search Class or Section</label>
                <input id="cr-search" type="text" name="search" value="{{ request('search') }}" placeholder="Class name or section...">
            </div>

            <div class="filter-item">
                <label for="cr-year">Academic Year</label>
                <select id="cr-year" name="academic_year">
                    <option value="">All academic years</option>
                    @foreach(($academicYears ?? collect()) as $year)
                        <option value="{{ $year }}" @selected((string) request('academic_year') === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="cr-status">Status</label>
                <select id="cr-status" name="status">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.class-reports.classes') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Academic Year</th>
                        <th>Subjects</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportClasses as $class)
                        <tr>
                            <td>{{ $loop->iteration + $firstOffset }}</td>
                            <td>
                                <div class="emp-name">{{ $class->class_name ?: '—' }}</div>
                            </td>
                            <td>{{ $class->section ?: '—' }}</td>
                            <td>{{ $class->academic_year ?: '—' }}</td>
                            <td class="strong">{{ $class->subjects_count ?? 0 }}</td>
                            <td>
                                @if((bool) $class->status)
                                    <span class="status paid">Active</span>
                                @else
                                    <span class="status unpaid">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                No classes found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-bar">
            <span>
                @if(method_exists($reportClasses, 'total'))
                    Showing {{ $reportClasses->firstItem() ?? 0 }} to {{ $reportClasses->lastItem() ?? 0 }} of {{ $reportClasses->total() }} records
                @else
                    {{ $reportClasses->count() }} records
                @endif
            </span>
            @if(method_exists($reportClasses, 'links'))
                <div>{{ $reportClasses->withQueryString()->links() }}</div>
            @endif
        </div>

        {{-- NOTE --}}
        <div class="report-note">
            <span class="report-note-mark"><i class="fas fa-info"></i></span>
            <div>
                <strong>Report note:</strong>
                This report uses existing class and section records only. Subject counts reflect currently linked subjects. Confirm academic year filters before using the printed version for official purposes.
            </div>
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated class report. For official use only.
        </div>

    </div>
</div>

<style>
.class-simple {
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

.class-simple *,
.class-simple *::before,
.class-simple *::after {
    box-sizing: border-box;
}

.class-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.class-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.class-simple .btn-back {
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

.class-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.class-simple .btn-primary,
.class-simple .btn-secondary {
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

.class-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.class-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}

.class-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.class-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HEADER ========== */
.class-simple .hero-section {
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

.class-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
}

.class-simple .hero-icon {
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

.class-simple .hero-text h2 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.class-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 520px;
}

.class-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.class-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}

.class-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}

.class-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.class-simple .hero-actions .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.class-simple .hero-actions .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

/* Filters */
.class-simple .filters {
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

.class-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.class-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.class-simple .filter-item input,
.class-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.class-simple .filter-item.filter-search input {
    min-width: 240px;
}

.class-simple .filter-item input:focus,
.class-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.class-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.class-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.class-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 900px;
}

.class-simple th {
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

.class-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.class-simple tbody tr:last-child td {
    border-bottom: none;
}

.class-simple tbody tr:hover td {
    background: #faf9f5;
}

.class-simple .emp-name {
    font-weight: 600;
}

.class-simple .strong {
    font-weight: 700;
}

.class-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.class-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.class-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.class-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.class-simple .pagination-bar {
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
.class-simple .report-note {
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

.class-simple .report-note strong {
    color: var(--text);
}

.class-simple .report-note-mark {
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
.class-simple .print-only,
.class-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .class-simple .hero-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .class-simple .hero-actions {
        align-items: flex-start;
    }
    .class-simple .hero-buttons {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .class-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .class-simple .filter-item input,
    .class-simple .filter-item select,
    .class-simple .filter-item.filter-search input {
        width: 100%;
        min-width: 0;
    }
    .class-simple .hero-text h2 {
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

    .class-simple,
    .class-simple * {
        visibility: visible;
    }

    .class-simple {
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
    .class-simple .top-bar,
    .class-simple .hero-section,
    .class-simple .filters,
    .class-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .class-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .class-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .class-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .class-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .class-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .class-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .class-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .class-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .class-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .class-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .class-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .class-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .class-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .class-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .class-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .class-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .class-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .class-simple th {
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

    .class-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .class-simple tr {
        break-inside: avoid;
    }

    .class-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .class-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .class-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .class-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .class-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .class-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .class-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    /* Note + footer */
    .class-simple .report-note {
        margin-top: 10px;
        padding: 7px 10px;
        background: #f7f6f2 !important;
        border: 1px solid #ccc;
        border-radius: 0;
        font-size: 8.5px;
        color: #444;
        break-inside: avoid;
    }

    .class-simple .report-note-mark {
        display: none !important;
    }

    .class-simple .print-footer {
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
    function setClassPrintDate() {
        const el = document.getElementById('class-print-date');
        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printClassReport = function () {
        setClassPrintDate();

        const logo = document.querySelector('.class-simple .print-school-logo');

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