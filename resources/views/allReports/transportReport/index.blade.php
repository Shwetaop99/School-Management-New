@extends('layouts.app')

@section('title', 'Transport Reports')
@section('page-title', 'Transport Reports')

@section('content')

<style>
    .transport-report-page {
        padding: 24px;
        background: #f5f8fc;
        min-height: calc(100vh - 70px);
        font-family: Arial, Helvetica, sans-serif;
        color: #17365d;
    }

    .transport-report-page * {
        box-sizing: border-box;
    }

    .transport-report-header {
        padding: 28px 32px;
        margin-bottom: 24px;
        color: #fff;
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(25, 118, 210, .15);
    }

    .transport-report-header h1 {
        margin: 0 0 8px;
        font-size: 28px;
        line-height: 1.3;
        font-weight: 600;
    }

    .transport-report-header p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

        .transport-report-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .transport-report-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .06);
        text-decoration: none;
        color: #17365d;
        transition: all .2s ease;
    }

    .transport-report-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 18px rgba(0, 0, 0, .10);
    }

    .transport-report-card-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e8f1ff;
        color: #1976d2;
        font-size: 22px;
    }

    .transport-report-card h3 {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 600;
        color: #17365d;
    }

    .transport-report-card p {
        margin: 0;
        color: #667085;
        font-size: 13px;
        line-height: 1.4;
    }

    .transport-report-card-arrow {
        margin-left: auto;
        color: #1976d2;
        font-size: 16px;
    }

    .transport-summary-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .transport-summary-card,
    .transport-filter-card,
    .transport-table-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .06);
    }

    .transport-summary-card {
        padding: 18px;
    }

    .transport-summary-label {
        margin-bottom: 7px;
        color: #667085;
        font-size: 13px;
    }

    .transport-summary-value {
        font-size: 26px;
        font-weight: 600;
    }

    .transport-summary-value.fee {
        font-size: 22px;
    }

    .value-blue { color: #17365d; }
    .value-green { color: #198754; }
    .value-red { color: #dc3545; }
    .value-primary { color: #1976d2; }

    .transport-filter-card,
    .transport-table-card {
        padding: 24px;
    }

    .transport-filter-card {
        margin-bottom: 24px;
    }

    .transport-section-title {
        margin: 0 0 18px;
        color: #17365d;
        font-size: 22px;
        line-height: 1.3;
        font-weight: 600;
    }

    .transport-filter-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 14px;
    }

    .transport-field label {
        display: block;
        margin-bottom: 6px;
        color: #4b5563;
        font-size: 13px;
        font-weight: 500;
    }

    .transport-field select,
    .transport-field input {
        width: 100%;
        height: 44px;
        padding: 9px 11px;
        border: 1px solid #d7dce2;
        border-radius: 8px;
        background: #fff;
        color: #333;
        font: inherit;
        font-size: 14px;
    }

    .transport-field select:focus,
    .transport-field input:focus {
        outline: none;
        border-color: #5b72e8;
    }

    .student-search-field {
        position: relative;
    }

    .student-search-box {
        position: relative;
    }

    .student-search-input {
        padding-left: 36px !important;
    }

    .student-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: 13px;
        z-index: 2;
        pointer-events: none;
    }

    .student-search-results {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 4px);
        z-index: 1000;
        display: none;
        max-height: 250px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #d7dce2;
        border-radius: 8px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, .12);
    }

    .student-search-result {
        display: block;
        width: 100%;
        padding: 10px 12px;
        border: 0;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
        color: #17365d;
        text-align: left;
        cursor: pointer;
        font: inherit;
    }

    .student-search-result:last-child {
        border-bottom: 0;
    }

    .student-search-result:hover {
        background: #f5f8fc;
    }

    .student-search-result-name {
        display: block;
        font-weight: 600;
        font-size: 14px;
    }

    .student-search-result-id {
        display: block;
        margin-top: 2px;
        color: #667085;
        font-size: 12px;
    }

    .student-search-message {
        padding: 11px 12px;
        color: #777;
        font-size: 13px;
    }

    .transport-filter-actions,
    .transport-action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .transport-filter-actions {
        margin-top: 16px;
    }

    .transport-action-row {
        justify-content: flex-end;
        margin-bottom: 14px;
    }

    .transport-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 10px 18px;
        border: 0;
        border-radius: 8px;
        font: inherit;
        font-size: 14px;
        text-decoration: none;
        cursor: pointer;
    }

    .transport-btn-filter {
        background: #5b72e8;
        color: #fff;
    }

    .transport-btn-reset {
        background: #eee;
        color: #333;
    }

    .transport-btn-print,
    .transport-btn-excel {
        background: #198754;
        color: #fff;
    }

    .transport-btn-pdf {
        background: #dc3545;
        color: #fff;
    }

    .transport-table-card {
        overflow-x: auto;
    }

    .transport-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        font-size: 14px;
    }

    .transport-table th {
        padding: 12px;
        background: #f1f3f5;
        color: #17365d;
        font-size: 13px;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
    }

    .transport-table td {
        padding: 12px;
        color: #344054;
        line-height: 1.4;
    }

    .transport-table tbody tr {
        border-bottom: 1px solid #eee;
    }

    .transport-table strong {
        color: #17365d;
        font-weight: 600;
    }

    .transport-student-id {
        margin-top: 3px;
        color: #777;
        font-size: 12px;
    }

    .transport-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .transport-badge-active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .transport-badge-inactive {
        background: #f8d7da;
        color: #842029;
    }

    .transport-payment-paid {
        color: #198754;
        font-weight: 600;
    }

    .transport-payment-pending {
        color: #dc3545;
        font-weight: 600;
    }

    .transport-payment-other {
        color: #856404;
        font-weight: 600;
    }

    .transport-empty {
        padding: 28px !important;
        text-align: center;
        color: #777 !important;
    }

    @media (max-width: 1200px) {
        .transport-summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .transport-filter-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .transport-report-page {
            padding: 15px;
                    .transport-report-cards {
            grid-template-columns: 1fr;
        }
        }

        .transport-summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .transport-filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .transport-report-header {
            padding: 22px;
        }

        .transport-report-header h1 {
            font-size: 24px;
        }

        .transport-filter-card,
        .transport-table-card {
            padding: 18px;
        }

        .transport-action-row {
            justify-content: stretch;
        }

        .transport-action-row .transport-btn {
            flex: 1;
        }
    }

    @media (max-width: 520px) {
        .transport-report-page {
            padding: 10px;
        }

        .transport-summary-grid,
        .transport-filter-grid {
            grid-template-columns: 1fr;
        }

        .transport-report-header {
            padding: 20px 16px;
        }

        .transport-report-header h1 {
            font-size: 21px;
        }

        .transport-filter-card,
        .transport-table-card {
            padding: 15px;
        }

        .transport-filter-actions,
        .transport-action-row {
            flex-direction: column;
        }

        .transport-btn {
            width: 100%;
        }

        .transport-table {
            min-width: 950px;
        }
    }

    @media print {
        .transport-report-page {
            padding: 0;
            background: #fff !important;
        }

        .transport-filter-card,
        .transport-action-row {
            display: none !important;
        }

        .transport-table-card {
            box-shadow: none;
            padding: 0;
            overflow: visible;
        }

        .transport-table {
            min-width: 0;
            font-size: 11px;
        }

        .transport-table th,
        .transport-table td {
            padding: 7px;
            font-size: 11px;
        }

        @page {
            size: landscape;
            margin: 12mm;
        }
    }
</style>

<div class="transport-report-page">
    <div class="transport-report-header">
        <h1><i class="fas fa-bus" style="margin-right:10px;"></i>Transport Reports</h1>
        <p>Routes, vehicles, students and transport fee records.</p>
    </div>

        <div class="transport-report-cards">

        <a href="{{ route('admin.reports.transport.student-travel') }}"
           class="transport-report-card">

            <div class="transport-report-card-icon">
                <i class="fas fa-route"></i>
            </div>

            <div>
                <h3>Student Travel Data</h3>
                <p>
                    View student-wise routes, vehicles, pickup points,
                    drop points and transport fees.
                </p>
            </div>

            <div class="transport-report-card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>


        <a href="{{ route('admin.reports.transport.vehicle') }}"
           class="transport-report-card">

            <div class="transport-report-card-icon">
                <i class="fas fa-bus"></i>
            </div>

            <div>
                <h3>Vehicle Report</h3>
                <p>
                    View vehicle-wise assigned students,
                    active students and transport fees.
                </p>
            </div>

            <div class="transport-report-card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>

        </a>

    </div>

    <div class="transport-summary-grid">
        <div class="transport-summary-card"><div class="transport-summary-label">Total Records</div><div class="transport-summary-value value-blue">{{ $totalRecords }}</div></div>
        <div class="transport-summary-card"><div class="transport-summary-label">Active Transport</div><div class="transport-summary-value value-green">{{ $activeRecords }}</div></div>
        <div class="transport-summary-card"><div class="transport-summary-label">Inactive Transport</div><div class="transport-summary-value value-red">{{ $inactiveRecords }}</div></div>
        <div class="transport-summary-card"><div class="transport-summary-label">Assigned Students</div><div class="transport-summary-value value-primary">{{ $assignedStudents }}</div></div>
        <div class="transport-summary-card"><div class="transport-summary-label">Transport Fees</div><div class="transport-summary-value fee value-blue">₹{{ number_format($totalTransportFees, 2) }}</div></div>
    </div>

    <div class="transport-filter-card">
        <h2 class="transport-section-title">Filter Report</h2>
        <form method="GET" action="{{ route('admin.reports.transport') }}">
            <div class="transport-filter-grid">
                <div class="transport-field student-search-field">
                    <label for="studentSearch">Student</label>

                    <div class="student-search-box">
                        <i class="fas fa-search student-search-icon"></i>

                        <input
                            type="text"
                            id="studentSearch"
                            class="student-search-input"
                            placeholder="Search student name or ID..."
                            value="{{ $selectedStudent ? trim(($selectedStudent->first_name ?? '') . ' ' . ($selectedStudent->middle_name ?? '') . ' ' . ($selectedStudent->last_name ?? '')) . ' - ' . ($selectedStudent->student_id ?? $selectedStudent->id) : '' }}"
                            autocomplete="off"
                        >

                        <input
                            type="hidden"
                            id="student_id"
                            name="student_id"
                            value="{{ request('student_id') }}"
                        >
                    </div>

                    <div id="studentSearchResults" class="student-search-results"></div>
                </div>

                <div class="transport-field">
                    <label for="transport_status">Transport Status</label>
                    <select id="transport_status" name="transport_status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('transport_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('transport_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="transport-field">
                    <label for="transport_type">Transport Type</label>
                    <select id="transport_type" name="transport_type">
                        <option value="">All Types</option>
                        <option value="school_bus" {{ request('transport_type') == 'school_bus' ? 'selected' : '' }}>School Bus</option>
                        <option value="van" {{ request('transport_type') == 'van' ? 'selected' : '' }}>Van</option>
                        <option value="private" {{ request('transport_type') == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="other" {{ request('transport_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="transport-field">
                    <label for="payment_status">Payment Status</label>
                    <select id="payment_status" name="payment_status">
                        <option value="">All Payments</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    </select>
                </div>

                <div class="transport-field">
                    <label for="from_date">From Date</label>
                    <input id="from_date" type="date" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="transport-field">
                    <label for="to_date">To Date</label>
                    <input id="to_date" type="date" name="to_date" value="{{ request('to_date') }}">
                </div>
            </div>

            <div class="transport-filter-actions">
                <button type="submit" class="transport-btn transport-btn-filter"><i class="fas fa-filter"></i>Apply Filters</button>
                <a href="{{ route('admin.reports.transport') }}" class="transport-btn transport-btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <div class="transport-action-row">
        <button type="button" onclick="window.print()" class="transport-btn transport-action-btn transport-btn-print"><i class="fas fa-print"></i>Print</button>
        <a href="{{ route('admin.reports.transport.pdf', request()->query()) }}" class="transport-btn transport-action-btn transport-btn-pdf"><i class="fas fa-file-pdf"></i>PDF</a>
        <a href="{{ route('admin.reports.transport.excel', request()->query()) }}" class="transport-btn transport-action-btn transport-btn-excel"><i class="fas fa-file-excel"></i>Excel</a>
    </div>

    <div class="transport-table-card">
        <h2 class="transport-section-title">Student Transport Records</h2>
        <div class="transport-table-wrapper">
            <table class="transport-table">
                <thead>
                    <tr>
                        <th>Student</th><th>Route</th><th>Vehicle</th><th>Pickup Point</th><th>Drop Point</th>
                        <th style="text-align:center;">Type</th><th style="text-align:center;">Status</th>
                        <th style="text-align:right;">Fee</th><th style="text-align:center;">Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>
                                <strong>{{ trim(($record->student->first_name ?? '') . ' ' . ($record->student->last_name ?? '')) ?: '-' }}</strong>
                                @if($record->student?->student_id)<div class="transport-student-id">{{ $record->student->student_id }}</div>@endif
                            </td>
                            <td>{{ $record->route ?? '-' }}</td>
                            <td>{{ $record->vehicle ?? '-' }}</td>
                            <td>{{ $record->pickup_point ?? '-' }}</td>
                            <td>{{ $record->drop_point ?? '-' }}</td>
                            <td style="text-align:center;">{{ ucfirst(str_replace('_', ' ', $record->transport_type ?? '-')) }}</td>
                            <td style="text-align:center;">
                                @if($record->transport_status === 'active')
                                    <span class="transport-badge transport-badge-active">Active</span>
                                @else
                                    <span class="transport-badge transport-badge-inactive">{{ ucfirst($record->transport_status ?? 'Inactive') }}</span>
                                @endif
                            </td>
                            <td style="text-align:right;font-weight:600;">₹{{ number_format($record->transport_fee ?? 0, 2) }}</td>
                            <td style="text-align:center;">
                                @if($record->payment_status === 'paid')
                                    <span class="transport-payment-paid">Paid</span>
                                @elseif($record->payment_status === 'pending')
                                    <span class="transport-payment-pending">Pending</span>
                                @else
                                    <span class="transport-payment-other">{{ ucfirst(str_replace('_', ' ', $record->payment_status ?? '-')) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="transport-empty">No transport records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('studentSearch');
    const studentIdInput = document.getElementById('student_id');
    const resultsBox = document.getElementById('studentSearchResults');

    /*
    |--------------------------------------------------------------------------
    | Get the filter form
    |--------------------------------------------------------------------------
    */

    const filterForm = searchInput
        ? searchInput.closest('form')
        : null;

    if (
        !searchInput ||
        !studentIdInput ||
        !resultsBox ||
        !filterForm
    ) {
        console.error(
            'Transport report student search elements not found.'
        );

        return;
    }

    let searchTimer = null;

    /*
    |--------------------------------------------------------------------------
    | Hide search results
    |--------------------------------------------------------------------------
    */

    function hideResults() {

        resultsBox.style.display = 'none';

        resultsBox.innerHTML = '';
    }

    /*
    |--------------------------------------------------------------------------
    | Show message
    |--------------------------------------------------------------------------
    */

    function showMessage(message) {

        resultsBox.innerHTML =
            '<div class="student-search-message">' +
            escapeHtml(message) +
            '</div>';

        resultsBox.style.display = 'block';
    }

    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /*
    |--------------------------------------------------------------------------
    | Display student results
    |--------------------------------------------------------------------------
    */

    function showResults(students) {

        if (!Array.isArray(students)) {

            console.error(
                'Invalid student search response:',
                students
            );

            showMessage('Unable to search students.');

            return;
        }

        if (students.length === 0) {

            showMessage('No student found.');

            return;
        }

        resultsBox.innerHTML = students
            .map(function (student) {

                const id = student.id ?? '';

                const studentId =
                    student.student_id ?? id;

                const name =
                    student.name ?? 'Unknown Student';

                return `
                    <button
                        type="button"
                        class="student-search-result"

                        data-id="${escapeHtml(id)}"

                        data-name="${escapeHtml(name)}"

                        data-student-id="${escapeHtml(studentId)}"
                    >

                        <span class="student-search-result-name">
                            ${escapeHtml(name)}
                        </span>

                        <span class="student-search-result-id">
                            ID: ${escapeHtml(studentId)}
                        </span>

                    </button>
                `;

            })
            .join('');

        resultsBox.style.display = 'block';

        /*
        |--------------------------------------------------------------------------
        | Student selection
        |--------------------------------------------------------------------------
        */

        resultsBox
            .querySelectorAll('.student-search-result')
            .forEach(function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        /*
                        | Set selected database student ID
                        */

                        studentIdInput.value =
                            this.dataset.id;

                        /*
                        | Show selected student in search box
                        */

                        searchInput.value =
                            this.dataset.name +
                            ' - ' +
                            this.dataset.studentId;

                        /*
                        | Hide dropdown
                        */

                        hideResults();

                        /*
                        |--------------------------------------------------------------------------
                        | IMPORTANT:
                        | Automatically submit the report filter.
                        |--------------------------------------------------------------------------
                        */

                        filterForm.submit();
                    }
                );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Search students
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener(
        'input',
        function () {

            const query =
                this.value.trim();

            /*
            | User changed the search text,
            | so old student selection is no longer valid.
            */

            studentIdInput.value = '';

            clearTimeout(searchTimer);

            /*
            | Empty search
            */

            if (query.length < 1) {

                hideResults();

                return;
            }

            showMessage('Searching...');

            /*
            |--------------------------------------------------------------------------
            | Small delay to prevent request on every keystroke
            |--------------------------------------------------------------------------
            */

            searchTimer = setTimeout(
                function () {

                    const url = new URL(
                        '{{ route('admin.reports.transport') }}',
                        window.location.origin
                    );

                    url.searchParams.set(
                        'student_search',
                        query
                    );

                    fetch(
                        url.toString(),
                        {
                            method: 'GET',

                            headers: {
                                'X-Requested-With':
                                    'XMLHttpRequest',

                                'Accept':
                                    'application/json'
                            }
                        }
                    )

                    /*
                    |--------------------------------------------------------------------------
                    | Read as TEXT first
                    |--------------------------------------------------------------------------
                    |
                    | This prevents the BOM / \uFEFF JSON error you were getting.
                    |
                    */

                    .then(function (response) {

                        if (!response.ok) {

                            throw new Error(
                                'Search request failed. HTTP ' +
                                response.status
                            );
                        }

                        return response.text();
                    })

                    /*
                    |--------------------------------------------------------------------------
                    | Parse JSON safely
                    |--------------------------------------------------------------------------
                    */

                    .then(function (text) {

                        /*
                        | Remove UTF-8 BOM if the response contains one.
                        */

                        text = text
                            .replace(/^\uFEFF/, '')
                            .trim();

                        let data;

                        try {

                            data = JSON.parse(text);

                        } catch (error) {

                            console.error(
                                'Invalid JSON returned by student search:',
                                text
                            );

                            throw error;
                        }

                        return data;
                    })

                    /*
                    |--------------------------------------------------------------------------
                    | Process API response
                    |--------------------------------------------------------------------------
                    */

                    .then(function (data) {

                        console.log(
                            'Student search response:',
                            data
                        );

                        /*
                        | Our controller returns:
                        |
                        | {
                        |     success: true,
                        |     students: [...]
                        | }
                        |
                        */

                        if (
                            !data ||
                            data.success !== true
                        ) {

                            showMessage(
                                'Unable to search students.'
                            );

                            return;
                        }

                        showResults(
                            Array.isArray(data.students)
                                ? data.students
                                : []
                        );
                    })

                    /*
                    |--------------------------------------------------------------------------
                    | Error
                    |--------------------------------------------------------------------------
                    */

                    .catch(function (error) {

                        console.error(
                            'Transport student search error:',
                            error
                        );

                        showMessage(
                            'Unable to search students.'
                        );
                    });

                },
                300
            );
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Close dropdown when clicking outside
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest(
                    '.student-search-field'
                )
            ) {

                hideResults();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Escape key
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Escape') {

                hideResults();
            }
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Keep selected student after page reload
    |--------------------------------------------------------------------------
    */

    const selectedStudentId =
        studentIdInput.value;

    if (selectedStudentId) {

        /*
        | The controller already provides
        | $selectedStudent, so the value
        | in the input is restored by Blade.
        |
        | Nothing else is required here.
        */

        console.log(
            'Selected student:',
            selectedStudentId
        );
    }

});
</script>

@endsection
