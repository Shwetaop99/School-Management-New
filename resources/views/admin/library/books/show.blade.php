@extends('layouts.app')

@section('title', 'Book Details | Admin')
@section('page-title', 'Book Details')

@section('content')
<div class="book-details-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> BOOKS <span>/</span> DETAILS
                    </div>
                    <h2>Book Details</h2>
                    <p>View complete information about this library book.</p>
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

        {{-- MAIN DETAILS CARD --}}
        <section class="form-card">
            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-circle-info"></i>
                    </div>
                    <div>
                        <div class="section-eyebrow">LIBRARY REGISTER</div>
                        <h3>{{ $book->title }}</h3>
                        <p>Complete record of this library book.</p>
                    </div>
                </div>
                <div class="status-badge {{ $book->status === 'Available' ? 'status-available' : 'status-unavailable' }}">
                    <span class="status-dot"></span>
                    {{ $book->status }}
                </div>
            </div>

            <div class="form-body">

                {{-- BOOK TOP: COVER + MAIN INFO --}}
                <div class="book-top-section">
                    <div class="book-cover-wrap">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}"
                                 alt="{{ $book->title }}"
                                 class="book-cover-img">
                        @else
                            <div class="default-cover">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif
                    </div>

                    <div class="book-main-info">
                        <div class="book-title-block">
                            <h2>{{ $book->title }}</h2>
                            <p class="book-author">
                                <i class="fas fa-user-pen"></i>
                                By {{ $book->author }}
                            </p>
                        </div>

                        @if($book->description)
                            <div class="book-description">
                                {{ $book->description }}
                            </div>
                        @endif

                        <div class="meta-grid">
                            <div class="meta-item">
                                <span class="meta-label">Category</span>
                                <strong class="meta-value">{{ $book->category }}</strong>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">ISBN</span>
                                <strong class="meta-value">{{ $book->isbn ?: 'Not provided' }}</strong>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Publisher</span>
                                <strong class="meta-value">{{ $book->publisher ?: 'Not provided' }}</strong>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">Language</span>
                                <strong class="meta-value">{{ $book->language ?: 'Not provided' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- INVENTORY INFORMATION --}}
                <div class="form-section">
                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="fas fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h4>Inventory Information</h4>
                            <span>Current stock and availability status.</span>
                        </div>
                    </div>

                    <div class="inventory-grid">
                        <div class="inventory-box">
                            <div class="inventory-icon">
                                <i class="fas fa-books"></i>
                            </div>
                            <span class="inventory-label">Total Copies</span>
                            <strong class="inventory-value">{{ $book->quantity }}</strong>
                        </div>

                        <div class="inventory-box available">
                            <div class="inventory-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span class="inventory-label">Available Copies</span>
                            <strong class="inventory-value">{{ $book->available_quantity }}</strong>
                        </div>

                        <div class="inventory-box issued">
                            <div class="inventory-icon">
                                <i class="fas fa-hand-holding"></i>
                            </div>
                            <span class="inventory-label">Issued Copies</span>
                            <strong class="inventory-value">
                                {{ $book->quantity - $book->available_quantity }}
                            </strong>
                        </div>

                        <div class="inventory-box">
                            <div class="inventory-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="inventory-label">Shelf Number</span>
                            <strong class="inventory-value">{{ $book->shelf_number ?: 'Not assigned' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- ADDITIONAL INFORMATION --}}
                <div class="form-section">
                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="fas fa-calendar-days"></i>
                        </div>
                        <div>
                            <h4>Additional Information</h4>
                            <span>Publication and record timestamps.</span>
                        </div>
                    </div>

                    <div class="additional-grid">
                        <div class="meta-item">
                            <span class="meta-label">Publication Date</span>
                            <strong class="meta-value">
                                {{ $book->publication_date
                                    ? $book->publication_date->format('d M Y')
                                    : 'Not provided' }}
                            </strong>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Added On</span>
                            <strong class="meta-value">
                                {{ $book->created_at->format('d M Y, h:i A') }}
                            </strong>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Last Updated</span>
                            <strong class="meta-value">
                                {{ $book->updated_at->format('d M Y, h:i A') }}
                            </strong>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="form-actions">
                <a href="{{ route('admin.library.books.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-xmark"></i>
                    Back
                </a>
                <a href="{{ route('admin.library.books.edit', $book) }}"
                   class="diary-primary-btn">
                    <i class="fas fa-pen-to-square"></i>
                    Edit Book
                </a>
            </div>
        </section>
    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY BOOKS — BOOK DETAILS PAGE
   Same tokens, cards and buttons as Meal Items / Edit Book.
   Every selector is scoped under .book-details-page.
========================================================= */
.book-details-page {
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

.book-details-page *,
.book-details-page *::before,
.book-details-page *::after {
    box-sizing: border-box;
}

.book-details-page a,
.book-details-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.book-details-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.book-details-page .form-hero,
.book-details-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.book-details-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.book-details-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.book-details-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.book-details-page .form-hero-icon {
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

.book-details-page .form-hero-text {
    min-width: 0;
}

.book-details-page .form-breadcrumb {
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

.book-details-page .form-breadcrumb span {
    color: #b8b09e;
}

.book-details-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.book-details-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.book-details-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */
.book-details-page .diary-primary-btn,
.book-details-page .diary-reset-btn {
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

.book-details-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.book-details-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.book-details-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.book-details-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* FORM CARD */
.book-details-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.book-details-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.book-details-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.book-details-page .panel-title-icon {
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

.book-details-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.book-details-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
    line-height: 1.3;
}

.book-details-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

/* STATUS BADGE */
.book-details-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 13px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
    white-space: nowrap;
}

.book-details-page .status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.book-details-page .status-available {
    background: #edf7ed;
    color: #347849;
    border: 1px solid #cfe5d3;
}

.book-details-page .status-available .status-dot {
    background: #347849;
}

.book-details-page .status-unavailable {
    background: #fff4ef;
    color: #a84840;
    border: 1px solid #f0d5ce;
}

.book-details-page .status-unavailable .status-dot {
    background: #a84840;
}

/* FORM BODY */
.book-details-page .form-body {
    padding: 24px 22px 8px;
}

/* BOOK TOP SECTION */
.book-details-page .book-top-section {
    display: flex;
    gap: 28px;
    margin-bottom: 28px;
    padding-bottom: 28px;
    border-bottom: 1px dashed #d8d1bf;
}

.book-details-page .book-cover-wrap {
    flex: 0 0 160px;
    width: 160px;
    height: 215px;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    background: var(--chalk);
    box-shadow: 0 4px 14px rgba(38, 59, 53, .06);
}

.book-details-page .book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.book-details-page .default-cover {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink);
    font-size: 42px;
    background: var(--chalk);
}

.book-details-page .book-main-info {
    flex: 1;
    min-width: 0;
}

.book-details-page .book-title-block h2 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 24px;
    font-weight: 700;
    line-height: 1.25;
    letter-spacing: -.3px;
}

.book-details-page .book-author {
    margin: 0 0 16px;
    color: var(--muted);
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 7px;
}

.book-details-page .book-author i {
    font-size: 12px;
    color: var(--amber);
}

.book-details-page .book-description {
    margin: 0 0 22px;
    color: var(--text);
    font-size: 13px;
    line-height: 1.75;
    max-width: 720px;
}

/* META GRID */
.book-details-page .meta-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px 24px;
}

.book-details-page .meta-item {
    min-width: 0;
}

.book-details-page .meta-label {
    display: block;
    margin-bottom: 5px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
}

.book-details-page .meta-value {
    color: var(--ink);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.4;
}

/* FORM SECTIONS */
.book-details-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.book-details-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
    padding-bottom: 10px;
}

.book-details-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.book-details-page .section-heading-icon {
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

.book-details-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.book-details-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* INVENTORY GRID */
.book-details-page .inventory-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
}

.book-details-page .inventory-box {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    text-align: left;
}

.book-details-page .inventory-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    margin-bottom: 12px;
    border: 1px solid #e3e2d5;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.book-details-page .inventory-label {
    display: block;
    margin-bottom: 6px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3px;
    text-transform: uppercase;
}

.book-details-page .inventory-value {
    color: var(--ink);
    font-size: 20px;
    font-weight: 700;
    font-family: var(--serif);
    line-height: 1.2;
}

.book-details-page .inventory-box.available {
    background: #edf7ed;
    border-color: #cfe5d3;
}

.book-details-page .inventory-box.available .inventory-icon {
    color: #347849;
    background: #fffefa;
    border-color: #cfe5d3;
}

.book-details-page .inventory-box.available .inventory-value {
    color: #347849;
}

.book-details-page .inventory-box.issued {
    background: #faf5e7;
    border-color: #e6dfc9;
}

.book-details-page .inventory-box.issued .inventory-icon {
    color: var(--amber);
    background: #fffefa;
    border-color: #e6dfc9;
}

.book-details-page .inventory-box.issued .inventory-value {
    color: #765e32;
}

/* ADDITIONAL GRID */
.book-details-page .additional-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 20px 24px;
}

/* ACTION BAR */
.book-details-page .form-actions {
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
    .book-details-page .form-hero {
        grid-template-columns: 1fr;
    }
    .book-details-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }

    .book-details-page .book-top-section {
        flex-direction: column;
        align-items: flex-start;
    }

    .book-details-page .book-cover-wrap {
        width: 140px;
        height: 188px;
        flex-basis: 140px;
    }

    .book-details-page .inventory-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .book-details-page .additional-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .book-details-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .book-details-page .form-hero::before {
        left: 22px;
    }

    .book-details-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .book-details-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .book-details-page .form-hero-text h2 {
        font-size: 23px;
    }

    .book-details-page .form-hero-text p {
        font-size: 12px;
    }

    .book-details-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .book-details-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .book-details-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
        flex-wrap: wrap;
    }

    .book-details-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .book-details-page .form-body {
        padding: 18px 15px 6px;
    }

    .book-details-page .book-title-block h2 {
        font-size: 20px;
    }

    .book-details-page .meta-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .book-details-page .inventory-grid {
        grid-template-columns: 1fr;
    }

    .book-details-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .book-details-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .book-details-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .book-details-page .section-heading {
        align-items: flex-start;
    }

    .book-details-page .book-cover-wrap {
        width: 120px;
        height: 160px;
        flex-basis: 120px;
    }
}

/* ACCESSIBILITY */
.book-details-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .book-details-page *,
    .book-details-page *::before,
    .book-details-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endsection