@extends('layouts.app')

@section('content')

<style>
    .return-page {
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
        gap: 7px;
        padding: 10px 15px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #dce2eb;
        color: #455066;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f7f9fc;
        color: #172033;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
    }

    .table-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .table-header h3 {
        margin: 0;
        color: #172033;
        font-size: 15px;
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

    .return-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
    }

    .return-table th {
        padding: 13px 18px;
        background: #f8fafc;
        color: #70798c;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }

    .return-table td {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
        color: #343d50;
        font-size: 13px;
        white-space: nowrap;
        vertical-align: middle;
    }

    .return-table tbody tr:hover {
        background: #fafcff;
    }

    .book-title {
        color: #172033;
        font-weight: 650;
    }

    .book-author {
        margin-top: 3px;
        color: #8a93a5;
        font-size: 11px;
    }

    .student-id {
        color: #455066;
        font-weight: 600;
    }

    .date {
        color: #455066;
    }

    .overdue {
        color: #d32f2f;
        font-weight: 700;
    }

    .due {
        color: #343d50;
        font-weight: 600;
    }

    .return-btn {
        border: none;
        background: #e8f5e9;
        color: #2e7d32;
        padding: 8px 13px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .return-btn:hover {
        background: #c8e6c9;
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

    /* Modal */

    .return-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 23, 42, .45);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .return-modal.active {
        display: flex;
    }

    .modal-box {
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 20px 50px rgba(15, 23, 42, .18);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .modal-header h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
    }

    .close-modal {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 7px;
        background: #f5f7fa;
        color: #596477;
        font-size: 20px;
        cursor: pointer;
    }

    .close-modal:hover {
        background: #edf0f5;
    }

    .modal-body {
        padding: 20px;
    }

    .book-summary {
        padding: 13px;
        margin-bottom: 18px;
        border-radius: 9px;
        background: #f8fafc;
        border: 1px solid #edf0f5;
    }

    .summary-title {
        color: #172033;
        font-size: 13px;
        font-weight: 700;
    }

    .summary-details {
        margin-top: 5px;
        color: #7b8497;
        font-size: 11px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #455066;
        font-size: 12px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 11px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        background: #fff;
        color: #343d50;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .form-control:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, .08);
    }

    textarea.form-control {
        min-height: 85px;
        resize: vertical;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 15px 20px;
        border-top: 1px solid #edf0f5;
    }

    .cancel-btn,
    .confirm-btn {
        padding: 9px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .cancel-btn {
        border: 1px solid #dce2eb;
        background: #fff;
        color: #596477;
    }

    .confirm-btn {
        border: none;
        background: #2e7d32;
        color: #fff;
    }

    .confirm-btn:hover {
        background: #256b29;
    }

    @media (max-width: 700px) {
        .return-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .modal-box {
            max-height: 94vh;
        }
    }
</style>


<div class="return-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="page-title">
            <h1>Return Books</h1>
            <p>Manage currently issued books and process their returns.</p>
        </div>

        <a href="{{ route('admin.library.issues.index') }}"
           class="back-btn">
            ← Back to Library
        </a>

    </div>


    {{-- Alerts --}}
    @if(session('success'))
        <div style="padding:12px 15px; margin-bottom:18px; border-radius:8px; background:#e8f5e9; color:#2e7d32; font-size:13px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="padding:12px 15px; margin-bottom:18px; border-radius:8px; background:#ffebee; color:#c62828; font-size:13px;">
            {{ session('error') }}
        </div>
    @endif


    {{-- Returns Table --}}
    <div class="table-card">

        <div class="table-header">
            <h3>Currently Issued Books</h3>
            <p>Books that are waiting to be returned by students.</p>
        </div>

        <div class="table-wrapper">

            @if($returns->count())

                <table class="return-table">

                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Student ID</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($returns as $return)

                            <tr>

                                <td>
                                    <div class="book-title">
                                        {{ $return->book->title ?? 'Book Deleted' }}
                                    </div>

                                    @if($return->book)
                                        <div class="book-author">
                                            {{ $return->book->author }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <span class="student-id">
                                        #{{ $return->student_id }}
                                    </span>
                                </td>

                                <td>
                                    <span class="date">
                                        {{ $return->issue_date?->format('d M Y') ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    @if(
                                        $return->status === 'Overdue' ||
                                        ($return->due_date && $return->due_date->isPast())
                                    )
                                        <span class="overdue">
                                            {{ $return->due_date?->format('d M Y') ?? '—' }}
                                        </span>
                                    @else
                                        <span class="due">
                                            {{ $return->due_date?->format('d M Y') ?? '—' }}
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    @if($return->status === 'Overdue')

                                        <span style="display:inline-flex; padding:5px 9px; border-radius:6px; background:#ffebee; color:#d32f2f; font-size:11px; font-weight:700;">
                                            Overdue
                                        </span>

                                    @else

                                        <span style="display:inline-flex; padding:5px 9px; border-radius:6px; background:#e3f2fd; color:#1976d2; font-size:11px; font-weight:700;">
                                            Issued
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <button type="button"
                                            class="return-btn"
                                            onclick="openReturnModal(
                                                {{ $return->id }},
                                                @js($return->book->title ?? 'Book Deleted'),
                                                {{ $return->student_id }},
                                                @js($return->due_date?->format('d M Y') ?? '—')
                                            )">
                                        Return Book
                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <div class="pagination-wrapper">
                    {{ $returns->links() }}
                </div>

            @else

                <div class="empty-state">

                    <h4>No books waiting for return</h4>

                    <p>
                        There are currently no issued or overdue books.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- Return Modal --}}
<div id="returnModal"
     class="return-modal">

    <div class="modal-box">

        <div class="modal-header">

            <h3>Return Book</h3>

            <button type="button"
                    class="close-modal"
                    onclick="closeReturnModal()">
                ×
            </button>

        </div>


        <form id="returnForm"
              method="POST">

            @csrf

            <div class="modal-body">

                <div class="book-summary">

                    <div class="summary-title"
                         id="modalBookTitle">
                        Book
                    </div>

                    <div class="summary-details">
                        Student ID:
                        <strong id="modalStudentId">—</strong>
                        &nbsp; • &nbsp;
                        Due Date:
                        <strong id="modalDueDate">—</strong>
                    </div>

                </div>


                <div class="form-group">

                    <label for="fine_type">
                        Fine Type
                    </label>

                    <select name="fine_type"
                            id="fine_type"
                            class="form-control"
                            required>

                        <option value="None">
                            No Fine
                        </option>

                        <option value="Late Return">
                            Late Return
                        </option>

                        <option value="Book Damaged">
                            Book Damaged
                        </option>

                        <option value="Book Lost">
                            Book Lost
                        </option>

                        <option value="Pages Damaged">
                            Pages Damaged
                        </option>

                        <option value="Cover Damaged">
                            Cover Damaged
                        </option>

                        <option value="Other">
                            Other
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="fine">
                        Fine Amount (₹)
                    </label>

                    <input type="number"
                           name="fine"
                           id="fine"
                           class="form-control"
                           min="0"
                           step="0.01"
                           value="0"
                           required>

                </div>


                <div class="form-group">

                    <label for="fine_reason">
                        Reason / Explanation
                    </label>

                    <textarea name="fine_reason"
                              id="fine_reason"
                              class="form-control"
                              maxlength="1000"
                              placeholder="Explain why the fine was applied..."></textarea>

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                        class="cancel-btn"
                        onclick="closeReturnModal()">
                    Cancel
                </button>

                <button type="submit"
                        class="confirm-btn">
                    Confirm Return
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    function openReturnModal(issueId, bookTitle, studentId, dueDate) {

        const modal = document.getElementById('returnModal');
        const form = document.getElementById('returnForm');

        form.action = "{{ url('/admin/library/issues') }}/" + issueId + "/return";

        document.getElementById('modalBookTitle').textContent = bookTitle;
        document.getElementById('modalStudentId').textContent = '#' + studentId;
        document.getElementById('modalDueDate').textContent = dueDate;

        document.getElementById('fine_type').value = 'None';
        document.getElementById('fine').value = '0';
        document.getElementById('fine_reason').value = '';

        modal.classList.add('active');
    }


    function closeReturnModal() {
        document.getElementById('returnModal').classList.remove('active');
    }


    document.getElementById('returnModal').addEventListener('click', function(event) {

        if (event.target === this) {
            closeReturnModal();
        }

    });


    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {
            closeReturnModal();
        }

    });


    document.getElementById('fine_type').addEventListener('change', function() {

        const fineInput = document.getElementById('fine');

        if (this.value === 'None') {
            fineInput.value = '0';
        }

    });
</script>

@endsection