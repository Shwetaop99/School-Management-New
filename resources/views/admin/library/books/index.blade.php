@extends('layouts.app')

@section('content')


<style>
    .library-page { max-width: 1500px; margin: 0 auto; color: var(--text); }
    .library-header { display:flex; justify-content:space-between; align-items:flex-end; gap:16px; margin-bottom:26px; flex-wrap:wrap; }
    .library-title h1 { font-family:var(--serif); font-size:26px; font-weight:700; color:var(--ink); margin:0 0 4px; }
    .library-title p { color:var(--muted); font-size:14px; margin:0; }
    .paper, .filter-card, .books-card { background:#fff; border:1px solid var(--line); border-radius:10px; }
    .add-book-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; height:42px; padding:0 18px; border:0; border-radius:8px; background:var(--ink); color:#fff; text-decoration:none; font-size:14px; font-weight:600; transition:background .15s, transform .15s; }
    .add-book-btn:hover { background:var(--ink-2); color:#fff; transform:translateY(-1px); }
    .add-book-btn i { color:var(--chalk); }
    .alert { display:flex; align-items:center; gap:10px; padding:12px 15px; margin-bottom:18px; border-radius:9px; font-size:13.5px; font-weight:500; }
    .alert-success { background:#e3f4ea; color:#17703b; border:1px solid #bfe3cd; }
    .alert-error { background:#fdecea; color:#b3321d; border:1px solid #f5c2bb; }

    .library-stats { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:18px; margin-bottom:22px; }
    .library-stat-card { --c:#2b5d8a; position:relative; display:flex; align-items:center; justify-content:space-between; gap:12px; padding:22px 22px 20px; margin-top:10px; border-top:4px solid var(--c); border-radius:10px; background:#fff; box-shadow:0 2px 8px rgba(29,58,51,.05); transition:transform .22s ease,box-shadow .22s ease; }
    .library-stat-card:hover { transform:translateY(-6px); box-shadow:0 16px 28px rgba(29,58,51,.12); }
    .library-stat-card::before { content:attr(data-label); position:absolute; top:-24px; left:16px; padding:3px 12px; background:var(--c); color:#fff; font-size:12px; font-weight:600; border-radius:7px 7px 0 0; }
    .library-stat-card.blue { --c:#2b5d8a; }
    .library-stat-card.purple { --c:#7a4fb5; }
    .library-stat-card.green { --c:#3a9d6b; }
    .library-stat-card.red { --c:#e4572e; }
    .stat-icon { width:48px; height:48px; flex:0 0 48px; display:flex; align-items:center; justify-content:center; border-radius:12px; background:color-mix(in srgb,var(--c) 14%,#fff); color:var(--c); font-size:21px; }
    .stat-content { min-width:0; display:flex; flex-direction:column; }
    .stat-label { margin-bottom:5px; color:var(--muted); font-size:10px; font-weight:700; letter-spacing:.7px; }
    .stat-content strong { color:var(--ink); font-family:var(--serif); font-size:30px; line-height:1.1; font-weight:700; }
    .stat-content small { margin-top:5px; color:var(--muted); font-size:11px; }

    .filter-card { overflow:hidden; margin-bottom:22px; padding:0; background:#fffdf8; }
    .filter-form { display:grid; grid-template-columns:minmax(0,1.6fr) minmax(0,1fr) auto auto; gap:10px; align-items:center; padding:16px 22px; }
    .filter-group label { display:block; margin-bottom:7px; color:var(--text); font-size:12px; font-weight:600; }
    .filter-input,.filter-select { width:100%; height:42px; box-sizing:border-box; padding:0 11px; border:1px solid var(--line); border-radius:8px; background:#fff; color:var(--text); font:inherit; font-size:13px; outline:none; transition:border-color .15s,box-shadow .15s; }
    .filter-input:focus,.filter-select:focus { border-color:var(--ink); box-shadow:0 0 0 3px rgba(242,182,50,.4); }
    .filter-btn,.clear-btn { height:42px; box-sizing:border-box; padding:0 18px; display:inline-flex; align-items:center; justify-content:center; gap:6px; border-radius:8px; font:inherit; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap; cursor:pointer; }
    .filter-btn { border:0; background:var(--ink); color:#fff; }
    .filter-btn:hover { background:var(--ink-2); }
    .clear-btn { border:1px solid var(--line); background:#fff; color:var(--muted); }
    .clear-btn:hover { color:var(--ink); border-color:#b9b29c; }

    .books-card { overflow:hidden; }
    .books-card-header { display:flex; justify-content:space-between; align-items:center; gap:12px; padding:15px 22px; border-bottom:1px dashed #d8d1bf; background:#fff; flex-wrap:wrap; }
    .books-card-header h3 { margin:0; color:var(--ink); font-family:var(--serif); font-size:17px; font-weight:700; }
    .books-card-header p { margin:2px 0 0; color:var(--muted); font-size:12.5px; }
    .table-wrapper { overflow-x:auto; }
    .books-table { width:100%; min-width:1050px; border-collapse:collapse; }
    .books-table th { padding:12px 22px; background:#faf6ea; border-bottom:1px solid var(--line); color:var(--muted); font-size:12px; font-weight:600; text-align:left; white-space:nowrap; }
    .books-table td { padding:14px 22px; border-bottom:1px solid #f0ebdc; color:var(--text); font-size:13px; vertical-align:middle; white-space:nowrap; }
    .books-table tbody tr:hover td { background:#fffcf3; }
    .books-table tbody tr:last-child td { border-bottom:none; }
    .books-table th:last-child,.books-table td:last-child { width:150px; min-width:150px; }
    .books-table td:last-child { white-space:nowrap; }
    .book-cell { display:flex; align-items:center; gap:12px; min-width:250px; }
    .book-cover { display:flex; align-items:center; justify-content:center; width:44px; height:58px; flex:0 0 44px; overflow:hidden; border:1px solid var(--line); border-radius:6px; background:#faf6ea; }
    .book-cover img { width:100%; height:100%; object-fit:cover; }
    .cover-placeholder { color:var(--muted); font-size:10px; text-align:center; }
    .book-info { min-width:0; }
    .book-name { max-width:250px; overflow:hidden; color:var(--ink); font-weight:600; text-overflow:ellipsis; }
    .book-author { margin-top:3px; color:var(--muted); font-size:12px; }
    .category-badge { display:inline-flex; padding:4px 11px; border-radius:6px; background:var(--accent-soft); color:var(--accent); font-size:12px; font-weight:600; }
    .status-badge { display:inline-flex; align-items:center; gap:6px; padding:4px 11px; border-radius:999px; font-size:12px; font-weight:600; }
    .status-badge::before { content:""; width:6px; height:6px; border-radius:50%; background:currentColor; }
    .status-available { background:#e3f4ea; color:#17703b; }
    .status-unavailable { background:#fde8e4; color:#b3321d; }
    .quantity { color:var(--text); font-weight:600; }
    .available { color:#17703b; font-weight:700; }
    .unavailable { color:#b3321d; font-weight:700; }

    .actions { display:flex; flex-direction:row; flex-wrap:nowrap; align-items:center; justify-content:flex-start; gap:6px; min-width:126px; white-space:nowrap; }
    .actions form { display:inline-flex; flex:0 0 auto; margin:0; padding:0; }
    .action-btn { width:34px; height:34px; flex:0 0 34px; display:inline-flex; align-items:center; justify-content:center; padding:0; border:1px solid var(--line); border-radius:8px; background:#fff; color:var(--muted); font-size:15px; line-height:1; cursor:pointer; transition:all .15s; text-decoration:none; }
    .action-btn:hover { background:var(--accent-soft); border-color:#c5d6ea; color:var(--accent); }
    .delete-btn:hover { background:#fde8e4; border-color:#f5c2bb; color:#b3321d; }

    .empty-state { padding:60px 20px; text-align:center; }
    .empty-state i { display:block; font-size:36px; color:#a3b0c2; margin-bottom:10px; }
    .empty-state h4 { display:block; margin:0 0 4px; color:var(--ink); font-family:var(--serif); font-size:18px; }
    .empty-state p { margin:0; color:var(--muted); font-size:13px; }
    .pagination-wrapper { padding:14px 22px; border-top:1px dashed #d8d1bf; }

    @media(max-width:1250px) { .filter-form { grid-template-columns:repeat(3,minmax(0,1fr)); } .filter-group:first-child { grid-column:1/-1; } .filter-btn,.clear-btn { width:100%; } }
    @media(max-width:1100px) { .library-stats { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:650px) { .library-page { padding:0; } .library-stats,.filter-form { grid-template-columns:1fr; } .filter-group:first-child { grid-column:auto; } .library-header { flex-direction:column; align-items:stretch; } .add-book-btn { width:100%; } .filter-btn,.clear-btn { width:100%; } .library-stat-card { margin-top:18px; } }
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

    <div class="library-stat-card blue" data-label="Books">

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

    <div class="library-stat-card purple" data-label="Copies">

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

    <div class="library-stat-card green" data-label="Available">

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

    <div class="library-stat-card red" data-label="Unavailable">

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

        title="View book"

        aria-label="View book"

    >

        <i class="fas fa-eye"></i>

    </a>

    <a

        href="{{ route('admin.library.books.edit', $book) }}"

        class="action-btn edit-btn"

        title="Edit book"

        aria-label="Edit book"

    >

        <i class="fas fa-pen-to-square"></i>

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

            title="Delete book"

            aria-label="Delete book"

        >

            <i class="fas fa-trash-can"></i>

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
