@extends('layouts.app')

@section('title', 'Edit Teacher')

@section('content')

<style>
.teacher-container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
    box-sizing: border-box;
}

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

.is-invalid {
    border-color: #dc3545 !important;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
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

.current-profile-image,
.new-profile-preview,
.profile-placeholder {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    box-sizing: border-box;
}

.current-profile-image,
.new-profile-preview {
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

.image-error-message {
    display: none;
    margin-top: 7px;
    color: #dc3545;
    font-size: 11px;
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
   ACTIONS
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
        <h2>Edit Teacher</h2>
        <p>Update teacher information and profile details</p>
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

{{-- =====================================================
     SINGLE FORM START
====================================================== --}}

<form
    action="{{ route('admin.teachers.update', $teacher->id) }}"
    method="POST"
    enctype="multipart/form-data"
    id="teacherEditForm"
    class="teacher-form"
>

    @csrf
    @method('PUT')

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
                    value="{{ old('first_name', $teacher->first_name) }}"
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
                    value="{{ old('last_name', $teacher->last_name) }}"
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
                    value="{{ old('date_of_birth', $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('Y-m-d') : '') }}"
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

                    <option value="">
                        Select Gender
                    </option>

                    <option
                        value="Male"
                        {{ old('gender', $teacher->gender) == 'Male' ? 'selected' : '' }}
                    >
                        Male
                    </option>

                    <option
                        value="Female"
                        {{ old('gender', $teacher->gender) == 'Female' ? 'selected' : '' }}
                    >
                        Female
                    </option>

                    <option
                        value="Other"
                        {{ old('gender', $teacher->gender) == 'Other' ? 'selected' : '' }}
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
                    class="teacher-input @error('email') is-invalid @enderror"
                    value="{{ old('email', $teacher->email) }}"
                    placeholder="example@gmail.com"
                    required
                >

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
                    class="teacher-input @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $teacher->phone) }}"
                    placeholder="Enter phone number"
                >

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
                >{{ old('address', $teacher->address) }}</textarea>

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
                    <span class="required">*</span>
                </label>

                <input
                    type="text"
                    name="teacher_id"
                    class="teacher-input @error('teacher_id') is-invalid @enderror"
                    value="{{ old('teacher_id', $teacher->teacher_id) }}"
                    placeholder="Enter teacher ID"
                    required
                >

                @error('teacher_id')
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
                    value="{{ old('qualification', $teacher->qualification) }}"
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
                        value="{{ old('subject', $teacher->subject) }}"
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
                    value="{{ old('joining_date', $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('Y-m-d') : '') }}"
                >

                @error('joining_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

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
                        {{ old('status', $teacher->status) == 'Active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        {{ old('status', $teacher->status) == 'Inactive' ? 'selected' : '' }}
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
         PROFILE IMAGE
    ================================================== --}}

    <div class="form-section">

        <div class="form-section-header">

            <div class="form-section-icon">
                <i class="bi bi-image-fill"></i>
            </div>

            <div>
                <h3>Profile Image</h3>
                <p>Update the teacher profile photo</p>
            </div>

        </div>

        
<div class="profile-area">

    <div class="profile-preview-wrapper">

        @if($teacher->profile_image)

            <img
                src="{{ $teacher->profile_image }}"
                alt="Teacher Profile"
                id="profilePreview"
                class="current-profile-image"
            >

        @else

            <div
                class="profile-placeholder"
                id="profilePlaceholder"
            >
                <i class="bi bi-person-fill"></i>
            </div>

        @endif

        <span class="preview-badge">
            <i class="bi bi-camera-fill"></i>
        </span>

    </div>

    <div class="profile-info">

        <h4>Current Profile Photo</h4>

        <p>
            Choose a new image below to replace the current photo.
        </p>

        @if($teacher->profile_image)
            <div class="selected-image-name">
                Current image is stored on Cloudinary
            </div>
        @else
            <div class="selected-image-name">
                No profile image uploaded
            </div>
        @endif

    </div>

</div>



        {{-- IMAGE INPUT --}}
        <div class="form-field">

            <label class="teacher-label">
                Change Profile Image
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
            id="updateTeacherBtn"
        >

            <i class="bi bi-check-lg"></i>

            <span id="updateTeacherText">
                Update Teacher
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
   IMAGE ERROR
========================================================= */

function handleImageError(image) {

    image.style.display = 'none';

    const errorMessage =
        document.getElementById('imageErrorMessage');

    if (errorMessage) {
        errorMessage.style.display = 'block';
    }

    const wrapper =
        image.parentElement;

    if (
        wrapper &&
        !wrapper.querySelector('.profile-placeholder')
    ) {

        const placeholder =
            document.createElement('div');

        placeholder.className =
            'profile-placeholder';

        placeholder.id =
            'profilePlaceholder';

        placeholder.innerHTML =
            '<i class="bi bi-person-fill"></i>';

        wrapper.insertBefore(
            placeholder,
            wrapper.querySelector('.preview-badge')
        );
    }
}


/* =========================================================
   IMAGE LIVE PREVIEW
========================================================= */


/* =========================================================
   IMAGE LIVE PREVIEW
========================================================= */

const profileImageInput =
    document.getElementById('profileImageInput');

const selectedImageName =
    document.getElementById('selectedImageName');

if (profileImageInput) {

    profileImageInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        // Maximum 2 MB
        const maxSize = 2 * 1024 * 1024;

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

        // Show selected file name
        if (selectedImageName) {
            selectedImageName.textContent =
                'Selected: ' + file.name;
        }

        // Create temporary preview
        const reader = new FileReader();

        reader.onload = function (event) {

            const previewWrapper =
                document.querySelector('.profile-preview-wrapper');

            if (!previewWrapper) {
                return;
            }

            // Remove old image
            const oldImage =
                previewWrapper.querySelector(
                    '#profilePreview'
                );

            if (oldImage) {
                oldImage.remove();
            }

            // Remove placeholder
            const placeholder =
                previewWrapper.querySelector(
                    '#profilePlaceholder'
                );

            if (placeholder) {
                placeholder.remove();
            }

            // Create new preview image
            const newImage =
                document.createElement('img');

            newImage.src =
                event.target.result;

            newImage.alt =
                'New profile preview';

            newImage.id =
                'profilePreview';

            newImage.className =
                'current-profile-image';

            previewWrapper.insertBefore(
                newImage,
                previewWrapper.querySelector('.preview-badge')
            );
        };

        reader.readAsDataURL(file);
    });
}



/* =========================================================
   SUBMIT LOADING
========================================================= */

const teacherEditForm =
    document.getElementById('teacherEditForm');

const updateTeacherBtn =
    document.getElementById('updateTeacherBtn');

const updateTeacherText =
    document.getElementById('updateTeacherText');

if (teacherEditForm) {

    teacherEditForm.addEventListener(
        'submit',
        function () {

            if (
                updateTeacherBtn &&
                updateTeacherText
            ) {

                updateTeacherBtn.classList.add(
                    'loading'
                );

                updateTeacherBtn.disabled =
                    true;

                updateTeacherText.textContent =
                    'Updating...';

                const icon =
                    updateTeacherBtn.querySelector('i');

                if (icon) {
                    icon.className = 'spinner';
                }
            }
        }
    );
}
</script>


@endsection
