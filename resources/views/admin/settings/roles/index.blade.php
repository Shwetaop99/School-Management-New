@extends('layouts.app')

@section('title', 'User Roles & Permission | Admin')
@section('page-title', 'User Roles & Permission')

@section('content')

@php
    $rolesCollection = $roles ?? collect();
    $totalRoles = $rolesCollection->count();
    $activeRoles = $rolesCollection->where('status', 'Active')->count();
    $inactiveRoles = $rolesCollection->where('status', 'Inactive')->count();
@endphp

<div class="roles-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="roles-success-alert" role="status">
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
            <div class="roles-error-alert" role="alert">
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
        <section class="roles-hero">
            <div class="roles-hero-content">
                <div class="roles-hero-icon">
                    <i class="fas fa-user-shield"></i>
                </div>

                <div class="roles-hero-text">
                    <div class="roles-breadcrumb">
                        SETTINGS <span>/</span> ROLES
                    </div>

                    <h2>User Roles & Permission</h2>

                    <p>
                        Create roles and control access to school management
                        modules from one central place.
                    </p>

                    <div class="roles-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Access Control Management</span>
                    </div>
                </div>
            </div>

            <div class="roles-hero-actions">
                <div class="hero-handwritten">Access. Control. Accountability.</div>

                <p>
                    Define roles and assign permissions
                    to keep the system secure.
                </p>

                <a href="{{ route('admin.settings.roles.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Role
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="roles-kpis">

            <article class="roles-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Roles</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-shield"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalRoles }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Registered roles
                </div>
            </article>

            <article class="roles-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Roles</span>
                    <span class="kpi-icon">
                        <i class="fas fa-circle-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $activeRoles }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Currently active roles
                </div>
            </article>

            <article class="roles-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Roles</span>
                    <span class="kpi-icon">
                        <i class="fas fa-ban"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $inactiveRoles }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-user-slash"></i>
                    Currently inactive roles
                </div>
            </article>

        </section>

        {{-- ROLES TABLE --}}
        <section class="roles-table-card">

            <div class="roles-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">ROLE REGISTER</div>
                        <h3>All Roles</h3>
                        <p>Review, edit or remove user roles and permissions.</p>
                    </div>
                </div>

                <div class="roles-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalRoles }}</strong>
                    {{ $totalRoles == 1 ? 'Role' : 'Roles' }}
                </div>
            </div>

            <div class="roles-table-wrapper">
                <table class="roles-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Role</th>
                            <th>Users</th>
                            <th>Permissions</th>
                            <th>Status</th>
                            <th class="actions-heading">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($rolesCollection as $role)
                            @php
                                $isActive = $role->status === 'Active';
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>

                                <td>
                                    <div class="role-info">
                                        <div class="role-mini-icon">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        <div class="role-details">
                                            <div class="role-name">
                                                {{ $role->display_name }}
                                            </div>
                                            <div class="role-description">
                                                {{ $role->description ?? 'No description added.' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $role->users_count ?? 0 }}
                                    </span>
                                </td>

                                <td>
                                    <div class="permission-value">
                                        <strong>{{ $role->permissions->count() ?? 0 }}</strong>
                                        <span>permissions</span>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="roles-actions">

                                        <a href="{{ route('admin.settings.roles.edit', $role) }}"
                                           class="game-action-btn edit"
                                           title="Edit Role"
                                           aria-label="Edit {{ $role->display_name }}">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        @if($role->name !== 'super_admin')
                                            <form method="POST"
                                                  action="{{ route('admin.settings.roles.destroy', $role) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="game-action-btn delete"
                                                        title="Delete Role"
                                                        aria-label="Delete {{ $role->display_name }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="roles-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-user-shield"></i>
                                        </div>

                                        <h4>No Roles Found</h4>

                                        <p>
                                            Create your first user role to manage permissions.
                                        </p>

                                        <div class="empty-state-actions">
                                            <a href="{{ route('admin.settings.roles.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add Role
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            @if($totalRoles > 0)
                <div class="roles-card-footer">
                    <div>
                        Showing
                        <strong>{{ $rolesCollection->count() }}</strong>
                        role(s)
                    </div>

                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Role register loaded successfully
                    </div>
                </div>
            @endif

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   USER ROLES & PERMISSION — INDEX PAGE
   Same tokens, cards, buttons and layout as Users / Meal Items / etc.
   Every selector is scoped under .roles-page.
========================================================= */

.roles-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --blue: #2b5d8a;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    --cyan: #527f79;
    --serif: Georgia, "Times New Roman", serif;
    --hand: "Segoe Print", "Comic Sans MS", cursive;

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.roles-page *,
.roles-page *::before,
.roles-page *::after {
    box-sizing: border-box;
}

.roles-page a,
.roles-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.roles-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.roles-page .roles-hero,
.roles-page .roles-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.roles-page .roles-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.roles-page .roles-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.roles-page .roles-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.roles-page .roles-hero-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 54px;
    width: 54px;
    height: 54px;
    border: 1px solid #e6dfce;
    border-radius: 12px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 22px;
}

.roles-page .roles-hero-text {
    min-width: 0;
}

.roles-page .roles-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.roles-page .roles-breadcrumb span {
    color: #b8b09e;
}

.roles-page .roles-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.roles-page .roles-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.roles-page .roles-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.roles-page .roles-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 25px;
    color: #e9efe2;
    background:
        radial-gradient(ellipse at 20% 0, rgba(255,255,255,.09), transparent 60%),
        linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.roles-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.roles-page .roles-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.roles-page .diary-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid var(--ink);
    border-radius: 7px;
    color: #fff !important;
    background: var(--ink);
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.roles-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.roles-page .roles-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.roles-page .roles-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* ALERTS */

.roles-page .roles-success-alert,
.roles-page .roles-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.roles-page .roles-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.roles-page .roles-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.roles-page .success-icon,
.roles-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.roles-page .success-icon { background: #dcefd9; }
.roles-page .error-icon { background: #fbe0d8; }

.roles-page .alert-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 30px;
    width: 30px;
    height: 30px;
    margin-left: auto;
    border: 0;
    border-radius: 6px;
    color: #68746a;
    background: transparent;
    cursor: pointer;
}

.roles-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.roles-page .roles-kpis {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.roles-page .roles-kpi {
    --kpi-color: var(--blue);

    position: relative;
    display: flex;
    flex-direction: column;
    gap: 14px;
    min-width: 0;
    padding: 22px 20px 16px;
    border: 1px solid var(--line);
    border-top: 4px solid var(--kpi-color);
    border-radius: 9px;
    background: var(--paper);
    box-shadow: 0 4px 13px rgba(38, 59, 53, .045);
    transition: transform .2s ease, box-shadow .2s ease;
}

.roles-page .roles-kpi::before {
    position: absolute;
    top: -25px;
    left: 15px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .25px;
}

.roles-page .roles-kpi.blue  { --kpi-color: #2b5d8a; }
.roles-page .roles-kpi.green { --kpi-color: #3a8060; }
.roles-page .roles-kpi.red   { --kpi-color: #bd5148; }

.roles-page .roles-kpi.blue::before  { content: "Total"; }
.roles-page .roles-kpi.green::before { content: "Active"; }
.roles-page .roles-kpi.red::before   { content: "Inactive"; }

.roles-page .roles-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.roles-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.roles-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.roles-page .roles-kpi .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white);
    border-radius: 10px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    font-size: 17px;
}

.roles-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.roles-page .kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    min-height: 35px;
    padding-top: 11px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.roles-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.roles-page .roles-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.roles-page .roles-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.roles-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.roles-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.roles-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.roles-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.roles-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.roles-page .roles-record-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.roles-page .roles-record-count i,
.roles-page .roles-record-count strong {
    color: var(--ink);
}

/* TABLE */

.roles-page .roles-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.roles-page .roles-table {
    width: 100%;
    min-width: 850px;
    margin: 0;
    border-collapse: collapse;
}

.roles-page .roles-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: .45px;
    white-space: nowrap;
}

.roles-page .roles-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.roles-page .roles-table tbody tr {
    transition: background .18s ease;
}

.roles-page .roles-table tbody tr:hover {
    background: #faf9f2;
}

.roles-page .roles-table tbody tr:last-child td {
    border-bottom: none;
}

.roles-page .roles-table .actions-heading {
    text-align: right;
}

.roles-page .record-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 31px;
    height: 31px;
    border: 1px solid #e7e4d8;
    border-radius: 7px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

/* ROLE CELL */

.roles-page .role-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 240px;
}

.roles-page .role-mini-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 40px;
    width: 40px;
    height: 40px;
    border: 1px solid #e6dfce;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 15px;
}

.roles-page .role-details {
    min-width: 0;
}

.roles-page .role-name {
    max-width: 260px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.roles-page .role-description {
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
    max-width: 280px;
}

/* OTHER CELLS */

.roles-page .count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 28px;
    padding: 0 9px;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    color: var(--blue);
    background: #f0f5f8;
    font-size: 12px;
    font-weight: 800;
}

.roles-page .permission-value {
    display: flex;
    align-items: baseline;
    gap: 5px;
}

.roles-page .permission-value strong {
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

.roles-page .permission-value span {
    color: var(--muted);
    font-size: 10px;
}

/* STATUS BADGES */

.roles-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.roles-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.roles-page .status-badge.active {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.roles-page .status-badge.active .status-dot {
    background: #3a8060;
}

.roles-page .status-badge.inactive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.roles-page .status-badge.inactive .status-dot {
    background: #bd5148;
}

/* ACTION BUTTONS */

.roles-page .roles-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

.roles-page .roles-actions form {
    display: inline-flex;
    margin: 0;
}

.roles-page .game-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    padding: 0;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    text-decoration: none !important;
    cursor: pointer;
}

.roles-page .game-action-btn:hover {
    transform: translateY(-2px);
}

.roles-page .game-action-btn.edit:hover {
    border-color: #e6d0a8;
    color: var(--amber);
    background: #fcf7e9;
}

.roles-page .game-action-btn.delete:hover {
    border-color: #f0c9c2;
    color: var(--red);
    background: #fff1ed;
}

/* EMPTY STATE */

.roles-page .roles-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.roles-page .empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    border: 1px solid #e5dfcc;
    border-radius: 14px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 27px;
}

.roles-page .roles-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.roles-page .roles-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.roles-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.roles-page .roles-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.roles-page .roles-card-footer strong {
    color: var(--ink);
}

.roles-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.roles-page .footer-status i {
    color: var(--green);
}

/* TABLET */

@media (max-width: 1100px) {
    .roles-page .roles-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .roles-page .roles-hero {
        grid-template-columns: 1fr;
    }

    .roles-page .roles-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .roles-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .roles-page .roles-hero::before {
        left: 22px;
    }

    .roles-page .roles-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .roles-page .roles-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .roles-page .roles-hero-text h2 {
        font-size: 23px;
    }

    .roles-page .roles-hero-text p {
        font-size: 12px;
    }

    .roles-page .roles-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .roles-page .roles-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .roles-page .roles-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .roles-page .roles-kpi {
        padding: 20px 16px 15px;
    }

    .roles-page .kpi-value {
        font-size: 30px;
    }

    .roles-page .roles-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .roles-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .roles-page .roles-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .roles-page .roles-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .roles-page .roles-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .roles-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .roles-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .roles-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.roles-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .roles-page *,
    .roles-page *::before,
    .roles-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection