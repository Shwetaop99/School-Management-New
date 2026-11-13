@extends('layouts.app')

@section('title', 'Add New School | Admin')
@section('page-title', 'Add New School')

@section('content')

<div class="school-create-page">
    <div class="container-fluid py-4">

        {{-- FLASH / VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="school-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="alert-body">
                    <strong>Please correct the following errors:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <button type="button"
                        class="alert-close"
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

                    <h2>Add New School</h2>

                    <p>
                        Create a school profile with basic information,
                        contact details and branding for this school.
                    </p>

                    <div class="school-hero-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>School Profile Management</span>
                    </div>
                </div>
            </div>

            <div class="school-hero-actions">
                <div class="hero-handwritten">Simple. Organised. Efficient.</div>

                <p>
                    Add school details so the rest of the
                    system can use the correct profile.
                </p>

                <a href="{{ route('admin.settings.index') }}"
                   class="diary-primary-btn hero-primary-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to School Profiles
                </a>
            </div>
        </section>

        {{-- FORM CARD --}}
        <section class="school-form-card">

            <div class="school-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-building"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">SCHOOL PROFILE</div>
                        <h3>School Information</h3>
                        <p>Fill in the details below to create a new school profile.</p>
                    </div>
                </div>
            </div>

            <div class="school-form-body">
                <form action="{{ route('admin.settings.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="schoolForm">

                    @csrf

                    @include('admin.settings._form', [
                        'school'       => null,
                        'submitRoute'  => 'admin.settings.store',
                        'submitMethod' => 'POST',
                        'buttonText'   => 'Save School'
                    ])

                </form>
            </div>

        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   ADD NEW SCHOOL — CREATE PAGE
   Same tokens, hero, cards and buttons as Users / Roles / etc.
========================================================= */

.school-create-page {
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

.school-create-page *,
.school-create-page *::before,
.school-create-page *::after {
    box-sizing: border-box;
}

.school-create-page a,
.school-create-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.school-create-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.school-create-page .school-hero,
.school-create-page .school-form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* HERO */

.school-create-page .school-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(270px, 1fr);
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.school-create-page .school-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.school-create-page .school-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 29px 28px 29px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.school-create-page .school-hero-icon {
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

.school-create-page .school-hero-text {
    min-width: 0;
}

.school-create-page .school-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 7px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.3px;
}

.school-create-page .school-breadcrumb span {
    color: #b8b09e;
}

.school-create-page .school-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.school-create-page .school-hero-text p {
    max-width: 540px;
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.school-create-page .school-hero-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-top: 13px;
    color: var(--green);
    font-size: 11px;
    font-weight: 700;
}

.school-create-page .school-hero-actions {
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

.school-create-page .hero-handwritten {
    color: var(--chalk);
    font-family: var(--hand);
    font-size: 23px;
    line-height: 1.3;
}

.school-create-page .school-hero-actions p {
    max-width: 300px;
    margin: 0;
    color: #d2dccf;
    font-size: 12px;
    line-height: 1.75;
}

/* BUTTONS */

.school-create-page .diary-primary-btn {
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

.school-create-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.school-create-page .school-hero-actions .hero-primary-btn {
    border-color: #f1ead8;
    color: var(--ink) !important;
    background: #f1ead8;
}

.school-create-page .school-hero-actions .hero-primary-btn:hover {
    border-color: #fff8e8;
    color: var(--ink-dark) !important;
    background: #fff8e8;
}

/* ALERTS */

.school-create-page .school-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border: 1px solid #f0d5ce;
    border-radius: 9px;
    color: #a84840;
    background: #fff4ef;
    font-size: 12px;
    font-weight: 700;
}

.school-create-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.school-create-page .alert-body {
    flex: 1;
    min-width: 0;
}

.school-create-page .alert-body strong {
    display: block;
    margin-bottom: 6px;
}

.school-create-page .alert-body ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.school-create-page .alert-close {
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

.school-create-page .alert-close:hover {
    color: var(--ink);
    background: #e5efdf;
}

/* FORM CARD */

.school-create-page .school-form-card {
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.school-create-page .school-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.school-create-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.school-create-page .panel-title-icon {
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

.school-create-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.school-create-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.school-create-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.school-create-page .school-form-body {
    padding: 22px 24px 28px;
}

/* Soften Bootstrap form elements inside locked page */
.school-create-page .form-label {
    color: #56665a !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    margin-bottom: 7px !important;
}

.school-create-page .form-control,
.school-create-page .form-select {
    min-height: 41px !important;
    border: 1px solid #deded2 !important;
    border-radius: 7px !important;
    background: #fffefa !important;
    color: var(--text) !important;
    font-size: 12px !important;
    box-shadow: none !important;
}

.school-create-page .form-control:focus,
.school-create-page .form-select:focus {
    border-color: #789382 !important;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11) !important;
}

.school-create-page .btn-primary {
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
    font-size: 12px !important;
    font-weight: 700 !important;
    box-shadow: none !important;
}

.school-create-page .btn-primary:hover {
    border-color: var(--ink-hover) !important;
    background: var(--ink-hover) !important;
    color: #fff !important;
    transform: translateY(-1px);
}

.school-create-page .btn-outline-secondary,
.school-create-page .btn-secondary {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    min-height: 40px !important;
    padding: 10px 16px !important;
    border: 1px solid #deded2 !important;
    border-radius: 7px !important;
    color: #59675d !important;
    background: #fffefa !important;
    font-size: 12px !important;
    font-weight: 700 !important;
    box-shadow: none !important;
}

.school-create-page .btn-outline-secondary:hover,
.school-create-page .btn-secondary:hover {
    border-color: #b7c4b7 !important;
    color: var(--ink) !important;
    background: #f5f4eb !important;
}

.school-create-page .card {
    border: 1px solid var(--line) !important;
    border-radius: 10px !important;
    background: var(--paper) !important;
    box-shadow: 0 3px 12px rgba(38, 59, 53, .04) !important;
}

.school-create-page .card-header {
    border-bottom: 1px dashed #d8d1bf !important;
    background: #faf9f4 !important;
    color: var(--ink) !important;
    font-family: var(--serif) !important;
    font-weight: 700 !important;
}

/* RESPONSIVE */

@media (max-width: 900px) {
    .school-create-page .school-hero {
        grid-template-columns: 1fr;
    }

    .school-create-page .school-hero-actions {
        min-height: 165px;
    }
}

@media (max-width: 650px) {
    .school-create-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .school-create-page .school-hero::before {
        left: 22px;
    }

    .school-create-page .school-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 22px 42px;
    }

    .school-create-page .school-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .school-create-page .school-hero-text h2 {
        font-size: 23px;
    }

    .school-create-page .school-hero-text p {
        font-size: 12px;
    }

    .school-create-page .school-hero-note {
        align-items: flex-start;
        font-size: 10px;
    }

    .school-create-page .school-hero-actions {
        min-height: 155px;
        padding: 20px;
    }

    .school-create-page .school-panel-header {
        padding: 15px;
    }

    .school-create-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .school-create-page .school-form-body {
        padding: 16px;
    }
}

/* ACCESSIBILITY */

.school-create-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    .school-create-page *,
    .school-create-page *::before,
    .school-create-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

@endsection