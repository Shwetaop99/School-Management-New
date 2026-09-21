@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="page-title mb-1">Library Reports</h2>

            <p class="page-subtitle mb-0">
                Overview of books, transactions, returns and fines
            </p>
        </div>

        <div class="d-flex gap-2 no-print">

    {{-- PDF --}}
    <a href="{{ route('admin.library.reports.pdf', request()->query()) }}"
       class="btn btn-outline-danger">

        <i class="fas fa-file-pdf me-2"></i>
        PDF

    </a>


    {{-- Excel --}}
    <a href="{{ route('admin.library.reports.excel', request()->query()) }}"
       class="btn btn-outline-success">

        <i class="fas fa-file-excel me-2"></i>
        Excel

    </a>


    {{-- Print --}}
    <button type="button"
            onclick="window.print()"
            class="btn btn-outline-primary">

        <i class="fas fa-print me-2"></i>
        Print

    </button>

</div>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-4 mb-4">

        {{-- Total Books --}}
        <div class="col-xl-3 col-md-6">

            <div class="report-card">

                <div class="report-icon books">
                    <i class="fas fa-book"></i>
                </div>

                <div>
                    <span>Total Books</span>
                    <h3>{{ $totalBooks }}</h3>
                </div>

            </div>

        </div>


        {{-- Total Copies --}}
        <div class="col-xl-3 col-md-6">

            <div class="report-card">

                <div class="report-icon copies">
                    <i class="fas fa-layer-group"></i>
                </div>

                <div>
                    <span>Total Book Stock</span>
                    <h3>{{ $totalCopies }}</h3>
                </div>

            </div>

        </div>


        {{-- Available --}}
        <div class="col-xl-3 col-md-6">

            <div class="report-card">

                <div class="report-icon available">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div>
                    <span>Available Copies</span>
                    <h3>{{ $availableCopies }}</h3>
                </div>

            </div>

        </div>


        {{-- Issued --}}
        <div class="col-xl-3 col-md-6">

            <div class="report-card">

                <div class="report-icon issued">
                    <i class="fas fa-book-reader"></i>
                </div>

                <div>
                    <span>Currently Issued</span>
                    <h3>{{ $issuedCopies }}</h3>
                </div>

            </div>

        </div>

    </div>


    {{-- Second Summary Row --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="mini-card">

                <div>
                    <span>Returned Books</span>
                    <h4>{{ $returnedBooks }}</h4>
                </div>

                <i class="fas fa-undo-alt"></i>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-card">

                <div>
                    <span>Overdue Books</span>
                    <h4>{{ $overdueBooks }}</h4>
                </div>

                <i class="fas fa-clock"></i>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-card">

                <div>
                    <span>Total Fines</span>
                    <h4>₹{{ number_format($totalFines, 2) }}</h4>
                </div>

                <i class="fas fa-rupee-sign"></i>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="mini-card">

                <div>
                    <span>Pending Fines</span>
                    <h4>₹{{ number_format($pendingFines, 2) }}</h4>
                </div>

                <i class="fas fa-exclamation-circle"></i>

            </div>

        </div>

    </div>


    {{-- Book Stock --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-header bg-white border-0 px-4 pt-4 pb-3">

        <div>
            <h5 class="section-title mb-1">
                <i class="fas fa-boxes text-primary me-2"></i>
                Book Stock
            </h5>

            <p class="text-muted small mb-0">
                Individual stock details for every book
            </p>
        </div>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="px-4">Book</th>
                        <th>Author</th>
                        <th>Total Copies</th>
                        <th>Available</th>
                        <th>Issued</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($bookStock as $book)

                        @php
                            $issued = $book->quantity - $book->available_quantity;
                        @endphp

                        <tr>

                            <td class="px-4">
                                <div class="fw-semibold">
                                    {{ $book->title }}
                                </div>

                                @if($book->isbn)
                                    <div class="text-muted small">
                                        ISBN: {{ $book->isbn }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $book->author }}
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    {{ $book->quantity }}
                                </span>
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
                            <td colspan="5"
                                class="text-center py-4 text-muted">
                                No books found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

                <tfoot>
                    <tr class="stock-total-row">
                        <td class="px-4 fw-bold" colspan="2">
                            Total Book Stock
                        </td>
                        <td class="fw-bold">
                            {{ $bookStock->sum('quantity') }}
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

            </table>

        </div>

    </div>

</div>


    {{-- Filters --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 no-print">

        <div class="card-header bg-white border-0 px-4 pt-4">

            <h5 class="section-title mb-1">
                <i class="fas fa-filter text-primary me-2"></i>
                Report Filters
            </h5>

            <p class="text-muted small mb-0">
                Filter library transactions by date, book, student or status
            </p>

        </div>


        <div class="card-body px-4 pb-4">

            <form method="GET"
                  action="{{ route('admin.library.reports.index') }}">

                <div class="row g-3">

                    {{-- From Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ $fromDate }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ $toDate }}">

                    </div>


                    {{-- Book --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Book
                        </label>

                        <select name="book_id"
                                class="form-select">

                            <option value="">
                                All Books
                            </option>

                            @foreach($books as $book)

                                <option value="{{ $book->id }}"
                                    {{ $bookId == $book->id ? 'selected' : '' }}>

                                    {{ $book->title }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Student ID --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Student ID
                        </label>

                        <input type="text"
                               name="student_id"
                               class="form-control"
                               placeholder="Student ID"
                               value="{{ $studentId }}">

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Issued"
                                {{ $status === 'Issued' ? 'selected' : '' }}>
                                Issued
                            </option>

                            <option value="Returned"
                                {{ $status === 'Returned' ? 'selected' : '' }}>
                                Returned
                            </option>

                            <option value="Overdue"
                                {{ $status === 'Overdue' ? 'selected' : '' }}>
                                Overdue
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-1 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary filter-btn">

                            <i class="fas fa-search"></i>

                        </button>

                        <a href="{{ route('admin.library.reports.index') }}"
                           class="btn btn-light border filter-btn">

                            <i class="fas fa-redo"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Transactions --}}
    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="section-title mb-1">
                        Library Transactions
                    </h5>

                    <p class="text-muted small mb-0">
                        Complete issue and return transaction history
                    </p>

                </div>

                <span class="transaction-count">
                    {{ $transactions->total() }} Records
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($transactions->count())

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Book
                                </th>

                                <th>
                                    Student ID
                                </th>

                                <th>
                                    Issue Date
                                </th>

                                <th>
                                    Due Date
                                </th>

                                <th>
                                    Return Date
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Fine
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($transactions as $transaction)

                                <tr>

                                    {{-- Book --}}
                                    <td class="px-4">

                                        <div class="fw-semibold">
                                            {{ $transaction->book->title ?? 'Deleted Book' }}
                                        </div>

                                        @if($transaction->book)

                                            <div class="text-muted small">
                                                {{ $transaction->book->author }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- Student --}}
                                    <td>
                                        {{ $transaction->student_id }}
                                    </td>


                                    {{-- Issue Date --}}
                                    <td>

                                        {{ $transaction->issue_date
                                            ? $transaction->issue_date->format('d M Y')
                                            : '—' }}

                                    </td>


                                    {{-- Due Date --}}
                                    <td>

                                        {{ $transaction->due_date
                                            ? $transaction->due_date->format('d M Y')
                                            : '—' }}

                                    </td>


                                    {{-- Return Date --}}
                                    <td>

                                        {{ $transaction->return_date
                                            ? $transaction->return_date->format('d M Y')
                                            : '—' }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if(
                                            $transaction->status === 'Returned'
                                        )

                                            <span class="status-badge returned">
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

                                            <span class="status-badge overdue">
                                                Overdue
                                            </span>

                                        @else

                                            <span class="status-badge issued">
                                                Issued
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Fine --}}
                                    <td>

                                        @if($transaction->fine > 0)

                                            <span class="fine-amount">
                                                ₹{{ number_format($transaction->fine, 2) }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                ₹0.00
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($transactions->hasPages())

                    <div class="px-4 py-3 border-top no-print">

                        {{ $transactions->links() }}

                    </div>

                @endif


            @else

                <div class="text-center py-5">

                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>

                    <h5 class="fw-semibold">
                        No Transactions Found
                    </h5>

                    <p class="text-muted mb-0">
                        Try changing your report filters.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


<style>

    /* Page */

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #12233f;
    }

    .page-subtitle {
        font-size: 14px;
        color: #718096;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
    }


    /* Main Report Cards */

    .report-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 100px;
        box-shadow: 0 4px 16px rgba(0,0,0,.05);
    }

    .report-card span,
    .mini-card span {
        display: block;
        color: #718096;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .report-card h3 {
        margin: 0;
        font-size: 25px;
        font-weight: 700;
        color: #1f2937;
    }


    .report-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
    }

    .report-icon.books {
        background: #e8f0ff;
        color: #0d6efd;
    }

    .report-icon.copies {
        background: #f0eaff;
        color: #6f42c1;
    }

    .report-icon.available {
        background: #e8f7ef;
        color: #198754;
    }

    .report-icon.issued {
        background: #fff3df;
        color: #d97706;
    }


    /* Mini Cards */

    .mini-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 82px;
        box-shadow: 0 4px 16px rgba(0,0,0,.04);
    }

    .mini-card h4 {
        margin: 0;
        font-size: 21px;
        font-weight: 700;
    }

    .mini-card > i {
        font-size: 21px;
        color: #9aa5b1;
    }


    /* Form */

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        font-size: 14px;
        min-height: 40px;
    }

    .filter-btn {
        height: 40px;
        min-width: 40px;
    }


    /* Transaction Count */

    .transaction-count {
        background: #eef4ff;
        color: #0d6efd;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }


    /* Table */

    .table {
        font-size: 14px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        white-space: nowrap;
    }

    .table td {
        font-size: 14px;
        color: #374151;
    }

    .table > :not(caption) > * > * {
        padding-top: 13px;
        padding-bottom: 13px;
    }


    /* Status */

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge.returned {
        background: #e8f7ef;
        color: #198754;
    }

    .status-badge.issued {
        background: #e8f0ff;
        color: #0d6efd;
    }

    .status-badge.overdue {
        background: #fff0f0;
        color: #dc3545;
    }

    .fine-amount {
        color: #dc3545;
        font-weight: 600;
    }


    .stock-total-row td {
        background: #f8faff;
        border-top: 2px solid #e5eaf2;
    }

    /* Print */

    @media print {

        .no-print,
        .sidebar,
        .top-header {
            display: none !important;
        }

        body {
            background: white !important;
        }

        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }

        .card,
        .report-card,
        .mini-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

    }


    /* Mobile */

    @media (max-width: 576px) {

        .page-title {
            font-size: 24px;
        }

        .report-card {
            padding: 16px;
        }

        .report-card h3 {
            font-size: 22px;
        }

    }

    .stock-badge {
        display: inline-block;
        min-width: 38px;
        padding: 5px 9px;
        text-align: center;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .available-stock {
        background: #e8f7ef;
        color: #198754;
    }

    .issued-stock {
        background: #fff3df;
        color: #d97706;
    }

</style>

@endsection