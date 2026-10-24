@extends('layouts.app')

@section('title', 'Librarians | Admin')
@section('page-title', 'Librarians')

@section('content')

@php
    $totalLibrarians    = $librarians->total();
    $activeLibrarians   = $librarians->getCollection()->where('status', 'Active')->count();
    $inactiveLibrarians = $librarians->getCollection()->where('status', 'Inactive')->count();
@endphp

<div class="librarians-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="librarians-success-alert" role="status">
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
            <div class="librarians-error-alert" role="alert">
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
        <section class="librarians-hero">
            <div class="librarians-hero-content">
                <div class="librarians-hero-icon">
                    <i class="fas fa-book-reader"></i>
                </div>

                <div class="librarians-hero-text">
                    <div class="librarians-breadcrumb">
                        LIBRARY <span>/</span> LIBRARIANS
                    </div>

                    <h2>Librarians</h2>

                    <p>
                        View and manage all librarians who look after
                        the school library.
                    </p>

                    <div class="librarians-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Added through Other Staff</span>
                    </div>
                </div>
            </div>

            <div class="librarians-hero-actions">
                <div class="hero-handwritten">Organise. Assist. Inspire.</div>

                <p>
                    Add a staff member with the designation
                    Librarian to see them here.
                </p>

                <a href="{{ route('admin.other-staff.create') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-plus"></i>
                    Add Librarian
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="librarians-kpis">

            <article class="librarians-kpi blue">
                <div class="kpi-top">
                    <span class="kpi-label">Total Librarians</span>
                    <span class="kpi-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $totalLibrarians }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-layer-group"></i>
                    Registered librarians
                </div>
            </article>

            <article class="librarians-kpi green">
                <div class="kpi-top">
                    <span class="kpi-label">Active Librarians</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-check"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $activeLibrarians }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-check"></i>
                    Currently active
                </div>
            </article>

            <article class="librarians-kpi red">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Librarians</span>
                    <span class="kpi-icon">
                        <i class="fas fa-user-slash"></i>
                    </span>
                </div>

                <div class="kpi-value">{{ $inactiveLibrarians }}</div>

                <div class="kpi-caption">
                    <i class="fas fa-ban"></i>
                    Currently inactive
                </div>
            </article>

        </section>

        {{-- LIBRARIANS TABLE --}}
        <section class="librarians-table-card">

            <div class="librarians-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">STAFF REGISTER</div>
                        <h3>All Librarians</h3>
                        <p>Librarians added through Other Staff.</p>
                    </div>
                </div>

                <div class="librarians-record-count">
                    <i class="fas fa-folder-open"></i>
                    <strong>{{ $totalLibrarians }}</strong>
                    {{ $totalLibrarians == 1 ? 'Librarian' : 'Librarians' }}
                </div>
            </div>

            <div class="librarians-table-wrapper">
                <table class="librarians-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Librarian</th>
                            <th scope="col">Staff ID</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Department</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="actions-heading">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($librarians as $librarian)
                            @php
                                $isActive = $librarian->status === 'Active';
                            @endphp

                            <tr>
                                <td>
                                    <span class="record-number">
                                        {{ $librarians->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td>
                                    <div class="librarian-info">
                                        <div class="librarian-avatar">
                                            @if($librarian->profile_photo)
                                                <img src="{{ asset('storage/' . $librarian->profile_photo) }}"
                                                     alt="{{ $librarian->name }}"
                                                     loading="lazy">
                                            @else
                                                <i class="fas fa-user"></i>
                                            @endif
                                        </div>

                                        <div class="librarian-details">
                                            <div class="librarian-name">
                                                {{ $librarian->name }}
                                            </div>
                                            <div class="librarian-description">
                                                {{ $librarian->designation ?: 'Librarian' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="count-badge">
                                        {{ $librarian->staff_id }}
                                    </span>
                                </td>

                                <td>
                                    <div class="text-value {{ $librarian->phone ? '' : 'muted' }}">
                                        {{ $librarian->phone ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="text-value {{ $librarian->department ? '' : 'muted' }}">
                                        {{ $librarian->department ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="librarians-actions">
                                        <a href="{{ route('admin.library.librarian.show', $librarian) }}"
                                           class="diary-primary-btn compact-btn"
                                           aria-label="View profile of {{ $librarian->name }}">
                                            <i class="fas fa-eye"></i>
                                            View Profile
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="librarians-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>

                                        <h4>No Librarians Found</h4>

                                        <p>
                                            No staff member has been added with the
                                            designation <strong>Librarian</strong>.
                                        </p>

                                        <div class="empty-state-actions">
                                            <a href="{{ route('admin.other-staff.create') }}"
                                               class="diary-primary-btn">
                                                <i class="fas fa-plus"></i>
                                                Add Librarian
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
            <div class="librarians-card-footer">
                <div>
                    Showing
                    <strong>{{ $librarians->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $librarians->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ $librarians->total() }}</strong>
                    librarian(s)
                </div>

                @if($librarians->hasPages())
                    <div class="librarians-pagination">
                        {{ $librarians->withQueryString()->links() }}
                    </div>
                @else
                    <div class="footer-status">
                        <i class="fas fa-circle-check"></i>
                        Staff register loaded successfully
                    </div>
                @endif
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY LIBRARIANS — INDEX PAGE
   Same tokens, cards, buttons and layout as Roles / Notices / Library.
   Every selector is scoped under .librarians-page.
========================================================= */

.librarians-page {
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

.librarians-page *,
.librarians-page *::before,
.librarians-page *::after {
    box-sizing: border-box;
}

.librarians-page a,
.librarians-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.librarians-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.librarians-page .librarians-hero,
.librarians-page .librarians-table-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.librarians-page .librarians-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.librarians-page .librarians-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.librarians-page .librarians-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.librarians-page .librarians-hero-icon {
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

.librarians-page .librarians-hero-text {
    min-width: 0;
}

.librarians-page .librarians-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.librarians-page .librarians-breadcrumb span {
    color: #b8b09e;
}

.librarians-page .librarians-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.librarians-page .librarians-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.librarians-page .librarians-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.librarians-page .librarians-hero-actions {
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

.librarians-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.librarians-page .librarians-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.librarians-page .diary-primary-btn {
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

.librarians-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.librarians-page .diary-primary-btn.compact-btn {
    min-height: 34px;
    padding: 7px 12px;
    font-size: 11px;
}

.librarians-page .librarians-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.librarians-page .librarians-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* ALERTS */

.librarians-page .librarians-success-alert,
.librarians-page .librarians-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.librarians-page .librarians-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.librarians-page .librarians-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.librarians-page .success-icon,
.librarians-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.librarians-page .success-icon { background: #dcefd9; }
.librarians-page .error-icon { background: #fbe0d8; }

.librarians-page .alert-close {
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

.librarians-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* KPI CARDS */

.librarians-page .librarians-kpis {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.librarians-page .librarians-kpi {
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

.librarians-page .librarians-kpi::before {
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

.librarians-page .librarians-kpi.blue  { --kpi-color: #2b5d8a; }
.librarians-page .librarians-kpi.green { --kpi-color: #3a8060; }
.librarians-page .librarians-kpi.red   { --kpi-color: #bd5148; }

.librarians-page .librarians-kpi.blue::before  { content: "Total"; }
.librarians-page .librarians-kpi.green::before { content: "Active"; }
.librarians-page .librarians-kpi.red::before   { content: "Inactive"; }

.librarians-page .librarians-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.librarians-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.librarians-page .kpi-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    line-height: 1.5;
}

.librarians-page .librarians-kpi .kpi-icon {
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

.librarians-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: 35px;
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
    overflow-wrap: anywhere;
}

.librarians-page .kpi-caption {
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

.librarians-page .kpi-caption i {
    flex: 0 0 auto;
    color: var(--kpi-color);
}

/* RECORDS PANEL */

.librarians-page .librarians-table-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.librarians-page .librarians-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.librarians-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.librarians-page .panel-title-icon {
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

.librarians-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.librarians-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.librarians-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.librarians-page .librarians-record-count {
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

.librarians-page .librarians-record-count i,
.librarians-page .librarians-record-count strong {
    color: var(--ink);
}

/* TABLE */

.librarians-page .librarians-table-wrapper {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.librarians-page .librarians-table {
    width: 100%;
    min-width: 950px;
    margin: 0;
    border-collapse: collapse;
}

.librarians-page .librarians-table thead th {
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

.librarians-page .librarians-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    color: var(--text);
    font-size: 12px;
    vertical-align: middle;
}

.librarians-page .librarians-table tbody tr {
    transition: background .18s ease;
}

.librarians-page .librarians-table tbody tr:hover {
    background: #faf9f2;
}

.librarians-page .librarians-table tbody tr:last-child td {
    border-bottom: none;
}

.librarians-page .librarians-table .actions-heading {
    text-align: right;
}

.librarians-page .record-number {
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

/* LIBRARIAN CELL */

.librarians-page .librarian-info {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 240px;
}

.librarians-page .librarian-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    border-radius: 50%;
    color: var(--ink);
    background: var(--chalk);
    font-size: 16px;
}

.librarians-page .librarian-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.librarians-page .librarian-details {
    min-width: 0;
}

.librarians-page .librarian-name {
    max-width: 240px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.librarians-page .librarian-description {
    max-width: 260px;
    margin-top: 4px;
    color: #858e82;
    font-size: 10px;
    line-height: 1.4;
}

/* OTHER CELLS */

.librarians-page .count-badge {
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
    white-space: nowrap;
}

.librarians-page .text-value {
    color: var(--ink);
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}

.librarians-page .text-value.muted {
    color: var(--muted);
    font-weight: 600;
}

/* STATUS BADGES */

.librarians-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.librarians-page .status-dot {
    width: 6px;
    height: 6px;
    flex: 0 0 6px;
    border-radius: 50%;
}

.librarians-page .status-badge.active {
    border: 1px solid #cfe5d3;
    color: #347849;
    background: #edf7ed;
}

.librarians-page .status-badge.active .status-dot {
    background: #3a8060;
}

.librarians-page .status-badge.inactive {
    border: 1px solid #f0d5ce;
    color: #bd5148;
    background: #fff1ed;
}

.librarians-page .status-badge.inactive .status-dot {
    background: #bd5148;
}

/* ACTIONS */

.librarians-page .librarians-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}

/* EMPTY STATE */

.librarians-page .librarians-empty-state {
    padding: 60px 20px;
    text-align: center;
}

.librarians-page .empty-state-icon {
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

.librarians-page .librarians-empty-state h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.librarians-page .librarians-empty-state p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.librarians-page .librarians-empty-state p strong {
    color: var(--ink);
}

.librarians-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 9px;
}

/* FOOTER */

.librarians-page .librarians-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    min-height: 53px;
    padding: 12px 20px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
}

.librarians-page .librarians-card-footer strong {
    color: var(--ink);
}

.librarians-page .footer-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #68766d;
    font-size: 10px;
}

.librarians-page .footer-status i {
    color: var(--green);
}

/* PAGINATION (Laravel / Bootstrap) */

.librarians-page .librarians-pagination nav > div:first-child {
    display: none;
}

.librarians-page .librarians-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    margin: 0;
}

.librarians-page .librarians-pagination .page-link,
.librarians-page .librarians-pagination .pagination span,
.librarians-page .librarians-pagination .pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 9px;
    border: 1px solid #dedfd4;
    border-radius: 7px;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none !important;
}

.librarians-page .librarians-pagination .page-item.active .page-link,
.librarians-page .librarians-pagination .pagination [aria-current="page"] span {
    border-color: var(--ink);
    color: #fff;
    background: var(--ink);
}

.librarians-page .librarians-pagination .page-item.disabled .page-link {
    opacity: .5;
}

.librarians-page .librarians-pagination .page-link:hover {
    border-color: var(--ink-hover);
    color: var(--ink);
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 1100px) {
    .librarians-page .librarians-kpis {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 900px) {
    .librarians-page .librarians-hero {
        grid-template-columns: 1fr;
    }

    .librarians-page .librarians-hero-actions {
        min-height: 165px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .librarians-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .librarians-page .librarians-hero::before {
        left: 22px;
    }

    .librarians-page .librarians-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .librarians-page .librarians-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .librarians-page .librarians-hero-text h2 {
        font-size: 23px;
    }

    .librarians-page .librarians-hero-text p {
        font-size: 12px;
    }

    .librarians-page .librarians-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .librarians-page .librarians-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .librarians-page .librarians-kpis {
        grid-template-columns: 1fr;
        gap: 15px;
        margin-top: 30px;
    }

    .librarians-page .librarians-kpi {
        padding: 20px 16px 15px;
    }

    .librarians-page .kpi-value {
        font-size: 30px;
    }

    .librarians-page .librarians-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .librarians-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .librarians-page .librarians-record-count {
        gap: 5px;
        padding: 5px 7px;
    }

    .librarians-page .librarians-card-footer {
        align-items: flex-start;
        flex-direction: column;
        padding: 13px 15px;
    }
}

@media (max-width: 400px) {
    .librarians-page .librarians-panel-header {
        flex-wrap: wrap;
        gap: 8px;
    }

    .librarians-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .librarians-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .librarians-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.librarians-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .librarians-page *,
    .librarians-page *::before,
    .librarians-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection