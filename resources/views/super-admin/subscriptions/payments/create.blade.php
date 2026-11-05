
@extends('super-admin.layouts.app')

@section('title', 'Record Payment')
@section('breadcrumb', 'Subscriptions / Record Payment')
@section('page-title', 'Record Offline Payment')

@section('content')
<div class="panel">
    <div class="panel-head">
        <div>
            <h3>Record School Payment</h3>
            <p>Enter the payment details for this subscription.</p>
        </div>
    </div>

    <div style="padding: 24px;">

        <div style="background: var(--canvas); padding: 18px; border-radius: 10px; margin-bottom: 24px;">
            <p>
                <strong>School:</strong>
                {{ $subscription->school->name }}
            </p>

            <p>
                <strong>Plan:</strong>
                {{ $subscription->plan->name }}
            </p>

            <p>
                <strong>Agreed amount:</strong>
                ₹{{ number_format((float) $subscription->agreed_price, 2) }}
            </p>

            <p>
                <strong>Subscription status:</strong>
                Pending
            </p>
        </div>

        @if ($errors->any())
            <div style="color: var(--red); margin-bottom: 18px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <p style="color: var(--red); margin-bottom: 18px;">
                {{ session('error') }}
            </p>
        @endif

        <form
            method="POST"
            action="{{ route('super-admin.subscriptions.payments.store', $subscription) }}"
        >
            @csrf

            <div style="margin-bottom: 20px;">
                <label for="amount">Amount received (₹)</label>
                <input
                    id="amount"
                    name="amount"
                    type="number"
                    min="0"
                    max="999999999"
                    step="0.01"
                    value="{{ old('amount', $subscription->agreed_price) }}"
                    required
                    style="display:block; width:100%; padding:11px; margin-top:7px; border:1px solid var(--line); border-radius:8px;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label for="payment_method">Payment method</label>
                <select
                    id="payment_method"
                    name="payment_method"
                    required
                    style="display:block; width:100%; padding:11px; margin-top:7px; border:1px solid var(--line); border-radius:8px;"
                >
                    <option value="">Select payment method</option>
                    <option value="cash" @selected(old('payment_method') === 'cash')>Cash</option>
                    <option value="bank_transfer" @selected(old('payment_method') === 'bank_transfer')>Bank Transfer</option>
                    <option value="upi" @selected(old('payment_method') === 'upi')>UPI</option>
                    <option value="cheque" @selected(old('payment_method') === 'cheque')>Cheque</option>
                    <option value="other" @selected(old('payment_method') === 'other')>Other</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="reference_number">Transaction / receipt reference</label>
                <input
                    id="reference_number"
                    name="reference_number"
                    type="text"
                    maxlength="255"
                    value="{{ old('reference_number') }}"
                    placeholder="Enter transaction ID or receipt number"
                    style="display:block; width:100%; padding:11px; margin-top:7px; border:1px solid var(--line); border-radius:8px;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label for="paid_at">Payment date</label>
                <input
                    id="paid_at"
                    name="paid_at"
                    type="date"
                    max="{{ now()->toDateString() }}"
                    value="{{ old('paid_at', now()->toDateString()) }}"
                    required
                    style="display:block; width:100%; padding:11px; margin-top:7px; border:1px solid var(--line); border-radius:8px;"
                >
            </div>

            <div style="margin-bottom: 24px;">
                <label for="notes">Notes</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="3"
                    maxlength="1000"
                    placeholder="Optional payment remarks"
                    style="display:block; width:100%; padding:11px; margin-top:7px; border:1px solid var(--line); border-radius:8px; resize:vertical;"
                >{{ old('notes') }}</textarea>
            </div>

            <div style="padding:14px; background:var(--green-soft); border-radius:8px; margin-bottom:24px;">
                <strong>Important:</strong>
                Recording a payment does not activate the subscription.
                Verify the payment before confirming it.
            </div>

            <button type="submit" class="welcome-action">
                Record Payment
            </button>

            <a
                href="{{ route('super-admin.subscriptions.index') }}"
                style="margin-left:14px;"
            >
                Cancel
            </a>
        </form>
    </div>
</div>
@endsection
