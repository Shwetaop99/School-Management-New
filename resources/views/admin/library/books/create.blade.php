@extends('layouts.app')

@section('title', 'Add Book | Admin')
@section('page-title', 'Add Book')

@section('content')

<div class="book-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-book-medical"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span>
                        <a href="{{ route('admin.library.books.index') }}">BOOKS</a>
                        <span>/</span> ADD BOOK
                    </div>

                    <h2>Add New Book</h2>

                    <p>Add a new book to the school library.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.library.books.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Books
                </a>
            </div>
        </section>

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert" id="formErrorAlert" tabindex="-1">
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
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">BOOK REGISTER</div>
                        <h3>Create Book</h3>
                        <p>Enter the book information required for library management.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.library.books.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="bookForm">
                @csrf

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
                                    <i class="fas fa-book input-icon"></i>

                                    <input type="text"
                                           name="title"
                                           id="title"
                                           value="{{ old('title') }}"
                                           class="diary-control with-icon @error('title') is-invalid @enderror"
                                           placeholder="Enter book title"
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
                                           value="{{ old('author') }}"
                                           class="diary-control with-icon @error('author') is-invalid @enderror"
                                           placeholder="Enter author name"
                                           maxlength="255"
                                           autocomplete="off"
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
                                           value="{{ old('isbn') }}"
                                           class="diary-control with-icon @error('isbn') is-invalid @enderror"
                                           placeholder="Enter ISBN number"
                                           maxlength="30"
                                           autocomplete="off"
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
                                           value="{{ old('category') }}"
                                           class="diary-control with-icon @error('category') is-invalid @enderror"
                                           placeholder="e.g. Science, Fiction"
                                           maxlength="100"
                                           autocomplete="off"
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
                                           value="{{ old('publisher') }}"
                                           class="diary-control with-icon @error('publisher') is-invalid @enderror"
                                           placeholder="Enter publisher"
                                           maxlength="255"
                                           autocomplete="off"
                                           @error('publisher') aria-invalid="true" aria-describedby="publisher_error" @enderror>
                                </div>

                                @error('publisher')
                                    <div class="field-error" id="publisher_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- LANGUAGE --}}
                            <div class="diary-filter-group">
                                <label for="language">Language</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-language input-icon"></i>

                                    <input type="text"
                                           name="language"
                                           id="language"
                                           value="{{ old('language') }}"
                                           class="diary-control with-icon @error('language') is-invalid @enderror"
                                           placeholder="e.g. English"
                                           maxlength="50"
                                           autocomplete="off"
                                           @error('language') aria-invalid="true" aria-describedby="language_error" @enderror>
                                </div>

                                @error('language')
                                    <div class="field-error" id="language_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PUBLICATION DATE --}}
                            <div class="diary-filter-group">
                                <label for="publication_date">Publication Date</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt input-icon"></i>

                                    <input type="date"
                                           name="publication_date"
                                           id="publication_date"
                                           value="{{ old('publication_date') }}"
                                           class="diary-control with-icon @error('publication_date') is-invalid @enderror"
                                           @error('publication_date') aria-invalid="true" aria-describedby="publication_date_error" @enderror>
                                </div>

                                @error('publication_date')
                                    <div class="field-error" id="publication_date_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
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
                                <span>Set how many copies you have and where they are shelved.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- QUANTITY --}}
                            <div class="diary-filter-group">
                                <label for="quantity">
                                    Total Quantity <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-copy input-icon"></i>

                                    <input type="number"
                                           name="quantity"
                                           id="quantity"
                                           value="{{ old('quantity', 1) }}"
                                           class="diary-control with-icon @error('quantity') is-invalid @enderror"
                                           min="1"
                                           step="1"
                                           inputmode="numeric"
                                           placeholder="e.g. 5"
                                           required
                                           @error('quantity') aria-invalid="true" aria-describedby="quantity_error" @enderror>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Total number of copies owned by the library.
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
                                    <i class="fas fa-location-dot input-icon"></i>

                                    <input type="text"
                                           name="shelf_number"
                                           id="shelf_number"
                                           value="{{ old('shelf_number') }}"
                                           class="diary-control with-icon @error('shelf_number') is-invalid @enderror"
                                           placeholder="e.g. A-12"
                                           maxlength="30"
                                           autocomplete="off"
                                           @error('shelf_number') aria-invalid="true" aria-describedby="shelf_number_error" @enderror>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Helps staff find the book quickly.
                                </div>

                                @error('shelf_number')
                                    <div class="field-error" id="shelf_number_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

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
                                <span>Add the cover, availability status and a short description.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- COVER --}}
                            <div class="diary-filter-group">
                                <label for="cover_image">Book Cover</label>

                                <div class="cover-card">
                                    <div class="cover-preview" id="cover-preview" aria-live="polite">
                                        @if(old('cover_image'))
                                            <img src="{{ old('cover_image') }}" alt="Book cover preview">
                                        @else
                                            <i class="fas fa-image"></i>
                                        @endif
                                    </div>

                                    <div class="cover-controls">
                                        <input type="file"
                                               id="cover_image"
                                               class="diary-control file-control"
                                               accept="image/*">

                                        <input type="hidden"
                                               name="cover_image"
                                               id="cover_image_url"
                                               value="{{ old('cover_image') }}">

                                        <div class="upload-status" id="upload-status" role="status" aria-live="polite"></div>

                                        <div class="field-help">
                                            <i class="fas fa-circle-info"></i>
                                            Image is compressed and uploaded automatically.
                                        </div>
                                    </div>
                                </div>

                                @error('cover_image')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon @error('status') is-invalid @enderror"
                                            required
                                            @error('status') aria-invalid="true" aria-describedby="status_error" @enderror>
                                        <option value="Available"
                                            @selected(old('status', 'Available') === 'Available')>
                                            Available
                                        </option>
                                        <option value="Unavailable"
                                            @selected(old('status') === 'Unavailable')>
                                            Unavailable
                                        </option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="field-error" id="status_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- DESCRIPTION --}}
                            <div class="description-card full">

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
                                          class="diary-textarea @error('description') is-invalid @enderror"
                                          placeholder="Enter a short description about the book..."
                                          @error('description') aria-invalid="true" aria-describedby="description_error" @enderror>{{ old('description') }}</textarea>

                                <div class="description-footer">
                                    <div class="field-help">
                                        <i class="fas fa-lightbulb"></i>
                                        Add a short summary to help students choose.
                                    </div>

                                    <span class="character-count">
                                        <span id="descriptionCount">{{ strlen(old('description', '')) }}</span>/2000
                                    </span>
                                </div>

                                @error('description')
                                    <div class="field-error" id="description_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.library.books.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="diary-primary-btn"
                            id="saveBookBtn">
                        <i class="fas fa-plus"></i>
                        Add Book
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY BOOKS — ADD BOOK FORM PAGE
   Same tokens, cards and buttons as Meal Items form.
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

.book-form-page .form-breadcrumb a {
    color: var(--amber);
    text-decoration: none;
}

.book-form-page .form-breadcrumb a:hover {
    color: var(--ink);
    text-decoration: underline;
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

.book-form-page .form-grid .full {
    grid-column: 1 / -1;
}

.book-form-page .diary-filter-group {
    min-width: 0;
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
    border-color: var(--ink);
    box-shadow: 0 0 0 3px rgba(38, 59, 53, .18);
}

.book-form-page .diary-control.is-invalid,
.book-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.book-form-page .diary-control.is-invalid:focus,
.book-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .16);
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

/* COVER UPLOAD */

.book-form-page .cover-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.book-form-page .cover-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.book-form-page .cover-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 80px;
    width: 80px;
    height: 104px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: #a3a99c;
    background: var(--chalk);
    font-size: 24px;
}

.book-form-page .cover-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-form-page .cover-controls {
    flex: 1;
    min-width: 0;
}

.book-form-page .file-control {
    height: auto;
    padding: 8px 10px;
    cursor: pointer;
}

.book-form-page .file-control::file-selector-button {
    margin-right: 10px;
    padding: 6px 11px;
    border: 1px solid var(--ink);
    border-radius: 6px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    cursor: pointer;
}

.book-form-page .upload-status {
    min-height: 16px;
    margin-top: 8px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.book-form-page .upload-status.is-working { color: var(--blue); }
.book-form-page .upload-status.is-success { color: #347849; }
.book-form-page .upload-status.is-error   { color: var(--red); }

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

    .book-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .book-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .book-form-page .form-grid .full {
        grid-column: auto;
    }

    /* 16px prevents iOS from zooming into focused fields */
    .book-form-page .diary-control,
    .book-form-page .diary-textarea {
        font-size: 16px;
    }

    .book-form-page .cover-card {
        flex-direction: column;
    }

    .book-form-page .cover-controls {
        width: 100%;
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
    var form        = document.getElementById('bookForm');
    var saveBtn     = document.getElementById('saveBookBtn');
    var saveHtml    = saveBtn ? saveBtn.innerHTML : '';
    var fileInput   = document.getElementById('cover_image');
    var urlInput    = document.getElementById('cover_image_url');
    var preview     = document.getElementById('cover-preview');
    var status      = document.getElementById('upload-status');
    var description = document.getElementById('description');
    var counter     = document.getElementById('descriptionCount');
    var uploading   = false;

    var cloudName    = @json(config('services.cloudinary.cloud_name'));
    var uploadPreset = @json(config('services.cloudinary.upload_preset'));
    var placeholder  = '<i class="fas fa-image"></i>';

    /* Description character counter */
    if (description && counter) {
        var updateCounter = function () {
            counter.textContent = description.value.length;
        };
        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    /* Bring the first error into view */
    var firstInvalid = document.querySelector('.book-form-page .is-invalid');
    if (firstInvalid) {
        firstInvalid.focus({ preventScroll: true });
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        var alertBox = document.getElementById('formErrorAlert');
        if (alertBox) {
            alertBox.focus({ preventScroll: true });
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function setStatus(type, message) {
        status.className = 'upload-status' + (type ? ' is-' + type : '');
        status.textContent = message || '';
    }

    function setUploading(state) {
        uploading = state;
        if (saveBtn) {
            saveBtn.disabled = state;
        }
    }

    function resetCover() {
        urlInput.value = '';
        preview.innerHTML = placeholder;
    }

    /* Cover compression + Cloudinary upload */
    if (fileInput) {
        fileInput.addEventListener('change', async function () {
            var file = this.files[0];
            if (!file) { return; }

            var objectUrl = null;

            try {
                setUploading(true);
                setStatus('working', 'Compressing cover...');

                var image = new Image();
                objectUrl = URL.createObjectURL(file);
                image.src = objectUrl;

                await new Promise(function (resolve, reject) {
                    image.onload = resolve;
                    image.onerror = function () {
                        reject(new Error('Please choose a valid image file.'));
                    };
                });

                var maxWidth = 1000;
                var maxHeight = 1400;
                var width = image.width;
                var height = image.height;

                if (width > maxWidth || height > maxHeight) {
                    var ratio = Math.min(maxWidth / width, maxHeight / height);
                    width = Math.round(width * ratio);
                    height = Math.round(height * ratio);
                }

                var canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;

                var ctx = canvas.getContext('2d');
                if (!ctx) {
                    throw new Error('Your browser does not support image compression.');
                }

                ctx.drawImage(image, 0, 0, width, height);

                var blob = await new Promise(function (resolve) {
                    canvas.toBlob(resolve, 'image/jpeg', 0.80);
                });

                if (!blob) {
                    throw new Error('Image compression failed.');
                }

                var compressedFile = new File([blob], 'book-cover.jpg', { type: 'image/jpeg' });
                var sizeKB = Math.round(compressedFile.size / 1024);

                setStatus('working', 'Compressed to ' + sizeKB + ' KB. Uploading...');

                var uploadUrl = 'https://api.cloudinary.com/v1_1/' + cloudName + '/image/upload';

                var formData = new FormData();
                formData.append('file', compressedFile);
                formData.append('upload_preset', uploadPreset);

                var response = await fetch(uploadUrl, { method: 'POST', body: formData });
                var data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        (data.error && data.error.message) ||
                        response.headers.get('X-Cld-Error') ||
                        'Cloudinary upload failed.'
                    );
                }

                urlInput.value = data.secure_url;

                preview.innerHTML = '';
                var img = document.createElement('img');
                img.src = data.secure_url;
                img.alt = 'Book cover preview';
                preview.appendChild(img);

                setStatus('success', 'Cover uploaded successfully (' + sizeKB + ' KB).');
            } catch (error) {
                resetCover();
                setStatus('error', 'Upload failed: ' + error.message);
            } finally {
                if (objectUrl) { URL.revokeObjectURL(objectUrl); }
                setUploading(false);
            }
        });
    }

    /* Prevent double submit and submitting mid-upload */
    if (form && saveBtn) {
        form.addEventListener('submit', function (event) {
            if (uploading) {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        });

        /* Restore the button if the page comes back from the browser cache */
        window.addEventListener('pageshow', function () {
            saveBtn.disabled = false;
            saveBtn.innerHTML = saveHtml;
        });
    }
});
</script>

@endsection