
@extends('super-admin.layouts.app')

@section('title', 'School Profile')
@section('breadcrumb', 'School Management / School Profile')
@section('page-title', 'School Profile')

@section('content')

<style>
    .profile-page { width: 100%; }

    .profile-header {
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:15px;
        margin-bottom:24px;
    }

    .profile-header h2 {
        margin:0;
        font:800 25px Manrope,sans-serif;
        color:var(--ink);
    }

    .profile-header p {
        margin:6px 0 0;
        color:var(--muted);
        font-size:13px;
    }

    .profile-actions { display:flex; gap:10px; flex-wrap:wrap; }

    .profile-btn {
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 15px;
        border-radius:9px;
        font-size:13px;
        font-weight:700;
        text-decoration:none;
        border:1px solid var(--line);
    }

    .profile-btn.back { background:#fff; color:var(--ink); }
    .profile-btn.edit { background:var(--navy); color:#fff; }

    .profile-card {
        background:#fff;
        border:1px solid var(--line);
        border-radius:14px;
        box-shadow:var(--shadow);
        margin-bottom:22px;
        overflow:hidden;
    }

    .profile-card-header {
        padding:19px 22px;
        border-bottom:1px solid var(--line);
        display:flex;
        align-items:center;
        gap:12px;
    }

    .profile-card-header h3 {
        margin:0;
        font:800 15px Manrope,sans-serif;
        color:var(--ink);
    }

    .profile-card-body { padding:23px; }

    .school-identity {
        display:flex;
        align-items:center;
        gap:17px;
    }

    .school-logo {
        width:65px;
        height:65px;
        border-radius:14px;
        object-fit:cover;
        background:var(--green-soft);
    }

    .school-initial {
        width:65px;
        height:65px;
        border-radius:14px;
        display:grid;
        place-items:center;
        background:var(--green-soft);
        color:var(--green);
        font:800 25px Manrope,sans-serif;
    }

    .school-name {
        font:800 19px Manrope,sans-serif;
        color:var(--ink);
    }

    .school-code {
        margin-top:5px;
        color:var(--muted);
        font-size:12px;
    }

    .detail-grid {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:23px 30px;
    }

    .detail-label {
        color:var(--muted);
        font-size:10px;
        font-weight:800;
        letter-spacing:.8px;
        text-transform:uppercase;
        margin-bottom:7px;
    }

    .detail-value {
        color:var(--ink);
        font-size:13px;
        font-weight:700;
        overflow-wrap:anywhere;
    }

    .status-pill {
        display:inline-block;
        padding:6px 11px;
        border-radius:20px;
        font-size:11px;
        font-weight:800;
    }

    .status-active { background:#edf8f0; color:#2d8050; }
    .status-inactive { background:#fff0ed; color:#bd5149; }
    .status-pending { background:#fff5df; color:#9a7428; }
    .status-expired { background:#f0f1f2; color:#626b70; }

    .subscription-item {
        border:1px solid var(--line);
        border-radius:11px;
        padding:17px;
        margin-bottom:14px;
    }

    .subscription-top {
        display:flex;
        justify-content:space-between;
        align-items:center;
        flex-wrap:wrap;
        gap:10px;
        margin-bottom:17px;
    }

    .subscription-top strong {
        font:800 14px Manrope,sans-serif;
        color:var(--ink);
    }

    .payment-table {
        width:100%;
        border-collapse:collapse;
    }

    .payment-table th,
    .payment-table td {
        padding:11px;
        border-bottom:1px solid var(--line);
        text-align:left;
        font-size:12px;
    }

    .payment-table th {
        color:var(--muted);
        background:#f8faf8;
        font-size:10px;
        text-transform:uppercase;
    }

    .empty-message {
        color:var(--muted);
        font-size:13px;
        padding:10px 0;
    }

    @media(max-width:650px) {
        .detail-grid { grid-template-columns:1fr; gap:18px; }
        .profile-card-body { padding:17px; }
        .payment-table { min-width:600px; }
        .payment-scroll { overflow-x:auto; }
    }
</style>

@php
    $administrator = $school->administrator;
    $subscriptions = $school->subscriptions->sortByDesc('created_at');
@endphp

<div class="profile-page">

    <div class="profile-header">
        <div>
            <div class="eyebrow">SCHOOL MANAGEMENT</div>
            <h2>School Profile</h2>
            <p>Complete registration and subscription information.</p>
        </div>

        <div class="profile-actions">
            <a href="{{ route('super-admin.schools.index') }}"
               class="profile-btn back">
                <i class="fas fa-arrow-left"></i> Back
            </a>

            <a href="{{ route('super-admin.schools.edit', $school->id) }}"
               class="profile-btn edit">
                <i class="fas fa-pen"></i> Edit School
            </a>
        </div>
    </div>

    {{-- SCHOOL IDENTITY --}}
    <div class="profile-card">
        <div class="profile-card-body">
            <div class="school-identity">

                @if($school->logo_path)
                    <img class="school-logo"
                         src="{{ asset('storage/'.$school->logo_path) }}"
                         alt="School logo">
                @else
                    <div class="school-initial">
                        {{ strtoupper(substr($school->name,0,1)) }}
                    </div>
                @endif

                <div>
                    <div class="school-name">{{ $school->name }}</div>
                    <div class="school-code">
                        School Code: {{ $school->school_code ?? 'N/A' }}
                        &nbsp; | &nbsp; School ID: #{{ $school->id }}
                    </div>
                    <div style="margin-top:8px;">
                        <span class="status-pill {{ strtolower($school->status ?? '') === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($school->status ?? 'Unknown') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BASIC INFORMATION --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-school" style="color:var(--green)"></i>
            <h3>Basic School Information</h3>
        </div>

        <div class="profile-card-body">
            <div class="detail-grid">

                <div><div class="detail-label">School Name</div><div class="detail-value">{{ $school->name }}</div></div>
                <div><div class="detail-label">School Type</div><div class="detail-value">{{ $school->school_type ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Education Board</div><div class="detail-value">{{ $school->education_board ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Registration Number</div><div class="detail-value">{{ $school->registration_number ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Establishment Year</div><div class="detail-value">{{ $school->establishment_year ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Principal Name</div><div class="detail-value">{{ $school->principal_name ?? 'N/A' }}</div></div>

            </div>
        </div>
    </div>

    {{-- CONTACT INFORMATION --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-address-book" style="color:var(--green)"></i>
            <h3>Contact Information</h3>
        </div>

        <div class="profile-card-body">
            <div class="detail-grid">

                <div><div class="detail-label">School Email</div><div class="detail-value">{{ $school->email ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Phone Number</div><div class="detail-value">{{ $school->phone ?? 'N/A' }}</div></div>

                <div>
                    <div class="detail-label">Website</div>
                    <div class="detail-value">
                        @if($school->website)
                            <a href="{{ $school->website }}" target="_blank" rel="noopener noreferrer">
                                {{ $school->website }}
                            </a>
                        @else
                            N/A
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ADDRESS --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-location-dot" style="color:var(--green)"></i>
            <h3>Address Information</h3>
        </div>

        <div class="profile-card-body">
            <div class="detail-grid">

                <div><div class="detail-label">Address</div><div class="detail-value">{{ $school->address_line ?? 'N/A' }}</div></div>
                <div><div class="detail-label">City</div><div class="detail-value">{{ $school->city ?? 'N/A' }}</div></div>
                <div><div class="detail-label">District</div><div class="detail-value">{{ $school->district ?? 'N/A' }}</div></div>
                <div><div class="detail-label">State</div><div class="detail-value">{{ $school->state ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Postal Code</div><div class="detail-value">{{ $school->postal_code ?? 'N/A' }}</div></div>
                <div><div class="detail-label">Country</div><div class="detail-value">{{ $school->country ?? 'N/A' }}</div></div>

            </div>
        </div>
    </div>

    {{-- ADMINISTRATOR --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-user-shield" style="color:var(--green)"></i>
            <h3>School Administrator</h3>
        </div>

        <div class="profile-card-body">

            @if($administrator)

                <div class="detail-grid">
                    <div><div class="detail-label">Administrator Name</div><div class="detail-value">{{ $administrator->name }}</div></div>
                    <div><div class="detail-label">Email</div><div class="detail-value">{{ $administrator->email }}</div></div>
                    <div><div class="detail-label">Login ID</div><div class="detail-value">{{ $administrator->login_id ?? 'N/A' }}</div></div>
                    <div><div class="detail-label">Phone</div><div class="detail-value">{{ $administrator->phone ?? 'N/A' }}</div></div>
                    <div><div class="detail-label">Account Status</div><div class="detail-value">{{ $administrator->status ?? 'N/A' }}</div></div>
                </div>

            @else
                <p class="empty-message">No school administrator account was found.</p>
            @endif

        </div>
    </div>

    {{-- SUBSCRIPTION HISTORY --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-credit-card" style="color:var(--green)"></i>
            <h3>Subscription History</h3>
        </div>

        <div class="profile-card-body">

            @forelse($subscriptions as $subscription)

                <div class="subscription-item">

                    <div class="subscription-top">
                        <strong>
                            {{ $subscription->plan?->name ?? 'Unknown Plan' }}
                            ({{ ucfirst($subscription->type) }})
                        </strong>

                        <span class="status-pill
                            {{ $subscription->status === 'active' ? 'status-active' :
                               ($subscription->status === 'pending' ? 'status-pending' :
                               ($subscription->status === 'expired' ? 'status-expired' : 'status-inactive')) }}">
                            {{ ucfirst($subscription->status) }}
                        </span>
                    </div>

                    <div class="detail-grid">
                        <div>
                            <div class="detail-label">Agreed Price</div>
                            <div class="detail-value">
                                ₹{{ number_format((float)$subscription->agreed_price, 2) }}
                            </div>
                        </div>

                        <div>
                            <div class="detail-label">Duration</div>
                            <div class="detail-value">
                                {{ $subscription->plan?->duration_days ?? 'N/A' }} days
                            </div>
                        </div>

                        <div>
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value">
                                {{ $subscription->starts_at?->format('d M Y') ?? 'N/A' }}
                            </div>
                        </div>

                        <div>
                            <div class="detail-label">Expiry Date</div>
                            <div class="detail-value">
                                {{ $subscription->ends_at?->format('d M Y') ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($subscription->payments->isNotEmpty())
                        <div style="margin-top:20px;">
                            <div class="detail-label" style="margin-bottom:10px;">
                                Payment History
                            </div>

                            <div class="payment-scroll">
                                <table class="payment-table">
                                    <thead>
                                        <tr>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Reference</th>
                                            <th>Paid On</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subscription->payments as $payment)
                                            <tr>
                                                <td>₹{{ number_format((float)$payment->amount,2) }}</td>
                                                <td>{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</td>
                                                <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                                <td>{{ $payment->paid_at?->format('d M Y') ?? 'N/A' }}</td>
                                                <td>{{ ucfirst($payment->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <p class="empty-message" style="margin-top:15px;">
                            No payment records for this subscription.
                        </p>
                    @endif

                </div>

            @empty

                <p class="empty-message">
                    No subscription records found for this school.
                </p>

            @endforelse

        </div>
    </div>

    {{-- REGISTRATION TIMESTAMPS --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <i class="fas fa-calendar-check" style="color:var(--green)"></i>
            <h3>Registration Information</h3>
        </div>

        <div class="profile-card-body">
            <div class="detail-grid">
                <div>
                    <div class="detail-label">Registered On</div>
                    <div class="detail-value">{{ $school->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                </div>

                <div>
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ $school->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
