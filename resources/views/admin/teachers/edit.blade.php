@extends('layouts.app')

@section('title', 'Edit Teacher')
@section('page-title', 'Edit Teacher')

@section('content')

<style>
    .teacher-form-page{max-width:1100px;margin:0 auto}
    .paper{background:#fff;border:1px solid var(--line);border-radius:10px}

    /* Hero */
    .form-hero{
        margin-bottom:22px;
        padding:24px 28px;
        position:relative;
        overflow:hidden;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:20px;
        flex-wrap:wrap;
    }
    .form-hero::before{
        content:"";
        position:absolute;
        left:0;top:0;bottom:0;
        width:4px;
        background:rgba(228,87,46,.55);
        border-radius:10px 0 0 10px;
    }
    .form-hero h1{
        font-family:var(--serif);
        font-size:24px;
        font-weight:700;
        color:var(--ink);
        margin:0 0 6px;
    }
    .form-hero p{margin:0;color:var(--muted);font-size:13.5px}

    .btn-line{
        display:inline-flex;align-items:center;gap:8px;
        padding:9px 16px;border-radius:8px;
        font-size:13.5px;font-weight:500;
        text-decoration:none;
        background:#fff;border:1px solid #cfc8b6;color:var(--text);
    }
    .btn-line:hover{border-color:var(--ink);color:var(--ink)}
    .btn-solid{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 20px;border-radius:8px;
        font-size:13.5px;font-weight:600;
        background:var(--ink);color:#fff;border:1px solid var(--ink);
        cursor:pointer;
    }
    .btn-solid:hover{background:var(--ink-2);color:#fff}
    .btn-solid:disabled{opacity:.7;cursor:not-allowed}

    /* Form sections */
    .form-card{overflow:hidden;margin-bottom:22px}
    .form-section{padding:24px 28px;border-bottom:1px dashed #d8d1bf}
    .form-section:last-of-type{border-bottom:none}

    .section-head{
        display:flex;align-items:center;gap:12px;
        margin-bottom:20px;
    }
    .section-icon{
        width:40px;height:40px;border-radius:10px;
        display:flex;align-items:center;justify-content:center;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;font-size:17px;
    }
    .section-head h3{
        margin:0 0 2px;
        font-family:var(--serif);
        font-size:16px;font-weight:700;color:var(--ink);
    }
    .section-head p{margin:0;font-size:12px;color:var(--muted)}

    .form-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:18px;
    }
    .form-field.full{grid-column:1 / -1}

    .field-label{
        display:block;
        margin-bottom:6px;
        font-size:12px;font-weight:700;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:.3px;
    }
    .required{color:#e4572e}

    .field-input,.field-select,.field-textarea{
        width:100%;
        height:42px;
        padding:0 14px;
        border:1px solid #cfc8b6;
        border-radius:8px;
        background:#fff;
        color:var(--text);
        font-size:14px;
        outline:none;
        transition:border-color .15s,box-shadow .15s;
    }
    .field-textarea{height:auto;min-height:100px;padding:12px 14px;resize:vertical}
    .field-input:focus,.field-select:focus,.field-textarea:focus{
        border-color:var(--ink);
        box-shadow:0 0 0 3px rgba(29,58,51,.10);
    }
    .is-invalid{border-color:#e4572e !important}
    .invalid-feedback{margin-top:5px;color:#e4572e;font-size:12px}
    .help-text{margin-top:5px;font-size:11.5px;color:var(--muted)}

    /* Profile image */
    .profile-area{
        display:flex;align-items:center;gap:22px;
        padding:18px;margin-bottom:18px;
        background:#f8f6f0;border-radius:10px;
        border:1px dashed #d8d1bf;
    }
    .profile-preview-wrap{position:relative;width:100px;height:100px;flex-shrink:0}
    .profile-preview,.profile-placeholder{
        width:100px;height:100px;border-radius:50%;
        object-fit:cover;
    }
    .profile-placeholder{
        display:flex;align-items:center;justify-content:center;
        background:color-mix(in srgb,#2b5d8a 12%,#fff);
        color:#2b5d8a;font-size:32px;
        border:3px solid #fff;
    }
    .profile-preview{border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,.1)}
    .preview-badge{
        position:absolute;right:-2px;bottom:0;
        width:28px;height:28px;border-radius:50%;
        background:var(--ink);color:#fff;
        display:flex;align-items:center;justify-content:center;
        font-size:12px;border:2px solid #fff;
    }
    .profile-info h4{margin:0 0 4px;font-size:15px;color:var(--ink)}
    .profile-info p{margin:0;font-size:12.5px;color:var(--muted)}
    .selected-name{margin-top:6px;font-size:12px;color:#2b5d8a;font-weight:600}

    /* Actions */
    .form-actions{
        display:flex;justify-content:flex-end;gap:12px;
        padding:20px 28px;
        border-top:1px dashed #d8d1bf;
        background:#faf9f6;
    }

    .alert-box{
        margin-bottom:18px;padding:14px 18px;
        border-radius:8px;font-size:13px;
        background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;
    }

    @media (max-width:800px){
        .form-grid{grid-template-columns:1fr}
        .form-field.full{grid-column:auto}
        .form-hero{flex-direction:column;align-items:flex-start}
        .profile-area{flex-direction:column;align-items:flex-start}
        .form-actions{flex-direction:column}
        .btn-solid,.btn-line{width:100%;justify-content:center}
    }
</style>

<div class="teacher-form-page">

    {{-- Header --}}
    <section class="paper form-hero">
        <div>
            <h1>Edit Teacher</h1>
            <p>Update teacher information and profile details</p>
        </div>
        <a href="{{ route('admin.teachers.index') }}" class="btn-line">
            <i class="bi bi-arrow-left"></i> Back to Teachers
        </a>
    </section>

    @if ($errors->any())
        <div class="alert-box">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.teachers.update', $teacher->id) }}"
          method="POST"
          enctype="multipart/form-data"
          id="teacherEditForm"
          class="paper form-card">
        @csrf
        @method('PUT')

        {{-- Personal Information --}}
        <div class="form-section">
            <div class="section-head">
                <div class="section-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <h3>Personal Information</h3>
                    <p>Basic information about the teacher</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label class="field-label">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name"
                           class="field-input @error('first_name') is-invalid @enderror"
                           value="{{ old('first_name', $teacher->first_name) }}" required>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name"
                           class="field-input @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name', $teacher->last_name) }}" required>
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           class="field-input @error('date_of_birth') is-invalid @enderror"
                           value="{{ old('date_of_birth', $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('Y-m-d') : '') }}">
                    @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Gender</label>
                    <select name="gender" class="field-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $teacher->gender)=='Male'?'selected':'' }}>Male</option>
                        <option value="Female" {{ old('gender', $teacher->gender)=='Female'?'selected':'' }}>Female</option>
                        <option value="Other" {{ old('gender', $teacher->gender)=='Other'?'selected':'' }}>Other</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="form-section">
            <div class="section-head">
                <div class="section-icon"><i class="bi bi-person-lines-fill"></i></div>
                <div>
                    <h3>Contact Information</h3>
                    <p>Teacher contact details</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label class="field-label">Email <span class="required">*</span></label>
                    <input type="email" name="email"
                           class="field-input @error('email') is-invalid @enderror"
                           value="{{ old('email', $teacher->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Phone</label>
                    <input type="text" name="phone" id="teacherPhone"
                           class="field-input @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $teacher->phone) }}" maxlength="10">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field full">
                    <label class="field-label">Address</label>
                    <textarea name="address" id="teacherAddress" rows="3" maxlength="1000"
                              class="field-textarea @error('address') is-invalid @enderror">{{ old('address', $teacher->address) }}</textarea>
                    <div class="help-text">
                        <span id="addressCounter">0 / 1000</span>
                    </div>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Professional Information --}}
        <div class="form-section">
            <div class="section-head">
                <div class="section-icon"><i class="bi bi-briefcase-fill"></i></div>
                <div>
                    <h3>Professional Information</h3>
                    <p>Teacher qualification and work details</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label class="field-label">Teacher ID <span class="required">*</span></label>
                    <input type="text" name="teacher_id"
                           class="field-input @error('teacher_id') is-invalid @enderror"
                           value="{{ old('teacher_id', $teacher->teacher_id) }}" required>
                    @error('teacher_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Qualification</label>
                    <input type="text" name="qualification"
                           class="field-input @error('qualification') is-invalid @enderror"
                           value="{{ old('qualification', $teacher->qualification) }}"
                           placeholder="e.g. M.Sc, B.Ed">
                    @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Subject</label>
                    <input type="text" name="subject"
                           class="field-input @error('subject') is-invalid @enderror"
                           value="{{ old('subject', $teacher->subject) }}"
                           placeholder="Enter subject taught">
                    <div class="help-text">Enter the main subject taught by the teacher.</div>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Joining Date</label>
                    <input type="date" name="joining_date"
                           class="field-input @error('joining_date') is-invalid @enderror"
                           value="{{ old('joining_date', $teacher->joining_date ? \Carbon\Carbon::parse($teacher->joining_date)->format('Y-m-d') : '') }}">
                    @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Status <span class="required">*</span></label>
                    <select name="status" class="field-select @error('status') is-invalid @enderror" required>
                        <option value="Active" {{ old('status', $teacher->status)=='Active'?'selected':'' }}>Active</option>
                        <option value="Inactive" {{ old('status', $teacher->status)=='Inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Profile Image --}}
        <div class="form-section">
            <div class="section-head">
                <div class="section-icon"><i class="bi bi-image-fill"></i></div>
                <div>
                    <h3>Profile Image</h3>
                    <p>Update the teacher profile photo</p>
                </div>
            </div>

            <div class="profile-area">
                <div class="profile-preview-wrap">
                    @if($teacher->profile_image)
                        <img src="{{ $teacher->profile_image }}"
                             alt="Teacher Profile"
                             id="profilePreview"
                             class="profile-preview">
                    @else
                        <div class="profile-placeholder" id="profilePlaceholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                    <span class="preview-badge"><i class="bi bi-camera-fill"></i></span>
                </div>
                <div class="profile-info">
                    <h4>Current Profile Photo</h4>
                    <p>Choose a new image below to replace the current photo.</p>
                    <div class="selected-name" id="selectedImageName">
                        @if($teacher->profile_image)
                            Current image is stored
                        @else
                            No profile image uploaded
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-field">
                <label class="field-label">Change Profile Image</label>
                <input type="file" name="profile_image" id="profileImageInput"
                       class="field-input @error('profile_image') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                <div class="help-text">JPG, PNG or WEBP · Maximum size: 2 MB</div>
                @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('admin.teachers.index') }}" class="btn-line">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
            <button type="submit" class="btn-solid" id="updateTeacherBtn">
                <i class="bi bi-check-lg"></i>
                <span id="updateTeacherText">Update Teacher</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Address counter
    const address = document.getElementById('teacherAddress');
    const counter = document.getElementById('addressCounter');
    if (address && counter) {
        const update = () => counter.textContent = address.value.length + ' / 1000';
        address.addEventListener('input', update);
        update();
    }

    // Phone digits only
    const phone = document.getElementById('teacherPhone');
    if (phone) {
        phone.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }

    // Profile preview
    const input = document.getElementById('profileImageInput');
    const nameEl = document.getElementById('selectedImageName');

    if (input) {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('Image must be less than 2 MB.');
                this.value = '';
                return;
            }

            if (nameEl) nameEl.textContent = 'Selected: ' + file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                const wrapper = document.querySelector('.profile-preview-wrap');
                if (!wrapper) return;

                // Remove old image / placeholder
                const oldImg = wrapper.querySelector('#profilePreview');
                const oldPh = wrapper.querySelector('#profilePlaceholder');
                if (oldImg) oldImg.remove();
                if (oldPh) oldPh.remove();

                // Add new preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.id = 'profilePreview';
                img.className = 'profile-preview';
                img.alt = 'New profile preview';
                wrapper.insertBefore(img, wrapper.querySelector('.preview-badge'));
            };
            reader.readAsDataURL(file);
        });
    }

    // Loading state
    const form = document.getElementById('teacherEditForm');
    const btn = document.getElementById('updateTeacherBtn');
    const text = document.getElementById('updateTeacherText');

    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            if (text) text.textContent = 'Updating...';
        });
    }
});
</script>

@endsection