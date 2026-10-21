@extends('layouts.app')

@section('content')

<style>
    .users-page {
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

    .btn-add {
        background: #2563eb;
        color: #fff;
        border: none;
        padding: 10px 17px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-add:hover {
        background: #1d4ed8;
        color: #fff;
    }

    /* Stats */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 17px;
        display: flex;
        align-items: center;
        gap: 13px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .06);
        border: 1px solid #eef1f6;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        flex-shrink: 0;
    }

    .stat-icon svg {
        width: 23px;
        height: 23px;
    }

    .stat-info span {
        display: block;
        color: #7b8497;
        font-size: 12px;
        margin-bottom: 3px;
    }

    .stat-info strong {
        display: block;
        color: #172033;
        font-size: 22px;
        line-height: 1;
    }

    /* Filters */

    .filter-card {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .04);
    }

    .filter-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 10px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 39px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        padding: 0 11px;
        font-size: 13px;
        color: #334155;
        outline: none;
        background: #fff;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .btn-filter {
        height: 39px;
        border: none;
        border-radius: 7px;
        padding: 0 17px;
        background: #172033;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 39px;
        padding: 0 14px;
        margin-left: 5px;
        border-radius: 7px;
        border: 1px solid #dfe4ec;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        background: #fff;
    }

    /* Table */

    .table-card {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        overflow: hidden;
    }

    .table-header {
        padding: 17px 19px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .table-header h5 {
        margin: 0;
        color: #172033;
        font-size: 17px;
        font-weight: 700;
    }

    .table-header span {
        font-size: 12px;
        color: #8a94a6;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .users-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: 12px 15px;
        text-align: left;
        white-space: nowrap;
    }

    .users-table td {
        padding: 13px 15px;
        border-top: 1px solid #f0f2f6;
        color: #475569;
        font-size: 13px;
        vertical-align: middle;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #2563eb;
    font-size: 14px;
    font-weight: 700;
}

.user-avatar-img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    border-radius: 50%;
}
    .user-name {
        color: #172033;
        font-weight: 600;
        font-size: 13px;
    }

    .user-email {
        color: #8a94a6;
        font-size: 11px;
        margin-top: 2px;
    }

    .login-id {
        font-weight: 600;
        color: #334155;
        background: #f8fafc;
        padding: 5px 8px;
        border-radius: 5px;
        font-size: 12px;
        display: inline-block;
    }

    .role-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        background: #eef4ff;
        color: #2563eb;
        font-size: 11px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 9px;
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

    .actions {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 31px;
        height: 31px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .action-btn svg {
        width: 15px;
        height: 15px;
    }

    .view-btn {
        background: #eff6ff;
        color: #2563eb;
    }

    .edit-btn {
        background: #f8fafc;
        color: #475569;
    }

    .delete-btn {
        background: #fef2f2;
        color: #dc2626;
    }

    .pagination-wrap {
        padding: 15px 18px;
        border-top: 1px solid #edf0f5;
    }

    .empty-state {
        text-align: center;
        padding: 45px 20px;
        color: #8a94a6;
        font-size: 13px;
    }

    .alert {
        border-radius: 8px;
        padding: 11px 14px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .alert-success {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .alert-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    @media (max-width: 900px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .page-header {
            align-items: flex-start;
        }
    }

    @media (max-width: 600px) {
        .users-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
        }

        .btn-add {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="users-page">

    {{-- Page Header --}}
    <div class="page-header">
        <div>
            <h2>User Management</h2>
            <p>Manage user accounts, login credentials, roles and access status.</p>
        </div>

        <a href="{{ route('admin.settings.users.create') }}" class="btn-add">
            + Add User
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div class="stat-info">
                <span>Total Users</span>
                <strong>{{ $totalUsers }}</strong>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>

            <div class="stat-info">
                <span>Active Users</span>
                <strong>{{ $activeUsers }}</strong>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M18 6L6 18"/>
                    <path d="M6 6l12 12"/>
                </svg>
            </div>

            <div class="stat-info">
                <span>Inactive Users</span>
                <strong>{{ $inactiveUsers }}</strong>
            </div>
        </div>

    </div>

    {{-- Filters --}}
    <div class="filter-card">

        <form method="GET"
              action="{{ route('admin.settings.users.index') }}"
              class="filter-form">

            <div class="filter-group">
                <label>Search</label>

                <input type="text"
                       name="search"
                       class="form-control"
                       value="{{ request('search') }}"
                       placeholder="Name, Login ID or Email">
            </div>

            <div class="filter-group">
                <label>Role</label>

                <select name="role_id" class="form-select">
                    <option value="">All Roles</option>

                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Status</label>

                <select name="status" class="form-select">
                    <option value="">All Status</option>

                    <option value="Active"
                        {{ request('status') === 'Active' ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="Inactive"
                        {{ request('status') === 'Inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn-filter">
                    Filter
                </button>

                <a href="{{ route('admin.settings.users.index') }}"
                   class="btn-reset">
                    Reset
                </a>
            </div>

        </form>

    </div>

    {{-- Users Table --}}
    <div class="table-card">

        <div class="table-header">
            <h5>All Users</h5>

            <span>
                {{ $users->total() }} user{{ $users->total() == 1 ? '' : 's' }}
            </span>
        </div>

        <div class="table-responsive">

            @if($users->count())

                <table class="users-table">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Login ID</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($users as $user)

                            <tr>

                                <td>
                                    <div class="user-info">

                                        <div class="user-avatar">
    @if($user->profile_photo)
        <img src="{{ $user->profile_photo }}"
             alt="{{ $user->name }}"
             class="user-avatar-img">
    @else
        <span>
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </span>
    @endif
</div>

                                        <div>
                                            <div class="user-name">
                                                {{ $user->name }}
                                            </div>

                                            <div class="user-email">
                                                {{ $user->email }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    <span class="login-id">
                                        {{ $user->login_id }}
                                    </span>
                                </td>

                                <td>
                                    @if($user->role)
                                        <span class="role-badge">
                                            {{ $user->role->display_name }}
                                        </span>
                                    @else
                                        <span style="color:#94a3b8;">
                                            No Role
                                        </span>
                                    @endif
                                </td>

                                <td>

                                    @if($user->status === 'Active')

                                        <span class="status-badge status-active">
                                            Active
                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $user->created_at?->format('d M Y') }}
                                </td>

                                <td>

                                    <div class="actions">

                                        {{-- View --}}
                                        <a href="{{ route('admin.settings.users.show', $user) }}"
                                           class="action-btn view-btn"
                                           title="View">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>

                                        </a>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.settings.users.edit', $user) }}"
                                           class="action-btn edit-btn"
                                           title="Edit">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>

                                        </a>

                                        {{-- Delete --}}
                                        @if($user->id !== auth()->id() && !$user->hasRole('super_admin'))

                                            <form action="{{ route('admin.settings.users.destroy', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="action-btn delete-btn"
                                                        title="Delete">

                                                    <svg viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6l-1 14H6L5 6"/>
                                                        <path d="M10 11v6"/>
                                                        <path d="M14 11v6"/>
                                                        <path d="M9 6V4h6v2"/>
                                                    </svg>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">
                    No users found.
                </div>

            @endif

        </div>

        @if($users->hasPages())
            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

@endsection