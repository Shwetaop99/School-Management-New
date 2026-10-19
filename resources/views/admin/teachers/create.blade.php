@extends('layouts.app')

@section('title', 'Add Teacher')
@section('page-title', 'Add Teacher')

@section('content')

<style>
/* =========================================================
   MAIN CONTAINER
========================================================= */

.teacher-container {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px;
    background: #f4f7fb;
    min-height: calc(100vh - 80px);
    box-sizing: border-box;
}

/* =========================================================
   PAGE HEADER
========================================================= */

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

/* =========================================================
   BACK BUTTON
========================================================= */

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

/* =========================================================
   SECTION HEADER
========================================================= */

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

.is-invalid,
.validation-error {
    border-color: #dc3545 !important;
    background: #fff8f8 !important;
}

.validation-success {
    border-color: #22c55e !important;
    background: #f8fff9 !important;
}

.invalid-feedback {
    display: block;
    margin-top: 5px;
    color: #dc3545;
    font-size: 11px;
    font-weight: 600;
}

/* =========================================================
   VALIDATION WARNING
========================================================= */

.validation-warning {
    display: none;
    margin-top: 6px;
    padding: 7px 10px;
    background: #fff1f2;
    border-left: 3px solid #dc3545;
    border-radius: 6px;
    color: #dc3545;
    font-size: 11px;
    font-weight: 600;
}

.validation-warning.show {
    display: block;
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

.profile-preview,
.profile-placeholder {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 50%;
    box-sizing: border-box;
}

.profile-preview {
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
   ACTION BUTTONS
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

/* =========================================================
   SPINNER
========================================================= */

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
    .field-input[readonly]{
        background:#f8f6f0;
        color:#2b5d8a;
        font-weight:600;
        cursor:not-allowed;
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
    .profile-preview{display:none;border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,.1)}
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
        <h2>Add Teacher</h2>
        <p>Add a new teacher to the school</p>
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


{{-- FORM --}}
<form
    action="{{ route('admin.teachers.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="teacherCreateForm"
    class="teacher-form"
>

    @csrf


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
</style>

<div class="teacher-form-page">

    {{-- Header --}}
    <section class="paper form-hero">
        <div>
            <h1>Add Teacher</h1>
            <p>Add a new teacher to the school</p>
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

    <form action="{{ route('admin.teachers.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="teacherCreateForm"
          class="paper form-card">
        @csrf

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
                           value="{{ old('first_name') }}" placeholder="Enter first name" required>
                    @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name"
                           class="field-input @error('last_name') is-invalid @enderror"
                           value="{{ old('last_name') }}" placeholder="Enter last name" required>
                    @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           class="field-input @error('date_of_birth') is-invalid @enderror"
                           value="{{ old('date_of_birth') }}">
                    @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Gender</label>
                    <select name="gender" class="field-select @error('gender') is-invalid @enderror">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                        <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                        <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                    </select>
                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
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
                    value="{{ old('first_name') }}"
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
                    value="{{ old('last_name') }}"
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
                    value="{{ old('date_of_birth') }}"
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
                    <option value="">Select Gender</option>

                    <option
                        value="Male"
                        {{ old('gender') == 'Male' ? 'selected' : '' }}
                    >
                        Male
                    </option>

                    <option
                        value="Female"
                        {{ old('gender') == 'Female' ? 'selected' : '' }}
                    >
                        Female
                    </option>

                    <option
                        value="Other"
                        {{ old('gender') == 'Other' ? 'selected' : '' }}
                    >
                        Other
                    </option>
                </select>

                @error('gender')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

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
                           value="{{ old('email') }}" placeholder="example@gmail.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Phone</label>
                    <input type="text" name="phone" id="teacherPhone"
                           class="field-input @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" placeholder="Enter phone number" maxlength="10">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field full">
                    <label class="field-label">Address</label>
                    <textarea name="address" id="teacherAddress" rows="3" maxlength="1000"
                              class="field-textarea @error('address') is-invalid @enderror"
                              placeholder="Enter complete address">{{ old('address') }}</textarea>
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
                    <label class="field-label">Teacher ID</label>
                    <input type="text" class="field-input" value="Automatically Generated" readonly>
                    <div class="help-text">Teacher ID will be generated automatically.</div>
                </div>

                <div class="form-field">
                    <label class="field-label">Status <span class="required">*</span></label>
                    <select name="status" class="field-select @error('status') is-invalid @enderror" required>
                        <option value="Active" {{ old('status','Active')=='Active'?'selected':'' }}>Active</option>
                        <option value="Inactive" {{ old('status')=='Inactive'?'selected':'' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Qualification</label>
                    <input type="text" name="qualification"
                           class="field-input @error('qualification') is-invalid @enderror"
                           value="{{ old('qualification') }}" placeholder="e.g. M.Sc, B.Ed">
                    @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Subject</label>
                    <input type="text" name="subject"
                           class="field-input @error('subject') is-invalid @enderror"
                           value="{{ old('subject') }}" placeholder="Enter subject taught">
                    <div class="help-text">Enter the main subject taught by the teacher.</div>
                    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-field">
                    <label class="field-label">Joining Date</label>
                    <input type="date" name="joining_date"
                           class="field-input @error('joining_date') is-invalid @enderror"
                           value="{{ old('joining_date') }}">
                    @error('joining_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- Profile Image --}}
        <div class="form-section">
            <div class="section-head">
                <div class="section-icon"><i class="bi bi-image-fill"></i></div>
                <div>
                    <h3>Profile Image</h3>
                    <p>Upload the teacher profile photo</p>
                </div>
            </div>

            <div class="profile-area">
                <div class="profile-preview-wrap">
                    <div class="profile-placeholder" id="profilePlaceholder">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <img src="" alt="Preview" id="profilePreview" class="profile-preview">
                    <span class="preview-badge"><i class="bi bi-camera-fill"></i></span>
                </div>
                <div class="profile-info">
                    <h4>Teacher Profile Photo</h4>
                    <p>Upload a clear profile image of the teacher.</p>
                    <div class="selected-name" id="selectedImageName"></div>
                </div>
            </div>

            <div class="form-field">
                <label class="field-label">Profile Image</label>
                <input type="file" name="profile_image" id="profileImageInput"
                       class="field-input @error('profile_image') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                <div class="help-text">JPG, PNG or WEBP · Maximum size: 2 MB</div>
                @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    id="teacherEmail"
                    class="teacher-input @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="example@gmail.com"
                    required
                >

                <div
                    id="emailWarning"
                    class="validation-warning"
                ></div>

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
                    id="teacherPhone"
                    class="teacher-input @error('phone') is-invalid @enderror"
                    value="{{ old('phone') }}"
                    placeholder="Enter 10 digit phone number"
                    maxlength="10"
                    inputmode="numeric"
                >

                <div
                    id="phoneWarning"
                    class="validation-warning"
                ></div>

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
                >{{ old('address') }}</textarea>

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
                </label>

                <input
                    type="text"
                    class="teacher-input"
                    value="Automatically Generated"
                    readonly
                    style="background:#eef5ff; color:#1769d1; font-weight:700; cursor:not-allowed;"
                >

                <div class="teacher-help">
                    <span>
                        Teacher ID will be generated automatically.
                    </span>
                </div>

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
                        {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        {{ old('status') == 'Inactive' ? 'selected' : '' }}
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


            {{-- QUALIFICATION --}}
            <div class="form-field">

                <label class="teacher-label">
                    Qualification
                </label>

                <input
                    type="text"
                    name="qualification"
                    class="teacher-input @error('qualification') is-invalid @enderror"
                    value="{{ old('qualification') }}"
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
                        value="{{ old('subject') }}"
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
                    value="{{ old('joining_date') }}"
                >

                @error('joining_date')
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
                <p>Upload the teacher profile photo</p>
            </div>

        </div>


        <div class="profile-area">

            <div class="profile-preview-wrapper">

                <div
                    class="profile-placeholder"
                    id="profilePlaceholder"
                >
                    <i class="bi bi-person-fill"></i>
                </div>

                <img
                    src=""
                    alt="Profile Preview"
                    id="profilePreview"
                    class="profile-preview"
                    style="display:none;"
                >

                <span class="preview-badge">
                    <i class="bi bi-camera-fill"></i>
                </span>

            </div>


            <div class="profile-info">

                <h4>
                    Teacher Profile Photo
                </h4>

                <p>
                    Upload a clear profile image of the teacher.
                </p>

                <div
                    class="selected-image-name"
                    id="selectedImageName"
                ></div>

            </div>

        </div>


        {{-- IMAGE INPUT --}}
        <div class="form-field">

            <label class="teacher-label">
                Profile Image
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
            id="saveTeacherBtn"
        >
            <i class="bi bi-check-lg"></i>

            <span id="saveTeacherText">
                Save Teacher
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
        {{-- Actions --}}
        <div class="form-actions">
            <a href="{{ route('admin.teachers.index') }}" class="btn-line">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
            <button type="submit" class="btn-solid" id="saveTeacherBtn">
                <i class="bi bi-check-lg"></i>
                <span id="saveTeacherText">Save Teacher</span>
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

if (teacherAddress) {

    teacherAddress.addEventListener(
        'input',
        updateAddressCounter
    );

    updateAddressCounter();
}


/* =========================================================
   EMAIL & PHONE ELEMENTS
========================================================= */

const teacherEmail =
    document.getElementById('teacherEmail');

const teacherPhone =
    document.getElementById('teacherPhone');

const emailWarning =
    document.getElementById('emailWarning');

const phoneWarning =
    document.getElementById('phoneWarning');


/* =========================================================
   EMAIL VALIDATION
========================================================= */

function validateTeacherEmail(showWarning = true) {

    if (!teacherEmail) {
        return true;
    }

    const email =
        teacherEmail.value.trim();

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


    if (email === '') {

        teacherEmail.classList.remove(
            'validation-success'
        );

        teacherEmail.classList.add(
            'validation-error'
        );

        if (emailWarning) {

            emailWarning.textContent =
                'Email address is required.';

            emailWarning.classList.add('show');
        }

        return false;
    }


    if (!emailPattern.test(email)) {

        teacherEmail.classList.remove(
            'validation-success'
        );

        teacherEmail.classList.add(
            'validation-error'
        );

        if (emailWarning) {

            emailWarning.textContent =
                'Please enter a valid email address, e.g. example@gmail.com.';

            emailWarning.classList.add('show');
        }

        return false;
    }


    teacherEmail.classList.remove(
        'validation-error'
    );

    teacherEmail.classList.add(
        'validation-success'
    );

    if (emailWarning) {
        emailWarning.classList.remove('show');
    }

    return true;
}


/* =========================================================
   PHONE INPUT
========================================================= */

if (teacherPhone) {

    teacherPhone.addEventListener(
        'input',
        function () {

            this.value =
                this.value
                    .replace(/\D/g, '')
                    .slice(0, 10);

            if (this.value.length === 10) {

                validateTeacherPhone(false);

            } else {

                this.classList.remove(
                    'validation-success'
                );
            }
        }
    );
}


/* =========================================================
   PHONE VALIDATION
========================================================= */

function validateTeacherPhone(showWarning = true) {

    if (!teacherPhone) {
        return true;
    }

    const phone =
        teacherPhone.value.trim();


    /* Phone is optional */
    if (phone === '') {

        teacherPhone.classList.remove(
            'validation-error',
            'validation-success'
        );

        if (phoneWarning) {
            phoneWarning.classList.remove('show');
        }

        return true;
    }


    if (phone.length !== 10) {

        teacherPhone.classList.remove(
            'validation-success'
        );

        teacherPhone.classList.add(
            'validation-error'
        );

        if (phoneWarning) {

            phoneWarning.textContent =
                'Phone number must contain exactly 10 digits.';

            phoneWarning.classList.add('show');
        }

        return false;
    }


    teacherPhone.classList.remove(
        'validation-error'
    );

    teacherPhone.classList.add(
        'validation-success'
    );

    if (phoneWarning) {
        phoneWarning.classList.remove('show');
    }

    return true;
}


/* =========================================================
   EMAIL LIVE VALIDATION
========================================================= */

if (teacherEmail) {

    teacherEmail.addEventListener(
        'input',
        function () {

            if (this.value.trim() !== '') {

                validateTeacherEmail(false);
            }
        }
    );

    teacherEmail.addEventListener(
        'blur',
        function () {

            validateTeacherEmail(true);
        }
    );
}


/* =========================================================
   PHONE BLUR VALIDATION
========================================================= */

if (teacherPhone) {

    teacherPhone.addEventListener(
        'blur',
        function () {

            validateTeacherPhone(true);
        }
    );
}


/* =========================================================
   PROFILE IMAGE PREVIEW
========================================================= */

const profileImageInput =
    document.getElementById('profileImageInput');

const profilePreview =
    document.getElementById('profilePreview');

const profilePlaceholder =
    document.getElementById('profilePlaceholder');

const selectedImageName =
    document.getElementById('selectedImageName');


if (profileImageInput) {

    profileImageInput.addEventListener(
        'change',
        function () {

            const file =
                this.files[0];

            if (!file) {
                return;
            }


            /* Maximum 2 MB */
            const maxSize =
                2 * 1024 * 1024;


            if (file.size > maxSize) {

                alert(
                    'The selected image is larger than 2 MB. Please choose a smaller image.'
                );

    // Profile preview
    const input = document.getElementById('profileImageInput');
    const preview = document.getElementById('profilePreview');
    const placeholder = document.getElementById('profilePlaceholder');
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

            /* File name */
            if (selectedImageName) {

                selectedImageName.textContent =
                    'Selected: ' + file.name;
            }


            /* Preview */
            const reader =
                new FileReader();


            reader.onload =
                function (event) {

                    if (profilePreview) {

                        profilePreview.src =
                            event.target.result;

                        profilePreview.style.display =
                            'block';
                    }


                    if (profilePlaceholder) {

                        profilePlaceholder.style.display =
                            'none';
                    }
                };


            reader.readAsDataURL(file);
        }
    );
}


/* =========================================================
   FORM SUBMIT VALIDATION + LOADING
========================================================= */

const teacherCreateForm =
    document.getElementById('teacherCreateForm');

const saveTeacherBtn =
    document.getElementById('saveTeacherBtn');

const saveTeacherText =
    document.getElementById('saveTeacherText');


if (teacherCreateForm) {

    teacherCreateForm.addEventListener(
        'submit',
        function (event) {

            const emailValid =
                validateTeacherEmail(true);

            const phoneValid =
                validateTeacherPhone(true);


            /* Stop submission if invalid */

            if (!emailValid || !phoneValid) {

                event.preventDefault();

                alert(
                    'Please correct the Email and Phone validation errors before saving the teacher.'
                );

                return;
            }


            /* Valid → show loading */

            if (
                saveTeacherBtn &&
                saveTeacherText
            ) {

                saveTeacherBtn.classList.add(
                    'loading'
                );

                saveTeacherBtn.disabled = true;

                saveTeacherText.textContent =
                    'Saving...';


                const icon =
                    saveTeacherBtn.querySelector('i');

                if (icon) {

                    icon.className =
                        'spinner';
                }
            }
        }
    );
}
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    // Loading state
    const form = document.getElementById('teacherCreateForm');
    const btn = document.getElementById('saveTeacherBtn');
    const text = document.getElementById('saveTeacherText');

    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.disabled = true;
            if (text) text.textContent = 'Saving...';
        });
    }
});
</script>

@endsection