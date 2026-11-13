@extends('layouts.app')

@section('title', 'School Profile | Admin')
@section('page-title', 'School Profile')

@section('content')

<div class="school-profile-page">
    <div class="container-fluid py-4">

        {{-- FLASH MESSAGES --}}
        @if(session('success'))
            <div class="school-success-alert" role="status">
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
            <div class="school-error-alert" role="alert">
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
        <section class="school-hero">
            <div class="school-hero-content">
                <div class="school-hero-icon">
                    <i class="fas fa-school"></i>
                </div>

                <div class="school-hero-text">
                    <div class="school-breadcrumb">
                        SETTINGS <span>/</span> SCHOOL PROFILE
                    </div>

                    <h2>School Profile</h2>

                    <p>
                        Manage your school’s official information used across
                        certificates, reports and other modules.
                    </p>

                    <div class="school-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Identity &amp; Contact Management</span>
                    </div>
                </div>
            </div>

            <div class="school-hero-actions">
                <div class="hero-handwritten">Identity. Vision. Excellence.</div>

                <p>
                    Keep school details accurate and
                    ready for certificates and reports.
                </p>

                @if($school)
                    <a href="{{ route('admin.settings.edit', $school->id) }}"
                       class="diary-primary-btn hero-primary-btn">
                        <i class="fas fa-pen"></i>
                        Edit School
                    </a>
                @else
                    <a href="{{ route('admin.settings.create') }}"
                       class="diary-primary-btn hero-primary-btn">
                        <i class="fas fa-plus"></i>
                        Add School
                    </a>
                @endif
            </div>
        </section>

        @if($school)

            {{-- SCHOOL PROFILE CARD --}}
            <section class="school-profile-card">

                {{-- PROFILE HEADER --}}
                <div class="school-profile-header">
                    <div class="school-logo-wrap">
                        <img
                            src="{{ $school->logo
                                ? asset('storage/' . $school->logo)
                                : asset('images/gurukullogo.png') }}"
                            alt="School Logo"
                            onerror="this.src='{{ asset('images/gurukullogo.png') }}'"
                        >
                    </div>

                    <div class="school-profile-meta">
                        <div class="section-eyebrow">OFFICIAL PROFILE</div>
                        <h3>{{ $school->school_name }}</h3>
                        <p>
                            {{ $school->city }}
                            @if($school->district), {{ $school->district }}@endif
                            @if($school->state), {{ $school->state }}@endif
                        </p>
                    </div>
                </div>

                {{-- QUICK INFO ROW --}}
                <div class="school-info-grid">

                    <div class="school-info-box">
                        <div class="info-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <span class="info-label">School Code</span>
                            <div class="info-value">
                                {{ $school->school_code ?: 'Not available' }}
                            </div>
                        </div>
                    </div>

                    <div class="school-info-box">
                        <div class="info-icon">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <div>
                            <span class="info-label">UDISE Code</span>
                            <div class="info-value">
                                {{ $school->udise_code ?: 'Not available' }}
                            </div>
                        </div>
                    </div>

                    <div class="school-info-box">
                        <div class="info-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <span class="info-label">Principal</span>
                            <div class="info-value">
                                {{ $school->principal_name ?: 'Not available' }}
                            </div>
                        </div>
                    </div>

                </div>

                {{-- DETAIL CARDS --}}
                <div class="school-detail-grid">

                    {{-- ADDRESS --}}
                    <div class="school-detail-card">
                        <div class="detail-card-header">
                            <div class="detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h4>Address</h4>
                        </div>
                        <div class="detail-body">
                            <p>
                                {{ $school->address ?: 'Not available' }}
                                @if($school->city)
                                    <br>{{ $school->city }}
                                @endif
                                @if($school->district)
                                    , {{ $school->district }}
                                @endif
                                @if($school->state)
                                    , {{ $school->state }}
                                @endif
                                @if($school->pincode)
                                    - {{ $school->pincode }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div class="school-detail-card">
                        <div class="detail-card-header">
                            <div class="detail-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h4>Contact Information</h4>
                        </div>
                        <div class="detail-body">
                            @if($school->phone)
                                <div class="contact-row">
                                    <i class="fas fa-mobile-alt"></i>
                                    <span>{{ $school->phone }}</span>
                                </div>
                            @endif

                            @if($school->email)
                                <div class="contact-row">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $school->email }}</span>
                                </div>
                            @endif

                            @if($school->website)
                                <div class="contact-row">
                                    <i class="fas fa-globe"></i>
                                    <a href="{{ $school->website }}"
                                       target="_blank"
                                       rel="noopener">
                                        {{ $school->website }}
                                    </a>
                                </div>
                            @endif

                            @if(!$school->phone && !$school->email && !$school->website)
                                <p class="diary-muted">No contact information available.</p>
                            @endif
                        </div>
                    </div>

                    {{-- SCHOOL INFORMATION --}}
                    <div class="school-detail-card">
                        <div class="detail-card-header">
                            <div class="detail-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4>School Information</h4>
                        </div>
                        <div class="detail-body">
                            <div class="info-pair">
                                <span class="info-label">Established Year</span>
                                <div class="info-value">
                                    {{ $school->established_year ?: 'Not available' }}
                                </div>
                            </div>
                            <div class="info-pair">
                                <span class="info-label">State</span>
                                <div class="info-value">
                                    {{ $school->state ?: 'Not available' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PROFILE ACTIONS --}}
                    <div class="school-detail-card">
                        <div class="detail-card-header">
                            <div class="detail-icon">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h4>Profile Actions</h4>
                        </div>
                        <div class="detail-body">
                            <div class="action-buttons">
                                <a href="{{ route('admin.settings.edit', $school->id) }}"
                                   class="diary-primary-btn">
                                    <i class="fas fa-pen"></i>
                                    Edit School
                                </a>

                                <form action="{{ route('admin.settings.destroy', $school->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this school profile? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="diary-danger-btn">
                                        <i class="fas fa-trash-alt"></i>
                                        Delete School
                                    </button>
                                </form>
                            </div>

                            <p class="action-note">
                                <i class="fas fa-info-circle"></i>
                                Delete the current school profile only if you need to
                                register a different school.
                            </p>
                        </div>
                    </div>

                </div>

            </section>

        @else

            {{-- EMPTY STATE --}}
            <section class="school-empty-card">
                <div class="school-empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-school"></i>
                    </div>

                    <h4>No School Profile Found</h4>

                    <p>
                        Add your school’s profile to use school information
                        throughout certificates, reports and other modules.
                    </p>

                    <div class="empty-state-actions">
                        <a href="{{ route('admin.settings.create') }}"
                           class="diary-primary-btn">
                            <i class="fas fa-plus"></i>
                            Add School
                        </a>
                    </div>
                </div>
            </section>

        @endif

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   SCHOOL PROFILE — PAGE
   Same tokens, cards, buttons and layout as Meal Items / Library / etc.
   Every selector is scoped under .school-profile-page.
========================================================= */

.school-profile-page {
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

.school-profile-page *,
.school-profile-page *::before,
.school-profile-page *::after {
    box-sizing: border-box;
}

.school-profile-page a,
.school-profile-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.school-profile-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.school-profile-page .school-hero,
.school-profile-page .school-profile-card,
.school-profile-page .school-empty-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.school-profile-page .school-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.school-profile-page .school-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.school-profile-page .school-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.school-profile-page .school-hero-icon {
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

.school-profile-page .school-hero-text {
    min-width: 0;
}

.school-profile-page .school-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.school-profile-page .school-breadcrumb span {
    color: #b8b09e;
}

.school-profile-page .school-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.school-profile-page .school-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.school-profile-page .school-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.school-profile-page .school-hero-actions {
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

.school-profile-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.school-profile-page .school-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.school-profile-page .diary-primary-btn {
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

.school-profile-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.school-profile-page .school-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.school-profile-page .school-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

.school-profile-page .diary-danger-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 40px;
    padding: 10px 16px;
    border: 1px solid #f0c9c2;
    border-radius: 7px;
    color: var(--red);
    background: #fff1ed;
    font-family: inherit;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    cursor: pointer;
}

.school-profile-page .diary-danger-btn:hover {
    transform: translateY(-2px);
    border-color: var(--red);
    background: #ffe8e2;
}

/* ALERTS */

.school-profile-page .school-success-alert,
.school-profile-page .school-error-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
    font-weight: 700;
}

.school-profile-page .school-success-alert {
    border: 1px solid #cfe3d1;
    color: #347849;
    background: #f1f8ef;
}

.school-profile-page .school-error-alert {
    align-items: flex-start;
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.school-profile-page .success-icon,
.school-profile-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.school-profile-page .success-icon { background: #dcefd9; }
.school-profile-page .error-icon { background: #fbe0d8; }

.school-profile-page .alert-close {
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

.school-profile-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* PROFILE CARD */

.school-profile-page .school-profile-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.school-profile-page .school-profile-header {
    display: flex;
    align-items: center;
    gap: 22px;
    padding: 28px 28px 24px;
    border-bottom: 1px dashed #d8d1bf;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .5), transparent 60%),
        var(--paper);
}

.school-profile-page .school-logo-wrap {
    flex: 0 0 110px;
    width: 110px;
    height: 110px;
    border: 1px solid #e6dfce;
    border-radius: 16px;
    background: var(--chalk);
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(38, 59, 53, .06);
}

.school-profile-page .school-logo-wrap img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 10px;
}

.school-profile-page .school-profile-meta {
    min-width: 0;
}

.school-profile-page .section-eyebrow {
    margin-bottom: 6px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.school-profile-page .school-profile-meta h3 {
    margin: 0 0 6px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 26px;
    font-weight: 700;
    line-height: 1.25;
}

.school-profile-page .school-profile-meta p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
}

/* INFO GRID */

.school-profile-page .school-info-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    padding: 22px 24px;
    border-bottom: 1px dashed #d8d1bf;
}

.school-profile-page .school-info-box {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px 18px;
    border: 1px solid var(--line);
    border-radius: 10px;
    background: #faf9f4;
}

.school-profile-page .info-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 44px;
    width: 44px;
    height: 44px;
    border: 1px solid #e6dfce;
    border-radius: 10px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 16px;
}

.school-profile-page .info-label {
    display: block;
    margin-bottom: 3px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.school-profile-page .info-value {
    color: var(--ink);
    font-size: 13px;
    font-weight: 800;
}

/* DETAIL GRID */

.school-profile-page .school-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
    padding: 22px 24px 26px;
}

.school-profile-page .school-detail-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
    overflow: hidden;
}

.school-profile-page .detail-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-bottom: 1px dashed #d8d1bf;
    background: #faf9f4;
}

.school-profile-page .detail-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 36px;
    width: 36px;
    height: 36px;
    border: 1px solid #e6dfce;
    border-radius: 8px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 14px;
}

.school-profile-page .detail-card-header h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 15px;
    font-weight: 700;
}

.school-profile-page .detail-body {
    padding: 18px;
}

.school-profile-page .detail-body p {
    margin: 0;
    color: var(--text);
    font-size: 13px;
    line-height: 1.7;
}

.school-profile-page .contact-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
    color: var(--text);
    font-size: 13px;
}

.school-profile-page .contact-row:last-child {
    margin-bottom: 0;
}

.school-profile-page .contact-row i {
    color: var(--amber);
    width: 16px;
    text-align: center;
}

.school-profile-page .contact-row a {
    color: var(--blue);
    text-decoration: none;
    font-weight: 600;
}

.school-profile-page .contact-row a:hover {
    text-decoration: underline;
}

.school-profile-page .info-pair {
    margin-bottom: 14px;
}

.school-profile-page .info-pair:last-child {
    margin-bottom: 0;
}

.school-profile-page .action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}

.school-profile-page .action-buttons form {
    margin: 0;
}

.school-profile-page .action-note {
    margin: 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.school-profile-page .action-note i {
    color: var(--amber);
    margin-right: 4px;
}

.school-profile-page .diary-muted {
    color: #929a8e;
    font-size: 12px;
}

/* EMPTY STATE */

.school-profile-page .school-empty-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.school-profile-page .school-empty-state {
    padding: 70px 24px;
    text-align: center;
}

.school-profile-page .empty-state-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    margin: 0 auto 18px;
    border: 1px solid #e5dfcc;
    border-radius: 16px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 32px;
}

.school-profile-page .school-empty-state h4 {
    margin: 0 0 10px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 700;
}

.school-profile-page .school-empty-state p {
    max-width: 480px;
    margin: 0 auto 22px;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.8;
}

.school-profile-page .empty-state-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .school-profile-page .school-info-grid {
        grid-template-columns: 1fr;
    }

    .school-profile-page .school-detail-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 900px) {
    .school-profile-page .school-hero {
        grid-template-columns: 1fr;
    }

    .school-profile-page .school-hero-actions {
        min-height: 165px;
    }

    .school-profile-page .school-profile-header {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
}

@media (max-width: 650px) {
    .school-profile-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .school-profile-page .school-hero::before {
        left: 22px;
    }

    .school-profile-page .school-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .school-profile-page .school-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .school-profile-page .school-hero-text h2 {
        font-size: 23px;
    }

    .school-profile-page .school-hero-text p {
        font-size: 12px;
    }

    .school-profile-page .school-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .school-profile-page .school-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .school-profile-page .school-profile-header {
        padding: 20px 16px;
    }

    .school-profile-page .school-logo-wrap {
        flex-basis: 90px;
        width: 90px;
        height: 90px;
    }

    .school-profile-page .school-profile-meta h3 {
        font-size: 22px;
    }

    .school-profile-page .school-info-grid,
    .school-profile-page .school-detail-grid {
        padding: 16px;
        gap: 12px;
    }

    .school-profile-page .action-buttons {
        flex-direction: column;
    }

    .school-profile-page .action-buttons .diary-primary-btn,
    .school-profile-page .action-buttons .diary-danger-btn {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .school-profile-page .empty-state-actions {
        align-items: stretch;
        flex-direction: column;
        width: 100%;
    }

    .school-profile-page .empty-state-actions > a {
        width: 100%;
    }
}

/* ACCESSIBILITY */

.school-profile-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .school-profile-page *,
    .school-profile-page *::before,
    .school-profile-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection