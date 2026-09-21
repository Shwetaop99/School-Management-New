@extends('layouts.app')

@section('content')

<style>
    .library-page {
        padding: 24px;
    }

    .library-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .library-title h1 {
        margin: 0;
        color: #172033;
        font-size: 24px;
        font-weight: 700;
    }

    .library-title p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .add-book-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        background: #1976d2;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(25, 118, 210, .18);
        transition: .2s;
    }

    .add-book-btn:hover {
        background: #1565c0;
        color: #fff;
        transform: translateY(-1px);
    }

    .alert {
        padding: 12px 15px;
        margin-bottom: 18px;
        border-radius: 8px;
        font-size: 13px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    .library-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
}

.library-stat-card {
    position: relative;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 14px;
    color: #fff;
    overflow: hidden;
    min-height: 115px;
    box-shadow: 0 6px 18px rgba(25, 45, 75, 0.10);
}

.library-stat-card::after {
    content: "";
    position: absolute;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    right: -25px;
    bottom: -35px;
    background: rgba(255, 255, 255, 0.12);
}

.library-stat-card.blue {
    background: linear-gradient(135deg, #1976d2, #42a5f5);
}

.library-stat-card.purple {
    background: linear-gradient(135deg, #7b1fa2, #ab47bc);
}

.library-stat-card.green {
    background: linear-gradient(135deg, #2e7d32, #66bb6a);
}

.library-stat-card.red {
    background: linear-gradient(135deg, #c62828, #ef5350);
}

.stat-icon {
    position: relative;
    z-index: 2;
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.stat-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
    opacity: .9;
    margin-bottom: 5px;
}

.stat-content strong {
    font-size: 26px;
    line-height: 1.1;
    font-weight: 750;
}

.stat-content small {
    margin-top: 5px;
    font-size: 11px;
    opacity: .85;
}

@media (max-width: 1100px) {
    .library-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .library-stats {
        grid-template-columns: 1fr;
    }
}
    .filter-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 22px;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 1.5fr 1fr auto auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        color: #455066;
        font-size: 12px;
        font-weight: 600;
    }

    .filter-input,
    .filter-select {
        width: 100%;
        height: 40px;
        box-sizing: border-box;
        padding: 8px 11px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        background: #fff;
        color: #343d50;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, .08);
    }

    .filter-btn,
    .clear-btn {
        height: 40px;
        padding: 0 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    .filter-btn {
        border: none;
        background: #1976d2;
        color: #fff;
    }

    .filter-btn:hover {
        background: #1565c0;
    }

    .clear-btn {
        border: 1px solid #dce2eb;
        background: #fff;
        color: #596477;
    }

    .clear-btn:hover {
        background: #f7f9fc;
    }

    .books-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
    }

    .books-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .books-card-header h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .books-card-header p {
        margin: 5px 0 0;
        color: #8a93a5;
        font-size: 12px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .books-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .books-table th {
        padding: 13px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #edf0f5;
        color: #70798c;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }

    .books-table td {
        padding: 14px 18px;
        border-top: 1px solid #edf0f5;
        color: #343d50;
        font-size: 13px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .books-table tbody tr:hover {
        background: #fafcff;
    }

    .book-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 250px;
    }

    .book-cover {
        width: 44px;
        height: 58px;
        flex: 0 0 44px;
        border-radius: 6px;
        overflow: hidden;
        background: #f1f4f8;
        border: 1px solid #e5e9ef;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cover-placeholder {
        color: #9aa4b4;
        font-size: 10px;
        text-align: center;
    }

    .book-info {
        min-width: 0;
    }

    .book-name {
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #172033;
        font-weight: 650;
    }

    .book-author {
        margin-top: 3px;
        color: #8a93a5;
        font-size: 11px;
    }

    .category-badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        background: #eef4ff;
        color: #315fa8;
        font-size: 11px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-available {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-unavailable {
        background: #ffebee;
        color: #c62828;
    }

    .quantity {
        font-weight: 700;
        color: #343d50;
    }

    .available {
        color: #2e7d32;
        font-weight: 700;
    }

    .unavailable {
        color: #c62828;
        font-weight: 700;
    }

    .actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 7px;
        text-decoration: none;
        border: none;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
    }

    .view-btn {
        background: #eef4ff;
        color: #315fa8;
    }

    .view-btn:hover {
        background: #dfeaff;
        color: #315fa8;
    }

    .edit-btn {
        background: #fff3e0;
        color: #ef6c00;
    }

    .edit-btn:hover {
        background: #ffe5bd;
        color: #ef6c00;
    }

    .delete-btn {
        background: #ffebee;
        color: #d32f2f;
    }

    .delete-btn:hover {
        background: #ffcdd2;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
        color: #7b8497;
    }

    .empty-state h4 {
        margin: 0 0 7px;
        color: #343d50;
        font-size: 15px;
    }

    .empty-state p {
        margin: 0;
        font-size: 12px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f5;
    }

    @media (max-width: 1000px) {
        .library-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .library-page {
            padding: 15px;
        }

        .library-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .add-book-btn {
            width: 100%;
            justify-content: center;
        }

        .library-stats {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="library-page">

    {{-- Header --}}
    <div class="library-header">

        <div class="library-title">
            <h1>Library Management</h1>
            <p>Manage books and library resources.</p>
        </div>

        <a href="{{ route('admin.library.books.create') }}"
           class="add-book-btn">
            + Add Book
        </a>

    </div>


    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif


    {{-- Statistics --}}
    @php
        $totalBooks = $books->total();

        $totalQuantity = $books->sum('quantity');

        $availableQuantity = $books->sum('available_quantity');

        $unavailableBooks = $books->where('status', 'Unavailable')->count();
    @endphp

    <div class="library-stats">

    {{-- Books --}}
    <div class="library-stat-card blue">
        <div class="stat-icon">
            📚
        </div>

        <div class="stat-content">
            <span class="stat-label">BOOKS</span>
            <strong>{{ $totalBooks }}</strong>
            <small>Total books in library</small>
        </div>
    </div>


    {{-- Total Copies --}}
    <div class="library-stat-card purple">
        <div class="stat-icon">
            📖
        </div>

        <div class="stat-content">
            <span class="stat-label">TOTAL COPIES</span>
            <strong>{{ $totalQuantity }}</strong>
            <small>All book copies</small>
        </div>
    </div>


    {{-- Available Copies --}}
    <div class="library-stat-card green">
        <div class="stat-icon">
            ✓
        </div>

        <div class="stat-content">
            <span class="stat-label">AVAILABLE COPIES</span>
            <strong>{{ $availableQuantity }}</strong>
            <small>Currently available</small>
        </div>
    </div>


    {{-- Unavailable Books --}}
    <div class="library-stat-card red">
        <div class="stat-icon">
            !
        </div>

        <div class="stat-content">
            <span class="stat-label">UNAVAILABLE BOOKS</span>
            <strong>{{ $unavailableBooks }}</strong>
            <small>Currently unavailable</small>
        </div>
    </div>

</div>


    {{-- Filters --}}
    <div class="filter-card">

        <form method="GET"
              action="{{ route('admin.library.books.index') }}"
              class="filter-form">

            <div class="filter-group">

                <label for="search">
                    Search Books
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    class="filter-input"
                    value="{{ request('search') }}"
                    placeholder="Search by title, author or ISBN..."
                >

            </div>


            <div class="filter-group">

                <label for="category">
                    Category
                </label>

                <select
                    id="category"
                    name="category"
                    class="filter-select"
                >

                    <option value="">
                        All Categories
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category }}"
                            {{ request('category') == $category ? 'selected' : '' }}
                        >
                            {{ $category }}
                        </option>

                    @endforeach

                </select>

            </div>


            <button type="submit"
                    class="filter-btn">
                Search
            </button>


            <a href="{{ route('admin.library.books.index') }}"
               class="clear-btn">
                Clear
            </a>

        </form>

    </div>


    {{-- Books --}}
    <div class="books-card">

        <div class="books-card-header">

            <h3>Books</h3>

            <p>
                View, edit and manage your library collection.
            </p>

        </div>


        <div class="table-wrapper">

            @if($books->count())

                <table class="books-table">

                    <thead>

                        <tr>
                            <th>Book</th>
                            <th>ISBN</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Available</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($books as $book)

                            <tr>

                                {{-- Book --}}
                                <td>

                                    <div class="book-cell">

                                        <div class="book-cover">

                                            @if($book->cover_image)

                                                <img
                                                    src="{{ $book->cover_image }}"
                                                    alt="{{ $book->title }}"
                                                    loading="lazy"
                                                >

                                            @else

                                                <div class="cover-placeholder">
                                                    No Cover
                                                </div>

                                            @endif

                                        </div>


                                        <div class="book-info">

                                            <div class="book-name">
                                                {{ $book->title }}
                                            </div>

                                            <div class="book-author">
                                                {{ $book->author }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- ISBN --}}
                                <td>
                                    {{ $book->isbn ?: '—' }}
                                </td>


                                {{-- Category --}}
                                <td>

                                    <span class="category-badge">
                                        {{ $book->category ?: 'General' }}
                                    </span>

                                </td>


                                {{-- Quantity --}}
                                <td>

                                    <span class="quantity">
                                        {{ $book->quantity }}
                                    </span>

                                </td>


                                {{-- Available --}}
                                <td>

                                    <span class="{{ $book->available_quantity > 0 ? 'available' : 'unavailable' }}">
                                        {{ $book->available_quantity }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($book->status === 'Available')

                                        <span class="status-badge status-available">
                                            Available
                                        </span>

                                    @else

                                        <span class="status-badge status-unavailable">
                                            Unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td>

                                    <div class="actions">

                                        <a
                                            href="{{ route('admin.library.books.show', $book) }}"
                                            class="action-btn view-btn"
                                        >
                                            View
                                        </a>


                                        <a
                                            href="{{ route('admin.library.books.edit', $book) }}"
                                            class="action-btn edit-btn"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            action="{{ route('admin.library.books.destroy', $book) }}"
                                            method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this book?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="action-btn delete-btn"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <h4>No books found</h4>

                    <p>
                        There are no books matching your current search or filter.
                    </p>

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if($books->hasPages())

            <div class="pagination-wrapper">
                {{ $books->withQueryString()->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
