@extends('layouts.app')

@section('content')

<style>
    .account-page {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-bottom: 22px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
        color: #172033;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .header-actions {
        display: flex;
        gap: 8px;
    }

    .btn {
        height: 39px;
        padding: 0 16px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-edit {
        background: #2563eb;
        color: #fff;
    }

    .btn-edit:hover {
        background: #1d4ed8;
        color: #fff;
    }

    .btn-back {
        background: #fff;
        border: 1px solid #dfe4ec;
        color: #475569;
    }

    .account-card {
        max-width: 950px;
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        overflow: hidden;
        margin-bottom: 18px;
    }

    .profile-header {
        padding: 25px 24px;
        background: linear-gradient(135deg, #eff6ff, #ffffff);
        border-bottom: 1px solid #edf0f5;
        display: flex;
        align-items: center;
        gap: 17px;
    }

    .avatar {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #2563eb;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .profile-info h3 {
        margin: 0 0 5px;
        font-size: 21px;
        color: #172033;
    }

    .profile-info p {
        margin: 0;
        font-size: 12px;
        color: #7b8497;
    }

    .status-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-active {
        background: #ecfdf3;
        color: #15803d;
    }

    .status-inactive {
        background: #fef2f2;
        color: #dc2626;
    }

    .card-title {
        padding: 16px 20px;
        border-bottom: 1px solid #edf0f5;
        font-size: 16px;
        font-weight: 700;
        color: #172033;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }

    .detail-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f2f6;
    }

    .detail-item:nth-child(odd) {
        border-right: 1px solid #f0f2f6;
    }

    .detail-label {
        display: block;
        color: #8a94a6;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: .25px;
    }

    .detail-value {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }

    .login-id {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 5px;
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .3px;
    }

    .role-badge {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        background: #eef4ff;
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
    }

    .security-note {
        margin: 18px 20px 20px;
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid #e8edf4;
        border-radius: 8px;
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .security-note strong {
        color: #334155;
    }

    @media (max-width: 700px) {
        .account-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .detail-item:nth-child(odd) {
            border-right: none;
        }

        .profile-header {
            padding: 20px;
        }
    }
</style>

<div class="account-page">

    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h2>View Account</h2>
            <p>View account information and assigned access role.</p>
        </div>

        <div class="header-actions">

            <a href="{{ route('admin.settings.users.index') }}"
               class="btn btn-back">
                ← Back
            </a>

            <a href="{{ route('admin.settings.users.edit', $user) }}"
               class="btn btn-edit">
                Edit Account
            </a>

        </div>

    </div>


    {{-- Profile Card --}}
    <div class="account-card">

        <div class="profile-header">

            <div class="avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="profile-info">

                <h3>{{ $user->name }}</h3>

                <p>
                    {{ $user->email }}
                </p>

                @if($user->status === 'Active')

                    <span class="status-badge status-active">
                        Active Account
                    </span>

                @else

                    <span class="status-badge status-inactive">
                        Inactive Account
                    </span>

                @endif

            </div>

        </div>


        {{-- Account Details --}}
        <div class="card-title">
            Account Information
        </div>

        <div class="details-grid">

            <div class="detail-item">

                <span class="detail-label">
                    Full Name
                </span>

                <div class="detail-value">
                    {{ $user->name }}
                </div>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <div class="detail-value">
                    {{ $user->email }}
                </div>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Login ID
                </span>

                <div class="detail-value">

                    <span class="login-id">
                        {{ $user->login_id }}
                    </span>

                </div>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Assigned Role
                </span>

                <div class="detail-value">

                    @if($user->role)

                        <span class="role-badge">
                            {{ $user->role->display_name }}
                        </span>

                    @else

                        No Role Assigned

                    @endif

                </div>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Account Status
                </span>

                <div class="detail-value">

                    @if($user->status === 'Active')

                        <span class="status-badge status-active">
                            Active
                        </span>

                    @else

                        <span class="status-badge status-inactive">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>


            <div class="detail-item">

                <span class="detail-label">
                    Account Created
                </span>

                <div class="detail-value">
                    {{ $user->created_at?->format('d M Y, h:i A') }}
                </div>

            </div>

        </div>


        {{-- Security --}}
        <div class="card-title">
            Login Security
        </div>

        <div class="security-note">

            <strong>Password:</strong>
            For security reasons, the account password is never displayed here.

            To change the password, use the
            <strong>Edit Account</strong> option.

        </div>

    </div>


    {{-- Role Information --}}
    @if($user->role)

        <div class="account-card">

            <div class="card-title">
                Assigned Role
            </div>

            <div class="details-grid">

                <div class="detail-item">

                    <span class="detail-label">
                        Role
                    </span>

                    <div class="detail-value">
                        {{ $user->role->display_name }}
                    </div>

                </div>

                <div class="detail-item">

                    <span class="detail-label">
                        Role Status
                    </span>

                    <div class="detail-value">
                        {{ $user->role->status }}
                    </div>

                </div>

                <div class="detail-item"
                     style="grid-column: 1 / -1;">

                    <span class="detail-label">
                        Description
                    </span>

                    <div class="detail-value">
                        {{ $user->role->description ?? 'No description available.' }}
                    </div>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection