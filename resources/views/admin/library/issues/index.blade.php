@extends('layouts.app')

@section('content')

<style>
    .issue-page {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
    }

    .page-title h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #172033;
    }

    .page-title p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .issue-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #1976d2;
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.18);
        transition: .2s;
    }

    .issue-btn:hover {
        background: #1565c0;
        color: #fff;
        transform: translateY(-1px);
    }

    /* Library Action Cards */

    .library-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .library-action-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        text-decoration: none;
        box-shadow: 0 3px 12px rgba(25, 45, 75, 0.04);
        transition: all .2s ease;
    }

    .library-action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(25, 45, 75, 0.08);
        border-color: #d7e2f0;
    }

    .action-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-icon svg {
        width: 24px;
        height: 24px;
    }

    .issue-icon {
        background: #e3f2fd;
        color: #1976d2;
    }

    .return-icon {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .fine-icon {
        background: #fff3e0;
        color: #ef6c00;
    }

    .action-content {
        flex: 1;
    }

    .action-content h3 {
        margin: 0 0 4px;
        color: #172033;
        font-size: 15px;
        font-weight: 700;
    }

    .action-content p {
        margin: 0;
        color: #8a93a5;
        font-size: 11px;
    }

    .action-arrow {
        color: #a0a8b7;
        font-size: 20px;
        font-weight: 500;
    }

    .library-action-card:hover .action-arrow {
        color: #1976d2;
    }

    /* Alerts */

    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
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

    /* Table */

    .table-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, 0.04);
    }

    .table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .table-header h3 {
        margin: 0;
        font-size: 15px;
        color: #172033;
        font-weight: 700;
    }

    .table-header p {
        margin: 5px 0 0;
        color: #8a93a5;
        font-size: 12px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .issue-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .issue-table th {
        background: #f8fafc;
        color: #70798c;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
        font-weight: 700;
        padding: 13px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .issue-table td {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
        color: #343d50;
        font-size: 13px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .issue-table tbody tr {
        transition: .15s;
    }

    .issue-table tbody tr:hover {
        background: #fafcff;
    }

    .book-title {
        font-weight: 650;
        color: #172033;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .student-id {
        font-weight: 600;
        color: #455066;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-issued {
        background: #e3f2fd;
        color: #1976d2;
    }

    .status-returned {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-overdue {
        background: #ffebee;
        color: #d32f2f;
    }

    .fine {
        font-weight: 700;
    }

    .fine-zero {
        color: #7b8497;
    }

    .fine-positive {
        color: #d32f2f;
    }

    .fine-type {
        display: block;
        margin-top: 3px;
        color: #8a93a5;
        font-size: 10px;
        font-weight: 500;
    }

    .return-btn {
        border: none;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 8px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .return-btn:hover {
        background: #c8e6c9;
    }

    .returned-label {
        color: #8a93a5;
        font-size: 12px;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
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

    @media (max-width: 900px) {
        .library-actions {
            grid-template-columns: 1fr;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .issue-table {
            min-width: 1000px;
        }
    }

    @media (max-width: 700px) {
        .issue-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .issue-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="issue-page">

    {{-- Page Header --}}
    <div class="page-header">

        <div class="page-title">
            <h1>Issue / Return Books</h1>
            <p>Manage issued books, returns and library fines.</p>
        </div>

        <a href="{{ route('admin.library.issues.create') }}"
           class="issue-btn">
            + Issue Book
        </a>

    </div>


    {{-- Library Action Cards --}}
    <div class="library-actions">

        {{-- Issue Book --}}
        <a href="{{ route('admin.library.issues.create') }}"
           class="library-action-card">

            <div class="action-icon issue-icon">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path d="M4 4h16v16H4z"/>
                    <path d="M8 8h8"/>
                    <path d="M8 12h5"/>
                    <path d="M12 16v-3"/>
                    <path d="M10.5 14.5h3"/>
                </svg>
            </div>

            <div class="action-content">
                <h3>Issue Book</h3>
                <p>Issue a book to a student</p>
            </div>

            <div class="action-arrow">→</div>

        </a>


        {{-- Return Books --}}
        <a href="{{ route('admin.library.returns.index') }}"
           class="library-action-card">

            <div class="action-icon return-icon">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path d="M9 14l-4-4 4-4"/>
                    <path d="M5 10h9a5 5 0 0 1 5 5v1"/>
                    <path d="M4 4h16v16H4z"
                          opacity=".25"/>
                </svg>
            </div>

            <div class="action-content">
                <h3>Return Books</h3>
                <p>Manage issued and returned books</p>
            </div>

            <div class="action-arrow">→</div>

        </a>


        {{-- Fines --}}
        <a href="{{ route('admin.library.fines.index') }}"
           class="library-action-card">

            <div class="action-icon fine-icon">
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v10"/>
                    <path d="M9 9.5c0-1 1.2-1.5 3-1.5s3 .5 3 1.5-1.2 1.5-3 1.5-3 .5-3 1.5 1.2 1.5 3 1.5 3-.5 3-1.5"/>
                </svg>
            </div>

            <div class="action-content">
                <h3>Fines</h3>
                <p>View and manage library fines</p>
            </div>

            <div class="action-arrow">→</div>

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


    {{-- Transactions Table --}}
    <div class="table-card">

        <div class="table-header">
            <h3>Library Transactions</h3>
            <p>Track issued books, returns and fines.</p>
        </div>

        <div class="table-wrapper">

            @if($issues->count())

                <table class="issue-table">

                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Student ID</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                            <th>Fine</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($issues as $issue)

                            <tr>

                                <td>
                                    <div class="book-title">
                                        {{ $issue->book->title ?? 'Book Deleted' }}
                                    </div>

                                    @if($issue->book)
                                        <div style="margin-top: 3px; color: #8a93a5; font-size: 11px;">
                                            {{ $issue->book->author }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="student-id">
                                        #{{ $issue->student_id }}
                                    </span>
                                </td>

                                <td>
                                    {{ $issue->issue_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    {{ $issue->due_date?->format('d M Y') ?? '—' }}
                                </td>

                                <td>
                                    @if($issue->return_date)
                                        {{ $issue->return_date->format('d M Y') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td>
                                    @if($issue->status === 'Issued')

                                        <span class="status-badge status-issued">
                                            Issued
                                        </span>

                                    @elseif($issue->status === 'Returned')

                                        <span class="status-badge status-returned">
                                            Returned
                                        </span>

                                    @else

                                        <span class="status-badge status-overdue">
                                            Overdue
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    @if((float) $issue->fine > 0)

                                        <span class="fine fine-positive">
                                            ₹{{ number_format((float) $issue->fine, 2) }}
                                        </span>

                                        @if($issue->fine_type && $issue->fine_type !== 'None')
                                            <span class="fine-type">
                                                {{ $issue->fine_type }}
                                            </span>
                                        @endif

                                    @else

                                        <span class="fine fine-zero">
                                            ₹0.00
                                        </span>

                                    @endif
                                </td>

                                <td>
                                    @if($issue->status !== 'Returned')

                                        <form action="{{ route('admin.library.issues.return', $issue) }}"
                                              method="POST"
                                              style="display:inline;"
                                              onsubmit="return confirm('Are you sure you want to mark this book as returned?');">

                                            @csrf

                                            <button type="submit"
                                                    class="return-btn">
                                                Return Book
                                            </button>

                                        </form>

                                    @else

                                        <span class="returned-label">
                                            Returned
                                        </span>

                                    @endif
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="pagination-wrapper">
                    {{ $issues->links() }}
                </div>

            @else

                <div class="empty-state">

                    <h4>No library transactions yet</h4>

                    <p>
                        No books have been issued yet.
                        Click "Issue Book" to create your first transaction.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
