@extends('layouts.app')

@section('title', 'Staff Profile | Admin')
@section('page-title', 'Staff Profile')

@section('content')

<div class="staff-profile-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-id-card"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        STAFF <span>/</span> OTHER STAFF <span>/</span> PROFILE
                    </div>

                    <h2>Staff Profile</h2>

                    <p>View complete staff member information.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.other-staff.edit', $otherStaff->id) }}"
                   class="diary-primary-btn">
                    <i class="fas fa-pen-to-square"></i>
                    Edit
                </a>

                <a href="{{ route('admin.other-staff.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Staff
                </a>
            </div>
        </section>

        {{-- PROFILE SUMMARY CARD --}}
        <section class="profile-summary-card">

            <div class="profile-photo-wrap">
                @if($otherStaff->profile_photo)
                    <x-cloudinary::image
                        public-id="{{ $otherStaff->profile_photo }}"
                        width="110"
                        height="110"
                        alt="{{ $otherStaff->name }}"
                        class="profile-photo"
                    />
                @else
                    <div class="profile-photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>

            <div class="profile-summary-body">
                <div class="profile-name-row">
                    <h3>{{ $otherStaff->name }}</h3>

                    @if($otherStaff->status === 'Active')
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

                <div class="profile-meta">
                    <span class="meta-item">
                        <i class="fas fa-briefcase"></i>
                        {{ $otherStaff->designation ?? 'Staff Member' }}
                    </span>

                    @if($otherStaff->department)
                        <span class="meta-item">
                            <i class="fas fa-building"></i>
                            {{ $otherStaff->department }}
                        </span>
                    @endif

                    <span class="meta-item staff-id-badge">
                        <i class="fas fa-hashtag"></i>
                        {{ $otherStaff->staff_id }}
                    </span>
                </div>
            </div>

        </section>

        {{-- PERSONAL INFORMATION --}}
        <section class="form-card info-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-user"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">DETAILS</div>
                        <h3>Personal Information</h3>
                    </div>
                </div>
            </div>

            <div class="info-body">
                <div class="info-grid">

                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $otherStaff->name }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Staff ID</div>
                        <div class="info-value">{{ $otherStaff->staff_id }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value">
                            @if($otherStaff->gender)
                                {{ $otherStaff->gender }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value">
                            @if($otherStaff->date_of_birth)
                                {{ \Carbon\Carbon::parse($otherStaff->date_of_birth)->format('d M Y') }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

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
                        <div class="section-eyebrow">DETAILS</div>
                        <h3>Contact Information</h3>
                    </div>
                </div>
            </div>

            <div class="info-body">
                <div class="info-grid">

                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">
                            @if($otherStaff->phone)
                                <i class="fas fa-phone info-inline-icon"></i>
                                {{ $otherStaff->phone }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            @if($otherStaff->email)
                                <i class="fas fa-envelope info-inline-icon"></i>
                                {{ $otherStaff->email }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item info-item-full">
                        <div class="info-label">Address</div>
                        <div class="info-value">
                            @if($otherStaff->address)
                                {{ $otherStaff->address }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </section>

        {{-- PROFESSIONAL INFORMATION --}}
        <section class="form-card info-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">DETAILS</div>
                        <h3>Professional Information</h3>
                    </div>
                </div>
            </div>

            <div class="info-body">
                <div class="info-grid">

                    <div class="info-item">
                        <div class="info-label">Designation</div>
                        <div class="info-value">
                            {{ $otherStaff->designation ?: 'Not Provided' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Department</div>
                        <div class="info-value">
                            {{ $otherStaff->department ?: 'Not Provided' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Qualification</div>
                        <div class="info-value">
                            {{ $otherStaff->qualification ?: 'Not Provided' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">License Number</div>
                        <div class="info-value">
                            {{ $otherStaff->license_number ?: 'Not Provided' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">License Expiry</div>
                        <div class="info-value">
                            @if($otherStaff->license_expiry)
                                {{ \Carbon\Carbon::parse($otherStaff->license_expiry)->format('d M Y') }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Joining Date</div>
                        <div class="info-value">
                            @if($otherStaff->joining_date)
                                {{ \Carbon\Carbon::parse($otherStaff->joining_date)->format('d M Y') }}
                            @else
                                <span class="empty-value">Not Provided</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @if($otherStaff->status === 'Active')
                                <span class="status-text status-text-active">
                                    <i class="fas fa-check-circle"></i>
                                    Active
                                </span>
                            @else
                                <span class="status-text status-text-inactive">
                                    <i class="fas fa-times-circle"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </section>

        {{-- RECORD INFORMATION --}}
        <section class="form-card info-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">DETAILS</div>
                        <h3>Record Information</h3>
                    </div>
                </div>
            </div>

            <div class="info-body">
                <div class="info-grid">

                    <div class="info-item">
                        <div class="info-label">Created At</div>
                        <div class="info-value">
                            @if($otherStaff->created_at)
                                {{ $otherStaff->created_at->format('d M Y, h:i A') }}
                            @else
                                <span class="empty-value">Not Available</span>
                            @endif
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">
                            @if($otherStaff->updated_at)
                                {{ $otherStaff->updated_at->format('d M Y, h:i A') }}
                            @else
                                <span class="empty-value">Not Available</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   OTHER STAFF — PROFILE / SHOW PAGE
   Same tokens, cards and buttons as Edit Staff / Roles.
   Every selector is scoped under .staff-profile-page.
========================================================= */

.staff-profile-page {
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

.staff-profile-page *,
.staff-profile-page *::before,
.staff-profile-page *::after {
    box-sizing: border-box;
}

.staff-profile-page a,
.staff-profile-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.staff-profile-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.staff-profile-page .form-hero,
.staff-profile-page .form-card,
.staff-profile-page .profile-summary-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.staff-profile-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.staff-profile-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.staff-profile-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.staff-profile-page .form-hero-icon {
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

.staff-profile-page .form-hero-text {
    min-width: 0;
}

.staff-profile-page .form-breadcrumb {
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

.staff-profile-page .form-breadcrumb span {
    color: #b8b09e;
}

.staff-profile-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.staff-profile-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.staff-profile-page .form-hero-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.staff-profile-page .diary-primary-btn,
.staff-profile-page .diary-reset-btn {
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

.staff-profile-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.staff-profile-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.staff-profile-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.staff-profile-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* PROFILE SUMMARY CARD */

.staff-profile-page .profile-summary-card {
    display: flex;
    align-items: center;
    gap: 22px;
    margin-bottom: 20px;
    padding: 24px 26px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.staff-profile-page .profile-photo-wrap {
    flex: 0 0 110px;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #e6dfce;
    background: var(--chalk);
    display: flex;
    align-items: center;
    justify-content: center;
}

.staff-profile-page .profile-photo,
.staff-profile-page .profile-photo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.staff-profile-page .profile-photo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: var(--ink);
    font-size: 36px;
}

.staff-profile-page .profile-summary-body {
    flex: 1;
    min-width: 0;
}

.staff-profile-page .profile-name-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 10px;
}

.staff-profile-page .profile-name-row h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 24px;
    font-weight: 700;
    line-height: 1.2;
}

.staff-profile-page .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.staff-profile-page .status-active {
    color: #2f6b45;
    background: #edf7ed;
    border: 1px solid #cfe5d3;
}

.staff-profile-page .status-inactive {
    color: #a84840;
    background: #fff4ef;
    border: 1px solid #f0d5ce;
}

.staff-profile-page .status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: currentColor;
}

.staff-profile-page .profile-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px 18px;
}

.staff-profile-page .meta-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
}

.staff-profile-page .meta-item i {
    color: var(--amber);
    font-size: 12px;
}

.staff-profile-page .staff-id-badge {
    padding: 4px 10px;
    border: 1px solid var(--line);
    border-radius: 20px;
    background: #faf9f4;
    color: var(--ink);
    font-size: 12px;
}

/* INFO CARDS */

.staff-profile-page .info-card {
    margin-bottom: 18px;
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.staff-profile-page .info-card:last-child {
    margin-bottom: 0;
}

.staff-profile-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 16px 20px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.staff-profile-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.staff-profile-page .panel-title-icon {
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

.staff-profile-page .section-eyebrow {
    margin-bottom: 3px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.staff-profile-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.staff-profile-page .info-body {
    padding: 18px 20px 20px;
}

.staff-profile-page .info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px 16px;
}

.staff-profile-page .info-item {
    padding: 13px 15px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.staff-profile-page .info-item-full {
    grid-column: 1 / -1;
}

.staff-profile-page .info-label {
    margin-bottom: 5px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .5px;
    text-transform: uppercase;
}

.staff-profile-page .info-value {
    color: var(--ink);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.5;
    word-break: break-word;
}

.staff-profile-page .info-inline-icon {
    margin-right: 6px;
    color: var(--amber);
    font-size: 11px;
}

.staff-profile-page .empty-value {
    color: #a8b0a5;
    font-style: italic;
    font-weight: 500;
}

.staff-profile-page .status-text {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
}

.staff-profile-page .status-text-active {
    color: #2f6b45;
}

.staff-profile-page .status-text-inactive {
    color: #a84840;
}

/* TABLET */

@media (max-width: 900px) {
    .staff-profile-page .form-hero {
        grid-template-columns: 1fr;
    }

    .staff-profile-page .form-hero-actions {
        padding: 0 28px 24px 56px;
        flex-wrap: wrap;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .staff-profile-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .staff-profile-page .form-hero::before {
        left: 22px;
    }

    .staff-profile-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .staff-profile-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .staff-profile-page .form-hero-text h2 {
        font-size: 23px;
    }

    .staff-profile-page .form-hero-text p {
        font-size: 12px;
    }

    .staff-profile-page .form-hero-actions {
        padding: 0 16px 20px 42px;
        flex-direction: column;
    }

    .staff-profile-page .form-hero-actions > * {
        width: 100%;
    }

    .staff-profile-page .profile-summary-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 22px 18px;
    }

    .staff-profile-page .profile-name-row {
        justify-content: center;
    }

    .staff-profile-page .profile-name-row h3 {
        font-size: 20px;
    }

    .staff-profile-page .profile-meta {
        justify-content: center;
    }

    .staff-profile-page .form-panel-header {
        padding: 14px 15px;
    }

    .staff-profile-page .info-body {
        padding: 14px 15px 16px;
    }

    .staff-profile-page .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 400px) {
    .staff-profile-page .panel-title-icon {
        flex-basis: 32px;
        width: 32px;
        height: 32px;
    }
}

/* ACCESSIBILITY */

.staff-profile-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .staff-profile-page *,
    .staff-profile-page *::before,
    .staff-profile-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection