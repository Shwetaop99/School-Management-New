@extends('layouts.app')

@section('title', 'Edit Book | Admin')
@section('page-title', 'Edit Book')

@section('content')
<div class="book-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> BOOKS <span>/</span> EDIT BOOK
                    </div>
                    <h2>Edit Book</h2>
                    <p>Update the information of this library book.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.library.books.show', $book) }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Book
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

        {{-- MAIN FORM CARD --}}
        <section class="form-card">
            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-pen-to-square"></i>
                    </div>
                    <div>
                        <div class="section-eyebrow">LIBRARY REGISTER</div>
                        <h3>Update Book Details</h3>
                        <p>Modify the book information required for library management.</p>
                    </div>
                </div>
                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.library.books.update', $book) }}"
                  method="POST"
                  id="bookForm"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-body">

                    {{-- BOOK INFORMATION --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-circle-info"></i>
                            </div>
                            <div>
                                <h4>Book Information</h4>
                                <span>Provide the main details of the book.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            {{-- TITLE --}}
                            <div class="diary-filter-group full">
                                <label for="title">
                                    Book Title <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-book-open input-icon"></i>
                                    <input type="text"
                                           name="title"
                                           id="title"
                                           value="{{ old('title', $book->title) }}"
                                           class="diary-control with-icon @error('title') is-invalid @enderror"
                                           placeholder="e.g. The Great Gatsby"
                                           maxlength="255"
                                           autocomplete="off"
                                           required
                                           @error('title') aria-invalid="true" aria-describedby="title_error" @enderror>
                                </div>
                                @error('title')
                                    <div class="field-error" id="title_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- AUTHOR --}}
                            <div class="diary-filter-group">
                                <label for="author">
                                    Author <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user-pen input-icon"></i>
                                    <input type="text"
                                           name="author"
                                           id="author"
                                           value="{{ old('author', $book->author) }}"
                                           class="diary-control with-icon @error('author') is-invalid @enderror"
                                           placeholder="e.g. F. Scott Fitzgerald"
                                           required
                                           @error('author') aria-invalid="true" aria-describedby="author_error" @enderror>
                                </div>
                                @error('author')
                                    <div class="field-error" id="author_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- ISBN --}}
                            <div class="diary-filter-group">
                                <label for="isbn">ISBN</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-barcode input-icon"></i>
                                    <input type="text"
                                           name="isbn"
                                           id="isbn"
                                           value="{{ old('isbn', $book->isbn) }}"
                                           class="diary-control with-icon @error('isbn') is-invalid @enderror"
                                           placeholder="e.g. 978-3-16-148410-0"
                                           @error('isbn') aria-invalid="true" aria-describedby="isbn_error" @enderror>
                                </div>
                                @error('isbn')
                                    <div class="field-error" id="isbn_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- CATEGORY --}}
                            <div class="diary-filter-group">
                                <label for="category">
                                    Category <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-layer-group input-icon"></i>
                                    <input type="text"
                                           name="category"
                                           id="category"
                                           value="{{ old('category', $book->category) }}"
                                           class="diary-control with-icon @error('category') is-invalid @enderror"
                                           placeholder="e.g. Fiction"
                                           required
                                           @error('category') aria-invalid="true" aria-describedby="category_error" @enderror>
                                </div>
                                @error('category')
                                    <div class="field-error" id="category_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PUBLISHER --}}
                            <div class="diary-filter-group">
                                <label for="publisher">Publisher</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-building input-icon"></i>
                                    <input type="text"
                                           name="publisher"
                                           id="publisher"
                                           value="{{ old('publisher', $book->publisher) }}"
                                           class="diary-control with-icon"
                                           placeholder="e.g. Scribner">
                                </div>
                            </div>

                            {{-- LANGUAGE --}}
                            <div class="diary-filter-group">
                                <label for="language">Language</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-language input-icon"></i>
                                    <input type="text"
                                           name="language"
                                           id="language"
                                           value="{{ old('language', $book->language) }}"
                                           class="diary-control with-icon"
                                           placeholder="e.g. English">
                                </div>
                            </div>

                            {{-- PUBLICATION DATE --}}
                            <div class="diary-filter-group">
                                <label for="publication_date">Publication Date</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-days input-icon"></i>
                                    <input type="date"
                                           name="publication_date"
                                           id="publication_date"
                                           value="{{ old('publication_date', $book->publication_date?->format('Y-m-d')) }}"
                                           class="diary-control with-icon">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INVENTORY DETAILS --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-boxes-stacked"></i>
                            </div>
                            <div>
                                <h4>Inventory Details</h4>
                                <span>Manage quantity and shelf location.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            {{-- QUANTITY --}}
                            <div class="diary-filter-group">
                                <label for="quantity">
                                    Total Quantity <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-hashtag input-icon"></i>
                                    <input type="number"
                                           name="quantity"
                                           id="quantity"
                                           value="{{ old('quantity', $book->quantity) }}"
                                           class="diary-control with-icon @error('quantity') is-invalid @enderror"
                                           min="1"
                                           required
                                           @error('quantity') aria-invalid="true" aria-describedby="quantity_error" @enderror>
                                </div>
                                @error('quantity')
                                    <div class="field-error" id="quantity_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- SHELF NUMBER --}}
                            <div class="diary-filter-group">
                                <label for="shelf_number">Shelf Number</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text"
                                           name="shelf_number"
                                           id="shelf_number"
                                           value="{{ old('shelf_number', $book->shelf_number) }}"
                                           class="diary-control with-icon"
                                           placeholder="e.g. A-12">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- BOOK COVER --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-image"></i>
                            </div>
                            <div>
                                <h4>Book Cover</h4>
                                <span>Upload or replace the cover image.</span>
                            </div>
                        </div>

                        @if($book->cover_image)
                            <div class="current-cover-card">
                                <div class="current-cover-label">
                                    <i class="fas fa-image"></i>
                                    Current Cover
                                </div>
                                <img src="{{ $book->cover_image }}"
                                     alt="{{ $book->title }}"
                                     class="current-cover-img">
                            </div>
                        @endif

                        <div class="diary-filter-group">
                            <label for="cover_image">New Cover Image</label>
                            <div class="input-wrapper">
                                <i class="fas fa-cloud-upload-alt input-icon"></i>
                                <input type="file"
                                       id="cover_image"
                                       accept="image/*"
                                       class="diary-control with-icon file-input">
                            </div>

                            <input type="hidden"
                                   name="cover_image"
                                   id="cover_image_url"
                                   value="{{ old('cover_image', $book->cover_image) }}">

                            <div class="field-help" id="upload-status">
                                <i class="fas fa-circle-info"></i>
                                Select a new image only if you want to replace the current cover.
                            </div>

                            <div id="cover-preview" class="cover-preview"></div>

                            @error('cover_image')
                                <div class="field-error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- ADDITIONAL DETAILS --}}
                    <div class="form-section">
                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-align-left"></i>
                            </div>
                            <div>
                                <h4>Additional Details</h4>
                                <span>Status and optional description.</span>
                            </div>
                        </div>

                        <div class="form-grid">
                            {{-- STATUS --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Status <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>
                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon"
                                            required>
                                        <option value="Available"
                                            {{ old('status', $book->status) === 'Available' ? 'selected' : '' }}>
                                            Available
                                        </option>
                                        <option value="Unavailable"
                                            {{ old('status', $book->status) === 'Unavailable' ? 'selected' : '' }}>
                                            Unavailable
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="diary-filter-group full">
                                <div class="description-card">
                                    <div class="description-header">
                                        <div class="description-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <label for="description">Description</label>
                                        <span class="optional-badge">Optional</span>
                                    </div>
                                    <textarea name="description"
                                              id="description"
                                              rows="5"
                                              maxlength="2000"
                                              class="diary-textarea"
                                              placeholder="Enter book description, summary or notes...">{{ old('description', $book->description) }}</textarea>
                                    <div class="description-footer">
                                        <div class="field-help">
                                            <i class="fas fa-lightbulb"></i>
                                            Add useful information about the book.
                                        </div>
                                        <span class="character-count">
                                            <span id="descriptionCount">0</span>/2000
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.library.books.show', $book) }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>
                    <button type="submit"
                            class="diary-primary-btn"
                            id="saveBookBtn">
                        <i class="fas fa-check"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY BOOKS — EDIT BOOK FORM PAGE
   Same tokens, cards and buttons as Meal Items / Sports.
   Every selector is scoped under .book-form-page.
========================================================= */
.book-form-page {
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

.book-form-page *,
.book-form-page *::before,
.book-form-page *::after {
    box-sizing: border-box;
}

.book-form-page a,
.book-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.book-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.book-form-page .form-hero,
.book-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.book-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.book-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.book-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.book-form-page .form-hero-icon {
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

.book-form-page .form-hero-text {
    min-width: 0;
}

.book-form-page .form-breadcrumb {
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

.book-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.book-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.book-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.book-form-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */
.book-form-page .diary-primary-btn,
.book-form-page .diary-reset-btn {
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

.book-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.book-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.book-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.book-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.book-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ERROR ALERT */
.book-form-page .form-error-alert {
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

.book-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.book-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.book-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.book-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.book-form-page .alert-close {
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

.book-form-page .alert-close:hover {
    color: var(--ink);
    background: #f8e3dc;
}

/* FORM CARD */
.book-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.book-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.book-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.book-form-page .panel-title-icon {
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

.book-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.book-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.book-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.book-form-page .required-info {
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

.book-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */
.book-form-page .form-body {
    padding: 24px 22px 8px;
}

.book-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.book-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.book-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.book-form-page .section-heading-icon {
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

.book-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.book-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */
.book-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.book-form-page .diary-filter-group {
    min-width: 0;
}

.book-form-page .diary-filter-group.full {
    grid-column: 1 / -1;
}

.book-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.book-form-page .required {
    color: var(--red);
}

.book-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.book-form-page .input-icon {
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

.book-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.book-form-page .diary-control,
.book-form-page .diary-textarea {
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

.book-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.book-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.book-form-page select.diary-control {
    cursor: pointer;
}

.book-form-page .diary-control::placeholder,
.book-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.book-form-page .diary-control:hover,
.book-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.book-form-page .diary-control:focus,
.book-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.book-form-page .diary-control.is-invalid,
.book-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.book-form-page .diary-control.is-invalid:focus,
.book-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

/* File input specific */
.book-form-page .file-input {
    padding-top: 9px;
    padding-bottom: 9px;
    height: auto;
    min-height: 41px;
}

/* HELP AND ERROR TEXT */
.book-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.book-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.book-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.book-form-page .field-error i {
    margin-top: 2px;
}

/* CURRENT COVER */
.book-form-page .current-cover-card {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding: 14px 16px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.book-form-page .current-cover-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #765e32;
    font-size: 12px;
    font-weight: 700;
}

.book-form-page .current-cover-img {
    width: 70px;
    height: 90px;
    object-fit: cover;
    border-radius: 7px;
    border: 1px solid #e6dfc9;
    background: #fffefa;
}

/* COVER PREVIEW */
.book-form-page .cover-preview {
    display: none;
    margin-top: 12px;
}

.book-form-page .cover-preview img {
    width: 90px;
    height: 115px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e6dfc9;
    background: #fffefa;
}

/* DESCRIPTION CARD */
.book-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.book-form-page .description-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.book-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
}

.book-form-page .description-icon {
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

.book-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.book-form-page .optional-badge {
    padding: 3px 9px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.book-form-page .diary-textarea {
    min-height: 125px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.book-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.book-form-page .description-footer .field-help {
    margin-top: 0;
}

.book-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */
.book-form-page .form-actions {
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
    .book-form-page .form-hero {
        grid-template-columns: 1fr;
    }
    .book-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .book-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .book-form-page .form-hero::before {
        left: 22px;
    }

    .book-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .book-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .book-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .book-form-page .form-hero-text p {
        font-size: 12px;
    }

    .book-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .book-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .book-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .book-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .book-form-page .required-info {
        display: none;
    }

    .book-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .book-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .book-form-page .diary-filter-group.full {
        grid-column: auto;
    }

    /* 16px prevents iOS from zooming into focused fields */
    .book-form-page .diary-control,
    .book-form-page .diary-textarea {
        font-size: 16px;
    }

    .book-form-page .description-card {
        padding: 12px;
    }

    .book-form-page .description-footer {
        align-items: flex-start;
    }

    .book-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .book-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .book-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .book-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */
.book-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.book-form-page .diary-control:focus-visible,
.book-form-page .diary-textarea:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .book-form-page *,
    .book-form-page *::before,
    .book-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Description character counter
    var description = document.getElementById('description');
    var counter = document.getElementById('descriptionCount');
    if (description && counter) {
        var updateCounter = function () {
            counter.textContent = description.value.length;
        };
        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Prevent double submit
    var form = document.getElementById('bookForm');
    var saveBtn = document.getElementById('saveBookBtn');
    if (form && saveBtn) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        });
    }

    // Cover image upload + compression + Cloudinary
    var coverInput = document.getElementById('cover_image');
    if (coverInput) {
        coverInput.addEventListener('change', async function () {
            const file = this.files[0];
            if (!file) return;

            const status = document.getElementById('upload-status');
            const preview = document.getElementById('cover-preview');
            const urlInput = document.getElementById('cover_image_url');

            try {
                status.style.color = '#2b5d8a';
                status.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Compressing new cover...';

                const image = new Image();
                const objectUrl = URL.createObjectURL(file);
                image.src = objectUrl;

                await new Promise((resolve, reject) => {
                    image.onload = resolve;
                    image.onerror = reject;
                });

                const maxWidth = 1000;
                const maxHeight = 1400;
                let width = image.width;
                let height = image.height;

                if (width > maxWidth || height > maxHeight) {
                    const ratio = Math.min(maxWidth / width, maxHeight / height);
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                if (!ctx) throw new Error('Your browser does not support image compression.');

                ctx.drawImage(image, 0, 0, width, height);

                const blob = await new Promise(resolve => {
                    canvas.toBlob(resolve, 'image/jpeg', 0.80);
                });

                URL.revokeObjectURL(objectUrl);

                if (!blob) throw new Error('Image compression failed.');

                const compressedFile = new File([blob], 'book-cover.jpg', { type: 'image/jpeg' });
                const sizeKB = Math.round(compressedFile.size / 1024);

                status.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Compressed to ${sizeKB} KB. Uploading...`;

                const cloudName = '{{ config('services.cloudinary.cloud_name') }}';
                const uploadPreset = '{{ config('services.cloudinary.upload_preset') }}';
                const uploadUrl = `https://api.cloudinary.com/v1_1/${cloudName}/image/upload`;

                const formData = new FormData();
                formData.append('file', compressedFile);
                formData.append('upload_preset', uploadPreset);

                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.error?.message ||
                        response.headers.get('X-Cld-Error') ||
                        'Cloudinary upload failed.'
                    );
                }

                urlInput.value = data.secure_url;

                preview.innerHTML = `
                    <img src="${data.secure_url}" alt="New Book Cover">
                `;
                preview.style.display = 'block';

                status.style.color = '#3a8060';
                status.innerHTML = `<i class="fas fa-check-circle"></i> New cover uploaded successfully (${sizeKB} KB).`;

            } catch (error) {
                console.error('Cloudinary Upload Error:', error);
                status.style.color = '#bd5148';
                status.innerHTML = `<i class="fas fa-circle-exclamation"></i> Upload failed: ${error.message}`;
                urlInput.value = '{{ old('cover_image', $book->cover_image) }}';
                preview.innerHTML = '';
                preview.style.display = 'none';
            }
        });
    }
});
</script>
@endsection