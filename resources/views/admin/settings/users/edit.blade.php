@extends('layouts.app')



@section('content')



<style>
.user-form-page {
    --ink: #263b35;
    --ink-dark: #1d302b;
    --ink-hover: #456854;
    --text: #34423d;
    --muted: #78827a;
    --line: #e5e1d5;
    --paper: #fffefa;
    --paper-bg: #f7f6f0;
    --chalk: #f4f0df;
    --amber: #a87543;
    --green: #3a8060;
    --red: #bd5148;
    padding: 24px;
    color: var(--text);
}
.user-form-page .page-header {
    display:flex; align-items:flex-start; justify-content:space-between;
    gap:16px; margin-bottom:22px;
}
.user-form-page .eyebrow {
    color:var(--amber); font-size:10px; font-weight:800;
    letter-spacing:1.5px; text-transform:uppercase; margin-bottom:7px;
}
.user-form-page .page-header h2 {
    margin:0; color:var(--ink); font-size:26px; line-height:1.2; font-weight:800;
}
.user-form-page .page-header p {
    margin:7px 0 0; color:var(--muted); font-size:13px;
}
.user-form-page .back-link {
    display:inline-flex; align-items:center; gap:7px; padding:9px 13px;
    border:1px solid var(--line); border-radius:9px; color:var(--ink);
    background:var(--paper); text-decoration:none; font-size:12px; font-weight:700;
}
.user-form-page .back-link:hover { background:var(--chalk); }
.user-form-page .form-card {
    max-width:1000px; background:var(--paper); border:1px solid var(--line);
    border-radius:15px; box-shadow:0 8px 24px rgba(29,48,43,.06); overflow:hidden;
}
.user-form-page .card-header {
    padding:19px 22px; background:linear-gradient(110deg,var(--ink-dark),var(--ink));
    border-bottom:3px solid #c99a42;
}
.user-form-page .card-header h5 { margin:0; color:#fffefa; font-size:16px; font-weight:800; }
.user-form-page .card-header p { margin:5px 0 0; color:#d8e1d9; font-size:12px; }
.user-form-page .form-body { padding:24px 22px; }
.user-form-page .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:19px 20px; }
.user-form-page .form-group { min-width:0; margin:0; }
.user-form-page .form-group.full, .user-form-page .section-title { grid-column:1/-1; }
.user-form-page .section-title {
    margin-top:7px; padding:0 0 10px; border-bottom:1px solid var(--line);
    color:var(--ink); font-size:13px; font-weight:800; letter-spacing:.35px;
}
.user-form-page .section-title:first-child { margin-top:0; }
.user-form-page .form-label { display:block; margin-bottom:7px; color:var(--text); font-size:12px; font-weight:700; }
.user-form-page .required { color:var(--red); }
.user-form-page .form-control, .user-form-page .form-select {
    display:block; width:100%; height:43px; box-sizing:border-box; padding:0 12px;
    border:1px solid #d9ded5; border-radius:8px; background:#fff;
    color:var(--text); font-size:13px; outline:none; box-shadow:none;
}
.user-form-page .form-control:focus, .user-form-page .form-select:focus {
    border-color:var(--ink-hover); box-shadow:0 0 0 3px rgba(69,104,84,.12);
}
.user-form-page .form-control.is-invalid, .user-form-page .form-select.is-invalid { border-color:var(--red); }
.user-form-page .invalid-feedback { display:block; margin-top:5px; color:var(--red); font-size:11px; }
.user-form-page .field-help { margin-top:6px; color:var(--muted); font-size:11px; line-height:1.5; }
.user-form-page .profile-upload-box {
    display:flex; align-items:center; gap:18px; padding:15px;
    border:1px dashed #c9d0c5; border-radius:12px; background:var(--paper-bg);
}
.user-form-page .profile-preview { flex:0 0 auto; }
.user-form-page .profile-preview img, .user-form-page .profile-placeholder {
    width:86px; height:86px; object-fit:cover; border-radius:50%;
    border:3px solid #e5e1d5; display:flex; align-items:center; justify-content:center;
}
.user-form-page .profile-placeholder {
    background:var(--ink); color:#fff; font-size:27px; font-weight:800;
}
.user-form-page .profile-upload-content { flex:1; min-width:0; }
.user-form-page .profile-upload-content input[type=file] { height:auto; padding:9px; background:#fff; }
.user-form-page .upload-status { margin-top:6px; font-size:11px; }
.user-form-page .password-wrapper { position:relative; }
.user-form-page .password-wrapper .form-control { padding-right:46px; }
.user-form-page .toggle-password {
    position:absolute; right:7px; top:50%; transform:translateY(-50%);
    width:32px; height:32px; border:0; border-radius:7px;
    background:transparent; color:var(--muted); cursor:pointer;
}
.user-form-page .toggle-password:hover { background:var(--chalk); color:var(--ink); }
.user-form-page .toggle-password svg { width:17px; height:17px; }
.user-form-page .role-info {
    margin-top:8px; padding:10px 12px; border-left:3px solid var(--amber);
    border-radius:0 8px 8px 0; background:var(--paper-bg);
    color:var(--muted); font-size:11px; line-height:1.55;
}
.user-form-page .role-info strong { color:var(--ink); }
.user-form-page .form-footer {
    display:flex; justify-content:flex-end; gap:10px; padding:16px 22px;
    border-top:1px solid var(--line); background:#fbfaf5;
}
.user-form-page .btn {
    min-height:40px; padding:0 17px; border-radius:8px; display:inline-flex;
    align-items:center; justify-content:center; gap:7px; cursor:pointer;
    text-decoration:none; font-size:12px; font-weight:800; transition:.15s ease;
}
.user-form-page .btn-cancel { border:1px solid var(--line); background:#fff; color:var(--ink); }
.user-form-page .btn-cancel:hover { background:var(--chalk); }
.user-form-page .btn-save { border:1px solid var(--ink); background:var(--ink); color:#fffefa; }
.user-form-page .btn-save:hover { background:var(--ink-hover); }
.user-form-page .alert {
    max-width:1000px; margin-bottom:16px; padding:12px 15px;
    border:1px solid #efc7c2; border-radius:9px; background:#fff5f3;
    color:var(--red); font-size:12px;
}
@media(max-width:700px) {
    .user-form-page { padding:15px; }
    .user-form-page .page-header { flex-direction:column; }
    .user-form-page .form-grid { grid-template-columns:1fr; }
    .user-form-page .form-group.full, .user-form-page .section-title { grid-column:auto; }
    .user-form-page .profile-upload-box { align-items:flex-start; flex-direction:column; }
    .user-form-page .form-footer { flex-direction:column-reverse; }
    .user-form-page .btn { width:100%; }
}
</style>



<div class="user-form-page">



    {{-- Header --}}

    <div class="page-header">
        <div>
            <div class="eyebrow">Settings / User Management / Edit</div>
            <h2>Edit User</h2>
            <p>Update this user's account details, login credentials and access role.</p>
        </div>
        <a href="{{ route('admin.settings.users.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
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



    // Check file size*

    if (file.size > 2 * 1024 * 1024) {



        uploadStatus.innerHTML =

            '<span class="text-danger">Image must be less than 2 MB.</span>';



        this.value = '';



        return;

    }



    // Preview selected image*

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



        // Save Cloudinary URL in hidden input*

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