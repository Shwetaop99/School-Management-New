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
        padding: 9px 14px;
        border-radius: 8px;
        background: #f3f6fa;
        color: #455066;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #e8edf3;
    }

    .form-card {
        max-width: 850px;
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, 0.05);
    }

    .form-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-header h3 {
        margin: 0;
        font-size: 16px;
        color: #172033;
    }

    .form-header p {
        margin: 5px 0 0;
        color: #7b8497;
        font-size: 12px;
    }

    .form-body {
        padding: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #343d50;
        font-size: 13px;
        font-weight: 600;
    }

    .required {
        color: #d32f2f;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        padding: 10px 12px;
        font-family: inherit;
        font-size: 13px;
        color: #343d50;
        background: #fff;
        outline: none;
    }

    .form-input,
    .form-select {
        height: 42px;
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.08);
    }

    .form-help {
        margin-top: 5px;
        color: #8a93a5;
        font-size: 11px;
    }

    .error-message {
        margin-top: 5px;
        color: #d32f2f;
        font-size: 11px;
    }

    .book-info {
        margin-bottom: 22px;
        padding: 14px 16px;
        border-radius: 9px;
        background: #f5f9ff;
        border: 1px solid #dcecff;
        color: #455066;
        font-size: 12px;
    }

    .book-info strong {
        color: #1976d2;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 18px 24px;
        border-top: 1px solid #edf0f5;
        background: #fafbfd;
    }

    .cancel-btn,
    .submit-btn {
        height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .cancel-btn {
        border: none;
        background: #f0f2f5;
        color: #455066;
    }

    .submit-btn {
        border: none;
        background: #1976d2;
        color: #fff;
    }

    .submit-btn:hover {
        background: #1565c0;
    }

    .cancel-btn:hover {
        background: #e5e8ed;
    }

    .alert {
        max-width: 850px;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
    }

    @media (max-width: 700px) {
        .issue-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-body {
            padding: 18px;
        }

        .form-footer {
            padding: 15px 18px;
        }
    }
</style>

<div class="issue-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="page-title">
            <h1>Issue Book</h1>
            <p>Issue a library book to a student.</p>
        </div>

        <a href="{{ route('admin.library.issues.index') }}"
           class="back-btn">
            ← Back to Issue / Return
        </a>

    </div>

    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-error">

            <strong>Please fix the following:</strong>

            <ul style="margin: 8px 0 0 18px; padding: 0;">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    {{-- Controller Error --}}
    @if(session('error'))

        <div class="alert alert-error">
            {{ session('error') }}
        </div>

    @endif

    <form action="{{ route('admin.library.issues.store') }}"
          method="POST">

        @csrf

        <div class="form-card">

            <div class="form-header">
                <h3>Issue Details</h3>
                <p>Enter the book and borrowing information.</p>
            </div>

            <div class="form-body">

                <div class="book-info">
                    <strong>Available books:</strong>
                    Only books with available copies are shown below.
                </div>

                {{-- Book --}}
                <div class="form-group">

                    <label class="form-label">
                        Book
                        <span class="required">*</span>
                    </label>

                    <select
                        name="book_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Select a book
                        </option>

                        @foreach($books as $book)

                            <option
                                value="{{ $book->id }}"
                                {{ old('book_id') == $book->id ? 'selected' : '' }}
                            >
                                {{ $book->title }}
                                — {{ $book->author }}
                                ({{ $book->available_quantity }} available)
                            </option>

                        @endforeach

                    </select>

                    @if($books->isEmpty())
                        <div class="form-help" style="color:#d32f2f;">
                            No books are currently available for issue.
                        </div>
                    @else
                        <div class="form-help">
                            Select the book you want to issue.
                        </div>
                    @endif

                    @error('book_id')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Student ID --}}
                <div class="form-group">

                    <label class="form-label">
                        Student ID
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="student_id"
                        class="form-input"
                        placeholder="Enter student ID"
                        value="{{ old('student_id') }}"
                        min="1"
                        required
                    >

                    <div class="form-help">
                        We'll connect this to the actual Student Management system later.
                    </div>

                    @error('student_id')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- Dates --}}
                <div class="form-row">

                    <div class="form-group">

                        <label class="form-label">
                            Issue Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="issue_date"
                            class="form-input"
                            value="{{ old('issue_date', date('Y-m-d')) }}"
                            required
                        >

                        @error('issue_date')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Due Date
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="due_date"
                            class="form-input"
                            value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}"
                            required
                        >

                        <div class="form-help">
                            The date by which the book should be returned.
                        </div>

                        @error('due_date')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                {{-- Remarks --}}
                <div class="form-group" style="margin-bottom:0;">

                    <label class="form-label">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        class="form-textarea"
                        placeholder="Optional notes about this issue..."
                    >{{ old('remarks') }}</textarea>

                    @error('remarks')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('admin.library.issues.index') }}"
                   class="cancel-btn">
                    Cancel
                </a>

                <button type="submit"
                        class="submit-btn"
                        {{ $books->isEmpty() ? 'disabled' : '' }}>
                    Issue Book
                </button>

            </div>

        </div>

    </form>

</div>

@endsection