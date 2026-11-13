@extends('layouts.app')

@section('title', 'Add Staff Member | Admin')
@section('page-title', 'Add Staff Member')

@section('content')

<div class="staff-form-page">
    <div class="container-fluid py-4">

        {{-- SCHOOL DIARY PAGE HEADER --}}
        <section class="form-hero">
            <div class="form-hero-content">
                <div class="form-hero-icon">
                    <i class="fas fa-user-plus"></i>
                </div>

                <div class="form-hero-text">
                    <div class="form-breadcrumb">
                        STAFF <span>/</span> OTHER STAFF <span>/</span> ADD
                    </div>

                    <h2>Add Staff Member</h2>

                    <p>Add a new non-teaching staff member to the school.</p>
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
                        <i class="fas fa-plus"></i>
                    </div>

                    <div>
                        <div class="section-eyebrow">STAFF REGISTER</div>

                        <p>Enter personal, contact and employment details.</p>
                    </div>
                </div>

                <div class="required-info">
                    <span>*</span> Required fields
                </div>
            </div>

            <form id="staffForm"
                  action="{{ route('admin.other-staff.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

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
                                <label for="staff_id">Staff ID</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-hashtag input-icon"></i>

                                    <input type="text"
                                           name="staff_id"
                                           id="staff_id"
                                           class="diary-control with-icon"
                                           value="{{ old('staff_id', $nextStaffId) }}"
                                           required>
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Staff ID is automatically generated.
                                </div>
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
                                           placeholder="Enter full name"
                                           value="{{ old('name') }}"
                                           required
                                           @error('name') aria-invalid="true" @enderror>
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
                                            class="diary-control with-icon">
                                        <option value="">Select Gender</option>
                                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                        <option value="Other" @selected(old('gender') === 'Other')>Other</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="diary-filter-group">
                                <label for="date_of_birth">Date of Birth</label>

                                <div class="input-wrapper">
                                    <i class="fas fa-calendar input-icon"></i>

                                    <input type="date"
                                           name="date_of_birth"
                                           id="date_of_birth"
                                           class="diary-control with-icon"
                                           value="{{ old('date_of_birth') }}">
                                </div>
                            </div>

                            {{-- Profile Photo --}}
                            <div class="diary-filter-group form-grid-full">
                                <label for="profilePhoto">Profile Photo</label>

                                <div class="photo-upload-card">
                                    <div class="photo-preview-wrap">
                                        <div class="photo-placeholder" id="photoPlaceholder">
                                            <i class="fas fa-user"></i>
                                        </div>

                                        <img id="photoPreview"
                                             class="photo-preview"
                                             alt="Profile Preview"
                                             style="display: none;">
                                    </div>

                                    <div class="photo-upload-body">
                                        <input type="file"
                                               id="profilePhoto"
                                               name="profile_photo"
                                               class="diary-file-input @error('profile_photo') is-invalid @enderror"
                                               accept=".jpg,.jpeg,.png,.webp">

                                        <div class="field-help">
                                            <i class="fas fa-circle-info"></i>
                                            JPG, JPEG, PNG or WEBP. Image will be compressed to approximately 250 KB.
                                        </div>

                                        <div id="compressionStatus" class="compression-status"></div>

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
                                <label for="phone">
                                    Phone Number <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-phone input-icon"></i>

                                    <input type="tel"
                                           name="phone"
                                           id="phone"
                                           class="diary-control with-icon"
                                           placeholder="Enter 10-digit phone number"
                                           value="{{ old('phone') }}"
                                           maxlength="10"
                                           minlength="10"
                                           pattern="[0-9]{10}"
                                           inputmode="numeric"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                           title="Please enter a valid 10-digit phone number"
                                           required>
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="diary-filter-group">
                                <label for="email">
                                    Email Address <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-envelope input-icon"></i>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="diary-control with-icon @error('email') is-invalid @enderror"
                                           placeholder="Enter email address"
                                           value="{{ old('email') }}"
                                           maxlength="254"
                                           required
                                           autocomplete="email">
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
                                          class="diary-textarea"
                                          rows="3"
                                          placeholder="Enter complete address">{{ old('address') }}</textarea>
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
                                        <option value="Librarian" @selected(old('designation') === 'Librarian')>Librarian</option>
                                        <option value="Accountant" @selected(old('designation') === 'Accountant')>Accountant</option>
                                        <option value="Receptionist" @selected(old('designation') === 'Receptionist')>Receptionist</option>
                                        <option value="Peon" @selected(old('designation') === 'Peon')>Peon</option>
                                        <option value="Driver" @selected(old('designation') === 'Driver')>Driver</option>
                                        <option value="Other" @selected(old('designation') === 'Other')>Other</option>
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
                                           class="diary-control with-icon is-readonly"
                                           placeholder="Select designation first"
                                           value="{{ old('department') }}"
                                           readonly
                                           tabindex="-1">
                                </div>

                                <div class="field-help">
                                    <i class="fas fa-circle-info"></i>
                                    Department is assigned automatically according to designation.
                                </div>
                            </div>

                            {{-- Role-specific section --}}
                            <div id="roleSpecificSection"
                                 class="role-specific-section form-grid-full">

                                <div class="role-specific-header">
                                    <div class="role-specific-icon">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>

                                    <div>
                                        <h4 id="roleSpecificTitle">Role Information</h4>
                                        <p id="roleSpecificDescription">
                                            Select a designation to see the fields for that role.
                                        </p>
                                    </div>
                                </div>

                                <div id="roleSpecificFields"
                                     class="role-specific-grid"></div>
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
                                           value="{{ old('joining_date') }}"
                                           max="{{ date('Y-m-d') }}">
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

                            {{-- Employment Status --}}
                            <div class="diary-filter-group">
                                <label for="status">
                                    Employment Status <span class="required">*</span>
                                </label>

                                <div class="input-wrapper">
                                    <i class="fas fa-toggle-on input-icon"></i>

                                    <select name="status"
                                            id="status"
                                            class="diary-control with-icon"
                                            required>
                                        <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                                        <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                                    </select>
                                </div>
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
                            id="saveButton"
                            class="diary-primary-btn">
                        <i class="fas fa-check"></i>
                        Save Staff
                    </button>
                </div>

            </form>
        </section>

    </div>
</div>

<style>
/* =========================================================
   LOCKED SCHOOL DIARY DESIGN SYSTEM
   OTHER STAFF — ADD STAFF MEMBER
   Same tokens, cards and buttons as Roles / Security / Backup.
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

/* ERROR ALERT */

.staff-form-page .form-error-alert {
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
}

.staff-form-page .error-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 27px;
    width: 27px;
    height: 27px;
    border-radius: 50%;
    background: #fbe0d8;
}

.staff-form-page .error-copy {
    flex: 1;
    min-width: 0;
}

.staff-form-page .error-copy strong {
    display: block;
    margin-bottom: 4px;
    font-weight: 800;
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
    background: #f8e3dc;
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

.staff-form-page .diary-control.is-readonly {
    background: #f5f4eb;
    color: #68766d;
    cursor: not-allowed;
    border-color: #e3e2d5;
}

.staff-form-page .diary-control.is-readonly:hover,
.staff-form-page .diary-control.is-readonly:focus {
    border-color: #e3e2d5;
    box-shadow: none;
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
    flex: 0 0 82px;
}

.staff-form-page .photo-placeholder,
.staff-form-page .photo-preview {
    width: 82px;
    height: 82px;
    border-radius: 50%;
    border: 2px solid #e6dfce;
}

.staff-form-page .photo-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink);
    background: var(--chalk);
    font-size: 26px;
}

.staff-form-page .photo-preview {
    object-fit: cover;
    background: #fffefa;
}

.staff-form-page .photo-upload-body {
    flex: 1;
    min-width: 0;
}

.staff-form-page .compression-status {
    margin-top: 6px;
    font-size: 11px;
    font-weight: 600;
}

.staff-form-page .compression-success {
    color: var(--green);
}

.staff-form-page .compression-warning {
    color: var(--amber);
}

/* ROLE SPECIFIC SECTION */

.staff-form-page .role-specific-section {
    display: none;
    margin-top: 4px;
    padding: 18px;
    border: 1px solid #e6dfc9;
    border-radius: 9px;
    background: #faf5e7;
}

.staff-form-page .role-specific-section.show {
    display: block;
}

.staff-form-page .role-specific-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.staff-form-page .role-specific-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 32px;
    width: 32px;
    height: 32px;
    border: 1px solid #e6dfc9;
    border-radius: 8px;
    color: var(--amber);
    background: #fffefa;
    font-size: 13px;
}

.staff-form-page .role-specific-header h4 {
    margin: 0;
    color: #765e32;
    font-family: var(--serif);
    font-size: 14px;
    font-weight: 700;
}

.staff-form-page .role-specific-header p {
    margin: 4px 0 0;
    color: #8a7447;
    font-size: 11px;
    line-height: 1.5;
}

.staff-form-page .role-specific-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
}

.staff-form-page .role-specific-grid .full {
    grid-column: 1 / -1;
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

    .staff-form-page .role-specific-grid {
        grid-template-columns: 1fr;
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
    const roleSpecificSection = document.getElementById('roleSpecificSection');
    const roleSpecificFields = document.getElementById('roleSpecificFields');
    const roleSpecificTitle = document.getElementById('roleSpecificTitle');
    const roleSpecificDescription = document.getElementById('roleSpecificDescription');

    const departmentMap = {
        Librarian: 'Library',
        Accountant: 'Accounts',
        Receptionist: 'Administration',
        Peon: 'Maintenance',
        Driver: 'Transport',
        Other: 'Other'
    };

    const roleTemplates = {

        Driver: {
            title: 'Driver Information',
            description: 'Enter vehicle, route and driving license details.',
            fields: `
                <div class="diary-filter-group">
                    <label>Bus Number <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-bus input-icon"></i>
                        <input type="text" name="role_details[bus_number]" class="diary-control with-icon"
                               placeholder="e.g. BUS-101" value="{{ old('role_details.bus_number') }}" required>
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Vehicle Number <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-car input-icon"></i>
                        <input type="text" name="role_details[vehicle_number]" class="diary-control with-icon"
                               placeholder="e.g. MH12AB1234" value="{{ old('role_details.vehicle_number') }}" required>
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Route Name <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <i class="fas fa-route input-icon"></i>
                        <input type="text" name="role_details[route_name]" class="diary-control with-icon"
                               placeholder="e.g. Pune Station - School" value="{{ old('role_details.route_name') }}" required>
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Driving License Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="text" name="license_number" class="diary-control with-icon"
                               placeholder="Enter driving license number" value="{{ old('license_number') }}">
                    </div>
                    <div class="field-help">
                        <i class="fas fa-circle-info"></i>
                        This license number will be available when assigning this driver to a vehicle.
                    </div>
                    @if($errors->has('license_number'))
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $errors->first('license_number') }}
                        </div>
                    @endif
                </div>

                <div class="diary-filter-group">
                    <label>License Expiry Date</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-xmark input-icon"></i>
                        <input type="date" name="license_expiry" class="diary-control with-icon"
                               value="{{ old('license_expiry') }}">
                    </div>
                    @if($errors->has('license_expiry'))
                        <div class="field-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $errors->first('license_expiry') }}
                        </div>
                    @endif
                </div>

                <div class="diary-filter-group">
                    <label>Shift</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <select name="role_details[shift]" class="diary-control with-icon">
                            <option value="">Select Shift</option>
                            <option value="Morning">Morning</option>
                            <option value="General">General</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>
            `
        },

        Receptionist: {
            title: 'Receptionist Information',
            description: 'Enter reception desk and working shift details.',
            fields: `
                <div class="diary-filter-group">
                    <label>Extension Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input type="text" name="role_details[extension_number]" class="diary-control with-icon"
                               placeholder="e.g. 101" value="{{ old('role_details.extension_number') }}">
                    </div>
                </div>

                <div class="diary-filter-group form-grid-full full">
                    <label>Responsibilities</label>
                    <textarea name="role_details[responsibilities]" class="diary-textarea" rows="3"
                              placeholder="Enter receptionist responsibilities">{{ old('role_details.responsibilities') }}</textarea>
                </div>
            `
        },

        Peon: {
            title: 'Peon Information',
            description: 'Enter assigned area, shift and responsibilities.',
            fields: `
                <div class="diary-filter-group">
                    <label>Assigned Area</label>
                    <div class="input-wrapper">
                        <i class="fas fa-map-marker-alt input-icon"></i>
                        <input type="text" name="role_details[assigned_area]" class="diary-control with-icon"
                               placeholder="e.g. Main Building" value="{{ old('role_details.assigned_area') }}">
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Shift</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <select name="role_details[shift]" class="diary-control with-icon">
                            <option value="">Select Shift</option>
                            <option value="Morning">Morning</option>
                            <option value="General">General</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>

                <div class="diary-filter-group form-grid-full full">
                    <label>Duties / Responsibilities</label>
                    <textarea name="role_details[duties]" class="diary-textarea" rows="3"
                              placeholder="Enter assigned duties">{{ old('role_details.duties') }}</textarea>
                </div>
            `
        },

        Librarian: {
            title: 'Librarian Information',
            description: 'Enter library assignment and working details.',
            fields: `
                <div class="diary-filter-group">
                    <label>Library Section</label>
                    <div class="input-wrapper">
                        <i class="fas fa-book input-icon"></i>
                        <input type="text" name="role_details[library_section]" class="diary-control with-icon"
                               placeholder="e.g. Reference Section" value="{{ old('role_details.library_section') }}">
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Shift</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <select name="role_details[shift]" class="diary-control with-icon">
                            <option value="">Select Shift</option>
                            <option value="Morning">Morning</option>
                            <option value="General">General</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>

                <div class="diary-filter-group form-grid-full full">
                    <label>Library Responsibilities</label>
                    <textarea name="role_details[responsibilities]" class="diary-textarea" rows="3"
                              placeholder="Enter library responsibilities">{{ old('role_details.responsibilities') }}</textarea>
                </div>
            `
        },

        Accountant: {
            title: 'Accountant Information',
            description: 'Enter accounting and financial work details.',
            fields: `
                <div class="diary-filter-group">
                    <label>Accounting Software</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calculator input-icon"></i>
                        <input type="text" name="role_details[accounting_software]" class="diary-control with-icon"
                               placeholder="e.g. Tally" value="{{ old('role_details.accounting_software') }}">
                    </div>
                </div>

                <div class="diary-filter-group">
                    <label>Shift</label>
                    <div class="input-wrapper">
                        <i class="fas fa-clock input-icon"></i>
                        <select name="role_details[shift]" class="diary-control with-icon">
                            <option value="">Select Shift</option>
                            <option value="Morning">Morning</option>
                            <option value="General">General</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                </div>

                <div class="diary-filter-group form-grid-full full">
                    <label>Responsibilities</label>
                    <textarea name="role_details[responsibilities]" class="diary-textarea" rows="3"
                              placeholder="Enter accounting responsibilities">{{ old('role_details.responsibilities') }}</textarea>
                </div>
            `
        },

        Other: {
            title: 'Staff Role Information',
            description: 'Enter information about this staff member\'s role.',
            fields: `
                <div class="diary-filter-group form-grid-full full">
                    <label>Role Description</label>
                    <textarea name="role_details[description]" class="diary-textarea" rows="3"
                              placeholder="Describe the staff member's role and responsibilities">{{ old('role_details.description') }}</textarea>
                </div>
            `
        }
    };

    function updateRoleFields() {
        const selectedRole = designation.value;

        if (departmentMap[selectedRole]) {
            department.value = departmentMap[selectedRole];
        } else {
            department.value = '';
        }

        roleSpecificFields.innerHTML = '';

        if (!selectedRole || !roleTemplates[selectedRole]) {
            roleSpecificSection.classList.remove('show');
            return;
        }

        const role = roleTemplates[selectedRole];

        roleSpecificTitle.textContent = role.title;
        roleSpecificDescription.textContent = role.description;
        roleSpecificFields.innerHTML = role.fields;
        roleSpecificSection.classList.add('show');

        const oldRoleDetails = @json(old('role_details', []));

        Object.keys(oldRoleDetails).forEach(function (key) {
            const field = document.querySelector(`[name="role_details[${key}]"]`);
            if (field) {
                field.value = oldRoleDetails[key] ?? '';
            }
        });
    }

    designation.addEventListener('change', updateRoleFields);
    updateRoleFields();

    /* Joining date protection */
    const joiningDate = document.getElementById('joiningDate');

    joiningDate.addEventListener('change', function () {
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const selectedDate = new Date(this.value);

        if (selectedDate > today) {
            alert('Joining date cannot be in the future.');
            this.value = '';
        }
    });

    /* Profile photo preview + compression */
    const photoInput = document.getElementById('profilePhoto');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const compressionStatus = document.getElementById('compressionStatus');
    const TARGET_SIZE = 250 * 1024;

    photoInput.addEventListener('change', async function () {
        const file = this.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image.');
            this.value = '';
            return;
        }

        compressionStatus.textContent = 'Processing image...';
        compressionStatus.className = 'compression-status';

        try {
            const compressedFile = await compressImage(file);

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(compressedFile);
            photoInput.files = dataTransfer.files;

            const previewUrl = URL.createObjectURL(compressedFile);
            photoPreview.src = previewUrl;
            photoPreview.style.display = 'block';
            photoPlaceholder.style.display = 'none';

            const sizeKB = compressedFile.size / 1024;

            if (compressedFile.size <= TARGET_SIZE) {
                compressionStatus.textContent = '✓ Compressed successfully: ' + sizeKB.toFixed(0) + ' KB';
                compressionStatus.className = 'compression-status compression-success';
            } else {
                compressionStatus.textContent = 'Image compressed to ' + sizeKB.toFixed(0) + ' KB';
                compressionStatus.className = 'compression-status compression-warning';
            }
        } catch (error) {
            console.error(error);
            compressionStatus.textContent = 'Unable to compress image. Please choose another image.';
            compressionStatus.className = 'compression-status compression-warning';
            this.value = '';
        }
    });

    async function compressImage(file) {
        const image = await loadImage(file);
        let width = image.width;
        let height = image.height;
        const maxWidth = 1200;
        const maxHeight = 1200;

        if (width > maxWidth || height > maxHeight) {
            const ratio = Math.min(maxWidth / width, maxHeight / height);
            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }

        let quality = 0.85;
        let blob = await createBlob(image, width, height, quality);

        while (blob.size > TARGET_SIZE && quality > 0.35) {
            quality -= 0.05;
            blob = await createBlob(image, width, height, quality);
        }

        while (blob.size > TARGET_SIZE && width > 600) {
            width = Math.round(width * 0.85);
            height = Math.round(height * 0.85);
            quality = 0.75;
            blob = await createBlob(image, width, height, quality);

            while (blob.size > TARGET_SIZE && quality > 0.35) {
                quality -= 0.05;
                blob = await createBlob(image, width, height, quality);
            }
        }

        return new File([blob], 'staff-photo.jpg', {
            type: 'image/jpeg',
            lastModified: Date.now()
        });
    }

    function loadImage(file) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            const url = URL.createObjectURL(file);

            img.onload = function () {
                URL.revokeObjectURL(url);
                resolve(img);
            };

            img.onerror = reject;
            img.src = url;
        });
    }

    function createBlob(image, width, height, quality) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const context = canvas.getContext('2d');
            context.drawImage(image, 0, 0, width, height);

            canvas.toBlob(resolve, 'image/jpeg', quality);
        });
    }

    /* Prevent double submission */
    const form = document.getElementById('staffForm');
    const saveButton = document.getElementById('saveButton');

    form.addEventListener('submit', function () {
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    });

});
</script>

@endsection