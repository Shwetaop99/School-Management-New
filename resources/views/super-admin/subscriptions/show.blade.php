
@extends('super-admin.layouts.app')

@section('title', 'View Subscription')
@section('breadcrumb', 'Subscriptions / View')
@section('page-title', 'Subscription details')

@section('content')

@php
    $school = $subscription->school;
    $plan = $subscription->plan;

    $latestPayment = $subscription->payments
        ->sortByDesc('created_at')
        ->first();

    $isExpired = $subscription->ends_at
        && $subscription->ends_at->lte(now());

    $displayStatus = $isExpired
        ? 'expired'
        : strtolower($subscription->status ?? 'pending');
@endphp

<div class="subscription-view">

    {{-- Page heading --}}
    <div class="view-heading">
        <div>
            
            <p>Review subscription information and verify recorded payments.</p>
        </div>

        <a href="{{ route('super-admin.subscriptions.index') }}"
           class="view-btn view-btn--secondary">
            &larr; Back to subscriptions
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="view-alert view-alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="view-alert view-alert--error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="view-alert view-alert--error">
            <strong>Please check the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Subscription summary --}}
    <div class="view-grid">

        <section class="view-card view-card--main">
            <div class="view-card-heading">
                <div>
                    <h3>Subscription overview</h3>
                    <p>Plan and subscription period</p>
                </div>

                <span class="view-status view-status--{{ $displayStatus }}">
                    {{ ucfirst($displayStatus) }}
                </span>
            </div>

            <div class="view-details">
                <div class="view-detail">
                    <span>School name</span>
                    <strong>{{ $school?->name ?? 'School unavailable' }}</strong>
                </div>

                <div class="view-detail">
                    <span>School code</span>
                    <strong>{{ $school?->school_code ?? '—' }}</strong>
                </div>

                <div class="view-detail">
                    <span>Subscription plan</span>
                    <strong>{{ $plan?->name ?? 'Plan unavailable' }}</strong>
                </div>

                <div class="view-detail">
                    <span>Subscription type</span>
                    <strong>{{ ucfirst($subscription->type ?? 'unknown') }}</strong>
                </div>

                <div class="view-detail">
                    <span>Start date</span>
                    <strong>{{ $subscription->starts_at?->format('d M Y') ?? 'Not set' }}</strong>
                </div>

                <div class="view-detail">
                    <span>Expiry date</span>
                    <strong>{{ $subscription->ends_at?->format('d M Y') ?? 'Not set' }}</strong>
                </div>

                <div class="view-detail">
                    <span>Agreed price</span>
                    <strong class="view-price">
                        ₹{{ number_format((float) $subscription->agreed_price, 2) }}
                    </strong>
                </div>

                <div class="view-detail">
                    <span>Created on</span>
                    <strong>{{ $subscription->created_at?->format('d M Y, h:i A') ?? '—' }}</strong>
                </div>
            </div>

            @if ($subscription->notes)
                <div class="view-notes">
                    <span>Subscription notes</span>
                    <p>{{ $subscription->notes }}</p>
                </div>
            @endif
        </section>

        {{-- Payment summary --}}
        <section class="view-card">
            <div class="view-card-heading">
                <div>
                    <h3>Payment summary</h3>
                    <p>Latest recorded payment</p>
                </div>
                <span class="view-payment-icon">₹</span>
            </div>

            @if ($latestPayment)
                <div class="payment-summary">
                    <span class="payment-label">Recorded amount</span>

                    <div class="payment-amount">
                        ₹{{ number_format((float) $latestPayment->amount, 2) }}
                    </div>

                    <div class="payment-summary-row">
                        <span>Payment status</span>
                        <strong>{{ ucfirst($latestPayment->status) }}</strong>
                    </div>

                    <div class="payment-summary-row">
                        <span>Payment method</span>
                        <strong>{{ ucwords(str_replace('_', ' ', $latestPayment->payment_method)) }}</strong>
                    </div>

                    <div class="payment-summary-row">
                        <span>Payment date</span>
                        <strong>{{ $latestPayment->paid_at?->format('d M Y') ?? '—' }}</strong>
                    </div>

                    <div class="payment-summary-row">
                        <span>Reference number</span>
                        <strong>{{ $latestPayment->reference_number ?: '—' }}</strong>
                    </div>

                    @if ($latestPayment->notes)
                        <div class="payment-notes">
                            <span>Payment notes</span>
                            <p>{{ $latestPayment->notes }}</p>
                        </div>
                    @endif
                </div>

                @if (
                    $latestPayment->status === 'pending'
                    && $subscription->status === 'pending'
                    && $subscription->type === 'paid'
                )
                    <div class="confirm-panel">
                        <strong>Payment awaiting verification</strong>
                        <p>
                            Verify the payment details and amount before activating
                            this subscription.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('super-admin.subscriptions.payments.confirm', [$subscription, $latestPayment]) }}"
                            onsubmit="return confirm('Confirm that this payment has been verified and activate the subscription?');"
                        >
                            @csrf

                            <button type="submit" class="view-btn view-btn--confirm">
                                Confirm Payment &amp; Activate
                            </button>
                        </form>
                    </div>
                @elseif ($latestPayment->status === 'confirmed')
                    <div class="payment-confirmed">
                        Payment confirmed
                        @if ($latestPayment->confirmed_at)
                            <span>
                                on {{ $latestPayment->confirmed_at->format('d M Y, h:i A') }}
                            </span>
                        @endif
                    </div>
                @endif

            @else
                <div class="payment-empty">
                    <div class="payment-empty-icon">₹</div>
                    <strong>No payment recorded</strong>
                    <p>A payment record has not been added to this subscription yet.</p>

                    @if ($subscription->type === 'paid' && $subscription->status === 'pending')
                        <a
                            href="{{ route('super-admin.subscriptions.payments.create', $subscription) }}"
                            class="view-btn view-btn--primary"
                        >
                            + Record Payment
                        </a>
                    @endif
                </div>
            @endif
        </section>

    </div>

    {{-- Payment history --}}
    <section class="view-card payment-history">
        <div class="view-card-heading">
            <div>
                <h3>Payment history</h3>
                <p>All payment records associated with this subscription</p>
            </div>

            <span class="view-count">
                {{ $subscription->payments->count() }} records
            </span>
        </div>

        @if ($subscription->payments->isNotEmpty())
            <div class="view-table-wrap">
                <table class="view-table">
                    <thead>
                        <tr>
                            <th>Payment date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Recorded on</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($subscription->payments->sortByDesc('created_at') as $payment)
                            <tr>
                                <td>{{ $payment->paid_at?->format('d M Y') ?? '—' }}</td>

                                <td>
                                    <strong>
                                        ₹{{ number_format((float) $payment->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>

                                <td>{{ $payment->reference_number ?: '—' }}</td>

                                <td>
                                    <span class="view-status view-status--{{ strtolower($payment->status) }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>

                                <td>{{ $payment->created_at?->format('d M Y, h:i A') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="history-empty">
                No payment history is available for this subscription.
            </div>
        @endif
    </section>

</div>

<style>
.subscription-view {
    color: var(--ink, #1f2937);
}

.view-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.view-eyebrow {
    color: var(--muted, #75827d);
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 7px;
}

.view-heading h2 {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 800;
}

.view-heading p,
.view-card-heading p {
    margin: 0;
    color: var(--muted, #75827d);
    font-size: 13px;
}

.view-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, 0.8fr);
    gap: 20px;
    align-items: start;
}

.view-card {
    min-width: 0;
    background: var(--surface, #fff);
    border: 1px solid var(--line, #e5e7eb);
    border-radius: 14px;
    padding: 22px;
    margin-bottom: 20px;
}

.view-card-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 22px;
}

.view-card-heading h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 800;
}

.view-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px 18px;
}

.view-detail {
    min-width: 0;
}

.view-detail span,
.payment-label,
.view-notes > span,
.payment-notes > span {
    display: block;
    margin-bottom: 7px;
    color: var(--muted, #75827d);
    font-size: 12px;
}

.view-detail strong {
    display: block;
    overflow-wrap: anywhere;
    font-size: 13.5px;
    font-weight: 700;
}

.view-price {
    color: var(--green, #18794e);
    font-size: 18px !important;
}

.view-notes,
.payment-notes {
    margin-top: 22px;
    padding-top: 17px;
    border-top: 1px solid var(--line, #e5e7eb);
}

.view-notes p,
.payment-notes p {
    margin: 0;
    font-size: 13px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.view-status {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
    background: #f3f4f6;
    color: #4b5563;
}

.view-status--active,
.view-status--confirmed {
    background: #dcfce7;
    color: #166534;
}

.view-status--pending {
    background: #fef3c7;
    color: #92400e;
}

.view-status--expired,
.view-status--cancelled,
.view-status--failed,
.view-status--rejected {
    background: #fee2e2;
    color: #991b1b;
}

.view-payment-icon,
.payment-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 11px;
    background: #ecfdf5;
    color: #047857;
    font-weight: 800;
    font-size: 19px;
}

.payment-amount {
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: 850;
    letter-spacing: -0.5px;
}

.payment-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-top: 1px solid var(--line, #e5e7eb);
    font-size: 12px;
}

.payment-summary-row span {
    color: var(--muted, #75827d);
}

.payment-summary-row strong {
    max-width: 60%;
    text-align: right;
    overflow-wrap: anywhere;
}

.confirm-panel {
    margin-top: 20px;
    padding: 16px;
    border: 1px solid #f3d58a;
    border-radius: 10px;
    background: #fffbeb;
}

.confirm-panel > strong {
    display: block;
    color: #92400e;
    font-size: 13px;
}

.confirm-panel p {
    margin: 7px 0 14px;
    color: #78350f;
    font-size: 12px;
    line-height: 1.6;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 38px;
    padding: 9px 13px;
    border: 1px solid transparent;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    transition: opacity .2s ease;
}

.view-btn:hover {
    opacity: .85;
}

.view-btn--secondary {
    background: var(--canvas, #f3f5f4);
    border-color: var(--line, #e5e7eb);
    color: var(--ink, #1f2937);
}

.view-btn--primary,
.view-btn--confirm {
    background: var(--green, #18794e);
    color: #fff;
}

.view-btn--confirm {
    width: 100%;
}

.payment-confirmed {
    margin-top: 18px;
    padding: 12px;
    border-radius: 8px;
    background: #ecfdf5;
    color: #166534;
    font-size: 12px;
    font-weight: 800;
}

.payment-confirmed span {
    display: block;
    margin-top: 4px;
    font-weight: 500;
}

.payment-empty {
    padding: 12px 0;
    text-align: center;
}

.payment-empty-icon {
    margin: 0 auto 12px;
}

.payment-empty > strong {
    display: block;
    font-size: 14px;
}

.payment-empty p {
    margin: 8px 0 18px;
    color: var(--muted, #75827d);
    font-size: 12px;
    line-height: 1.6;
}

.view-count {
    padding: 6px 10px;
    border-radius: 20px;
    background: var(--canvas, #f3f5f4);
    color: var(--muted, #75827d);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.view-table-wrap {
    overflow-x: auto;
}

.view-table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

.view-table th {
    padding: 12px;
    background: var(--canvas, #f7f8f7);
    color: var(--muted, #75827d);
    font-size: 10px;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .04em;
}

.view-table td {
    padding: 14px 12px;
    border-bottom: 1px solid var(--line, #e5e7eb);
    font-size: 12px;
}

.view-table tbody tr:last-child td {
    border-bottom: 0;
}

.history-empty {
    padding: 28px 12px;
    color: var(--muted, #75827d);
    text-align: center;
    font-size: 13px;
}

.view-alert {
    margin-bottom: 18px;
    padding: 13px 15px;
    border-radius: 9px;
    font-size: 13px;
}

.view-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

.view-alert--success {
    background: #ecfdf5;
    color: #166534;
}

.view-alert--error {
    background: #fef2f2;
    color: #991b1b;
}

@media (max-width: 900px) {
    .view-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }
}

@media (max-width: 560px) {
    .view-card {
        padding: 16px;
    }

    .view-details {
        grid-template-columns: 1fr;
        gap: 17px;
    }

    .view-heading h2 {
        font-size: 21px;
    }

    .view-heading > a {
        width: 100%;
    }
}
</style>

@endsection
