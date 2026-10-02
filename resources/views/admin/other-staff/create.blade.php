@extends('layouts.app')

@section('title', 'Add Staff')
@section('page-title', 'Add Staff')

@section('content')

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    /* =========================================================
       ADD STAFF PAGE
    ========================================================= */

    .staff-create-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 10px 0 30px;
    }

    .staff-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        border-radius: 18px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(30, 80, 180, 0.15);
    }

    .staff-header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .staff-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .staff-header-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .staff-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    .staff-header p {
        margin: 5px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        color: #1769d1;
        border: none;
        border-radius: 10px;
        padding: 11px 17px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .back-btn:hover {
        color: #1769d1;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
    }

    /* =========================================================
       FORM CARD
    ========================================================= */

    .staff-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e7edf7;
        box-shadow: 0 6px 24px rgba(31, 52, 90, 0.07);
        overflow: hidden;
    }

    .card-section {
        padding: 25px 28px;
        border-bottom: 1px solid #edf1f7;
    }

    .card-section:last-child {
        border-bottom: none;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 20px;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #eef5ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .section-heading h3 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #26364d;
    }

    .section-heading p {
        margin: 3px 0 0;
        color: #8995a7;
        font-size: 12px;
    }

    .form-label {
        color: #34445a;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .required {
        color: #dc3545;
    }

    .form-control,
    .form-select {
        min-height: 44px;
        border: 1px solid #dce4ef;
        border-radius: 10px;
        padding: 10px 13px;
        color: #334155;
        font-size: 14px;
        background-color: #fff;
        box-shadow: none;
        transition: 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.10);
    }

    textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .form-text {
        color: #8a96a8;
        font-size: 11px;
        margin-top: 5px;
    }

    .is-invalid {
        border-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: block;
        font-size: 12px;
        margin-top: 5px;
    }

    /* =========================================================
       STAFF ID
    ========================================================= */

    .staff-id-wrapper {
        position: relative;
    }

    .staff-id-wrapper .form-control {
        padding-right: 45px;
        background: #f7f9fd;
        font-weight: 700;
        color: #1769d1;
    }

    .staff-id-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c63ff;
        pointer-events: none;
    }

    /* =========================================================
       PHOTO UPLOAD
    ========================================================= */

    .photo-upload-box {
        border: 2px dashed #d8e2f0;
        border-radius: 14px;
        padding: 20px;
        background: #f9fbff;
        text-align: center;
        transition: 0.2s ease;
    }

    .photo-upload-box:hover {
        border-color: #6c63ff;
        background: #f6f7ff;
    }

    .photo-icon {
        width: 55px;
        height: 55px;
        margin: 0 auto 10px;
        border-radius: 14px;
        background: #eef5ff;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 25px;
    }

    .photo-upload-box label {
        cursor: pointer;
    }

    .photo-title {
        display: block;
        color: #34445a;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }

    .photo-subtitle {
        color: #8a96a8;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .photo-preview {
        display: none;
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        margin: 12px auto 0;
        border: 3px solid #fff;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
    }

    /* =========================================================
       FOOTER BUTTONS
    ========================================================= */

    .form-footer {
        padding: 20px 28px;
        background: #f9fbfe;
        border-top: 1px solid #edf1f7;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-cancel,
    .btn-save {
        min-height: 44px;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #d9e1ec;
        color: #5c6b7e;
    }

    .btn-cancel:hover {
        color: #334155;
        background: #f4f7fb;
    }

    .btn-save {
        border: none;
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        color: #fff;
        box-shadow: 0 5px 15px rgba(67, 90, 220, 0.20);
    }

    .btn-save:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(67, 90, 220, 0.28);
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .error-alert {
        border: none;
        border-radius: 12px;
        background: #fff1f2;
        color: #b42318;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .error-alert ul {
        margin: 7px 0 0 18px;
        padding: 0;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {
        .staff-create-page {
            padding: 5px 0 20px;
        }

        .staff-header {
            padding: 20px;
            border-radius: 14px;
        }

        .staff-header-inner {
            align-items: flex-start;
            flex-direction: column;
        }

        .staff-header h2 {
            font-size: 20px;
        }

        .back-btn {
            width: 100%;
            justify-content: center;
        }

        .card-section {
            padding: 20px;
        }

        .form-footer {
            padding: 18px 20px;
            flex-direction: column-reverse;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="staff-create-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="staff-header">
        <div class="staff-header-inner">

            <div class="staff-header-left">

                <div class="staff-header-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>

                <div>
                    <h2>Add Staff</h2>
                    <p>Create a new non-teaching staff member.</p>
                </div>

            </div>

            <a
                href="{{ route('admin.other-staff.index') }}"
                class="back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Staff
            </a>

        </div>
    </div>

    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}
    @if ($errors->any())
        <div class="error-alert">

            <strong>
                <i class="bi bi-exclamation-circle me-1"></i>
                Please correct the following errors:
            </strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- =====================================================
         FORM
    ====================================================== --}}
    <form
        action="{{ route('admin.other-staff.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="staff-card">

            {{-- =================================================
                 BASIC INFORMATION
            ================================================== --}}
            <div class="card-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <div>
                        <h3>Basic Information</h3>
                        <p>Enter the staff member's basic details.</p>
                    </div>

                </div>

                <div class="row g-4">

                    {{-- STAFF ID --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Staff ID <span class="required">*</span>
                        </label>

                        <div class="staff-id-wrapper">

                            <input
                                type="text"
                                name="staff_id"
                                value="{{ old('staff_id', $nextStaffId) }}"
                                class="form-control @error('staff_id') is-invalid @enderror"
                                readonly
                            >

                            <i class="bi bi-shield-check staff-id-icon"></i>

                        </div>

                        @error('staff_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            Staff ID is generated automatically.
                        </div>

                    </div>

                    {{-- NAME --}}
                    <div class="col-lg-8 col-md-6">

                        <label class="form-label">
                            Full Name <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter staff full name"
                            maxlength="255"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- DESIGNATION --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Designation <span class="required">*</span>
                        </label>

                        <select
                            name="designation"
                            class="form-select @error('designation') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select designation
                            </option>

                            @foreach ([
                                'Librarian',
                                'Accountant',
                                'Receptionist',
                                'Peon',
                                'Driver',
                                'Other'
                            ] as $designation)

                                <option
                                    value="{{ $designation }}"
                                    @selected(old('designation') === $designation)
                                >
                                    {{ $designation }}
                                </option>

                            @endforeach

                        </select>

                        @error('designation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- DEPARTMENT --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Department
                        </label>

                        <input
                            type="text"
                            name="department"
                            value="{{ old('department') }}"
                            class="form-control @error('department') is-invalid @enderror"
                            placeholder="Enter department"
                            maxlength="255"
                        >

                        @error('department')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- STATUS --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Status <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option value="Active"
                                @selected(old('status', 'Active') === 'Active')
                            >
                                Active
                            </option>

                            <option value="Inactive"
                                @selected(old('status') === 'Inactive')
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

                </div>

            </div>

            {{-- =================================================
                 PERSONAL INFORMATION
            ================================================== --}}
            <div class="card-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>

                    <div>
                        <h3>Personal Information</h3>
                        <p>Enter personal and contact information.</p>
                    </div>

                </div>

                <div class="row g-4">

                    {{-- GENDER --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                        >

                            <option value="">
                                Select gender
                            </option>

                            <option value="Male"
                                @selected(old('gender') === 'Male')
                            >
                                Male
                            </option>

                            <option value="Female"
                                @selected(old('gender') === 'Female')
                            >
                                Female
                            </option>

                            <option value="Other"
                                @selected(old('gender') === 'Other')
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

                    {{-- DATE OF BIRTH --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Date of Birth
                        </label>

                        <input
                            type="date"
                            name="date_of_birth"
                            value="{{ old('date_of_birth') }}"
                            class="form-control @error('date_of_birth') is-invalid @enderror"
                        >

                        @error('date_of_birth')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- JOINING DATE --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Joining Date
                        </label>

                        <input
                            type="date"
                            name="joining_date"
                            value="{{ old('joining_date') }}"
                            class="form-control @error('joining_date') is-invalid @enderror"
                            max="{{ date('Y-m-d') }}"
                        >

                        @error('joining_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- PHONE --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="Enter phone number"
                            maxlength="20"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- EMAIL --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Enter email address"
                            maxlength="255"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- QUALIFICATION --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="form-label">
                            Qualification
                        </label>

                        <input
                            type="text"
                            name="qualification"
                            value="{{ old('qualification') }}"
                            class="form-control @error('qualification') is-invalid @enderror"
                            placeholder="Enter qualification"
                            maxlength="255"
                        >

                        @error('qualification')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ADDRESS --}}
                    <div class="col-12">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter complete address"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- =================================================
                 PROFILE PHOTO
            ================================================== --}}
            <div class="card-section">

                <div class="section-heading">

                    <div class="section-icon">
                        <i class="bi bi-camera"></i>
                    </div>

                    <div>
                        <h3>Profile Photo</h3>
                        <p>Upload a photo of the staff member.</p>
                    </div>

                </div>

                <div class="photo-upload-box">

                    <div class="photo-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>

                    <label for="profile_photo">

                        <span class="photo-title">
                            Choose Profile Photo
                        </span>

                        <span class="photo-subtitle">
                            JPG, JPEG, PNG or WEBP — maximum 2 MB
                        </span>

                    </label>

                    <input
                        type="file"
                        id="profile_photo"
                        name="profile_photo"
                        class="form-control @error('profile_photo') is-invalid @enderror"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                    <img
                        id="photoPreview"
                        class="photo-preview"
                        alt="Profile photo preview"
                    >

                    @error('profile_photo')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            {{-- =================================================
                 FORM FOOTER
            ================================================== --}}
            <div class="form-footer">

                <a
                    href="{{ route('admin.other-staff.index') }}"
                    class="btn-cancel"
                >
                    <i class="bi bi-x-lg"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    <i class="bi bi-check-circle"></i>
                    Save Staff
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const photoInput = document.getElementById('profile_photo');
        const photoPreview = document.getElementById('photoPreview');

        if (photoInput && photoPreview) {

            photoInput.addEventListener('change', function (event) {

                const file = event.target.files[0];

                if (!file) {
                    photoPreview.style.display = 'none';
                    photoPreview.removeAttribute('src');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    photoPreview.style.display = 'none';
                    photoPreview.removeAttribute('src');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            });
        }

    });
</script>

@endsection