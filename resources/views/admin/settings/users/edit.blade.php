@extends('layouts.app')

@section('content')

<style>

    .profile-upload-box {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #fafafa;
}

.profile-preview img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #e5e7eb;
}

.profile-upload-content {
    flex: 1;
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

    .current-role {
        margin-top: 7px;
        font-size: 11px;
        color: #64748b;
    }

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
            flex-direction: column;
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
        <h2>Edit User</h2>
        <p>Update this user's account details, login credentials and access role.</p>
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
            <p>Update the account information for {{ $user->name }}.</p>
        </div>



        <form action="{{ route('admin.settings.users.update', $user) }}"
              method="POST"
              autocomplete="off">

            @csrf
            @method('PUT')

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
                               value="{{ old('name', $user->name) }}"
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

                                @if($user->profile_photo)
                                    <img id="profilePreview"
                                         src="{{ $user->profile_photo }}"
                                         alt="Profile Preview">
                                    <div id="profilePlaceholder"
                                         class="profile-placeholder"
                                         style="display:none;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @else
                                    <img id="profilePreview"
                                         src=""
                                         alt="Profile Preview"
                                         style="display:none;">
                                    <div id="profilePlaceholder"
                                         class="profile-placeholder">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif

                            </div>

                            <div class="profile-upload-content">

                                <input type="file"
                                       id="profile_photo_file"
                                       class="form-control"
                                       accept="image/jpeg,image/png,image/webp">

                                <input type="hidden"
                                       name="profile_photo"
                                       id="profile_photo"
                                       value="{{ old('profile_photo', $user->profile_photo) }}">

                                <div class="field-help">
                                    JPG, PNG or WebP. Maximum 2 MB.
                                </div>

                                <div id="uploadStatus" class="upload-status"></div>

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
                               id="login_id"
                               class="form-control @error('login_id') is-invalid @enderror"
                               value="{{ old('login_id', $user->login_id) }}"
                               placeholder="Example: LIB-001"
                               autocomplete="off"
                               autocorrect="off"
                               autocapitalize="characters"
                               spellcheck="false"
                               inputmode="text"
                               data-form-type="other"
                               required>

                        <div class="field-help">
                            This is the ID the user uses to log in.
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
                               value="{{ old('email', $user->email) }}"
                               placeholder="user@example.com"
                               autocomplete="email"
                               required>

                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Password --}}
                    <div class="section-title">
                        Login Security
                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            New Password
                        </label>

                        <div class="password-wrapper">

                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Leave blank to keep current password"
                                   autocomplete="new-password">

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

                        <div class="field-help">
                            Leave empty if you don't want to change the password.
                        </div>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

                    {{-- Confirm Password --}}
                    <div class="form-group">

                        <label class="form-label">
                            Confirm New Password
                        </label>

                        <div class="password-wrapper">

                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   class="form-control"
                                   placeholder="Re-enter new password"
                                   autocomplete="new-password">

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

                    {{-- Access --}}
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
                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                                {{ old('status', $user->status) === 'Active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="Inactive"
                                {{ old('status', $user->status) === 'Inactive' ? 'selected' : '' }}>
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
                    Update User
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);

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

    roleSelect.addEventListener('change', updateRoleInfo);

    updateRoleInfo();

    const profileInput = document.getElementById('profile_photo_file');
const profilePreview = document.getElementById('profilePreview');
const profilePhoto = document.getElementById('profile_photo');
const uploadStatus = document.getElementById('uploadStatus');

profileInput.addEventListener('change', async function () {

    const file = this.files[0];

    if (!file) {
        return;
    }

    // Check file size
    if (file.size > 2 * 1024 * 1024) {

        uploadStatus.innerHTML =
            '<span class="text-danger">Image must be less than 2 MB.</span>';

        this.value = '';

        return;
    }

    // Preview selected image
    profilePreview.src = URL.createObjectURL(file);

    uploadStatus.innerHTML =
        '<span class="text-primary">Uploading image...</span>';

    const formData = new FormData();

    formData.append('file', file);

    formData.append(
        'upload_preset',
        '{{ env("CLOUDINARY_UPLOAD_PRESET") }}'
    );

    try {

        const response = await fetch(
            'https://api.cloudinary.com/v1_1/{{ env("CLOUDINARY_CLOUD_NAME") }}/image/upload',
            {
                method: 'POST',
                body: formData
            }
        );

        const data = await response.json();

        if (!response.ok) {
            throw new Error(
                data.error?.message || 'Upload failed'
            );
        }

        // Save Cloudinary URL in hidden input
        profilePhoto.value = data.secure_url;

        uploadStatus.innerHTML =
            '<span class="text-success">✓ Profile picture uploaded</span>';

    } catch (error) {

        console.error(error);

        uploadStatus.innerHTML =
            '<span class="text-danger">Upload failed. Please try again.</span>';
    }
});
</script>

@endsection