@extends('layouts.app')

@section('title', 'Library | Admin')
@section('page-title', 'Library Management')

@section('content')

@php
    $totalBooks        = $books->total();
    $totalQuantity     = $books->sum('quantity');
    $availableQuantity = $books->sum('available_quantity');
    $unavailableBooks  = $books->where('status', 'Unavailable')->count();
@endphp

<div class="library-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="library-success-alert" role="status">
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
            <div class="library-error-alert" role="alert">
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
        <section class="library-hero">
            <div class="library-hero-content">
                <div class="library-hero-icon">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="library-hero-text">
                    <div class="library-breadcrumb">
                        ADMIN <span>/</span> LIBRARY
                    </div>

                    <h2>Library Management</h2>

                    <p>
                        Manage books, copies and library resources
                        from one central place.
                    </p>

                    <div class="library-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Library Catalogue &amp; Stock Control</span>
                    </div>
                </div>
            </div>

            <div class="library-hero-actions">
                <div class="hero-handwritten">Read. Borrow. Return.</div>

                <p>
                    Keep the collection organised so every
                    student can find the right book.
                </p>

                <a href="{{ route('admin.library.books.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Book
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="library-kpis">

            <article class="library-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Books</span>
                    <span class="kpi-icon">
                        <i class="fas fa-book"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalBooks }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Total books in library
                </div>
            </article>

            <article class="library-kpi cyan">
                <div class="kpi-top">
                    <span class="kpi-label">Total Copies</span>
                    <span class="kpi-icon">
                        <i class="fas fa-copy"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalQuantity }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-books"></i>
                    All book copies
                </div>
            </article>

            <article class="library-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Available Copies</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $availableQuantity }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Currently available
                </div>
            </article>

            <article class="library-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Unavailable Books</span>
                    <span class="kpi-icon">
                        <i class="fas fa-ban"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $unavailableBooks }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-book-skull"></i>
                    Currently unavailable
                </div>
            </article>

        </section>

        {{-- BOOKS TABLE --}}
        <section class="library-table-card">

            <div class="library-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">BOOK REGISTER</div>
                        <h3>All Books</h3>
                        <p>View, edit and manage your library collection.</p>
                    </div>
                </div>

                <div class="library-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalBooks }}</strong>
                    {{ $totalBooks == 1 ? 'Book' : 'Books' }}
                </div>
            </div>

            {{-- FILTERS --}}
            <form method="GET"
                  action="{{ route('admin.library.books.index') }}"
                  class="library-filters">

                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by title, author or ISBN..."
                           aria-label="Search books">
                </div>

                <select name="category" aria-label="Category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}"
                            @selected(request('category') == $category)>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="diary-primary-btn">
                    <i class="fas fa-search"></i>
                    Search
                </button>

                @if(request()->hasAny(['search','category']))
                    <a href="{{ route('admin.library.books.index') }}"
                       class="diary-secondary-btn">
                        Clear
                    </a>
                @endif
            </form>

            <div class="library-table-wrapper">
                <table class="library-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Book</th>
                            <th scope="col">ISBN</th>
                            <th scope="col">Category</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Available</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($books as $book)
                            @php
                                $isAvailable = $book->status === 'Available';
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $books->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="book-info">
                                        <div class="book-cover">
                                            @if($book->cover_image)
                                                <img src="{{ $book->cover_image }}"
                                                     alt="{{ $book->title }}"
                                                     loading="lazy">
                                            @else
                                                <i class="fas fa-book"></i>
                                            @endif
                                        </div>

                                        <div class="book-details">
                                            <div class="book-name">
                                                {{ $book->title }}
                                            </div>
                                            <div class="book-description">
                                                {{ $book->author ?: 'Author not added' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="isbn-value">
                                        {{ $book->isbn ?: '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="category-badge">
                                        {{ $book->category ?: 'General' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $book->quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="stock-badge {{ $book->available_quantity > 0 ? 'in-stock' : 'out-stock' }}">
                                        {{ $book->available_quantity }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge {{ $isAvailable ? 'available' : 'unavailable' }}">
                                        <span class="status-dot"></span>
                                        {{ $isAvailable ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="library-actions">

                                        <a href="{{ route('admin.library.books.show', $book) }}"
                                           class="game-action-btn view"
                                           title="View Book"
                                           aria-label="View {{ $book->title }}">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.library.books.edit', $book) }}"
                                           class="game-action-btn edit"
                                           title="Edit Book"
                                           aria-label="Edit {{ $book->title }}">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.library.books.destroy', $book) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this book?');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="game-action-btn delete"
                                                    title="Delete Book"
                                                    aria-label="Delete {{ $book->title }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="library-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-book-open"></i>
                                        </div>

                                        <h4>No Books Found</h4>

                                        <p>
                                            There are no books matching your current
                                            search or filter. Try changing them or add a new book.
                                        </p>

                                        <div class="empty-state-actions">
                                            <a href="{{ route('admin.library.books.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add Book
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
            <div class="library-card-footer">
                <div>
                    Showing
                    <strong>{{ $books->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $books->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ $books->total() }}</strong>
                    book(s)
                </div>

                @if($books->hasPages())
                    <div class="library-pagination">
                        {{ $books->withQueryString()->links() }}
                    </div>
                @else
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Book register loaded successfully
                    </div>
                @endif
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY BOOKS — INDEX PAGE
   Same tokens, cards, buttons and layout as Roles / Notices / Users.
   Every selector is scoped under .library-page.
========================================================= */

.library-page {
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

.library-page *,
.library-page *::before,
.library-page *::after {
    box-sizing: border-box;
}

.library-page a,
.library-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.library-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.library-page .library-hero,
.library-page .library-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.library-page .library-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.library-page .library-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.library-page .library-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.library-page .library-hero-icon {
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

.library-page .library-hero-text {
    min-width: 0;
}

.library-page .library-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.library-page .library-breadcrumb span {
    color: #b8b09e;
}

.library-page .library-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.library-page .library-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.library-page .library-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.library-page .library-hero-actions {
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

.library-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.library-page .library-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.library-page .diary-primary-btn {
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

.library-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.library-page .library-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.library-page .library-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

.library-page .diary-secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--line);
    border-radius: 7px;
    color: var(--muted) !important;
    background: #fff;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.library-page .diary-secondary-btn:hover {
    border-color: #b9b29c;
    color: var(--ink) !important;
    background: #faf9f4;
}

/* ALERTS */

.library-page .library-success-alert,
.library-page .library-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.library-page .library-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.library-page .library-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.library-page .success-icon,
.library-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.library-page .success-icon { background: #dcefd9; }
.library-page .error-icon { background: #fbe0d8; }

.library-page .alert-close {
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

.library-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.library-page .library-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.library-page .library-kpi {
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

.library-page .library-kpi::before {
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

.library-page .library-kpi.blue  { --kpi-color: #2b5d8a; }
.library-page .library-kpi.cyan  { --kpi-color: #527f79; }
.library-page .library-kpi.green { --kpi-color: #3a8060; }
.library-page .library-kpi.red   { --kpi-color: #bd5148; }

.library-page .library-kpi.blue::before  { content: "Books"; }
.library-page .library-kpi.cyan::before  { content: "Copies"; }
.library-page .library-kpi.green::before { content: "Available"; }
.library-page .library-kpi.red::before   { content: "Unavailable"; }

.library-page .library-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.library-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.library-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.library-page .library-kpi .kpi-icon {
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

.library-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.library-page .kpi-caption {
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

.library-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.library-page .library-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.library-page .library-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.library-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.library-page .panel-title-icon {
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

.library-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.library-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.library-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.library-page .library-record-count {
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

.library-page .library-record-count i,
.library-page .library-record-count strong {
    color: var(--ink);
}

/* FILTERS */

.library-page .library-filters {
    display: grid;
    grid-template-columns: minmax(0, 1.6fr) minmax(0, 1fr) auto auto;
    align-items: center;
    gap: 10px;
    padding: 16px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: #faf9f4;
}

.library-page .filter-search {
    position: relative;
}

.library-page .filter-search i {
    position: absolute;
    top: 50%;
    left: 13px;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: 12px;
    pointer-events: none;
}

.library-page .library-filters input,
.library-page .library-filters select {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid var(--line);
    border-radius: 7px;
    color: var(--text);
    background: var(--paper);
    font-family: inherit;
    font-size: 12px;
    outline: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.library-page .library-filters input {
    padding-left: 36px;
}

.library-page .library-filters input:focus,
.library-page .library-filters select:focus {
    border-color: var(--ink);
    box-shadow: 0 0 0 3px rgba(38, 59, 53, .1);
}

/* TABLE */

.library-page .library-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.library-page .library-table {
    width: 100%;
    min-width: 1050px;
    margin: 0;
    border-collapse: collapse;
}

.library-page .library-table thead th {
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

.library-page .library-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.library-page .library-table tbody tr {
    transition: background .18s ease;
}

.library-page .library-table tbody tr:hover {
    background: #faf9f2;
}

.library-page .library-table tbody tr:last-child td {
    border-bottom: none;
}

.library-page .library-table .actions-heading {
    text-align: right;
}

.library-page .record-number {
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

.library-page .book-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 260px;
}

.library-page .book-cover {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 54px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    border-radius: 6px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.library-page .book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.library-page .book-details {
    min-width: 0;
}

.library-page .book-name {
    max-width: 260px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.library-page .book-description {
    max-width: 280px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.library-page .isbn-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.library-page .category-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 28px;
    padding: 0 10px;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    color: var(--blue);
    background: #f0f5f8;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.library-page .count-badge,
.library-page .stock-badge {
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
}

.library-page .stock-badge.in-stock {
    border-color: #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.library-page .stock-badge.out-stock {
    border-color: #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

/* STATUS BADGES */

.library-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.library-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.library-page .status-badge.available {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.library-page .status-badge.available .status-dot {
    background: #3a8060;
}

.library-page .status-badge.unavailable {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.library-page .status-badge.unavailable .status-dot {
    background: #bd5148;
}

/* ACTION BUTTONS */

.library-page .library-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.library-page .library-actions form {
    display: inline-flex;
    margin: 0;
}

.library-page .game-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none !important;
    cursor: pointer;
}

.library-page .game-action-btn:hover {
    transform: translateY(-2px);
}

.library-page .game-action-btn.view:hover {
    border-color: #c5d6ea;
    color: var(--blue);
    background: #f0f5f8;
}

.library-page .game-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.library-page .game-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* EMPTY STATE */

.library-page .library-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.library-page .empty-state-icon {
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

.library-page .library-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.library-page .library-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.library-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.library-page .library-card-footer {
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

.library-page .library-card-footer strong {
    color: var(--ink);
}

.library-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.library-page .footer-status i {
    color: var(--green);
}

/* PAGINATION (Laravel / Bootstrap) */

.library-page .library-pagination nav > div:first-child {
    display: none;
}

.library-page .library-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.library-page .library-pagination .page-link,
.library-page .library-pagination .pagination span,
.library-page .library-pagination .pagination a {
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

.library-page .library-pagination .page-item.active .page-link,
.library-page .library-pagination .pagination [aria-current="page"] span {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.library-page .library-pagination .page-item.disabled .page-link {
    opacity: .5;
}

.library-page .library-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1250px) {
    .library-page .library-filters {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .library-page .filter-search {
        grid-column: 1 / -1;
    }

    .library-page .library-filters .diary-primary-btn,
    .library-page .library-filters .diary-secondary-btn {
        width: 100%;
    }
}

@media (max-width: 1100px) {
    .library-page .library-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .library-page .library-hero {
        grid-template-columns: 1fr;
    }

    .library-page .library-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .library-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .library-page .library-hero::before {
        left: 22px;
    }

    .library-page .library-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .library-page .library-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .library-page .library-hero-text h2 {
        font-size: 23px;
    }

    .library-page .library-hero-text p {
        font-size: 12px;
    }

    .library-page .library-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .library-page .library-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .library-page .library-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .library-page .library-kpi {
        padding: 20px 16px 15px;
    }

    .library-page .kpi-value {
        font-size: 30px;
    }

    .library-page .library-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .library-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .library-page .library-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .library-page .library-filters {
        grid-template-columns: 1fr;
        padding: 14px 15px;
    }

    .library-page .library-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .library-page .library-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .library-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .library-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .library-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.library-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .library-page *,
    .library-page *::before,
    .library-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection