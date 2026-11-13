@extends('layouts.app')

@section('content')

<style>

    /* Profile Picture */
    .profile-upload-box {
        display: flex;
        align-items: center;
        gap: 18px;
        width: 100%;
        padding: 14px;
        border: 1px solid #e5eaf1;
        border-radius: 12px;
        background: #f8fafc;
    }

    .profile-preview {
        width: 82px;
        height: 82px;
        min-width: 82px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #ffffff;
        background: #e8eef7;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .10);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-preview img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        border-radius: 50%;
    }

    .profile-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eaf2ff;
        color: #2563eb;
        font-size: 25px;
        font-weight: 700;
    }

    .profile-upload-content {
        flex: 1;
        min-width: 0;
    }

    .profile-upload-content input[type="file"] {
        width: 100%;
        height: 42px;
        padding: 7px 10px;
        border: 1px solid #d9e1ec;
        border-radius: 8px;
        background: #ffffff;
        color: #475569;
        font-size: 13px;
        cursor: pointer;
    }

    .profile-upload-content input[type="file"]:hover {
        border-color: #8bbcf8;
    }

    .profile-upload-content input[type="file"]:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .profile-help {
        display: block;
        margin-top: 6px;
        color: #8a94a6;
        font-size: 11px;
    }

    .upload-status {
        margin-top: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .upload-status.loading {
        color: #2563eb;
    }

    .upload-status.success {
        color: #16a34a;
    }

    .upload-status.error {
        color: #dc2626;
    }

    .user-form-page {
        padding: 24px;
    }

    .page-header {
        margin-bottom: 22px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
        color: #172033;
    }

    .page-header p {
        margin: 5px 0 0;
        color: #7b8497;
        font-size: 13px;
    }

    .form-card {
        background: #fff;
        border: 1px solid #eef1f6;
        border-radius: 12px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .05);
        overflow: hidden;
        max-width: 950px;
    }

    .card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #edf0f5;
    }

    .card-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #172033;
    }

    .card-header p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #8a94a6;
    }

    .form-body {
        padding: 22px 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group {
        margin-bottom: 2px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .required {
        color: #dc2626;
    }

    .form-control,
    .form-select {
        width: 100%;
        height: 42px;
        border: 1px solid #dfe4ec;
        border-radius: 7px;
        padding: 0 12px;
        font-size: 13px;
        color: #334155;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    textarea.form-control {
        height: 90px;
        padding-top: 11px;
        resize: vertical;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc2626;
    }

    .invalid-feedback {
        display: block;
        color: #dc2626;
        font-size: 11px;
        margin-top: 5px;
    }

    .field-help {
        color: #8a94a6;
        font-size: 11px;
        margin-top: 5px;
    }

    /* Login section */

    .section-title {
        grid-column: 1 / -1;
        margin-top: 5px;
        padding-bottom: 8px;
        border-bottom: 1px solid #edf0f5;
        color: #172033;
        font-size: 14px;
        font-weight: 700;
    }

    .section-title:first-child {
        margin-top: 0;
    }

    .login-id-wrapper {
        position: relative;
    }

    .login-id-prefix {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 12px;
        pointer-events: none;
    }

    .login-id-wrapper .form-control {
        padding-left: 12px;
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }

    .toggle-password {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        border-radius: 5px;
    }

    .toggle-password:hover {
        background: #f1f5f9;
    }

    .toggle-password svg {
        width: 17px;
        height: 17px;
    }

    /* Role info */

    .role-info {
        margin-top: 7px;
        padding: 9px 11px;
        background: #f8fafc;
        border-radius: 7px;
        color: #64748b;
        font-size: 11px;
        line-height: 1.5;
    }

    .role-info strong {
        color: #334155;
    }

    /* Footer */

    .form-footer {
        padding: 15px 20px;
        border-top: 1px solid #edf0f5;
        display: flex;
        justify-content: flex-end;
        gap: 9px;
    }

    .btn {
        height: 39px;
        padding: 0 17px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #dfe4ec;
        color: #475569;
    }

    .btn-save {
        border: none;
        background: #2563eb;
        color: #fff;
    }

    .btn-save:hover {
        background: #1d4ed8;
    }

    .alert {
        max-width: 950px;
        border-radius: 8px;
        padding: 11px 14px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .alert-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    @media (max-width: 700px) {
        .user-form-page {
            padding: 15px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full,
        .section-title {
            grid-column: auto;
        }

        .profile-upload-box {
            align-items: flex-start;
        }

        .profile-preview {
            width: 72px;
            height: 72px;
            min-width: 72px;
        }

        .profile-upload-box {
            gap: 12px;
        }

        .form-footer {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
        }
    }
</style>

<div class="user-form-page">

    {{-- Header --}}
    <div class="page-header">
        <h2>Add User</h2>
        <p>Create a login account and assign a role to a staff member.</p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>

            <ul style="margin:6px 0 0 18px; padding:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">

        <div class="card-header">
            <h5>User Account Information</h5>
            <p>Set the credentials this user will use to access the school management system.</p>
        </div>

        <form action="{{ route('admin.settings.users.store') }}"
              method="POST"
              autocomplete="off">

            @csrf

            <div class="form-body">

                <div class="form-grid">

                    {{-- Account Information --}}
                    <div class="section-title">
                        Account Information
                    </div>

                    {{-- Name --}}
                    <div class="form-group full">

                        <label class="form-label">
                            Full Name <span class="required">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Enter user's full name"
                               required>

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Profile Picture --}}
                    <div class="form-group full">

                        <label class="form-label">
                            Profile Picture
                        </label>

                        <div class="profile-upload-box">

                            <div class="profile-preview">
                                <img id="profilePreview"
                                     src=""
                                     alt="Profile Preview"
                                     style="display: none;">

                                <div id="profilePlaceholder"
                                     class="profile-placeholder">
                                    U
                                </div>
                            </div>

                            <div class="profile-upload-content">

                                <input type="file"
                                       id="profile_photo_file"
                                       accept="image/jpeg,image/png,image/webp">

                                <input type="hidden"
                                       name="profile_photo"
                                       id="profile_photo"
                                       value="{{ old('profile_photo') }}">

                                <small class="profile-help">
                                    JPG, PNG or WebP • Maximum 250 KB after compression
                                </small>

                                <div id="uploadStatus"
                                     class="upload-status"></div>

                            </div>

                        </div>

                        @error('profile_photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Login ID --}}
                    <div class="form-group">

                        <label class="form-label">
                            Login ID <span class="required">*</span>
                        </label>

                        <input type="text"
       name="login_id"
       class="form-control @error('login_id') is-invalid @enderror"
       value="{{ old('login_id') }}"
       placeholder="Example: LIB-001"
       autocomplete="username"
       autocapitalize="characters"
       spellcheck="false"
       required>

                        <div class="field-help">
                            This is the ID the user will enter on the login page.
                        </div>

                        @error('login_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="form-group">

                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="user@example.com"
                               required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Login Security --}}
                    <div class="section-title">
                        Login Security
                    </div>

                    {{-- Password --}}
                    <div class="form-group">

                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>

                        <div class="password-wrapper">

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Minimum 8 characters"
                                   autocomplete="new-password"
                                   required>

                            <button type="button"
                                    class="toggle-password"
                                    onclick="togglePassword('password', this)"
                                    aria-label="Show password">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </button>

                        </div>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">

                        <label class="form-label">
                            Confirm Password <span class="required">*</span>
                        </label>

                        <div class="password-wrapper">

                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="Re-enter password"
                                   autocomplete="new-password"
                                   required>

                            <button type="button"
                                    class="toggle-password"
                                    onclick="togglePassword('password_confirmation', this)"
                                    aria-label="Show password">

                                <svg viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>

                            </button>

                        </div>

                    </div>

                    {{-- Access Settings --}}
                    <div class="section-title">
                        Access & Role
                    </div>

                    {{-- Role --}}
                    <div class="form-group">

                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>

                        <select name="role_id"
                                id="role_id"
                                class="form-select @error('role_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Role
                            </option>

                            @foreach($roles as $role)

                                <option value="{{ $role->id }}"
                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>

                            @endforeach

                        </select>

                        <div class="role-info" id="roleInfo">
                            Select a role to see its access description.
                        </div>

                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="form-group">

                        <label class="form-label">
                            Account Status <span class="required">*</span>
                        </label>

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="Active"
                                {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status') === 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <div class="field-help">
                            Inactive users should not be allowed to log in.
                        </div>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('admin.settings.users.index') }}"
                   class="btn btn-cancel">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-save">
                    Create User
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        if (input.type === 'password') {
            input.type = 'text';
            button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            button.setAttribute('aria-label', 'Show password');
        }
    }

    const roleSelect = document.getElementById('role_id');
    const roleInfo = document.getElementById('roleInfo');

    const roleDescriptions = {
        @foreach($roles as $role)
            "{{ $role->id }}": @json(
                $role->description ??
                'Access is controlled by the permissions assigned to this role.'
            ),
        @endforeach
    };

    function updateRoleInfo() {
        if (!roleSelect || !roleInfo) {
            return;
        }

        const roleId = roleSelect.value;

        if (roleId && roleDescriptions[roleId]) {
            roleInfo.innerHTML =
                '<strong>Role access:</strong> ' +
                roleDescriptions[roleId];
        } else {
            roleInfo.innerHTML =
                'Select a role to see its access description.';
        }
    }

    if (roleSelect) {
        roleSelect.addEventListener('change', updateRoleInfo);
        updateRoleInfo();
    }

    /*
     * ------------------------------------------------------------
     * Profile Picture Compression + Cloudinary Upload
     * Maximum target size: 250 KB
     * ------------------------------------------------------------
     */

    const profileInput = document.getElementById('profile_photo_file');
    const profilePreview = document.getElementById('profilePreview');
    const profilePlaceholder = document.getElementById('profilePlaceholder');
    const profilePhoto = document.getElementById('profile_photo');
    const uploadStatus = document.getElementById('uploadStatus');

    function canvasToBlob(canvas, quality) {
        return new Promise((resolve, reject) => {
            canvas.toBlob(
                blob => {
                    if (blob) {
                        resolve(blob);
                    } else {
                        reject(new Error('Image compression failed.'));
                    }
                },
                'image/jpeg',
                quality
            );
        });
    }

    async function compressProfileImage(file) {

        const MAX_WIDTH = 800;
        const MAX_HEIGHT = 800;
        const MAX_SIZE = 250 * 1024; // 250 KB

        const imageUrl = URL.createObjectURL(file);

        try {

            const image = await new Promise((resolve, reject) => {

                const img = new Image();

                img.onload = () => resolve(img);

                img.onerror = () => reject(
                    new Error('Unable to read the selected image.')
                );

                img.src = imageUrl;
            });

            let width = image.naturalWidth;
            let height = image.naturalHeight;

            // Keep aspect ratio.
            const scale = Math.min(
                MAX_WIDTH / width,
                MAX_HEIGHT / height,
                1
            );

            width = Math.max(1, Math.round(width * scale));
            height = Math.max(1, Math.round(height * scale));

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            canvas.width = width;
            canvas.height = height;

            context.imageSmoothingEnabled = true;
            context.imageSmoothingQuality = 'high';

            context.drawImage(
                image,
                0,
                0,
                width,
                height
            );

            let quality = 0.85;

            let blob = await canvasToBlob(
                canvas,
                quality
            );

            // Reduce JPEG quality until <= 250 KB.
            while (
                blob.size > MAX_SIZE &&
                quality > 0.40
            ) {

                quality -= 0.05;

                blob = await canvasToBlob(
                    canvas,
                    quality
                );
            }

            // If still too large, reduce dimensions.
            while (
                blob.size > MAX_SIZE &&
                canvas.width > 400 &&
                canvas.height > 400
            ) {

                canvas.width = Math.round(
                    canvas.width * 0.85
                );

                canvas.height = Math.round(
                    canvas.height * 0.85
                );

                context.clearRect(
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                context.drawImage(
                    image,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                quality = 0.75;

                blob = await canvasToBlob(
                    canvas,
                    quality
                );

                while (
                    blob.size > MAX_SIZE &&
                    quality > 0.40
                ) {

                    quality -= 0.05;

                    blob = await canvasToBlob(
                        canvas,
                        quality
                    );
                }
            }

            // Final result is JPEG and should be <= 250 KB.
            return new File(
                [blob],
                'profile-photo.jpg',
                {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                }
            );

        } finally {

            URL.revokeObjectURL(imageUrl);

        }
    }

    if (profileInput) {

        profileInput.addEventListener(
            'change',
            async function () {

                const file = this.files[0];

                if (!file) {
                    return;
                }

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {

                    uploadStatus.className =
                        'upload-status error';

                    uploadStatus.textContent =
                        'Please select a JPG, PNG or WebP image.';

                    this.value = '';

                    return;
                }

                // Browser-side source file limit.
                if (file.size > 10 * 1024 * 1024) {

                    uploadStatus.className =
                        'upload-status error';

                    uploadStatus.textContent =
                        'Original image must be smaller than 10 MB.';

                    this.value = '';

                    return;
                }

                try {

                    // Show original image immediately.
                    const localUrl =
                        URL.createObjectURL(file);

                    profilePreview.src = localUrl;
                    profilePreview.style.display = 'block';

                    if (profilePlaceholder) {
                        profilePlaceholder.style.display = 'none';
                    }

                    uploadStatus.className =
                        'upload-status loading';

                    uploadStatus.textContent =
                        'Compressing image...';

                    // Compress to maximum 250 KB.
                    const compressedFile =
                        await compressProfileImage(file);

                    const compressedKB =
                        (compressedFile.size / 1024).toFixed(0);

                    if (compressedFile.size > 250 * 1024) {

                        throw new Error(
                            'Could not compress the image below 250 KB.'
                        );
                    }

                    uploadStatus.textContent =
                        `Compressed to ${compressedKB} KB. Uploading...`;

                    const formData =
                        new FormData();

                    formData.append(
                        'file',
                        compressedFile,
                        'profile-photo.jpg'
                    );

                    formData.append(
                        'upload_preset',
                        '{{ env("CLOUDINARY_UPLOAD_PRESET") }}'
                    );

                    const response = await fetch(
                        'https://api.cloudinary.com/v1_1/{{ env("CLOUDINARY_CLOUD_NAME") }}/image/upload',
                        {
                            method: 'POST',
                            body: formData
                        }
                    );

                    const data =
                        await response.json();

                    if (!response.ok) {

                        throw new Error(
                            data.error?.message ||
                            'Cloudinary upload failed.'
                        );
                    }

                    // Save Cloudinary URL in hidden input.
                    profilePhoto.value =
                        data.secure_url;

                    uploadStatus.className =
                        'upload-status success';

                    uploadStatus.textContent =
                        `✓ Uploaded successfully (${compressedKB} KB)`;

                } catch (error) {

                    console.error(error);

                    profilePhoto.value = '';

                    profilePreview.src = '';
                    profilePreview.style.display = 'none';

                    if (profilePlaceholder) {
                        profilePlaceholder.style.display = 'flex';
                    }

                    uploadStatus.className =
                        'upload-status error';

                    uploadStatus.textContent =
                        error.message ||
                        'Upload failed. Please try again.';

                    this.value = '';
                }
            }
        );
    }
</script>

@endsection