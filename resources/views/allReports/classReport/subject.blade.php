@extends('layouts.app')

@section('title', 'Subject Report | Admin')
@section('page-title', 'Subject Report')

@section('content')
<div class="subject-simple">
    <div class="container-fluid py-4">

        @php
            $reportSubjects = $subjects ?? collect();
            $recordsCount = method_exists($reportSubjects, 'total')
                ? $reportSubjects->total()
                : $reportSubjects->count();
            $firstOffset = method_exists($reportSubjects, 'firstItem')
                ? (($reportSubjects->firstItem() ?? 1) - 1)
                : 0;

            $selSearch  = trim((string) request('search'));
            $selClassId = request('class_id');
            $selSection = request('section');
            $selStatus  = request('status');

            $statusLabel = 'All statuses';
            if ($selStatus === '1') {
                $statusLabel = 'Active';
            } elseif ($selStatus === '0') {
                $statusLabel = 'Inactive';
            }

            // Resolve selected class name for print meta
            $selectedClassName = 'All classes';
            if ($selClassId) {
                $selectedClass = ($classes ?? collect())->firstWhere('id', $selClassId);
                if ($selectedClass) {
                    $selectedClassName = $selectedClass->class_name . ($selectedClass->section ? ' - ' . $selectedClass->section : '');
                }
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

            // Unique sections from classes for the Division dropdown
            $allSections = ($classes ?? collect())
                ->pluck('section')
                ->filter(fn ($s) => trim((string) $s) !== '')
                ->unique()
                ->sort()
                ->values();
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
                    <div class="print-brand-name">Subject Report</div>
                    <div class="print-brand-sub">Class-wise Subject Overview</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Records:</span>
                    <span class="print-value">{{ number_format($recordsCount) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Class:</span>
                    <span class="print-value">{{ $selectedClassName }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Section:</span>
                    <span class="print-value">{{ $selSection ?: 'All sections' }}</span>
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
                    <span class="print-value" id="subject-print-date">—</span>
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
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="hero-text">
                    <h2>Subject Report</h2>
                    <p>Select a Class and Section first to view the subjects linked to that class. Search and status filters are available after selection.</p>
                </div>
            </div>
            <div class="hero-actions">
                <span class="hero-count">
                    <strong>{{ number_format($recordsCount) }}</strong> matching records
                </span>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printSubjectReport()">
                        <i class="fas fa-print"></i> Print / Save as PDF
                    </button>
                </div>
            </div>
        </section>

        {{-- FILTERS — Class & Section first --}}
        <form method="GET" action="{{ route('admin.class-reports.subjects') }}" class="filters">
            <div class="filter-item">
                <label for="sr-class">Class <span style="color:#a33a2e">*</span></label>
                <select id="sr-class" name="class_id" required>
                    <option value="">Select Class</option>
                    @foreach(($classes ?? collect()) as $class)
                        <option value="{{ $class->id }}" @selected((string) request('class_id') === (string) $class->id)>
                            {{ $class->class_name }}{{ $class->section ? ' - ' . $class->section : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="sr-section">Section / Division</label>
                <select id="sr-section" name="section">
                    <option value="">All sections</option>
                    @foreach($allSections as $section)
                        <option value="{{ $section }}" @selected((string) request('section') === (string) $section)>
                            {{ $section }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item filter-search">
                <label for="sr-search">Search Subject</label>
                <input id="sr-search" type="text" name="search" value="{{ request('search') }}" placeholder="Subject name or code...">
            </div>

            <div class="filter-item">
                <label for="sr-status">Status</label>
                <select id="sr-status" name="status">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Active</option>
                    <option value="0" @selected(request('status') === '0')>Inactive</option>
                </select>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.class-reports.subjects') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE (only when Class is selected) --}}
        @if($selClassId)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportSubjects as $subject)
                            <tr>
                                <td>{{ $loop->iteration + $firstOffset }}</td>
                                <td>
                                    <div class="emp-name">{{ $subject->subject_name ?: '—' }}</div>
                                </td>
                                <td>
                                    @if($subject->subject_code)
                                        <span class="strong">{{ $subject->subject_code }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $subject->schoolClass->class_name ?? '—' }}</td>
                                <td>{{ $subject->schoolClass->section ?? '—' }}</td>
                                <td>
                                    @if((bool) $subject->status)
                                        <span class="status paid">Active</span>
                                    @else
                                        <span class="status unpaid">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty">
                                    No subjects found for the selected Class / Section.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="pagination-bar">
                <span>
                    @if(method_exists($reportSubjects, 'total'))
                        Showing {{ $reportSubjects->firstItem() ?? 0 }} to {{ $reportSubjects->lastItem() ?? 0 }} of {{ $reportSubjects->total() }} records
                    @else
                        {{ $reportSubjects->count() }} records
                    @endif
                </span>
                @if(method_exists($reportSubjects, 'links'))
                    <div>{{ $reportSubjects->withQueryString()->links() }}</div>
                @endif
            </div>
        @else
            {{-- Empty state when no Class selected --}}
            <div class="table-wrap">
                <div class="empty" style="padding: 60px 20px;">
                    <div style="font-size: 28px; color: #9aa99d; margin-bottom: 12px;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <strong style="display:block; color: var(--text); font-size: 15px; margin-bottom: 6px;">
                        Select Class &amp; Section
                    </strong>
                    <span style="color: var(--muted); font-size: 13px;">
                        Choose a Class (and optionally Section) above, then click Apply to view the subjects.
                    </span>
                </div>
            </div>
        @endif

        {{-- NOTE --}}
        <div class="report-note">
            <span class="report-note-mark"><i class="fas fa-info"></i></span>
            <div>
                <strong>Report note:</strong>
                Subjects are shown only after a Class is selected. This report uses existing subject and class records. Confirm the selected Class and Section before printing for official use.
            </div>
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated subject report. For official use only.
        </div>

    </div>
</div>

<style>
.subject-simple {
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

.subject-simple *,
.subject-simple *::before,
.subject-simple *::after {
    box-sizing: border-box;
}

.subject-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.subject-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.subject-simple .btn-back {
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

.subject-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.subject-simple .btn-primary,
.subject-simple .btn-secondary {
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

.subject-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.subject-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}

.subject-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.subject-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HEADER ========== */
.subject-simple .hero-section {
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

.subject-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
}

.subject-simple .hero-icon {
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

.subject-simple .hero-text h2 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.subject-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 520px;
}

.subject-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.subject-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}

.subject-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}

.subject-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.subject-simple .hero-actions .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.subject-simple .hero-actions .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

/* Filters */
.subject-simple .filters {
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

.subject-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.subject-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.subject-simple .filter-item input,
.subject-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.subject-simple .filter-item.filter-search input {
    min-width: 220px;
}

.subject-simple .filter-item input:focus,
.subject-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.subject-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.subject-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.subject-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 900px;
}

.subject-simple th {
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

.subject-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.subject-simple tbody tr:last-child td {
    border-bottom: none;
}

.subject-simple tbody tr:hover td {
    background: #faf9f5;
}

.subject-simple .emp-name {
    font-weight: 600;
}

.subject-simple .strong {
    font-weight: 700;
}

.subject-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.subject-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.subject-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.subject-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.subject-simple .pagination-bar {
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
.subject-simple .report-note {
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

.subject-simple .report-note strong {
    color: var(--text);
}

.subject-simple .report-note-mark {
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
.subject-simple .print-only,
.subject-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .subject-simple .hero-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .subject-simple .hero-actions {
        align-items: flex-start;
    }
    .subject-simple .hero-buttons {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .subject-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .subject-simple .filter-item input,
    .subject-simple .filter-item select,
    .subject-simple .filter-item.filter-search input {
        width: 100%;
        min-width: 0;
    }
    .subject-simple .hero-text h2 {
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

    .subject-simple,
    .subject-simple * {
        visibility: visible;
    }

    .subject-simple {
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
    .subject-simple .top-bar,
    .subject-simple .hero-section,
    .subject-simple .filters,
    .subject-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .subject-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .subject-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .subject-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .subject-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .subject-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .subject-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .subject-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .subject-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .subject-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .subject-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .subject-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .subject-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .subject-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .subject-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .subject-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .subject-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .subject-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .subject-simple th {
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

    .subject-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .subject-simple tr {
        break-inside: avoid;
    }

    .subject-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .subject-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .subject-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .subject-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .subject-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .subject-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .subject-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    /* Note + footer */
    .subject-simple .report-note {
        margin-top: 10px;
        padding: 7px 10px;
        background: #f7f6f2 !important;
        border: 1px solid #ccc;
        border-radius: 0;
        font-size: 8.5px;
        color: #444;
        break-inside: avoid;
    }

    .subject-simple .report-note-mark {
        display: none !important;
    }

    .subject-simple .print-footer {
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
    function setSubjectPrintDate() {
        const el = document.getElementById('subject-print-date');
        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printSubjectReport = function () {
        setSubjectPrintDate();

        const logo = document.querySelector('.subject-simple .print-school-logo');

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