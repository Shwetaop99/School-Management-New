@extends('layouts.app')

@section('title', 'Library Reports')
@section('page-title', 'Library Reports')

@section('content')

<style>
/* =========================================================
   LIBRARY REPORTS PAGE
   Matching Teacher Reports UI
========================================================= */

.library-reports-page {
    width: 100% !important;
    max-width: 1600px !important;
    margin: 0 auto !important;
    padding: 28px !important;
    background: #f4f7fb !important;
    min-height: calc(100vh - 120px) !important;
    box-sizing: border-box !important;
}


/* =========================================================
   HEADER
========================================================= */

.library-reports-page .reports-header {
    width: 100% !important;
    background: linear-gradient(135deg, #147cf5, #6c63ff) !important;
    border-radius: 16px !important;
    padding: 28px 32px !important;
    margin-bottom: 25px !important;
    color: #ffffff !important;
    box-shadow: 0 8px 25px rgba(20, 124, 245, 0.15) !important;
    box-sizing: border-box !important;

    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    gap: 20px !important;
}

.library-reports-page .reports-header h2 {
    margin: 0 0 8px 0 !important;
    color: #ffffff !important;
    font-size: 26px !important;
    font-weight: 700 !important;
}

.library-reports-page .reports-header p {
    margin: 0 !important;
    color: rgba(255,255,255,0.9) !important;
    font-size: 14px !important;
}


/* =========================================================
   HEADER ACTION BUTTONS
========================================================= */

.library-reports-page .header-actions {
    display: flex !important;
    gap: 8px !important;
    flex-wrap: wrap !important;
}

.library-reports-page .header-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;

    padding: 10px 15px !important;
    border: 0 !important;
    border-radius: 8px !important;

    color: #ffffff !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    text-decoration: none !important;

    cursor: pointer !important;
}

.library-reports-page .pdf-btn {
    background: #dc2626 !important;
}

.library-reports-page .excel-btn {
    background: #16a34a !important;
}

.library-reports-page .print-btn {
    background: #0f766e !important;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.library-reports-page .summary-grid {
    display: grid !important;
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    gap: 18px !important;
    margin-bottom: 18px !important;
}

.library-reports-page .summary-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 20px !important;

    display: flex !important;
    align-items: center !important;
    gap: 14px !important;

    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.05) !important;
}

.library-reports-page .summary-icon {
    width: 50px !important;
    height: 50px !important;
    min-width: 50px !important;

    border-radius: 13px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    font-size: 19px !important;
}

.library-reports-page .icon-blue {
    background: #eaf3ff !important;
    color: #147cf5 !important;
}

.library-reports-page .icon-purple {
    background: #f1efff !important;
    color: #6c63ff !important;
}

.library-reports-page .icon-green {
    background: #ecfdf5 !important;
    color: #059669 !important;
}

.library-reports-page .icon-orange {
    background: #fff7ed !important;
    color: #ea580c !important;
}

.library-reports-page .summary-label {
    margin: 0 0 4px 0 !important;
    color: #64748b !important;
    font-size: 12px !important;
}

.library-reports-page .summary-value {
    margin: 0 !important;
    color: #1e293b !important;
    font-size: 24px !important;
    font-weight: 700 !important;
}


/* =========================================================
   SECONDARY SUMMARY
========================================================= */

.library-reports-page .mini-grid {
    display: grid !important;
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    gap: 18px !important;
    margin-bottom: 25px !important;
}

.library-reports-page .mini-card {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    padding: 17px 19px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;

    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04) !important;
}

.library-reports-page .mini-label {
    color: #64748b !important;
    font-size: 12px !important;
    margin-bottom: 3px !important;
}

.library-reports-page .mini-value {
    color: #1e293b !important;
    font-size: 20px !important;
    font-weight: 700 !important;
}

.library-reports-page .mini-card > i {
    color: #94a3b8 !important;
    font-size: 20px !important;
}


/* =========================================================
   MAIN CARD
========================================================= */

.library-reports-page .reports-main-card {
    width: 100% !important;
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 25px !important;
    margin-bottom: 25px !important;

    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
    box-sizing: border-box !important;
}

.library-reports-page .reports-main-card h3 {
    margin: 0 0 6px 0 !important;
    color: #1e293b !important;
    font-size: 19px !important;
    font-weight: 700 !important;
}

.library-reports-page .reports-main-card > p {
    margin: 0 0 22px 0 !important;
    color: #64748b !important;
    font-size: 13px !important;
}


/* =========================================================
   FILTERS
========================================================= */

.library-reports-page .filter-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    padding: 25px !important;
    margin-bottom: 25px !important;

    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
}

.library-reports-page .filter-title {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    margin-bottom: 20px !important;
}

.library-reports-page .filter-title-icon {
    width: 40px !important;
    height: 40px !important;
    border-radius: 10px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    background: #eaf3ff !important;
    color: #147cf5 !important;
}

.library-reports-page .filter-title h3 {
    margin: 0 !important;
    color: #1e293b !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.library-reports-page .filter-label {
    display: block !important;
    margin-bottom: 7px !important;

    color: #334155 !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.library-reports-page .filter-control {
    width: 100% !important;
    height: 42px !important;

    border: 1px solid #d9e0ea !important;
    border-radius: 9px !important;

    padding: 0 12px !important;

    font-size: 13px !important;
    color: #334155 !important;
    background: #ffffff !important;

    outline: none !important;
}

.library-reports-page .filter-control:focus {
    border-color: #147cf5 !important;
    box-shadow: 0 0 0 3px rgba(20,124,245,0.08) !important;
}

.library-reports-page .filter-buttons {
    display: flex !important;
    align-items: end !important;
    gap: 8px !important;
    height: 42px !important;
}

.library-reports-page .filter-btn {
    height: 42px !important;
    padding: 0 16px !important;
    border: 0 !important;
    border-radius: 9px !important;

    background: #147cf5 !important;
    color: #ffffff !important;

    font-size: 12px !important;
    font-weight: 700 !important;
}

.library-reports-page .reset-btn {
    height: 42px !important;
    padding: 0 16px !important;

    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    border: 1px solid #d9e0ea !important;
    border-radius: 9px !important;

    background: #ffffff !important;
    color: #475569 !important;

    text-decoration: none !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}


/* =========================================================
   TABLE
========================================================= */

.library-reports-page .table-card {
    background: #ffffff !important;
    border-radius: 16px !important;
    overflow: hidden !important;

    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
}

.library-reports-page .table-card-header {
    padding: 22px 25px !important;
    border-bottom: 1px solid #e2e8f0 !important;

    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 15px !important;
}

.library-reports-page .table-card-header h3 {
    margin: 0 0 5px 0 !important;
    color: #1e293b !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.library-reports-page .table-card-header p {
    margin: 0 !important;
    color: #64748b !important;
    font-size: 12px !important;
}

.library-reports-page .record-count {
    background: #eaf3ff !important;
    color: #147cf5 !important;

    padding: 7px 12px !important;
    border-radius: 20px !important;

    font-size: 11px !important;
    font-weight: 700 !important;
}

.library-reports-page .library-table {
    width: 100% !important;
    border-collapse: collapse !important;
}

.library-reports-page .library-table th {
    background: #f8fafc !important;
    color: #64748b !important;

    padding: 14px 18px !important;

    font-size: 11px !important;
    font-weight: 700 !important;

    text-transform: uppercase !important;
    white-space: nowrap !important;

    border-bottom: 1px solid #e2e8f0 !important;
}

.library-reports-page .library-table td {
    padding: 15px 18px !important;

    color: #334155 !important;
    font-size: 13px !important;

    border-bottom: 1px solid #eef2f7 !important;
    vertical-align: middle !important;
}

.library-reports-page .library-table tbody tr:hover {
    background: #fafbff !important;
}

.library-reports-page .book-title {
    color: #1e293b !important;
    font-weight: 700 !important;
}

.library-reports-page .book-author {
    color: #64748b !important;
    font-size: 11px !important;
    margin-top: 3px !important;
}


/* =========================================================
   BADGES
========================================================= */

.library-reports-page .status-badge {
    display: inline-block !important;

    padding: 5px 10px !important;
    border-radius: 20px !important;

    font-size: 11px !important;
    font-weight: 700 !important;
}

.library-reports-page .status-issued {
    background: #eaf3ff !important;
    color: #147cf5 !important;
}

.library-reports-page .status-returned {
    background: #ecfdf5 !important;
    color: #059669 !important;
}

.library-reports-page .status-overdue {
    background: #fef2f2 !important;
    color: #dc2626 !important;
}

.library-reports-page .fine {
    color: #dc2626 !important;
    font-weight: 700 !important;
}

.library-reports-page .stock-badge {
    display: inline-block !important;

    min-width: 38px !important;
    padding: 5px 9px !important;

    text-align: center !important;
    border-radius: 8px !important;

    font-size: 11px !important;
    font-weight: 700 !important;
}

.library-reports-page .available-stock {
    background: #ecfdf5 !important;
    color: #059669 !important;
}

.library-reports-page .issued-stock {
    background: #fff7ed !important;
    color: #ea580c !important;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.library-reports-page .empty-state {
    text-align: center !important;
    padding: 55px 20px !important;
}

.library-reports-page .empty-state i {
    display: block !important;
    margin-bottom: 12px !important;
    color: #94a3b8 !important;
    font-size: 42px !important;
}

.library-reports-page .empty-state h4 {
    margin: 0 0 6px 0 !important;
    color: #334155 !important;
    font-size: 16px !important;
}

.library-reports-page .empty-state p {
    margin: 0 !important;
    color: #64748b !important;
    font-size: 13px !important;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1000px) {

    .library-reports-page .summary-grid,
    .library-reports-page .mini-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .library-reports-page .reports-header {
        align-items: flex-start !important;
        flex-direction: column !important;
    }

    .library-reports-page .header-actions {
        width: 100% !important;
    }
}


@media (max-width: 600px) {

    .library-reports-page {
        padding: 15px !important;
    }

    .library-reports-page .reports-header {
        padding: 22px !important;
    }

    .library-reports-page .reports-header h2 {
        font-size: 22px !important;
    }

    .library-reports-page .summary-grid,
    .library-reports-page .mini-grid {
        grid-template-columns: 1fr !important;
    }

    .library-reports-page .table-card-header {
        align-items: flex-start !important;
        flex-direction: column !important;
    }
}


/* =========================================================
   PRINT
========================================================= */

@media print {

    .no-print,
    .header-actions,
    .filter-card,
    .sidebar,
    .top-header {
        display: none !important;
    }

    .library-reports-page {
        padding: 0 !important;
        background: #ffffff !important;
    }

    .library-reports-page .reports-header {
        background: none !important;
        color: #000 !important;
        box-shadow: none !important;
        padding: 0 0 20px !important;
    }

    .library-reports-page .reports-header h2,
    .library-reports-page .reports-header p {
        color: #000 !important;
    }

    .library-reports-page .summary-card,
    .library-reports-page .mini-card,
    .library-reports-page .table-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>


<div class="library-reports-page">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="reports-header">

        <div>

            <h2>
                <i class="bi bi-book-half"></i>
                Library Reports
            </h2>

            <p>
                Overview of books, transactions, returns and fines.
            </p>

        </div>


        <div class="header-actions">

            <a
                href="{{ route('admin.library.reports.pdf', request()->query()) }}"
                class="header-action-btn pdf-btn"
            >
                <i class="bi bi-file-earmark-pdf"></i>
                PDF
            </a>


            <a
                href="{{ route('admin.library.reports.excel', request()->query()) }}"
                class="header-action-btn excel-btn"
            >
                <i class="bi bi-file-earmark-excel"></i>
                Excel
            </a>


            <button
                type="button"
                onclick="window.print()"
                class="header-action-btn print-btn"
            >
                <i class="bi bi-printer"></i>
                Print
            </button>

        </div>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-icon icon-blue">
                <i class="bi bi-book"></i>
            </div>

            <div>
                <div class="summary-label">
                    Total Books
                </div>

                <div class="summary-value">
                    {{ $totalBooks }}
                </div>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon icon-purple">
                <i class="bi bi-layers"></i>
            </div>

            <div>
                <div class="summary-label">
                    Total Book Stock
                </div>

                <div class="summary-value">
                    {{ $totalCopies }}
                </div>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon icon-green">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                <div class="summary-label">
                    Available Copies
                </div>

                <div class="summary-value">
                    {{ $availableCopies }}
                </div>
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-icon icon-orange">
                <i class="bi bi-bookmark-check"></i>
            </div>

            <div>
                <div class="summary-label">
                    Currently Issued
                </div>

                <div class="summary-value">
                    {{ $issuedCopies }}
                </div>
            </div>

        </div>

    </div>


    {{-- SECONDARY SUMMARY --}}

    <div class="mini-grid">

        <div class="mini-card">

            <div>
                <div class="mini-label">
                    Returned Books
                </div>

                <div class="mini-value">
                    {{ $returnedBooks }}
                </div>
            </div>

            <i class="bi bi-arrow-return-left"></i>

        </div>


        <div class="mini-card">

            <div>
                <div class="mini-label">
                    Overdue Books
                </div>

                <div class="mini-value">
                    {{ $overdueBooks }}
                </div>
            </div>

            <i class="bi bi-clock"></i>

        </div>


        <div class="mini-card">

            <div>
                <div class="mini-label">
                    Total Fines
                </div>

                <div class="mini-value">
                    ₹{{ number_format($totalFines, 2) }}
                </div>
            </div>

            <i class="bi bi-currency-rupee"></i>

        </div>


        <div class="mini-card">

            <div>
                <div class="mini-label">
                    Pending Fines
                </div>

                <div class="mini-value">
                    ₹{{ number_format($pendingFines, 2) }}
                </div>
            </div>

            <i class="bi bi-exclamation-circle"></i>

        </div>

    </div>


    {{-- =====================================================
         BOOK STOCK
    ====================================================== --}}

    <div class="table-card"
         style="margin-bottom:25px;">

        <div class="table-card-header">

            <div>

                <h3>
                    Book Stock
                </h3>

                <p>
                    Individual stock details for every book.
                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="library-table">

                <thead>

                    <tr>

                        <th>Book</th>
                        <th>Author</th>
                        <th>Total Copies</th>
                        <th>Available</th>
                        <th>Issued</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($bookStock as $book)

                        @php
                            $issued =
                                $book->quantity -
                                $book->available_quantity;
                        @endphp

                        <tr>

                            <td>

                                <div class="book-title">
                                    {{ $book->title }}
                                </div>

                                @if($book->isbn)

                                    <div class="book-author">
                                        ISBN: {{ $book->isbn }}
                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ $book->author ?? '-' }}
                            </td>


                            <td>
                                {{ $book->quantity }}
                            </td>


                            <td>

                                <span class="stock-badge available-stock">
                                    {{ $book->available_quantity }}
                                </span>

                            </td>


                            <td>

                                <span class="stock-badge issued-stock">
                                    {{ $issued }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <i class="bi bi-bookshelf"></i>

                                    <h4>
                                        No Books Found
                                    </h4>

                                    <p>
                                        No book stock is currently available.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if($bookStock->count())

                    <tfoot>

                        <tr>

                            <td colspan="2">
                                <strong>Total Book Stock</strong>
                            </td>

                            <td>
                                <strong>
                                    {{ $bookStock->sum('quantity') }}
                                </strong>
                            </td>

                            <td>

                                <span class="stock-badge available-stock">
                                    {{ $bookStock->sum('available_quantity') }}
                                </span>

                            </td>

                            <td>

                                <span class="stock-badge issued-stock">
                                    {{ $bookStock->sum('quantity') - $bookStock->sum('available_quantity') }}
                                </span>

                            </td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>


    {{-- =====================================================
         FILTERS
    ====================================================== --}}

    <div class="filter-card no-print">

        <div class="filter-title">

            <div class="filter-title-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <h3>
                Report Filters
            </h3>

        </div>


        <form
            method="GET"
            action="{{ route('admin.library.reports.index') }}"
        >

            <div class="row g-3">

                {{-- From Date --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="from_date"
                        class="filter-control"
                        value="{{ $fromDate }}"
                    >

                </div>


                {{-- To Date --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="to_date"
                        class="filter-control"
                        value="{{ $toDate }}"
                    >

                </div>


                {{-- Book --}}
                <div class="col-lg-3 col-md-6">

                    <label class="filter-label">
                        Book
                    </label>

                    <select
                        name="book_id"
                        class="filter-control"
                    >

                        <option value="">
                            All Books
                        </option>

                        @foreach($books as $book)

                            <option
                                value="{{ $book->id }}"
                                {{ $bookId == $book->id ? 'selected' : '' }}
                            >
                                {{ $book->title }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Student --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Student ID
                    </label>

                    <input
                        type="text"
                        name="student_id"
                        class="filter-control"
                        placeholder="Student ID"
                        value="{{ $studentId }}"
                    >

                </div>


                {{-- Status --}}
                <div class="col-lg-2 col-md-6">

                    <label class="filter-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="filter-control"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="Issued"
                            {{ $status === 'Issued' ? 'selected' : '' }}
                        >
                            Issued
                        </option>

                        <option
                            value="Returned"
                            {{ $status === 'Returned' ? 'selected' : '' }}
                        >
                            Returned
                        </option>

                        <option
                            value="Overdue"
                            {{ $status === 'Overdue' ? 'selected' : '' }}
                        >
                            Overdue
                        </option>

                    </select>

                </div>


                {{-- Buttons --}}
                <div class="col-lg-1 col-md-6">

                    <label class="filter-label">
                        &nbsp;
                    </label>

                    <div class="filter-buttons">

                        <button
                            type="submit"
                            class="filter-btn"
                        >
                            <i class="bi bi-search"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.library.reports.index') }}"
                            class="reset-btn"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
         TRANSACTIONS
    ====================================================== --}}

    <div class="table-card">

        <div class="table-card-header">

            <div>

                <h3>
                    Library Transactions
                </h3>

                <p>
                    Complete issue and return transaction history.
                </p>

            </div>


            <span class="record-count">
                {{ $transactions->total() }} Records
            </span>

        </div>


        <div class="table-responsive">

            <table class="library-table">

                <thead>

                    <tr>

                        <th>Book</th>
                        <th>Student ID</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Fine</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($transactions as $transaction)

                        <tr>

                            <td>

                                <div class="book-title">
                                    {{ $transaction->book->title ?? 'Deleted Book' }}
                                </div>

                                @if($transaction->book)

                                    <div class="book-author">
                                        {{ $transaction->book->author }}
                                    </div>

                                @endif

                            </td>


                            <td>
                                {{ $transaction->student_id }}
                            </td>


                            <td>
                                {{ $transaction->issue_date
                                    ? $transaction->issue_date->format('d M Y')
                                    : '—' }}
                            </td>


                            <td>
                                {{ $transaction->due_date
                                    ? $transaction->due_date->format('d M Y')
                                    : '—' }}
                            </td>


                            <td>
                                {{ $transaction->return_date
                                    ? $transaction->return_date->format('d M Y')
                                    : '—' }}
                            </td>


                            <td>

                                @if($transaction->status === 'Returned')

                                    <span class="status-badge status-returned">
                                        Returned
                                    </span>

                                @elseif(
                                    $transaction->status === 'Overdue'
                                    ||
                                    (
                                        !$transaction->return_date &&
                                        $transaction->due_date &&
                                        $transaction->due_date->isPast()
                                    )
                                )

                                    <span class="status-badge status-overdue">
                                        Overdue
                                    </span>

                                @else

                                    <span class="status-badge status-issued">
                                        Issued
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($transaction->fine > 0)

                                    <span class="fine">
                                        ₹{{ number_format($transaction->fine, 2) }}
                                    </span>

                                @else

                                    <span>
                                        ₹0.00
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7">

                                <div class="empty-state">

                                    <i class="bi bi-journal-x"></i>

                                    <h4>
                                        No Transactions Found
                                    </h4>

                                    <p>
                                        Try changing your report filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($transactions->hasPages())

            <div class="px-4 py-3 border-top no-print">

                {{ $transactions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection