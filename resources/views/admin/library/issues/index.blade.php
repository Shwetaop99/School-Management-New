@extends('layouts.app')

@section('title', 'Issue / Return Books | Admin')
@section('page-title', 'Issue / Return Books')

@section('content')

@php
    $issueRows       = $issues->getCollection();
    $totalIssues     = $issues->total();
    $issuedCount     = $issueRows->where('status', 'Issued')->count();
    $returnedCount   = $issueRows->where('status', 'Returned')->count();
    $overdueCount    = $issueRows->whereNotIn('status', ['Issued', 'Returned'])->count();
@endphp

<div class="issues-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="issues-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="issues-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>{{ session('error') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="issues-hero">
            <div class="issues-hero-content">
                <div class="issues-hero-icon">
                    <i class="fas fa-book-reader"></i>
                </div>

                <div class="issues-hero-text">
                    <div class="issues-breadcrumb">
                        LIBRARY <span>/</span> ISSUE &amp; RETURN
                    </div>

                    <h2>Issue / Return Books</h2>

                    <p>
                        Manage issued books, returns and library fines
                        from one central place.
                    </p>

                    <div class="issues-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Circulation Desk &amp; Fine Tracking</span>
                    </div>
                </div>
            </div>

            <div class="issues-hero-actions">
                <div class="hero-handwritten">Issue. Track. Return.</div>

                <p>
                    Keep every book accounted for and every
                    due date visible.
                </p>

                <a href="{{ route('admin.library.issues.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Issue Book
                </a>
            </div>
        </section>

        {{-- QUICK ACTIONS --}}
        <section class="issues-quick-actions">

            <a href="{{ route('admin.library.issues.create') }}"
               class="issues-action-card">
                <div class="action-icon">
                    <i class="fas fa-book-medical"></i>
                </div>

                <div class="action-content">
                    <h3>Issue Book</h3>
                    <p>Issue a book to a student</p>
                </div>

                <i class="fas fa-arrow-right action-arrow"></i>
            </a>

            <a href="{{ route('admin.library.returns.index') }}"
               class="issues-action-card">
                <div class="action-icon">
                    <i class="fas fa-rotate-left"></i>
                </div>

                <div class="action-content">
                    <h3>Return Books</h3>
                    <p>Manage issued and returned books</p>
                </div>

                <i class="fas fa-arrow-right action-arrow"></i>
            </a>

        </section>

        {{-- KPI CARDS --}}
        <section class="issues-kpis">

            <article class="issues-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Transactions</span>
                    <span class="kpi-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalIssues }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    All library transactions
                </div>
            </article>

            <article class="issues-kpi cyan">
                <div class="kpi-top">
                    <span class="kpi-label">Issued</span>
                    <span class="kpi-icon">
                        <i class="fas fa-book-open"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $issuedCount }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-hourglass-half"></i>
                    Currently with students
                </div>
            </article>

            <article class="issues-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Returned</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $returnedCount }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Back on the shelf
                </div>
            </article>

            <article class="issues-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Overdue</span>
                    <span class="kpi-icon">
                        <i class="fas fa-triangle-exclamation"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $overdueCount }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-clock"></i>
                    Past their due date
                </div>
            </article>

        </section>

        {{-- TRANSACTIONS TABLE --}}
        <section class="issues-table-card">

            <div class="issues-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">TRANSACTION REGISTER</div>
                        <h3>Library Transactions</h3>
                        <p>Track issued books, returns and fines.</p>
                    </div>
                </div>

                <div class="issues-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalIssues }}</strong>
                    {{ $totalIssues == 1 ? 'Transaction' : 'Transactions' }}
                </div>
            </div>

            <div class="issues-table-wrapper">
                <table class="issues-table">
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
                            <th scope="col" class="actions-heading">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($issues as $issue)
                            @php
                                $statusKey = $issue->status === 'Issued'
                                    ? 'issued'
                                    : ($issue->status === 'Returned' ? 'returned' : 'overdue');

                                $statusLabel = $statusKey === 'overdue'
                                    ? 'Overdue'
                                    : $issue->status;

                                $fineAmount = (float) $issue->fine;
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $issues->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="issue-info">
                                        <div class="issue-mini-icon">
                                            <i class="fas fa-book"></i>
                                        </div>

                                        <div class="issue-details">
                                            @if($issue->book)
                                                <div class="issue-name">
                                                    {{ $issue->book->title }}
                                                </div>
                                                <div class="issue-description">
                                                    {{ $issue->book->author ?: 'Author not added' }}
                                                </div>
                                            @else
                                                <div class="issue-name deleted">
                                                    Book Deleted
                                                </div>
                                                <div class="issue-description">
                                                    This book is no longer in the library
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        #{{ $issue->student_id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="date-value">
                                        {{ $issue->issue_date?->format('d M Y') ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="date-value">
                                        {{ $issue->due_date?->format('d M Y') ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="date-value {{ $issue->return_date ? '' : 'muted' }}">
                                        {{ $issue->return_date?->format('d M Y') ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge {{ $statusKey }}">
                                        <span class="status-dot"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td>
                                    @if($fineAmount > 0)
                                        <span class="fine-badge positive">
                                            ₹{{ number_format($fineAmount, 2) }}
                                        </span>

                                        @if($issue->fine_type && $issue->fine_type !== 'None')
                                            <div class="fine-type">
                                                {{ $issue->fine_type }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="fine-badge zero">
                                            ₹0.00
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="issues-actions">
                                        @if($issue->status !== 'Returned')
                                            <form action="{{ route('admin.library.issues.return', $issue) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to mark this book as returned?');">
                                                @csrf

                                                <button type="submit"
                                                        class="diary-primary-btn compact-btn"
                                                        aria-label="Return {{ $issue->book->title ?? 'book' }}">
                                                    <i class="fas fa-rotate-left"></i>
                                                    Return Book
                                                </button>
                                            </form>
                                        @else
                                            <span class="returned-label">
                                                <i class="fas fa-circle-check"></i>
                                                Returned
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="issues-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-book-reader"></i>
                                        </div>

                                        <h4>No Library Transactions Yet</h4>

                                        <p>
                                            No books have been issued yet. Issue a book
                                            to create your first transaction.
                                        </p>

                                        <div class="empty-state-actions">
                                            <a href="{{ route('admin.library.issues.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Issue Book
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="issues-card-footer">
                <div>
                    Showing
                    <strong>{{ $issues->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $issues->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ $issues->total() }}</strong>
                    transaction(s)
                </div>

                @if($issues->hasPages())
                    <div class="issues-pagination">
                        {{ $issues->withQueryString()->links() }}
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
   LIBRARY ISSUE / RETURN — INDEX PAGE
   Same tokens, cards, buttons and layout as Library / Roles / Notices.
   Every selector is scoped under .issues-page.
========================================================= */

.issues-page {
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

.issues-page *,
.issues-page *::before,
.issues-page *::after {
    box-sizing: border-box;
}

.issues-page a,
.issues-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.issues-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.issues-page .issues-hero,
.issues-page .issues-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.issues-page .issues-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.issues-page .issues-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.issues-page .issues-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.issues-page .issues-hero-icon {
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

.issues-page .issues-hero-text {
    min-width: 0;
}

.issues-page .issues-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.issues-page .issues-breadcrumb span {
    color: #b8b09e;
}

.issues-page .issues-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.issues-page .issues-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.issues-page .issues-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.issues-page .issues-hero-actions {
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

.issues-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.issues-page .issues-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.issues-page .diary-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--ink);
    border-radius: 7px;
    color: #fff !important;
    background: var(--ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.issues-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.issues-page .diary-primary-btn.compact-btn {
    min-height: 34px;
    padding: 7px 12px;
    font-size: 11px;
}

.issues-page .issues-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.issues-page .issues-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* ALERTS */

.issues-page .issues-success-alert,
.issues-page .issues-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.issues-page .issues-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.issues-page .issues-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.issues-page .success-icon,
.issues-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.issues-page .success-icon { background: #dcefd9; }
.issues-page .error-icon { background: #fbe0d8; }

.issues-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.issues-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* QUICK ACTION CARDS */

.issues-page .issues-quick-actions {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 8px;
}

.issues-page .issues-action-card {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    padding: 18px 20px;
    border: 1px solid var(--line);
    border-radius: 9px;
    color: inherit;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    text-decoration: none !important;
}

.issues-page .issues-action-card:hover {
    transform: translateY(-3px);
    border-color: #d5cdb7;
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.issues-page .action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e6dfce;
    border-radius: 10px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 17px;
}

.issues-page .action-content {
    flex: 1;
    min-width: 0;
}

.issues-page .action-content h3 {
    margin: 0 0 4px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.issues-page .action-content p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

.issues-page .action-arrow {
    flex: 0 0 auto;
    color: #a3a99c;
    font-size: 13px;
}

.issues-page .issues-action-card:hover .action-arrow {
    color: var(--ink);
    transform: translateX(2px);
}

/* KPI CARDS */

.issues-page .issues-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.issues-page .issues-kpi {
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

.issues-page .issues-kpi::before {
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

.issues-page .issues-kpi.blue  { --kpi-color: #2b5d8a; }
.issues-page .issues-kpi.cyan  { --kpi-color: #527f79; }
.issues-page .issues-kpi.green { --kpi-color: #3a8060; }
.issues-page .issues-kpi.red   { --kpi-color: #bd5148; }

.issues-page .issues-kpi.blue::before  { content: "Total"; }
.issues-page .issues-kpi.cyan::before  { content: "Issued"; }
.issues-page .issues-kpi.green::before { content: "Returned"; }
.issues-page .issues-kpi.red::before   { content: "Overdue"; }

.issues-page .issues-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.issues-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.issues-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.issues-page .issues-kpi .kpi-icon {
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

.issues-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.issues-page .kpi-caption {
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

.issues-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.issues-page .issues-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.issues-page .issues-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.issues-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.issues-page .panel-title-icon {
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

.issues-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.issues-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.issues-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.issues-page .issues-record-count {
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

.issues-page .issues-record-count i,
.issues-page .issues-record-count strong {
    color: var(--ink);
}

/* TABLE */

.issues-page .issues-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.issues-page .issues-table {
    width: 100%;
    min-width: 1100px;
    margin: 0;
    border-collapse: collapse;
}

.issues-page .issues-table thead th {
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

.issues-page .issues-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.issues-page .issues-table tbody tr {
    transition: background .18s ease;
}

.issues-page .issues-table tbody tr:hover {
    background: #faf9f2;
}

.issues-page .issues-table tbody tr:last-child td {
    border-bottom: none;
}

.issues-page .issues-table .actions-heading {
    text-align: right;
}

.issues-page .record-number {
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

.issues-page .issue-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 240px;
}

.issues-page .issue-mini-icon {
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

.issues-page .issue-details {
    min-width: 0;
}

.issues-page .issue-name {
    max-width: 240px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.issues-page .issue-name.deleted {
    color: var(--muted);
    font-style: italic;
    font-weight: 600;
}

.issues-page .issue-description {
    max-width: 260px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.issues-page .count-badge {
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

.issues-page .date-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.issues-page .date-value.muted {
    color: var(--muted);
    font-weight: 600;
}

/* STATUS BADGES */

.issues-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.issues-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.issues-page .status-badge.issued {
    border: 1px solid #d7e2e9;
    color: #2b5d8a;
    background: #f0f5f8;
}

.issues-page .status-badge.issued .status-dot {
    background: #2b5d8a;
}

.issues-page .status-badge.returned {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.issues-page .status-badge.returned .status-dot {
    background: #3a8060;
}

.issues-page .status-badge.overdue {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.issues-page .status-badge.overdue .status-dot {
    background: #bd5148;
}

/* FINE */

.issues-page .fine-badge {
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

.issues-page .fine-badge.zero {
    border: 1px solid #e7e4d8;
    color: #667269;
    background: #f5f3e9;
}

.issues-page .fine-badge.positive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.issues-page .fine-type {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* ACTIONS */

.issues-page .issues-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.issues-page .issues-actions form {
    display: inline-flex;
    margin: 0;
}

.issues-page .returned-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
}

.issues-page .returned-label i {
    color: var(--green);
}

/* EMPTY STATE */

.issues-page .issues-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.issues-page .empty-state-icon {
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

.issues-page .issues-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.issues-page .issues-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.issues-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.issues-page .issues-card-footer {
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

.issues-page .issues-card-footer strong {
    color: var(--ink);
}

.issues-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.issues-page .footer-status i {
    color: var(--green);
}

/* PAGINATION (Laravel / Bootstrap) */

.issues-page .issues-pagination nav > div:first-child {
    display: none;
}

.issues-page .issues-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.issues-page .issues-pagination .page-link,
.issues-page .issues-pagination .pagination span,
.issues-page .issues-pagination .pagination a {
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

.issues-page .issues-pagination .page-item.active .page-link,
.issues-page .issues-pagination .pagination [aria-current="page"] span {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.issues-page .issues-pagination .page-item.disabled .page-link {
    opacity: .5;
}

.issues-page .issues-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1100px) {
    .issues-page .issues-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .issues-page .issues-hero {
        grid-template-columns: 1fr;
    }

    .issues-page .issues-hero-actions {
        min-height: 165px;
    }

    .issues-page .issues-quick-actions {
        grid-template-columns: 1fr;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .issues-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .issues-page .issues-hero::before {
        left: 22px;
    }

    .issues-page .issues-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .issues-page .issues-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .issues-page .issues-hero-text h2 {
        font-size: 23px;
    }

    .issues-page .issues-hero-text p {
        font-size: 12px;
    }

    .issues-page .issues-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .issues-page .issues-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .issues-page .issues-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .issues-page .issues-kpi {
        padding: 20px 16px 15px;
    }

    .issues-page .kpi-value {
        font-size: 30px;
    }

    .issues-page .issues-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .issues-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .issues-page .issues-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .issues-page .issues-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .issues-page .issues-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .issues-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .issues-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .issues-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.issues-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .issues-page *,
    .issues-page *::before,
    .issues-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection