@extends('layouts.app')

@section('title', 'Library Reports | Admin')
@section('page-title', 'Library Reports')

@section('content')

@php
    $fmt = function ($date) {
        return $date ? \Carbon\Carbon::parse($date)->format('d M Y') : '—';
    };

    $stockTotal     = $bookStock->sum('quantity');
    $stockAvailable = $bookStock->sum('available_quantity');
    $stockIssued    = $stockTotal - $stockAvailable;

    $hasFilters = request()->hasAny(['from_date', 'to_date', 'book_id', 'student_id', 'status'])
        && collect(request()->only(['from_date', 'to_date', 'book_id', 'student_id', 'status']))->filter()->isNotEmpty();
@endphp

<div class="reports-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY HERO --}}
        <section class="reports-hero">
            <div class="reports-hero-content">
                <div class="reports-hero-icon">
                    <i class="fas fa-chart-column"></i>
                </div>

                <div class="reports-hero-text">
                    <div class="reports-breadcrumb">
                        LIBRARY <span>/</span> REPORTS
                    </div>

                    <h2>Library Reports</h2>

                    <p>
                        Overview of books, transactions, returns and fines
                        for the school library.
                    </p>

                    <div class="reports-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Stock, Circulation &amp; Fine Summary</span>
                    </div>
                </div>
            </div>

            <div class="reports-hero-actions no-print">
                <div class="hero-handwritten">Measure. Review. Improve.</div>

                <p>
                    Export the current report or print it
                    for your records.
                </p>

                <div class="hero-tools">
                    <a href="{{ route('admin.library.reports.pdf', request()->query()) }}"
                       class="hero-tool-btn">
                        <i class="fas fa-file-pdf"></i>
                        PDF
                    </a>

                    <a href="{{ route('admin.library.reports.excel', request()->query()) }}"
                       class="hero-tool-btn">
                        <i class="fas fa-file-excel"></i>
                        Excel
                    </a>

                    <button type="button"
                            onclick="window.print()"
                            class="hero-tool-btn">
                        <i class="fas fa-print"></i>
                        Print
                    </button>
                </div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="reports-kpis">

            <article class="reports-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Books</span>
                    <span class="kpi-icon">
                        <i class="fas fa-book"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ number_format($totalBooks) }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Titles in the library
                </div>
            </article>

            <article class="reports-kpi cyan">
                <div class="kpi-top">
                    <span class="kpi-label">Total Book Stock</span>
                    <span class="kpi-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ number_format($totalCopies) }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-copy"></i>
                    All copies owned
                </div>
            </article>

            <article class="reports-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Available Copies</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ number_format($availableCopies) }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Ready to be issued
                </div>
            </article>

            <article class="reports-kpi amber">
                <div class="kpi-top">
                    <span class="kpi-label">Currently Issued</span>
                    <span class="kpi-icon">
                        <i class="fas fa-book-reader"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ number_format($issuedCopies) }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-hourglass-half"></i>
                    With students right now
                </div>
            </article>

        </section>

        {{-- REPORT FILTERS --}}
        <section class="reports-table-card no-print">

            <div class="reports-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">REPORT FILTERS</div>
                        <h3>Filter Transactions</h3>
                        <p>Filter library transactions by date, book, student or status.</p>
                    </div>
                </div>
            </div>

            <form method="GET"
                  action="{{ route('admin.library.reports.index') }}"
                  class="reports-filters">

                <div class="diary-filter-group">
                    <label for="from_date">From Date</label>
                    <input type="date"
                           id="from_date"
                           name="from_date"
                           class="diary-control"
                           value="{{ $fromDate }}">
                </div>

                <div class="diary-filter-group">
                    <label for="to_date">To Date</label>
                    <input type="date"
                           id="to_date"
                           name="to_date"
                           class="diary-control"
                           value="{{ $toDate }}">
                </div>

                <div class="diary-filter-group">
                    <label for="book_id">Book</label>
                    <select id="book_id" name="book_id" class="diary-control">
                        <option value="">All books</option>
                        @foreach ($books as $book)
                            <option value="{{ $book->id }}"
                                @selected($bookId == $book->id)>
                                {{ $book->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="diary-filter-group">
                    <label for="student_id">Student ID</label>
                    <input type="text"
                           id="student_id"
                           name="student_id"
                           class="diary-control"
                           placeholder="Student ID"
                           value="{{ $studentId }}">
                </div>

                <div class="diary-filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="diary-control">
                        <option value="">All status</option>
                        @foreach (['Issued', 'Returned', 'Overdue'] as $option)
                            <option value="{{ $option }}"
                                @selected($status === $option)>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="diary-primary-btn">
                        <i class="fas fa-search"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.library.reports.index') }}"
                       class="diary-secondary-btn"
                       title="Reset filters">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- BOOK STOCK --}}
        <section class="reports-table-card">

            <div class="reports-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">STOCK REGISTER</div>
                        <h3>Book Stock</h3>
                        <p>Individual stock details for every book.</p>
                    </div>
                </div>

                <div class="reports-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $bookStock->count() }}</strong>
                    {{ $bookStock->count() == 1 ? 'Book' : 'Books' }}
                </div>
            </div>

            <div class="reports-table-wrapper">
                <table class="reports-table stock-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book</th>
                            <th scope="col">Author</th>
                            <th scope="col">Total Copies</th>
                            <th scope="col">Available</th>
                            <th scope="col">Issued</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bookStock as $book)
                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <div class="report-info">
                                        <div class="report-mini-icon">
                                            <i class="fas fa-book"></i>
                                        </div>

                                        <div class="report-details">
                                            <div class="report-name">
                                                {{ $book->title }}
                                            </div>
                                            <div class="report-description">
                                                {{ $book->isbn ? 'ISBN: ' . $book->isbn : 'No ISBN added' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-value {{ $book->author ? '' : 'muted' }}">
                                        {{ $book->author ?: '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $book->quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="stock-badge in-stock">
                                        {{ $book->available_quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="stock-badge issued">
                                        {{ $book->quantity - $book->available_quantity }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="reports-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>

                                        <h4>No Books Found</h4>

                                        <p>Add books to the library to see stock here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($bookStock->count())
                        <tfoot>
                            <tr>
                                <td colspan="3">Total book stock</td>
                                <td>
                                    <span class="count-badge">{{ $stockTotal }}</span>
                                </td>
                                <td>
                                    <span class="stock-badge in-stock">{{ $stockAvailable }}</span>
                                </td>
                                <td>
                                    <span class="stock-badge issued">{{ $stockIssued }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </section>

        {{-- TRANSACTIONS --}}
        <section class="reports-table-card">

            <div class="reports-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-right-left"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">TRANSACTION REGISTER</div>
                        <h3>Library Transactions</h3>
                        <p>Complete issue and return history.</p>
                    </div>
                </div>

                <div class="reports-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $transactions->total() }}</strong>
                    {{ $transactions->total() == 1 ? 'Record' : 'Records' }}
                </div>
            </div>

            <div class="reports-table-wrapper">
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book</th>
                            <th scope="col">Student ID</th>
                            <th scope="col">Issue Date</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Return Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Fine</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transactions as $transaction)
                            @php
                                $overdue = $transaction->status === 'Overdue'
                                    || (! $transaction->return_date
                                        && $transaction->due_date
                                        && \Carbon\Carbon::parse($transaction->due_date)->isPast());

                                $state = $transaction->status === 'Returned'
                                    ? 'returned'
                                    : ($overdue ? 'overdue' : 'issued');

                                $fineAmount = (float) $transaction->fine;
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $transactions->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="report-info">
                                        <div class="report-mini-icon">
                                            <i class="fas fa-book"></i>
                                        </div>

                                        <div class="report-details">
                                            @if ($transaction->book)
                                                <div class="report-name">
                                                    {{ $transaction->book->title }}
                                                </div>
                                                <div class="report-description">
                                                    {{ $transaction->book->author ?: 'Author not added' }}
                                                </div>
                                            @else
                                                <div class="report-name deleted">
                                                    Deleted book
                                                </div>
                                                <div class="report-description">
                                                    This book is no longer in the library
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $transaction->student_id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="date-value">{{ $fmt($transaction->issue_date) }}</div>
                                </td>

                                <td>
                                    <div class="date-value">{{ $fmt($transaction->due_date) }}</div>
                                </td>

                                <td>
                                    <div class="date-value {{ $transaction->return_date ? '' : 'muted' }}">
                                        {{ $fmt($transaction->return_date) }}
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge {{ $state }}">
                                        <span class="status-dot"></span>
                                        {{ ucfirst($state) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($fineAmount > 0)
                                        <span class="fine-badge positive">
                                            ₹{{ number_format($fineAmount, 2) }}
                                        </span>
                                    @else
                                        <span class="fine-badge zero">
                                            ₹0.00
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="reports-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-chart-column"></i>
                                        </div>

                                        <h4>No Transactions Found</h4>

                                        <p>Try changing your report filters.</p>

                                        @if ($hasFilters)
                                            <div class="empty-state-actions no-print">
                                                <a href="{{ route('admin.library.reports.index') }}"
                                                   class="diary-primary-btn">
                                                    <i class="fas fa-rotate-left"></i>
                                                    Reset Filters
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="reports-card-footer">
                <div>
                    Showing
                    <strong>{{ $transactions->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $transactions->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ $transactions->total() }}</strong>
                    record(s)
                </div>

                @if ($transactions->hasPages())
                    <div class="reports-pagination no-print">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                @else
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Transaction register loaded successfully
                    </div>
                @endif
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY REPORTS — INDEX PAGE
   Same tokens, cards, buttons and layout as Library / Roles / Notices.
   Every selector is scoped under .reports-page.
========================================================= */

.reports-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.reports-page *,
.reports-page *::before,
.reports-page *::after {
    box-sizing: border-box;
}

.reports-page a,
.reports-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.reports-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.reports-page .reports-hero,
.reports-page .reports-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.reports-page .reports-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.reports-page .reports-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.reports-page .reports-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.reports-page .reports-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.reports-page .reports-hero-text {
    min-width: 0;
}

.reports-page .reports-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.reports-page .reports-breadcrumb span {
    color: #b8b09e;
}

.reports-page .reports-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.reports-page .reports-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.reports-page .reports-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.reports-page .reports-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 25px;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.reports-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.reports-page .reports-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

.reports-page .hero-tools {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
}

.reports-page .hero-tool-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 14px;
    border: 1px solid #f1ead8;
    border-radius: 7px;
    color: var(--ink) !important;
    background: #f1ead8;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.reports-page .hero-tool-btn:hover {
    transform: translateY(-2px);
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* BUTTONS */

.reports-page .diary-primary-btn,
.reports-page .diary-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.reports-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.reports-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.reports-page .diary-secondary-btn {
    border: 1px solid var(--line);
    color: var(--muted) !important;
    background: #fff;
}

.reports-page .diary-secondary-btn:hover {
    border-color: #b9b29c;
    color: var(--ink) !important;
    background: #faf9f4;
}

/* KPI CARDS */

.reports-page .reports-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.reports-page .reports-kpi {
    --kpi-color: var(--blue);

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    padding: 22px 20px 16px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.reports-page .reports-kpi::before {
    position: absolute;
    top: -25px;
    left: 15px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .25px;
}

.reports-page .reports-kpi.blue  { --kpi-color: #2b5d8a; }
.reports-page .reports-kpi.cyan  { --kpi-color: #527f79; }
.reports-page .reports-kpi.green { --kpi-color: #3a8060; }
.reports-page .reports-kpi.amber { --kpi-color: #a87543; }

.reports-page .reports-kpi.blue::before  { content: "Books"; }
.reports-page .reports-kpi.cyan::before  { content: "Stock"; }
.reports-page .reports-kpi.green::before { content: "Available"; }
.reports-page .reports-kpi.amber::before { content: "Issued"; }

.reports-page .reports-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.reports-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.reports-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.reports-page .reports-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white);
    border-radius: 10px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    font-size: 17px;
}

.reports-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.reports-page .kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 35px;
    padding-top: 11px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.reports-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.reports-page .reports-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.reports-page .reports-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.reports-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.reports-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.reports-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.reports-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.reports-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.reports-page .reports-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.reports-page .reports-record-count i,
.reports-page .reports-record-count strong {
    color: var(--ink);
}

/* FILTERS */

.reports-page .reports-filters {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr)) minmax(0, 1.5fr) minmax(0, 1fr) minmax(0, 1fr) auto;
    align-items: end;
    gap: 14px;
    padding: 18px 21px 20px;
    background: #faf9f4;
}

.reports-page .diary-filter-group {
    min-width: 0;
}

.reports-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.reports-page .diary-control {
    display: block;
    width: 100%;
    min-width: 0;
    height: 41px;
    padding: 0 10px;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.reports-page select.diary-control {
    cursor: pointer;
}

.reports-page .diary-control::placeholder {
    color: #929b8f;
}

.reports-page .diary-control:hover {
    border-color: #bfcbbd;
}

.reports-page .diary-control:focus {
    border-color: var(--ink);
    box-shadow: 0 0 0 3px rgba(38, 59, 53, .18);
}

.reports-page .filter-actions {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* TABLE */

.reports-page .reports-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.reports-page .reports-table {
    width: 100%;
    min-width: 980px;
    margin: 0;
    border-collapse: collapse;
}

.reports-page .reports-table.stock-table {
    min-width: 820px;
}

.reports-page .reports-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.reports-page .reports-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.reports-page .reports-table tbody tr {
    transition: background .18s ease;
}

.reports-page .reports-table tbody tr:hover {
    background: #faf9f2;
}

.reports-page .reports-table tbody tr:last-child td {
    border-bottom: none;
}

.reports-page .reports-table tfoot td {
    padding: 13px 14px;
    border-top: 1px solid #e5e3d8;
    color: var(--ink);
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
    vertical-align: middle;
}

.reports-page .record-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e7e4d8;
    border-radius: 7px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

/* BOOK CELL */

.reports-page .report-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 240px;
}

.reports-page .report-mini-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e6dfce;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.reports-page .report-details {
    min-width: 0;
}

.reports-page .report-name {
    max-width: 260px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.reports-page .report-name.deleted {
    color: var(--muted);
    font-style: italic;
    font-weight: 600;
}

.reports-page .report-description {
    max-width: 280px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.reports-page .text-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
}

.reports-page .text-value.muted,
.reports-page .date-value.muted {
    color: var(--muted);
    font-weight: 600;
}

.reports-page .date-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.reports-page .count-badge,
.reports-page .stock-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 28px;
    padding: 0 9px;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    color: var(--blue);
    background: #f0f5f8;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.reports-page .stock-badge.in-stock {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.reports-page .stock-badge.issued {
    border-color: #e8d9c0;
    color: #a87543;
    background: #fcf7e9;
}

/* STATUS BADGES */

.reports-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.reports-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.reports-page .status-badge.issued {
    border: 1px solid #d7e2e9;
    color: #2b5d8a;
    background: #f0f5f8;
}

.reports-page .status-badge.issued .status-dot {
    background: #2b5d8a;
}

.reports-page .status-badge.returned {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.reports-page .status-badge.returned .status-dot {
    background: #3a8060;
}

.reports-page .status-badge.overdue {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.reports-page .status-badge.overdue .status-dot {
    background: #bd5148;
}

/* FINE */

.reports-page .fine-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 28px;
    padding: 0 9px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

.reports-page .fine-badge.zero {
    border: 1px solid #e7e4d8;
    color: #667269;
    background: #f5f3e9;
}

.reports-page .fine-badge.positive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

/* EMPTY STATE */

.reports-page .reports-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.reports-page .empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border: 1px solid #e5dfcc;
    border-radius: 14px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 27px;
}

.reports-page .reports-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.reports-page .reports-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.reports-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.reports-page .reports-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.reports-page .reports-card-footer strong {
    color: var(--ink);
}

.reports-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.reports-page .footer-status i {
    color: var(--green);
}

/* PAGINATION (Laravel / Bootstrap) */

.reports-page .reports-pagination nav > div:first-child {
    display: none;
}

.reports-page .reports-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.reports-page .reports-pagination .page-link,
.reports-page .reports-pagination .pagination span,
.reports-page .reports-pagination .pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 9px;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none !important;
}

.reports-page .reports-pagination .page-item.active .page-link,
.reports-page .reports-pagination .pagination [aria-current="page"] span {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.reports-page .reports-pagination .page-item.disabled .page-link {
    opacity: .5;
}

.reports-page .reports-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1300px) {
    .reports-page .reports-filters {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .reports-page .filter-actions {
        grid-column: 1 / -1;
        justify-content: flex-end;
    }
}

@media (max-width: 1100px) {
    .reports-page .reports-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .reports-page .reports-hero {
        grid-template-columns: 1fr;
    }

    .reports-page .reports-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .reports-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .reports-page .reports-hero::before {
        left: 22px;
    }

    .reports-page .reports-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .reports-page .reports-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .reports-page .reports-hero-text h2 {
        font-size: 23px;
    }

    .reports-page .reports-hero-text p {
        font-size: 12px;
    }

    .reports-page .reports-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .reports-page .reports-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .reports-page .hero-tools,
    .reports-page .hero-tool-btn {
        width: 100%;
    }

    .reports-page .hero-tool-btn {
        flex: 1;
    }

    .reports-page .reports-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .reports-page .reports-kpi {
        padding: 20px 16px 15px;
    }

    .reports-page .kpi-value {
        font-size: 30px;
    }

    .reports-page .reports-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .reports-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .reports-page .reports-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .reports-page .reports-filters {
        grid-template-columns: 1fr;
        padding: 14px 15px 16px;
    }

    .reports-page .filter-actions .diary-primary-btn,
    .reports-page .filter-actions .diary-secondary-btn {
        flex: 1;
    }

    /* 16px prevents iOS from zooming into focused fields */
    .reports-page .diary-control {
        font-size: 16px;
    }

    .reports-page .reports-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .reports-page .reports-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .reports-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */

.reports-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .reports-page *,
    .reports-page *::before,
    .reports-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}

/* PRINT */

@media print {
    .no-print,
    .sidebar,
    .top-header,
    .app-footer,
    .sidebar-overlay,
    .pagination {
        display: none !important;
    }

    .main-area {
        margin: 0 !important;
        width: 100% !important;
    }

    .main-content {
        padding: 0 !important;
        background: #fff !important;
    }

    body {
        background: #fff !important;
    }

    .reports-page {
        min-height: 0;
        background: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .reports-page .container-fluid {
        padding: 0 !important;
        max-width: none;
    }

    .reports-page .reports-hero {
        grid-template-columns: 1fr;
    }

    .reports-page .reports-hero,
    .reports-page .reports-kpi,
    .reports-page .reports-table-card {
        box-shadow: none !important;
        transform: none !important;
        break-inside: avoid;
    }

    .reports-page .reports-table,
    .reports-page .reports-table.stock-table {
        min-width: 0;
    }

    .reports-page .reports-table-wrapper {
        overflow: visible;
    }
}
</style>

@endsection