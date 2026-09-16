@extends('layouts.app')

@section('content')

<style>
    .fine-page {
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

    .back-btn {
        display: inline-flex;
        align-items: center;
        background: #fff;
        border: 1px solid #dce2eb;
        color: #34405a;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f7f9fc;
    }

    .alert {
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
    }

    .fine-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, 0.04);
    }

    .card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .card-header h3 {
        margin: 0;
        font-size: 15px;
        color: #172033;
    }

    .card-header p {
        margin: 5px 0 0;
        color: #8a93a5;
        font-size: 12px;
    }

    .fine-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fine-table th {
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

    .fine-table td {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
        color: #343d50;
        font-size: 13px;
        vertical-align: middle;
    }

    .book-title {
        font-weight: 700;
        color: #172033;
    }

    .student-id {
        font-weight: 600;
        color: #455066;
    }

    .fine-type {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #fff3e0;
        color: #ef6c00;
        font-size: 11px;
        font-weight: 700;
    }

    .fine-amount {
        color: #d32f2f;
        font-weight: 700;
        font-size: 14px;
    }

    .reason {
        max-width: 250px;
        white-space: normal;
        line-height: 1.5;
        color: #687287;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff3e0;
        color: #ef6c00;
    }

    .status-paid {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-form {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .status-select {
        border: 1px solid #dce2eb;
        background: #fff;
        color: #34405a;
        padding: 7px 9px;
        border-radius: 7px;
        font-size: 12px;
        outline: none;
        cursor: pointer;
    }

    .status-select:focus {
        border-color: #1976d2;
    }

    .update-btn {
        border: none;
        background: #e3f2fd;
        color: #1976d2;
        padding: 7px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .update-btn:hover {
        background: #bbdefb;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #e8f5e9;
        color: #2e7d32;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
    }

    .empty-state h4 {
        margin: 0 0 7px;
        color: #343d50;
        font-size: 15px;
    }

    .empty-state p {
        margin: 0;
        color: #8a93a5;
        font-size: 13px;
    }

    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid #edf0f5;
    }

    @media (max-width: 1000px) {
        .fine-card {
            overflow-x: auto;
        }

        .fine-table {
            min-width: 1100px;
        }
    }

    @media (max-width: 700px) {
        .fine-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="fine-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="page-title">
            <h1>Library Fines</h1>
            <p>Manage book fines and track payment status.</p>
        </div>

        <a href="{{ route('admin.library.books.index') }}"
   class="back-btn">
    ← Back to Library
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


    {{-- Fine Table --}}
    <div class="fine-card">

        <div class="card-header">
            <h3>Books With Fines</h3>
            <p>
                Only transactions with an outstanding or recorded fine are shown here.
            </p>
        </div>


        @if($fines->count())

            <table class="fine-table">

                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Student ID</th>
                        <th>Fine Type</th>
                        <th>Amount</th>
                        <th>Reason</th>
                        <th>Return Date</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>


                <tbody>

                    @foreach($fines as $fine)

                        <tr>

                            {{-- Book --}}
                            <td>
                                <div class="book-title">
                                    {{ $fine->book->title ?? 'Book Deleted' }}
                                </div>
                            </td>


                            {{-- Student --}}
                            <td>
                                <span class="student-id">
                                    #{{ $fine->student_id }}
                                </span>
                            </td>


                            {{-- Fine Type --}}
                            <td>
                                <span class="fine-type">
                                    {{ $fine->fine_type ?? 'Other' }}
                                </span>
                            </td>


                            {{-- Amount --}}
                            <td>
                                <span class="fine-amount">
                                    ₹{{ number_format((float) $fine->fine, 2) }}
                                </span>
                            </td>


                            {{-- Reason --}}
                            <td>
                                <div class="reason">
                                    {{ $fine->fine_reason ?: 'No reason provided.' }}
                                </div>
                            </td>


                            {{-- Return Date --}}
                            <td>
                                @if($fine->return_date)
                                    {{ $fine->return_date->format('d M Y') }}
                                @else
                                    —
                                @endif
                            </td>


                            {{-- Payment Status --}}
                            <td>

                                @if($fine->fine_status === 'Paid')

                                    <span class="status-badge status-paid">
                                        Paid
                                    </span>

                                @else

                                    <span class="status-badge status-pending">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td>

                                <form
                                    action="{{ route('admin.library.fines.status', $fine) }}"
                                    method="POST"
                                    class="status-form"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <select
                                        name="fine_status"
                                        class="status-select"
                                    >
                                        <option
                                            value="Pending"
                                            {{ $fine->fine_status === 'Pending' ? 'selected' : '' }}
                                        >
                                            Pending
                                        </option>

                                        <option
                                            value="Paid"
                                            {{ $fine->fine_status === 'Paid' ? 'selected' : '' }}
                                        >
                                            Paid
                                        </option>
                                    </select>

                                    <button
                                        type="submit"
                                        class="update-btn"
                                    >
                                        Update
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>


            <div class="pagination-wrapper">
                {{ $fines->links() }}
            </div>


        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ✓
                </div>

                <h4>No fines recorded</h4>

                <p>
                    There are currently no books with fines.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection