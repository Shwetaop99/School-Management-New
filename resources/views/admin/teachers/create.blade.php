@extends('layouts.app')

@section('title', 'Add Teacher')
@section('page-title', 'Add Teacher')

@section('content')

<style>
    .teacher-form-page{
        --f-ink:var(--ink,#1d3a33);
        --f-ink2:var(--ink-2,#16302a);
        --f-line:var(--line,#d8d1bf);
        --f-muted:var(--muted,#6b7c75);
        --f-text:var(--text,#2c3e3a);
        --f-accent:var(--accent,#e4572e);
        --f-serif:var(--serif,Georgia,serif);
        --f-blue:#2b5d8a;
        max-width:1100px;margin:0 auto;padding-bottom:28px;color:var(--f-text);font-size:13.5px
    }
    .teacher-form-page *,.teacher-form-page *::before,.teacher-form-page *::after{box-sizing:border-box}
    .teacher-form-page a{text-decoration:none}

    .tf-paper{background:#fff;border:1px solid var(--f-line);border-radius:10px}

    /* ---------- Hero ---------- */
    .tf-hero{position:relative;overflow:hidden;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:20px;padding:22px 28px 22px 56px}
    .tf-hero::before{content:"";position:absolute;left:34px;top:0;bottom:0;width:2px;background:rgba(228,87,46,.35)}
    .tf-eyebrow{margin-bottom:6px;color:var(--f-accent);font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase}
    .tf-hero h1{margin:0 0 4px;color:var(--f-ink);font-family:var(--f-serif);font-size:26px;font-weight:700}
    .tf-hero p{margin:0;color:var(--f-muted)}

    /* ---------- Buttons ---------- */
    .tf-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border:1px solid transparent;border-radius:8px;font-family:inherit;font-size:13.5px;font-weight:600;line-height:1.3;white-space:nowrap;cursor:pointer;transition:background .15s,border-color .15s,color .15s,transform .15s}
    .tf-btn-line{background:#fff;border-color:#cfc8b6;color:var(--f-text)}
    .tf-btn-line:hover{border-color:var(--f-ink);color:var(--f-ink)}
    .tf-btn-solid{min-width:150px;background:var(--f-ink);border-color:var(--f-ink);color:#fff}
    .tf-btn-solid:hover{background:var(--f-ink2);border-color:var(--f-ink2);color:#fff;transform:translateY(-1px)}
    .tf-btn-solid:disabled{opacity:.75;cursor:not-allowed;transform:none}

    .tf-spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,.4);border-top-color:#fff;border-radius:50%;animation:tf-spin .7s linear infinite}
    @keyframes tf-spin{to{transform:rotate(360deg)}}

    /* ---------- Error summary ---------- */
    .tf-alert{margin-bottom:18px;padding:14px 18px;border:1px solid #f0d5ce;border-radius:10px;background:#fff4ef;color:#a84840;font-size:13px;line-height:1.6}
    .tf-alert ul{margin:6px 0 0;padding-left:20px}

    /* ---------- Form card + sections ---------- */
    .tf-card{overflow:hidden}
    .tf-section{padding:24px 28px;border-bottom:1px dashed var(--f-line)}
    .tf-section:last-of-type{border-bottom:0}

    .tf-section-head{display:flex;align-items:center;gap:12px;margin-bottom:20px}
    .tf-section-icon{display:flex;align-items:center;justify-content:center;flex:0 0 40px;width:40px;height:40px;border-radius:10px;background:color-mix(in srgb,var(--f-blue) 12%,#fff);color:var(--f-blue);font-size:17px}
    .tf-section-head h3{margin:0 0 2px;color:var(--f-ink);font-family:var(--f-serif);font-size:16px;font-weight:700}
    .tf-section-head p{margin:0;color:var(--f-muted);font-size:12px}

    .tf-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
    .tf-field{min-width:0}
    .tf-field.full{grid-column:1 / -1}

    .tf-label{display:block;margin-bottom:6px;color:var(--f-ink);font-size:12px;font-weight:700}
    .tf-label b{color:var(--f-accent);font-weight:700}

    /* ---------- Controls ---------- */
    .tf-control{width:100%;min-width:0;height:42px;padding:0 14px;border:1px solid #cfc8b6;border-radius:8px;background:#fff;color:var(--f-text);font-family:inherit;font-size:14px;outline:0;transition:border-color .15s,box-shadow .15s,background .15s}
    textarea.tf-control{height:auto;min-height:104px;padding:12px 14px;line-height:1.5;resize:vertical}
    select.tf-control{cursor:pointer}

    .tf-control:hover{border-color:#b9b19a}
    .tf-control:focus{border-color:var(--f-ink);box-shadow:0 0 0 3px rgba(29,58,51,.1)}

    .tf-control[readonly]{background:#f8f6f0;color:var(--f-blue);font-weight:600;cursor:not-allowed}

    .tf-control.is-invalid,.tf-control.has-error{border-color:var(--f-accent);background:#fffaf8}
    .tf-control.is-valid{border-color:#3a9d6b}

    input[type="file"].tf-control{padding:6px 10px;line-height:28px}
    input[type="file"].tf-control::file-selector-button{margin-right:12px;padding:6px 14px;border:1px solid #cfc8b6;border-radius:6px;background:#f8f6f0;color:var(--f-text);font-family:inherit;font-size:12.5px;font-weight:600;cursor:pointer}
    input[type="file"].tf-control::file-selector-button:hover{border-color:var(--f-ink);color:var(--f-ink)}

    .tf-help{display:flex;justify-content:space-between;gap:10px;margin-top:6px;color:var(--f-muted);font-size:11.5px}
    .tf-error{margin-top:5px;color:var(--f-accent);font-size:12px;font-weight:600}
    .tf-warning{display:none;margin-top:6px;padding:7px 10px;border-left:3px solid var(--f-accent);border-radius:6px;background:#fff1ed;color:#a84840;font-size:12px;font-weight:600}
    .tf-warning.show{display:block}

    /* ---------- Profile photo ---------- */
    .tf-profile{display:flex;align-items:center;gap:22px;margin-bottom:18px;padding:18px;border:1px dashed var(--f-line);border-radius:10px;background:#faf8f3}

    .tf-avatar{position:relative;flex:0 0 100px;width:100px;height:100px}

    .tf-avatar-circle{display:flex;align-items:center;justify-content:center;width:100px;height:100px;overflow:hidden;border:3px solid #fff;border-radius:50%;background:color-mix(in srgb,var(--f-blue) 12%,#fff);box-shadow:0 4px 12px rgba(29,58,51,.12);color:var(--f-blue);font-size:34px}
    .tf-avatar-circle img{display:none;width:100%;height:100%;object-fit:cover}
    .tf-avatar-circle.has-image img{display:block}
    .tf-avatar-circle.has-image i{display:none}

    .tf-avatar-badge{position:absolute;right:-2px;bottom:0;display:flex;align-items:center;justify-content:center;width:28px;height:28px;border:2px solid #fff;border-radius:50%;background:var(--f-ink);color:#fff;font-size:12px}

    .tf-profile-info h4{margin:0 0 4px;color:var(--f-ink);font-size:15px}
    .tf-profile-info p{margin:0;color:var(--f-muted);font-size:12.5px;line-height:1.6}
    .tf-picked{margin-top:6px;color:var(--f-blue);font-size:12px;font-weight:600;overflow-wrap:anywhere}

    /* ---------- Actions ---------- */
    .tf-actions{display:flex;justify-content:flex-end;gap:12px;padding:20px 28px;border-top:1px dashed var(--f-line);background:#faf9f6}

    /* ---------- Responsive ---------- */
    @media (max-width:800px){
        .tf-hero{padding:20px 18px 20px 42px}
        .tf-hero::before{left:22px}
        .tf-hero h1{font-size:22px}
        .tf-hero .tf-btn{width:100%}
        .tf-section{padding:20px 18px}
        .tf-grid{grid-template-columns:1fr}
        .tf-field.full{grid-column:auto}
    }

    @media (max-width:600px){
        .tf-profile{flex-direction:column;align-items:flex-start}
        .tf-actions{flex-direction:column-reverse;padding:18px}
        .tf-actions .tf-btn{width:100%}
    }

    @media (prefers-reduced-motion:reduce){
        .teacher-form-page *{transition:none!important}
        .tf-spinner{animation-duration:2s}
    }
</style>

<div class="teacher-form-page">

    {{-- Header --}}
    <header class="tf-paper tf-hero">
        <div>
            <div class="tf-eyebrow">Teacher Management</div>
            <h1>Add Teacher</h1>
            <p>Add a new teacher to the school.</p>
        </div>

        <a href="{{ route('admin.teachers.index') }}" class="tf-btn tf-btn-line">
            <i class="bi bi-arrow-left"></i> Back to Teachers
        </a>
    </header>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="tf-alert" role="alert">
            <strong><i class="bi bi-exclamation-triangle"></i> Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.teachers.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="teacherCreateForm"
          class="tf-paper tf-card"
          novalidate>
        @csrf

        {{-- ===================== Personal information ===================== --}}
        <section class="tf-section">
            <div class="tf-section-head">
                <div class="tf-section-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <h3>Personal Information</h3>
                    <p>Basic information about the teacher</p>
                </div>
            </div>

            <div class="tf-grid">
                <div class="tf-field">
                    <label class="tf-label" for="first_name">First name <b>*</b></label>
                    <input type="text" id="first_name" name="first_name"
                           class="tf-control @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name') }}" placeholder="Enter first name" required>
                    @error('first_name') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="last_name">Last name <b>*</b></label>
                    <input type="text" id="last_name" name="last_name"
                           class="tf-control @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name') }}" placeholder="Enter last name" required>
                    @error('last_name') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="date_of_birth">Date of birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           class="tf-control @error('date_of_birth') is-invalid @enderror"
                           value="{{ old('date_of_birth') }}">
                    @error('date_of_birth') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="gender">Gender</label>
                    <select id="gender" name="gender"
                            class="tf-control @error('gender') is-invalid @enderror">
                        <option value="">Select gender</option>
                        @foreach (['Male', 'Female', 'Other'] as $option)
                            <option value="{{ $option }}" @selected(old('gender') === $option)>{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('gender') <div class="tf-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </section>

        {{-- ===================== Contact information ===================== --}}
        <section class="tf-section">
            <div class="tf-section-head">
                <div class="tf-section-icon"><i class="bi bi-person-lines-fill"></i></div>
                <div>
                    <h3>Contact Information</h3>
                    <p>Teacher contact details</p>
                </div>
            </div>

            <div class="tf-grid">
                <div class="tf-field">
                    <label class="tf-label" for="teacherEmail">Email <b>*</b></label>
                    <input type="email" id="teacherEmail" name="email"
                           class="tf-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="example@gmail.com" required>
                    <div id="emailWarning" class="tf-warning" role="alert"></div>
                    @error('email') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="teacherPhone">Phone</label>
                    <input type="text" id="teacherPhone" name="phone"
                           class="tf-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="Enter 10 digit phone number"
                           maxlength="10" inputmode="numeric" autocomplete="tel">
                    <div id="phoneWarning" class="tf-warning" role="alert"></div>
                    @error('phone') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field full">
                    <label class="tf-label" for="teacherAddress">Address</label>
                    <textarea id="teacherAddress" name="address" rows="4" maxlength="1000"
                              class="tf-control @error('address') is-invalid @enderror"
                              placeholder="Enter complete address">{{ old('address') }}</textarea>
                    <div class="tf-help">
                        <span>Enter the complete residential address.</span>
                        <span id="addressCounter">0 / 1000</span>
                    </div>
                    @error('address') <div class="tf-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </section>

        {{-- ===================== Professional information ===================== --}}
        <section class="tf-section">
            <div class="tf-section-head">
                <div class="tf-section-icon"><i class="bi bi-briefcase-fill"></i></div>
                <div>
                    <h3>Professional Information</h3>
                    <p>Teacher qualification and work details</p>
                </div>
            </div>

            <div class="tf-grid">
                <div class="tf-field">
                    <label class="tf-label" for="teacherIdPreview">Teacher ID</label>
                    <input type="text" id="teacherIdPreview" class="tf-control"
                           value="Automatically generated" readonly tabindex="-1">
                    <div class="tf-help"><span>The Teacher ID is generated automatically when you save.</span></div>
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="status">Status <b>*</b></label>
                    <select id="status" name="status"
                            class="tf-control @error('status') is-invalid @enderror" required>
                        <option value="Active" @selected(old('status', 'Active') === 'Active')>Active</option>
                        <option value="Inactive" @selected(old('status') === 'Inactive')>Inactive</option>
                    </select>
                    @error('status') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="qualification">Qualification</label>
                    <input type="text" id="qualification" name="qualification"
                           class="tf-control @error('qualification') is-invalid @enderror"
                           value="{{ old('qualification') }}" placeholder="e.g. M.Sc, B.Ed">
                    @error('qualification') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="subject">Subject</label>
                    <input type="text" id="subject" name="subject"
                           class="tf-control @error('subject') is-invalid @enderror"
                           value="{{ old('subject') }}" placeholder="Enter subject taught">
                    <div class="tf-help"><span>Enter the main subject taught by the teacher.</span></div>
                    @error('subject') <div class="tf-error">{{ $message }}</div> @enderror
                </div>

                <div class="tf-field">
                    <label class="tf-label" for="joining_date">Joining date</label>
                    <input type="date" id="joining_date" name="joining_date"
                           class="tf-control @error('joining_date') is-invalid @enderror"
                           value="{{ old('joining_date') }}">
                    @error('joining_date') <div class="tf-error">{{ $message }}</div> @enderror
                </div>
            </div>
        </section>

        {{-- ===================== Profile image ===================== --}}
        <section class="tf-section">
            <div class="tf-section-head">
                <div class="tf-section-icon"><i class="bi bi-image-fill"></i></div>
                <div>
                    <h3>Profile Image</h3>
                    <p>Upload the teacher profile photo</p>
                </div>
            </div>

            <div class="tf-profile">
                <div class="tf-avatar">
                    <div class="tf-avatar-circle" id="avatarCircle">
                        <i class="bi bi-person-fill"></i>
                        <img src="" alt="Profile preview" id="profilePreview">
                    </div>
                    <span class="tf-avatar-badge"><i class="bi bi-camera-fill"></i></span>
                </div>

                <div class="tf-profile-info">
                    <h4>Teacher profile photo</h4>
                    <p>Upload a clear profile image of the teacher.</p>
                    <div class="tf-picked" id="selectedImageName"></div>
                </div>
            </div>

            <div class="tf-field">
                <label class="tf-label" for="profileImageInput">Profile image</label>
                <input type="file" id="profileImageInput" name="profile_image"
                       class="tf-control @error('profile_image') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                <div class="tf-help">
                    <span>JPG, PNG or WEBP</span>
                    <span>Maximum size: 2 MB</span>
                </div>
                @error('profile_image') <div class="tf-error">{{ $message }}</div> @enderror
            </div>
        </section>

        {{-- ===================== Actions ===================== --}}
        <footer class="tf-actions">
            <a href="{{ route('admin.teachers.index') }}" class="tf-btn tf-btn-line">
                <i class="bi bi-x-lg"></i> Cancel
            </a>

            <button type="submit" class="tf-btn tf-btn-solid" id="saveTeacherBtn">
                <i class="bi bi-check-lg" id="saveTeacherIcon"></i>
                <span id="saveTeacherText">Save Teacher</span>
            </button>
        </footer>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $ = (id) => document.getElementById(id);

    const form         = $('teacherCreateForm');
    const email        = $('teacherEmail');
    const phone        = $('teacherPhone');
    const emailWarning = $('emailWarning');
    const phoneWarning = $('phoneWarning');
    const address      = $('teacherAddress');
    const counter      = $('addressCounter');

    /* ---------- Address counter ---------- */
    if (address && counter) {
        const updateCounter = () => { counter.textContent = address.value.length + ' / 1000'; };
        address.addEventListener('input', updateCounter);
        updateCounter();
    }

    /* ---------- Validation helpers ---------- */
    function setState(input, warning, message) {
        input.classList.remove('has-error', 'is-valid');

        if (message) {
            input.classList.add('has-error');
            warning.textContent = message;
            warning.classList.add('show');
            return false;
        }

        warning.classList.remove('show');
        if (input.value.trim() !== '') input.classList.add('is-valid');
        return true;
    }

    function validateEmail() {
        const value = email.value.trim();

        if (value === '') {
            return setState(email, emailWarning, 'Email address is required.');
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return setState(email, emailWarning, 'Please enter a valid email address, e.g. example@gmail.com.');
        }
        return setState(email, emailWarning, '');
    }

    // The phone number is optional, but if it is entered it must be exactly 10 digits.
    function validatePhone() {
        const value = phone.value.trim();

        if (value === '') {
            phone.classList.remove('has-error', 'is-valid');
            phoneWarning.classList.remove('show');
            return true;
        }
        if (value.length !== 10) {
            return setState(phone, phoneWarning, 'Phone number must contain exactly 10 digits.');
        }
        return setState(phone, phoneWarning, '');
    }

    /* ---------- Live validation ---------- */
    if (email) {
        email.addEventListener('input', function () { if (email.value.trim() !== '') validateEmail(); });
        email.addEventListener('blur', validateEmail);
    }

    if (phone) {
        phone.addEventListener('input', function () {
            phone.value = phone.value.replace(/\D/g, '').slice(0, 10);
            if (phone.value.length === 10) validatePhone();
            else phone.classList.remove('is-valid');
        });
        phone.addEventListener('blur', validatePhone);
    }

    /* ---------- Profile image preview ---------- */
    const imageInput   = $('profileImageInput');
    const preview      = $('profilePreview');
    const avatarCircle = $('avatarCircle');
    const imageName    = $('selectedImageName');
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    function clearImage() {
        imageInput.value = '';
        preview.removeAttribute('src');
        avatarCircle.classList.remove('has-image');
        imageName.textContent = '';
    }

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = imageInput.files[0];

            if (!file) {
                clearImage();
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                alert('Please choose a JPG, PNG or WEBP image.');
                clearImage();
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('The selected image is larger than 2 MB. Please choose a smaller image.');
                clearImage();
                return;
            }

            imageName.textContent = 'Selected: ' + file.name;

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                avatarCircle.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        });
    }

    /* ---------- Submit: validate, then show the loading state ---------- */
    if (form) {
        form.addEventListener('submit', function (event) {
            const emailOk = validateEmail();
            const phoneOk = validatePhone();

            if (!emailOk || !phoneOk) {
                event.preventDefault();
                (!emailOk ? email : phone).focus();
                return;
            }

            const button = $('saveTeacherBtn');
            const icon   = $('saveTeacherIcon');

            button.disabled = true;
            $('saveTeacherText').textContent = 'Saving...';
            icon.className = 'tf-spinner';
        });
    }
});
</script>

@endsection