@extends('super-admin.layouts.app')

@section('title', 'Edit '.$plan->name)
@section('breadcrumb', 'Subscriptions / Edit plan')
@section('page-title', 'Edit subscription plan')

@section('content')

<div class="welcome">
    <div>
        <div class="eyebrow">Plan configuration</div>
        <h2>{{ $plan->name }}</h2>
        <p>Update pricing, subscription duration and the features available to schools.</p>
    </div>

    <a href="{{ route('super-admin.subscription-plans.index') }}" class="chip">
        ← Back to plans
    </a>
</div>

@if($errors->any())
    <div class="alert alert--error">
        <strong>Please correct these errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel plan-edit">
    <div class="panel-head">
        <div>
            <h3>Plan settings</h3>
            <p>Plan name and slug remain unchanged to protect existing references.</p>
        </div>
        <span class="count-chip">{{ $plan->is_trial ? 'Free trial' : 'Paid plan' }}</span>
    </div>

    <form method="POST" action="{{ route('super-admin.subscription-plans.update', $plan) }}">
        @csrf
        @method('PUT')

        {{-- Basic fields --}}
        <div class="plan-edit__section">
            <div class="plan-edit__grid">
                <div class="form-group">
                    <label for="plan_name">Plan name</label>
                    <input id="plan_name" type="text" value="{{ $plan->name }}" disabled class="input input--disabled">
                </div>

                <div class="form-group">
                    <label for="slug">Plan identifier</label>
                    <input id="slug" type="text" value="{{ $plan->slug }}" disabled class="input input--disabled">
                </div>

                <div class="form-group">
                    <label for="price">Price (₹)</label>
                    <input id="price"
                           name="price"
                           type="number"
                           min="0"
                           max="999999999"
                           step="0.01"
                           required
                           value="{{ old('price', $plan->is_trial ? 0 : $plan->price) }}"
                           @disabled($plan->is_trial)
                           class="input {{ $plan->is_trial ? 'input--disabled' : '' }}">
                    @if($plan->is_trial)
                        <small class="form-hint">Free trials must remain free.</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="duration_days">Duration (days)</label>
                    <input id="duration_days"
                           name="duration_days"
                           type="number"
                           min="1"
                           max="3650"
                           required
                           value="{{ old('duration_days', $plan->is_trial ? 30 : $plan->duration_days) }}"
                           @disabled($plan->is_trial)
                           class="input {{ $plan->is_trial ? 'input--disabled' : '' }}">
                    @if($plan->is_trial)
                        <small class="form-hint">Trial duration is fixed at 30 days.</small>
                    @endif
                </div>

                <div class="form-group form-group--full">
                    <label for="description">Plan description</label>
                    <textarea id="description"
                              name="description"
                              rows="3"
                              maxlength="1000"
                              placeholder="Describe the benefits of this plan..."
                              class="input input--textarea">{{ old('description', $plan->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Features --}}
        <div class="plan-edit__section plan-edit__section--features">
            <div class="plan-edit__section-header">
                <h4>Feature access</h4>
                <p>Select which features this plan includes. These selections save to the plan record; enforcement across school modules must also be connected separately.</p>
            </div>

            <div class="feature-grid">
                @foreach($availableFeatures as $key => $label)
                    @php
                        $selectedFeatures = old('features', $plan->features ?? []);
                        if (!is_array($selectedFeatures)) {
                            $selectedFeatures = [];
                        }
                        $checked = $plan->is_trial || in_array($key, $selectedFeatures, true);
                    @endphp

                    <label class="feature-item {{ $plan->is_trial ? 'feature-item--locked' : '' }}">
                        <input type="checkbox"
                               name="features[]"
                               value="{{ $key }}"
                               @checked($checked)
                               @disabled($plan->is_trial)
                               class="feature-item__checkbox">
                        <span class="feature-item__label">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

            @if($plan->is_trial)
                <input type="hidden" name="features[]" value="dashboard">
                <p class="form-hint" style="margin-top:14px;">
                    The trial automatically retains all available features.
                </p>
            @endif
        </div>

        {{-- Active toggle --}}
        <div class="plan-edit__section plan-edit__section--status">
            <label class="status-toggle">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       @checked(old('is_active', $plan->is_active) == 1)
                       class="status-toggle__input">
                <span class="status-toggle__text">Plan is active and available for assignment</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="plan-edit__actions">
            <a href="{{ route('super-admin.subscription-plans.index') }}" class="chip">
                Cancel
            </a>
            <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                Save changes
            </button>
        </div>
    </form>
</div>

<style>
    /* Alerts */
    .alert {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .alert--error {
        border: 1px solid #f2d8d3;
        background: #fff2ef;
        color: #b54f43;
    }

    .alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    /* Form layout */
    .plan-edit__section {
        padding: 24px;
    }

    .plan-edit__section--features {
        padding-top: 8px;
        border-top: 1px solid var(--line, #e5e7eb);
    }

    .plan-edit__section--status {
        padding: 18px 24px;
        border-top: 1px solid var(--line, #e5e7eb);
        background: #fbfcfb;
    }

    .plan-edit__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group--full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 800;
        color: var(--ink, #111827);
        letter-spacing: 0.01em;
    }

    .input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        color: var(--ink, #111827);
        font-size: 14px;
        line-height: 1.4;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .input:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.18);
    }

    .input--disabled {
        background: #f5f7f5;
        color: var(--muted, #6b7280);
        cursor: not-allowed;
    }

    .input--textarea {
        resize: vertical;
        min-height: 90px;
    }

    .form-hint {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        color: var(--muted, #6b7280);
        line-height: 1.4;
    }

    /* Features section */
    .plan-edit__section-header {
        margin-bottom: 18px;
    }

    .plan-edit__section-header h4 {
        margin: 0 0 6px;
        font-family: Manrope, system-ui, sans-serif;
        font-size: 16px;
        font-weight: 800;
        color: var(--ink, #111827);
    }

    .plan-edit__section-header p {
        margin: 0;
        color: var(--muted, #6b7280);
        font-size: 13px;
        line-height: 1.6;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(100%, 230px), 1fr));
        gap: 12px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 14px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
    }

    .feature-item:hover:not(.feature-item--locked) {
        border-color: #cbd5e1;
        background: #f8fafc;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .feature-item--locked {
        background: #f8fafc;
        cursor: not-allowed;
        opacity: 0.85;
    }

    .feature-item__checkbox {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
        accent-color: #0f766e;
        cursor: pointer;
    }

    .feature-item--locked .feature-item__checkbox {
        cursor: not-allowed;
    }

    .feature-item__label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        line-height: 1.35;
    }

    /* Status toggle */
    .status-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--ink, #111827);
        cursor: pointer;
    }

    .status-toggle__input {
        width: 16px;
        height: 16px;
        accent-color: #0f766e;
        cursor: pointer;
    }

    /* Actions */
    .plan-edit__actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 18px 24px;
        border-top: 1px solid var(--line, #e5e7eb);
        background: #fff;
    }

    @media (max-width: 640px) {
        .plan-edit__grid {
            grid-template-columns: 1fr;
        }

        .plan-edit__actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .plan-edit__actions .chip,
        .plan-edit__actions .welcome-action {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }
</style>

@endsection