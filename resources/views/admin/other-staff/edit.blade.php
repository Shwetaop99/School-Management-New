@extends('layouts.app')

@section('title', 'Edit Staff')

@section('content')

<style>
    .staff-page {
        padding: 24px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        color: #172554;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .back-btn {
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 10px;
        border: 1px solid #dbe3ef;
        color: #334155;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
    }

    .back-btn:hover {
        background: #f8fafc;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .section-header {
        padding: 18px 24px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        font-size: 16px;
        font-weight: 700;
        color: #172554;
    }

    .form-section {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
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
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .form-group label span {
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 13px;
        border: 1px solid #dbe3ef;
        border-radius: 9px;
        background: #fff;
        color: #1e293b;
        font-size: 14px;
        outline: none;
        transition: .2s;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }

    .error {
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
    }

    .photo-area {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .photo-preview {
        width: 110px;
        height: 110px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #dbe3ef;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-placeholder {
        font-size: 36px;
        color: #94a3b8;
    }

    .photo-help {
        color: #64748b;
        font-size: 12px;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        background: #fafafa;
    }

    .btn {
        border: none;
        border-radius: 9px;
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-cancel {
        background: #fff;
        color: #475569;
        border: 1px solid #dbe3ef;
    }

    .btn-update {
        background: #2563eb;
        color: #fff;
    }

    .btn-update:hover {
        background: #1d4ed8;
    }

    .btn-update:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .staff-page {
            padding: 15px;
        }

        .page-header {
            align-items: flex-start;
            gap: 15px;
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full {
            grid-column: auto;
        }
    }
</style>

<div class="staff-page">

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Staff</h1>
            <p class="page-subtitle">
                Update staff information and employment details.
            </p>
        </div>

        <a href="{{ route('admin.other-staff.index') }}" class="back-btn">
            ← Back to Staff
        </a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:18px;padding:12px 15px;border-radius:9px;background:#dcfce7;color:#166534;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin-bottom:18px;padding:12px 15px;border-radius:9px;background:#fee2e2;color:#991b1b;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="margin-bottom:18px;padding:12px 15px;border-radius:9px;background:#fee2e2;color:#991b1b;">
            <strong>Please fix the following:</strong>
            <ul style="margin:8px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.other-staff.update', $otherStaff->id) }}"
        method="POST"
        enctype="multipart/form-data"
        id="editStaffForm"
    >
        @csrf
        @method('PUT')

        {{-- PERSONAL INFORMATION --}}
        <div class="form-card" style="margin-bottom:20px;">

            <div class="section-header">
                Personal Information
            </div>

            <div class="form-section">

                <div class="form-grid">

                    <div class="form-group">
                        <label>
                            Staff ID <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="staff_id"
                            class="form-control"
                            value="{{ old('staff_id', $otherStaff->staff_id) }}"
                            required
                        >

                        @error('staff_id')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>
                            Full Name <span>*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $otherStaff->name) }}"
                            required
                        >

                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Gender</label>

                        <select name="gender" class="form-control">
                            <option value="">Select Gender</option>

                            <option value="Male"
                                {{ old('gender', $otherStaff->gender) == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="Female"
                                {{ old('gender', $otherStaff->gender) == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option value="Other"
                                {{ old('gender', $otherStaff->gender) == 'Other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>

                        @error('gender')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>

                        <input
                            type="date"
                            name="date_of_birth"
                            class="form-control"
                            value="{{ old('date_of_birth', optional($otherStaff->date_of_birth)->format('Y-m-d')) }}"
                        >

                        @error('date_of_birth')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label>Profile Photo</label>

                        <div class="photo-area">

                            <div class="photo-preview" id="photoPreview">

                                @if($otherStaff->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $otherStaff->profile_photo) }}"
                                        alt="Profile Photo"
                                        id="previewImage"
                                    >
                                @else
                                    <span class="photo-placeholder" id="photoPlaceholder">
                                        <i class="fas fa-user"></i>
                                    </span>
                                @endif

                            </div>

                            <div>
                                <input
                                    type="file"
                                    name="profile_photo"
                                    id="profilePhoto"
                                    accept="image/jpeg,image/png,image/webp"
                                >

                                <div class="photo-help">
                                    JPG, PNG or WEBP. Maximum 2 MB.
                                    New image will be compressed before upload.
                                </div>
                            </div>

                        </div>

                        @error('profile_photo')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- CONTACT INFORMATION --}}
        <div class="form-card" style="margin-bottom:20px;">

            <div class="section-header">
                Contact Information
            </div>

            <div class="form-section">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Phone</label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $otherStaff->phone) }}"
                            maxlength="20"
                        >

                        @error('phone')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $otherStaff->email) }}"
                        >

                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label>Address</label>

                        <textarea
                            name="address"
                            class="form-control"
                        >{{ old('address', $otherStaff->address) }}</textarea>

                        @error('address')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>


        {{-- EMPLOYMENT INFORMATION --}}
        <div class="form-card">

            <div class="section-header">
                Employment Information
            </div>

            <div class="form-section">

                <div class="form-grid">

                    <div class="form-group">
                        <label>
                            Designation <span>*</span>
                        </label>

                        <select
                            name="designation"
                            id="designation"
                            class="form-control"
                            required
                        >
                            <option value="">Select Designation</option>

                            @foreach([
                                'Librarian',
                                'Accountant',
                                'Receptionist',
                                'Peon',
                                'Driver',
                                'Other'
                            ] as $designation)

                                <option
                                    value="{{ $designation }}"
                                    {{ old('designation', $otherStaff->designation) == $designation ? 'selected' : '' }}
                                >
                                    {{ $designation }}
                                </option>

                            @endforeach

                        </select>

                        @error('designation')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Department</label>

                        <input
                            type="text"
                            name="department"
                            id="department"
                            class="form-control"
                            value="{{ old('department', $otherStaff->department) }}"
                        >

                        @error('department')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Qualification</label>

                        <input
                            type="text"
                            name="qualification"
                            class="form-control"
                            value="{{ old('qualification', $otherStaff->qualification) }}"
                        >

                        @error('qualification')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>Joining Date</label>

                        <input
                            type="date"
                            name="joining_date"
                            id="joiningDate"
                            class="form-control"
                            value="{{ old('joining_date', optional($otherStaff->joining_date)->format('Y-m-d')) }}"
                        >

                        @error('joining_date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label>
                            Status <span>*</span>
                        </label>

                        <select name="status" class="form-control" required>

                            <option value="Active"
                                {{ old('status', $otherStaff->status) == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status', $otherStaff->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="form-actions">

                <a
                    href="{{ route('admin.other-staff.index') }}"
                    class="btn btn-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-update"
                    id="updateBtn"
                >
                    <i class="fas fa-save"></i>
                    Update Staff
                </button>

            </div>

        </div>

    </form>

</div>


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
                <img
                    src="${event.target.result}"
                    alt="Profile Preview"
                    id="previewImage"
                >
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
                            {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            }
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

        updateBtn.innerHTML = `
            <i class="fas fa-spinner fa-spin"></i>
            Updating...
        `;

    });

});
</script>

@endsection