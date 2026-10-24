@extends('layouts.app')

@section('title', 'Librarian Profile | Admin')
@section('page-title', 'Librarian Profile')

@section('content')
<div class="librarian-profile-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        LIBRARY <span>/</span> LIBRARIANS <span>/</span> PROFILE
                    </div>
                    <h2>Librarian Profile</h2>
                    <p>View librarian personal and employment information.</p>
                </div>
            </div>
            <div class="form-hero-actions">
                <a href="{{ route('admin.library.librarian.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Librarians
                </a>
            </div>
        </section>

        {{-- PROFILE HEADER CARD --}}
        <section class="form-card profile-header-card">
            <div class="profile-header">
                <div class="profile-photo-wrap">
                    @if($librarian->profile_photo)
                        <img src="{{ asset('storage/' . $librarian->profile_photo) }}"
                             alt="{{ $librarian->name }}"
                             class="profile-photo">
                    @else
                        <div class="profile-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>

                <div class="profile-info">
                    <h2 class="profile-name">{{ $librarian->name }}</h2>

                    <div class="profile-designation">
                        <i class="fas fa-book-reader"></i>
                        {{ $librarian->designation }}
                    </div>

                    <div class="profile-meta">
                        <span class="meta-chip">
                            <i class="fas fa-id-card"></i>
                            {{ $librarian->staff_id }}
                        </span>

                        @if($librarian->department)
                            <span class="meta-chip">
                                <i class="fas fa-building"></i>
                                {{ $librarian->department }}
                            </span>
                        @endif

                        @if($librarian->status === 'Active')
                            <span class="status-badge status-active">
                                <span class="status-dot"></span>
                                Active
                            </span>
                        @else
                            <span class="status-badge status-inactive">
                                <span class="status-dot"></span>
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- INFORMATION SECTIONS --}}
        <div class="info-grid">

            {{-- PERSONAL INFORMATION --}}
            <section class="form-card info-card">
                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="section-eyebrow">PERSONAL</div>
                            <h3>Personal Information</h3>
                        </div>
                    </div>
                </div>
                <div class="info-body">
                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <strong class="info-value">{{ $librarian->name }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Staff ID</span>
                        <strong class="info-value">{{ $librarian->staff_id }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender</span>
                        <strong class="info-value">{{ $librarian->gender ?? 'Not provided' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth</span>
                        <strong class="info-value">
                            {{ $librarian->date_of_birth
                                ? $librarian->date_of_birth->format('d M Y')
                                : 'Not provided' }}
                        </strong>
                    </div>
                </div>
            </section>

            {{-- CONTACT INFORMATION --}}
            <section class="form-card info-card">
                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <div>
                            <div class="section-eyebrow">CONTACT</div>
                            <h3>Contact Information</h3>
                        </div>
                    </div>
                </div>
                <div class="info-body">
                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <strong class="info-value">{{ $librarian->phone ?? 'Not provided' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <strong class="info-value">{{ $librarian->email ?? 'Not provided' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Address</span>
                        <strong class="info-value">{{ $librarian->address ?? 'Not provided' }}</strong>
                    </div>
                </div>
            </section>

            {{-- EMPLOYMENT INFORMATION --}}
            <section class="form-card info-card">
                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div>
                            <div class="section-eyebrow">EMPLOYMENT</div>
                            <h3>Employment Information</h3>
                        </div>
                    </div>
                </div>
                <div class="info-body">
                    <div class="info-row">
                        <span class="info-label">Designation</span>
                        <strong class="info-value">{{ $librarian->designation }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Department</span>
                        <strong class="info-value">{{ $librarian->department ?? 'Not provided' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Qualification</span>
                        <strong class="info-value">{{ $librarian->qualification ?? 'Not provided' }}</strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Joining Date</span>
                        <strong class="info-value">
                            {{ $librarian->joining_date
                                ? $librarian->joining_date->format('d M Y')
                                : 'Not provided' }}
                        </strong>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <strong class="info-value">
                            @if($librarian->status === 'Active')
                                <span class="status-badge status-active">
                                    <span class="status-dot"></span>
                                    Active
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <span class="status-dot"></span>
                                    Inactive
                                </span>
                            @endif
                        </strong>
                    </div>
                </div>
            </section>

            {{-- LIBRARY ROLE --}}
            <section class="form-card info-card">
                <div class="form-panel-header">
                    <div class="panel-title-wrap">
                        <div class="panel-title-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <div class="section-eyebrow">ROLE</div>
                            <h3>Library Role</h3>
                        </div>
                    </div>
                </div>
                <div class="info-body">
                    <div class="role-box">
                        <div class="role-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div>
                            <h4 class="role-title">Librarian</h4>
                            <p class="role-desc">
                                Responsible for managing books, issues, returns and library records.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- ACTIONS --}}
        <div class="profile-actions">
            <a href="{{ route('admin.library.librarian.index') }}"
               class="diary-reset-btn">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
            <a href="{{ route('admin.other-staff.edit', $librarian->id) }}"
               class="diary-primary-btn">
                <i class="fas fa-pen-to-square"></i>
                Edit Details
            </a>
        </div>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   LIBRARY — LIBRARIAN PROFILE PAGE
   Same tokens, cards and buttons as previous pages.
   Every selector is scoped under .librarian-profile-page.
========================================================= */
.librarian-profile-page {
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
    min-height: calc(100vh - 70px);
    color: var(--text);
    background: var(--paper-bg);
    font-family: "Public Sans", Inter, Arial, sans-serif;
}

.librarian-profile-page *,
.librarian-profile-page *::before,
.librarian-profile-page *::after {
    box-sizing: border-box;
}

.librarian-profile-page a,
.librarian-profile-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.librarian-profile-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.librarian-profile-page .form-hero,
.librarian-profile-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */
.librarian-profile-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.librarian-profile-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.librarian-profile-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.librarian-profile-page .form-hero-icon {
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

.librarian-profile-page .form-hero-text {
    min-width: 0;
}

.librarian-profile-page .form-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.librarian-profile-page .form-breadcrumb span {
    color: #b8b09e;
}

.librarian-profile-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.librarian-profile-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.librarian-profile-page .form-hero-actions {
    padding: 0 28px;
}

/* BUTTONS */
.librarian-profile-page .diary-primary-btn,
.librarian-profile-page .diary-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 41px;
    padding: 10px 16px;
    border-radius: 7px;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none !important;
    white-space: nowrap;
    cursor: pointer;
}

.librarian-profile-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.librarian-profile-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.librarian-profile-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.librarian-profile-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* PROFILE HEADER CARD */
.librarian-profile-page .profile-header-card {
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.librarian-profile-page .profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 28px 26px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .55), transparent 70%),
        var(--paper);
}

.librarian-profile-page .profile-photo-wrap {
    flex-shrink: 0;
}

.librarian-profile-page .profile-photo,
.librarian-profile-page .profile-placeholder {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #e6dfce;
    background: var(--chalk);
    box-shadow: 0 4px 14px rgba(38, 59, 53, .08);
}

.librarian-profile-page .profile-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink);
    font-size: 34px;
}

.librarian-profile-page .profile-info {
    min-width: 0;
}

.librarian-profile-page .profile-name {
    margin: 0 0 6px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 24px;
    font-weight: 700;
    line-height: 1.25;
}

.librarian-profile-page .profile-designation {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
}

.librarian-profile-page .profile-designation i {
    color: var(--amber);
    font-size: 13px;
}

.librarian-profile-page .profile-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}

.librarian-profile-page .meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    background: #faf9f4;
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
}

.librarian-profile-page .meta-chip i {
    color: var(--amber);
    font-size: 11px;
}

/* STATUS BADGES */
.librarian-profile-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.librarian-profile-page .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.librarian-profile-page .status-active {
    background: #edf7ed;
    color: #347849;
    border: 1px solid #cfe5d3;
}

.librarian-profile-page .status-active .status-dot {
    background: #347849;
}

.librarian-profile-page .status-inactive {
    background: #f0f0ea;
    color: #68746a;
    border: 1px solid #deded2;
}

.librarian-profile-page .status-inactive .status-dot {
    background: #929b8f;
}

/* INFO GRID */
.librarian-profile-page .info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.librarian-profile-page .info-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

/* PANEL HEADER (shared) */
.librarian-profile-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 18px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.librarian-profile-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.librarian-profile-page .panel-title-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e3e2d5;
    border-radius: 9px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 13px;
}

.librarian-profile-page .section-eyebrow {
    margin-bottom: 3px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.librarian-profile-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

/* INFO BODY */
.librarian-profile-page .info-body {
    padding: 8px 18px 16px;
}

.librarian-profile-page .info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    padding: 12px 0;
    border-bottom: 1px solid #f0ebe0;
}

.librarian-profile-page .info-row:last-child {
    border-bottom: 0;
}

.librarian-profile-page .info-label {
    color: var(--muted);
    font-size: 11px;
    font-weight: 700;
    flex-shrink: 0;
}

.librarian-profile-page .info-value {
    color: var(--ink);
    font-size: 13px;
    font-weight: 600;
    text-align: right;
    max-width: 65%;
    line-height: 1.45;
}

/* ROLE BOX */
.librarian-profile-page .role-box {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.librarian-profile-page .role-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    color: var(--amber);
    background: #fffefa;
    font-size: 16px;
}

.librarian-profile-page .role-title {
    margin: 0 0 5px;
    color: #765e32;
    font-family: var(--serif);
    font-size: 15px;
    font-weight: 700;
}

.librarian-profile-page .role-desc {
    margin: 0;
    color: #8a7447;
    font-size: 12px;
    line-height: 1.6;
}

/* ACTIONS */
.librarian-profile-page .profile-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
}

/* TABLET */
@media (max-width: 900px) {
    .librarian-profile-page .form-hero {
        grid-template-columns: 1fr;
    }
    .librarian-profile-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }

    .librarian-profile-page .info-grid {
        grid-template-columns: 1fr;
    }
}

/* MOBILE */
@media (max-width: 650px) {
    .librarian-profile-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .librarian-profile-page .form-hero::before {
        left: 22px;
    }

    .librarian-profile-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .librarian-profile-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .librarian-profile-page .form-hero-text h2 {
        font-size: 23px;
    }

    .librarian-profile-page .form-hero-text p {
        font-size: 12px;
    }

    .librarian-profile-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .librarian-profile-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .librarian-profile-page .profile-header {
        flex-direction: column;
        align-items: flex-start;
        padding: 22px 18px;
        gap: 18px;
    }

    .librarian-profile-page .profile-name {
        font-size: 20px;
    }

    .librarian-profile-page .form-panel-header {
        padding: 14px 15px;
    }

    .librarian-profile-page .info-body {
        padding: 6px 15px 14px;
    }

    .librarian-profile-page .info-row {
        flex-direction: column;
        gap: 4px;
    }

    .librarian-profile-page .info-value {
        text-align: left;
        max-width: 100%;
    }

    .librarian-profile-page .profile-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .librarian-profile-page .profile-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .librarian-profile-page .panel-title-icon {
        flex-basis: 32px;
        width: 32px;
        height: 32px;
    }

    .librarian-profile-page .profile-photo,
    .librarian-profile-page .profile-placeholder {
        width: 84px;
        height: 84px;
    }
}

/* ACCESSIBILITY */
.librarian-profile-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .librarian-profile-page *,
    .librarian-profile-page *::before,
    .librarian-profile-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>
@endsection