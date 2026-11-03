@extends('super-admin.layouts.app')

@section('title', 'Subscriptions')
@section('breadcrumb', 'Subscriptions')
@section('page-title', 'Subscription management')

@section('content')

<div class="welcome">
    <div>
        <div class="eyebrow">School Central / Billing</div>
        <h2>Subscription overview</h2>
        <p>Manage school trials, paid plans, subscription status and expiry dates.</p>
    </div>

    <a href="{{ route('super-admin.subscriptions.assign') }}" class="welcome-action">
        + Assign plan
    </a>
</div>

{{-- Statistics --}}
<div class="kpis">
    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">Total subscriptions</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <rect x="2" y="5" width="20" height="14" rx="2"/>
                    <path d="M2 10h20"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $totalSubscriptions }}</div>
        <div class="kpi-foot"><span class="dot"></span> All recorded subscriptions</div>
    </div>

    <div class="kpi green">
        <div class="kpi-top">
            <span class="kpi-label">Active subscriptions</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $activeSubscriptions }}</div>
        <div class="kpi-foot"><span class="dot"></span> Currently within the active period</div>
    </div>

    <div class="kpi amber">
        <div class="kpi-top">
            <span class="kpi-label">Active free trials</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M8 2v4M16 2v4M3 10h18"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $trialSubscriptions }}</div>
        <div class="kpi-foot"><span class="dot"></span> Trial subscriptions in progress</div>
    </div>

    <div class="kpi red">
        <div class="kpi-top">
            <span class="kpi-label">Expired subscriptions</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $expiredSubscriptions }}</div>
        <div class="kpi-foot"><span class="dot"></span> Past their expiry date</div>
    </div>
</div>

{{-- Subscription directory --}}
<div class="section-heading">
    <div>
        <h3>School subscriptions</h3>
        <p>Review each school's plan, subscription period, payment status and agreed price.</p>
    </div>
    <span class="count-chip">{{ $subscriptions->total() }} records</span>
</div>

<div class="panel subscriptions-panel">

    {{-- Search and filters --}}
    <div class="toolbar">
        <form method="GET"
              action="{{ route('super-admin.subscriptions.index') }}"
              class="subscriptions-filters">

            <label class="search subscriptions-filters__search">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>
                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search school name or code..."
                       aria-label="Search school name or code">
            </label>

            <select name="status" aria-label="Filter by status" class="filter-select">
                <option value="">All statuses</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="expired" @selected(request('status') === 'expired')>Expired</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>

            <select name="type" aria-label="Filter by subscription type" class="filter-select">
                <option value="">All types</option>
                <option value="trial" @selected(request('type') === 'trial')>Free trial</option>
                <option value="paid" @selected(request('type') === 'paid')>Paid</option>
            </select>

            <div class="subscriptions-filters__actions">
                <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                    Apply filters
                </button>
                <a href="{{ route('super-admin.subscriptions.index') }}" class="chip">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Results table --}}
    <div class="table-wrap">
        <table class="subscriptions-table">
            <thead>
                <tr>
                    <th>School</th>
                    <th>Plan</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Start date</th>
                    <th>Expiry date</th>
                    <th class="text-right">Agreed price</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($subscriptions as $subscription)
                    @php
                        $isExpired = $subscription->ends_at
                            && $subscription->ends_at->lte(now());

                        $status = $isExpired
                            ? 'expired'
                            : strtolower($subscription->status ?? 'pending');

                        $statusClass = match ($status) {
                            'active' => 'active',
                            'expired', 'cancelled' => 'inactive',
                            default => 'other',
                        };

                        $schoolName = $subscription->school?->name ?? 'School unavailable';

                        $payment = $subscription->payments
                            ->sortByDesc('created_at')
                            ->first();

                        $hasPayment = $payment !== null;
                    @endphp

                    <tr>
                        <td data-label="School" class="cell-school">
                            <div class="school-cell">
                                <div class="school-avatar">
                                    {{ strtoupper(substr($schoolName, 0, 1)) }}
                                </div>
                                <div class="school-meta">
                                    <div class="school-name">{{ $schoolName }}</div>
                                    <div class="school-sub">
                                        {{ $subscription->school?->school_code ?? 'No school code' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td data-label="Plan">
                            <span class="plan-name">
                                {{ $subscription->plan?->name ?? 'Plan unavailable' }}
                            </span>
                        </td>

                        <td data-label="Type">
                            @if ($subscription->type === 'trial')
                                <span class="badge other">Free trial</span>
                            @else
                                <span class="badge other">
                                    {{ ucfirst($subscription->type ?? 'unknown') }}
                                </span>
                            @endif
                        </td>

                        <td data-label="Status">
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>

                        <td data-label="Start date" class="cell-date">
                            {{ $subscription->starts_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td data-label="Expiry date" class="cell-date">
                            {{ $subscription->ends_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td data-label="Agreed price" class="cell-price text-right">
                            <strong>₹{{ number_format((float) $subscription->agreed_price, 2) }}</strong>
                        </td>

                        <td data-label="Actions" class="subscription-actions">
                            <div class="subscription-action-buttons">
                                @if (
                                    $subscription->type === 'paid'
                                    && $subscription->status === 'pending'
                                    && !$hasPayment
                                )
                                    <a href="{{ route('super-admin.subscriptions.payments.create', $subscription) }}"
                                       class="welcome-action payment-action">
                                        Record Payment
                                    </a>

                                @elseif ($hasPayment)
                                    <a href="{{ route('super-admin.subscriptions.show', $subscription) }}"
                                       class="subscription-btn subscription-btn--view">
                                        View
                                    </a>
                                    <a href="{{ route('super-admin.subscriptions.edit', $subscription) }}"
                                       class="subscription-btn subscription-btn--edit">
                                        Edit
                                    </a>

                                @elseif ($subscription->type === 'trial')
                                    <span class="action-note">Trial plan</span>

                                @elseif (
                                    $subscription->type === 'paid'
                                    && $subscription->status === 'active'
                                )
                                    <span class="action-note">
                                        <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="m5 12 4 4L19 6"/>
                                        </svg>
                                        Activated
                                    </span>

                                @else
                                    <span class="action-note">{{ ucfirst($status) }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg class="i" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                                        <path d="M8 2v4M16 2v4M3 10h18"/>
                                    </svg>
                                </div>
                                <h4>No subscriptions found</h4>
                                <p>Try changing your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel-foot">
        <span>
            Showing <strong>{{ $subscriptions->firstItem() ?? 0 }}</strong>
            to <strong>{{ $subscriptions->lastItem() ?? 0 }}</strong>
            of <strong>{{ $subscriptions->total() }}</strong> subscriptions
        </span>
        <span>
            {{ $subscriptions->links('pagination::simple-tailwind') }}
        </span>
    </div>
</div>

<style>
    .subscriptions-filters {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .subscriptions-filters__search {
        flex: 1 1 240px;
        min-width: 200px;
    }

    .filter-select {
        height: 40px;
        padding: 0 12px;
        border: 1px solid var(--line, #e5e7eb);
        border-radius: 9px;
        background: #fff;
        color: var(--ink, #111827);
        font-size: 13.5px;
        min-width: 140px;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.18);
    }

    .subscriptions-filters__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    .subscriptions-table th {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: #6b7280;
        white-space: nowrap;
    }

    .subscriptions-table td {
        vertical-align: middle;
        font-size: 13.5px;
        color: #374151;
    }

    .school-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .school-avatar {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e0f2fe, #dbeafe);
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        letter-spacing: -0.02em;
    }

    .school-meta {
        min-width: 0;
    }

    .school-name {
        font-weight: 700;
        color: #111827;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 220px;
    }

    .school-sub {
        margin-top: 2px;
        font-size: 12px;
        color: #9ca3af;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .plan-name {
        font-weight: 700;
        color: #111827;
    }

    .cell-date {
        white-space: nowrap;
        color: #6b7280;
        font-variant-numeric: tabular-nums;
    }

    .cell-price {
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .cell-price strong {
        color: #111827;
        font-weight: 700;
    }

    .text-right {
        text-align: right;
    }

    .subscription-actions {
        min-width: 145px;
    }

    .subscription-action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .subscription-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: background 0.2s ease;
    }

    .subscription-btn--view {
        background: var(--green-soft, #e8f5ee);
        color: var(--green, #1f7a4d);
    }

    .subscription-btn--view:hover {
        background: #d8ecdf;
    }

    .subscription-btn--edit {
        background: var(--canvas, #f5f7f5);
        color: var(--ink, #111827);
        border: 1px solid var(--line, #e5e7eb);
    }

    .subscription-btn--edit:hover {
        background: #e9eeeb;
    }

    .payment-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 12px;
        text-decoration: none;
        white-space: nowrap;
        font-size: 12px;
        border-radius: 8px;
    }

    .payment-action .i,
    .action-note .i {
        width: 15px;
        height: 15px;
        flex-shrink: 0;
    }

    .action-note {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #75827d;
        font-size: 12px;
        white-space: nowrap;
    }

    .empty {
        padding: 48px 24px;
    }

    .empty h4 {
        margin: 12px 0 6px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .empty p {
        margin: 0;
        color: #6b7280;
        font-size: 13.5px;
    }

    @media (max-width: 900px) {
        .subscriptions-filters__actions {
            margin-left: 0;
            width: 100%;
        }

        .subscriptions-filters__actions .welcome-action,
        .subscriptions-filters__actions .chip {
            flex: 1;
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 640px) {
        .filter-select {
            flex: 1 1 140px;
        }

        .school-name {
            max-width: 160px;
        }

        .payment-action {
            white-space: normal;
        }
    }
</style>

@endsection