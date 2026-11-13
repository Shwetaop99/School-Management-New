@extends('layouts.app')

@section('title', 'Edit Student | Admin')

@section('content')

<style>
    /* =========================================================
       SCHOOL DIARY DESIGN SYSTEM — EDIT STUDENT
    ========================================================= */

    .student-edit-page {
        width: 100%;
        min-height: calc(100vh - 60px);
        padding-bottom: 40px;
        background: #f5f3e9;
        color: #263b35;
        font-family: "Inter", sans-serif;
        font-size: 12px;
    }

    .student-edit-page *,
    .student-edit-page *::before,
    .student-edit-page *::after {
        box-sizing: border-box;
    }

    /* ---------- PAGE HEADER ---------- */
    .student-edit-header {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
        margin-bottom: 20px;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-left: 4px solid #34775a;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
    }

    .edit-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .edit-header-left {
        min-width: 0;
    }

    .edit-breadcrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
        color: #7a857d;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .55px;
    }

    .edit-breadcrumb a {
        color: #34775a;
        text-decoration: none;
        transition: color .2s ease;
    }

    .edit-breadcrumb a:hover {
        color: #245a43;
        text-decoration: underline;
    }

    .edit-breadcrumb i {
        color: #a87543;
        font-size: 9px;
    }

    .edit-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .edit-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 12px;
        font-size: 22px;
        box-shadow: 0 2px 6px rgba(52, 119, 90, .08);
    }

    .edit-title {
        margin: 0;
        color: #263b35;
        font-size: 22px;
        font-weight: 750;
        line-height: 1.25;
        letter-spacing: -.4px;
    }

    .edit-subtitle {
        margin: 5px 0 0;
        color: #778078;
        font-size: 11px;
        line-height: 1.5;
    }

    .student-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        color: #245a43;
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    /* ---------- MAIN CARD ---------- */
    .student-form-card {
        background: #fffefa;
        border: 1px solid #e5e1d3;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(38, 59, 53, .05);
        overflow: hidden;
    }

    /* ---------- TABS ---------- */
    .student-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 14px 18px 0;
        background: #faf8f0;
        border-bottom: 1px solid #e5e1d3;
        overflow-x: auto;
    }

    .student-tabs .nav-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 11px 16px;
        color: #5c6b62;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 8px 8px 0 0;
        font-size: 11px;
        font-weight: 650;
        white-space: nowrap;
        cursor: pointer;
        transition: color .18s ease, background .18s ease, border-color .18s ease;
    }

    .student-tabs .nav-link:hover {
        color: #34775a;
        background: rgba(52, 119, 90, .06);
    }

    .student-tabs .nav-link.active {
        color: #245a43;
        background: #fffefa;
        border-bottom-color: #34775a;
    }

    .student-tabs .nav-link i {
        font-size: 13px;
    }

    /* ---------- FORM BODY ---------- */
    .student-form-body {
        padding: 24px 22px;
    }

    .tab-content-section {
        display: none;
    }

    .tab-content-section.active {
        display: block;
    }

    /* ---------- SECTION HEADING ---------- */
    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e9e5d9;
    }

    .section-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        color: #34775a;
        background: linear-gradient(145deg, #eaf3e9, #dce9d9);
        border: 1px solid #d0e2d0;
        border-radius: 10px;
        font-size: 17px;
        box-shadow: 0 2px 5px rgba(52, 119, 90, .07);
    }

    .section-heading h5 {
        margin: 0;
        color: #263b35;
        font-size: 14px;
        font-weight: 750;
    }

    .section-heading p {
        margin: 3px 0 0;
        color: #7a857d;
        font-size: 11px;
        line-height: 1.4;
    }

    /* ---------- FORM FIELDS ---------- */
    .scheme-form-group {
        margin-bottom: 4px;
    }

    .scheme-form-group label,
    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #405149;
        font-size: 11px;
        font-weight: 650;
    }

    .required {
        color: #bd5148;
    }

    .scheme-form-control,
    .scheme-form-select,
    .scheme-form-textarea,
    .form-control,
    .form-select {
        display: block;
        width: 100%;
        color: #263b35;
        background: #fffefa;
        border: 1px solid #e0ddcf;
        border-radius: 8px;
        outline: none;
        font-family: inherit;
        font-size: 12px;
        font-weight: 500;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }

    .scheme-form-control,
    .scheme-form-select,
    .form-control:not(textarea),
    .form-select {
        height: 40px;
        padding: 0 12px;
    }

    .scheme-form-textarea,
    textarea.form-control {
        min-height: 100px;
        padding: 10px 12px;
        resize: vertical;
        line-height: 1.5;
    }

    .scheme-form-control::placeholder,
    .scheme-form-textarea::placeholder,
    .form-control::placeholder {
        color: #a0a397;
        font-weight: 400;
    }

    .scheme-form-control:hover,
    .scheme-form-select:hover,
    .scheme-form-textarea:hover,
    .form-control:hover,
    .form-select:hover {
        border-color: #c5d1c2;
    }

    .scheme-form-control:focus,
    .scheme-form-select:focus,
    .scheme-form-textarea:focus,
    .form-control:focus,
    .form-select:focus {
        background: #fffefa;
        border-color: #6e9a7c;
        box-shadow: 0 0 0 3px rgba(52, 119, 90, .10);
    }

    .scheme-form-control[readonly],
    .form-control[readonly] {
        cursor: default;
        color: #69766d;
        background: #f5f3e9;
    }

    .scheme-form-select,
    .form-select {
        cursor: pointer;
    }

    /* ---------- VALIDATION ---------- */
    .field-error {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
        line-height: 1.4;
    }

    .scheme-alert {
        margin-bottom: 18px;
        padding: 14px 16px;
        border-radius: 9px;
        font-size: 11px;
        line-height: 1.5;
    }

    .scheme-alert-danger {
        background: #fff1ed;
        border: 1px solid #e8b8b0;
        border-left: 4px solid #bd5148;
        color: #8a3a34;
    }

    .scheme-alert-success {
        background: #eaf3e9;
        border: 1px solid #c5dcc8;
        border-left: 4px solid #34775a;
        color: #245a43;
    }

    .scheme-alert strong {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .scheme-alert ul {
        margin: 0;
        padding-left: 18px;
    }

    /* ---------- PROFILE PREVIEW ---------- */
    .profile-preview {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #d8e8d8;
        box-shadow: 0 3px 10px rgba(38, 59, 53, .1);
        background: #f5f3e9;
    }

    /* ---------- LOADING ---------- */
    .loading-text {
        margin-top: 5px;
        color: #7a857d;
        font-size: 10px;
    }

    .loading-text.d-none,
    .error-text.d-none {
        display: none !important;
    }

    .error-text {
        margin-top: 5px;
        color: #bd5148;
        font-size: 10px;
    }

    .loading-icon {
        display: inline-block;
        animation: transportSpin .8s linear infinite;
    }

    @keyframes transportSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* ---------- FOOTER ---------- */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 22px;
        background: #faf8f0;
        border-top: 1px solid #e5e1d3;
    }

    .btn-scheme-cancel,
    .btn-scheme-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 0 18px;
        border-radius: 8px;
        cursor: pointer;
        font-family: inherit;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all .18s ease;
    }

    .btn-scheme-cancel {
        color: #526057;
        background: #fffefa;
        border-color: #dcd8ca;
    }

    .btn-scheme-cancel:hover {
        color: #263b35;
        background: #f5f3e9;
        border-color: #c5c0b0;
    }

    .btn-scheme-save {
        color: #fffefa;
        background: linear-gradient(135deg, #34775a, #2d6a50);
        border-color: #34775a;
        box-shadow: 0 3px 10px rgba(52, 119, 90, .18);
    }

    .btn-scheme-save:hover {
        color: #fffefa;
        background: linear-gradient(135deg, #245a43, #1f4f3a);
        border-color: #245a43;
        box-shadow: 0 5px 14px rgba(36, 90, 67, .22);
        transform: translateY(-1px);
    }

    /* ---------- RESPONSIVE ---------- */
    @media (max-width: 767.98px) {
        .student-edit-header {
            padding: 18px 16px;
        }

        .edit-title {
            font-size: 19px;
        }

        .edit-title-icon {
            flex-basis: 42px;
            width: 42px;
            height: 42px;
            font-size: 19px;
        }

        .edit-header-content {
            flex-direction: column;
        }

        .student-form-body {
            padding: 18px 15px;
        }

        .student-tabs {
            padding: 10px 12px 0;
        }

        .student-tabs .nav-link {
            padding: 10px 12px;
            font-size: 10px;
        }

        .form-actions {
            padding: 14px 15px;
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .btn-scheme-cancel,
        .btn-scheme-save {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .edit-title {
            font-size: 17px;
        }

        .edit-subtitle {
            font-size: 10px;
        }

        .edit-breadcrumb {
            font-size: 9px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .student-edit-page *,
        .student-edit-page *::before,
        .student-edit-page *::after {
            scroll-behavior: auto !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: .01ms !important;
        }
    }
</style>

<div class="student-edit-page">

    {{-- ===================== PAGE HEADER ===================== --}}
    <div class="student-edit-header">
        <div class="edit-header-content">
            <div class="edit-header-left">
                <div class="edit-breadcrumb">
                    <a href="{{ route('admin.students.index') }}">Students</a>
                    <i class="bi bi-chevron-right"></i>
                    <a href="{{ route('admin.students.show', $student) }}">{{ $student->first_name }} {{ $student->last_name }}</a>
                    <i class="bi bi-chevron-right"></i>
                    <span>Edit</span>
                </div>

                <div class="edit-title-row">
                    <div class="edit-title-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h1 class="edit-title">Edit Student</h1>
                        <p class="edit-subtitle">Update student information</p>
                    </div>
                </div>
            </div>

            <span class="student-id-badge">
                <i class="bi bi-person-badge"></i>
                {{ $student->student_id }}
            </span>
        </div>
    </div>

    {{-- ===================== FORM CARD ===================== --}}
    <div class="student-form-card">

        {{-- TABS --}}
        <ul class="nav student-tabs" id="studentTabs">
            <li class="nav-item">
                <button type="button" class="nav-link active" data-tab="basic">
                    <i class="bi bi-person"></i>
                    Basic Information
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-tab="academic">
                    <i class="bi bi-mortarboard"></i>
                    Academic Information
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-tab="parents">
                    <i class="bi bi-people"></i>
                    Parents Data
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-tab="previous">
                    <i class="bi bi-building"></i>
                    Previous School
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" data-tab="address">
                    <i class="bi bi-geo-alt"></i>
                    Address
                </button>
            </li>
        </ul>

        <div class="student-form-body">

            {{-- VALIDATION ERRORS --}}
            @if ($errors->any())
                <div class="scheme-alert scheme-alert-danger">
                    <strong>
                        <i class="bi bi-exclamation-triangle"></i>
                        Please fix the following errors:
                    </strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="scheme-alert scheme-alert-success">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.students.update', $student->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="studentForm">

                @csrf
                @method('PUT')

                {{-- ===================== TAB 1 : BASIC ===================== --}}
                <div class="tab-content-section active" id="tab-basic">

                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h5>Basic Information</h5>
                            <p>Enter student's personal information.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" id="first_name"
                                   class="form-control scheme-form-control"
                                   value="{{ old('first_name', $student->first_name) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name"
                                   class="form-control scheme-form-control"
                                   value="{{ old('middle_name', $student->middle_name) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" id="last_name"
                                   class="form-control scheme-form-control"
                                   value="{{ old('last_name', $student->last_name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Marathi Name</label>
                            <input type="text" name="marathi_name" id="marathi_name"
                                   class="form-control scheme-form-control"
                                   value="{{ old('marathi_name', $student->marathi_name ?? '') }}"
                                   placeholder="Enter student's Marathi name">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Gender <span class="required">*</span></label>
                            <select name="gender" class="form-select scheme-form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Date of Birth <span class="required">*</span></label>
                            <input type="date" name="date_of_birth"
                                   class="form-control scheme-form-control"
                                   value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Birth Place</label>
                            <input type="text" name="birth_place"
                                   class="form-control scheme-form-control"
                                   value="{{ old('birth_place', $student->birth_place ?? '') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Aadhaar Number</label>
                            <input type="text" name="aadhar_card_no" id="aadhar_card_no"
                                   class="form-control scheme-form-control"
                                   maxlength="12" inputmode="numeric"
                                   value="{{ old('aadhar_card_no', $student->aadhar_card_no) }}">
                            <div id="aadharError" class="field-error"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="phone"
                                   class="form-control scheme-form-control"
                                   maxlength="10" inputmode="numeric"
                                   value="{{ old('phone', $student->phone) }}">
                            <div id="phoneError" class="field-error"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control scheme-form-control"
                                   value="{{ old('email', $student->email) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" id="profile_image"
                                   class="form-control scheme-form-control" accept="image/*">
                            <div class="mt-2">
                                @if($student->profile_image)
                                    <img src="{{ $student->profile_image }}" id="imagePreview"
                                         class="profile-preview" alt="Student">
                                @else
                                    <img src="" id="imagePreview"
                                         class="profile-preview d-none" alt="Preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== TAB 2 : ACADEMIC ===================== --}}
                <div class="tab-content-section" id="tab-academic">

                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            <h5>Academic Information</h5>
                            <p>Manage academic and admission information.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year"
                                   class="form-control scheme-form-control"
                                   value="{{ old('academic_year', $student->academic_year) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Class</label>
                            <select name="class" id="class" class="form-select scheme-form-select">
                                @foreach(['Nursery','LKG','UKG','1','2','3','4','5','6','7','8','9','10','11','12'] as $class)
                                    <option value="{{ $class }}" {{ old('class', $student->class) == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Section</label>
                            <select name="section" id="section" class="form-select scheme-form-select">
                                @foreach(['A','B','C','D','E','F'] as $section)
                                    <option value="{{ $section }}" {{ old('section', $student->section) == $section ? 'selected' : '' }}>
                                        {{ $section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Roll Number</label>
                            <input type="text" name="roll_number" id="roll_number"
                                   class="form-control scheme-form-control"
                                   value="{{ old('roll_number', $student->roll_number) }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Admission Class</label>
                            <select name="admission_class" class="form-select scheme-form-select">
                                @foreach(['Nursery','LKG','UKG','1','2','3','4','5','6','7','8','9','10','11','12'] as $class)
                                    <option value="{{ $class }}" {{ old('admission_class', $student->admission_class) == $class ? 'selected' : '' }}>
                                        {{ $class }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Admission Date</label>
                            <input type="date" name="admission_date"
                                   class="form-control scheme-form-control"
                                   value="{{ old('admission_date', optional($student->admission_date)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Register Number</label>
                            <input type="text" name="register_no"
                                   class="form-control scheme-form-control"
                                   value="{{ old('register_no', $student->register_no) }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Book Number</label>
                            <input type="text" name="book_no"
                                   class="form-control scheme-form-control"
                                   value="{{ old('book_no', $student->book_no) }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">APAAR ID</label>
                            <input type="text" name="appar_id"
                                   class="form-control scheme-form-control"
                                   value="{{ old('appar_id', $student->appar_id) }}" readonly>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">PEN Number</label>
                            <input type="text" name="pen_no"
                                   class="form-control scheme-form-control"
                                   value="{{ old('pen_no', $student->pen_no) }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Medium</label>
                            <select name="medium" class="form-select scheme-form-select">
                                @foreach(['Marathi','English','Hindi'] as $medium)
                                    <option value="{{ $medium }}" {{ old('medium', $student->medium) == $medium ? 'selected' : '' }}>
                                        {{ $medium }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mother Tongue</label>
                            <input type="text" name="mother_tongue"
                                   class="form-control scheme-form-control"
                                   value="{{ old('mother_tongue', $student->mother_tongue) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nationality</label>
                            <input type="text" name="nationality"
                                   class="form-control scheme-form-control"
                                   value="{{ old('nationality', $student->nationality ?? 'Indian') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Religion</label>
                            <input type="text" name="religion"
                                   class="form-control scheme-form-control"
                                   value="{{ old('religion', $student->religion) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Caste</label>
                            <input type="text" name="caste"
                                   class="form-control scheme-form-control"
                                   value="{{ old('caste', $student->caste) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sub Caste</label>
                            <input type="text" name="sub_caste"
                                   class="form-control scheme-form-control"
                                   value="{{ old('sub_caste', $student->sub_caste) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select scheme-form-select">
                                <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ===================== TAB 3 : PARENTS ===================== --}}
                <div class="tab-content-section" id="tab-parents">

                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5>Parents Data</h5>
                            <p>Enter father, mother and guardian details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Father Name</label>
                            <input type="text" name="father_name" class="form-control scheme-form-control"
                                   value="{{ old('father_name', $student->father_name) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father Phone</label>
                            <input type="text" name="father_phone" class="form-control scheme-form-control parent-phone"
                                   maxlength="10" inputmode="numeric"
                                   value="{{ old('father_phone', $student->father_phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Father Occupation</label>
                            <input type="text" name="father_occupation" class="form-control scheme-form-control"
                                   value="{{ old('father_occupation', $student->father_occupation) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Mother Name</label>
                            <input type="text" name="mother_name" class="form-control scheme-form-control"
                                   value="{{ old('mother_name', $student->mother_name) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother Phone</label>
                            <input type="text" name="mother_phone" class="form-control scheme-form-control parent-phone"
                                   maxlength="10" inputmode="numeric"
                                   value="{{ old('mother_phone', $student->mother_phone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mother Occupation</label>
                            <input type="text" name="mother_occupation" class="form-control scheme-form-control"
                                   value="{{ old('mother_occupation', $student->mother_occupation) }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" name="guardian_name" class="form-control scheme-form-control"
                                   value="{{ old('guardian_name', $student->guardian_name) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Guardian Relation</label>
                            <input type="text" name="guardian_relation" class="form-control scheme-form-control"
                                   value="{{ old('guardian_relation', $student->guardian_relation) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Guardian Phone</label>
                            <input type="text" name="guardian_phone" class="form-control scheme-form-control parent-phone"
                                   maxlength="10" inputmode="numeric"
                                   value="{{ old('guardian_phone', $student->guardian_phone) }}">
                        </div>
                    </div>
                </div>

                {{-- ===================== TAB 4 : PREVIOUS SCHOOL ===================== --}}
                <div class="tab-content-section" id="tab-previous">

                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <div>
                            <h5>Previous School</h5>
                            <p>Enter previous school information.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Previous School Name</label>
                            <input type="text" name="previous_school_name" class="form-control scheme-form-control"
                                   value="{{ old('previous_school_name', $student->previous_school_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Previous School Class</label>
                            <input type="text" name="previous_school_class" class="form-control scheme-form-control"
                                   value="{{ old('previous_school_class', $student->previous_school_class) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Previous School Address</label>
                            <textarea name="previous_school_address" class="form-control scheme-form-textarea">{{ old('previous_school_address', $student->previous_school_address) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Medium</label>
                            <input type="text" name="previous_school_medium" class="form-control scheme-form-control"
                                   value="{{ old('previous_school_medium', $student->previous_school_medium) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Board</label>
                            <input type="text" name="previous_school_board" class="form-control scheme-form-control"
                                   value="{{ old('previous_school_board', $student->previous_school_board) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Result</label>
                            <input type="text" name="previous_school_result" class="form-control scheme-form-control"
                                   value="{{ old('previous_school_result', $student->previous_school_result) }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Remarks</label>
                            <textarea name="previous_school_remarks" class="form-control scheme-form-textarea">{{ old('previous_school_remarks', $student->previous_school_remarks) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ===================== TAB 5 : ADDRESS ===================== --}}
                <div class="tab-content-section" id="tab-address">

                    <div class="section-heading">
                        <div class="section-heading-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h5>Address Information</h5>
                            <p>Select state, district, taluka and location. District, taluka and location are loaded dynamically.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Country</label>
                            <select name="country" id="country" class="form-select scheme-form-select">
                                <option value="India" {{ old('country', $student->country ?? 'India') == 'India' ? 'selected' : '' }}>India</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">State</label>
                            <select name="state_id" id="state_id" class="form-select scheme-form-select">
                                <option value="1" {{ old('state_id', 1) == 1 ? 'selected' : '' }}>Maharashtra</option>
                            </select>
                            <input type="hidden" name="state" id="state"
                                   value="{{ old('state', $student->state ?? 'Maharashtra') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">District</label>
                            <select name="district_id" id="district_id" class="form-select scheme-form-select">
                                <option value="">Loading districts...</option>
                            </select>
                            <input type="hidden" name="district" id="district"
                                   value="{{ old('district', $student->district ?? '') }}">
                            <div id="districtLoading" class="loading-text d-none">Loading districts...</div>
                            <div id="districtError" class="error-text d-none"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Taluka / Tehsil</label>
                            <select name="taluka_id" id="taluka_id" class="form-select scheme-form-select">
                                <option value="">Select district first</option>
                            </select>
                            <input type="hidden" name="taluka" id="taluka"
                                   value="{{ old('taluka', $student->taluka ?? '') }}">
                            <div id="talukaLoading" class="loading-text d-none">Loading talukas...</div>
                            <div id="talukaError" class="error-text d-none"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City / Village</label>
                            <select name="city_village" id="city_village" class="form-select scheme-form-select">
                                <option value="">Select taluka first</option>
                            </select>
                            <div id="locationLoading" class="loading-text d-none">Loading locations...</div>
                            <div id="locationError" class="error-text d-none"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pincode</label>
                            <input type="text" name="pincode" id="pincode"
                                   class="form-control scheme-form-control"
                                   value="{{ old('pincode', $student->pincode ?? '') }}" readonly>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Full Address</label>
                            <textarea name="address" id="address"
                                      class="form-control scheme-form-textarea">{{ old('address', $student->address) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="form-actions mt-4">
                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn-scheme-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn-scheme-save">
                        <i class="bi bi-check-circle"></i>
                        Update Student
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       TAB SYSTEM
    ========================================================== */
    const tabButtons = document.querySelectorAll('#studentTabs .nav-link');
    const tabSections = document.querySelectorAll('.tab-content-section');

    tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const target = this.dataset.tab;

            tabButtons.forEach(function (btn) {
                btn.classList.remove('active');
            });

            tabSections.forEach(function (section) {
                section.classList.remove('active');
            });

            this.classList.add('active');

            const targetSection = document.getElementById('tab-' + target);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });

    /* =========================================================
       NUMERIC INPUT
    ========================================================== */
    const numericFields = document.querySelectorAll(
        '#aadhar_card_no, #phone, .parent-phone, #pincode'
    );

    numericFields.forEach(function (field) {
        field.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    });

    /* =========================================================
       AADHAAR VALIDATION
    ========================================================== */
    const aadhar = document.getElementById('aadhar_card_no');
    const aadharError = document.getElementById('aadharError');

    if (aadhar) {
        aadhar.addEventListener('input', function () {
            const value = this.value.trim();
            if (value !== '' && !/^\d{12}$/.test(value)) {
                if (aadharError) {
                    aadharError.textContent = 'Aadhaar number must contain exactly 12 digits.';
                }
            } else {
                if (aadharError) {
                    aadharError.textContent = '';
                }
            }
        });
    }

    /* =========================================================
       PHONE VALIDATION
    ========================================================== */
    const phone = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');

    if (phone) {
        phone.addEventListener('input', function () {
            const value = this.value.trim();
            if (value !== '' && !/^[6-9]\d{9}$/.test(value)) {
                if (phoneError) {
                    phoneError.textContent = 'Enter a valid 10 digit Indian mobile number.';
                }
            } else {
                if (phoneError) {
                    phoneError.textContent = '';
                }
            }
        });
    }

    /* =========================================================
       PROFILE IMAGE PREVIEW
    ========================================================== */
    const profileImage = document.getElementById('profile_image');
    const imagePreview = document.getElementById('imagePreview');

    if (profileImage) {
        profileImage.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                this.value = '';
                alert('Please select a valid image file.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                if (imagePreview) {
                    imagePreview.src = event.target.result;
                    imagePreview.classList.remove('d-none');
                }
            };
            reader.readAsDataURL(file);
        });
    }

    /* =========================================================
       LOCATION FIELDS
    ========================================================== */
    const stateField = document.getElementById('state_id');
    const districtField = document.getElementById('district_id');
    const talukaField = document.getElementById('taluka_id');
    const villageField = document.getElementById('city_village');
    const pincodeField = document.getElementById('pincode');

    const stateHidden = document.getElementById('state');
    const districtHidden = document.getElementById('district');
    const talukaHidden = document.getElementById('taluka');

    const districtLoading = document.getElementById('districtLoading');
    const districtError = document.getElementById('districtError');
    const talukaLoading = document.getElementById('talukaLoading');
    const talukaError = document.getElementById('talukaError');
    const locationLoading = document.getElementById('locationLoading');
    const locationError = document.getElementById('locationError');

    const LOCATION_URLS = {
        states: "{{ route('admin.locations.states') }}",
        districts: "{{ route('admin.locations.districts') }}",
        tehsils: "{{ route('admin.locations.tehsils') }}",
        locations: "{{ route('admin.locations.locations') }}"
    };

    const currentState = @json(old('state', $student->state ?? 'Maharashtra'));
    const currentDistrict = @json(old('district', $student->district ?? ''));
    const currentTaluka = @json(old('taluka', $student->taluka ?? ''));
    const currentVillage = @json(old('city_village', $student->city_village ?? ''));
    const currentPincode = @json(old('pincode', $student->pincode ?? ''));

    async function fetchJson(url, type = 'API') {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        });

        const text = await response.text();

        if (!response.ok) {
            let message = type + ' API request failed: HTTP ' + response.status;
            if (response.status === 404) message += '. Laravel route was not found.';
            else if (response.status === 401) message += '. Authentication failed.';
            else if (response.status === 403) message += '. Access denied.';
            else if (response.status >= 500) message += '. Laravel/API server error.';
            throw new Error(message);
        }

        if (!text.trim()) {
            throw new Error(type + ' API returned empty response.');
        }

        try {
            return JSON.parse(text);
        } catch (error) {
            throw new Error(type + ' API returned invalid JSON.');
        }
    }

    function extractArray(response, possibleKeys = []) {
        if (Array.isArray(response)) return response;
        if (response && Array.isArray(response.data)) return response.data;

        if (response && response.data && typeof response.data === 'object') {
            for (const key of possibleKeys) {
                if (Array.isArray(response.data[key])) return response.data[key];
            }
            if (Array.isArray(response.data.data)) return response.data.data;
        }

        if (response) {
            for (const key of possibleKeys) {
                if (Array.isArray(response[key])) return response[key];
            }
        }

        if (response && Array.isArray(response.results)) return response.results;
        if (response && Array.isArray(response.items)) return response.items;

        return [];
    }

    function getId(item, type) {
        if (!item) return '';
        let keys = [];
        if (type === 'state') keys = ['id', 'state_id', 'state_code', 'code'];
        else if (type === 'district') keys = ['id', 'district_id', 'district_code', 'code'];
        else if (type === 'taluka') keys = ['id', 'tehsil_id', 'taluka_id', 'tehsil_code', 'taluka_code', 'code'];
        else if (type === 'location') keys = ['id', 'location_id', 'village_id', 'location_code', 'village_code', 'code'];

        for (const key of keys) {
            if (item[key] !== undefined && item[key] !== null && item[key] !== '') return item[key];
        }
        return '';
    }

    function getName(item, type) {
        if (!item) return '';
        let keys = [];
        if (type === 'state') keys = ['name', 'state_name', 'state', 'title'];
        else if (type === 'district') keys = ['name', 'district_name', 'district', 'title'];
        else if (type === 'taluka') keys = ['name', 'tehsil_name', 'taluka_name', 'taluka', 'tehsil', 'title'];
        else if (type === 'location') keys = ['name', 'location_name', 'village_name', 'village', 'location', 'title'];

        for (const key of keys) {
            if (item[key] !== undefined && item[key] !== null && String(item[key]).trim() !== '') {
                return String(item[key]).trim();
            }
        }
        return '';
    }

    function normalize(value) {
        return String(value ?? '').trim().toLowerCase();
    }

    function clearSelect(select, placeholder) {
        if (!select) return;
        select.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.textContent = placeholder;
        select.appendChild(option);
    }

    function addOption(select, value, text, selected = false) {
        if (!select) return;
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        option.selected = selected;
        select.appendChild(option);
    }

    function saveStateName() {
        if (!stateField || !stateHidden) return;
        const option = stateField.options[stateField.selectedIndex];
        if (option && option.value && option.textContent.trim()) {
            stateHidden.value = option.textContent.trim();
        } else {
            stateHidden.value = currentState || 'Maharashtra';
        }
    }

    function saveDistrictName() {
        if (!districtField || !districtHidden) return;
        const option = districtField.options[districtField.selectedIndex];
        if (option && option.value && option.textContent.trim()) {
            districtHidden.value = option.textContent.trim();
        } else {
            districtHidden.value = currentDistrict || '';
        }
    }

    function saveTalukaName() {
        if (!talukaField || !talukaHidden) return;
        const option = talukaField.options[talukaField.selectedIndex];
        if (option && option.value && option.textContent.trim()) {
            talukaHidden.value = option.textContent.trim();
        } else {
            talukaHidden.value = currentTaluka || '';
        }
    }

    async function loadDistricts() {
        try {
            districtField.disabled = true;
            talukaField.disabled = true;
            villageField.disabled = true;

            clearSelect(districtField, 'Loading districts...');
            clearSelect(talukaField, 'Select district first');
            clearSelect(villageField, 'Select Taluka first');

            if (districtLoading) districtLoading.classList.remove('d-none');
            if (districtError) {
                districtError.classList.add('d-none');
                districtError.textContent = '';
            }

            let stateId = stateField ? stateField.value : '1';
            if (!stateId) stateId = '1';

            const districtUrl = new URL(LOCATION_URLS.districts, window.location.origin);
            districtUrl.searchParams.set('state_id', stateId);

            const districtResponse = await fetchJson(districtUrl.toString(), 'District');
            const districts = extractArray(districtResponse, ['districts', 'data', 'results', 'items']);

            clearSelect(districtField, 'Select District');

            if (!districts.length) throw new Error('No districts found.');

            let selectedDistrictItem = null;

            districts.forEach(function (district) {
                const id = getId(district, 'district');
                const name = getName(district, 'district');
                if (!id || !name) return;

                const selected = normalize(name) === normalize(currentDistrict);
                addOption(districtField, id, name, selected);
                if (selected) selectedDistrictItem = district;
            });

            if (currentDistrict && !selectedDistrictItem) {
                addOption(districtField, currentDistrict, currentDistrict, true);
            }

            districtField.disabled = false;
            saveDistrictName();

            if (selectedDistrictItem) {
                await loadTalukas(getId(selectedDistrictItem, 'district'), currentTaluka);
            } else if (currentDistrict) {
                await loadTalukas(currentDistrict, currentTaluka);
            }
        } catch (error) {
            clearSelect(districtField, 'Unable to load districts');
            districtField.disabled = true;
            if (districtError) {
                districtError.textContent = error.message || 'Unable to load districts.';
                districtError.classList.remove('d-none');
            }
        } finally {
            if (districtLoading) districtLoading.classList.add('d-none');
        }
    }

    async function loadTalukas(districtId, selectedTaluka = '') {
        if (!districtId) return;

        try {
            talukaField.disabled = true;
            villageField.disabled = true;

            clearSelect(talukaField, 'Loading talukas...');
            clearSelect(villageField, 'Select Taluka first');

            if (talukaLoading) talukaLoading.classList.remove('d-none');
            if (talukaError) {
                talukaError.classList.add('d-none');
                talukaError.textContent = '';
            }

            const tehsilUrl = new URL(LOCATION_URLS.tehsils, window.location.origin);
            tehsilUrl.searchParams.set('district_id', districtId);

            const response = await fetchJson(tehsilUrl.toString(), 'Taluka');
            const talukas = extractArray(response, ['tehsils', 'talukas', 'tehsil', 'taluka', 'results', 'items', 'data']);

            clearSelect(talukaField, 'Select Taluka');

            if (!talukas.length) throw new Error('No talukas found.');

            let selectedTalukaItem = null;

            talukas.forEach(function (taluka) {
                const id = getId(taluka, 'taluka');
                const name = getName(taluka, 'taluka');
                if (!id || !name) return;

                const selected = normalize(name) === normalize(selectedTaluka);
                addOption(talukaField, id, name, selected);
                if (selected) selectedTalukaItem = taluka;
            });

            if (selectedTaluka && !selectedTalukaItem) {
                addOption(talukaField, selectedTaluka, selectedTaluka, true);
            }

            talukaField.disabled = false;
            saveTalukaName();

            if (selectedTalukaItem) {
                await loadLocations(getId(selectedTalukaItem, 'taluka'), currentVillage);
            } else if (selectedTaluka) {
                await loadLocations(selectedTaluka, currentVillage);
            }
        } catch (error) {
            clearSelect(talukaField, 'Unable to load talukas');
            talukaField.disabled = true;
            if (talukaError) {
                talukaError.textContent = error.message || 'Unable to load talukas.';
                talukaError.classList.remove('d-none');
            }
        } finally {
            if (talukaLoading) talukaLoading.classList.add('d-none');
        }
    }

    async function loadLocations(talukaId, selectedVillage = '') {
        if (!talukaId) return;

        try {
            villageField.disabled = true;
            clearSelect(villageField, 'Loading villages...');

            if (locationLoading) locationLoading.classList.remove('d-none');
            if (locationError) {
                locationError.classList.add('d-none');
                locationError.textContent = '';
            }

            const locationUrl = new URL(LOCATION_URLS.locations, window.location.origin);
            locationUrl.searchParams.set('tehsil_id', talukaId);

            const response = await fetchJson(locationUrl.toString(), 'Location');
            const locations = extractArray(response, ['locations', 'villages', 'results', 'items', 'data']);

            clearSelect(villageField, 'Select Village');

            if (!locations.length) throw new Error('No villages/locations found.');

            let selectedFound = false;

            locations.forEach(function (location) {
                const id = getId(location, 'location');
                const name = getName(location, 'location');
                if (!name) return;

                const option = document.createElement('option');
                option.value = name;
                option.textContent = name;
                option.dataset.locationId = id || '';

                const pin = location.pin_code ?? location.pincode ?? location.pinCode ?? location.pin ?? location.postal_code ?? '';
                option.dataset.pincode = pin;

                if (normalize(name) === normalize(selectedVillage)) {
                    option.selected = true;
                    selectedFound = true;
                    if (pin) pincodeField.value = pin;
                }

                villageField.appendChild(option);
            });

            if (selectedVillage && !selectedFound) {
                addOption(villageField, selectedVillage, selectedVillage, true);
                pincodeField.value = currentPincode || '';
            }

            if (selectedVillage && !pincodeField.value && currentPincode) {
                pincodeField.value = currentPincode;
            }

            villageField.disabled = false;
        } catch (error) {
            clearSelect(villageField, 'Unable to load locations');
            villageField.disabled = true;
            if (locationError) {
                locationError.textContent = error.message || 'Unable to load city / village locations.';
                locationError.classList.remove('d-none');
            }
        } finally {
            if (locationLoading) locationLoading.classList.add('d-none');
        }
    }

    if (districtField) {
        districtField.addEventListener('change', function () {
            const districtId = this.value;
            saveDistrictName();
            clearSelect(talukaField, 'Select district first');
            clearSelect(villageField, 'Select Taluka first');
            talukaField.disabled = true;
            villageField.disabled = true;

            if (!districtId) {
                if (districtHidden) districtHidden.value = '';
                if (talukaHidden) talukaHidden.value = '';
                if (pincodeField) pincodeField.value = '';
                return;
            }
            loadTalukas(districtId, '');
        });
    }

    if (talukaField) {
        talukaField.addEventListener('change', function () {
            const talukaId = this.value;
            saveTalukaName();
            clearSelect(villageField, 'Select Taluka first');
            villageField.disabled = true;

            if (!talukaId) {
                if (talukaHidden) talukaHidden.value = '';
                if (pincodeField) pincodeField.value = '';
                return;
            }
            loadLocations(talukaId, '');
        });
    }

    if (villageField) {
        villageField.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption) {
                pincodeField.value = '';
                return;
            }
            pincodeField.value = selectedOption.dataset.pincode || '';
        });
    }

    if (stateField) {
        stateField.addEventListener('change', function () {
            saveStateName();
            const stateId = this.value;
            if (!stateId) return;
            loadDistricts();
        });
    }

    if (stateField && districtField && talukaField && villageField) {
        saveStateName();
        loadDistricts();
    }

    /* =========================================================
       FORM VALIDATION
    ========================================================== */
    const studentForm = document.getElementById('studentForm');

    if (studentForm) {
        studentForm.addEventListener('submit', function (event) {
            let valid = true;

            if (aadhar && aadhar.value.trim() !== '' && !/^\d{12}$/.test(aadhar.value.trim())) {
                if (aadharError) aadharError.textContent = 'Aadhaar number must contain exactly 12 digits.';
                valid = false;
            }

            if (phone && phone.value.trim() !== '' && !/^[6-9]\d{9}$/.test(phone.value.trim())) {
                if (phoneError) phoneError.textContent = 'Enter a valid 10 digit Indian mobile number.';
                valid = false;
            }

            document.querySelectorAll('.parent-phone').forEach(function (input) {
                if (input.value.trim() !== '' && !/^[6-9]\d{9}$/.test(input.value.trim())) {
                    valid = false;
                }
            });

            saveStateName();
            saveDistrictName();
            saveTalukaName();

            if (!valid) {
                event.preventDefault();
                const basicButton = document.querySelector('[data-tab="basic"]');
                if (basicButton) basicButton.click();
            }
        });
    }
});
</script>

@endsection