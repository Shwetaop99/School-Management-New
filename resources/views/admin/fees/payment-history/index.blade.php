@extends('layouts.app')

@section('title', 'Payment History')
@section('page-title', 'Payment History')

@section('content')

@php
    $hasFilters = request()->filled('search')
        || request()->filled('payment_method')
        || request()->filled('payment_date');
@endphp

<div class="payment-history-page">
    <div class="ph-container">

        {{-- SUCCESS / ERROR --}}
        @if(session('success'))
            <div class="ph-alert ph-success" role="status">
                <span class="ph-alert-icon">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="ph-alert ph-error" role="status">
                <span class="ph-alert-icon">
                    <i class="bi bi-exclamation-lg" aria-hidden="true"></i>
                </span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- HERO --}}
        <section class="ph-hero">
            <div class="ph-hero-content">
                <div class="ph-hero-icon">
                    <i class="bi bi-credit-card-2-front" aria-hidden="true"></i>
                </div>

                <div class="ph-hero-text">
                    <div class="ph-breadcrumb">
                        <span>Fee Management</span>
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        <span>Payment History</span>
                    </div>

                    <h2>Payment History</h2>

                    <p>
                        Track and manage all student fee payments, receipts,
                        payment methods and transaction references from one place.
                    </p>

                    <div class="ph-hero-note">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        <span>School Fees &amp; Payment Records Management</span>
                    </div>
                </div>
            </div>

            <div class="ph-hero-actions">
                <div class="ph-hero-decoration">
                    <i class="bi bi-wallet2" aria-hidden="true"></i>
                </div>

                <div class="ph-handwritten">Every Payment. Properly Recorded.</div>

                <p>
                    Keep student fee collections organised and
                    payment records accurate for every academic year.
                </p>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="ph-kpis">

            <article class="ph-kpi blue" data-tab="RECORDS">
                <div class="ph-kpi-top">
                    <span class="ph-kpi-label">Total Payments</span>
                    <span class="ph-kpi-icon">
                        <i class="bi bi-receipt" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="ph-kpi-value">{{ number_format($totalPayments) }}</div>
                <div class="ph-kpi-caption">All recorded student payments</div>
            </article>

            <article class="ph-kpi green" data-tab="COLLECTED">
                <div class="ph-kpi-top">
                    <span class="ph-kpi-label">Total Collected</span>
                    <span class="ph-kpi-icon">
                        <i class="bi bi-currency-rupee" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="ph-kpi-value">
                    ₹{{ number_format((float) $totalCollected, 2) }}
                </div>
                <div class="ph-kpi-caption">Total fee collection received</div>
            </article>

            <article class="ph-kpi amber" data-tab="TODAY">
                <div class="ph-kpi-top">
                    <span class="ph-kpi-label">Today’s Collection</span>
                    <span class="ph-kpi-icon">
                        <i class="bi bi-calendar-check" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="ph-kpi-value">
                    ₹{{ number_format((float) $todayCollected, 2) }}
                </div>
                <div class="ph-kpi-caption">Payments collected today</div>
            </article>

            <article class="ph-kpi red" data-tab="CASH">
                <div class="ph-kpi-top">
                    <span class="ph-kpi-label">Cash Collection</span>
                    <span class="ph-kpi-icon">
                        <i class="bi bi-wallet2" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="ph-kpi-value">
                    ₹{{ number_format((float) $cashCollected, 2) }}
                </div>
                <div class="ph-kpi-caption">Total cash payments received</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="ph-paper-panel">

            <div class="ph-panel-header">
                <div class="ph-title-wrap">
                    <div class="ph-title-icon">
                        <i class="bi bi-receipt-cutoff" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h3>All Payments</h3>
                        <p>Complete list of student fee transactions.</p>
                    </div>
                </div>

                <div class="ph-record-count">
                    <i class="bi bi-folder2-open" aria-hidden="true"></i>
                    <strong>{{ number_format($payments->total()) }}</strong>
                    Payments
                </div>
            </div>

            {{-- FILTERS --}}
            <form action="{{ route('admin.fees.payment-history.index') }}"
                  method="GET"
                  class="ph-filter-panel">

                <div class="ph-filter-heading">
                    <div class="ph-filter-heading-icon">
                        <i class="bi bi-sliders" aria-hidden="true"></i>
                    </div>

                    <div>
                        <h4>Find Payments</h4>
                        <p>
                            Search payments using receipt number, student,
                            payment method or payment date.
                        </p>
                    </div>
                </div>

                <div class="ph-filter-grid">

                    <div class="ph-filter-group">
                        <label for="ph-search">Search Payments</label>

                        <div class="ph-search-input">
                            <i class="bi bi-search" aria-hidden="true"></i>

                            <input id="ph-search"
                                   type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Receipt, student, ID or transaction reference..."
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="ph-filter-group">
                        <label for="ph-payment-method">Payment Method</label>

                        <select id="ph-payment-method"
                                name="payment_method"
                                class="ph-control">
                            <option value="">All Methods</option>

                            @foreach([
                                'Cash',
                                'UPI',
                                'Card',
                                'Bank Transfer',
                                'Cheque'
                            ] as $method)
                                <option value="{{ $method }}"
                                    {{ request('payment_method') === $method ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="ph-filter-group">
                        <label for="ph-payment-date">Payment Date</label>

                        <input id="ph-payment-date"
                               type="date"
                               name="payment_date"
                               class="ph-control"
                               value="{{ request('payment_date') }}">
                    </div>

                    <div class="ph-filter-actions">
                        <button type="submit" class="ph-apply-btn">
                            <i class="bi bi-search" aria-hidden="true"></i>
                            Search
                        </button>

                        <a href="{{ route('admin.fees.payment-history.index') }}"
                           class="ph-reset-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="ph-active-filters">
                    <div class="ph-filter-items">
                        <span class="ph-filter-label">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                            Active filters
                        </span>

                        @if(request()->filled('search'))
                            <span class="ph-filter-chip">
                                <i class="bi bi-search" aria-hidden="true"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request()->filled('payment_method'))
                            <span class="ph-filter-chip">
                                <i class="bi bi-credit-card" aria-hidden="true"></i>
                                {{ request('payment_method') }}
                            </span>
                        @endif

                        @if(request()->filled('payment_date'))
                            <span class="ph-filter-chip">
                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                {{ request('payment_date') }}
                            </span>
                        @endif
                    </div>

                    <span class="ph-result-count">
                        {{ number_format($payments->total()) }} result(s)
                    </span>
                </div>
            @endif

            {{-- TABLE --}}
            @if($payments->count() > 0)

                <div class="ph-table-wrapper">
                    <table class="ph-table">
                        <thead>
                            <tr>
                                <th scope="col">Receipt No.</th>
                                <th scope="col">Student</th>
                                <th scope="col">Academic Year</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment Date</th>
                                <th scope="col">Method</th>
                                <th scope="col">Transaction Reference</th>
                                <th scope="col" class="ph-actions-heading">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="ph-receipt-cell">
                                            <span class="ph-receipt-icon">
                                                <i class="bi bi-receipt" aria-hidden="true"></i>
                                            </span>

                                            <div class="ph-receipt-text">
                                                <strong>{{ $payment->receipt_number }}</strong>
                                                <span>Payment Receipt</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($payment->studentFee?->student)
                                            @php
                                                $student = $payment->studentFee->student;

                                                $studentName = trim(
                                                    $student->first_name . ' ' .
                                                    ($student->middle_name ?? '') . ' ' .
                                                    $student->last_name
                                                );

                                                $initials = strtoupper(
                                                    substr($student->first_name ?? 'S', 0, 1) .
                                                    substr($student->last_name ?? '', 0, 1)
                                                );
                                            @endphp

                                            <div class="ph-student-info">
                                                <div class="ph-student-avatar">
                                                    {{ $initials }}
                                                </div>

                                                <div class="ph-student-details">
                                                    <div class="ph-student-name"
                                                         title="{{ $studentName }}">
                                                        {{ $studentName }}
                                                    </div>

                                                    <div class="ph-student-id">
                                                        <i class="bi bi-id-badge" aria-hidden="true"></i>
                                                        {{ $student->student_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="ph-muted">Student unavailable</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="ph-info-cell">
                                            <span class="ph-info-icon blue">
                                                <i class="bi bi-calendar3" aria-hidden="true"></i>
                                            </span>

                                            <div class="ph-info-text">
                                                <strong>
                                                    {{ $payment->studentFee?->academic_year ?? '—' }}
                                                </strong>
                                                <span>Academic Year</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="ph-amount">
                                            ₹{{ number_format((float) $payment->amount, 2) }}
                                        </div>
                                        <div class="ph-cell-caption">Payment Amount</div>
                                    </td>

                                    <td>
                                        @if($payment->payment_date)
                                            <div class="ph-date">
                                                <div class="ph-date-primary">
                                                    <i class="bi bi-calendar-event" aria-hidden="true"></i>
                                                    {{ $payment->payment_date->format('d M Y') }}
                                                </div>
                                                <div class="ph-cell-caption">Payment Date</div>
                                            </div>
                                        @else
                                            <span class="ph-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="ph-method">
                                            @if($payment->payment_method === 'Cash')
                                                <i class="bi bi-cash" aria-hidden="true"></i>
                                            @elseif($payment->payment_method === 'UPI')
                                                <i class="bi bi-phone" aria-hidden="true"></i>
                                            @elseif($payment->payment_method === 'Card')
                                                <i class="bi bi-credit-card" aria-hidden="true"></i>
                                            @elseif($payment->payment_method === 'Bank Transfer')
                                                <i class="bi bi-bank" aria-hidden="true"></i>
                                            @else
                                                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                            @endif

                                            {{ $payment->payment_method }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($payment->transaction_reference)
                                            <span class="ph-reference"
                                                  title="{{ $payment->transaction_reference }}">
                                                {{ $payment->transaction_reference }}
                                            </span>
                                        @else
                                            <span class="ph-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="ph-actions">
                                            <a href="{{ route('admin.fees.payment-history.show', $payment) }}"
                                               class="ph-action-btn view"
                                               title="View Payment"
                                               aria-label="View payment {{ $payment->receipt_number }}">
                                                <i class="bi bi-eye" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($payments->hasPages())
                    <div class="ph-pagination">
                        <div class="ph-pagination-info">
                            Showing <strong>{{ $payments->firstItem() }}</strong>
                            to <strong>{{ $payments->lastItem() }}</strong>
                            of <strong>{{ $payments->total() }}</strong> payments
                        </div>

                        <div class="ph-pagination-links">
                            {{ $payments->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

                {{-- FOOTER --}}
                <div class="ph-card-footer">
                    <div>
                        Showing <strong>{{ $payments->count() }}</strong>
                        payment(s) on this page
                    </div>

                    <div class="ph-footer-status">
                        <i class="bi bi-check-circle" aria-hidden="true"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="ph-empty-state">
                    <div class="ph-empty-icon">
                        <i class="bi bi-receipt" aria-hidden="true"></i>
                    </div>

                    @if($hasFilters)
                        <h4>No Matching Payments Found</h4>
                        <p>
                            No payment records match your current search or
                            selected filters. Try adjusting your filters.
                        </p>

                        <a href="{{ route('admin.fees.payment-history.index') }}"
                           class="ph-primary-btn">
                            <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                            Clear Filters
                        </a>
                    @else
                        <h4>No Payment Records Yet</h4>
                        <p>
                            There are currently no student fee payment records.
                            Payments will appear here once recorded.
                        </p>
                    @endif
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* PAYMENT HISTORY — TRANSPORT PAGE DESIGN SYSTEM
   Scoped so it does not affect other pages. */

.payment-history-page {
    --ph-ink: #263b35;
    --ph-text: #39483e;
    --ph-muted: #7b857b;
    --ph-line: #e5e1d5;
    --ph-paper: #fffefa;
    --ph-chalk: #f5f3e9;
    --ph-blue: #426b8a;
    --ph-green: #43835d;
    --ph-amber: #a87543;
    --ph-red: #b95b50;
    --ph-serif: var(--serif, Georgia, "Times New Roman", serif);
    --ph-hand: var(--hand, "Caveat", cursive);

    min-height: calc(100vh - 70px);
    color: var(--ph-text);
    background: #f7f6f0;
    font-family: "Public Sans", sans-serif;
    font-size: 13px;
}

.payment-history-page *,
.payment-history-page *::before,
.payment-history-page *::after {
    box-sizing: border-box;
}

.payment-history-page a,
.payment-history-page button,
.payment-history-page .ph-kpi {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.payment-history-page .ph-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.payment-history-page .ph-hero,
.payment-history-page .ph-paper-panel {
    border: 1px solid var(--ph-line);
    border-radius: 15px;
    background: var(--ph-paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.payment-history-page .ph-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.payment-history-page .ph-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.payment-history-page .ph-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.payment-history-page .ph-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ph-ink);
    background: var(--ph-chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.payment-history-page .ph-hero-text {
    min-width: 0;
}

.payment-history-page .ph-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.payment-history-page .ph-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.payment-history-page .ph-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ph-ink);
    font-family: var(--ph-serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.payment-history-page .ph-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--ph-muted);
    font-size: 12px;
    line-height: 1.9;
}

.payment-history-page .ph-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.payment-history-page .ph-hero-note i {
    font-size: 13px;
}

.payment-history-page .ph-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.payment-history-page .ph-hero-actions::before,
.payment-history-page .ph-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.payment-history-page .ph-hero-actions::before {
    top: -115px;
    right: -60px;
}

.payment-history-page .ph-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.payment-history-page .ph-hero-decoration {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.payment-history-page .ph-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--ph-hand);
    font-size: 23px;
    line-height: 1.4;
}

.payment-history-page .ph-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

/* BUTTONS */

.payment-history-page .ph-primary-btn,
.payment-history-page .ph-apply-btn,
.payment-history-page .ph-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 10px 16px;
    border: 1px solid var(--ph-ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ph-ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.payment-history-page .ph-primary-btn {
    position: relative;
    z-index: 1;
}

.payment-history-page .ph-primary-btn:hover,
.payment-history-page .ph-apply-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.payment-history-page .ph-reset-btn {
    border-color: #deded2;
    color: #5d6b60;
    background: var(--ph-paper);
}

.payment-history-page .ph-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ph-ink);
    background: #f0f3e9;
}

/* ALERTS */

.payment-history-page .ph-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
}

.payment-history-page .ph-success {
    border: 1px solid #d2e5d1;
    color: #347849;
    background: #f1f8ef;
}

.payment-history-page .ph-error {
    border: 1px solid #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.payment-history-page .ph-alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    background: rgba(255, 255, 255, .7);
}

/* KPI CARDS */

.payment-history-page .ph-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.payment-history-page .ph-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--ph-line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--ph-paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.payment-history-page .ph-kpi.blue { --kpi-color: #426b8a; }
.payment-history-page .ph-kpi.green { --kpi-color: #43835d; }
.payment-history-page .ph-kpi.amber { --kpi-color: #a87543; }
.payment-history-page .ph-kpi.red { --kpi-color: #b95b50; }

.payment-history-page .ph-kpi::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
    content: attr(data-tab);
}

.payment-history-page .ph-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.payment-history-page .ph-kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.payment-history-page .ph-kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.payment-history-page .ph-kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.payment-history-page .ph-kpi-value {
    color: var(--ph-ink);
    font-family: var(--ph-serif);
    font-size: clamp(22px, 2vw, 35px);
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.payment-history-page .ph-kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.payment-history-page .ph-paper-panel {
    overflow: hidden;
}

.payment-history-page .ph-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
}

.payment-history-page .ph-title-wrap,
.payment-history-page .ph-filter-heading {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.payment-history-page .ph-title-icon,
.payment-history-page .ph-filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ph-ink);
    background: #f1f2e8;
    font-size: 16px;
}

.payment-history-page .ph-title-wrap h3,
.payment-history-page .ph-filter-heading h4 {
    margin: 0;
    color: var(--ph-ink);
    font-family: var(--ph-serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.payment-history-page .ph-title-wrap p,
.payment-history-page .ph-filter-heading p {
    margin: 5px 0 0;
    color: var(--ph-muted);
    font-size: 11px;
    line-height: 1.7;
}

.payment-history-page .ph-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.payment-history-page .ph-record-count i,
.payment-history-page .ph-record-count strong {
    color: var(--ph-ink);
}

/* FILTERS */

.payment-history-page .ph-filter-panel {
    margin: 0;
    padding: 23px 24px;
    border-bottom: 1px solid var(--ph-line);
    background: #faf9f3;
}

.payment-history-page .ph-filter-heading {
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.payment-history-page .ph-filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.payment-history-page .ph-filter-heading h4 {
    font-size: 17px;
}

.payment-history-page .ph-filter-grid {
    display: grid;
    grid-template-columns:
        minmax(220px, 2fr)
        minmax(150px, 1fr)
        minmax(150px, .9fr)
        auto;
    align-items: end;
    gap: 16px;
}

.payment-history-page .ph-filter-group {
    min-width: 0;
}

.payment-history-page .ph-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.payment-history-page .ph-control,
.payment-history-page .ph-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    color: var(--ph-text);
    background: var(--ph-paper);
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.payment-history-page .ph-control {
    padding: 0 12px;
    cursor: pointer;
}

.payment-history-page .ph-control:hover,
.payment-history-page .ph-search-input:hover {
    border-color: #bdcabb;
}

.payment-history-page .ph-control:focus,
.payment-history-page .ph-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.payment-history-page .ph-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.payment-history-page .ph-search-input > i {
    color: #829083;
    font-size: 13px;
}

.payment-history-page .ph-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    color: var(--ph-text);
    background: transparent;
    font: inherit;
}

.payment-history-page .ph-search-input input::placeholder {
    color: #9a9f93;
}

.payment-history-page .ph-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

/* ACTIVE FILTERS */

.payment-history-page .ph-active-filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 24px;
    border-bottom: 1px solid #eeece3;
}

.payment-history-page .ph-filter-items {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    min-width: 0;
}

.payment-history-page .ph-filter-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #758075;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
    text-transform: uppercase;
}

.payment-history-page .ph-filter-label i {
    color: var(--ph-green);
}

.payment-history-page .ph-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    max-width: 260px;
    padding: 6px 10px;
    overflow-wrap: anywhere;
    border: 1px solid #e7dfc9;
    border-radius: 7px;
    color: #765e32;
    background: #faf5e8;
    font-size: 10px;
    font-weight: 700;
}

.payment-history-page .ph-result-count {
    color: #788277;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

/* TABLE */

.payment-history-page .ph-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.payment-history-page .ph-table {
    width: 100%;
    min-width: 1250px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.payment-history-page .ph-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.payment-history-page .ph-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--ph-text);
    font-size: 11px;
    vertical-align: middle;
    white-space: nowrap;
}

.payment-history-page .ph-table tbody tr {
    background: var(--ph-paper);
    transition: background .18s ease;
}

.payment-history-page .ph-table tbody tr:hover {
    background: #f8f8f1;
}

.payment-history-page .ph-table tbody tr:last-child td {
    border-bottom: none;
}

.payment-history-page .ph-table .ph-actions-heading {
    text-align: right;
}

/* RECEIPT CELL */

.payment-history-page .ph-receipt-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 170px;
}

.payment-history-page .ph-receipt-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border: 1px solid #ebdfcc;
    border-radius: 10px;
    color: var(--ph-amber);
    background: #faf2e7;
    font-size: 13px;
}

.payment-history-page .ph-receipt-text strong {
    display: block;
    max-width: 160px;
    overflow: hidden;
    color: #293d32;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.payment-history-page .ph-receipt-text span,
.payment-history-page .ph-cell-caption {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

/* STUDENT CELL */

.payment-history-page .ph-student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.payment-history-page .ph-student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ph-ink);
    background: #edf1e7;
    font-size: 13px;
    font-weight: 800;
}

.payment-history-page .ph-student-details {
    min-width: 0;
}

.payment-history-page .ph-student-name {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.payment-history-page .ph-student-id {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.payment-history-page .ph-student-id i {
    color: var(--ph-amber);
}

/* INFO CELLS */

.payment-history-page .ph-info-cell {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 115px;
}

.payment-history-page .ph-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 13px;
}

.payment-history-page .ph-info-icon.blue {
    border-color: #d9e4ec;
    color: var(--ph-blue);
    background: #edf4f8;
}

.payment-history-page .ph-info-text {
    min-width: 0;
}

.payment-history-page .ph-info-text strong {
    display: block;
    max-width: 155px;
    overflow: hidden;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.payment-history-page .ph-info-text span {
    display: block;
    margin-top: 4px;
    color: #879083;
    font-size: 9px;
}

/* AMOUNT, DATE, METHOD, REFERENCE */

.payment-history-page .ph-amount {
    color: var(--ph-green);
    font-family: var(--ph-serif);
    font-size: 15px;
    font-weight: 700;
}

.payment-history-page .ph-date {
    min-width: 105px;
}

.payment-history-page .ph-date-primary {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #34443a;
    font-size: 10px;
    font-weight: 800;
}

.payment-history-page .ph-date-primary i {
    color: var(--ph-blue);
}

.payment-history-page .ph-method {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid #d9e4ec;
    border-radius: 7px;
    color: var(--ph-blue);
    background: #edf4f8;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.payment-history-page .ph-reference {
    display: inline-block;
    max-width: 190px;
    overflow: hidden;
    color: #526358;
    font-size: 10px;
    font-weight: 700;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.payment-history-page .ph-muted {
    color: #929a8e;
    font-size: 12px;
}

/* ACTIONS */

.payment-history-page .ph-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.payment-history-page .ph-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e2e1d7;
    border-radius: 9px;
    color: #657369;
    background: var(--ph-paper);
    font-size: 12px;
    text-decoration: none;
    cursor: pointer;
}

.payment-history-page .ph-action-btn:hover {
    transform: translateY(-2px);
}

.payment-history-page .ph-action-btn.view:hover {
    border-color: #cbdce5;
    color: var(--ph-blue);
    background: #edf4f8;
}

/* PAGINATION AND FOOTER */

.payment-history-page .ph-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
}

.payment-history-page .ph-pagination-info {
    color: var(--ph-muted);
    font-size: 10px;
}

.payment-history-page .ph-pagination-info strong,
.payment-history-page .ph-card-footer strong {
    color: var(--ph-ink);
}

.payment-history-page .ph-pagination-links {
    min-width: 0;
}

.payment-history-page .ph-pagination-links nav {
    margin: 0;
}

.payment-history-page .ph-pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.payment-history-page .ph-pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--ph-line);
    border-radius: 8px !important;
    color: var(--ph-ink);
    background: var(--ph-paper);
    font-size: 11px;
    box-shadow: none;
}

.payment-history-page .ph-pagination-links .page-link:hover {
    border-color: #b5c5b0;
    color: var(--ph-green);
    background: #f2f4e9;
}

.payment-history-page .ph-pagination-links .page-item.active .page-link {
    border-color: var(--ph-ink);
    color: #fff;
    background: var(--ph-ink);
}

.payment-history-page .ph-pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

.payment-history-page .ph-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.payment-history-page .ph-footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.payment-history-page .ph-footer-status i {
    color: var(--ph-green);
}

/* EMPTY STATE */

.payment-history-page .ph-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.payment-history-page .ph-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ph-ink);
    background: var(--ph-chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.payment-history-page .ph-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ph-ink);
    font-family: var(--ph-serif);
    font-size: 22px;
    font-weight: 700;
}

.payment-history-page .ph-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--ph-muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .payment-history-page .ph-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        row-gap: 42px;
    }

    .payment-history-page .ph-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .payment-history-page .ph-hero {
        grid-template-columns: 1fr;
    }

    .payment-history-page .ph-hero-actions {
        min-height: 220px;
    }

    .payment-history-page .ph-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .payment-history-page .ph-container {
        padding: 18px 14px;
    }

    .payment-history-page .ph-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .payment-history-page .ph-hero::before {
        left: 18px;
    }

    .payment-history-page .ph-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .payment-history-page .ph-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .payment-history-page .ph-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .payment-history-page .ph-hero-text h2 {
        font-size: 24px;
    }

    .payment-history-page .ph-hero-text p {
        font-size: 11px;
    }

    .payment-history-page .ph-hero-note {
        align-items: flex-start;
        line-height: 1.7;
    }

    .payment-history-page .ph-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .payment-history-page .ph-handwritten {
        font-size: 21px;
    }

    .payment-history-page .ph-kpis {
        column-gap: 15px;
        row-gap: 40px;
        margin-top: 31px;
    }

    .payment-history-page .ph-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .payment-history-page .ph-kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .payment-history-page .ph-kpi-value {
        font-size: 27px;
    }

    .payment-history-page .ph-kpi-caption {
        font-size: 9px;
    }

    .payment-history-page .ph-panel-header {
        align-items: flex-start;
        flex-wrap: wrap;
        padding: 16px;
    }

    .payment-history-page .ph-title-wrap {
        gap: 10px;
    }

    .payment-history-page .ph-title-icon,
    .payment-history-page .ph-filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .payment-history-page .ph-title-wrap h3,
    .payment-history-page .ph-filter-heading h4 {
        font-size: 16px;
    }

    .payment-history-page .ph-title-wrap p {
        font-size: 10px;
    }

    .payment-history-page .ph-filter-panel {
        padding: 17px 15px;
    }

    .payment-history-page .ph-filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .payment-history-page .ph-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .payment-history-page .ph-filter-actions > * {
        flex: 1;
    }

    .payment-history-page .ph-active-filters,
    .payment-history-page .ph-pagination,
    .payment-history-page .ph-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .payment-history-page .ph-result-count {
        white-space: normal;
    }

    .payment-history-page .ph-pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .payment-history-page .ph-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .payment-history-page .ph-kpis {
        grid-template-columns: 1fr;
    }

    .payment-history-page .ph-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .payment-history-page .ph-filter-chip {
        max-width: 100%;
    }
}

/* ACCESSIBILITY AND REDUCED MOTION */

.payment-history-page a:focus-visible,
.payment-history-page button:focus-visible,
.payment-history-page input:focus-visible,
.payment-history-page select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .payment-history-page *,
    .payment-history-page *::before,
    .payment-history-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection