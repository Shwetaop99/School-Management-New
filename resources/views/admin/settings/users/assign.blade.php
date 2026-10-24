@extends('layouts.app')

@section('title', 'User Assign | Admin')
@section('page-title', 'User Assign')

@section('content')

@php
    $totalUsers     = \App\Models\User::count();
    $activeRoles    = $roles->count();
    $displayedUsers = $users->total();

    // Optional extra KPI – users that currently have a role assigned
    $usersWithRole  = $users->getCollection()->whereNotNull('role_id')->count();
@endphp

<div class="id-card-index">
    <div class="container-fluid py-4">

        {{-- SUCCESS / ERROR / VALIDATION ALERTS --}}
        @if(session('success'))
            <div class="idcard-alert" role="status">
                <span class="success-icon"><i class="fas fa-check"></i></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="idcard-alert" role="alert" style="border-color:#eed6cf;color:#b95b50;background:#fff1ed;">
                <span class="success-icon" style="color:#b95b50;background:#f8e0db;">
                    <i class="fas fa-exclamation"></i>
                </span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="idcard-alert" role="alert" style="border-color:#eed6cf;color:#b95b50;background:#fff1ed;">
                <span class="success-icon" style="color:#b95b50;background:#f8e0db;">
                    <i class="fas fa-exclamation"></i>
                </span>
                <div>
                    <strong>Please correct the following:</strong>
                    <ul style="margin:6px 0 0;padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- HERO --}}
        <section class="idcard-hero">
            <div class="idcard-hero-content">
                <div class="idcard-hero-icon">
                    <i class="fas fa-user-tag"></i>
                </div>

                <div class="idcard-hero-text">
                    <div class="idcard-breadcrumb">
                        <span>User Management</span>
                        <i class="fas fa-chevron-right"></i>
                        <span>User Assign</span>
                    </div>

                    <h2>User Role Assignment</h2>

                    <p>
                        Give every team member the right responsibilities.
                        Select an existing user, review their current role,
                        and assign an active role without changing their account details.
                    </p>

                    <div class="idcard-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Access Control &amp; Responsibility Management</span>
                    </div>
                </div>
            </div>

            <div class="idcard-hero-actions">
                <div class="hero-decoration">
                    <i class="fas fa-users-cog"></i>
                </div>

                <div class="hero-handwritten">The right role. The right access.</div>

                <p>
                    Keep school operations organised by managing role
                    assignments from one dedicated workspace.
                </p>

                <div class="hero-button-group">
                    <a href="{{ route('admin.settings.users.index') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-users"></i>
                        All Users
                    </a>
                </div>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="idcard-kpis">

            <article class="idcard-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total User Accounts</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($totalUsers) }}</div>
                <div class="kpi-caption">All registered user accounts</div>
            </article>

            <article class="idcard-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Roles</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-shield"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($activeRoles) }}</div>
                <div class="kpi-caption">Roles available for assignment</div>
            </article>

            <article class="idcard-kpi orange">
                <div class="kpi-top">
                    <span class="kpi-label">Users Displayed</span>
                    <span class="kpi-icon">
                        <i class="fas fa-list"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($displayedUsers) }}</div>
                <div class="kpi-caption">Users matching current filters</div>
            </article>

            <article class="idcard-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">With Role (page)</span>
                    <span class="kpi-icon">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="kpi-value">{{ number_format($usersWithRole) }}</div>
                <div class="kpi-caption">Users on this page that already have a role</div>
            </article>

        </section>

        {{-- MAIN PANEL --}}
        <section class="idcard-paper-panel">

            <div class="idcard-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <h3>Manage Assignments</h3>
                        <p>Search for an account and assign an available role.</p>
                    </div>
                </div>

                <div class="idcard-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ number_format($displayedUsers) }}</strong>
                    Users
                </div>
            </div>

            {{-- SEARCH AND FILTERS --}}
            <div class="idcard-filter-panel">

                <div class="filter-heading">
                    <div class="filter-heading-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4>Find Users</h4>
                        <p>Search by name, login ID or email address.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.settings.user-assign') }}">
                    <div class="idcard-filter-grid" style="grid-template-columns: minmax(220px, 2.5fr) auto;">

                        <div class="diary-filter-group search-group">
                            <label for="userSearch">Search Users</label>
                            <div class="diary-search-input">
                                <i class="fas fa-search"></i>
                                <input id="userSearch"
                                       type="search"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Name, login ID or email..."
                                       autocomplete="off"
                                       aria-label="Search users">
                            </div>
                        </div>

                        <div class="diary-filter-actions">
                            <button type="submit" class="diary-primary-btn" style="min-height:44px;">
                                <i class="fas fa-search"></i> Search
                            </button>

                            @if(request()->filled('search'))
                                <a href="{{ route('admin.settings.user-assign') }}"
                                   class="diary-reset-btn">
                                    <i class="fas fa-rotate-left"></i> Clear
                                </a>
                            @endif
                        </div>

                    </div>
                </form>
            </div>

            {{-- RECORDS TABLE --}}
            @if($users->count() > 0)

                <div class="idcard-table-wrapper">
                    <table class="idcard-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Account</th>
                                <th>Login ID</th>
                                <th>Current Role</th>
                                <th>Status</th>
                                <th class="actions-heading">Assign New Role</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $index => $user)

                                <tr class="card-row">

                                    <td>
                                        <span class="idcard-row-number">
                                            {{ $users->firstItem() + $index }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="student-info">
                                            <div class="student-avatar">
                                                @if($user->profile_photo)
                                                    <img src="{{ $user->profile_photo }}"
                                                         alt="{{ $user->name }}"
                                                         loading="lazy">
                                                @else
                                                    <span style="font-weight:800;">
                                                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="student-details">
                                                <div class="student-name">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="student-id">
                                                    <i class="fas fa-envelope"></i>
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if(!empty($user->login_id))
                                            <span class="idcard-id-chip">
                                                <i class="fas fa-id-badge"></i>
                                                {{ $user->login_id }}
                                            </span>
                                        @else
                                            <span class="diary-muted">—</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($user->role)
                                            <span class="idcard-template template-modern">
                                                <i class="fas fa-check"></i>
                                                {{ $user->role->display_name
                                                    ?? $user->role->name
                                                    ?? 'Assigned Role' }}
                                            </span>
                                        @else
                                            <span class="idcard-template" style="border-color:#ebdfc5;color:#8b6b3c;background:#f8f0df;">
                                                <i class="fas fa-minus"></i>
                                                No role assigned
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if(($user->status ?? '') === 'Active')
                                            <span class="idcard-status active">
                                                <span class="status-dot"></span> Active
                                            </span>
                                        @else
                                            <span class="idcard-status inactive">
                                                <span class="status-dot"></span>
                                                {{ $user->status ?? 'Unknown' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($roles->isNotEmpty())
                                            <form action="{{ route('admin.settings.user-assign.update', $user) }}"
                                                  method="POST"
                                                  class="idcard-actions"
                                                  style="justify-content:flex-end;gap:8px;min-width:260px;">
                                                @csrf

                                                <select name="role_id"
                                                        class="diary-control"
                                                        style="min-width:140px;height:34px;font-size:11px;padding:0 10px;"
                                                        aria-label="Select role for {{ $user->name }}"
                                                        required>
                                                    <option value="" disabled>Select role</option>

                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            @selected((string) $user->role_id === (string) $role->id)>
                                                            {{ $role->display_name
                                                                ?? $role->name
                                                                ?? 'Role #' . $role->id }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <button type="submit"
                                                        class="idcard-action-btn print"
                                                        title="Assign Role"
                                                        aria-label="Assign role"
                                                        onclick="return confirm('Assign the selected role to {{ addslashes($user->name) }}?');"
                                                        style="width:auto;padding:0 12px;min-width:70px;">
                                                    <i class="fas fa-user-check"></i>
                                                    Assign
                                                </button>
                                            </form>
                                        @else
                                            <span class="diary-muted">No active roles available.</span>
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($users->hasPages())
                    <div class="idcard-pagination">
                        <div class="pagination-info">
                            Showing <strong>{{ $users->firstItem() }}</strong>
                            to <strong>{{ $users->lastItem() }}</strong>
                            of <strong>{{ $users->total() }}</strong> users
                        </div>

                        <div class="pagination-links">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif

                {{-- INFORMATION BAR --}}
                <div class="idcard-info-bar">
                    <span class="idcard-info-icon">
                        <i class="fas fa-lightbulb"></i>
                    </span>

                    <div>
                        <h6>Access Reminder</h6>
                        <p>
                            Assign roles carefully because a role may grant permissions to
                            sensitive school-management features. Only active roles are
                            available for new assignments. Changing a role does not change
                            the user's password, email, login ID, or account status.
                        </p>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="idcard-card-footer">
                    <div>
                        Showing <strong>{{ $users->count() }}</strong>
                        user(s) on this page
                    </div>
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Data loaded successfully
                    </div>
                </div>

            @else

                {{-- EMPTY STATE --}}
                <div class="idcard-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-user-slash"></i>
                    </div>

                    <h4>No User Accounts Found</h4>
                    <p>
                        Try a different search term or create a user account first.
                    </p>
                    <a href="{{ route('admin.settings.users.index') }}"
                       class="diary-primary-btn">
                        <i class="fas fa-users"></i> Go to All Users
                    </a>
                </div>

            @endif
        </section>
    </div>
</div>

<style>
/* ==========================================================
   USER ASSIGN — SCHOOL DIARY DESIGN SYSTEM
   Exact match to Student ID Cards / Government Schemes design
========================================================== */

.id-card-index {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-soft: #40574a;
    --text: #39483e;
    --muted: #7b857b;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f5f3e9;
    --sage: #527f70;
    --blue: #426b8a;
    --amber: #a87543;
    --green: #43835d;
    --red: #b95b50;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: 'Public Sans', sans-serif;
    font-size: 13px;
}

.id-card-index *,
.id-card-index *::before,
.id-card-index *::after {
    box-sizing: border-box;
}

.id-card-index a,
.id-card-index button {
    transition: color .2s ease, background .2s ease,
        border-color .2s ease, box-shadow .2s ease, transform .2s ease;
}

.id-card-index .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding-right: 24px;
    padding-left: 24px;
}

.idcard-hero,
.idcard-paper-panel {
    border: 1px solid var(--line);
    border-radius: 15px;
    background: var(--paper);
    box-shadow: 0 5px 18px rgba(38, 59, 53, .035);
}

/* HERO */

.idcard-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.55fr) minmax(290px, .85fr);
    overflow: hidden;
    margin-bottom: 36px;
}

.idcard-hero::before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 28px;
    width: 2px;
    background: #dfb4a5;
    content: "";
}

.idcard-hero-content {
    display: flex;
    align-items: center;
    gap: 21px;
    min-width: 0;
    padding: 35px 30px 35px 51px;
}

.idcard-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 64px;
    width: 64px;
    height: 64px;
    border: 1px solid #e2ddcf;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 3px 10px rgba(38, 59, 53, .04);
    font-size: 25px;
}

.idcard-hero-text {
    min-width: 0;
}

.idcard-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
    color: #858d80;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .8px;
    text-transform: uppercase;
}

.idcard-breadcrumb i {
    color: #b1a895;
    font-size: 9px;
}

.idcard-hero-text h2 {
    margin: 0 0 11px;
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: clamp(26px, 2.2vw, 34px);
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.7px;
}

.idcard-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.9;
}

.idcard-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 16px;
    color: #477858;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.idcard-hero-note i {
    font-size: 13px;
}

.idcard-hero-actions {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 30px 25px;
    overflow: hidden;
    color: #e8eee4;
    background: linear-gradient(145deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.idcard-hero-actions::before,
.idcard-hero-actions::after {
    position: absolute;
    width: 190px;
    height: 190px;
    border: 1px solid rgba(255, 255, 255, .09);
    border-radius: 50%;
    content: "";
    pointer-events: none;
}

.idcard-hero-actions::before {
    top: -115px;
    right: -60px;
}

.idcard-hero-actions::after {
    bottom: -140px;
    left: -70px;
}

.hero-decoration {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 1px solid rgba(255, 255, 255, .2);
    border-radius: 12px;
    color: #ead7aa;
    background: rgba(255, 255, 255, .07);
    font-size: 16px;
}

.hero-handwritten {
    position: relative;
    z-index: 1;
    color: #f0e5c9;
    font-family: var(--hand, cursive);
    font-size: 23px;
    line-height: 1.4;
}

.idcard-hero-actions p {
    position: relative;
    z-index: 1;
    max-width: 300px;
    margin: 0;
    color: #cbd5cb;
    font-size: 11px;
    line-height: 1.9;
}

.hero-button-group {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

.diary-primary-btn {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid var(--ink);
    border-radius: 9px;
    color: #fff;
    background: var(--ink);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.idcard-hero-actions .diary-primary-btn {
    border-color: #e8e0c9;
    color: var(--ink);
    background: #f1ead8;
}

.diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: #456854;
    color: #fff;
    background: #456854;
    box-shadow: 0 6px 15px rgba(29, 48, 43, .13);
}

.idcard-hero-actions .diary-primary-btn:hover {
    border-color: #fff5dc;
    color: var(--ink);
    background: #fff5dc;
}

.diary-ghost-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 42px;
    padding: 11px 17px;
    border: 1px solid rgba(255, 255, 255, .25);
    border-radius: 9px;
    color: #f0e5c9;
    background: rgba(255, 255, 255, .07);
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
}

.diary-ghost-btn:hover {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, .45);
    color: #fff;
    background: rgba(255, 255, 255, .14);
}

/* SUCCESS MESSAGE */

.idcard-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #d2e5d1;
    border-radius: 11px;
    color: #347849;
    background: #f1f8ef;
    font-size: 12px;
    font-weight: 700;
}

.success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 31px;
    width: 31px;
    height: 31px;
    border-radius: 9px;
    color: #347849;
    background: #deeedb;
}

/* KPI CARDS */

.idcard-kpis {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin: 36px 0 29px;
}

.idcard-kpi {
    --kpi-color: #426b8a;

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 15px;
    min-width: 0;
    padding: 22px 21px 18px;
    border: 1px solid var(--line);
    border-top: 3px solid var(--kpi-color);
    border-radius: 12px;
    background: var(--paper);
    box-shadow: 0 3px 12px rgba(38, 59, 53, .025);
}

.idcard-kpi.blue { --kpi-color: #426b8a; }
.idcard-kpi.green { --kpi-color: #43835d; }
.idcard-kpi.orange { --kpi-color: #a87543; }
.idcard-kpi.red { --kpi-color: #b95b50; }

.idcard-kpi::before {
    position: absolute;
    top: -24px;
    left: 17px;
    padding: 5px 10px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
}

.idcard-kpi.blue::before { content: "USERS"; }
.idcard-kpi.green::before { content: "ROLES"; }
.idcard-kpi.orange::before { content: "DISPLAYED"; }
.idcard-kpi.red::before { content: "ASSIGNED"; }

.idcard-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 9px 22px rgba(38, 59, 53, .07);
}

.kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.kpi-label {
    color: #758176;
    font-size: 11px;
    font-weight: 700;
}

.idcard-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e8e5da;
    border-radius: 12px;
    color: var(--kpi-color);
    background: #f7f6ef;
    font-size: 17px;
}

.kpi-value {
    color: var(--ink);
    font-family: var(--serif, Georgia, "Times New Roman", serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    overflow-wrap: anywhere;
}

.kpi-caption {
    padding-top: 13px;
    border-top: 1px dashed #e1dccf;
    color: #818a7e;
    font-size: 10px;
    line-height: 1.7;
}

/* MAIN PANEL */

.idcard-paper-panel {
    overflow: hidden;
}

.idcard-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px solid #e9e5d9;
    background: #fffefa;
}

.panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 13px;
    min-width: 0;
}

.panel-title-icon,
.filter-heading-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #e0e3d7;
    border-radius: 12px;
    color: var(--ink);
    background: #f1f2e8;
    font-size: 16px;
}

.panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 19px;
    font-weight: 700;
    letter-spacing: -.25px;
}

.panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.idcard-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 35px;
    padding: 8px 12px;
    border: 1px solid #e4e0d4;
    border-radius: 9px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.idcard-record-count i,
.idcard-record-count strong {
    color: var(--ink);
}

/* FILTER PANEL */

.idcard-filter-panel {
    padding: 23px 24px;
    border-bottom: 1px solid var(--line);
    background: #faf9f3;
}

.filter-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 19px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #ddd8ca;
}

.filter-heading-icon {
    color: #527f70;
    background: #edf2e9;
}

.filter-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 17px;
    font-weight: 700;
}

.filter-heading p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

.idcard-filter-grid {
    display: grid;
    grid-template-columns: minmax(220px, 2fr) minmax(160px, .9fr) auto;
    align-items: end;
    gap: 16px;
}

.diary-filter-group {
    min-width: 0;
}

.diary-filter-group label {
    display: block;
    margin-bottom: 8px;
    color: #526358;
    font-size: 11px;
    font-weight: 800;
}

.diary-control,
.diary-search-input {
    width: 100%;
    min-width: 0;
    height: 44px;
    border: 1px solid #dedfd3;
    border-radius: 9px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .2s ease, box-shadow .2s ease;
}

.diary-control {
    padding: 0 12px;
    cursor: pointer;
}

.diary-control:hover,
.diary-search-input:hover {
    border-color: #bdcabb;
}

.diary-control:focus,
.diary-search-input:focus-within {
    border-color: #739780;
    box-shadow: 0 0 0 3px rgba(67, 131, 93, .1);
}

.diary-search-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0 13px;
}

.diary-search-input > i {
    flex: 0 0 auto;
    color: #829083;
    font-size: 13px;
}

.diary-search-input input {
    width: 100%;
    min-width: 0;
    height: 100%;
    padding: 0;
    border: 0;
    outline: 0;
    color: var(--text);
    background: transparent;
    font: inherit;
}

.diary-search-input input::placeholder {
    color: #9a9f93;
}

.diary-filter-actions {
    display: flex;
    align-items: center;
    gap: 9px;
}

.diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 15px;
    border: 1px solid #deded2;
    border-radius: 9px;
    color: #5d6b60;
    background: #fffefa;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.diary-reset-btn:hover {
    border-color: #b5c4b0;
    color: var(--ink);
    background: #f0f3e9;
}

/* RECORDS TABLE */

.idcard-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.idcard-table {
    width: 100%;
    min-width: 1050px;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.idcard-table thead th {
    padding: 15px 14px;
    border-bottom: 1px solid #e2e0d5;
    color: #637166;
    background: #f1f2e9;
    font-size: 9px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .65px;
    white-space: nowrap;
}

.idcard-table tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #efede5;
    color: var(--text);
    font-size: 11px;
    vertical-align: middle;
}

.idcard-table tbody tr {
    background: #fffefa;
    transition: background .18s ease;
}

.idcard-table tbody tr:hover {
    background: #f8f8f1;
}

.idcard-table tbody tr:last-child td {
    border-bottom: none;
}

.idcard-table .actions-heading {
    text-align: right;
}

.idcard-row-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 6px;
    border: 1px solid #e3e1d6;
    border-radius: 9px;
    color: #68776a;
    background: #f5f4ec;
    font-size: 10px;
    font-weight: 800;
}

/* STUDENT / USER */

.student-info {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 190px;
}

.student-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    overflow: hidden;
    border: 1px solid #dce3d8;
    border-radius: 12px;
    color: var(--ink);
    background: #edf1e7;
    font-size: 14px;
    font-weight: 800;
}

.student-avatar img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-details {
    min-width: 0;
}

.student-name {
    max-width: 210px;
    overflow: hidden;
    color: #293d32;
    font-size: 11px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.student-id {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
    color: #828c7f;
    font-size: 10px;
}

.student-id i {
    color: var(--amber);
}

.idcard-id-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid #dce4d8;
    border-radius: 7px;
    color: #477858;
    background: #edf6e9;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-id-chip i {
    color: var(--green);
}

.idcard-number {
    color: #34443a;
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

/* TEMPLATE / ROLE */

.idcard-template {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid #e4e0d4;
    border-radius: 7px;
    color: #6d786d;
    background: #f8f7f0;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-template.template-modern {
    border-color: #d9e2e9;
    color: #426b8a;
    background: #edf4f8;
}

/* STATUS */

.idcard-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.idcard-status.active {
    border-color: #d4e5d0;
    color: #347849;
    background: #edf6e9;
}

.idcard-status.inactive {
    border-color: #eed6cf;
    color: #b95b50;
    background: #fff1ed;
}

.status-dot {
    flex: 0 0 7px;
    width: 7px;
    height: 7px;
    border-radius: 50%;
}

.idcard-status.active .status-dot {
    background: #43835d;
}

.idcard-status.inactive .status-dot {
    background: #b95b50;
}

.diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* ACTION BUTTONS */

.idcard-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
}

.idcard-actions form {
    display: inline-flex;
    margin: 0;
}

.idcard-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 34px;
    width: 34px;
    height: 34px;
    padding: 0;
    border: 1px solid #e2e1d7;
    border-radius: 9px;
    color: #657369;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none;
    cursor: pointer;
}

.idcard-action-btn:hover {
    transform: translateY(-2px);
}

.idcard-action-btn.view:hover {
    border-color: #cbdce5;
    color: #426b8a;
    background: #edf4f8;
}

.idcard-action-btn.print:hover {
    border-color: #d4e5d0;
    color: #43835d;
    background: #edf6e9;
}

.idcard-action-btn.delete:hover {
    border-color: #edcbc3;
    color: #b95b50;
    background: #fff0ec;
}

/* PAGINATION */

.idcard-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 17px 22px;
    border-top: 1px solid #eae6da;
    background: #fffefa;
}

.pagination-info {
    color: #7b857b;
    font-size: 10px;
}

.pagination-info strong {
    color: var(--ink);
    font-weight: 800;
}

.pagination-links {
    min-width: 0;
}

.pagination-links nav {
    margin: 0;
}

.pagination-links .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.pagination-links .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    min-height: 34px;
    padding: 5px 10px;
    border: 1px solid var(--line);
    border-radius: 8px !important;
    color: var(--ink);
    background: var(--paper);
    font-size: 11px;
    box-shadow: none;
}

.pagination-links .page-link:hover {
    border-color: #b5c5b0;
    color: #43835d;
    background: #f2f4e9;
}

.pagination-links .page-item.active .page-link {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.pagination-links .page-item.disabled .page-link {
    color: #a9aea5;
    background: #f7f6f0;
}

/* INFORMATION BAR */

.idcard-info-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 19px 24px;
    border-top: 1px solid var(--line);
    background: #fffefa;
}

.idcard-info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid #dce4d8;
    border-radius: 12px;
    color: var(--sage);
    background: #edf2e9;
    font-size: 16px;
}

.idcard-info-bar h6 {
    margin: 0 0 4px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 15px;
    font-weight: 700;
}

.idcard-info-bar p {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.7;
}

/* FOOTER */

.idcard-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 55px;
    padding: 13px 22px;
    border-top: 1px dashed #ddd7c9;
    color: #788277;
    background: #f8f7f0;
    font-size: 10px;
}

.idcard-card-footer strong {
    color: var(--ink);
}

.footer-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #68776a;
    font-size: 10px;
    font-weight: 700;
}

.footer-status i {
    color: #43835d;
}

/* EMPTY STATE */

.idcard-empty-state {
    padding: 65px 20px;
    text-align: center;
}

.empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    margin: 0 auto 19px;
    border: 1px solid #e3decc;
    border-radius: 19px;
    color: var(--ink);
    background: var(--chalk);
    box-shadow: 0 5px 13px rgba(38, 59, 53, .04);
    font-size: 27px;
}

.idcard-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif, Georgia, serif);
    font-size: 22px;
    font-weight: 700;
}

.idcard-empty-state p {
    max-width: 450px;
    margin: 0 auto 21px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.9;
}

/* TABLET */

@media (max-width: 1200px) {
    .idcard-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .idcard-filter-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .diary-filter-actions {
        grid-column: span 2;
    }
}

@media (max-width: 900px) {
    .idcard-hero {
        grid-template-columns: 1fr;
    }

    .idcard-hero-actions {
        min-height: 220px;
    }

    .idcard-hero-content {
        padding-top: 29px;
        padding-bottom: 29px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .id-card-index .container-fluid {
        padding: 18px 14px !important;
    }

    .idcard-hero {
        margin-bottom: 34px;
        border-radius: 12px;
    }

    .idcard-hero::before {
        left: 18px;
    }

    .idcard-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 23px 15px 23px 32px;
    }

    .idcard-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 18px;
    }

    .idcard-breadcrumb {
        gap: 6px;
        font-size: 8px;
        letter-spacing: .55px;
    }

    .idcard-hero-text h2 {
        font-size: 24px;
    }

    .idcard-hero-text p {
        font-size: 11px;
    }

    .idcard-hero-note {
        align-items: flex-start;
        font-size: 10px;
        line-height: 1.7;
    }

    .idcard-hero-actions {
        min-height: 205px;
        padding: 24px 16px;
    }

    .hero-handwritten {
        font-size: 21px;
    }

    .idcard-kpis {
        gap: 15px;
        margin-top: 31px;
    }

    .idcard-kpi {
        gap: 12px;
        padding: 19px 15px 15px;
        border-radius: 11px;
    }

    .idcard-kpi .kpi-icon {
        flex-basis: 38px;
        width: 38px;
        height: 38px;
        font-size: 15px;
    }

    .kpi-value {
        font-size: 29px;
    }

    .kpi-caption {
        font-size: 9px;
    }

    .idcard-panel-header {
        align-items: flex-start;
        padding: 16px;
    }

    .panel-title-wrap {
        gap: 10px;
    }

    .panel-title-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .panel-title-wrap h3 {
        font-size: 16px;
    }

    .panel-title-wrap p {
        font-size: 10px;
    }

    .idcard-record-count {
        gap: 5px;
        padding: 7px 8px;
        font-size: 9px;
    }

    .idcard-filter-panel {
        padding: 17px 15px;
    }

    .filter-heading {
        align-items: flex-start;
        gap: 10px;
    }

    .filter-heading-icon {
        flex-basis: 36px;
        width: 36px;
        height: 36px;
    }

    .filter-heading h4 {
        font-size: 16px;
    }

    .idcard-filter-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .diary-filter-actions {
        grid-column: auto;
        flex-wrap: wrap;
    }

    .diary-filter-actions > * {
        flex: 1;
    }

    .idcard-pagination {
        align-items: flex-start;
        flex-direction: column;
        padding: 15px;
    }

    .pagination-links {
        max-width: 100%;
        overflow-x: auto;
    }

    .idcard-info-bar {
        align-items: flex-start;
        padding: 16px 15px;
    }

    .idcard-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px 15px;
    }

    .idcard-empty-state {
        padding: 48px 17px;
    }
}

@media (max-width: 400px) {
    .idcard-kpis {
        grid-template-columns: 1fr;
    }

    .idcard-panel-header {
        flex-wrap: wrap;
        gap: 12px;
    }

    .idcard-record-count {
        margin-left: 46px;
    }

    .diary-filter-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .diary-reset-btn,
    .diary-primary-btn {
        width: 100%;
    }

    .hero-button-group {
        flex-direction: column;
        width: 100%;
    }

    .hero-button-group > * {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.id-card-index a:focus-visible,
.id-card-index button:focus-visible,
.id-card-index input:focus-visible,
.id-card-index select:focus-visible {
    outline: 2px solid #648b72;
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .id-card-index *,
    .id-card-index *::before,
    .id-card-index *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection