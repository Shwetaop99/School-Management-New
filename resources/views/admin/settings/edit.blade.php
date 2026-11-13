@extends('layouts.app')

@section('title', 'Edit School Profile | Admin')
@section('page-title', 'Edit School Profile')

@section('content')

@php
    // You can pass these from the controller if preferred
    $school = $schoolSetting;
@endphp

<div class="school-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-school"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        SETTINGS <span>/</span> SCHOOL PROFILE <span>/</span> EDIT
                    </div>

                    <h2>Edit School Profile</h2>

                    <p>Update your school's information and branding.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.settings.index', $school->id) }}"
                   class="diary-reset-btn">
                    <i class="fas fa-eye"></i>
                    View Profile
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </section>

        {{-- SUCCESS ALERT --}}
        @if(session('success'))
            <div class="form-success-alert" role="alert">
                <span class="success-icon">
                    <i class="fas fa-check"></i>
                </span>

                <div class="success-copy">
                    <strong>Success</strong>
                    <p>{{ session('success') }}</p>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if($errors->any())
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Please check the following:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
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

        {{-- MAIN FORM CARD --}}
        <section class="form-card">

            <div class="form-panel-header">
                <div class="panel-title-wrap">
                    <div class="panel-title-icon">
                        <i class="fas fa-pen-to-square"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">SCHOOL SETTINGS</div>
                        <h3>Update School Profile</h3>
                        <p>Edit the information required for your school profile.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.settings.update', $school->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="schoolForm">
                @csrf
                @method('PUT')

                <div class="form-body">

                    {{-- BASIC INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-circle-info"></i>
                            </div>

                            <div>
                                <h4>Basic Information</h4>
                                <span>Core details of your school.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- SCHOOL NAME --}}
                            <div class="diary-filter-group">
                                <label for="school_name">
                                    School Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-school input-icon"></i>

                                    <input type="text"
                                           name="school_name"
                                           id="school_name"
                                           value="{{ old('school_name', $school->school_name ?? '') }}"
                                           class="diary-control with-icon @error('school_name') is-invalid @enderror"
                                           placeholder="e.g. Green Valley Public School"
                                           maxlength="255"
                                           autocomplete="organization"
                                           required
                                           @error('school_name') aria-invalid="true" aria-describedby="school_name_error" @enderror>
                                </div>

                                @error('school_name')
                                    <div class="field-error" id="school_name_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- SCHOOL CODE --}}
                            <div class="diary-filter-group">
                                <label for="school_code">School Code / Affiliation</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-hashtag input-icon"></i>

                                    <input type="text"
                                           name="school_code"
                                           id="school_code"
                                           value="{{ old('school_code', $school->school_code ?? '') }}"
                                           class="diary-control with-icon @error('school_code') is-invalid @enderror"
                                           placeholder="e.g. CBSE-12345"
                                           maxlength="100"
                                           @error('school_code') aria-invalid="true" aria-describedby="school_code_error" @enderror>
                                </div>

                                @error('school_code')
                                    <div class="field-error" id="school_code_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- PRINCIPAL NAME --}}
                            <div class="diary-filter-group">
                                <label for="principal_name">Principal Name</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-user-tie input-icon"></i>

                                    <input type="text"
                                           name="principal_name"
                                           id="principal_name"
                                           value="{{ old('principal_name', $school->principal_name ?? '') }}"
                                           class="diary-control with-icon @error('principal_name') is-invalid @enderror"
                                           placeholder="e.g. Dr. Anita Sharma"
                                           maxlength="150"
                                           @error('principal_name') aria-invalid="true" aria-describedby="principal_name_error" @enderror>
                                </div>

                                @error('principal_name')
                                    <div class="field-error" id="principal_name_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- ESTABLISHED YEAR --}}
                            <div class="diary-filter-group">
                                <label for="established_year">Established Year</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar input-icon"></i>

                                    <input type="number"
                                           name="established_year"
                                           id="established_year"
                                           value="{{ old('established_year', $school->established_year ?? '') }}"
                                           class="diary-control with-icon @error('established_year') is-invalid @enderror"
                                           placeholder="e.g. 1998"
                                           min="1800"
                                           max="{{ date('Y') }}"
                                           @error('established_year') aria-invalid="true" aria-describedby="established_year_error" @enderror>
                                </div>

                                @error('established_year')
                                    <div class="field-error" id="established_year_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- CONTACT & ADDRESS --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-location-dot"></i>
                            </div>

                            <div>
                                <h4>Contact & Address</h4>
                                <span>How parents and staff can reach the school.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- PHONE --}}
                            <div class="diary-filter-group">
                                <label for="phone">
                                    Phone <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>

                                    <input type="tel"
                                           name="phone"
                                           id="phone"
                                           value="{{ old('phone', $school->phone ?? '') }}"
                                           class="diary-control with-icon @error('phone') is-invalid @enderror"
                                           placeholder="e.g. +91 98765 43210"
                                           maxlength="20"
                                           required
                                           @error('phone') aria-invalid="true" aria-describedby="phone_error" @enderror>
                                </div>

                                @error('phone')
                                    <div class="field-error" id="phone_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- EMAIL --}}
                            <div class="diary-filter-group">
                                <label for="email">
                                    Email <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           value="{{ old('email', $school->email ?? '') }}"
                                           class="diary-control with-icon @error('email') is-invalid @enderror"
                                           placeholder="e.g. info@school.edu"
                                           maxlength="150"
                                           required
                                           @error('email') aria-invalid="true" aria-describedby="email_error" @enderror>
                                </div>

                                @error('email')
                                    <div class="field-error" id="email_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- WEBSITE --}}
                            <div class="diary-filter-group">
                                <label for="website">Website</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-globe input-icon"></i>

                                    <input type="url"
                                           name="website"
                                           id="website"
                                           value="{{ old('website', $school->website ?? '') }}"
                                           class="diary-control with-icon @error('website') is-invalid @enderror"
                                           placeholder="https://www.school.edu"
                                           maxlength="255"
                                           @error('website') aria-invalid="true" aria-describedby="website_error" @enderror>
                                </div>

                                @error('website')
                                    <div class="field-error" id="website_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- CITY --}}
                            <div class="diary-filter-group">
                                <label for="city">City</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-city input-icon"></i>

                                    <input type="text"
                                           name="city"
                                           id="city"
                                           value="{{ old('city', $school->city ?? '') }}"
                                           class="diary-control with-icon @error('city') is-invalid @enderror"
                                           placeholder="e.g. Jaipur"
                                           maxlength="100"
                                           @error('city') aria-invalid="true" aria-describedby="city_error" @enderror>
                                </div>

                                @error('city')
                                    <div class="field-error" id="city_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- ADDRESS (full width) --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="address">Address</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt input-icon"></i>

                                    <input type="text"
                                           name="address"
                                           id="address"
                                           value="{{ old('address', $school->address ?? '') }}"
                                           class="diary-control with-icon @error('address') is-invalid @enderror"
                                           placeholder="Street, Area, Landmark"
                                           maxlength="500"
                                           @error('address') aria-invalid="true" aria-describedby="address_error" @enderror>
                                </div>

                                @error('address')
                                    <div class="field-error" id="address_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- BRANDING / LOGO --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-image"></i>
                            </div>

                            <div>
                                <h4>Branding</h4>
                                <span>Upload or replace the school logo.</span>
                            </div>
                        </div>

                        <div class="logo-upload-card">
                            <div class="logo-preview-wrap">
                                @if(!empty($school->logo))
                                    <img src="{{ asset('storage/' . $school->logo) }}"
                                         alt="Current school logo"
                                         class="logo-preview"
                                         id="logoPreview">
                                @else
                                    <div class="logo-placeholder" id="logoPreview">
                                        <i class="fas fa-image"></i>
                                        <span>No logo</span>
                                    </div>
                                @endif
                            </div>

                            <div class="logo-upload-body">
                                <label for="logo" class="logo-upload-label">
                                    School Logo
                                </label>

                                <input type="file"
                                       name="logo"
                                       id="logo"
                                       class="diary-file-input @error('logo') is-invalid @enderror"
                                       accept="image/png,image/jpeg,image/jpg,image/webp"
                                       @error('logo') aria-invalid="true" aria-describedby="logo_error" @enderror>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Recommended: square image, PNG or JPG, max 2 MB.
                                </div>

                                @error('logo')
                                    <div class="field-error" id="logo_error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ADDITIONAL INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-align-left"></i>
                            </div>

                            <div>
                                <h4>Additional Information</h4>
                                <span>Optional notes about the school.</span>
                            </div>
                        </div>

                        <div class="description-card">

                            <div class="description-header">
                                <div class="description-icon">
                                    <i class="fas fa-pen"></i>
                                </div>

                                <label for="description">About / Description</label>

                                <span class="optional-badge">Optional</span>
                            </div>

                            <textarea name="description"
                                      id="description"
                                      rows="5"
                                      maxlength="2000"
                                      class="diary-textarea @error('description') is-invalid @enderror"
                                      placeholder="Brief description, motto, or other useful information about the school..."
                                      @error('description') aria-invalid="true" aria-describedby="description_error" @enderror>{{ old('description', $school->description ?? '') }}</textarea>

                            <div class="description-footer">
                                <div class="field-help">
                                    <i class="fas fa-lightbulb"></i>
                                    This text may appear on reports or the public profile.
                                </div>

                                <span class="character-count">
                                    <span id="descriptionCount">0</span>/2000
                                </span>
                            </div>

                            @error('description')
                                <div class="field-error" id="description_error">
                                    <i class="fas fa-circle-exclamation"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.settings.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="diary-primary-btn"
                            id="saveSchoolBtn">
                        <i class="fas fa-check"></i>
                        Update School Profile
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   SCHOOL PROFILE — EDIT FORM PAGE
   Same tokens, cards and buttons as Meal Items / Sports.
   Every selector is scoped under .school-form-page.
========================================================= */

.school-form-page {
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

.school-form-page *,
.school-form-page *::before,
.school-form-page *::after {
    box-sizing: border-box;
}

.school-form-page a,
.school-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.school-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.school-form-page .form-hero,
.school-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.school-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.school-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.school-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.school-form-page .form-hero-icon {
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

.school-form-page .form-hero-text {
    min-width: 0;
}

.school-form-page .form-breadcrumb {
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

.school-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.school-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.school-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.school-form-page .form-hero-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.school-form-page .diary-primary-btn,
.school-form-page .diary-reset-btn {
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

.school-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.school-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.school-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.school-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.school-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* SUCCESS & ERROR ALERTS */

.school-form-page .form-success-alert,
.school-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
}

.school-form-page .form-success-alert {
    border: 1px solid #cfe5d3;
    color: #2f6b45;
    background: #edf7ed;
}

.school-form-page .form-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.school-form-page .success-icon,
.school-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.school-form-page .success-icon {
    background: #d8f0dc;
}

.school-form-page .error-icon {
    background: #fbe0d8;
}

.school-form-page .success-copy,
.school-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.school-form-page .success-copy strong,
.school-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.school-form-page .success-copy p {
    margin: 0;
    font-weight: 600;
    line-height: 1.5;
}

.school-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.school-form-page .alert-close {
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

.school-form-page .alert-close:hover {
    color: var(--ink);
    background: rgba(0, 0, 0, .04);
}

/* FORM CARD */

.school-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.school-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.school-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.school-form-page .panel-title-icon {
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

.school-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.school-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.school-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.school-form-page .required-info {
    flex-shrink: 0;
    padding: 6px 11px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #faf9f4;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.school-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */

.school-form-page .form-body {
    padding: 24px 22px 8px;
}

.school-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.school-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.school-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.school-form-page .section-heading-icon {
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

.school-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.school-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */

.school-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.school-form-page .form-grid-full {
    grid-column: 1 / -1;
}

.school-form-page .diary-filter-group {
    min-width: 0;
}

.school-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.school-form-page .required {
    color: var(--red);
}

.school-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.school-form-page .input-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    z-index: 2;
    width: 15px;
    transform: translateY(-50%);
    color: #929b8f;
    font-size: 12px;
    pointer-events: none;
    transition: color .18s ease;
}

.school-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.school-form-page .diary-control,
.school-form-page .diary-textarea {
    display: block;
    width: 100%;
    min-width: 0;
    border: 1px solid #deded2;
    border-radius: 7px;
    outline: none;
    color: var(--text);
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    box-shadow: none;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.school-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.school-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.school-form-page select.diary-control {
    cursor: pointer;
}

.school-form-page .diary-control::placeholder,
.school-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.school-form-page .diary-control:hover,
.school-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.school-form-page .diary-control:focus,
.school-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.school-form-page .diary-control.is-invalid,
.school-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.school-form-page .diary-control.is-invalid:focus,
.school-form-page .diary-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(189, 81, 72, .10);
}

/* HELP AND ERROR TEXT */

.school-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.school-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.school-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.school-form-page .field-error i {
    margin-top: 2px;
}

/* LOGO UPLOAD */

.school-form-page .logo-upload-card {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 18px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.school-form-page .logo-preview-wrap {
    flex: 0 0 110px;
}

.school-form-page .logo-preview {
    width: 110px;
    height: 110px;
    object-fit: contain;
    border: 1px solid #e6dfce;
    border-radius: 10px;
    background: #fffefa;
    padding: 8px;
}

.school-form-page .logo-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 110px;
    height: 110px;
    border: 1px dashed #d8d1bf;
    border-radius: 10px;
    color: var(--muted);
    background: #fffefa;
    font-size: 11px;
    font-weight: 600;
}

.school-form-page .logo-placeholder i {
    font-size: 22px;
    color: #b8b09e;
}

.school-form-page .logo-upload-body {
    flex: 1;
    min-width: 0;
}

.school-form-page .logo-upload-label {
    display: block;
    margin-bottom: 10px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.school-form-page .diary-file-input {
    display: block;
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #deded2;
    border-radius: 7px;
    background: #fffefa;
    font-family: inherit;
    font-size: 12px;
    color: var(--text);
    cursor: pointer;
}

.school-form-page .diary-file-input:hover {
    border-color: #bfcbbd;
}

.school-form-page .diary-file-input:focus {
    outline: none;
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.school-form-page .diary-file-input.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

/* DESCRIPTION CARD */

.school-form-page .description-card {
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
    transition: border-color .18s ease, box-shadow .18s ease;
}

.school-form-page .description-card:focus-within {
    border-color: #b7c4b7;
    box-shadow: 0 3px 12px rgba(58, 128, 96, .06);
}

.school-form-page .description-header {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 9px;
    margin-bottom: 11px;
}

.school-form-page .description-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 29px;
    height: 29px;
    border: 1px solid #e6dfce;
    border-radius: 7px;
    color: var(--ink);
    background: var(--chalk);
    font-size: 11px;
}

.school-form-page .description-header label {
    margin: 0;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.school-form-page .optional-badge {
    padding: 3px 9px;
    border: 1px solid var(--line);
    border-radius: 20px;
    color: var(--muted);
    background: #fffefa;
    font-size: 9px;
    font-weight: 700;
}

.school-form-page .diary-textarea {
    min-height: 125px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.school-form-page .description-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 9px;
}

.school-form-page .description-footer .field-help {
    margin-top: 0;
}

.school-form-page .character-count {
    flex-shrink: 0;
    color: var(--muted);
    font-size: 10px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

/* ACTION BAR */

.school-form-page .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    margin-top: 8px;
    padding: 15px 22px;
    border-top: 1px dashed #d8d1bf;
    background: #faf9f4;
}

/* TABLET */

@media (max-width: 900px) {
    .school-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .school-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
        flex-wrap: wrap;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .school-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .school-form-page .form-hero::before {
        left: 22px;
    }

    .school-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .school-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .school-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .school-form-page .form-hero-text p {
        font-size: 12px;
    }

    .school-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
        flex-direction: column;
    }

    .school-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .school-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .school-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .school-form-page .required-info {
        display: none;
    }

    .school-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .school-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .school-form-page .diary-control,
    .school-form-page .diary-textarea {
        font-size: 16px; /* prevents iOS zoom */
    }

    .school-form-page .logo-upload-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .school-form-page .description-card {
        padding: 12px;
    }

    .school-form-page .description-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .school-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .school-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .school-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .school-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */

.school-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.school-form-page .diary-control:focus-visible,
.school-form-page .diary-textarea:focus-visible,
.school-form-page .diary-file-input:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .school-form-page *,
    .school-form-page *::before,
    .school-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var description = document.getElementById('description');
    var counter = document.getElementById('descriptionCount');
    var form = document.getElementById('schoolForm');
    var saveBtn = document.getElementById('saveSchoolBtn');
    var logoInput = document.getElementById('logo');
    var logoPreview = document.getElementById('logoPreview');

    /* Description character counter */
    if (description && counter) {
        var updateCounter = function () {
            counter.textContent = description.value.length;
        };

        description.addEventListener('input', updateCounter);
        updateCounter();
    }

    /* Live logo preview */
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function () {
            var file = this.files && this.files[0];

            if (!file) return;

            if (!file.type.match(/^image\//)) return;

            var reader = new FileReader();

            reader.onload = function (e) {
                if (logoPreview.tagName === 'IMG') {
                    logoPreview.src = e.target.result;
                } else {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Logo preview';
                    img.className = 'logo-preview';
                    img.id = 'logoPreview';
                    logoPreview.replaceWith(img);
                }
            };

            reader.readAsDataURL(file);
        });
    }

    /* Prevent double submit */
    if (form && saveBtn) {
        form.addEventListener('submit', function () {
            if (!form.checkValidity()) {
                return;
            }

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        });
    }
});
</script>

@endsection