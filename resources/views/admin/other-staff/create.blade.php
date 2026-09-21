@extends('layouts.app')

@section('content')

<style>
    .staff-create-page {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 15px;
    }

    .page-title h1 {
        margin: 0;
        color: #172033;
        font-size: 24px;
        font-weight: 700;
    }

    .page-title p {
        margin: 6px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 15px;
        border: 1px solid #dce2eb;
        border-radius: 8px;
        background: #fff;
        color: #455066;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f7f9fc;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e7ebf2;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(25, 45, 75, .04);
        overflow: hidden;
    }

    .form-section {
        padding: 22px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        margin-bottom: 18px;
    }

    .section-title h3 {
        margin: 0;
        color: #172033;
        font-size: 16px;
        font-weight: 700;
    }

    .section-title p {
        margin: 5px 0 0;
        color: #8a93a5;
        font-size: 12px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 7px;
        color: #455066;
        font-size: 12px;
        font-weight: 650;
    }

    .required {
        color: #d32f2f;
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        background: #fff;
        color: #343d50;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
    }

    textarea.form-control {
        height: 100px;
        padding: 11px 12px;
        resize: vertical;
    }

    .form-control:focus {
        border-color: #1976d2;
        box-shadow: 0 0 0 3px rgba(25, 118, 210, .08);
    }

    .readonly-field {
        background: #f5f7fa;
        color: #5f6b80;
        cursor: not-allowed;
        font-weight: 650;
    }

    .file-input {
        height: auto;
        padding: 9px 12px;
    }

    .form-help {
        margin-top: 5px;
        color: #8a93a5;
        font-size: 11px;
    }

    .error-message {
        margin-top: 5px;
        color: #d32f2f;
        font-size: 11px;
    }

    /* Photo preview */

    .photo-upload-area {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .photo-preview {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e1e6ef;
        background: #f5f7fa;
        display: none;
    }

    .photo-placeholder {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: #eef4ff;
        color: #1976d2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .photo-info {
        flex: 1;
    }

    .photo-size {
        margin-top: 5px;
        font-size: 11px;
        color: #7b8497;
    }

    .compression-status {
        margin-top: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .compression-success {
        color: #2e7d32;
    }

    .compression-warning {
        color: #ef6c00;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 20px 22px;
        background: #fafbfd;
        border-top: 1px solid #edf0f5;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 100px;
        height: 40px;
        padding: 0 15px;
        border-radius: 7px;
        background: #f0f2f6;
        color: #596579;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .save-btn {
        border: none;
        min-width: 120px;
        height: 40px;
        padding: 0 18px;
        border-radius: 7px;
        background: #1976d2;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .save-btn:hover {
        background: #1565c0;
    }

    .save-btn:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    @media (max-width: 700px) {
        .staff-create-page {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }

        .photo-upload-area {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .cancel-btn,
        .save-btn {
            width: 100%;
        }
    }
</style>


<div class="staff-create-page">

    {{-- Header --}}
    <div class="page-header">

        <div class="page-title">
            <h1>Add Staff Member</h1>

            <p>
                Add a new non-teaching staff member to the school.
            </p>
        </div>

        <a href="{{ route('admin.other-staff.index') }}"
           class="back-btn">
            ← Back to Staff
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div style="
            background:#ffebee;
            color:#c62828;
            border:1px solid #ffcdd2;
            padding:14px 16px;
            border-radius:8px;
            margin-bottom:20px;
            font-size:13px;
        ">

            <strong>Please fix the following:</strong>

            <ul style="margin:8px 0 0 18px;">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form
        id="staffForm"
        action="{{ route('admin.other-staff.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="form-card">


            {{-- =========================
                 PERSONAL INFORMATION
            ========================== --}}

            <div class="form-section">

                <div class="section-title">

                    <h3>Personal Information</h3>

                    <p>
                        Basic information about the staff member.
                    </p>

                </div>


                <div class="form-grid">


                    {{-- Staff ID --}}

                    <div class="form-group">

                        <label>
                            Staff ID
                        </label>

                        <input
    type="text"
    name="staff_id"
    class="form-control"
    value="{{ old('staff_id', $nextStaffId) }}"
    required
>

                        <span class="form-help">
                            Staff ID is automatically generated.
                        </span>

                    </div>


                    {{-- Full Name --}}

                    <div class="form-group">

                        <label>
                            Full Name
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter full name"
                            value="{{ old('name') }}"
                            required
                        >

                        @error('name')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Gender --}}

                    <div class="form-group">

                        <label>Gender</label>

                        <select
                            name="gender"
                            class="form-control"
                        >

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                {{ old('gender') === 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender') === 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option value="Other"
                                {{ old('gender') === 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Date of Birth --}}

                    <div class="form-group">

                        <label>Date of Birth</label>

                        <input
                            type="date"
                            name="date_of_birth"
                            class="form-control"
                            value="{{ old('date_of_birth') }}"
                        >

                    </div>


                    {{-- Profile Photo --}}

                    <div class="form-group full">

                        <label>Profile Photo</label>

                        <div class="photo-upload-area">

                            <div class="photo-placeholder"
                                 id="photoPlaceholder">
                                👤
                            </div>

                            <img
                                id="photoPreview"
                                class="photo-preview"
                                alt="Profile Preview"
                            >

                            <div class="photo-info">

                                <input
                                    type="file"
                                    id="profilePhoto"
                                    name="profile_photo"
                                    class="form-control file-input"
                                    accept=".jpg,.jpeg,.png,.webp"
                                >

                                <div class="form-help">
                                    JPG, JPEG, PNG or WEBP.
                                    Image will be compressed to approximately 250 KB.
                                </div>

                                <div
                                    id="compressionStatus"
                                    class="compression-status"
                                ></div>

                            </div>

                        </div>

                        @error('profile_photo')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =========================
                 CONTACT INFORMATION
            ========================== --}}

            <div class="form-section">

                <div class="section-title">

                    <h3>Contact Information</h3>

                    <p>
                        Contact details and residential address.
                    </p>

                </div>


                <div class="form-grid">


                    {{-- Phone --}}

                    <div class="form-group">

                        <label>Phone Number</label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            placeholder="Enter phone number"
                            value="{{ old('phone') }}"
                        >

                    </div>


                    {{-- Email --}}

                    <div class="form-group">

                        <label>Email Address</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email address"
                            value="{{ old('email') }}"
                        >

                        @error('email')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Address --}}

                    <div class="form-group full">

                        <label>Address</label>

                        <textarea
                            name="address"
                            class="form-control"
                            placeholder="Enter complete address"
                        >{{ old('address') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- =========================
                 EMPLOYMENT INFORMATION
            ========================== --}}

            <div class="form-section">

                <div class="section-title">

                    <h3>Employment Information</h3>

                    <p>
                        Role and employment details.
                    </p>

                </div>


                <div class="form-grid">


                    {{-- Designation --}}

                    <div class="form-group">

                        <label>
                            Designation
                            <span class="required">*</span>
                        </label>

                        <select
                            name="designation"
                            id="designation"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Select Designation
                            </option>

                            <option value="Librarian"
                                {{ old('designation') === 'Librarian' ? 'selected' : '' }}>
                                Librarian
                            </option>

                            <option value="Accountant"
                                {{ old('designation') === 'Accountant' ? 'selected' : '' }}>
                                Accountant
                            </option>

                            <option value="Receptionist"
                                {{ old('designation') === 'Receptionist' ? 'selected' : '' }}>
                                Receptionist
                            </option>

                            <option value="Peon"
                                {{ old('designation') === 'Peon' ? 'selected' : '' }}>
                                Peon
                            </option>

                            <option value="Driver"
                                {{ old('designation') === 'Driver' ? 'selected' : '' }}>
                                Driver
                            </option>

                            <option value="Other"
                                {{ old('designation') === 'Other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                        @error('designation')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Department --}}

                    <div class="form-group">

                        <label>Department</label>

                        <input
                            type="text"
                            name="department"
                            id="department"
                            class="form-control"
                            placeholder="Select designation first"
                            value="{{ old('department') }}"
                        >

                        <span class="form-help">
                            Department will be suggested automatically.
                        </span>

                    </div>


                    {{-- Qualification --}}

                    <div class="form-group">

                        <label>Qualification</label>

                        <input
                            type="text"
                            name="qualification"
                            class="form-control"
                            placeholder="e.g. B.Com, B.A."
                            value="{{ old('qualification') }}"
                        >

                    </div>


                    {{-- Joining Date --}}

                    <div class="form-group">

                        <label>Joining Date</label>

                        <input
                            type="date"
                            name="joining_date"
                            id="joiningDate"
                            class="form-control"
                            value="{{ old('joining_date') }}"
                            max="{{ date('Y-m-d') }}"
                        >

                        <span class="form-help">
                            Future joining dates are not allowed.
                        </span>

                        @error('joining_date')
                            <span class="error-message">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Status --}}

                    <div class="form-group">

                        <label>
                            Employment Status
                            <span class="required">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-control"
                            required
                        >

                            <option value="Active"
                                {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- Actions --}}

            <div class="form-actions">

                <a
                    href="{{ route('admin.other-staff.index') }}"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    id="saveButton"
                    class="save-btn"
                >
                    Save Staff
                </button>

            </div>

        </div>

    </form>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Department Suggestions
    |--------------------------------------------------------------------------
    */

    const designation = document.getElementById('designation');
    const department = document.getElementById('department');

    const departmentMap = {
        'Librarian': 'Library',
        'Accountant': 'Accounts',
        'Receptionist': 'Administration',
        'Peon': 'Maintenance',
        'Driver': 'Transport',
        'Other': 'Other'
    };

    designation.addEventListener('change', function () {

        const selected = this.value;

        if (departmentMap[selected]) {
            department.value = departmentMap[selected];
        } else {
            department.value = '';
        }

    });


    /*
    |--------------------------------------------------------------------------
    | Joining Date Protection
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Profile Photo Preview + Compression
    |--------------------------------------------------------------------------
    */

    const photoInput = document.getElementById('profilePhoto');
    const photoPreview = document.getElementById('photoPreview');
    const photoPlaceholder = document.getElementById('photoPlaceholder');
    const compressionStatus = document.getElementById('compressionStatus');

    const TARGET_SIZE = 250 * 1024;

    photoInput.addEventListener('change', async function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {

            alert('Please select a valid image.');

            this.value = '';

            return;
        }


        compressionStatus.textContent = 'Processing image...';
        compressionStatus.className = 'compression-status';


        try {

            const compressedFile = await compressImage(file);


            /*
            |--------------------------------------------------------------------------
            | Replace selected file with compressed file
            |--------------------------------------------------------------------------
            */

            const dataTransfer = new DataTransfer();

            dataTransfer.items.add(compressedFile);

            photoInput.files = dataTransfer.files;


            /*
            |--------------------------------------------------------------------------
            | Preview compressed image
            |--------------------------------------------------------------------------
            */

            const previewUrl = URL.createObjectURL(compressedFile);

            photoPreview.src = previewUrl;
            photoPreview.style.display = 'block';

            photoPlaceholder.style.display = 'none';


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            const sizeKB = compressedFile.size / 1024;

            if (compressedFile.size <= TARGET_SIZE) {

                compressionStatus.textContent =
                    '✓ Compressed successfully: ' +
                    sizeKB.toFixed(0) +
                    ' KB';

                compressionStatus.className =
                    'compression-status compression-success';

            } else {

                compressionStatus.textContent =
                    'Image compressed to ' +
                    sizeKB.toFixed(0) +
                    ' KB';

                compressionStatus.className =
                    'compression-status compression-warning';
            }


        } catch (error) {

            console.error(error);

            compressionStatus.textContent =
                'Unable to compress image. Please choose another image.';

            compressionStatus.className =
                'compression-status compression-warning';

            this.value = '';

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Image Compression Function
    |--------------------------------------------------------------------------
    */

    async function compressImage(file) {

        const image = await loadImage(file);

        let width = image.width;
        let height = image.height;

        const maxWidth = 1200;
        const maxHeight = 1200;

        /*
        |--------------------------------------------------------------------------
        | Resize large images
        |--------------------------------------------------------------------------
        */

        if (width > maxWidth || height > maxHeight) {

            const ratio = Math.min(
                maxWidth / width,
                maxHeight / height
            );

            width = Math.round(width * ratio);
            height = Math.round(height * ratio);
        }


        let quality = 0.85;
        let blob = await createBlob(
            image,
            width,
            height,
            quality
        );


        /*
        |--------------------------------------------------------------------------
        | Reduce quality progressively until approximately 250 KB
        |--------------------------------------------------------------------------
        */

        while (blob.size > TARGET_SIZE && quality > 0.35) {

            quality -= 0.05;

            blob = await createBlob(
                image,
                width,
                height,
                quality
            );
        }


        /*
        |--------------------------------------------------------------------------
        | If still too large, reduce dimensions
        |--------------------------------------------------------------------------
        */

        while (blob.size > TARGET_SIZE && width > 600) {

            width = Math.round(width * 0.85);
            height = Math.round(height * 0.85);

            quality = 0.75;

            blob = await createBlob(
                image,
                width,
                height,
                quality
            );


            while (blob.size > TARGET_SIZE && quality > 0.35) {

                quality -= 0.05;

                blob = await createBlob(
                    image,
                    width,
                    height,
                    quality
                );
            }
        }


        return new File(
            [blob],
            'staff-photo.jpg',
            {
                type: 'image/jpeg',
                lastModified: Date.now()
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Load Image
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Create Compressed Blob
    |--------------------------------------------------------------------------
    */

    function createBlob(
        image,
        width,
        height,
        quality
    ) {

        return new Promise((resolve) => {

            const canvas = document.createElement('canvas');

            canvas.width = width;
            canvas.height = height;

            const context = canvas.getContext('2d');

            context.drawImage(
                image,
                0,
                0,
                width,
                height
            );

            canvas.toBlob(
                resolve,
                'image/jpeg',
                quality
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Prevent accidental double submission
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('staffForm');
    const saveButton = document.getElementById('saveButton');

    form.addEventListener('submit', function () {

        saveButton.disabled = true;
        saveButton.textContent = 'Saving...';

    });

});
</script>

@endsection