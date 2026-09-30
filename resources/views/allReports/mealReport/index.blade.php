@extends('layouts.app')

@section('title', 'Meal Reports')
@section('page-title', 'Meal Reports')

@section('content')

<style>
/* =========================================================
   MEAL REPORTS PAGE
   Same design system as Teacher Reports
========================================================= */

.meal-reports-page {
    width: 100% !important;
    max-width: 1600px !important;
    margin: 0 auto !important;
    padding: 28px !important;
    background: #f4f7fb !important;
    min-height: calc(100vh - 120px) !important;
    box-sizing: border-box !important;
}

/* HEADER */

.meal-reports-page .reports-header {
    width: 100% !important;
    background: linear-gradient(135deg, #147cf5, #6c63ff) !important;
    border-radius: 16px !important;
    padding: 28px 32px !important;
    margin-bottom: 25px !important;
    color: #ffffff !important;
    box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15) !important;
    box-sizing: border-box !important;
}

.meal-reports-page .reports-header h2 {
    margin: 0 0 8px 0 !important;
    color: #ffffff !important;
    font-size: 26px !important;
    font-weight: 700 !important;
    line-height: 1.3 !important;
}

.meal-reports-page .reports-header p {
    margin: 0 !important;
    color: rgba(255,255,255,0.9) !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
}

/* MAIN CARD */

.meal-reports-page .reports-main-card {
    width: 100% !important;
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 25px !important;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
    box-sizing: border-box !important;
}

.meal-reports-page .reports-main-card h3 {
    margin: 0 0 6px 0 !important;
    color: #1e293b !important;
    font-size: 19px !important;
    font-weight: 700 !important;
}

.meal-reports-page .reports-main-card > p {
    margin: 0 0 22px 0 !important;
    color: #64748b !important;
    font-size: 13px !important;
}

/* SUMMARY */

.meal-reports-page .summary-grid {
    width: 100% !important;
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 14px !important;
    margin-bottom: 25px !important;
}

.meal-reports-page .summary-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 18px !important;
    box-sizing: border-box !important;
}

.meal-reports-page .summary-label {
    margin: 0 0 7px 0 !important;
    color: #64748b !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.meal-reports-page .summary-value {
    margin: 0 !important;
    color: #1e293b !important;
    font-size: 22px !important;
    font-weight: 700 !important;
}

/* FILTERS */

.meal-reports-page .meal-report-filters {
    width: 100% !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 18px !important;
    margin-bottom: 25px !important;
    box-sizing: border-box !important;
}

.meal-reports-page .filter-row {
    width: 100% !important;
    display: grid !important;
    grid-template-columns: 2fr 1fr 1fr 1fr !important;
    gap: 14px !important;
    align-items: end !important;
}

.meal-reports-page .filter-group {
    min-width: 0 !important;
}

.meal-reports-page .filter-group label {
    display: block !important;
    margin-bottom: 6px !important;
    color: #334155 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.meal-reports-page .filter-group input,
.meal-reports-page .filter-group select {
    width: 100% !important;
    height: 40px !important;
    padding: 8px 11px !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    background: #ffffff !important;
    color: #334155 !important;
    font-size: 12px !important;
    outline: none !important;
    box-sizing: border-box !important;
}

.meal-reports-page .filter-group input:focus,
.meal-reports-page .filter-group select:focus {
    border-color: #147cf5 !important;
    box-shadow: 0 0 0 3px rgba(20, 124, 245, 0.10) !important;
}

.meal-reports-page .filter-actions {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    margin-top: 16px !important;
}

.meal-reports-page .filter-apply-btn,
.meal-reports-page .filter-reset-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    height: 40px !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
}

.meal-reports-page .filter-apply-btn {
    border: 0 !important;
    background: #147cf5 !important;
    color: #ffffff !important;
}

.meal-reports-page .filter-reset-btn {
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    color: #475569 !important;
}

/* TABLE CARD */

.meal-reports-page .report-table-card {
    width: 100% !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 18px !important;
    margin-bottom: 25px !important;
    box-sizing: border-box !important;
    overflow-x: auto !important;
}

.meal-reports-page .report-table-card h3 {
    margin: 0 0 15px 0 !important;
    color: #1e293b !important;
    font-size: 17px !important;
    font-weight: 700 !important;
}

.meal-reports-page .report-table {
    width: 100% !important;
    border-collapse: collapse !important;
}

.meal-reports-page .report-table th {
    padding: 11px 12px !important;
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    color: #334155 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-align: left !important;
    white-space: nowrap !important;
}

.meal-reports-page .report-table td {
    padding: 11px 12px !important;
    border-bottom: 1px solid #eef2f7 !important;
    color: #64748b !important;
    font-size: 12px !important;
}

.meal-reports-page .report-table td:first-child {
    color: #334155 !important;
    font-weight: 700 !important;
}

.meal-reports-page .status {
    display: inline-block !important;
    padding: 5px 10px !important;
    border-radius: 20px !important;
    font-size: 10px !important;
    font-weight: 700 !important;
}

.meal-reports-page .status-low {
    background: #fee2e2 !important;
    color: #b91c1c !important;
}

.meal-reports-page .status-ok {
    background: #dcfce7 !important;
    color: #15803d !important;
}

.meal-reports-page .status-in {
    background: #dcfce7 !important;
    color: #15803d !important;
}

.meal-reports-page .status-out {
    background: #fee2e2 !important;
    color: #b91c1c !important;
}

.meal-reports-page .reports-empty {
    text-align: center !important;
    padding: 45px 20px !important;
    color: #64748b !important;
    font-size: 13px !important;
}


/* REPORT ACTIONS */

.meal-reports-page .report-actions {
    width: 100% !important;
    display: flex !important;
    justify-content: flex-end !important;
    align-items: center !important;
    gap: 10px !important;
    margin-bottom: 20px !important;
}

.meal-reports-page .report-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    min-height: 40px !important;
    padding: 9px 15px !important;
    border: 0 !important;
    border-radius: 8px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    cursor: pointer !important;
    box-sizing: border-box !important;
}

.meal-reports-page .excel-btn {
    background: #16a34a !important;
    color: #ffffff !important;
}

.meal-reports-page .pdf-btn {
    background: #dc2626 !important;
    color: #ffffff !important;
}

.meal-reports-page .print-btn {
    background: #475569 !important;
    color: #ffffff !important;
}

@media print {
    .meal-reports-page .report-actions,
    .meal-reports-page .meal-report-filters {
        display: none !important;
    }

    .meal-reports-page {
        padding: 0 !important;
        background: #ffffff !important;
        max-width: none !important;
    }

    .meal-reports-page .reports-header,
    .meal-reports-page .reports-main-card,
    .meal-reports-page .report-table-card {
        box-shadow: none !important;
    }
}

/* RESPONSIVE */

@media (max-width: 1000px) {
    .meal-reports-page .summary-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    .meal-reports-page .filter-row {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 600px) {
    .meal-reports-page {
        padding: 15px !important;
    }

    .meal-reports-page .reports-header {
        padding: 22px !important;
    }

    .meal-reports-page .reports-header h2 {
        font-size: 22px !important;
    }

    .meal-reports-page .reports-main-card {
        padding: 18px !important;
    }

    .meal-reports-page .summary-grid,
    .meal-reports-page .filter-row {
        grid-template-columns: 1fr !important;
    }

    .meal-reports-page .filter-actions {
        flex-direction: column !important;
        align-items: stretch !important;
    }

    .meal-reports-page .filter-apply-btn,
    .meal-reports-page .filter-reset-btn {
        width: 100% !important;
    }
}
</style>

<div class="meal-reports-page">

    {{-- HEADER --}}
    <div class="reports-header">
        <h2>
            <i class="bi bi-egg-fried"></i>
            Meal Reports
        </h2>

        <p>
            View meal stock, usage and inventory records.
        </p>
    </div>

    <div class="report-actions">

    {{-- Excel --}}
    <a href="{{ route('admin.reports.meal.excel', request()->query()) }}"
       class="report-action-btn excel-btn">
        <i class="fas fa-file-excel"></i>
        Download Excel
    </a>

    {{-- PDF --}}
    <a href="{{ route('admin.reports.meal.pdf', request()->query()) }}"
       class="report-action-btn pdf-btn">
        <i class="fas fa-file-pdf"></i>
        Download PDF
    </a>

    {{-- Print --}}
    <button type="button"
            onclick="window.print()"
            class="report-action-btn print-btn">
        <i class="fas fa-print"></i>
        Print
    </button>

</div>

    {{-- MAIN CARD --}}
    <div class="reports-main-card">

        <h3>
            <i class="bi bi-box-seam"></i>
            Meal Inventory Reports
        </h3>

        <p>
            View current meal stock and stock transaction records.
        </p>

        {{-- SUMMARY --}}
        <div class="summary-grid">

            <div class="summary-card">
                <div class="summary-label">Total Items</div>
                <div class="summary-value">
                    {{ $totalItems }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Low Stock Items</div>
                <div class="summary-value">
                    {{ $lowStockItems }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Current Stock</div>
                <div class="summary-value">
                    {{ number_format($totalCurrentStock, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Total Stock In</div>
                <div class="summary-value">
                    {{ number_format($totalStockIn, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Total Stock Out</div>
                <div class="summary-value">
                    {{ number_format($totalStockOut, 2) }}
                </div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Stock In Amount</div>
                <div class="summary-value">
                    ₹{{ number_format($totalStockInAmount, 2) }}
                </div>
            </div>

        </div>

        {{-- FILTERS --}}
        <div class="meal-report-filters">

            <form method="GET"
                  action="{{ route('admin.reports.meal') }}">

                <div class="filter-row">

                    <div class="filter-group">
                        <label for="meal_item_id">Meal Item</label>

                        <select id="meal_item_id" name="meal_item_id">

                            <option value="">All Items</option>

                            @foreach($items as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ request('meal_item_id') == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->item_name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="transaction_type">
                            Transaction Type
                        </label>

                        <select id="transaction_type"
                                name="transaction_type">

                            <option value="">All Transactions</option>

                            <option value="stock_in"
                                {{ request('transaction_type') == 'stock_in' ? 'selected' : '' }}>
                                Stock In
                            </option>

                            <option value="stock_out"
                                {{ request('transaction_type') == 'stock_out' ? 'selected' : '' }}>
                                Stock Out
                            </option>

                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="from_date">From Date</label>

                        <input
                            type="date"
                            id="from_date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                        >
                    </div>

                    <div class="filter-group">
                        <label for="to_date">To Date</label>

                        <input
                            type="date"
                            id="to_date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                        >
                    </div>

                </div>

                <div class="filter-actions">

                    <button type="submit"
                            class="filter-apply-btn">

                        <i class="bi bi-funnel"></i>
                        Apply Filters

                    </button>

                    <a
                        href="{{ route('admin.reports.meal') }}"
                        class="filter-reset-btn"
                    >
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- CURRENT STOCK --}}
        <div class="report-table-card">

            <h3>
                <i class="bi bi-boxes"></i>
                Current Meal Stock
            </h3>

            <table class="report-table">

                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Unit</th>
                        <th>Minimum Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($items as $item)

                        <tr>

                            <td>
                                {{ $item->item_name }}
                            </td>

                            <td>
                                {{ $item->category ?? '-' }}
                            </td>

                            <td>
                                {{ number_format($item->current_stock, 2) }}
                            </td>

                            <td>
                                {{ $item->unit }}
                            </td>

                            <td>
                                {{ number_format($item->minimum_stock, 2) }}
                            </td>

                            <td>

                                @if($item->current_stock <= $item->minimum_stock)

                                    <span class="status status-low">
                                        Low Stock
                                    </span>

                                @else

                                    <span class="status status-ok">
                                        Available
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6">
                                <div class="reports-empty">
                                    No meal items found.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- STOCK TRANSACTIONS --}}
        <div class="report-table-card">

            <h3>
                <i class="bi bi-arrow-left-right"></i>
                Stock Transactions
            </h3>

            <table class="report-table">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Supplier</th>
                        <th>Reason</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($transactions as $transaction)

                        <tr>

                            <td>
                                {{ optional($transaction->transaction_date)->format('d M Y') }}
                            </td>

                            <td>
                                {{ $transaction->mealItem->item_name ?? '-' }}
                            </td>

                            <td>

                                @if($transaction->transaction_type === 'stock_in')

                                    <span class="status status-in">
                                        Stock In
                                    </span>

                                @else

                                    <span class="status status-out">
                                        Stock Out
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ number_format($transaction->quantity, 2) }}
                            </td>

                            <td>
                                {{ $transaction->unit }}
                            </td>

                            <td>
                                ₹{{ number_format($transaction->rate ?? 0, 2) }}
                            </td>

                            <td>
                                ₹{{ number_format($transaction->total_amount ?? 0, 2) }}
                            </td>

                            <td>
                                {{ $transaction->supplier ?? '-' }}
                            </td>

                            <td>
                                {{ $transaction->reason ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9">
                                <div class="reports-empty">
                                    No stock transactions found.
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection