@extends('layouts.app')

@section('title', 'Fee Report | Admin')
@section('page-title', 'Fee Report')

@section('content')
<div class="payroll-simple">
    <div class="container-fluid py-4">
        @php
            $money = function ($amount) {
                return '₹' . number_format((float) ($amount ?? 0), 2);
            };

            $selectedSearch = request('search');
            $selectedYear = request('academic_year');
            $selectedStatus = request('status');
            $fromDate = request('from_date');
            $toDate = request('to_date');

            // School identity for print (exact same as Payroll)
            $schoolSetting = $schoolSetting ?? \App\Models\SchoolSetting::query()->first();
            $schoolName = trim((string) ($schoolSetting->school_name ?? ''));
            $schoolName = $schoolName !== '' ? $schoolName : 'School Management System';
            $schoolLogo = $schoolSetting->logo_url ?? null;
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
                    <div class="print-brand-name">Fee Report</div>
                    <div class="print-brand-sub">Official Fee Collection Statement</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Search:</span>
                    <span class="print-value">{{ $selectedSearch ?: 'All Students' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Academic Year:</span>
                    <span class="print-value">{{ $selectedYear ?: 'All' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Status:</span>
                    <span class="print-value">{{ $selectedStatus ? ucfirst($selectedStatus) : 'All' }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Due Period:</span>
                    <span class="print-value">
                        @if ($fromDate || $toDate)
                            {{ $fromDate ? \Carbon\Carbon::parse($fromDate)->format('d M Y') : '…' }}
                            –
                            {{ $toDate ? \Carbon\Carbon::parse($toDate)->format('d M Y') : '…' }}
                        @else
                            All Dates
                        @endif
                    </span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Total Assigned:</span>
                    <span class="print-value">{{ $money($totalAssigned) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Collected:</span>
                    <span class="print-value">{{ $money($totalPaid) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Outstanding:</span>
                    <span class="print-value">{{ $money($totalBalance) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Records:</span>
                    <span class="print-value">{{ method_exists($fees, 'total') ? number_format($fees->total()) : number_format($fees->count()) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="fee-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <div></div>
        </div>

        {{-- HERO SECTION (Cream / Pearl) --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="hero-text">
                    <div class="hero-breadcrumb"></div>
                    <h2>Fee Report</h2>
                    <p>Student-wise fee collection, payments and outstanding balances.</p>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-handwritten">Collect. Track. Report.</div>
                <p>Review total fees, collections and balances in one place.</p>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printFeeReport()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="{{ route('admin.reports.fees.pdf', request()->query()) }}" class="btn-secondary">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
        </section>

        

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.reports.fees') }}" class="filters">
            <div class="filter-item">
                <label>Search Student</label>
                <input type="text" name="search" value="{{ $selectedSearch }}" placeholder="Student ID or name">
            </div>

            <div class="filter-item">
                <label>Academic Year</label>
                <select name="academic_year">
                    <option value="">All Years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" @selected($selectedYear == $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Fee Status</label>
                <select name="status">
                    <option value="">All Statuses</option>
                    @foreach(['paid', 'partial', 'pending', 'overdue'] as $status)
                        <option value="{{ $status }}" @selected($selectedStatus == $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label>Due From</label>
                <input type="date" name="from_date" value="{{ $fromDate }}">
            </div>

            <div class="filter-item">
                <label>Due To</label>
                <input type="date" name="to_date" value="{{ $toDate }}">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.reports.fees') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Academic Year</th>
                        <th>Fee Structure</th>
                        <th>Total Fee</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fees as $fee)
                        <tr>
                            <td>{{ $fees->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="emp-name">
                                    {{ $fee->student->first_name ?? 'N/A' }}
                                    {{ $fee->student->last_name ?? '' }}
                                </div>
                                <div class="emp-id">ID: {{ $fee->student->student_id ?? '—' }}</div>
                            </td>
                            <td>{{ $fee->academic_year ?? '—' }}</td>
                            <td>{{ $fee->feeStructure->structure_name ?? '—' }}</td>
                            <td>{{ $money($fee->total_amount) }}</td>
                            <td class="net">{{ $money($fee->paid_amount) }}</td>
                            <td style="color:#a33a2e; font-weight:600;">{{ $money($fee->balance_amount) }}</td>
                            <td>
                                {{ $fee->due_date ? $fee->due_date->format('d M Y') : '—' }}
                            </td>
                            <td>
                                @php
                                    $status = strtolower(trim($fee->status ?? 'pending'));
                                    $statusClass = $status === 'paid' ? 'paid' : (in_array($status, ['pending', 'overdue', 'partial'], true) ? 'unpaid' : '');
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty">
                                No fee records found. Try changing the filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($fees->hasPages())
            <div class="pagination-bar">
                <span>
                    Showing {{ $fees->firstItem() ?? 0 }} to {{ $fees->lastItem() ?? 0 }} of {{ $fees->total() }} records
                </span>
                <div>{{ $fees->links() }}</div>
            </div>
        @elseif ($fees->count())
            <div class="pagination-bar">
                <span>Showing {{ $fees->count() }} of {{ $fees->total() }} records</span>
            </div>
        @endif
    </div>
</div>

<style>
.payroll-simple {
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
.payroll-simple .container-fluid {
    max-width: 1400px;
}
/* Top bar */
.payroll-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.payroll-simple .btn-back {
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
.payroll-simple .btn-back:hover {
    background: #f0eee8;
}
.payroll-simple .top-actions {
    display: flex;
    gap: 8px;
}
.payroll-simple .btn-primary,
.payroll-simple .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid transparent;
}
.payroll-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.payroll-simple .btn-primary:hover {
    background: var(--primary-hover);
}
.payroll-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}
.payroll-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}
/* ========== HERO SECTION (Cream / Pearl) ========== */
.payroll-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}
.payroll-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}
.payroll-simple .hero-icon {
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
.payroll-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.payroll-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}
.payroll-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}
/* Cream / Pearl Right Side */
.payroll-simple .hero-right {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 28px 24px;
    text-align: center;
    background: linear-gradient(145deg, #f8f1e3 0%, #f0e6d2 50%, #e8dcc6 100%);
    border-left: 1px solid #e5d9c4;
}
.payroll-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}
.payroll-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}
.payroll-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}
.payroll-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}
.payroll-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}
.payroll-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}
.payroll-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}
/* Filters */
.payroll-simple .filters {
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
.payroll-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.payroll-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}
.payroll-simple .filter-item input,
.payroll-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}
.payroll-simple .filter-item input:focus,
.payroll-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}
.payroll-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.payroll-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}
.payroll-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1000px;
}
.payroll-simple th {
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
.payroll-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}
.payroll-simple tbody tr:last-child td {
    border-bottom: none;
}
.payroll-simple tbody tr:hover td {
    background: #faf9f5;
}
.payroll-simple .emp-name {
    font-weight: 600;
}
.payroll-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}
.payroll-simple .net {
    color: var(--net);
    font-weight: 700;
}
.payroll-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.payroll-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}
.payroll-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}
.payroll-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}
/* Pagination */
.payroll-simple .pagination-bar {
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
.payroll-simple .print-only {
    display: none;
}
/* Responsive */
@media (max-width: 900px) {
    .payroll-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .payroll-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
}
@media (max-width: 768px) {
    .payroll-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .payroll-simple .filter-item input,
    .payroll-simple .filter-item select {
        width: 100%;
        min-width: 0;
    }
    .payroll-simple .summary {
        gap: 16px;
    }
    .payroll-simple .hero-left {
        padding: 22px 20px;
    }
    .payroll-simple .hero-text h2 {
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
    .payroll-simple,
    .payroll-simple * {
        visibility: visible;
    }
    .payroll-simple {
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
    .payroll-simple .top-bar,
    .payroll-simple .hero-section,
    .payroll-simple .filters,
    .payroll-simple .summary,
    .payroll-simple .pagination-bar {
        display: none !important;
    }
    /* ---- Print Header ---- */
    .payroll-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }
    .payroll-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }
    .payroll-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }
    .payroll-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }
    .payroll-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }
    .payroll-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }
    .payroll-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }
    .payroll-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }
    .payroll-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }
    .payroll-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }
    .payroll-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }
    .payroll-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }
    .payroll-simple .print-label {
        color: #666;
        font-weight: 500;
    }
    .payroll-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }
    .payroll-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }
    /* ---- Table Styling for Print ---- */
    .payroll-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }
    .payroll-simple table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }
    .payroll-simple th {
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
    .payroll-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }
    .payroll-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }
    .payroll-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }
    .payroll-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }
    .payroll-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }
    .payroll-simple .net {
        color: #1a6b3c !important;
        font-weight: 700;
    }
    .payroll-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }
    .payroll-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }
    .payroll-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }
    .payroll-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }
    /* Subtle footer note */
    .payroll-simple .table-wrap::after {
        content: "This is a system-generated fee report. For official use only.";
        display: block;
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
    function setFeePrintDate() {
        const el = document.getElementById('fee-print-date');
        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printFeeReport = function () {
        setFeePrintDate();
        const logo = document.querySelector('.print-school-logo');
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