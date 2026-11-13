@extends('super-admin.layouts.app')

@section('title', 'Platform Staff')
@section('breadcrumb', 'Administration / Platform Staff')
@section('page-title', 'Platform Staff Management')

@section('content')

<div class="welcome">
    <div>
        <div class="eyebrow">Administration</div>
        <h2>Platform Staff Management</h2>
        <p>Manage Super Admin team members and their platform access.</p>
    </div>

    <a href="{{ route('super-admin.users.create') }}" class="welcome-action">
        + Add Platform Staff
    </a>
</div>

@if(session('success'))
    <div class="staff-alert staff-alert--success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="staff-alert staff-alert--error">
        {{ session('error') }}
    </div>
@endif

{{-- Stats --}}
<div class="kpis" style="margin-bottom:28px;">
    <div class="kpi blue">
        <div class="kpi-top">
            <span class="kpi-label">Total platform staff</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <circle cx="9" cy="8" r="3.5"/>
                    <path d="M2.5 20c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6"/>
                    <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18.5 14.5c2 .8 3 2.6 3 5.5"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $totalUsers }}</div>
        <div class="kpi-foot"><span class="dot"></span> All registered staff</div>
    </div>

    <div class="kpi green">
        <div class="kpi-top">
            <span class="kpi-label">Active staff</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $activeUsers }}</div>
        <div class="kpi-foot"><span class="dot"></span> Currently active accounts</div>
    </div>

    <div class="kpi red">
        <div class="kpi-top">
            <span class="kpi-label">Inactive staff</span>
            <span class="kpi-icon">
                <svg class="i" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M10 9v6M14 9v6"/>
                </svg>
            </span>
        </div>
        <div class="kpi-value">{{ $inactiveUsers }}</div>
        <div class="kpi-foot"><span class="dot"></span> Disabled accounts</div>
    </div>
</div>

{{-- Directory --}}
<div class="section-heading">
    <div>
        <h3>Staff directory</h3>
        <p>Search, filter and manage platform staff accounts.</p>
    </div>
    <span class="count-chip">{{ $users->total() }} records</span>
</div>

<div class="panel">
    <div class="toolbar">
        <form method="GET"
              action="{{ route('super-admin.users.index') }}"
              class="staff-filters">

            <label class="search staff-filters__search">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m20 20-3.5-3.5"/>
                </svg>
                <input type="search"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search name, login ID or email..."
                       aria-label="Search staff">
            </label>

            <select name="super_admin_role_id" class="filter-select" aria-label="Filter by role">
                <option value="">All platform roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        @selected(request('super_admin_role_id') == $role->id)>
                        {{ $role->display_name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="filter-select" aria-label="Filter by status">
                <option value="">All statuses</option>
                <option value="Active" @selected(request('status') === 'Active')>Active</option>
                <option value="Inactive" @selected(request('status') === 'Inactive')>Inactive</option>
            </select>

            <div class="staff-filters__actions">
                <button type="submit" class="welcome-action" style="border:0;cursor:pointer;">
                    Apply filters
                </button>
                <a href="{{ route('super-admin.users.index') }}" class="chip">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table class="staff-table">
            <thead>
                <tr>
                    <th>Staff member</th>
                    <th>Login ID</th>
                    <th>Platform role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td data-label="Staff member">
                            <div class="staff-cell">
                                <div class="staff-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="staff-meta">
                                    <div class="staff-name">{{ $user->name }}</div>
                                    <div class="staff-email">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <td data-label="Login ID">
                            <span class="login-chip">{{ $user->login_id }}</span>
                        </td>

                        <td data-label="Platform role">
                            <span class="badge other">
                                {{ $user->superAdminRole?->display_name ?? 'Unassigned' }}
                            </span>
                        </td>

                        <td data-label="Status">
                            @if($user->status === 'Active')
                                <span class="badge active">Active</span>
                            @else
                                <span class="badge inactive">Inactive</span>
                            @endif
                        </td>

                        <td data-label="Created" class="cell-date">
                            {{ $user->created_at?->format('d M Y') ?? '—' }}
                        </td>

                        <td data-label="Actions">
                            <div class="staff-actions">
                                <a href="{{ route('super-admin.users.edit', $user->id) }}"
                                   class="staff-btn staff-btn--edit">
                                    Edit
                                </a>

                                <form action="{{ route('super-admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this platform staff account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="staff-btn staff-btn--delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6">
                            <div class="empty">
                                <div class="empty-icon">
                                    <svg class="i" viewBox="0 0 24 24">
                                        <circle cx="9" cy="8" r="3.5"/>
                                        <path d="M2.5 20c0-3.5 3-6 6.5-6s6.5 2.5 6.5 6"/>
                                        <path d="M16 4.5a3.5 3.5 0 0 1 0 7M18.5 14.5c2 .8 3 2.6 3 5.5"/>
                                    </svg>
                                </div>
                                <h4>No platform staff found</h4>
                                <p>Start by adding your first platform staff member.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="panel-foot">
        <span>
            Showing <strong>{{ $users->firstItem() ?? 0 }}</strong>
            to <strong>{{ $users->lastItem() ?? 0 }}</strong>
            of <strong>{{ $users->total() }}</strong> staff
        </span>
        <span>
            {{ $users->links('pagination::simple-tailwind') }}
        </span>
    </div>
</div>

<style>
    .staff-alert {
        padding: 14px 18px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13.5px;
        line-height: 1.5;
    }

    .staff-alert--success {
        border: 1px solid #d3e9d9;
        background: #edf8f0;
        color: #2d8050;
    }

    .staff-alert--error {
        border: 1px solid #f2d8d3;
        background: #fff2ef;
        color: #b54f43;
    }

    .staff-filters {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        width: 100%;
    }

    .staff-filters__search {
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
        min-width: 150px;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, .18);
    }

    .staff-filters__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
    }

    .staff-table th {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: uppercase;
        color: #6b7280;
        white-space: nowrap;
    }

    .staff-table td {
        vertical-align: middle;
        font-size: 13.5px;
        color: #374151;
    }

    .staff-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .staff-avatar {
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
    }

    .staff-meta { min-width: 0; }

    .staff-name {
        font-weight: 700;
        color: #111827;
        line-height: 1.3;
    }

    .staff-email {
        margin-top: 2px;
        font-size: 12px;
        color: #9ca3af;
    }

    .login-chip {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f0f4f8;
        color: #526d8b;
        font-size: 11px;
        font-weight: 800;
        font-family: ui-monospace, monospace;
    }

    .cell-date {
        white-space: nowrap;
        color: #6b7280;
        font-variant-numeric: tabular-nums;
    }

    .staff-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .staff-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        border: 0;
        cursor: pointer;
        transition: background .15s ease;
    }

    .staff-btn--edit {
        background: #edf3f8;
        color: #426b8b;
    }

    .staff-btn--edit:hover {
        background: #dbe7f0;
    }

    .staff-btn--delete {
        background: #fff0ed;
        color: #c34e3e;
    }

    .staff-btn--delete:hover {
        background: #fde0db;
    }

    .empty {
        padding: 48px 24px;
        text-align: center;
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
        .staff-filters__actions {
            margin-left: 0;
            width: 100%;
        }

        .staff-filters__actions .welcome-action,
        .staff-filters__actions .chip {
            flex: 1;
            justify-content: center;
            text-align: center;
        }
    }

    @media (max-width: 640px) {
        .filter-select {
            flex: 1 1 140px;
        }
    }
</style>

@endsection