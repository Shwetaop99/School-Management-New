@extends('layouts.app')

@section('title', 'User Management | Admin')
@section('page-title', 'User Management')

@section('content')

@php
    $usersCollection = $users ?? collect();

    $totalUsers = isset($users) && method_exists($users, 'total')
        ? $users->total()
        : ($totalUsers ?? $usersCollection->count());

    $activeUsers   = $activeUsers ?? $usersCollection->where('status', 'Active')->count();
    $inactiveUsers = $inactiveUsers ?? $usersCollection->where('status', 'Inactive')->count();

    $hasFilters = request()->hasAny(['search', 'role_id', 'status']);
@endphp

<div class="users-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="users-success-alert" role="status">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>
                <span>{{ session('success') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="users-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>{{ session('error') }}</span>
                <button type="button" class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- SCHOOL DIARY HERO --}}
        <section class="users-hero">
            <div class="users-hero-content">
                <div class="users-hero-icon">
                    <i class="fas fa-users-cog"></i>
                </div>

                <div class="users-hero-text">
                    <div class="users-breadcrumb">
                        SETTINGS <span>/</span> USERS
                    </div>

                    <h2>User Management</h2>

                    <p>
                        Manage user accounts, login credentials, roles and
                        access status from one central place.
                    </p>

                    <div class="users-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School User Access Control</span>
                    </div>
                </div>
            </div>

            <div class="users-hero-actions">
                <div class="hero-handwritten">Simple. Organised. Efficient.</div>

                <p>
                    Add new users and keep roles and
                    access status under control.
                </p>

                <a href="{{ route('admin.settings.users.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add User
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="users-kpis">

            <article class="users-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Users</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalUsers }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Registered accounts
                </div>
            </article>

            <article class="users-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Users</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $activeUsers }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-circle-check"></i>
                    Currently active accounts
                </div>
            </article>

            <article class="users-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Users</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-slash"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $inactiveUsers }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-ban"></i>
                    Currently inactive accounts
                </div>
            </article>

        </section>

        {{-- SEARCH AND FILTERS --}}
        <section class="users-filter-card">

            <div class="filter-heading">
                <div class="filter-heading-icon">
                    <i class="fas fa-filter"></i>
                </div>

                <div class="filter-heading-copy">
                    <div class="section-eyebrow">RECORD MANAGEMENT</div>
                    <h3>Search &amp; Filter Users</h3>
                    <p>Find users by name, login ID, email, role or status.</p>
                </div>

                @if($hasFilters)
                    <a href="{{ route('admin.settings.users.index') }}"
                       class="diary-reset-btn reset-heading-btn">
                        <i class="fas fa-rotate-left"></i>
                        Reset
                    </a>
                @endif
            </div>

            <form method="GET" action="{{ route('admin.settings.users.index') }}">

                <div class="users-filter-grid">

                    <div class="diary-filter-group search-group">
                        <label for="users-search">Search Records</label>

                        <div class="diary-search-input">
                            <i class="fas fa-search"></i>

                            <input
                                type="text"
                                name="search"
                                id="users-search"
                                value="{{ request('search') }}"
                                placeholder="Name, Login ID or Email..."
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="diary-filter-group">
                        <label for="users-role">Role</label>

                        <select name="role_id"
                                id="users-role"
                                class="diary-control">
                            <option value="">All Roles</option>

                            @foreach($roles ?? [] as $role)
                                <option value="{{ $role->id }}"
                                    @selected(request('role_id') == $role->id)>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="diary-filter-group">
                        <label for="users-status">Status</label>

                        <select name="status"
                                id="users-status"
                                class="diary-control">
                            <option value="">All Status</option>
                            <option value="Active" @selected(request('status') === 'Active')>
                                Active
                            </option>
                            <option value="Inactive" @selected(request('status') === 'Inactive')>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="diary-filter-actions">
                        <button type="submit" class="diary-apply-btn">
                            <i class="fas fa-filter"></i>
                            Apply Filters
                        </button>

                        <a href="{{ route('admin.settings.users.index') }}"
                           class="diary-reset-btn">
                            <i class="fas fa-rotate-left"></i>
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            {{-- ACTIVE FILTERS --}}
            @if($hasFilters)
                <div class="users-active-filters">
                    <div class="active-filter-items">
                        <span class="active-filter-label">
                            <i class="fas fa-sliders-h"></i>
                            Active filters
                        </span>

                        @if(request('search'))
                            <span class="filter-chip">
                                <i class="fas fa-search"></i>
                                {{ request('search') }}
                            </span>
                        @endif

                        @if(request('role_id'))
                            @php
                                $selectedRole = collect($roles ?? [])->firstWhere('id', request('role_id'));
                            @endphp
                            <span class="filter-chip">
                                <i class="fas fa-user-tag"></i>
                                {{ $selectedRole->display_name ?? 'Role' }}
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="filter-chip">
                                <i class="fas fa-circle-half-stroke"></i>
                                {{ request('status') }}
                            </span>
                        @endif
                    </div>

                    <span class="filter-result-count">
                        {{ $totalUsers }} {{ $totalUsers == 1 ? 'result' : 'results' }}
                    </span>
                </div>
            @endif

        </section>

        {{-- USERS TABLE --}}
        <section class="users-table-card">

            <div class="users-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">USER REGISTER</div>
                        <h3>All Users</h3>
                        <p>Review, edit or remove user accounts.</p>
                    </div>
                </div>

                <div class="users-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalUsers }}</strong>
                    {{ $totalUsers == 1 ? 'User' : 'Users' }}
                </div>
            </div>

            <div class="users-table-wrapper">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Login ID</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($usersCollection as $user)
                            @php
                                $isActive = $user->status === 'Active';
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        @if(isset($users) && method_exists($users, 'firstItem'))
                                            {{ $users->firstItem() + $loop->index }}
                                        @else
                                            {{ $loop->iteration }}
                                        @endif
                                    </span>
                                </td>

                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            @if($user->profile_photo)
                                                <img src="{{ $user->profile_photo }}"
                                                     alt="{{ $user->name }}"
                                                     class="user-avatar-img"
                                                     loading="lazy">
                                            @else
                                                <span>
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="user-details">
                                            <div class="user-name">
                                                {{ $user->name }}
                                            </div>
                                            <div class="user-email">
                                                <i class="fas fa-envelope"></i>
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
                                            <i class="fas fa-user-tag"></i>
                                            {{ $user->role->display_name }}
                                        </span>
                                    @else
                                        <span class="diary-muted">No Role</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    @if($user->created_at)
                                        <div class="event-date">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $user->created_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="diary-muted">—</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="users-actions">

                                        <a href="{{ route('admin.settings.users.show', $user) }}"
                                           class="game-action-btn view"
                                           title="View User"
                                           aria-label="View {{ $user->name }}">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.settings.users.edit', $user) }}"
                                           class="game-action-btn edit"
                                           title="Edit User"
                                           aria-label="Edit {{ $user->name }}">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        @if($user->id !== auth()->id() && !$user->hasRole('super_admin'))
                                            <form method="POST"
                                                  action="{{ route('admin.settings.users.destroy', $user) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="game-action-btn delete"
                                                        title="Delete User"
                                                        aria-label="Delete {{ $user->name }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="users-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-users"></i>
                                        </div>

                                        <h4>No Users Found</h4>

                                        <p>
                                            No users match your current search
                                            or filters.
                                        </p>

                                        <div class="empty-state-actions">
                                            @if($hasFilters)
                                                <a href="{{ route('admin.settings.users.index') }}"
                                                   class="diary-reset-btn">
                                                    <i class="fas fa-rotate-left"></i>
                                                    Clear Filters
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.settings.users.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add User
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if(isset($users) && method_exists($users, 'hasPages') && $users->hasPages())
                <div class="users-pagination">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $users->firstItem() }}</strong>
                        to
                        <strong>{{ $users->lastItem() }}</strong>
                        of
                        <strong>{{ $users->total() }}</strong>
                        users
                    </div>

                    <div>
                        {{ $users->withQueryString()->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif

            {{-- FOOTER --}}
            @if($totalUsers > 0)
                <div class="users-card-footer">
                    <div>
                        Showing
                        <strong>{{ $usersCollection->count() }}</strong>
                        user(s) on this page
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        User register loaded successfully
                    </div>
                </div>
            @endif

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM — FORCED VERSION
   Higher specificity + !important so Bootstrap cannot override
========================================================= */

.users-page {
    --ink: #263b35 !important;
    --ink-dark: #1d302b !important;
    --ink-hover: #456854 !important;
    --text: #34423d !important;
    --muted: #78827a !important;
    --line: #e5e1d5 !important;
    --paper: #fffefa !important;
    --paper-bg: #f7f6f0 !important;
    --chalk: #f4f0df !important;
    --blue: #2b5d8a !important;
    --amber: #a87543 !important;
    --green: #3a8060 !important;
    --red: #bd5148 !important;
    --cyan: #527f79 !important;
    --serif: Georgia, "Times New Roman", serif !important;
    --hand: "Segoe Print", "Comic Sans MS", cursive !important;

    min-height: calc(100vh - 70px) !important;
    color: var(--text) !important;
    background: var(--paper-bg) !important;
    font-family: "Public Sans", Inter, Arial, sans-serif !important;
}

.users-page *,
.users-page *::before,
.users-page *::after {
    box-sizing: border-box !important;
}

.users-page a,
.users-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease !important;
}

.users-page .container-fluid {
    width: 100% !important;
    max-width: 1650px !important;
    margin: 0 auto !important;
}

/* Kill Bootstrap card / table / btn styles inside this page */
.users-page .card,
.users-page .table,
.users-page .btn,
.users-page .form-control,
.users-page .form-select,
.users-page .badge {
    all: unset !important;
}

.users-page .users-hero,
.users-page .users-filter-card,
.users-page .users-table-card {
    border: 1px solid var(--line) !important;
    border-radius: 10px !important;
    background: var(--paper) !important;
    display: block !important;
}

/* ==================== HERO ==================== */

.users-page .users-hero {
    display: grid !important;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr) !important;
    position: relative !important;
    overflow: hidden !important;
    margin-bottom: 30px !important;
    border-radius: 10px !important;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045) !important;
}

.users-page .users-hero::before {
    content: "" !important;
    position: absolute !important;
    z-index: 1 !important;
    top: 0 !important;
    bottom: 0 !important;
    left: 34px !important;
    width: 2px !important;
    background: rgba(189, 81, 72, .35) !important;
}

.users-page .users-hero-content {
    display: flex !important;
    align-items: center !important;
    gap: 17px !important;
    min-width: 0 !important;
    padding: 29px 28px 29px 56px !important;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper) !important;
}

.users-page .users-hero-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 54px !important;
    width: 54px !important;
    height: 54px !important;
    border: 1px solid #e6dfce !important;
    border-radius: 12px !important;
    color: var(--ink) !important;
    background: var(--chalk) !important;
    font-size: 22px !important;
}

.users-page .users-hero-text {
    min-width: 0 !important;
}

.users-page .users-breadcrumb {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    margin-bottom: 7px !important;
    color: var(--amber) !important;
    font-size: 9px !important;
    font-weight: 800 !important;
    letter-spacing: 1.3px !important;
}

.users-page .users-breadcrumb span {
    color: #b8b09e !important;
}

.users-page .users-hero-text h2 {
    margin: 0 0 7px !important;
    color: var(--ink) !important;
    font-family: var(--serif) !important;
    font-size: 29px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    letter-spacing: -.45px !important;
}

.users-page .users-hero-text p {
    max-width: 540px !important;
    margin: 0 !important;
    color: var(--muted) !important;
    font-size: 13px !important;
    line-height: 1.75 !important;
}

.users-page .users-hero-note {
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 7px !important;
    margin-top: 13px !important;
    color: var(--green) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
}

.users-page .users-hero-actions {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 12px !important;
    min-height: 245px !important;
    padding: 25px !important;
    color: #e9efe2 !important;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%) !important;
    text-align: center !important;
}

.users-page .hero-handwritten {
    color: var(--chalk) !important;
    font-family: var(--hand) !important;
    font-size: 23px !important;
    line-height: 1.3 !important;
}

.users-page .users-hero-actions p {
    max-width: 300px !important;
    margin: 0 !important;
    color: #d2dccf !important;
    font-size: 12px !important;
    line-height: 1.75 !important;
}

/* ==================== BUTTONS ==================== */

.users-page .diary-primary-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    min-height: 40px !important;
    padding: 10px 16px !important;
    border: 1px solid var(--ink) !important;
    border-radius: 7px !important;
    color: #fff !important;
    background: var(--ink) !important;
    font-family: inherit !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    line-height: 1.2 !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    cursor: pointer !important;
}

.users-page .diary-primary-btn:hover {
    transform: translateY(-2px) !important;
    border-color: var(--ink-hover) !important;
    color: #fff !important;
    background: var(--ink-hover) !important;
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14) !important;
}

.users-page .users-hero-actions .hero-primary-btn {
    border-color: #f1ead8 !important;
    color: var(--ink) !important;
    background: #f1ead8 !important;
}

.users-page .users-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8 !important;
    color: var(--ink-dark) !important;
    background: #fff8e8 !important;
}

/* ==================== ALERTS ==================== */

.users-page .users-success-alert,
.users-page .users-error-alert {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    margin-bottom: 20px !important;
    padding: 13px 16px !important;
    border-radius: 9px !important;
    font-size: 12px !important;
    font-weight: 700 !important;
}

.users-page .users-success-alert {
    border: 1px solid #cfe3d1 !important;
    color: #347849 !important;
    background: #f1f8ef !important;
}

.users-page .users-error-alert {
    align-items: flex-start !important;
    border: 1px solid #f0d5ce !important;
    color: #a84840 !important;
    background: #fff4ef !important;
}

.users-page .success-icon,
.users-page .error-icon {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 27px !important;
    width: 27px !important;
    height: 27px !important;
    border-radius: 50% !important;
}

.users-page .success-icon { background: #dcefd9 !important; }
.users-page .error-icon { background: #fbe0d8 !important; }

.users-page .alert-close {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 30px !important;
    width: 30px !important;
    height: 30px !important;
    margin-left: auto !important;
    border: 0 !important;
    border-radius: 6px !important;
    color: #68746a !important;
    background: transparent !important;
    cursor: pointer !important;
}

.users-page .alert-close:hover {
    color: var(--ink) !important;
    background: #e5efdf !important;
}

/* ==================== KPI CARDS ==================== */

.users-page .users-kpis {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 18px !important;
    margin: 34px 0 25px !important;
}

.users-page .users-kpi {
    --kpi-color: var(--blue);

    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 14px !important;
    min-width: 0 !important;
    padding: 22px 20px 16px !important;
    border: 1px solid var(--line) !important;
    border-top: 4px solid var(--kpi-color) !important;
    border-radius: 9px !important;
    background: var(--paper) !important;
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045) !important;
    transition: transform .2s ease, box-shadow .2s ease !important;
}

.users-page .users-kpi::before {
    position: absolute !important;
    top: -25px !important;
    left: 15px !important;
    padding: 5px 12px 4px !important;
    border-radius: 6px 6px 0 0 !important;
    color: #fff !important;
    background: var(--kpi-color) !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: .25px !important;
}

.users-page .users-kpi.blue  { --kpi-color: #2b5d8a; }
.users-page .users-kpi.green { --kpi-color: #3a8060; }
.users-page .users-kpi.red   { --kpi-color: #bd5148; }

.users-page .users-kpi.blue::before  { content: "Total" !important; }
.users-page .users-kpi.green::before { content: "Active" !important; }
.users-page .users-kpi.red::before   { content: "Inactive" !important; }

.users-page .users-kpi:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09) !important;
}

.users-page .kpi-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 12px !important;
}

.users-page .kpi-label {
    color: var(--muted) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    line-height: 1.5 !important;
}

.users-page .users-kpi .kpi-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 43px !important;
    width: 43px !important;
    height: 43px !important;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white) !important;
    border-radius: 10px !important;
    color: var(--kpi-color) !important;
    background: color-mix(in srgb, var(--kpi-color) 10%, white) !important;
    font-size: 17px !important;
}

.users-page .kpi-value {
    color: var(--ink) !important;
    font-family: var(--serif) !important;
    font-size: 35px !important;
    font-weight: 700 !important;
    line-height: 1.1 !important;
    font-variant-numeric: tabular-nums !important;
    overflow-wrap: anywhere !important;
}

.users-page .kpi-caption {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    min-height: 35px !important;
    padding-top: 11px !important;
    border-top: 1px dashed #d8d1bf !important;
    color: var(--muted) !important;
    font-size: 11px !important;
    line-height: 1.6 !important;
}

.users-page .kpi-caption i {
    flex: 0 0 auto !important;
    color: var(--kpi-color) !important;
}

/* ==================== FILTER PANEL ==================== */

.users-page .users-filter-card {
    overflow: hidden !important;
    margin-bottom: 24px !important;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .035) !important;
}

.users-page .filter-heading {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 19px 20px 16px !important;
    border-bottom: 1px dashed #d8d1bf !important;
    background: #fffefa !important;
}

.users-page .filter-heading-icon,
.users-page .panel-title-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 40px !important;
    width: 40px !important;
    height: 40px !important;
    border: 1px solid #e3e2d5 !important;
    border-radius: 9px !important;
    color: var(--ink) !important;
    background: var(--chalk) !important;
    font-size: 14px !important;
}

.users-page .section-eyebrow {
    margin-bottom: 4px !important;
    color: var(--amber) !important;
    font-size: 9px !important;
    font-weight: 800 !important;
    letter-spacing: 1px !important;
}

.users-page .filter-heading h3,
.users-page .panel-title-wrap h3 {
    margin: 0 !important;
    color: var(--ink) !important;
    font-family: var(--serif) !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.users-page .filter-heading p,
.users-page .panel-title-wrap p {
    margin: 5px 0 0 !important;
    color: var(--muted) !important;
    font-size: 11px !important;
    line-height: 1.6 !important;
}

.users-page .reset-heading-btn {
    margin-left: auto !important;
}

.users-page .users-filter-card form {
    padding: 19px 20px 20px !important;
}

.users-page .users-filter-grid {
    display: grid !important;
    grid-template-columns:
        minmax(200px, 2fr)
        minmax(130px, 1fr)
        minmax(120px, 1fr)
        auto !important;
    align-items: end !important;
    gap: 12px !important;
}

.users-page .diary-filter-group {
    min-width: 0 !important;
}

.users-page .diary-filter-group label {
    display: block !important;
    margin-bottom: 7px !important;
    color: #68766d !important;
    font-size: 11px !important;
    font-weight: 700 !important;
}

.users-page .diary-control,
.users-page .diary-search-input {
    width: 100% !important;
    min-width: 0 !important;
    height: 41px !important;
    border: 1px solid #deded2 !important;
    border-radius: 7px !important;
    outline: none !important;
    color: var(--text) !important;
    background: #fffefa !important;
    font-family: inherit !important;
    font-size: 12px !important;
    transition: border-color .18s ease, box-shadow .18s ease !important;
}

.users-page .diary-control {
    padding: 0 10px !important;
}

.users-page .diary-control:focus,
.users-page .diary-search-input:focus-within {
    border-color: #789382 !important;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11) !important;
}

.users-page .diary-search-input {
    display: flex !important;
    align-items: center !important;
    gap: 9px !important;
    padding: 0 11px !important;
}

.users-page .diary-search-input > i {
    flex: 0 0 auto !important;
    color: #929b8f !important;
    font-size: 12px !important;
}

.users-page .diary-search-input input {
    width: 100% !important;
    min-width: 0 !important;
    height: 100% !important;
    padding: 0 !important;
    border: 0 !important;
    outline: 0 !important;
    color: var(--text) !important;
    background: transparent !important;
    font: inherit !important;
}

.users-page .diary-search-input input::placeholder {
    color: #929b8f !important;
}

.users-page .diary-filter-actions {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.users-page .diary-apply-btn,
.users-page .diary-reset-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    min-height: 41px !important;
    padding: 0 13px !important;
    border-radius: 7px !important;
    font-family: inherit !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    text-decoration: none !important;
    white-space: nowrap !important;
    cursor: pointer !important;
}

.users-page .diary-apply-btn {
    border: 1px solid var(--ink) !important;
    color: #fff !important;
    background: var(--ink) !important;
}

.users-page .diary-apply-btn:hover {
    transform: translateY(-1px) !important;
    border-color: var(--ink-hover) !important;
    color: #fff !important;
    background: var(--ink-hover) !important;
}

.users-page .diary-reset-btn {
    border: 1px solid #deded2 !important;
    color: #59675d !important;
    background: #fffefa !important;
}

.users-page .diary-reset-btn:hover {
    border-color: #b7c4b7 !important;
    color: var(--ink) !important;
    background: #f5f4eb !important;
}

/* ==================== ACTIVE FILTERS ==================== */

.users-page .users-active-filters {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 12px !important;
    padding: 11px 20px !important;
    border-top: 1px solid #eeece3 !important;
    background: #faf9f4 !important;
}

.users-page .active-filter-items {
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 7px !important;
    min-width: 0 !important;
}

.users-page .active-filter-label {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    margin-right: 3px !important;
    color: var(--muted) !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: .3px !important;
}

.users-page .active-filter-label i {
    color: var(--green) !important;
}

.users-page .filter-chip {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    max-width: 220px !important;
    padding: 5px 9px !important;
    overflow: hidden !important;
    border: 1px solid #e6dfc9 !important;
    border-radius: 6px !important;
    color: #765e32 !important;
    background: #faf5e7 !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    overflow-wrap: anywhere !important;
}

.users-page .filter-chip i {
    color: var(--amber) !important;
}

.users-page .filter-result-count {
    color: var(--muted) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    white-space: nowrap !important;
}

/* ==================== TABLE PANEL ==================== */

.users-page .users-table-card {
    overflow: hidden !important;
    margin-bottom: 24px !important;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04) !important;
}

.users-page .users-panel-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 15px !important;
    padding: 18px 21px !important;
    border-bottom: 1px dashed #d8d1bf !important;
    background: var(--paper) !important;
}

.users-page .panel-title-wrap {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    min-width: 0 !important;
}

.users-page .users-record-count {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    min-height: 31px !important;
    padding: 6px 11px !important;
    border: 1px solid var(--line) !important;
    border-radius: 20px !important;
    color: var(--muted) !important;
    background: #faf9f4 !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    white-space: nowrap !important;
}

.users-page .users-record-count i,
.users-page .users-record-count strong {
    color: var(--ink) !important;
}

/* ==================== TABLE ==================== */

.users-page .users-table-wrapper {
    width: 100% !important;
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
}

.users-page .users-table {
    width: 100% !important;
    min-width: 950px !important;
    margin: 0 !important;
    border-collapse: collapse !important;
}

.users-page .users-table thead th {
    padding: 13px 14px !important;
    border-bottom: 1px solid #e5e3d8 !important;
    color: #727a70 !important;
    background: #f5f3e9 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    text-align: left !important;
    text-transform: uppercase !important;
    letter-spacing: .45px !important;
    white-space: nowrap !important;
}

.users-page .users-table tbody td {
    padding: 13px 14px !important;
    border-bottom: 1px solid #eeece3 !important;
    color: var(--text) !important;
    font-size: 12px !important;
    vertical-align: middle !important;
}

.users-page .users-table tbody tr {
    transition: background .18s ease !important;
}

.users-page .users-table tbody tr:hover {
    background: #faf9f2 !important;
}

.users-page .users-table tbody tr:last-child td {
    border-bottom: none !important;
}

.users-page .users-table .actions-heading {
    text-align: right !important;
}

.users-page .record-number {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 31px !important;
    height: 31px !important;
    border: 1px solid #e7e4d8 !important;
    border-radius: 7px !important;
    color: #667269 !important;
    background: #f5f3e9 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
}

/* ==================== USER CELL ==================== */

.users-page .user-info {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    min-width: 220px !important;
}

.users-page .user-avatar {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 42px !important;
    width: 42px !important;
    height: 42px !important;
    overflow: hidden !important;
    border: 1px solid #e6dfce !important;
    border-radius: 50% !important;
    background: var(--chalk) !important;
    color: var(--ink) !important;
    font-size: 14px !important;
    font-weight: 800 !important;
}

.users-page .user-avatar-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

.users-page .user-details {
    min-width: 0 !important;
}

.users-page .user-name {
    max-width: 200px !important;
    overflow: hidden !important;
    color: var(--ink) !important;
    font-size: 12px !important;
    font-weight: 800 !important;
    text-overflow: ellipsis !important;
    white-space: nowrap !important;
}

.users-page .user-email {
    display: flex !important;
    align-items: center !important;
    gap: 5px !important;
    margin-top: 4px !important;
    color: #858e82 !important;
    font-size: 10px !important;
}

.users-page .user-email i {
    color: var(--amber) !important;
}

/* ==================== OTHER CELLS ==================== */

.users-page .login-id {
    display: inline-block !important;
    padding: 5px 9px !important;
    border: 1px solid #e6dfce !important;
    border-radius: 6px !important;
    color: var(--ink) !important;
    background: #faf9f4 !important;
    font-size: 11px !important;
    font-weight: 800 !important;
}

.users-page .role-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 6px 9px !important;
    border: 1px solid #d7e2e9 !important;
    border-radius: 6px !important;
    color: var(--blue) !important;
    background: #f0f5f8 !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    white-space: nowrap !important;
}

.users-page .event-date {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    color: var(--text) !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    white-space: nowrap !important;
}

.users-page .event-date i {
    color: var(--blue) !important;
}

/* ==================== STATUS BADGES ==================== */

.users-page .status-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 6px 9px !important;
    border-radius: 20px !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    white-space: nowrap !important;
}

.users-page .status-dot {
    width: 6px !important;
    height: 6px !important;
    flex: 0 0 6px !important;
    border-radius: 50% !important;
}

.users-page .status-badge.active {
    border: 1px solid #cfe5d3 !important;
    color: #347849 !important;
    background: #edf7ed !important;
}

.users-page .status-badge.active .status-dot {
    background: #3a8060 !important;
}

.users-page .status-badge.inactive {
    border: 1px solid #f0d5ce !important;
    color: #bd5148 !important;
    background: #fff1ed !important;
}

.users-page .status-badge.inactive .status-dot {
    background: #bd5148 !important;
}

.users-page .diary-muted {
    color: #929a8e !important;
    font-size: 11px !important;
}

/* ==================== ACTION BUTTONS ==================== */

.users-page .users-actions {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    gap: 6px !important;
}

.users-page .users-actions form {
    display: inline-flex !important;
    margin: 0 !important;
}

.users-page .game-action-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex: 0 0 32px !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    border: 1px solid #dedfd4 !important;
    border-radius: 7px !important;
    color: #68746a !important;
    background: #fffefa !important;
    font-size: 11px !important;
    text-decoration: none !important;
    cursor: pointer !important;
}

.users-page .game-action-btn:hover {
    transform: translateY(-2px) !important;
}

.users-page .game-action-btn.view:hover {
    border-color: #c5d6ea !important;
    color: var(--blue) !important;
    background: #f0f5f8 !important;
}

.users-page .game-action-btn.edit:hover {
    border-color: #e6d0a8 !important;
    color: var(--amber) !important;
    background: #fcf7e9 !important;
}

.users-page .game-action-btn.delete:hover {
    border-color: #f0c9c2 !important;
    color: var(--red) !important;
    background: #fff1ed !important;
}

/* ==================== EMPTY STATE ==================== */

.users-page .users-empty-state {
    padding: 60px 20px !important;
    text-align: center !important;
}

.users-page .empty-state-icon {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 72px !important;
    height: 72px !important;
    margin: 0 auto 16px !important;
    border: 1px solid #e5dfcc !important;
    border-radius: 14px !important;
    color: var(--ink) !important;
    background: var(--chalk) !important;
    font-size: 27px !important;
}

.users-page .users-empty-state h4 {
    margin: 0 0 8px !important;
    color: var(--ink) !important;
    font-family: var(--serif) !important;
    font-size: 21px !important;
    font-weight: 700 !important;
}

.users-page .users-empty-state p {
    max-width: 450px !important;
    margin: 0 auto 18px !important;
    color: var(--muted) !important;
    font-size: 12px !important;
    line-height: 1.8 !important;
}

.users-page .empty-state-actions {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-wrap: wrap !important;
    gap: 9px !important;
}

/* ==================== PAGINATION ==================== */

.users-page .users-pagination {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 15px !important;
    padding: 16px 20px !important;
    border-top: 1px dashed #d8d1bf !important;
    background: var(--paper) !important;
}

.users-page .pagination-info {
    color: var(--muted) !important;
    font-size: 11px !important;
}

.users-page .pagination-info strong {
    color: var(--ink) !important;
    font-weight: 800 !important;
}

.users-page .users-pagination nav {
    margin: 0 !important;
}

.users-page .users-pagination .pagination {
    flex-wrap: wrap !important;
    gap: 4px !important;
    margin: 0 !important;
}

.users-page .users-pagination .page-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 32px !important;
    min-height: 32px !important;
    padding: 5px 9px !important;
    border: 1px solid var(--line) !important;
    border-radius: 7px !important;
    color: var(--ink) !important;
    background: var(--paper) !important;
    font-size: 12px !important;
    box-shadow: none !important;
}

.users-page .users-pagination .page-link:hover {
    border-color: #bfcbbd !important;
    color: var(--ink-hover) !important;
    background: #f5f4eb !important;
}

.users-page .users-pagination .page-item.active .page-link {
    border-color: var(--ink) !important;
    color: #fff !important;
    background: var(--ink) !important;
}

.users-page .users-pagination .page-item.disabled .page-link {
    color: #a9aea5 !important;
    background: #f7f6f0 !important;
}

/* ==================== FOOTER ==================== */

.users-page .users-card-footer {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 15px !important;
    min-height: 53px !important;
    padding: 12px 20px !important;
    border-top: 1px dashed #d8d1bf !important;
    color: var(--muted) !important;
    background: #faf9f4 !important;
    font-size: 11px !important;
}

.users-page .users-card-footer strong {
    color: var(--ink) !important;
}

.users-page .footer-status {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    color: #68766d !important;
    font-size: 10px !important;
}

.users-page .footer-status i {
    color: var(--green) !important;
}

/* ==================== RESPONSIVE ==================== */

@media (max-width: 1250px) {
    .users-page .users-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .users-page .users-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .users-page .diary-filter-actions {
        grid-column: span 2 !important;
    }
}

@media (max-width: 900px) {
    .users-page .users-hero {
        grid-template-columns: 1fr !important;
    }

    .users-page .users-hero-actions {
        min-height: 165px !important;
    }
}

@media (max-width: 650px) {
    .users-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .users-page .users-hero::before {
        left: 22px !important;
    }

    .users-page .users-hero-content {
        align-items: flex-start !important;
        gap: 12px !important;
        padding: 22px 16px 22px 42px !important;
    }

    .users-page .users-hero-icon {
        flex-basis: 43px !important;
        width: 43px !important;
        height: 43px !important;
        border-radius: 9px !important;
        font-size: 18px !important;
    }

    .users-page .users-hero-text h2 {
        font-size: 23px !important;
    }

    .users-page .users-hero-text p {
        font-size: 12px !important;
    }

    .users-page .users-hero-note {
        align-items: flex-start !important;
        font-size: 10px !important;
    }

    .users-page .users-hero-actions {
        min-height: 155px !important;
        padding: 20px !important;
    }

    .users-page .users-kpis {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
        margin-top: 30px !important;
    }

    .users-page .users-kpi {
        padding: 20px 16px 15px !important;
    }

    .users-page .kpi-value {
        font-size: 30px !important;
    }

    .users-page .filter-heading {
        align-items: flex-start !important;
        flex-wrap: wrap !important;
        padding: 16px !important;
    }

    .users-page .filter-heading h3 {
        font-size: 16px !important;
    }

    .users-page .reset-heading-btn {
        margin-left: 0 !important;
    }

    .users-page .users-filter-card form {
        padding: 15px !important;
    }

    .users-page .users-filter-grid {
        grid-template-columns: 1fr !important;
        gap: 12px !important;
    }

    .users-page .diary-filter-actions {
        grid-column: auto !important;
    }

    .users-page .diary-filter-actions > * {
        flex: 1 !important;
    }

    .users-page .users-active-filters {
        align-items: flex-start !important;
        flex-direction: column !important;
        padding: 11px 15px !important;
    }

    .users-page .filter-result-count {
        white-space: normal !important;
    }

    .users-page .users-panel-header {
        align-items: flex-start !important;
        padding: 15px !important;
    }

    .users-page .panel-title-wrap h3 {
        font-size: 16px !important;
    }

    .users-page .users-record-count {
        gap: 5px !important;
        padding: 5px 7px !important;
    }

    .users-page .users-pagination {
        align-items: flex-start !important;
        flex-direction: column !important;
        padding: 14px 15px !important;
    }

    .users-page .users-card-footer {
        align-items: flex-start !important;
        flex-direction: column !important;
        padding: 13px 15px !important;
    }
}

@media (max-width: 400px) {
    .users-page .users-panel-header {
        flex-wrap: wrap !important;
        gap: 8px !important;
    }

    .users-page .panel-title-icon {
        flex-basis: 34px !important;
        width: 34px !important;
        height: 34px !important;
    }

    .users-page .diary-filter-actions {
        align-items: stretch !important;
        flex-direction: column !important;
    }

    .users-page .diary-apply-btn,
    .users-page .diary-reset-btn {
        width: 100% !important;
    }

    .users-page .active-filter-items {
        align-items: flex-start !important;
    }

    .users-page .filter-chip {
        max-width: 100% !important;
    }

    .users-page .empty-state-actions {
        align-items: stretch !important;
        flex-direction: column !important;
        width: 100% !important;
    }

    .users-page .empty-state-actions > a {
        width: 100% !important;
    }
}

/* ==================== ACCESSIBILITY ==================== */

.users-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45) !important;
    outline-offset: 3px !important;
}

@media (prefers-reduced-motion: reduce) {
    .users-page *,
    .users-page *::before,
    .users-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection