@extends('layouts.app')

@section('title', 'Return Books | Admin')
@section('page-title', 'Return Books')

@section('content')
<div class="return-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> ISSUE / RETURN <span>/</span> RETURN BOOKS
                    </div>
                    <h2>Return Books</h2>
                    <p>Manage currently issued books and process their returns.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.library.issues.index') }}"
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

        {{-- MAIN TABLE CARD --}}
        <section class="form-card">
            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <div class="section-eyebrow">LIBRARY REGISTER</div>
                        <h3>Currently Issued Books</h3>
                        <p>Books that are waiting to be returned by students.</p>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                @if($returns->count())
                    <table class="diary-table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Student ID</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                                <tr>
                                    <td>
                                        <div class="book-title">
                                            {{ $return->book->title ?? 'Book Deleted' }}
                                        </div>
                                        @if($return->book)
                                            <div class="book-author">
                                                {{ $return->book->author }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="student-id">
                                            #{{ $return->student_id }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="date-text">
                                            {{ $return->issue_date?->format('d M Y') ?? '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(
                                            $return->status === 'Overdue' ||
                                            ($return->due_date && $return->due_date->isPast())
                                        )
                                            <span class="date-overdue">
                                                {{ $return->due_date?->format('d M Y') ?? '—' }}
                                            </span>
                                        @else
                                            <span class="date-text">
                                                {{ $return->due_date?->format('d M Y') ?? '—' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($return->status === 'Overdue')
                                            <span class="status-badge status-overdue">
                                                <span class="status-dot"></span>
                                                Overdue
                                            </span>
                                        @else
                                            <span class="status-badge status-issued">
                                                <span class="status-dot"></span>
                                                Issued
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="return-action-btn"
                                                onclick="openReturnModal(
                                                    {{ $return->id }},
                                                    @js($return->book->title ?? 'Book Deleted'),
                                                    {{ $return->student_id }},
                                                    @js($return->due_date?->format('d M Y') ?? '—')
                                                )">
                                            <i class="fas fa-undo"></i>
                                            Return Book
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="pagination-wrapper">
                        {{ $returns->links() }}
                    </div>

                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h4>No books waiting for return</h4>
                        <p>There are currently no issued or overdue books.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

{{-- RETURN MODAL --}}
<div id="returnModal" class="return-modal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-wrap">
                <div class="modal-title-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <div>
                    <div class="section-eyebrow">PROCESS RETURN</div>
                    <h3>Return Book</h3>
                </div>
            </div>
            <button type="button"
                    class="modal-close-btn"
                    onclick="closeReturnModal()"
                    aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="returnForm" method="POST">
            @csrf

            <div class="modal-body">
                <div class="book-summary">
                    <div class="summary-title" id="modalBookTitle">Book</div>
                    <div class="summary-details">
                        Student ID: <strong id="modalStudentId">—</strong>
                        &nbsp;•&nbsp;
                        Due Date: <strong id="modalDueDate">—</strong>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fine_type">Fine Type</label>
                    <div class="input-wrapper">
                        <i class="fas fa-tag input-icon"></i>
                        <select name="fine_type"
                                id="fine_type"
                                class="diary-control with-icon"
                                required>
                            <option value="None">No Fine</option>
                            <option value="Late Return">Late Return</option>
                            <option value="Book Damaged">Book Damaged</option>
                            <option value="Book Lost">Book Lost</option>
                            <option value="Pages Damaged">Pages Damaged</option>
                            <option value="Cover Damaged">Cover Damaged</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fine">Fine Amount (₹)</label>
                    <div class="input-wrapper">
                        <i class="fas fa-indian-rupee-sign input-icon"></i>
                        <input type="number"
                               name="fine"
                               id="fine"
                               class="diary-control with-icon"
                               min="0"
                               step="0.01"
                               value="0"
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="fine_reason">Reason / Explanation</label>
                    <textarea name="fine_reason"
                              id="fine_reason"
                              class="diary-textarea"
                              rows="4"
                              maxlength="1000"
                              placeholder="Explain why the fine was applied..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="diary-reset-btn"
                        onclick="closeReturnModal()">
                    <i class="fas fa-xmark"></i>
                    Cancel
                </button>
                <button type="submit"
                        class="diary-primary-btn">
                    <i class="fas fa-check"></i>
                    Confirm Return
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY — RETURN BOOKS PAGE
   Same tokens, cards and buttons as previous pages.
   Every selector is scoped under .return-page / modal.
========================================================= */
.return-page {
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

.return-page *,
.return-page *::before,
.return-page *::after {
    box-sizing: border-box;
}

.return-page a,
.return-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.return-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.return-page .form-hero,
.return-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.return-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.return-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.return-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.return-page .form-hero-icon {
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

.return-page .form-hero-text {
    min-width: 0;
}

.return-page .form-breadcrumb {
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

.return-page .form-breadcrumb span {
    color: #b8b09e;
}

.return-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.return-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.return-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */
.return-page .diary-primary-btn,
.return-page .diary-reset-btn {
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

.return-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.return-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.return-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.return-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ALERTS */
.return-page .form-success-alert {
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

.return-page .success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #d4edda;
}

.return-page .success-copy {
    flex: 1;
    min-width: 0;
}

.return-page .success-copy strong {
    display: block;
    font-weight: 800;
}

.return-page .form-error-alert {
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

.return-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.return-page .error-copy {
    flex: 1;
    min-width: 0;
}

.return-page .error-copy strong {
    display: block;
    font-weight: 800;
}

.return-page .alert-close {
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

.return-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */
.return-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.return-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.return-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.return-page .panel-title-icon {
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

.return-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.return-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.return-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

/* TABLE */
.return-page .table-wrapper {
    overflow-x: auto;
}

.return-page .diary-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}

.return-page .diary-table th {
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

.return-page .diary-table td {
    padding: 15px 16px;
    border-bottom: 1px solid #f0ebe0;
    color: var(--text);
    font-size: 12.5px;
    vertical-align: middle;
}

.return-page .diary-table tbody tr:last-child td {
    border-bottom: 0;
}

.return-page .diary-table tbody tr:hover {
    background: #faf9f4;
}

.return-page .book-title {
    font-weight: 700;
    color: var(--ink);
    font-size: 13px;
}

.return-page .book-author {
    margin-top: 3px;
    color: var(--muted);
    font-size: 11px;
}

.return-page .student-id {
    font-weight: 700;
    color: var(--ink);
}

.return-page .date-text {
    color: var(--text);
    font-weight: 600;
}

.return-page .date-overdue {
    color: var(--red);
    font-weight: 800;
}

/* STATUS BADGES */
.return-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.return-page .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.return-page .status-issued {
    background: #edf7ed;
    color: #347849;
    border: 1px solid #cfe5d3;
}

.return-page .status-issued .status-dot {
    background: #347849;
}

.return-page .status-overdue {
    background: #fff4ef;
    color: #a84840;
    border: 1px solid #f0d5ce;
}

.return-page .status-overdue .status-dot {
    background: #a84840;
}

/* RETURN ACTION BUTTON */
.return-page .return-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 12px;
    border: 1px solid #cfe5d3;
    border-radius: 7px;
    background: #edf7ed;
    color: #347849;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.return-page .return-action-btn:hover {
    background: #d4edda;
    border-color: #b7d9bf;
}

/* EMPTY STATE */
.return-page .empty-state {
    text-align: center;
    padding: 60px 20px;
}

.return-page .empty-icon {
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

.return-page .empty-state h4 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 17px;
    font-weight: 700;
}

.return-page .empty-state p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
}

/* PAGINATION */
.return-page .pagination-wrapper {
    padding: 16px 20px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* =========================
   RETURN MODAL
========================= */
.return-modal {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(38, 59, 53, .38);
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.return-modal.active {
    display: flex;
}

.return-modal .modal-box {
    width: 100%;
    max-width: 520px;
    max-height: 90vh;
    overflow-y: auto;
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 12px;
    box-shadow: 0 18px 40px rgba(38, 59, 53, .16);
}

.return-modal .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px;
    border-bottom: 1px dashed #d8d1bf;
}

.return-modal .modal-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
}

.return-modal .modal-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.return-modal .modal-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 17px;
    font-weight: 700;
}

.return-modal .modal-close-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 7px;
    background: #f5f4eb;
    color: #68746a;
    cursor: pointer;
}

.return-modal .modal-close-btn:hover {
    background: #ebe8d9;
    color: var(--ink);
}

.return-modal .modal-body {
    padding: 20px;
}

.return-modal .book-summary {
    padding: 14px 16px;
    margin-bottom: 20px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.return-modal .summary-title {
    color: #765e32;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 5px;
}

.return-modal .summary-details {
    color: #8a7447;
    font-size: 12px;
}

.return-modal .form-group {
    margin-bottom: 16px;
}

.return-modal .form-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.return-modal .input-wrapper {
    position: relative;
    width: 100%;
}

.return-modal .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
}

.return-modal .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.return-modal .diary-control,
.return-modal .diary-textarea {
    display: block;
    width: 100%;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.return-modal .diary-control {
    height: 41px;
    padding: 0 10px;
}

.return-modal .diary-control.with-icon {
    padding-left: 35px;
}

.return-modal select.diary-control {
    cursor: pointer;
}

.return-modal .diary-textarea {
    min-height: 100px;
    padding: 11px 12px;
    line-height: 1.65;
    resize: vertical;
}

.return-modal .diary-control:focus,
.return-modal .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.return-modal .modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    padding: 15px 20px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */
@media (max-width: 900px) {
    .return-page .form-hero {
        grid-template-columns: 1fr;
    }
    .return-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .return-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .return-page .form-hero::before {
        left: 22px;
    }

    .return-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .return-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .return-page .form-hero-text h2 {
        font-size: 23px;
    }

    .return-page .form-hero-text p {
        font-size: 12px;
    }

    .return-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .return-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .return-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .return-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .return-modal .modal-footer {
        flex-direction: column-reverse;
    }

    .return-modal .modal-footer > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .return-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }
}

/* ACCESSIBILITY */
.return-page :focus-visible,
.return-modal :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .return-page *,
    .return-page *::before,
    .return-page *::after,
    .return-modal *,
    .return-modal *::before,
    .return-modal *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
function openReturnModal(issueId, bookTitle, studentId, dueDate) {
    const modal = document.getElementById('returnModal');
    const form = document.getElementById('returnForm');

    form.action = "{{ url('/admin/library/issues') }}/" + issueId + "/return";

    document.getElementById('modalBookTitle').textContent = bookTitle;
    document.getElementById('modalStudentId').textContent = '#' + studentId;
    document.getElementById('modalDueDate').textContent = dueDate;

    document.getElementById('fine_type').value = 'None';
    document.getElementById('fine').value = '0';
    document.getElementById('fine_reason').value = '';

    modal.classList.add('active');
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.remove('active');
}

document.getElementById('returnModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeReturnModal();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeReturnModal();
    }
});

document.getElementById('fine_type').addEventListener('change', function() {
    const fineInput = document.getElementById('fine');
    if (this.value === 'None') {
        fineInput.value = '0';
    }
});
</script>
@endsection