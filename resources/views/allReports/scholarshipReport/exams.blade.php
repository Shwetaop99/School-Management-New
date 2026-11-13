@extends('layouts.app')

@section('title', 'Scholarship Exam Report | Admin')
@section('page-title', 'Scholarship Exam Report')

@section('content')
<div class="exam-simple">
    <div class="container-fluid py-4">

        @php
            $recordsCount = method_exists($exams, 'total') ? $exams->total() : $exams->count();
            $firstOffset = method_exists($exams, 'firstItem') ? (($exams->firstItem() ?? 1) - 1) : 0;

            $selSearch = trim((string) request('search'));
            $selYear = request('academic_year');
            $selStatus = request('status');
            $statusLabel = $selStatus === '1' ? 'Active' : ($selStatus === '0' ? 'Inactive' : 'All statuses');

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
                    <div class="print-brand-name">Scholarship Exam Report</div>
                    <div class="print-brand-sub">Examination Results &amp; Awards</div>
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
                    <span class="print-value" id="exam-print-date">—</span>
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
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="hero-text">
                    <h2>Scholarship Exam Report</h2>
                    <p>Examination participation, results and scholarship awards recorded in the system.</p>
                </div>
            </div>
            <div class="hero-actions">
                <span class="hero-count">
                    <strong>{{ number_format($recordsCount) }}</strong> matching records
                </span>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printExamReport()">
                        <i class="fas fa-print"></i> Print / Save as PDF
                    </button>
                </div>
            </div>
        </section>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.reports.scholarship.exams') }}" class="filters">
            <div class="filter-item filter-search">
                <label for="sr-search">Search</label>
                <input id="sr-search" type="text" name="search" value="{{ request('search') }}" placeholder="Exam name, type, organizer">
            </div>

            <div class="filter-item">
                <label for="sr-year">Academic Year</label>
                <select id="sr-year" name="academic_year">
                    <option value="">All academic years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" @selected(request('academic_year') == $year)>{{ $year }}</option>
                    @endforeach
                </select>
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
                <a href="{{ route('admin.reports.scholarship.exams') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Examination</th>
                        <th>Academic Year</th>
                        <th>Exam Date</th>
                        <th>Eligible</th>
                        <th>Applied</th>
                        <th>Appeared</th>
                        <th>Passed</th>
                        <th>Pass %</th>
                        <th>Awards</th>
                        <th>Award Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>{{ $firstOffset + $loop->iteration }}</td>
                            <td>
                                <div class="emp-name">{{ $exam->exam_name }}</div>
                                <div class="emp-id">{{ $exam->exam_type ?: '—' }}</div>
                                @if($exam->conducted_by)
                                    <div class="emp-id">By: {{ $exam->conducted_by }}</div>
                                @endif
                            </td>
                            <td>{{ $exam->academic_year ?: '—' }}</td>
                            <td>{{ $exam->exam_date?->format('d M Y') ?? '—' }}</td>
                            <td>{{ number_format((int) $exam->total_eligible) }}</td>
                            <td>{{ number_format((int) $exam->total_applied) }}</td>
                            <td>{{ number_format((int) $exam->total_appeared) }}</td>
                            <td>{{ number_format((int) $exam->total_passed) }}</td>
                            <td class="strong">{{ number_format((float) $exam->pass_percentage, 2) }}%</td>
                            <td>{{ number_format((int) $exam->scholarships_awarded_count) }}</td>
                            <td>{{ number_format((float) ($exam->scholarship_amount_sum ?? 0), 2) }}</td>
                            <td>
                                <span class="status {{ $exam->status ? 'paid' : 'unpaid' }}">
                                    {{ $exam->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="empty">
                                No scholarship examination records match your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-bar">
            <span>
                @if(method_exists($exams, 'total'))
                    Showing {{ $exams->firstItem() ?? 0 }} to {{ $exams->lastItem() ?? 0 }} of {{ $exams->total() }} records
                @else
                    {{ $exams->count() }} records
                @endif
            </span>
            @if(method_exists($exams, 'links'))
                <div>{{ $exams->links() }}</div>
            @endif
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated scholarship exam report. For official use only.
        </div>

    </div>
</div>

<style>
.exam-simple {
    --bg: #f5f4f0;
    --text: #1e2a25;
    --muted: #5c6b64;
    --border: #d4cfc2;
    --primary: #2c3e36;
    --primary-hover: #3a4f45;
    background: var(--bg);
    color: var(--text);
    min-height: calc(100vh - 70px);
    font-family: Inter, "Public Sans", Arial, sans-serif;
}

.exam-simple *,
.exam-simple *::before,
.exam-simple *::after {
    box-sizing: border-box;
}

.exam-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.exam-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.exam-simple .btn-back {
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

.exam-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.exam-simple .btn-primary,
.exam-simple .btn-secondary {
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

.exam-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.exam-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}

.exam-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.exam-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HEADER (simple, full pearl) ========== */
.exam-simple .hero-section {
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

.exam-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
}

.exam-simple .hero-icon {
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

.exam-simple .hero-text h2 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.exam-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 520px;
}

.exam-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
}

.exam-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}

.exam-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}

.exam-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.exam-simple .hero-actions .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.exam-simple .hero-actions .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

/* Filters */
.exam-simple .filters {
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

.exam-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.exam-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.exam-simple .filter-item input,
.exam-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.exam-simple .filter-item.filter-search input {
    min-width: 260px;
}

.exam-simple .filter-item input:focus,
.exam-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.exam-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.exam-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.exam-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1100px;
}

.exam-simple th {
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

.exam-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.exam-simple tbody tr:last-child td {
    border-bottom: none;
}

.exam-simple tbody tr:hover td {
    background: #faf9f5;
}

.exam-simple .emp-name {
    font-weight: 600;
}

.exam-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.exam-simple .strong {
    font-weight: 700;
}

.exam-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.exam-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.exam-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.exam-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.exam-simple .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    font-size: 13px;
    color: var(--muted);
}

/* Print only (hidden on screen) */
.exam-simple .print-only,
.exam-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .exam-simple .hero-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .exam-simple .hero-actions {
        align-items: flex-start;
    }
    .exam-simple .hero-buttons {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .exam-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .exam-simple .filter-item input,
    .exam-simple .filter-item select,
    .exam-simple .filter-item.filter-search input {
        width: 100%;
        min-width: 0;
    }
    .exam-simple .hero-text h2 {
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

    .exam-simple,
    .exam-simple * {
        visibility: visible;
    }

    .exam-simple {
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
    .exam-simple .top-bar,
    .exam-simple .hero-section,
    .exam-simple .filters,
    .exam-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .exam-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .exam-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .exam-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .exam-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .exam-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .exam-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .exam-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .exam-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .exam-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .exam-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .exam-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .exam-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .exam-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .exam-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .exam-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .exam-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .exam-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .exam-simple th {
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

    .exam-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .exam-simple tr {
        break-inside: avoid;
    }

    .exam-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .exam-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .exam-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .exam-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }

    .exam-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .exam-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .exam-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .exam-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    .exam-simple .print-footer {
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
    function setExamPrintDate() {
        const el = document.getElementById('exam-print-date');

        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printExamReport = function () {
        setExamPrintDate();

        const logo = document.querySelector('.exam-simple .print-school-logo');

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