@extends('super-admin.layouts.app')

@section('title', 'Manage Plans')
@section('breadcrumb', 'Subscriptions / Plans')
@section('page-title', 'Subscription plans')

@section('content')

<div class="welcome plans-welcome">
    <div>
        <div class="eyebrow">Pricing & access control</div>
        <h2>Manage subscription plans</h2>
        <p>Configure pricing, duration and feature access for every school.</p>
    </div>
</div>

@if(session('success'))
    <div class="plans-alert plans-alert--success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="plans-alert plans-alert--error">
        Please correct the form errors before continuing.
    </div>
@endif

{{-- Overview cards --}}
<div class="kpis plans-kpis">
    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">Total plans</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
                    <path d="m3 12 9 5 9-5M3 16l9 5 9-5"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $totalPlans }}</div>
        <div class="kpi-foot">Configured subscription plans</div>
    </div>

    <div class="kpi green">
        <div class="kpi-top">
            <span class="kpi-label">Active plans</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="m5 12 4 4L19 6"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $activePlans }}</div>
        <div class="kpi-foot">Available for assignment</div>
    </div>

    <div class="kpi amber">
        <div class="kpi-top">
            <span class="kpi-label">Free trial</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M8 2v4M16 2v4M3 10h18"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">30</div>
        <div class="kpi-foot">Days with full feature access</div>
    </div>

    <div class="kpi red">
        <div class="kpi-top">
            <span class="kpi-label">Billing method</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="M6 4h12M6 9h12M6 14h4a5 5 0 0 0 0-10M10 14l7 7"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value plans-manual">Manual</div>
        <div class="kpi-foot">Offline payment confirmation</div>
    </div>
</div>

{{-- Plan section --}}
<div class="section-heading plans-section-heading">
    <div>
        <h3>Available plans</h3>
        <p>Compare plans and manage their pricing and features.</p>
    </div>
    <span class="count-chip">{{ $plans->count() }} plans</span>
</div>

@php
    // Enforce display order: Free Trial → Silver → Gold → Platinum
    $orderedPlans = $plans->sortBy(function ($plan) {
        $slug = strtolower($plan->slug ?? '');

        if ($plan->is_trial || $slug === 'free-trial' || $slug === 'trial') {
            return 1;
        }
        if ($slug === 'silver') {
            return 2;
        }
        if ($slug === 'gold') {
            return 3;
        }
        if ($slug === 'platinum') {
            return 4;
        }
        return 99; // any other plans last
    })->values();
@endphp

<div class="plans-grid">
    @foreach($orderedPlans as $plan)
        @php
            $features     = is_array($plan->features) ? $plan->features : [];
            $featureCount = count($features);
            $slug         = strtolower($plan->slug ?? '');
            $isTrial      = (bool) $plan->is_trial || in_array($slug, ['free-trial', 'trial'], true);

            if ($isTrial) {
                $theme = 'trial';
                $fallbackDescription = 'Explore the complete platform before subscribing.';
            } elseif ($slug === 'silver') {
                $theme = 'silver';
                $fallbackDescription = 'Essential tools for everyday school operations.';
            } elseif ($slug === 'gold') {
                $theme = 'gold';
                $fallbackDescription = 'Advanced tools for growing institutions.';
            } elseif ($slug === 'platinum') {
                $theme = 'platinum';
                $fallbackDescription = 'Comprehensive tools for complete school management.';
            } else {
                $theme = 'silver';
                $fallbackDescription = 'A configurable plan for your institution.';
            }
        @endphp

        <article class="plan-card plan-card--{{ $theme }}">
            {{-- Header --}}
            <div class="plan-card__header">
                <div class="plan-card__identity">
                    <div class="plan-card__symbol" aria-hidden="true">
                        @if($theme === 'trial')
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <path d="M12 7v10M7 12h10"/>
                            </svg>
                        @elseif($theme === 'silver')
                            <svg viewBox="0 0 24 24">
                                <path d="m12 2 8 7-8 13L4 9l8-7Z"/>
                                <path d="M4 9h16M12 2l-3 7 3 13 3-13-3-7Z"/>
                            </svg>
                        @elseif($theme === 'gold')
                            <svg viewBox="0 0 24 24">
                                <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>
                            </svg>
                        @else
                            <svg viewBox="0 0 24 24">
                                <path d="M3 7 7 3h10l4 4-9 14L3 7Z"/>
                                <path d="M3 7h18M7 3l5 18 5-18"/>
                            </svg>
                        @endif
                    </div>

                    <div class="plan-card__identity-text">
                        <h4 class="plan-card__name">{{ $plan->name }}</h4>
                        <p class="plan-card__description">
                            {{ $plan->description ?: $fallbackDescription }}
                        </p>
                    </div>
                </div>

                @if($isTrial)
                    <span class="plan-badge plan-badge--trial">Trial</span>
                @elseif($plan->is_active)
                    <span class="plan-badge plan-badge--active">Active</span>
                @else
                    <span class="plan-badge plan-badge--inactive">Inactive</span>
                @endif
            </div>

            {{-- Pricing --}}
            <div class="plan-card__pricing">
                <div class="plan-card__price">
                    <span class="plan-card__currency">₹</span>{{ number_format((float) $plan->price, 0) }}
                </div>
                <div class="plan-card__duration">
                    {{ $isTrial ? '30-day free access' : $plan->duration_days.' days' }}
                </div>
            </div>

            <div class="plan-card__divider"></div>

            {{-- Features --}}
            <div class="plan-card__features">
                <div class="plan-card__features-heading">
                    <span>Included features</span>
                    <span class="plan-card__feature-count">{{ $featureCount }}</span>
                </div>

                @if($featureCount > 0)
                    <ul class="plan-card__feature-list">
                        @foreach(array_slice($features, 0, 5) as $feature)
                            <li>
                                <span class="plan-card__check" aria-hidden="true">
                                    <svg viewBox="0 0 20 20">
                                        <path d="m4 10 4 4 8-8"/>
                                    </svg>
                                </span>
                                <span>{{ ucwords(str_replace('_', ' ', $feature)) }}</span>
                            </li>
                        @endforeach
                    </ul>

                    @if($featureCount > 5)
                        <div class="plan-card__more">
                            +{{ $featureCount - 5 }} more features
                        </div>
                    @endif
                @else
                    <div class="plan-card__empty">No features configured yet.</div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="plan-card__footer">
                <a href="{{ route('super-admin.subscription-plans.edit', $plan) }}"
                   class="plan-card__cta">
                    <span>Edit plan</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </article>
    @endforeach
</div>

{{-- Note --}}
<div class="plan-note">
    <div class="plan-note__icon">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
            <path d="m9 12 2 2 4-4"/>
        </svg>
    </div>
    <div>
        <strong>Pricing and subscription note</strong>
        <p>
            Updating a plan changes its configured price. Existing subscriptions
            retain their separately recorded agreed price unless you explicitly
            update those subscriptions.
        </p>
    </div>
</div>

<style>
    .plans-welcome {
        margin-bottom: 26px;
    }

    .plans-alert {
        padding: 14px 18px;
        margin-bottom: 20px;
        border: 1px solid;
        border-radius: 10px;
        font-size: 13px;
    }

    .plans-alert--success {
        color: #246b45;
        background: #edf8f0;
        border-color: #d3e9d9;
    }

    .plans-alert--error {
        color: #a83f36;
        background: #fff2ef;
        border-color: #f2d8d3;
    }

    .plans-kpis {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .plans-manual {
        font-size: 27px;
    }

    .plans-section-heading {
        margin-top: 0;
        margin-bottom: 18px;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        align-items: stretch;
        gap: 18px;
        width: 100%;
    }

    .plan-card {
        --plan-accent: #66747b;
        --plan-soft: #edf0f2;
        --plan-border: #e0e5e8;
        --plan-surface: #fff;

        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 410px;
        overflow: hidden;
        border: 1px solid var(--plan-border);
        border-radius: 15px;
        background: var(--plan-surface);
        box-shadow: 0 3px 12px rgba(23, 43, 39, .035);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .plan-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto;
        height: 4px;
        background: var(--plan-accent);
    }

    .plan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(23, 43, 39, .09);
    }

    /* Themes */
    .plan-card--trial {
        --plan-accent: #2869b5;
        --plan-soft: #e7f1ff;
        --plan-border: #d6e5f7;
    }

    .plan-card--silver {
        --plan-accent: #697781;
        --plan-soft: #edf1f3;
        --plan-border: #dfe5e8;
    }

    .plan-card--gold {
        --plan-accent: #a96e12;
        --plan-soft: #fff1cf;
        --plan-border: #ead7b0;
        --plan-surface: #fffefa;
    }

    .plan-card--platinum {
        --plan-accent: #247452;
        --plan-soft: #e2f3e9;
        --plan-border: #cfe5d7;
    }

    /* Header */
    .plan-card__header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        padding: 23px 18px 15px;
    }

    .plan-card__identity {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        min-width: 0;
    }

    .plan-card__symbol {
        display: grid;
        place-items: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 11px;
        background: var(--plan-soft);
        color: var(--plan-accent);
    }

    .plan-card__symbol svg {
        width: 22px;
        height: 22px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.7;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .plan-card__identity-text {
        min-width: 0;
    }

    .plan-card__name {
        margin: 1px 0 5px;
        color: #20332d;
        font-family: Manrope, sans-serif;
        font-size: 18px;
        font-weight: 800;
        line-height: 1.25;
        overflow-wrap: anywhere;
    }

    .plan-card__description {
        min-height: 36px;
        margin: 0;
        color: #77847e;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* Badges */
    .plan-badge {
        flex: 0 0 auto;
        padding: 5px 8px;
        border: 1px solid;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
        line-height: 1.2;
    }

    .plan-badge--active {
        border-color: #c8e7d2;
        background: #eff9f2;
        color: #27764b;
    }

    .plan-badge--trial {
        border-color: #cfe1fb;
        background: #eff6ff;
        color: #2869b5;
    }

    .plan-badge--inactive {
        border-color: #e1e5e8;
        background: #f3f5f6;
        color: #65717a;
    }

    /* Pricing */
    .plan-card__pricing {
        padding: 4px 18px 17px;
    }

    .plan-card__price {
        color: #1d312b;
        font-family: Manrope, sans-serif;
        font-size: clamp(25px, 2vw, 32px);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -.8px;
        overflow-wrap: anywhere;
    }

    .plan-card--gold .plan-card__price {
        color: #965d0e;
    }

    .plan-card__currency {
        margin-right: 2px;
        font-size: .76em;
        font-weight: 700;
    }

    .plan-card__duration {
        margin-top: 5px;
        color: #7b8781;
        font-size: 12px;
    }

    .plan-card__divider {
        height: 1px;
        margin: 0 18px;
        background: var(--plan-border);
    }

    /* Features */
    .plan-card__features {
        flex: 1;
        padding: 16px 18px 10px;
    }

    .plan-card__features-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 14px;
        color: #84918a;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
    }

    .plan-card__feature-count {
        display: inline-grid;
        place-items: center;
        min-width: 22px;
        height: 22px;
        padding: 0 5px;
        border-radius: 7px;
        background: var(--plan-soft);
        color: var(--plan-accent);
        font-size: 11px;
        letter-spacing: 0;
    }

    .plan-card__feature-list {
        display: grid;
        gap: 11px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .plan-card__feature-list li {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        min-width: 0;
        color: #52645c;
        font-size: 12px;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .plan-card__check {
        display: grid;
        place-items: center;
        flex: 0 0 17px;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        background: var(--plan-soft);
        color: var(--plan-accent);
    }

    .plan-card__check svg {
        width: 11px;
        height: 11px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2.3;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .plan-card__more {
        display: inline-block;
        margin-top: 13px;
        padding: 5px 9px;
        border-radius: 7px;
        background: var(--plan-soft);
        color: var(--plan-accent);
        font-size: 11px;
        font-weight: 700;
    }

    .plan-card__empty {
        color: #8b9690;
        font-size: 12px;
    }

    /* Footer */
    .plan-card__footer {
        margin-top: auto;
        padding: 15px 18px 18px;
    }

    .plan-card__cta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid var(--plan-accent);
        border-radius: 9px;
        background: var(--plan-accent);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        transition: filter .15s ease, transform .15s ease;
    }

    .plan-card--trial .plan-card__cta,
    .plan-card--silver .plan-card__cta {
        background: #fff;
        color: var(--plan-accent);
    }

    .plan-card__cta:hover {
        filter: brightness(.94);
        color: inherit;
        transform: translateY(-1px);
    }

    .plan-card--gold .plan-card__cta:hover,
    .plan-card--platinum .plan-card__cta:hover {
        color: #fff;
    }

    .plan-card__cta svg {
        flex: 0 0 16px;
        width: 16px;
        height: 16px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    /* Note */
    .plan-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 22px;
        padding: 16px 18px;
        border: 1px solid #d5e8db;
        border-radius: 12px;
        background: #f4faf5;
    }

    .plan-note__icon {
        display: grid;
        place-items: center;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #e2f3e7;
        color: #28764b;
    }

    .plan-note__icon svg {
        width: 19px;
        height: 19px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .plan-note strong {
        color: #264d37;
        font-size: 12px;
    }

    .plan-note p {
        margin: 4px 0 0;
        color: #62766a;
        font-size: 12px;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 1250px) {
        .plans-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .plans-kpis {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .plan-card {
            min-height: 390px;
        }
    }

    @media (max-width: 600px) {
        .plans-grid,
        .plans-kpis {
            grid-template-columns: minmax(0, 1fr);
        }

        .plan-card {
            min-height: 0;
        }

        .plan-card__description {
            min-height: 0;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .plan-card,
        .plan-card__cta {
            transition: none;
        }
    }
</style>

@endsection