@extends('layouts.app')



@section('content')



<style>
.user-form-page {
    --ink:#263b35; --ink-dark:#1d302b; --ink-hover:#456854;
    --text:#34423d; --muted:#78827a; --line:#e5e1d5;
    --paper:#fffefa; --paper-bg:#f7f6f0; --chalk:#f4f0df;
    --amber:#a87543; --green:#3a8060; --red:#bd5148;
    min-height:calc(100vh - 70px); padding:24px;
    color:var(--text); background:var(--paper-bg);
    font-family:"Public Sans",Inter,Arial,sans-serif;
}
.user-form-page *, .user-form-page *::before, .user-form-page *::after { box-sizing:border-box; }
.user-form-page .page-header {
    display:flex; align-items:center; gap:16px; margin-bottom:22px;
    padding:25px 28px; border:1px solid var(--line); border-radius:10px;
    background:radial-gradient(ellipse at top left,rgba(244,240,223,.7),transparent 65%),var(--paper);
    box-shadow:0 5px 18px rgba(38,59,53,.045);
}
.user-form-page .header-icon {
    display:flex; align-items:center; justify-content:center; flex:0 0 52px;
    width:52px; height:52px; border:1px solid #e6dfce; border-radius:11px;
    background:var(--chalk); color:var(--ink); font-size:21px;
}
.user-form-page .breadcrumb {
    margin-bottom:6px; color:var(--amber); font-size:9px;
    font-weight:800; letter-spacing:1.2px;
}
.user-form-page .breadcrumb span { color:#b8b09e; margin:0 5px; }
.user-form-page .page-header h2 {
    margin:0; color:var(--ink); font:700 28px/1.2 Georgia,"Times New Roman",serif;
}
.user-form-page .page-header p { margin:7px 0 0; color:var(--muted); font-size:13px; }
.user-form-page .form-card {
    max-width:950px; overflow:hidden; border:1px solid var(--line);
    border-radius:10px; background:var(--paper);
    box-shadow:0 5px 17px rgba(38,59,53,.04);
}
.user-form-page .card-header {
    padding:18px 21px; border-bottom:1px dashed #d8d1bf; background:var(--paper);
}
.user-form-page .card-header h5 {
    margin:0; color:var(--ink); font:700 18px Georgia,"Times New Roman",serif;
}
.user-form-page .card-header p { margin:5px 0 0; color:var(--muted); font-size:12px; }
.user-form-page .form-body { padding:23px 21px; }
.user-form-page .form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:19px 17px; }
.user-form-page .form-group { min-width:0; margin-bottom:2px; }
.user-form-page .form-group.full, .user-form-page .section-title { grid-column:1/-1; }
.user-form-page .form-label {
    display:block; margin-bottom:7px; color:var(--ink);
    font-size:11px; font-weight:800; letter-spacing:.35px;
}
.user-form-page .required { color:var(--red); }
.user-form-page .form-control, .user-form-page .form-select {
    width:100%; height:42px; padding:0 12px; border:1px solid #deded2;
    border-radius:7px; outline:none; background:#fffefa; color:var(--text);
    font:12px "Public Sans",Inter,Arial,sans-serif; transition:.2s ease;
}
.user-form-page textarea.form-control { height:90px; padding-top:11px; resize:vertical; }
.user-form-page .form-control:focus, .user-form-page .form-select:focus {
    border-color:var(--green); box-shadow:0 0 0 3px rgba(58,128,96,.10);
}
.user-form-page .form-control.is-invalid, .user-form-page .form-select.is-invalid { border-color:var(--red); }
.user-form-page .invalid-feedback { display:block; margin-top:5px; color:var(--red); font-size:11px; }
.user-form-page .field-help, .user-form-page .profile-help { display:block; margin-top:6px; color:var(--muted); font-size:11px; }
.user-form-page .section-title {
    margin-top:7px; padding-bottom:9px; border-bottom:1px solid var(--line);
    color:var(--ink); font-size:13px; font-weight:800; letter-spacing:.25px;
}
.user-form-page .section-title:first-child { margin-top:0; }
.user-form-page .login-id-wrapper, .user-form-page .password-wrapper { position:relative; }
.user-form-page .login-id-prefix {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    color:#94a3b8; font-size:12px; pointer-events:none;
}
.user-form-page .login-id-wrapper .form-control { padding-left:12px; }
.user-form-page .password-wrapper .form-control { padding-right:45px; }
.user-form-page .toggle-password {
    position:absolute; right:8px; top:50%; transform:translateY(-50%);
    display:flex; align-items:center; justify-content:center; width:32px; height:32px;
    border:0; border-radius:5px; background:transparent; color:#68766b; cursor:pointer;
}
.user-form-page .toggle-password:hover { background:var(--chalk); color:var(--ink); }
.user-form-page .toggle-password svg { width:17px; height:17px; }
.user-form-page .role-info {
    margin-top:7px; padding:10px 12px; border:1px solid var(--line);
    border-radius:7px; background:#faf9f4; color:var(--muted);
    font-size:11px; line-height:1.5;
}
.user-form-page .role-info strong { color:var(--ink); }
.user-form-page .profile-upload-box {
    display:flex; align-items:center; gap:18px; width:100%; padding:14px;
    border:1px solid var(--line); border-radius:9px; background:#faf9f4;
}
.user-form-page .profile-preview {
    display:flex; align-items:center; justify-content:center; flex:0 0 82px;
    width:82px; height:82px; overflow:hidden; border:2px solid #e6dfce;
    border-radius:50%; background:var(--chalk); box-shadow:0 2px 8px rgba(38,59,53,.08);
}
.user-form-page .profile-preview img { display:block; width:100%; height:100%; border-radius:50%; object-fit:cover; }
.user-form-page .profile-placeholder {
    display:flex; align-items:center; justify-content:center; width:100%; height:100%;
    border-radius:50%; background:var(--chalk); color:var(--ink); font-size:25px; font-weight:800;
}
.user-form-page .profile-upload-content { flex:1; min-width:0; }
.user-form-page .profile-upload-content input[type=file] {
    width:100%; min-height:42px; padding:7px 10px; border:1px solid #deded2;
    border-radius:7px; background:#fffefa; color:var(--text); font-size:12px; cursor:pointer;
}
.user-form-page .profile-upload-content input[type=file]:focus {
    outline:none; border-color:var(--green); box-shadow:0 0 0 3px rgba(58,128,96,.10);
}
.user-form-page .upload-status { margin-top:6px; font-size:11px; font-weight:700; }
.user-form-page .upload-status.loading { color:#527f79; }
.user-form-page .upload-status.success { color:#2f6b45; }
.user-form-page .upload-status.error { color:var(--red); }
.user-form-page .form-footer {
    display:flex; justify-content:flex-end; gap:9px; padding:16px 21px;
    border-top:1px solid var(--line); background:#fffefa;
}
.user-form-page .btn {
    display:inline-flex; align-items:center; justify-content:center; min-height:41px;
    padding:0 18px; border-radius:7px; font-size:12px; font-weight:700;
    text-decoration:none; cursor:pointer; transition:.2s ease;
}
.user-form-page .btn-cancel { border:1px solid #deded2; background:#fffefa; color:#59675d; }
.user-form-page .btn-cancel:hover { border-color:#b7c4b7; background:var(--chalk); color:var(--ink); }
.user-form-page .btn-save { border:1px solid var(--ink); background:var(--ink); color:#fff; }
.user-form-page .btn-save:hover { border-color:var(--ink-hover); background:var(--ink-hover); transform:translateY(-1px); }
.user-form-page .alert {
    max-width:950px; padding:12px 15px; margin-bottom:16px; border-radius:8px;
    border:1px solid #f0d5ce; background:#fff4ef; color:#a84840; font-size:12px;
}
@media(max-width:700px) {
    .user-form-page { padding:15px; }
    .user-form-page .page-header { align-items:flex-start; padding:20px; }
    .user-form-page .page-header h2 { font-size:23px; }
    .user-form-page .form-grid { grid-template-columns:1fr; }
    .user-form-page .form-group.full, .user-form-page .section-title { grid-column:1; }
    .user-form-page .profile-upload-box { align-items:flex-start; gap:12px; }
    .user-form-page .profile-preview { flex-basis:72px; width:72px; height:72px; }
    .user-form-page .form-footer { flex-direction:column-reverse; }
    .user-form-page .btn { width:100%; }
}
</style>



<div class="user-form-page">



    {{-- Header --}}

    <div class="page-header">
        <div class="header-icon"><i class="fas fa-user-plus"></i></div>
        <div>
            <div class="breadcrumb">SETTINGS <span>/</span> USER MANAGEMENT <span>/</span> CREATE</div>
            <h2>Add User</h2>
            <p>Create a login account and assign a role to a staff member.</p>
        </div>
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



    /* Profile Picture Compression + Cloudinary Upload - maximum target size: 250 KB */*



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

        const MAX_SIZE = 250 * 1024; // 250 KB*



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



            // Keep aspect ratio.*

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



            // Reduce JPEG quality until <= 250 KB.*

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



            // If still too large, reduce dimensions.*

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



            // Final result is JPEG and should be <= 250 KB.*

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



                // Browser-side source file limit.*

                if (file.size > 10 * 1024 * 1024) {



                    uploadStatus.className =

                        'upload-status error';



                    uploadStatus.textContent =

                        'Original image must be smaller than 10 MB.';



                    this.value = '';



                    return;

                }



                try {



                    // Show original image immediately.*

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



                    // Compress to maximum 250 KB.*

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

                        'https\://api.cloudinary.com/v1_1/{{ env("CLOUDINARY_CLOUD_NAME") }}/image/upload',

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



                    // Save Cloudinary URL in hidden input.*

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