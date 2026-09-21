@extends('layouts.app')

@section('content')

<div class="book-details-page">

    {{-- Header --}}
    <div class="book-details-header">
        <div>
            <h1>Book Details</h1>
            <p>View complete information about this library book.</p>
        </div>

        <a href="{{ route('admin.library.books.index') }}" class="back-btn">
            ← Back to Books
        </a>
    </div>

    {{-- Main Book Card --}}
    <div class="book-details-card">

        {{-- Book Top Section --}}
        <div class="book-top">

            {{-- Cover --}}
            <div class="book-cover">

                @if($book->cover_image)
                    <img
    src="{{ $book->cover_image }}"
    alt="{{ $book->title }}"
>
                @else
                    <div class="default-cover">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            <path d="M8 6h8"/>
                            <path d="M8 10h8"/>
                        </svg>
                    </div>
                @endif

            </div>

            {{-- Basic Information --}}
            <div class="book-main-info">

                <div class="title-row">

                    <div>
                        <h2>{{ $book->title }}</h2>

                        <p class="author">
                            By {{ $book->author }}
                        </p>
                    </div>

                    <span class="status-badge
                        {{ $book->status === 'Available'
                            ? 'status-available'
                            : 'status-unavailable' }}">

                        <span class="status-dot"></span>

                        {{ $book->status }}

                    </span>

                </div>

                @if($book->description)
                    <p class="description">
                        {{ $book->description }}
                    </p>
                @endif

                <div class="book-meta">

                    <div class="meta-item">
                        <span>Category</span>
                        <strong>{{ $book->category }}</strong>
                    </div>

                    <div class="meta-item">
                        <span>ISBN</span>
                        <strong>{{ $book->isbn ?: 'Not provided' }}</strong>
                    </div>

                    <div class="meta-item">
                        <span>Publisher</span>
                        <strong>{{ $book->publisher ?: 'Not provided' }}</strong>
                    </div>

                    <div class="meta-item">
                        <span>Language</span>
                        <strong>{{ $book->language ?: 'Not provided' }}</strong>
                    </div>

                </div>

            </div>

        </div>


        {{-- Inventory Section --}}
        <div class="details-section">

            <h3>Inventory Information</h3>

            <div class="inventory-grid">

                <div class="inventory-box">
                    <span>Total Copies</span>
                    <strong>{{ $book->quantity }}</strong>
                </div>

                <div class="inventory-box available">
                    <span>Available Copies</span>
                    <strong>{{ $book->available_quantity }}</strong>
                </div>

                <div class="inventory-box issued">
                    <span>Issued Copies</span>
                    <strong>
                        {{ $book->quantity - $book->available_quantity }}
                    </strong>
                </div>

                <div class="inventory-box">
                    <span>Shelf Number</span>
                    <strong>{{ $book->shelf_number ?: 'Not assigned' }}</strong>
                </div>

            </div>

        </div>


        {{-- Additional Information --}}
        <div class="details-section">

            <h3>Additional Information</h3>

            <div class="additional-grid">

                <div>
                    <span>Publication Date</span>

                    <strong>
                        {{ $book->publication_date
                            ? $book->publication_date->format('d M Y')
                            : 'Not provided' }}
                    </strong>
                </div>

                <div>
                    <span>Added On</span>

                    <strong>
                        {{ $book->created_at->format('d M Y, h:i A') }}
                    </strong>
                </div>

                <div>
                    <span>Last Updated</span>

                    <strong>
                        {{ $book->updated_at->format('d M Y, h:i A') }}
                    </strong>
                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="book-actions">

            <a href="{{ route('admin.library.books.index') }}"
               class="secondary-btn">
                Back
            </a>

            <a href="{{ route('admin.library.books.edit', $book) }}"
               class="primary-btn">
                Edit Book
            </a>

        </div>

    </div>

</div>


<style>

/* =========================
   PAGE
========================= */

.book-details-page {
    padding: 10px 5px;
}


/* =========================
   HEADER
========================= */

.book-details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.book-details-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #172033;
}

.book-details-header p {
    margin: 6px 0 0;
    color: #7b8497;
    font-size: 14px;
}

.back-btn {
    height: 42px;
    padding: 0 16px;
    border: 1px solid #dfe4ec;
    border-radius: 9px;
    background: #fff;
    color: #344054;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 600;
}

.back-btn:hover {
    background: #f5f7fa;
}


/* =========================
   MAIN CARD
========================= */

.book-details-card {
    background: #fff;
    border: 1px solid #edf0f5;
    border-radius: 16px;
    box-shadow: 0 4px 18px rgba(30, 50, 80, 0.07);
    overflow: hidden;
}


/* =========================
   BOOK TOP
========================= */

.book-top {
    display: flex;
    gap: 30px;
    padding: 30px;
}


/* =========================
   COVER
========================= */

.book-cover {
    width: 180px;
    height: 240px;
    flex-shrink: 0;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e5e9f0;
    background: #f5f8fc;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-cover {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1976d2;
    background: #eef4ff;
}

.default-cover svg {
    width: 70px;
    height: 70px;
}


/* =========================
   MAIN INFO
========================= */

.book-main-info {
    flex: 1;
    min-width: 0;
}

.title-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    align-items: flex-start;
}

.title-row h2 {
    margin: 0;
    font-size: 25px;
    color: #172033;
}

.author {
    margin: 7px 0 0;
    color: #7b8497;
    font-size: 14px;
}

.description {
    margin: 22px 0;
    color: #596579;
    font-size: 13px;
    line-height: 1.7;
    max-width: 800px;
}


/* =========================
   STATUS
========================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    flex-shrink: 0;
}

.status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.status-available {
    background: #e9f7ed;
    color: #2e7d32;
}

.status-available .status-dot {
    background: #2e7d32;
}

.status-unavailable {
    background: #ffebee;
    color: #d32f2f;
}

.status-unavailable .status-dot {
    background: #d32f2f;
}


/* =========================
   META
========================= */

.book-meta {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px 30px;
    margin-top: 25px;
}

.meta-item span,
.additional-grid span {
    display: block;
    color: #9099a9;
    font-size: 11px;
    margin-bottom: 5px;
}

.meta-item strong,
.additional-grid strong {
    color: #354052;
    font-size: 13px;
}


/* =========================
   SECTIONS
========================= */

.details-section {
    padding: 25px 30px;
    border-top: 1px solid #edf0f5;
}

.details-section h3 {
    margin: 0 0 18px;
    color: #172033;
    font-size: 16px;
}


/* =========================
   INVENTORY
========================= */

.inventory-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.inventory-box {
    padding: 18px;
    border: 1px solid #e7ebf1;
    border-radius: 11px;
    background: #fafbfc;
}

.inventory-box span {
    display: block;
    color: #7b8497;
    font-size: 11px;
    margin-bottom: 7px;
}

.inventory-box strong {
    color: #172033;
    font-size: 21px;
}

.inventory-box.available {
    background: #f4fbf5;
    border-color: #dcefe0;
}

.inventory-box.available strong {
    color: #2e7d32;
}

.inventory-box.issued {
    background: #fff9f1;
    border-color: #f3e4cd;
}

.inventory-box.issued strong {
    color: #b76a00;
}


/* =========================
   ADDITIONAL
========================= */

.additional-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}


/* =========================
   ACTIONS
========================= */

.book-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px 30px;
    border-top: 1px solid #edf0f5;
    background: #fafbfc;
}

.primary-btn,
.secondary-btn {
    height: 42px;
    padding: 0 18px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
}

.primary-btn {
    background: #1976d2;
    color: #fff;
}

.primary-btn:hover {
    background: #1565c0;
    color: #fff;
}

.secondary-btn {
    border: 1px solid #dfe4ec;
    background: #fff;
    color: #344054;
}

.secondary-btn:hover {
    background: #f5f7fa;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {

    .book-top {
        flex-direction: column;
    }

    .book-cover {
        width: 150px;
        height: 200px;
    }

    .inventory-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .additional-grid {
        grid-template-columns: 1fr;
        gap: 18px;
    }
}

@media (max-width: 600px) {

    .book-details-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .book-top,
    .details-section {
        padding: 20px;
    }

    .title-row {
        flex-direction: column;
    }

    .book-meta {
        grid-template-columns: 1fr;
    }

    .inventory-grid {
        grid-template-columns: 1fr;
    }

    .book-actions {
        padding: 15px 20px;
        flex-direction: column-reverse;
    }

    .primary-btn,
    .secondary-btn {
        width: 100%;
    }
}

</style>

@endsection