@extends('layouts.app')

@section('title', 'Transport Reports | Admin')
@section('page-title', 'Transport Reports')

@section('content')
<div class="transport-simple">
    <div class="container-fluid py-4">

        @php
            $money = function ($amount) {
                return '₹' . number_format((float) ($amount ?? 0), 2);
            };

            $selectedFromDate = request('from_date');
            $selectedToDate = request('to_date');
            $selectedTransportStatus = request('transport_status');
            $selectedTransportType = request('transport_type');
            $selectedPaymentStatus = request('payment_status');

            // Fetch school identity for the browser print view.
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

            $periodLabel = 'All Dates';
            if ($selectedFromDate || $selectedToDate) {
                $periodLabel = ($selectedFromDate ? \Carbon\Carbon::parse($selectedFromDate)->format('d M Y') : 'Start')
                    . ' - ' .
                    ($selectedToDate ? \Carbon\Carbon::parse($selectedToDate)->format('d M Y') : 'Today');
            }

            $recordsCount = method_exists($records, 'total') ? $records->total() : count($records);
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
                    <div class="print-brand-name">Transport Report</div>
                    <div class="print-brand-sub">Student Transport Records</div>
                </div>
            </div>
            
                
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="transport-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
            <div>
            </div>
        </div>

        {{-- HERO SECTION (Cream / Pearl) --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <div class="hero-text">
                    <div class="hero-breadcrumb"></div>
                    <h2>Transport Reports</h2>
                    <p>Routes, vehicles, students and transport fee records in one place.</p>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-handwritten">Route. Ride. Report.</div>
                <p>Review transport assignments, fees and payment status at a glance.</p>
                <div class="hero-buttons">
                    <button type="button" class="btn-primary" onclick="printTransportReport()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                    <a href="{{ route('admin.transport.records.index', request()->query()) }}" class="btn-secondary">
                        <i class="fas fa-list"></i> View Transport Records
                    </a>
                </div>
            </div>
        </section>

        {{-- REPORT LINKS --}}
        <div class="report-links">
            <a href="{{ route('admin.reports.transport.student-travel') }}" class="report-link">
                <div class="report-link-icon"><i class="fas fa-route"></i></div>
                <div class="report-link-text">
                    <h3>Student Travel Data</h3>
                    <p>View student-wise routes, vehicles, pickup points, drop points and transport fees.</p>
                </div>
                <div class="report-link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>

            <a href="{{ route('admin.reports.transport.vehicle') }}" class="report-link">
                <div class="report-link-icon"><i class="fas fa-bus"></i></div>
                <div class="report-link-text">
                    <h3>Vehicle Management</h3>
                    <p>View vehicles and their assigned transport information.</p>
                </div>
                <div class="report-link-arrow"><i class="fas fa-chevron-right"></i></div>
            </a>
        </div>

      

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.transport.reports.student-search') }}" class="filters" id="transportFilterForm">
            <div class="filter-item student-search-field">
                <label for="studentSearch">Student</label>
                <div class="student-search-box">
                    <i class="fas fa-search student-search-icon"></i>
                    <input type="text" id="studentSearch" class="student-search-input" placeholder="Search student name or ID..." value="{{ $selectedStudent ? trim(($selectedStudent->first_name ?? '') . ' ' . ($selectedStudent->middle_name ?? '') . ' ' . ($selectedStudent->last_name ?? '')) . ' - ' . ($selectedStudent->student_id ?? $selectedStudent->id) : '' }}" autocomplete="off">
                    <input type="hidden" id="student_id" name="student_id" value="{{ request('student_id') }}">
                </div>
                <div id="studentSearchResults" class="student-search-results"></div>
            </div>

            <div class="filter-item">
                <label for="transport_status">Transport Status</label>
                <select id="transport_status" name="transport_status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('transport_status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('transport_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="transport_type">Transport Type</label>
                <select id="transport_type" name="transport_type">
                    <option value="">All Types</option>
                    <option value="school_bus" {{ request('transport_type') === 'school_bus' ? 'selected' : '' }}>School Bus</option>
                    <option value="van" {{ request('transport_type') === 'van' ? 'selected' : '' }}>Van</option>
                    <option value="private" {{ request('transport_type') === 'private' ? 'selected' : '' }}>Private</option>
                    <option value="other" {{ request('transport_type') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="payment_status">Payment Status</label>
                <select id="payment_status" name="payment_status">
                    <option value="">All Payments</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                </select>
            </div>

            <div class="filter-item">
                <label for="from_date">From Date</label>
                <input id="from_date" type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="filter-item">
                <label for="to_date">To Date</label>
                <input id="to_date" type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.transport.reports.student-search') }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Route</th>
                        <th>Vehicle</th>
                        <th>Pickup Point</th>
                        <th>Drop Point</th>
                        <th class="t-center">Type</th>
                        <th class="t-center">Status</th>
                        <th class="t-right">Fee</th>
                        <th class="t-center">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>
                                <div class="emp-name">{{ trim(($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '')) ?: '-' }}</div>
                                @if($record->student?->student_id)
                                    <div class="emp-id">ID: {{ $record->student->student_id }}</div>
                                @endif
                            </td>
                            <td>{{ $record->route ?? '-' }}</td>
                            <td>{{ $record->vehicle ?? '-' }}</td>
                            <td>{{ $record->pickup_point ?? '-' }}</td>
                            <td>{{ $record->drop_point ?? '-' }}</td>
                            <td class="t-center">{{ ucfirst(str_replace('_', ' ', $record->transport_type ?? '-')) }}</td>
                            <td class="t-center">
                                @if(($record->transport_status ?? '') === 'active')
                                    <span class="status paid">Active</span>
                                @else
                                    <span class="status unpaid">{{ ucfirst($record->transport_status ?? 'Inactive') }}</span>
                                @endif
                            </td>
                            <td class="t-right net">{{ $money($record->transport_fee) }}</td>
                            <td class="t-center">
                                @if(($record->payment_status ?? '') === 'paid')
                                    <span class="status paid">Paid</span>
                                @elseif(($record->payment_status ?? '') === 'pending')
                                    <span class="status unpaid">Pending</span>
                                @else
                                    <span class="status partial">{{ ucfirst(str_replace('_', ' ', $record->payment_status ?? '-')) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty">
                                No transport records found. Try changing the filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (only if the controller passes a paginator) --}}
        @if (method_exists($records, 'hasPages'))
            @if ($records->hasPages())
                <div class="pagination-bar">
                    <span>
                        Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} records
                    </span>
                    <div>{{ $records->links() }}</div>
                </div>
            @elseif($records->count())
                <div class="pagination-bar">
                    <span>Showing {{ $records->count() }} of {{ $records->total() }} records</span>
                </div>
            @endif
        @endif

    </div>
</div>

<style>
.transport-simple {
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

.transport-simple *,
.transport-simple *::before,
.transport-simple *::after {
    box-sizing: border-box;
}

.transport-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.transport-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.transport-simple .btn-back {
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

.transport-simple .btn-back:hover {
    background: #f0eee8;
}

.transport-simple .btn-primary,
.transport-simple .btn-secondary {
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

.transport-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.transport-simple .btn-primary:hover {
    background: var(--primary-hover);
}

.transport-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.transport-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HERO SECTION (Cream / Pearl) ========== */
.transport-simple .hero-section {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 24px;
    background: #fff;
}

.transport-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 28px 28px 28px 32px;
    background: #fdfbf7;
}

.transport-simple .hero-icon {
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

.transport-simple .hero-text .hero-breadcrumb {
    font-size: 11px;
    font-weight: 700;
    color: #9c7b4a;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.transport-simple .hero-text h2 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.transport-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 480px;
}

/* Cream / Pearl Right Side */
.transport-simple .hero-right {
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

.transport-simple .hero-handwritten {
    font-family: "Segoe Print", "Comic Sans MS", cursive;
    font-size: 22px;
    color: #5c4a32;
    line-height: 1.3;
}

.transport-simple .hero-right p {
    margin: 0;
    font-size: 13px;
    color: #6b5c45;
    max-width: 260px;
    line-height: 1.6;
}

.transport-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 6px;
}

.transport-simple .hero-right .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.transport-simple .hero-right .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

.transport-simple .hero-right .btn-secondary {
    background: rgba(255, 252, 245, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.transport-simple .hero-right .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Report links */
.transport-simple .report-links {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.transport-simple .report-link {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 18px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    color: var(--text);
    text-decoration: none;
}

.transport-simple .report-link:hover {
    background: #faf9f5;
    border-color: #c4b49a;
    color: var(--text);
}

.transport-simple .report-link-icon {
    width: 46px;
    height: 46px;
    min-width: 46px;
    border-radius: 10px;
    background: #f3ead8;
    border: 1px solid #e5d9c0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #5c4a32;
    font-size: 18px;
}

.transport-simple .report-link-text h3 {
    margin: 0 0 4px;
    font-size: 15px;
    font-weight: 700;
    color: var(--text);
}

.transport-simple .report-link-text p {
    margin: 0;
    font-size: 12.5px;
    color: var(--muted);
    line-height: 1.5;
}

.transport-simple .report-link-arrow {
    margin-left: auto;
    color: var(--muted);
    font-size: 14px;
}

/* Filters */
.transport-simple .filters {
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

.transport-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.transport-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.transport-simple .filter-item input,
.transport-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.transport-simple .filter-item input:focus,
.transport-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.transport-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Student search dropdown */
.transport-simple .student-search-field {
    position: relative;
    min-width: 260px;
}

.transport-simple .student-search-box {
    position: relative;
}

.transport-simple .student-search-box input.student-search-input {
    width: 100%;
    padding-left: 34px;
}

.transport-simple .student-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 12px;
    z-index: 2;
    pointer-events: none;
}

.transport-simple .student-search-results {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 4px);
    z-index: 1000;
    display: none;
    max-height: 250px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 6px;
    box-shadow: 0 8px 22px rgba(30, 42, 37, 0.12);
}

.transport-simple .student-search-result {
    display: block;
    width: 100%;
    padding: 10px 12px;
    border: 0;
    border-bottom: 1px solid #eee9dd;
    background: #fff;
    color: var(--text);
    text-align: left;
    cursor: pointer;
    font: inherit;
}

.transport-simple .student-search-result:last-child {
    border-bottom: none;
}

.transport-simple .student-search-result:hover {
    background: #faf9f5;
}

.transport-simple .student-search-result-name {
    display: block;
    font-weight: 600;
    font-size: 13px;
}

.transport-simple .student-search-result-id {
    display: block;
    margin-top: 2px;
    color: var(--muted);
    font-size: 12px;
}

.transport-simple .student-search-message {
    padding: 11px 12px;
    color: var(--muted);
    font-size: 13px;
}

/* Summary */
.transport-simple .summary {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    padding: 14px 16px;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 13px;
}

.transport-simple .summary span {
    display: block;
    font-size: 11px;
    color: var(--muted);
    margin-bottom: 2px;
    text-transform: uppercase;
}

.transport-simple .summary strong {
    font-size: 15px;
}

.transport-simple .summary .net,
.transport-simple .summary .good {
    color: var(--net);
}

.transport-simple .summary .bad {
    color: #a33a2e;
}

/* Table */
.transport-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.transport-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1000px;
}

.transport-simple th {
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

.transport-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.transport-simple th.t-center,
.transport-simple td.t-center {
    text-align: center;
}

.transport-simple th.t-right,
.transport-simple td.t-right {
    text-align: right;
}

.transport-simple tbody tr:last-child td {
    border-bottom: none;
}

.transport-simple tbody tr:hover td {
    background: #faf9f5;
}

.transport-simple .emp-name {
    font-weight: 600;
}

.transport-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.transport-simple .net {
    color: var(--net);
    font-weight: 700;
}

.transport-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.transport-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.transport-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.transport-simple .status.partial {
    background: #fbf1da;
    color: #8a6414;
}

.transport-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Pagination */
.transport-simple .pagination-bar {
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
.transport-simple .print-only {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .transport-simple .hero-section {
        grid-template-columns: 1fr;
    }
    .transport-simple .hero-right {
        border-left: none;
        border-top: 1px solid #e5d9c4;
    }
    .transport-simple .report-links {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .transport-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .transport-simple .filter-item input,
    .transport-simple .filter-item select {
        width: 100%;
        min-width: 0;
    }
    .transport-simple .student-search-field {
        min-width: 0;
    }
    .transport-simple .summary {
        gap: 16px;
    }
    .transport-simple .hero-left {
        padding: 22px 20px;
    }
    .transport-simple .hero-text h2 {
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

    .transport-simple,
    .transport-simple * {
        visibility: visible;
    }

    .transport-simple {
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
    .transport-simple .top-bar,
    .transport-simple .hero-section,
    .transport-simple .report-links,
    .transport-simple .filters,
    .transport-simple .summary,
    .transport-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .transport-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .transport-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .transport-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .transport-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .transport-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .transport-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .transport-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .transport-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .transport-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .transport-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .transport-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .transport-simple .print-meta-row {
        display: flex;
        align-items: right;
        gap: 850px;
        font-size: 10px;
    }

    .transport-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .transport-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .transport-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .transport-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .transport-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .transport-simple th {
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

    .transport-simple th.t-center,
    .transport-simple td.t-center {
        text-align: center;
    }

    .transport-simple th.t-right,
    .transport-simple td.t-right {
        text-align: right;
    }

    .transport-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .transport-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .transport-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .transport-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .transport-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }

    .transport-simple .net {
        color: #1a6b3c !important;
        font-weight: 700;
    }

    .transport-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .transport-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .transport-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .transport-simple .status.partial {
        background: #fbf1da !important;
        color: #8a6414 !important;
        border-color: #e6cf98;
    }

    .transport-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    /* Subtle footer note */
    .transport-simple .table-wrap::after {
        content: "This is a system-generated transport report. For official use only.";
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
    function setTransportPrintDate() {
        const el = document.getElementById('transport-print-date');
        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printTransportReport = function () {
        setTransportPrintDate();

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

/* ===== Student search (unchanged working logic) ===== */
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('studentSearch');
    const studentIdInput = document.getElementById('student_id');
    const resultsBox = document.getElementById('studentSearchResults');
    const filterForm = document.getElementById('transportFilterForm');

    if (!searchInput || !studentIdInput || !resultsBox || !filterForm) return;

    let searchTimer = null;

    function hideResults() {
        resultsBox.style.display = 'none';
        resultsBox.innerHTML = '';
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function showMessage(message) {
        resultsBox.innerHTML = '<div class="student-search-message">' + escapeHtml(message) + '</div>';
        resultsBox.style.display = 'block';
    }

    function showResults(students) {
        if (!Array.isArray(students) || students.length === 0) {
            showMessage('No student found.');
            return;
        }

        resultsBox.innerHTML = students.map(function (student) {
            const id = student.id ?? '';
            const studentId = student.student_id ?? id;
            const name = student.name ?? 'Unknown Student';
            return `<button type="button" class="student-search-result"
                data-id="${escapeHtml(id)}" data-name="${escapeHtml(name)}"
                data-student-id="${escapeHtml(studentId)}">
                <span class="student-search-result-name">${escapeHtml(name)}</span>
                <span class="student-search-result-id">ID: ${escapeHtml(studentId)}</span>
            </button>`;
        }).join('');

        resultsBox.style.display = 'block';
        resultsBox.querySelectorAll('.student-search-result').forEach(function (button) {
            button.addEventListener('click', function () {
                studentIdInput.value = this.dataset.id;
                searchInput.value = this.dataset.name + ' - ' + this.dataset.studentId;
                hideResults();
                filterForm.submit();
            });
        });
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        studentIdInput.value = '';
        clearTimeout(searchTimer);

        if (!query) {
            hideResults();
            return;
        }

        showMessage('Searching...');
        searchTimer = setTimeout(function () {
            const url = new URL(@json(route('admin.transport.reports.student-search')), window.location.origin);
            url.searchParams.set('student_search', query);

            fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function (response) {
                if (!response.ok) throw new Error('Search request failed. HTTP ' + response.status);
                return response.text();
            })
            .then(function (responseText) {
                const cleaned = responseText.replace(/^\uFEFF/, '').trim();
                return JSON.parse(cleaned);
            })
            .then(function (data) {
                if (!data || data.success !== true) {
                    showMessage('Unable to search students.');
                    return;
                }
                showResults(Array.isArray(data.students) ? data.students : []);
            })
            .catch(function (error) {
                console.error('Transport student search error:', error);
                showMessage('Unable to search students.');
            });
        }, 300);
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.student-search-field')) hideResults();
    });

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') hideResults();
    });
});
</script>
@endsection