@extends('layouts.app')

@section('title', 'Library Fines | Admin')
@section('page-title', 'Library Fines')

@section('content')
<div class="fines-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> FINES
                    </div>
                    <h2>Library Fines</h2>
                    <p>Manage book fines and track payment status.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.library.books.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Library
                </a>
            </div>
        </section>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div class="form-success-alert" role="alert">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <div class="success-copy">
                    <strong>{{ session('success') }}</strong>
                </div>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- ERROR ALERT --}}
        @if(session('error'))
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <div class="error-copy">
                    <strong>{{ session('error') }}</strong>
                </div>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <section class="form-card">
            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="section-eyebrow">LIBRARY REGISTER</div>
                        <h3>Books With Fines</h3>
                        <p>Only transactions with an outstanding or recorded fine are shown here.</p>
                    </div>
                </div>
            </div>

            @if($fines->count())
                <div class="table-wrapper">
                    <table class="diary-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Student ID</th>
                                <th>Fine Type</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Return Date</th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fines as $fine)
                                <tr>
                                    {{-- Book --}}
                                    <td>
                                        <div class="book-title">
                                            {{ $fine->book->title ?? 'Book Deleted' }}
                                        </div>
                                    </td>

                                    {{-- Student --}}
                                    <td>
                                        <span class="student-id">
                                            #{{ $fine->student_id }}
                                        </span>
                                    </td>

                                    {{-- Fine Type --}}
                                    <td>
                                        <span class="fine-type-badge">
                                            {{ $fine->fine_type ?? 'Other' }}
                                        </span>
                                    </td>

                                    {{-- Amount --}}
                                    <td>
                                        <span class="fine-amount">
                                            ₹{{ number_format((float) $fine->fine, 2) }}
                                        </span>
                                    </td>

                                    {{-- Reason --}}
                                    <td>
                                        <div class="reason-text">
                                            {{ $fine->fine_reason ?: 'No reason provided.' }}
                                        </div>
                                    </td>

                                    {{-- Return Date --}}
                                    <td>
                                        @if($fine->return_date)
                                            {{ $fine->return_date->format('d M Y') }}
                                        @else
                                            <span class="muted-text">—</span>
                                        @endif
                                    </td>

                                    {{-- Payment Status --}}
                                    <td>
                                        @if($fine->fine_status === 'Paid')
                                            <span class="status-badge status-paid">
                                                <span class="status-dot"></span>
                                                Paid
                                            </span>
                                        @else
                                            <span class="status-badge status-pending">
                                                <span class="status-dot"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action --}}
                                    <td>
                                        <form action="{{ route('admin.library.fines.status', $fine) }}"
                                              method="POST"
                                              class="status-form">
                                            @csrf
                                            @method('PATCH')

                                            <select name="fine_status"
                                                    class="status-select">
                                                <option value="Pending"
                                                    {{ $fine->fine_status === 'Pending' ? 'selected' : '' }}>
                                                    Pending
                                                </option>
                                                <option value="Paid"
                                                    {{ $fine->fine_status === 'Paid' ? 'selected' : '' }}>
                                                    Paid
                                                </option>
                                            </select>

                                            <button type="submit" class="update-btn">
                                                Update
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    {{ $fines->links() }}
                </div>

            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h4>No fines recorded</h4>
                    <p>There are currently no books with fines.</p>
                </div>
            @endif
        </section>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY — FINES LIST PAGE
   Same tokens, cards and buttons as previous pages.
   Every selector is scoped under .fines-page.
========================================================= */
.fines-page {
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
    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.fines-page *,
.fines-page *::before,
.fines-page *::after {
    box-sizing: border-box;
}

.fines-page a,
.fines-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.fines-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.fines-page .form-hero,
.fines-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.fines-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.fines-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.fines-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.fines-page .form-hero-icon {
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

.fines-page .form-hero-text {
    min-width: 0;
}

.fines-page .form-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.fines-page .form-breadcrumb span {
    color: #b8b09e;
}

.fines-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.fines-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.fines-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */
.fines-page .diary-primary-btn,
.fines-page .diary-reset-btn {
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

.fines-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.fines-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* SUCCESS ALERT */
.fines-page .form-success-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #cfe5d3;
    border-radius: 9px;
    color: #347849;
    background: #edf7ed;
    font-size: 12px;
}

.fines-page .success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #d4edda;
}

.fines-page .success-copy {
    flex: 1;
    min-width: 0;
}

.fines-page .success-copy strong {
    display: block;
    font-weight: 800;
}

/* ERROR ALERT */
.fines-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #f0d5ce;
    border-radius: 9px;
    color: #a84840;
    background: #fff4ef;
    font-size: 12px;
}

.fines-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.fines-page .error-copy {
    flex: 1;
    min-width: 0;
}

.fines-page .error-copy strong {
    display: block;
    font-weight: 800;
}

.fines-page .alert-close {
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

.fines-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */
.fines-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.fines-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.fines-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.fines-page .panel-title-icon {
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

.fines-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.fines-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.fines-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

/* TABLE */
.fines-page .table-wrapper {
    overflow-x: auto;
}

.fines-page .diary-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px;
}

.fines-page .diary-table th {
    background: #faf9f4;
    color: var(--muted);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .6px;
    font-weight: 800;
    padding: 13px 16px;
    text-align: left;
    white-space: nowrap;
    border-bottom: 1px solid var(--line);
}

.fines-page .diary-table td {
    padding: 15px 16px;
    border-bottom: 1px solid #f0ebe0;
    color: var(--text);
    font-size: 12.5px;
    vertical-align: middle;
}

.fines-page .diary-table tbody tr:last-child td {
    border-bottom: 0;
}

.fines-page .diary-table tbody tr:hover {
    background: #faf9f4;
}

.fines-page .book-title {
    font-weight: 700;
    color: var(--ink);
    font-size: 13px;
}

.fines-page .student-id {
    font-weight: 700;
    color: var(--ink);
}

.fines-page .fine-type-badge {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 6px;
    background: #faf5e7;
    border: 1px solid #e6dfc9;
    color: #765e32;
    font-size: 10px;
    font-weight: 800;
}

.fines-page .fine-amount {
    color: var(--red);
    font-weight: 800;
    font-size: 13px;
}

.fines-page .reason-text {
    max-width: 220px;
    white-space: normal;
    line-height: 1.5;
    color: var(--muted);
    font-size: 12px;
}

.fines-page .muted-text {
    color: var(--muted);
}

/* STATUS BADGES */
.fines-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.fines-page .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.fines-page .status-paid {
    background: #edf7ed;
    color: #347849;
    border: 1px solid #cfe5d3;
}

.fines-page .status-paid .status-dot {
    background: #347849;
}

.fines-page .status-pending {
    background: #faf5e7;
    color: #765e32;
    border: 1px solid #e6dfc9;
}

.fines-page .status-pending .status-dot {
    background: #a87543;
}

/* STATUS FORM */
.fines-page .status-form {
    display: inline-flex;
    align-items: center;
    gap: 7px;
}

.fines-page .status-select {
    height: 32px;
    padding: 0 10px;
    border: 1px solid #deded2;
    border-radius: 6px;
    background: #fffefa;
    color: var(--text);
    font-size: 11px;
    font-weight: 600;
    outline: none;
    cursor: pointer;
}

.fines-page .status-select:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.fines-page .update-btn {
    height: 32px;
    padding: 0 12px;
    border: 1px solid var(--ink);
    border-radius: 6px;
    background: var(--ink);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.fines-page .update-btn:hover {
    background: var(--ink-hover);
    border-color: var(--ink-hover);
}

/* EMPTY STATE */
.fines-page .empty-state {
    text-align: center;
    padding: 60px 20px;
}

.fines-page .empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 54px;
    height: 54px;
    margin: 0 auto 16px;
    border: 1px solid #cfe5d3;
    border-radius: 50%;
    background: #edf7ed;
    color: #347849;
    font-size: 20px;
}

.fines-page .empty-state h4 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 17px;
    font-weight: 700;
}

.fines-page .empty-state p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
}

/* PAGINATION */
.fines-page .pagination-wrapper {
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */
@media (max-width: 900px) {
    .fines-page .form-hero {
        grid-template-columns: 1fr;
    }
    .fines-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .fines-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .fines-page .form-hero::before {
        left: 22px;
    }

    .fines-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .fines-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .fines-page .form-hero-text h2 {
        font-size: 23px;
    }

    .fines-page .form-hero-text p {
        font-size: 12px;
    }

    .fines-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .fines-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .fines-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .fines-page .panel-title-wrap h3 {
        font-size: 16px;
    }
}

@media (max-width: 400px) {
    .fines-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */
.fines-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .fines-page *,
    .fines-page *::before,
    .fines-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endsection