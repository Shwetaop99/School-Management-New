@extends('layouts.app')

@section('title', 'Payment History')

@section('content')

<style>
    /* =========================================================
       PAYMENT HISTORY DASHBOARD
    ========================================================= */

    .dashboard-container {
        width: 100%;
        max-width: 1600px;
        margin: 0 auto;
        padding: 28px;
        background: #f4f7fb;
    }

    /* =========================================================
       WELCOME BANNER
    ========================================================= */

    .welcome-card {
        position: relative;
        overflow: hidden;
        min-height: 145px;
        padding: 30px 34px;
        margin-bottom: 24px;
        border-radius: 18px;

        background: linear-gradient(
            135deg,
            #1769d1 0%,
            #159cc7 100%
        );

        color: #fff;

        box-shadow:
            0 10px 28px rgba(23, 105, 209, 0.18);

        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .welcome-card::before {
        content: "";
        position: absolute;

        width: 190px;
        height: 190px;

        right: -45px;
        top: -105px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .welcome-card::after {
        content: "";
        position: absolute;

        width: 120px;
        height: 120px;

        right: 100px;
        bottom: -82px;

        border-radius: 50%;

        background: rgba(255,255,255,.12);
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-icon {
        width: 46px;
        height: 46px;

        margin-bottom: 12px;

        border-radius: 12px;

        background: rgba(255,255,255,.15);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 22px;
    }

    .welcome-card h2 {
        position: relative;
        z-index: 2;

        margin: 0 0 7px;

        font-size: 28px;
        font-weight: 800;
    }

    .welcome-card p {
        position: relative;
        z-index: 2;

        margin: 0;

        font-size: 14px;

        color: rgba(255,255,255,.94);
    }

    /* =========================================================
       MAIN STAT CARDS
    ========================================================= */

    .stats-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;
        overflow: hidden;

        min-height: 165px;

        padding: 24px 25px;

        border-radius: 17px;

        color: #fff;

        display: flex;
        flex-direction: column;
        justify-content: space-between;

        box-shadow:
            0 8px 22px rgba(15,23,42,.12);

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);

        box-shadow:
            0 15px 32px rgba(15,23,42,.18);
    }

    .stat-card::before {
        content: "";
        position: absolute;

        width: 150px;
        height: 150px;

        right: -50px;
        top: -65px;

        border-radius: 50%;

        background: rgba(255,255,255,.10);
    }

    .stat-card::after {
        content: "";
        position: absolute;

        width: 80px;
        height: 80px;

        right: -20px;
        bottom: -38px;

        border-radius: 50%;

        background: rgba(255,255,255,.08);
    }

    .stat-card.blue {
        background: linear-gradient(
            135deg,
            #1769d1,
            #237de0
        );
    }

    .stat-card.orange {
        background: linear-gradient(
            135deg,
            #ed9208,
            #f7aa25
        );
    }

    .stat-card.cyan {
        background: linear-gradient(
            135deg,
            #079dbd,
            #16b5d0
        );
    }

    .stat-card.red {
        background: linear-gradient(
            135deg,
            #e94d47,
            #f75d56
        );
    }

    .stat-top {
        position: relative;
        z-index: 2;

        display: flex;

        align-items: flex-start;

        justify-content: space-between;
    }

    .stat-number {
        margin: 0 0 7px;

        font-size: 30px;

        line-height: 1;

        font-weight: 800;
    }

    .stat-title {
        font-size: 14px;

        font-weight: 600;

        color: rgba(255,255,255,.95);
    }

    .stat-icon {
        position: relative;
        z-index: 2;

        width: 48px;
        height: 48px;

        border-radius: 13px;

        background: rgba(255,255,255,.15);

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 25px;

        color: rgba(255,255,255,.95);
    }

    .stat-link {
        position: relative;
        z-index: 2;

        width: fit-content;

        margin-top: 15px;

        color: #fff;

        text-decoration: none;

        font-size: 12px;

        font-weight: 600;
    }

    .stat-link:hover {
        color: #fff;

        transform: translateX(4px);
    }

    /* =========================================================
       DASHBOARD CARD
    ========================================================= */

    .dashboard-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #e5ebf3;

        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15,23,42,.06);

        margin-bottom: 22px;
    }

    .card-header {
        min-height: 70px;

        padding: 17px 22px;

        border-bottom: 1px solid #edf1f6;

        display: flex;

        align-items: center;

        justify-content: space-between;
    }

    .card-title {
        display: flex;

        align-items: center;

        gap: 9px;
    }

    .card-title i {
        color: #1769d1;

        font-size: 19px;
    }

    .card-title h3 {
        margin: 0;

        color: #172033;

        font-size: 17px;

        font-weight: 700;
    }

    .card-subtitle {
        margin: 4px 0 0 28px;

        color: #718096;

        font-size: 12px;
    }

    .card-body {
        padding: 22px;
    }

    /* =========================================================
       FILTERS
    ========================================================= */

    .filter-grid {
        display: grid;

        grid-template-columns:
            minmax(0, 2fr)
            minmax(170px, 1fr)
            minmax(160px, 1fr)
            auto;

        gap: 15px;

        align-items: end;
    }

    .form-label {
        font-size: 12px;

        font-weight: 700;

        color: #536078;

        margin-bottom: 7px;
    }

    .form-control,
    .form-select {
        min-height: 44px;

        border: 1px solid #dce4ef;

        border-radius: 10px;

        color: #344054;

        box-shadow: none;

        font-size: 13px;
    }

    .form-control::placeholder {
        color: #a0aabd;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1769d1;

        box-shadow:
            0 0 0 3px rgba(23,105,209,.10);
    }

    .filter-actions {
        display: flex;

        gap: 8px;
    }

    .btn-search {
        min-height: 44px;

        padding: 0 19px;

        border: none;

        border-radius: 10px;

        background: #1769d1;

        color: #fff;

        font-weight: 650;

        box-shadow:
            0 5px 12px rgba(23,105,209,.18);

        transition: .2s;
    }

    .btn-search:hover {
        background: #125ab5;

        color: #fff;

        transform: translateY(-1px);
    }

    .btn-reset {
        min-height: 44px;

        padding: 0 17px;

        border-radius: 10px;

        background: #f1f4f8;

        color: #566176;

        text-decoration: none;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        font-weight: 650;
    }

    .btn-reset:hover {
        background: #e7ebf1;

        color: #344054;
    }

    /* =========================================================
       TABLE
    ========================================================= */

    .table-wrapper {
        overflow-x: auto;
    }

    .payment-table {
        width: 100%;

        min-width: 1100px;

        border-collapse: collapse;
    }

    .payment-table th {
        background: #f8fafc;

        color: #64748b;

        font-size: 11px;

        font-weight: 750;

        text-transform: uppercase;

        letter-spacing: .35px;

        padding: 14px 16px;

        border-bottom: 1px solid #e7edf5;

        white-space: nowrap;
    }

    .payment-table td {
        padding: 15px 16px;

        border-bottom: 1px solid #eef2f7;

        color: #39465d;

        font-size: 13px;

        vertical-align: middle;

        background: #fff;
    }

    .payment-table tbody tr {
        transition: .18s ease;
    }

    .payment-table tbody tr:hover td {
        background: #f8fbff;
    }

    .payment-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =========================================================
       RECEIPT
    ========================================================= */

    .receipt-box {
        display: flex;

        align-items: center;

        gap: 9px;
    }

    .receipt-icon {
        width: 35px;
        height: 35px;

        flex-shrink: 0;

        border-radius: 10px;

        background: #dbeafe;

        color: #1769d1;

        display: flex;

        align-items: center;

        justify-content: center;
    }

    .receipt-number {
        color: #1769d1;

        font-weight: 750;

        white-space: nowrap;
    }

    /* =========================================================
       STUDENT
    ========================================================= */

    .student-box {
        display: flex;

        align-items: center;

        gap: 10px;
    }

    .student-avatar {
        width: 38px;
        height: 38px;

        flex-shrink: 0;

        border-radius: 11px;

        background: #ede9fe;

        color: #7c3aed;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 13px;

        font-weight: 800;
    }

    .student-name {
        color: #172033;

        font-size: 13px;

        font-weight: 700;
    }

    .student-id {
        margin-top: 3px;

        color: #64748b;

        font-size: 11px;
    }

    /* =========================================================
       AMOUNT
    ========================================================= */

    .amount {
        display: inline-flex;

        align-items: center;

        gap: 5px;

        padding: 7px 11px;

        border-radius: 9px;

        background: #dcfce7;

        color: #15803d;

        font-weight: 800;

        white-space: nowrap;
    }

    /* =========================================================
       DATE
    ========================================================= */

    .date-box {
        display: flex;

        align-items: center;

        gap: 7px;

        color: #536078;

        white-space: nowrap;
    }

    .date-box i {
        color: #1769d1;
    }

    /* =========================================================
       METHOD
    ========================================================= */

    .method-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 11px;

        border-radius: 20px;

        background: #e0f2fe;

        color: #0369a1;

        font-size: 11px;

        font-weight: 700;

        white-space: nowrap;
    }

    /* =========================================================
       TRANSACTION
    ========================================================= */

    .reference {
        display: inline-block;

        max-width: 180px;

        padding: 6px 9px;

        border-radius: 7px;

        background: #f8fafc;

        color: #64748b;

        font-size: 12px;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;
    }

    /* =========================================================
       ACTION
    ========================================================= */

    .action-btn {
        width: 37px;
        height: 37px;

        border-radius: 10px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        text-decoration: none;

        background: #ede9fe;

        color: #7c3aed;

        border: 1px solid #ddd6fe;

        transition: .2s;
    }

    .action-btn:hover {
        background: #7c3aed;

        color: #fff;

        border-color: #7c3aed;

        transform: translateY(-2px);

        box-shadow:
            0 5px 12px rgba(124,58,237,.20);
    }

    /* =========================================================
       PAYMENT COUNT
    ========================================================= */

    .payment-count {
        padding: 7px 12px;

        border-radius: 20px;

        background: #dbeafe;

        color: #1769d1;

        font-size: 11px;

        font-weight: 750;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .empty-state {
        padding: 60px 20px;

        text-align: center;
    }

    .empty-icon {
        width: 68px;
        height: 68px;

        margin: 0 auto 14px;

        border-radius: 18px;

        background: #dbeafe;

        color: #1769d1;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 29px;
    }

    .empty-state-title {
        color: #172033;

        font-size: 16px;

        font-weight: 750;

        margin-bottom: 5px;
    }

    .empty-state-text {
        color: #94a3b8;

        font-size: 13px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .pagination-wrapper {
        padding: 18px 22px;

        border-top: 1px solid #edf1f6;

        background: #fafbfe;
    }

    .fee-currency {
        white-space: nowrap;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1200px) {

        .stats-grid {
            grid-template-columns:
                repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns:
                1fr 1fr;
        }

        .filter-actions {
            grid-column: 1 / -1;
        }

    }

    @media (max-width: 850px) {

        .dashboard-container {
            padding: 18px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
        }

    }

    @media (max-width: 650px) {

        .dashboard-container {
            padding: 14px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .welcome-card {
            padding: 25px;
        }

        .welcome-card h2 {
            font-size: 24px;
        }

        .stat-card {
            min-height: 150px;
        }

        .card-body {
            padding: 16px;
        }

    }
</style>


<div class="dashboard-container">

    {{-- =====================================================
         WELCOME BANNER
    ====================================================== --}}

    <div class="welcome-card">

        <div class="welcome-content">

            <div class="welcome-icon">
                <i class="bi bi-credit-card-2-front"></i>
            </div>

            <h2>
                Payment History
            </h2>

            <p>
                Track and manage all student fee payments from one place.
            </p>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="stats-grid">

        {{-- TOTAL PAYMENTS --}}

        <div class="stat-card blue">

            <div class="stat-top">

                <div>
                    <div class="stat-number">
                        {{ $totalPayments }}
                    </div>

                    <div class="stat-title">
                        Total Payments
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-receipt"></i>
                </div>

            </div>

            <a href="#" class="stat-link">
                Payment records
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>


        {{-- TOTAL COLLECTED --}}

        <div class="stat-card orange">

            <div class="stat-top">

                <div>
                    <div class="stat-number fee-currency">
                        &#8377;{{ number_format((float) $totalCollected, 2) }}
                    </div>

                    <div class="stat-title">
                        Total Collected
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-currency-rupee"></i>
                </div>

            </div>

            <a href="#" class="stat-link">
                Collection overview
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>


        {{-- TODAY COLLECTION --}}

        <div class="stat-card cyan">

            <div class="stat-top">

                <div>
                    <div class="stat-number fee-currency">
                        &#8377;{{ number_format((float) $todayCollected, 2) }}
                    </div>

                    <div class="stat-title">
                        Today's Collection
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

            </div>

            <a href="#" class="stat-link">
                Today's payments
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>


        {{-- CASH COLLECTION --}}

        <div class="stat-card red">

            <div class="stat-top">

                <div>
                    <div class="stat-number fee-currency">
                        &#8377;{{ number_format((float) $cashCollected, 2) }}
                    </div>

                    <div class="stat-title">
                        Cash Collection
                    </div>
                </div>

                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>

            </div>

            <a href="#" class="stat-link">
                Cash payments
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>

    </div>


    {{-- =====================================================
         FILTER CARD
    ====================================================== --}}

    <div class="dashboard-card">

        <div class="card-header">

            <div>

                <div class="card-title">

                    <i class="bi bi-funnel-fill"></i>

                    <h3>
                        Payment Filters
                    </h3>

                </div>

                <div class="card-subtitle">
                    Search payments using receipt, student, method or date.
                </div>

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.fees.payment-history.index') }}"
            >

                <div class="filter-grid">

                    <div>

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Receipt, student, ID or transaction reference"
                        >

                    </div>


                    <div>

                        <label class="form-label">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            class="form-select"
                        >

                            <option value="">
                                All Methods
                            </option>

                            @foreach([
                                'Cash',
                                'UPI',
                                'Card',
                                'Bank Transfer',
                                'Cheque'
                            ] as $method)

                                <option
                                    value="{{ $method }}"
                                    {{ request('payment_method') === $method ? 'selected' : '' }}
                                >
                                    {{ $method }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label class="form-label">
                            Payment Date
                        </label>

                        <input
                            type="date"
                            name="payment_date"
                            class="form-control"
                            value="{{ request('payment_date') }}"
                        >

                    </div>


                    <div class="filter-actions">

                        <button
                            type="submit"
                            class="btn-search"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('admin.fees.payment-history.index') }}"
                            class="btn-reset"
                        >
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         PAYMENTS TABLE
    ====================================================== --}}

    <div class="dashboard-card">

        <div class="card-header">

            <div>

                <div class="card-title">

                    <i class="bi bi-receipt-cutoff"></i>

                    <h3>
                        All Payments
                    </h3>

                </div>

                <div class="card-subtitle">
                    Complete list of student fee transactions.
                </div>

            </div>


            <span class="payment-count">

                {{ $payments->total() }}

                payment(s)

            </span>

        </div>


        <div class="table-wrapper">

            <table class="payment-table">

                <thead>

                    <tr>

                        <th>
                            Receipt No.
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Academic Year
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Payment Date
                        </th>

                        <th>
                            Method
                        </th>

                        <th>
                            Transaction Reference
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            {{-- RECEIPT --}}

                            <td>

                                <div class="receipt-box">

                                    <div class="receipt-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>

                                    <span class="receipt-number">
                                        {{ $payment->receipt_number }}
                                    </span>

                                </div>

                            </td>


                            {{-- STUDENT --}}

                            <td>

                                @if($payment->studentFee?->student)

                                    @php

                                        $student =
                                            $payment->studentFee->student;

                                        $studentName = trim(
                                            $student->first_name . ' ' .
                                            ($student->middle_name ?? '') . ' ' .
                                            $student->last_name
                                        );

                                        $initials = strtoupper(
                                            substr(
                                                $student->first_name ?? 'S',
                                                0,
                                                1
                                            ) .
                                            substr(
                                                $student->last_name ?? '',
                                                0,
                                                1
                                            )
                                        );

                                    @endphp


                                    <div class="student-box">

                                        <div class="student-avatar">
                                            {{ $initials }}
                                        </div>

                                        <div>

                                            <div class="student-name">
                                                {{ $studentName }}
                                            </div>

                                            <div class="student-id">
                                                {{ $student->student_id }}
                                            </div>

                                        </div>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        Student unavailable
                                    </span>

                                @endif

                            </td>


                            {{-- ACADEMIC YEAR --}}

                            <td>
                                {{ $payment->studentFee?->academic_year ?? '—' }}
                            </td>


                            {{-- AMOUNT --}}

                            <td>

                                <span class="amount">

                                    <i class="bi bi-arrow-down-left"></i>

                                    &#8377;{{ number_format(
                                        (float) $payment->amount,
                                        2
                                    ) }}

                                </span>

                            </td>


                            {{-- DATE --}}

                            <td>

                                <div class="date-box">

                                    <i class="bi bi-calendar3"></i>

                                    {{ $payment->payment_date?->format('d M Y') ?? '—' }}

                                </div>

                            </td>


                            {{-- METHOD --}}

                            <td>

                                <span class="method-badge">

                                    @if($payment->payment_method === 'Cash')

                                        <i class="bi bi-cash"></i>

                                    @elseif($payment->payment_method === 'UPI')

                                        <i class="bi bi-phone"></i>

                                    @elseif($payment->payment_method === 'Card')

                                        <i class="bi bi-credit-card"></i>

                                    @elseif($payment->payment_method === 'Bank Transfer')

                                        <i class="bi bi-bank"></i>

                                    @else

                                        <i class="bi bi-file-earmark-text"></i>

                                    @endif

                                    {{ $payment->payment_method }}

                                </span>

                            </td>


                            {{-- TRANSACTION REFERENCE --}}

                            <td>

                                @if($payment->transaction_reference)

                                    <span class="reference">
                                        {{ $payment->transaction_reference }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}

                            <td>

                                <a
                                    href="{{ route(
                                        'admin.fees.payment-history.show',
                                        $payment
                                    ) }}"
                                    class="action-btn"
                                    title="View Payment"
                                >

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>

                                    <div class="empty-state-title">
                                        No Payment Records Found
                                    </div>

                                    <div class="empty-state-text">
                                        There are no payment records matching your filters.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}

        @if($payments->hasPages())

            <div class="pagination-wrapper">

                {{ $payments->links() }}

            </div>

        @endif

    </div>

</div>

@endsection