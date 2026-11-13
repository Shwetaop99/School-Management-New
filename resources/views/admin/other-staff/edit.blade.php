@extends('layouts.app')

@section('title', 'Edit Staff | Admin')
@section('page-title', 'Edit Staff')

@section('content')

<div class="staff-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-user-pen"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        STAFF <span>/</span> OTHER STAFF <span>/</span> EDIT
                    </div>

                    <h2>Edit Staff</h2>

                    <p>Update staff information and employment details.</p>
                </div>
            </div>

            <div class="form-hero-actions">
                <a href="{{ route('admin.other-staff.index') }}"
                   class="diary-reset-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Staff
                </a>
            </div>
        </section>

        {{-- FLASH / VALIDATION ALERTS --}}
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

        @if(session('error'))
            <div class="form-error-alert" role="alert">
                <span class="error-icon">
                    <i class="fas fa-exclamation"></i>
                </span>

                <div class="error-copy">
                    <strong>Error</strong>
                    <p>{{ session('error') }}</p>
                </div>

                <button type="button"
                        class="alert-close"
                        aria-label="Close alert"
                        onclick="this.parentElement.remove();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

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
                        <div class="section-eyebrow">STAFF REGISTER</div>
                        <h3>Update Staff Member</h3>
                        <p>Edit personal, contact and employment details.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form action="{{ route('admin.other-staff.update', $otherStaff->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="editStaffForm">
                @csrf
                @method('PUT')

                <div class="form-body">

                    {{-- PERSONAL INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <h4>Personal Information</h4>
                                <span>Basic information about the staff member.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- Staff ID --}}
                            <div class="diary-filter-group">
                                <label for="staff_id">
                                    Staff ID <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-hashtag input-icon"></i>

                                    <input type="text"
                                           name="staff_id"
                                           id="staff_id"
                                           class="diary-control with-icon @error('staff_id') is-invalid @enderror"
                                           value="{{ old('staff_id', $otherStaff->staff_id) }}"
                                           required>
                                </div>

                                @error('staff_id')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Full Name --}}
                            <div class="diary-filter-group">
                                <label for="name">
                                    Full Name <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-signature input-icon"></i>

                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="diary-control with-icon @error('name') is-invalid @enderror"
                                           value="{{ old('name', $otherStaff->name) }}"
                                           required>
                                </div>

                                @error('name')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div class="diary-filter-group">
                                <label for="gender">Gender</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-venus-mars input-icon"></i>

                                    <select name="gender"
                                            id="gender"
                                            class="diary-control with-icon @error('gender') is-invalid @enderror">
                                        <option value="">Select Gender</option>
                                        <option value="Male" @selected(old('gender', $otherStaff->gender) == 'Male')>Male</option>
                                        <option value="Female" @selected(old('gender', $otherStaff->gender) == 'Female')>Female</option>
                                        <option value="Other" @selected(old('gender', $otherStaff->gender) == 'Other')>Other</option>
                                    </select>
                                </div>

                                @error('gender')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div class="diary-filter-group">
                                <label for="date_of_birth">Date of Birth</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar input-icon"></i>

                                    <input type="date"
                                           name="date_of_birth"
                                           id="date_of_birth"
                                           class="diary-control with-icon @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth', optional($otherStaff->date_of_birth)->format('Y-m-d')) }}">
                                </div>

                                @error('date_of_birth')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Profile Photo --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="profilePhoto">Profile Photo</label>

                                <div class="photo-upload-card">
                                    <div class="photo-preview-wrap" id="photoPreview">
                                        @if($otherStaff->profile_photo)
                                            <img src="{{ asset('storage/' . $otherStaff->profile_photo) }}"
                                                 alt="Profile Photo"
                                                 class="photo-preview"
                                                 id="previewImage">
                                        @else
                                            <div class="photo-placeholder" id="photoPlaceholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="photo-upload-body">
                                        <input type="file"
                                               name="profile_photo"
                                               id="profilePhoto"
                                               class="diary-file-input @error('profile_photo') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/webp">

                                        <div class="field-help">
                                            <i class="fas fa-circle-info"></i>
                                            JPG, PNG or WEBP. Maximum 2 MB. New image will be compressed before upload.
                                        </div>

                                        @error('profile_photo')
                                            <div class="field-error">
                                                <i class="fas fa-circle-exclamation"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- CONTACT INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-address-book"></i>
                            </div>

                            <div>
                                <h4>Contact Information</h4>
                                <span>Contact details and residential address.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- Phone --}}
                            <div class="diary-filter-group">
                                <label for="phone">Phone</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>

                                    <input type="text"
                                           name="phone"
                                           id="phone"
                                           class="diary-control with-icon @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $otherStaff->phone) }}"
                                           maxlength="20">
                                </div>

                                @error('phone')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="diary-filter-group">
                                <label for="email">Email</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="diary-control with-icon @error('email') is-invalid @enderror"
                                           value="{{ old('email', $otherStaff->email) }}">
                                </div>

                                @error('email')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Address --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="address">Address</label>

                                <textarea name="address"
                                          id="address"
                                          class="diary-textarea @error('address') is-invalid @enderror"
                                          rows="3">{{ old('address', $otherStaff->address) }}</textarea>

                                @error('address')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- EMPLOYMENT INFORMATION --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-heading-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>

                            <div>
                                <h4>Employment Information</h4>
                                <span>Role and employment details.</span>
                            </div>
                        </div>

                        <div class="form-grid">

                            {{-- Designation --}}
                            <div class="diary-filter-group">
                                <label for="designation">
                                    Designation <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-id-badge input-icon"></i>

                                    <select name="designation"
                                            id="designation"
                                            class="diary-control with-icon @error('designation') is-invalid @enderror"
                                            required>
                                        <option value="">Select Designation</option>

                                        @foreach(['Librarian', 'Accountant', 'Receptionist', 'Peon', 'Driver', 'Other'] as $designation)
                                            <option value="{{ $designation }}"
                                                @selected(old('designation', $otherStaff->designation) == $designation)>
                                                {{ $designation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('designation')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Department --}}
                            <div class="diary-filter-group">
                                <label for="department">Department</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-building input-icon"></i>

                                    <input type="text"
                                           name="department"
                                           id="department"
                                           class="diary-control with-icon @error('department') is-invalid @enderror"
                                           value="{{ old('department', $otherStaff->department) }}">
                                </div>

                                @error('department')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Qualification --}}
                            <div class="diary-filter-group">
                                <label for="qualification">Qualification</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-graduation-cap input-icon"></i>

                                    <input type="text"
                                           name="qualification"
                                           id="qualification"
                                           class="diary-control with-icon @error('qualification') is-invalid @enderror"
                                           value="{{ old('qualification', $otherStaff->qualification) }}">
                                </div>

                                @error('qualification')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Joining Date --}}
                            <div class="diary-filter-group">
                                <label for="joiningDate">Joining Date</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-check input-icon"></i>

                                    <input type="date"
                                           name="joining_date"
                                           id="joiningDate"
                                           class="diary-control with-icon @error('joining_date') is-invalid @enderror"
                                           value="{{ old('joining_date', optional($otherStaff->joining_date)->format('Y-m-d')) }}">
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Future joining dates are not allowed.
                                </div>

                                @error('joining_date')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Status <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon @error('status') is-invalid @enderror"
                                            required>
                                        <option value="Active" @selected(old('status', $otherStaff->status) == 'Active')>Active</option>
                                        <option value="Inactive" @selected(old('status', $otherStaff->status) == 'Inactive')>Inactive</option>
                                    </select>
                                </div>

                                @error('status')
                                    <div class="field-error">
                                        <i class="fas fa-circle-exclamation"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM ACTIONS --}}
                <div class="form-actions">
                    <a href="{{ route('admin.other-staff.index') }}"
                       class="diary-reset-btn">
                        <i class="fas fa-xmark"></i>
                        Cancel
                    </a>

                    <button type="submit"
                            class="diary-primary-btn"
                            id="updateBtn">
                        <i class="fas fa-check"></i>
                        Update Staff
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   OTHER STAFF — EDIT STAFF MEMBER
   Same tokens, cards and buttons as Add Staff / Roles.
   Every selector is scoped under .staff-form-page.
========================================================= */

.staff-form-page {
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

.staff-form-page *,
.staff-form-page *::before,
.staff-form-page *::after {
    box-sizing: border-box;
}

.staff-form-page a,
.staff-form-page button {
    transition: color .18s ease, background .18s ease,
        border-color .18s ease, transform .18s ease,
        box-shadow .18s ease;
}

.staff-form-page .container-fluid {
    width: 100%;
    max-width: 1650px;
    margin: 0 auto;
}

.staff-form-page .form-hero,
.staff-form-page .form-card {
    border: 1px solid var(--line);
    border-radius: 10px;
    background: var(--paper);
}

/* PAGE HEADER */

.staff-form-page .form-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 5px 18px rgba(38, 59, 53, .045);
}

.staff-form-page .form-hero::before {
    content: "";
    position: absolute;
    z-index: 1;
    top: 0;
    bottom: 0;
    left: 34px;
    width: 2px;
    background: rgba(189, 81, 72, .35);
}

.staff-form-page .form-hero-content {
    display: flex;
    align-items: center;
    gap: 17px;
    min-width: 0;
    padding: 26px 28px 26px 56px;
    background:
        radial-gradient(ellipse at top left, rgba(244, 240, 223, .65), transparent 65%),
        var(--paper);
}

.staff-form-page .form-hero-icon {
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

.staff-form-page .form-hero-text {
    min-width: 0;
}

.staff-form-page .form-breadcrumb {
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

.staff-form-page .form-breadcrumb span {
    color: #b8b09e;
}

.staff-form-page .form-hero-text h2 {
    margin: 0 0 7px;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    letter-spacing: -.45px;
}

.staff-form-page .form-hero-text p {
    margin: 0;
    color: var(--muted);
    font-size: 13px;
    line-height: 1.75;
}

.staff-form-page .form-hero-actions {
    padding: 0 28px;
}

/* LOCKED BUTTON STYLES */

.staff-form-page .diary-primary-btn,
.staff-form-page .diary-reset-btn {
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

.staff-form-page .diary-primary-btn {
    border: 1px solid var(--ink);
    color: #fff !important;
    background: var(--ink);
}

.staff-form-page .diary-primary-btn:hover {
    transform: translateY(-2px);
    border-color: var(--ink-hover);
    color: #fff !important;
    background: var(--ink-hover);
    box-shadow: 0 5px 13px rgba(29, 48, 43, .14);
}

.staff-form-page .diary-primary-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.staff-form-page .diary-reset-btn {
    border: 1px solid #deded2;
    color: #59675d !important;
    background: #fffefa;
}

.staff-form-page .diary-reset-btn:hover {
    border-color: #b7c4b7;
    color: var(--ink) !important;
    background: #f5f4eb;
}

/* ALERTS */

.staff-form-page .form-success-alert,
.staff-form-page .form-error-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
    padding: 13px 16px;
    border-radius: 9px;
    font-size: 12px;
}

.staff-form-page .form-success-alert {
    border: 1px solid #cfe5d3;
    color: #2f6b45;
    background: #edf7ed;
}

.staff-form-page .form-error-alert {
    border: 1px solid #f0d5ce;
    color: #a84840;
    background: #fff4ef;
}

.staff-form-page .success-icon,
.staff-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
}

.staff-form-page .success-icon {
    background: #d8f0dc;
}

.staff-form-page .error-icon {
    background: #fbe0d8;
}

.staff-form-page .success-copy,
.staff-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.staff-form-page .success-copy strong,
.staff-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
}

.staff-form-page .success-copy p,
.staff-form-page .error-copy p {
    margin: 0;
    font-weight: 600;
    line-height: 1.5;
}

.staff-form-page .error-copy ul {
    margin: 0;
    padding-left: 18px;
    font-weight: 600;
    line-height: 1.7;
}

.staff-form-page .alert-close {
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

.staff-form-page .alert-close:hover {
    color: var(--ink);
    background: rgba(0, 0, 0, .04);
}

/* FORM CARD */

.staff-form-page .form-card {
    overflow: hidden;
    box-shadow: 0 5px 17px rgba(38, 59, 53, .04);
}

.staff-form-page .form-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 21px;
    border-bottom: 1px dashed #d8d1bf;
    background: var(--paper);
}

.staff-form-page .panel-title-wrap {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.staff-form-page .panel-title-icon {
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

.staff-form-page .section-eyebrow {
    margin-bottom: 4px;
    color: var(--amber);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1px;
}

.staff-form-page .panel-title-wrap h3 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 700;
}

.staff-form-page .panel-title-wrap p {
    margin: 5px 0 0;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.6;
}

.staff-form-page .required-info {
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

.staff-form-page .required-info span {
    color: var(--red);
    font-size: 12px;
}

/* FORM BODY AND SECTIONS */

.staff-form-page .form-body {
    padding: 24px 22px 8px;
}

.staff-form-page .form-section {
    padding-bottom: 26px;
    margin-bottom: 26px;
    border-bottom: 1px dashed #d8d1bf;
}

.staff-form-page .form-section:last-child {
    margin-bottom: 0;
    border-bottom: 0;
}

.staff-form-page .section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 20px;
}

.staff-form-page .section-heading-icon {
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

.staff-form-page .section-heading h4 {
    margin: 0;
    color: var(--ink);
    font-family: var(--serif);
    font-size: 16px;
    font-weight: 700;
}

.staff-form-page .section-heading span {
    display: block;
    margin-top: 4px;
    color: var(--muted);
    font-size: 11px;
    line-height: 1.5;
}

/* FORM GRID AND FIELDS */

.staff-form-page .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    align-items: start;
    gap: 20px 16px;
}

.staff-form-page .form-grid-full {
    grid-column: 1 / -1;
}

.staff-form-page .diary-filter-group {
    min-width: 0;
}

.staff-form-page .diary-filter-group label {
    display: block;
    margin-bottom: 7px;
    color: #68766d;
    font-size: 11px;
    font-weight: 700;
}

.staff-form-page .required {
    color: var(--red);
}

.staff-form-page .input-wrapper {
    position: relative;
    width: 100%;
}

.staff-form-page .input-icon {
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

.staff-form-page .input-wrapper:focus-within .input-icon {
    color: var(--green);
}

.staff-form-page .diary-control,
.staff-form-page .diary-textarea {
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

.staff-form-page .diary-control {
    height: 41px;
    padding: 0 10px;
}

.staff-form-page .diary-control.with-icon {
    padding-left: 35px;
}

.staff-form-page select.diary-control {
    cursor: pointer;
}

.staff-form-page .diary-control::placeholder,
.staff-form-page .diary-textarea::placeholder {
    color: #929b8f;
}

.staff-form-page .diary-control:hover,
.staff-form-page .diary-textarea:hover {
    border-color: #bfcbbd;
}

.staff-form-page .diary-control:focus,
.staff-form-page .diary-textarea:focus {
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

.staff-form-page .diary-control.is-invalid,
.staff-form-page .diary-textarea.is-invalid {
    border-color: var(--red);
    background: #fff8f5;
}

.staff-form-page .diary-textarea {
    min-height: 90px;
    padding: 11px 12px;
    line-height: 1.7;
    resize: vertical;
}

.staff-form-page .diary-file-input {
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

.staff-form-page .diary-file-input:hover {
    border-color: #bfcbbd;
}

.staff-form-page .diary-file-input:focus {
    outline: none;
    border-color: #789382;
    box-shadow: 0 0 0 3px rgba(58, 128, 96, .11);
}

/* HELP AND ERROR TEXT */

.staff-form-page .field-help {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 7px;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.55;
}

.staff-form-page .field-help i {
    margin-top: 2px;
    color: var(--amber);
    font-size: 10px;
}

.staff-form-page .field-error {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-top: 6px;
    color: var(--red);
    font-size: 11px;
    font-weight: 600;
    line-height: 1.45;
}

.staff-form-page .field-error i {
    margin-top: 2px;
}

/* PHOTO UPLOAD */

.staff-form-page .photo-upload-card {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    padding: 16px;
    border: 1px solid var(--line);
    border-radius: 9px;
    background: #faf9f4;
}

.staff-form-page .photo-preview-wrap {
    flex: 0 0 90px;
    width: 90px;
    height: 90px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e6dfce;
    background: var(--chalk);
    display: flex;
    align-items: center;
    justify-content: center;
}

.staff-form-page .photo-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.staff-form-page .photo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: var(--ink);
    font-size: 28px;
}

.staff-form-page .photo-upload-body {
    flex: 1;
    min-width: 0;
}

/* ACTION BAR */

.staff-form-page .form-actions {
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
    .staff-form-page .form-hero {
        grid-template-columns: 1fr;
    }

    .staff-form-page .form-hero-actions {
        padding: 0 28px 24px 56px;
    }
}

/* MOBILE */

@media (max-width: 650px) {
    .staff-form-page .container-fluid {
        padding-top: 18px !important;
        padding-bottom: 18px !important;
    }

    .staff-form-page .form-hero::before {
        left: 22px;
    }

    .staff-form-page .form-hero-content {
        align-items: flex-start;
        gap: 12px;
        padding: 22px 16px 18px 42px;
    }

    .staff-form-page .form-hero-icon {
        flex-basis: 43px;
        width: 43px;
        height: 43px;
        border-radius: 9px;
        font-size: 18px;
    }

    .staff-form-page .form-hero-text h2 {
        font-size: 23px;
    }

    .staff-form-page .form-hero-text p {
        font-size: 12px;
    }

    .staff-form-page .form-hero-actions {
        padding: 0 16px 20px 42px;
    }

    .staff-form-page .form-hero-actions .diary-reset-btn {
        width: 100%;
    }

    .staff-form-page .form-panel-header {
        align-items: flex-start;
        padding: 15px;
    }

    .staff-form-page .panel-title-wrap h3 {
        font-size: 16px;
    }

    .staff-form-page .required-info {
        display: none;
    }

    .staff-form-page .form-body {
        padding: 18px 15px 6px;
    }

    .staff-form-page .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .staff-form-page .diary-control,
    .staff-form-page .diary-textarea {
        font-size: 16px;
    }

    .staff-form-page .photo-upload-card {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .staff-form-page .form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
        padding: 14px 15px;
    }

    .staff-form-page .form-actions > * {
        width: 100%;
    }
}

@media (max-width: 400px) {
    .staff-form-page .panel-title-icon {
        flex-basis: 34px;
        width: 34px;
        height: 34px;
    }

    .staff-form-page .section-heading {
        align-items: flex-start;
    }
}

/* ACCESSIBILITY */

.staff-form-page :focus-visible {
    outline: 3px solid rgba(82, 127, 121, .45);
    outline-offset: 3px;
}

.staff-form-page .diary-control:focus-visible,
.staff-form-page .diary-textarea:focus-visible,
.staff-form-page .diary-file-input:focus-visible {
    outline: none;
}

@media (prefers-reduced-motion: reduce) {
    .staff-form-page *,
    .staff-form-page *::before,
    .staff-form-page *::after {
        scroll-behavior: auto !important;
        animation: none !important;
        transition: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const designation = document.getElementById('designation');
    const department = document.getElementById('department');
    const joiningDate = document.getElementById('joiningDate');
    const photoInput = document.getElementById('profilePhoto');
    const preview = document.getElementById('photoPreview');
    const form = document.getElementById('editStaffForm');
    const updateBtn = document.getElementById('updateBtn');

    const departmentMap = {
        'Librarian': 'Library',
        'Accountant': 'Accounts',
        'Receptionist': 'Administration',
        'Peon': 'Maintenance',
        'Driver': 'Transport',
        'Other': 'Other'
    };

    /* DESIGNATION → DEPARTMENT */
    designation.addEventListener('change', function () {
        const selected = this.value;
        if (departmentMap[selected]) {
            department.value = departmentMap[selected];
        }
    });

    /* JOINING DATE CANNOT BE FUTURE */
    const today = new Date().toISOString().split('T')[0];
    joiningDate.max = today;

    joiningDate.addEventListener('change', function () {
        if (this.value > today) {
            alert('Joining date cannot be in the future.');
            this.value = '';
        }
    });

    /* PHOTO PREVIEW + COMPRESSION */
    photoInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image.');
            this.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            preview.innerHTML = `
                <img src="${event.target.result}"
                     alt="Profile Preview"
                     class="photo-preview"
                     id="previewImage">
            `;
        };
        reader.readAsDataURL(file);

        compressImage(file);
    });

    function compressImage(file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            const img = new Image();

            img.onload = function () {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;
                const maxSize = 1200;

                if (width > maxSize || height > maxSize) {
                    if (width > height) {
                        height = Math.round(height * maxSize / width);
                        width = maxSize;
                    } else {
                        width = Math.round(width * maxSize / height);
                        height = maxSize;
                    }
                }

                canvas.width = width;
                canvas.height = height;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                let quality = 0.85;

                function createBlob() {
                    canvas.toBlob(function (blob) {
                        if (!blob) return;

                        if (blob.size > 250 * 1024 && quality > 0.35) {
                            quality -= 0.10;
                            createBlob();
                            return;
                        }

                        const compressedFile = new File(
                            [blob],
                            'staff-photo.jpg',
                            { type: 'image/jpeg', lastModified: Date.now() }
                        );

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        photoInput.files = dataTransfer.files;

                    }, 'image/jpeg', quality);
                }

                createBlob();
            };

            img.src = event.target.result;
        };

        reader.readAsDataURL(file);
    }

    /* PREVENT DOUBLE SUBMISSION */
    form.addEventListener('submit', function (event) {
        if (joiningDate.value && joiningDate.value > today) {
            event.preventDefault();
            alert('Joining date cannot be in the future.');
            return;
        }

        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    });

});
</script>

@endsection