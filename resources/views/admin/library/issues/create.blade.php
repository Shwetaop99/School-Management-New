@extends('layouts.app')

@section('title', 'Issue Book | Admin')
@section('page-title', 'Issue Book')

@section('content')
<div class="issue-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> ISSUE / RETURN <span>/</span> ISSUE BOOK
                    </div>
                    <h2>Issue Book</h2>
                    <p>Issue a library book to a student.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.library.issues.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Issue / Return
                </a>
            </div>
        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <div class="error-copy">
                    <strong>Please check the following:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- CONTROLLER / SESSION ERROR --}}
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

        {{-- MAIN FORM CARD --}}
        <section class="form-card">
            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <div class="section-eyebrow">LIBRARY REGISTER</div>
                        <h3>Issue Details</h3>
                        <p>Enter the book and borrowing information.</p>
                    </div>
                </div>
                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.library.issues.store') }}"
                  method="POST"
                  id="issueForm">
                @csrf

                <div class="form-body">

                    {{-- INFO NOTE --}}
                    <div class="info-note">
                        <div class="info-note-icon">
                            <i class="fas fa-circle-info"></i>
                        </div>
                        <div>
                            <strong>Available books only</strong>
                            <p>Only books with available copies are shown in the list below.</p>
                        </div>
                    </div>

                    {{-- BOOK SELECTION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <div>
                                <h4>Book Selection</h4>
                                <span>Choose the book you want to issue.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="diary-filter-group full">
                                <label for="book_id">
                                    Book <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-book-open input-icon"></i>
                                    <select name="book_id"
                                            id="book_id"
                                            class="diary-control with-icon @error('book_id') is-invalid @enderror"
                                            required
                                            @error('book_id') aria-invalid="true" aria-describedby="book_id_error" @enderror>
                                        <option value="">Select a book</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}"
                                                {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                                {{ $book->title }} — {{ $book->author }}
                                                ({{ $book->available_quantity }} available)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($books->isEmpty())
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        No books are currently available for issue.
                                    </div>
                                @else
                                    <div class="field-help">
                                        <i class="fas fa-circle-info"></i>
                                        Select the book you want to issue.
                                    </div>
                                @endif

                                @error('book_id')
                                    <div class="field-error" id="book_id_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- BORROWER DETAILS --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div>
                                <h4>Borrower Details</h4>
                                <span>Student information for this issue.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="diary-filter-group">
                                <label for="student_id">
                                    Student ID <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="number"
                                           name="student_id"
                                           id="student_id"
                                           value="{{ old('student_id') }}"
                                           class="diary-control with-icon @error('student_id') is-invalid @enderror"
                                           placeholder="Enter student ID"
                                           min="1"
                                           required
                                           @error('student_id') aria-invalid="true" aria-describedby="student_id_error" @enderror>
                                </div>
                                <div class="field-help">
                                    <i class="fas fa-lightbulb"></i>
                                    We'll connect this to the actual Student Management system later.
                                </div>
                                @error('student_id')
                                    <div class="field-error" id="student_id_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- DATES --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-calendar-days"></i>
                            </div>
                            <div>
                                <h4>Issue Dates</h4>
                                <span>Set the issue and expected return dates.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="diary-filter-group">
                                <label for="issue_date">
                                    Issue Date <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-plus input-icon"></i>
                                    <input type="date"
                                           name="issue_date"
                                           id="issue_date"
                                           value="{{ old('issue_date', date('Y-m-d')) }}"
                                           class="diary-control with-icon @error('issue_date') is-invalid @enderror"
                                           required
                                           @error('issue_date') aria-invalid="true" aria-describedby="issue_date_error" @enderror>
                                </div>
                                @error('issue_date')
                                    <div class="field-error" id="issue_date_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="diary-filter-group">
                                <label for="due_date">
                                    Due Date <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-check input-icon"></i>
                                    <input type="date"
                                           name="due_date"
                                           id="due_date"
                                           value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}"
                                           class="diary-control with-icon @error('due_date') is-invalid @enderror"
                                           required
                                           @error('due_date') aria-invalid="true" aria-describedby="due_date_error" @enderror>
                                </div>
                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    The date by which the book should be returned.
                                </div>
                                @error('due_date')
                                    <div class="field-error" id="due_date_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- REMARKS --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <div>
                                <h4>Additional Notes</h4>
                                <span>Optional remarks about this issue.</span>
                            </div>
                        </div>

                        <div class="description-card">
                            <div class="description-header">
                                <div class="description-icon">
                                    <i class="fas fa-pen"></i>
                                </div>
                                <label for="remarks">Remarks</label>
                                <span class="optional-badge">Optional</span>
                            </div>
                            <textarea name="remarks"
                                      id="remarks"
                                      rows="4"
                                      maxlength="1000"
                                      class="diary-textarea @error('remarks') is-invalid @enderror"
                                      placeholder="Optional notes about this issue..."
                                      @error('remarks') aria-invalid="true" aria-describedby="remarks_error" @enderror>{{ old('remarks') }}</textarea>
                            <div class="description-footer">
                                <div class="field-help">
                                    <i class="fas fa-lightbulb"></i>
                                    Add any special notes if needed.
                                </div>
                                <span class="character-count">
                                    <span id="remarksCount">0</span>/1000
                                </span>
                            </div>
                            @error('remarks')
                                <div class="field-error" id="remarks_error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.library.issues.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>
                    <button type="submit"
                            class="diary-primary-btn"
                            id="issueBtn"
                            {{ $books->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-check"></i>
                        Issue Book
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY — ISSUE BOOK FORM PAGE
   Same tokens, cards and buttons as previous pages.
   Every selector is scoped under .issue-form-page.
========================================================= */
.issue-form-page {
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

.issue-form-page *,
.issue-form-page *::before,
.issue-form-page *::after {
    box-sizing: border-box;
}

.issue-form-page a,
.issue-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.issue-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.issue-form-page .form-hero,
.issue-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.issue-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.issue-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.issue-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.issue-form-page .form-hero-icon {
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

.issue-form-page .form-hero-text {
    min-width: 0;
}

.issue-form-page .form-breadcrumb {
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

.issue-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.issue-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.issue-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.issue-form-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */
.issue-form-page .diary-primary-btn,
.issue-form-page .diary-reset-btn {
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

.issue-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.issue-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.issue-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.issue-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.issue-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERROR ALERT */
.issue-form-page .form-error-alert {
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

.issue-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.issue-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.issue-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.issue-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.issue-form-page .alert-close {
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

.issue-form-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */
.issue-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.issue-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.issue-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.issue-form-page .panel-title-icon {
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

.issue-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.issue-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.issue-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.issue-form-page .required-info {
    flex-shrink: 0;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.issue-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */
.issue-form-page .form-body {
    padding: 24px 22px 8px;
}

.issue-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.issue-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.issue-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.issue-form-page .section-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.issue-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.issue-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* INFO NOTE */
.issue-form-page .info-note {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 26px;
    padding: 14px 16px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.issue-form-page .info-note-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid #e6dfc9;
    border-radius: 8px;
    color: var(--amber);
    background: #fffefa;
    font-size: 13px;
}

.issue-form-page .info-note strong {
    display: block;
    margin-bottom: 3px;
    color: #765e32;
    font-size: 12px;
    font-weight: 800;
}

.issue-form-page .info-note p {
    margin: 0;
    color: #8a7447;
    font-size: 11px;
    line-height: 1.55;
}

/* FORM GRID AND FIELDS */
.issue-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.issue-form-page .diary-filter-group {
    min-width: 0;
}

.issue-form-page .diary-filter-group.full {
    grid-column: 1 / -1;
}

.issue-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.issue-form-page .required {
    color: var(--red);
}

.issue-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.issue-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.issue-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.issue-form-page .diary-control,
.issue-form-page .diary-textarea {
    display: block;
    width: 100%;
    min-width: 0;
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

.issue-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.issue-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.issue-form-page select.diary-control {
    cursor: pointer;
}

.issue-form-page .diary-control::placeholder,
.issue-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.issue-form-page .diary-control:hover,
.issue-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.issue-form-page .diary-control:focus,
.issue-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.issue-form-page .diary-control.is-invalid,
.issue-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.issue-form-page .diary-control.is-invalid:focus,
.issue-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

/* HELP AND ERROR TEXT */
.issue-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.issue-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.issue-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.issue-form-page .field-error i {
    margin-top: 2px;
}

/* DESCRIPTION CARD */
.issue-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.issue-form-page .description-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.issue-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
}

.issue-form-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
}

.issue-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.issue-form-page .optional-badge {
    padding: 3px 9px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.issue-form-page .diary-textarea {
    min-height: 110px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.issue-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.issue-form-page .description-footer .field-help {
    margin-top: 0;
}

.issue-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */
.issue-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 8px;
    padding: 15px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */
@media (max-width: 900px) {
    .issue-form-page .form-hero {
        grid-template-columns: 1fr;
    }
    .issue-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .issue-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .issue-form-page .form-hero::before {
        left: 22px;
    }

    .issue-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .issue-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .issue-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .issue-form-page .form-hero-text p {
        font-size: 12px;
    }

    .issue-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .issue-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .issue-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .issue-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .issue-form-page .required-info {
        display: none;
    }

    .issue-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .issue-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .issue-form-page .diary-filter-group.full {
        grid-column: auto;
    }

    .issue-form-page .diary-control,
    .issue-form-page .diary-textarea {
        font-size: 16px;
    }

    .issue-form-page .description-card {
        padding: 12px;
    }

    .issue-form-page .description-footer {
        align-items: flex-start;
    }

    .issue-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .issue-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .issue-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .issue-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */
.issue-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.issue-form-page .diary-control:focus-visible,
.issue-form-page .diary-textarea:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .issue-form-page *,
    .issue-form-page *::before,
    .issue-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Remarks character counter
    var remarks = document.getElementById('remarks');
    var counter = document.getElementById('remarksCount');
    if (remarks && counter) {
        var updateCounter = function () {
            counter.textContent = remarks.value.length;
        };
        remarks.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Prevent double submit
    var form = document.getElementById('issueForm');
    var issueBtn = document.getElementById('issueBtn');
    if (form && issueBtn) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }
            issueBtn.disabled = true;
            issueBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Issuing...';
        });
    }
});
</script>
@endsection