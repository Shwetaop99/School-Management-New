
@extends('super-admin.layouts.app')

@section('title', 'Assign Subscription')
@section('breadcrumb', 'Subscriptions / Assign plan')
@section('page-title', 'Assign a subscription')

@section('content')

<div class="welcome">
    <div>
        <div class="eyebrow">School subscription management</div>
        <h2>Assign a plan</h2>
        <p>Select the school and paid plan. Access remains pending until payment is confirmed.</p>
    </div>

    <a href="{{ route('super-admin.subscriptions.index') }}" class="chip">
        ← Back to subscriptions
    </a>
</div>

@if($errors->any())
    <div style="padding:15px 18px;margin-bottom:20px;border:1px solid #f2d8d3;border-radius:10px;background:#fff2ef;color:#a83f36;">
        <strong>Please correct the following:</strong>
        <ul style="margin:8px 0 0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel">
    <div class="panel-head">
        <div>
            <h3>Subscription details</h3>
            <p>Choose a registered school and an active paid plan.</p>
        </div>
        <span class="count-chip">Payment pending</span>
    </div>

    <form method="POST" action="{{ route('super-admin.subscriptions.assign.store') }}">
        @csrf

        <div style="padding:24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,260px),1fr));gap:22px;">

            <div>
                <label for="school_id" style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;">
                    Select school *
                </label>
                <select id="school_id" name="school_id" required
                        style="width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink);">
                    <option value="">Choose a school</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}"
                            @selected((string)old('school_id') === (string)$school->id)>
                            {{ $school->name }} ({{ $school->school_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="subscription_plan_id" style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;">
                    Subscription plan *
                </label>
                <select id="subscription_plan_id" name="subscription_plan_id" required
                        style="width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;background:#fff;color:var(--ink);">
                    <option value="">Choose a plan</option>
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}"
                            data-price="{{ $plan->price }}"
                            data-duration="{{ $plan->duration_days }}"
                            @selected((string)old('subscription_plan_id') === (string)$plan->id)>
                            {{ $plan->name }} — ₹{{ number_format((float)$plan->price, 2) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="starts_at" style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;">
                    Start date *
                </label>
                <input id="starts_at" name="starts_at" type="date"
                       min="{{ now()->toDateString() }}"
                       value="{{ old('starts_at', now()->toDateString()) }}" required
                       style="width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;">
            </div>

            <div>
                <label for="agreed_price" style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;">
                    Agreed price (₹) *
                </label>
                <input id="agreed_price" name="agreed_price" type="number"
                       min="0" max="999999999" step="0.01"
                       value="{{ old('agreed_price') }}" required
                       placeholder="Enter agreed amount"
                       style="width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;">
                <small style="display:block;margin-top:6px;color:var(--muted);">
                    You can adjust this for an agreed offline price.
                </small>
            </div>

            <div style="grid-column:1/-1;">
                <label for="notes" style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;">
                    Notes (optional)
                </label>
                <textarea id="notes" name="notes" rows="3" maxlength="1000"
                          placeholder="Sales agreement or subscription notes..."
                          style="width:100%;padding:12px;border:1px solid var(--line);border-radius:9px;resize:vertical;">{{ old('notes') }}</textarea>
            </div>

        </div>

        <div style="padding:16px 24px;border-top:1px solid var(--line);background:#fbfcfb;color:var(--muted);font-size:12px;line-height:1.7;">
            Assigning a plan does not activate school access. Payment recording, confirmation and feature enforcement are separate steps.
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding:20px 24px;">
            <a href="{{ route('super-admin.subscriptions.index') }}" class="chip">
                Cancel
            </a>
            <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                Assign plan
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const planSelect = document.getElementById('subscription_plan_id');
        const priceInput = document.getElementById('agreed_price');

        planSelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];

            if (option && option.dataset.price !== undefined) {
                priceInput.value = option.dataset.price;
            }
        });
    });
</script>

@endsection
