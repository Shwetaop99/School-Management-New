@extends('layouts.app')

@section('title', 'Add Teacher')

@section('content')

<style>
/* =========================================================
   MAIN CONTAINER
========================================================= */

.teacher-container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
    box-sizing: border-box;
}

/* =========================================================
   PAGE HEADER
========================================================= */

.teacher-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 24px;
    padding: 24px 28px;
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
}

.teacher-page-header h2 {
    margin: 0 0 6px;
    color: #172033;
    font-size: 28px;
    font-weight: 800;
}

.teacher-page-header p {
    margin: 0;
    color: #718096;
    font-size: 14px;
}

/* =========================================================
   BACK BUTTON
========================================================= */

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 17px;
    background: #f8fafc;
    border: 1px solid #dfe7f0;
    border-radius: 9px;
    color: #334155;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: .25s;
}

.back-button:hover {
    background: #1769d1;
    border-color: #1769d1;
    color: #fff;
}

/* =========================================================
   SINGLE FORM
========================================================= */

.teacher-form {
    background: #fff;
    border: 1px solid #e5ebf3;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .06);
    overflow: hidden;
}

/* =========================================================
   FORM SECTION
========================================================= */

.form-section {
    padding: 26px 28px;
    border-bottom: 1px solid #edf1f6;
}

.form-section:last-of-type {
    border-bottom: none;
}

/* =========================================================
   SECTION HEADER
========================================================= */

.form-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 22px;
}

.form-section-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #e7f0ff;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
}

.form-section-header h3 {
    margin: 0 0 3px;
    color: #172033;
    font-size: 17px;
    font-weight: 700;
}

.form-section-header p {
    margin: 0;
    color: #718096;
    font-size: 11px;
}

/* =========================================================
   GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.form-field {
    min-width: 0;
}

.form-field.full {
    grid-column: 1 / -1;
}

/* =========================================================
   LABEL
========================================================= */

.teacher-label {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 7px;
    color: #334155;
    font-size: 13px;
    font-weight: 700;
}

.required {
    color: #ef4444;
}

/* =========================================================
   INPUTS
========================================================= */

.teacher-input,
.teacher-select,
.teacher-textarea {
    width: 100%;
    padding: 11px 13px;
    background: #f8fafc;
    border: 1px solid #dfe7f0;
    border-radius: 9px;
    color: #172033;
    font-size: 13px;
    outline: none;
    box-sizing: border-box;
    transition: .2s;
}

.teacher-input:hover,
.teacher-select:hover,
.teacher-textarea:hover {
    border-color: #c7d7e9;
}

.teacher-input:focus,
.teacher-select:focus,
.teacher-textarea:focus {
    background: #fff;
    border-color: #1769d1;
    box-shadow: 0 0 0 3px rgba(23, 105, 209, .10);
}

.teacher-textarea {
    min-height: 110px;
    resize: vertical;
}

.is-invalid,
.validation-error {
    border-color: #dc3545 !important;
    background: #fff8f8 !important;
}

.validation-success {
    border-color: #22c55e !important;
    background: #f8fff9 !important;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
    font-weight: 600;
}

/* =========================================================
   VALIDATION WARNING
========================================================= */

.validation-warning {
    display: none;
    margin-top: 6px;
    padding: 7px 10px;
    background: #fff1f2;
    border-left: 3px solid #dc3545;
    border-radius: 6px;
    color: #dc3545;
    font-size: 11px;
    font-weight: 600;
}

.validation-warning.show {
    display: block;
}

/* =========================================================
   SUBJECT
========================================================= */

.subject-wrapper {
    position: relative;
}

.subject-wrapper::before {
    content: "\F5C3";
    font-family: "bootstrap-icons";
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #1769d1;
    font-size: 15px;
    pointer-events: none;
}

.subject-input {
    padding-left: 39px !important;
}

.subject-help {
    margin-top: 6px;
    color: #94a3b8;
    font-size: 11px;
}

/* =========================================================
   HELP
========================================================= */

.teacher-help {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 6px;
    color: #94a3b8;
    font-size: 11px;
}

/* =========================================================
   PROFILE IMAGE
========================================================= */

.profile-area {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc, #f5f9ff);
    border: 1px solid #e6edf5;
    border-radius: 14px;
    margin-bottom: 20px;
}

.profile-preview-wrapper {
    position: relative;
    width: 110px;
    height: 110px;
    flex-shrink: 0;
}

.profile-preview,
.profile-placeholder {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    box-sizing: border-box;
}

.profile-preview {
    display: block;
    border: 4px solid #fff;
    box-shadow: 0 7px 20px rgba(15, 23, 42, .14);
}

.profile-placeholder {
    background: #e7f0ff;
    color: #1769d1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    border: 4px solid #fff;
}

.preview-badge {
    position: absolute;
    right: -4px;
    bottom: 2px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #1769d1;
    border: 3px solid #fff;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.profile-info h4 {
    margin: 0 0 6px;
    color: #172033;
    font-size: 16px;
}

.profile-info p {
    margin: 0;
    color: #718096;
    font-size: 12px;
    line-height: 1.6;
}

.selected-image-name {
    margin-top: 8px;
    color: #1769d1;
    font-size: 11px;
    font-weight: 600;
    word-break: break-word;
}

/* =========================================================
   ALERT
========================================================= */

.teacher-alert {
    margin-bottom: 22px;
    padding: 15px 18px;
    border-radius: 12px;
    font-size: 13px;
}

/* =========================================================
   ACTION BUTTONS
========================================================= */

.teacher-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 22px 28px;
    background: #fbfdff;
    border-top: 1px solid #edf1f6;
}

.btn-teacher {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 11px 20px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: .25s;
}

.btn-secondary {
    background: #fff;
    border: 1px solid #dfe7f0;
    color: #475569;
}

.btn-secondary:hover {
    background: #eef3f8;
    color: #334155;
}

.btn-primary {
    min-width: 155px;
    background: linear-gradient(135deg, #1769d1, #237de0);
    color: #fff;
    box-shadow: 0 5px 14px rgba(23, 105, 209, .20);
}

.btn-primary:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 8px 20px rgba(23, 105, 209, .28);
}

.btn-primary.loading {
    pointer-events: none;
    opacity: .85;
}

/* =========================================================
   SPINNER
========================================================= */

.spinner {
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255,255,255,.45);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 800px) {

    .teacher-container {
        padding: 18px;
    }

    .teacher-page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .back-button {
        width: 100%;
        justify-content: center;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-field.full {
        grid-column: auto;
    }
}

@media (max-width: 600px) {

    .form-section {
        padding: 20px;
    }

    .profile-area {
        flex-direction: column;
        align-items: flex-start;
    }

    .teacher-actions {
        flex-direction: column;
        padding: 20px;
    }

    .btn-teacher {
        width: 100%;
    }
}
</style>

<div class="teacher-container">


{{-- PAGE HEADER --}}
<div class="teacher-page-header">
    <div>
        <h2>Add Teacher</h2>
        <p>Add a new teacher to the school</p>
    </div>

    <a
        href="{{ route('admin.teachers.index') }}"
        class="back-button"
    >
        <i class="bi bi-arrow-left"></i>
        Back to Teachers
    </a>
</div>


{{-- VALIDATION ERRORS --}}
@if ($errors->any())
    <div class="alert alert-danger teacher-alert">
        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- FORM --}}
<form
    action="{{ route('admin.teachers.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="teacherCreateForm"
    class="teacher-form"
>

    @csrf


    {{-- =================================================
         PERSONAL INFORMATION
    ================================================== --}}

    <div class="form-section">

        <div class="form-section-header">

            <div class="form-section-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <div>
                <h3>Personal Information</h3>
                <p>Basic information about the teacher</p>
            </div>

        </div>


        <div class="form-grid">

            {{-- FIRST NAME --}}
            <div class="form-field">

                <label class="teacher-label">
                    First Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="first_name"
                    class="teacher-input @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name') }}"
                    placeholder="Enter first name"
                    required
                >

                @error('first_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- LAST NAME --}}
            <div class="form-field">

                <label class="teacher-label">
                    Last Name
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="last_name"
                    class="teacher-input @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name') }}"
                    placeholder="Enter last name"
                    required
                >

                @error('last_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- DATE OF BIRTH --}}
            <div class="form-field">

                <label class="teacher-label">
                    Date of Birth
                </label>

                <input
                    type="date"
                    name="date_of_birth"
                    class="teacher-input @error('date_of_birth') is-invalid @enderror"
                    value="{{ old('date_of_birth') }}"
                >

                @error('date_of_birth')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- GENDER --}}
            <div class="form-field">

                <label class="teacher-label">
                    Gender
                </label>

                <select
                    name="gender"
                    class="teacher-select @error('gender') is-invalid @enderror"
                >
                    <option value="">Select Gender</option>

                    <option
                        value="Male"
                        {{ old('gender') == 'Male' ? 'selected' : '' }}
                    >
                        Male
                    </option>

                    <option
                        value="Female"
                        {{ old('gender') == 'Female' ? 'selected' : '' }}
                    >
                        Female
                    </option>

                    <option
                        value="Other"
                        {{ old('gender') == 'Other' ? 'selected' : '' }}
                    >
                        Other
                    </option>
                </select>

                @error('gender')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>
    </div>


    {{-- =================================================
         CONTACT INFORMATION
    ================================================== --}}

    <div class="form-section">

        <div class="form-section-header">

            <div class="form-section-icon">
                <i class="bi bi-person-lines-fill"></i>
            </div>

            <div>
                <h3>Contact Information</h3>
                <p>Teacher contact details</p>
            </div>

        </div>


        <div class="form-grid">

            {{-- EMAIL --}}
            <div class="form-field">

                <label class="teacher-label">
                    Email
                    <span class="required">*</span>
                </label>

                <input
                    type="email"
                    name="email"
                    id="teacherEmail"
                    class="teacher-input @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="example@gmail.com"
                    required
                >

                <div
                    id="emailWarning"
                    class="validation-warning"
                ></div>

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- PHONE --}}
            <div class="form-field">

                <label class="teacher-label">
                    Phone
                </label>

                <input
                    type="text"
                    name="phone"
                    id="teacherPhone"
                    class="teacher-input @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}"
                    placeholder="Enter 10 digit phone number"
                    maxlength="10"
                    inputmode="numeric"
                >

                <div
                    id="phoneWarning"
                    class="validation-warning"
                ></div>

                @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- ADDRESS --}}
            <div class="form-field full">

                <label class="teacher-label">
                    Address
                </label>

                <textarea
                    name="address"
                    id="teacherAddress"
                    rows="4"
                    maxlength="1000"
                    class="teacher-textarea @error('address') is-invalid @enderror"
                    placeholder="Enter complete address"
                >{{ old('address') }}</textarea>

                <div class="teacher-help">

                    <span>
                        Enter the complete residential address.
                    </span>

                    <span id="addressCounter">
                        0 / 1000
                    </span>

                </div>

                @error('address')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>
    </div>


    {{-- =================================================
         PROFESSIONAL INFORMATION
    ================================================== --}}

    <div class="form-section">

        <div class="form-section-header">

            <div class="form-section-icon">
                <i class="bi bi-briefcase-fill"></i>
            </div>

            <div>
                <h3>Professional Information</h3>
                <p>Teacher qualification and work details</p>
            </div>

        </div>


        <div class="form-grid">

            {{-- TEACHER ID --}}
            <div class="form-field">

                <label class="teacher-label">
                    Teacher ID
                </label>

                <input
                    type="text"
                    class="teacher-input"
                    value="Automatically Generated"
                    readonly
                    style="background:#eef5ff; color:#1769d1; font-weight:700; cursor:not-allowed;"
                >

                <div class="teacher-help">
                    <span>
                        Teacher ID will be generated automatically.
                    </span>
                </div>

            </div>


            {{-- STATUS --}}
            <div class="form-field">

                <label class="teacher-label">
                    Status
                    <span class="required">*</span>
                </label>

                <select
                    name="status"
                    class="teacher-select @error('status') is-invalid @enderror"
                    required
                >

                    <option
                        value="Active"
                        {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        {{ old('status') == 'Inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>

                </select>

                @error('status')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- QUALIFICATION --}}
            <div class="form-field">

                <label class="teacher-label">
                    Qualification
                </label>

                <input
                    type="text"
                    name="qualification"
                    class="teacher-input @error('qualification') is-invalid @enderror"
                    value="{{ old('qualification') }}"
                    placeholder="e.g. M.Sc, B.Ed"
                >

                @error('qualification')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- SUBJECT --}}
            <div class="form-field">

                <label class="teacher-label">
                    Subject
                </label>

                <div class="subject-wrapper">

                    <input
                        type="text"
                        name="subject"
                        class="teacher-input subject-input @error('subject') is-invalid @enderror"
                        value="{{ old('subject') }}"
                        placeholder="Enter subject taught"
                    >

                </div>

                <div class="subject-help">
                    Enter the main subject taught by the teacher.
                </div>

                @error('subject')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>


            {{-- JOINING DATE --}}
            <div class="form-field">

                <label class="teacher-label">
                    Joining Date
                </label>

                <input
                    type="date"
                    name="joining_date"
                    class="teacher-input @error('joining_date') is-invalid @enderror"
                    value="{{ old('joining_date') }}"
                >

                @error('joining_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>
    </div>


    {{-- =================================================
         PROFILE IMAGE
    ================================================== --}}

    <div class="form-section">

        <div class="form-section-header">

            <div class="form-section-icon">
                <i class="bi bi-image-fill"></i>
            </div>

            <div>
                <h3>Profile Image</h3>
                <p>Upload the teacher profile photo</p>
            </div>

        </div>


        <div class="profile-area">

            <div class="profile-preview-wrapper">

                <div
                    class="profile-placeholder"
                    id="profilePlaceholder"
                >
                    <i class="bi bi-person-fill"></i>
                </div>

                <img
                    src=""
                    alt="Profile Preview"
                    id="profilePreview"
                    class="profile-preview"
                    style="display:none;"
                >

                <span class="preview-badge">
                    <i class="bi bi-camera-fill"></i>
                </span>

            </div>


            <div class="profile-info">

                <h4>
                    Teacher Profile Photo
                </h4>

                <p>
                    Upload a clear profile image of the teacher.
                </p>

                <div
                    class="selected-image-name"
                    id="selectedImageName"
                ></div>

            </div>

        </div>


        {{-- IMAGE INPUT --}}
        <div class="form-field">

            <label class="teacher-label">
                Profile Image
            </label>

            <input
                type="file"
                name="profile_image"
                id="profileImageInput"
                class="teacher-input @error('profile_image') is-invalid @enderror"
                accept="image/jpeg,image/png,image/webp"
            >

            <div class="teacher-help">

                <span>
                    JPG, JPEG, PNG or WEBP
                </span>

                <span>
                    Maximum size: 2 MB
                </span>

            </div>

            @error('profile_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

    </div>


    {{-- =================================================
         ACTION BUTTONS
    ================================================== --}}

    <div class="teacher-actions">

        <a
            href="{{ route('admin.teachers.index') }}"
            class="btn-teacher btn-secondary"
        >
            <i class="bi bi-x-lg"></i>
            Cancel
        </a>

        <button
            type="submit"
            class="btn-teacher btn-primary"
            id="saveTeacherBtn"
        >
            <i class="bi bi-check-lg"></i>

            <span id="saveTeacherText">
                Save Teacher
            </span>
        </button>

    </div>

</form>

</div>

<script>
/* =========================================================
   ADDRESS COUNTER
========================================================= */

const teacherAddress =
    document.getElementById('teacherAddress');

const addressCounter =
    document.getElementById('addressCounter');

function updateAddressCounter() {

    if (!teacherAddress || !addressCounter) {
        return;
    }

    addressCounter.textContent =
        teacherAddress.value.length + ' / 1000';
}

if (teacherAddress) {

    teacherAddress.addEventListener(
        'input',
        updateAddressCounter
    );

    updateAddressCounter();
}


/* =========================================================
   EMAIL & PHONE ELEMENTS
========================================================= */

const teacherEmail =
    document.getElementById('teacherEmail');

const teacherPhone =
    document.getElementById('teacherPhone');

const emailWarning =
    document.getElementById('emailWarning');

const phoneWarning =
    document.getElementById('phoneWarning');


/* =========================================================
   EMAIL VALIDATION
========================================================= */

function validateTeacherEmail(showWarning = true) {

    if (!teacherEmail) {
        return true;
    }

    const email =
        teacherEmail.value.trim();

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    if (email === '') {

        teacherEmail.classList.remove(
            'validation-success'
        );

        teacherEmail.classList.add(
            'validation-error'
        );

        if (emailWarning) {

            emailWarning.textContent =
                'Email address is required.';

            emailWarning.classList.add('show');
        }

        return false;
    }


    if (!emailPattern.test(email)) {

        teacherEmail.classList.remove(
            'validation-success'
        );

        teacherEmail.classList.add(
            'validation-error'
        );

        if (emailWarning) {

            emailWarning.textContent =
                'Please enter a valid email address, e.g. example@gmail.com.';

            emailWarning.classList.add('show');
        }

        return false;
    }


    teacherEmail.classList.remove(
        'validation-error'
    );

    teacherEmail.classList.add(
        'validation-success'
    );

    if (emailWarning) {
        emailWarning.classList.remove('show');
    }

    return true;
}


/* =========================================================
   PHONE INPUT
========================================================= */

if (teacherPhone) {

    teacherPhone.addEventListener(
        'input',
        function () {

            this.value =
                this.value
                    .replace(/\D/g, '')
                    .slice(0, 10);

            if (this.value.length === 10) {

                validateTeacherPhone(false);

            } else {

                this.classList.remove(
                    'validation-success'
                );
            }
        }
    );
}


/* =========================================================
   PHONE VALIDATION
========================================================= */

function validateTeacherPhone(showWarning = true) {

    if (!teacherPhone) {
        return true;
    }

    const phone =
        teacherPhone.value.trim();


    /* Phone is optional */
    if (phone === '') {

        teacherPhone.classList.remove(
            'validation-error',
            'validation-success'
        );

        if (phoneWarning) {
            phoneWarning.classList.remove('show');
        }

        return true;
    }


    if (phone.length !== 10) {

        teacherPhone.classList.remove(
            'validation-success'
        );

        teacherPhone.classList.add(
            'validation-error'
        );

        if (phoneWarning) {

            phoneWarning.textContent =
                'Phone number must contain exactly 10 digits.';

            phoneWarning.classList.add('show');
        }

        return false;
    }


    teacherPhone.classList.remove(
        'validation-error'
    );

    teacherPhone.classList.add(
        'validation-success'
    );

    if (phoneWarning) {
        phoneWarning.classList.remove('show');
    }

    return true;
}


/* =========================================================
   EMAIL LIVE VALIDATION
========================================================= */

if (teacherEmail) {

    teacherEmail.addEventListener(
        'input',
        function () {

            if (this.value.trim() !== '') {

                validateTeacherEmail(false);
            }
        }
    );

    teacherEmail.addEventListener(
        'blur',
        function () {

            validateTeacherEmail(true);
        }
    );
}


/* =========================================================
   PHONE BLUR VALIDATION
========================================================= */

if (teacherPhone) {

    teacherPhone.addEventListener(
        'blur',
        function () {

            validateTeacherPhone(true);
        }
    );
}


/* =========================================================
   PROFILE IMAGE PREVIEW
========================================================= */

const profileImageInput =
    document.getElementById('profileImageInput');

const profilePreview =
    document.getElementById('profilePreview');

const profilePlaceholder =
    document.getElementById('profilePlaceholder');

const selectedImageName =
    document.getElementById('selectedImageName');


if (profileImageInput) {

    profileImageInput.addEventListener(
        'change',
        function () {

            const file =
                this.files[0];

            if (!file) {
                return;
            }


            /* Maximum 2 MB */
            const maxSize =
                2 * 1024 * 1024;


            if (file.size > maxSize) {

                alert(
                    'The selected image is larger than 2 MB. Please choose a smaller image.'
                );

                this.value = '';

                if (selectedImageName) {
                    selectedImageName.textContent = '';
                }

                return;
            }


            /* File name */
            if (selectedImageName) {

                selectedImageName.textContent =
                    'Selected: ' + file.name;
            }


            /* Preview */
            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    if (profilePreview) {

                        profilePreview.src =
                            event.target.result;

                        profilePreview.style.display =
                            'block';
                    }


                    if (profilePlaceholder) {

                        profilePlaceholder.style.display =
                            'none';
                    }
                };


            reader.readAsDataURL(file);
        }
    );
}


/* =========================================================
   FORM SUBMIT VALIDATION + LOADING
========================================================= */

const teacherCreateForm =
    document.getElementById('teacherCreateForm');

const saveTeacherBtn =
    document.getElementById('saveTeacherBtn');

const saveTeacherText =
    document.getElementById('saveTeacherText');


if (teacherCreateForm) {

    teacherCreateForm.addEventListener(
        'submit',
        function (event) {

            const emailValid =
                validateTeacherEmail(true);

            const phoneValid =
                validateTeacherPhone(true);


            /* Stop submission if invalid */

            if (!emailValid || !phoneValid) {

                event.preventDefault();

                alert(
                    'Please correct the Email and Phone validation errors before saving the teacher.'
                );

                return;
            }


            /* Valid → show loading */

            if (
                saveTeacherBtn &&
                saveTeacherText
            ) {

                saveTeacherBtn.classList.add(
                    'loading'
                );

                saveTeacherBtn.disabled = true;

                saveTeacherText.textContent =
                    'Saving...';


                const icon =
                    saveTeacherBtn.querySelector('i');

                if (icon) {

                    icon.className =
                        'spinner';
                }
            }
        }
    );
}
</script>

@endsection
