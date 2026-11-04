@extends('layouts.app')

@section('title', $reportTitle)
@section('page-title', $reportTitle)

@section('content')
<div class="meal-simple">
    <div class="container-fluid py-4">

        @php
            $recordsCount = (int) ($totalRecords ?? 0);

            $selFrom = request('from_date');
            $selTo = request('to_date');
            $periodLabel = 'All dates';
            if ($selFrom || $selTo) {
                $periodLabel = ($selFrom ? \Illuminate\Support\Carbon::parse($selFrom)->format('d M Y') : 'Start')
                    . ' to ' .
                    ($selTo ? \Illuminate\Support\Carbon::parse($selTo)->format('d M Y') : 'Today');
            }

            $selectedItem = request('meal_item_id')
                ? collect($items)->firstWhere('id', request('meal_item_id'))
                : null;
            $itemLabel = $selectedItem->item_name ?? 'All items';

            $heroIcon = $type === 'stock_in' ? 'fa-arrow-down' : ($type === 'stock_out' ? 'fa-arrow-up' : 'fa-history');

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
                    <div class="print-brand-name">{{ $reportTitle }}</div>
                    <div class="print-brand-sub">Meal Inventory Statement</div>
                </div>
            </div>
            <div class="print-meta">
                <div class="print-meta-row">
                    <span class="print-label">Records:</span>
                    <span class="print-value">{{ number_format($recordsCount) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">{{ $type === 'logs' ? 'Logged Quantity:' : 'Total Quantity:' }}</span>
                    <span class="print-value">{{ number_format((float) $totalQuantity, 2) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Transaction Amount:</span>
                    <span class="print-value">₹{{ number_format((float) $totalAmount, 2) }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Item:</span>
                    <span class="print-value">{{ $itemLabel }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Period:</span>
                    <span class="print-value">{{ $periodLabel }}</span>
                </div>
                <div class="print-meta-row">
                    <span class="print-label">Generated:</span>
                    <span class="print-value" id="meal-print-date">—</span>
                </div>
            </div>
            <div class="print-divider"></div>
        </div>

        {{-- TOP BAR --}}
        <div class="top-bar">
            <a href="{{ route('admin.reports.meal') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to Meal Reports
            </a>
            <div></div>
        </div>

        {{-- HEADER (simple, full pearl) --}}
        <section class="hero-section">
            <div class="hero-left">
                <div class="hero-icon">
                    <i class="fas {{ $heroIcon }}"></i>
                </div>
                <div class="hero-text">
                    <h2>{{ $reportTitle }}</h2>
                    <p>Review records, filter by item and date, then print or download this report.</p>
                </div>
            </div>
            <div class="hero-actions">
                <span class="hero-count">
                    <strong>{{ number_format($recordsCount) }}</strong> matching records
                </span>
                <span class="hero-meta">
                    {{ $type === 'logs' ? 'Logged quantity' : 'Total quantity' }}:
                    <strong>{{ number_format((float) $totalQuantity, 2) }}</strong>
                    &nbsp;·&nbsp;
                    Amount: <strong>₹{{ number_format((float) $totalAmount, 2) }}</strong>
                </span>
                <div class="hero-buttons">
                    <a href="{{ route('admin.reports.meal.pdf', request()->query()) }}" class="btn-primary">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                    <button type="button" class="btn-secondary" onclick="printMealReport()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </section>

        {{-- FILTERS --}}
        <form method="GET" action="{{ route('admin.reports.meal') }}" class="filters">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="filter-item filter-wide">
                <label for="meal_item_id">Meal Item</label>
                <select id="meal_item_id" name="meal_item_id">
                    <option value="">All Items</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}"
                            {{ (string) request('meal_item_id') === (string) $item->id ? 'selected' : '' }}>
                            {{ $item->item_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="from_date">From Date</label>
                <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="filter-item">
                <label for="to_date">To Date</label>
                <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('admin.reports.meal', ['type' => $type]) }}" class="btn-secondary">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="table-wrap">
            <table>
                @if($type === 'logs')
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date &amp; Time</th>
                            <th>Meal Item</th>
                            <th>Action</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Previous Stock</th>
                            <th>Updated Stock</th>
                            <th>Performed By</th>
                            <th>Reason / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $log->created_at ? $log->created_at->format('d M Y, h:i A') : '-' }}
                                </td>
                                <td>
                                    <div class="emp-name">{{ $log->mealItem?->item_name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="status {{ $log->action === 'stock_in' ? 'paid' : 'unpaid' }}">
                                        {{ \Illuminate\Support\Str::headline($log->action ?? 'unknown') }}
                                    </span>
                                </td>
                                <td>{{ number_format((float) $log->quantity, 2) }}</td>
                                <td>{{ $log->unit ?? '-' }}</td>
                                <td>{{ number_format((float) $log->previous_stock, 2) }}</td>
                                <td>{{ number_format((float) $log->updated_stock, 2) }}</td>
                                <td>{{ $log->performed_by ?? '-' }}</td>
                                <td>
                                    {{ $log->reason ?? '-' }}
                                    @if($log->remarks)
                                        <div class="emp-id">{{ $log->remarks }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty">
                                    No meal logs found for these filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @else
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Transaction Date</th>
                            <th>Meal Item</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Rate</th>
                            <th>Total Amount</th>
                            <th>Supplier</th>
                            <th>Reason</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $transaction->transaction_date?->format('d M Y') ?? '-' }}
                                </td>
                                <td>
                                    <div class="emp-name">{{ $transaction->mealItem?->item_name ?? '-' }}</div>
                                </td>
                                <td>{{ number_format((float) $transaction->quantity, 2) }}</td>
                                <td>{{ $transaction->unit ?? '-' }}</td>
                                <td>₹{{ number_format((float) ($transaction->rate ?? 0), 2) }}</td>
                                <td class="strong">₹{{ number_format((float) ($transaction->total_amount ?? 0), 2) }}</td>
                                <td>{{ $transaction->supplier ?? '-' }}</td>
                                <td>{{ $transaction->reason ?? '-' }}</td>
                                <td>{{ $transaction->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty">
                                    No {{ $type === 'stock_in' ? 'stock-in' : 'stock-out' }}
                                    transactions found for these filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>

        <div class="pagination-bar">
            <span>
                Dates for stock transactions refer to transaction dates; dates for logs refer to log creation dates.
            </span>
        </div>

        {{-- PRINT FOOTER --}}
        <div class="print-footer">
            This is a system-generated meal report. For official use only.
        </div>

    </div>
</div>

<style>
.meal-simple {
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

.meal-simple *,
.meal-simple *::before,
.meal-simple *::after {
    box-sizing: border-box;
}

.meal-simple .container-fluid {
    max-width: 1400px;
}

/* Top bar */
.meal-simple .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.meal-simple .btn-back {
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

.meal-simple .btn-back:hover {
    background: #f0eee8;
    color: var(--text);
}

.meal-simple .btn-primary,
.meal-simple .btn-secondary {
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

.meal-simple .btn-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.meal-simple .btn-primary:hover {
    background: var(--primary-hover);
    color: #fff;
}

.meal-simple .btn-secondary {
    background: #fff;
    color: var(--muted);
    border-color: var(--border);
}

.meal-simple .btn-secondary:hover {
    background: #f0eee8;
    color: var(--text);
}

/* ========== HEADER (simple, full pearl) ========== */
.meal-simple .hero-section {
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

.meal-simple .hero-left {
    display: flex;
    align-items: center;
    gap: 18px;
    min-width: 0;
}

.meal-simple .hero-icon {
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

.meal-simple .hero-text h2 {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 700;
    color: var(--text);
}

.meal-simple .hero-text p {
    margin: 0;
    font-size: 13.5px;
    color: var(--muted);
    line-height: 1.6;
    max-width: 520px;
}

.meal-simple .hero-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}

.meal-simple .hero-count {
    font-size: 12.5px;
    color: #6b5c45;
}

.meal-simple .hero-count strong {
    color: var(--text);
    font-size: 14px;
}

.meal-simple .hero-meta {
    font-size: 12px;
    color: #6b5c45;
}

.meal-simple .hero-meta strong {
    color: var(--text);
}

.meal-simple .hero-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
    margin-top: 2px;
}

.meal-simple .hero-actions .btn-primary {
    background: #3a3428;
    border-color: #3a3428;
}

.meal-simple .hero-actions .btn-primary:hover {
    background: #2a241c;
    border-color: #2a241c;
}

.meal-simple .hero-actions .btn-secondary {
    background: rgba(255, 255, 255, 0.7);
    border-color: #c4b49a;
    color: #5c4a32;
}

.meal-simple .hero-actions .btn-secondary:hover {
    background: #fff;
    border-color: #a8926e;
    color: #3a3428;
}

/* Filters */
.meal-simple .filters {
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

.meal-simple .filter-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.meal-simple .filter-item label {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
}

.meal-simple .filter-item input,
.meal-simple .filter-item select {
    height: 38px;
    padding: 0 12px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
    min-width: 160px;
    background: #fff;
}

.meal-simple .filter-item.filter-wide select {
    min-width: 260px;
}

.meal-simple .filter-item input:focus,
.meal-simple .filter-item select:focus {
    outline: none;
    border-color: var(--primary);
}

.meal-simple .filter-buttons {
    display: flex;
    gap: 8px;
}

/* Table */
.meal-simple .table-wrap {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow-x: auto;
}

.meal-simple table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
    min-width: 1000px;
}

.meal-simple th {
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

.meal-simple td {
    padding: 12px 14px;
    border-bottom: 1px solid #eee9dd;
    vertical-align: middle;
}

.meal-simple tbody tr:last-child td {
    border-bottom: none;
}

.meal-simple tbody tr:hover td {
    background: #faf9f5;
}

.meal-simple .emp-name {
    font-weight: 600;
}

.meal-simple .emp-id {
    font-size: 12px;
    color: var(--muted);
    margin-top: 2px;
}

.meal-simple .strong {
    font-weight: 700;
}

.meal-simple .status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.meal-simple .status.paid {
    background: #e4f3ea;
    color: #1a6b3c;
}

.meal-simple .status.unpaid {
    background: #fcebe8;
    color: #a33a2e;
}

.meal-simple .empty {
    text-align: center;
    padding: 40px 20px !important;
    color: var(--muted);
}

/* Note under table */
.meal-simple .pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
    font-size: 12.5px;
    color: var(--muted);
}

/* Print only (hidden on screen) */
.meal-simple .print-only,
.meal-simple .print-footer {
    display: none;
}

/* Responsive */
@media (max-width: 900px) {
    .meal-simple .hero-section {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px;
    }
    .meal-simple .hero-actions {
        align-items: flex-start;
    }
    .meal-simple .hero-buttons {
        justify-content: flex-start;
    }
}

@media (max-width: 768px) {
    .meal-simple .filters {
        flex-direction: column;
        align-items: stretch;
    }
    .meal-simple .filter-item input,
    .meal-simple .filter-item select,
    .meal-simple .filter-item.filter-wide select {
        width: 100%;
        min-width: 0;
    }
    .meal-simple .hero-text h2 {
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

    .meal-simple,
    .meal-simple * {
        visibility: visible;
    }

    .meal-simple {
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
    .meal-simple .top-bar,
    .meal-simple .hero-section,
    .meal-simple .filters,
    .meal-simple .pagination-bar {
        display: none !important;
    }

    /* ---- Print Header ---- */
    .meal-simple .print-only {
        display: block !important;
        margin-bottom: 14px;
        text-align: left;
    }

    .meal-simple .print-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding-bottom: 10px;
        border-bottom: 1px solid #d4cfc2;
    }

    .meal-simple .print-brand {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
        min-width: 0;
    }

    .meal-simple .print-school-logo {
        display: block;
        width: 62px;
        height: 62px;
        object-fit: contain;
        margin-right: 4px;
    }

    .meal-simple .print-school-identity {
        text-align: left;
        max-width: 430px;
    }

    .meal-simple .print-school-name {
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
        color: #263b35;
        margin-bottom: 3px;
    }

    .meal-simple .print-school-contact {
        font-size: 8.5px;
        line-height: 1.4;
        color: #5c6b64;
        margin-top: 2px;
    }

    .meal-simple .print-report-title {
        text-align: left;
        border-left: 1px solid #d4cfc2;
        padding-left: 16px;
        flex-shrink: 0;
    }

    .meal-simple .print-brand-name {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: -0.2px;
        line-height: 1.2;
        text-align: left;
    }

    .meal-simple .print-brand-sub {
        font-size: 11px;
        color: #555;
        margin-top: 2px;
        letter-spacing: 0.3px;
        text-align: left;
    }

    .meal-simple .print-meta {
        text-align: left;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 8px 18px;
        padding: 8px 0 2px;
    }

    .meal-simple .print-meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 9px;
    }

    .meal-simple .print-label {
        color: #666;
        font-weight: 500;
    }

    .meal-simple .print-value {
        color: #1a1a1a;
        font-weight: 600;
    }

    .meal-simple .print-divider {
        height: 2px;
        background: #344f43;
        margin: 8px 0 12px;
        width: 100%;
    }

    /* ---- Table Styling for Print ---- */
    .meal-simple .table-wrap {
        border: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-shadow: none !important;
    }

    .meal-simple table {
        width: 100%;
        min-width: 0;
        border-collapse: collapse;
        font-size: 9.5px;
        border: 1px solid #999;
    }

    .meal-simple th {
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

    .meal-simple td {
        padding: 6px 8px;
        border: 1px solid #ccc;
        vertical-align: middle;
        color: #222;
    }

    .meal-simple tr {
        break-inside: avoid;
    }

    .meal-simple tbody tr:nth-child(even) td {
        background: #f7f6f2 !important;
    }

    .meal-simple tbody tr:last-child td {
        border-bottom: 1px solid #999;
    }

    .meal-simple .emp-name {
        font-weight: 600;
        color: #1a1a1a;
        font-size: 9.5px;
    }

    .meal-simple .emp-id {
        font-size: 8.5px;
        color: #555;
        margin-top: 1px;
    }

    .meal-simple .status {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .meal-simple .status.paid {
        background: #e4f3ea !important;
        color: #1a6b3c !important;
        border-color: #a8d5b5;
    }

    .meal-simple .status.unpaid {
        background: #fcebe8 !important;
        color: #a33a2e !important;
        border-color: #e8b4ad;
    }

    .meal-simple .empty {
        text-align: center;
        padding: 28px 16px !important;
        color: #666;
        font-style: italic;
    }

    .meal-simple .print-footer {
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
    function setMealPrintDate() {
        const el = document.getElementById('meal-print-date');

        if (el) {
            el.textContent = new Date().toLocaleString();
        }
    }

    window.printMealReport = function () {
        setMealPrintDate();

        const logo = document.querySelector('.meal-simple .print-school-logo');

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