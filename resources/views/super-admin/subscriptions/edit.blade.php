
@extends('super-admin.layouts.app')

@section('title', 'Edit Subscription')
@section('breadcrumb', 'Subscriptions / Edit')
@section('page-title', 'Edit subscription')

@section('content')

@php
    $selectedPlanId = old(
        'subscription_plan_id',
        $subscription->subscription_plan_id
    );
@endphp

<div class="subscription-edit">

    <div class="edit-heading">
        <div>
            <div class="edit-eyebrow">School Central / Billing</div>
            <h2>Edit subscription</h2>
            <p>Update the school's assigned plan and subscription details.</p>
        </div>

        <a href="{{ route('super-admin.subscriptions.show', $subscription) }}"
           class="edit-btn edit-btn--secondary">
            &larr; Back to details
        </a>
    </div>

    @if ($errors->any())
        <div class="edit-alert">
            <strong>Please correct the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-layout">

        <section class="edit-card">
            <div class="edit-card-heading">
                <div>
                    <h3>Subscription information</h3>
                    <p>Fields marked with * are required.</p>
                </div>
            </div>

            <form method="POST"
                  action="{{ route('super-admin.subscriptions.update', $subscription) }}">
                @csrf
                @method('PUT')

                <div class="edit-form-grid">

                    <div class="edit-field edit-field--full">
                        <label>School</label>
                        <input type="text"
                               value="{{ $subscription->school?->name ?? 'School unavailable' }}"
                               disabled>
                        <small>The school cannot be changed here.</small>
                    </div>

                    <div class="edit-field edit-field--full">
                        <label for="subscription_plan_id">Subscription plan *</label>
                        <select id="subscription_plan_id"
                                name="subscription_plan_id"
                                required>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    @selected((string) $selectedPlanId === (string) $plan->id)>
                                    {{ $plan->name }}
                                    — ₹{{ number_format((float) $plan->price, 2) }}
                                    ({{ $plan->duration_days }} days)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="edit-field">
                        <label for="starts_at">Start date *</label>
                        <input type="date"
                               id="starts_at"
                               name="starts_at"
                               value="{{ old('starts_at', $subscription->starts_at?->format('Y-m-d')) }}"
                               required>
                    </div>

                    <div class="edit-field">
                        <label for="agreed_price">Agreed price (₹) *</label>
                        <input type="number"
                               id="agreed_price"
                               name="agreed_price"
                               min="0"
                               max="999999999.99"
                               step="0.01"
                               value="{{ old('agreed_price', $subscription->agreed_price) }}"
                               required>
                    </div>

                    <div class="edit-field edit-field--full">
                        <label for="notes">Notes</label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="4"
                                  maxlength="2000"
                                  placeholder="Add subscription-related notes...">{{ old('notes', $subscription->notes) }}</textarea>
                    </div>

                </div>

                <div class="edit-notice">
                    <strong>Before saving</strong>
                    <p>
                        The expiry date is recalculated from the selected plan's
                        duration and start date. Saving changes does not confirm
                        a payment or activate a pending subscription.
                    </p>
                </div>

                <div class="edit-form-actions">
                    <a href="{{ route('super-admin.subscriptions.show', $subscription) }}"
                       class="edit-btn edit-btn--secondary">
                        Cancel
                    </a>

                    <button type="submit" class="edit-btn edit-btn--primary">
                        Save changes
                    </button>
                </div>
            </form>
        </section>

        <aside class="edit-card edit-side-card">
            <div class="edit-card-heading">
                <div>
                    <h3>Current status</h3>
                    <p>Existing subscription record</p>
                </div>
            </div>

            <div class="edit-side-row">
                <span>Subscription ID</span>
                <strong>#{{ $subscription->id }}</strong>
            </div>

            <div class="edit-side-row">
                <span>Current plan</span>
                <strong>{{ $subscription->plan?->name ?? 'Unavailable' }}</strong>
            </div>

            <div class="edit-side-row">
                <span>Subscription type</span>
                <strong>{{ ucfirst($subscription->type ?? 'unknown') }}</strong>
            </div>

            <div class="edit-side-row">
                <span>Status</span>
                <strong>{{ ucfirst($subscription->status ?? 'pending') }}</strong>
            </div>

            <div class="edit-side-row">
                <span>Current expiry</span>
                <strong>{{ $subscription->ends_at?->format('d M Y') ?? 'Not set' }}</strong>
            </div>

            <div class="edit-side-note">
                <strong>Payment safety</strong>
                <p>
                    If a payment has already been recorded, verify its amount
                    before changing the agreed price. Confirmed payments should
                    remain in the payment history.
                </p>
            </div>
        </aside>

    </div>
</div>

<style>
.subscription-edit {
    color: var(--ink, #1f2937);
}

.edit-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.edit-eyebrow {
    margin-bottom: 7px;
    color: var(--muted, #75827d);
    font-size: 12px;
    font-weight: 700;
}

.edit-heading h2 {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 800;
}

.edit-heading p,
.edit-card-heading p {
    margin: 0;
    color: var(--muted, #75827d);
    font-size: 13px;
}

.edit-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(260px, .8fr);
    gap: 20px;
    align-items: start;
}

.edit-card {
    min-width: 0;
    padding: 22px;
    margin-bottom: 20px;
    border: 1px solid var(--line, #e5e7eb);
    border-radius: 14px;
    background: var(--surface, #fff);
}

.edit-card-heading {
    margin-bottom: 22px;
}

.edit-card-heading h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 800;
}

.edit-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px 16px;
}

.edit-field {
    min-width: 0;
}

.edit-field--full {
    grid-column: 1 / -1;
}

.edit-field label {
    display: block;
    margin-bottom: 8px;
    color: var(--ink, #1f2937);
    font-size: 12px;
    font-weight: 750;
}

.edit-field input,
.edit-field select,
.edit-field textarea {
    display: block;
    box-sizing: border-box;
    width: 100%;
    min-height: 43px;
    padding: 10px 12px;
    border: 1px solid var(--line, #d1d5db);
    border-radius: 9px;
    outline: none;
    background: var(--surface, #fff);
    color: var(--ink, #1f2937);
    font: inherit;
    font-size: 13px;
}

.edit-field textarea {
    resize: vertical;
    line-height: 1.6;
}

.edit-field input:focus,
.edit-field select:focus,
.edit-field textarea:focus {
    border-color: #6b9d84;
    box-shadow: 0 0 0 3px rgba(45, 125, 85, .10);
}

.edit-field input:disabled {
    background: var(--canvas, #f3f5f4);
    color: var(--muted, #75827d);
    cursor: not-allowed;
}

.edit-field small {
    display: block;
    margin-top: 6px;
    color: var(--muted, #75827d);
    font-size: 11px;
}

.edit-notice {
    margin-top: 24px;
    padding: 15px;
    border: 1px solid #dbeafe;
    border-radius: 10px;
    background: #eff6ff;
}

.edit-notice strong {
    color: #1e40af;
    font-size: 12px;
}

.edit-notice p {
    margin: 6px 0 0;
    color: #1e3a8a;
    font-size: 12px;
    line-height: 1.6;
}

.edit-form-actions {
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--line, #e5e7eb);
}

.edit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 10px 14px;
    border: 1px solid transparent;
    border-radius: 8px;
    text-decoration: none;
    font-family: inherit;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
}

.edit-btn--secondary {
    border-color: var(--line, #e5e7eb);
    background: var(--canvas, #f3f5f4);
    color: var(--ink, #1f2937);
}

.edit-btn--primary {
    background: var(--green, #18794e);
    color: #fff;
}

.edit-side-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid var(--line, #e5e7eb);
    font-size: 12px;
}

.edit-side-row span {
    color: var(--muted, #75827d);
}

.edit-side-row strong {
    text-align: right;
    overflow-wrap: anywhere;
}

.edit-side-note {
    margin-top: 20px;
    padding: 15px;
    border-radius: 10px;
    background: var(--canvas, #f3f5f4);
}

.edit-side-note strong {
    font-size: 12px;
}

.edit-side-note p {
    margin: 7px 0 0;
    color: var(--muted, #75827d);
    font-size: 12px;
    line-height: 1.6;
}

.edit-alert {
    margin-bottom: 20px;
    padding: 14px 16px;
    border-radius: 9px;
    background: #fef2f2;
    color: #991b1b;
    font-size: 13px;
}

.edit-alert ul {
    margin: 8px 0 0;
    padding-left: 20px;
}

@media (max-width: 900px) {
    .edit-layout {
        grid-template-columns: 1fr;
        gap: 0;
    }
}

@media (max-width: 560px) {
    .edit-card {
        padding: 16px;
    }

    .edit-form-grid {
        grid-template-columns: 1fr;
    }

    .edit-field--full {
        grid-column: auto;
    }

    .edit-heading h2 {
        font-size: 21px;
    }

    .edit-heading > a {
        width: 100%;
    }

    .edit-form-actions > * {
        flex: 1;
    }
}
</style>

@endsection
