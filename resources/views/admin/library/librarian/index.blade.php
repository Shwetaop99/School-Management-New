@extends('layouts.app')

@section('title', 'Librarians | Admin')
@section('page-title', 'Librarians')

@section('content')

@php
    $totalLibrarians = $librarians->total();

    // Pass $activeLibrariansTotal / $inactiveLibrariansTotal from the controller for counts across
    // all pages. Without them these two cards count only the librarians on the current page.
    $pageItems          = $librarians->getCollection();
    $activeLibrarians   = $activeLibrariansTotal   ?? $pageItems->where('status', 'Active')->count();
    $inactiveLibrarians = $inactiveLibrariansTotal ?? $pageItems->where('status', 'Inactive')->count();
@endphp

<div class="librarians-page">
    <div class="lib-container">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="lib-alert success" role="status">
                <span class="alert-icon"><i class="bi bi-check-lg"></i></span>
                <span class="alert-text">{{ session('success') }}</span>
                <button type="button" class="alert-close" aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="lib-alert error" role="alert">
                <span class="alert-icon"><i class="bi bi-exclamation-lg"></i></span>
                <span class="alert-text">{{ session('error') }}</span>
                <button type="button" class="alert-close" aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        {{-- HERO --}}
        <section class="lib-hero">
            <div class="lib-hero-content">
                <div class="lib-hero-icon"><i class="bi bi-book-half"></i></div>

                <div class="lib-hero-text">
                    <div class="lib-breadcrumb">LIBRARY <span>/</span> LIBRARIANS</div>

                    <h2>Librarians</h2>

                    <p>View and manage all librarians who look after the school library.</p>

                    <div class="lib-hero-note">
                        <i class="bi bi-shield-check"></i>
                        <span>Added through Other Staff</span>
                    </div>
                </div>
            </div>

            <div class="lib-hero-actions">
                <div class="lib-handwritten">Organise. Assist. Inspire.</div>

                <p>Add a staff member with the designation Librarian to see them here.</p>

                <a href="{{ route('admin.other-staff.create') }}" class="lib-btn lib-btn-light">
                    <i class="bi bi-plus-lg"></i>
                    Add Librarian
                </a>
            </div>
        </section>

        {{-- KPI CARDS --}}
        <section class="lib-kpis">

            <article class="lib-kpi blue" data-tab="TOTAL">
                <div class="kpi-top">
                    <span class="kpi-label">Total Librarians</span>
                    <span class="kpi-icon"><i class="bi bi-people"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($totalLibrarians) }}</div>
                <div class="kpi-caption"><i class="bi bi-layers"></i> Registered librarians</div>
            </article>

            <article class="lib-kpi green" data-tab="ACTIVE">
                <div class="kpi-top">
                    <span class="kpi-label">Active Librarians</span>
                    <span class="kpi-icon"><i class="bi bi-person-check"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($activeLibrarians) }}</div>
                <div class="kpi-caption"><i class="bi bi-check-lg"></i> Currently active</div>
            </article>

            <article class="lib-kpi red" data-tab="INACTIVE">
                <div class="kpi-top">
                    <span class="kpi-label">Inactive Librarians</span>
                    <span class="kpi-icon"><i class="bi bi-person-slash"></i></span>
                </div>
                <div class="kpi-value">{{ number_format($inactiveLibrarians) }}</div>
                <div class="kpi-caption"><i class="bi bi-slash-circle"></i> Currently inactive</div>
            </article>

        </section>

        {{-- LIBRARIANS TABLE --}}
        <section class="lib-card">

            <div class="lib-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon"><i class="bi bi-list-ul"></i></div>

                    <div>
                        <div class="section-eyebrow">STAFF REGISTER</div>
                        <h3>All Librarians</h3>
                        <p>Librarians added through Other Staff.</p>
                    </div>
                </div>

                <div class="lib-record-count">
                    <i class="bi bi-folder2-open"></i>
                    <strong>{{ number_format($totalLibrarians) }}</strong>
                    {{ $totalLibrarians == 1 ? 'Librarian' : 'Librarians' }}
                </div>
            </div>

            <div class="lib-table-wrap">
                <table class="lib-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Librarian</th>
                            <th scope="col">Staff ID</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Department</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($librarians as $librarian)
                            @php $isActive = $librarian->status === 'Active'; @endphp

                            <tr>
                                <td data-label="#" class="cell-no">
                                    <span class="record-number">
                                        {{ $librarians->firstItem() + $loop->index }}
                                    </span>
                                </td>

                                <td data-label="Librarian" class="cell-person">
                                    <div class="lib-person">
                                        <div class="lib-avatar">
                                            <i class="bi bi-person-fill"></i>
                                            @if($librarian->profile_photo)
                                                <img src="{{ asset('storage/' . $librarian->profile_photo) }}"
                                                     alt="{{ $librarian->name }}"
                                                     loading="lazy"
                                                     onerror="this.remove();">
                                            @endif
                                        </div>

                                        <div class="lib-person-details">
                                            <div class="lib-name">{{ $librarian->name }}</div>
                                            <div class="lib-sub">{{ $librarian->designation ?: 'Librarian' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td data-label="Staff ID">
                                    <span class="id-badge">{{ $librarian->staff_id }}</span>
                                </td>

                                <td data-label="Phone">
                                    <span class="text-value {{ $librarian->phone ? '' : 'muted' }}">
                                        {{ $librarian->phone ?: '—' }}
                                    </span>
                                </td>

                                <td data-label="Department">
                                    <span class="text-value {{ $librarian->department ? '' : 'muted' }}">
                                        {{ $librarian->department ?: '—' }}
                                    </span>
                                </td>

                                <td data-label="Status">
                                    <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $isActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td data-label="Action" class="cell-actions">
                                    <div class="lib-actions">
                                        <a href="{{ route('admin.library.librarian.show', $librarian) }}"
                                           class="lib-btn lib-btn-dark lib-btn-sm"
                                           aria-label="View profile of {{ $librarian->name }}">
                                            <i class="bi bi-eye"></i>
                                            View Profile
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-row">
                                <td colspan="7">
                                    <div class="lib-empty">
                                        <div class="empty-icon"><i class="bi bi-person-badge"></i></div>

                                        <h4>No Librarians Found</h4>

                                        <p>
                                            No staff member has been added with the
                                            designation <strong>Librarian</strong>.
                                        </p>

                                        <a href="{{ route('admin.other-staff.create') }}" class="lib-btn lib-btn-dark">
                                            <i class="bi bi-plus-lg"></i>
                                            Add Librarian
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER --}}
            <div class="lib-card-footer">
                <div>
                    Showing
                    <strong>{{ $librarians->firstItem() ?? 0 }}</strong>
                    to
                    <strong>{{ $librarians->lastItem() ?? 0 }}</strong>
                    of
                    <strong>{{ number_format($librarians->total()) }}</strong>
                    librarian(s)
                </div>

                @if($librarians->hasPages())
                    <div class="lib-pagination">
                        {{ $librarians->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="footer-status">
                        <i class="bi bi-check-circle-fill"></i>
                        Staff register loaded successfully
                    </div>
                @endif
            </div>

        </section>

    </div>
</div>

<style>
.librarians-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --chalk: #f4f0df;
    --amber: #a87543;
    --green: #3a8060;
    --serif: var(--serif-font, Georgia, "Times New Roman", serif);
    --hand: var(--hand-font, "Caveat", "Segoe Print", cursive);

    min-height: calc(100vh - 70px);
    color: var(--text);
    background: #f7f6f0;
    font-family: "Public Sans", Inter, Arial, sans-serif;
    font-size: 13px;
}

.librarians-page *,
.librarians-page *::before,
.librarians-page *::after { box-sizing: border-box; }

.librarians-page a,
.librarians-page button {
    transition: color .18s ease, background .18s ease, border-color .18s ease,
                transform .18s ease, box-shadow .18s ease;
}

.librarians-page .lib-container {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
    padding: 24px;
}

.librarians-page .lib-hero,
.librarians-page .lib-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */
.librarians-page .lib-hero {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.librarians-page .lib-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.librarians-page .lib-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background: radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%), var(--paper);
}

.librarians-page .lib-hero-icon {
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

.librarians-page .lib-hero-text { min-width: 0; }

.librarians-page .lib-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.librarians-page .lib-breadcrumb span { color: #b8b09e; }

.librarians-page .lib-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: clamp(24px, 2.2vw, 29px);
    font-weight: 700;
    line-height: 1.2;
}

.librarians-page .lib-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.librarians-page .lib-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.librarians-page .lib-hero-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-height: 245px;
    padding: 25px;
    color: #e9efe2;
    background: radial-gradient(ellipse at 20% 0, rgba(255, 255, 255, .09), transparent 60%),
                linear-gradient(135deg, #344f43 0%, #20352c 100%);
    text-align: center;
}

.librarians-page .lib-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.librarians-page .lib-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */
.librarians-page .lib-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
}

.librarians-page .lib-btn-dark { border-color: var(--ink); color: #fff; background: var(--ink); }
.librarians-page .lib-btn-dark:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.librarians-page .lib-btn-light { border-color: #f1ead8; color: var(--ink); background: #f1ead8; }
.librarians-page .lib-btn-light:hover {
    transform: translateY(-2px);
    border-color: #fff8e8;
    color: var(--ink-dark);
    background: #fff8e8;
}

.librarians-page .lib-btn-sm { min-height: 34px; padding: 7px 12px; font-size: 11px; }

/* ALERTS */
.librarians-page .lib-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.librarians-page .lib-alert.success { border: 1px solid #cfe3d1; color: #347849; background: #f1f8ef; }
.librarians-page .lib-alert.error   { border: 1px solid #f0d5ce; color: #a84840; background: #fff4ef; }

.librarians-page .alert-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.librarians-page .lib-alert.success .alert-icon { background: #dcefd9; }
.librarians-page .lib-alert.error .alert-icon   { background: #fbe0d8; }

.librarians-page .alert-text { flex: 1; min-width: 0; overflow-wrap: anywhere; }

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

.librarians-page .alert-close:hover { color: var(--ink); background: rgba(0, 0, 0, .05); }

/* KPI CARDS */
.librarians-page .lib-kpis {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
    margin: 34px 0 25px;
}

.librarians-page .lib-kpi {
    --kpi-color: #2b5d8a;
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
}

.librarians-page .lib-kpi.blue  { --kpi-color: #2b5d8a; }
.librarians-page .lib-kpi.green { --kpi-color: #3a8060; }
.librarians-page .lib-kpi.red   { --kpi-color: #bd5148; }

.librarians-page .lib-kpi::before {
    content: attr(data-tab);
    position: absolute;
    top: -25px;
    left: 15px;
    padding: 5px 12px 4px;
    border-radius: 6px 6px 0 0;
    color: #fff;
    background: var(--kpi-color);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .4px;
}

.librarians-page .lib-kpi:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 22px rgba(38, 59, 53, .09);
}

.librarians-page .kpi-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

.librarians-page .kpi-label { color: var(--muted); font-size: 11px; font-weight: 700; }

.librarians-page .kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 43px;
    width: 43px;
    height: 43px;
    border-radius: 10px;
    color: var(--kpi-color);
    background: color-mix(in srgb, var(--kpi-color) 10%, white);
    border: 1px solid color-mix(in srgb, var(--kpi-color) 14%, white);
    font-size: 17px;
}

.librarians-page .kpi-value {
    color: var(--ink);
    font-family: var(--serif);
    font-size: clamp(28px, 2.4vw, 35px);
    font-weight: 700;
    line-height: 1.1;
    font-variant-numeric: tabular-nums;
}

.librarians-page .kpi-caption {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 11px;
    border-top: 1px dashed #d8d1bf;
    color: var(--muted);
    font-size: 11px;
}

.librarians-page .kpi-caption i { color: var(--kpi-color); }

/* RECORDS CARD */
.librarians-page .lib-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.librarians-page .lib-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
}

.librarians-page .panel-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; }

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
    font-size: 15px;
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

.librarians-page .panel-title-wrap p { margin: 5px 0 0; color: var(--muted); font-size: 11px; line-height: 1.6; }

.librarians-page .lib-record-count {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    min-height: 31px;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.librarians-page .lib-record-count i,
.librarians-page .lib-record-count strong { color: var(--ink); }

/* TABLE */
.librarians-page .lib-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

.librarians-page .lib-table { width: 100%; min-width: 900px; margin: 0; border-collapse: collapse; }

.librarians-page .lib-table thead th {
    padding: 13px 14px;
    border-bottom: 1px solid #e5e3d8;
    color: #727a70;
    background: #f5f3e9;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .45px;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.librarians-page .lib-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #eeece3;
    font-size: 12px;
    vertical-align: middle;
}

.librarians-page .lib-table tbody tr:hover td { background: #faf9f2; }
.librarians-page .lib-table tbody tr:last-child td { border-bottom: 0; }
.librarians-page .text-end { text-align: right !important; }

.librarians-page .record-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 31px;
    height: 31px;
    padding: 0 6px;
    border: 1px solid #e7e4d8;
    border-radius: 7px;
    color: #667269;
    background: #f5f3e9;
    font-size: 11px;
    font-weight: 800;
}

.librarians-page .lib-person { display: flex; align-items: center; gap: 12px; min-width: 230px; }

.librarians-page .lib-avatar {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    border-radius: 50%;
    color: #9aa398;
    background: var(--chalk);
    font-size: 18px;
}

.librarians-page .lib-avatar img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.librarians-page .lib-person-details { min-width: 0; }

.librarians-page .lib-name {
    max-width: 240px;
    overflow: hidden;
    color: var(--ink);
    font-size: 12.5px;
    font-weight: 800;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.librarians-page .lib-sub { margin-top: 3px; color: #858e82; font-size: 10.5px; }

.librarians-page .id-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 28px;
    padding: 0 10px;
    border: 1px solid #d7e2e9;
    border-radius: 7px;
    color: #2b5d8a;
    background: #f0f5f8;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.librarians-page .text-value { color: var(--ink); font-size: 12px; font-weight: 700; white-space: nowrap; }
.librarians-page .text-value.muted { color: var(--muted); font-weight: 600; }

.librarians-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border: 1px solid transparent;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 800;
    white-space: nowrap;
}

.librarians-page .status-dot { flex: 0 0 6px; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
.librarians-page .status-badge.active   { border-color: #cfe5d3; color: #347849; background: #edf7ed; }
.librarians-page .status-badge.inactive { border-color: #f0d5ce; color: #bd5148; background: #fff1ed; }

.librarians-page .lib-actions { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }

/* EMPTY STATE */
.librarians-page .lib-empty { padding: 56px 20px; text-align: center; }

.librarians-page .empty-icon {
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

.librarians-page .lib-empty h4 {
    margin: 0 0 8px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 21px;
    font-weight: 700;
}

.librarians-page .lib-empty p {
    max-width: 450px;
    margin: 0 auto 18px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.8;
}

.librarians-page .lib-empty p strong { color: var(--ink); }

/* FOOTER + PAGINATION */
.librarians-page .lib-card-footer {
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

.librarians-page .lib-card-footer strong { color: var(--ink); }

.librarians-page .footer-status { display: inline-flex; align-items: center; gap: 6px; color: #68766d; font-size: 10.5px; }
.librarians-page .footer-status i { color: var(--green); }

.librarians-page .lib-pagination nav > div:first-child { display: none; }
.librarians-page .lib-pagination nav { margin: 0; }
.librarians-page .lib-pagination .pagination { display: flex; flex-wrap: wrap; gap: 5px; margin: 0; padding: 0; list-style: none; }

.librarians-page .lib-pagination .page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 9px;
    border: 1px solid #dedfd4;
    border-radius: 7px !important;
    color: #68746a;
    background: #fffefa;
    font-size: 11px;
    font-weight: 700;
    box-shadow: none;
    text-decoration: none;
}

.librarians-page .lib-pagination .page-link:hover { border-color: var(--ink-hover); color: var(--ink); background: #faf9f4; }
.librarians-page .lib-pagination .page-item.active .page-link { border-color: var(--ink); color: #fff; background: var(--ink); }
.librarians-page .lib-pagination .page-item.disabled .page-link { opacity: .5; }

/* RESPONSIVE */
@media (max-width: 1100px) {
    .librarians-page .lib-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); row-gap: 34px; }
}

@media (max-width: 900px) {
    .librarians-page .lib-hero { grid-template-columns: 1fr; }
    .librarians-page .lib-hero-actions { min-height: 165px; }
}

@media (max-width: 700px) {
    .librarians-page .lib-container { padding: 16px 12px; }

    .librarians-page .lib-hero::before { left: 22px; }

    .librarians-page .lib-hero-content { align-items: flex-start; gap: 12px; padding: 22px 16px 22px 42px; }

    .librarians-page .lib-hero-icon { flex-basis: 43px; width: 43px; height: 43px; border-radius: 9px; font-size: 18px; }
    .librarians-page .lib-hero-text p { font-size: 12px; }
    .librarians-page .lib-hero-actions { min-height: 155px; padding: 20px; }

    .librarians-page .lib-kpis { grid-template-columns: 1fr; gap: 32px; margin-top: 30px; }

    .librarians-page .lib-panel-header { align-items: flex-start; flex-wrap: wrap; padding: 15px; }
    .librarians-page .panel-title-wrap h3 { font-size: 16px; }

    /* table -> cards */
    .librarians-page .lib-table-wrap { overflow: visible; padding: 12px; background: #faf9f4; }

    .librarians-page .lib-table,
    .librarians-page .lib-table tbody,
    .librarians-page .lib-table tr,
    .librarians-page .lib-table td { display: block; width: 100%; min-width: 0; }

    .librarians-page .lib-table thead { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); }

    .librarians-page .lib-table tbody tr {
        margin-bottom: 12px;
        overflow: hidden;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: var(--paper);
    }

    .librarians-page .lib-table tbody tr:last-child { margin-bottom: 0; }
    .librarians-page .lib-table tbody tr:hover td { background: transparent; }

    .librarians-page .lib-table tbody td {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 10px 14px;
        border-bottom: 1px solid #f0ece0;
        text-align: right;
    }

    .librarians-page .lib-table tbody td::before {
        content: attr(data-label);
        flex: 0 0 auto;
        color: #68766d;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
        text-align: left;
    }

    .librarians-page .lib-table tbody td.cell-no { display: none; }

    .librarians-page .lib-table tbody td.cell-person { display: block; padding: 14px; background: #f5f3e9; text-align: left; }
    .librarians-page .lib-table tbody td.cell-person::before { display: none; }
    .librarians-page .lib-person { min-width: 0; }

    .librarians-page .lib-table tbody td.cell-actions { display: block; padding: 12px 14px; border-bottom: 0; }
    .librarians-page .lib-table tbody td.cell-actions::before { display: none; }
    .librarians-page .lib-actions .lib-btn { width: 100%; min-height: 38px; }

    .librarians-page .lib-table tbody tr.empty-row td { display: block; padding: 0; border: 0; }
    .librarians-page .lib-table tbody tr.empty-row td::before { display: none; }

    .librarians-page .lib-card-footer { align-items: flex-start; flex-direction: column; padding: 13px 15px; }
}

.librarians-page :focus-visible { outline: 3px solid rgba(82, 127, 121, .45); outline-offset: 3px; }

@media (prefers-reduced-motion: reduce) {
    .librarians-page *,
    .librarians-page *::before,
    .librarians-page *::after { animation: none !important; transition: none !important; }
}
</style>

@endsection